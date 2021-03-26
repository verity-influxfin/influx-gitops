<?php

class User_notification_model extends MY_Model
{
	public $_table = 'user_notification';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );
	public $status_list   	= array(
		0 =>	"刪除",
		1 =>	"未讀",
		2 =>	"已讀",
	);
	
	public $investor_list   	= array(
		0 =>	"借款端",
		1 =>	"出借端",
		2 =>	"共通",
	);
	
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

    public function get_filtered_deviceid($filterRole) {
		// 如果要指定某些 user
		if(!empty($filterRole['user_ids'])) {
			$userData = explode(",", $filterRole['user_ids']);
			$userList = array();

			foreach($userData as $users) {
				$userRange = explode("-", $users);
				if(count($userRange) == 2) {
					$this->_database->or_where('(id >=', $userRange[0], FALSE);
					$this->_database->where('id <=', $userRange[1].")", FALSE);
				}else if(is_numeric($users)) {
					$this->_database->or_where('id = ', $users);
				}
			}
		}else {
			// 過濾指定年齡
			if (isset($filterRole['age_range_start']) && isset($filterRole['age_range_end'])) {
				$age_start = $filterRole['age_range_start'];
				$age_end = $filterRole['age_range_end'];
				$this->_database->where("TIMESTAMPDIFF(YEAR,`birthday`,CURDATE()) between $age_start and $age_end");
			}

			// 過濾性別
			if (isset($filterRole['gender'])) {
				$this->_database->where('sex = ', $filterRole['gender']);
			}
		}
		$subquery = $this->_database->select('id')->get_compiled_select('`p2p_user`.`users`', TRUE);
		$this->_database
			->select('*')
			->from('`p2p_log.user_login_log` as ull')
			->join("($subquery) as `us`", "`us`.`id` = `ull`.`user_id`");

		// 投資人 or 借貸人 or 不過濾
		$targetMask = isset($filterRole['investment'])?1:0;
		$targetMask += isset($filterRole['loan'])?2:0;
		if($targetMask == 1) {
			$this->_database->or_where("investor = 1");
		}else if($targetMask == 2) {
			$this->_database->or_where("investor = 0");
		}
		$subquery2 = $this->_database->get_compiled_select('', FALSE);
		$list = $this->_database->get()->{$this->_return_type(1)}();

		// {"loan":"1","android":"1","ios":"1","gender":"M","age_range_start":"1","age_range_end":"25","user_ids":"1,2,3,50-100,1500-2000","title":"45245","content":"45245245","send_date":"1899-12-31 00:00","notification":"1"}
		// Example SQL:
		// SELECT *
		// FROM `p2p_log`.`user_login_log` as `ull`
		// JOIN (SELECT `id`
		// FROM `p2p_user`.`users`
		// WHERE `id` = '1'
		// OR `id` = '2'
		// OR `id` = '3'
		// OR (id >=50
		// AND id <=100)
		// OR (id >=1500
		// AND id <=2000)
		// AND TIMESTAMPDIFF(YEAR,`birthday`,CURDATE()) between 1 and 25
		// AND `sex` = 'M') as `us` ON `us`.`id` = `ull`.`user_id`
		// WHERE `investor` =0

		// 過濾掉重複 device_id，重新組建一個 array
		$deviceList = array();
		foreach($list as $user) {
			$device_id_borrow = json_decode($user->client);
			if(isset($device_id_borrow) && !empty($device_id_borrow->device_id) && !in_array($device_id_borrow->device_id, $deviceList)) {
				array_push($deviceList, $device_id_borrow->device_id);
			}
		}
		return $deviceList;
		exit();
		/*
		$subquery2 = $this->_database
			->select('`name` as `user_name`, jp.*')
			->from('users')
			->join("($subquery) jp", "jp.user_id = users.id")
			->get_compiled_select('', FALSE);
		$list = $this->_database->get()->{$this->_return_type(1)}();

		$company_users = array();
		foreach($list as $user) {
			array_push($company_users, $user->company_user_id);
		}

		$subquery = $this->_database->where_in('user_id', $company_users)->get_compiled_select('judicial_person', TRUE);
		$no_taishin = $this->_database
			->select('user_id, COUNT(*)')
			->from('virtual_account va')
			->join("($subquery) jp", "jp.user_id = va.user_id")
			->where(array(
				'investor' => 0,
				'virtual_account' => "CONCAT(".TAISHIN_VIRTUAL_CODE."0, tax_id)"
			))
			->get_compiled_select('', FALSE);

		*/
	}

}
