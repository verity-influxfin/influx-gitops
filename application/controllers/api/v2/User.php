<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class User extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
        $method 		= $this->router->fetch_method();
        $nonAuthMethods = ['register','registerphone','login','sociallogin','smslogin','smsloginphone','forgotpw','credittest','biologin','fraud', 'user_behavior'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
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

			if($this->request->method != 'get'){
				$this->load->model('log/log_request_model');
				$this->log_request_model->insert([
					'method' 	=> $this->request->method,
					'url'	 	=> $this->uri->uri_string(),
					'investor'	=> $tokenData->investor,
					'user_id'	=> $tokenData->id,
					'agent'		=> $tokenData->agent,
				]);
			}

			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
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
     * @apiErrorExample {Object} 100
     *     {
     *       "result": "ERROR",
     *       "error": "100"
     *     }
     */
	 /**
     * @apiDefine BlockUser
     * @apiError 101 帳戶已黑名單
     * @apiErrorExample {Object} 101
     *     {
     *       "result": "ERROR",
     *       "error": "101"
     *     }
     */
    /**
     * @apiDefine InputError
     * @apiError 200 參數錯誤
     * @apiErrorExample {Object} 200
     *     {
     *       "result": "ERROR",
     *       "error": "200"
     *     }
     */
	 /**
     * @apiDefine InsertError
     * @apiError 201 新增時發生錯誤
     * @apiErrorExample {Object} 201
     *     {
     *       "result": "ERROR",
     *       "error": "201"
     *     }
     */
	 /**
     * @apiDefine NotInvestor
     * @apiError 205 非出借端登入
     * @apiErrorExample {Object} 205
     *     {
     *       "result": "ERROR",
     *       "error": "205"
     *     }
     */
	 /**
	 * @apiDefine IsInvestor
     * @apiError 207 非借款端登入
     * @apiErrorExample {Object} 207
     *     {
     *       "result": "ERROR",
     *       "error": "207"
     *     }
     */
	 /**
	 * @apiDefine NotIncharge
     * @apiError 213 非法人負責人
     * @apiErrorExample {Object} 213
     *     {
     *       "result": "ERROR",
     *       "error": "213"
     *     }
	 */
	 /**
	 * @apiDefine IsCompany
     * @apiError 216 不支援法人帳號使用
     * @apiErrorExample {Object} 216
     *     {
     *       "result": "ERROR",
     *       "error": "216"
     *     }
	 */
	 /**
	 * @apiDefine NotCompany
     * @apiError 217 限法人帳號使用
     * @apiErrorExample {Object} 217
     *     {
     *       "result": "ERROR",
     *       "error": "217"
     *     }
     */

	/**
     * @api {post} /v2/user/registerphone 會員 註冊簡訊
	 * @apiVersion 0.2.0
	 * @apiName PostUserRegisterphone
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 * @apiUse InputError
     *
     * @apiError 301 會員已存在
     * @apiErrorExample {Object} 301
     *     {
     *       "result": "ERROR",
     *       "error": "301"
     *     }
	 *
     * @apiError 307 發送簡訊間隔過短
     * @apiErrorExample {Object} 307
     *     {
     *       "result": "ERROR",
     *       "error": "307"
     *     }
     *
     */

	public function registerphone_post()
    {
        $input = $this->input->post(NULL, TRUE);
		$phone = isset($input['phone'])?trim($input['phone']):'';

		if(!preg_match('/^09[0-9]{8}$/', $phone)){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

        // 檢查該手機在有效時間內註冊簡訊驗證碼是否正確
		$this->load->library('sms_lib');
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at']) <= SMS_LIMIT_TIME && !is_development()){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_BUSY ));
		}

        $send_sms_result = $this->sms_lib->send_register($phone);
        if ($send_sms_result) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR','error' => SMS_SEND_FAIL ));
        }
    }

	/**
     * @api {post} /v2/user/register 會員 註冊
	 * @apiVersion 0.2.0
	 * @apiName PostUserRegister
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{8}} tax_id 統編(若為法人身份)
     * @apiParam {String{6..50}} password 設定密碼
     * @apiParam {String} code 簡訊驗證碼
	 * @apiParam {String} [access_token] Facebook AccessToken
     * @apiParam {Number=0,1} [investor=0] 1:投資端 0:借款端
     * @apiParam {String{0..16}} [promote_code] 邀請碼
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {Number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time": 1
     *      }
     *    }
	 * @apiUse InputError
	 * @apiUse InsertError
     *
     * @apiError 301 會員已存在
     * @apiErrorExample {Object} 301
     *     {
     *       "result": "ERROR",
     *       "error": "301"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {Object} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
	 *
	 * @apiError 312 密碼長度錯誤
     * @apiErrorExample {Object} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
     *     }
     *
     * @apiError 305 AccessToken無效
     * @apiErrorExample {Object} 305
     *     {
     *       "result": "ERROR",
     *       "error": "305"
     *     }
     *
     * @apiError 308 此FB帳號已綁定過
     * @apiErrorExample {Object} 308
     *     {
     *       "result": "ERROR",
     *       "error": "308"
     *     }
     *
     */
	public function register_post()
    {
		$this->load->library('notification_lib');
		$this->load->library('facebook_lib');
		$this->load->library('sms_lib');

        $input = $this->input->post(NULL, TRUE);
        $result = [
            'result' => 'ERROR',
            'error' => INPUT_NOT_CORRECT
        ];

        // required fields

        // check phone
        if (!isset($input['phone']) || !preg_match('/^09[0-9]{8}$/', $input['phone'])) {
            goto END;
        }
        // check password
        if (!isset($input['password']) || (strlen($input['password']) < PASSWORD_LENGTH || strlen($input['password']) > PASSWORD_LENGTH_MAX)) {
            $result['error'] = PASSWORD_LENGTH_ERROR;
            goto END;
        }
        // TODO: wait to implement
        // check code
        if (!isset($input['code'])) {
            goto END;
        }


        // 確認該法人是否存在
        $tax_id_exist = isset($input['tax_id']);
        if ($tax_id_exist) {
            // check tax_id 統編8碼數字
            if (strlen($input['tax_id']) != 8) {
                $result['error'] = TAX_ID_LENGTH_ERROR;
                goto END;
            }

            // 檢查'法人關聯表-judicial_person'是否已存在此公司對應自然人之歸戶
            $this->load->model('user/judicial_person_model');
            $company_already_exist = $this->judicial_person_model->get_by([
                    'tax_id' => $input['tax_id'],
                    // 2:審核失敗
                    'status !=' => 2
            ]);
            if($company_already_exist){
                $result['error'] = COMPANY_EXIST;
                goto END;
            }

            // 檢查該法人在'使用者表-users'是否已存在
            $user_already_exist = $this->user_model->get_by([
                'id_number' => $input['tax_id'],
                'phone' => $input['phone'],
                // 法人狀態: 1=啟用
                'company_status' => 1
            ]);
            if ($user_already_exist) {
                $result['error'] = COMPANY_EXIST;
                goto END;
            }

        // 確認自然人是否存在
        } else {
            // 檢查該自然人在'使用者表-users'是否已存在
            $user_already_exist = $this->user_model->get_by([
                'phone' => $input['phone'],
                // 法人狀態: 0=未啟用
                'company_status' => 0
            ]);
            if ($user_already_exist) {
                $result['error'] = USER_EXIST;
                goto END;
            }
        }

        // default parameters

        // investor
        if (!isset($input['investor']) || !in_array($input['investor'], ['0', '1'])) {
            $input['investor'] = 0;
        }

        // 確認簡訊驗證
        $verify_result = $this->sms_lib->verify_code($input['phone'], $input['code']);
        if ($verify_result) {

            $opt_token = get_rand_token();

            // 新自然人帳號資料
            $new_account_data = [
                'phone' => $input['phone'],
                'password' => $input['password'],
                'promote_code' => isset($input['promote_code']) ? $input['promote_code'] : '',
                'status' => $input['investor'] ? 0 : 1,
                'investor_status' => $input['investor'] ? 1 : 0,
                'my_promote_code' => '',
                'auth_otp' => $opt_token,
            ];


            // 使用sql transaction，確保資料一致性

            // 啟用SQL事務
            $this->db->trans_start();

            // 新增法人帳號
            if ($tax_id_exist) {

                // 建立法人帳號
                $new_company_user_param = [
                    'phone' => $input['phone'],
                    'id_number' => $input['tax_id'],
                    'password' => $input['password'],
                    // 啟用法人
                    'company_status' => 1,
                    'auth_otp' => $opt_token,
                ];
                $new_id = $this->user_model->insert($new_company_user_param);

                // 若該法人之自然人帳號不存在，則自動建立其自然人帳號
                $company_user_already_exist = $this->user_model->get_by([
                    'phone' => $input['phone'],
                    // 法人狀態: 0=未啟用
                    'company_status' => 0
                ]);
                if (!$company_user_already_exist) {
                    $this->user_model->insert($new_account_data);
                }

            // 新增自然人帳號
            } else {

                $new_id = $this->user_model->insert($new_account_data);
                if ($new_id) {

                    // 若facebook的token存在，則新增'社群'認證
                    $facebook_access_token = isset($input['access_token']) ? $input['access_token'] : false;
                    if ($facebook_access_token) {
                        $this->load->library('facebook_lib');
                        $info = $this->facebook_lib->get_info($facebook_access_token);
                        $this->load->model('user/user_certification_model');
                        $this->user_certification_model->insert([
                                'user_id' => $new_id,
                                'certification_id' => 4,
                                'investor' => $input['investor'],
                                'content' => json_encode(['facebook'=> $info,'instagram'=>''])
                            ]);
                    }

                } else {
                    $result['error'] = INSERT_ERROR;
                    goto END;
                }
            }

            // 回傳創建帳號成功之token
            $token = (object) [
                'id'            => $new_id,
                'phone'         => $new_account_data['phone'],
                'auth_otp'      => $new_account_data['auth_otp'],
                'expiry_time'   => time() + REQUEST_TOKEN_EXPIRY,
                'investor'      => $new_account_data['investor_status'],
                'company'       => $tax_id_exist ? 1 : 0,
                'incharge'      => 0,
                'agent'         => 0,
            ];
            $request_token      = AUTHORIZATION::generateUserToken($token);
            $this->notification_lib->first_login($new_id, $input['investor']);
            $result = [
                'result' => 'SUCCESS',
                'data'   => [
                    'token'         => $request_token,
                    'expiry_time'   => $token->expiry_time,
                    'first_time'    => 1
                ]
            ];

            // 事務交易完成，提交結果
            $this->db->trans_complete();

        } else {
            $result['error'] = VERIFY_CODE_ERROR;
            goto END;
        }

END:
        // response result
        $this->response($result);
    }

	/**
     * @api {post} /v2/user/login 會員 用戶登入
	 * @apiVersion 0.2.0
	 * @apiName PostUserLogin
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{8}} tax_id 統編(若為法人身份)
     * @apiParam {String{6..50}} password 密碼
	 * @apiParam {Number=0,1} [investor=0] 1:投資端 0:借款端
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {Number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time": 1
     *      }
     *    }
	 * @apiUse InputError
	 * @apiUse BlockUser
     *
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 304 密碼錯誤
     * @apiErrorExample {Object} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
	 *
	 * @apiError 312 密碼長度錯誤
     * @apiErrorExample {Object} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
     *     }
	 *
     */

    public function login_post()
    {
		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['phone','password'];
        $device_id  = isset($input['device_id']) && $input['device_id'] ?$input['device_id']:null;
        $location   = isset($input['location'])?trim($input['location']):'';
        $os			= isset($input['os'])?trim($input['os']):'';
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }

		if(strlen($input['password']) < PASSWORD_LENGTH || strlen($input['password'])> PASSWORD_LENGTH_MAX ){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}

		$investor	= isset($input['investor']) && $input['investor'] ?1:0;

        // 自然人或法人判斷
        if (isset($input['tax_id'])) {
            // 法人
            $user_info = $this->user_model->get_by([
                'id_number' => $input['tax_id'],
                'phone' => $input['phone'],
                'company_status' => 1
            ]);
        } else {
            // 自然人
            $user_info = $this->user_model->get_by([
                'phone' => $input['phone'],
                'company_status' => 0
            ]);
        }
		if($user_info){
            //判斷鎖定狀態並解除
            $this->load->library('user_lib');
            $unblock_status = $this->user_lib->unblock_user($user_info->id);
            if($unblock_status){
                $user_info->block_status = 0;
            }
            if($user_info->block_status == 3) {
                $this->response(array('result' => 'ERROR','error' => SYSTEM_BLOCK_USER ));
            } elseif ($user_info->block_status == 2) {
                $this->response(array('result' => 'ERROR','error' => TEMP_BLOCK_USER ));
            }

			if(sha1($input['password'])==$user_info->password){

				if($user_info->block_status != 0){
				    $this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
				}

                $appIdentity = $this->input->request_headers()['User-Agent']??"";
				if(strpos($appIdentity,"PuHey") !== FALSE) {
                    if ($investor == 1 && $user_info->app_investor_status == 0) {
                        $user_info->app_investor_status = 1;
                        $this->user_model->update($user_info->id, array('app_investor_status' => 1));
                    }else if ($investor == 0 && $user_info->app_status == 0) {
                        $user_info->app_status = 1;
                        $this->user_model->update($user_info->id, array('app_status' => 1));
                    }
                }

				$first_time = 0;
				if($investor==1 && $user_info->investor_status==0){
					$user_info->investor_status = 1;
					$this->user_model->update($user_info->id,array('investor_status'=>1));
					$first_time = 1;

				}else if($investor==0 && $user_info->status==0){
					$user_info->status = 1;
					$this->user_model->update($user_info->id,array('status'=>1));
					$first_time = 1;
				}

                // 負責人
                $is_charge = 0;
                if (isset($input['tax_id'])) {
                    $this->load->model('user/judicial_person_model');
                    $charge_person = $this->judicial_person_model->check_valid_charge_person($input['tax_id']);
                    if ($charge_person) {
                        $userData = $this->user_model->get($charge_person->user_id);
                        $userData ? $is_charge = 1 : '';
                    }
                } else {
                    // TODO: 自然人登入，是否需關聯其法人負責人
                }

				$token = (object) [
					'id'			=> $user_info->id,
					'phone'			=> $user_info->phone,
					'auth_otp'		=> get_rand_token(),
					'expiry_time'	=> time() + REQUEST_TOKEN_EXPIRY,
					'investor'		=> $investor,
					'company'		=> isset($input['tax_id']) ? 1 : 0,
					'incharge'		=> $is_charge,
					'agent'			=> 0,
				];
				$request_token 		= AUTHORIZATION::generateUserToken($token);
				$this->user_model->update($user_info->id,array('auth_otp'=>$token->auth_otp));

				$this->insert_login_log($input['phone'],$investor,1,$user_info->id,$device_id,$location,$os);

				if($first_time){
					$this->load->library('notification_lib');
					$this->notification_lib->first_login($user_info->id,$investor);
				}
				$this->response([
					'result' => 'SUCCESS',
					'data' 	 => [
						'token' 		=> $request_token,
						'expiry_time'	=> $token->expiry_time,
						'first_time'	=> $first_time,
					]
				]);
			}else{
                $remind_count = $this->insert_login_log($input['phone'],$investor,0,$user_info->id,$device_id,$location,$os);
				$this->response([
				    'result' => 'ERROR',
                    'error'  => PASSWORD_ERROR,
                    'data'   => [
                        'remind_count' => $remind_count,
                    ]
                ]);
			}
		}else{
			$this->insert_login_log($input['phone'],$investor,0,0,$device_id,$location,$os);
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}
	}

	/**
     * @api {post} /v2/user/sociallogin 會員 第三方登入
	 * @apiVersion 0.2.0
	 * @apiName PostUserSociallogin
     * @apiGroup User
     * @apiParam {String} access_token Facebook AccessToken
	 * @apiParam {Number=0,1} [investor=0] 1:投資端 0:借款端
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {Number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time": 1
     *      }
     *    }
     *
	 * @apiUse InputError
	 * @apiUse BlockUser
	 *
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 304 密碼錯誤
     * @apiErrorExample {Object} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
     *
     */

    // 未使用
	// public function sociallogin_post(){
 //        $input 		= $this->input->post(NULL, TRUE);
	// 	$investor	= isset($input['investor']) && $input['investor'] ?1:0;
	// 	$device_id  = isset($input['device_id']) && $input['device_id'] ?$input['device_id']:null;
 //        $location   = isset($input['location'])?trim($input['location']):'';
	// 	$fields = ['access_token'];
	// 	foreach ($fields as $field) {
 //            if (empty($input[$field])) {
	// 			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
 //            }
 //        }

	// 	$this->load->library('facebook_lib');
	// 	$info	 	= $this->facebook_lib->get_info($input['access_token']);
	// 	$user_id  	= $this->facebook_lib->login($info);
	// 	$account  	= isset($info['id'])?$info['id']:'';
	// 	if($user_id && $account){
	// 		$user_info = $this->user_model->get($user_id);
	// 		if($user_info){

	// 			if($user_info->block_status != 0){
	// 				$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
	// 			}

	// 			$first_time = 0;
	// 			if($investor==1 && $user_info->investor_status==0){
	// 				$first_time = $user_info->investor_status = 1;
	// 				$this->user_model->update($user_info->id,array('investor_status'=>1));
	// 			}else if($investor==0 && $user_info->status==0){
	// 				$first_time = $user_info->status = 1;
	// 				$this->user_model->update($user_info->id,array('status'=>1));
	// 			}

	// 			$token = (object) [
	// 				'id'			=> $user_info->id,
	// 				'phone'			=> $user_info->phone,
	// 				'auth_otp'		=> get_rand_token(),
	// 				'expiry_time'	=> time() + REQUEST_TOKEN_EXPIRY,
	// 				'investor'		=> $investor,
	// 				'company'		=> 0,
	// 				'incharge'		=> 0,
	// 				'agent'			=> 0,
	// 			];
	// 			$request_token = AUTHORIZATION::generateUserToken($token);
	// 			$this->user_model->update($user_info->id,array('auth_otp'=>$token->auth_otp));
	// 			$this->insert_login_log($account,$investor,1,$user_id,$device_id,$location);
	// 			if($first_time){
	// 				$this->load->library('notification_lib');
	// 				$this->notification_lib->first_login($user_info->id,$investor);
	// 			}
	// 			$this->response(array(
	// 				'result' => 'SUCCESS',
	// 				'data' 	 => array(
	// 					'token'			=> $request_token,
	// 					'expiry_time'	=> $token->expiry_time,
	// 					'first_time'	=> $first_time
	// 				)
	// 			));
	// 		}else{
	// 			$this->insert_login_log($account,$investor,0,$user_id,$device_id,$location);
	// 			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
	// 		}
	// 	}else{
	// 		$this->insert_login_log($account,$investor,0,0,$device_id,$location);
	// 		$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
	// 	}
	// }

	/**
     * @api {post} /v2/user/smsloginphone 會員 忘記密碼簡訊
	 * @apiVersion 0.2.0
	 * @apiName PostUserSmsloginphone
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{8}} tax_id 統編(若為法人身份)
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 *
	 * @apiUse InputError
	 *
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 307 發送簡訊間隔過短
     * @apiErrorExample {Object} 307
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

		if(!preg_match('/^09[0-9]{8}$/', $phone)){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$this->load->library('sms_lib');
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at'])<=SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_BUSY ));
		}

        // 自然人或法人判斷
        if (isset($input['tax_id'])) {
            // 法人
            $user_info = $this->user_model->get_by([
                'id_number' => $input['tax_id'],
                'phone' => $input['phone'],
                'company_status' => 1
            ]);
        } else {
            // 自然人
            $user_info = $this->user_model->get_by([
                'phone' => $input['phone'],
                'company_status' => 0
            ]);
        }
        if ($user_info) {
			$this->sms_lib->send_verify_code($user_info->id, $phone);
			$this->response(array('result' => 'SUCCESS'));
        } else {
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
        }
    }

	/**
     * @api {post} /v2/user/forgotpw 會員 忘記密碼
	 * @apiVersion 0.2.0
	 * @apiName PostUserForgotpw
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{8}} tax_id 統編(若為法人身份)
     * @apiParam {String} code 簡訊驗證碼
	 * @apiParam {String{6..50}} new_password 新密碼
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
     * @apiUse InsertError
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {Object} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
	 *
	 * @apiError 312 密碼長度錯誤
     * @apiErrorExample {Object} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
     *     }
     *
     */
	public function forgotpw_post()
    {

		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['phone','code','new_password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }

		if(!preg_match('/^09[0-9]{8}$/', $input['phone'])){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		if(strlen($input['new_password']) < PASSWORD_LENGTH || strlen($input['new_password'])> PASSWORD_LENGTH_MAX ){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}

        // 自然人或法人判斷
        if (isset($input['tax_id'])) {
            // 法人
            $user_info = $this->user_model->get_by([
                'id_number' => $input['tax_id'],
                'phone' => $input['phone'],
                'company_status' => 1
            ]);
        } else {
            // 自然人
            $user_info = $this->user_model->get_by([
                'phone' => $input['phone'],
                'company_status' => 0
            ]);
        }
		if($user_info){
			$this->load->library('sms_lib');
			$rs = $this->sms_lib->verify_code($user_info->phone,$input['code']);
			if($rs){
				$res = $this->user_model->update($user_info->id,array('password'=>$input['new_password']));
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
     * @api {get} /v2/user/info 會員 個人資訊
	 * @apiVersion 0.2.0
	 * @apiName GetUserInfo
     * @apiGroup User
     *
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id User ID
	 * @apiSuccess {String} name 姓名
	 * @apiSuccess {String} picture 照片
	 * @apiSuccess {String} nickname 暱稱
	 * @apiSuccess {String} sex 性別
	 * @apiSuccess {String} phone 手機號碼
	 * @apiSuccess {String} id_number 身分證字號
	 * @apiSuccess {Number} investor 1:投資端 0:借款端
	 * @apiSuccess {Number} company 1:法人帳號 0:自然人帳號
	 * @apiSuccess {Number} incharge 1:負責人 0:代理人
	 * @apiSuccess {Number} agent 代理人User ID
	 * @apiSuccess {String} transaction_password 是否設置交易密碼
	 * @apiSuccess {String} my_promote_code 推廣碼
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {Object} SUCCESS
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
     *      	"company": 0,
     *      	"incharge": 0,
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
			$data[$field] = $this->user_info->$field?$this->user_info->$field:'';
		}

		$data['transaction_password'] 	= empty($this->user_info->transaction_password)?false:true;
		$data['investor'] 				= intval($this->user_info->investor);
		$data['company'] 				= intval($this->user_info->company);
		$data['incharge'] 				= intval($this->user_info->incharge);
		$data['agent'] 					= intval($this->user_info->agent);
		$data['expiry_time'] 			= intval($this->user_info->expiry_time);
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }

	/**
     * @api {post} /v2/user/bind 會員 綁定第三方帳號
	 * @apiVersion 0.2.0
	 * @apiName PostUserBind
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
	 * @apiParam {String} access_token Facebook AccessToken
	 *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     *
     * @apiError 305 access_token錯誤
     * @apiErrorExample {Object} 305
     *     {
     *       "result": "ERROR",
     *       "error": "305"
     *     }
     *
     * @apiError 306 已綁定過第三方帳號
     * @apiErrorExample {Object} 306
     *     {
     *       "result": "ERROR",
     *       "error": "306"
     *     }
     *
     * @apiError 308 此FB帳號已綁定過
     * @apiErrorExample {Object} 308
     *     {
     *       "result": "ERROR",
     *       "error": "308"
     *     }
     *
     */
	// 未使用
  //   public function bind_post()
  //   {
		// $this->not_support_company();
  //       $input 	= $this->input->post(NULL, TRUE);
		// $fields = ['access_token'];
  //       foreach ($fields as $field) {
  //           if (empty($input[$field])) {
		// 		$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
  //           }
  //       }

		// $this->load->library('facebook_lib');
		// $meta  = $this->facebook_lib->get_user_meta($this->user_info->id);
		// if($meta){
		// 	$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
		// }

		// $debug_token = $this->facebook_lib->debug_token($input['access_token']);
		// if($debug_token){
		// 	$info 		= $this->facebook_lib->get_info($input['access_token']);
		// 	if($info){
		// 		$user_id 	= $this->facebook_lib->login($info);
		// 		if($user_id){
		// 			$this->response(array('result' => 'ERROR','error' => FBID_EXIST ));
		// 		}else{
		// 			$rs 		= $this->facebook_lib->bind_user($this->user_info->id,$info);
		// 			if($rs){
		// 				$this->set_nickname($info);
		// 				$this->response(array('result' => 'SUCCESS'));
		// 			}else{
		// 				$this->response(array('result' => 'ERROR','error' => TYPE_WAS_BINDED ));
		// 			}
		// 		}
		// 	}
		// }
		// $this->response(array('result' => 'ERROR','error' => ACCESS_TOKEN_ERROR ));
  //   }

	private function set_nickname($info){
		if($this->user_info->nickname=='' && $info['name']){
			$this->user_model->update($this->user_info->id,array('nickname'=>$info['name']));
		}

		if($this->user_info->picture=='' && $info['picture']){
			$this->user_model->update($this->user_info->id,array('picture'=>$info['picture']));
		}
		return true;
	}

	/**
     * @api {get} /v2/user/editpwphone 會員 交易、修改密碼簡訊
	 * @apiVersion 0.2.0
	 * @apiName GetUserEditpwphone
     * @apiGroup User
     *
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     *
     * @apiError 307 發送簡訊間隔過短
     * @apiErrorExample {Object} 307
     *     {
     *       "result": "ERROR",
     *       "error": "307"
     *     }
     *
    */

	public function editpwphone_get()
    {
		$this->not_support_company();
        $input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$phone 		= $this->user_info->phone;

		$this->load->library('sms_lib');
		$code = $this->sms_lib->get_code($phone);
		if($code && (time()-$code['created_at']) <= SMS_LIMIT_TIME){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_BUSY ));
		}

		$this->sms_lib->send_verify_code($user_id,$phone);
		$this->response(array('result' => 'SUCCESS'));
    }

	/**
     * @api {post} /v2/user/editpw 會員 修改密碼
	 * @apiVersion 0.2.0
	 * @apiName PostUserEditpw
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} password 原密碼
     * @apiParam {String{6..50}} new_password 新密碼
     * @apiParam {String} code 簡訊驗證碼
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {Object} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
	 *
	 * @apiError 304 密碼錯誤
     * @apiErrorExample {Object} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
	 *
	 * @apiError 312 密碼長度錯誤
     * @apiErrorExample {Object} 312
     *     {
     *       "result": "ERROR",
     *       "error": "312"
     *     }
	 *
     */
	public function editpw_post()
    {
		$this->not_support_company();
		$input 		= $this->input->post(NULL, TRUE);
		$data		= array();
        $fields 	= ['password','new_password','code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
				$data[$field] = $input[$field];
			}
        }

		if(strlen($input['new_password']) < PASSWORD_LENGTH || strlen($input['new_password'])> PASSWORD_LENGTH_MAX ){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}

		$user_info = $this->user_info;
		if(sha1($data['password'])!=$user_info->password){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_ERROR ));
		}

		$this->load->library('sms_lib');
		$rs = $this->sms_lib->verify_code($user_info->phone,$data['code']);
		if(!$rs){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
		}

		$res = $this->user_model->update($user_info->id,array('password'=>$data['new_password']));
		if($res){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
		}
    }

	/**
     * @api {post} /v2/user/edittpw 會員 設置交易密碼
	 * @apiVersion 0.2.0
	 * @apiName PostUserEdittpw
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String{6..50}} new_password 新交易密碼
     * @apiParam {String} code 簡訊驗證碼
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
	 *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {Object} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
	 *
	 * @apiError 311 交易密碼長度不足
     * @apiErrorExample {Object} 311
     *     {
     *       "result": "ERROR",
     *       "error": "311"
     *     }
	 *
     */
	public function edittpw_post()
    {
		$this->not_support_company();
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

		if(strlen($input['new_password']) < TRANSACTION_PASSWORD_LENGTH || strlen($input['new_password']) > TRANSACTION_PASSWORD_LENGTH_MAX){
			$this->response(array('result' => 'ERROR','error' => TRANSACTIONPW_LEN_ERROR ));
		}

		$this->load->library('sms_lib');
		$rs = $this->sms_lib->verify_code($user_info->phone,$data['code']);
		if(!$rs){
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
		}
        $this->load->model('user/judicial_person_model');
        $judicial_person = $this->judicial_person_model->get_by([
            'user_id'=> $user_info->id
        ]);
        if($judicial_person){
            $this->user_model->update($judicial_person->company_user_id,array('transaction_password'=>$data['new_password']));
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
     * @api {get} /v2/user/chagetoken 會員 交換Token
	 * @apiVersion 0.2.0
	 * @apiName GetUserChagetoken
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
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
		$token = (object) [
			'id'			=> $this->user_info->id,
			'phone'			=> $this->user_info->id,
			'auth_otp'		=> $this->user_info->auth_otp,
			'expiry_time'	=> time() + REQUEST_RETOKEN_EXPIRY,
			'investor'		=> $this->user_info->investor,
			'company'		=> $this->user_info->company,
			'incharge'		=> $this->user_info->incharge,
			'agent'			=> $this->user_info->agent,
		];
		$request_token 		= AUTHORIZATION::generateUserToken($token);
		$this->response(array('result' => 'SUCCESS','data' => array('token'=>$request_token,'expiry_time'=>$token->expiry_time) ));
    }

	/**
     * @api {post} /v2/user/contact 會員 投訴與建議
	 * @apiVersion 0.2.0
	 * @apiName PostUserContact
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String} content 內容
     * @apiParam {file} [image1] 附圖1
     * @apiParam {file} [image2] 附圖2
     * @apiParam {file} [image3] 附圖3
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
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
		$param		= array('user_id' => $user_id,'investor'=>$investor);
		if (empty($input['content'])) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}else{
			$param['content'] = $input['content'];
		}

        $image = array();
		if(isset($input['image'])){
            $image_ids = explode(',', $input['image']);
            if (count($image_ids) > 5) {
                $image_ids = array_slice($image_ids, 0, 5);
            }
            $list = $this->log_image_model->get_many_by([
                'id' => $image_ids,
                'user_id' => $user_id,
            ]);

            if ($list && count($list) == count($image_ids)) {
                $image['image'] = [];
                foreach ($list as $k => $v) {
                    $image['image'][] = $v->url;
                }
            }
        }

		$fields 	= ['image1','image2','image3'];
		foreach ($fields as $field) {
            if(isset($_FILES[$field]) && !empty($_FILES[$field])){
				$image[$field] = $this->s3_upload->image($_FILES,$field,$user_id,'contact');
			}else{
				$image[$field] = '';
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
     * @api {get} /v2/user/promote 會員 推薦有獎 Line Point 活動
	 * @apiVersion 0.2.0
	 * @apiName GetUserPromote
     * @apiGroup User
     *
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} promote_code 推廣邀請碼
	 * @apiSuccess {String} promote_url 推廣連結
	 * @apiSuccess {String} promote_qrcode 推廣QR code
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"promote_code": "D221BL0K",
     *      	"promote_url": "http://dev.influxfin.com?promote_code=D221BL0K",
     *      	"promote_qrcode": "http://chart.apis.google.com/chart?cht=qr&choe=UTF-8&chl=http%3A%2F%2Fdev.influxfin.com%3Fpromote_code%3DD221BL0K&chs=200x200"
     *      }
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     *
     */
    /**
     *
    */
	public function promote_get()
    {
        $this->load->model('log/log_game_model');
        $this->not_support_company();
        $this->load->library('line_lib');
		$user_id 		  = $this->user_info->id;
		$promote_code	  = $this->user_info->my_promote_code;
        $url              = 'https://event.influxfin.com/R/url?p='.$promote_code;
		$qrcode			  = get_qrcode($url);
        $beginDate = '2021-09-20 00:00';
        $lastday = '2021-12-31 23:59';

//        $check= $this->line_lib->check_thirty_points();
//      if ($check !== 'success') {
//			  $this->response(array('result' => 'ERROR', 'error' => TARGET_IS_BUSY));
//      }

        //檢查是否有推薦其他人
        $promote_count = $this->user_model->getPromotedCount($promote_code,
            strtotime($beginDate),
            strtotime($lastday));
        $promotecount = count($promote_count);

        $collect_count= floor($promotecount/3);
		$my_detail    = $this->user_model->get_by([
			'id'  => $user_id
			]);

        $this->load->model('user/user_meta_model');
        $rs  = $this->user_meta_model->get_by([
			'user_id'  => $user_id,
			'meta_key'  => 'line_access_token'
			]);
        $my_line_id  = $rs ? $rs->meta_value : '';
        $this->load->library('game_lib');
		// if (!empty($my_line_id) && isset($my_detail->promote_code)) {
		// 	$promote_code=$my_detail->promote_code;
		// 	if($promote_code!== 'fbpost01'){
		// 		$this->game_lib->count_and_send_thirty_points($user_id, $my_line_id, $collect_count);
		// 	}

		// }
        $check_30send = $this->log_game_model->get_many_by(array("user_id"=>$user_id,"content"=>$my_line_id,"memo"=>'send_thirty_points'));
        $check_30send =count( $check_30send );
		$data = array(
            'promote_name'	           => '推薦有獎',
            'promote_code'	           => $promote_code,
			'promote_url'	           => $url,
			'promote_qrcode'           => $qrcode,
            'promote_count'            => count($promote_count),//檢查推薦幾人
            'collect_count'            => intval($collect_count), //跟30點有關 可領取次數
            'done_collect_count'       =>  intval($check_30send),//跟30點有關 已領取次數
            'game_status'              => true,
            'promote_endtime'          => $lastday
		);
		$this->response(array('result' => 'SUCCESS','data' => $data));
    }


	/**
     * @api {post} /v2/user/promote 會員   Line Point 活動
	 * @apiVersion 0.2.0
     *
    */
	public function promote_post()
    {
        $this->not_support_company();
		$this->load->model('log/log_game_model');
        $this->not_support_company();
        $this->load->library('line_lib');
		$user_id 		  = $this->user_info->id;
		$promote_code	  = $this->user_info->my_promote_code;
        $url              = 'https://event.influxfin.com/R/url?p='.$promote_code;
		$qrcode			  = get_qrcode($url);
        $beginDate = '2021-09-20 00:00';
        $lastday = '2021-12-31 23:59';

//        $check= $this->line_lib->check_thirty_points();
//        if ($check !== 'success') {
//			$this->response(array('result' => 'ERROR', 'error' => TARGET_IS_BUSY));
//        }

        //檢查是否有推薦其他人
        $promote_count = $this->user_model->getPromotedCount($promote_code,
            strtotime($beginDate),
            strtotime($lastday));
        $promotecount = count($promote_count);

        $collect_count= floor($promotecount/3);
		$my_detail    = $this->user_model->get_by([
			'id'  => $user_id
			]);

        $this->load->model('user/user_meta_model');
        $my_line_id  = $this->user_meta_model->get_by([
			'user_id'  => $user_id,
			'meta_key'  => 'line_access_token'
			])->meta_value;
        $this->load->library('game_lib');
		if (!empty($my_line_id) && isset($my_detail->promote_code)) {
			$promote_code=$my_detail->promote_code;
			if($promote_code!== 'fbpost01'){
				$this->game_lib->count_and_send_thirty_points($user_id, $my_line_id, $collect_count);
			}

		}
            $this->response(array('result' => 'SUCCESS'));
    }

	/**
     * @api {post} /v2/user/upload_m 會員 上傳影片
	 * @apiVersion 0.2.0
	 * @apiName PostUserUploadM
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
     * @apiParam {file="*.mp4","*.mov"} video 影片檔
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} image_id 圖片ID
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "result": "SUCCESS",
     *      "data": {
     *      	"media_id": 191
     *      }
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     */
	public function upload_m_post()
    {
		$user_id = $this->user_info->id;
		$data 	 = [];
		//上傳檔案欄位
		if (isset($_FILES['media']) && !empty($_FILES['media'])) {
			$this->load->library('S3_upload');
			$media = $this->s3_upload->media_id($_FILES,'media',$user_id,'user_upload/'.$user_id);
			if($media){
				$data['media_id'] = $media;
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$this->response(['result' => 'SUCCESS','data' => $data]);
    }

	/**
     * @api {post} /v2/user/upload 會員 上傳圖片
	 * @apiVersion 0.2.0
	 * @apiName PostUserUpload
     * @apiGroup User
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
     * @apiParam {file="*.jpg","*.png","*.gif"} image 圖片檔
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} image_id 圖片ID
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "result": "SUCCESS",
     *      "data": {
     *      	"image_id": 191
     *      }
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     */
	public function upload_post()
    {
		$user_id = $this->user_info->id;
		$data 	 = [];
		//上傳檔案欄位
		if (isset($_FILES['image']) && !empty($_FILES['image'])) {
            // 確認不是空檔案
            if ($_FILES['image']['size'] == 0) {
                $this->response(array('result' => 'ERROR','error' => FILE_IS_EMPTY ));
            }

			$this->load->library('S3_upload');
			$image = $this->s3_upload->image_id($_FILES,'image',$user_id,'user_upload/'.$user_id);
			if($image){
				$data['image_id'] = $image;
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$this->response(['result' => 'SUCCESS','data' => $data]);
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

	private function not_support_company(){
		if($this->user_info->company != 0 ){
			$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
		}
	}

    public function bioregister_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $data		= [];
        $fields 	= ['bio_type','device_id'];
        foreach ($fields as $field) {
            if (!isset($input[$field]) && !$input[$field]) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $data[$field] = $input[$field];
            }
        }
        $this->load->model('user/user_bio_model');

        $user_id    = $this->user_info->id;
        $investor   = $this->user_info->investor;
        $company   = $this->user_info->company;
        $bio_type       = $data['bio_type'];
        $device_id  = $data['device_id'];

        $token = (object)[
            'user_id'   => $user_id,
            'bio_type'  => $bio_type,
            'investor'  => $investor,
            'company'  => $company,
            'device_id' => $device_id,
            'auth_otp'  => get_rand_token(),
        ];
        $bio_key 		= AUTHORIZATION::generateUserToken($token);

        $registed = $this->user_bio_model->get_by(array(
            'user_id'	=> $user_id,
            'bio_type'	=> $bio_type,
            'investor'	=> $investor,
            'company'  => $company,
            'device_id' => $device_id,
        ));

        if($registed){
            $insert = $this->user_bio_model->update($registed->id,array(
                'bio_key'	=> $bio_key,
            ));
        }
        else{
            $insert = $this->user_bio_model->insert(array(
                'user_id'	=> $user_id,
                'bio_type'	=> $bio_type,
                'investor'	=> $investor,
                'company'  => $company,
                'device_id'	=> $device_id,
                'bio_key'	=> $bio_key,
            ));
        }
        if(!$insert) {
            $this->response(array('result' => 'ERROR','error' => KEY_FAIL ));
        }

        $this->response(array(
            'result' => 'SUCCESS',
            'data' 	 => array(
                'bio_key'   => $bio_key,
            )
        ));
    }

    public function biologin_post()
    {
        $bio_key 		= isset($this->input->request_headers()['bio_key'])?$this->input->request_headers()['bio_key']:'';
        $bio_keyData 	= AUTHORIZATION::getUserInfoByToken($bio_key);
        $input = $this->input->post(NULL, TRUE);
        $pdevice_id = isset($input['device_id'])?trim($input['device_id']):'';
        $location   = isset($input['location'])?trim($input['location']):'';
        $user_id    = $bio_keyData->user_id;
        $bio_type   = $bio_keyData->bio_type;
        $investor   = $bio_keyData->investor;
        $company   = isset($bio_keyData->company) ? $bio_keyData->company : 0;
        $device_id  = $bio_keyData->device_id;

        if ($pdevice_id != $bio_keyData->device_id ) {
            $this->response(array('result' => 'ERROR','error' => KEY_FAIL ));
        }


        $this->load->model('user/user_bio_model');
        $active = $this->user_bio_model->get_by(array(
            'user_id'	=> $user_id,
            'bio_type'	=> $bio_type,
            'investor'	=> $investor,
            'company'	=> $company,
            'device_id' => $device_id,
            'bio_key'   => $bio_key
        ));

        if($bio_keyData && isset($active)) {
            if($bio_key !== $active->bio_key) {
                $this->response(array('result' => 'ERROR','error' => KEY_FAIL ));
            }

            $user_info = $this->user_model->get($user_id);
            if ($user_info) {
                if ($user_info->block_status != 0) {
                    $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
                }

                $token = (object)[
                    'id' => $user_info->id,
                    'phone' => $user_info->phone,
                    'auth_otp' => get_rand_token(),
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                    'investor' => $investor,
                    'company' => $company,
                    'incharge' => 0,
                    'agent' => 0,
                ];
                $request_token = AUTHORIZATION::generateUserToken($token);
                $this->user_model->update($user_info->id, array('auth_otp' => $token->auth_otp));
                $this->insert_login_log($user_info->phone, $investor, 1, $user_info->id, $device_id,$location);

                //new biokey
                $ntoken = (object)[
                    'user_id' => $user_id,
                    'bio_type' => $bio_type,
                    'investor' => $investor,
                    'device_id' => $device_id,
                    'company' => $company,
                    'auth_otp' => get_rand_token(),
                ];
                $bio_key = AUTHORIZATION::generateUserToken($ntoken);

                $insert = $this->user_bio_model->update($active->id, array(
                    'bio_key' => $bio_key,
                ));

                if ($insert) {
                    $this->response([
                        'result' => 'SUCCESS',
                        'data' => [
                            'bio_key' => $bio_key,
                            'token' => $request_token,
                            'expiry_time' => $token->expiry_time,
                        ]
                    ]);
                }
                $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
            }
        }
        else{
            $this->response(array('result' => 'ERROR','error' => KEY_FAIL ));
        }
    }

    public function fraud_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $request_token = isset($input['request_token'])?$input['request_token']:'';
        $device_id     = isset($input['device_id'])?$input['device_id']:'';
        $location      = isset($input['location'])?$input['location']:'';
        $behavior      = isset($input['behavior'])?$input['behavior']:'';
        $token 		= isset($request_token)?$request_token:'';
        $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);

        $user_id  = isset($tokenData->id)?$tokenData->id:'';
        $identity = isset($tokenData->investor)?$tokenData->investor:'';

        $this->load->model('behavion/beha_user_model');
        $this->beha_user_model->insert(array(
            'user_id'	    => $user_id,
            'identity'	    => $identity,
            'device_id'	    => $device_id,
            'location'	    => $location,
            'behavior'	    => $behavior,
        ));

        $this->response(array(
            'result' => 'SUCCESS',
            'data' 	 => []
        ));
    }

    public function user_behavior_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $request_token = $input['request_token'] ?? '';
        $device_id     = $input['device_id'] ?? '';
        $action      = $input['action'] ?? '';
        $type      = $input['type'] ?? '';
        $data1      = $input['data1'] ?? '';
        $data2      = $input['data2'] ?? '';
        $json_data      = $input['json_data'] ?? '{}';
        $token 		= $request_token ?? '';
        $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);

        $user_id  = $tokenData->id ?? '';
        $investor = isset($tokenData->investor) ? $tokenData->investor + 1 : 0;

        $this->load->model('behavion/user_behavior_model');
        $this->user_behavior_model->insert(array(
            'user_id'	    => $user_id,
            'identity'	    => $investor,
            'device_id'	    => $device_id,
            'action'	    => $action,
            'type'	        => $type,
            'data1'	        => $data1,
            'data2'	        => $data2,
            'json_data'	    => $json_data,
        ));

        $this->response(array(
            'result' => 'SUCCESS',
            'data' 	 => []
        ));
    }

	private function insert_login_log($account='',$investor=0,$status=0,$user_id=0,$device_id=null,$location='',$os=''){
        $this->load->library('user_agent');
        $this->agent->device_id=$device_id;
        $this->load->model('log/log_userlogin_model');
		$loginLog = [
			'account'	=> $account,
			'investor'	=> intval($investor),
			'user_id'	=> intval($user_id),
			'location'	=> $location,
			'status'	=> intval($status),
			'os'		=> $os
		];
		$this->log_userlogin_model->insert($loginLog);

		$this->load->model('mongolog/user_login_log_model');
		$fullLoginLog = $this->log_userlogin_model->getCurrentInstance($loginLog);
		$this->user_login_log_model->save($fullLoginLog);

        $this->load->library('user_lib');
        $remind_count = $this->user_lib->auto_block_user($account,$investor,$user_id,$device_id);

        return $remind_count;
	}

    public function apply_promote_code_post()
    {
        $this->not_support_company();
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_certification_model');
        $user_id    = $this->user_info->id;
        $investor   = $this->user_info->investor;
        $promote_code = $this->user_info->my_promote_code;
        $promote_cert_list = $this->config->item('promote_code_certs');

        $alias_name = $this->qrcode_setting_model->generalCaseAliasName;

        $param = array(
            'user_id'			=> $user_id,
            'certification_id'	=> $promote_cert_list,
            'investor'			=> $investor,
            'status'            => [0,1,3,6],
        );
        $certList = $this->user_certification_model->order_by('created_at','desc')->get_many_by($param);
        $doneCertifications = [];
        $certifications = array_reduce($certList, function ($list, $item) use (&$doneCertifications){
            if(!isset($list[$item->certification_id])) {
                $list[$item->certification_id] = $item;
                if($item->status == 1)
                    $doneCertifications[$item->certification_id] = $item;
            }
            return $list;
        }, []);

        if(count($certifications) != count($promote_cert_list)){
            $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NEVER_VERIFY ));
        }

        $user_qrcode = $this->user_qrcode_model->get_by(['user_id' => $user_id, 'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY]]);
        if(isset($user_qrcode) && in_array($user_qrcode->status, [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_VERIFY])) {
            $this->response(array('result' => 'ERROR', 'error' => APPLY_EXIST));
        }

        $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $alias_name]);
        if(!isset($qrcode_settings)) {
            $this->response(array('result' => 'ERROR','error' => EXIT_DATABASE ));
        }

        if($promote_code == '') {
            $this->load->library('user_lib');
            $promote_code = $this->user_lib->get_promote_code($qrcode_settings->length, $qrcode_settings->prefix);
        }

        $settings = json_decode($qrcode_settings->settings, true);
        $settings['certification_id'] = array_column($certifications, 'id');
        $settings['description'] = $qrcode_settings->description;

        $this->load->library('contract_lib');
        $start_time = date('Y-m-d H:i:s');
        $end_time = date("Y-m-d H:i:s", strtotime("+ 1 year"));
        $contract_year = date('Y') - 1911;
        $contract_month = date('m');
        $contract_day = date('d');
        $contract_id = $this->contract_lib->sign_contract(PROMOTE_GENERAL_CONTRACT_TYPE_NAME, ['', $contract_year, $contract_month, $contract_day,
            $settings['reward']['product']['student']['amount'], $settings['reward']['product']['salary_man']['amount'], '', '', '',
            $contract_year, $contract_month, $contract_day]);

        $rs = FALSE;
        if(isset($user_qrcode)) {
            if($user_qrcode->status == PROMOTE_STATUS_PENDING_TO_SENT) {
                $rs = $this->user_qrcode_model->update_by(array('id' => $user_qrcode), PROMOTE_STATUS_PENDING_TO_VERIFY);
            }
        } else {
            $rs = $this->user_qrcode_model->insert(array(
                'user_id' => $user_id,
                'alias' => $alias_name,
                'promote_code' => $promote_code,
                'contract_id' => $contract_id,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'settings' => json_encode($settings),
                'status' => PROMOTE_STATUS_PENDING_TO_VERIFY,
            ));
        }

        if(count($doneCertifications) === count($promote_cert_list)){
            $this->load->library('Certification_lib');
            $this->certification_lib->verify_promote_code($doneCertifications[CERTIFICATION_IDCARD], FALSE);
        }

        if($rs) {
            $this->response(array('result' => 'SUCCESS', 'data' => []));
        }else{
            $this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
        }
    }

    public function promote_code_get()
    {
        $this->not_support_company();
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('admin/contract_format_model');
        $this->load->library('contract_lib');
        $this->load->library('user_lib');
        $user_id            = $this->user_info->id;
        $list = [];

        $data = array(
            'promote_name'	           => '',
            'promote_alias'	           => '',
            'promote_code'	           => '',
            'promote_url'	           => '',
            'promote_qrcode'           => '',
            'start_time'               => '',
            'expired_time'             => '',
            'contract'                 => '',
            'status'                   => 0,
            'total_reward_amount'      => 0,
            'overview'                 => [],
            'detail_list'              => [],
        );
        $categoryInitList = array_combine(array_keys($this->user_lib->rewardCategories), array_fill(0,count($this->user_lib->rewardCategories), ['detail' => [], 'count' => 0, 'rewardAmount' => 0]));
        $initList = array_merge_recursive(['registered' => [], 'registeredCount' => 0, 'fullMember' => [], 'fullMemberCount' => 0, 'fullMemberRewardAmount' => 0], $categoryInitList);
        $contract_format             = $this->contract_format_model->order_by('created_at','desc')->get_by(['type' => PROMOTE_GENERAL_CONTRACT_TYPE_NAME]);
        if(isset($contract_format)) {
            $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $this->qrcode_setting_model->generalCaseAliasName]);
            if($qrcode_settings) {
                $settings = json_decode($qrcode_settings->settings, true);
                $contract_year = date('Y') - 1911;
                $contract_month = date('m');
                $contract_day = date('d');
                // PROMOTE_GENERAL_CONTRACT_TYPE_NAME
                $data['contract'] = vsprintf($contract_format->content, ['', $contract_year, $contract_month, $contract_day,
                    $settings['reward']['product']['student']['amount'], $settings['reward']['product']['salary_man']['amount'], '', '', '',
                    $contract_year, $contract_month, $contract_day]);
            }
        }

        $userQrcode         = $this->user_lib->getPromotedRewardInfo(['user_id' => $user_id, 'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY]]);
        if(isset($userQrcode) && !empty($userQrcode)) {
            $userQrcode         = reset($userQrcode);
            $userQrcodeInfo     = $userQrcode['info'];
            $settings           = $userQrcodeInfo['settings'];
            $promote_code       = $userQrcodeInfo['promote_code'];
            $url                = 'https://event.influxfin.com/R/url?p='.$promote_code;
            $qrcode             = get_qrcode($url);
            $contract = $this->contract_lib->get_contract($userQrcodeInfo['contract_id']);

            if($userQrcodeInfo['status'] == PROMOTE_STATUS_AVAILABLE) {
                // 初始化結構
                try {
                    $d1 = new DateTime($userQrcodeInfo['start_time']);
                    $d2 = new DateTime($userQrcodeInfo['end_time'] >= date("Y-m-d H:i:s") ? date("Y-m-d H:i:s") : $userQrcodeInfo['end_time']);
                    $diffMonths = $d1->diff($d2)->m + ($d1->diff($d2)->y*12);
                } catch (Exception $e) {
                    $diffMonths = 0;
                    error_log($e->getMessage());
                }
                for ($i=0; $i<=$diffMonths; $i++) {
                    $date1 = date("Y-m", strtotime(date("Y-m", strtotime($userQrcodeInfo['start_time'])).'+'.$i.' MONTH'));
                    $list[$date1] = $initList;
                }

                // 處理產品案件獎金計算
                $keys = array_flip(['id', 'user_id', 'product_id', 'loan_amount', 'loan_date']);
                foreach ($this->user_lib->rewardCategories as $category => $productIdList) {
                    $rewardAmount = 0;
                    if(isset($settings['reward']) && isset($settings['reward']['product']))
                        $rewardAmount = $this->user_lib->getRewardAmountByProduct($settings['reward']['product'], $productIdList);

                    if(!isset($userQrcode[$category]) || empty($userQrcode[$category]))
                        continue;
                    foreach ($userQrcode[$category] as $value) {
                        $formattedMonth = date("Y-m", strtotime($value['loan_date']));
                        $list[$formattedMonth][$category]['detail'][] = array_intersect_key($value, $keys);
                        $list[$formattedMonth][$category]['count'] += 1;
                        $list[$formattedMonth][$category]['rewardAmount'] += $rewardAmount;
                        $data['total_reward_amount'] += $rewardAmount;
                    }
                }

                // 處理下載+註冊會員
                $keys = array_flip(['user_id', 'created_at']);
                foreach ($userQrcode['fullMember'] as $value) {
                    $formattedMonth = date("Y-m", strtotime($value['created_at']));
                    $list[$formattedMonth]['fullMember'][] = array_intersect_key($value, $keys);
                    $list[$formattedMonth]['fullMemberCount'] += 1;
                    $list[$formattedMonth]['fullMemberRewardAmount'] += intval($settings['reward']['full_member']['amount']);
                    $data['total_reward_amount'] += intval($settings['reward']['full_member']['amount']);
                }

                // 處理註冊會員
                $keys = array_flip(['user_id', 'created_at']);
                foreach ($userQrcode['registered'] as $value) {
                    $formattedMonth = date("Y-m", strtotime($value['created_at']));
                    $list[$formattedMonth]['registered'][] = array_intersect_key($value, $keys);
                    $list[$formattedMonth]['registeredCount'] += 1;
                }

                $data['promote_code']   = $userQrcodeInfo['promote_code'];
                $data['promote_url'] = $url;
                $data['promote_qrcode'] = $qrcode;
                $data['start_time']   = $userQrcodeInfo['start_time'];
                $data['expired_time']   = $userQrcodeInfo['end_time'];
                $data['detail_list']   = $list;

                $keys = array_flip(['loanedCount', 'rewardAmount', 'fullMemberCount']);
                $data['overview'] =  array_intersect_key($userQrcode, $keys);
            }
            $data['promote_name']   = $settings['description'] ?? '';
            $data['promote_alias']  = $userQrcodeInfo['alias'];
            $data['status'] = intval($userQrcodeInfo['status']);
            $data['contract'] = $contract ? $contract['content'] : "";

        }

        $this->response(array('result' => 'SUCCESS','data' => $data));
    }
}
