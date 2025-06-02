<?php

namespace Database\Seeders;

use App\Models\Approver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApproverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Approver::insert([
            ['id' => 1, 'name' => 'John Doe', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'John Smith', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
