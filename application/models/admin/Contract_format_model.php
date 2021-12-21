<?php

class Contract_format_model extends MY_Model
{
	public $_table = 'contract_format';
	public $before_create = array( 'before_data_c' );
    public $before_update = array( 'before_data_u' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }
    protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }
}