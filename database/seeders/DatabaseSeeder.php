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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            StatusSeeder::class,
            ApproverSeeder::class,
            ApprovalStageSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
