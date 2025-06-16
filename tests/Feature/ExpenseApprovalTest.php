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

    private function createApproversAndStages()
    {
        $ana = $this->postJson('/api/approvers', ['name' => 'Ana'])->json();
        $ani = $this->postJson('/api/approvers', ['name' => 'Ani'])->json();
        $ina = $this->postJson('/api/approvers', ['name' => 'Ina'])->json();

        $this->postJson('/api/approval-stages', ['approver_id' => $ana['id']]);
        $this->postJson('/api/approval-stages', ['approver_id' => $ani['id']]);
        $this->postJson('/api/approval-stages', ['approver_id' => $ina['id']]);

        return compact('ana', 'ani', 'ina');
    }

    public function test_expense_approval_flow()
    {
        // Buat Approver dan Approval Stage
        extract($this->createApproversAndStages());

        // Buat 4 pengeluaran
        $expenses = [];
        for ($i = 0; $i < 4; $i++) {
            $response = $this->postJson('/api/expense', ['amount' => 1000 * ($i + 1)]);
            $response->assertStatus(201);
            $expenses[] = $response->json();
        }

        // Pengeluaran 1 → semua approver approve
        $this->patchJson("/api/expense/{$expenses[0]['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);
        $this->patchJson("/api/expense/{$expenses[0]['id']}/approve", ['approver_id' => $ani['id']])->assertStatus(200);
        $this->patchJson("/api/expense/{$expenses[0]['id']}/approve", ['approver_id' => $ina['id']])->assertStatus(200);

        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[0]['id'],
            'status_id' => 2,
        ]);


        // Pengeluaran 2 → 2 approver approve
        $this->patchJson("/api/expense/{$expenses[1]['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);
        $this->patchJson("/api/expense/{$expenses[1]['id']}/approve", ['approver_id' => $ani['id']])->assertStatus(200);


        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[1]['id'],
            'status_id' => 1,
        ]);

        // Pengeluaran 3 → 1 approver approve
        $this->patchJson("/api/expense/{$expenses[2]['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);


        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[2]['id'],
            'status_id' => 1,
        ]);

        // Pengeluaran 4 → tidak ada approval
        $this->assertDatabaseHas('expenses', [
            'id' => $expenses[3]['id'],
            'status_id' => 1,
        ]);
    }

    public function test_approver_cannot_approve_twice()
    {
        // Buat Approver dan Approval Stage
        extract($this->createApproversAndStages());

        // Buat 1 pengeluaran
        $expense = $this->postJson('/api/expense', ['amount' => 1000])->json();

        // Approve pertama kali
        $this->patchJson("/api/expense/{$expense['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(200);

        // Coba approve lagi, harus gagal
        $this->patchJson("/api/expense/{$expense['id']}/approve", ['approver_id' => $ana['id']])->assertStatus(422);
    }

    public function test_approval_sequence_must_be_followed()
    {
        // Buat Approver dan Approval Stage
        extract($this->createApproversAndStages());

        // Buat 1 pengeluaran
        $expense = $this->postJson('/api/expense', ['amount' => 1000])->json();

        // Approve oleh Ani (bukan Ana), harus gagal
        $this->patchJson("/api/expense/{$expense['id']}/approve", ['approver_id' => $ani['id']])->assertStatus(422);
    }

    public function test_invalid_approver_cannot_approve()
    {
        // Buat Approver dan Approval Stage
        extract($this->createApproversAndStages());

        // Buat 1 pengeluaran
        $expense = $this->postJson('/api/expense', ['amount' => 1000])->json();

        // Coba approve dengan approver yang tidak ada, harus gagal
        $this->patchJson("/api/expense/{$expense['id']}/approve", ['approver_id' => 999])->assertStatus(422);
    }
}
