<?php

class Certification_model extends MY_Model
{
	public $_table = 'certifications';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"已停權",
		1 =>	"正常"
	);
	
	public $block_status_list   = array(
		0 =>	"正常",
		1 =>	"人工停權",
		1 =>	"系統停權",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('platform',TRUE);
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
