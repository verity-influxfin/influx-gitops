<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLoanShare extends Model
{
    public $timestamps = true;
    protected $table = 'work_loan_share';
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
