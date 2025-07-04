<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $fillable = [
        'name',
    ];
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}
