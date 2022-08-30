<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessLoanContact extends Model
{
    public $timestamps = true;
    protected $table = 'business_loan_contact';
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
