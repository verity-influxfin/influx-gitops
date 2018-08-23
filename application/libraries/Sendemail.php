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
			'smtp_host'		=> 'ssl://smtp.gmail.com',
			'smtp_port'		=> '465',
			'smtp_timeout'	=> '30',
			'smtp_user'		=> GMAIL_SMTP_ACCOUNT,
			'smtp_pass'		=> GMAIL_SMTP_PASSWORD,
			'charset'		=> 'utf-8',
			'newline'		=> "\r\n",
			'mailtype'		=> 'html',
			'wordwrap'		=>  true,
		);
    }

	public function send_verify_school($certification_id,$email=""){
		if($certification_id && !empty($email)){
			$type	 = 'school';
			$code	 = md5($email.time());
			$link    = BORROW_URL."/verifyemail?type=$type&email=".urlencode($email)."&code=".$code;
			$content = $this->CI->parser->parse('email/verify_email', array("link" => $link),TRUE);
			$subject = "手機ATM - 學校電子郵件認證";
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
			$type	 = 'email';
			$code	 = md5($email.time());
			if($investor){
				$link    = LENDING_URL."/verifyemail?type=$type&email=".urlencode($email)."&code=".$code;
			}else{
				$link    = BORROW_URL."/verifyemail?type=$type&email=".urlencode($email)."&code=".$code;
			}
			
			$content = $this->CI->parser->parse('email/verify_email', array("link" => $link),TRUE);
			$subject = "手機ATM - 電子郵件認證";
			$param = array(
				"certification_id"	=> $certification_id,
				"type" 				=> $type,
				"email"				=> $email,
				"code"				=> $code,
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
				$this->CI->email_verify_model->update($rs->id,array("status"=>1));
				$this->CI->load->library('Certification_lib');
				$this->CI->certification_lib->set_success($rs->certification_id);
				return true;
			}
		}
		return false;
	}
	
	public function user_notification($user_id=0,$title="",$content=""){
		if($user_id){
			$user_info 		= $this->CI->user_model->get($user_id);
			if($user_info && $user_info->email){
				$content 	= $this->CI->parser->parse('email/user_notification', array("title" => $title , "content"=> $content ),TRUE);
				return $this->send($user_info->email,$title,$content);
			}
		}
		return false;
	}
	
	public function email_notification($email="",$title="",$content=""){
		if($email){
			$content 	= $this->CI->parser->parse('email/user_notification', array("title" => $title , "content"=> $content ),TRUE);
			return $this->send($email,$title,$content);
		}
		return false;
	}
	
	public function admin_notification($title="",$content=""){
		$admin_email 	= $this->CI->config->item('admin_email');
		$content 		= $this->CI->parser->parse('email/admin_notification', array("title" => $title , "content"=> $content ),TRUE);
		return $this->send($admin_email,$title,$content);
	}
	
    private function send($email,$subject,$content)
    {
		$this->CI->email->initialize($this->config);
		$this->CI->email->clear();
		$this->CI->email->to($email);
		$this->CI->email->from(GMAIL_SMTP_ACCOUNT,GMAIL_SMTP_NAME);
		$this->CI->email->subject($subject);
		$this->CI->email->message($content);
		
		$rs = $this->CI->email->send();
		if($rs){
			return true;
		}else{
			return false;
		}
    }
	
}