<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use Symfony\Component\HttpClient\HttpClient;

class Notification_lib{


	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/user_notification_model');
		$this->CI->load->library('Transfer_lib');
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

	public function recheck_certification($user_id,$investor,$fail=""){
		$title = "【普匯系統通知】";
		$content = "親愛的會員您好，為確保資料真實性，請您提重新提供實名認證資料，更新您的訊息，謝謝。";
		$type = 'b03';

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type, FALSE, FALSE, FALSE, TRUE, $investor);

		return $rs;
	}

	public function certification($user_id,$investor,$name,$status,$fail=""){
	    $type = false;
		if($status==1){
			$title = "【認證成功】您的".$name."已通過";
			$content = "您好！您的".$name."已通過。";
            $type = 'b02';
        }

		if($status==2){
			$title = "【認證失敗】您的".$name."未通過";
			$content = "您好！您的 ".$name."未通過，請重新認證。".($fail?"退件原因：".(is_array($fail)?join("、", $fail):$fail):"");
            $type = 'b03';
        }

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type, FALSE, FALSE, FALSE, TRUE, $investor);

		return $rs;
	}

    public function promote_contract_done($user_id, $investor, $status)
    {
        $type = FALSE;
        $title = "";
        $content = "";

        if ($status == PROMOTE_REVIEW_STATUS_SUCCESS)
        {
            $title = "推薦有賞「特約通路」審核通過";
            $content = "恭喜您申請成功
馬上前往普匯APP同意合約
即可成為特約通路商";
            $type = 'b08';
        }

        if ($status == PROMOTE_REVIEW_STATUS_WITHDRAW)
        {
            $title = "推薦有賞「特約通路」審核未通過";
            $content = "很遺憾本次無法成功申請特約通路合作
非常感謝您的支持";
            $type = 'b08';
        }

        $param = array(
            "user_id" => $user_id,
            "investor" => $investor,
            "title" => $title,
            "content" => $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('sendemail');
        $this->CI->sendemail->user_notification($user_id, $title, nl2br($content), $type, FALSE, FALSE, FALSE, FALSE, $investor);

        return $rs;
    }

    public function promote_contract_review($email, $review_user_id)
    {
        $title = "推薦有賞「特約通路」合約待您審閱";
        $content = "您有一件推薦有賞特約通路合約待審閱
請點選下方連結進行審核
謝謝

審核頁面網址：" . base_url(URL_ADMIN . 'sales/qrcode_contracts?user_id=' . $review_user_id);
        $type = 'b08';

        $this->CI->load->library('sendemail');
        $rs = $this->CI->sendemail->email_notification($email, $title, nl2br($content), $type);

        return $rs;
    }

	public function temp_realname_failed($user_id){
		$title = "普匯系統通知";
		$content = "親愛的會員您好：
普匯本公司為響應政府推出紓困優惠，全面提高額度，上班族高達30萬，邀請您重新體驗最方便的金融科技借貸平台！
快快體驗提升額度的感覺，拿起手機更新您的實名認證！";
		$type = 'b03';

		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type);

		return true;
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

	public function approve_target($user_id,$status,$target,$amount=0,$subloan=false,$remark = false) {
        $notificationType = NOTIFICATION_TYPE_NONE;
        $data = [];
        $title = "";
        $content = "";

		if($status==1){
            $loan_type = $subloan?'產品轉換':'借款';
			$title = "【".$loan_type."審核】 您的審核已通過";
			$content = "親愛的用戶，您好！
您的".$loan_type."審核已通過，
審核額度為 $amount 元，
您已經可以預約申請簽約了，
請登錄普匯APP並在您申請的產品進入簽約頁面。";
            $type = 'b02';
		}

		if($status==9){
            if (is_array($target))
            {
                $target = json_decode(json_encode($target));
            }
			$title = "【借款審核】 您的借款審核未通過";
			$content = "親愛的用戶，您好！
很抱歉的通知，您的借款審核未能通過，
非常感謝您的申請，
我們將會對您的申請信息進行嚴格保密，
感謝您對普匯的信任。
";
			$remark ? $content .= '退件原因：' . $remark : '';
            $type = 'b03';
            $notificationType = NOTIFICATION_TYPE_GOTO_TARGET;
            $data = ['target_id' => $target->id, 'product_id' => $target->product_id];
		}

        $rs = $this->send_app_with_data($user_id,USER_BORROWER,$notificationType,$title,$content,$data);

		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type);
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

    public function expired_cancel($user_id)
    {
        $title = '【借款申請】 您的借款申請已取消';
        $content = "您好！\n很抱歉的通知，您的借款簽約期效已過，\n非常感謝您的申請，\n我們將會對您的申請信息進行嚴格保密，\n感謝您對普匯的信任。";

        $param = array(
            'user_id' => $user_id,
            'investor' => BORROWER,
            'title' => $title,
            'content' => $content,
        );
        $this->CI->user_notification_model->insert($param);
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03', FALSE, FALSE, FALSE, TRUE, $investor);
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03', FALSE, FALSE, FALSE, TRUE, $investor);
		return $rs;
	}

    public function target_sign_failed($user_id, $investor = 0, $product_name = "", $type=1)
    {
        $title = "【簽約失敗】";
        $content = "親愛的會員您好：
系統偵測到您申請的 [" . $product_name . "] 簽約照片異常，
請重新簽約。";

        $param = array(
            "user_id" => $user_id,
            "investor" => $investor,
            "title" => $title,
            "content" => $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id, $title, nl2br($content), 'b03', FALSE, FALSE, FALSE, TRUE, $investor);
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
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03', FALSE, FALSE, FALSE, TRUE, $investor);
        return $rs;
    }

    public function legal_collection_cancel_transfer($user_id,$target_no,$loan_userid){
        $title = "【下架通知】逾期案件「債權轉讓」下架通知！";
        $content = "親愛的用戶，您好！
很抱歉通知您，平台案號：" . $target_no . " (逾期債務人ID: " . $loan_userid . ")，
正在進行償還部分款項，故必須下架該案債轉媒合，造成不便，還請見諒！";

        $param = array(
            "user_id" => $user_id,
            "investor" => 1,
            "title" => $title,
            "content" => $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id, $title, nl2br($content), 'b03', FALSE, FALSE, FALSE, TRUE, INVESTOR);
        return $rs;
    }

    public function withdraw_invalid_target($user_id,$investor=0){
        $title = "【系統通知】";
        $content = "親愛的會員您好，配合民法規定，調降借貸最高利率至16%，為維護您的權益，請重新申請案件，謝謝您的配合！";

        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> $investor,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03', FALSE, FALSE, FALSE, TRUE, $investor);
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
        $this->CI->load->model('user/user_meta_model');
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03');
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
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03');
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

	public function lending_success($user_id,$investor,$target_no,$product_id,$sub_product_id,$amount=0,$bankaccount=""){
		if($investor==1){
			$product_list = $this->CI->config->item('product_list');
			$sub_product_list = $this->CI->config->item('sub_product_list');

			if(isset($product_list[$product_id])) {
				$product = $product_list[$product_id];
				if ((count($product['sub_product']) > 0 && isset($sub_product_list[$sub_product_id]))
					|| $product['hiddenMainProduct']
					|| ($sub_product_id && !in_array($sub_product_id, $product['sub_product']))) {
					if ($this->CI->transfer_lib->is_sub_product($product, $sub_product_id)) {
						$product = $this->CI->transfer_lib->trans_sub_product($product, $sub_product_id);
					}
				}
			}
			$product_name = isset($product['name']) ? "【".$product['name']."】" : "";

			$title 		= $product_name."您投資的標的 $target_no 已完成放款";
			$content 	= "親愛的用戶，您好！
您申請的標的 $target_no ，核可金額 $amount 元，
已成功放款。";
            $type = 'i04';
		}else{
			$bankaccount = substr($bankaccount, -4, 4);
			$title 		= "【借款放款成功】 您的借款 $target_no 已發放成功";
			$content 	= "親愛的用戶，您好！
您的借款 $target_no ，
借款金額 $amount 元已發放至您的綁定金融卡賬戶尾號 $bankaccount 內，
請您妥善安排用款。

敬告用戶，本公司不會以短信或電話等任何形式告知您其他非APP內的還款專屬帳號。";
            $type = 'b04';
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b04');
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
                $type = 'i06';
			}else{
				$title 		= "【債權出讓成功】";
				$content 	= "親愛的用戶，您好！
親愛的用戶，您於 $date 出讓標的 $target_no ，
金額 $amount 元，已成功出讓，
還有更多精選標的等著您挑選，詳情請進入投資端閱覽";
                $type = 'i05';
			}
		}else{
			$title 		= "【債權轉讓通知】";
			$content 	= "親愛的用戶，您好！
您於本平台的借款，
(使用者編號 $user_id ，借款案號 $target_no )，
債權已轉讓與新債權人(使用者編號 $new_user_id )，
您的計息方式仍按原有約定辦理，僅此通知。";
            $type = 'b08';
		}

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type,$attach, FALSE, FALSE, TRUE, $investor);
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'i08', FALSE, FALSE, FALSE, TRUE, INVESTOR);
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
        $type = $investor?'i08':'b08';
		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type, FALSE, FALSE, FALSE, TRUE, $investor);
		return $rs;
	}

	public function notice_normal_target($user_id,$amount=0,$difference=0,$target_no="",$date=""){

		$title 		= "【普匯金融科技貼心提醒】您的借款 $target_no ，還款日為 $date";
		$content 	= "親愛的用戶，您好！
您的借款 $target_no ，本期應還本息合計為 $amount 元，
虛擬帳戶尚不足 ".abs($difference)." 元，
請在還款日 $date 前將款項匯入虛擬帳戶。
專屬帳號可在普匯inFlux APP服務內點擊我的→我的還款查看。
敬告用戶，本公司不會以簡訊、電話或任何形式，告知您其他非服務內揭露的專屬還款帳號。";

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> 0,
			"title"		=> $title,
			"content"	=> $content,
		);
		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b08');
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
			$content 	= "親愛的用戶，您好！
您投資的標的 $target_no ，
由於借款人提前結清債務，已全額結清，
回款金額為 $amount 元，感謝您。";
			$type = 'i04';
		}else{
			$title 		= "【借款還款成功】 您的還款已成功，還款金額 $amount 元";
			$content 	= "親愛的用戶，
您好！
您的借款 $target_no ，還款金額 $amount 元已全額還清，
感謝您對普匯inFlux的信任，
在您有資金需求的時候，我們總會及時出現。";
            $type = 'b04';
		}

		$param = array(
			"user_id"	=> $user_id,
			"investor"	=> $investor,
			"title"		=> $title,
			"content"	=> $content,
		);

		$rs = $this->CI->user_notification_model->insert($param);
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type, FALSE, FALSE, FALSE, TRUE, $investor);
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03');
		return $rs;
	}

	public function notice_delay_target($user_id,$amount=0,$target_no=""){

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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03');
		return $rs;
	}

    public function notice_delay_target_lv2($user_id,$amount=0,$target_no="", $total, $delay_interest){

        $title 		= "【逾期通知】";
        $content 	= "親愛的用戶：
您的借款 $target_no 已經逾期,
本期應還款 $amount 元,請立即繳款,
逾寬限期需立即全額還款,
18號當日總還款金額為 $total 元,每天增加 $delay_interest 元延滯息,不得再使用普匯借貸服務。

若有還款問題請加入「普匯，你的手機ATM。」(LINE ID: @influxfin) 與我們聯繫，
珍惜信用，如期償還，避免法律催收及公告，影響未來銀行借貸。";
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03');
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
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'i08', FALSE, FALSE, FALSE, TRUE, INVESTOR);
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
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b02');
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
		$this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'i08', FALSE, FALSE, FALSE, TRUE, $investor);
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

		$etitle 		= "【資料提供】聯合徵信申請";
        $econtent = <<<econtent
親愛的{$user_id}用戶，感謝您提供信用報告，普匯將儘速協助您完成貸款，謝謝。
<ul style="list-style-type: decimal; text-align: left;">
<li>申請「個人聯徵信用報告」請進入「聯徵中心」網站查看說明。\r\n＊網址：<a href="https://www.jcic.org.tw/main_ch/docDetail.aspx?uid=170&pid=170&docid=412">https://www.jcic.org.tw/main_ch/docDetail.aspx?uid=170&pid=170&docid=412</a></li>
<li>申請完成後請將「個人信用報告」電子檔(PDF)以附件方式回傳此封驗證信件。</li>
<li>請至「普匯influx」APP點選「已完成回信」，系統將自動審核。</li>
</ul>
<img src="https://d3imllwf4as09k.cloudfront.net/mail_assets/content_images/investigation_apply_sample.png" style="width:100%;height:auto">
econtent;
		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->user_notification($user_id,$etitle,nl2br($econtent),'b08',false,CREDIT_EMAIL,'普匯驗證中心');
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
請將您申請完之《勞保異動明細》，以附件形式回覆此封mail，
系統收到您的來信後會直接更新驗證內容，
請進入普匯inFlux確認您的認證狀態。";
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$etitle,nl2br($econtent),'b08',false,CREDIT_EMAIL,'普匯驗證中心');
        return $rs;
    }

    public function stageCer_Target_remind($user_id){

            $title = "【媒合失敗】您未能媒合成功";
            $content = '很可惜，您未能媒合成功，
建議可再提供更多資料或訊息，
加強信用透明度。';
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03');

        return $rs;
    }

	public function notify_target_associates($mail, $username, $subProduct, $character){

		$title = "【合作夥伴】專案{$character}";
		$content = "您好，
	您的合作夥伴 {$username} 在普匯金融科技
	邀請您做為企業主貸 {$subProduct} 專案的 {$character}，
	請至 <a href='https://www.influxfin.com/'> 普匯金融科技官網</a>了解更多，
	或下載 <a href='https://event.influxfin.com/r/url'> 普匯inFlux</a>更新您的資料，
	取得授信額度。";

		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->email_notification($mail,$title,nl2br($content));
	}

    public function notify_target_product_1002_associates($mail, $username, $subProduct, $character)
    {
        $this->CI->load->library('target_lib');
        $character = $this->CI->target_lib->get_product_1002_character_meaning($character);

		$title = "【{$subProduct}】新增「{$character}」";
        $content = "「{$username}」已加入您作為「{$character}」
1.下載「普匯inFlux」APP。
2.完成「一般使用者」會員註冊。
3.成功登入會員。
4.至「產品列表」中「{$subProduct}」點選「查看狀態」。
5.完成資料提供(實名認證、個人基本資料...)。";

		$this->CI->load->library('Sendemail');
		$this->CI->sendemail->email_notification($mail,$title,nl2br($content));
	}

    public function EDM($user_id, $title, $content, $EDM, $EDM_href, $investor = 0, $school = false, $years, $sex, $app, $mail, $mail_list = [], $disable_output=FALSE)
    {
        $user_list = [];
        $user_ids = false;
        $count = 0;
        $param = [
            'email !=' => null,
        ];
        $investor == 0 ? $param['status'] = 1 : '';
        $investor == 1 ? $param['investor_status'] = 1 : '';
        if ($investor == 2) {
            $param['email !='] = '';
        }

        if ($years) {
            $years = explode('to', $years);
            $form_years = (isset($years[1]) ? date('Y', strtotime('-' . ($years[1]-1) . ' year', time())) : date('Y', strtotime('-' . ($years[0]-1) . ' year', time()))) . '-01-01';
            $to_years = date('Y', strtotime('-' . ($years[0]-1) . ' year', time())) . '-12-31';
            $param['birthday >='] = strval($form_years);
            $param['birthday <='] = strval($to_years);
        }

        if ($sex) {
            $param['sex'] = $sex;
        }

        if ($school) {
            $this->CI->load->model('user/user_meta_model');
            $school_list = $this->CI->user_meta_model->get_many_by([
                "meta_key" => "school_name",
                "meta_value" => $school,
            ]);
            foreach ($school_list as $key => $value) {
                $user_ids[] = $value->user_id;
            }
            $user_ids ? $param['id'] = $user_ids : false;
        }

        if ($user_id == 0) {
            $user_list = $this->CI->user_model->get_many_by($param);
        } else {
            $user_info = $this->CI->user_model->get($user_id);
            $user_list[] = $user_info;
        }

		if (count($mail_list) > 0)
		{
			$this->CI->load->library('Sendemail');
			foreach ($mail_list as $mail) {
				$this->CI->sendemail->EDM($mail, $title, nl2br($content), $EDM, $EDM_href);
			}
			$count = count($mail_list);
		}else if (count($user_list) > 0) {
            foreach ($user_list as $key => $value) {
                if($app){
                    $param = array(
                        "user_id" => $value->id,
                        "title" => $title,
                        "content" => $this->remove_emoji($content),
                    );
                    if($investor == 0 || $investor == 2 ){
                        $param['investor'] = 0;
                        $this->CI->user_notification_model->insert($param);
                    }
                    if($investor == 1 || $investor == 2 ){
                        $param['investor'] = 1;
                        $this->CI->user_notification_model->insert($param);
                    }
                }
                if($mail){
                    $this->CI->load->library('Sendemail');
                    $this->CI->sendemail->EDM($value->email, $title, nl2br($content), $EDM, $EDM_href);
                }
                $count++;
            }
            $this->CI->load->library('parser');
		}
		if(!$disable_output) {
            echo $content = $this->CI->parser->parse('email/sales_mail', array("title" => $title, "content" => nl2br($content), "EDM" => $EDM), TRUE);
            echo '已發送 ' . $count . ' 位使用者';
        }
        return $count;

    }

    public function notice_msg($user_id, $title, $content, $investor = 0 ,$type)
    {
        $user_list = [];
        $count = 0;
        $param = [
            'email !=' => null,
        ];
        $investor == 0 ? $param['status'] = 1 : '';
        $investor == 1 ? $param['investor_status'] = 1 : '';
        if ($investor == 2) {
            $param['status'] = 1;
            $param['investor_status'] = 1;
        }
        if ($user_id == 0) {
            $user_list = $this->CI->user_model->get_many_by($param);
        } else {
            $user_info = $this->CI->user_model->get($user_id);
            $user_list[] = $user_info;
        }
        if (count($user_list) > 0) {
            foreach ($user_list as $key => $value) {
                $param = array(
                    "user_id" => $value->id,
                    "title" => $title,
                    "content" => $this->remove_emoji($content),
                );
                if($investor == 0 || $investor == 2 ){
                    $param['investor'] = 0;
                    $this->CI->user_notification_model->insert($param);
                }
                if($investor == 1 || $investor == 2 ){
                    $param['investor'] = 1;
                    $this->CI->user_notification_model->insert($param);
                }
                $this->CI->load->library('Sendemail');
                $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type, FALSE, FALSE, FALSE, TRUE, $param['investor'] ?? 0);
                $count++;
            }
            $this->CI->load->library('parser');
            $mail_event = $this->CI->config->item('mail_event');
            echo $content = $this->CI->parser->parse('email/user_notification', array("title" => $title , "content"=> $content , "type"=> $type , "mail_event"=> $mail_event),TRUE);
        }
        echo '已發送 ' . $count . ' 位使用者';
    }

    function remove_emoji($text)
    {
        return preg_replace('/[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0077}\x{E006C}\x{E0073}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0073}\x{E0063}\x{E0074}\x{E007F})|[\x{1F3F4}](?:\x{E0067}\x{E0062}\x{E0065}\x{E006E}\x{E0067}\x{E007F})|[\x{1F3F4}](?:\x{200D}\x{2620}\x{FE0F})|[\x{1F3F3}](?:\x{FE0F}\x{200D}\x{1F308})|[\x{0023}\x{002A}\x{0030}\x{0031}\x{0032}\x{0033}\x{0034}\x{0035}\x{0036}\x{0037}\x{0038}\x{0039}](?:\x{FE0F}\x{20E3})|[\x{1F441}](?:\x{FE0F}\x{200D}\x{1F5E8}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F467})|[\x{1F468}](?:\x{200D}\x{1F468}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467}\x{200D}\x{1F466})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F467})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F469}\x{200D}\x{1F466})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F468})|[\x{1F469}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F469})|[\x{1F469}\x{1F468}](?:\x{200D}\x{2764}\x{FE0F}\x{200D}\x{1F48B}\x{200D}\x{1F468})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B3})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B2})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B1})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F9B0})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F9B0})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2640}\x{FE0F})|[\x{1F575}\x{1F3CC}\x{26F9}\x{1F3CB}](?:\x{FE0F}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FF}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FE}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FD}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FC}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FB}\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F9B8}\x{1F9B9}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{200D}\x{2640}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FF}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FE}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FD}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FC}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{1F3FB}\x{200D}\x{2642}\x{FE0F})|[\x{1F46E}\x{1F9B8}\x{1F9B9}\x{1F482}\x{1F477}\x{1F473}\x{1F471}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F9DE}\x{1F9DF}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F46F}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93C}\x{1F93D}\x{1F93E}\x{1F939}](?:\x{200D}\x{2642}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F692})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F680})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2708}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A8})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3A4})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BB})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F52C})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F4BC})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3ED})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F527})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F373})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F33E})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2696}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F3EB})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{200D}\x{1F393})|[\x{1F468}\x{1F469}](?:\x{1F3FF}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FE}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FD}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FC}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{1F3FB}\x{200D}\x{2695}\x{FE0F})|[\x{1F468}\x{1F469}](?:\x{200D}\x{2695}\x{FE0F})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FF})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FE})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FD})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FC})|[\x{1F476}\x{1F9D2}\x{1F466}\x{1F467}\x{1F9D1}\x{1F468}\x{1F469}\x{1F9D3}\x{1F474}\x{1F475}\x{1F46E}\x{1F575}\x{1F482}\x{1F477}\x{1F934}\x{1F478}\x{1F473}\x{1F472}\x{1F9D5}\x{1F9D4}\x{1F471}\x{1F935}\x{1F470}\x{1F930}\x{1F931}\x{1F47C}\x{1F385}\x{1F936}\x{1F9D9}\x{1F9DA}\x{1F9DB}\x{1F9DC}\x{1F9DD}\x{1F64D}\x{1F64E}\x{1F645}\x{1F646}\x{1F481}\x{1F64B}\x{1F647}\x{1F926}\x{1F937}\x{1F486}\x{1F487}\x{1F6B6}\x{1F3C3}\x{1F483}\x{1F57A}\x{1F9D6}\x{1F9D7}\x{1F9D8}\x{1F6C0}\x{1F6CC}\x{1F574}\x{1F3C7}\x{1F3C2}\x{1F3CC}\x{1F3C4}\x{1F6A3}\x{1F3CA}\x{26F9}\x{1F3CB}\x{1F6B4}\x{1F6B5}\x{1F938}\x{1F93D}\x{1F93E}\x{1F939}\x{1F933}\x{1F4AA}\x{1F9B5}\x{1F9B6}\x{1F448}\x{1F449}\x{261D}\x{1F446}\x{1F595}\x{1F447}\x{270C}\x{1F91E}\x{1F596}\x{1F918}\x{1F919}\x{1F590}\x{270B}\x{1F44C}\x{1F44D}\x{1F44E}\x{270A}\x{1F44A}\x{1F91B}\x{1F91C}\x{1F91A}\x{1F44B}\x{1F91F}\x{270D}\x{1F44F}\x{1F450}\x{1F64C}\x{1F932}\x{1F64F}\x{1F485}\x{1F442}\x{1F443}](?:\x{1F3FB})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FA}](?:\x{1F1FF})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1FA}](?:\x{1F1FE})|[\x{1F1E6}\x{1F1E8}\x{1F1F2}\x{1F1F8}](?:\x{1F1FD})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F9}\x{1F1FF}](?:\x{1F1FC})|[\x{1F1E7}\x{1F1E8}\x{1F1F1}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1FB})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1FB}](?:\x{1F1FA})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FE}](?:\x{1F1F9})|[\x{1F1E6}\x{1F1E7}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FA}\x{1F1FC}](?:\x{1F1F8})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F7})|[\x{1F1E6}\x{1F1E7}\x{1F1EC}\x{1F1EE}\x{1F1F2}](?:\x{1F1F6})|[\x{1F1E8}\x{1F1EC}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}](?:\x{1F1F5})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EE}\x{1F1EF}\x{1F1F2}\x{1F1F3}\x{1F1F7}\x{1F1F8}\x{1F1F9}](?:\x{1F1F4})|[\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1F3})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1EC}\x{1F1ED}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F4}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FF}](?:\x{1F1F2})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1F1})|[\x{1F1E8}\x{1F1E9}\x{1F1EB}\x{1F1ED}\x{1F1F1}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FD}](?:\x{1F1F0})|[\x{1F1E7}\x{1F1E9}\x{1F1EB}\x{1F1F8}\x{1F1F9}](?:\x{1F1EF})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EB}\x{1F1EC}\x{1F1F0}\x{1F1F1}\x{1F1F3}\x{1F1F8}\x{1F1FB}](?:\x{1F1EE})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F5}\x{1F1F8}\x{1F1F9}](?:\x{1F1ED})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}](?:\x{1F1EC})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F9}\x{1F1FC}](?:\x{1F1EB})|[\x{1F1E6}\x{1F1E7}\x{1F1E9}\x{1F1EA}\x{1F1EC}\x{1F1EE}\x{1F1EF}\x{1F1F0}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F7}\x{1F1F8}\x{1F1FB}\x{1F1FE}](?:\x{1F1EA})|[\x{1F1E6}\x{1F1E7}\x{1F1E8}\x{1F1EC}\x{1F1EE}\x{1F1F2}\x{1F1F8}\x{1F1F9}](?:\x{1F1E9})|[\x{1F1E6}\x{1F1E8}\x{1F1EA}\x{1F1EE}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F8}\x{1F1F9}\x{1F1FB}](?:\x{1F1E8})|[\x{1F1E7}\x{1F1EC}\x{1F1F1}\x{1F1F8}](?:\x{1F1E7})|[\x{1F1E7}\x{1F1E8}\x{1F1EA}\x{1F1EC}\x{1F1F1}\x{1F1F2}\x{1F1F3}\x{1F1F5}\x{1F1F6}\x{1F1F8}\x{1F1F9}\x{1F1FA}\x{1F1FB}\x{1F1FF}](?:\x{1F1E6})|[\x{00A9}\x{00AE}\x{203C}\x{2049}\x{2122}\x{2139}\x{2194}-\x{2199}\x{21A9}-\x{21AA}\x{231A}-\x{231B}\x{2328}\x{23CF}\x{23E9}-\x{23F3}\x{23F8}-\x{23FA}\x{24C2}\x{25AA}-\x{25AB}\x{25B6}\x{25C0}\x{25FB}-\x{25FE}\x{2600}-\x{2604}\x{260E}\x{2611}\x{2614}-\x{2615}\x{2618}\x{261D}\x{2620}\x{2622}-\x{2623}\x{2626}\x{262A}\x{262E}-\x{262F}\x{2638}-\x{263A}\x{2640}\x{2642}\x{2648}-\x{2653}\x{2660}\x{2663}\x{2665}-\x{2666}\x{2668}\x{267B}\x{267E}-\x{267F}\x{2692}-\x{2697}\x{2699}\x{269B}-\x{269C}\x{26A0}-\x{26A1}\x{26AA}-\x{26AB}\x{26B0}-\x{26B1}\x{26BD}-\x{26BE}\x{26C4}-\x{26C5}\x{26C8}\x{26CE}-\x{26CF}\x{26D1}\x{26D3}-\x{26D4}\x{26E9}-\x{26EA}\x{26F0}-\x{26F5}\x{26F7}-\x{26FA}\x{26FD}\x{2702}\x{2705}\x{2708}-\x{270D}\x{270F}\x{2712}\x{2714}\x{2716}\x{271D}\x{2721}\x{2728}\x{2733}-\x{2734}\x{2744}\x{2747}\x{274C}\x{274E}\x{2753}-\x{2755}\x{2757}\x{2763}-\x{2764}\x{2795}-\x{2797}\x{27A1}\x{27B0}\x{27BF}\x{2934}-\x{2935}\x{2B05}-\x{2B07}\x{2B1B}-\x{2B1C}\x{2B50}\x{2B55}\x{3030}\x{303D}\x{3297}\x{3299}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F171}\x{1F17E}-\x{1F17F}\x{1F18E}\x{1F191}-\x{1F19A}\x{1F201}-\x{1F202}\x{1F21A}\x{1F22F}\x{1F232}-\x{1F23A}\x{1F250}-\x{1F251}\x{1F300}-\x{1F321}\x{1F324}-\x{1F393}\x{1F396}-\x{1F397}\x{1F399}-\x{1F39B}\x{1F39E}-\x{1F3F0}\x{1F3F3}-\x{1F3F5}\x{1F3F7}-\x{1F3FA}\x{1F400}-\x{1F4FD}\x{1F4FF}-\x{1F53D}\x{1F549}-\x{1F54E}\x{1F550}-\x{1F567}\x{1F56F}-\x{1F570}\x{1F573}-\x{1F57A}\x{1F587}\x{1F58A}-\x{1F58D}\x{1F590}\x{1F595}-\x{1F596}\x{1F5A4}-\x{1F5A5}\x{1F5A8}\x{1F5B1}-\x{1F5B2}\x{1F5BC}\x{1F5C2}-\x{1F5C4}\x{1F5D1}-\x{1F5D3}\x{1F5DC}-\x{1F5DE}\x{1F5E1}\x{1F5E3}\x{1F5E8}\x{1F5EF}\x{1F5F3}\x{1F5FA}-\x{1F64F}\x{1F680}-\x{1F6C5}\x{1F6CB}-\x{1F6D2}\x{1F6E0}-\x{1F6E5}\x{1F6E9}\x{1F6EB}-\x{1F6EC}\x{1F6F0}\x{1F6F3}-\x{1F6F9}\x{1F910}-\x{1F93A}\x{1F93C}-\x{1F93E}\x{1F940}-\x{1F945}\x{1F947}-\x{1F970}\x{1F973}-\x{1F976}\x{1F97A}\x{1F97C}-\x{1F9A2}\x{1F9B0}-\x{1F9B9}\x{1F9C0}-\x{1F9C2}\x{1F9D0}-\x{1F9FF}]/u', '', $text);
    }


	/**
	 * 確認是否擁有審核推播訊息放行的權限
	 * @param $role: stdClass(login_info)
	 * @return bool
	 */
	function has_check_permission($role) {
		if (isset($role) && $role->role_id == "1")
			return True;
		return False;
	}

	public function send_notification($data) {
		try {
			$httpClient = HttpClient::create();
			$response = $httpClient->request('POST', ENV_NOTIFICATION_REQUEST_URL, [
					   'body' => json_encode($data),
					   'headers' => ['Content-Type' => 'application/json']
				   ]);
			$statusCode = $response->getStatusCode();
			$statusDescription = 'OK.';
		} catch (Exception $e) {
			$statusCode = -1;
			$statusDescription = "無法連線到推播中心";
		}finally {
			return ['code'=>$statusCode, 'msg'=>$statusDescription];
	   }
	}

    /**
     * Notify users interest rate increase.
     * @param $user_ids
     * @param $old_rate
     * @param $new_rate
     * @return void
     */
    public function notify_rate_increased($user_ids, $old_rate, $new_rate, $title=NULL, $content=NULL)
    {
        $this->CI->load->model('log/Log_userlogin_model');
        $devices = $this->CI->Log_userlogin_model->get_filtered_deviceid(
            ['user_ids' => $user_ids, 'android' => TRUE, 'ios' => TRUE], NotificationTargetCategory::Investment
        );
        $old_rate = rtrim(strval($old_rate), '0');
        $old_rate = rtrim($old_rate, '.');
        $result = $this->send_notification([
            'user_id' => SYSTEM_ADMIN_ID,
            'sender_name' => "利率提升系統提醒",
            'target_category' => $this->CI->config->item('notification')['target_category_name'][NotificationTargetCategory::Investment],
            'target_platform' => 'android/ios',
            'tokens' => $devices,
            "notification"=> [
                "title" => $title ?? "優質案件利率提升",
                "body" => $content ?? "優質案件上架，借款人主動提升利率 {$old_rate}% → {$new_rate}%，趕快開啟[普匯投資]搶標！",
            ],
            "data" => [],
            "send_at" => (new DateTime())->format('Y-m-d H:i'),
            "apns" => [
                "payload" => [
                    "category" => "NEW_MESSAGE_CATEGORY"
                ]
            ],
            "status" => NotificationStatus::Accepted,
            "type" => NotificationType::RoutineReminder,
            "dry_run" => 0
        ]);
        if ($result['code'] == HTTP_STATUS_OK || $result['code'] == HTTP_STATUS_CREATED)
        {
            $sent_count = count($devices ,COUNT_RECURSIVE) - count($devices) - array_sum(array_map('count',$devices ));
            if ($sent_count == 0)
                log_message('error', 'No devices to notify rate-increased.');
            else
                log_message('info', "[Push Notification Reserved] {$sent_count} devices will receive rate-increased notification.");
        }
        else
        {
            log_message('error', "Failed to send rate-increased notifications: [{$result['code']}] {$result['msg']}");
        }
    }

    public function cancel_subloan_bidding($target) {
        $title = "【產品轉換下架通知】產品轉換金額不符";
        $content = "親愛的用戶，
您好！您的標的 $target->target_no ，
因您的逾期金額已有部分完成清償，
導致您申請之產品轉換媒合金額與原先不符，系統已將您的案件先行下架。
非常感謝您的申請及對我們的信任，
若有任何問題請不吝連繫客服協助處理，謝謝。";

        $param = array(
            "user_id"	=> $target->user_id,
            "investor"	=> 0,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($target->user_id,$title,nl2br($content),'b03');
        return $rs;
    }

    public function cancel_transfer_bidding($user_id, $target_no='') {
        $title = "【債權轉讓下架通知】出讓債權金額不符";
        $content = "親愛的出讓人，
您好！您投資的標的 $target_no ，
因此案債務人已完成部分清償，
導致您申請出讓之債權媒合金額與原先不符，系統已將您的案件先行下架，
若還有出讓需求，請重新操作一遍，
非常感謝您對我們的信任，
若有任何問題請不吝連繫客服協助處理，謝謝。";

        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> 1,
            "title"		=> $title,
            "content"	=> $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),'b03', FALSE, FALSE, FALSE, TRUE, INVESTOR);
        return $rs;
    }

    public function repay_partial_success($user_id,$investor=0,$target_no='',$amount=0,$remaining_amount=0){
        if($investor==1){
            $title 		= "【標的還款成功】 您投資的標的 $target_no 已回款";
            $content 	= "親愛的投資人，您好！
您投資的標的 $target_no ，已回款，
回款金額為 $amount 元，感謝您。";
            $type = 'i04';
        }else{
            $title 		= "【借款部分清償成功】您的借款已成功部分清償，清償金額 $amount 元";
            $content 	= "親愛的用戶，您好！
您的借款 $target_no 。
是否已逾期：是。
部分清償金額 $amount 元已清償成功。
剩餘需清償金額：$remaining_amount 元
感謝您每一次的信用積累。";
            $type = 'b04';
        }

        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> $investor,
            "title"		=> $title,
            "content"	=> $content,
        );

        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('Sendemail');
        $this->CI->sendemail->user_notification($user_id,$title,nl2br($content),$type, FALSE, FALSE, FALSE, TRUE, $investor);
        return $rs;
	}

    public function send_app_with_data($user_id,$investor,$type,$title,$content,$data=""){
        $param = array(
            "user_id"	=> $user_id,
            "investor"	=> $investor,
            "title"		=> $title,
            "content"	=> $content,
            "type"      => $type,
            "data"      => !empty($data)?json_encode($data):'',
        );
        $rs = $this->CI->user_notification_model->insert($param);
        return $rs;
    }

    public function target_credit_expired($user_id)
    {
        $title = "【借款申請】 您申請的借款核准額度已失效";
        $content = "親愛的用戶，您好！
非常抱歉通知您，由於您的核准額度有效期已過，系統已自動下架。
建議您可以重新申請並更新近一個月徵信資料，系統將會在核准後自動上架！";

        $param = array(
            "user_id" => $user_id,
            "investor" => USER_BORROWER,
            "title" => $title,
            "content" => $content,
        );
        $rs = $this->CI->user_notification_model->insert($param);
        $this->CI->load->library('sendemail');
        $this->CI->sendemail->user_notification($user_id, $title, nl2br($content), 'b03');
        return $rs;
    }
}
