<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_lib {

	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_userlogin_model');
    }

    //登入失敗鎖定帳號
    public function auto_block_user($account,$investor,$user_id,$device_id)
    {
        $temp_lock = '000';
        $system_lock = '0000000000';
        $check_log = '';
        $check_logs = $this->CI->log_userlogin_model->order_by('created_at', 'desc')->limit(10)->get_many_by(array(
                'user_id'	  => $user_id,
                'status <'	  => 2,
                'created_at >'=> strtotime('-30 minutes')
        ));
        foreach ($check_logs as $field) {
            $check_log .= $field->status;
        }


        if (substr($check_log, 0, 3) == $temp_lock) {
            if ($check_log != $system_lock) {
                $block_status = 2;
            } else {
                $block_status = 3;
            }
            $this->CI->user_model->update($user_id, array("block_status" => $block_status));
            $this->CI->agent->device_id=$device_id;
            $this->CI->log_userlogin_model->insert(array(
                'account'	=> $account,
                'investor'	=> $investor,
                'user_id'	=> $user_id,
                'status'	=> $block_status
            ));
            $remind_count = 0;
        }
        else{
            $remind_count = substr($check_log, 0, 2) == '00'?1:(substr($check_log, 0, 1) == '0'?2:3);
        }

        return $remind_count;
    }

    public function unblock_user($user_id)
    {
        $this->CI->load->model('log/log_userlogin_model');
        $check_logs = $this->CI->log_userlogin_model->order_by('created_at', 'desc')->limit(10)->get_many_by(array(
            'user_id' => $user_id,
            'status <' => 2,
            'created_at >' => strtotime('-30 minutes')
        ));
        if (!$check_logs) {
            $this->CI->user_model->update_by(array(
                'id' => $user_id,
                'block_status' => 2,
            ), array(
                'block_status' => 0
            ));
            return true;
        }
    }

}
