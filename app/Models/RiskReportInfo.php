<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskReportInfo extends Model
{
    public $table = 'risk_report_info';
    public $timestamps = true;
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT];
}
