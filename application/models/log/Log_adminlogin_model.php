<?php

class Log_adminlogin_model extends MY_Model
{
	public $_table = 'admin_login_log';
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
}
