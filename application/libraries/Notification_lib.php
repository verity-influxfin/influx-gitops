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
	
	public function certification($user_id,$investor,$name,$status,$fail=""){
		if($status==1){
			$title = "【認證成功】您的".$name."已通過";
			$content = "您好！您的".$name."已通過。";
		}
		
		if($status==2){
			$title = "【認證失敗】您的".$name."未通過";
			$content = "您好！您的 ".$name."未通過，請重新認證。".($fail?"
退件原因：".$fail:'');
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));

		return $rs;
	}
	
	public function transaction_password($user_id,$investor){
		$title = "【會員】 交易密碼設置成功";
		$content = "您好！您的交易密碼設置成功。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		return $rs;
	}
	
	public function approve_target($user_id,$status,$amount=0,$subloan=false){
		if($status==1){
            $loan_type = $subloan?'產品轉換':'借款';
			$title = "【".$loan_type."審核】 您的審核已通過";
			$content = "親愛的用戶，您好！
您的".$loan_type."審核已通過，
審核額度為 $amount 元，
您已經可以預約申請簽約了，
請登錄普匯APP並在您申請的產品進入簽約頁面。";
		}
		
		if($status==9){
			$title = "【借款審核】 您的借款審核未通過";
			$content = "親愛的用戶，您好！
很抱歉的通知，您的借款審核未能通過，
非常感謝您的申請，
我們將會對您的申請信息進行嚴格保密，
感謝您對普匯的信任。";
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

	public function approve_cancel($user_id){

		$title = "【借款申請】 您的借款申請已取消";
		$content = "您好！
很抱歉的通知，您的借款申請期效已過，
非常感謝您的申請，
我們將會對您的申請信息進行嚴格保密，
感謝您對普匯的信任。";
		
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
很抱歉的通知，您的金融驗證未能通過，
請您重新認證，
我們將會對您的申請信息進行嚴格保密，
感謝您對普匯的信任。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

	public function target_verify_failed($user_id,$investor=0,$remark=""){
		$title = "【審核申請失敗】";
			$content = "親愛的用戶，您好！
非常抱歉，您的借款審批未能通過，
普匯將會對您的申請信息進行嚴格保密，
非常感謝您的申請及對我們的信任。".($remark?"
退件原因：".$remark:'');

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

    public function target_cancel_bidding($user_id,$investor=0,$remark=""){
        $title = "【下架通知】";
        $content = "親愛的用戶，
您好！
非常抱歉，您的申請已下架，
普匯將會對您的申請信息進行嚴格保密，
非常感謝您的申請及對我們的信任".($remark?"
退件原因：".$remark:'')."
或請連繫客服協助處理";

        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> $investor,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
        return $rs;
    }

	
	public function target_verify_success($target){
		$target_no = $target->target_no;
		$title = "【借款驗證】 您申請的借款 $target_no 驗證已通過";
		$content = "親愛的用戶，
您好！
您申請的借款 $target_no 驗證已通過，
將進入媒合階段，感謝您的關注與信任。";

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
	
	public function subloan_verify_failed($user_id,$date="",$amount=0,$target_no="",$remark=false){
		$title = "【產品轉換失敗通知】";
			$content = "親愛的用戶，
您好！
您於 $date 申請的標的，金額 $amount 元，
標的號 $target_no 產品轉換失敗，
請儘速還款以免影響您的信用並造成不必要的損失，謝謝。".($remark?"<br />退件原因：".$remark:'');

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

    public function subloan_auction_failed($user_id,$date="",$amount=0,$target_no="",$name=""){
        $title = "【產品轉換失敗通知】";
        $content = "親愛的用戶，
$name 您好，
您於普匯inFlux申請的產品轉換案件，
案號 $target_no 搓合失敗，
加計至 $date 之清償金額為 $amount 元，
已為您更新金額重新上架，
您可對債務進行全額清償或嘗試等待投資人協助進行展延，
若有疑問請聯繫客服協助處理。";

        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
        return $rs;
    }

	public function auction_closed($user_id,$investor,$target_no,$amount=0){
		if($investor==1){
			$title = "【標的籌滿】 您申請的標的 $target_no 已滿標";
			$content = "親愛的用戶，您好！
您申請的標的 $target_no ，已成功籌滿，
您的得標金額為 $amount 元，感謝您的關注與信任。";
		}else{
			$title = "【借款籌滿】 您申請的借款 $target_no 已滿標";
			$content = "您好！
您申請的借款 $target_no ，核可金額 $amount 元，
已成功籌滿，系統將安排進行放款，請即刻注意銀行匯款資訊。";
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
			$content 	= "親愛的用戶，您好！
您申請的標的 $target_no ，核可金額 $amount 元，
已成功放款。";
		}else{
			$bankaccount = substr($bankaccount, -4, 4);
			$title 		= "【借款放款成功】 您的借款 $target_no 已發放成功";
			$content 	= "親愛的用戶，您好！
您的借款 $target_no ，
借款金額 $amount 元已發放至您的綁定金融卡賬戶尾號 $bankaccount 內，
請您妥善安排用款。

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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}
	
	public function subloan_success($user_id,$target_no,$amount=0){
		$title 		= "【轉換產品成功】 您的借款 $target_no 已發放成功";
		$content 	= "親愛的用戶，您好！
您的借款 $target_no ，
借款金額 $amount 元已經完成產品轉換，已為你清償原借款。
相關繳款訊息請登錄普匯APP查詢。
普匯金融科技提醒你，準時繳款可以避免違約罰款產生。

敬告用戶，本公司不會以短信或電話等任何形式告知您其他非APP內的還款專屬帳號。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}
	
	public function recharge_success($user_id,$investor){
		if($investor==1){
			$title 		= "【資金匯入通知】";
			$content 	= "親愛的用戶，您好！
目前在您綁定的虛擬帳戶中，偵測到資金投入，
請登入服務後，在我的→虛擬帳戶餘額中查詢。";
		}else{
			$title 		= "【款項確認通知】";
			$content 	= "親愛的用戶，您好！
普匯專屬您的還款帳號已收到款項入帳，
平台將代為轉付給出借人，如有任何問題請您與平台聯繫。";
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
	
	public function transfer_success($user_id,$investor,$buy=0,$target_no,$amount, $new_user_id,$date="",$attach=false){
		if($investor==1){
			if($buy==1){
				$title 		= "【債權受讓成功】";
				$content 	= "親愛的用戶，您好！
親愛的用戶，您於 $date 受讓標的 $target_no ，
金額 $amount 元，已成功受讓，
還有更多精選標的等著您挑選，詳情請進入投資端閱覽";
			}else{
				$title 		= "【債權出讓成功】";
				$content 	= "親愛的用戶，您好！
親愛的用戶，您於 $date 出讓標的 $target_no ，
金額 $amount 元，已成功出讓，
還有更多精選標的等著您挑選，詳情請進入投資端閱覽";
			}
		}else{
			$title 		= "【債權轉讓通知】";
			$content 	= "親愛的用戶，您好！
您於本平台的借款，
(使用者編號 $user_id ，借款案號 $target_no )，
債權已轉讓與新債權人(使用者編號 $new_user_id )，
您的還款方式仍按原有約定辦理，僅此通知。";
		}

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$attach);
		return $rs;
	}

	public function unknown_refund($user_id){
		$title 		= "【安全通知】";
		$content 	= "親愛的用戶，您好！
目前在您綁定的虛擬帳戶中，偵測到不明資金投入，
普匯將在近日安排退款。";
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 1,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

	public function withdraw_success($user_id,$investor,$amount=0,$bankaccount=""){
		$bankaccount = substr($bankaccount, -4, 4);
		$title 		= "【提領匯款通知】";
		$content 	= "親愛的用戶，您好！
您的申請的提領，
金額 $amount 元
已發放至您的綁定金融卡賬戶尾號 $bankaccount 內，
請確認核實。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

	public function notice_normal_target($user_id,$amount=0,$target_no="",$date=""){

		$title 		= "【普匯金融科技貼心提醒】您的借款 $target_no ，還款日為 $date";
		$content 	= "親愛的用戶，您好！
您的借款 $target_no ，本期應還本息合計為 $amount 元，
您的應還款日為 $date ，
請在當天中午12點前將款項主動匯入您的專屬還款帳號內，
專屬帳號可在普匯inFlux APP服務內點擊我的→我的還款查看，
如已還款，請忽略本訊息。
敬告用戶，本公司不會以短信、電話或任何形式，告知您其他非服務內揭露的專屬還款帳號。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}
	
	public function repay_success($user_id,$investor=0,$target_no,$amount=0){
		if($investor==1){
			$title 		= "【標的還款成功】 您投資的標的 $target_no 已回款";
			$content 	= " 親愛的用戶，您好！
 您投資的標的 $target_no ，本期已回款，
 回款金額為 $amount 元，感謝您。";
		}else{
			$title 		= "【借款還款成功】 您的還款已成功，還款金額 $amount 元";
			$content 	= "親愛的用戶，您好！
您的借款 $target_no ，
還款金額 $amount 元已還款成功，
感謝您每一次的信用積累。";
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
			$content 	= " 親愛的用戶，您好！
您投資的標的 $target_no ，
由於借款人提前結清債務，已全額結清，
回款金額為 $amount 元，感謝您。";
		}else{
			$title 		= "【借款還款成功】 您的還款已成功，還款金額 $amount 元";
			$content 	= "親愛的用戶，
您好！
您的借款 $target_no ，還款金額 $amount 元已全額還清，
感謝您對普匯inFlux的信任，
在您有資金需求的時候，我們總會及時出現。";
		}
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}
	
	public function prepay_failed($user_id,$target_no){
		$title 		= "【提前還款失敗通知】";
		$content 	= "親愛的用戶，您好！
您的提前還款申請失敗，
若有疑問可洽詢內客服或致電02-2507-9990
普匯inFlux APP(來電客服時間9:30~18:00，國定例假日為公休)。";
		
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}
		
	public function notice_delay_target($user_id,$amount=0,$target_no=""){

		$title 		= "【逾期通知】";
		$content 	= "親愛的用戶，
您的借款 $target_no ，本期應還款 $amount 元已逾期，
請珍惜您的信用，儘快安排還款，如已還款，請忽略本訊息。";
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

    public function notice_delay_target_lv2($user_id,$amount=0,$target_no=""){

        $title 		= "【逾期通知】";
        $content 	= "親愛的用戶，
善意提醒，您的借款 $target_no ，
本期應還款 $amount 元已逾期，
請立即繳款，逾寬限期需立即全額還款。";
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
        return $rs;
    }

    public function notice_order_apply($user_id,$item='',$instalment=0,$delivery=0){

        $delivery = $delivery==0?'線下':'線上';
        $title 		= "【接收到新的訂單】";
        $content 	= "親愛的用戶，您好！
您在普匯合作夥伴APP中已經接收到一筆新的訂單，
「".$item."」分期付款 $instalment 期、".$delivery."交易，
請儘速至APP中完成報價。";
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 1,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
        return $rs;
    }

    public function notice_order_quotes($user_id,$item='',$instalment=0,$amount=0){

        $title 		= "【商家報價成功】";
        $content 	= "親愛的用戶，您好！
您在普匯金融科技申請的手機貸廠商已經報價，
「".$item."」分期付款 $instalment 期，月付金 $amount 元，
請儘快至普匯APP進行身分認證，完成分期申請。";
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
        return $rs;
    }

    public function account_remaining($user_id,$investor=0){

		$title 		= "【普匯金融科技提醒】";
		$content 	= "親愛的會員：
普匯平台提醒您，您在本平台的虛擬帳戶餘額超過1000元，
如不投資或還款，請儘快匯出。
普匯金融科技平台謝謝您的支持。";
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content));
		return $rs;
	}

	public function notice_cer_investigation($user_id,$target_no){
        $descri     = $target_no!=''?'[ 消費貸 ]，案號[ ".$target_no." ]':'';
        $title 		= "您的聯合徵信申請已送出";
        $content 	= "親愛的用戶，
您好！您申請的普匯inFlux".$descri."聯合徵信認證信件已送出，
請至綁定信箱收信，並依內容回覆相關文件。";
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);

		$etitle 		= "【認證】聯合徵信申請";
		$econtent 	= "親愛的用戶( 使用者編號 $user_id )，
您好！感謝您申請普匯inFlux".$descri."聯合徵信認證，
請將您申請完之徵信報告；以附件形式回覆此封mail，
系統收到您的來信後會直接更新驗證內容，
請進入普匯inFlux確認您的認證狀態。";
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$etitle,nl2br($econtent),false,CREDIT_EMAIL,'普匯驗證中心');
		return $rs;
	}

    public function notice_cer_job($user_id){
        $title 		= "您的工作認證申請已送出";
        $content 	= "親愛的用戶，
您好！您申請的普匯inFlux工作認證信件已送出，
請至綁定信箱收信，並依內容回覆相關文件。";
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);

        $etitle 		= "【認證】工作認證申請";
        $econtent 	= "親愛的用戶( 使用者編號 $user_id )，
您好！感謝您申請普匯inFlux工作認證，
請將您申請完之徵信報告；以附件形式回覆此封mail，
系統收到您的來信後會直接更新驗證內容，
請進入普匯inFlux確認您的認證狀態。";
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$etitle,nl2br($econtent),false,CREDIT_EMAIL,'普匯驗證中心');
        return $rs;
    }
}
