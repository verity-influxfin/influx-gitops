<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Judicial_yuan_lib
{

    function __construct($params = [])
    {
        $this->CI = &get_instance();
        if (empty(getenv('GRACULA_IP')) || empty(getenv('GRACULA_PORT')))
        {
            throw new Exception('can not get Judicial_yuan ip or port');
        }
        $end_point = 'verdicts';
        $this->scraper_url = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point . '/';
        if (isset($params['ip']))
        {
            $this->scraper_url = "http://{$params['ip']}/scraper/api/v1.0/" . $end_point . '/';
        }
    }

    # $address and $reference not used
    public function requestJudicialYuanVerdicts($name)
    {
        if ( ! $name)
        {
            return FALSE;
        }
        $response = [];
        $url = $this->scraper_url . 'data';

        $data = ['query' => $name];

        $result = curl_get($url, $data);
        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsStatuses($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }
        $query = urlencode($query);
        $url = $this->scraper_url . "{$query}/status";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsCount($query)
    {
        if ( ! $query)
        {
            return FALSE;
        }
        $query = urlencode($query);
        $url = $this->scraper_url . "{$query}/count";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsCase($query, $type)
    {
        if ( ! $query || ! $type)
        {
            return FALSE;
        }
        $response = [];
        $query = urlencode($query);
        $type = urlencode($type);
        $url = $this->scraper_url . "{$query}/case?type={$type}";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsBornCount($reference, $born)
    {
        if ( ! $reference || ! $born)
        {
            return FALSE;
        }
        $response = [];
        $reference = urlencode($reference);
        $url = $this->scraper_url . "{$reference}/bornCount?born={$born}";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsBornCase($reference, $case, $born)
    {
        if ( ! $reference || ! $case || ! $born)
        {
            return FALSE;
        }
        $response = [];
        $reference = urlencode($reference);
        $url = $this->scraper_url . "{$reference}/bornCase?born={$born}&case={$case}";
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }
}
