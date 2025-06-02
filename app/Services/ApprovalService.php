<?php

namespace App\Services;

use App\Models\Approval;

class ApprovalService
{
    public function store(array $data): Approval
    {
        return Approval::create([
            'expense_id' => $data['expense_id'],
            'approver_id' => $data['approver_id'],
            'status_id' => 2,
        ]);
    }
}
