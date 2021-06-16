<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assets_report_lib
{
    function __construct($params=[])
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('download');
        $sisyphusServerPort = '9748';
        $this->$sisyphusUrl = "http://" . getenv('GRACULA_IP') . ":{$sisyphusServerPort}/accounting/api/v1.0/assets/report";
    }

    public function getAssetsReport()
    {
        $url = $this->$sisyphusUrl  . "?time=" . time();

        $file = file_get_contents($url);

        if (json_decode($file, 1) && json_decode($file, 1)['status'] == 204) {
          return false;
        }

        if ($file === FALSE) {
            return false;
        }

        return $file;
    }


}
