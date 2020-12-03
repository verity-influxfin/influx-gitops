<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Google_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $googlePort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$googlePort}/scraper/api/v1.0/";
    }

    public function requestGoogle($userid, $keyword)
    {
        if(!$userid || !$keyword) {
            return false;
        }

        $url = $this->scraperUrl  . "google";
        $data = ["userid" => $userid, "keyword" => $keyword];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }

    public function getGoogleStatus($userid, $keyword)
    {
        if(!$userid || !$keyword) {
            return;
        }

        $encodedKeyword = urlencode($keyword);
        $url = $this->scraperUrl  . "google/{$userid}/{$encodedKeyword}/status";

        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response;
    }

	public function getGoogleResult($userid, $keyword)
	{
		if(!$userid || !$keyword) {
			return;
		}

		$encodedKeyword = urlencode($keyword);
		$url = $this->scraperUrl  . "google/{$userid}/{$encodedKeyword}/result";

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}

	public function getGoogleResultsByUserid($userid)
	{
		if(!$userid) {
			return;
		}

		$url = $this->scraperUrl  . "google/{$userid}/allResult";

		$result = curl_get($url);
		$response = json_decode($result);

		if (!$result || !isset($response->status) || $response->status != 200) {
			return;
		}

		return $response;
	}


}
