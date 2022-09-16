<?php

namespace App\Http\Controllers;

class RiskReportController extends Controller
{
    public function get_info_by_month($year, $month)
    {
        $result = [
            'yearly_rate_of_return' => 14.02,
            'this_month_apply' => [
                'all' => 1580,
                'student' => 598,
                'work' => 982,
            ],
            'total_apply' => [
                'success' => 15829,
                'money' => 539531987,
                'count' => 95701,
                'avg_invest' => 10725,
                'avg_invest_student' => 20446,
                'avg_invest_work' => 60362,
            ],
            'total_delay' => [
                'return_money' => 17435176,
                'users_count' => 19,
                'loans_count' => 29,
            ],
            'on_time' => [
                'rate_level1' => 99.90,
                'rate_level4' => 99.44,
                'rate_level7' => 99.35,
            ],
            'growth' => [
                'money' => 2.00,
                'student' => 2.00,
                'work' => 3.50,
            ],
        ];
        return $this->return_success($result);
    }
}
