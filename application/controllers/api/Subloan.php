<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Subloan extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->library('Subloan_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
			
			//只限借款人
			if($tokenData->investor != 0){
				$this->response(array('result' => 'ERROR','error' => IS_INVERTOR ));
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
     * @api {get} /subloan/preapply/{ID} 借款方 產品轉換前資訊
      * @apiGroup Subloan
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} amount 金額
	 * @apiSuccess {json} instalment 期數
	 * @apiSuccess {json} repayment 還款方式
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 	       "amount": 51493,
     * 	       "instalment": [
     * 	       	{
     * 	       		"name": "3期",
     * 	       		"value": 3
     * 	       	},
     * 	       	{
     * 	       		"name": "6期",
     * 	       		"value": 6
     * 	       	},
     * 	       	{
     * 	       		"name": "12期",
     * 	       		"value": 12
     * 	       	},
     * 	       	{
     * 	       		"name": "18期",
     * 	       		"value": 18
     * 	       	},
     * 	       	{
     * 	       		"name": "24期",
     * 	       		"value": 24
     * 	       	}
     * 	       ],
     * 	       "repayment": {
     * 	       		"1": {
     * 	       			"name": "等額本息",
     * 	       			"value": 1
     * 	       		},
     * 	       		"2": {
     * 	       			"name": "先息後本",
     * 	       			"value": 2
     * 	       		}
     * 	       }
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
	 *
     * @apiError 407 目前狀態無法完成此動作(需逾期且過寬限期)
     * @apiErrorExample {json} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 903 已申請提前還款或產品轉換
     * @apiErrorExample {json} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
	public function preapply_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target) && $target->status == 5 ){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}
			
			if($target->sub_status != 0){
				$this->response(array('result' => 'ERROR','error' => TARGET_HAD_SUBSTATUS ));
			}

			if($target->delay == 0 || $target->delay_days < GRACE_PERIOD){ 
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
			
			$this->load->model('loan/product_model');
			$product 	= $this->product_model->get($target->product_id);
			$instalment = json_decode($product->instalment,TRUE);
			foreach($instalment as $k => $v){
				$instalment[$k] = array("name"=>$instalment_list[$v],"value"=>$v);
			}
			
			$repayment = array();
			foreach($repayment_type as $k => $v){
				$repayment[$k] = array("name"=>$v,"value"=>$k);
			}
			
			$info 		= $this->subloan_lib->get_info($target);
			$data		= array(
				"amount" 		=> $info["total"],
				"instalment"	=> $instalment,
				"repayment"		=> $repayment
			);

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {post} /subloan/apply/ 借款方 產品轉換申請
     * @apiGroup Subloan
	 * @apiParam {number} target_id Target ID
	 * @apiParam {number} instalment 申請期數
	 * @apiParam {number} repayment 還款方式
	 * 
	 * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS"
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse InsertError
	 *
	 *
     * @apiError 403 不支援此期數
     * @apiErrorExample {json} 403
     *     {
     *       "result": "ERROR",
     *       "error": "403"
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
     * @apiError 407 目前狀態無法完成此動作(需逾期且過寬限期)
     * @apiErrorExample {json} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 409 不支援此還款方式
     * @apiErrorExample {json} 409
     *     {
     *       "result": "ERROR",
     *       "error": "409"
     *     }
	 *
     * @apiError 903 已申請提前還款或產品轉換
     * @apiErrorExample {json} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
	public function apply_post()
    {
		$input 				= $this->input->post(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$param				= array("user_id"=> $user_id);
		$repayment_type 	= $this->config->item('repayment_type');
		//必填欄位
		$fields 	= ['target_id','instalment','repayment'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}
		
		$target = $this->target_model->get($input["target_id"]);
		if(!empty($target) && $target->status == 5 ){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			if($target->sub_status != 0){
				$this->response(array('result' => 'ERROR','error' => TARGET_HAD_SUBSTATUS ));
			}
			
			if($target->delay == 0 || $target->delay_days < GRACE_PERIOD){ 
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
			
			$this->load->model('loan/product_model');
			$product 			 = $this->product_model->get($target->product_id);
			$product->instalment = json_decode($product->instalment,TRUE);
			if(!in_array($input['instalment'],$product->instalment)){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_INSTALMENT_ERROR ));
			}
			
			if(!isset($repayment_type[$input['repayment']])){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_REPAYMENT_ERROR ));
			}
			
			$rs = $this->subloan_lib->apply_subloan($target,$param);
			if($rs){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /subloan/applyinfo/{ID} 借款方 產品轉換紀錄資訊
     * @apiGroup Subloan
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} target_id Target ID
	 * @apiSuccess {String} amount 產品轉換金額
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} settlement_date 結息日
	 * @apiSuccess {String} status 產品轉換狀態 0:待簽約 1:轉貸中 2:成功 8:已取消 9:申請失敗	
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} subloan_target 產品轉換產生的Target
	 * @apiSuccess {String} subloan_target.id Target ID
	 * @apiSuccess {String} subloan_target.target_no 案號
	 * @apiSuccess {String} subloan_target.product_id Product ID
	 * @apiSuccess {String} subloan_target.user_id User ID
	 * @apiSuccess {String} subloan_target.amount 申請金額
	 * @apiSuccess {String} subloan_target.loan_amount 核准金額
	 * @apiSuccess {String} subloan_target.platform_fee 平台服務費
	 * @apiSuccess {String} subloan_target.interest_rate 核可利率
	 * @apiSuccess {String} subloan_target.instalment 期數
	 * @apiSuccess {String} subloan_target.repayment 還款方式
	 * @apiSuccess {String} subloan_target.remark 備註
	 * @apiSuccess {String} subloan_target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} subloan_target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} subloan_target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} subloan_target.created_at 申請日期
	 * @apiSuccess {String} subloan_target.contract 合約內容
	 * @apiSuccess {json} subloan_target.product 產品資訊
	 * @apiSuccess {String} subloan_target.product.name 產品名稱
	 * @apiSuccess {json} subloan_target.amortization_schedule 預計還款計畫(簽約後不出現)
	 * @apiSuccess {String} subloan_target.amortization_schedule.amount 借款金額
	 * @apiSuccess {String} subloan_target.amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} subloan_target.amortization_schedule.rate 年利率
	 * @apiSuccess {String} subloan_target.amortization_schedule.date 起始時間
	 * @apiSuccess {String} subloan_target.amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} subloan_target.amortization_schedule.leap_year 是否為閏年
	 * @apiSuccess {String} subloan_target.amortization_schedule.year_days 本年日數
	 * @apiSuccess {String} subloan_target.amortization_schedule.XIRR XIRR
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule 還款計畫
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.instalment 第幾期
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.repayment_date 還款日
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.days 本期日數
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.remaining_principal 剩餘本金
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {String} subloan_target.amortization_schedule.total 還款總計
	 * @apiSuccess {String} subloan_target.amortization_schedule.total.principal 本金
	 * @apiSuccess {String} subloan_target.amortization_schedule.total.interest 利息
	 * @apiSuccess {String} subloan_target.amortization_schedule.total.total_payment 加總
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"target_id":"1",
     * 			"amount":"53651",
     * 			"instalment":"3期",
     * 			"repayment":"先息後本",
     * 			"settlement_date":"2018-05-26",
     * 			"status":"0",
     * 			"created_at":"1527151277",
	 * 			"subloan_target": {
     * 				"id":"35",
     * 				"target_no": "1805247784",
     * 				"product_id":"1",
     * 				"user_id":"1",
     * 				"amount":"53651",
     * 				"loan_amount":"53651",
     * 				"platform_fee":"1610",
     * 				"interest_rate":"9",
     * 				"instalment":"3期",
     * 				"repayment":"先息後本",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"1",
     * 				"sub_status":"8",
     * 				"created_at":"1520421572",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸"
     * 				},
  	 *       		"amortization_schedule": {
  	 *           		"amount": "12000",
  	 *           		"instalment": "6",
  	 *           		"rate": "9",
  	 *           		"date": "2018-04-17",
  	 *           		"total_payment": 2053,
  	 *           		"leap_year": false,
  	 *           		"year_days": 365,
  	 *           		"XIRR": 0.0939,
  	 *           		"schedule": {
 	 *                		"1": {
   	 *                  		"instalment": 1,
   	 *                  		"repayment_date": "2018-06-10",
   	 *                  		"days": 54,
   	 *                  		"remaining_principal": "12000",
   	 *                  		"principal": 1893,
   	 *                  		"interest": 160,
   	 *                  		"total_payment": 2053
   	 *              		},
   	 *              		"2": {
  	 *                   		"instalment": 2,
   	 *                  		"repayment_date": "2018-07-10",
   	 *                  		"days": 30,
  	 *                   		"remaining_principal": 10107,
  	 *                   		"principal": 1978,
  	 *                   		"interest": 75,
 	 *                    		"total_payment": 2053
  	 *               		},
   	 *              		"3": {
 	 *                    		"instalment": 3,
 	 *                    		"repayment_date": "2018-08-10",
 	 *                    		"days": 31,
 	 *                    		"remaining_principal": 8129,
  	 *                   		"principal": 1991,
  	 *                   		"interest": 62,
 	 *                    		"total_payment": 2053
 	 *                		}
 	 *            		},
  	 *           		"total": {
 	 *                		"principal": 12000,
 	 *                		"interest": 391,
 	 *                		"total_payment": 12391
	 *            		}
	 *        		}
     * 			}
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
	 *
     * @apiError 904 尚未申請產品轉換
     * @apiErrorExample {json} 904
     *     {
     *       "result": "ERROR",
     *       "error": "904"
     *     }
     */
	public function applyinfo_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		$subloan_target		= array();
		if(!empty($target)){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}
			
			$subloan = $this->subloan_lib->get_subloan($target);
			if(!$subloan){
				$this->response(array('result' => 'ERROR','error' => TARGET_SUBLOAN_NOT_EXIST ));
			}
			
			$data = array(
				"target_id"			=> $subloan->target_id,
				"amount"			=> $subloan->amount,
				"instalment"		=> $instalment_list[$subloan->instalment],
				"repayment"			=> $repayment_type[$subloan->repayment],
				"settlement_date"	=> $subloan->settlement_date,
				"status"			=> $subloan->status,
				"created_at"		=> $subloan->created_at,
			);
			
			$new_target  = $this->target_model->get($subloan->new_target_id);

			$amortization_schedule = array();
			if($new_target->status==1){
				$amortization_schedule = $this->financial_lib->get_amortization_schedule($new_target->loan_amount,$new_target->instalment,$new_target->interest_rate,$date="",$new_target->repayment);
			}
			
			$contract = "";
			if($new_target->contract_id){
				$this->load->library('Contract_lib');
				$contract_data = $this->contract_lib->get_contract($new_target->contract_id);
				$contract = $contract_data["content"];
			}
			
			$fields = $this->target_model->detail_fields;
			foreach($fields as $field){
				$subloan_target[$field] = isset($new_target->$field)?$new_target->$field:"";
				if($field=="instalment"){
					$subloan_target[$field] = $instalment_list[$new_target->$field];
				}
				
				if($field=="repayment"){
					$subloan_target[$field] = $repayment_type[$new_target->$field];
				}
				if($field=="product_id"){
					$this->load->model('loan/product_model');
					$product_info = $this->product_model->get($new_target->product_id);
					$product = array(
						"id"			=> $product_info->id,
						"name"			=> $product_info->name,
					);
				}
			}
			$subloan_target["contract"] 				= $contract;
			$subloan_target["product"] 					= $product;
			$subloan_target["amortization_schedule"] 	= $amortization_schedule;
			$data["subloan_target"]						= $subloan_target;
			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {post} /subloan/signing 借款方 產品轉換簽約
     * @apiGroup Subloan
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
	 * @apiUse BlockUser
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
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {json} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 206 人臉辨識不通過
     * @apiErrorExample {json} 206
     *     {
     *       "result": "ERROR",
     *       "error": "206"
     *     }
	 *
     * @apiError 904 尚未申請產品轉換
     * @apiErrorExample {json} 904
     *     {
     *       "result": "ERROR",
     *       "error": "904"
     *     }
	 *
     */
	public function signing_post()
    {
		$this->load->library('S3_upload');
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= array();
		
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
		
		$target 	= $this->target_model->get($param['target_id']);
		if(!empty($target)){

			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}
			
			$subloan = $this->subloan_lib->get_subloan($target);
			if(!$subloan){
				$this->response(array('result' => 'ERROR','error' => TARGET_SUBLOAN_NOT_EXIST ));
			}

			if($subloan->status == 0){
				$rs = $this->subloan_lib->signing_subloan($subloan,$param);
				if($rs){
					$this->response(array('result' => 'SUCCESS'));
				}
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}else{
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}

			$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /subloan/cancel/{ID} 借款方 取消產品轉換
     * @apiGroup Subloan
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
	 * @apiUse BlockUser
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
     * @apiError 904 尚未申請產品轉換
     * @apiErrorExample {json} 904
     *     {
     *       "result": "ERROR",
     *       "error": "904"
     *     }
	 *
     */
	public function cancel_get($target_id)
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$target 	= $this->target_model->get($target_id);
		if(!empty($target)){

			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			$subloan = $this->subloan_lib->get_subloan($target);
			if(!$subloan){
				$this->response(array('result' => 'ERROR','error' => TARGET_SUBLOAN_NOT_EXIST ));
			}
			
			if($subloan->status == 0 ){
				$this->subloan_lib->cancel_subloan($subloan);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
}
