<?php

class Log_script_model extends MY_Model
{
	public $_table 			= 'script_log';
	public $before_create 	= array( 'before_data_c' );
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        return $data;
    } 	
}
