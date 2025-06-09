<?php

namespace Database\Seeders;

use App\Models\ApprovalStage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class ApprovalStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApprovalStage::insert([
            ['id' => 1, 'approver_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'approver_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'approver_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
