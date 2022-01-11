<?php

class Group_model extends MY_Model
{
	public $_table = 'groups';
	public $before_create = array('before_data_c');
	public $before_update = array('before_data_u');

	//角色名稱
	public $position_list = array(
		1 => '執行長',
		2 => '主管',
		3 => '經辦',
	);

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin', TRUE);
	}

	protected function before_data_c($data)
	{
		$data['creator_id'] = $this->login_info->id;
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
