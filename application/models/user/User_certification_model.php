<?php

class User_certification_model extends MY_Model
{
	public $_table = 'user_certification';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $status_list   	= array(
		0 => "待驗證" ,
		1 => "驗證成功" ,
		2 => "驗證失敗" ,
		3 => "待人工審核" ,
		4 => "未完成填寫" ,
	);
	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"出借端",
	);
	public $certificationContentKeyMapping = [
		'id_number' => 1,
		'bank_account' => 3,
		'address' => 1,
	];
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] 	= $data['updated_at'] = time();
		if(!isset($data['expire_time'])){
			$data['expire_time'] 	= strtotime("+6 months", $data['created_at']);
		}
        $data['created_ip'] 	= $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

	public function get_users_with_same_value($userId, $key, $values)
	{
		if (
			!$values
			|| !isset($this->certificationContentKeyMapping[$key])
		) {
			return [];
		}

		$this->db->select('users.*')
			->from('p2p_user.user_certification as cert')
			->join('p2p_user.users as users', 'users.id = cert.user_id')
			->where('user_id !=', $userId);

		$certificationId = $this->certificationContentKeyMapping[$key];
		$this->db->where('cert.certification_id', $certificationId);

		$numbersLike = count($values);
		$this->db->group_start();
		for ($i = 0; $i < $numbersLike; $i++) {
			$value = $values[$i];
			if (preg_match('/[^A-Za-z0-9]/', $value)) {
				$value = json_encode($value);
				$value = rtrim($value, '"');
				$value = ltrim($value, '"');
			}

			$value = "{$key}\":\"{$value}\"";

			if ($i == 0) $this->db->like("content", $value);
			else $this->db->or_like("content", $value);
		}
		$this->db->group_end();

		$this->db->group_by('cert.user_id');

		$query = $this->db->get();

		return $query->result();
	}
}