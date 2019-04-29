<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Product extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->library('Certification_lib');
		$this->load->library('Target_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 				= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
            $tokenData 			= AUTHORIZATION::getUserInfoByToken($token);
			$nonCheckMethods 	= ['list','info'];
			if(in_array($method, $nonCheckMethods) && empty($token)){
				$this->user_info = [];
			}else{
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
				
				//只限借款人
				if($tokenData->investor != 0 && !in_array($method,['list'])){
					$this->response(array('result' => 'ERROR','error' => IS_INVERTOR ));
				}

				//暫不開放法人
				if(isset($tokenData->company) && $tokenData->company != 0  && !in_array($method,['list'])){
					$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
				}
			
				if($this->request->method != 'get'){
					$this->load->model('log/log_request_model');
					$this->log_request_model->insert(array(
						'method' 	=> $this->request->method,
						'url'	 	=> $this->uri->uri_string(),
						'investor'	=> $tokenData->investor,
						'user_id'	=> $tokenData->id,
						'agent'		=> $tokenData->agent,
					));
				}
			
				$this->user_info->investor 		= $tokenData->investor;
				$this->user_info->company 		= $tokenData->company;
				$this->user_info->incharge 		= $tokenData->incharge;
				$this->user_info->agent 		= $tokenData->agent;
				$this->user_info->expiry_time 	= $tokenData->expiry_time;
			}
        }
    }

	
	/**
     * @api {get} /v2/product/list 借款方 取得產品列表
	 * @apiVersion 0.2.0
	 * @apiName GetProductList
     * @apiGroup Product
	 * @apiHeader {String} [request_token] 登入後取得的 Request Token
	 * @apiDescription 登入狀態下，若已申請產品且申請狀態為未簽約，則提供申請資訊欄位及認證完成資訊。
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Product ID
	 * @apiSuccess {Number} type 類型 1:信用貸款 2:分期付款
	 * @apiSuccess {Number} identity 身份 1:學生 2:社會新鮮人
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {Object} instalment 可選期數 0:其他
	 * @apiSuccess {Object} repayment 可選還款方式 1:等額本息
	 * @apiSuccess {Number} loan_range_s 最低借款額度(元)
	 * @apiSuccess {Number} loan_range_e 最高借款額度(元)
	 * @apiSuccess {Number} interest_rate_s 年利率下限(%)
	 * @apiSuccess {Number} interest_rate_e 年利率上限(%)
	 * @apiSuccess {Number} charge_platform 平台服務費(%)
	 * @apiSuccess {Number} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {Object} target 申請資訊
	 * @apiSuccess {Object} certification 認證完成資訊
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":1,
     * 				"type":1,
     * 				"identity":1,
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"loan_range_s":12222,
     * 				"loan_range_e":14333333,
     * 				"interest_rate_s":12,
     * 				"interest_rate_e":14,
     * 				"charge_platform":0,
     * 				"charge_platform_min":0,
	 * 				"instalment": [
     *					3,
     * 				    6,
     * 				    12,
     * 				    18,
     * 				    24
     * 				  ]
	 * 				"repayment": [
     *					1
     * 				  ],
	 * 				"target":{
     * 					"id":1,
     * 					"target_no": "1803269743",
     * 					"amount":5000,
     * 					"loan_amount":0,
     * 					"status":0,
     * 					"instalment":3,
     * 					"created_at":"1520421572"
     * 				},
	 * 				"certification":[
	 * 					{
     * 						"id":1,
     * 						"name": "實名認證",
     * 						"description":"實名認證",
     * 						"alias":"id_card",
     * 						"user_status":1
     * 					},
	 * 					{
     * 						"id":2,
     * 						"name": "學生身份認證",
     * 						"description":"學生身份認證",
     * 						"alias":"student",
     * 						"user_status":1
     * 					}
	 * 				]
     * 			}
     * 			]
     * 		}
     * }
     */
	 	
	public function list_get()
    {
		$list			= [];
		$product_list 	= $this->config->item('product_list');
		if(isset($this->user_info->id) && $this->user_info->id && $this->user_info->investor==0){
			$certification_list	= $this->certification_lib->get_status($this->user_info->id,$this->user_info->investor);
		}else{
			$certification_list = [];
			$certification = $this->config->item('certifications');
			foreach($certification as $key => $value){
				$value['user_status'] 		= null;
				$value['certification_id'] 	= null;
				$certification_list[$key] = $value;
			}
		}
		
		if(!empty($product_list)){
			foreach($product_list as $key => $value){
				$target 				= [];
				$certification 			= [];
				if(isset($this->user_info->id) && $this->user_info->id && $this->user_info->investor==0){
					$targets = $this->target_model->get_by(array(
						'status <='		=> 1,
						'sub_status'	=> 0,
						'user_id'		=> $this->user_info->id,
						'product_id'	=> $value['id']
					));
					if($targets){
						$target = [
							'id'			=> intval($targets->id),
							'target_no'		=> $targets->target_no,
							'status'		=> intval($targets->status),
							'amount'		=> intval($targets->amount),
							'loan_amount'	=> intval($targets->loan_amount),
							'created_at'	=> intval($targets->created_at),
							'instalment'	=> intval($targets->instalment),
						];
					}
				}
				
				if(!empty($certification_list)){
					foreach($certification_list as $k => $v){
						if(in_array($k,$value['certifications'])){
							$certification[] = $v;
						}
					}
				}

				$list[] = array(
					'id' 					=> $value['id'],
					'type' 					=> $value['type'],
					'identity' 				=> $value['identity'],
					'name' 					=> $value['name'],
					'description' 			=> $value['description'],
					'loan_range_s'			=> $value['loan_range_s'],
					'loan_range_e'			=> $value['loan_range_e'],
					'interest_rate_s'		=> $value['interest_rate_s'],
					'interest_rate_e'		=> $value['interest_rate_e'],
					'charge_platform'		=> PLATFORM_FEES,
					'charge_platform_min'	=> PLATFORM_FEES_MIN,
					'instalment'			=> $value['instalment'],
					'repayment'				=> $value['repayment'],
					'target'				=> $target,
					'certification'			=> $certification,
				);
			}
		}

		$this->response(array('result' => 'SUCCESS','data' => ['list' => $list] ));
    }

	/**
     * @api {get} /v2/product/info/:id 借款方 取得產品資訊
	 * @apiVersion 0.2.0
	 * @apiName GetProductInfo
     * @apiGroup Product
	 * @apiParam {Number} id 產品ID
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} type 類型 1:信用貸款 2:分期付款
	 * @apiSuccess {Number} identity 身份 1:學生 2:社會新鮮人
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {Object} instalment 可選期數 0:其他
	 * @apiSuccess {Object} repayment 可選還款方式 1:等額本息
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 	"result": "SUCCESS",
     * 		"data": {
     * 			"id": 1,
     * 			"type": 1,
     * 			"identity": 1,
     * 			"name": "學生貸",
     * 			"description": "\r\n普匯學生貸\r\n計畫留學、創業或者實現更多理想嗎？\r\n需要資金卻無法向銀行聲請借款嗎？\r\n普匯陪你一起實現夢想",
     * 			"loan_range_s": 5000,
     * 			"loan_range_e": 120000,
     * 			"interest_rate_s": 5,
     * 			"interest_rate_e": 20,
     * 			"charge_platform": 3,
     * 			"charge_platform_min": 500,
     * 			"instalment": [
     * 				3,
     * 				6,
     * 				12,
     * 				18,
     * 				24
     * 			],
     * 			"repayment": [
     * 				1
     * 			]
     * 		}
     * }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
	 *
	 * @apiError 401 產品不存在
     * @apiErrorExample {Object} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     */
	 
	public function info_get($id)
    {
		if($id){
			$data			= [];
			$product_list 	= $this->config->item('product_list');
			if(isset($product_list[$id])){
				$data = array(
					'id' 					=> $product_list[$id]['id'],
					'type' 					=> $product_list[$id]['type'],
					'identity' 				=> $product_list[$id]['identity'],
					'name' 					=> $product_list[$id]['name'],
					'description' 			=> $product_list[$id]['description'],
					'loan_range_s'			=> $product_list[$id]['loan_range_s'],
					'loan_range_e'			=> $product_list[$id]['loan_range_e'],
					'interest_rate_s'		=> $product_list[$id]['interest_rate_s'],
					'interest_rate_e'		=> $product_list[$id]['interest_rate_e'],
					'charge_platform'		=> PLATFORM_FEES,
					'charge_platform_min'	=> PLATFORM_FEES_MIN,
					'instalment'			=> $product_list[$id]['instalment'],
					'repayment'				=> $product_list[$id]['repayment'],
				);
				$this->response(array('result' => 'SUCCESS','data' => $data ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
    }
	
	/**
     * @api {post} /v2/product/apply 借款方 申請借款
	 * @apiVersion 0.2.0
	 * @apiName PostProductApply
     * @apiGroup Product
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiDescription 此API只支援信用貸款類型產品，產品簽約前一次只能一案。
	 *
	 * @apiParam {Number} product_id 產品ID
     * @apiParam {Number} amount 借款金額
     * @apiParam {Number} instalment 申請期數
	 * @apiParam {String{0..128}} [reason] 借款原因
	 * @apiParam {String{0..16}} [promote_code] 邀請碼
	 * 
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} target_id Targets ID
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"target_id": 1
     * 		}
     * }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
	 * @apiError 401 產品不存在
     * @apiErrorExample {Object} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
	 *
     * @apiError 402 超過此產品可申請額度
     * @apiErrorExample {Object} 402
     *     {
     *       "result": "ERROR",
     *       "error": "402"
     *     }
	 *
     * @apiError 403 不支援此期數
     * @apiErrorExample {Object} 403
     *     {
     *       "result": "ERROR",
     *       "error": "403"
     *     }
	 *
     * @apiError 408 同產品重複申請
     * @apiErrorExample {Object} 408
     *     {
     *       "result": "ERROR",
     *       "error": "408"
     *     }
	 *
     * @apiError 410 產品類型錯誤
     * @apiErrorExample {Object} 410
     *     {
     *       "result": "ERROR",
     *       "error": "410"
     *     }
	 *
     */
	public function apply_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= [
			'user_id' => $user_id,
			'damage_rate' => LIQUIDATED_DAMAGES
		];
		
		//必填欄位
		$fields 	= ['product_id','amount','instalment'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}
		
		$param['reason'] 		= isset($input['reason'])?$input['reason']:'';
		$param['promote_code'] 	= isset($input['promote_code'])?$input['promote_code']:'';

		//邀請碼保留月
		if(strtotime('-'.TARGET_PROMOTE_LIMIT.' month') <= $this->user_info->created_at && $this->user_info->promote_code != ''){
			$param['promote_code'] = $this->user_info->promote_code;
		}

		$product_list 	= $this->config->item('product_list');
		$product 		= isset($product_list[$input['product_id']])?$product_list[$input['product_id']]:[];
		if($product){
			
			if($product['type'] != 1){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_TYPE_ERROR ));
			}
			
			if(!in_array($input['instalment'],$product['instalment'])){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_INSTALMENT_ERROR ));
			}
			
			if($input['amount'] < $product['loan_range_s'] || $input['amount'] > $product['loan_range_e']){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_AMOUNT_RANGE ));
			}

			$exist = $this->target_model->get_by([
				'status <='		=> 1,
				'user_id'		=> $user_id,
				'product_id'	=> $product['id']
			]);
			if($exist){
				$this->response(['result' => 'ERROR','error' => APPLY_EXIST]);
			}

			$insert = $this->target_lib->add_target($param);
			if($insert){
				$this->response(['result' => 'SUCCESS','data'=>['target_id'=> $insert ]]);
			}else{
				$this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
			}
		}
		$this->response(['result' => 'ERROR','error' => PRODUCT_NOT_EXIST]);
    }

	/**
     * @api {post} /v2/product/signing 借款方 申請簽約
	 * @apiVersion 0.2.0
	 * @apiName PostProductSigning
     * @apiGroup Product
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiDescription 此API只支援信用貸款類型產品。
	 *
	 * @apiParam {Number} target_id Targets ID
	 * @apiParam {file} person_image 本人照
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
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
	 * @apiError 401 產品不存在
     * @apiErrorExample {Object} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 410 產品類型錯誤
     * @apiErrorExample {Object} 410
     *     {
     *       "result": "ERROR",
     *       "error": "410"
     *     }
	 *
     * @apiError 202 未通過所需的驗證
     * @apiErrorExample {Object} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 未綁定金融帳號
     * @apiErrorExample {Object} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 206 人臉辨識不通過
     * @apiErrorExample {Object} 206
     *     {
     *       "result": "ERROR",
     *       "error": "206"
     *     }
	 *
     * @apiError 208 未滿20歲或大於35歲
     * @apiErrorExample {Object} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     */
	public function signing_post()
    {
		$this->load->library('S3_upload');
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('Certification_lib');
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= ['status'=>2];

		//必填欄位
		if (empty($input['target_id'])) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		//上傳檔案欄位
		if (isset($_FILES['person_image']) && !empty($_FILES['person_image'])) {
			$image 	= $this->s3_upload->image($_FILES,'person_image',$user_id,'signing_target');
			if($image){
				$param['person_image'] = $image;
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$target 	= $this->target_model->get($input['target_id']);
		if(!empty($target)){

			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}
			
			if($target->status != 1 || $target->sub_status != 0){
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
			
			$product_list 	= $this->config->item('product_list');
			$product 		= $product_list[$target->product_id];
			if($product){
				if($product['type'] != 1){
					$this->response(array('result' => 'ERROR','error' => PRODUCT_TYPE_ERROR ));
				}
			
				//檢查認證 NOT_VERIFIED
				$certification_list	= $this->certification_lib->get_status($user_id,$investor);
				foreach($certification_list as $key => $value){
					if(in_array($key,$product['certifications']) && $value['user_status']!=1){
						$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
					}
				}

				if(get_age($this->user_info->birthday) < 20 || get_age($this->user_info->birthday) > 35 ){
					$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
				}
				
				//檢查金融卡綁定 NO_BANK_ACCOUNT
				$bank_account = $this->user_bankaccount_model->get_by([
					'status'	=> 1,
					'investor'	=> $investor,
					'verify'	=> 1,
					'user_id'	=> $user_id 
				]);
				if($bank_account){
					$this->user_bankaccount_model->update($bank_account->id,['verify'=>2]);
                }else{
                    $this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
                }
				
				$this->target_lib->signing_target($target->id,$param,$user_id);
				$this->response(array('result' => 'SUCCESS'));
			}
			$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /v2/product/applylist 借款方 申請紀錄列表
	 * @apiVersion 0.2.0
	 * @apiName GetProductApplylist
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiGroup Product
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {Number} product_id Product ID
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Number} amount 申請金額
	 * @apiSuccess {Number} loan_amount 核准金額
	 * @apiSuccess {Number} platform_fee 平台服務費
	 * @apiSuccess {Number} interest_rate 年化利率
	 * @apiSuccess {Number} instalment 期數 0:其他
	 * @apiSuccess {Number} repayment 還款方式 1:等額本息
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {Number} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id": 5,
     * 				"target_no": "STN2019010484186",
     * 				"product_id": 1,
     * 				"user_id": 1,
     * 				"amount": 5000,
     * 				"loan_amount": 0,
     * 				"platform_fee": 500,
     * 				"interest_rate": 0,
     * 				"instalment": 3,
     * 				"repayment": 1,
     * 				"reason": "",
     * 				"remark": "系統自動取消",
     * 				"delay": 0,
     * 				"status": 9,
     * 				"sub_status": 0,
     * 				"created_at": 1546591486
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
     */
	public function applylist_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$param				= ['user_id'=> $user_id];
		$targets 			= $this->target_model->get_many_by($param);
		$list				= [];
		if(!empty($targets)){
			foreach($targets as $key => $value){
				
				$list[] = [
					'id' 				=> intval($value->id),
					'target_no' 		=> $value->target_no,
					'product_id' 		=> intval($value->product_id),
					'user_id' 			=> intval($value->user_id),
					'amount' 			=> intval($value->amount),
					'loan_amount' 		=> intval($value->loan_amount),
					'platform_fee' 		=> intval($value->platform_fee),
					'interest_rate' 	=> floatval($value->interest_rate),
					'instalment' 		=> intval($value->instalment),
					'repayment' 		=> intval($value->repayment),
					'reason' 			=> $value->reason, 
					'remark' 			=> $value->remark, 
					'delay' 			=> intval($value->delay),
					'status' 			=> intval($value->status),
					'sub_status' 		=> intval($value->sub_status),
					'created_at' 		=> intval($value->created_at),
				];
			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
    }
	
	/**
     * @api {get} /v2/product/applyinfo/:id 借款方 申請紀錄資訊
	 * @apiVersion 0.2.0
	 * @apiName GetProductApplyinfo
     * @apiGroup Product
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiDescription 預計還款計畫欄位只在待簽約時出現。
	 *
	 * @apiParam {Number} id Targets ID
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Target ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {Number} product_id Product ID
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Number} amount 申請金額
	 * @apiSuccess {Number} loan_amount 核准金額
	 * @apiSuccess {Number} platform_fee 平台服務費
	 * @apiSuccess {Number} interest_rate 核可利率
	 * @apiSuccess {Number} instalment 期數 0:其他
	 * @apiSuccess {Number} repayment 還款方式 1:等額本息
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {Number} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} delay_days 逾期天數
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {Object} certification 認證完成資訊
	 * @apiSuccess {Object} credit 信用資訊
	 * @apiSuccess {String} credit.level 信用指數
	 * @apiSuccess {String} credit.points 信用分數
	 * @apiSuccess {String} credit.amount 總信用額度
	 * @apiSuccess {String} credit.created_at 核准日期
	 * @apiSuccess {Object} amortization_schedule 預計還款計畫
	 * @apiSuccess {String} amortization_schedule.amount 借款金額
	 * @apiSuccess {String} amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} amortization_schedule.rate 年利率
	 * @apiSuccess {String} amortization_schedule.date 起始時間
	 * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} amortization_schedule.leap_year 是否為閏年
	 * @apiSuccess {String} amortization_schedule.year_days 本年日數
	 * @apiSuccess {String} amortization_schedule.XIRR XIRR
	 * @apiSuccess {String} amortization_schedule.schedule 還款計畫
	 * @apiSuccess {String} amortization_schedule.schedule.instalment 第幾期
	 * @apiSuccess {String} amortization_schedule.schedule.repayment_date 還款日
	 * @apiSuccess {String} amortization_schedule.schedule.days 本期日數
	 * @apiSuccess {String} amortization_schedule.schedule.remaining_principal 剩餘本金
	 * @apiSuccess {String} amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {String} amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {String} amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {String} amortization_schedule.total 還款總計
	 * @apiSuccess {String} amortization_schedule.total.principal 本金
	 * @apiSuccess {String} amortization_schedule.total.interest 利息
	 * @apiSuccess {String} amortization_schedule.total.total_payment 加總
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"target_no": "1803269743",
     * 			"product_id":"1",
     * 			"user_id":"1",
     * 			"amount":"5000",
     * 			"loan_amount":"12000",
     * 			"platform_fee":"1500",
     * 			"interest_rate":"9",
     * 			"instalment":"3",
     * 			"repayment":"1",
     * 			"reason":"",
     * 			"remark":"",
     * 			"delay":"0",
     * 			"status":"0",
     * 			"sub_status":"0",
     * 			"created_at":"1520421572",
     * 			"contract":"我是合約",
	 * 			"credit":{
     * 				"level":"1",
     * 				"points":"1985",
     * 				"amount":"45000",
     * 				"created_at":"1520421572"
     * 			},
     *	         "certification": [
     *           	{
     *           	     "id": "1",
     *           	     "name": "身分證認證",
     *           	     "description": "身分證認證",
     *           	     "alias": "id_card",
     *            	    "user_status": "1"
     *           	},
     *           	{
     *           	    "id": "2",
     *            	    "name": "學生證認證",
     *           	    "description": "學生證認證",
     *            	   "alias": "student",
     *            	   "user_status": "1"
     *           	}
     *           ],
  	 *       "amortization_schedule": {
  	 *           "amount": "12000",
  	 *           "instalment": "6",
  	 *           "rate": "9",
  	 *           "date": "2018-04-17",
  	 *           "total_payment": 2053,
  	 *           "leap_year": false,
  	 *           "year_days": 365,
  	 *           "XIRR": 0.0939,
  	 *           "schedule": {
 	 *                "1": {
   	 *                  "instalment": 1,
   	 *                  "repayment_date": "2018-06-10",
   	 *                  "days": 54,
   	 *                  "remaining_principal": "12000",
   	 *                  "principal": 1893,
   	 *                  "interest": 160,
   	 *                  "total_payment": 2053
   	 *              },
   	 *              "2": {
  	 *                   "instalment": 2,
   	 *                  "repayment_date": "2018-07-10",
   	 *                  "days": 30,
  	 *                   "remaining_principal": 10107,
  	 *                   "principal": 1978,
  	 *                   "interest": 75,
 	 *                    "total_payment": 2053
  	 *               },
   	 *              "3": {
 	 *                    "instalment": 3,
 	 *                    "repayment_date": "2018-08-10",
 	 *                    "days": 31,
 	 *                    "remaining_principal": 8129,
  	 *                   "principal": 1991,
  	 *                   "interest": 62,
 	 *                    "total_payment": 2053
 	 *                }
 	 *            },
  	 *           "total": {
 	 *                "principal": 12000,
 	 *                "interest": 391,
 	 *                "total_payment": 12391
	 *            }
	 *        }
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
     */
	public function applyinfo_get($target_id)
    {
		$this->load->library('credit_lib');
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$target 			= $this->target_model->get($target_id);
		if(!empty($target)){
			
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}
			
			$product_list 		= $this->config->item('product_list');
			$product 			= $product_list[$target->product_id];
			$certification		= [];
			$certification_list	= $this->certification_lib->get_status($user_id,$investor);
			if(!empty($certification_list)){
				foreach($certification_list as $key => $value){
					if(in_array($key,$product['certifications'])){
						$certification[] = $value;
					}
				}
			}
			
			$amortization_schedule = [];
			if($target->status==1){
				$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$date='',$target->repayment);
			}
			
			$credit 	= $this->credit_lib->get_credit($user_id,$target->product_id); 
			
			$contract = '';
			if($target->contract_id){
				$this->load->library('Contract_lib');
				$contract_data 	= $this->contract_lib->get_contract($target->contract_id);
				$contract 		= $contract_data['content'];
			}
			
			$data = [
				'id' 				=> intval($target->id),
				'target_no' 		=> $target->target_no,
				'product_id' 		=> intval($target->product_id),
				'user_id' 			=> intval($target->user_id),
				'amount' 			=> intval($target->amount),
				'loan_amount' 		=> intval($target->loan_amount),
				'platform_fee' 		=> intval($target->platform_fee),
				'interest_rate' 	=> floatval($target->interest_rate),
				'instalment' 		=> intval($target->instalment),
				'repayment' 		=> intval($target->repayment),
				'reason' 			=> $target->reason, 
				'remark' 			=> $target->remark, 
				'delay' 			=> intval($target->delay),
				'delay_days' 		=> intval($target->delay_days),
				'status' 			=> intval($target->status),
				'sub_status' 		=> intval($target->sub_status),
				'created_at' 		=> intval($target->created_at),
				'contract'			=> $contract,
				'credit'			=> $credit,
				'certification'		=> $certification,
				'amortization_schedule'	=> $amortization_schedule,
			];

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /v2/product/cancel/:id 借款方 取消申請
	 * @apiVersion 0.2.0
	 * @apiName GetProductCancel
     * @apiGroup Product
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id Targets ID
	 * 
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     */
	public function cancel_get($target_id)
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$targets 	= $this->target_model->get($target_id);
		if(!empty($targets)){

			if($targets->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			if(in_array($targets->status,array(0,1,2)) && $targets->sub_status == 0){
				$rs = $this->target_lib->cancel_target($targets,$user_id);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

		
	/**
     * @api {get} /v2/product/order 借款方 分期訂單列表
	 * @apiVersion 0.2.0
	 * @apiName GetProductOrder
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiGroup Product
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} order_no 訂單編號
	 * @apiSuccess {String} company 經銷商名稱
	 * @apiSuccess {String} merchant_order_no 經銷商訂單編號
	 * @apiSuccess {Number} product_id Product ID
	 * @apiSuccess {Number} amount 金額
	 * @apiSuccess {Number} instalment 期數 0:其他
	 * @apiSuccess {Object} item_name 商品名稱
	 * @apiSuccess {Object} item_count 商品數量
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"order_no": "29-2019013116565856678",
     * 				"company": "普匯金融科技股份有限公司",
     * 				"merchant_order_no": "toytoytoy123",
     * 				"product_id": 2,
     * 				"total": 20619,
     * 				"instalment": 3,
     * 				"item_name": [
     * 					"小雞",
     * 					"'丫丫'"
     * 				],
     * 				"item_count": [
     * 					1,
     * 					2
     * 				],
     * 				"created_at": 1548925018
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
     */
	public function order_get()
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		
		$this->load->model('transaction/order_model');
		$orders 	= $this->order_model->get_many_by([
			'phone'		=> $this->user_info->phone,
			'status'	=> 0,
		]);

		$list	= [];
		if(!empty($orders)){
			$this->load->library('contract_lib');
			foreach($orders as $key => $value){
				$date = get_entering_date();
				$amortization_schedule = $this->financial_lib->get_amortization_schedule(intval($value->total),intval($value->instalment),ORDER_INTEREST_RATE,$date,1);
				$company = $this->user_model->get(intval($value->company_user_id));
				$items 		= [];
				$item_name	= explode(',',$value->item_name);
				$item_count	= explode(',',$value->item_count);
				foreach($item_count as $k => $v){
					$items[] = $item_name[$k].' x '.$v;
					$item_count[$k] = intval($v);
				}
				
				$contract = $this->contract_lib->pretransfer_contract('order',[
					$value->company_user_id,
					$user_id,
					implode(' , ',$items),
					$value->total,
					$amortization_schedule['total']['total_payment'].'、'.$value->instalment.'、'.$amortization_schedule['total_payment'].'、',
				]);

				$list[] = [
					'order_no' 			=> $value->order_no,
					'company' 			=> $company->name,
					'merchant_order_no' => $value->merchant_order_no,
					'product_id' 		=> intval($value->product_id),
					'amount' 			=> intval($value->total),
					'instalment' 		=> intval($value->instalment),
					'pmt' 				=> intval($amortization_schedule['total_payment']),
					'item_name' 		=> $item_name,
					'item_count' 		=> $item_count,
					'contract' 			=> $contract,
					'created_at' 		=> intval($value->created_at),
				];
			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
    }
	
	/**
     * @api {post} /v2/product/order 借款方 申請分期訂單
	 * @apiVersion 0.2.0
	 * @apiName PostProductOrder
     * @apiGroup Product
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiDescription 此API只支援申請分期訂單，產品簽約前一次只能一案。
	 *
	 * @apiParam {String} order_no 訂單編號
	 * @apiParam {file} person_image 本人照
	 * 
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} target_id Targets ID
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"target_id": 1
     * 		}
     * }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
	 *
     * @apiError 408 同產品重複申請
     * @apiErrorExample {Object} 408
     *     {
     *       "result": "ERROR",
     *       "error": "408"
     *     }
	 *
     * @apiError 411 訂單不存在
     * @apiErrorExample {Object} 411
     *     {
     *       "result": "ERROR",
     *       "error": "411"
     *     }
	 *
     * @apiError 412 已申請過或已失效
     * @apiErrorExample {Object} 412
     *     {
     *       "result": "ERROR",
     *       "error": "412"
     *     }
	 *
	 *
     */
	public function order_post()
    {
		$this->load->library('S3_upload');
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('Certification_lib');
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param 		= [];
		$date 		= get_entering_date();
		//必填欄位
		if (empty($input['order_no'])) {
			$this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
		}
		$this->load->model('transaction/order_model');
		$order = $this->order_model->get_by([
			'phone'		=> $this->user_info->phone,
			'order_no'	=> $input['order_no'],
		]);
		
		if($order){
			if($order->status != 0 ){
				$this->response(['result' => 'ERROR','error' => ORDER_STATUS_ERROR]);
			}
			
			//上傳檔案欄位
			if (isset($_FILES['person_image']) && !empty($_FILES['person_image'])) {
				$image 	= $this->s3_upload->image($_FILES,'person_image',$user_id,'order');
				if(!$image){
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			
			$exist = $this->target_model->get_by([
				'status <='		=> 1,
				'user_id'		=> $user_id,
				'product_id'	=> $order->product_id
			]);
			if($exist){
				$this->response(['result' => 'ERROR','error' => APPLY_EXIST]);
			}
			
			$items 		= [];
			$item_name	= explode(',',$order->item_name);
			$item_count	= explode(',',$order->item_count);
			foreach($item_count as $k => $v){
				$items[] = $item_name[$k].' x '.$v;
				$item_count[$k] = intval($v);
			}
			
			$amortization_schedule = $this->financial_lib->get_amortization_schedule(intval($order->total),intval($order->instalment),ORDER_INTEREST_RATE,$date,1);
			
			$this->load->library('contract_lib');
			$contract = $this->contract_lib->sign_contract('order',[
				$order->company_user_id,
				$user_id,
				implode(' , ',$items),
				$order->total,
				$amortization_schedule['total']['total_payment'].'、'.$order->instalment.'、'.$amortization_schedule['total_payment'].'、',
			]);
				
			$param = [
				'product_id'	=> $order->product_id,
				'user_id'		=> $user_id,
				'amount'		=> $order->total,
				'damage_rate' 	=> LIQUIDATED_DAMAGES,
				'instalment'	=> $order->instalment,
				'order_id'		=> $order->id,
				'reason'		=> '分期:'.implode(' , ',$items),
				'contract_id'	=> $contract,
				'person_image'	=> $image,
				'loan_date'		=> $date,
			];

			$insert = $this->target_lib->add_target($param);
			if($insert){
				$this->order_model->update($order->id,['status'=>2]);
				$this->response(['result' => 'SUCCESS','data'=>['target_id'=> $insert ]]);
			}else{
				$this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
			}
		}
		$this->response(array('result' => 'ERROR','error' => ORDER_NOT_EXIST ));
   }
	
}