<?php

class Virtual_account_model extends MY_Model
{
	public $_table = 'virtual_account';
	public $before_create = array( 'before_data_c' );
//	public $before_update = array( 'before_data_u' );
	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"出借端",
	);
	
	public $status_list  	= array(
		0 =>	"凍結中",
		1 =>	"正常",
		2 =>	"使用中",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

}