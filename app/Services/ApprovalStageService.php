<?php

namespace App\Services;

use App\Models\ApprovalStage;

class ApprovalStageService
{
    public function store(array $data): ApprovalStage
    {
        return ApprovalStage::create([
            'approver_id' => $data['approver_id'],
        ]);
    }

    public function update(ApprovalStage $approvalStage, array $data)
    {
        $approvalStage->update([
            'approver_id' => $data['approver_id'] ?? $approvalStage->approver_id,
        ]);

        return $approvalStage;
    }
}
