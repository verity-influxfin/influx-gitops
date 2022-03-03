<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sip_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT')))
        {
            throw new Exception('can not get SIP ip or port');
        }
        $end_point = 'sips';
        $this->scraperUrl = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point . '/';
    }

    public function requestLogin($university, $account, $password)
    {
        if ( ! $university || ! $account || ! $password)
        {
            return FALSE;
        }

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl . "{$encodedUni}/login";

        $data = ["account" => $account, "password" => $password];

        $result = curl_get($url, $data);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']) || $response['status'] != 200)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function requestDeep($university, $account, $password)
    {
        if ( ! $university || ! $account || ! $password)
        {
            return FALSE;
        }

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl . "{$encodedUni}/deep";

        $data = ["account" => $account, "password" => $password];

        $result = curl_get($url, $data);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']) || $response['status'] != 200)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function getLoginLog($university, $account)
    {
        if ( ! $university || ! $account)
        {
            return FALSE;
        }

        $response = [];

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl . "{$encodedUni}/login-log?account={$account}";

        $result = curl_get($url);
        if ( ! empty($result))
        {
            $response = json_decode($result, TRUE);
        }

        return $response;
    }

    public function getDeepLog($university, $account)
    {
        if ( ! $university || ! $account)
        {
            return FALSE;
        }

        $response = [];

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl . "{$encodedUni}/deep-log?account={$account}";

        $result = curl_get($url);
        if ( ! empty($result))
        {
            $response = json_decode($result, TRUE);
        }

        return $response;
    }

    public function getDeepData($university, $account)
    {
        if ( ! $university || ! $account)
        {
            return FALSE;
        }

        $response = [];

        $encodedUni = urlencode($university);
        $url = $this->scraperUrl . "{$encodedUni}/deep-sip-data?account={$account}";

        $result = curl_get($url);
        if ( ! empty($result))
        {
            $response = json_decode($result, TRUE);
        }

        return $response;
    }

    public function getUniversityUrl($university, $account)
    {
        if ( ! $university || ! $account)
        {
            return FALSE;
        }

        $response = [];
        $encodedUni = urlencode($university);
        $url = $this->scraperUrl . "{$encodedUni}/university-url?account={$account}";

        $result = curl_get($url);
        if ( ! empty($result))
            $response = json_decode($result, TRUE);

        return $response;
    }
}
