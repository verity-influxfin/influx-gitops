<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instagram_lib
{
    function __construct($params=[])
    {
        $this->CI = &get_instance();
        $judicialYuanServerPort = '9998';
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":{$judicialYuanServerPort}/scraper/api/v1.0/instagram/";
        if(isset($params['ip'])){
          $this->scraperUrl = "http://{$params['ip']}/scraper/api/v1.0/";
        }
    }

    public function autoFollow($reference, $followed_account)
    {
        if(!$followed_account || !$reference) {
            return false;
        }

        $url = $this->scraperUrl  . "{$reference}/{$followed_account}/follow";
        $data = ['key'=>''];
        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }

    public function getUserFollow($reference, $followed_account)
    {
        if(!$followed_account || !$reference) {
            return false;
        }

        $url = $this->scraperUrl  . "{$reference}/{$followed_account}/info";
        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status)) {
            return false;
        }

        return $response;
    }

    public function updateUserFollow($reference, $followed_account)
    {
        if(!$followed_account || !$reference) {
            return false;
        }

        $url = $this->scraperUrl  . "{$reference}/{$followed_account}/info";
        $data = ['key'=>''];
        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return $response;
    }
}
