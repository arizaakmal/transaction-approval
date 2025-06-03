<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'amount' => $this->amount,
            'status' => [
                'id'   => $this->status_id,
                'name' => optional($this->status)->name, 
            ],
            'approval' => $this->approvals->map(function ($approval) {
                return [
                    'id'       => $approval->id,
                    'approver' => [
                        'id'   => $approval->approver_id,
                        'name' => optional($approval->approver)->name, 
                    ],
                    'status' => [
                        'id'   => $approval->status_id,
                        'name' => optional($approval->status)->name, 
                    ],
                ];
            }),
        ];
    }
}
