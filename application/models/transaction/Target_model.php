<?php

class Target_model extends MY_Model
{
	public $_table = 'targets';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"待核可",
		1 =>	"待簽約",
		2 =>	"待借款",
		3 =>	"待放款（結標）",
		4 =>	"還款中",
		8 =>	"已取消",
		9 =>	"申請失敗",
		10 =>	"已結案",
	);
	public $simple_fields  = array(
		"id",
		"target_no",
		"product_id",
		"user_id",
		"amount",
		"loan_amount",
		"interest_rate",
		"instalment",
		"delay",
		"status",
		"created_at",
	);
	
	public $detail_fields  = array(
		"id",
		"target_no",
		"product_id",
		"user_id",
		"amount",
		"loan_amount",
		"interest_rate",
		"total_interest",
		"instalment",
		"repayment",
		"bank_code",
		"branch_code",
		"bank_account",
		"virtual_account",
		"contract",
		"remark",
		"delay",
		"status",
		"created_at",
		"updated_at"
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
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
