<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Repayment extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('product/product_model');
		$this->load->model('platform/certification_model');
		$this->load->model('transaction/target_model');
		$this->load->model('transaction/investment_model');
		$this->load->model('transaction/transaction_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			
			//只限借款人
			if($tokenData->investor != 0){
				$this->response(array('result' => 'ERROR',"error" => IS_INVERTOR ));
			}
			
			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }
	

	/**
     * @api {get} /repayment/dashboard 借款端 我的還款
     * @apiGroup Repayment
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} total_lending 借款總額
	 * @apiSuccess {String} total_payable 代還本息
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"total_lending": "12345",
     * 				"total_payable": "1588"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
	 *
     */
	 	
	public function dashboard_get()
    {
		$input 	= $this->input->get();
		$data	= array("total_lending"=>12345,"total_payable"=>1588);
 
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }
	
	/**
     * @api {get} /repayment/list 借款方 我的還款列表
     * @apiGroup Repayment
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {String} next_repayment_date 最近一期應還款日
	 * @apiSuccess {String} next_repayment_instalment 最近期數
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
     * 				"interest_rate":"0,
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"created_at":"1520421572",
     * 				"next_repayment_date":"2018-06-10",
     * 				"next_repayment_instalment":"1"
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
     * 				"interest_rate":"",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"created_at":"1520421572",
     * 				"next_repayment_date":"",
     * 				"next_repayment_instalment":""
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
     *
     */
	public function list_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$param				= array( "user_id"=> $user_id,"status"=>array(5,10));
		$targets 			= $this->target_model->get_many_by($param);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$list				= array();
		if(!empty($targets)){
			foreach($targets as $key => $value){
				$transaction 				= $this->transaction_model->order_by("limit_date","asc")->get_by(array("source"=>array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST),"target_id"=>$value->id,"status"=>"1"));
				$next_repayment_date 		= $transaction?$transaction->limit_date:"";
				$next_repayment_instalment 	= $transaction?$transaction->instalment_no:"";
				
				$product_info 	= $this->product_model->get($value->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
					"description"	=> $product_info->description,
					"alias"			=> $product_info->alias,
				);
				
				$list[] = array(
					"id" 				=> $value->id,
					"target_no" 		=> $value->target_no,
					"product_id" 		=> $value->product_id,
					"product" 			=> $product,
					"user_id" 			=> $value->user_id,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"instalment" 		=> $instalment_list[$value->instalment],
					"repayment" 		=> $repayment_type[$value->repayment],
					"contract" 			=> $value->contract,
					"remark" 			=> $value->remark, 
					"delay" 			=> $value->delay,
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at,
					"next_repayment_date" 		=> $next_repayment_date,
					"next_repayment_instalment" => $next_repayment_instalment,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	
	/**
     * @api {get} /repayment/info/{ID} 借款方 我的還款資訊
     * @apiGroup Repayment
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Target ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} bank_code 借款人收款銀行代碼
	 * @apiSuccess {String} branch_code 借款人收款分行代碼
	 * @apiSuccess {String} bank_account 借款人收款帳號
	 * @apiSuccess {String} virtual_account 還款虛擬帳號
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {json} certification 認證完成資訊(簽約後不出現)
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
     * 			"interest_rate":"9",
     * 			"instalment":"3期",
     * 			"repayment":"等額本息",
     * 			"bank_code":"",
     * 			"branch_code":"",
     * 			"bank_account":"",
     * 			"virtual_account":"",
     * 			"remark":"",
     * 			"delay":"0",
     * 			"status":"0",
     * 			"created_at":"1520421572",
     * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸",
     * 				"description":"輕鬆學貸",
     * 				"alias":"FA"
     * 			}
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
	public function info_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target) && in_array($target->status,array(5,10))){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}

			$product_info 	= $this->product_model->get($target->product_id);
			$product 		= array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
				"description"	=> $product_info->description,
				"alias"			=> $product_info->alias,
			);
			$product_info->certifications 	= json_decode($product_info->certifications,TRUE);
			
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

			$data["product"] 				= $product;


			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
}
