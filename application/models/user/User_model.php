<?php

class User_model extends MY_Model
{
	public $_table = 'users';
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
}
