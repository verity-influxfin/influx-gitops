<?php


class Brookesia_lib
{
	function __construct()
	{
		$this->CI = &get_instance();
		$brookesiaPort = '9453';
		$this->brookesiaUrl = "http://" . getenv('GRACULA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";
	}

	public function userCheckAllRules($userId)
	{
		if(!$userId) {
			return false;
		}

		$url = $this->brookesiaUrl  . "check/checkAll";
		$data = ["userId" => $userId];

		$result = curl_get($url, $data);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return false;
		}

		return true;
	}

	public function userNotChecked($userId)
	{
		if(!$userId) {
			return FALSE;
		}

		$url = $this->brookesiaUrl  . "check/checkAll?userId=" . $userId;

		$result = curl_get($url);
		$response = json_decode($result);

        // 子系統無回應（案件會因流程轉二審，暫不觸發反詐欺）
		if (!$result || !isset($response->status)) {
			return FALSE;
		}
        // 子系統無 check log 資料
        else if($response->status != 200)
        {
            return TRUE;
        }
        // check log 有資料，但未完成
        else if (isset($response->response->result->status) && $response->response->result->status != 'finished')
        {
            return TRUE;
        }

		return FALSE;
	}

}
