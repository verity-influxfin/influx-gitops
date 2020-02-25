<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sip_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $sipServerPort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$sipServerPort}/scraper/api/v1.0/";
    }

    public function requestSipLogin($university, $account, $password)
    {
        if(!$university || !$account || !$password) {
            return false;
        }

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl  . "sips/{$encodedUni}/login";

        $data = ["account" => $account, "password" => $password];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }
}