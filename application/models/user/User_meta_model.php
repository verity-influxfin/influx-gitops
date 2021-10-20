<?php

class User_meta_model extends MY_Model
{
	public $_table = 'user_meta';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
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

    public function get_users_with_same_emergency_contact($userId)
	{
		$this->db->select('meta_value')
			     ->from('p2p_user.user_meta')
			     ->where('user_id', $userId)
			     ->where('meta_key', 'emergency_phone');

		$subQuery = $this->db->get_compiled_select();

		$this->db->select('users.*')
			     ->from('p2p_user.user_meta as meta')
				 ->join('p2p_user.users as users', 'meta.user_id = users.id')
				 ->where('meta.user_id !=', $userId)
				 ->where("meta.meta_value IN ($subQuery)");

		$query = $this->db->get();
		return $query->result();
	}

	public function get_emergency_contact_who_is_member($userId)
	{
		$this->db->select('users.*')
				 ->from('p2p_user.user_meta as meta')
				 ->join('p2p_user.users as users', 'users.phone = meta.meta_value')
			     ->where('meta.user_id', $userId)
				 ->where('meta.meta_key', 'emergency_phone');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_meta_value_by_user_id_and_meta_key($userId='', $metaKey=[]){
		if($userId && $metaKey){
			$this->db->select('meta_value')
				     ->from('p2p_user.user_meta')
				     ->where('user_id', $userId)
				     ->where_in('meta_key', $metaKey);
		 	$query = $this->db->get();
		}
	 	return $query->result();
	}
}
