<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/user_notification_model');
    }

	public function first_login($user_id,$investor){
		
		$title = "【登入資訊】 首次登入成功";
		$content = "您已於 ".date("Y-m-d H:i:s")." 首次登入成功
					普匯金融科技歡迎您";
		
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
			$title = "【認證成功】 您的".$name."已通過";
			$content = "您好！
						您的".$name."已通過。";
		}
		
		if($status==2){
			$title = "【認證失敗】 您的".$name."未通過";
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
		$title = "【會員】 交易密碼設置成功";
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
			$title = "【借款審核】 您的借款審核已通過";
			$content = "親愛的用戶：
						您好！
						您的借款審核已通過，審核額度為 $amount 元，您已經可以預約申請簽約了，請登錄並在首頁您申請的產品處進入簽約頁面。";
		}
		
		if($status==9){
			$title = "【借款審核】 您的借款審核未通過";
			$content = "親愛的用戶：
						您好！
						很抱歉的通知，您的借款審核未能通過，非常感謝您的申請，我們將會對您的申請信息進行嚴格保密，感謝您對普匯的信任。";
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content);
		return $rs;
	}

	public function approve_cancel($user_id){

		$title = "【借款申請】 您的借款申請已取消";
		$content = "您好！
					很抱歉的通知，您的借款申請期效已過，非常感謝您的申請，我們將會對您的申請信息進行嚴格保密，感謝您對普匯的信任。";
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}
	
	public function bankaccount_verify_failed($user_id,$investor=0){
		$title = "【驗證失敗】 您的金融驗證未通過";
			$content = "您好！
						很抱歉的通知，您的金融驗證未能通過，請您重新認證，我們將會對您的申請信息進行嚴格保密，感謝您對普匯的信任。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content);
		return $rs;
	}

	public function target_verify_success($target){
		$target_no = $target->target_no;
		$title = "【借款驗證】 您申請的借款 $target_no 驗證已通過";
			$content = "親愛的用戶：
						您好！
						您申請的借款 $target_no 驗證已通過，將進入媒合階段，感謝您的關注與信任。";

		$param = array(
			"user_id"	=> $target->user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->admin_notification("案件已上架 會員ID：".$target->user_id,"案件已上架 會員ID：".$target->user_id." 案號：".$target->target_no);
		return $rs;
	}
	
	public function auction_closed($user_id,$investor,$target_no,$amount=0){
		if($investor==1){
			$title = "【標的籌滿】 您申請的標的 $target_no 已滿標";
			$content = "親愛的用戶：
						您好！
						您申請的標的 $target_no ，已成功籌滿，您的得標金額為 $amount 元，感謝您的關注與信任。";
		}else{
			$title = "【借款籌滿】 您申請的借款 $target_no 已滿標";
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
	
	public function lending_success($user_id,$investor,$target_no,$amount=0,$bankaccount=""){
		if($investor==1){
			$title 		= "【借款放款成功】 您申請的標的 $target_no 已放款成功";
			$content 	= "親愛的用戶：
						您好！
						您申請的標的 $target_no ，核可金額 $amount 元，已成功放款。";
		}else{
			$bankaccount = substr($bankaccount, -4, 4);
			$title 		= "【借款放款成功】 您的借款 $target_no 已發放成功";
			$content 	= "親愛的用戶：
						您好！
						您的借款 $target_no ，借款金額 $amount 元已發放至您的綁定金融卡賬戶尾號 $bankaccount 內，請您妥善安排用款。
						敬告用戶，本公司不會以短信或電話等任何形式告知您其他非APP內的還款專屬帳號。";
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content);
		return $rs;
	}
	
	public function recharge_success($user_id,$investor){
		if($investor==1){
			$title 		= "普匯金融科技【資金匯入通知】";
			$content 	= "親愛的用戶：
							您好！
							目前在您綁定的虛擬帳戶中，偵測到資金投入，請登入服務後，在我的→虛擬帳戶餘額中查詢。";
		}else{
			$title 		= "普匯金融科技【款項確認通知】";
			$content 	= "親愛的用戶：
							您好！
							普匯專屬您的還款帳號已收到款項入帳，平台將代為轉付給出借人，如有任何問題請您與平台聯繫。";
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
	
	public function unknown_refund($user_id){
		$title 		= "【普匯金融科技安全通知】";
		$content 	= "親愛的用戶：
						您好！
						目前在您綁定的虛擬帳戶中，偵測到不明資金投入，普匯將在近日安排退款。";
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 1,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content); 
		return $rs;
	}
	
	public function withdraw_success($user_id,$investor,$amount=0,$bankaccount=""){
		$bankaccount = substr($bankaccount, -4, 4);
		$title 		= "普匯金融科技【提領匯款通知】";
		$content 	= "親愛的用戶：
					您好！
					您的申請的提領，金額 $amount 元已發放至您的綁定金融卡賬戶尾號 $bankaccount 內，請確認核實。";
	
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content); 
		return $rs;
	}
	
	public function notice_normal_target($user_id,$amount=0,$target_no="",$date=""){

		$title 		= "【普匯金融科技貼心提醒】您的借款 $target_no ，還款日為 $date";
		$content 	= "親愛的用戶，您好！
					您的借款 $target_no ，本期應還本息合計為 $amount 元，您的應還款日為 $date ，請在當天中午12點前將款項主動匯入您的專屬還款帳號內，專屬帳號可在我的手機ATM服務內點擊我的→我的還款查看，如已還款，請忽略本訊息。
					敬告用戶，本公司不會以短信、電話或任何形式，告知您其他非服務內揭露的專屬還款帳號。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content);
		return $rs;
	}
	
	public function repay_success($user_id,$investor=0,$target_no,$amount=0){
		if($investor==1){
			$title 		= "【標的還款成功】 您投資的標的 $target_no 已回款";
			$content 	= " 親愛的用戶：
							您好！
							您投資的標的 $target_no ，本期已回款，回款金額為 $amount 元，感謝您。";
		}else{
			$title 		= "【借款還款成功】 您的還款已成功，還款金額 $amount 元";
			$content 	= "親愛的用戶：
							您好！
							您的借款 $target_no ，還款金額 $amount 元已還款成功，感謝您每一次的信用積累。";
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

	public function prepay_success($user_id,$investor=0,$target_no,$amount=0){
		if($investor==1){
			$title 		= "【標的還款成功】 您投資的標的 $target_no 已提前回款";
			$content 	= " 親愛的用戶：
							您好！
							您投資的標的 $target_no ，由於借款人提前結清債務，已全額結清，回款金額為 $amount 元，感謝您。";
		}else{
			$title 		= "【借款還款成功】 您的還款已成功，還款金額 $amount 元";
			$content 	= "親愛的用戶：
							您好！
							您的借款 $target_no ，還款金額 $amount 元已全額還清，感謝您對手機ATM的信任，在您有資金需求的時候，我們總會及時出現。";
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
	
	public function notice_delay_target($user_id,$amount=0,$target_no=""){

		$title 		= "【普匯金融科技逾期通知】";
		$content 	= "您好！您在手機ATM申請的借款 $target_no ，本期應還款 $amount 元已逾期，請珍惜您的信用，及時安排還款，如已還款，請忽略本訊息。";
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,$content);
		return $rs;
	}
}
