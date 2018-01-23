<?php

class Admin_model extends MY_Model
{
	public $_table = 'admins';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}
	
	protected function before_data_c($data)
    {
		$data["password"] 	= sha1($data["password"]);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
		if(isset($data["password"])){
			$data["password"] 	= sha1($data["password"]);
		}
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }
	
	public function get_name_list(){
		$data 	= array();
		$list 	= $this->get_all();
		if($list){
			foreach($list as $key => $value){
				$data[$value->id] = $value->name;
			}
		}
		return $data;
	}
}