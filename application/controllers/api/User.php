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
		$this->load->library('sms_lib'); 
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['register','registerphone','login','sociallogin','smslogin','smsloginphone','forgotpw'];
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
     * @apiError 100 Token錯誤
     * @apiErrorExample {json} 100
     *     {
     *       "result": "ERROR",
     *       "error": "100"
     *     }
     */
    /**
     * @apiDefine InputError
     * @apiError 200 參數錯誤
     * @apiErrorExample {json} 200
     *     {
     *       "result": "ERROR",
     *       "error": "200"
     *     }
     */
	 /**
     * @apiDefine InsertError
     * @apiError 201 新增時發生錯誤
     * @apiErrorExample {json} 201
     *     {
     *       "result": "ERROR",
     *       "error": "201"
     *     }
     */
	 /**
     * @apiDefine NotInvestor
     * @apiError 205 身份非放款端
     * @apiErrorExample {json} 205
     *     {
     *       "result": "ERROR",
     *       "error": "205"
     *     }
     */
	 
	/**
     * @api {post} /user/registerphone 會員 發送驗證簡訊 (註冊)
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
     * @apiError 307 發送簡訊間隔過短
     * @apiErrorExample {json} 307
     *     {
     *       "result": "ERROR",
     *       "error": "307"
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
		
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_BUSY ));
		}
		
		$result = $this->user_model->get_by('phone', $phone);
        if ($result) {
			$this->response(array('result' => 'ERROR',"error" => USER_EXIST ));
        } else {
			$this->sms_lib->send_register($phone);
			$this->response(array('result' => 'SUCCESS'));
        }
    }

	
	 /**
     * @api {post} /user/register 會員 註冊
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     * @apiParam {String} password (required) 設定密碼
     * @apiParam {String} code (required) 簡訊驗證碼
     * @apiParam {String} investor 1:投資端 0:借款端 default:0
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
	 * @apiUse InsertError
     *
     * @apiError 301 會員已存在
     * @apiErrorExample {json} 301
     *     {
     *       "result": "ERROR",
     *       "error": "301"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
     *
     *
     */
	public function register_post()
    {

		$input 		= $this->input->post(NULL, TRUE);
		$data		= array();
        $fields 	= ['phone','password','code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }

		if(isset($input['investor']) && $input['investor']){
			$data['status'] 			= 0;
			$data['investor_status'] 	= 1;;
		}else{
			$data['investor_status'] 	= 0;
			$data['status'] 			= 1;
		}
		
		$data['my_promote_code'] = $this->get_promote_code();
		$result = $this->user_model->get_by('phone',$data["phone"]);
		if ($result) {
			$this->response(array('result' => 'ERROR',"error" => USER_EXIST ));
        } else {
			$rs = $this->sms_lib->verify_code($data["phone"],$data["code"]);
			if($rs){
				unset($data["code"]);
				$insert = $this->user_model->insert($data);
				if($insert){
					$user_info 	= $this->user_model->get($insert);
					if($user_info){
						$token 				= new stdClass();
						$token->investor 	= $data['investor_status'];
						$fields 			= $this->user_model->token_fields;
						foreach($fields as $key => $field){
							$token->$field = $user_info->$field?$user_info->$field:"";
						}	
						$request_token = AUTHORIZATION::generateUserToken($token);
						$this->response(array('result' => 'SUCCESS', "data" => array( "token" => $request_token,"first_time"=>1)));
					}else{
						$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
					}
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
	 * @apiParam {String} investor 1:投資端 0:借款端 default:0
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
	 *			"first_time": 1,
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
	 * @apiUse InputError
     *
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 304 密碼錯誤
     * @apiErrorExample {json} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
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
		$investor	= isset($input['investor']) && $input['investor'] ?1:0;
		$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			if(sha1($input['password'])==$user_info->password){
				$data 		= new stdClass();
				$first_time = 0;
				if($investor==1 && $user_info->investor_status==0){
					$user_info->investor_status = 1;
					$this->user_model->update($user_info->id,array("investor_status"=>1));
					$first_time = 1;
				}else if($investor==0 && $user_info->status==0){
					$user_info->status = 1;
					$this->user_model->update($user_info->id,array("status"=>1));
					$first_time = 1;
				}

				$data->investor 		= $investor;
				$fields = $this->user_model->token_fields;
				foreach($fields as $key => $field){
					$data->$field = $user_info->$field?$user_info->$field:"";
				}

				$token = AUTHORIZATION::generateUserToken($data);
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor ,"user_id"=>$user_info->id,"status"=>1));
				$this->response(array('result' => 'SUCCESS', "data" => array( "token" => $token,"first_time"=>$first_time) ));
			}else{
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"user_id"=>$user_info->id,"status"=>0));
				$this->response(array('result' => 'ERROR',"error" => PASSWORD_ERROR ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"status"=>0));
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	/**
     * @api {post} /user/sociallogin 會員 第三方登入
     * @apiGroup User
     * @apiParam {String} type (required) 登入類型（"facebook"）
     * @apiParam {String} access_token (required) access_token
	 * @apiParam {String} investor 1:投資端 0:借款端 default:0
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
	 *			"first_time": 1,
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
     *
	 * @apiUse InputError
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 304 密碼錯誤
     * @apiErrorExample {json} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
     *
     */
	 
	public function sociallogin_post(){
        $input 		= $this->input->post(NULL, TRUE);
		$type  		= isset($input['type'])?$input['type']:"";
		$investor	= isset($input['investor']) && $input['investor'] ?1:0;
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
				$data 		= new stdClass();
				$first_time = 0;
				if($investor==1 && $user_info->investor_status==0){
					$user_info->investor_status = 1;
					$this->user_model->update($user_info->id,array("investor_status"=>1));
					$first_time = 1;
				}else if($investor==0 && $user_info->status==0){
					$user_info->status = 1;
					$this->user_model->update($user_info->id,array("status"=>1));
					$first_time = 1;
				}
				
				$data->investor = $investor;
				
				$fields = $this->user_model->token_fields;
				foreach($fields as $key => $field){
					$data->$field = $user_info->$field?$user_info->$field:"";
				}
				
				$token = AUTHORIZATION::generateUserToken($data);
				$this->log_userlogin_model->insert(array("account"=>$account,"investor"=>$investor,"user_id"=>$user_id,"status"=>1));
				$this->response(array('result' => 'SUCCESS',"data" => array("token"=>$token) ));

			}else{
				$this->log_userlogin_model->insert(array("account"=>$account,"investor"=>$investor,"user_id"=>$user_id,"status"=>0));
				$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$account,"investor"=>$investor,"status"=>0));
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	/**
     * @api {post} /user/smsloginphone 會員 發送驗證簡訊 （簡訊登入/忘記密碼）
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 *
	 * @apiUse InputError
	 *
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 307 發送簡訊間隔過短
     * @apiErrorExample {json} 307
     *     {
     *       "result": "ERROR",
     *       "error": "307"
     *     }
     *
     */
	 
	public function smsloginphone_post()
    {

        $input = $this->input->post(NULL, TRUE);
		$phone = isset($input["phone"])?trim($input["phone"]):"";
		if (empty($phone)) {
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}

		if(!preg_match("/09[0-9]{2}[0-9]{6}/", $phone)){
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}

		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_BUSY ));
		}
		
		$result = $this->user_model->get_by('phone', $phone);
        if ($result) {
			$this->sms_lib->send_verify_code($result->id,$phone);
			$this->response(array('result' => 'SUCCESS'));
        } else {
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
        }
    }
	
	 /**
     * @api {post} /user/smslogin 會員 簡訊登入
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     * @apiParam {String} code (required) 簡訊驗證碼
	 * @apiParam {String} investor 1:投資端 0:借款端 default:0
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
	 *			"first_time": 1,
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
	 * @apiUse InputError
     *
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
     *
     */
	public function smslogin_post(){

		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['phone','code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }
        }
		$investor	= isset($input['investor']) && $input['investor'] ?1:0;
		$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			$rs = $this->sms_lib->verify_code($user_info->phone,$input["code"]);
			if($rs){
				$data 		= new stdClass();
				$first_time = 0;
				if($investor==1 && $user_info->investor_status==0){
					$user_info->investor_status = 1;
					$this->user_model->update($user_info->id,array("investor_status"=>1));
					$first_time = 1;
				}else if($investor==0 && $user_info->status==0){
					$user_info->status = 1;
					$this->user_model->update($user_info->id,array("status"=>1));
					$first_time = 1;
				}

				$data->investor 		= $investor;
				$fields = $this->user_model->token_fields;
				foreach($fields as $key => $field){
					$data->$field = $user_info->$field?$user_info->$field:"";
				}
				$token = AUTHORIZATION::generateUserToken($data);
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor ,"user_id"=>$user_info->id,"status"=>1));
				$this->response(array('result' => 'SUCCESS', "data" => array( "token" => $token,"first_time"=>$first_time) ));
			}else{
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"user_id"=>$user_info->id,"status"=>0));
				$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_ERROR ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"status"=>0));
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	 /**
     * @api {post} /user/forgotpw 會員 忘記密碼
     * @apiGroup User
     * @apiParam {String} phone (required) 手機號碼
     * @apiParam {String} code (required) 簡訊驗證碼
	 * @apiParam {String} new_password (required) 新密碼
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
	 *			"first_time": 1,
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE"
     *      }
     *    }
	 * @apiUse InputError
     * @apiUse InsertError
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
     *
     */
	public function forgotpw_post(){

		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['phone','code','new_password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }
        }

		$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			$rs = $this->sms_lib->verify_code($user_info->phone,$input["code"]);
			if($rs){
				$res = $this->user_model->update($user_info->id,array("password"=>$input['new_password']));
				if($res){
					$this->response(array('result' => 'SUCCESS'));
				}else{
					$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR));
				}
			}else{
				$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_ERROR ));
			}
		}else{
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}
	}
	
	 /**
     * @api {get} /user/info 會員 個人資訊
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id User ID
	 * @apiSuccess {String} name 姓名（空值則代表未完成身份驗證）
	 * @apiSuccess {String} phone 手機號碼
	 * @apiSuccess {String} status 用戶狀態
	 * @apiSuccess {String} block_status 是否為黑名單
	 * @apiSuccess {String} id_number 身分證字號（空值則代表未完成身份驗證）
	 * @apiSuccess {String} investor 1:投資端 0:借款端
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"id": "1",
     *      	"name": "",
     *      	"phone": "0912345678",
     *      	"status": "1",
     *      	"id_number": null,
     *      	"investor": 1,
     *      	"block_status": "0"     
	 *      }
     *    }
	 * @apiUse TokenError
     *
     */
	public function info_get()
    {
		$user_id	= $this->user_info->id;
		$fields = $this->user_model->token_fields;
		foreach($fields as $key => $field){
			$data[$field] = $this->user_info->$field?$this->user_info->$field:"";
		}
		$data["investor"] = $this->user_info->investor;
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
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
     * @apiError 305 access_token錯誤
     * @apiErrorExample {json} 305
     *     {
     *       "result": "ERROR",
     *       "error": "305"
     *     }
     *
     * @apiError 306 此種類型已綁定過了
     * @apiErrorExample {json} 306
     *     {
     *       "result": "ERROR",
     *       "error": "306"
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
     * @api {get} /user/editpwphone 會員 發送驗證簡訊 （修改密碼）
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 307 發送簡訊間隔過短
     * @apiErrorExample {json} 307
     *     {
     *       "result": "ERROR",
     *       "error": "307"
     *     }
     *
     */
	 
	public function editpwphone_get()
    {
        $input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$phone 		= $this->user_info->phone;
		if (empty($phone)) {
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
		}

		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_BUSY ));
		}
		
		$this->sms_lib->send_verify_code($user_id,$phone);
		$this->response(array('result' => 'SUCCESS'));
    }
	
	/**
     * @api {post} /user/editpw 會員 修改密碼
     * @apiGroup User
     * @apiParam {String} password (required) 原密碼
     * @apiParam {String} new_password (required) 新密碼
     * @apiParam {String} code (required) 簡訊驗證碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse InsertError
	 *
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
	 *
	 * @apiError 304 密碼錯誤
     * @apiErrorExample {json} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
	 *
     */
	public function editpw_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$data		= array();
		$user_id 	= $this->user_info->id;
        $fields 	= ['password','new_password','code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }
		
		$user_info = $this->user_model->get($this->user_info->id);
		if ($user_info) {
			if(sha1($input['password'])!=$user_info->password){
				$this->response(array('result' => 'ERROR',"error" => PASSWORD_ERROR ));
			}
			
			$rs = $this->sms_lib->verify_code($user_info->phone,$data["code"]);
			if(!$rs){
				$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_ERROR ));
			}
			
			$res = $this->user_model->update($user_info->id,array("password"=>$data['new_password']));
			if($res){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}

        } else {
			$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
        }
    }
	
	/**
     * @api {post} /user/contact 會員 投訴與建議
     * @apiGroup User
	 * @apiParam {String} content (required) 內容
     * @apiParam {file} image1 附圖1
     * @apiParam {file} image2 附圖2
     * @apiParam {file} image3 附圖3
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
     *
     *
     */
	public function contact_post()
    {
		$this->load->model('user/user_contact_model');
		$this->load->library('S3_upload');
        $input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("user_id" => $user_id);
		if (empty($input['content'])) {
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}else{
			$param['content'] = $input['content'];
		}
		
		$image		= array();
		$fields 	= ['image1','image2','image3'];
		foreach ($fields as $field) {
            if(isset($_FILES[$field]) && !empty($_FILES[$field])){
				$image[$field] = $this->s3_upload->image($_FILES,$field,$user_id,"contact");
			}else{
				$image[$field] = "";
			}
        }
		$param['image'] = json_encode($image);
		$insert = $this->user_contact_model->insert($param);
		if($insert){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
		}
    }
	
	function get_promote_code(){
		$code = make_promote_code();
		$result = $this->user_model->get_by('my_promote_code',$code);
		if ($result) {
			return $this->get_promote_code();
		}else{
			return $code;
		}
	}
}
