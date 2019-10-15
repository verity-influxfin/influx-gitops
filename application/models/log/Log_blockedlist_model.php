<?php

class Log_blockedlist_model extends MY_Model
{
	public $_table = 'blocked_list';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}

	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['updated_at'] = time();
        return $data;
    }

}
