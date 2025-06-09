<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Status;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Status::insert([
            ['id' => 1, 'name' => 'menunggu persetujuan'],
            ['id' => 2, 'name' => 'disetujui'],
        ]);
    }

    public function test_expense_approval_flow()
    {
        // 1. Buat 3 approvers
        $ana = $this->postJson('/api/approvers', ['name' => 'Ana'])->json();
        $ani = $this->postJson('/api/approvers', ['name' => 'Ani'])->json();
        $ina = $this->postJson('/api/approvers', ['name' => 'Ina'])->json();

        // 2. Buat 3 approval stages (urutannya penting)
        $this->postJson('/api/approval-stages', ['approver_id' => $ana['id']]);
        $this->postJson('/api/approval-stages', ['approver_id' => $ani['id']]);
        $this->postJson('/api/approval-stages', ['approver_id' => $ina['id']]);

        // 3. Buat 4 pengeluaran
        $expenses = [];
        for ($i = 0; $i < 4; $i++) {
            $response = $this->postJson('/api/expense', ['amount' => 1000 * ($i + 1)]);
            $response->assertStatus(201);
            $expenses[] = $response->json();
        }

        // 4. Pengeluaran 1 → semua approver approve
        $this->patchJson("/api/expense/{$expenses[0]['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);
        $this->patchJson("/api/expense/{$expenses[0]['id']}/approve", ['approver_id' => $ani['id']])->assertStatus(200);
        $this->patchJson("/api/expense/{$expenses[0]['id']}/approve", ['approver_id' => $ina['id']])->assertStatus(200);

        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[0]['id'],
            'status_id' => 2,
        ]);


        // 5. Pengeluaran 2 → 2 approver approve
        $this->patchJson("/api/expense/{$expenses[1]['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);
        $this->patchJson("/api/expense/{$expenses[1]['id']}/approve", ['approver_id' => $ani['id']])->assertStatus(200);


        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[1]['id'],
            'status_id' => 1,
        ]);

        // 6. Pengeluaran 3 → 1 approver approve
        $this->patchJson("/api/expense/{$expenses[2]['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);


        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[2]['id'],
            'status_id' => 1,
        ]);

        // 7. Pengeluaran 4 → tidak ada approval
        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[3]['id'],
            'status_id' => 1,
        ]);
    }
}
