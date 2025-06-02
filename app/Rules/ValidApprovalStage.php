<?php

namespace App\Rules;

use Closure;
use App\Models\Expense;
use App\Models\Approval;
use App\Models\ApprovalStage;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidApprovalStage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    protected $expenseId;

    public function __construct($expenseId)
    {
        $this->expenseId = $expenseId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $expense = Expense::find($this->expenseId);

        if (!$expense) {
            $fail('Expense not found.');
            return;
        }

        $stages = ApprovalStage::orderBy('id')->pluck('approver_id')->toArray();
        $currentIndex = array_search($value, $stages);

        $alreadyApproved = Approval::where('expense_id', $this->expenseId)
            ->where('approver_id', $value)
            ->exists();

        if ($alreadyApproved) {
            $fail('Approver has already approved this expense.');
            return;
        }

        if ($currentIndex !== false && $currentIndex > 0) {
            $previousApprovers = array_slice($stages, 0, $currentIndex);
            $approvedApprovers = Approval::where('expense_id', $this->expenseId)
                ->whereIn('approver_id', $previousApprovers)
                ->pluck('approver_id')
                ->toArray();

            if (count($approvedApprovers) < count($previousApprovers)) {
                $fail('Must not precede the approval sequence.');
            }
        }
    }
}
