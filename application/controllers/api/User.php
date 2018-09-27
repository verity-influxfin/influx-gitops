<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class User extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('log/log_userlogin_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['register','registerphone','login','sociallogin','smslogin','smsloginphone','forgotpw','credittest'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
			
			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
			}
			
			if($this->user_info->block_status != 0){
				$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
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
     * @apiDefine BlockUser
     * @apiError 101 帳戶已黑名單
     * @apiErrorExample {json} 101
     *     {
     *       "result": "ERROR",
     *       "error": "101"
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
     * @apiError 205 非出借端登入
     * @apiErrorExample {json} 205
     *     {
     *       "result": "ERROR",
     *       "error": "205"
     *     }
     */
	 /**
	 * @apiDefine IsInvestor
     * @apiError 207 非借款端登入
     * @apiErrorExample {json} 207
     *     {
     *       "result": "ERROR",
     *       "error": "207"
     *     }
     */
	 
	/**
     * @api {post} /user/registerphone 會員 發送驗證簡訊 (註冊)
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
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
		$phone = isset($input['phone'])?trim($input['phone']):"";
		if (empty($phone)) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		if(!preg_match("/^09[0-9]{2}[0-9]{6}$/", $phone)){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		$this->load->library('sms_lib'); 
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_BUSY ));
		}
		
		$result = $this->user_model->get_by('phone', $phone);
        if ($result) {
			$this->response(array('result' => 'ERROR','error' => USER_EXIST ));
        } else {
			$this->sms_lib->send_register($phone);
			$this->response(array('result' => 'SUCCESS'));
        }
    }

	 /**
     * @api {post} /user/register 會員 註冊
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{6..}} password 設定密碼
     * @apiParam {String} code 簡訊驗證碼
     * @apiParam {number=0,1} [investor=0] 1:投資端 0:借款端
     * @apiParam {String} [promote_code] 邀請碼
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time":1		
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
	 * @apiError 312 密碼長度不足
     * @apiErrorExample {json} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
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
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }

		if(!preg_match("/^09[0-9]{2}[0-9]{6}$/", $input['phone'])){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		if(strlen($input['password']) < PASSWORD_LENGTH){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}
		
		if(isset($input['investor']) && $input['investor']){
			$data['status'] 			= 0;
			$data['investor_status'] 	= 1;;
		}else{
			$data['investor_status'] 	= 0;
			$data['status'] 			= 1;
		}
		
		$data['promote_code']		= isset($input['promote_code'])?$input['promote_code']:"";
		$data['my_promote_code'] 	= $this->get_promote_code();
		$data['auth_otp'] 			= get_rand_token();
		$result = $this->user_model->get_by('phone',$data['phone']);
		if ($result) {
			$this->response(array('result' => 'ERROR','error' => USER_EXIST ));
        } else {
			$this->load->library('sms_lib'); 
			$rs = $this->sms_lib->verify_code($data['phone'],$data["code"]);
			if($rs){
				unset($data['code']);
				$insert = $this->user_model->insert($data);
				if($insert){
					$token 				= new stdClass();
					$token->id			= $insert;
					$token->phone		= $data['phone'];
					$token->auth_otp	= $data['auth_otp'];
					$token->expiry_time	= time() + REQUEST_TOKEN_EXPIRY;
					$token->investor 	= $data['investor_status'];
					$request_token 		= AUTHORIZATION::generateUserToken($token);
					$this->load->library('notification_lib'); 
					$this->notification_lib->first_login($insert,$input['investor']);
					$this->response(array('result' => 'SUCCESS', 'data' => array( "token" => $request_token, "expiry_time"=>$token->expiry_time ,"first_time"=>1)));
				}else{
					$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
			}
        }
    }

	 /**
     * @api {post} /user/login 會員 用戶登入
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{6..}} password 密碼
	 * @apiParam {number=0,1} [investor=0] 1:投資端 0:借款端
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time":1		
     *      }
     *    }
	 * @apiUse InputError
	 * @apiUse BlockUser
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
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
		$investor	= isset($input['investor']) && $input['investor'] ?1:0;
		$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			if(sha1($input['password'])==$user_info->password){
				
				if($user_info->block_status != 0){
					$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
				}
			
				$token 		= new stdClass();
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

				$token->id			= $user_info->id;
				$token->phone		= $user_info->phone;
				$token->auth_otp	= get_rand_token();
				$token->expiry_time	= time()+REQUEST_TOKEN_EXPIRY;
				$token->investor 	= $investor;
				$request_token = AUTHORIZATION::generateUserToken($token);
				$this->user_model->update($user_info->id,array("auth_otp"=>$token->auth_otp));
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor ,"user_id"=>$user_info->id,"status"=>1));
				if($first_time){
					$this->load->library('notification_lib'); 
					$this->notification_lib->first_login($user_info->id,$investor);
				}
				$this->response(array('result' => 'SUCCESS', 'data' => array( "token" => $request_token,"expiry_time"=>$token->expiry_time,"first_time"=>$first_time) ));
			}else{
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"user_id"=>$user_info->id,"status"=>0));
				$this->response(array('result' => 'ERROR','error' => PASSWORD_ERROR ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"status"=>0));
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}
	}
	
	/**
     * @api {post} /user/sociallogin 會員 第三方登入
     * @apiGroup User
     * @apiParam {String=facebook,instagram,line} type 登入類型
     * @apiParam {String} access_token access_token
	 * @apiParam {number=0,1} [investor=0] 1:投資端 0:借款端
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time":1		
     *      }
     *    }
     *
	 * @apiUse InputError
	 * @apiUse BlockUser
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
	 
	public function sociallogin_post(){
        $input 		= $this->input->post(NULL, TRUE);
		$type  		= isset($input['type'])?$input['type']:"";
		$investor	= isset($input['investor']) && $input['investor'] ?1:0;
		switch ($type){
			case "facebook":
				$fields = ['access_token'];
				break; 
			case "instagram":
				$fields = ['access_token'];
				break;  
			case "line":
				$fields = ['access_token'];
				break;  
			default:
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
		
		if($type=="facebook"){
			$this->load->library('facebook_lib'); 
			$info	 	= $this->facebook_lib->get_info($input["access_token"]);
			$user_id  	= $this->facebook_lib->login($info);
			$account  	= isset($info['id'])?$info['id']:"";
		}

		if($type=="instagram"){
			$this->load->library('instagram_lib'); 
			$info 		= $this->instagram_lib->get_info($input["access_token"]);
			$user_id  	= $this->instagram_lib->login($info);
			$account  	= isset($info['id'])?$info['id']:"";
		}
		
		if($type=="line"){
			$this->load->library('line_lib'); 
			$info 		= $this->line_lib->get_info($input["access_token"]);
			$user_id  	= $this->line_lib->login($info);
			$account  	= isset($info['id'])?$info['id']:"";
		}
		
		if($user_id && $account){
			$user_info = $this->user_model->get($user_id);	
			if($user_info){
				
				if($user_info->block_status != 0){
					$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
				}

				$token 		= new stdClass();
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
				
				$token->investor 	= $investor;
				$token->id			= $user_info->id;
				$token->phone		= $user_info->phone;
				$token->auth_otp	= get_rand_token();
				$token->expiry_time	= time() + REQUEST_TOKEN_EXPIRY;

				$request_token = AUTHORIZATION::generateUserToken($token);
				$this->user_model->update($user_info->id,array("auth_otp"=>$token->auth_otp));
				$this->log_userlogin_model->insert(array("account"=>$account,"investor"=>$investor,"user_id"=>$user_id,"status"=>1));
				if($first_time){
					$this->load->library('notification_lib'); 
					$this->notification_lib->first_login($user_info->id,$investor);
				}
				$this->response(array('result' => 'SUCCESS','data' => array("token"=>$request_token,"expiry_time"=>$token->expiry_time,"first_time"=>$first_time) ));

			}else{
				$this->log_userlogin_model->insert(array("account"=>$account,"investor"=>$investor,"user_id"=>$user_id,"status"=>0));
				$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$account,"investor"=>$investor,"status"=>0));
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}
	}
	
	/**
     * @api {post} /user/smsloginphone 會員 發送驗證簡訊 （忘記密碼）
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
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
		$phone = isset($input['phone'])?trim($input['phone']):'';
		if (empty($phone)) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		if(!preg_match("/^09[0-9]{2}[0-9]{6}$/", $phone)){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		$this->load->library('sms_lib');
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_BUSY ));
		}
		
		$result = $this->user_model->get_by('phone', $phone);
        if ($result) {
			$this->sms_lib->send_verify_code($result->id,$phone);
			$this->response(array('result' => 'SUCCESS'));
        } else {
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
        }
    }
	
	 /**
     * @api {post} /user/forgotpw 會員 忘記密碼
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String} code 簡訊驗證碼
	 * @apiParam {String{6..}} new_password 新密碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
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
	 * @apiError 312 密碼長度不足
     * @apiErrorExample {json} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
     *     }
     *
     */
	public function forgotpw_post(){

		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['phone','code','new_password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
		
		if(!preg_match("/^09[0-9]{2}[0-9]{6}$/", $input['phone'])){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		if(strlen($input['new_password']) < PASSWORD_LENGTH){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}
		
		$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			$this->load->library('sms_lib'); 
			$rs = $this->sms_lib->verify_code($user_info->phone,$input["code"]);
			if($rs){
				$res = $this->user_model->update($user_info->id,array("password"=>$input['new_password']));
				if($res){
					$this->response(array('result' => 'SUCCESS'));
				}else{
					$this->response(array('result' => 'ERROR','error' => INSERT_ERROR));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}
	}
	
	 /**
     * @api {get} /user/info 會員 個人資訊
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id User ID
	 * @apiSuccess {String} name 姓名
	 * @apiSuccess {String} picture 照片
	 * @apiSuccess {String} nickname 暱稱
	 * @apiSuccess {String} sex 性別
	 * @apiSuccess {String} phone 手機號碼
	 * @apiSuccess {String} id_number 身分證字號
	 * @apiSuccess {number} investor 1:投資端 0:借款端
	 * @apiSuccess {String} transaction_password 是否設置交易密碼
	 * @apiSuccess {String} my_promote_code 推廣碼
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"id": "1",
     *      	"name": "",
     *      	"picture": "https://graph.facebook.com/2495004840516393/picture?type=large",
     *      	"nickname": "陳霈",
     *      	"phone": "0912345678",
     *      	"investor_status": "1",
     *      	"my_promote_code": "9JJ12CQ5",
     *      	"id_number": null,
     *      	"transaction_password": true,
     *      	"investor": 1,  
     *      	"created_at": "1522651818",     
     *      	"updated_at": "1522653939",     
     *      	"expiry_time": "1522675539"     
	 *      }
     *    }
	 * @apiUse TokenError
	 * @apiUse BlockUser
     *
     */
	public function info_get()
    {
		$user_id	= $this->user_info->id;
		$fields 	= $this->user_model->token_fields;
		foreach($fields as $key => $field){
			$data[$field] = $this->user_info->$field?$this->user_info->$field:"";
		}
		$data['transaction_password'] = empty($this->user_info->transaction_password)?false:true;
		$data['investor'] 		= $this->user_info->investor;
		$data['expiry_time'] 	= $this->user_info->expiry_time;
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }
	
	/**
     * @api {post} /user/bind 會員 綁定第三方帳號
     * @apiGroup User
     * @apiParam {String=facebook,instagram,line} type 登入類型
     * @apiParam {String} access_token access_token
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
     * @apiError 308 此FB帳號已綁定過
     * @apiErrorExample {json} 308
     *     {
     *       "result": "ERROR",
     *       "error": "308"
     *     }
	 *
     * @apiError 309 此IG帳號已綁定過
     * @apiErrorExample {json} 309
     *     {
     *       "result": "ERROR",
     *       "error": "309"
     *     }
	 *
     * @apiError 310 此LINE帳號已綁定過
     * @apiErrorExample {json} 310
     *     {
     *       "result": "ERROR",
     *       "error": "310"
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
			case "instagram":
				$fields = ['access_token'];
				break; 
			case "line":
				$fields = ['access_token'];
				break;  
			default:
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
		
		if($type=="facebook"){
			$this->load->library('facebook_lib'); 
			
			$meta  = $this->facebook_lib->get_user_meta($this->user_info->id);
			if($meta){
				$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
			}
			
			$debug_token = $this->facebook_lib->debug_token($input["access_token"]);
			if($debug_token){
				$info 		= $this->facebook_lib->get_info($input["access_token"]);
				if($info){
					$user_id 	= $this->facebook_lib->login($info);
					if($user_id){
						$this->response(array('result' => 'ERROR','error' => FBID_EXIST ));
					}else{
						$rs 		= $this->facebook_lib->bind_user($this->user_info->id,$info);
						if($rs){
							$this->set_nickname($info);
							$this->response(array('result' => 'SUCCESS'));
						}else{
							$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
						}
					}
				}
			}
			$this->response(array('result' => 'ERROR','error' => ACCESS_TOKEN_ERROR ));
		}
		
		if($type=="instagram"){
			$this->load->library('instagram_lib'); 
			
			$meta  = $this->instagram_lib->get_user_meta($this->user_info->id);
			if($meta){
				$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
			}
			
			$info 			= $this->instagram_lib->get_info($input["access_token"]);
			if($info){
				$user_id 	= $this->instagram_lib->login($info);
				if($user_id){
					$this->response(array('result' => 'ERROR','error' => IGID_EXIST ));
				}else{
					$rs 	= $this->instagram_lib->bind_user($this->user_info->id,$info);
					if($rs){
						$this->set_nickname($info);
						$this->response(array('result' => 'SUCCESS'));
					}else{
						$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
					}
				}
			}
			$this->response(array('result' => 'ERROR','error' => ACCESS_TOKEN_ERROR ));
		}
		
		if($type=="line"){
			$this->load->library('line_lib'); 
			
			$meta  = $this->line_lib->get_user_meta($this->user_info->id);
			if($meta){
				$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
			}
			
			$debug_token = $this->line_lib->debug_token($input["access_token"]);
			if($debug_token){
				$info 			= $this->line_lib->get_info($input["access_token"]);
				if($info){
					$user_id 	= $this->line_lib->login($info);
					if($user_id){
						$this->response(array('result' => 'ERROR','error' => LINEID_EXIST ));
					}else{
						$rs 	= $this->line_lib->bind_user($this->user_info->id,$info);
						if($rs){
							$this->set_nickname($info);
							$this->response(array('result' => 'SUCCESS'));
						}else{
							$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
						}
					}
				}
			}
			$this->response(array('result' => 'ERROR','error' => ACCESS_TOKEN_ERROR ));
		}
    }
	
	private function set_nickname($info){
		if($this->user_info->nickname=="" && $info['name']){
			$this->user_model->update($this->user_info->id,array("nickname"=>$info['name']));
		}
		
		if($this->user_info->picture=="" && $info['picture']){
			$this->user_model->update($this->user_info->id,array("picture"=>$info['picture']));
		}
		return true;
	}
	
	/**
     * @api {get} /user/editpwphone 會員 發送驗證簡訊 （修改密碼、交易密碼）
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}
		
		$this->load->library('sms_lib'); 
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_BUSY ));
		}
		
		$this->sms_lib->send_verify_code($user_id,$phone);
		$this->response(array('result' => 'SUCCESS'));
    }
	
	/**
     * @api {post} /user/editpw 會員 修改密碼
     * @apiGroup User
     * @apiParam {String} password 原密碼
     * @apiParam {String{6..}} new_password 新密碼
     * @apiParam {String} code 簡訊驗證碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
	 * @apiError 312 密碼長度不足
     * @apiErrorExample {json} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
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
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }
		
		if(strlen($input["new_password"]) < PASSWORD_LENGTH){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}
		
		$user_info = $this->user_info;
		if ($user_info) {
			if(sha1($data['password'])!=$user_info->password){
				$this->response(array('result' => 'ERROR','error' => PASSWORD_ERROR ));
			}
			
			$this->load->library('sms_lib'); 
			$rs = $this->sms_lib->verify_code($user_info->phone,$data["code"]);
			if(!$rs){
				$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
			}
			
			$res = $this->user_model->update($user_info->id,array("password"=>$data['new_password']));
			if($res){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}

        } else {
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
        }
    }
	
	/**
     * @api {post} /user/edittpw 會員 設置交易密碼
     * @apiGroup User
     * @apiParam {String{6..}} new_password 新交易密碼
     * @apiParam {String} code 簡訊驗證碼
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
	 * @apiError 311 交易密碼長度不足
     * @apiErrorExample {json} 311
     *     {
     *       "result": "ERROR",
     *       "error": "311"
     *     }
	 *
     */
	public function edittpw_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$data		= array();
		$user_info 	= $this->user_info;
		$investor 	= $this->user_info->investor;
		
		$fields 	= ['new_password','code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }
		
		if(strlen($input['new_password']) < TRANSACTION_PASSWORD_LENGTH){
			$this->response(array('result' => 'ERROR','error' => TRANSACTIONPW_LEN_ERROR ));
		}

		$this->load->library('sms_lib'); 
		$rs = $this->sms_lib->verify_code($user_info->phone,$data['code']);
		if(!$rs){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
		}
		
		$res = $this->user_model->update($user_info->id,array('transaction_password'=>$data['new_password']));
		if($res){
			$this->load->library('notification_lib'); 
			$this->notification_lib->transaction_password($user_info->id,$investor);
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
		}
    }
	
	/**
     * @api {get} /user/chagetoken 會員 交換Token
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418"
     *      }
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
     *
     */
	 
	public function chagetoken_get()
    {
        $input 				= $this->input->get(NULL, TRUE);
		$token 				= new stdClass();
		$token->investor 	= $this->user_info->investor;
		$token->id			= $this->user_info->id;
		$token->phone		= $this->user_info->phone;
		$token->auth_otp	= $this->user_info->auth_otp;
		$token->expiry_time	= time()+REQUEST_RETOKEN_EXPIRY;
		$request_token 		= AUTHORIZATION::generateUserToken($token);
		$this->response(array('result' => 'SUCCESS','data' => array("token"=>$request_token,"expiry_time"=>$token->expiry_time) ));
    }
	
	/**
     * @api {post} /user/contact 會員 投訴與建議
     * @apiGroup User
	 * @apiParam {String} content 內容
     * @apiParam {file} [image1] 附圖1
     * @apiParam {file} [image2] 附圖2
     * @apiParam {file} [image3] 附圖3
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
	 * @apiUse BlockUser
     * 
     */
	public function contact_post()
    {
		$this->load->model('user/user_contact_model');
		$this->load->library('S3_upload');
        $input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= array("user_id" => $user_id,"investor"=>$investor);
		if (empty($input['content'])) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
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
			$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
		}
    }
	
	/**
     * @api {post} /user/credittest 會員 測一測
     * @apiGroup User
	 * @apiParam {String} school 學校名稱
	 * @apiParam {String=0,1,2} [system=0] 學制 0:大學 1:碩士 2:博士
	 * @apiParam {String} department 系所
	 * @apiParam {String} grade 年級
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} amount 可貸款額度
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
	 *		"data":{
	 *			"amount": 50000
	 *		}
     *    }
	 *
	 * @apiUse InputError
     * 
     */
	public function credittest_post()
    {
        $input 	= $this->input->post(NULL, TRUE);
		$data 	= array("amount"=>0); 
		//必填欄位
		$fields 	= ['school','department','grade'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}
		
		$input['system'] = isset($input['system']) && in_array($input['system'],array(0,1,2))?$input['system']:0;
		$this->load->library('credit_lib'); 
		$point  = $this->credit_lib->get_school_point($input['school'],$input['system'],"");
		if($point>0){
			$point = $point + 300;
			$data["amount"] = $this->credit_lib->get_credit_amount($point);
		}
		
		$this->response(array('result' => 'SUCCESS','data'=> $data));
    }
	
	/**
     * @api {get} /user/promote 會員 推薦有獎
     * @apiGroup User
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} promote_code 推廣邀請碼
	 * @apiSuccess {String} promote_url 推廣連結
	 * @apiSuccess {String} promote_qrcode 推廣QR code
	 * @apiSuccess {json} bonus_list 獎勵列表(規劃中)
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"promote_code": "D221BL0K",
     *      	"promote_url": "http://dev.influxfin.com?promote_code=D221BL0K",
     *      	"promote_qrcode": "http://chart.apis.google.com/chart?cht=qr&choe=UTF-8&chl=http%3A%2F%2Fdev.influxfin.com%3Fpromote_code%3DD221BL0K&chs=200x200",
     *      	"bonus_list": []
     *      }
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
     * 
     */
	public function promote_get()
    {
		$user_id 		= $this->user_info->id;
		$promote_code	= $this->user_info->my_promote_code;
		$url 			= BORROW_URL.'?promote_code='.$promote_code;
		$qrcode			= get_qrcode($url);
		$data			= array(
			"promote_code"	=> $promote_code,
			"promote_url"	=> $url,
			"promote_qrcode"=> $qrcode,
			"bonus_list"	=> array()
		);

		$this->response(array('result' => 'SUCCESS','data'=>$data));
    }
	
	private function get_promote_code(){
		$code = make_promote_code();
		$result = $this->user_model->get_by('my_promote_code',$code);
		if ($result) {
			return $this->get_promote_code();
		}else{
			return $code;
		}
	}
}
