<?php

namespace Database\Seeders;

use App\Models\Approver;
use App\Models\Expense;
use App\Models\Status;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatusSeeder::class,
            ApproverSeeder::class,
            ApprovalStageSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
