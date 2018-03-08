<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook_lib{

	public $graph_api_url = "https://graph.facebook.com/v2.11/";
	
	private $meta_key 	= array("fb_id","fb_name"); 
	
	public function __construct()
    {
        $this->CI = &get_instance();
    }
	
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
	
	public function bind_user( $user_id="",$info = array() ){
		$this->CI->load->model('user/user_meta_model');
		if(!empty($user_id)){
			if(isset($info["id"]) && $info["id"]){
				$info["name"] 	= isset($info["name"])?$info["name"]:"";
				$param = array(
					array("user_id"=>$user_id , "meta_key" => "fb_id" 	, "meta_value"=> $info["id"]),
					array("user_id"=>$user_id , "meta_key" => "fb_name" , "meta_value"=> $info["name"]),
				);
				$rs 	= $this->CI->user_meta_model->insert_many($param);
				if($rs){
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	public function get_user_meta( $user_id=""){
		$this->CI->load->model('user/user_meta_model');
		if(!empty($user_id)){
			$data 	= new stdClass();
			$rs 	= $this->CI->user_meta_model->get_many_by(array("user_id"=>$user_id,"meta_key"=>$this->meta_key));
			if($rs){
				foreach($rs as $key => $value){
					$meta_key = $value->meta_key;
					$data->$meta_key = $value->meta_value;
				}
				return $data;
			}
		}
		return FALSE;
	}
	
	public function login( $info = array() ){
		$this->CI->load->model('user/user_meta_model');
		if(!empty($info)){
			if(isset($info["id"]) && $info["id"]){
				$rs = $this->CI->user_meta_model->get_by(array("meta_key"=>"fb_id","meta_value"=>$info["id"]));
				if($rs && isset($rs->user_id)){
					return $rs->user_id;
				}
			}
		}
		return FALSE;
	}
	
}
