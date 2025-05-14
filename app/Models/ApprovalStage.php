<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStage extends Model
{
    //
    protected $fillable = [
        'approver_id',
    ];

    public function approver()
    {
        return $this->belongsTo(Approver::class);
    }

    
}
