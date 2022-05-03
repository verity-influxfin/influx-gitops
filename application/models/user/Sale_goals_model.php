<?php

class Sale_goals_model extends MY_Model
{
    const GOAL_WEB_TRAFFIC = 0;
    const GOAL_APP_DOWNLOAD = 1;
    const GOAL_LOAN_TOTAL = 2;
    const GOAL_USER_REGISTER = 3;
    const GOAL_LOAN_SALARY_MAN = 4;
    const GOAL_LOAN_STUDENT = 5;
    const GOAL_LOAN_SMART_STUDENT = 6;
    const GOAL_DEAL_SALARY_MAN = 7;
    const GOAL_DEAL_STUDENT = 8;
    const GOAL_DEAL_SMART_STUDENT = 9;
    const GOAL_LOAN_CREDIT_INSURANCE = 10;
    const GOAL_LOAN_SME = 11;
    const GOAL_DEAL_CREDIT_INSURANCE = 12;
    const GOAL_DEAL_SME = 13;

    public $_table = 'sale_goals';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function type_name_mapping()
    {
        return [
            self::GOAL_WEB_TRAFFIC => '官網流量',
            self::GOAL_USER_REGISTER => '會員註冊',
            self::GOAL_APP_DOWNLOAD => 'APP下載',
            self::GOAL_LOAN_TOTAL => '申貸總計',

            self::GOAL_LOAN_SALARY_MAN => '上班族貸申貸',
            self::GOAL_DEAL_SALARY_MAN => '上班族貸成交',

            self::GOAL_LOAN_STUDENT => '學生貸申貸',
            self::GOAL_DEAL_STUDENT => '學生貸成交',

            self::GOAL_LOAN_SMART_STUDENT => '3S名校貸申貸',
            self::GOAL_DEAL_SMART_STUDENT => '3S名校貸成交',

            self::GOAL_LOAN_CREDIT_INSURANCE => '信保專案申貸',
            self::GOAL_DEAL_CREDIT_INSURANCE => '信保專案成交',

            self::GOAL_LOAN_SME => '中小企業申貸',
            self::GOAL_DEAL_SME => '中小企業成交',
        ];
    }

    public function get_goals_number_at_this_month($at_month = '')
    {
        $at_month = empty($at_month) ? date('Ym') : $at_month;
        $goals = $this->_goals_at($at_month);

        if (empty($goals))
        {
            // 如果指定的月份首次開啟時會沒有目標，所以先複製上個月的資料
            $goals = $this->_create_new_month_goals($at_month);
        }

        return $goals;
    }

    private function _goals_at($at_month)
    {
        return $this->db
            ->select('id, type, number')
            ->get_where('p2p_user.sale_goals', [
                'at_month' => $at_month,
            ])->result_array();
    }

    private function _create_new_month_goals($at_month)
    {
        // MEMO 固定用今天的上個月目標來複製一份
        $pre_month = date('Ym', strtotime('-1 month'));
        $pre_goals = $this->_goals_at($pre_month);

        // 建立本月的目標項目
        $goals = [];
        foreach ($pre_goals as $value)
        {
            $insert_id = $this->insert([
                'at_month' => $at_month,
                'type' => $value['type'],
                'number' => $value['number'],
            ]);

            $goals[] = [
                'id' => $insert_id,
                'type' => $value['type'],
                'number' => $value['number'],
            ];
        }

        return $goals;
    }
}
