<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Line_lib{

	public $graph_api_url = "https://api.line.me/";
	
	private $meta_key 	= array("line_id","line_username","line_access_token"); 
	
	public function __construct()
    {
        $this->CI = &get_instance();
    }
	
	public function debug_token($access_token=""){
		if(!empty($access_token)){
			$url 		= $this->graph_api_url."oauth2/v2.1/verify?access_token=".$access_token;
			$rs 		= curl_get($url);
			$rs			= json_decode($rs,TRUE);
			if($rs){
				if(isset($rs['client_id']) && $rs['client_id']==LINE_CHANNEL_ID){
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	public function get_info($access_token=""){
		if(!empty($access_token)){
			$data		= array();
			$url 		= $this->graph_api_url."v2/profile";
			$rs  		= curl_get($url,array(),array("Authorization: Bearer ".$access_token));
			$rs			= json_decode($rs,TRUE);
			if(isset($rs["userId"]) && $rs["userId"]){
				$data["id"] 			= $rs["userId"];
				$data["username"] 		= $rs["displayName"];
				$data["name"] 			= $rs["displayName"];
				$data["picture"] 		= $rs["pictureUrl"];
				$data["access_token"] 	= $access_token;
				return $data;
			}
		}
		return FALSE;
	}
	
	public function bind_user( $user_id="",$info = array() ){
		$this->CI->load->model('user/user_meta_model');
		if(!empty($user_id)){
			if(isset($info["id"]) && $info["id"]){
				$param = array(
					array("user_id"=>$user_id , "meta_key" => "line_id" 			, "meta_value"=> $info["id"]),
					array("user_id"=>$user_id , "meta_key" => "line_username" 		, "meta_value"=> $info["username"]),
					array("user_id"=>$user_id , "meta_key" => "line_access_token" 	, "meta_value"=> $info["access_token"]),
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
				$rs = $this->CI->user_meta_model->get_by(array("meta_key"=>"line_id","meta_value"=>$info["id"]));
				if($rs && isset($rs->user_id)){
					return $rs->user_id;
				}
			}
		}
		return FALSE;
	}
	

	public function send_five_points($line_id){
		$this->CI->load->model('log/log_line_model');
		curl_get(LINEBOT_URL.'/send_line_five_points?'.$line_id);
		$param = array(
		 "line_user_id"=>$line_id , "response" => "SUCCESS", "type"=> "text","content"=> "send_line_five_points");
		 $this->CI->log_line_model->insert($param);
	}


	public function check_thirty_points(){
		$this->CI->load->model('log/log_line_model');
		$url = LINEBOT_URL.'/check_thirty_points';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		
		curl_close($ch);

		return $output ;
	}


	public function check_five_points(){
		$this->CI->load->model('log/log_line_model');
		$url = LINEBOT_URL.'/check_five_points';
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		
		curl_close($ch);

		return $output ;
	}

	public function send_fifty_points($line_id){
		$this->CI->load->model('log/log_line_model');
		curl_get(LINEBOT_URL.'/send_line_fifty_points?'.$line_id);
		$param = array(
		 "line_user_id"=>$line_id , "response" => "SUCCESS", "type"=> "text","content"=> "send_line_fifty_points");
		 $this->CI->log_line_model->insert($param);
	}

	public function send_thirty_points($line_id){
		$this->CI->load->model('log/log_line_model');
		curl_get(LINEBOT_URL.'/send_line_thirty_points?'.$line_id);
		$param = array(
		 "line_user_id"=>$line_id , "response" => "SUCCESS", "type"=> "text","content"=> "send_thirty_points");
		 $this->CI->log_line_model->insert($param);
	}
}
