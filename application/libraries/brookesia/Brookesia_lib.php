<?php


class Brookesia_lib
{
	function __construct()
	{
		$this->CI = &get_instance();
		$brookesiaPort = '9453';
		$this->brookesiaUrl = "https://" . getenv('GRACULA_DOMAIN') . ":{$brookesiaPort}/brookesia/api/v1.0/";
	}

	public function userCheckAllRules($userId)
	{
		if(!$userId) {
			return false;
		}

		$url = $this->brookesiaUrl  . "check/9487/checkAll";
		$data = ["userId" => $userId];

		$result = curl_get($url, $data);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return false;
		}

		return true;
	}

	public function userCheckAllLog($userId)
	{
		if(!$userId) {
			return false;
		}

		$url = $this->brookesiaUrl  . "check/0/checkAll?userId=" . $userId;

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

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
