<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Google_lib
{
    function __construct($params = [])
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT')))
        {
            throw new Exception('can not get Google ip or port');
        }
        $end_point = 'google';
        $this->scraperUrl = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point . '/';
    }

    public function request_google($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }
        $response = [];
        $url = $this->scraperUrl . 'data';

        $data = [
            'keyword' => $query
        ];

        $result = curl_get($url, $data);
        if ($result)
        {
            $response = json_decode($result, TRUE);
        }
        else
        {
            return FALSE;
        }

        return $response;
    }

    public function get_google_status($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }

        $query = urlencode($query);
        $url = $this->scraperUrl . "{$query}/status";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function get_google_count($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }

        $query = urlencode($query);
        $url = $this->scraperUrl . "{$query}/count";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function get_google_hit($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }

        $query = urlencode($query);
        $url = $this->scraperUrl . "{$query}/hit";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function get_google_data($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }

        $query = urlencode($query);
        $url = $this->scraperUrl . "{$query}/data";

        $result = curl_get($url);
        $response = json_decode($result, TRUE);
        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }
}
