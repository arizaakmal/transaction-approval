<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approver extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
    public function approvalStages()
    {
        return $this->hasMany(ApprovalStage::class);
    }
}
