<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

class User extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
        $method 		= $this->router->fetch_method();
        $nonAuthMethods = ['register','registerphone','login','login_new_app','sociallogin','smslogin','smsloginphone','forgotpw','credittest','biologin','fraud', 'user_behavior', 'charity_institutions','donate_anonymous'];
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

            // 取得 JWT token
            try
            {
                $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : '';
                $request_method = $this->request->method ?? "";

                if (empty($token))
                {
                    $result['error'] = TOKEN_NOT_CORRECT;
                    goto END;
                }
                $this->load->library('user_lib');
                $personal_user_info = $this->user_lib->parse_token($token, $request_method, $this->uri->uri_string());
            }
            catch (Exception $e)
            {
                $result['error'] = $e->getCode();
                goto END;
            }

            // 確認自然人需通過實名認證
            $this->load->library('Certification_lib');
            $user_certification = $this->certification_lib->get_certification_info($personal_user_info->id, CERTIFICATION_IDENTITY,
                $personal_user_info->investor);
            if ( ! $user_certification || $user_certification->status != CERTIFICATION_STATUS_SUCCEED)
            {
                $result['error'] = NO_CER_IDENTITY;
                goto END;
            }

            // 確認自然人姓名與登記公司負責人一樣
            try
            {
                $this->load->library('gcis_lib');
                $is_business_responsible = $this->gcis_lib->is_business_responsible($input['tax_id'], $personal_user_info->name);
                if ( ! $is_business_responsible)
                {
                    $result['error'] = NOT_IN_CHARGE;
                    goto END;
                }
            }
            catch (Exception $e)
            {
                $result['error'] = $e->getCode();
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

                if ($new_id)
                {
                    $this->load->model('user/user_certification_model');
                    $this->user_certification_model->insert([
                        'user_id' => $new_id,
                        'certification_id' => CERTIFICATION_TARGET_APPLY,
                        'investor' => USER_INVESTOR,
                        'content' => '',
                        'remark' => '',
                        'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
                    ]);
                }

                // 若該法人之自然人帳號不存在，則自動建立其自然人帳號
                $company_user_already_exist = $this->user_model->get_by([
                    'phone' => $input['phone'],
                    // 法人狀態: 0=未啟用
                    'company_status' => 0
                ]);
                if (!$company_user_already_exist) {
                    $responsible_user_id = $this->user_model->insert($new_account_data);
                }else{
                    $responsible_user_id = $company_user_already_exist->id;
                }

                $company_meta = [
                    [
                        'user_id' => $new_id,
                        'meta_key' => 'company_responsible_user_id',
                        'meta_value' => (int)$responsible_user_id,
                    ]
                    ];
                $this->load->model('user/user_meta_model');
                $this->user_meta_model->insert_many($company_meta);
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
    * @api {post} /v2/user/login_new_app 新版會員 用戶登入
	 * @apiVersion 0.3.0
	 * @apiName PostUserLoginNewApp
     * @apiGroup User
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{9,}} company_user_id 帳號(目前僅開放法人身份)
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
    public function login_new_app_post()
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
        if ( ! empty($input['company_user_id']))
        {
            // 法人
            $user_info = $this->user_model->get_by([
                'user_id' => sha1($input['company_user_id']),
                'phone' => $input['phone'],
                'company_status' => 1
            ]);
        }
        elseif ( ! empty($input['tax_id']))
        { // todo: 因官網尚未同步 APP 以「手機＋帳號＋密碼」登入，故保留原登入方式
            // 法人
            $user_info = $this->user_model->get_by([
                'id_number' => $input['tax_id'],
                'phone' => $input['phone'],
                'company_status' => 1
            ]);
        }
        else {
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
                if (isset($input['company_user_id']) || isset($input['tax_id']))
                {
                    $this->load->model('user/judicial_person_model');
                    $charge_person = $this->judicial_person_model->check_valid_charge_person($user_info->id_number, $user_info->id);
                    if ($charge_person) {
                        $userData = $this->user_model->get($charge_person->user_id);
                        $userData ? $is_charge = 1 : '';
                    }

                    // 針對法人進行法人與負責人的綁定
                    $this->load->model('user/user_meta_model');
                    $rs = $this->user_meta_model->get_by(['user_id' => $user_info->id, 'meta_key' => 'company_responsible_user_id']);
                    if ( ! isset($rs))
                    {
                        $responsible_user_info = $this->user_model->get_by([
                            'phone' => $input['phone'],
                            'company_status' => 0
                        ]);
                        if(isset($responsible_user_info))
                        {
                            $company_meta = [
                                [
                                    'user_id' => $user_info->id,
                                    'meta_key' => 'company_responsible_user_id',
                                    'meta_value' => $responsible_user_info->id,
                                ]
                            ];

                            $this->user_meta_model->insert_many($company_meta);
                        }
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
                    'company'       => (isset($input['company_user_id']) || isset($input['tax_id'])) ? 1 : 0,
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
     * @api {post} /v2/user/login 舊版會員 用戶登入
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

                    // 針對法人進行法人與負責人的綁定
                    $this->load->model('user/user_meta_model');
                    $rs = $this->user_meta_model->get_by(['user_id' => $user_info->id, 'meta_key' => 'company_responsible_user_id']);
                    if ( ! isset($rs))
                    {
                        $responsible_user_info = $this->user_model->get_by([
                            'phone' => $input['phone'],
                            'company_status' => 0
                        ]);
                        if(isset($responsible_user_info))
                        {
                            $company_meta = [
                                [
                                    'user_id' => $user_info->id,
                                    'meta_key' => 'company_responsible_user_id',
                                    'meta_value' => $responsible_user_info->id,
                                ]
                            ];

                            $this->user_meta_model->insert_many($company_meta);
                        }
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
     * @api {post} /v2/user/upload_sound_file 會員 上傳聲紋檔案
     * @apiVersion 0.2.0
     * @apiName PostUserUploadSoundFile
     * @apiGroup User
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiParam {file="*.mp4","*.mov"} media 媒體檔案
     * @apiParam {String} label 標籤註記
     * @apiParam {Number} group 群組編號(可選填，不給則回傳該使用者的最高group編號+1)
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} media_id 媒體id
     * @apiSuccess {Number} group 群組編號
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"media_id": 191,
     *      	"group": "2"
     *      }
     *    }
     *
     * @apiUse InputError
     * @apiUse TokenError
     * @apiUse BlockUser
     *
     */
    public function upload_sound_file_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $inputData		= [];
        $result         = [];
        $fields 	= ['label'];
        $user_id = $this->user_info->id;
        foreach ($fields as $field) {
            if (!isset($input[$field]) || !$input[$field]) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $inputData[$field] = $input[$field];
            }
        }

        if(!isset($input['group'])) {
            $this->load->model('user/sound_record_model');
            $soundRecord = $this->sound_record_model->
                get_many_by(['user_id' => $user_id, 'status' => 1]);
            if(!empty($soundRecord)) {
                $soundRecord = end($soundRecord);
                $inputData['group'] = $soundRecord->group + 1;
            }else
                $inputData['group'] = 1;
        }else{
            $inputData['group'] = $input['group'];
        }

        //上傳檔案欄位
        if (isset($_FILES['media']) && !empty($_FILES['media'])) {
            $this->load->library('S3_upload');
            $media = $this->s3_upload->media_id($_FILES,'media',$user_id,'user_upload/sound/'.$user_id,2,$inputData);
            if($media){
                $result['media_id'] = $media;
                $result['group'] = $inputData['group'];
            }else{
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }else{
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
        }

        $this->response(['result' => 'SUCCESS','data' => $result]);
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
        $this->load->library('certification_lib');
        $this->load->library('user_lib');
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_certification_model');
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $company = $this->user_info->company;
        $promote_code = $this->user_info->my_promote_code;

        $alias_name = '';
        if ($company == USER_NOT_COMPANY)
        {
            // 一般方案 qrcode_setting 的 alias
            $promote_cert_list = $this->config->item('promote_code_certs');
            $alias_name = $this->qrcode_setting_model->generalCaseAliasName;
        }
        else
        {
            // 確認負責人需通過實名認證
            try
            {
                $responsible_user = $this->user_lib->get_identified_responsible_user($user_id, $investor);
            }
            catch (Exception $e)
            {
                $this->response(array('result' => 'ERROR', 'error' => $e->getCode(), 'msg' => $e->getMessage()));
            }

            // 特約方案 qrcode_setting 的 alias
            $promote_cert_list = $this->config->item('promote_code_certs_company');
            $alias_name = $this->qrcode_setting_model->appointedCaseAliasName;
        }

        $certifications = [];
        $doneCertifications = [];
        if ( ! empty($promote_cert_list))
        {
            $param = array(
                'user_id' => $user_id,
                'certification_id' => $promote_cert_list,
                'investor' => $investor,
                'status' => [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_SUCCEED,
                    CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_AUTHENTICATED],
            );
            $certList = $this->user_certification_model->order_by('created_at', 'desc')->get_many_by($param);
            $certifications = array_reduce($certList, function ($list, $item) use (&$doneCertifications) {
                if ( ! isset($list[$item->certification_id]))
                {
                    $list[$item->certification_id] = $item;
                    if ($item->status == CERTIFICATION_STATUS_SUCCEED)
                        $doneCertifications[$item->certification_id] = $item;
                }
                return $list;
            }, []);
        }

        if (count($certifications) != count($promote_cert_list))
        {
            $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NEVER_VERIFY));
        }

        $user_qrcode = $this->user_qrcode_model->get_by(['user_id' => $user_id, 'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY]]);
        if (isset($user_qrcode) && in_array($user_qrcode->status, [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_VERIFY]))
        {
            $this->response(array('result' => 'ERROR', 'error' => APPLY_EXIST));
        }

        $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $alias_name]);
        if ( ! isset($qrcode_settings))
        {
            $this->response(array('result' => 'ERROR', 'error' => EXIT_DATABASE));
        }

        if ($promote_code == '')
        {
            $this->load->library('user_lib');
            $promote_code = $this->user_lib->get_promote_code($qrcode_settings->length, $qrcode_settings->prefix);
        }

        $settings = json_decode($qrcode_settings->settings, TRUE);
        $settings['certification_id'] = array_column($certifications, 'id');
        $settings['description'] = $qrcode_settings->description;
        $settings['investor'] = $investor;

        $this->load->library('contract_lib');
        $this->load->library('qrcode_lib');
        $start_time = date('Y-m-d H:i:s');
        $end_time = date("Y-m-d H:i:s", strtotime("+ 1 year"));

        if ($company == USER_NOT_COMPANY)
        {
            $contract_type_name = PROMOTE_GENERAL_CONTRACT_TYPE_NAME;
            $user_info = $this->user_model->get($user_id);
            $name = $user_info->name ?? '';
            $address = $user_info->address ?? '';
        }
        else
        {
            $contract_type_name = PROMOTE_APPOINTED_CONTRACT_TYPE_NAME;
            $this->load->model('user/judicial_person_model');
            $judicial_person_info = $this->judicial_person_model->get_by(['company_user_id' => $user_id]);
            $name = $judicial_person_info->company ?? '';
            $address = $judicial_person_info->cooperation_address ?? '';
        }

        $rs = FALSE;
        if (isset($user_qrcode))
        {
            if ($user_qrcode->status == PROMOTE_STATUS_PENDING_TO_SENT)
            {
                // 不是特約方案才會直接到待審核
                if ($alias_name != $this->qrcode_setting_model->appointedCaseAliasName)
                {
                    $rs = $this->user_qrcode_model->update_by(['id' => $user_qrcode->id],
                        ['status' => PROMOTE_STATUS_PENDING_TO_VERIFY]);
                }
                else
                {
                    $rs = $this->user_qrcode_model->update_by(['id' => $user_qrcode->id],
                        [
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                            'contract_end_time' => $end_time
                        ]);
                }
            }
        }
        else
        {
            $contract = $this->qrcode_lib->get_contract_format_content($contract_type_name, $name, $address, $settings);
            $contract_id = $this->contract_lib->sign_contract($contract_type_name, $contract);

            switch ($alias_name)
            {
                case $this->qrcode_setting_model->appointedCaseAliasName:
                    $status = PROMOTE_STATUS_PENDING_TO_SENT;
                    break;
                default:
                    $status = PROMOTE_STATUS_PENDING_TO_VERIFY;
                    break;
            }

            $rs = $this->user_qrcode_model->insert([
                'user_id' => $user_id,
                'alias' => $alias_name,
                'promote_code' => $promote_code,
                'contract_id' => $contract_id,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'contract_end_time' => $end_time,
                'settings' => json_encode($settings),
                'status' => $status,
            ]);
        }

        // 把待送出審核的狀態改為待驗證
        if ( ! empty($settings['certification_id']))
        {
            $this->user_certification_model->update_by([
                'id' => $settings['certification_id'],
                'user_id' => $user_id,
                'investor' => $investor,
                'status' => CERTIFICATION_STATUS_AUTHENTICATED
            ], ['status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE]);
        }

        // 特約方案需增加過審合約
        $user_qrcode = $this->user_qrcode_model->get_by([
            'user_id' => $user_id,
            'alias' => $alias_name,
            'status != ' => PROMOTE_STATUS_DISABLED
        ]);
        if (isset($user_qrcode) && $user_qrcode->alias == $this->qrcode_setting_model->appointedCaseAliasName)
        {
            $this->load->model('user/user_qrcode_apply_model');
            $this->load->model('admin/contract_format_model');
            $this->load->library('qrcode_lib');
            $contract_format = $this->contract_format_model->get_by(['type' => PROMOTE_APPOINTED_CONTRACT_TYPE_NAME]);

            $qrcode_apply = $this->user_qrcode_apply_model->get_by(['user_qrcode_id' => $user_qrcode->id, 'status !=' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
            if ( ! isset($qrcode_apply))
            {
                $rs = $this->user_qrcode_apply_model->insert([
                    'user_qrcode_id' => $user_qrcode->id,
                    'status' => PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP,
                    'contract_format_id' => $contract_format->id ?? 0,
                    'contract_content' => json_encode($this->qrcode_lib->get_contract_format_content(PROMOTE_APPOINTED_CONTRACT_TYPE_NAME, $name, $address)),
                ]);
            }
            else if ($user_qrcode->status == PROMOTE_STATUS_PENDING_TO_SENT && $qrcode_apply->status == PROMOTE_REVIEW_STATUS_SUCCESS)
            {
                $rs = $this->user_qrcode_model->update_by(['id' => $user_qrcode->id],
                    ['status' => PROMOTE_STATUS_PENDING_TO_VERIFY]);
            }
        }

        if (count($doneCertifications) === count($promote_cert_list))
        {
            $this->load->library('Certification_lib');
            if ($company == USER_NOT_COMPANY && isset($doneCertifications[CERTIFICATION_IDENTITY]))
            {
                $this->certification_lib->verify_promote_code($doneCertifications[CERTIFICATION_IDENTITY], FALSE);
            }
            else if ($company == USER_IS_COMPANY && isset($doneCertifications[CERTIFICATION_GOVERNMENTAUTHORITIES]))
            {
                $this->certification_lib->verify_promote_code($doneCertifications[CERTIFICATION_GOVERNMENTAUTHORITIES], FALSE);
            }
        }

        if ($rs)
        {
            $this->response(array('result' => 'SUCCESS', 'data' => []));
        }
        else
        {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
        }
    }

    public function promote_code_get()
    {
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('admin/contract_format_model');
        $this->load->model('user/qrcode_collaborator_model');
        $this->load->library('contract_lib');
        $this->load->library('user_lib');
        $this->load->library('qrcode_lib');
        $user_id = $this->user_info->id;
        $company = $this->user_info->company;
        $investor = $this->user_info->investor;
        $list = [];

        $data = array(
            'promote_name' => '',
            'promote_alias' => '',
            'promote_code' => '',
            'promote_url' => '',
            'promote_qrcode' => '',
            'start_time' => '',
            'expired_time' => '',
            'contract' => '',
            'status' => PROMOTE_STATUS_DISABLED,
            'total_reward_amount' => 0,
            'overview' => [],
            'detail_list' => [],
        );

        // 建立合作方案的初始化資料結構
        $collaboratorList = json_decode(json_encode($this->qrcode_collaborator_model->get_many_by(['status' => PROMOTE_COLLABORATOR_AVAILABLE])), TRUE) ?? [];
        $collaboratorList = array_column($collaboratorList, NULL, 'id');
        $collaboratorInitList = array_combine(array_keys($collaboratorList), array_fill(0, count($collaboratorList), ['detail' => [], 'count' => 0, 'rewardAmount' => 0]));
        foreach ($collaboratorInitList as $collaboratorIdx => $value)
        {
            $collaboratorInitList[$collaboratorIdx]['collaborator'] = $collaboratorList[$collaboratorIdx]['collaborator'];
        }

        // 建立各產品的初始化資料結構
        $categoryInitList = array_combine(array_keys($this->user_lib->rewardCategories), array_fill(0, count($this->user_lib->rewardCategories), ['detail' => [], 'count' => 0, 'rewardAmount' => 0]));
        $initList = array_merge_recursive(['registered' => [], 'registeredCount' => 0, 'registeredRewardAmount' => 0, 'fullMember' => [], 'fullMemberCount' => 0, 'fullMemberRewardAmount' => 0], $categoryInitList);
        $initList = array_merge_recursive($initList, ['collaboration' => $collaboratorInitList]);

        if ( ! $company)
        {
            $contract_type_name = PROMOTE_GENERAL_CONTRACT_TYPE_NAME;
            $alias = $this->qrcode_setting_model->generalCaseAliasName;
        }
        else
        {
            $contract_type_name = PROMOTE_APPOINTED_CONTRACT_TYPE_NAME;
            $alias = $this->qrcode_setting_model->appointedCaseAliasName;
        }

        $contract_format = $this->contract_format_model->order_by('created_at', 'desc')->get_by(['type' => $contract_type_name]);
        if (isset($contract_format))
        {
            $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $alias]);
            if ($qrcode_settings)
            {
                $settings = json_decode($qrcode_settings->settings, TRUE);
                $data['contract'] = vsprintf($contract_format->content,
                    $this->qrcode_lib->get_contract_format_content($contract_type_name, '', '', $settings));
            }
        }

        $where = ['user_id' => $user_id, 'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY],
            'subcode_flag' => IS_NOT_PROMOTE_SUBCODE];

        $userQrcode = $this->qrcode_lib->get_promoted_reward_info($where);
        if (isset($userQrcode) && ! empty($userQrcode))
        {
            $userQrcode = reset($userQrcode);
            $userQrcodeInfo = $userQrcode['info'];
            $settings = $userQrcodeInfo['settings'];
            $promote_code = $userQrcodeInfo['promote_code'];
            $url = 'https://event.influxfin.com/R/url?p=' . $promote_code;
            $qrcode = get_qrcode($url);

            $contract = "";
            if ($userQrcodeInfo['status'] == PROMOTE_STATUS_AVAILABLE)
            {
                $contract = $this->contract_lib->get_contract($userQrcodeInfo['contract_id']);

                // 初始化結構
                try
                {
                    $d1 = new DateTime($userQrcodeInfo['start_time']);
                    $d2 = new DateTime($userQrcodeInfo['end_time'] >= date("Y-m-d H:i:s") ? date("Y-m-d H:i:s") : $userQrcodeInfo['end_time']);
                    $start = date_create($d1->format('Y-m-01'));
                    $end = date_create($d2->format('Y-m-01'));
                    $diffMonths = $start->diff($end)->m + ($start->diff($end)->y * 12) + ($start->diff($end)->d > 0 ? 1 : 0);
                }
                catch (Exception $e)
                {
                    $diffMonths = 0;
                    error_log($e->getMessage());
                }
                for ($i = 0; $i <= $diffMonths; $i++)
                {
                    $date = date("Y-m", strtotime(date("Y-m", strtotime($userQrcodeInfo['start_time'])) . '+' . $i . ' MONTH'));
                    $list[$date] = $initList;
                }

                $keys = array_flip(['loanedCount', 'fullMemberCount']);
                $data['overview'] = array_intersect_key($userQrcode, $keys);
                $data['overview']['collaboration'] = $collaboratorInitList;
                $data['overview']['rewardAmount'] = array_combine(array_keys($this->user_lib->rewardCategories), array_fill(0, count($this->user_lib->rewardCategories), 0));

                // 套用特約方案的每月利息結算之獎金
                $list = array_replace_recursive($list, $userQrcode['monthly']);

                // 處理產品案件獎金計算
                $keys = array_flip(['id', 'user_id', 'product_id', 'loan_amount', 'loan_date']);
                foreach ($this->user_lib->rewardCategories as $category => $productIdList)
                {
                    $rewardAmount = 0;

                    if (isset($settings['reward']) && isset($settings['reward']['product']))
                    {
                        $rewardAmount = $this->user_lib->getRewardAmountByProduct($settings['reward']['product'], $productIdList);
                    }
                    if ( ! isset($userQrcode[$category]) || empty($userQrcode[$category]))
                        continue;

                    foreach ($userQrcode[$category] as $value)
                    {
                        $formattedMonth = date("Y-m", strtotime($value['loan_date']));
                        $list[$formattedMonth][$category]['detail'][] = array_intersect_key($value, $keys);
                        $list[$formattedMonth][$category]['count'] += 1;
                        $list[$formattedMonth][$category]['rewardAmount'] += $rewardAmount;

                        if (isset($list[$formattedMonth][$category]['borrowerPlatformFee']))
                        {
                            unset($list[$formattedMonth][$category]['borrowerPlatformFee']);
                        }
                        if (isset($list[$formattedMonth][$category]['investorPlatformFee']))
                        {
                            unset($list[$formattedMonth][$category]['investorPlatformFee']);
                        }

                        $data['overview']['rewardAmount'][$category] += $list[$formattedMonth][$category]['rewardAmount'];
                        $data['total_reward_amount'] += $list[$formattedMonth][$category]['rewardAmount'];
                    }
                }

                // 處理合作資料的計算
                $keys = array_flip(['loan_time']);
                foreach ($userQrcode['collaboration'] as $collaboratorId => $collaborationList)
                {
                    if ( ! isset($collaboratorList[$collaboratorId]))
                    {
                        continue;
                    }
                    $collaborationRewardAmount = $this->user_lib->getCollaborationRewardAmount($settings['reward'], $collaboratorList[$collaboratorId]['type']);
                    foreach ($collaborationList as $value)
                    {
                        $formattedMonth = date("Y-m", strtotime($value['loan_time']));
                        $list[$formattedMonth]['collaboration'][$collaboratorId]['detail'][] = array_intersect_key($value, $keys);
                        $list[$formattedMonth]['collaboration'][$collaboratorId]['count'] += 1;
                        $list[$formattedMonth]['collaboration'][$collaboratorId]['rewardAmount'] += $collaborationRewardAmount;
                        $data['total_reward_amount'] += $collaborationRewardAmount;
                        $data['overview']['collaboration'][$collaboratorId]['count'] += 1;
                        $data['overview']['collaboration'][$collaboratorId]['rewardAmount'] += $collaborationRewardAmount;
                    }
                }

                // 將合作資料轉為整數索引陣列
                $data['overview']['collaboration'] = array_values($data['overview']['collaboration']);
                foreach ($list as $key => $value)
                {
                    $list[$key]['collaboration'] = array_values($value['collaboration']);
                }

                // 處理下載+註冊會員
                $keys = array_flip(['user_id', 'created_at']);
                foreach ($userQrcode['fullMember'] as $value)
                {
                    $formattedMonth = date("Y-m", strtotime($value['created_at']));
                    $list[$formattedMonth]['fullMember'][] = array_intersect_key($value, $keys);
                    $list[$formattedMonth]['fullMemberCount'] += 1;
                    $list[$formattedMonth]['fullMemberRewardAmount'] += intval($settings['reward']['full_member']['amount']);
                    $data['total_reward_amount'] += intval($settings['reward']['full_member']['amount']);
                }

                // 處理註冊會員
                $keys = array_flip(['user_id', 'created_at']);
                $reward_registered_amount = (int) ($settings['reward']['registered']['amount'] ?? 0);
                foreach ($userQrcode['registered'] as $value)
                {
                    $formattedMonth = date("Y-m", strtotime($value['created_at']));
                    $list[$formattedMonth]['registered'][] = array_intersect_key($value, $keys);
                    $list[$formattedMonth]['registeredCount'] += 1;
                    $list[$formattedMonth]['registeredRewardAmount'] += $reward_registered_amount;
                    $data['total_reward_amount'] += $reward_registered_amount;
                }

                $data['promote_code'] = $userQrcodeInfo['promote_code'];
                $data['promote_url'] = $url;
                $data['promote_qrcode'] = $qrcode;
                $data['start_time'] = $userQrcodeInfo['start_time'];
                $data['expired_time'] = $userQrcodeInfo['end_time'];
                $data['detail_list'] = $list;
            }
            $data['promote_name'] = $settings['description'] ?? '';
            $data['promote_alias'] = $userQrcodeInfo['alias'];
            $data['status'] = intval($userQrcodeInfo['status']);
            if ($company)
            {
                // 法人的 qrcode 狀態：
                // 預設狀態或無任何已通過審查合約紀錄   : 0 (app顯示待申請)
                // 點選我要申請後                   : 0->2 (app顯示合約審核中)
                // 經辦人員新增合約，且老闆審核過後    : 2->4 (app顯示合約，待進行簽約)
                // 法人按下同意合約進行申請後         : 4->3 (app顯示審核中)
                // 當該過的認證項都通過              : 3->1 (app顯示QR code和已收到的獎金)
                $this->load->model('user/user_qrcode_apply_model');
                $apply_info = $this->user_qrcode_apply_model->get_by(['user_qrcode_id' => $userQrcodeInfo['id']]);
                if (isset($apply_info))
                {
                    if ($userQrcodeInfo['status'] == PROMOTE_STATUS_PENDING_TO_SENT)
                    {
                        switch ($apply_info->status)
                        {
                            case PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP:
                            case PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW:
                                $data['status'] = PROMOTE_STATUS_PENDING_TO_SENT;
                                break;
                            case PROMOTE_REVIEW_STATUS_WITHDRAW:
                                $data['status'] = PROMOTE_STATUS_DISABLED;
                                break;
                            case PROMOTE_REVIEW_STATUS_SUCCESS:
                                $data['status'] = PROMOTE_STATUS_CAN_SIGN_CONTRACT;
                                $contract = $this->contract_lib->get_contract($userQrcodeInfo['contract_id']);
                                break;
                        }
                    }
                }
            }
            $data['contract'] = ! empty($contract) ? $contract['content'] : $data['contract'];
        }

        $this->response(array('result' => 'SUCCESS', 'data' => $data));
    }

    public function apply_subcode_post()
    {
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_subcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_certification_model');
        $this->load->library('certification_lib');
        $this->load->library('qrcode_lib');

        $user_id = $this->user_info->id;

        $master_user_qrcode = $this->user_qrcode_model->get_by(['user_id' => $user_id, 'status' => PROMOTE_STATUS_AVAILABLE]);
        if ( ! isset($master_user_qrcode))
        {
            $this->response(array('result' => 'ERROR', 'error' => PROMOTE_CODE_NOT_EXIST, 'msg' => '找不到合法的推薦主碼紀錄'));
        }
        $this->load->library('qrcode_lib');

        $this->load->library('user_lib');
        $promote_code = $this->user_lib->get_promote_code(8, 'SUB');

        $this->user_qrcode_model->trans_begin();
        $this->user_subcode_model->trans_begin();
        $rollback = function () {
            $this->user_qrcode_model->trans_rollback();
            $this->user_subcode_model->trans_rollback();
        };

        try
        {
            $record_num = $this->user_subcode_model->count_by([
                'master_user_qrcode_id' => $master_user_qrcode->id,
            ]);

            $new_qrcode_id = $this->user_qrcode_model->insert([
                'user_id' => 0,
                'alias' => $master_user_qrcode->alias,
                'promote_code' => $promote_code,
                'status' => PROMOTE_STATUS_AVAILABLE,
                'subcode_flag' => 1,
                'start_time' => date('Y-m-d H:i:s'),
                'end_time' => $master_user_qrcode->end_time,
                'contract_end_time' => '0000-00-00 00:00:00',
                'settings' => json_encode([]),
            ]);

            $user_subcode_id = $this->user_subcode_model->insert([
                'alias' => "經銷商".($record_num+1),
                'registered_id' => 0,
                'master_user_qrcode_id' => $master_user_qrcode->id,
                'user_qrcode_id' => (int) $new_qrcode_id,
            ]);

            if ( ! $new_qrcode_id || ! $user_subcode_id ||
                $this->user_qrcode_model->trans_status() === FALSE ||
                $this->user_subcode_model->trans_status() === FALSE)
            {
                throw new \Exception('新增qrcode失敗');
            }
            $this->user_qrcode_model->trans_commit();
            $this->user_subcode_model->trans_commit();
            $this->response(array('result' => 'SUCCESS', 'data' => ['subcode_id' => $user_subcode_id]));
        }
        catch (Exception $e)
        {
            $rollback();
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
        }
    }

    public function subcode_info_post()
    {
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_subcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_certification_model');
        $this->load->library('certification_lib');
        $this->load->library('qrcode_lib');

        $user_id = $this->user_info->id;

        $input = $this->input->post(NULL, TRUE);
        $id = isset($input['subcode_id']) ? trim($input['subcode_id']) : '';
        $alias = isset($input['alias']) ? trim($input['alias']) : '';
        $status = isset($input['status']) ? trim($input['status']) : NULL;
        if ($status != PROMOTE_STATUS_DISABLED)
        {
            $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '非禁用的操作不允許'));
        }

        $user_subcode = $this->qrcode_lib->get_subcode_list($user_id, ['id' => $id]);
        if ( empty($user_subcode))
        {
            $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '找不到合法的 subcode 紀錄'));
        }
        $user_subcode = reset($user_subcode);

        $subcode_param = [];
        $promote_param = [];
        if (isset($alias))
        {
            $subcode_param['alias'] = $alias;
        }
        if (isset($status) && $status == PROMOTE_STATUS_DISABLED)
        {
            $promote_param['status'] = $status;
            $promote_param['end_time'] = date('Y-m-d H:i:s');
        }

        if ( empty($subcode_param) && empty($promote_param))
        {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '輸入參數有誤'));
        }

        $rs = $this->qrcode_lib->update_subcode_info($user_subcode['id'] ?? '', $subcode_param, $promote_param);
        if ($rs)
        {
            $this->response(array('result' => 'SUCCESS', 'data' => []));
        }
        else
        {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
        }
    }

    public function subcode_list_get()
    {
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_subcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_certification_model');
        $this->load->library('certification_lib');
        $this->load->library('qrcode_lib');

        $user_id = $this->user_info->id;
        $company = $this->user_info->company;
        $list = [];

        if($company == USER_NOT_COMPANY)
        {
            $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => 'subcode 不支援自然人操作'));
        }

        $input = $this->input->get(NULL, TRUE);
        $id_str = isset($input['subcode_ids'])?trim($input['subcode_ids']):'';
        // 針對空元素透過 filter 過濾
        $ids = array_filter(explode(',', $id_str));

        $conditions = [];
        if(!empty($ids))
        {
            $conditions['id'] = $ids;
        }

        $user_subcode_list = $this->qrcode_lib->get_subcode_list($user_id, $conditions, ['status' => PROMOTE_STATUS_AVAILABLE]);
        foreach ($user_subcode_list as $user_subcode)
        {
            $keys = array_flip(['registered_id', 'alias', 'promote_code', 'status', 'start_time', 'end_time']);
            $data = array_intersect_key($user_subcode, $keys);

            $data['subcode_id'] = (int)$user_subcode['id'];
            $data['status'] = (int)$data['status'];
            $data['promote_url'] = 'https://event.influxfin.com/R/url?p='.$data['promote_code'];
            $data['promote_qrcode'] = get_qrcode('https://event.influxfin.com/R/url?p='.$data['promote_code']);
            $list[] = $data;
        }

        $this->response(array('result' => 'SUCCESS', 'data' => ['list' => $list]));
    }

    public function subcode_detail_get()
    {
        $this->load->library('qrcode_lib');

        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $list = [];
        $input = $this->input->get(NULL, TRUE);

        try
        {
            $list = $this->qrcode_lib->get_subcode_detail_list($user_id, $investor, $input['start_time'] ?? NULL, $input['end_time'] ?? NULL);
        }
        catch (Exception $e)
        {
            $this->response(array('result' => 'ERROR', 'error' => $e->getCode(), 'msg' => $e->getMessage()));
        }

        $this->response(array('result' => 'SUCCESS', 'data' => ['detail_list' => $list]));
    }

    public function subcode_detail_email_post()
    {
        $this->load->library('qrcode_lib');
        $this->load->library('spreadsheet_lib');
        $sent = FALSE;
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;

        try
        {
            $list = $this->qrcode_lib->get_subcode_detail_list($user_id, $investor, $input['start_time'] ?? NULL, $input['end_time'] ?? NULL);

            $data_rows = [];
            foreach ($list as $month => $reward_list)
            {
                foreach ($reward_list as $reward_info)
                {
                    if (empty($reward_info['list']))
                    {
                        continue;
                    }
                    $data_rows = array_merge($data_rows, $reward_info['list']);
                }
            }
            rsort($data_rows);

            $product_list = $this->config->item('product_list');
            $student_name = $product_list[PRODUCT_ID_STUDENT]['name'] ?? '';
            $salary_man_name = $product_list[PRODUCT_ID_SALARY_MAN]['name'] ?? '';
            $small_enterprise_name = $product_list[PRODUCT_SK_MILLION_SMEG]['name'] ?? '';
            $title_rows = [
                'alias' => ['name' => '經銷商', 'width' => 15, 'alignment' => ['h' => 'center','v' => 'center']],
                'category' => ['name' => '產品', 'width' => 10,'alignment' => ['h' => 'center','v' => 'center']],
                'loan_date' => ['name' => '成交日期', 'width' => 10,'alignment' => ['h' => 'center','v' => 'center']],
                'reward' => ['name' => '獎金', 'width' => 8, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC, 'alignment' => ['h' => 'center','v' => 'center']]
            ];

            $user = $this->user_model->get($user_id);

            $spreadsheet = $this->spreadsheet_lib->load($title_rows, $data_rows);
            $filepath = 'tmp/subcode_' . round(microtime(true) * 1000) .'.xlsx';

            $this->spreadsheet_lib->save($filepath, $spreadsheet);
            if (file_exists($filepath))
            {
                $title = '【普匯金融推薦有賞明細】';
                $content = '親愛的會員您好：<br> 　　茲寄送您推薦有賞明細列表，請您核對。<br>若有疑問請洽Line@粉絲團客服，我們將竭誠為您服務。<br>普匯金融科技有限公司　敬上 <br><p style="color:red;font-size:14px;"></p>';
                $this->load->library('sendemail');
                $sent = $this->sendemail->email_file_estatement($user->email, $title, $content, $filepath, '', $investor, '推薦碼明細.xlsx');
                unlink($filepath);
            }
            else
            {
                $this->response(array('result' => 'ERROR', 'error' => EXIT_UNKNOWN_FILE, 'msg' => '系統無法生成檔案'));
            }
        }
        catch (Exception $e)
        {
            $this->response(array('result' => 'ERROR', 'error' => $e->getCode(), 'msg' => $e->getMessage()));
        }

        $this->response(array('result' => 'SUCCESS', 'data' => ['sent' => $sent]));
    }

    public function charity_institutions_get()
    {
        $this->load->model('user/charity_institution_model');
        $this->load->model('admin/agreement_model');

        $list = [];
        $rs = $this->charity_institution_model->get_many_by(['status' => 1]);
        foreach ($rs as $value)
        {
            $agreement = $this->agreement_model->get($value->agreement_id);
            $agreementContent = $agreement->content ?? '';

            $list[] = [
                'alias' => $value->alias,
                'name' => $value->name,
                'min_amount' => intval($value->min_amount),
                'max_amount' => intval($value->max_amount),
                'agreement_title' => $agreement->name ?? '',
                'agreement' => $agreementContent,
            ];
        }
        $this->response(array('result' => 'SUCCESS', 'data' => array('list' => $list)));
    }

    public function donated_list_get()
    {
        $userId = $this->user_info->id;
        $investor = $this->user_info->investor;
        $input = $this->input->get(NULL, TRUE);
        $alias = '';
        $where = [
            'user_id' => $userId,
            'investor' => $investor,
        ];

        $this->load->model('transaction/charity_model');
        if ( ! empty($input['alias']))
        {
            $alias = $input['alias'];
        }

        $list = $this->charity_model->getDonatedList($alias, $where);
        $list = array_map(function ($value) {
            $donatorData = json_decode($value['data'], TRUE);

            return [
                'tx_datetime' => date('Y-m-d H:i:s', strtotime($value['tx_datetime'])),
                'amount' => intval($value['amount']),
                'donator_name' => $donatorData['name'],
                'donator_sex' => $this->user_info->sex == "M" ? "先生" : "小姐",
                'institution_name' => $value['name'],
            ];

        }, $list);
        $this->response(array('result' => 'SUCCESS', 'data' => array('list' => $list)));
    }

    public function donate_charity_post()
    {
        $userId = $this->user_info->id;
        $investor = $this->user_info->investor;
        $input = $this->input->post(NULL, TRUE);
        $data = [];
        $errorCode = 0;

        $fields = ['alias', 'amount'];
        foreach ($fields as $field)
        {
            if ( ! isset($input[$field]) || ! $input[$field])
            {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
            else
            {
                $data[$field] = $input[$field];
            }
        }

        $fields = ['receipt_id_number', 'receipt_address'];
        foreach ($fields as $field)
        {
            if (isset($input[$field]))
            {
                $data[$field] = $input[$field];
            }
        }

        $this->load->model('user/charity_institution_model');
        $institution = $this->charity_institution_model->get_by(['alias' => $data['alias'], 'status' => 1]);
        if ( ! isset($institution))
        {
            $this->response(array('result' => 'ERROR', 'error' => KEY_FAIL));
        }

        $this->load->model('user/judicial_person_model');
        $judical_person_info = $this->judicial_person_model->get_by(['id' => $institution->judicial_person_id]);
        if ( ! isset($judical_person_info))
        {
            $this->response(array('result' => 'ERROR', 'error' => EXIT_DATABASE));
        }

        $donateAmount = intval($data['amount']);
        if ($donateAmount < $institution->min_amount || $donateAmount > $institution->max_amount)
        {
            $this->response(array('result' => 'ERROR', 'error' => CHARITY_INVALID_AMOUNT));
        }

        $this->load->library('user_lib');
        $this->load->model('user/virtual_account_model');
        $virtual = $this->user_lib->getVirtualAccountPrefix($investor);
        $virtual_account = $this->virtual_account_model->setVirtualAccount($userId, $investor,
            VIRTUAL_ACCOUNT_STATUS_AVAILABLE, VIRTUAL_ACCOUNT_STATUS_USING, $virtual);
        if (empty($virtual_account))
        {
            $this->response(array('result' => 'ERROR', 'error' => EXIT_ERROR));
        }

        // 取得借款人虛擬帳戶餘額
        $this->load->library('transaction_lib');
        $funds = $this->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
        $balance = $funds['total'] - $funds['frozen'];

        $this->load->model('transaction/charity_model');
        $this->load->model('transaction/transaction_model');
        $this->load->model('transaction/virtual_passbook_model');
        $this->load->library('passbook_lib');

        try
        {
            if ($balance < $donateAmount)
            {
                $errorCode = NOT_ENOUGH_FUNDS;
                throw new ValueError('The balance is not enough.');
            }

            $this->transaction_model->trans_begin();
            $this->virtual_passbook_model->trans_begin();
            $this->charity_model->trans_begin();

            $transactions = [
                'source' => SOURCE_CHARITY,
                'entering_date' => get_entering_date(),
                'user_from' => $userId,
                'bank_account_from' => $virtual_account->virtual_account,
                'amount' => $donateAmount,
                'target_id' => 0,
                'investment_id' => 0,
                'instalment_no' => 0,
                'user_to' => $judical_person_info->company_user_id,
                'bank_account_to' => $institution->virtual_account,
                'status' => TRANSACTION_STATUS_PAID_OFF
            ];

            $tranRsId = $this->transaction_model->insert($transactions);
            if ( ! $tranRsId)
            {
                $errorCode = EXIT_DATABASE;
                throw new Exception('insert failed');
            }

            $data = array_intersect_key($data, array_flip(['receipt_id_number', 'receipt_address']));
            $data['name'] = $this->user_info->name;
            $data['email'] = $this->user_info->email;
            $data['phone'] = $this->user_info->phone;

            $this->passbook_lib->enter_account($tranRsId);
            if ( ! $this->charity_model->insert([
                'institution_id' => $institution->id,
                'user_id' => $userId,
                'investor' => $investor,
                'amount' => $donateAmount,
                'transaction_id' => $tranRsId,
                'tx_datetime' => date("Y-m-d H:i:s"),
                'receipt_type' => CHARITY_RECEIPT_TYPE_SINGLE_PAPER,
                'data' => json_encode($data),
            ]))
            {
                $errorCode = EXIT_DATABASE;
                throw new Exception('insert failed');
            }

            if ($this->transaction_model->trans_status() === TRUE && $this->virtual_passbook_model->trans_status() === TRUE
                && $this->charity_model->trans_status() === TRUE)
            {
                $this->transaction_model->trans_commit();
                $this->virtual_passbook_model->trans_commit();
                $this->charity_model->trans_commit();

            }
            else
            {
                $errorCode = EXIT_DATABASE;
                throw new Exception("transaction_status is invalid.");
            }

        }
        catch (Throwable $t)
        {
            $this->transaction_model->trans_rollback();
            $this->virtual_passbook_model->trans_rollback();
            $this->charity_model->trans_rollback();
        }
        finally
        {
            $this->virtual_account_model->setVirtualAccount($userId, $investor,
                VIRTUAL_ACCOUNT_STATUS_USING, VIRTUAL_ACCOUNT_STATUS_AVAILABLE, $virtual);
        }

        if ( ! $errorCode)
        {
            $this->response(array('result' => 'SUCCESS'));
        }
        else
        {
            $this->response(array('result' => 'ERROR', 'error' => $errorCode));
        }
    }

    /**
     * 慈善匿名捐款 - 查詢捐款紀錄
     * @param string  *$last5  帳號末五碼
     * @param integer *$amount 捐款金額
     * @param string  $name    捐款人|公司抬頭
     * @param string  $number  身份證字號|公司統編
     * 
     * @return void
     **/
    public function donate_anonymous_get()
    {
        $input = $this->input->get(NULL, TRUE);

        $error_msg = [
            INPUT_NOT_CORRECT => '輸入不正確資料。',
            CHARITY_RECORD_NOT_FOUND => '捐款紀錄不存在。',
        ];

        if ( ! isset($input['last5']) || ! isset($input['amount']) ||
            ! is_numeric($input['amount']) || $input['amount'] <= 0 ||
            ! preg_match('/[0-9]{5}/', $input['last5']))
        {
            $this->response([
                'result' => 'SUCCESS',
                'error' => INPUT_NOT_CORRECT,
                'data' => [
                    'msg' => $error_msg[INPUT_NOT_CORRECT],
                ],
            ]);
        }

        $this->load->model('transaction/anonymous_donate_model');
        $donate_list = $this->anonymous_donate_model->get_donates($input);
        if (empty($donate_list))
        {
            $this->response([
                'result' => 'SUCCESS',
                'error' => CHARITY_RECORD_NOT_FOUND,
                'data' => [
                    'msg' => $error_msg[CHARITY_RECORD_NOT_FOUND],
                ],
            ]);
        }

        $return_data = [
            'tx_datetime' => date('Y-m-d', time()),
            'amount' => $input['amount'],
            'donator_name' => '',
            'donator_sex' => '',
        ];

        // 如果用戶有填入身份證號的話才有辦法檢查與末五碼的關聯
        if ( ! empty($input['number']) || ! empty($input['name']))
        {
            $this->load->model('user/charity_anonymous_model');
            foreach ($donate_list as $key => $donate)
            {
                if ($donate['charity_anonymous_id'] != 0)
                {
                    $anonymous = $this->charity_anonymous_model->as_array()->get($donate['charity_anonymous_id']);
                    if ($input['name'] == $anonymous['name'] &&
                        $input['number'] == $anonymous['number'] &&
                        $input['name'] !== '')
                    {
                        $return_data['donator_name'] = $input['name'];
                        $return_data['donator_sex'] = '先生/小姐';
                        break;
                    }
                }
                else
                {
                    $anonymous_id = $this->charity_anonymous_model->get_anonymous($input);
                    if ($anonymous_id != 0)
                    {
                        $this->anonymous_donate_model->update($donate['id'], [
                            'charity_anonymous_id' => $anonymous_id,
                        ]);
                        if ($input['name'] !== '')
                        {
                            $return_data['donator_name'] = $input['name'];
                            $return_data['donator_sex'] = '先生/小姐';
                            break;
                        }
                    }
                }
            }
        }

        $this->response([
            'result' => 'SUCCESS',
            'data' => $return_data,
        ]);
    }

    /**
     * 慈善匿名捐款 - 遊客捐款
     * @param integer *$amount  捐款金額
     * @param string  $name     捐款人|公司抬頭
     * @param string  $number   身份證字號|公司統編
     * @param string  $phone    電話
     * @param string  $email    信箱
     * @param integer $upload   是否上傳國稅局 0:否, 1:是
     * @param integer $receipt  是否要紙本收據 0:否, 1:是
     * @param string  $address  收件地址
     * @param integer *$source  捐款來源 1:官網, 2:借款app, 3:投資app
     *
     * @return void
     **/
    public function donate_anonymous_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $alias = $input['alias'] ?? 'NTUH';

        $error_msg = [
            CHARITY_INVALID_AMOUNT => '無效的慈善捐款金額。',
            CHARITY_ILLEGAL_AMOUNT => '因AMC防制法規定：捐款金額 超過500,000元 請洽客服。',
        ];

        if ( ! is_numeric($input['amount']) || $input['amount'] <= 0)
        {
            $this->response([
                'result' => 'SUCCESS',
                'error' => CHARITY_INVALID_AMOUNT,
                'data' => [
                    'msg' => $error_msg[CHARITY_INVALID_AMOUNT],
                ],
            ]);
        }
        elseif ($input['amount'] >= 500000)
        {
            $this->response([
                'result' => 'SUCCESS',
                'error' => CHARITY_ILLEGAL_AMOUNT,
                'data' => [
                    'msg' => $error_msg[CHARITY_ILLEGAL_AMOUNT],
                ],
            ]);
        }

        $this->load->model('user/charity_anonymous_model');
        $anonymous_id = $this->charity_anonymous_model->anonymous_insert($input);

        // 取得慈善機構的虛擬帳戶
        $this->load->model('user/charity_institution_model');
        $institution = $this->charity_institution_model
            ->as_array()
            ->get_by([
                'alias' => $alias,
                'status' => 1,
            ]);

        if ($anonymous_id && $institution)
        {
            $this->response([
                'result' => 'SUCCESS',
                'data' =>
                [
                    'bank_code' => CATHAY_BANK_CODE,
                    'bank_account' => $institution['virtual_account'],
                    'charity_title' => $institution['name'],
                ],
            ]);
        }
        else
        {
            $this->response([
                'result' => 'ERROR',
                'error' => EXIT_ERROR,
                'data' => [
                    'msg' => 'generic error',
                ],
            ]);
        }
    }

    public function user_bankaccount_get()
    {
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $this->load->model('user/user_bankaccount_model');
        $bank_account = $this->user_bankaccount_model->get_by([
            'user_id' => $user_id,
            'investor' => $investor,
            'status' => 1,
            'verify' => 1,
        ]);

        $this->response(['result' => 'SUCCESS', 'data' => [
            'verify' => (int) ($bank_account->verify ?? 0),
            'status' => (int) ($bank_account->status ?? 0),
        ]]);
    }
}
