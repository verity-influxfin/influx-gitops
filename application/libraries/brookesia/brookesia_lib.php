<?php


class brookesia_lib
{
	function __construct()
	{
		$this->CI = &get_instance();
		$brookesiaPort = '5000';
		$this->brookesiaUrl = "http://" . getenv('ENV_BROOKESIA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";
	}

	public function getRuleHitByUserId($userId)
	{
		if(!$userId) {
			return;
		}

		$url = $this->brookesiaUrl . "result/userHitRule?value=" . $userId;

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

	public function getResultByUserId($userId)
	{
		if(!$userId) {
			return;
		}

		$url = $this->brookesiaUrl . "result/userId?value=" . $userId;

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

}
