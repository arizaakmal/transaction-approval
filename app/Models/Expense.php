<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    protected $fillable = [
        'amount',
        'status_id',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}
