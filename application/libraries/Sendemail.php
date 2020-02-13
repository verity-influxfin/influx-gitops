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
			$link    = BORROW_URL."/verifyemail?type=$type&email=".urlencode($email)."&code=".$code;
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
					$this->CI->certification_lib->set_success($rs->certification_id);
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
	
    private function send($email,$subject,$content,$reply_to=false,$reply_to_name='')
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

    public function EDM($mail, $title = "", $content = "", $EDM, $url)
    {
        if ($mail) {
            $content = $this->CI->parser->parse('email/sales_mail', array("title" => $title, "content" => $content, "EDM" => $EDM, "url" => $url), TRUE);
            return $this->send($mail, $title, $content);
        }
        return false;
    }
}