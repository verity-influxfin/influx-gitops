<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign2022_vote extends Model
{
    public $timestamps = true;
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
