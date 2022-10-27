<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowReport extends Model
{
    protected $table = 'borrow_report';
	protected $primaryKey = 'id';
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
