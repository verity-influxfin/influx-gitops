<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Recoveries extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('product/product_model');
		$this->load->model('transaction/target_model');
		$this->load->model('transaction/investment_model');
		$this->load->model('transaction/transaction_model');
		$this->load->library('Certification_lib');
		$this->load->library('Transaction_lib'); 
		$this->load->library('Target_lib'); 
		$this->load->library('Passbook_lib'); 
		
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			
			//只限出借人
			if($tokenData->investor != 1){
				$this->response(array('result' => 'ERROR',"error" => NOT_INVERTOR ));
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
     * @api {get} /recoveries/dashboard 出借方 我的帳戶
     * @apiGroup Recoveries
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} remaining_principal 持有債權
	 * @apiSuccess {String} accounts_receivable 應收帳款
	 * @apiSuccess {String} interest_receivable 應收利息
	 * @apiSuccess {String} interest 已收利息收入
	 * @apiSuccess {String} other_income 已收其他收入
	 * @apiSuccess {json} principal_level 標的等級應收帳款 1~5:正常 6:觀察 7:次級 8:不良
	 * @apiSuccess {json} funds 資金資訊
	 * @apiSuccess {String} funds.total 資金總額
	 * @apiSuccess {String} funds.last_recharge_date 最後一次匯入日
	 * @apiSuccess {String} funds.frozen 待交易餘額
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 				"remaining_principal": "50000",
     * 				"interest": "0",
     * 				"accounts_receivable": "50751",
     * 				"interest_receivable": "751",
     * 				"other_income": "0",
     * 				"principal_level": {
     * 				    "1": 0,
     * 				    "2": 500,
     * 				    "3": 0,
     * 				    "4": 50000,
     * 				    "5": 0,
     * 				    "6": 0,
     * 				    "7": 0,
     * 				    "8": 0,
     * 				},
     * 				"funds": {
     * 				    "total": 500,
     * 				    "last_recharge_date": "2018-05-03 19:15:42",
     * 				    "frozen": 0
     * 				}
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse NotInvestor
	 *
     */
	 	
	public function dashboard_get()
    {
		$input 		 			= $this->input->get();
		$user_id 	 			= $this->user_info->id;
		$remaining_principal 	= 0;
		$accounts_receivable	= 0;
		$interest_receivable	= 0;
		$interest				= 0;
		$other_income			= 0;
		$credit_level 			= $this->config->item('credit_level');
		$principal_level		= array();
		if($credit_level){
			foreach($credit_level as $level => $value){
				$principal_level[$level] = 0;
			}
		}
		
		$user					= array();
		$transaction = $this->transaction_model->order_by("limit_date","asc")->get_many_by(array("user_to"=>$user_id,"status"=>array(1,2)));
		if($transaction){
			$target_ids 	= array();
			$target_level 	= array();
			foreach($transaction as $key => $value){
				if($value->target_id && !in_array($value->target_id,$target_ids)){
					$target_ids[] = $value->target_id;
				}
			}
			if(!empty($target_ids)){
				$targets = $this->target_model->get_many($target_ids);
				foreach($targets as $key => $value){
					$target_level[$value->id] = $value->credit_level;
				}
			}
			
			foreach($transaction as $key => $value){
				if($value->source == SOURCE_AR_PRINCIPAL && $value->status==1){
					$remaining_principal += $value->amount;
					$accounts_receivable += $value->amount;
					$principal_level[$target_level[$value->target_id]] += $value->amount;
				}
				if($value->source == SOURCE_AR_INTEREST && $value->status==1){
					$interest_receivable += $value->amount;
					$accounts_receivable += $value->amount;
				}
				
				if(in_array($value->source,array(SOURCE_INTEREST,SOURCE_DELAYINTEREST))&& $value->status==2){
					$interest += $value->amount;
				}

				if($value->source ==SOURCE_PREPAYMENT_ALLOWANCE && $value->status==2){
					$other_income += $value->amount;
				}
			}
		}
		$virtual_account = $this->virtual_account_model->get_by(array("investor"=>1,"user_id"=>$user_id));
		$funds 			 = $this->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
		$data			 = array(
			"remaining_principal"	=> $remaining_principal,
			"interest"				=> $interest,
			"accounts_receivable"	=> $accounts_receivable,
			"interest_receivable"	=> $interest_receivable,
			"other_income"			=> $other_income,
			"principal_level"		=> $principal_level,
			"funds"					=> $funds
		);
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }
	
	/**
     * @api {get} /recoveries/list 出借方 已出借列表
     * @apiGroup Recoveries
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Investments ID
	 * @apiSuccess {String} loan_amount 出借金額
	 * @apiSuccess {String} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {json} target 標的資訊
	 * @apiSuccess {String} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} target.credit_level 信用指數
	 * @apiSuccess {String} target.delay_days 逾期天數
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {String} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {json} next_repayment 最近一期應還款
	 * @apiSuccess {String} next_repayment.date 還款日
	 * @apiSuccess {String} next_repayment.instalment 期數
	 * @apiSuccess {String} next_repayment.amount 金額
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"amount":"50000",
     * 				"loan_amount":"",
     * 				"status":"3",
     * 				"created_at":"1520421572",
	 * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸"
     * 				},
     * 				"target": {
     * 					"id": "19",
     * 					"target_no": "1804233189",
     * 					"credit_level": "4",
     * 					"delay": "0",
     * 					"delay_days": "0",
     * 					"status": "5"
     * 				},
	 * 	        	"next_repayment": {
     * 	            	"date": "2018-06-10",
     * 	            	"instalment": "1",
     * 	            	"amount": "16890"
     * 	        	}
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse NotInvestor
     *
     */
	public function list_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$param				= array( "user_id"=> $user_id,"status"=>array(3,10));
		$investments		= $this->investment_model->get_many_by($param);
		$list				= array();
		if(!empty($investments)){
			foreach($investments as $key => $value){

				$target_info = $this->target_model->get($value->target_id);
				$target = array(
					"id"			=> $target_info->id,
					"target_no"		=> $target_info->target_no,
					"credit_level"		=> $target_info->credit_level,
					"delay"			=> $target_info->delay,
					"delay_days"	=> $target_info->delay_days,
					"status"		=> $target_info->status,
				);
				
				$next_repayment = array(
					"date" 			=> "",
					"instalment"	=> "",
					"amount"		=> 0,
				);

				$transaction = $this->transaction_model->order_by("limit_date","asc")->get_many_by(array("target_id"=>$target_info->id,"user_to"=>$user_id,"status"=>"1"));
				if($transaction){
					$first = true;
					foreach($transaction as $k => $v){
						if($first){
							$next_repayment["date"] 		= $v->limit_date;
							$next_repayment["instalment"] 	= $v->instalment_no;
							$first = false;
						}

						if($v->limit_date && $v->limit_date == $next_repayment["date"]){
							$next_repayment["amount"] += $v->amount;
						}
					}
				}
				
				$product_info = $this->product_model->get($target_info->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
				);
				
				$list[] = array(          
					"id" 				=> $value->id,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at,
					"product" 			=> $product,
					"target" 			=> $target,
					"next_repayment" 	=> $next_repayment,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	
	/**
     * @api {get} /recoveries/info/{ID} 出借方 已出借資訊
     * @apiGroup Recoveries
	 * @apiParam {number} ID Investments ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Investments ID
	 * @apiSuccess {String} loan_amount 出借金額
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {String} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {json} target 標的資訊
	 * @apiSuccess {String} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} target.credit_level 信用指數
	 * @apiSuccess {String} target.delay_days 逾期天數
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {String} target.instalment 期數
	 * @apiSuccess {String} target.repayment 還款方式
	 * @apiSuccess {String} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {json} amortization_schedule 回款計畫
	 * @apiSuccess {String} amortization_schedule.amount 借款金額
	 * @apiSuccess {String} amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} amortization_schedule.rate 年利率
	 * @apiSuccess {String} amortization_schedule.date 起始時間
	 * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} amortization_schedule.schedule 回款計畫
	 * @apiSuccess {String} amortization_schedule.schedule.instalment 第幾期
	 * @apiSuccess {String} amortization_schedule.schedule.repayment_date 還款日
	 * @apiSuccess {String} amortization_schedule.schedule.days 本期日數
	 * @apiSuccess {String} amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {String} amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {String} amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {String} amortization_schedule.schedule.repayment 已還款金額
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"amount":"50000",
     * 			"loan_amount":"",
     * 			"status":"3",
     * 			"created_at":"1520421572",
	 * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸"
     * 			},
     * 			"target": {
     * 				"id": "19",
     * 				"target_no": "1804233189",
     * 				"credit_level": "4",
     * 				"delay": "0",
     * 				"delay_days": "0",
     * 				"instalment": "3期",
     * 				"repayment": "等額本息",
     * 				"status": "5"
     * 			},
  	 *       	"amortization_schedule": {
  	 *           	"amount": "12000",
  	 *           	"instalment": "3",
  	 *           	"rate": "9",
  	 *           	"date": "2018-04-17",
  	 *           	"total_payment": 2053,
  	 *           	"schedule": {
 	 *              "1": {
   	 *                  "instalment": 1,
   	 *                  "repayment_date": "2018-06-10",
   	 *                  "repayment": 0,
   	 *                  "principal": 1893,
   	 *                  "interest": 160,
   	 *                  "total_payment": 2053
   	 *              },
   	 *              "2": {
  	 *                   "instalment": 2,
   	 *                   "repayment_date": "2018-07-10",
   	 *                   "repayment": 0,
  	 *                   "principal": 1978,
  	 *                   "interest": 75,
 	 *                   "total_payment": 2053
  	 *               },
   	 *              "3": {
 	 *                    "instalment": 3,
 	 *                    "repayment_date": "2018-08-10",
 	 *                    "repayment": 0,
  	 *                    "principal": 1991,
  	 *                    "interest": 62,
 	 *                    "total_payment": 2053
 	 *               }
 	 *            }
	 *        	}
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse NotInvestor
	 *
     * @apiError 806 此申請不存在
     * @apiErrorExample {json} 806
     *     {
     *       "result": "ERROR",
     *       "error": "806"
     *     }
	 *
     * @apiError 805 對此申請無權限
     * @apiErrorExample {json} 805
     *     {
     *       "result": "ERROR",
     *       "error": "805"
     *     }
     */
	public function info_get($investment_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investment 		= $this->investment_model->get($investment_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		if(!empty($investment) && in_array($investment->status,array(3,10))){
			if($investment->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => TARGET_APPLY_NO_PERMISSION ));
			}

			$target_info = $this->target_model->get($investment->target_id);
			$target = array(
				"id"			=> $target_info->id,
				"target_no"		=> $target_info->target_no,
				"credit_level"	=> $target_info->credit_level,
				"delay"			=> $target_info->delay,
				"delay_days"	=> $target_info->delay_days,
				"status"		=> $target_info->status,
				"instalment" 	=> $instalment_list[$target_info->instalment],
				"repayment" 	=> $repayment_type[$target_info->repayment],
			);
			
			$repayment_detail = array();
			$transaction = $this->transaction_model->order_by("limit_date","asc")->get_many_by(array("target_id"=>$target_info->id,"user_to"=>$user_id,"status"=>array(1,2)));
			if($transaction){
				foreach($transaction as $k => $v){
					if(in_array($v->source,array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST))){
						$repayment_detail[$v->limit_date] = isset($repayment_detail[$v->limit_date])?$repayment_detail[$v->limit_date]+$v->amount:$v->amount;
					}
				}
			}
			
			$product_info 	= $this->product_model->get($target_info->product_id);
			$product 		= array(
				"id"	=> $product_info->id,
				"name"	=> $product_info->name,
			);

			$data = array(
				"id" 					=> $investment->id,
				"loan_amount" 			=> $investment->loan_amount?$investment->loan_amount:"",
				"contract" 				=> $investment->contract,
				"status" 				=> $investment->status,
				"created_at" 			=> $investment->created_at,
				"product" 				=> $product,
				"target" 				=> $target,
				"amortization_schedule" => $this->target_lib->get_investment_amortization_table($target_info,$investment),
			);
			
			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {post} /recoveries/withdraw 出借方 提領申請
     * @apiGroup Recoveries
     * @apiParam {number} amount (required) 提領金額
     * @apiParam {String} transaction_password (required) 交易密碼
	 * 
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} target_id Targets ID
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse NotInvestor
     *
     * @apiError 202 未通過所需的驗證(實名驗證)
     * @apiErrorExample {json} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 金融帳號驗證尚未通過
     * @apiErrorExample {json} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {json} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
     *     }
	 *
     * @apiError 210 交易密碼錯誤
     * @apiErrorExample {json} 210
     *     {
     *       "result": "ERROR",
     *       "error": "210"
     *     }
	 *
     * @apiError 211 可用餘額不足
     * @apiErrorExample {json} 211
     *     {
     *       "result": "ERROR",
     *       "error": "211"
     *     }
	 *
     */
	public function withdraw_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;

		//必填欄位
		$fields 	= ['amount','transaction_password'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}
		}
		
		//檢查認證 NOT_VERIFIED
		$certification_list	= $this->certification_lib->get_status($user_id,$investor);
		foreach($certification_list as $key => $value){
			if( $value->alias=='id_card' && $value->user_status != 1 ){
				$this->response(array('result' => 'ERROR',"error" => NOT_VERIFIED ));
			}
		}
		
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$bank_account = $this->user_bankaccount_model->get_by(array("investor"=>$investor,"status"=>1,"user_id"=>$user_id,"verify"=>1));
		if(!$bank_account){
			$this->response(array('result' => 'ERROR',"error" => NO_BANK_ACCOUNT ));
		}
		
		if($this->user_info->transaction_password==""){
			$this->response(array('result' => 'ERROR',"error" => NO_TRANSACTION_PASSWORD ));
		}
		
		if($this->user_info->transaction_password != sha1($input['transaction_password'])){
			$this->response(array('result' => 'ERROR',"error" => TRANSACTION_PASSWORD_ERROR ));
		}
		
		$withdraw = $this->transaction_lib->withdraw($user_id,intval($input['amount']));
		if($withdraw){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR',"error" => NOT_ENOUGH_FUNDS ));
		}
    }

	/**
     * @api {get} /recoveries/passbook 出借方 虛擬帳戶明細
     * @apiGroup Recoveries
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} amount 金額
     * @apiSuccess {String} bank_amount 帳戶餘額
     * @apiSuccess {String} remark 備註
     * @apiSuccess {String} tx_datetime 交易時間
     * @apiSuccess {String} created_at 入帳時間
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"amount":"50500",
     * 				"bank_amount":"50500",
     * 				"remark":"source:3",
     * 				"tx_datetime":"2018-05-08 15:38:07",
     * 				"created_at":"1520421572"
     * 			},
     * 			{
     * 				"amount":"-50000",
     * 				"bank_amount":"500",
     * 				"remark":"source:3",
     * 				"tx_datetime":"2018-04-20 17:55:51",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse NotInvestor
     *
     * @apiError 202 未通過所需的驗證(實名驗證)
     * @apiErrorExample {json} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 金融帳號驗證尚未通過
     * @apiErrorExample {json} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     */
	public function passbook_get()
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$data		= array();
		//檢查認證 NOT_VERIFIED
		$certification_list	= $this->certification_lib->get_status($user_id,$investor);
		foreach($certification_list as $key => $value){
			if( $value->alias=='id_card' && $value->user_status != 1 ){
				$this->response(array('result' => 'ERROR',"error" => NOT_VERIFIED ));
			}
		}
		
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$bank_account = $this->user_bankaccount_model->get_by(array("investor"=>$investor,"status"=>1,"user_id"=>$user_id,"verify"=>1));
		if(!$bank_account){
			$this->response(array('result' => 'ERROR',"error" => NO_BANK_ACCOUNT ));
		}
		
		$virtual_account = $this->virtual_account_model->get_by(array("investor"=>1,"user_id"=>$user_id));
		$list = $this->passbook_lib->get_passbook_list($virtual_account->virtual_account);

		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	
	/**
     * @api {post} /recoveries/transfer 出借方 轉讓申請
     * @apiGroup Recoveries
     * @apiParam {String} ids (required) Investments IDs (1,3,10,21)
	 * 
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 * 
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse NotInvestor
     *
     * @apiError 806 此申請不存在
     * @apiErrorExample {json} 806
     *     {
     *       "result": "ERROR",
     *       "error": "806"
     *     }
	 *
     * @apiError 805 對此申請無權限
     * @apiErrorExample {json} 805
     *     {
     *       "result": "ERROR",
     *       "error": "805"
     *     }
     */
	public function transfer_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$ids		= array();
		//必填欄位
		if (empty($input['ids'])) {
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}
		
		$ids = explode(",",$input['ids']);
		if(!empty($ids)){
			foreach($ids as $key => $id){
				$id = intval($id);
				if(empty($id)){
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}
			}
		}else{
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}
		
		$investments = $this->investment_model->get_many($ids);
		if(count($investments)!=count($ids)){
			foreach( $investments as $key => $value ){
				if($value->user_id != $user_id){
					$this->response(array('result' => 'ERROR',"error" => TARGET_APPLY_NO_PERMISSION ));
				}
			}
			
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
}