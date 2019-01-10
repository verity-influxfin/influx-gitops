<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Order extends REST_Controller {

	public $cooperation_info;
	
    public function __construct()
    {
        parent::__construct();
		$authorization 	= isset($this->input->request_headers()['Authorization'])?$this->input->request_headers()['Authorization']:'';
		$time 			= isset($this->input->request_headers()['Timestamp'])?$this->input->request_headers()['Timestamp']:'';
		$cooperation_id = isset($this->input->request_headers()['CooperationID'])?$this->input->request_headers()['CooperationID']:'';
		
		if(strlen($authorization) != 39 || substr($authorization,0,7) != 'Bearer '){
			$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		}
		
		$time_ragne = time() - intval($time);
		if($time_ragne > COOPER_TIMEOUT){
			$this->response(['error' =>'TimeOut'],REST_Controller::HTTP_FORBIDDEN);//403 TimeOut
		}
		
		if($cooperation_id){
			$this->load->model('user/cooperation_model');
			$cooperation = $this->cooperation_model->get_by([
				'cooperation_id'	=> $cooperation_id,
				'status'			=> 1,
			]);
			if($cooperation){
				$this->cooperation_info = $cooperation;
			}else{
				$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
			}
		}else{
			$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
		}


		$middles = '';
		if($this->request->method == 'post'){
			$method = $this->router->fetch_method();
			$input 	= $this->input->post(NULL, TRUE);
			ksort($input);
			$middles = implode('',array_values($input));
		}
		
		$signature = 'Bearer '.MD5(SHA1($cooperation_id.$middles.$time).$cooperation->cooperation_key);
		if($signature != $authorization){
			$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		}
    }
	
	/**
     * @api {get} /order/product Product List
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderProduct
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
     *
     * @apiSuccess {String} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name Product Name
	 * @apiSuccess {String} description Description
	 * @apiSuccess {String} rank Rank
	 * @apiSuccess {Object} instalment Number Of Instalment
	 * @apiSuccess {Number} loan_range_s Minimum Loan Amount
	 * @apiSuccess {Number} loan_range_e Maximum Loan Amount
	 * @apiSuccess {Number} interest_rate_s Minimum Interest Rate(%)
	 * @apiSuccess {Number} interest_rate_e Maximum Interest Rate(%)
	 * @apiSuccess {Number} charge_platform Platform Fee Rate(%)
	 * @apiSuccess {Number} charge_platform_min Minimum Platform Fee
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"rank":"0",
     * 				"loan_range_s":"5000",
     * 				"loan_range_e":"120000",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"20",
     * 				"charge_platform":"3",
     * 				"charge_platform_min":"500",
	 * 				"instalment": [
     *					3,
     * 				    6,
     * 				    12,
     * 				    18,
     * 				    24
     * 				  ]
     * 				}
     * 			]
     * 		}
     * }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse CooperationNotFound
     */
	 	
	public function product_get()
    {
		$list			= array();
		$this->load->model('loan/product_model');
		$product_list 	= $this->product_model->order_by('rank','asc')->get_many_by(array('type'=>2 ,'status' => 1 ));
		if(!empty($product_list)){
			foreach($product_list as $key => $value){
				$list[] = array(
					'id' 					=> $value->id,
					'name' 					=> $value->name,
					'description' 			=> $value->description,
					'rank'					=> $value->rank,
					'loan_range_s'			=> $value->loan_range_s,
					'loan_range_e'			=> $value->loan_range_e,
					'interest_rate_s'		=> $value->interest_rate_s,
					'interest_rate_e'		=> $value->interest_rate_e,
					'charge_platform'		=> PLATFORM_FEES,
					'charge_platform_min'	=> PLATFORM_FEES_MIN,
					'instalment'			=> json_decode($value->instalment,TRUE)
				);
			}
		}
		$data['list'] = $list;
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }

	/**
     * @api {get} /order/schedule Repayment Schedule
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderSchedule
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiParam {Number} product_id Product ID
	 * @apiParam {Number{5000-300000}} amount 金額
	 * @apiParam {Number} instalment 期數
     *
     * @apiSuccess {String} result SUCCESS
	 * @apiSuccess {json} amortization_schedule 預計還款計畫
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
	 *
     * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
	 *      "data": {
  	 *      	"amortization_schedule": {
  	 *          	"amount": "12000",
  	 *          	"instalment": "6",
  	 *          	"rate": "9",
  	 *          	"date": "2018-04-17",
  	 *         	 	"total_payment": 2053,
  	 *         	 	"leap_year": false,
  	 *          	"year_days": 365,
  	 *          	"XIRR": 0.0939,
  	 *          	"schedule": {
 	 *              	"1": {
   	 *                  	"instalment": 1,
   	 *                  	"repayment_date": "2018-06-10",
   	 *                  	"days": 54,
   	 *                  	"remaining_principal": "12000",
   	 *                  	"principal": 1893,
   	 *                  	"interest": 160,
   	 *                  	"total_payment": 2053
   	 *              	},
   	 *              	"2": {
  	 *                   	"instalment": 2,
   	 *                  	"repayment_date": "2018-07-10",
   	 *                  	"days": 30,
  	 *                   	"remaining_principal": 10107,
  	 *                   	"principal": 1978,
  	 *                   	"interest": 75,
 	 *                    	"total_payment": 2053
  	 *               	},
   	 *              	"3": {
 	 *                    	"instalment": 3,
 	 *                    	"repayment_date": "2018-08-10",
 	 *                    	"days": 31,
 	 *                    	"remaining_principal": 8129,
  	 *                   	"principal": 1991,
  	 *                   	"interest": 62,
 	 *                    	"total_payment": 2053
 	 *                	}
 	 *            	},
  	 *           	"total": {
 	 *              	"principal": 12000,
 	 *                	"interest": 391,
 	 *                	"total_payment": 12391
	 *            	}
	 *      	}
	 *    	}
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
     */
	public function schedule_get()
    {
    }
	
	/**
     * @api {post} /order/create Create Order
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName PostOrderCreate
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiParam {Number} product_id Product ID
	 * @apiParam {Number{5000-300000}} amount 金額
	 * @apiParam {Number} instalment 期數
	 * @apiParam {String} merchant_order_no 自訂編號
	 * @apiParam {String} item_name 商品名稱，多項商品時，以逗號分隔
	 * @apiParam {String} item_count 商品數量，多項商品時，以逗號分隔
	 * @apiParam {String} item_price 商品單價，多項商品時，以逗號分隔
	 *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} merchant_order_no 廠商自訂編號
     * @apiSuccess {String} order_no 訂單單號
     * @apiSuccess {String} request_token RequestToken
     * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
	 *		"merchant_order_no": "123456789",
	 *		"order_no": "20180405113558632",
     *      "request_token": "fcea920f7412b5da7be0cf42b8c93759"
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (409) InsertError Insert Error.
	 * @apiErrorExample InsertError
	 *     HTTP/1.1 409 Not Found
	 *     {
	 *       "error": "InsertError"
	 *     }
     * 
     */
	public function add_post()
    {
    }

	 /**
     * @api {get} /order/info Order Information
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderInfo
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} company 公司名稱
     * @apiSuccess {String} tax_id 統一編號
     * @apiSuccess {String} name 負責人姓名
     * @apiSuccess {String} phone 負責人電話
     * @apiSuccess {String} my_promote_code 邀請碼
	 *
     * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"company": "普匯金融科技股份有限公司",
     *      	"tax_id": "68566881",
     *      	"name": "陳霈",
     *      	"phone": "0912345678",
     *      	"my_promote_code": "9JJ12CQ5",  
	 *      }
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
     *
     */
	public function info_get()
    {
    }

	 /**
     * @api {get} /order/list Order List
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderList
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} company 公司名稱
     * @apiSuccess {String} tax_id 統一編號
     * @apiSuccess {String} name 負責人姓名
     * @apiSuccess {String} phone 負責人電話
     * @apiSuccess {String} my_promote_code 邀請碼
	 *
     * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"company": "普匯金融科技股份有限公司",
     *      	"tax_id": "68566881",
     *      	"name": "陳霈",
     *      	"phone": "0912345678",
     *      	"my_promote_code": "9JJ12CQ5",  
	 *      }
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
     *
     */
	public function list_get()
    {
    }
	
}
