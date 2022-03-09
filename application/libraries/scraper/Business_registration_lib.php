<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Business_registration_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT')))
        {
            throw new Exception('can not get Business_registration ip or port');
        }
        $end_point = 'businessregistration';
        $this->scraper_url = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point;
    }

    public function downloadBusinessRegistration()
    {
        $url = $this->scraper_url . '/download';
        $data = ['download'];
        $result = curl_get($url, $data);

        if ( ! $result)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function getResultByBusinessId($businessid)
    {
        if ( ! $businessid)
        {
            return FALSE;
        }
        $response = [];
        $url = $this->scraper_url . '/data?businessId=' . $businessid;
        $result = curl_get($url);
        if ( ! empty($result))
        {
            $response = json_decode($result, TRUE);
        }

        return $response;
    }

    public function getDate()
    {
        $response = [];
        $url = $this->scraper_url . '/date';
        $result = curl_get($url);
        if ( ! empty($result))
        {
            $response = json_decode($result, TRUE);
        }

        return $response;
    }

    public function getBrStatus($date)
    {
        if ( ! $date)
        {
            return FALSE;
        }
        $response = [];
        $url = $this->scraper_url . '/status?date=' . $date;
        $result = curl_get($url);
        if ( ! empty($result))
        {
            $response = json_decode($result, TRUE);
        }

        return $response;
    }

}
