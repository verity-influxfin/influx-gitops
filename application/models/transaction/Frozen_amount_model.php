<?php

class Frozen_amount_model extends MY_Model
{
	public $_table = 'frozen_amount';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   	= array(
		0 =>	"已解除",
		1 =>	"凍結中",
	);
	public $type_list  	= array(
		1 =>	"投標",
		2 =>	"債轉投標",
		3 =>	"提領",
		4 =>	"其他",
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
