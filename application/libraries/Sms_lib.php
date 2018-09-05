<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Aws\Sns\SnsClient;

class Sms_lib {
	
	private $client;
	
	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('user/sms_verify_model');
		$this->CI->load->model('log/log_sns_model');
    }
	
	public function send_register($phone=""){
		if(!empty($phone)){
			$code	 = get_rand_token();
			$content = "手機ATM，會員註冊簡訊，您的驗證碼為".$code."，請注意有效時間為30分鐘以內";
			$param = array(
				"type" 			=> 'register',
				"phone"			=> $phone,
				"code"			=> $code,
				"expire_time"	=> time()+SMS_EXPIRE_TIME,
			);
			$rs = $this->CI->sms_verify_model->insert($param);
			if($rs){
				return $this->send('register',0,$phone,$content);
			}
		}
		return false;
	}

	public function send_verify_code($user_id,$phone=""){
		if(!empty($phone)){
			$code	 = get_rand_token();
			$content = "手機ATM，認證簡訊，您的驗證碼為".$code."，請注意有效時間為30分鐘以內";
			$param = array(
				"type" 			=> 'verify',
				"phone"			=> $phone,
				"code"			=> $code,
				"expire_time"	=> time()+SMS_EXPIRE_TIME,
			);
			$rs = $this->CI->sms_verify_model->insert($param);
			if($rs){
				return $this->send('register',$user_id,$phone,$content);
			}
		}
		return false;
	}

	public function lending_success($user_id,$investor,$target_no,$amount=0,$bankaccount=""){
		if(!empty($user_id)){
			$user_info 	= $this->CI->user_model->get($user_id);
			if($user_info){
				$phone 		= $user_info->phone;
				if($investor==1){
					$content 	= "親愛的用戶：您好！您申請的標的 $target_no ，核可金額 $amount 元，已成功放款。";
				}else{
					$bankaccount = substr($bankaccount, -4, 4);
					$content = "【手機Atm用戶通知】您的借款 $target_no ，借款金額為 $amount 元已發放至您的綁定金融卡帳戶尾號 $bankaccount 內! 立即登入手機Atm查看最新資訊 https://borrow.influxfin.com/ ，祝您一切順心。";
				}
				return $this->send('lending_success',$user_id,$phone,$content);
			}
		}
		return false;
	}
	
	public function notice_normal_target($user_id,$amount=0,$target_no="",$date=""){
		if(!empty($user_id)){
			$user_info 	= $this->CI->user_model->get($user_id);
			if($user_info){
				$phone 		= $user_info->phone;
				$content 	= "親愛的用戶，您好！
							您的借款 $target_no ，本期應還本息合計為 $amount 元，您的應還款日為 $date ，請在當天中午12點前將款項主動匯入您的專屬還款帳號內，專屬帳號可在我的手機ATM服務內點擊我的→我的還款查看，如已還款，請忽略本訊息。
							敬告用戶，本公司不會以短信、電話或任何形式，告知您其他非服務內揭露的專屬還款帳號，若有收到類似通知，謹防詐騙，或致電我司客服電話02-25079990舉報，感謝您的配合。";
				return $this->send('target_notice',$user_id,$phone,$content);
			}
		}				
		return false;
	}
	
	public function verify_code($phone="",$code=""){
		if(!empty($phone) && !empty($code)){
			$param = array(
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
	
	public function get_code($phone=""){
		if(!empty($phone)){
			$rs = $this->CI->sms_verify_model->order_by("expire_time","desc")->get_by(array("phone"=>$phone));
			if($rs){
				$data = array(
					"phone"			=> $rs->phone,
					"code"			=> $rs->code,
					"expire_time"	=> $rs->expire_time,
					"status"		=> $rs->status,
					"created_at"	=> $rs->created_at,
				);
				return $data;
			}
		}
		return false;
	}

	private function send($type,$user_id,$phone,$content){
		
		$data = array(
			"UID"	=> EVER8D_UID,
			"PWD"	=> EVER8D_PWD,
			"msg"	=> $content,
			"DEST"	=> $phone,
		);
		
		if(is_development()){
			$rs 	= 1;
			$status = 1;
		}else{
			$rs = curl_get("https://oms.every8d.com/API21/HTTP/sendSMS.ashx",$data);
			if(substr($rs,0,1) == "-"){
				$status = 0;
			} else {
				$status	= 1;
			}
		}
		
		$rs = $this->CI->log_sns_model->insert(array(
			"type" 		=> $type,
			"user_id"	=> $user_id,
			"phone"		=> $phone,
			"response"	=> $rs,
			"status" 	=> $status
		));
		return $status?true:false;
	}
	
}
