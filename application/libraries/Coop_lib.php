<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coop_lib {

	private $client;

	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_coop_model');
    }

	public function coop_request($coop_url,$postData,$user_id,$phone){
		if(!empty($user_id)){
            ksort($postData);
            $middles        = implode('',array_values($postData));
            $Timestamp      = time();
            $Authorization  ='Bearer '.md5(sha1(COOPER_ID.$middles.$Timestamp).COOPER_KEY);
            $header = [
                'Authorization:'.$Authorization,
                'CooperID:'.COOPER_ID,
                'Timestamp:'.$Timestamp,
            ];
            $result = json_decode(curl_get(COOPER_API_URL.$coop_url,$postData,$header));
            $this->log_coop($result,$coop_url,$user_id,$phone);
            return $result;
		}
		return false;
	}

    private function log_coop($result,$coop_url,$user_id,$phone){
        $this->CI->log_coop_model->insert(array(
            "type" 		=> $coop_url,
            "user_id"	=> $user_id,
            "phone"		=> $phone,
            "response"	=> isset($result->result)=='SUCCESS'?$result->result:(isset($result->error)?$result->error:SAVE_FAIL),
        ));
    }
}
