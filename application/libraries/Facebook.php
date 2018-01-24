<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook{

	public $graph_api_url = "https://graph.facebook.com/v2.11/";
	
	public function debug_token($access_token=""){
		if(!empty($access_token)){
			$app_token 	= FACEBOOK_APP_ID.'|'.FACEBOOK_APP_SECRET;
			$url 		= $this->graph_api_url."debug_token?input_token=$access_token&access_token=".$app_token;
			$rs 		= curl_get($url);
			$rs			= json_decode($rs,TRUE);
			if($rs){
				if(isset($rs["data"]["app_id"]) && $rs["data"]["app_id"]==FACEBOOK_APP_ID){
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	public function get_info($access_token=""){
		if(!empty($access_token)){
			$data		= array();
			$url 		= $this->graph_api_url."me?fields=id,name,email&access_token=".$access_token;
			$rs 		= curl_get($url);
			$rs			= json_decode($rs,TRUE);
			if(isset($rs["id"]) && $rs["id"]){
				$data["id"] 	= $rs["id"];
				$data["name"] 	= isset($rs["name"])?$rs["name"]:"";
				$data["email"] 	= isset($rs["email"])?$rs["email"]:"";
				return $data;
			}
		}
		return FALSE;
	}
	
}
