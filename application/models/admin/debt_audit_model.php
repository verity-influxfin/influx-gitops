<?php

class debt_audit_model extends MY_Model
{
	public $_table = 'debt_audit';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}

	public $status_list   = array(
		0 =>	"觀察",
		1 =>	"關注",
		2 =>	"次級",
		3 =>	"不良",
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
