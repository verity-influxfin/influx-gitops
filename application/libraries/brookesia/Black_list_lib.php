<?php


class Black_list_lib
{
	function __construct()
	{
		$this->CI = &get_instance();
		$brookesiaPort = '9453';
		$this->brookesiaUrl = 'http://' . getenv('GRACULA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";
	}

    /*
     * 取得使用者黑名單狀態
     * 204 => 該使用者沒有黑名單紀錄
     * 無回應 => 子系統有問題
     */
	public function check_user($userId, $action)
	{
        $check_key = $action == CHECK_APPLY_PRODUCT ? 'isUserBlocked' : 'isUserSecondInstance';
		$url = $this->brookesiaUrl  . "blockUser/{$check_key}?userId=" . $userId;

		$result = curl_get($url);
		$json = json_decode($result, TRUE);

        // 無回應
		if (!$json || !isset($json['status'])) {
			return [];
		}

        // 黑名單無資料
        if ($json['status'] == 204)
        {
            return [
                'userId' => $userId,
                $check_key => FALSE,
                'remark' => 'no block user data'
            ];
        }

		return $json['response'];
	}

	/*
	 * 觸發「新增黑名單處置」API
	 */
	public function add_block_log($block_user)
	{
		$url = $this->brookesiaUrl  . 'blockUser/log';
		$payload = [
			'blockUser' => $block_user
		];

		$result = curl_get($url, $payload);

		if ( ! $result)
		{
			return [
				'message' => '黑名單系統無回應，請洽工程師。'
			];
		}

		return json_decode($result, TRUE);
	}

}
