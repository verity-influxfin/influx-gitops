<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sendemail
{

	private $config = array();

    function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('user/email_verify_model');
		$this->CI->load->library('email');
		$this->CI->load->library('parser');
        $this->config = array(
			'protocol'		=> 'smtp',
			'smtp_host'		=> 'ssl://email-smtp.us-west-2.amazonaws.com',
			'smtp_port'		=> '465',
			'smtp_timeout'	=> '30',
			'smtp_user'		=> SES_SMTP_ACCOUNT,
			'smtp_pass'		=> SES_SMTP_PASSWORD,
			'charset'		=> 'utf-8',
			'newline'		=> "\r\n",
			'mailtype'		=> 'html',
			'wordwrap'		=>  true,
		);
    }

	public function send_verify_school($certification_id,$email=""){
		if($certification_id && !empty($email)){
            $mail_event = $this->CI->config->item('mail_event');
			$type	 = 'school';
			$code	 = md5($email.time());
			$link    = BORROW_URL."/verifyemail?type=$type&email=".urlencode(base64_encode($email))."&code=".$code;
			$content = $this->CI->parser->parse('email/verify_email', array("link" => $link, "type"=> 'b01', "mail_event"=> $mail_event),TRUE);
			$subject = "普匯inFlux - 學校電子郵件認證";
			$param = array(
				"certification_id"	=> $certification_id,
				"type" 			=> $type,
				"email"			=> $email,
				"code"			=> $code,
			);
			$rs = $this->CI->email_verify_model->insert($param);
			if($rs){
				return $this->send($email,$subject,$content);
			}
		}
		return false;
	}

	public function send_verify_email($certification_id,$email="",$investor=0){
		if($certification_id && !empty($email)){
		    $mail_event = $this->CI->config->item('mail_event');
			$type	 = 'email';
			$code	 = md5($email.time());
			if($investor){
				$link    = LENDING_URL."/verifyemail?type=$type&email=".urlencode(base64_encode($email))."&code=".$code;
				$show_type = 'i01';
			}else{
				$link    = BORROW_URL."/verifyemail?type=$type&email=".urlencode(base64_encode($email))."&code=".$code;
                $show_type = 'b01';
			}

			$content = $this->CI->parser->parse('email/verify_email', array('link' => $link, "type"=> $show_type , "mail_event"=> $mail_event),TRUE);
			$subject = '普匯inFlux - 電子郵件認證';
			$param = array(
				'certification_id'	=> $certification_id,
				'type' 				=> $type,
				'email'				=> $email,
				'code'				=> $code,
			);
			$rs = $this->CI->email_verify_model->insert($param);
			if($rs){
				return $this->send($email,$subject,$content);
			}
		}
		return false;
	}

	public function verify_code($type="",$email="",$code=""){
		if(!empty($type) && !empty($email) && !empty($code)){

			$param = array(
				"type"		=> $type,
				"email"		=> $email,
				"status"	=> 0,
				"code"		=> $code
			);

			$rs = $this->CI->email_verify_model->get_by($param);
			if($rs){
				$this->CI->load->model('user/user_certification_model');
				$this->CI->email_verify_model->update($rs->id,array("status"=>1));
				$certification = $this->CI->user_certification_model->get($rs->certification_id);
				if($certification && $certification->status==0){
					$this->CI->load->library('Certification_lib');
                    // to do : 學生認證轉待驗證(未來加入sip後要改掉)
                    if($certification->certification_id == 2){
                        $this->CI->user_certification_model->update($rs->certification_id,['status'=>3]);
                    }else{
                        $this->CI->certification_lib->set_success($rs->certification_id);
                    }
				}
				return true;
			}
		}
		return false;
	}

	public function user_notification($user_id=0,$title="",$content="",$type=false,$attach=false,$replay_to=false,$replay_to_name=false){
		if($user_id){
			$user_info 		= $this->CI->user_model->get($user_id);
			if($user_info && $user_info->email){
			    $mail_event = $this->CI->config->item('mail_event');
				$content 	= $this->CI->parser->parse('email/user_notification', array("title" => $title , "content"=> $content , "type"=> $type , "mail_event"=> $mail_event),TRUE);
				if($attach){
                    $this->CI->email->initialize($this->config);
                    $this->CI->email->clear(TRUE);
                    $this->CI->email->to($user_info->email);
                    $this->CI->email->from(GMAIL_SMTP_ACCOUNT,GMAIL_SMTP_NAME);
                    $this->CI->email->subject($title);
                    $this->CI->email->message($content);
                    foreach($attach as $key => $value) {
                        $this->CI->email->attach($value,"",$key);
                    }
                    $rs = $this->CI->email->send();
                }
				else{
                    $rs = $this->send($user_info->email,$title,$content,$replay_to,$replay_to_name);
                }
                if($rs){
                    $this->CI->email->clear(true);
                    return true;
                }else{
                    return false;
                }
			}
		}
		return false;
	}

	public function email_notification($email="",$title="",$content=""){
		if($email){
		    $mail_event = $this->CI->config->item('mail_event');
			$content 	= $this->CI->parser->parse('email/user_notification', array("title" => $title , "content"=> $content , "type"=> 'b08', "mail_event"=> $mail_event),TRUE);
			return $this->send($email,$title,$content);
		}
		return false;
	}

	public function admin_notification($title="",$content=""){
		$admin_email 	= $this->CI->config->item('admin_email');
		$mail_event = $this->CI->config->item('mail_event');
		$content 		= $this->CI->parser->parse('email/admin_notification', array("title" => $title , "content"=> $content , "url"=> base_url(), "type"=> 'b02', "mail_event"=> $mail_event),TRUE);
		return $this->send($admin_email,$title,$content);
	}


	public function email_file_estatement($email="",$title="",$content="",$estatement="",$estatement_detail="",$investor_status=""){
		if($email){
		    $mail_event = $this->CI->config->item('mail_event');
		    $type = $investor_status==1?'i':'b';
			$content 	= $this->CI->parser->parse('email/user_notification', array("title" => $title , "content"=> $content,"investor_status"=>$investor_status, "type"=> $type.'08', "mail_event"=> $mail_event),TRUE);
			$this->CI->email->initialize($this->config);
			$this->CI->email->clear(TRUE);
			$this->CI->email->to($email);
			$this->CI->email->from(GMAIL_SMTP_ACCOUNT,GMAIL_SMTP_NAME);
			$this->CI->email->subject($title);
			$this->CI->email->message($content);
			if($estatement!=""){
				$this->CI->email->attach($estatement,"","estatement.pdf");
			}
			if($estatement_detail!=""){
				$this->CI->email->attach($estatement_detail,"","estatement_detail.pdf");
			}

			$rs = $this->CI->email->send();
			if($rs){
				return true;
			}else{
				return false;
			}
		}
	}

    public function send($email,$subject,$content,$reply_to=false,$reply_to_name='')
    {
		$this->CI->email->initialize($this->config);
		$this->CI->email->clear();
		$this->CI->email->to($email);
		$this->CI->email->from(GMAIL_SMTP_ACCOUNT,GMAIL_SMTP_NAME);
		$this->CI->email->subject($subject);
		$this->CI->email->message($content);

        $reply_to?$this->CI->email->reply_to($reply_to,$reply_to_name):'';

		$rs = $this->CI->email->send();

		if($rs){
			return true;
		}else{
			return false;
		}
    }

    public function lending_success($user_id, $investor, $target_no, $amount, $bankaccount="", $borrower_user_id=0) {
		if($investor == 1) {
			$subject = "【投資標的】您的資金已放款成功";
			$title = "【競標成功】";
			$content = "親愛的投資人，恭喜您標得 " . $target_no . " 新台幣 " . number_format($amount) . " 元的債權，並成功放款至會員ID:" . $borrower_user_id . "
				更多投資標的，盡在普匯APP";
			$type = 'i04';
		} else {
			$bankaccount = substr($bankaccount, -4, 4);
			$title 		= "【借款放款成功】 您的借款 $target_no 已發放成功";
			$content 	= "親愛的用戶，您好！
您的借款 $target_no ，
借款金額 $amount 元已發放至您的綁定金融卡賬戶尾號 $bankaccount 內，
請您妥善安排用款。

敬告用戶，本公司不會以短信或電話等任何形式告知您其他非APP內的還款專屬帳號。";
			$type = 'b04';
		}
		$user_info 		= $this->CI->user_model->get($user_id);
		if(isset($user_info) && $user_info->email) {
			$mail_event = $this->CI->config->item('mail_event');
			$content = $this->CI->parser->parse('email/user_notification', array("title" => $title, "content" => nl2br($content), "type" => $type, "mail_event" => $mail_event), TRUE);
			$this->send($user_info->email,isset($subject)?$subject:$title, $content);
		}
	}


	/**
	 * 當貸款人提高利率時，寄送通知信件給所有已得標的投資人。
	 * @param investment_model $investment: 投資物件
	 * @param float $old_rate: 舊利率
	 * @param float $new_rate: 新利率
	 * @return false
	 */
	public function change_interest_rate($investment, $old_rate, $new_rate) {
		// 如果該筆投資不是 0: 待付款 或 1: 待結標(款項已移至待交易) 就不寄信了
		if(!isset($investment) || !in_array($investment->status, [0, 1]))
			return false;

		$subject = "【投資標的】您的投資利率已提高";
		$title = "【好康標的】";
		$content = "親愛的投資人請注意：
			您的投資標的有一項重大變更！
			您於" .  date("m月d日",strtotime($investment->tx_datetime)) . "投資債權，剛剛自主提升利率由
			".$old_rate."%-->".$new_rate."%
			同樣的項目，更高的潛在收益！
			請登錄普匯APP 下標搶佔先手";
		$type = 'i09';

		$user_info 		= $this->CI->user_model->get($investment->user_id);
		if(isset($user_info)) {
			$this->CI->load->library('certification_lib');
			$this->CI->load->model('user/user_certification_model');

			// 找出投資人的 certification
			$certification_info = $this->CI->certification_lib->get_last_status($user_info->id,1,$user_info->company_status);
			foreach($certification_info as $value) {
				if($value['alias']=='email')
					$certification_id = $value['certification_id'];
			}
			// 依照 email 的 certification id 找到 user certification 才能找到對應投資人的 email
			$info = $this->CI->user_certification_model->get($certification_id);
			$user_certification = json_decode($info->content, true);

			$mail_event = $this->CI->config->item('mail_event');
			$content = $this->CI->parser->parse('email/user_notification', array("title" => $title, "content" => nl2br($content), "type" => $type, "mail_event" => $mail_event, "investor_status" => 1), TRUE);
			$this->send($user_certification['email'],isset($subject)?$subject:$title, $content);
			return true;
		}
		return false;
	}

    public function EDM($mail, $title = "", $content = "", $EDM, $EDM_href)
    {
        if ($mail) {
            $content = $this->CI->parser->parse('email/sales_mail', array("title" => $title, "content" => $content, "EDM" => $EDM, "EDM_href" => $EDM_href), TRUE);
            return $this->send($mail, $title, $content);
        }
        return false;
    }

}
