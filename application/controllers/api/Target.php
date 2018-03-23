<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Target extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('product/product_model');
		$this->load->model('product/product_category_model');
		$this->load->model('platform/certification_model');
		$this->load->model('transaction/target_model');
		$this->load->model('transaction/investment_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list','info'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
			if($tokenData->investor!=1){
				$this->response(array('result' => 'ERROR',"error" => NOT_INVERTOR ));
			}
            if (empty($tokenData->id) || empty($tokenData->phone)) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			$this->user_info = $tokenData;
        }
    }
	

	/**
     * @api {get} /target/list 出借方 取得標的列表
     * @apiGroup Target
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請額度
	 * @apiSuccess {String} loan_amount 核可額度
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"product_id":"2",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"user_id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"5000",
     * 				"interest_rate":"12",
     * 				"total_interest":"150",
     * 				"instalment":"3",
     * 				"remark":"",
     * 				"status":"2",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
     */
	 	
	public function list_get()
    {
		$input 	= $this->input->get();
		$data	= array();
		$list	= array();
		$where	= array( "status" => 2 );
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$target_list 		= $this->target_model->get_many_by($where);
		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				$product_info = $this->product_model->get($value->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
					"description"	=> $product_info->description,
					"alias"			=> $product_info->alias,
				);
				
				$list[] = array(
					"id" 				=> $value->id,
					"product_id" 		=> $value->product_id,
					"product" 			=> $product,
					"user_id" 			=> $value->user_id,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"total_interest" 	=> $value->total_interest?$value->total_interest:"",
					"instalment" 		=> $instalment_list[$value->instalment],
					"repayment" 		=> $repayment_type[$value->repayment],
					"contract" 			=> $value->contract,
					"remark" 			=> $value->remark,
					"delay" 			=> $value->delay,
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at,
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }

	/**
     * @api {get} /target/info/{ID} 出借方 取得標的資訊
     * @apiGroup Target
	 * @apiParam {number} ID 標的ID
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Target ID
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請額度
	 * @apiSuccess {String} loan_amount 核可額度
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} bank_account 借款人收款帳號
	 * @apiSuccess {String} virtual_account 還款虛擬帳號
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 5:已結案 9:申請失敗
	 * @apiSuccess {String} created_at 申請日期

     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"product_id":"2",
     * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸",
     * 				"description":"輕鬆學貸",
     * 				"alias":"FA"
     * 			},
     * 			"user_id":"1",
     * 			"amount":"5000",
     * 			"loan_amount":"4000",
     * 			"interest_rate":"18",
     * 			"total_interest":"11111",
     * 			"instalment":"3期",
     * 			"repayment":"等額本息",
     * 			"bank_code":"222",
     * 			"branch_code":"5245",
     * 			"bank_account":"111111111111",
     * 			"virtual_account":"1111111111111111",
     * 			"remark":"",
     * 			"delay":"0",
     * 			"status":"2",
     * 			"created_at":"1520421572"
     * 		}
     *    }
	 *
	 * @apiError 801 標的不存在
     * @apiErrorExample {json} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
     */
	 
	public function info_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		if(!empty($target) && $target->status==2){
			
			$product_info = $this->product_model->get($target->product_id);
			$product = array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
				"description"	=> $product_info->description,
				"alias"			=> $product_info->alias,
			);
			
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
			$data["product"] = $product;


			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => TARGET_NOT_EXIST ));
    }
	
	/**
     * @api {post} /target/apply 出借方 申請出借
     * @apiGroup Target
	 * @apiParam {number} target_id (required) 產品ID
     * @apiParam {number} amount (required) 出借金額
	 * 
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
	 * @apiUse NotInvestor
     *
	 * @apiError 801 標的不存在
     * @apiErrorExample {json} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
	 *
     * @apiError 802 金額過高或過低
     * @apiErrorExample {json} 802
     *     {
     *       "result": "ERROR",
     *       "error": "802"
     *     }
	 *
     * @apiError 803 金額須為全額或千的倍數
     * @apiErrorExample {json} 803
     *     {
     *       "result": "ERROR",
     *       "error": "803"
     *     }
	 *
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
	 *
     * @apiError 202 未通過所需的驗證(實名驗證)
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
     */
	public function apply_post()
    {

		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("user_id"=> $user_id);
		
		//必填欄位
		$fields 	= ['target_id','amount'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$input[$field] = intval($input[$field]);
				$param[$field] = $input[$field];
			}
		}

		$target = $this->target_model->get($input['target_id']);
		if($target && $target->status == 2 ){
			
			if( $input['amount'] > $target->loan_amount ){
				$this->response(array('result' => 'ERROR',"error" => TARGET_AMOUNT_RANGE ));
			}

			/*if( $input['amount'] != $target->loan_amount && $input['amount']<TARGET_AMOUNT_MIN ){
				$this->response(array('result' => 'ERROR',"error" => TARGET_AMOUNT_LIMIT ));
			}*/
			
			$this->load->model('user/user_model');
			$this->load->model('user/user_bankaccount_model');
			
			$user_info = $this->user_model->get($user_id);	
			if($user_info){
				//檢查認證 NOT_VERIFIED
				$this->load->library('Certification_lib');
				$certification_list	= $this->certification_lib->get_status($user_id);
				foreach($certification_list as $key => $value){
					if( $value->alias=='id_card' && $value->user_status!=1 ){
						$this->response(array('result' => 'ERROR',"error" => NOT_VERIFIED ));
					}
				}
			}else{
				$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
			}
			
			//檢查金融卡綁定 NO_BANK_ACCOUNT
			$bank_account = $this->user_bankaccount_model->get_by(array("status"=>1,"user_id"=>$user_id ));
			if(!$bank_account){
				$this->response(array('result' => 'ERROR',"error" => NO_BANK_ACCOUNT ));
			}

			$insert = $this->investment_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		
		$this->response(array('result' => 'ERROR',"error" => TARGET_NOT_EXIST ));
    }
	
	/**
     * @api {get} /target/applylist 出借方 申請紀錄列表
     * @apiGroup Target
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請額度
	 * @apiSuccess {String} loan_amount 核可額度
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
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
     * 				"interest_rate":"0,
     * 				"total_interest":"",
     * 				"instalment":"3",
     * 				"remark":"",
     * 				"status":"0",
     * 				"created_at":"1520421572"
     * 			},
     * 			{
     * 				"id":"2",
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
     * 				"interest_rate":"",
     * 				"total_interest":"",
     * 				"instalment":"3",
     * 				"remark":"",
     * 				"status":"0",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
     *
     */
	public function applylist_get()
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array( "user_id"=> $user_id);
		$targets 	= $this->target_model->get_many_by($param);
		
		if(!empty($targets)){
			foreach($targets as $key => $value){
				$product_info = $this->product_model->get($value->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
					"description"	=> $product_info->description,
					"alias"			=> $product_info->alias,
				);
				
				$list[] = array(
					"id" 				=> $value->id,
					"product_id" 		=> $value->product_id,
					"product" 			=> $product,
					"user_id" 			=> $value->user_id,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"total_interest" 	=> $value->total_interest?$value->total_interest:"",
					"instalment" 		=> $value->instalment,
					"contract" 			=> $value->contract,
					"remark" 			=> $value->remark,
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	
	/**
     * @api {get} /target/applyinfo/{ID} 出借方 申請紀錄資訊
     * @apiGroup Target
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Target ID
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請額度
	 * @apiSuccess {String} loan_amount 核可額度
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} bank_account 借款人收款帳號
	 * @apiSuccess {String} virtual_account 還款虛擬帳號
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 5:已結案 9:申請失敗
	 * @apiSuccess {String} created_at 申請日期

     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"product_id":"2",
     * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸",
     * 				"description":"輕鬆學貸",
     * 				"alias":"FA"
     * 			},
     * 			"user_id":"1",
     * 			"amount":"5000",
     * 			"loan_amount":"",
     * 			"interest_rate":"",
     * 			"total_interest":"",
     * 			"instalment":"3",
     * 			"bank_account":"",
     * 			"virtual_account":"",
     * 			"remark":"",
     * 			"status":"0",
     * 			"created_at":"1520421572"
     * 		}
     *    }
	 *
	 * @apiUse TokenError
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
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("id"=> $target_id , "user_id"=> $user_id);
		$targets 	= $this->target_model->get($target_id);
		if(!empty($targets)){
			if($targets->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}

			$product_info = $this->product_model->get($targets->product_id);
			$product = array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
				"description"	=> $product_info->description,
				"alias"			=> $product_info->alias,
			);
			
			$data = array(
				"id" 				=> $targets->id,
				"product_id" 		=> $targets->product_id,
				"product" 			=> $product,
				"user_id" 			=> $targets->user_id,
				"amount" 			=> $targets->amount,
				"loan_amount" 		=> $targets->loan_amount?$targets->loan_amount:"",
				"interest_rate" 	=> $targets->interest_rate?$targets->interest_rate:"",
				"total_interest" 	=> $targets->total_interest?$targets->total_interest:"",
				"instalment" 		=> $targets->instalment,
				"bank_account" 		=> $targets->bank_code.'-'.$targets->bank_account,
				"virtual_account"	=> $targets->virtual_account,
				"contract" 			=> $targets->contract,
				"remark" 			=> $targets->remark,
				"status" 			=> $targets->status,
				"created_at" 		=> $targets->created_at,
			);
			
			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {post} /target/applyedit 出借方 申請紀錄修改
     * @apiGroup Target
	 * @apiParam {number} id (required) Targets ID
	 * @apiParam {number} action (required) 動作 contract：確認合約 cancel：取消申請
	 * 
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
     * @apiError 406 此動作不存在
     * @apiErrorExample {json} 406
     *     {
     *       "result": "ERROR",
     *       "error": "406"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {json} 406
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     */
	public function applyedit_post()
    {

		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("user_id"=> $user_id);
		
		//必填欄位
		$fields 	= ['id','action'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}
		}

		$targets 	= $this->target_model->get($input['id']);
		if(!empty($targets)){
			if($targets->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}
			
			if(!in_array($input['action'],array("contract","cancel"))){
				$this->response(array('result' => 'ERROR',"error" => APPLY_ACTION_ERROR ));
			}
			
			if($input['action']=="contract"){
				if($targets->status == 1){
					$rs = $this->target_model->update($targets->id,array("status"=>2));
				}else{
					$this->response(array('result' => 'ERROR',"error" => APPLY_STATUS_ERROR ));
				}
			}else if($input['action']=="cancel"){
				if(in_array($targets->status,array(0,1,2))){
					$rs = $this->target_model->update($targets->id,array("status"=>8));
				}else{
					$this->response(array('result' => 'ERROR',"error" => APPLY_STATUS_ERROR ));
				}
			}
			$this->response(array('result' => 'SUCCESS'));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
}
