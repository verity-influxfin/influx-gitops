<?php

class Prepayment_model extends MY_Model
{
	public $_table = 'prepayment';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );

	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] 	= time();
        $data['created_ip'] 	= get_ip();
        return $data;
    }

}
