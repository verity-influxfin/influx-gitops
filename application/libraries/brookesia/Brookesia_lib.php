<?php


class Brookesia_lib
{
	function __construct()
	{
		$this->CI = &get_instance();
		$brookesiaPort = '9453';
		$this->brookesiaUrl = "http://" . getenv('GRACULA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";
	}

	public function userCheckAllRules($userId, $target_id)
	{
		if(!$userId) {
			return false;
		}

		$url = $this->brookesiaUrl  . "check/checkAll";
		$data = ['userId' => $userId, 'targetId' => $target_id];

		$result = curl_get($url, $data);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return false;
		}

		return true;
	}

	public function is_user_checked($user_id, $target_id)
	{
		$url = $this->brookesiaUrl  . "check/checkAll?userId=" . $user_id . "&targetId=" . $target_id;

		$result = curl_get($url);
		$response = json_decode($result);

        // 子系統無回應（案件會因流程轉二審，暫不觸發反詐欺）
		if (!$result || !isset($response->status)) {
			return TRUE;
		}
        // 子系統無 check log 資料
        else if($response->status != 200)
        {
            return FALSE;
        }
        // check log 有資料，但未完成
        else if (isset($response->response->result->status) && $response->response->result->status != 'finished')
        {
            return FALSE;
        }

		return TRUE;
	}

    // for second instance page

    public function getRuleHitByUserId($userId)
    {
        if(!$userId) {
            return;
        }

        $url = $this->brookesiaUrl . "result/userHitRule?userId=" . $userId;

        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }

    public function getRelatedUserByUserId($userId)
    {
        if (!$userId) {
            return;
        }

        $url = $this->brookesiaUrl . "result/relatedUser?userId=" . $userId;

        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }

}
