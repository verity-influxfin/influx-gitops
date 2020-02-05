<?php

class User_bankaccount_model extends MY_Model
{
	public $_table = 'user_bankaccount';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $fields			= array("user_id","bank_code","bank_account","front_image","back_image");
	public $sys_check_list = array(
		0 =>	"未驗證",
		20 =>	"交易成功(系統自動)",
		21 =>	"需轉人工",
	);

	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"投資端",
        2 =>	"法人借款端",
        3 =>	"法人投資端",
	);
	
	public $verify_list  	= array(
		0 =>	"未驗證",
		1 =>	"驗證成功",
		2 =>	"待驗證",
		3 =>	"已發送",
		4 =>	"驗證失敗",
	);

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
