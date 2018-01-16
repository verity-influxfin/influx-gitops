<?php

class Log_script_model extends MY_Model
{
	public $_table = 'script_log';
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
}
