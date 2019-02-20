<?php

class Article_model extends MY_Model
{
	public $_table = 'articles';
	public $before_create = [ 'before_data_c' ];
	public $before_update = [ 'before_data_u' ];
	public $status_list   = [
		0 => '未發布',
		1 => '已發布'
	];

	public $type_list   = [
		1 => '最新活動',
		2 => '最新消息',
	];
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}
	
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