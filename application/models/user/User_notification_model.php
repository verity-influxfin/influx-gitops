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

		if($targetCategory & NotificationTargetCategory::investment) {
			$this->_database->or_where("investor = 1");
		}else if($targetCategory & NotificationTargetCategory::loan) {
			$this->_database->or_where("investor = 0");
		}
		//$debug_query = $this->_database->get_compiled_select('', FALSE);
		$list = $this->_database->get()->{$this->_return_type(1)}();

		// 過濾掉重複 device_id，塞選對應的 platform 會員，並重新組建一個 array
		$deviceList = array('ios' => array(), 'android' => array());
		foreach($list as $user) {
			$device_id_borrow = json_decode($user->client);
			// 如果登入資訊的 device_id 有存在，而且也有 os 的資訊
			if(isset($device_id_borrow) && !empty($device_id_borrow->device_id) &&
				  (!empty($device_id_borrow->os) &&
					  // 有勾選任一作業系統時篩選，若兩個都沒勾選擇忽略篩選
					  (((isset($filterRole['android']) && $device_id_borrow->os == 'android') ||
					(isset($filterRole['ios']) && $device_id_borrow->os == 'ios')) ||
					  (!isset($filterRole['android']) && !isset($filterRole['ios'])))) &&
				!array_key_exists($user->user_id, $deviceList[$device_id_borrow->os])
			) {
				$deviceList[$device_id_borrow->os][$user->user_id] =$device_id_borrow->device_id;
			}
		}

		// Convert the dictionary to 1 dimension array.
		// $android_tokens = array_values($deviceList['android']);
		// $ios_tokens = array_values($deviceList['ios']);
		// $deviceList = array_merge($android_tokens, $ios_tokens);
		// return $deviceList;
		return array('android' => array_values($deviceList['android']),
					'ios' => array_values($deviceList['ios']));
	}

}
