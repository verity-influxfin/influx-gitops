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
			'device_id'	=> $device_id,
			'os'		=> isset($data['os'])?$data['os']:""
		]);
		// 由於 os 是要存在 client 欄位內，所以需要 unset，否則會造成多一個欄位插入
		unset($data['os']);

		return $data;
    }

	public function getCurrentInstance($data)
	{
		$convertedData = $this->before_data_c($data);
		$convertedData["client"] = json_decode($convertedData["client"]);
		return $convertedData;
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
			$value = "\"device_id\":\"{$deviceId}\"";
			if ($i == 0) $this->db->like("client", $value);
			else $this->db->or_like("client", $value);
		}
		$this->db->group_end();

		$this->db->group_by('login.user_id');

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * 把新增通知的表單資料進行過濾，最後返回 device tokens
	 * @param $filterRole
	 * @param $targetCategory: 目標端種類(投資端, 借款端)
	 * @return array
	 * @author wayne
	 */
	public function get_filtered_deviceid($filterRole, $targetCategory) {

		// 如果要指定某些使用者時則不套用其他過濾條件
		if(!empty($filterRole['user_ids'])) {
			$userData = explode(",", $filterRole['user_ids']);

			foreach($userData as $users) {
				$userRange = explode("-", $users);
				if(isset($userRange) && is_array($userRange) && count($userRange) == 2) {
					$this->_database->or_where('(id >=', $userRange[0], FALSE);
					$this->_database->where('id <=', $userRange[1].")", FALSE);
				}else if(is_numeric($users)) {
					$this->_database->or_where('id = ', $users);
				}
			}
		}else {
			// 過濾指定年齡
			if (!empty($filterRole['age_range_start']) && !empty($filterRole['age_range_end'])) {
				$age_start = $filterRole['age_range_start'];
				$age_end = $filterRole['age_range_end'];
				$this->_database->where("TIMESTAMPDIFF(YEAR,`birthday`,CURDATE()) between $age_start and $age_end");
			}

			// 過濾性別
			if (!empty($filterRole['gender'])) {
				$this->_database->where('sex = ', $filterRole['gender']);
			}
		}
		$subquery = $this->_database->select('id')->get_compiled_select('`p2p_user`.`users`', TRUE);
		$this->_database
			->select('*')
			->from('`p2p_log.user_login_log` as ull')
			->join("($subquery) as `us`", "`us`.`id` = `ull`.`user_id`")
			->order_by('created_at', 'DESC');

		if($targetCategory & NotificationTargetCategory::Investment) {
			$this->_database->or_where("investor = 1");
		}else if($targetCategory & NotificationTargetCategory::Loan) {
			$this->_database->or_where("investor = 0");
		}
		//$debug_query = $this->_database->get_compiled_select('', FALSE);
		$list = $this->_database->get()->{$this->_return_type(1)}();

		// 過濾掉重複 device_id，塞選對應的 platform 會員，並重新組建一個 array
		$deviceList = [0=> [], 1 => []];
		foreach($list as $user) {
			$device_id_borrow = json_decode($user->client);
			// 如果登入資訊的 device_id 有存在，而且也有 os 的資訊
			if(isset($device_id_borrow) && !empty($device_id_borrow->device_id) && !empty($device_id_borrow->os) &&
				((isset($filterRole['android']) && $device_id_borrow->os == 'android') ||
					(isset($filterRole['ios']) && $device_id_borrow->os == 'ios'))) {

				if(!array_key_exists($device_id_borrow->os, $deviceList[$user->investor]))
					$deviceList[$user->investor][$device_id_borrow->os] = [];

				if(!array_key_exists($user->user_id, $deviceList[$user->investor][$device_id_borrow->os]))
					$deviceList[$user->investor][$device_id_borrow->os][$user->user_id] =$device_id_borrow->device_id;
			}
		}

		return $this->convert_device_list($deviceList);
	}

	/**
	 * 取得所有用戶的 device token
	 * @return array
	 */
	public function get_all_devices() {
		$this->db
			->distinct()
			->select('client, user_id, investor')
			->from('`p2p_log.user_login_log`')
			->where('client like ', '%"os":"%')
			->order_by('created_at', 'DESC');
		//$debug_query = $this->db->get_compiled_select('', FALSE);
		$query = $this->db->get();
		$result = $query->result();
		$deviceList = array(0=>[], 1=>[]);
		foreach ($result as $data) {
			$client = json_decode($data->client);
			if(isset($client) && !empty($client->device_id) && !empty($client->os) && !empty($data->user_id)) {
				if(!array_key_exists($client->os, $deviceList[$data->investor]))
					$deviceList[$data->investor][$client->os] = [];
				if(!array_key_exists($data->user_id, $deviceList[$data->investor][$client->os]))
					$deviceList[$data->investor][$client->os][$data->user_id] = $client->device_id;
			}
		}

		return $this->convert_device_list($deviceList);
	}

	/**
	 * 將 device token 陣列轉換為指定格式
	 * @param $deviceList
	 * Input format: $deviceList[$data->investor][$client->os][$data->user_id][$client->device_id];
	 *
	 * Convert to   : $deviceList['loan'/'investment'][$client->os][$client->device_id];
	 * @return array
	 */
	private function convert_device_list($deviceList) {
		// 去除 user_id，然後過濾多個 user 登入同一台手機的重複 token，在轉為正常排序的 index array
		foreach ($deviceList as $target => $_v) {
			foreach ($_v as $key => $v)
				$deviceList[$target][$key] = array_values(array_unique(array_values($v)));
		}
		// 轉換投資人與借款人成指定 index
		return ['loan'=> $deviceList[0], 'investment'=> $deviceList[1]];
	}

	public function get_latest_devices($investment, $offset=0, $limit=0) {
		$this->db
			->select('*')
			->from('`p2p_log.user_login_log`')
			->where('investor = ', $investment)
			->where('client like ', '%"device_id":"%')
			->order_by('created_at', 'DESC');
		if($limit)
			$this->db->limit($limit, $offset);

		$query = $this->db->get();
		return $query->result();
	}
}
