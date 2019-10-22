<?php

class Investment_model extends MY_Model
{
	public $_table = 'investments';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"待付款",
		1 =>	"待結標(款項已移至待交易)",
		2 =>	"待放款(已結標)",
		3 =>	"持有中",
		8 =>	"已取消",
		9 =>	"已流標",
		10 =>	"已結案",
	);

	public $transfer_status_list   = array(
		0 =>	"",
		1 =>	"債轉申請中",
		2 =>	"已轉出",
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
