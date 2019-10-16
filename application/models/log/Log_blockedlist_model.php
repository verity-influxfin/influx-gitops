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

	public function findByUserId($userId)
	{
		$this->db->select('
					 blocked_list.id,
					 users.id as user_id,
					 users.name as user_name,
					 blocked_list.admin_id,
					 admins.name as admin_name,
					 users.phone,
					 users.sex,
					 users.email,
					 users.status,
					 users.block_status,
					 users.investor_status,
					 blocked_list.reason,
					 blocked_list.created_at,
					 blocked_list.updated_at
				 ')
				 ->from('p2p_user.users as users')
				 ->join('p2p_log.' . $this->_table . ' as blocked_list', 'users.id = blocked_list.blocked_user_id', 'left')
				 ->join('p2p_admin.admins as admins', 'blocked_list.admin_id IS NOT NULL AND admins.id = blocked_list.admin_id', 'left')
				 ->where('users.id', $userId);
		$query = $this->db->get();
		return $query->row();
	}

	public function getBlockedLogs()
	{
		$this->db->select('
					 blocked_list.id,
					 users.id as user_id,
					 users.name as user_name,
					 blocked_list.admin_id,
					 admins.name as admin_name,
					 users.phone,
					 users.sex,
					 users.email,
					 users.status,
					 users.block_status,
					 users.investor_status,
					 blocked_list.reason,
					 blocked_list.created_at,
					 blocked_list.updated_at
				 ')
				 ->from('p2p_user.users as users')
				 ->join('p2p_log.' . $this->_table . ' as blocked_list', 'users.id = blocked_list.blocked_user_id', 'left')
				 ->join('p2p_admin.admins as admins', 'blocked_list.admin_id IS NOT NULL AND admins.id = blocked_list.admin_id', 'left')
				 ->where_in('users.block_status', [1,2,3]);
		$query = $this->db->get();
		return $query->result();
	}
}
