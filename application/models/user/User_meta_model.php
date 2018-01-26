<?php

class User_meta_model extends MY_Model
{
	public $_table = 'users_meta';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        return $data;
    }
	
}
