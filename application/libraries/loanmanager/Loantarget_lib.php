<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Loantarget_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
    }


    public function delayType($delay_days)
    {
        $target_delay_range = $this->CI->config->item('loanmanager')['targetDelayRange'];
        $delay_type = 0;
        if($delay_days > 30 && $delay_days <= 59){
            $delay_type = 1;
        }elseif($delay_days > 60 && $delay_days <= 89){
            $delay_type = 2;
        }elseif($delay_days > 90 && $delay_days <= 119){
            $delay_type = 3;
        }elseif($delay_days > 120){
            $delay_type = 4;
        }
        return $target_delay_range[$delay_type];
    }

    public function targetStatus($day)
    {
        $delayStatus = $this->CI->config->item('loanmanager')['delayStatus'];
        $delay_type = 0;
        if($day >= 1 && $day <= 7){
            $delay_type = 1;
        }elseif($day >= 8){
            $delay_type = 2;
        }
        return $delayStatus[$delay_type];
    }
}