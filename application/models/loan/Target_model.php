<?php

class Target_model extends MY_Model
{
	public $_table = 'targets';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"待核可",
		1 =>	"待簽約",
		2 =>	"待驗證",
		3 =>	"待出借",
		4 =>	"待放款（結標）",
		5 =>	"還款中",
		8 =>	"已取消",
		9 =>	"申請失敗",
		10 =>	"已結案",
	);
	
	public $delay_list   = array(
		0 =>	"無",
		1 =>	"逾期中",
	);
	
	public $simple_fields  = array(
		"id",
		"target_no",
		"product_id",
		"user_id",
		"amount",
		"loan_amount",
		"platform_fee",
		"interest_rate",
		"instalment",
		"repayment",
		"delay",
		"delay_days",
		"status",
		"sub_status",
		"created_at",
	);
	
	public $detail_fields  = array(
		"id",
		"target_no",
		"product_id",
		"user_id",
		"amount",
		"loan_amount",
		"platform_fee",
		"interest_rate",
		"instalment",
		"repayment",
		"remark",
		"delay",
		"delay_days",
		"status",
		"sub_status",
		"created_at",
		"updated_at"
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('loan',TRUE);
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
