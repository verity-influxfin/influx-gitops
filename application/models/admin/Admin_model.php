<?php

class Admin_model extends MY_Model
{
	public $_table = 'admins';
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}
}
