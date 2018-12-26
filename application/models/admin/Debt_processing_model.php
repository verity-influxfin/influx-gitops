<?php

class debt_processing_model extends MY_Model
{
	public $_table = 'debt_processing';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}

	public $push_status_list   = array(
		0 =>	"待催收",
		1 =>	"已催收",
	);
	public $push_by_status_list   = array(
		0 =>	"簡訊催收",
		1 =>	"電話催收",
		2 =>	"上門催收",
		3 =>	"信件催收",
		4 =>	"律師函",
		5 =>	"其它",
	);
	public $result_status_list   = array(
		0 =>	"可連絡",
		1 =>	"聯絡失敗",
		2 =>	"失聯",
	);

	
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
