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
        $this->scraper_url = 'http://' . getenv('GRACULA_IP') . ':' . getenv('GRACULA_PORT') . '/scraper/api/v1.0/' . $end_point;
        if (isset($params['ip']))
        {
            $this->scraper_url = "http://{$params['ip']}/scraper/api/v1.0/" . $end_point;
        }
    }

    public function requestJudicialYuanVerdicts($name, $address='')
    {
        if ( ! $name || ! $address)
        {
            return FALSE;
        }
        $response = [];
        $url = $this->scraper_url . '/data';

        $data = [
            'query' => $name,
            'address' => $address
        ];

        $result = curl_get($url, $data);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsStatuses($query, $address='')
    {
        if ( ! $query)
        {
            return FALSE;
        }
        $query = urlencode($query);

        if($address)
        {
            $url = $this->scraper_url . '/status?query=' . $query;
        }
        else
        {
            $url = $this->scraper_url . '/status?query=' . $query . '&address=' . $address;
        }

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsCount($query, $address='')
    {
        if ( ! $query)
        {
            return FALSE;
        }
        $query = urlencode($query);

        if($address)
        {
            $url = $this->scraper_url . '/count?query=' . $query;
        }
        else
        {
            $url = $this->scraper_url . '/count?query=' . $query . '&address=' . $address;
        }

        $result = curl_get($url);
        $response = json_decode($result, TRUE);
        return $response;

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

    }

    public function requestJudicialYuanVerdictsCase($query, $case, $address='')
    {
        if ( ! $query|| ! $case)
        {
            return FALSE;
        }
        $query = urlencode($query);
        $case = urlencode($case);

        if($address)
        {
            $url = $this->scraper_url . '/case?query=' . $query . '&case=' . $case;
        }
        else
        {
            $url = $this->scraper_url . '/case?query=' . $query . '&case=' . $case . '&address=' . $address;
        }

        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }

    public function requestJudicialYuanVerdictsResult($query, $address='')
    {
        if ( ! $query)
        {
            return FALSE;
        }
        $query = urlencode($query);

        if($address)
        {
            $url = $this->scraper_url . '/result?query=' . $query;
        }
        else
        {
            $url = $this->scraper_url . '/result?query=' . $query . '&address=' . $address;
        }
        $result = curl_get($url);
        $response = json_decode($result, TRUE);

        if ( ! $result || ! isset($response['status']))
        {
            return FALSE;
        }

        return $response;
    }
}
