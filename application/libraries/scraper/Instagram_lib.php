<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instagram_lib
{
    function __construct($params = [])
    {
        $this->CI = &get_instance();
        $this->scraperUrl = "http://" . getenv('GRACULA_IP') . ":" . getenv('GRACULA_PORT') . "/scraper/api/v1.0/instagram/";
        if (isset($params['ip']))
        {
            $this->scraperUrl = "http://{$params['ip']}/scraper/api/v1.0/";
        }
    }

    public function autoFollow($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/follow";
        $data = ['key' => ''];
        $result = curl_get($url, $data);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return TRUE;
    }

    public function getUserInfo($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/info";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function updateUserInfo($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/info";
        $data = ['key' => ''];
        $result = curl_get($url, $data);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function getLogStatus($reference, $followed_account, $action = 'riskControlInfo')
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/status?action={$action}";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function getTaskLog($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/taskLog";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function getRiskControlInfo($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/riskControlInfo";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);
        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function updateRiskControlInfo($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraperUrl . "{$reference}/{$followed_account}/riskControlInfo";
        $data = ['key' => ''];
        $result = curl_get($url, $data);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }
}