<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Faceplusplus_lib{

	public $api_url = "https://api-us.faceplusplus.com/facepp/v3/";

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('log/log_faceplusplus_model');
    }
	
	public function url_detect($image="",$user_id=0){
		if(!empty($image)){
			$file_content = base64_encode( file_get_contents( $image ) );
			$url 		= $this->api_url."detect";
			$data		= array(
				"api_key"			=> FACEPLUSPLUS_KEY,
				"api_secret"		=> FACEPLUSPLUS_SECRET,
				"return_attributes"	=> "gender,age",
				"image_base64"		=> $file_content
			);
			
			$rs 		= curl_get($url,$data);
			$log_data	= array(
				"type"		=> "detect",
				"user_id"	=> $user_id,
				"response"	=> $rs,
				"request"	=> $image,
			);
			$this->CI->log_faceplusplus_model->insert($log_data);

			$rs			= json_decode($rs,TRUE);
			if($rs && isset($rs["image_id"]) && count($rs['faces'])==2){
				$face1 		= $rs['faces'][0]["face_token"];
				$face2 		= $rs['faces'][1]["face_token"];
				$compare 	= $this->token_compare($face1,$face2);
				return $compare;
			}
		}
		return FALSE;
	}

	public function get_face_token($image="",$user_id=0,$cer_id=0){
		if(!empty($image)){
			$file_content = base64_encode( file_get_contents( $image ) );
			$url 		= $this->api_url."detect";
			$data		= array(
				"api_key"			=> FACEPLUSPLUS_KEY,
				"api_secret"		=> FACEPLUSPLUS_SECRET,
				"return_attributes"	=> "gender,age",
				"image_base64"		=> $file_content
			);
			
			$rs 		= curl_get($url,$data);
			$log_data	= array(
				"type"		=> "detect",
				"user_id"	=> $user_id,
				"cer_id"	=> $cer_id,
				"response"	=> $rs,
				"request"	=> $image,
			);
			$this->CI->log_faceplusplus_model->insert($log_data);
			$rs			= json_decode($rs,TRUE);
			if($rs && isset($rs["image_id"]) && count($rs["faces"])>0){
				$token 	= array();
				foreach($rs["faces"] as $key => $value){
                    $token[] = [
					    $value["face_token"],
					    $value['attributes']['gender'],
					    $value['attributes']['age'],
                    ];
				}

				return $token;
			}
		}
		return FALSE;
	}
	
	public function get_face_token_by_base64($image="",$user_id=0,$cer_id=0){
		if(!empty($image)){
			$url 			= $this->api_url."detect";
			$data			= array(
				"api_key"			=> FACEPLUSPLUS_KEY,
				"api_secret"		=> FACEPLUSPLUS_SECRET,
				"return_attributes"	=> "gender,age",
				"image_base64"		=> $image
			);
			
			$rs 		= curl_get($url,$data);
			$log_data	= array(
				"type"		=> "detect",
				"user_id"	=> $user_id,
                "cer_id"	=> $cer_id,
				"response"	=> $rs,
				"request"	=> $image,
			);
			$this->CI->log_faceplusplus_model->insert($log_data);
			
			$rs			= json_decode($rs,TRUE);
			if($rs && isset($rs["image_id"]) && count($rs["faces"])>0){
				$token 		= array();
				foreach($rs["faces"] as $key => $value){
					$token[] = $value["face_token"];
				}

				return $token;
			}
		}
		return FALSE;
	}
	
	public function url_compare($face1="",$face2="",$user_id=0){
		if(!empty($face1) && !empty($face2)){
			
			$url 		= $this->api_url."compare";
			$data		= array(
				"api_key"			=> FACEPLUSPLUS_KEY,
				"api_secret"		=> FACEPLUSPLUS_SECRET,
				"face_token1"		=> $face1,
				"face_token2"		=> $face2
			);
			
			$rs 		= curl_get($url,$data);
			$log_data	= array(
				"type"		=> "compare",
				"user_id"	=> $user_id,
				"response"	=> $rs,
				"request"	=> json_encode($data),
			);
			$this->CI->log_faceplusplus_model->insert($log_data);
			
			$rs			= json_decode($rs,TRUE);
			if($rs && isset($rs["confidence"])){
				return $rs["confidence"];
			}
		}
		return FALSE;
	}
	
	public function token_compare($face1="",$face2="",$user_id=0,$cer_id=0){
		if(!empty($face1) && !empty($face2)){
			
			$url 		= $this->api_url."compare";
			$data		= array(
				"api_key"			=> FACEPLUSPLUS_KEY,
				"api_secret"		=> FACEPLUSPLUS_SECRET,
				"face_token1"		=> $face1,
				"face_token2"		=> $face2
			);
			
			$rs 		= curl_get($url,$data);
			$log_data	= array(
				"type"		=> "compare",
				"user_id"	=> $user_id,
                "cer_id"	=> $cer_id,
				"response"	=> $rs,
				"request"	=> json_encode($data),
			);
			$this->CI->log_faceplusplus_model->insert($log_data);
			
			$rs			= json_decode($rs,TRUE);
			if($rs && isset($rs["confidence"])){
				return $rs["confidence"];
			}
		}
		return FALSE;
	}
	
}
