<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Judicial_yuan_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $judicialYuanServerPort = '10000';
        $this->scraperUrl = 'http://127.0.0.1:10000/scraper/api/v1.0/';//"http://" . getenv('GRACULA_IP') . ":{$judicialYuanServerPort}/scraper/api/v1.0/";
    }

    public function requestJudicialYuanVerdicts($name, $address, $reference)
    {
        if(!$name || !$address || !$reference) {
            return false;
        }

        $url = $this->scraperUrl  . "verdicts";

        $data = ["query" => $name, "location" => $address, "reference" => $reference];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }

    /*public function requestSipLogin($university, $account, $password)
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

    public function getSipLogin($university, $account)
    {
        if(!$university || !$account) {
            return;
        }

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl  . "sips/{$encodedUni}/login?account={$account}";

        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response->response;
    }*/
}
