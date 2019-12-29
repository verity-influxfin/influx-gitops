<?php

class User_model extends MY_Model
{
	public $_table = 'users';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	'已停權',
		1 =>	'正常'
	);
	
	public $block_status_list   = array(
		0 =>	'正常',
		1 =>	'人工停權',
		2 =>	'系統暫時鎖定',
        3 =>    '系統鎖定',
	);
	
	public $token_fields  = array(
		'id',
		'name',
		'picture',
		'nickname',
		'sex',
		'id_number',
		'phone',
		'my_promote_code',
		'created_at',
		'updated_at'
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
		$data['password'] 	= sha1($data['password']);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
		if(isset($data['password']) && !empty($data['password'])){
			$data['password'] 	= sha1($data['password']);
		}
		
		if(isset($data['transaction_password']) && !empty($data['transaction_password'])){
			$data['transaction_password'] 	= sha1($data['transaction_password']);
		}
		
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

	public function getStudents($filters, $offset = 0, $limit = 20)
	{
		$this->db->select('users.*')
				 ->from ('p2p_user.users')
				 ->join('p2p_user.user_meta as meta', 'users.id = meta.user_id');

		foreach ($filters as $filter) {
			if ($filter[0] == 'status') {
				$this->db->where('users.status ' . $filter[1], $filter[2]);
			}
			if ($filter[0] == 'created_at') {
				$this->db->where('users.created_at ' . $filter[1], $filter[2]);
			}
			if ($filter[0] == 'meta_key') {
				$this->db->where('meta.meta_key ' . $filter[1], $filter[2]);
			}
			if ($filter[0] == 'promote_code') {
				$this->db->where('users.promote_code ' . $filter[1], $filter[2]);
			}
		}

		$this->db->limit($limit, $offset);

		$query = $this->db->get();

		return $query->result();
	}
}
