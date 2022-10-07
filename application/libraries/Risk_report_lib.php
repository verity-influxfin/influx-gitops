<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 風險報告書上的數據，由以下幾項資訊組成：
 * 1. 每個月的風險指標
 * 2. 開台至今的風險指標
 * 3. 各信評逾期率
 */
class Risk_report_lib
{
    /**
     * @var CI_Controller|object
     */
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('transaction/transaction_model');
    }

    /**
     * 取得每個月的風險指標
     * @param $year : 年份
     * @param $month : 月份
     * @return array
     */
    public function get_info_by_month($year, $month)
    {
        return $this->CI->transaction_model->get_risk_report_info_by_month($year, $month);
    }

    /**
     * 取得開台至今的風險指標
     * @param $year : 年份
     * @param $month : 月份
     * @return array
     */
    public function get_info_from_beginning($year, $month)
    {
        return $this->CI->transaction_model->get_risk_report_info_from_beginning($year, $month);
    }

    /**
     * 取得開台至今的各信評等級的逾期率
     * @param $year
     * @param $month
     * @return array
     */
    public function get_delay_rate_by_level($year, $month)
    {
        return $this->CI->transaction_model->get_delay_rate_by_level($year, $month);
    }

    /**
     * 取得風險報告書各個指標的預設值
     * @return array
     */
    public function get_initial_data(): array
    {
        return [
            'yearly_rate_of_return' => NULL, // 累計至本月之平均年化報酬率
            'this_month_apply' => [
                'all' => NULL, // 本月申貸案件數
                'amount' => NULL, // 本月申貸金額
                'student' => NULL, // 本月學生貸申貸案件
                'work' => NULL, // 本月上班族貸申貸案件數
                'delay_users_count' => NULL, // 當月逾期人數
                'delay_loans_count' => NULL, // 當月逾期筆數
            ],
            'total_apply' => [
                'success' => NULL, // 媒合成功件數
                'amount' => NULL, // 累積金額
                'count' => NULL, // 累積筆數
                'avg_invest' => NULL, // 平均每筆投資金額
                'avg_invest_student' => NULL, // 學生貸媒合成功均額
                'avg_invest_work' => NULL, // 上班族貸媒合成功均額
                'delay_return_amount' => NULL, // 已累計回收逾期金額
            ],
            'delay_rate' => [ // 各信評等級的逾期率(%)
                'level1' => 0,
                'level2' => 0,
                'level3' => 0,
                'level4' => 0,
                'level5' => 0,
                'level6' => 0,
                'level7' => 0,
                'level8' => 0,
                'level9' => 0,
                'level10' => 0,
                'level11' => 0,
                'level12' => 0,
                'level13' => 0,
            ],
        ];
    }
}