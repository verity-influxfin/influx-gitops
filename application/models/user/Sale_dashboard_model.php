<?php

class sale_dashboard_model extends MY_Model
{
    const PLATFORM_TYPE_GOOGLE_ANALYTICS = 0;
    const PLATFORM_TYPE_ANDROID = 1;
    const PLATFORM_TYPE_IOS = 2;

    public $_table = 'sale_dashboard';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');

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

    public function get_amounts_at($date)
    {
        $result = [
            self::PLATFORM_TYPE_GOOGLE_ANALYTICS => 0,
            self::PLATFORM_TYPE_ANDROID => 0,
            self::PLATFORM_TYPE_IOS => 0,
        ];

        $daily_amounts = $this->db->get_where('p2p_user.sale_dashboard', [
            'data_at' => $date->format('Y-m-d'),
        ])->result_array();

        if (empty($daily_amounts))
        {
            return $result;
        }

        return array_column($daily_amounts, 'amounts', 'platform_type');
    }

    public function set_amounts_at($date, $platform_type, $amounts)
    {
        $record = $this->db->get_where('p2p_user.sale_dashboard', [
            'data_at' => $date->format('Y-m-d'),
            'platform_type' => $platform_type,
        ])->row_array();

        if (empty($record))
        {
            $insert = [
                'data_at' => $date->format('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s'),
                'platform_type' => $platform_type,
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
