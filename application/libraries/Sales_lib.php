<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_lib
{
    /**
     * 如果需要增加新的目標：
     * 1.
     * Sale_goals_model::GOAL_NEW_ONE 設定新的常數
     * 2.
     * Sale_goals_model->type_name_mapping 設定中文名稱
     * 3.
     * $this->_parse_dashboard_key_to_goal_key() 設定資料來源
     * $datas[sale_goals_model::GOAL_NEW_ONE] = ...
     * 因為後面的數字肯定跟 sale_dashboard 的 const 對應不起來
     * 4.
     * 後台呈現跟匯出 excel 的地方要調整：
     * view:admin/sales_target_setting 決定呈現的資料排列位置
     * controller:admin/Sales@goals_export 決定匯出的資料排列
     **/

    private $at_month = ''; // '202204'
    private $days_in_month = 0; // 30
    private $total_deals = []; // 成交總數

    public function __construct($params)
    {
        $this->CI = &get_instance();
        $this->CI->load->model('user/sale_goals_model');
        $this->CI->load->model('user/sale_dashboard_model');

        $this->at_month = $params['at_month'];
        $this->days_in_month = $this->_get_days_in_month($params['at_month']);
    }

    private function _get_days_in_month($at_month)
    {
        return (int) date('t', strtotime(date($at_month . '01')));
    }

    public function calculate()
    {
        $return_data = [];
        $this->_init_total_deals();

        // 取得本月的各項目
        $goals = $this->get_goals();

        // 取得本月的各項績效資料
        $datas = $this->CI->sale_dashboard_model->get_amounts_by_month($this->at_month);

        // 從 sale_dashboard_model 取出來的格式有幾項要轉換後才能 MATCH sale_goals_model key
        $datas = $this->_parse_dashboard_key_to_goal_key($datas);

        // 整理回傳格式
        $types = $this->CI->sale_goals_model->type_name_mapping();
        foreach ($types as $key => $name)
        {
            $return_data[$key] = $this->_data_parser(
                $key, $goals, $datas[$key]);
        }

        $this->_sum_total_deals_and_put_at_head();
        $return_data['total_deals'] = $this->total_deals;

        return $return_data;
    }

    private function _init_total_deals()
    {
        $this->total_deals = array_fill(0, $this->days_in_month, 0);
    }

    private function _sum_total_deals_and_put_at_head()
    {
        $total = array_sum($this->total_deals);
        array_unshift($this->total_deals, $total);
    }

    private function _parse_dashboard_key_to_goal_key($datas)
    {
        //     dashboard     vs. goal
        // index 1+2         => index 1
        // index 4+5+6+10+11 => index 2
        $datas[sale_goals_model::GOAL_APP_DOWNLOAD] = array_merge(
            $datas[sale_dashboard_model::TARGET_DOWNLOAD_ANDROID],
            $datas[sale_dashboard_model::TARGET_DOWNLOAD_IOS]
        );
        $datas[sale_goals_model::GOAL_LOAN_TOTAL] = array_merge(
            $datas[sale_dashboard_model::TARGET_LOAN_SALARY_MAN],
            $datas[sale_dashboard_model::TARGET_LOAN_STUDENT],
            $datas[sale_dashboard_model::TARGET_LOAN_SMART_STUDENT],
            $datas[sale_dashboard_model::TARGET_LOAN_CREDIT_INSURANCE],
            $datas[sale_dashboard_model::TARGET_LOAN_SME]
        );

        return $datas;
    }

    private function _data_parser($type, $goals, $type_datas)
    {
        if (empty($goals['goal_a_month'][$type]))
        {
            return [];
        }
        $goal_per_day = $goals['goal_per_day'][$type];
        $goal_a_month = $goals['goal_a_month'][$type];
        $real_a_month = 0;

        // 回傳結構
        $goal = array_fill(0, $this->days_in_month, $goal_per_day);
        $real = array_fill(0, $this->days_in_month, 0);
        $rate = array_fill(0, $this->days_in_month, '0%');

        foreach ($type_datas as $value)
        {
            $day2key = explode('-', $value['data_at'])[2] - 1;
            $real_a_month += $value['amounts'];

            // 計算達成率 real/goal 取百分比
            $real[$day2key] += $value['amounts']; // MEMO 申貸總計這項會有多個同日資料
            $rate[$day2key] = $this->_caculate_rate($real[$day2key], $goal_per_day);

            // 檢查是否要累積成交數
            if (in_array($type, $this->_total_deal_content_type()))
            {
                $this->total_deals[$day2key] += $value['amounts'];
            }
        }

        // 將總和資料塞進第一行
        array_unshift($goal, $goal_a_month);
        array_unshift($real, $real_a_month);
        array_unshift($rate, $this->_caculate_rate($real_a_month, $goal_a_month));

        return [
            'goal' => $goal,
            'real' => $real,
            'rate' => $rate,
        ];
    }

    private function _caculate_rate($real, $goal): String
    {
        if ($real == 0 || $goal == 0)
        {
            return '0%';
        }

        return round($real / $goal * 100) . '%';
    }

    private function _total_deal_content_type()
    {
        return [
            sale_dashboard_model::TARGET_DEAL_SALARY_MAN,
            sale_dashboard_model::TARGET_DEAL_STUDENT,
            sale_dashboard_model::TARGET_DEAL_SMART_STUDENT,
            sale_dashboard_model::TARGET_DEAL_CREDIT_INSURANCE,
            sale_dashboard_model::TARGET_DEAL_SME,
        ];
    }

    public function get_goals()
    {
        $goals = $this->CI->sale_goals_model->get_goals_number_at_this_month($this->at_month);

        $goal_a_month = [];
        array_walk($goals, function ($element) use (&$goal_a_month) {
            if ( ! empty($element['status']) && $element['status'] == 1)
            {
                $goal_a_month[$element['type']] = $element['number'];
            }
        });
        $goal_per_day = array_map(
            function ($n)
            {
                return round($n / $this->days_in_month);
            },
            $goal_a_month
        );

        return [
            'goal_a_month' => $goal_a_month,
            'goal_per_day' => $goal_per_day,
        ];
    }

    public function get_goals_id()
    {
        $goals = $this->CI->sale_goals_model->get_goals_number_at_this_month($this->at_month);
        return array_column($goals, 'id', 'type');
    }

    public function get_days()
    {
        $int_month = (int) substr($this->at_month, -2);
        $month_day = [];
        $week_chinese = [];
        $week_name = ['日', '一', '二', '三', '四', '五', '六'];
        for ($i = 1; $i <= $this->days_in_month; $i++)
        {
            array_push($month_day, "{$int_month}月{$i}日");

            $week = $week_name[date('w', strtotime(
                date($this->at_month . str_pad($i, 2, '0', STR_PAD_LEFT))
            ))];
            array_push($week_chinese, $week);
        }

        return [
            'date' => $month_day,
            'week' => $week_chinese,
            'int_month' => $int_month,
        ];
    }
}
