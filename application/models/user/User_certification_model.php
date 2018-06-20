<?php

class User_certification_model extends MY_Model
{
	public $_table = 'user_certification';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $status_list   	= array(
		0 => "待驗證" ,
		1 => "驗證成功" ,
		2 => "驗證失敗" ,
		3 => "待人工審核" ,
	);
	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"投資端",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] 	= $data['updated_at'] = time();
		if(!isset($data['expire_time'])){
			$data['expire_time'] 	= strtotime("+6 months", $data['created_at']);
		}
        $data['created_ip'] 	= $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }
}
