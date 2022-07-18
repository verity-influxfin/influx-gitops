<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusAmbassador2022 extends Model
{
    public $table = 'campus_ambassador_2022';
    public $timestamps = true;
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
