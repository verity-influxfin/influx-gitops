<?php


class Brookesia_react_lib
{
	function __construct()
	{
		$this->CI = &get_instance();
		$brookesiaPort = '9453';
		$this->brookesiaUrl = "http://" . getenv('GRACULA_IP') . ":{$brookesiaPort}/brookesia/api/v1.0/";
	}

	public function getAllRuleType()
	{
		$url = $this->brookesiaUrl  . "rule/all";

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return false;
		}

		return $response;
	}

	public function getResultByRule($typeId, $ruleId)
	{
		if(!$typeId || !$ruleId) {
			return false;
		}

		$url = $this->brookesiaUrl  . "result/ruleId?typeId=" . $typeId . "&ruleId=" . $ruleId;

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

	public function getRuleInfoByTypeId($typeId)
	{
		if(!$typeId) {
			return false;
		}

		$url = $this->brookesiaUrl  . "result/ruleStatistics?typeId=" . $typeId;

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

}
