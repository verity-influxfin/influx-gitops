<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class User extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_model');
		
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['register','registerphone'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 	= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::generateUserToken($token);
            if (empty($tokenData->id)) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
        }
    }
	

	public function registerphone_post()
    {

        $input = $this->input->post();
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

        $input 		= $this->input->post();
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
}
