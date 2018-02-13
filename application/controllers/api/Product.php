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
     * @api {get} /product/category 貸款產品 取得分類列表
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
     * @api {get} /product/list 貸款產品 取得產品列表
     * @apiGroup Product
	 * @apiParam {number} category 產品分類ID
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} parent_id 父層產品
	 * @apiSuccess {String} rank 排序
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
     * 				"rank": "0"
     * 			},
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"parent_id":"0",
     * 				"rank":"0"
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
					"id" 			=> $value->id,
					"name" 			=> $value->name,
					"description" 	=> $value->description,
					"alias" 		=> $value->alias,
					"category"		=> $value->category,
					"parent_id"		=> $value->parent_id,
					"rank"			=> $value->rank,
					"status"		=> $value->status,
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }

	/**
     * @api {get} /product/info/{ID} 貸款產品 取得產品資訊
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
     * 				"ratings":"{"1":{"id":"1","status":1,"value":0},"2":{"id":"2","status":1,"value":"123"},"3":{"id":"3","status":1,"value":0}}"
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
					"ratings"				=> $product->	ratings,
				);
				$this->response(array('result' => 'SUCCESS',"data" => $data ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
    }
	
}
