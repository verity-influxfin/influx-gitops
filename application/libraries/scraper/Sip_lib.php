<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sip_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT'))) {
            throw new Exception('can not get SIP ip or port');
        }
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":".getenv('GRACULA_PORT')."/scraper/api/v1.0/";
    }

    public function requestLogin($university, $account, $password)
    {
        if(!$university || !$account || !$password) {
            return false;
        }

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl  . "sips/{$encodedUni}/login";

        $data = ["account" => $account, "password" => $password];

        $result = curl_get($url, $data);
        $response = json_decode($result,true);

        if (!$result || !isset($response['status']) || $response['status'] != 200) {
            return false;
        }

        return true;
    }

	public function requestDeep($university, $account, $password)
	{
		if(!$university || !$account || !$password) {
			return false;
		}

		$encodedUni = urlencode($university);
		$url = $this->scraperUrl  . "sips/{$encodedUni}/deep";

		$data = ["account" => $account, "password" => $password];

		$result = curl_get($url, $data);
		$response = json_decode($result,true);

		if (!$result || !isset($response['status']) || $response['status'] != 200) {
			return false;
		}

		return true;
	}

    public function getLoginLog($university, $account)
    {
        if(!$university || !$account) {
            return;
        }

        $response = [];

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl  . "sips/{$encodedUni}/login-log?account={$account}";

        $result = curl_get($url);
        if(!empty($result)){
            $response = json_decode($result,true);
        }

        return $response;
    }

	public function getDeepLog($university, $account)
	{
		if(!$university || !$account) {
			return;
		}

        $response = [];

		$encodedUni = urlencode($university);
		$url = $this->scraperUrl  . "sips/{$encodedUni}/deep-log?account={$account}";

		$result = curl_get($url);
        if(!empty($result)){
            $response = json_decode($result,true);
        }

		return $response;
	}

	public function getDeepData($university, $account)
	{
		if(!$university || !$account) {
			return;
		}

        $response = [];

		$encodedUni = urlencode($university);
		$url = $this->scraperUrl  . "sips/{$encodedUni}/deep-sip-data?account={$account}";

		$result = curl_get($url);
        if(!empty($result)){
            $response = json_decode($result,true);
        }

		return $response;
	}

	public function check($university, $account)
	{
		$loginLogResponse = $this->getLoginLog($university, $account);
		$loginLog = isset($loginLogResponse)?$loginLogResponse:'';
		if (!$loginLog)
		{
			return;
		}
		$status = isset($loginLogResponse->status)?$loginLogResponse->status:'';
		if ($status && $status == 'failed')
		{
			# 轉人工
			return;
		}
		elseif ($status && $status != 'finished')
		{
			return;
		}
		$loginStatus = isset($loginLogResponse->loginStatus)?$loginLogResponse->loginStatus:'';
		if (!$loginStatus){
			# TODO: wrong account or password, set_failed
			return;
		}

		$deepLogResponse = $this->getDeepLog($university, $account);
		$deepLog = isset($deepLogResponse)?$deepLogResponse:'';
		if (!$deepLog)
		{
			return;
		}
		$deepLogStatus = isset($loginLogResponse->status)?$loginLogResponse->status:'';
		if ($deepLogStatus && $deepLogStatus == 'failure'){
			# TODO: scraper crashed, human check
			return;
		}
		elseif ($deepLogStatus && $deepLogStatus != 'finished')
		{
			return;
		}

		$deepDataResponse = $this->getDeepData($university, $account);
		$deepData = isset($deepDataResponse)?$deepDataResponse:'';
		if (!$deepData)
		{
			# TODO: scraper has bugs, human check
			return;
		}
	}

}
