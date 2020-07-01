<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_lib{

	public $graph_api_url = "https://api.instagram.com/v1/";
	
	private $meta_key 	= array("ig_id","ig_username","ig_access_token"); 
	
	public function __construct()
    {
        $this->CI = &get_instance();
    }
	
	public function get_access_token($code="",$redirect_uri=""){
		if(!empty($code)){
			$param 	= array(
				"client_id"		=> INSTAGRAM_CLIENT_ID,
				"client_secret"	=> INSTAGRAM_CLIENT_SECRET,
				"grant_type"	=> 'authorization_code',
				"redirect_uri"	=> $redirect_uri,
				"code"			=> $code,
			);
			$rs = curl_get('https://api.instagram.com/oauth/access_token',$param);
			$data = json_decode($rs,TRUE);
			if(isset($data['access_token']) && $data['access_token']){
				return $data['access_token'];
			}
		}
		return FALSE;
	}
	
	public function get_info($access_token=""){
		if(!empty($access_token)){
			$data		= array();
			$url 		= $this->graph_api_url."users/self/?access_token=".$access_token;
			$rs 		= curl_get($url);
			$rs			= json_decode($rs,TRUE);
			if(isset($rs["meta"]["code"]) && $rs["meta"]["code"]=="200"){
				$data["id"] 			= $rs["data"]["id"];
				$data["username"] 		= $rs["data"]["username"];
				$data["name"] 			= isset($rs["data"]["full_name"])?$rs["data"]["full_name"]:"";
				$data["counts"] 		= isset($rs["data"]["counts"])?$rs["data"]["counts"]:"";
				$data["picture"] 		= isset($rs["data"]["profile_picture"])?$rs["data"]["profile_picture"]:"";
				$data["link"] 			= isset($rs["data"]["username"])?'https://www.instagram.com/'.$rs["data"]["username"]:"";
				$data["access_token"] 	= $access_token;
				$meta					= $this->get_media($access_token);	
				$data["meta"]			= $meta;
				return $data;
			}
		}
		return FALSE;
	}
	
	public function get_media($access_token=""){
		if(!empty($access_token)){
			$list	= array();
			$url 	= $this->graph_api_url."users/self/media/recent/?count=300&access_token=".$access_token;
			$rs 	= curl_get($url);
			$rs		= json_decode($rs,TRUE);
			if(isset($rs["meta"]["code"]) && $rs["meta"]["code"]=="200"){
				if($rs["data"]){
					foreach($rs["data"] as $key => $value){
						$list[] = array(
							"id"			=> $value['id'],
							"picture"		=> $value['images']['low_resolution']['url'],
							"created_time"	=> $value['created_time'],
							"text"			=> $value['caption']['text'],
							"likes"			=> $value['likes']['count'],
						);
					}
				}
			}
			return $list;
		}
		return FALSE;
	}
	
	public function bind_user( $user_id="",$info = array() ){
		$this->CI->load->model('user/user_meta_model');
		if(!empty($user_id)){
			if(isset($info["id"]) && $info["id"]){
				$param = array(
					array("user_id"=>$user_id , "meta_key" => "ig_id" 			, "meta_value"=> $info["id"]),
					array("user_id"=>$user_id , "meta_key" => "ig_username" 	, "meta_value"=> $info["username"]),
					array("user_id"=>$user_id , "meta_key" => "ig_access_token" , "meta_value"=> $info["access_token"]),
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
				$rs = $this->CI->user_meta_model->get_by(array("meta_key"=>"ig_id","meta_value"=>$info["id"]));
				if($rs && isset($rs->user_id)){
					return $rs->user_id;
				}
			}
		}
		return FALSE;
	}
	
}
