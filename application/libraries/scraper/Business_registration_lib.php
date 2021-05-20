<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Business_registration_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $businessRegistrationPort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$businessRegistrationPort}/scraper/api/v1.0/";
    }

    public function downloadBusinessRegistration()
    {
        $url = $this->scraperUrl  . "businessregistration/download";
		$data = ["download"];
		$result = curl_get($url, $data);

		if (!$result) {
            return false;
        }

        return true;
    }

    public function getResultByBusinessId($businessid)
    {
        if(!$businessid) {
            return;
        }

		$url = $this->scraperUrl  . "businessregistration/{$businessid}/";
        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }

	public function getDate()
	{
		$url = $this->scraperUrl  . "businessregistration/date";
		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

}
