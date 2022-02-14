<?php

class sale_dashboard_model extends MY_Model
{
	public $_table = 'sale_dashboard';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
		$data['password'] 	= sha1($data['password']);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
		if(isset($data['password']) && !empty($data['password'])){
			$data['password'] 	= sha1($data['password']);
		}
		
		if(isset($data['transaction_password']) && !empty($data['transaction_password'])){
			$data['transaction_password'] 	= sha1($data['transaction_password']);
		}
		
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }
}
