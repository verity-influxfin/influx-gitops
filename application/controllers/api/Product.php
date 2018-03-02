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
					"id" 				=> $value->id,
					"name" 				=> $value->name,
					"description" 		=> $value->description,
					"alias" 			=> $value->alias,
					"category"			=> $value->category,
					"parent_id"			=> $value->parent_id,
					"rank"				=> $value->rank,
					"status"			=> $value->status,
					"loan_range_s"		=> $value->loan_range_s,
					"loan_range_e"		=> $value->loan_range_e,
					"interest_rate_s"	=> $value->interest_rate_s,
					"interest_rate_e"	=> $value->interest_rate_e,
					"charge_platform"		=> $value->charge_platform,
					"charge_platform_min"	=> $value->charge_platform_min,
					"instalment"			=> $value->instalment,
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
     * 				"ratings":"{"1":{"id":"1","status":1,"value":0},"2":{"id":"2","status":1,"value":"123"},"3":{"id":"3","status":1,"value":0}}",
	 * 				"instalment": "[3,6,12,18]"
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
				$data['product'] = array(
					"id" 				=> $product->id,
					"name" 				=> $product->name,
					"description" 		=> $product->description,
					"alias" 			=> $product->alias,
					"category"			=> $product->category,
					"parent_id"			=> $product->parent_id,
					"rank"				=> $product->rank,
					"loan_range_s"		=> $product->loan_range_s,
					"loan_range_e"		=> $product->loan_range_e,
					"interest_rate_s"	=> $product->interest_rate_s,
					"interest_rate_e"		=> $product->interest_rate_e,
					"charge_platform"		=> $product->charge_platform,
					"charge_platform_min"	=> $product->charge_platform_min,
					"charge_overdue"		=> $product->charge_overdue,
					"charge_sub_loan"		=> $product->charge_sub_loan,
					"charge_prepayment"		=> $product->charge_prepayment,
					"ratings"				=> $product->ratings,
					"instalment"			=> $product->instalment,
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
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 504 身分證字號格式錯誤
     * @apiErrorExample {json} 504
     *     {
     *       "result": "ERROR",
     *       "error": "504"
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
			$product->instalment = json_decode($product->instalment,TRUE);
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
				//檢查身分證字號 NOT_VERIFIED
				if(empty($user_info->id_number) || !check_cardid($user_info->id_number)){
					$this->response(array('result' => 'ERROR',"error" => NOT_VERIFIED ));
				}
			}else{
				$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
			}
			
			//檢查金融卡綁定 NO_BANK_ACCOUNT
			$bank_account = $this->user_bankaccount_model->get_by(array("status"=>1,"user_id"=>$user_id ));
			if(!$bank_account){
				$this->response(array('result' => 'ERROR',"error" => NO_BANK_ACCOUNT ));
			}
			
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		
		$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
    }
}
