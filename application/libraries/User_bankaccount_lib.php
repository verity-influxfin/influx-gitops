<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_bankaccount_lib
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_userbankaccount_model');
    }

    /**
     * 寫入金融驗證 Log
     * @param $user_bankaccount_id : user_bankaccount.id
     * @param $update_param : 欲更新 Table user_bankaccount 的欄位值
     * @return false
     */
    public function insert_change_log($user_bankaccount_id, $update_param)
    {
        if (empty($user_bankaccount_id))
        {
            return FALSE;
        }

        $param = [
            'user_bankaccount_id' => $user_bankaccount_id,
        ];
        $fields = ['user_id', 'status', 'verify', 'verify_at', 'sys_check'];
        foreach ($fields as $field)
        {
            if (isset($update_param[$field]))
            {
                $param[$field] = $update_param[$field];
            }
        }
        return $this->CI->log_userbankaccount_model->insert($param);
    }
}