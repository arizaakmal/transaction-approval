<?php

namespace App\Services;

use App\Models\Approver;

class ApproverService
{
    public function store(array $data): Approver
    {
        return Approver::create([
            'name' => $data['name'],
        ]);
    }
}
