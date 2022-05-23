<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign2022_add extends Model
{
    public $timestamps = true;
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
