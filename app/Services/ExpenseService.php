<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Approval;
use App\Models\ApprovalStage;

class ExpenseService
{
    public function store(array $data): Expense
    {
        return Expense::create([
            'amount' => $data['amount'],
            'status_id' => 1,
        ]);
    }

    public function update(Expense $expense, array $data): Expense
    {
        Approval::create([
            'expense_id'  => $expense->id,
            'approver_id' => $data['approver_id'],
            'status_id'   => 2,
        ]);

        $requiredApprovers = ApprovalStage::orderBy('id')->pluck('approver_id')->toArray();
        $approvedApprovers = $expense->approvals()->pluck('approver_id')->toArray();

        $allApproved = empty(array_diff($requiredApprovers, $approvedApprovers));

        if ($allApproved) {
            $expense->update([
                'status_id' => 2,
            ]);
        }

        return $expense;
    }
}
