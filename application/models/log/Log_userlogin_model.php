<?php

class Log_userlogin_model extends MY_Model
{
	public $_table = 'user_login_log';
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
}
