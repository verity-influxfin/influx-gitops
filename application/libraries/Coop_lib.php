<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Coop_lib {

	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('user/cooperation_model');
        $this->CI->load->model('log/log_coop_model');
    }

	public function coop_request($coop_url,$postData,$user_id,$normal=false){
        $Authorization = '';
        $header = [];
        if(!$normal){
            ksort($postData);
            $middles        = implode('',array_values($postData));
            $Timestamp      = time();
            $Authorization  ='Bearer '.md5(sha1(COOPER_ID.$middles.$Timestamp).COOPER_KEY);
            $header = [
                'Authorization:'.$Authorization,
                'CooperID:'.COOPER_ID,
                'Timestamp:'.$Timestamp,
            ];
        }
        $curl   = curl_get(COOPER_API_URL.$coop_url,$postData,$header);
        $result = json_decode($curl);
        $this->log_coop($result,$coop_url,$user_id);
        if(isset($result->result)) {
            return $result;
        }
        return false;
	}

    private function log_coop($result,$type,$user_id){
        $this->CI->log_coop_model->insert([
            "type" 		=> $type,
            "user_id"	=> $user_id,
            "response"	=> isset($result->result) && $result->result == 'SUCCESS'?
                $result->result:(isset($result->error)?
                    $result->error:LINK_FAIL),
        ]);
    }

    public function get_cooperation_info($store_id){
        $cooperation = $this->CI->cooperation_model->get_by(array(
            'id' 	=> $store_id,
        ));
        $cooperation->selling_type = $this->get_coopType($cooperation->company_user_id);
        return $cooperation;
    }

    public function get_store_id($company_user_id){
        $store_id = $this->CI->cooperation_model->get_by(array(
            'company_user_id' 	=> $company_user_id,
        ));
        return $store_id->id;
    }

    private function get_coopType($company_user_id){
        $this->CI->load->model('user/judicial_person_model');
        $judicial_person = $this->CI->judicial_person_model->get_by([
            'company_user_id' => $company_user_id
        ]);
        return $judicial_person->selling_type;
    }
}
