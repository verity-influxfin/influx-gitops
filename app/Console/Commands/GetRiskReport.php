<?php

namespace App\Console\Commands;

use App\Models\RiskReportInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetRiskReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riskReport:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取得風險報告書數據';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = (new \DateTime())->setTimezone(new \DateTimeZone('+8'));
        $date_interval = new \DateInterval('P1M');
        $last_month = $now->sub($date_interval)->getTimestamp();
        $year = date('Y', $last_month);
        $month = date('m', $last_month);

        // 取得主站「風險指標」數據
        $exist = RiskReportInfo::where('year', date('Y', $last_month))
            ->where('month', date('m', $last_month))
            ->exists();

        if ($exist === FALSE) {
            $res = Http::get(env('API_URL') . 'website/each_month_risk_report', [
                'year' => $year,
                'month' => $month
            ])->json();

            if ($res['result'] != 'SUCCESS' ||
                empty($res['data']['year']) || empty($res['data']['month']) || $res['data']['year'] != $year || $res['data']['month'] != $month) {
                return Command::FAILURE;
            }

            $param = [
                'year' => $year,
                'month' => $month,
                'yearly_rate_of_return' => empty($res['data']['yearly_rate_of_return']) ? 0 : $this->get_double_number($res['data']['yearly_rate_of_return']),
                'this_month_apply' => '[]',
                'total_apply' => '[]',
                'delay_rate' => '[]'

            ];

            if (!empty($res['data']['this_month_apply']) && is_array($res['data']['this_month_apply'])) {
                $detail = $this->get_this_month_apply_detail($res['data']['this_month_apply']);
                $param['this_month_apply'] = json_encode($detail);
            }

            if (!empty($res['data']['total_apply']) && is_array($res['data']['total_apply'])) {
                $detail = $this->get_total_apply_detail($res['data']['total_apply']);
                $param['total_apply'] = json_encode($detail);
            }

            if (!empty($res['data']['delay_rate']) && is_array($res['data']['delay_rate'])) {
                $detail = $this->get_delay_rate_detail($res['data']['delay_rate']);
                $param['delay_rate'] = json_encode($detail);
            }

            RiskReportInfo::create($param);
        }

        return Command::SUCCESS;
    }

    private function get_this_month_apply_detail($detail)
    {
        $result = [];

        !isset($detail['all']) ?: $result['all'] = (int)$detail['all'];
        !isset($detail['amount']) ?: $result['amount'] = (int)$detail['amount'];
        !isset($detail['student']) ?: $result['student'] = (int)$detail['student'];
        !isset($detail['work']) ?: $result['work'] = (int)$detail['work'];
        !isset($detail['delay_users_count']) ?: $result['delay_users_count'] = (int)$detail['delay_users_count'];
        !isset($detail['delay_loans_count']) ?: $result['delay_loans_count'] = (int)$detail['delay_loans_count'];

        return $result;
    }


    private function get_total_apply_detail($detail)
    {
        $result = [];

        !isset($detail['success']) ?: $result['success'] = (int)$detail['success'];
        !isset($detail['amount']) ?: $result['amount'] = (int)$detail['amount'];
        !isset($detail['count']) ?: $result['count'] = (int)$detail['count'];
        !isset($detail['avg_invest']) ?: $result['avg_invest'] = $this->get_double_number($detail['avg_invest']);
        !isset($detail['avg_invest_student']) ?: $result['avg_invest_student'] = $this->get_double_number($detail['avg_invest_student']);
        !isset($detail['avg_invest_work']) ?: $result['avg_invest_work'] = $this->get_double_number($detail['avg_invest_work']);
        !isset($detail['delay_return_amount']) ?: $result['delay_return_amount'] = (int)$detail['delay_return_amount'];

        return $result;
    }


    private function get_delay_rate_detail($detail)
    {
        $result = [];

        foreach ($detail as $key => $value) {
            $result[$key] = $this->get_double_number($value);
        }

        return $result;
    }

    private function get_double_number($number)
    {
        return round($number * 100) / 100;
    }
}

