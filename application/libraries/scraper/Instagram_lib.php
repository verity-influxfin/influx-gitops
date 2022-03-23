<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Instagram_lib
{
    function __construct($params = [])
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT')))
        {
            throw new Exception('can not get Instagram ip or port');
        }
        $end_point = 'instagram';
        $this->scraper_url = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point . '/';
    }

    public function autoFollow($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraper_url . "{$reference}/{$followed_account}/follow";
        $data = ['key' => ''];
        $result = curl_get_statuscode($url, $data);
        $response = json_decode($result, TRUE);

        return $response;
    }

    public function getUserInfo($reference, $followed_account)
    {
        if ( ! $followed_account || ! $reference)
        {
            return FALSE;
        }

        $url = $this->scraper_url . "{$reference}/{$followed_account}/info";
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

        $url = $this->scraper_url . "{$reference}/{$followed_account}/info";
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

        $url = $this->scraper_url . "{$reference}/{$followed_account}/status?action={$action}";
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

        $url = $this->scraper_url . "{$reference}/{$followed_account}/taskLog";
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

        $url = $this->scraper_url . "{$reference}/{$followed_account}/riskControlInfo";
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

        $url = $this->scraper_url . "{$reference}/{$followed_account}/riskControlInfo";
        $data = ['key' => ''];
        $response = curl_get_statuscode($url, $data);

        return $response;
    }
}
