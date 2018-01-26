<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class User extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('log/log_userlogin_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['register','registerphone','login','sociallogin'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone)) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			$this->user_info = $tokenData;
        }
    }
	

	public function registerphone_post()
    {

        $input = $this->input->post(NULL, TRUE);
		$phone = isset($input["phone"])?trim($input["phone"]):"";
		if (empty($phone)) {
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}

		if(!preg_match("/09[0-9]{2}[0-9]{6}/", $phone)){
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}

		$result = $this->user_model->get_by('phone', $phone);
        if ($result) {
			$this->response(array('result' => 'ERROR',"error" => USER_EXIST ));
        } else {
			$this->load->library('sms'); 
			$this->sms->send_register($phone);
			$this->response(array('result' => 'SUCCESS'));
        }
    }
	
	public function register_post()
    {

		$input = $this->input->post(NULL, TRUE);
		$data		= array();
        $fields 	= ['phone','password','code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }

		$result = $this->user_model->get_by('phone',$data["phone"]);
		if ($result) {
			$this->response(array('result' => 'ERROR',"error" => USER_EXIST ));
        } else {
			$this->load->library('sms'); 
			$rs = $this->sms->verify_register($data["phone"],$data["code"]);
			if($rs){
				unset($data["code"]);
				$insert = $this->user_model->insert($data);
				if($insert){
					$this->response(array('result' => 'SUCCESS'));
				}else{
					$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
				}
			}else{
				$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_ERROR ));
			}
			
        }
		
    }
	
	public function login_post(){

		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['phone','password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }
        }

		$user_info = $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			if(sha1($input['password'])==$user_info->password){
				$data = new stdClass();
				$data->id 			= $user_info->id;
				$data->name 		= isset($user_info->name)?$user_info->name:"";
				$data->phone 		= $user_info->phone;
				$data->status 		= $user_info->status;
				$data->block_status = $user_info->block_status;
				$token = AUTHORIZATION::generateUserToken($data);
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"status"=>1));
				$this->response(array('result' => 'SUCCESS',"data" => array("token"=>$token) ));
			}else{
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"status"=>0));
				$this->response(array('result' => 'ERROR',"error" => PASSWORD_ERROR ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$input['phone'],"status"=>0));
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	public function sociallogin_post(){
        $input = $this->input->post(NULL, TRUE);
		$type  = isset($input['type'])?$input['type']:"";
		switch ($type){
			case "facebook":
				$fields = ['access_token'];
				break;  
			default:
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}
		
		foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }
        }
		
		if($type=="facebook"){
			$this->load->library('facebook'); 
			$info = $this->facebook->get_info($input["access_token"]);
			$user_id  = $this->facebook->login($info);
			$account  = isset($info['id'])?$info['id']:"";
		}

		if($user_id && $account){
			$user_info = $this->user_model->get($user_id);	
			if($user_info){
				$data = new stdClass();
				$data->id 			= $user_info->id;
				$data->name 		= isset($user_info->name)?$user_info->name:"";
				$data->phone 		= $user_info->phone;
				$data->status 		= $user_info->status;
				$data->block_status = $user_info->block_status;
				$token = AUTHORIZATION::generateUserToken($data);
				$this->log_userlogin_model->insert(array("account"=>$account,"status"=>1));
				$this->response(array('result' => 'SUCCESS',"data" => array("token"=>$token) ));

			}else{
				$this->log_userlogin_model->insert(array("account"=>$account,"status"=>0));
				
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$account,"status"=>1));
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	public function bind_post()
    {
        $input = $this->input->post(NULL, TRUE);
		$type  = isset($input['type'])?$input['type']:"";
		
		switch ($type){
			case "facebook":
				$fields = ['access_token'];
				break;  
			default:
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}

        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }
        }
		
		if($type=="facebook"){
			$this->load->library('facebook'); 
			$meta  = $this->facebook->get_user_meta($this->user_info->id);
			if($meta){
				$this->response(array('result' => 'ERROR',"error" => TYPE_WAS_BINDED ));
			}
			
			$debug_token = $this->facebook->debug_token($input["access_token"]);
			if($debug_token){
				$info = $this->facebook->get_info($input["access_token"]);
				if($info){
					$rs = $this->facebook->bind_user($this->user_info->id,$info);
					if($rs){
						$this->response(array('result' => 'SUCCESS'));
					}else{
						$this->response(array('result' => 'ERROR',"error" => TYPE_WAS_BINDED ));
					}
				}
			}
			$this->response(array('result' => 'ERROR',"error" => ACCESS_TOKEN_ERROR ));
		}
    }
}
