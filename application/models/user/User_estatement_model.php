<?php

class User_estatement_model extends MY_Model
{
	public $_table = 'user_estatement';
	public $before_create 	= array( 'before_data_c' );
	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"出借端",
	);
	public $status_list  	= array(
		0 =>	"未發送",
		1 =>	"已發送",
		2 =>	"作廢",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

    public function query_table(){
        return $this->db->from($this->_table);
    }
}
