<?php

namespace App\Http\Controllers;

use App\Models\RiskReportInfo;
use Illuminate\Support\Facades\DB;

class RiskReportController extends Controller
{
    private $year;
    private $month;
    private $compare_growth_rate_interval;

    public function __construct()
    {
        $this->compare_growth_rate_interval = 12; // 單位：月
    }

    public function get_info_by_month($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
        $result = RiskReportInfo::select(['yearly_rate_of_return', 'this_month_apply', 'total_apply', 'delay_rate'])
            ->where('year', $this->year)
            ->where('month', $this->month)
            ->first();

        if (empty($result)) {
            return $this->return_failed('尚未建立該月份指標');
        }
        $result = $result->toArray();

        $result['this_month_apply'] = json_decode($result['this_month_apply'], true);
        $result['total_apply'] = json_decode($result['total_apply'], true);

        $delay_rate = json_decode($result['delay_rate'], true);
        $result['on_time'] = $this->convert_delay_rate_to_on_time_rate($delay_rate);
        $result['growth'] = $this->get_growth_rate($result['this_month_apply']);

        unset($result['delay_rate']);
        if (isset($result['this_month_apply']['amount'])) {
            unset($result['this_month_apply']['amount']);
        }

        return $this->return_success($result);
    }

    public function get_list()
    {
        $result = RiskReportInfo::get(['year', 'month'])->sortByDesc('year')->sortByDesc('month')->toArray();
        $result = array_values($result);
        return $this->return_success($result);
    }

    private function get_growth_rate($this_month_data)
    {
        $result = [];

        $compare_data = $this->get_compare_data();
        !isset($compare_data['amount']) ?: $result['amount'] = round(($this_month_data['amount'] - $compare_data['amount']) / $compare_data['amount'] * 100);
        !isset($compare_data['student']) ?: $result['student'] = round(($this_month_data['student'] - $compare_data['student']) / $compare_data['student'] * 100);
        !isset($compare_data['work']) ?: $result['work'] = round(($this_month_data['work'] - $compare_data['work']) / $compare_data['work'] * 100);

        return $result;
    }

    private function get_compare_data()
    {
        $this_date = (new \DateTime())->setDate($this->year, $this->month, 1);
        $compare_with = $this_date->sub(new \DateInterval("P{$this->compare_growth_rate_interval}M"))->getTimestamp();

        return RiskReportInfo::select([
            DB::raw('this_month_apply->>"$.amount" AS amount'),
            DB::raw('this_month_apply->>"$.work" AS work'),
            DB::raw('this_month_apply->>"$.student" AS student'),
        ])
            ->where('year', date('Y', $compare_with))
            ->where('month', date('m', $compare_with))
            ->first();
    }

    private function convert_delay_rate_to_on_time_rate($delay_rate)
    {
        $result = [];
        $level_span = [
            ['start' => 1, 'end' => 3],
            ['start' => 4, 'end' => 6],
            ['start' => 7, 'end' => 10]
        ];
        foreach ($level_span as $level) {
            $sum = 0;
            for ($i = $level['start']; $i <= $level['end']; $i++) {
                if (!isset($delay_rate['level' . $i])) {
                    continue;
                }
                $sum += $delay_rate['level' . $i];
            }
            $result['level' . $level['start']] = round(100 - $sum / ($level['end'] - $level['start'] + 1), 2);
        }

        return $result;
    }
}
