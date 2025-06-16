<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Expense::insert([
            ['id' => 1, 'amount' => 100000, 'status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'amount' => 200000, 'status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'amount' => 150000, 'status_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
