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
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['password'] = sha1($data['password']);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function get_amounts_at($date, $types = [])
    {
        $result = [
            self::TARGET_WEB_TRAFFIC => 0,
            self::TARGET_DOWNLOAD_ANDROID => 0,
            self::TARGET_DOWNLOAD_IOS => 0,
        ];

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
            return $result;
        }

        return array_column($daily_amounts, 'amounts', 'type');
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
                'updated_at' => date('Y-m-d H:i:s'),
                'type' => $type,
                'amounts' => $amounts,
            ];
            $this->db->insert('p2p_user.sale_dashboard', $insert);
        }
        else
        {
            $update = [
                'amounts' => $amounts,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->where('id', $record['id']);
            $this->db->update('p2p_user.sale_dashboard', $update);
        }
    }
}
