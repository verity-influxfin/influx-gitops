<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLoanContact extends Model
{
    public $timestamps = true;
    protected $table = 'work_loan_contact';
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
