<?php

class Beha_user_model extends MY_Model
{
	public $_table = 'beh_user';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('behavion',TRUE);

 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
		return $data;
    } 	
	
}
