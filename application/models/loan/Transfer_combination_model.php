<?php

class Transfer_combination_model extends MY_Model
{
	public $_table = 'transfer_combination';
	public $before_create = array( 'before_data_c' );

	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('loan',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['combination_no'] = 'PKG'.round(microtime(true) * 1000).rand(0, 9).rand(0, 9).rand(0, 9);
        $data['created_at'] 	= time();
        $data['created_ip'] 	= get_ip();
        return $data;
    }
}
