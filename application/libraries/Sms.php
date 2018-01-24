<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sms{
	
	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('user/sms_verify_model');
    }

	
	public function send_register($phone=""){
		if(!empty($phone)){
			$code	 = rand(1, 9).rand(0, 9).rand(0, 9).rand(0, 9);
			$content = "P2P認證簡訊，您的驗證碼為".$code."，請注意有效時間為30分鐘以內";
			$param = array(
				"type" 			=> SMS_TYPE_REGISTER,
				"phone"			=> $phone,
				"code"			=> $code,
				"expire_time"	=> time()+SMS_EXPIRE_TIME,
			);
			$this->CI->sms_verify_model->insert($param);
			return true;
		}
		return false;
	}

	public function verify_register($phone="",$code=""){
		if(!empty($phone) && !empty($code)){
			$param = array(
				"type" 			=> SMS_TYPE_REGISTER,
				"phone"			=> $phone,
				"status"		=> 0,
			);
			$rs = $this->CI->sms_verify_model->order_by("expire_time","desc")->get_by($param);
			if($rs){
				if($rs->code == $code && $rs->expire_time>=time()){
					$this->CI->sms_verify_model->update($rs->id,array("status"=>1));
					return true;
				}
			}
		}
		return false;
	}
	
}
