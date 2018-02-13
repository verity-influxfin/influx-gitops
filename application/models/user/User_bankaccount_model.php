<?php

class User_bankaccount_model extends MY_Model
{
	public $_table = 'user_bankaccount';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $fields			= array("user_id","bank_code","bank_account");
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }
}
