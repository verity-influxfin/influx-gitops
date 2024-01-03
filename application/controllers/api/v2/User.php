<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/REST_Controller.php');
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class User extends REST_Controller
{

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['register', 'registerphone', 'login', 'login_new_app', 'sociallogin', 'smslogin', 'smsloginphone', 'forgotpw', 'credittest', 'biologin', 'fraud', 'user_behavior', 'charity_institutions', 'donate_anonymous', 'check_phone', 'create_qrcode'];
        if (!in_array($method, $nonAuthMethods)) {
            $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : '';
            $tokenData = AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
                $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            $this->user_info = $this->user_model->get($tokenData->id);
            if ($tokenData->auth_otp != $this->user_info->auth_otp) {
                $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            if ($this->user_info->block_status != 0) {
                $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
            }

            if ($this->request->method != 'get') {
                $this->load->model('log/log_request_model');
                $this->log_request_model->insert([
                    'method' => $this->request->method,
                    'url' => $this->uri->uri_string(),
                    'investor' => $tokenData->investor,
                    'user_id' => $tokenData->id,
                    'agent' => $tokenData->agent,
                ]);
            }

            $this->user_info->investor = $tokenData->investor;
            $this->user_info->company = $tokenData->company;
            $this->user_info->incharge = $tokenData->incharge;
            $this->user_info->agent = $tokenData->agent;
            $this->user_info->expiry_time = $tokenData->expiry_time;
        }
        $this->load->library('log/Log_request_lib');
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

        $log_request_info = new Log_request_info($this->request->method, $this->uri->uri_string);
        $log_request_data = new Log_request_data($log_request_info, $input);
        $log_request_result = $this->log_request_lib->insert($log_request_data);
        
        $phone = isset($input['phone']) ? trim($input['phone']) : '';

        if (!preg_match('/^09[0-9]{8}$/', $phone)) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        // 檢查該手機在有效時間內註冊簡訊驗證碼是否正確
        $this->load->library('sms_lib');
        $code = $this->sms_lib->get_code($phone);
        if ($code && (time() - $code['created_at']) <= SMS_LIMIT_TIME && !is_development()) {
            $this->response(array('result' => 'ERROR', 'error' => VERIFY_CODE_BUSY));
        }

        $send_sms_result = $this->sms_lib->send_register($phone);
        if ($send_sms_result) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => SMS_SEND_FAIL));
        }
    }

    /**
     * @param int $user_id
     * @param int $investor
     * @param bool $company
     * @return bool
     * @throws Exception
     */
    private function create_qrcode(int $user_id, int $investor, bool $company): bool
    {
        // 產生 QRCode
        $this->load->library('qrcode_lib');
        $promote_code = $this->qrcode_lib->generate_general_qrcode($user_id, $investor, $company);
        if (!$promote_code) {
            log_message('error', "user_qrcode insert failed for user {$user_id}.");
            throw new Exception('QRCode 新增失敗', INSERT_ERROR);
        }
        $this->user_model->update($user_id, ['my_promote_code' => $promote_code]);

        return true;
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
            try {
                $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : '';
                $request_method = $this->request->method ?? "";

                if (empty($token)) {
                    $result['error'] = TOKEN_NOT_CORRECT;
                    goto END;
                }
                $this->load->library('user_lib');
                $personal_user_info = $this->user_lib->parse_token($token, $request_method, $this->uri->uri_string());
            } catch (Exception $e) {
                $result['error'] = $e->getCode();
                goto END;
            }

            // 確認自然人需通過實名認證
            $this->load->library('Certification_lib');
            $user_certification = $this->certification_lib->get_certification_info(
                $personal_user_info->id,
                CERTIFICATION_IDENTITY,
                $personal_user_info->investor
            );
            if (!$user_certification || $user_certification->status != CERTIFICATION_STATUS_SUCCEED) {
                $result['error'] = NO_CER_IDENTITY;
                goto END;
            }

            // 確認自然人姓名與登記公司負責人一樣
            try {
                $this->load->library('gcis_lib');
                $is_business_responsible = $this->gcis_lib->is_business_responsible($input['tax_id'], $personal_user_info->name);
                if (!$is_business_responsible) {
                    $result['error'] = NOT_IN_CHARGE;
                    goto END;
                }
                $company_info = $this->gcis_lib->get_company_president_info($input['tax_id']);
            } catch (Exception $e) {
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
            if ($company_already_exist) {
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

            $this->load->library('user_agent');
            $appIdentity = $this->agent->agent_string() ?? "";
            $app_status = (strpos($appIdentity, "PuHey") !== FALSE && $input['investor'] == 0) ? 1 : 0;

            // 新自然人帳號資料
            $new_account_data = [
                'phone' => $input['phone'],
                'password' => $input['password'],
                'promote_code' => isset($input['promote_code']) ? $input['promote_code'] : '',
                'status' => $input['investor'] ? 0 : 1,
                'investor_status' => $input['investor'] ? 1 : 0,
                'my_promote_code' => '',
                'auth_otp' => $opt_token,
                'app_status' => $app_status,
            ];


            // 使用sql transaction，確保資料一致性

            // 啟用SQL事務
            $this->db->trans_start();

            // 新增法人帳號
            if ($tax_id_exist) {
                // 新版app法人註冊改 POST api/v2/user/register_company
                // 建立法人帳號
                $new_company_user_param = [
                    'phone' => $input['phone'],
                    'id_number' => $input['tax_id'],
                    'password' => $input['password'],
                    // 啟用法人
                    'company_status' => 1,
                    'auth_otp' => $opt_token,
                    'name' => $company_info['company_name'] ?? '',
                ];
                $new_id = $this->user_model->insert($new_company_user_param);

                if ($new_id) {
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
                } else {
                    $responsible_user_id = $company_user_already_exist->id;
                }

                $company_meta = [
                    [
                        'user_id' => $new_id,
                        'meta_key' => 'company_responsible_user_id',
                        'meta_value' => (int) $responsible_user_id,
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
                    $this->load->model('user/user_certification_model');
                    if ($facebook_access_token) {
                        $this->load->library('facebook_lib');
                        $info = $this->facebook_lib->get_info($facebook_access_token);
                        $this->user_certification_model->insert([
                            'user_id' => $new_id,
                            'certification_id' => 4,
                            'investor' => $input['investor'],
                            'content' => json_encode(['facebook' => $info, 'instagram' => ''])
                        ]);
                    }

                    // QR Code
                    try {
                        // Generate promote code.
                        $this->create_qrcode($new_id, $input['investor'], FALSE);
                    } catch (Exception $e) {
                        log_message('error', $e->getMessage());
                        $result['error'] = INSERT_ERROR;
                        goto END;
                    }
                } else {
                    $result['error'] = INSERT_ERROR;
                    goto END;
                }
            }

            // 回傳創建帳號成功之token
            $token = (object) [
                'id' => $new_id,
                'phone' => $new_account_data['phone'],
                'auth_otp' => $new_account_data['auth_otp'],
                'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                'investor' => $new_account_data['investor_status'],
                'company' => $tax_id_exist ? 1 : 0,
                'incharge' => 0,
                'agent' => 0,
            ];
            $request_token = AUTHORIZATION::generateUserToken($token);
            $this->notification_lib->first_login($new_id, $input['investor']);
            $result = [
                'result' => 'SUCCESS',
                'data' => [
                    'token' => $request_token,
                    'expiry_time' => $token->expiry_time,
                    'first_time' => 1
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

    // 會員 法人註冊
    public function register_company_post()
    {
        try {
            $this->load->library('user_lib');
            $input = $this->input->post(NULL, TRUE);

            // 檢查必填
            if (empty($input['tax_id'])) {
                throw new Exception('必填欄位空白', INPUT_NOT_CORRECT);
            }

            // 檢查統編
            $this->user_lib->check_tax_id($input['tax_id']);

            // 檢查公司是否存在
            $company_info = $this->user_lib->get_exist_company_user_id($input['tax_id']);
            if (!empty($company_info['id'])) { // 存在
                $company_exist = TRUE;
                $exist_company_responsible_info = $this->user_model->get_id_by_condition([
                    'id_number' => $input['tax_id'],
                    'id' => $company_info['id'],
                    'phone' => $this->user_info->phone,
                    'company_status' => 1
                ]);
                if (empty($exist_company_responsible_info)) {
                    throw new Exception('非公司負責人', NOT_IN_CHARGE);
                }
            } else { // 不存在
                $company_exist = FALSE;
                if (empty($input['password']) || empty($input['company_user_id']) || empty($input['governmentauthorities_image'])) {
                    throw new Exception('必填欄位空白', INPUT_NOT_CORRECT);
                }
            }

            // 檢查密碼長度
            if (!empty($input['password'])) {
                $this->user_lib->check_password($input['password']);
            }

            // 檢查帳號
            if (!empty($input['company_user_id'])) {
                // 檢查帳號格式
                $this->user_lib->check_user_id_format($input['company_user_id']);
                // 檢查帳號是否存在
                $this->user_lib->check_distinct_user_id($input['company_user_id'], $input['tax_id']);
            }

            // 撈取負責人資料
            $responsible_user_info = $this->user_model->get_by([
                'phone' => $this->user_info->phone,
                'company_status' => 0, // 法人狀態: 0=未啟用
            ]);

            // 確認自然人需通過實名認證
            $this->load->library('certification_lib');
            $user_certification = $this->certification_lib->get_certification_info($responsible_user_info->id, CERTIFICATION_IDENTITY, $this->user_info->investor);
            if (!$user_certification || $user_certification->status != CERTIFICATION_STATUS_SUCCEED) {
                throw new Exception('請先完成自然人實名', NO_CER_IDENTITY);
            }

            // investor
            if (!isset($input['investor']) || !in_array($input['investor'], [BORROWER, INVESTOR])) {
                $input['investor'] = 0;
            }

            // 檢查變卡照片
            if (!empty($input['governmentauthorities_image'])) {
                $image_ids = explode(',', $input['governmentauthorities_image']);
                if (count($image_ids) > 30) {
                    $image_ids = array_slice($image_ids, 0, 30);
                }
                $this->load->model('log/log_image_model');
                $list = $this->log_image_model->as_array()->get_many_by([
                    'id' => $image_ids,
                    'user_id' => $this->user_info->id,
                ]);
                if (count($list) !== count($image_ids)) {
                    throw new Exception('設立(變更)事項登記表有誤');
                }

                $cert_governmentauthorities_content = [
                    'governmentauthorities_image' => array_map(function ($element) {
                        return $element['url'];
                    }, $list),
                    'group_id' => $list[0]['group_info'] ?? ''
                ];
            }
        } catch (Exception $e) {
            $result = [
                'result' => 'ERROR',
                'error' => empty($e->getCode()) ? INPUT_NOT_CORRECT : $e->getCode(),
                'msg' => $e->getMessage()
            ];
            goto END;
        }

        // 異動公司 user 相關資料
        $this->user_model->trans_begin();
        $this->user_certification_model->trans_begin();
        $this->user_meta_model->trans_begin();
        try {
            $this->load->model('user/user_certification_model');
            if ($company_exist === FALSE) {
                // 確認自然人姓名與登記公司負責人一樣
                $this->load->library('gcis_lib');
                $is_business_responsible = $this->gcis_lib->is_business_responsible($input['tax_id'], $responsible_user_info->name);
                if (!$is_business_responsible) {
                    throw new Exception('非公司負責人', NOT_IN_CHARGE);
                }
                $gcis_company_info = $this->gcis_lib->get_company_president_info($input['tax_id']);

                // 新增法人帳號
                $insert_user_param = [
                    'phone' => $this->user_info->phone,
                    'id_number' => $input['tax_id'],
                    'user_id' => $input['company_user_id'],
                    'password' => $input['password'],
                    'company_status' => 1, // 法人狀態: 1=啟用
                    'auth_otp' => get_rand_token(),
                    'name' => $gcis_company_info['company_name'] ?? '',
                    'status' => $input['investor'] ? 0 : 1,
                    'investor_status' => $input['investor'] ? 1 : 0,
                    'promote_code' => $input['promote_code'] ?? ''
                ];
                $new_id = $this->user_model->insert($insert_user_param);
                if (!$new_id) {
                    throw new Exception('資料庫異動失敗', INSERT_ERROR);
                }

                // 自動寫入「開通法人投資」的徵信項
                $insert_certification_param = [
                    [
                        'user_id' => $new_id,
                        'certification_id' => CERTIFICATION_TARGET_APPLY,
                        'investor' => USER_INVESTOR,
                        'content' => '',
                        'remark' => '',
                        'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
                    ]
                ];
                if (isset($cert_governmentauthorities_content)) {
                    // 自動寫入「設立(變更)事項登記表」的徵信項
                    $cert_governmentauthorities_content['compId'] = $insert_user_param['id_number'];
                    $cert_governmentauthorities_content['compName'] = $insert_user_param['name'];
                    $insert_certification_param[] = [
                        'user_id' => $new_id,
                        'certification_id' => CERTIFICATION_GOVERNMENTAUTHORITIES,
                        'investor' => $input['investor'],
                        'content' => json_encode($cert_governmentauthorities_content),
                        'remark' => '',
                        'status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
                    ];
                }
                $this->user_certification_model->insert_many($insert_certification_param);

                // 新增「公司-負責人」的關係
                $this->load->model('user/user_meta_model');
                $this->user_meta_model->insert([
                    'user_id' => $new_id,
                    'meta_key' => 'company_responsible_user_id',
                    'meta_value' => (int) $responsible_user_info->id,
                ]);

                // 產生 QRCode
                $this->create_qrcode($new_id, $input['investor'], TRUE);

                // 回傳創建帳號成功之token
                $token = (object) [
                    'id' => $new_id,
                    'phone' => $responsible_user_info->phone,
                    'auth_otp' => $insert_user_param['auth_otp'],
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                    'investor' => $insert_user_param['investor_status'],
                    'company' => 1,
                    'incharge' => 1, // todo: incharge 固定為１，是許雲輔說要改的
                    'agent' => 0,
                ];

                // 第一次登入通知
                $this->load->library('notification_lib');
                $this->notification_lib->first_login($new_id, $input['investor']);

                $request_token = AUTHORIZATION::generateUserToken($token);
                $result = [
                    'result' => 'SUCCESS',
                    'data' => [
                        'token' => $request_token,
                        'expiry_time' => $token->expiry_time,
                        'first_time' => 1
                    ]
                ];
            } else {
                // 更新使用者帳號
                if (!empty($input['company_user_id'])) {
                    $update_user_param = [
                        'auth_otp' => get_rand_token(),
                        'user_id' => $input['company_user_id']
                    ];
                    $this->user_model->update_by([
                        'phone' => $this->user_info->phone,
                        'id_number' => $input['tax_id']
                    ], $update_user_param);
                }
                // 更新認證徵信項
                if (isset($cert_governmentauthorities_content)) {
                    // 設立(變更)事項登記表
                    // 把舊的更新為失敗
                    $this->user_certification_model->update_by([
                        'user_id' => $company_info['id'],
                        'certification_id' => CERTIFICATION_GOVERNMENTAUTHORITIES
                    ], [
                        'status' => CERTIFICATION_STATUS_FAILED
                    ]);
                    // 新增
                    $insert_certification_param = [
                        'user_id' => $company_info['id'],
                        'certification_id' => CERTIFICATION_GOVERNMENTAUTHORITIES,
                        'investor' => $input['investor'],
                        'content' => json_encode($cert_governmentauthorities_content),
                        'remark' => '',
                        'status' => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
                    ];
                    $this->user_certification_model->insert($insert_certification_param);
                }
                $token = (object) [
                    'id' => $company_info['id'],
                    'phone' => $this->user_info->phone,
                    'auth_otp' => $update_user_param['auth_otp'] ?? $this->user_info->auth_opt,
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                    'investor' => $input['investor'] ? 1 : 0,
                    'company' => 1,
                    'incharge' => 1, // todo: incharge 固定為１，是許雲輔說要改的
                    'agent' => 0,
                ];
                $request_token = AUTHORIZATION::generateUserToken($token);
                $result = [
                    'result' => 'SUCCESS',
                    'data' => [
                        'token' => $request_token,
                        'expiry_time' => $token->expiry_time,
                    ]
                ];
            }

            $this->user_meta_model->trans_commit();
            $this->user_certification_model->trans_commit();
            $this->user_model->trans_commit();
        } catch (\Exception $e) {
            $this->user_meta_model->trans_rollback();
            $this->user_certification_model->trans_rollback();
            $this->user_model->trans_rollback();
            $result = [
                'result' => 'ERROR',
                'error' => empty($e->getCode()) ? INSERT_ERROR : $e->getCode(),
                'msg' => $e->getMessage()
            ];
            goto END;
        }

        END:
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
        $fields = ['phone', 'password'];
        $device_id = isset($input['device_id']) && $input['device_id'] ? $input['device_id'] : null;
        $location = isset($input['location']) ? trim($input['location']) : '';
        $os = isset($input['os']) ? trim($input['os']) : '';
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        if (strlen($input['password']) < PASSWORD_LENGTH || strlen($input['password']) > PASSWORD_LENGTH_MAX) {
            $this->response(array('result' => 'ERROR', 'error' => PASSWORD_LENGTH_ERROR));
        }

        $investor = isset($input['investor']) && $input['investor'] ? 1 : 0;

        // 自然人或法人判斷
        if (!empty($input['company_user_id'])) {
            // 法人
            $user_info = $this->user_model->get_by([
                'user_id' => sha1($input['company_user_id']),
                'phone' => $input['phone'],
                'company_status' => 1
            ]);
        } elseif (!empty($input['tax_id'])) { // todo: 因官網尚未同步 APP 以「手機＋帳號＋密碼」登入，故保留原登入方式
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
            //判斷鎖定狀態並解除
            $this->load->library('user_lib');
            $unblock_status = $this->user_lib->unblock_user($user_info->id);
            if ($unblock_status) {
                $user_info->block_status = 0;
            }
            if ($user_info->block_status == 3) {
                $this->response(array('result' => 'ERROR', 'error' => SYSTEM_BLOCK_USER));
            } elseif ($user_info->block_status == 2) {
                $this->response(array('result' => 'ERROR', 'error' => TEMP_BLOCK_USER));
            }

            if (sha1($input['password']) == $user_info->password) {

                if ($user_info->block_status != 0) {
                    $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
                }
                $this->load->library('user_agent');
                $appIdentity = $this->agent->agent_string() ?? "";
                if (strpos($appIdentity, "PuHey") !== FALSE) {
                    if ($investor == 1 && $user_info->app_investor_status == 0) {
                        $user_info->app_investor_status = 1;
                        $this->user_model->update($user_info->id, array('app_investor_status' => 1));
                    } else if ($investor == 0 && $user_info->app_status == 0) {
                        $user_info->app_status = 1;
                        $this->user_model->update($user_info->id, array('app_status' => 1));
                    }
                }

                $first_time = 0;
                if ($investor == 1 && $user_info->investor_status == 0) {
                    $user_info->investor_status = 1;
                    $this->user_model->update($user_info->id, array('investor_status' => 1));
                    $first_time = 1;

                } else if ($investor == 0 && $user_info->status == 0) {
                    $user_info->status = 1;
                    $this->user_model->update($user_info->id, array('status' => 1));
                    $first_time = 1;
                }

                // 負責人
                $is_charge = 0;
                if (isset($input['company_user_id']) || isset($input['tax_id'])) {
                    $this->load->model('user/judicial_person_model');
                    $charge_person = $this->judicial_person_model->check_valid_charge_person($user_info->id_number, $user_info->id);
                    if ($charge_person) {
                        $userData = $this->user_model->get($charge_person->user_id);
                        $userData ? $is_charge = 1 : '';
                    }

                    // 針對法人進行法人與負責人的綁定
                    $this->load->model('user/user_meta_model');
                    $rs = $this->user_meta_model->get_by(['user_id' => $user_info->id, 'meta_key' => 'company_responsible_user_id']);
                    if (!isset($rs)) {
                        $responsible_user_info = $this->user_model->get_by([
                            'phone' => $input['phone'],
                            'company_status' => 0
                        ]);
                        if (isset($responsible_user_info)) {
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
                    'id' => $user_info->id,
                    'phone' => $user_info->phone,
                    'auth_otp' => get_rand_token(),
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                    'investor' => $investor,
                    'company' => (isset($input['company_user_id']) || isset($input['tax_id'])) ? 1 : 0,
                    'incharge' => $is_charge,
                    'agent' => 0,
                ];
                $request_token = AUTHORIZATION::generateUserToken($token);
                $this->user_model->update($user_info->id, array('auth_otp' => $token->auth_otp));

                $this->insert_login_log($input['phone'], $investor, 1, $user_info->id, $device_id, $location, $os);

                if ($first_time) {
                    $this->load->library('notification_lib');
                    $this->notification_lib->first_login($user_info->id, $investor);
                }
                $this->response([
                    'result' => 'SUCCESS',
                    'data' => [
                        'token' => $request_token,
                        'expiry_time' => $token->expiry_time,
                        'first_time' => $first_time,
                    ]
                ]);
            } else {
                $remind_count = $this->insert_login_log($input['phone'], $investor, 0, $user_info->id, $device_id, $location, $os);
                $this->response([
                    'result' => 'ERROR',
                    'error' => PASSWORD_ERROR,
                    'data' => [
                        'remind_count' => $remind_count,
                    ]
                ]);
            }
        } else {
            $this->insert_login_log($input['phone'], $investor, 0, 0, $device_id, $location, $os);
            $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
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
        $fields = ['phone', 'password'];
        $device_id = isset($input['device_id']) && $input['device_id'] ? $input['device_id'] : null;
        $location = isset($input['location']) ? trim($input['location']) : '';
        $os = isset($input['os']) ? trim($input['os']) : '';
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        if (strlen($input['password']) < PASSWORD_LENGTH || strlen($input['password']) > PASSWORD_LENGTH_MAX) {
            $this->response(array('result' => 'ERROR', 'error' => PASSWORD_LENGTH_ERROR));
        }

        $investor = isset($input['investor']) && $input['investor'] ? 1 : 0;

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
            //判斷鎖定狀態並解除
            $this->load->library('user_lib');
            $unblock_status = $this->user_lib->unblock_user($user_info->id);
            if ($unblock_status) {
                $user_info->block_status = 0;
            }
            if ($user_info->block_status == 3) {
                $this->response(array('result' => 'ERROR', 'error' => SYSTEM_BLOCK_USER));
            } elseif ($user_info->block_status == 2) {
                $this->response(array('result' => 'ERROR', 'error' => TEMP_BLOCK_USER));
            }

            if (sha1($input['password']) == $user_info->password) {

                if ($user_info->block_status != 0) {
                    $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
                }
                $this->load->library('user_agent');
                $appIdentity = $this->agent->agent_string() ?? "";
                if (strpos($appIdentity, "PuHey") !== FALSE) {
                    if ($investor == 1 && $user_info->app_investor_status == 0) {
                        $user_info->app_investor_status = 1;
                        $this->user_model->update($user_info->id, array('app_investor_status' => 1));
                    } else if ($investor == 0 && $user_info->app_status == 0) {
                        $user_info->app_status = 1;
                        $this->user_model->update($user_info->id, array('app_status' => 1));
                    }
                }

                $first_time = 0;
                if ($investor == 1 && $user_info->investor_status == 0) {
                    $user_info->investor_status = 1;
                    $this->user_model->update($user_info->id, array('investor_status' => 1));
                    $first_time = 1;

                } else if ($investor == 0 && $user_info->status == 0) {
                    $user_info->status = 1;
                    $this->user_model->update($user_info->id, array('status' => 1));
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
                    if (!isset($rs)) {
                        $responsible_user_info = $this->user_model->get_by([
                            'phone' => $input['phone'],
                            'company_status' => 0
                        ]);
                        if (isset($responsible_user_info)) {
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
                    'id' => $user_info->id,
                    'phone' => $user_info->phone,
                    'auth_otp' => get_rand_token(),
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                    'investor' => $investor,
                    'company' => isset($input['tax_id']) ? 1 : 0,
                    'incharge' => $is_charge,
                    'agent' => 0,
                ];
                $request_token = AUTHORIZATION::generateUserToken($token);
                $this->user_model->update($user_info->id, array('auth_otp' => $token->auth_otp));

                $this->insert_login_log($input['phone'], $investor, 1, $user_info->id, $device_id, $location, $os);

                if ($first_time) {
                    $this->load->library('notification_lib');
                    $this->notification_lib->first_login($user_info->id, $investor);
                }
                $this->response([
                    'result' => 'SUCCESS',
                    'data' => [
                        'token' => $request_token,
                        'expiry_time' => $token->expiry_time,
                        'first_time' => $first_time,
                    ]
                ]);
            } else {
                $remind_count = $this->insert_login_log($input['phone'], $investor, 0, $user_info->id, $device_id, $location, $os);
                $this->response([
                    'result' => 'ERROR',
                    'error' => PASSWORD_ERROR,
                    'data' => [
                        'remind_count' => $remind_count,
                    ]
                ]);
            }
        } else {
            $this->insert_login_log($input['phone'], $investor, 0, 0, $device_id, $location, $os);
            $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
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
        $phone = isset($input['phone']) ? trim($input['phone']) : '';
        if (empty($phone)) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        if (!preg_match('/^09[0-9]{8}$/', $phone)) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $this->load->library('sms_lib');
        $code = $this->sms_lib->get_code($phone);
        if ($code && (time() - $code['created_at']) <= SMS_LIMIT_TIME) {
            $this->response(array('result' => 'ERROR', 'error' => VERIFY_CODE_BUSY));
        }

        // 自然人或法人判斷
        if (!empty($input['phone'])) {
            // 不管是法人或是自然人帳號，統一只驗證自然人的手機號碼是否存在即可
            $user_info = $this->user_model->get_by([
                'phone' => $input['phone'],
                'company_status' => 0
            ]);
        }
        if ($user_info) {
            $this->sms_lib->send_verify_code($user_info->id, $phone);
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
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
        $fields = ['phone', 'code', 'new_password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        if (!preg_match('/^09[0-9]{8}$/', $input['phone'])) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        if (strlen($input['new_password']) < PASSWORD_LENGTH || strlen($input['new_password']) > PASSWORD_LENGTH_MAX) {
            $this->response(array('result' => 'ERROR', 'error' => PASSWORD_LENGTH_ERROR));
        }

        // 自然人或法人判斷
        if (isset($input['tax_id'])) {
            // 法人
            $user_info = $this->user_model->get_by([
                'id_number' => $input['tax_id'],
                // 'phone' => $input['phone'],
                'company_status' => 1
            ]);
            if (empty($user_info)) { // 統編不存在，APP提示「前往註冊」
                $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
            }
            if ($user_info->phone != $input['phone']) { // 統編存在但與儲存的電話不同，APP提示「重新輸入」
                $this->response(array('result' => 'ERROR', 'error' => USER_TAX_ID_PHONE_UNMATCHED));
            }
        } else {
            // 自然人
            $user_info = $this->user_model->get_by([
                'phone' => $input['phone'],
                'company_status' => 0
            ]);
        }
        if ($user_info) {
            $this->load->library('sms_lib');
            $rs = $this->sms_lib->verify_code($user_info->phone, $input['code']);
            if ($rs) {
                $res = $this->user_model->update($user_info->id, array('password' => $input['new_password']));
                if ($res) {
                    $this->response(array('result' => 'SUCCESS'));
                } else {
                    $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                }
            } else {
                $this->response(array('result' => 'ERROR', 'error' => VERIFY_CODE_ERROR));
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
        }
    }

    // 法人忘記密碼
    public function forgotpw_company_post()
    {
        // 檢查必填
        $input = $this->input->post(NULL, TRUE);
        $fields = ['new_password', 'tax_id'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        // 檢查密碼
        try {
            $this->load->library('user_lib');
            $this->user_lib->check_password($input['new_password']);
        } catch (Exception $e) {
            $this->response([
                'result' => 'ERROR',
                'error' => empty($e->getCode()) ? INPUT_NOT_CORRECT : $e->getCode(),
            ]);
        }

        $user_info = $this->user_model->get_id_by_condition([
            'phone' => $this->user_info->phone,
            'id_number' => $input['tax_id'],
            'company_status' => 1
        ]);
        if (empty($user_info)) {
            $this->response(array('result' => 'ERROR', 'error' => COMPANY_NOT_EXIST));
        } elseif (count($user_info) > 1) {
            log_message('error', "無法更新法人密碼，因phone=\"{$this->user_info->phone}\" AND id_number=\"{$input['tax_id']}\"找到不只一組使用者資料");
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
        }
        $user_info = $user_info[0];

        $user_update_res = $this->user_model->update($user_info['id'], array('password' => $input['new_password']));
        if ($user_update_res) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
        }
    }

    // 法人忘記帳號
    public function forgot_user_id_post()
    {
        // 檢查必填
        $input = $this->input->post(NULL, TRUE);
        $fields = ['new_company_user_id', 'tax_id'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        // 檢查帳號
        try {
            $this->load->library('user_lib');
            $this->user_lib->check_user_id_validation($input['new_company_user_id'], $input['tax_id']);
        } catch (Exception $e) {
            $this->response([
                'result' => 'ERROR',
                'error' => empty($e->getCode()) ? INPUT_NOT_CORRECT : $e->getCode(),
            ]);
        }

        $user_info = $this->user_model->get_id_by_condition([
            'phone' => $this->user_info->phone,
            'id_number' => $input['tax_id'],
            'company_status' => 1
        ]);
        if (empty($user_info)) {
            $this->response(array('result' => 'ERROR', 'error' => COMPANY_NOT_EXIST));
        } elseif (count($user_info) > 1) {
            log_message('error', "無法更新法人帳號，因phone=\"{$this->user_info->phone}\" AND id_number=\"{$input['tax_id']}\"找到不只一組使用者資料");
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
        }
        $user_info = $user_info[0];

        $user_update_res = $this->user_model->update($user_info['id'], array('user_id' => $input['new_company_user_id']));
        if ($user_update_res) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
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
        $user_id = $this->user_info->id;
        $fields = $this->user_model->token_fields;
        foreach ($fields as $key => $field) {
            $data[$field] = $this->user_info->$field ? $this->user_info->$field : '';
        }

        $data['transaction_password'] = empty($this->user_info->transaction_password) ? false : true;
        $data['investor'] = intval($this->user_info->investor);
        $data['company'] = intval($this->user_info->company);
        $data['incharge'] = intval($this->user_info->incharge);
        $data['agent'] = intval($this->user_info->agent);
        $data['expiry_time'] = intval($this->user_info->expiry_time);
        $data['has_spouse'] = FALSE;

        $this->load->model('user/user_certification_model');
        if (isset($this->user_info->company) && $this->user_info->company != 0) { // 法人
            $this->load->library('judicialperson_lib');
            $natural_person = $this->judicialperson_lib->getNaturalPerson($this->user_info->id);
            $identity_cert = $this->user_certification_model->get_content($natural_person->id, CERTIFICATION_IDENTITY);
            $this->load->library('user_lib');
            $data['company_list'] = $this->user_lib->get_company_list_with_identity_status($natural_person->phone);
        } else {
            $identity_cert = $this->user_certification_model->get_content($user_id, CERTIFICATION_IDENTITY);
        }

        if (!empty($identity_cert)) {
            $identity_cert = current($identity_cert);
            $identity_cert_content = json_decode($identity_cert->content ?? '', TRUE);
            if (json_last_error() === JSON_ERROR_NONE) {
                $data['has_spouse'] = (bool) ($identity_cert_content['hasSpouse'] ?? FALSE);
            }
        }

        $this->response(array('result' => 'SUCCESS', 'data' => $data));
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

    private function set_nickname($info)
    {
        if ($this->user_info->nickname == '' && $info['name']) {
            $this->user_model->update($this->user_info->id, array('nickname' => $info['name']));
        }

        if ($this->user_info->picture == '' && $info['picture']) {
            $this->user_model->update($this->user_info->id, array('picture' => $info['picture']));
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
        $input = $this->input->get(NULL, TRUE);
        $user_id = $this->user_info->id;
        $phone = $this->user_info->phone;

        $this->load->library('sms_lib');
        $code = $this->sms_lib->get_code($phone);
        if ($code && (time() - $code['created_at']) <= SMS_LIMIT_TIME) {
            $this->response(array('result' => 'ERROR', 'error' => VERIFY_CODE_BUSY));
        }

        $this->sms_lib->send_verify_code($user_id, $phone);
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
        $input = $this->input->post(NULL, TRUE);
        $data = array();
        $fields = ['password', 'new_password', 'code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            } else {
                $data[$field] = $input[$field];
            }
        }

        if (strlen($input['new_password']) < PASSWORD_LENGTH || strlen($input['new_password']) > PASSWORD_LENGTH_MAX) {
            $this->response(array('result' => 'ERROR', 'error' => PASSWORD_LENGTH_ERROR));
        }

        $user_info = $this->user_info;
        if (sha1($data['password']) != $user_info->password) {
            $this->response(array('result' => 'ERROR', 'error' => PASSWORD_ERROR));
        }

        $this->load->library('sms_lib');
        $rs = $this->sms_lib->verify_code($user_info->phone, $data['code']);
        if (!$rs) {
            $this->response(array('result' => 'ERROR', 'error' => VERIFY_CODE_ERROR));
        }

        $res = $this->user_model->update($user_info->id, array('password' => $data['new_password']));
        if ($res) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
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
        $input = $this->input->post(NULL, TRUE);
        $data = array();
        $user_info = $this->user_info;
        $investor = $this->user_info->investor;

        $fields = ['new_password', 'code'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            } else {
                $data[$field] = $input[$field];
            }
        }

        if (strlen($input['new_password']) < TRANSACTION_PASSWORD_LENGTH || strlen($input['new_password']) > TRANSACTION_PASSWORD_LENGTH_MAX) {
            $this->response(array('result' => 'ERROR', 'error' => TRANSACTIONPW_LEN_ERROR));
        }

        $this->load->library('sms_lib');
        $rs = $this->sms_lib->verify_code($user_info->phone, $data['code']);
        if (!$rs) {
            $this->response(array('result' => 'ERROR', 'error' => VERIFY_CODE_ERROR));
        }
        $this->load->model('user/judicial_person_model');
        $judicial_person = $this->judicial_person_model->get_by([
            'user_id' => $user_info->id
        ]);
        if ($judicial_person) {
            $this->user_model->update($judicial_person->company_user_id, array('transaction_password' => $data['new_password']));
        }
        $res = $this->user_model->update($user_info->id, array('transaction_password' => $data['new_password']));
        if ($res) {
            $this->load->library('notification_lib');
            $this->notification_lib->transaction_password($user_info->id, $investor);
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
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
            'id' => $this->user_info->id,
            'phone' => $this->user_info->id,
            'auth_otp' => $this->user_info->auth_otp,
            'expiry_time' => time() + REQUEST_RETOKEN_EXPIRY,
            'investor' => $this->user_info->investor,
            'company' => $this->user_info->company,
            'incharge' => $this->user_info->incharge,
            'agent' => $this->user_info->agent,
        ];
        $request_token = AUTHORIZATION::generateUserToken($token);
        $this->response(array('result' => 'SUCCESS', 'data' => array('token' => $request_token, 'expiry_time' => $token->expiry_time)));
    }

    // 以法人 token 交換法人 token
    public function change_company_token_post()
    {
        // 欲交換 token 的法人帳號 ID
        $change_id = $this->input->post('company_list_id');
        if (empty($change_id)) {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        // 判斷是否為法人身份
        if (!$this->user_info->company) { // 非法人就讓他走原本的「交換 token」
            $this->chagetoken_get();
        }

        // 撈新的使用者資料
        $new_user_info = $this->user_model->get_by([
            'phone' => $this->user_info->phone,
            'id' => $change_id,
            'company_status' => USER_IS_COMPANY,
        ]);
        if (empty($new_user_info)) {
            $this->response(array('result' => 'ERROR', 'error' => COMPANY_NOT_EXIST));
        }

        // 判斷鎖定狀態並解除
        $this->load->library('user_lib');
        $unblock_status = $this->user_lib->unblock_user($new_user_info->id);
        if ($unblock_status) {
            $new_user_info->block_status = 0;
        }
        if ($new_user_info->block_status == 3) {
            $this->response(array('result' => 'ERROR', 'error' => SYSTEM_BLOCK_USER));
        } elseif ($new_user_info->block_status == 2) {
            $this->response(array('result' => 'ERROR', 'error' => TEMP_BLOCK_USER));
        } elseif ($new_user_info->block_status != 0) {
            $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
        }

        // 判斷交換 token 的來源
        $this->load->library('user_agent');
        $app_identity = $this->agent->agent_string() ?? "";
        $investor = isset($this->user_info->investor) && $this->user_info->investor ? 1 : 0;
        if (strpos($app_identity, 'PuHey') !== FALSE) {
            if ($investor == 1 && $new_user_info->app_investor_status == 0) {
                $new_user_info->app_investor_status = 1;
                $this->user_model->update($new_user_info->id, array('app_investor_status' => 1));
            } else if ($investor == 0 && $new_user_info->app_status == 0) {
                $new_user_info->app_status = 1;
                $this->user_model->update($new_user_info->id, array('app_status' => 1));
            }
        }

        // 判斷是否第一次登入
        if ($investor == 1 && $new_user_info->investor_status == 0) {
            $new_user_info->investor_status = 1;
            $this->user_model->update($new_user_info->id, array('investor_status' => 1));
            $first_time = 1;

        } else if ($investor == 0 && $new_user_info->status == 0) {
            $new_user_info->status = 1;
            $this->user_model->update($new_user_info->id, array('status' => 1));
            $first_time = 1;
        } else {
            $first_time = 0;
        }

        // 判斷是否為負責人
        $this->load->model('user/judicial_person_model');
        $charge_person = $this->judicial_person_model->check_valid_charge_person($new_user_info->id_number);
        $is_charge = 0;
        if ($charge_person) {
            $charge_person_user_info = $this->user_model->get($charge_person->user_id);
            if (!empty($charge_person_user_info)) {
                $is_charge = 1;
            }
        }

        // 針對法人進行法人與負責人的綁定
        $this->load->model('user/user_meta_model');
        $rs = $this->user_meta_model->get_by(['user_id' => $new_user_info->id, 'meta_key' => 'company_responsible_user_id']);
        if (!isset($rs)) {
            $responsible_user_info = $this->user_model->get_by([
                'phone' => $new_user_info->phone,
                'company_status' => 0
            ]);
            if (isset($responsible_user_info)) {
                $company_meta = [
                    [
                        'user_id' => $new_user_info->id,
                        'meta_key' => 'company_responsible_user_id',
                        'meta_value' => $responsible_user_info->id,
                    ]
                ];
                $this->user_meta_model->insert_many($company_meta);
            }
        }

        // 產生新 token
        $token = (object) [
            'id' => $new_user_info->id,
            'phone' => $new_user_info->phone,
            'auth_otp' => get_rand_token(),
            'expiry_time' => time() + REQUEST_RETOKEN_EXPIRY,
            'investor' => $investor,
            'company' => USER_IS_COMPANY,
            'incharge' => $is_charge,
            'agent' => 0,
        ];
        $request_token = AUTHORIZATION::generateUserToken($token);
        $this->user_model->update($new_user_info->id, array('auth_otp' => $token->auth_otp));
        if ($first_time) {
            $this->load->library('notification_lib');
            $this->notification_lib->first_login($new_user_info->id, $investor);
        }
        $this->response([
            'result' => 'SUCCESS',
            'data' => [
                'token' => $request_token,
                'expiry_time' => $token->expiry_time,
                'first_time' => $first_time,
            ]
        ]);
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
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $param = array('user_id' => $user_id, 'investor' => $investor);
        if (empty($input['content'])) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        } else {
            $param['content'] = $input['content'];
        }

        $image = array();
        if (isset($input['image'])) {
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

        $fields = ['image1', 'image2', 'image3'];
        foreach ($fields as $field) {
            if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
                $image[$field] = $this->s3_upload->image($_FILES, $field, $user_id, 'contact');
            } else {
                $image[$field] = '';
            }
        }
        $param['image'] = json_encode($image);
        $insert = $this->user_contact_model->insert($param);
        if ($insert) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
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
        $user_id = $this->user_info->id;
        $promote_code = $this->user_info->my_promote_code;
        $url = 'https://event.influxfin.com/R/url?p=' . $promote_code;
        $qrcode = get_qrcode($url);
        $beginDate = '2021-09-20 00:00';
        $lastday = '2021-12-31 23:59';

        //        $check= $this->line_lib->check_thirty_points();
//      if ($check !== 'success') {
//			  $this->response(array('result' => 'ERROR', 'error' => TARGET_IS_BUSY));
//      }

        //檢查是否有推薦其他人
        $promote_count = $this->user_model->getPromotedCount(
            $promote_code,
            strtotime($beginDate),
            strtotime($lastday)
        );
        $promotecount = count($promote_count);

        $collect_count = floor($promotecount / 3);
        $my_detail = $this->user_model->get_by([
            'id' => $user_id
        ]);

        $this->load->model('user/user_meta_model');
        $rs = $this->user_meta_model->get_by([
            'user_id' => $user_id,
            'meta_key' => 'line_access_token'
        ]);
        $my_line_id = $rs ? $rs->meta_value : '';
        $this->load->library('game_lib');
        // if (!empty($my_line_id) && isset($my_detail->promote_code)) {
        // 	$promote_code=$my_detail->promote_code;
        // 	if($promote_code!== 'fbpost01'){
        // 		$this->game_lib->count_and_send_thirty_points($user_id, $my_line_id, $collect_count);
        // 	}

        // }
        $check_30send = $this->log_game_model->get_many_by(array("user_id" => $user_id, "content" => $my_line_id, "memo" => 'send_thirty_points'));
        $check_30send = count($check_30send);
        $data = array(
            'promote_name' => '推薦有獎',
            'promote_code' => $promote_code,
            'promote_url' => $url,
            'promote_qrcode' => $qrcode,
            'promote_count' => count($promote_count), //檢查推薦幾人
            'collect_count' => intval($collect_count), //跟30點有關 可領取次數
            'done_collect_count' => intval($check_30send), //跟30點有關 已領取次數
            'game_status' => true,
            'promote_endtime' => $lastday
        );
        $this->response(array('result' => 'SUCCESS', 'data' => $data));
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
        $user_id = $this->user_info->id;
        $promote_code = $this->user_info->my_promote_code;
        $url = 'https://event.influxfin.com/R/url?p=' . $promote_code;
        $qrcode = get_qrcode($url);
        $beginDate = '2021-09-20 00:00';
        $lastday = '2021-12-31 23:59';

        //        $check= $this->line_lib->check_thirty_points();
//        if ($check !== 'success') {
//			$this->response(array('result' => 'ERROR', 'error' => TARGET_IS_BUSY));
//        }

        //檢查是否有推薦其他人
        $promote_count = $this->user_model->getPromotedCount(
            $promote_code,
            strtotime($beginDate),
            strtotime($lastday)
        );
        $promotecount = count($promote_count);

        $collect_count = floor($promotecount / 3);
        $my_detail = $this->user_model->get_by([
            'id' => $user_id
        ]);

        $this->load->model('user/user_meta_model');
        $my_line_id = $this->user_meta_model->get_by([
            'user_id' => $user_id,
            'meta_key' => 'line_access_token'
        ])->meta_value;
        $this->load->library('game_lib');
        if (!empty($my_line_id) && isset($my_detail->promote_code)) {
            $promote_code = $my_detail->promote_code;
            if ($promote_code !== 'fbpost01') {
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
        $input = $this->input->post(NULL, TRUE);
        $inputData = [];
        $result = [];
        $fields = ['label'];
        $user_id = $this->user_info->id;
        foreach ($fields as $field) {
            if (!isset($input[$field]) || !$input[$field]) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            } else {
                $inputData[$field] = $input[$field];
            }
        }

        if (!isset($input['group'])) {
            $this->load->model('user/sound_record_model');
            $soundRecord = $this->sound_record_model->
                get_many_by(['user_id' => $user_id, 'status' => 1]);
            if (!empty($soundRecord)) {
                $soundRecord = end($soundRecord);
                $inputData['group'] = $soundRecord->group + 1;
            } else
                $inputData['group'] = 1;
        } else {
            $inputData['group'] = $input['group'];
        }

        //上傳檔案欄位
        if (isset($_FILES['media']) && !empty($_FILES['media'])) {
            $this->load->library('S3_upload');
            $media = $this->s3_upload->media_id($_FILES, 'media', $user_id, 'user_upload/sound/' . $user_id, 2, $inputData);
            if ($media) {
                $result['media_id'] = $media;
                $result['group'] = $inputData['group'];
            } else {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $this->response(['result' => 'SUCCESS', 'data' => $result]);
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
        $data = [];
        //上傳檔案欄位
        if (isset($_FILES['media']) && !empty($_FILES['media'])) {
            $this->load->library('S3_upload');
            $media = $this->s3_upload->media_id($_FILES, 'media', $user_id, 'user_upload/' . $user_id);
            if ($media) {
                $data['media_id'] = $media;
            } else {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $this->response(['result' => 'SUCCESS', 'data' => $data]);
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
        $data = [];
        //上傳檔案欄位
        if (isset($_FILES['image']) && !empty($_FILES['image'])) {
            // 確認不是空檔案
            if ($_FILES['image']['size'] == 0) {
                $this->response(array('result' => 'ERROR', 'error' => FILE_IS_EMPTY));
            }

            $this->load->library('S3_upload');
            $image = $this->s3_upload->image_id($_FILES, 'image', $user_id, 'user_upload/' . $user_id);
            if ($image) {
                $data['image_id'] = $image;
            } else {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $this->response(['result' => 'SUCCESS', 'data' => $data]);
    }

    /**
     * 會員 上傳圖片（數量：n）
     * @api {post} /v2/user/upload_multi
     * @return void
     */
    public function upload_multi_post()
    {
        $user_id = $this->user_info->id;
        $data = [];
        // 上傳檔案欄位
        if (!empty($_FILES['image'])) {
            $files = $_FILES['image'];
            $count = count($files['name']);
            // 確認不是空檔案
            for ($i = 0; $i < $count; $i++) {
                if ($files['size'][$i] == 0) {
                    $this->response(array('result' => 'ERROR', 'error' => FILE_IS_EMPTY, 'msg' => '檔案大小為0'));
                }
            }
            // 上傳 S3
            $this->load->library('S3_upload');
            for ($i = 0; $i < $count; $i++) {
                $image = $this->s3_upload->image_id([
                    'image' => [
                        'name' => $files['name'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'type' => $files['type'][$i],
                        'size' => $files['size'][$i],
                    ]
                ], 'image', $user_id, 'user_upload/' . $user_id);
                if ($image) {
                    $data['image_id'][] = $image;
                } else {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '上傳S3失敗'));
                }
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '無上傳檔案'));
        }

        $this->response(['result' => 'SUCCESS', 'data' => $data]);
    }

    /**
     * 會員 上傳 PDF 檔案（數量：1）
     * @api {post} /v2/user/upload_pdf
     * @return void
     */
    public function upload_pdf_post()
    {
        $user_id = $this->user_info->id;
        $data = [];
        // 上傳檔案欄位
        if (isset($_FILES['pdf']) && !empty($_FILES['pdf'])) {
            // 確認不是空檔案
            if ($_FILES['pdf']['size'] == 0) {
                $this->response(array('result' => 'ERROR', 'error' => FILE_IS_EMPTY));
            }
            // 確認檔案格式
            if (!is_pdf($_FILES['pdf']['type'])) {
                $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '格式錯誤']);
            }
            if (!is_uploaded_file($_FILES['pdf']['tmp_name'])) {
                $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '非用戶上傳檔案']);
            }

            $this->load->library('S3_upload');
            $file_data = file_get_contents($_FILES['pdf']['tmp_name']);
            $pdf = $this->s3_upload->pdf_id(
                $file_data,
                round(microtime(TRUE) * 1000) . rand(1, 99) . '.pdf',
                $user_id,
                'user_upload/' . $user_id
            );
            if ($pdf) {
                $data['pdf_id'] = $pdf;
            } else {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $this->response(['result' => 'SUCCESS', 'data' => $data]);
    }

    /**
     * 會員 上傳 PDF 檔案（數量：n）
     * @api {post} /v2/user/upload_pdf_multi
     * @return void
     */
    public function upload_pdf_multi_post()
    {
        $user_id = $this->user_info->id;
        $data = [];
        // 上傳檔案欄位
        if (!empty($_FILES['pdf'])) {
            $files = $_FILES['pdf'];
            $count = count($files['name']);

            for ($i = 0; $i < $count; $i++) {
                // 確認不是空檔案
                if ($files['size'][$i] == 0) {
                    $this->response(array('result' => 'ERROR', 'error' => FILE_IS_EMPTY, 'msg' => '檔案大小為0'));
                }
                // 確認檔案格式
                if (!is_pdf($files['type'][$i])) {
                    $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '格式錯誤']);
                }
                // 確認檔案為 HTTP POST
                if (!is_uploaded_file($files['tmp_name'][$i])) {
                    $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '非用戶上傳檔案']);
                }
            }
            // 上傳 S3
            $this->load->library('S3_upload');
            for ($i = 0; $i < $count; $i++) {
                $pdf = $this->s3_upload->pdf_id(
                    file_get_contents($files['tmp_name'][$i]),
                    round(microtime(TRUE) * 1000) . rand(1, 99) . '.pdf',
                    $user_id,
                    'user_upload/' . $user_id
                );
                if ($pdf) {
                    $data['pdf_id'][] = $pdf;
                } else {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '上傳S3失敗'));
                }
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '無上傳檔案'));
        }

        $this->response(['result' => 'SUCCESS', 'data' => $data]);
    }

    private function get_promote_code()
    {
        $code = make_promote_code();
        $result = $this->user_model->get_by('my_promote_code', $code);
        if ($result) {
            return $this->get_promote_code();
        } else {
            return $code;
        }
    }

    private function not_support_company()
    {
        if ($this->user_info->company != 0) {
            $this->response(array('result' => 'ERROR', 'error' => IS_COMPANY));
        }
    }

    public function bioregister_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $data = [];
        $fields = ['bio_type', 'device_id'];
        foreach ($fields as $field) {
            if (!isset($input[$field]) && !$input[$field]) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            } else {
                $data[$field] = $input[$field];
            }
        }
        $this->load->model('user/user_bio_model');

        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $company = $this->user_info->company;
        $bio_type = $data['bio_type'];
        $device_id = $data['device_id'];

        $token = (object) [
            'user_id' => $user_id,
            'bio_type' => $bio_type,
            'investor' => $investor,
            'company' => $company,
            'device_id' => $device_id,
            'auth_otp' => get_rand_token(),
        ];
        $bio_key = AUTHORIZATION::generateUserToken($token);

        $registed = $this->user_bio_model->get_by(
            array(
                'user_id' => $user_id,
                'bio_type' => $bio_type,
                'investor' => $investor,
                'company' => $company,
                'device_id' => $device_id,
            )
        );

        if ($registed) {
            $insert = $this->user_bio_model->update(
                $registed->id,
                array(
                    'bio_key' => $bio_key,
                )
            );
        } else {
            $insert = $this->user_bio_model->insert(
                array(
                    'user_id' => $user_id,
                    'bio_type' => $bio_type,
                    'investor' => $investor,
                    'company' => $company,
                    'device_id' => $device_id,
                    'bio_key' => $bio_key,
                )
            );
        }
        if (!$insert) {
            $this->response(array('result' => 'ERROR', 'error' => KEY_FAIL));
        }

        $this->response(
            array(
                'result' => 'SUCCESS',
                'data' => array(
                    'bio_key' => $bio_key,
                )
            )
        );
    }

    public function biologin_post()
    {
        $bio_key = isset($this->input->request_headers()['bio_key']) ? $this->input->request_headers()['bio_key'] : '';
        $bio_keyData = AUTHORIZATION::getUserInfoByToken($bio_key);
        $input = $this->input->post(NULL, TRUE);
        $pdevice_id = isset($input['device_id']) ? trim($input['device_id']) : '';
        $location = isset($input['location']) ? trim($input['location']) : '';
        $user_id = $bio_keyData->user_id;
        $bio_type = $bio_keyData->bio_type;
        $investor = $bio_keyData->investor;
        $company = isset($bio_keyData->company) ? $bio_keyData->company : 0;
        $device_id = $bio_keyData->device_id;

        if ($pdevice_id != $bio_keyData->device_id) {
            $this->response(array('result' => 'ERROR', 'error' => KEY_FAIL));
        }


        $this->load->model('user/user_bio_model');
        $active = $this->user_bio_model->get_by(
            array(
                'user_id' => $user_id,
                'bio_type' => $bio_type,
                'investor' => $investor,
                'company' => $company,
                'device_id' => $device_id,
                'bio_key' => $bio_key
            )
        );

        if ($bio_keyData && isset($active)) {
            if ($bio_key !== $active->bio_key) {
                $this->response(array('result' => 'ERROR', 'error' => KEY_FAIL));
            }

            $user_info = $this->user_model->get($user_id);
            if ($user_info) {
                if ($user_info->block_status != 0) {
                    $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
                }

                // 負責人 or 代理人？
                $is_charge = 0;
                if ($company == 1) {
                    $this->load->model('user/judicial_person_model');
                    $charge_person = $this->judicial_person_model->check_valid_charge_person($user_info->id_number, $user_info->id);
                    if ($charge_person && ($this->user_model->get($charge_person->user_id))) {
                        $is_charge = 1;
                    }
                }

                $token = (object) [
                    'id' => $user_info->id,
                    'phone' => $user_info->phone,
                    'auth_otp' => get_rand_token(),
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                    'investor' => $investor,
                    'company' => $company,
                    'incharge' => $is_charge,
                    'agent' => 0,
                ];
                $request_token = AUTHORIZATION::generateUserToken($token);
                $this->user_model->update($user_info->id, array('auth_otp' => $token->auth_otp));
                $this->insert_login_log($user_info->phone, $investor, 1, $user_info->id, $device_id, $location);

                //new biokey
                $ntoken = (object) [
                    'user_id' => $user_id,
                    'bio_type' => $bio_type,
                    'investor' => $investor,
                    'device_id' => $device_id,
                    'company' => $company,
                    'auth_otp' => get_rand_token(),
                ];
                $bio_key = AUTHORIZATION::generateUserToken($ntoken);

                $insert = $this->user_bio_model->update(
                    $active->id,
                    array(
                        'bio_key' => $bio_key,
                    )
                );

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
        } else {
            $this->response(array('result' => 'ERROR', 'error' => KEY_FAIL));
        }
    }

    public function fraud_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $request_token = isset($input['request_token']) ? $input['request_token'] : '';
        $device_id = isset($input['device_id']) ? $input['device_id'] : '';
        $location = isset($input['location']) ? $input['location'] : '';
        $behavior = isset($input['behavior']) ? $input['behavior'] : '';
        $token = isset($request_token) ? $request_token : '';
        $tokenData = AUTHORIZATION::getUserInfoByToken($token);

        $user_id = isset($tokenData->id) ? $tokenData->id : '';
        $identity = isset($tokenData->investor) ? $tokenData->investor : '';

        $this->load->model('behavion/beha_user_model');
        $this->beha_user_model->insert(
            array(
                'user_id' => $user_id,
                'identity' => $identity,
                'device_id' => $device_id,
                'location' => $location,
                'behavior' => $behavior,
            )
        );

        $this->response(
            array(
                'result' => 'SUCCESS',
                'data' => []
            )
        );
    }

    public function user_behavior_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $request_token = $input['request_token'] ?? '';
        $device_id = $input['device_id'] ?? '';
        $action = $input['action'] ?? '';
        $type = $input['type'] ?? '';
        $data1 = $input['data1'] ?? '';
        $data2 = $input['data2'] ?? '';
        $json_data = $input['json_data'] ?? '{}';
        $token = $request_token ?? '';
        $tokenData = AUTHORIZATION::getUserInfoByToken($token);

        $user_id = $tokenData->id ?? '';
        $investor = isset($tokenData->investor) ? $tokenData->investor + 1 : 0;

        $this->load->model('behavion/user_behavior_model');
        $this->user_behavior_model->insert(
            array(
                'user_id' => $user_id,
                'identity' => $investor,
                'device_id' => $device_id,
                'action' => $action,
                'type' => $type,
                'data1' => $data1,
                'data2' => $data2,
                'json_data' => $json_data,
            )
        );

        $this->response(
            array(
                'result' => 'SUCCESS',
                'data' => []
            )
        );
    }

    private function insert_login_log($account = '', $investor = 0, $status = 0, $user_id = 0, $device_id = null, $location = '', $os = '')
    {
        $this->load->library('user_agent');
        $this->agent->device_id = $device_id;
        $this->load->model('log/log_userlogin_model');
        $loginLog = [
            'account' => $account,
            'investor' => intval($investor),
            'user_id' => intval($user_id),
            'location' => $location,
            'status' => intval($status),
            'os' => $os
        ];
        $this->log_userlogin_model->insert($loginLog);

        $this->load->model('mongolog/user_login_log_model');
        $fullLoginLog = $this->log_userlogin_model->getCurrentInstance($loginLog);
        $this->user_login_log_model->save($fullLoginLog);

        $this->load->library('user_lib');
        $remind_count = $this->user_lib->auto_block_user($account, $investor, $user_id, $device_id);

        return $remind_count;
    }

    public function apply_promote_code_post()
    {
        $this->load->library('certification_lib');
        $this->load->library('user_lib');
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_qrcode_apply_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_certification_model');
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $company = $this->user_info->company;

        // 取得 qrcode_setting 的 alias
        if ($company == USER_NOT_COMPANY) {
            // 一般經銷商 (自然人) 取得 qrcode_setting 的 alias
            $promote_cert_list = $this->config->item('promote_code_certs');
            $email_cert_id = CERTIFICATION_EMAIL; // 常用電子信箱

            $user_info = $this->user_model->get($user_id);
            $name = $user_info->name ?? '';
            $address = $user_info->address ?? '';
            $id_number = $user_info->id_number ?? '';
        } else {
            // 確認負責人需通過實名認證
            try {
                $this->user_lib->get_identified_responsible_user($user_id, $investor);
            } catch (Exception $e) {
                $this->response(array('result' => 'ERROR', 'error' => $e->getCode(), 'msg' => $e->getMessage()));
            }

            // 一般經銷商 (法人) 取得 qrcode_setting 的 alias
            $promote_cert_list = $this->config->item('promote_code_certs_company');
            $email_cert_id = CERTIFICATION_COMPANYEMAIL; // 公司電子信箱

            $this->load->model('user/judicial_person_model');
            $judicial_person_info = $this->judicial_person_model->get_by(['company_user_id' => $user_id]);
            $name = $judicial_person_info->company ?? '';
            $address = $judicial_person_info->cooperation_address ?? '';
            $id_number = $judicial_person_info->tax_id ?? '';
        }

        // 取得不同方案的必要徵信項
        $certifications = [];
        $doneCertifications = [];
        if (!empty($promote_cert_list)) {
            $param = array(
                'user_id' => $user_id,
                'certification_id' => $promote_cert_list,
                'investor' => $investor,
                'status' => [
                    CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
                    CERTIFICATION_STATUS_SUCCEED,
                    CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                    CERTIFICATION_STATUS_AUTHENTICATED
                ],
            );
            $certList = $this->user_certification_model->order_by('created_at', 'desc')->get_many_by($param);
            $certifications = array_reduce($certList, function ($list, $item) use (&$doneCertifications) {
                if (!isset($list[$item->certification_id])) {
                    $list[$item->certification_id] = $item;
                    if ($item->status == CERTIFICATION_STATUS_SUCCEED)
                        $doneCertifications[$item->certification_id] = $item;
                }
                return $list;
            }, []);
        }

        // 取得該 user 的 QRCode 資訊
        $user_qrcode = $this->user_qrcode_model->get_by(['user_id' => $user_id, 'status !=' => PROMOTE_STATUS_DISABLED]);
        if (empty($user_qrcode)) {
            // 預設大家都有一組 QRCode，沒有的 user 例外處理，需由客服洽開發部門協助
            $this->response(['result' => 'ERROR', 'error' => PROMOTE_CODE_NOT_EXIST, 'msg' => 'QRCode 失效或不存在，請洽客服人員協助處理']);
        }
        if (isset($input['appointed']) && strtolower($input['appointed']) === 'true') {
            $alias_name = $company == USER_NOT_COMPANY ? PROMOTE_APPOINTED_V2_CONTRACT_TYPE_NAME_NATURAL : PROMOTE_APPOINTED_V2_CONTRACT_TYPE_NAME_JUDICIAL;
        } else {
            $alias_name = $user_qrcode->alias;
        }
        if ($user_qrcode->status == PROMOTE_STATUS_AVAILABLE && $alias_name == $user_qrcode->alias) {
            $this->response(array('result' => 'ERROR', 'error' => APPLY_EXIST, 'msg' => '使用者已有推薦碼'));
        }

        // 取得 qrcode_setting
        $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $alias_name]);
        if (!isset($qrcode_settings)) {
            $this->response(array('result' => 'ERROR', 'error' => EXIT_DATABASE));
        }

        $this->user_model->trans_begin();
        $this->user_qrcode_model->trans_begin();
        $this->user_qrcode_apply_model->trans_begin();
        $trans_commit = function () {
            $this->user_qrcode_apply_model->trans_commit();
            $this->user_qrcode_model->trans_commit();
            $this->user_model->trans_commit();
        };
        $trans_rollback = function () {
            $this->user_qrcode_apply_model->trans_rollback();
            $this->user_qrcode_model->trans_rollback();
            $this->user_model->trans_rollback();
        };
        try {
            // 申請的新 QRCode 方案與該使用者舊有的 QRCode 方案不同者
            // 目前僅接受由「一般經銷商」更改為「特約通路商」
            $this->load->library('qrcode_lib');
            $is_appointed = $this->qrcode_lib->is_appointed_type($alias_name); // 是否為特約通路商
            if ($user_qrcode->alias != $alias_name) {
                // 非特約通路商，直接跳到檢查徵信項
                if ($is_appointed === FALSE) {
                    goto EMAIL_VERIFY;
                }

                // 「二級經銷商」不可變更為特約通路商
                if ($user_qrcode->subcode_flag) {
                    throw new Exception('QRCode 身份為二級經銷商，不得變更為特約通路商', PROMOTE_CODE_NOT_GENERAL);
                }

                // 「特約通路商」不可變更為其他特約通路商
                if ($this->qrcode_lib->is_appointed_type($user_qrcode->alias)) {
                    throw new Exception('QRCode 身份已為特約通路商，不得變更為特約通路商', PROMOTE_CODE_NOT_GENERAL);
                }

                // 是特約通路商，給使用者新的 QRCode
                $promote_code = $this->qrcode_lib->generate_appointed_qrcode($this->user_info->id, $this->user_info->investor, $this->user_info->company, $alias_name);
                if (!$promote_code) {
                    throw new Exception('QRCode 特約通路商身份修改失敗', INSERT_ERROR);
                }
                $user_qrcode_update_param = ['status' => PROMOTE_STATUS_DISABLED];
                $rs = $this->user_qrcode_model->update_by(['promote_code !=' => $promote_code, 'user_id' => $this->user_info->id], $user_qrcode_update_param);
                // 寫 log
                $this->load->model('log/log_user_qrcode_model');
                $user_qrcode_update_param['user_id'] = $this->user_info->id;
                $user_qrcode_update_param['promote_code'] = "! {$promote_code}";
                $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

                if (!$rs) {
                    throw new Exception('QRCode 特約通路商身份修改失敗', INSERT_ERROR);
                }
                $rs = $this->user_model->update($this->user_info->id, ['my_promote_code' => $promote_code]);
                if (!$rs) {
                    throw new Exception('QRCode 特約通路商身份修改失敗', INSERT_ERROR);
                }
                $user_qrcode = $this->user_qrcode_model->get_by(['promote_code' => $promote_code, 'status !=' => PROMOTE_STATUS_DISABLED]);
            }

            // 檢查「電子信箱」是否已提交驗證
            EMAIL_VERIFY:
            // 更新合約內容
            $contract_content = $this->qrcode_lib->get_finish_contract_cloze($user_qrcode, [
                'name' => $name,
                'id_number' => $id_number,
                'address' => $address,
            ]);
            if ($contract_content === FALSE) {
                throw new Exception('更新失敗');
            }

            $certifications_config = $this->config->item('certifications');
            if (!isset($certifications[$email_cert_id]->status)) {
                // 信箱只要沒交，就一直卡著
                throw new Exception('尚未更新' . $certifications_config[$email_cert_id]['name'] ?? '電子信箱', PROMOTE_CODE_NOT_APPLY);
            } elseif ($certifications[$email_cert_id]->status != CERTIFICATION_STATUS_SUCCEED) {
                // 未申請成功者 (email 狀態非成功)，回去繼續等
                throw new Exception('沒通過認證' . $certifications_config[$email_cert_id]['name'] ?? '電子信箱', NOT_VERIFIED_EMAIL);
            } else {
                // 申請成功，可開始提交其餘徵信項
                if (
                    $user_qrcode->status == PROMOTE_STATUS_PENDING_TO_SENT &&
                    $user_qrcode->sub_status != PROMOTE_SUB_STATUS_EMAIL_SUCCESS
                ) {
                    $user_qrcode_update_param = [
                        'status' => $is_appointed ? PROMOTE_STATUS_PENDING_TO_VERIFY : PROMOTE_STATUS_CAN_SIGN_CONTRACT,
                        'sub_status' => PROMOTE_SUB_STATUS_EMAIL_SUCCESS
                    ];
                    $rs = $this->user_qrcode_model->update($user_qrcode->id, $user_qrcode_update_param);
                    // 寫 log
                    $this->load->model('log/log_user_qrcode_model');
                    $user_qrcode_update_param['user_qrcode_id'] = $user_qrcode->id;
                    $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

                    if (!$rs) {
                        throw new Exception('更新失敗');
                    }
                    $trans_commit();
                    $msg = $is_appointed ? '我們已收到您的申請，將於2個工作天內回覆' : '請完成相關資訊驗證，並閱讀合約內容';

                    if ($is_appointed === TRUE) {
                        $qrcode_apply = $this->user_qrcode_apply_model->get_by(['user_qrcode_id' => $user_qrcode->id, 'status !=' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
                        if (empty($qrcode_apply)) {
                            $contract_format = $this->contract_format_model->get_by(['type' => $user_qrcode->alias]);
                            $this->user_qrcode_apply_model->insert([
                                'user_qrcode_id' => $user_qrcode->id,
                                'status' => PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP,
                                'contract_format_id' => $contract_format->id ?? 0,
                                'contract_content' => json_encode($contract_content),
                            ]);
                        }
                    }
                    // Todo: 2023-10-18調整，舊版app不會執行2階段認證，之後需要拿掉
                    if ($input['new_app'] ?? false) {
                        $this->response(['result' => 'SUCCESS', 'data' => [], 'msg' => $msg]);
                    }
                }

                // 檢查該提交的徵信項是否已審核成功
                if (count($doneCertifications) != count($promote_cert_list)) {
                    $change_sub_status_pending_to_default = true;
                    throw new Exception('徵信項未全數審核成功', CERTIFICATION_NOT_ACTIVE);
                }
            }

            // 特約方案需增加過審合約
            if ($is_appointed === TRUE) {
                if ($user_qrcode->status == PROMOTE_STATUS_AVAILABLE) {
                    throw new Exception('使用者已有推薦碼', APPLY_EXIST);
                }

                $qrcode_apply = $this->user_qrcode_apply_model->get_by(['user_qrcode_id' => $user_qrcode->id, 'status !=' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
                if ($user_qrcode->status == PROMOTE_STATUS_CAN_SIGN_CONTRACT && $qrcode_apply->status == PROMOTE_REVIEW_STATUS_SUCCESS) {
                    // 特約通路商的合約經老闆審核通過，QRCode 方可送出審核
                    goto PROMOTE_CODE_VERIFY;
                } else {
                    $rs = $this->contract_model->update($user_qrcode->contract_id, ['content' => json_encode($contract_content)]);
                }

                if (!$rs) {
                    throw new Exception('更新失敗');
                }
            }

            PROMOTE_CODE_VERIFY:
            $rs = TRUE;
            if (count($doneCertifications) === count($promote_cert_list)) {
                if ($company == USER_NOT_COMPANY) {
                    $certificationId = CERTIFICATION_IDENTITY;
                    $certificationName = '實名認證';
                } else if ($company == USER_IS_COMPANY) {
                    $certificationId = CERTIFICATION_JUDICIALGUARANTEE;
                    $certificationName = '公司授權核實';
                }

                if (!isset($doneCertifications[$certificationId])) {
                    $change_sub_status_pending_to_default = true;
                    throw new Exception("{$certificationName}未審核成功", CERTIFICATION_NOT_ACTIVE);
                }

                $rs = $this->certification_lib->verify_promote_code($doneCertifications[$certificationId], FALSE);

            } else {
                $change_sub_status_pending_to_default = true;
                throw new Exception('徵信項未全數審核成功', CERTIFICATION_NOT_ACTIVE);
            }

            if ($rs) {
                $trans_commit();
                $this->response(array('result' => 'SUCCESS', 'data' => []));
            } else {
                $trans_rollback();
                $change_sub_status_pending_to_default = true;
                throw new Exception('徵信項審核失敗');
                $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
            }
        } catch (Exception $e) {
            $trans_rollback();

            if (isset($change_sub_status_pending_to_default) && $change_sub_status_pending_to_default) {
                $user_qrcode_update_param = ['status' => PROMOTE_STATUS_PENDING_TO_SENT, 'sub_status' => PROMOTE_SUB_STATUS_DEFAULT];
                $rs = $this->user_qrcode_model->update($user_qrcode->id, $user_qrcode_update_param);
                // 寫 log
                $this->load->model('log/log_user_qrcode_model');
                $user_qrcode_update_param['user_qrcode_id'] = $user_qrcode->id;
                $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);
                if (!$rs) {
                    $this->response([
                        'result' => 'ERROR',
                        'error' => INSERT_ERROR,
                        'msg' => 'QR code更新失敗'
                    ]);
                }

                if (isset($is_appointed) && $is_appointed) {
                    $qrcode_apply = $this->user_qrcode_apply_model->get_by(['user_qrcode_id' => $user_qrcode->id, 'status !=' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
                    if (!isset($qrcode_apply)) {
                        $this->response([
                            'result' => 'ERROR',
                            'error' => INSERT_ERROR,
                            'msg' => '沒有找到特約申請'
                        ]);
                        return;
                    }
                    $user_qrcode_apply_update_param = ['status' => PROMOTE_REVIEW_STATUS_WITHDRAW];
                    $rs = $this->user_qrcode_apply_model->update($qrcode_apply->id, $user_qrcode_apply_update_param);
                    if (!$rs) {
                        $this->response([
                            'result' => 'ERROR',
                            'error' => INSERT_ERROR,
                            'msg' => '特約申請更新失敗'
                        ]);
                    }

                    $this->user_qrcode_apply_model->trans_commit();
                }
                $this->user_qrcode_model->trans_commit();
            }

            $this->response([
                'result' => 'ERROR',
                'error' => empty($e->getCode()) ? INSERT_ERROR : $e->getCode(),
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function promote_code_post()
    {
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_subcode_model');

        try {
            $input = $this->input->post(NULL, TRUE);

            if (empty($input['subcode_id']) || empty($input['action'])) {
                throw new Exception('參數錯誤', INPUT_NOT_CORRECT);
            }

            $subcode_info = $this->user_subcode_model->as_array()->get($input['subcode_id']);
            if (empty($subcode_info)) {
                throw new Exception('未有此 subcode 申請', PROMOTE_SUBCODE_NOT_EXIST);
            }
            $user_qrcode_info = $this->user_qrcode_model->as_array()->get($subcode_info['user_qrcode_id']);
            if (empty($user_qrcode_info)) {
                throw new Exception('推薦碼不存在', PROMOTE_CODE_NOT_EXIST);
            }

            $this->user_qrcode_model->trans_begin();
            $this->user_subcode_model->trans_begin();

            switch ($input['action']) {
                case 'agree': // 一般經銷商同意成為二級經銷商
                    if ($subcode_info['status'] != PROMOTE_SUBCODE_STATUS_DISABLED || $subcode_info['sub_status'] != PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD) {
                        throw new Exception('未有此 subcode 申請', PROMOTE_SUBCODE_NOT_EXIST);
                    }
                    if ($this->user_info->id != $user_qrcode_info['user_id']) {
                        throw new Exception('未有此 subcode 申請', PROMOTE_SUBCODE_NOT_EXIST);
                    }
                    $user_qrcode_update_param = [
                        'subcode_flag' => IS_PROMOTE_SUBCODE,
                    ];
                    $this->user_qrcode_model->update($user_qrcode_info['id'], $user_qrcode_update_param);
                    // 寫 log
                    $this->load->model('log/log_user_qrcode_model');
                    $user_qrcode_update_param['user_qrcode_id'] = $user_qrcode_info['id'];
                    $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

                    $this->user_subcode_model->update($subcode_info['id'], [
                        'status' => PROMOTE_SUBCODE_STATUS_AVAILABLE,
                        'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_DEFAULT
                    ]);
                    $this->user_subcode_model->update_by(['id !=' => $subcode_info['id'], 'user_qrcode_id' => $user_qrcode_info['id']], [
                        'status' => PROMOTE_SUBCODE_STATUS_DISABLED,
                        'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_DEFAULT
                    ]);
                    break;
                case 'reject': // 拒絕成為二級經銷商
                    if (
                        $subcode_info['status'] != PROMOTE_SUBCODE_STATUS_DISABLED ||
                        $subcode_info['sub_status'] != PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD ||
                        $this->user_info->id != $user_qrcode_info['user_id']
                    ) {
                        throw new Exception('未有此 subcode 申請', PROMOTE_SUBCODE_NOT_EXIST);
                    }
                    $this->user_subcode_model->update($subcode_info['id'], [
                        'status' => PROMOTE_SUBCODE_STATUS_DISABLED,
                        'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_REJECT
                    ]);
                    break;
                case 'read':
                    if (
                        $subcode_info['status'] != PROMOTE_SUBCODE_STATUS_DISABLED ||
                        $subcode_info['sub_status'] != PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_READ ||
                        $this->user_info->id != $user_qrcode_info['user_id']
                    ) {
                        throw new Exception('未有此 subcode 申請', PROMOTE_SUBCODE_NOT_EXIST);
                    }
                    $this->user_subcode_model->update($subcode_info['id'], [
                        'status' => PROMOTE_SUBCODE_STATUS_DISABLED,
                        'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_DEFAULT
                    ]);
                    break;
                case 'read_reject':
                    $user_qrcode_info = $this->user_qrcode_model->as_array()->get($subcode_info['master_user_qrcode_id']);
                    if (
                        $subcode_info['status'] != PROMOTE_SUBCODE_STATUS_DISABLED ||
                        $subcode_info['sub_status'] != PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_REJECT ||
                        $this->user_info->id != $user_qrcode_info['user_id']
                    ) {
                        throw new Exception('未有此 subcode 申請', PROMOTE_SUBCODE_NOT_EXIST);
                    }
                    $this->user_subcode_model->update($subcode_info['id'], [
                        'status' => PROMOTE_SUBCODE_STATUS_DISABLED,
                        'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_DEFAULT
                    ]);
                    break;
                default:
                    throw new Exception('參數錯誤', INPUT_NOT_CORRECT);
            }

            $this->user_subcode_model->trans_commit();
            $this->user_qrcode_model->trans_commit();
            $this->response(['result' => 'SUCCESS', 'data' => []]);
        } catch (Exception $e) {
            if ($this->user_qrcode_model->trans_status() === FALSE || $this->user_subcode_model->trans_status() === FALSE) {
                $this->user_subcode_model->trans_rollback();
                $this->user_qrcode_model->trans_rollback();
            }

            $this->response([
                'result' => 'ERROR',
                'error' => empty($e->getCode()) ? INSERT_ERROR : $e->getCode(),
                'msg' => $e->getMessage()
            ]);
        }

    }


    public function promote_performance_get()
    {
        $user_id = $this->user_info->id;
        $input = $this->input->get(NULL, TRUE);

        $promote_code = $input['promote_code'] ?? '';
        if (!$promote_code) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $start_date = $input['start_date'] ?? '';
        // 檢查日期格式 YYYY-MM-DD
        if ($start_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date)) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }
        $end_date = $input['end_date'] ?? '';
        // 檢查日期格式 YYYY-MM-DD
        if ($end_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $base_uri = getenv('ENV_ERP_HOST');
        if (!$base_uri) {
            $this->response(array('result' => 'ERROR', 'error' => '507'));
        }

        $client = new Client([
            'base_uri' => getenv('ENV_ERP_HOST'),
            'timeout' => 300,
        ]);
        $param = [
            'user_id' => $user_id,
            'objective_promote_code' => $promote_code,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        // 移除空字串的鍵值對
        $param = array_filter($param, function ($value) {
            return $value !== '';
        });

        try {
            $res = $client->request('GET', '/user_qrcode/promote_performance', [
                'query' => $param
            ]);
            $content = $res->getBody()->getContents();
            $json = json_decode($content, true);
            $this->response(array('result' => 'SUCCESS', 'data' => $json));

        } catch (RequestException $e) {
            $detail = '';
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $json = json_decode($responseBody, true);
                if ($e->getResponse()->getStatusCode() == 422) {
                    $detail = $json['detail'][0]['msg'] ?? ''; // 取得錯誤訊息
                } else {
                    $detail = $json['detail'] ?? '';
                }
            }
            $this->response(array('result' => 'ERROR', 'error' => $detail));
        }
    }

    public function is_new_app($ua = '')
    {
        $tmp = explode(';', $ua);
        $isNewApp = count($tmp) > 1 ? $tmp[1] == 'new_app=1' : false;
        // $isNewApp = true;

        return $isNewApp;
    }

    public function promote_code_get($action = '')
    {
        $this->load->model('user/user_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('admin/contract_format_model');
        $this->load->model('user/qrcode_collaborator_model');
        $this->load->library('contract_lib');
        $this->load->library('user_lib');
        $this->load->library('qrcode_lib');
        $user_id = $this->user_info->id;
        $company = $this->user_info->company;
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

        $where = [
            'user_id' => $user_id,
            'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY],
            'subcode_flag' => IS_NOT_PROMOTE_SUBCODE
        ];

        $isNewApp = $this->is_new_app($this->input->get_request_header('User-Agent', ''));

        if (!empty($action)) {
            switch ($action) {
                case 'contract': // 取得合約
                    $user_subcode_id = $this->input->get('subcode_id');
                    if (empty($user_subcode_id)) {
                        $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
                    }
                    $this->load->model('user/user_subcode_model');
                    $user_qrcode_info = $this->user_subcode_model->get_user_qrcode_info_by_subcode_id($user_subcode_id);
                    $user_id = $user_qrcode_info['user_id'];
                    $company = $user_qrcode_info['company_status'];
                    $where = ['user_id' => $user_id, 'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY]];
                    break;
                case 'detail_list': // 取得業績列明細
                    $user_subcode_id = $this->input->get('subcode_id');
                    if (empty($user_subcode_id)) {
                        $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
                    }
                    $this->load->model('user/user_subcode_model');
                    $user_qrcode_info = $this->user_subcode_model->get_user_qrcode_info_by_subcode_id($user_subcode_id);
                    $user_id = $user_qrcode_info['user_id'];
                    $company = $user_qrcode_info['company_status'];
                    $where = ['user_id' => $user_id, 'status' => [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY]];
                    break;
                default:
                    $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
            }
        }

        log_message('info', '1' . date('Y-m-d H:i:s'));
        // 建立合作方案的初始化資料結構
        $collaboratorList = json_decode(json_encode($this->qrcode_collaborator_model->get_many_by(['status' => PROMOTE_COLLABORATOR_AVAILABLE])), TRUE) ?? [];
        $collaboratorList = array_column($collaboratorList, NULL, 'id');
        $collaboratorInitList = array_combine(array_keys($collaboratorList), array_fill(0, count($collaboratorList), ['detail' => [], 'count' => 0, 'rewardAmount' => 0]));
        foreach ($collaboratorInitList as $collaboratorIdx => $value) {
            $collaboratorInitList[$collaboratorIdx]['collaborator'] = $collaboratorList[$collaboratorIdx]['collaborator'];
        }

        // 建立各產品的初始化資料結構
        log_message('info', '2' . date('Y-m-d H:i:s'));
        $categoryInitList = array_combine(array_keys($this->user_lib->rewardCategories), array_fill(0, count($this->user_lib->rewardCategories), ['detail' => [], 'count' => 0, 'rewardAmount' => 0]));
        $initList = array_merge_recursive(['registered' => [], 'registeredCount' => 0, 'registeredRewardAmount' => 0, 'fullMember' => [], 'fullMemberCount' => 0, 'fullMemberRewardAmount' => 0], $categoryInitList);
        $initList = array_merge_recursive($initList, ['collaboration' => $collaboratorInitList]);

        log_message('info', '3' . date('Y-m-d H:i:s'));
        $user_qrcode = $this->user_qrcode_model->get_by($where);
        if (isset($user_qrcode)) {
            $alias = $user_qrcode->alias;
            $contract_type_name = $this->qrcode_lib->get_contract_type_by_alias($alias);
        } else {
            if (!$company) {
                $contract_type_name = PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_NATURAL;
                $alias = PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_NATURAL;
            } else {
                $contract_type_name = PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_JUDICIAL;
                $alias = PROMOTE_GENERAL_V2_CONTRACT_TYPE_NAME_JUDICIAL;
            }
        }

        log_message('info', '4' . date('Y-m-d H:i:s'));
        $contract_format = $this->contract_format_model->order_by('created_at', 'desc')->get_by(['type' => $contract_type_name]);
        if (isset($contract_format)) {
            $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $alias]);
            if ($qrcode_settings) {
                $user_info = $this->user_model->get($user_id);
                $settings = json_decode($qrcode_settings->settings, TRUE);
                $data['contract'] = vsprintf(
                    $contract_format->content,
                    $this->qrcode_lib->get_contract_format_content($contract_type_name, $user_info->name ?? '', $user_info->id_number ?? '', $user_info->address ?? '', $settings)
                );
            }
        }

        log_message('info', '5' . date('Y-m-d H:i:s'));
        if (!$isNewApp) {
            $userQrcode = $this->qrcode_lib->get_promoted_reward_info($where);
        } else {
            if (isset($user_qrcode) && !empty($user_qrcode)) {
                $userQrcode = get_object_vars($user_qrcode);
            }
        }

        log_message('info', '6' . date('Y-m-d H:i:s'));
        if (isset($userQrcode) && !empty($userQrcode)) {
            if (!$isNewApp) {
                $userQrcode = reset($userQrcode);
                $userQrcodeInfo = $userQrcode['info'];
            } else {
                $userQrcodeInfo = $userQrcode;
            }
            $settings = $userQrcodeInfo['settings'];
            $promote_code = $userQrcodeInfo['promote_code'];
            $url = 'https://event.influxfin.com/R/url?p=' . $promote_code;
            $qrcode = get_qrcode($url);

            $contract = "";
            if (in_array($userQrcodeInfo['status'], [PROMOTE_STATUS_AVAILABLE, PROMOTE_STATUS_PENDING_TO_SENT, PROMOTE_STATUS_PENDING_TO_VERIFY, PROMOTE_STATUS_CAN_SIGN_CONTRACT])) {
                $contract = $this->contract_lib->get_contract($userQrcodeInfo['contract_id'], [], FALSE);

                if ($isNewApp) {
                    $list = [];
                } else {
                    // 初始化結構
                    try {
                        $d1 = new DateTime($userQrcodeInfo['start_time']);
                        $d2 = new DateTime($userQrcodeInfo['end_time'] >= date("Y-m-d H:i:s") ? date("Y-m-d H:i:s") : $userQrcodeInfo['end_time']);
                        $start = date_create($d1->format('Y-m-01'));
                        $end = date_create($d2->format('Y-m-01'));
                        $diffMonths = $start->diff($end)->m + ($start->diff($end)->y * 12) + ($start->diff($end)->d > 0 ? 1 : 0);
                    } catch (Exception $e) {
                        $diffMonths = 0;
                        error_log($e->getMessage());
                    }

                    for ($i = 0; $i <= $diffMonths; $i++) {
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
                    foreach ($this->user_lib->rewardCategories as $category => $productIdList) {
                        $rewardAmount = 0;

                        if (isset($settings['reward']) && isset($settings['reward']['product'])) {
                            $rewardAmount = $this->user_lib->getRewardAmountByProduct($settings['reward']['product'], $productIdList);
                        }
                        if (!isset($userQrcode[$category]) || empty($userQrcode[$category]))
                            continue;

                        foreach ($userQrcode[$category] as $value) {
                            $formattedMonth = date("Y-m", strtotime($value['loan_date']));
                            $list[$formattedMonth][$category]['detail'][] = array_intersect_key($value, $keys);
                            $list[$formattedMonth][$category]['count'] += 1;
                            $list[$formattedMonth][$category]['rewardAmount'] += $rewardAmount;

                            if (isset($list[$formattedMonth][$category]['borrowerPlatformFee'])) {
                                unset($list[$formattedMonth][$category]['borrowerPlatformFee']);
                            }
                            if (isset($list[$formattedMonth][$category]['investorPlatformFee'])) {
                                unset($list[$formattedMonth][$category]['investorPlatformFee']);
                            }

                            $data['overview']['rewardAmount'][$category] += $rewardAmount;
                            $data['total_reward_amount'] += $rewardAmount;
                        }
                    }

                    // 處理合作資料的計算
                    $keys = array_flip(['loan_time']);
                    foreach ($userQrcode['collaboration'] as $collaboratorId => $collaborationList) {
                        if (!isset($collaboratorList[$collaboratorId])) {
                            continue;
                        }
                        $collaborationRewardAmount = $this->user_lib->getCollaborationRewardAmount($settings['reward'], $collaboratorList[$collaboratorId]['type']);
                        foreach ($collaborationList as $value) {
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
                    foreach ($list as $key => $value) {
                        $list[$key]['collaboration'] = array_values($value['collaboration']);
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
                    $reward_registered_amount = (int) ($settings['reward']['registered']['amount'] ?? 0);
                    foreach ($userQrcode['registered'] as $value) {
                        $formattedMonth = date("Y-m", strtotime($value['created_at']));
                        $list[$formattedMonth]['registered'][] = array_intersect_key($value, $keys);
                        $list[$formattedMonth]['registeredCount'] += 1;
                        $list[$formattedMonth]['registeredRewardAmount'] += $reward_registered_amount;
                        $data['total_reward_amount'] += $reward_registered_amount;
                    }
                }

                $data['promote_code'] = $userQrcodeInfo['promote_code'];
                $data['promote_url'] = $url;
                $data['promote_qrcode'] = $qrcode;
                $data['start_time'] = $userQrcodeInfo['start_time'];
                $data['expired_time'] = $userQrcodeInfo['end_time'];
                $data['detail_list'] = $list;
            } elseif ($userQrcodeInfo['status'] != PROMOTE_STATUS_DISABLED) {
                $data['promote_code'] = $promote_code;
                $data['promote_url'] = $url;
                $data['promote_qrcode'] = $qrcode;
            }
            $data['promote_name'] = $settings['description'] ?? '';
            $data['promote_alias'] = $userQrcodeInfo['alias'];
            $data['status'] = intval($userQrcodeInfo['status']);

            // [一般經銷商]
            // 0    : 失效
            // 2    : 待送出審核，註冊即產生並供 user 分享推廣 (app 明細頁的按鈕顯示「簽約領獎」)
            // 2->3 : 審核中，user 點擊簽約領獎，並提交對應徵信項、同意合約書
            // 3->1 : 啟用，admin 審核成功必要徵信項 (app 明細頁的按鈕顯示「獎金提領」)
            // [特約通路商]
            // 0    : 失效
            // 2    : 待送出審核，註冊即產生並供 user 分享推廣 (app 明細頁的按鈕顯示「簽約領獎」)
            // 2->4 : 合約已經老闆同意，待特約通路商送出審核
            // 4->3 : 審核中，user 點擊簽約領獎，並提交對應徵信項、同意合約書
            // 3->1 : 啟用，admin 審核成功必要徵信項 (app 明細頁的按鈕顯示「獎金提領」)
            $this->load->model('user/user_qrcode_apply_model');
            $apply_info = $this->user_qrcode_apply_model->get_by(['user_qrcode_id' => $userQrcodeInfo['id']]);
            if (isset($apply_info)) {
                if ($userQrcodeInfo['status'] == PROMOTE_STATUS_PENDING_TO_SENT) {
                    switch ($apply_info->status) {
                        case PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP:
                        case PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW:
                            $data['status'] = PROMOTE_STATUS_PENDING_TO_SENT;
                            break;
                        case PROMOTE_REVIEW_STATUS_WITHDRAW:
                            $data['status'] = PROMOTE_STATUS_DISABLED;
                            break;
                        case PROMOTE_REVIEW_STATUS_SUCCESS:
                            $data['status'] = PROMOTE_STATUS_CAN_SIGN_CONTRACT;
                            $contract = $this->contract_lib->get_contract($userQrcodeInfo['contract_id'], [], FALSE);
                            break;
                    }
                }
            } else {
                log_message('info', '7' . date('Y-m-d H:i:s'));
                // 撈待確認成為二級經銷商的清單
                $this->load->model('user/user_subcode_model');
                $user_subcode_info = $this->user_subcode_model->get_info_by_user_id($user_id, ['sub_status !=' => PROMOTE_SUBCODE_SUB_STATUS_DEFAULT]);
                if (!empty($user_subcode_info)) {
                    foreach ($user_subcode_info as $value) {
                        if (
                            $value['status'] == PROMOTE_SUBCODE_STATUS_DISABLED &&
                            $value['sub_status'] == PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_READ
                        ) { // 特約通路商刪除二級經銷商，待二級經銷商閱讀 (即便二級經銷商未閱讀，刪除關係依然生效)
                            $user_name = $this->user_qrcode_model->get_user_name_by_id($value['master_user_qrcode_id']);
                            $data['subcode'] = $this->qrcode_lib->get_subcode_dialogue_content($value['id'], $user_name['name'] ?? '', $value['sub_status']);
                            goto END;
                        }

                        if (
                            $value['status'] == PROMOTE_SUBCODE_STATUS_DISABLED &&
                            $value['sub_status'] == PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD
                        ) { // 特約通路商加入 (待二級經銷商同意)
                            $user_name = $this->user_qrcode_model->get_user_name_by_id($value['master_user_qrcode_id']);
                            $data['subcode'] = $this->qrcode_lib->get_subcode_dialogue_content($value['id'], $user_name['name'] ?? '', $value['sub_status']);
                            goto END;
                        }
                    }
                }
            }
            log_message('info', '8' . date('Y-m-d H:i:s'));

            $data['contract'] = !empty($contract) ? $contract['content'] : $data['contract'];
        } else {
            log_message('info', '9' . date('Y-m-d H:i:s'));
            $user_qrcode_info = $this->user_qrcode_model->as_array()->get_by([
                'user_id' => $user_id,
                'status !=' => PROMOTE_STATUS_DISABLED,
            ]);
            if (empty($user_qrcode_info)) {
                goto END;
            }
            $qrcode_settings = $this->qrcode_setting_model->get_by(['alias' => $user_qrcode_info['alias']]);
            if ($qrcode_settings) {
                $data['promote_name'] = $qrcode_settings->description;
            }
            $url = 'https://event.influxfin.com/R/url?p=' . $user_qrcode_info['promote_code'];
            $qrcode = get_qrcode($url);

            $data['promote_alias'] = $user_qrcode_info['alias'];
            $data['promote_code'] = $user_qrcode_info['promote_code'];
            $data['promote_url'] = $url;
            $data['promote_qrcode'] = $qrcode;
            $data['status'] = intval($user_qrcode_info['status']);
            $data['start_time'] = $user_qrcode_info['start_time'];
            $data['expired_time'] = $user_qrcode_info['end_time'];
            if (intval($user_qrcode_info['status']) === 4 && intval($user_qrcode_info['sub_status']) === 1 && $user_qrcode_info['contract_id']) {
                $contract = $this->contract_lib->get_contract($user_qrcode_info['contract_id'], [], FALSE);
                if ($contract) {
                    $data['contract'] = $contract['content'];
                }
            }

            if ($isNewApp) {
                goto END;
            }

            $this->load->model('user/user_subcode_model');
            $user_subcode_info = $this->user_subcode_model->as_array()->order_by('created_at', 'desc')->get_many_by([
                'user_qrcode_id' => $user_qrcode_info['id'],
            ]);
            if (empty($user_subcode_info)) {
                goto END;
            }

            foreach ($user_subcode_info as $value) {
                log_message('info', '10' . date('Y-m-d H:i:s'));
                if (
                    $value['status'] == PROMOTE_SUBCODE_STATUS_AVAILABLE &&
                    $value['sub_status'] == PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_LEAVE
                ) { // 二級經銷商申請退出 (待特約通路商同意)
                    $user_name = $this->user_qrcode_model->get_user_name_by_id($value['master_user_qrcode_id']);
                    $data['subcode'] = $this->qrcode_lib->get_subcode_dialogue_content($value['id'], $user_name['name'] ?? '', $value['sub_status']);
                    goto END;
                }

                if (
                    $value['status'] == PROMOTE_SUBCODE_STATUS_AVAILABLE &&
                    $value['sub_status'] == PROMOTE_SUBCODE_SUB_STATUS_DEFAULT
                ) {
                    $user_name = $this->user_qrcode_model->get_user_name_by_id($value['master_user_qrcode_id']);
                    $data['subcode'] = $this->qrcode_lib->get_subcode_dialogue_content($value['id'], $user_name['name'] ?? '', $value['sub_status']);
                    goto END;
                }
            }
        }
        log_message('info', '11' . date('Y-m-d H:i:s'));

        END:
        if (!empty($action)) {
            switch ($action) {
                case 'contract': // 取得合約
                    $this->response(array('result' => 'SUCCESS', 'data' => ['contract' => $data['contract']]));
                    break;
                case 'detail_list': // 取得業績列明細
                    $this->response(
                        array(
                            'result' => 'SUCCESS',
                            'data' => [
                                'total_reward_amount' => $data['total_reward_amount'],
                                'overview' => $data['overview'],
                                'detail_list' => $data['detail_list'],
                            ]
                        )
                    );
                    break;
            }
        }
        log_message('info', '12' . date('Y-m-d H:i:s'));

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
        if (!isset($master_user_qrcode)) {
            $this->response(array('result' => 'ERROR', 'error' => PROMOTE_CODE_NOT_EXIST, 'msg' => '找不到合法的推薦主碼紀錄'));
        }

        // 檢查是否為特約通路商
        $this->load->library('qrcode_lib');
        if ($this->qrcode_lib->is_appointed_type($master_user_qrcode->alias) === FALSE) {
            $this->response(['result' => 'ERROR', 'error' => PROMOTE_CODE_NOT_APPOINTED, 'msg' => 'QRCode 身份非特約通路商，不得新增二級經銷商']);
        }

        $this->user_qrcode_model->trans_begin();
        $this->user_subcode_model->trans_begin();
        $rollback = function () {
            $this->user_qrcode_model->trans_rollback();
            $this->user_subcode_model->trans_rollback();
        };

        try {
            $record_num = $this->user_subcode_model->count_by([
                'master_user_qrcode_id' => $master_user_qrcode->id,
            ]);

            $subcode_user_id = intval($this->input->post('sub_user_id')); // 欲成為二級經銷商的使用者 ID
            $subcode_info = $this->user_qrcode_model->as_array()->get_by([
                'user_id' => $subcode_user_id,
                'status !=' => PROMOTE_STATUS_DISABLED
            ]);

            // 判斷是否正在邀請及已經被邀請過
            $existingSubcode = $this->user_subcode_model->get_by([
                'master_user_qrcode_id' => $master_user_qrcode->id,
                'user_qrcode_id' => (int) $subcode_info['id'],
                'status' => 0,
                'sub_status' => 2
            ]);
            if (!empty($existingSubcode)) {
                $this->response(['result' => 'ERROR', 'error' => PROMOTE_DUPLICATE_INVITE, 'msg' => '該二級經銷商邀請中']);
            }

            if (empty($subcode_info)) {
                $this->response(['result' => 'ERROR', 'error' => PROMOTE_SUBCODE_NOT_EXIST]);
            }
            if ($subcode_info['subcode_flag'] || $this->qrcode_lib->is_appointed_type($subcode_info['alias'])) {
                // 二級經銷商僅得以由一般經銷商轉換而來 (換言之，不得由特約通路商或其他二級經銷商轉換而來)
                $this->response(['result' => 'ERROR', 'error' => PROMOTE_CODE_NOT_GENERAL, 'msg' => 'sub_user_id 身份非一般經銷商，不得加為二級經銷商']);
            }

            $user_subcode_id = $this->user_subcode_model->insert([
                'alias' => "經銷商" . ($record_num + 1),
                'registered_id' => 0,
                'master_user_qrcode_id' => $master_user_qrcode->id,
                'user_qrcode_id' => (int) $subcode_info['id'],
                'status' => PROMOTE_STATUS_DISABLED, // 尚未啟用
                'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD, // 待二級經銷商同意
            ]);
            $user_qrcode_update_param = ['subcode_flag' => IS_NOT_PROMOTE_SUBCODE];
            $this->user_qrcode_model->update($subcode_info['id'], $user_qrcode_update_param);
            // 寫 log
            $this->load->model('log/log_user_qrcode_model');
            $user_qrcode_update_param['user_qrcode_id'] = $subcode_info['id'];
            $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);


            if (
                !$user_subcode_id ||
                $this->user_qrcode_model->trans_status() === FALSE ||
                $this->user_subcode_model->trans_status() === FALSE
            ) {
                throw new \Exception('新增二級經銷商失敗');
            }
            $this->user_qrcode_model->trans_commit();
            $this->user_subcode_model->trans_commit();
            $this->response(array('result' => 'SUCCESS', 'data' => ['subcode_id' => $user_subcode_id]));
        } catch (Exception $e) {
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

        $subcode_param = [];
        $promote_param = [];

        if (empty($input['subcode_id'])) {
            // 二級經銷商行為

            if (!isset($input['status'])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '輸入參數有誤'));
            }

            if ($input['status'] != PROMOTE_STATUS_DISABLED) {
                $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '非停權的操作不允許'));
            }

            // 用 request_token 找 user_subcode.id
            $subcode_id = $this->user_subcode_model->get_id_by_user_id($this->user_info->id);
            if (empty($subcode_id)) {
                $this->response(['result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '找不到合法的 subcode 紀錄']);
            }
            $subcode_id = $subcode_id[0]['id'];

            // 目前僅能接受申請退出
            $subcode_param = [
                'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_LEAVE, // 待特約通路商同意
            ];
        } else {
            // 特約通路商行為

            if (empty($input['alias']) && !isset($input['status'])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '輸入參數有誤'));
            }
            // 修改二級經銷商暱稱
            if (!empty($input['alias'])) {
                $subcode_param['alias'] = $input['alias'];
            }
            // 刪除二級經銷商
            $user_subcode = $this->qrcode_lib->get_subcode_list($user_id, ['id' => $input['subcode_id']]);
            if (empty($user_subcode)) {
                $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '找不到合法的 subcode 紀錄'));
            }
            $subcode_id = $input['subcode_id'];
            if (!isset($input['status'])) {
                goto UPDATE_SUBCODE;
            }
            if ($input['status'] != PROMOTE_STATUS_DISABLED) {
                $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '非停權的操作不允許'));
            }
            $promote_param['subcode_flag'] = IS_NOT_PROMOTE_SUBCODE;

            $subcode_info = $this->user_subcode_model->as_array()->get($subcode_id);
            if ($subcode_info['status'] == PROMOTE_SUBCODE_STATUS_DISABLED && $subcode_info['sub_status'] != PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD) {
                $this->response(array('result' => 'ERROR', 'error' => APPLY_NOT_EXIST, 'msg' => '找不到合法的 subcode 紀錄'));
            }
            $subcode_param = [
                'status' => PROMOTE_SUBCODE_STATUS_DISABLED,
                'sub_status' => PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_READ,
            ];
        }

        UPDATE_SUBCODE:
        $rs = $this->qrcode_lib->update_subcode_info($subcode_id, $subcode_param, $promote_param);
        if ($rs) {
            $this->response(array('result' => 'SUCCESS', 'data' => []));
        } else {
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
        $list = [];

        $input = $this->input->get(NULL, TRUE);
        $id_str = isset($input['subcode_ids']) ? trim($input['subcode_ids']) : '';
        // 針對空元素透過 filter 過濾
        $ids = array_filter(explode(',', $id_str));

        $conditions = [];
        if (!empty($ids)) {
            $conditions['id'] = $ids;
        }

        $user_subcode_list = $this->qrcode_lib->get_subcode_list($user_id, $conditions, ['status !=' => PROMOTE_STATUS_DISABLED]);
        foreach ($user_subcode_list as $user_subcode) {
            $keys = array_flip(['registered_id', 'alias', 'promote_code', 'status', 'start_time', 'end_time', 'user_id']);
            $data = array_intersect_key($user_subcode, $keys);

            $data['subcode_id'] = (int) $user_subcode['id'];
            $data['status'] = (int) $data['status'];
            $data['promote_url'] = 'https://event.influxfin.com/R/url?p=' . $data['promote_code'];
            $data['promote_qrcode'] = get_qrcode('https://event.influxfin.com/R/url?p=' . $data['promote_code']);

            $subcode_status = (int) $user_subcode['user_subcode_status'];
            $subcode_sub_status = (int) $user_subcode['user_subcode_sub_status'];
            if ($subcode_status == PROMOTE_SUBCODE_STATUS_AVAILABLE) {
                switch ($subcode_sub_status) {
                    case PROMOTE_SUBCODE_SUB_STATUS_DEFAULT:
                        $data['subcode_status'] = 0; // 啟用中的subcode
                        break;
                    case PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_LEAVE:
                        $data['subcode_status'] = $subcode_sub_status; // 二級經銷商申請退出，待特約通路商同意
                        break;
                    default:
                        unset($data);
                }
            } else {
                switch ($subcode_sub_status) {
                    case PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_ADD:
                        $data['subcode_status'] = $subcode_sub_status; // 特約通路商新增二級經銷商，待一般經銷商同意成為二級經銷商
                        break;
                    // case PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_REJECT:
                    //     $user = $this->user_model->get($data['user_id']);
                    //     $data['subcode_status'] = $subcode_sub_status; // 拒絕特約通路商成為二級經銷商，待特約經銷商閱讀
                    //     $data['message'] = $this->qrcode_lib->get_subcode_dialogue_content($data['subcode_id'], $user->name, PROMOTE_SUBCODE_SUB_STATUS_TEND_TO_REJECT);
                    //     break;
                    default:
                        unset($data);
                }
            }

            if (!isset($data)) {
                continue;
            }
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

        try {
            $list = $this->qrcode_lib->get_subcode_detail_list($user_id, $investor, $input['start_time'] ?? NULL, $input['end_time'] ?? NULL);
        } catch (Exception $e) {
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
        $input = $this->input->post(NULL, TRUE);

        try {
            $list = $this->qrcode_lib->get_subcode_detail_list($user_id, $investor, $input['start_time'] ?? NULL, $input['end_time'] ?? NULL);

            $data_rows = [];
            foreach ($list as $month => $reward_list) {
                foreach ($reward_list as $reward_info) {
                    if (empty($reward_info['list'])) {
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
                'alias' => ['name' => '經銷商', 'width' => 15, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'category' => ['name' => '產品', 'width' => 10, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'loan_date' => ['name' => '成交日期', 'width' => 10, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'reward' => ['name' => '獎金', 'width' => 8, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC, 'alignment' => ['h' => 'center', 'v' => 'center']]
            ];

            $user = $this->user_model->get($user_id);

            $spreadsheet = $this->spreadsheet_lib->load($title_rows, $data_rows);

            //check dir is exist
            $dir = 'tmp';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $filepath = 'tmp/subcode_' . round(microtime(true) * 1000) . '.xlsx';

            $this->spreadsheet_lib->save($filepath, $spreadsheet);
            if (file_exists($filepath)) {
                $title = '【普匯金融推薦有賞明細】';
                $content = '親愛的會員您好：<br> 　　茲寄送您推薦有賞明細列表，請您核對。<br>若有疑問請洽Line@粉絲團客服，我們將竭誠為您服務。<br>普匯金融科技有限公司　敬上 <br><p style="color:red;font-size:14px;"></p>';
                $this->load->library('sendemail');
                $sent = $this->sendemail->email_file_estatement($user->email, $title, $content, $filepath, '', $investor, '推薦碼明細.xlsx');
                unlink($filepath);
            } else {
                $this->response(array('result' => 'ERROR', 'error' => EXIT_UNKNOWN_FILE, 'msg' => '系統無法生成檔案'));
            }
        } catch (Exception $e) {
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
        foreach ($rs as $value) {
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
        if (!empty($input['alias'])) {
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
        foreach ($fields as $field) {
            if (!isset($input[$field]) || !$input[$field]) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            } else {
                $data[$field] = $input[$field];
            }
        }

        $fields = ['receipt_id_number', 'receipt_address'];
        foreach ($fields as $field) {
            if (isset($input[$field])) {
                $data[$field] = $input[$field];
            }
        }

        $this->load->model('user/charity_institution_model');
        $institution = $this->charity_institution_model->get_by(['alias' => $data['alias'], 'status' => 1]);
        if (!isset($institution)) {
            $this->response(array('result' => 'ERROR', 'error' => KEY_FAIL));
        }

        $this->load->model('user/judicial_person_model');
        $judical_person_info = $this->judicial_person_model->get_by(['id' => $institution->judicial_person_id]);
        if (!isset($judical_person_info)) {
            $this->response(array('result' => 'ERROR', 'error' => EXIT_DATABASE));
        }

        $donateAmount = intval($data['amount']);
        if ($donateAmount < $institution->min_amount || $donateAmount > $institution->max_amount) {
            $this->response(array('result' => 'ERROR', 'error' => CHARITY_INVALID_AMOUNT));
        }

        $this->load->library('user_lib');
        $this->load->model('user/virtual_account_model');
        $virtual = $this->user_lib->getVirtualAccountPrefix($investor);
        $virtual_account = $this->virtual_account_model->setVirtualAccount(
            $userId,
            $investor,
            VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
            VIRTUAL_ACCOUNT_STATUS_USING,
            $virtual
        );
        if (empty($virtual_account)) {
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

        try {
            if ($balance < $donateAmount) {
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
            if (!$tranRsId) {
                $errorCode = EXIT_DATABASE;
                throw new Exception('insert failed');
            }

            $data = array_intersect_key($data, array_flip(['receipt_id_number', 'receipt_address']));
            $data['name'] = $this->user_info->name;
            $data['email'] = $this->user_info->email;
            $data['phone'] = $this->user_info->phone;

            $this->passbook_lib->enter_account($tranRsId);
            if (
                !$this->charity_model->insert([
                    'institution_id' => $institution->id,
                    'user_id' => $userId,
                    'investor' => $investor,
                    'amount' => $donateAmount,
                    'transaction_id' => $tranRsId,
                    'tx_datetime' => date("Y-m-d H:i:s"),
                    'receipt_type' => CHARITY_RECEIPT_TYPE_SINGLE_PAPER,
                    'data' => json_encode($data),
                ])
            ) {
                $errorCode = EXIT_DATABASE;
                throw new Exception('insert failed');
            }

            if (
                $this->transaction_model->trans_status() === TRUE && $this->virtual_passbook_model->trans_status() === TRUE
                && $this->charity_model->trans_status() === TRUE
            ) {
                $this->transaction_model->trans_commit();
                $this->virtual_passbook_model->trans_commit();
                $this->charity_model->trans_commit();

            } else {
                $errorCode = EXIT_DATABASE;
                throw new Exception("transaction_status is invalid.");
            }

        } catch (Throwable $t) {
            $this->transaction_model->trans_rollback();
            $this->virtual_passbook_model->trans_rollback();
            $this->charity_model->trans_rollback();
        } finally {
            $this->virtual_account_model->setVirtualAccount(
                $userId,
                $investor,
                VIRTUAL_ACCOUNT_STATUS_USING,
                VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
                $virtual
            );
        }

        if (!$errorCode) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
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

        if (
            !isset($input['last5']) || !isset($input['amount']) ||
            !is_numeric($input['amount']) || $input['amount'] <= 0 ||
            !preg_match('/[0-9]{5}/', $input['last5'])
        ) {
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
        if (empty($donate_list)) {
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
        if (!empty($input['number']) || !empty($input['name'])) {
            $this->load->model('user/charity_anonymous_model');
            foreach ($donate_list as $key => $donate) {
                if ($donate['charity_anonymous_id'] != 0) {
                    $anonymous = $this->charity_anonymous_model->as_array()->get($donate['charity_anonymous_id']);
                    if (
                        $input['name'] == $anonymous['name'] &&
                        $input['number'] == $anonymous['number'] &&
                        $input['name'] !== ''
                    ) {
                        $return_data['donator_name'] = $input['name'];
                        $return_data['donator_sex'] = '先生/小姐';
                        break;
                    }
                } else {
                    $anonymous_id = $this->charity_anonymous_model->get_anonymous($input);
                    if ($anonymous_id != 0) {
                        $this->anonymous_donate_model->update($donate['id'], [
                            'charity_anonymous_id' => $anonymous_id,
                        ]);
                        if ($input['name'] !== '') {
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

        if (!is_numeric($input['amount']) || $input['amount'] <= 0) {
            $this->response([
                'result' => 'SUCCESS',
                'error' => CHARITY_INVALID_AMOUNT,
                'data' => [
                    'msg' => $error_msg[CHARITY_INVALID_AMOUNT],
                ],
            ]);
        } elseif ($input['amount'] >= 500000) {
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

        if ($anonymous_id && $institution) {
            $this->response([
                'result' => 'SUCCESS',
                'data' =>
                    [
                        'bank_code' => CATHAY_BANK_CODE,
                        'bank_account' => $institution['virtual_account'],
                        'charity_title' => $institution['name'],
                    ],
            ]);
        } else {
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

        $this->response([
            'result' => 'SUCCESS',
            'data' => [
                'verify' => (int) ($bank_account->verify ?? 0),
                'status' => (int) ($bank_account->status ?? 0),
            ]
        ]);
    }

    // 檢查使用者手機是否存在
    public function check_phone_get()
    {
        $phone = $this->input->get('phone');
        if (empty($phone)) {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }
        $user_info = $this->user_model->get_many_by(['phone' => $phone, 'status' => 1]);
        $user_company_status = array_column($user_info, 'id', 'company_status');

        if (!empty($user_company_status[0]) && !empty($user_company_status[1])) { // 該手機號碼已註冊自然人、法人
            $status = 2;
        } elseif (!empty($user_company_status[0])) { // 該手機號碼僅註冊自然人
            $status = 1;
        } else { // 該手機號碼未註冊
            $status = 0;
        }

        $this->response(['result' => 'SUCCESS', 'data' => ['status' => $status]]);
    }

    // 撈取相同負責人的公司列表
    public function company_list_get()
    {
        // 用自然人手機＋自然人密碼，取得相同負責人(意即相同手機號碼)的公司清單
        //     $input = $this->input->get(NULL, TRUE);
        //     if (empty($input['phone']) || empty($input['password']))
        //     {
        //         $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        //     }

        //     $user_exist = $this->user_model->count_by([
        //         'phone' => $input['phone'],
        //         'password' => sha1($input['password']),
        //         'company_status' => USER_NOT_COMPANY
        //     ]);
        //     if (empty($user_exist))
        //     {
        //         $this->response(['result' => 'ERROR', 'error' => USER_NOT_EXIST]);
        //     }
        //     $this->load->library('user_lib');
        //     $company_list = $this->user_lib->get_company_list_with_identity_status($input['phone']);

        //     $this->response(['result' => 'SUCCESS', 'data' => ['company_list' => $company_list]]);
        // }

        // 改成直接使用token取得user_info
        $this->load->library('user_lib');
        $company_list = $this->user_lib->get_company_list_with_identity_status($this->user_info->phone);

        $this->response(['result' => 'SUCCESS', 'data' => ['company_list' => $company_list]]);
    }

    // 撈取該法人的實名狀況
    public function company_identity_status_get()
    {
        $result = ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
        $tax_id = $this->input->get('tax_id');
        if (empty($tax_id)) {
            goto END;
        }
        // 檢查公司帳號
        $company_info = $this->user_model->as_array()->get_by([
            'id_number' => $tax_id,
            'company_status' => USER_IS_COMPANY
        ]);
        if (empty($company_info)) {
            $result['error'] = COMPANY_NOT_EXIST;
            goto END;
        }
        // 檢查公司負責人
        if ($this->user_info->company) { // 法人token
            $responsible_info = $this->user_model->get_by([
                'phone' => $this->user_info->phone,
                'company_status' => USER_NOT_COMPANY
            ]);
        } else { // 自然人 token
            $this->load->model('user/user_meta_model');
            $responsible_info = $this->user_meta_model->get_by([
                'user_id' => $company_info['id'],
                'meta_key' => 'company_responsible_user_id',
                'meta_value' => $this->user_info->id,
            ]);
        }
        if (empty($responsible_info)) {
            $result['error'] = NOT_IN_CHARGE;
            goto END;
        }

        $this->load->library('certification_lib');
        $certification_info = $this->certification_lib->get_certification_info($company_info['id'], CERTIFICATION_GOVERNMENTAUTHORITIES, $this->user_info->investor);

        if ($certification_info === FALSE) { // 未提交
            $status = 0;
        } elseif ($certification_info->status === CERTIFICATION_STATUS_SUCCEED) { // 已通過
            $status = 1;
        } else { // 審核中
            $status = 2;
        }

        $result = ['result' => 'SUCCESS', 'data' => ['status' => $status]];

        END:
        $this->response($result);
    }

    public function header_test_post()
    {
        $this->response(array('result' => 'SUCCESS', 'data' => ['header' => $this->input->request_headers()]));
    }


    private function access_pass()
    {
        $list = [
            '114.34.161.233' //公司內網IP
        ];
        foreach ($list as $ip) {
            if (preg_match('/\.\*$/', $ip)) {
                list($main, $sub) = explode('.*', $ip);
                if (stripos(get_ip(), $main) !== false) {
                    return true;
                }
            }
            if (get_ip() == $ip) {
                return true;
            }
        }
        return false;
    }

    /**
     */
    public function create_qrcode_post(): bool
    {
        if (!$this->access_pass()) {
            show_404();
        }
        $input = $this->input->post(NULL, TRUE);

        $this->load->library('form_validation');
        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('user_id', 'user_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('investor', 'investor', 'required|integer|in_list[0,1]');
        $this->form_validation->set_rules('company', 'company', 'required|integer|in_list[0,1]');
        $this->form_validation->set_error_delimiters('', '');
        if (!$this->form_validation->run()) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $user_id = intval($input['user_id']);
        $user_existed = $this->user_model->get($user_id);
        if (empty($user_existed)) {
            $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
        }

        $investor = intval($input['investor']);
        $company = boolval($input['company']);
        try {
            $result = $this->create_qrcode($user_id, $investor, $company);
            $this->response(array('result' => 'SUCCESS', 'data' => ['result' => $result]));
        } catch (Exception $e) {
            $this->response(array('result' => 'ERROR', 'error' => $e->getCode(), 'msg' => $e->getMessage()));
        }
    }
}
