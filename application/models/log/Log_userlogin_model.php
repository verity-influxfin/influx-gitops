<?php

class Log_userlogin_model extends MY_Model
{
	public $_table = 'user_login_log';
	public $before_create = array( 'before_data_c' );
	public $status_list   = array(
		0 =>	"失敗",
		1 =>	"成功"
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
		
		$this->load->library('user_agent');
		if ($this->agent->is_browser()){
			$agent = $this->agent->browser().' '.$this->agent->version();
		}elseif ($this->agent->is_robot()){
			$agent = $this->agent->robot();
		}elseif ($this->agent->is_mobile()){
			$agent = $this->agent->mobile();
		}else{
			$agent = 'Unidentified User Agent';
		}
		$device_id = isset($this->agent->device_id)?$this->agent->device_id:null;
		$data['client'] = json_encode([
			'agent'		=> $this->agent->agent_string(),
			'platform'	=> $this->agent->platform(),
            'device_id'	=> $device_id
		]);
		
		return $data;
    } 	

    public function get_same_ip_users($user_id, $time = 0)
	{
		$this->db->select('created_ip')
				 ->from ('p2p_log.user_login_log ')
			     ->where('user_id', $user_id);

		if ($time > 0) {
			$this->db->where('login.created_at >', $time);
		}

		$subQuery = $this->db->get_compiled_select();

		$this->db->select('users.*, login.created_ip as login_ip')
			     ->from('p2p_log.user_login_log as login')
				 ->join('p2p_user.users as users', 'users.id = login.user_id')
		         ->where('login.user_id !=', $user_id)
		         ->where('login.user_id !=', 0);

		if ($time > 0) {
			$this->db->where('login.created_at >', $time);
		}

		$this->db->where("login.created_ip IN ($subQuery)", null, false)
		 		 ->group_by('users.id');
		$query = $this->db->get();

		return $query->result();
	}

	public function get_same_device_id_users($userId, $deviceIds)
	{
		if ($userId <= 0 || !$deviceIds) {
			return [];
		}

		$this->db->select('users.*')
				 ->from('p2p_log.user_login_log as login')
				 ->join('p2p_user.users as users', 'users.id = login.user_id')
				 ->where('login.user_id !=', $userId);

		$this->db->group_start();
		$length = count($deviceIds);
		for ($i = 0; $i < $length; $i++) {
			$deviceId = $deviceIds[$i];
			$value = "\"device_id\":\"{$deviceId}";
			if ($i == 0) $this->db->like("client", $value);
			else $this->db->or_like("client", $value);
		}
		$this->db->group_end();

		$this->db->group_by('login.user_id');

		$query = $this->db->get();
		return $query->result();
	}
}
