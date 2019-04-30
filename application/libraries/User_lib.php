<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_lib {

	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_userlogin_model');
    }

    //登入失敗鎖定帳號
    public function auto_block_user($account,$investor,$status,$user_id,$device_id)
    {
        $temp_lock = '000';
        $system_lock = '0000000000';
        $check_log = '';
        $check_logs = $this->CI->log_userlogin_model->order_by('created_at', 'desc')->limit(10)->get_many_by(array(
                'account'	  => $account,
                'investor'	  => $investor,
                'user_id'	  => $user_id,
                'status <'	  => 2,
                'created_at >'=> strtotime('-1 minutes')
        ));
        foreach ($check_logs as $field) {
            $check_log .= $field->status;
        }
        if (substr($check_log, 0, 3) == $temp_lock) {
            if ($check_log != $system_lock) {
                $block_status = 3;
            } else {
                $block_status = 2;
            }
            $this->CI->user_model->update($user_id, array("block_status" => $block_status));
            $this->CI->agent->device_id=$device_id;
            $log_insert = $this->CI->log_userlogin_model->insert(array(
                'account'	=> $account,
                'investor'	=> $investor,
                'user_id'	=> $user_id,
                'status'	=> $block_status
            ));
        }
    }


    public function script_unlock_block_user(){
        $count 		= 0;
        $block_user = $this->CI->user_model->get_many_by(array( "block_status" => 2 ));
        if($block_user && !empty($block_user)) {
            foreach ($block_user as $key => $value) {
                $last_logs = $this->CI->log_userlogin_model->limit(1)->get_many_by(
                    array('user_id' => $value->id)
                );
                $success = $this->CI->user_model->update($value->id, ["block_status" => 0]);
                if ($success) {
                    $count++;
                }
            }
        }
        return $count;
	}
}
