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
			$content = "普匯inFlux，會員註冊簡訊，您的驗證碼為".$code."，請注意有效時間為30分鐘以內";
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
			$content = "普匯inFlux，認證簡訊，您的驗證碼為".$code."，請注意有效時間為30分鐘以內";
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
					$content = "【普匯】借款" . $target_no . "已發放至您的綁定金融帳戶,每月10日還款,逾期需繳納全額本金,違約金及延滯息,請準時匯款。";
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
                $content = '【普匯通知】:借款'.$target_no.'本期應還本息為'.$amount.'元，還款日為本月10號，請於當天凌晨前將款項匯入專屬還款帳號。';
				return $this->send('target_notice',$user_id,$phone,$content);
			}
		}				
		return false;
	}
	
	public function notice_delay_target($user_id,$amount=0,$target_no=""){
		if(!empty($user_id)){
			$user_info 	= $this->CI->user_model->get($user_id);
			if($user_info){
				$phone 		= $user_info->phone;
                $content 	= "親愛的用戶：
善意提醒，您的借款 $target_no ，本期應還款 $amount 元已逾期，請立即繳款，逾寬限期需立即全額還款。";
				return $this->send('target_notice',$user_id,$phone,$content);
			}
		}				
		return false;
	}

    public function notice_delay_target_lv2($user_id,$amount=0,$target_no="", $total, $delay_interest){
        if(!empty($user_id)){
            $user_info 	= $this->CI->user_model->get($user_id);
            if($user_info){
                $phone 		= $user_info->phone;
                $content 	= "親愛的用戶：
您的借款 $target_no 已經逾期,本期應還款 $amount 元,請立即繳款,逾寬限期需立即全額還款,18號當日總還款金額為 $total 元,每天增加 $delay_interest 元延滯息,不得再使用普匯借貸服務。

若有還款問題請加入「普匯，你的手機ATM。」(LINE ID: @influxfin) 與我們聯繫，珍惜信用，如期償還，避免法律催收及公告，影響未來銀行借貸。";
                return $this->send('target_notice',$user_id,$phone,$content);
            }
        }
        return false;
    }

    public function notice_order_quotes($user_id,$item='',$instalment=0,$amount=0){
        if(!empty($user_id)){
            $user_info 	= $this->CI->user_model->get($user_id);
            if($user_info){
                $phone 		= $user_info->phone;
                $content 	= "親愛的用戶，您好：
您在普匯金融科技申請的手機貸廠商已經報價「".$item."」，分期付款 $instalment 期，月付金 $amount 元，請儘快至普匯APP進行身分認證，完成分期申請。";
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
