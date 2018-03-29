<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sendemail
{
	
	private $config = array();
	
    function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->library('email');
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

	
    public function send_test($email,$subject,$content)
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