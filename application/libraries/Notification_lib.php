<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/user_notification_model');
    }

	public function first_login($user_id,$investor){
		
		$title = "[登入資訊] 首次登入成功";
		$content = "您已於 ".date("Y-m-d H:i:s")." 首次登入成功
					inFlux歡迎您";
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
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
	
	public function transaction_password($user_id,$investor){
		$title = "[會員] 交易密碼設置成功";
		$content = "您好！
					您的交易密碼設置成功。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}
	
	public function approve_target($user_id,$status,$amount=0){
		if($status==1){
			$title = "[借款審核] 您的借款審核已通過";
			$content = "尊敬的用戶：
						您好！
						您的借款審核已通過，審核額度為 $amount 元，您已經可以預約申請簽約了，請登錄並在首頁您申請的產品處進入簽約頁面。";
		}
		
		if($status==9){
			$title = "[借款審核] 您的借款審核未通過";
			$content = "您好！
						很抱歉的通知，您的借款審核未能通過，非常感謝您的申請，我們將會對您的申請信息進行嚴格保密，感謝您對我司的信任。";
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}

	public function bankaccount_verify_failed($user_id){
		$title = "[驗證失敗] 您的借款驗證未通過";
			$content = "您好！
						很抱歉的通知，您的借款驗證未能通過，非常感謝您的申請，我們將會對您的申請信息進行嚴格保密，感謝您對我司的信任。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}

	public function target_verify_success($target){
		$title = "[借款驗證] 您的借款驗證已通過";
			$content = "尊敬的用戶：
						您好！
						您的借款驗證已通過，將進入媒合階段，感謝您的關注與信任。";

		$param = array(
			"user_id"	=> $target->user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}
	
	public function auction_closed($user_id,$investor,$target_no,$amount=0){
		if($investor==1){
			$title = "[標的籌滿] 您申請的標的 $target_no 已滿標";
			$content = "尊敬的用戶：
						您好！
						您申請的標的 $target_no ，已成功籌滿，您的得標金額為 $amount 元，感謝您的關注與信任。";
		}else{
			$title = "[借款籌滿] 您申請的借款 $target_no 已滿標";
			$content = "您好！
						您申請的借款 $target_no ，核可金額 $amount 元，已成功籌滿，系統將安排進行放款，請即刻注意銀行匯款資訊。";
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
