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
	
	/**
     * @apiDefine TokenRequired
     * @apiHeader {String} request_token (required) Token for api authorization.
     */ 
    /**
     * @apiDefine TokenError
     * @apiError 100 Token錯誤.
     * @apiErrorExample {json} 100
     *     {
     *       "result": "ERROR",
     *       "error": "100"
     *     }
     */
    /**
     * @apiDefine InputError
     * @apiError 201 欄位錯誤.
     * @apiErrorExample {json} 201
     *     {
     *       "result": "ERROR",
     *       "error": "201"
     *     }
     */

	 /**
     * @api {post} /user/registerphone 會員 發送驗證簡訊
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 * @apiUse InputError
     *
     * @apiError 301 會員已存在
     * @apiErrorExample {json} 301
     *     {
     *       "result": "ERROR",
     *       "error": "301"
     *     }
     *
     */
	 
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
			$this->load->library('sms_lib'); 
			$this->sms_lib->send_register($phone);
			$this->response(array('result' => 'SUCCESS'));
        }
    }
	
	 /**
     * @api {post} /user/register 會員 註冊（簡訊驗證）
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     * @apiParam {String} password (required) 設定密碼
     * @apiParam {String} code (required) 收到的驗證碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 * @apiUse InputError
     *
     * @apiError 301 會員已存在
     * @apiErrorExample {json} 301
     *     {
     *       "result": "ERROR",
     *       "error": "301"
     *     }
     *
     * @apiError 302 驗證碼錯誤
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 303 新增時發生錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
     *
     */
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
			$this->load->library('sms_lib'); 
			$rs = $this->sms_lib->verify_register($data["phone"],$data["code"]);
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

	 /**
     * @api {post} /user/login 會員 用戶登入
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     * @apiParam {String} password (required) 密碼
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
	 * @apiUse InputError
     *
     * @apiError 304 會員不存在
     * @apiErrorExample {json} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
     *
     * @apiError 305 密碼錯誤
     * @apiErrorExample {json} 305
     *     {
     *       "result": "ERROR",
     *       "error": "305"
     *     }
     *
     */
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
				$this->response(array('result' => 'SUCCESS', "data" => array( "token" => $token) ));
			}else{
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"status"=>0));
				$this->response(array('result' => 'ERROR',"error" => PASSWORD_ERROR ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$input['phone'],"status"=>0));
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	/**
     * @api {post} /user/sociallogin 會員 第三方登入
     * @apiGroup User
     * @apiParam {String} type (required) 登入類型（"facebook"）
     * @apiParam {String} access_token (required) access_token
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
     *
	 * @apiUse InputError
     * @apiError 304 會員不存在
     * @apiErrorExample {json} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
     *
     * @apiError 305 密碼錯誤
     * @apiErrorExample {json} 305
     *     {
     *       "result": "ERROR",
     *       "error": "305"
     *     }
     *
     */
	 
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
			$this->load->library('facebook_lib'); 
			$info = $this->facebook_lib->get_info($input["access_token"]);
			$user_id  = $this->facebook_lib->login($info);
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
	
	/**
     * @api {post} /user/bind 會員 綁定第三方帳號
     * @apiGroup User
     * @apiParam {String} type (required) 登入類型（"facebook"）
     * @apiParam {String} access_token (required) access_token
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
	 * @apiUse InputError
	 * @apiUse TokenError
     *
     * @apiError 306 access_token錯誤
     * @apiErrorExample {json} 306
     *     {
     *       "result": "ERROR",
     *       "error": "306"
     *     }
     *
     * @apiError 307 此種類型已綁定過了
     * @apiErrorExample {json} 307
     *     {
     *       "result": "ERROR",
     *       "error": "307"
     *     }
     *
     */
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
			$this->load->library('facebook_lib'); 
			$meta  = $this->facebook_lib->get_user_meta($this->user_info->id);
			if($meta){
				$this->response(array('result' => 'ERROR',"error" => TYPE_WAS_BINDED ));
			}
			
			$debug_token = $this->facebook_lib->debug_token($input["access_token"]);
			if($debug_token){
				$info = $this->facebook_lib->get_info($input["access_token"]);
				if($info){
					$rs = $this->facebook_lib->bind_user($this->user_info->id,$info);
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

	/**
     * @api {post} /user/bankaccount 會員 綁定金融帳號
     * @apiGroup User
	 * @apiParam {number} bank_code (required) 銀行代碼
	 * @apiParam {number} bank_account (required) 銀行帳號
     * @apiParam {file} front_image 金融卡正面照
     * @apiParam {file} back_image 金融卡背面照
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
     *
     * @apiError 303 新增時發生錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
     *
     */
	public function bankaccount_post()
    {
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('S3_upload');
        $input 		= $this->input->post(NULL, TRUE);
		$fields 	= ['bank_code','bank_account'];
		$user_id 	= $this->user_info->id;
		$param		= array("user_id" => $user_id);
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }else{
				$param[$field] = $input[$field];
			}
        }
		
		if(isset($_FILES["front_image"])){
			$param["front_image"] = $this->s3_upload->image($_FILES,"front_image",$user_id,"bankaccount");
		}
		
		if(isset($_FILES["back_image"])){
			$param["back_image"] = $this->s3_upload->image($_FILES,"back_image",$user_id,"bankaccount");
		}
		
		$bank_account = $this->user_bankaccount_model->get_by(array("status"=>1,"user_id"=>$user_id));
		if($bank_account){
			$this->user_bankaccount_model->update_by(array("user_id"=>$user_id),array("status"=>0));
		}
		$insert = $this->user_bankaccount_model->insert($param);
		if($insert){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
		}
    }
	
	 /**
     * @api {get} /user/bankaccount 會員 取得金融帳號
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} bank_code 銀行代碼
	 * @apiSuccess {String} bank_account 銀行帳號
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"bank_code": "882",
     *      	"bank_account": "2147483647"     
	 *      }
     *    }
	 * @apiUse TokenError
     *
     */
	public function bankaccount_get()
    {
		$this->load->model('user/user_bankaccount_model');
		$user_id	= $this->user_info->id;
		$data		= array();
		$bank_account = $this->user_bankaccount_model->get_by(array("status"=>1 , "user_id"=>$user_id ));
		if($bank_account){
			$fields 	= $this->user_bankaccount_model->fields;
			foreach ($fields as $field) {
				$data[$field] = $bank_account->$field;
			}
		}
		
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }
}
