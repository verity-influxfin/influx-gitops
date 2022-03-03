<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ptt_lib
{
    function __construct($params = [])
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT')))
        {
            throw new Exception('can not get Ptt ip or port');
        }
        $end_point = 'ptt';
        $this->scraper_url = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point . '/';
    }

    public function get_ptt_status($user_id, $account)
    {
        if ( ! $user_id || ! $account)
        {
            return FALSE;
        }
        $url = $this->scraper_url . "{$user_id}/{$account}/status";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']) || $response['status'] != 200)
        {
            return FALSE;
        }

        return $response;
    }

    public function get_ptt_count($user_id, $account)
    {
        if ( ! $user_id || ! $account)
        {
            return FALSE;
        }
        $url = $this->scraper_url . "{$user_id}/{$account}/count";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']) || $response['status'] != 200)
        {
            return FALSE;
        }

        return $response;
    }

    public function get_ptt_data($user_id, $account)
    {
        if ( ! $user_id || ! $account)
        {
            return FALSE;
        }
        $url = $this->scraper_url . "{$user_id}/{$account}/data";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']) || $response['status'] != 200)
        {
            return FALSE;
        }

        return $response;
    }
}
