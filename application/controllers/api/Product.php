<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Product extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('product/product_model');
		$this->load->model('product/product_category_model');
		$this->load->model('platform/certification_model');
		$this->load->model('transaction/target_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list','category','info'];
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
     * @api {get} /product/category 借款方產品 取得分類列表
     * @apiGroup Product
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} parent_id 父層級
	 * @apiSuccess {String} rank 排序
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"parent_id":"0",
     * 				"rank":"0"
     * 			},
     * 			{
     * 				"id":"2",
     * 				"name":"房屋方案",
     * 				"description":"房屋方案",
     * 				"parent_id":"0",
     * 				"rank":"0"
     * 			}
     * 			]
     * 		}
     * }
     */
	 
	public function category_get()
    {
		$category_list 	= $this->product_category_model->get_many_by(array("status"=>1));
		$list			= array();
		if(!empty($category_list)){
			foreach($category_list as $key => $value){
				$list[] = array(
					"id" 			=> $value->id,
					"name" 			=> $value->name,
					"description" 	=> $value->description,
					"parent_id" 	=> $value->parent_id,
					"rank"			=> $value->rank,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	/**
     * @api {get} /product/list 借款方產品 取得產品列表
     * @apiGroup Product
	 * @apiParam {number} category 產品分類ID
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} parent_id 父層產品
	 * @apiSuccess {String} rank 排序
	 * @apiSuccess {json} instalment 可申請期數
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {json} category 分類資訊
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"category": {
     * 				"id": "1",
     * 				"name": "學生區",
     * 				"description": "學生區啊啊啊啊啊啊啊",
     * 				"parent_id": "0",
     * 				"rank": "0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
     * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
	 * 				"instalment": "[3,6,12,18]"
     * 			},
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"parent_id":"0",
     * 				"rank":"0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
     * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
	 * 				"instalment": "[3,6,12,18]"
     * 			}
     * 			]
     * 		}
     * }
     */
	 	
	public function list_get()
    {
		$input 	= $this->input->get();
		$data	= array();
		$list	= array();
		$where	= array( "status" => 1 );
		if(isset($input['category']) && intval($input['category'])){
			$where['category'] = intval($input['category']);
			$category 	= $this->product_category_model->get(intval($input['category']));
			if($category){
				$data['category'] = array(
					"id" 			=> $category->id,
					"name" 			=> $category->name,
					"description" 	=> $category->description,
					"parent_id" 	=> $category->parent_id,
					"rank"			=> $category->rank,
				);
			}
		}
		
		$product_list 	= $this->product_model->get_many_by($where);
		if(!empty($product_list)){
			foreach($product_list as $key => $value){
				$list[] = array(
					"id" 					=> $value->id,
					"name" 					=> $value->name,
					"description" 			=> $value->description,
					"alias" 				=> $value->alias,
					"category"				=> $value->category,
					"parent_id"				=> $value->parent_id,
					"rank"					=> $value->rank,
					"status"				=> $value->status,
					"loan_range_s"			=> $value->loan_range_s,
					"loan_range_e"			=> $value->loan_range_e,
					"interest_rate_s"		=> $value->interest_rate_s,
					"interest_rate_e"		=> $value->interest_rate_e,
					"charge_platform"		=> $value->charge_platform,
					"charge_platform_min"	=> $value->charge_platform_min,
					"instalment"			=> json_decode($value->instalment,TRUE),
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }

	/**
     * @api {get} /product/info/{ID} 借款方產品 取得產品資訊
     * @apiGroup Product
	 * @apiParam {number} ID 產品ID
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} alias 產品
	 * @apiSuccess {String} category 分類ID
	 * @apiSuccess {String} parent_id 父層產品
	 * @apiSuccess {String} rank 排序
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {String} charge_overdue 逾期管理費(%/天)	
	 * @apiSuccess {String} charge_sub_loan 轉貸服務費(%)
	 * @apiSuccess {String} charge_prepayment 提還手續費(%)
	 * @apiSuccess {json} certifications 需完成的認證列表
	 * @apiSuccess {json} ratings 評級方式資訊
	 * @apiSuccess {json} instalment 可申請期數
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"product":
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"alias":"FT",
     * 				"category":"3",
     * 				"parent_id":"0",
     * 				"rank":"0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
     * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
     * 				"charge_overdue":"0",
     * 				"charge_sub_loan":"0",
     * 				"charge_prepayment":"0",
     * 				"certifications":[{"id":"1","name":"身分證認證","description":"身分證認證","alias":"id_card"},{"id":"2","name":"學生證認證","description":"學生證認證","alias":"student"}],
     * 				"ratings":{"1":{"id":"1","status":1,"value":0},"2":{"id":"2","status":1,"value":"123"},"3":{"id":"3","status":1,"value":0}},
	 * 				"instalment": [3,6,12,18]
     * 			}
     * 		}
     * }
	 *
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     */
	 
	public function info_get($id)
    {
		if($id){
			$data		= array();
			$product 	= $this->product_model->get(intval($id));
			if($product && $product->status == 1 ){
				$product->certifications 	= json_decode($product->certifications,TRUE);
				$certification				= array();
				$certification_list = $this->certification_model->get_many_by(array("status"=>1));
				foreach($certification_list as $key => $value){
					if(in_array($value->id,$product->certifications)){
						$certification[] = array(
							"id" 			=> $value->id,
							"name" 			=> $value->name,
							"description" 	=> $value->description,
							"alias" 		=> $value->alias,
						);
					}
				}
				$data['product'] = array(
					"id" 					=> $product->id,
					"name" 					=> $product->name,
					"description" 			=> $product->description,
					"alias" 				=> $product->alias,
					"category"				=> $product->category,
					"parent_id"				=> $product->parent_id,
					"rank"					=> $product->rank,
					"loan_range_s"			=> $product->loan_range_s,
					"loan_range_e"			=> $product->loan_range_e,
					"interest_rate_s"		=> $product->interest_rate_s,
					"interest_rate_e"		=> $product->interest_rate_e,
					"charge_platform"		=> $product->charge_platform,
					"charge_platform_min"	=> $product->charge_platform_min,
					"charge_overdue"		=> $product->charge_overdue,
					"charge_sub_loan"		=> $product->charge_sub_loan,
					"charge_prepayment"		=> $product->charge_prepayment,
					"certifications"		=> $certification,
					"ratings"				=> json_decode($product->ratings,TRUE),
					"instalment"			=> json_decode($product->instalment,TRUE),
				);
				$this->response(array('result' => 'SUCCESS',"data" => $data ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
    }
	
	/**
     * @api {post} /product/apply 借款方產品 申請借款
     * @apiGroup Product
	 * @apiParam {number} product_id (required) 產品ID
     * @apiParam {number} amount (required) 借款金額
     * @apiParam {number} instalment (required) 申請期數
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
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
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
     */
	public function apply_post()
    {

		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("user_id"=> $user_id);
		
		//必填欄位
		$fields 	= ['product_id','amount','instalment'];
		foreach ($fields as $field) {
			$input[$field] = intval($input[$field]);
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = $input[$field];
			}
		}

		$product = $this->product_model->get($input['product_id']);
		if($product && $product->status == 1 ){
			$product->instalment 		= json_decode($product->instalment,TRUE);
			$product->certifications 	= json_decode($product->certifications,TRUE);
			if(!in_array($input['instalment'],$product->instalment)){
				$this->response(array('result' => 'ERROR',"error" => PRODUCT_INSTALMENT_ERROR ));
			}
			
			if($input['amount']<$product->loan_range_s || $input['amount']>$product->loan_range_e){
				$this->response(array('result' => 'ERROR',"error" => PRODUCT_AMOUNT_RANGE ));
			}

			$this->load->model('user/user_model');
			$this->load->model('user/user_bankaccount_model');
			
			$user_info = $this->user_model->get($user_id);	
			if($user_info){
				//檢查認證 NOT_VERIFIED
				$this->load->library('Certification_lib');
				$certification_list	= $this->certification_lib->get_status($user_id);
				foreach($certification_list as $key => $value){
					if(in_array($value->id,$product->certifications) && $value->user_status!=1){
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

			$insert = $this->target_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		
		$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
    }
	
	/**
     * @api {get} /product/applylist 借款方產品 申請借款列表
     * @apiGroup Product
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
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2: 待借款 3:待放款（結標）4:還款中 5:已結案 9:申請失敗
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
     * @api {get} /product/applyinfo/{ID} 借款方產品 申請借款資訊
     * @apiGroup Product
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
}
