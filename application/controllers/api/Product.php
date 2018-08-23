<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Product extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/product_model');
		$this->load->library('Certification_lib');
		$this->load->library('Target_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 				= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 			= AUTHORIZATION::getUserInfoByToken($token);
			$nonCheckMethods 	= ['list'];
			if(in_array($method, $nonCheckMethods) && empty($token)){
				$this->user_info = array();
			}else{
				if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
					$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
				}
			
				$this->user_info = $this->user_model->get($tokenData->id);
				if($tokenData->auth_otp != $this->user_info->auth_otp){
					$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
				}
				
				//只限借款人
				if($tokenData->investor != 0){
					$this->response(array('result' => 'ERROR','error' => IS_INVERTOR ));
				}
				
				$this->user_info->investor 		= $tokenData->investor;
				$this->user_info->expiry_time 	= $tokenData->expiry_time;
			}
        }
    }

	
	/**
     * @api {get} /product/list 借款方 取得產品列表
     * @apiGroup Product
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} rank 排序
	 * @apiSuccess {json} instalment 可申請期數
	 * @apiSuccess {json} repayment 可選還款方式
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {json} target 申請資訊（未簽約）
	 * @apiSuccess {json} certification 認證完成資訊
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"rank":"0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
     * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
	 * 				"instalment": [
	 * 					{
     * 				      "name": "3期",
     * 				      "value": 3
     * 				    },
	 * 					{
     * 				      "name": "12期",
     * 				      "value": 12
     * 				    },
	 * 					{
     * 				      "name": "24期",
     * 				      "value": 24
     * 				    },
	 * 				],
	 * 				"repayment": [
	 * 					{
     * 				      "name": "等額本息",
     * 				      "value": 1
     * 				    }
	 * 				],
	 * 				"target":{
     * 					"id":"1",
     * 					"target_no": "1803269743",
     * 					"amount":"5000",
     * 					"loan_amount":"",
     * 					"status":"0",
     * 					"instalment":"3期",
     * 					"created_at":"1520421572"
     * 				},
	 * 				"certification":[
	 * 					{
     * 						"id":"1",
     * 						"name": "實名認證",
     * 						"description":"實名認證",
     * 						"alias":"id_card",
     * 						"user_status":"1"
     * 					},
	 * 					{
     * 						"id":"2",
     * 						"name": "學生身份認證",
     * 						"description":"學生身份認證",
     * 						"alias":"student",
     * 						"user_status":"1"
     * 					}
	 * 				]
     * 			}
     * 			]
     * 		}
     * }
     */
	 	
	public function list_get()
    {
		$instalment_list= $this->config->item('instalment');
		$repayment_type = $this->config->item('repayment_type');
		$input 			= $this->input->get();
		$data			= array();
		$list			= array();
		$where			= array( "status" => 1 );
	
		$product_list 	= $this->product_model->get_many_by($where);
		if(isset($this->user_info->id) && $this->user_info->id){
			$certification_list	= $this->certification_lib->get_status($this->user_info->id,$this->user_info->investor);
		}
		
		if(!empty($product_list)){
			foreach($product_list as $key => $value){
				$target 				= array();
				$certification 			= array();
				$value->certifications 	= json_decode($value->certifications,true);
				if(isset($this->user_info->id) && $this->user_info->id){
					$targets = $this->target_model->get_by(array("status <="=>1,"user_id"=>$this->user_info->id,"product_id"=>$value->id));
					if($targets){
						$target['id'] 			= $targets->id;
						$target['target_no'] 	= $targets->target_no;
						$target['status'] 		= $targets->status;
						$target['amount'] 		= $targets->amount;
						$target['loan_amount'] 	= $targets->loan_amount;
						$target['created_at'] 	= $targets->created_at;
						$target['instalment'] 	= $instalment_list[$targets->instalment];
						
						if(!empty($certification_list)){
							foreach($certification_list as $k => $v){
								if(in_array($v->id,$value->certifications)){
									$certification[] = array(
										"id" 			=> $v->id,
										"name" 			=> $v->name,
										"description" 	=> $v->description,
										"alias" 		=> $v->alias,
										"user_status" 	=> $v->user_status,
									);
								}
							}
						}
					}
				}

				$instalment = json_decode($value->instalment,TRUE);
				foreach($instalment as $k => $v){
					$instalment[$k] = array("name"=>$instalment_list[$v],"value"=>$v);
				}
				
				$repayment = json_decode($value->repayment,TRUE);
				foreach($repayment as $k => $v){
					$repayment[$k] = array("name"=>$repayment_type[$v],"value"=>$v);
				}
				
				$list[] = array(
					"id" 					=> $value->id,
					"name" 					=> $value->name,
					"description" 			=> $value->description,
					"rank"					=> $value->rank,
					"loan_range_s"			=> $value->loan_range_s,
					"loan_range_e"			=> $value->loan_range_e,
					"interest_rate_s"		=> $value->interest_rate_s,
					"interest_rate_e"		=> $value->interest_rate_e,
					"charge_platform"		=> PLATFORM_FEES,
					"charge_platform_min"	=> PLATFORM_FEES_MIN,
					"instalment"			=> $instalment,
					"repayment"				=> $repayment,
					"target"				=> $target,
					"certification"			=> $certification,
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }

	/**
     * @api {get} /product/info/{ID} 借款方 取得產品資訊
     * @apiGroup Product
	 * @apiParam {number} ID 產品ID
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} rank 排序
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {json} instalment 可申請期數
	 * @apiSuccess {json} repayment 還款方式
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"product":
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"rank":"0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
	 * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
	 * 				"instalment": [
	  * 				{
     * 				      "name": "3期",
     * 				      "value": 3
     * 				    },
	  * 				{
     * 				      "name": "12期",
     * 				      "value": 12
     * 				    },
	  * 				{
     * 				      "name": "24期",
     * 				      "value": 24
     * 				    },
	 * 				],
	 * 				"repayment": [
	  * 				{
     * 				      "name": "等額本息",
     * 				      "value": 1
     * 				    }
	 * 				],
     * 			}
     * 		}
     * }
	 *
	 * @apiUse TokenError
	 * @apiUse IsInvestor
	 *
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
	 *
	 * @apiError 408 未完成預先申請
     * @apiErrorExample {json} 408
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     */
	 
	public function info_get($id)
    {
		if($id){
			$data			= array();
			$product 		= $this->product_model->get(intval($id));
			$user_id 		= $this->user_info->id;
			$instalment_list= $this->config->item('instalment');
			$repayment_type = $this->config->item('repayment_type');
			if($product && $product->status == 1 ){
				$product->certifications 	= json_decode($product->certifications,TRUE);
				
				$instalment = json_decode($product->instalment,TRUE);
				foreach($instalment as $k => $v){
					$instalment[$k] = array("name"=>$instalment_list[$v],"value"=>$v);
				}
				
				$repayment = json_decode($product->repayment,TRUE);
				foreach($repayment as $k => $v){
					$repayment[$k] = array("name"=>$repayment_type[$v],"value"=>$v);
				}
				
				$data['product'] = array(
					"id" 					=> $product->id,
					"name" 					=> $product->name,
					"description" 			=> $product->description,
					"rank"					=> $product->rank,
					"loan_range_s"			=> $product->loan_range_s,
					"loan_range_e"			=> $product->loan_range_e,
					"interest_rate_s"		=> $product->interest_rate_s,
					"interest_rate_e"		=> $product->interest_rate_e,
					"charge_platform"		=> PLATFORM_FEES,
					"charge_platform_min"	=> PLATFORM_FEES_MIN,
					"instalment"			=> $instalment,
					"repayment"				=> $repayment,
				);
				
				$this->response(array('result' => 'SUCCESS','data' => $data ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
    }
	
	/**
     * @api {post} /product/apply 借款方 申請產品
     * @apiGroup Product
	 * @apiParam {number} product_id 產品ID
     * @apiParam {number} amount 借款金額
     * @apiParam {number} instalment 申請期數
	 * @apiParam {String} [promote_code] 邀請碼
	 * 
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} target_id Targets ID
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "target_id": "1",
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse IsInvestor
     *
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
	 *
     * @apiError 402 超過此產品可申請額度
     * @apiErrorExample {json} 402
     *     {
     *       "result": "ERROR",
     *       "error": "402"
     *     }
	 *
     * @apiError 403 不支援此期數
     * @apiErrorExample {json} 403
     *     {
     *       "result": "ERROR",
     *       "error": "403"
     *     }
	 *
     * @apiError 408 重複申請
     * @apiErrorExample {json} 408
     *     {
     *       "result": "ERROR",
     *       "error": "408"
     *     }
	 *
     */
	public function apply_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("user_id"=> $user_id);
		
		//必填欄位
		$fields 	= ['product_id','amount','instalment'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}
		$param["promote_code"] 	= isset($input['promote_code'])?$input['promote_code']:"";
		$product 				= $this->product_model->get($input['product_id']);
		if($product && $product->status == 1 ){
			$product->instalment 		= json_decode($product->instalment,TRUE);
			if(!in_array($input['instalment'],$product->instalment)){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_INSTALMENT_ERROR ));
			}
			
			if($input['amount'] < $product->loan_range_s || $input['amount'] > $product->loan_range_e){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_AMOUNT_RANGE ));
			}

			$target = $this->target_model->get_by(array("status <="=>1,"user_id"=>$user_id,"product_id"=>$product->id));
			if($target){
				$this->response(array('result' => 'ERROR','error' => APPLY_EXIST ));
			}
			
			$param["target_no"] = $this->get_target_no();
			$insert = $this->target_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS','target_id'=>$insert));
			}else{
				$param["target_no"] = $this->get_target_no();
				$insert = $this->target_model->insert($param);
				if($insert){
					$this->response(array('result' => 'SUCCESS','target_id'=>$insert));
				}
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
    }

	/**
     * @api {post} /product/signing 借款方 申請簽約
     * @apiGroup Product
	 * @apiParam {number} target_id Targets ID
	 * @apiParam {file} person_image 本人照
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
	 * @apiUse IsInvestor
     *
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {json} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {json} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {json} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 202 未通過所需的驗證
     * @apiErrorExample {json} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 未綁定金融帳號
     * @apiErrorExample {json} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 206 人臉辨識不通過
     * @apiErrorExample {json} 206
     *     {
     *       "result": "ERROR",
     *       "error": "206"
     *     }
	 *
     * @apiError 208 未滿20歲或大於35歲
     * @apiErrorExample {json} 208
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
		$param		= array("status"=>2);
		
		//必填欄位
		$fields 	= ['target_id'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}
		
		//上傳檔案欄位
		$file_fields 	= ['person_image'];
		foreach ($file_fields as $field) {
			if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
				$image 	= $this->s3_upload->image($_FILES,$field,$user_id,'signing_target');
				if($image){
					$param[$field] = $image;
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}
		
		$targets 	= $this->target_model->get($param['target_id']);
		if(!empty($targets)){

			if($targets->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}
			
			$product = $this->product_model->get($targets->product_id);
			if($product && $product->status == 1 ){

				//檢查認證 NOT_VERIFIED
				$product->certifications 	= json_decode($product->certifications,TRUE);
				$certification_list	= $this->certification_lib->get_status($user_id,$investor);
				foreach($certification_list as $key => $value){
					if(in_array($value->id,$product->certifications) && $value->user_status!=1){
						$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
					}
				}
				
				if(get_age($this->user_info->birthday) < 20 || get_age($this->user_info->birthday) > 35 ){
					$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
				}
				
				//檢查金融卡綁定 NO_BANK_ACCOUNT
				$bank_account = $this->user_bankaccount_model->get_by(array("status"=>1,"investor"=>$investor,"user_id"=>$user_id ));
				if($bank_account){
					if($bank_account->verify==0){
						$this->user_bankaccount_model->update($bank_account->id,array("verify"=>2));
						$this->load->library('Sendemail');
						$this->sendemail->admin_notification("新的一筆金融帳號驗證 借款端會員ID:".$user_id,"有新的一筆金融帳號驗證 借款端會員ID:".$user_id);
					}
				}else{
					$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
				}
				
				if($targets->status == 1){
					unset($param['target_id']);
					$this->load->library('Sendemail');
					$this->sendemail->admin_notification("案件待審批 會員ID：".$user_id,"案件待審批 會員ID：".$user_id." 案號：".$targets->target_no);
					$rs = $this->target_model->update($targets->id,$param);
					$this->response(array('result' => 'SUCCESS'));
				}else{
					$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
				}
			}
			$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /product/applylist 借款方 申請紀錄列表
     * @apiGroup Product
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} platform_fee 平台服務費
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"target_no": "1803269743",
     * 				"product_id":"2",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"user_id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
     * 				"platform_fee":"",
     * 				"interest_rate":"0,
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"sub_status":"0",
     * 				"created_at":"1520421572"
     * 			},
     * 			{
     * 				"id":"2",
     * 				"target_no": "1803269713",
     * 				"product_id":"2",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"user_id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
     * 				"platform_fee":"",
     * 				"interest_rate":"",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"sub_status":"0",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse IsInvestor
     *
     */
	public function applylist_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$param				= array( "user_id"=> $user_id);
		$targets 			= $this->target_model->get_many_by($param);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$list				= array();
		if(!empty($targets)){
			foreach($targets as $key => $value){
				$product_info = $this->product_model->get($value->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
				);
				
				$list[] = array(
					"id" 				=> $value->id,
					"target_no" 		=> $value->target_no,
					"product_id" 		=> $value->product_id,
					"product" 			=> $product,
					"user_id" 			=> $value->user_id,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"platform_fee" 		=> $value->platform_fee?$value->platform_fee:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"instalment" 		=> $instalment_list[$value->instalment],
					"repayment" 		=> $repayment_type[$value->repayment],
					"remark" 			=> $value->remark, 
					"delay" 			=> $value->delay,
					"status" 			=> $value->status,
					"sub_status" 		=> $value->sub_status,
					"created_at" 		=> $value->created_at,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array("list" => $list) ));
    }
	
	/**
     * @api {get} /product/applyinfo/{ID} 借款方 申請紀錄資訊
     * @apiGroup Product
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Target ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} platform_fee 平台服務費
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {json} certification 認證完成資訊
	 * @apiSuccess {json} credit 信用資訊
	 * @apiSuccess {String} credit.level 信用指數
	 * @apiSuccess {String} credit.points 信用分數
	 * @apiSuccess {String} credit.amount 總信用額度
	 * @apiSuccess {String} credit.created_at 核准日期
	 * @apiSuccess {json} amortization_schedule 預計還款計畫(簽約後不出現)
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
     * @apiSuccessExample {json} SUCCESS
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
     * 			"instalment":"3期",
     * 			"repayment":"等額本息",
     * 			"remark":"",
     * 			"delay":"0",
     * 			"status":"0",
     * 			"sub_status":"0",
     * 			"created_at":"1520421572",
     * 			"contract":"我是合約",
     * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸",
     * 				"description":"輕鬆學貸",
     * 				"alias":"FA"
     * 			},
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
	 * @apiUse IsInvestor
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {json} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {json} 405
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
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target)){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			$product_info = $this->product_model->get($target->product_id);
			$product = array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
				"description"	=> $product_info->description,
			);
			$product_info->certifications 	= json_decode($product_info->certifications,TRUE);
			$certification					= array();
			$certification_list				= $this->certification_lib->get_status($user_id,$investor);
			if(!empty($certification_list)){
				foreach($certification_list as $key => $value){
					if(in_array($value->id,$product_info->certifications)){
						$certification[] = array(
							"id" 			=> $value->id,
							"name" 			=> $value->name,
							"description" 	=> $value->description,
							"alias" 		=> $value->alias,
							"user_status" 	=> $value->user_status,
						);
					}
				}
			}
			
			$amortization_schedule = array();
			if($target->status==1){
				$this->load->library('Financial_lib');
				$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$date="",$target->repayment);
			}
			
			$credit_info 	= $this->credit_lib->get_credit($user_id,$product_info->id); 
			$credit			= array();
			if($credit_info){
				$credit = $credit_info;
			}
			
			$contract = "";
			if($target->contract_id){
				$this->load->library('Contract_lib');
				$contract_data = $this->contract_lib->get_contract($target->contract_id);
				$contract = $contract_data["content"];
			}
			
			$fields = $this->target_model->detail_fields;
			foreach($fields as $field){
				$data[$field] = isset($target->$field)?$target->$field:"";
				if($field=="instalment"){
					$data[$field] = $instalment_list[$target->$field];
				}
				
				if($field=="repayment"){
					$data[$field] = $repayment_type[$target->$field];
				}
			}

			$data["contract"] 				= $contract;
			$data["credit"] 				= $credit;
			$data["product"] 				= $product;
			$data["certification"] 			= $certification;
			$data["amortization_schedule"] 	= $amortization_schedule;

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /product/cancel/{ID} 借款方 取消申請
     * @apiGroup Product
	 * @apiParam {number} id Targets ID
	 * 
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse IsInvestor
     *
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {json} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {json} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {json} 407
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

			if(in_array($targets->status,array(0,1,2))){
				$rs = $this->target_model->update($targets->id,array("status"=>8));
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	private function get_target_no(){
		$code = "STN".date("Ymd").rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$result = $this->target_model->get_by('target_no',$code);
		if ($result) {
			return $this->get_target_no();
		}else{
			return $code;
		}
	}
}