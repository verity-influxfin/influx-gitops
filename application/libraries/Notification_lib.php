<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/user_notification_model');
    }
	
	public function certification($user_id,$investor,$name,$status){
		if($status==1){
			$title = "[認證成功] 您的".$name."已通過";
			$content = "您好！
						您的".$name."已通過。";
		}
		
		if($status==2){
			$title = "[認證失敗] 您的".$name."未通過";
			$content = "您好！
						您的".$name."未通過，請重新認證。";
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}
	

	
}
