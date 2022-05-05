<?php

class Sale_dashboard_model extends MY_Model
{
    const TARGET_WEB_TRAFFIC = 0;
    const TARGET_DOWNLOAD_ANDROID = 1;
    const TARGET_DOWNLOAD_IOS = 2;

    const TARGET_USER_REGISTER = 3;
    const TARGET_LOAN_SALARY_MAN = 4;
    const TARGET_LOAN_STUDENT = 5;
    const TARGET_LOAN_SMART_STUDENT = 6;
    const TARGET_DEAL_SALARY_MAN = 7;
    const TARGET_DEAL_STUDENT = 8;
    const TARGET_DEAL_SMART_STUDENT = 9;
    const TARGET_LOAN_CREDIT_INSURANCE = 10;
    const TARGET_LOAN_SME = 11;
    const TARGET_DEAL_CREDIT_INSURANCE = 12;
    const TARGET_DEAL_SME = 13;

    const TARGET_LOAN_SK_MILLION_SMEG = 14; // 微企貸，沒有匯出在報表中
    const TARGET_DEAL_SK_MILLION_SMEG = 15;

    public $_table = 'sale_dashboard';
    public $before_create = ['before_data_u'];
    public $before_update = ['before_data_u'];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    private function _get_consts()
    {
        // WARNING:
        // 這邊的常數並沒有完全 MATCH Sale_goals_model 的常數
        return [
            self::TARGET_WEB_TRAFFIC => 0, // 官網
            self::TARGET_DOWNLOAD_ANDROID => 0, // android 下載 兩個下載數要合併!!
            self::TARGET_DOWNLOAD_IOS => 0, // ios 下載 兩個下載數要合併!!
            self::TARGET_USER_REGISTER => 0,
            self::TARGET_LOAN_SALARY_MAN => 0,
            self::TARGET_LOAN_STUDENT => 0,
            self::TARGET_LOAN_SMART_STUDENT => 0,
            self::TARGET_DEAL_SALARY_MAN => 0,
            self::TARGET_DEAL_STUDENT => 0,
            self::TARGET_DEAL_SMART_STUDENT => 0,
            self::TARGET_LOAN_CREDIT_INSURANCE => 0,
            self::TARGET_LOAN_SME => 0,
            self::TARGET_DEAL_CREDIT_INSURANCE => 0,
            self::TARGET_DEAL_SME => 0,
            self::TARGET_LOAN_SK_MILLION_SMEG => 0,
            self::TARGET_DEAL_SK_MILLION_SMEG => 0,
        ];
    }

    public function get_amounts_at($date, $types = [])
    {
        $consts = $this->_get_consts();

        $this->db->select('type, amounts');
        $this->db->from('sale_dashboard');
        $this->db->where('data_at', $date->format('Y-m-d'));

        if ( ! empty($types))
        {
            $this->db->where_in('type', $types);
        }
        $daily_amounts = $this->db->get()->result_array();

        if (empty($daily_amounts))
        {
            return $consts;
        }

        // 好像從db取出來的 type 會是 String, 跟 $consts 相加後有些 key 是 string, 有些變 int, 感覺要注意一下
        $return_type = array_column($daily_amounts, 'amounts', 'type');
        return $return_type + $consts;
    }

    public function set_amounts_at($date, $type, $amounts)
    {
        $record = $this->db->get_where('p2p_user.sale_dashboard', [
            'data_at' => $date->format('Y-m-d'),
            'type' => $type,
        ])->row_array();

        if (empty($record))
        {
            $insert = [
                'data_at' => $date->format('Y-m-d'),
                'type' => $type,
                'amounts' => $amounts,
            ];
            $this->insert($insert);
        }
        else
        {
            $this->update($record['id'], [
                'amounts' => $amounts,
            ]);
        }
    }

    public function get_loan_mapping_eboard_key_to_type()
    {
        return [
            'SMART_STUDENT' => self::TARGET_LOAN_SMART_STUDENT,
            'STUDENT' => self::TARGET_LOAN_STUDENT,
            'SALARY_MAN' => self::TARGET_LOAN_SALARY_MAN,
            'SK_MILLION' => self::TARGET_LOAN_SK_MILLION_SMEG, // 微企貸沒有出現在 匯出的 excel 裡面
        ];
    }

    public function get_deal_mapping_eboard_key_to_type()
    {
        return [
            'SMART_STUDENT' => self::TARGET_DEAL_SMART_STUDENT,
            'STUDENT' => self::TARGET_DEAL_STUDENT,
            'SALARY_MAN' => self::TARGET_DEAL_SALARY_MAN,
            'SK_MILLION' => self::TARGET_DEAL_SK_MILLION_SMEG, // 微企貸沒有出現在 匯出的 excel 裡面
        ];
    }

    public function get_amounts_by_month($at_month)
    {
        $date = new DateTimeImmutable(date($at_month . '01'));

        $this->db->select('data_at, type, amounts');
        $this->db->from('sale_dashboard');
        $this->db->where('data_at >=', $date->format('Y-m-d'));
        $this->db->where('data_at <', $date->modify('+1 month')->format('Y-m-d'));
        $datas = $this->db->get()->result_array();

        return $this->_parse_monthly_data($datas);
    }

    private function _parse_monthly_data($datas)
    {
        $consts = $this->_get_consts();
        $default_value = array_fill(0, count($consts), []);

        $type_collect = array_combine(array_keys($consts), $default_value);

        array_map(function ($data) use (&$type_collect)
        {
            array_push($type_collect[$data['type']], $data);
        }, $datas);

        return $type_collect;
    }
}
