<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Campaign2022 extends Model
{
    public $timestamps = true;
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];

    public function scopeRankingDesc($query)
    {
        return $query->orderBy('votes', 'desc');
    }

    public function scopePagination($query, $skip, $take)
    {
        return $query->skip($skip)->take($take);
    }

    public function scopeKeyword($query, $keyword)
    {
        return $query->where('nick_name', 'like', "%{$keyword}%");
    }

    public function scopeGetColumns($query)
    {
        return $query->select([
            'id', 'user_id', 'nick_name', 'votes', DB::raw("CONCAT('upload/campaign2022/',file_name) AS file_name")
        ]);
    }
}
