<?php

class Log_user_qrcode_model extends MY_Model
{
    public $_table 			= 'user_qrcode_log';
    public $before_create 	= ['before_data_c'];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('log', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = get_ip();
        return $data;
    }

    // 寫 QRCode 的異動紀錄
    public function insert_log($data)
    {
        $fields = ['user_qrcode_id', 'alias', 'status', 'sub_status', 'contract_id', 'settings', 'subcode_flag', 'user_id', 'handle_time'];
        $insert_param = [];

        foreach ($fields as $field)
        {
            if (isset($data[$field]))
            {
                $insert_param[$field] = $data[$field];
            }
        }

        if (empty($insert_param))
        {
            return TRUE;
        }

        $insert_param = $this->before_data_c($insert_param);
        return (bool) $this->db->insert('p2p_log.user_qrcode_log', $insert_param);
    }
}