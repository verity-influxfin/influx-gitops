<?php

class Partner_type_model extends MY_Model
{
	public $_table = 'partner_type';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"刪除",
		1 =>	"正常"
	);
	
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

	public function get_name_list(){
		$data 	= array();
		$list 	= $this->get_all();
		if($list){
			foreach($list as $key => $value){
				if($value->status ==1 )
					$data[$value->id] = $value->title;
			}
		}
		return $data;
	}
}