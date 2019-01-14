<?php

class Risk_model extends MY_Model
{
	public $_table = 'risk';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );

	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('loan',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] 	= $data['updated_at'] = time();
        $data['created_ip'] 	= $data['updated_ip'] = get_ip();
        return $data;
    }

}
