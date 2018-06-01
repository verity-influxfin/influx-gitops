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
		$this->load->library('Target_lib');
		$this->load->library('Prepayment_lib');
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
	 * @apiSuccess {String} remaining_principal 現欠本金餘額
	 * @apiSuccess {String} next_repayment 當期待還本息
	 * @apiSuccess {json} user 用戶資訊
	 * @apiSuccess {String} user.id User ID
	 * @apiSuccess {String} user.name 姓名
	 * @apiSuccess {String} user.picture 照片
	 * @apiSuccess {String} user.nickname 暱稱
	 * @apiSuccess {String} user.sex 性別
	 * @apiSuccess {String} user.phone 手機號碼
	 * @apiSuccess {String} user.id_number 身分證字號
	 * @apiSuccess {String} user.investor 1:投資端 0:借款端
	 * @apiSuccess {String} user.my_promote_code 推廣碼
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 				"remaining_principal": "12345",
     * 				"next_repayment_amount": "1588",
	 * 				"user": {
	 *      			"id": "1",
     *      			"name": "",
     *      			"picture": "https://graph.facebook.com/2495004840516393/picture?type=large",
     *      			"nickname": "陳霈",
     *      			"phone": "0912345678",
     *      			"investor_status": "1",
     *      			"my_promote_code": "9JJ12CQ5",
     *      			"id_number": null,
     *      			"investor": 1,  
     *      			"created_at": "1522651818",     
     *      			"updated_at": "1522653939"     
	 * 				}
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 *
     */
	 	
	public function dashboard_get()
    {
		$input 		 			= $this->input->get();
		$user_id 	 			= $this->user_info->id;
		$remaining_principal 	= 0;
		$next_repayment			= 0;
		$user					= array();
		$transaction = $this->transaction_model->order_by("limit_date","asc")->get_many_by(array("user_from"=>$user_id,"status"=>"1"));
		if($transaction){
			$first = true;
			foreach($transaction as $key => $value){
				if($first){
					$next_repayment_date = $value->limit_date;
					$first = false;
				}
				
				if($value->source == SOURCE_AR_PRINCIPAL){
					$remaining_principal += $value->amount;
				}
				
				if($value->limit_date && $value->limit_date == $next_repayment_date){
					$next_repayment += $value->amount;
				}
			}
		}
		
		$fields = $this->user_model->token_fields;
		foreach($fields as $key => $field){
			$user[$field] = $this->user_info->$field?$this->user_info->$field:"";
		}
		
		$data	= array("remaining_principal"=>$remaining_principal,"next_repayment"=>$next_repayment,"user"=>$user);
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }
	
	/**
     * @api {get} /repayment/list 借款方 我的還款列表
     * @apiGroup Repayment
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} next_repayment 最近一期應還款
	 * @apiSuccess {String} next_repayment.date 還款日
	 * @apiSuccess {String} next_repayment.instalment 期數
	 * @apiSuccess {String} next_repayment.amount 金額
	 * @apiSuccess {json} virtual_account 還款專屬虛擬帳號
	 * @apiSuccess {String} virtual_account.bank_code 銀行代碼
	 * @apiSuccess {String} virtual_account.branch_code 分行代碼
	 * @apiSuccess {String} virtual_account.bank_name 銀行名稱
	 * @apiSuccess {String} virtual_account.branch_name 分行名稱
	 * @apiSuccess {String} virtual_account.virtual_account 虛擬帳號
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"target_no": "1803269743",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸"
     * 				},
     * 				"user_id":"1",
     * 				"loan_amount":"50000",
     * 				"interest_rate":"8",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"delay_days":"0",
     * 				"status":"5",
     * 				"sub_status":"0",
     * 				"created_at":"1520421572",
	 * 	        	"next_repayment": {
     * 	            	"date": "2018-06-10",
     * 	            	"instalment": "1",
     * 	            	"amount": "16890"
     * 	        	},
	 * 	        	"virtual_account": {
     * 	            	"bank_code": "013",
     * 	            	"branch_code": "0154",
     * 	            	"bank_name": "國泰世華商業銀行",
     * 	            	"branch_name": "信義分行",
     * 	            	"virtual_account": "56631803269743"
     * 	        	}
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
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
				$next_repayment = array(
					"date" 			=> "",
					"instalment"	=> "",
					"amount"		=> 0,
				);

				$transaction = $this->transaction_model->order_by("limit_date","asc")->get_many_by(array("target_id"=>$value->id,"user_from"=>$user_id,"status"=>"1"));
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
				
				$product_info 	= $this->product_model->get($value->product_id);
				$product = array(
					"id"				=> $product_info->id,
					"name"				=> $product_info->name,
				);
				
				$virtual_account	= array(
					"bank_code"			=> CATHAY_BANK_CODE,
					"branch_code"		=> CATHAY_BRANCH_CODE,
					"bank_name"			=> CATHAY_BANK_NAME,
					"branch_name"		=> CATHAY_BRANCH_NAME,
					"virtual_account"	=> $value->virtual_account,
				);
		
				$list[] = array(
					"id" 				=> $value->id,
					"target_no" 		=> $value->target_no,
					"product" 			=> $product,
					"user_id" 			=> $value->user_id,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"instalment" 		=> $instalment_list[$value->instalment],
					"repayment" 		=> $repayment_type[$value->repayment],
					"contract" 			=> $value->contract,
					"remark" 			=> $value->remark, 
					"delay" 			=> $value->delay,
					"delay_days" 		=> $value->delay_days,
					"status" 			=> $value->status,
					"sub_status" 		=> $value->sub_status,
					"created_at" 		=> $value->created_at,
					"next_repayment" 	=> $next_repayment,
					"virtual_account"	=> $virtual_account,
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
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {String} remaining_principal 剩餘本金
	 * @apiSuccess {String} remaining_instalment 剩餘期數
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {json} fees 費用資料
	 * @apiSuccess {String} fees.sub_loan_fees 轉換產品手續費%
	 * @apiSuccess {String} fees.liquidated_damages 違約金(提前還款)%
	 * @apiSuccess {json} next_repayment 最近一期應還款
	 * @apiSuccess {String} next_repayment.date 還款日
	 * @apiSuccess {String} next_repayment.instalment 期數
	 * @apiSuccess {String} next_repayment.amount 金額
	 * @apiSuccess {String} next_repayment.list 明細
	 * @apiSuccess {json} virtual_account 還款專屬虛擬帳號
	 * @apiSuccess {String} virtual_account.bank_code 銀行代碼
	 * @apiSuccess {String} virtual_account.branch_code 分行代碼
	 * @apiSuccess {String} virtual_account.bank_name 銀行名稱
	 * @apiSuccess {String} virtual_account.branch_name 分行名稱
	 * @apiSuccess {String} virtual_account.virtual_account 虛擬帳號
	 * @apiSuccess {json} amortization_schedule 還款計畫
	 * @apiSuccess {String} amortization_schedule.amount 借款金額
	 * @apiSuccess {String} amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} amortization_schedule.rate 年利率
	 * @apiSuccess {String} amortization_schedule.date 起始時間
	 * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} amortization_schedule.sub_loan_fees 轉貸手續費用
	 * @apiSuccess {String} amortization_schedule.platform_fees 平台服務費用
	 * @apiSuccess {String} amortization_schedule.list 還款計畫
	 * @apiSuccess {String} amortization_schedule.list.instalment 第幾期
	 * @apiSuccess {String} amortization_schedule.list.repayment_date 還款日
	 * @apiSuccess {String} amortization_schedule.list.principal 還款本金
	 * @apiSuccess {String} amortization_schedule.list.interest 還款利息
	 * @apiSuccess {String} amortization_schedule.list.total_payment 本期還款金額
	 * @apiSuccess {String} amortization_schedule.list.repayment 已還款金額
	 * @apiSuccess {String} amortization_schedule.list.delay_interest 延滯息
	 * @apiSuccess {String} amortization_schedule.list.liquidated_damages 違約金（提還費）
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
     * 			"remark":"",
     * 			"delay":"0",
     * 			"status":"0",
     * 			"sub_status":"0",
     * 			"created_at":"1520421572",
     * 			"remaining_principal":"50000",
     * 			"remaining_instalment":"3",
     * 		"product":{
     * 			"id":"2",
     * 			"name":"輕鬆學貸",
     * 			"description":"輕鬆學貸",
     * 			"alias":"FA"
     * 		},
	 * 	     "fees": {
     * 	         "sub_loan_fees": "1",
     * 	         "liquidated_damages": "2"
     * 	     },
	 * 	     "next_repayment": {
     * 	         "date": "2018-06-10",
     * 	         "instalment": "1",
     * 	         "amount": "16890",
     * 	         "list": {
     * 	             "11": {
     * 	                 "amount": 16539,
     * 	                 "source_name": "應付借款本金"
     * 	             },
     * 	             "13": {
     * 	                 "amount": 351,
     * 	                 "source_name": "應付借款利息"
     * 	             }
     * 	         }
     * 	     },
	 * 	     "virtual_account": {
     * 	         "bank_code": "013",
     * 	         "branch_code": "0154",
     * 	         "bank_name": "國泰世華商業銀行",
     * 	         "branch_name": "信義分行",
     * 	         "virtual_account": "56631803269743"
     * 	     },
  	 *       "amortization_schedule": {
  	 *           "amount": "12000",
  	 *           "instalment": "3",
  	 *           "rate": "9",
  	 *           "date": "2018-04-17",
  	 *           "total_payment": 2053,
  	 *           "sub_loan_fees": 0,
  	 *           "platform_fees": 1500,
  	 *           "schedule": {
 	 *              "1": {
   	 *                 	"instalment": 1,
   	 *                  "repayment_date": "2018-06-10",
   	 *                  "repayment": 0,
   	 *                  "principal": 1893,
   	 *                  "interest": 160,
   	 *                  "total_payment": 2053,
   	 *                  "delay_interest": 0,
   	 *                  "liquidated_damages": 0
   	 *              },
   	 *              "2": {
  	 *                   "instalment": 2,
   	 *                   "repayment_date": "2018-07-10",
   	 *                   "repayment": 0,
  	 *                   "principal": 1978,
  	 *                   "interest": 75,
 	 *                   "total_payment": 2053,
   	 *                   "delay_interest": 0,
   	 *                   "liquidated_damages": 0
  	 *               },
   	 *              "3": {
 	 *                   "instalment": 3,
 	 *                   "repayment_date": "2018-08-10",
 	 *                   "repayment": 0,
  	 *                   "principal": 1991,
  	 *                   "interest": 62,
 	 *                   "total_payment": 2053,
   	 *                   "delay_interest": 0,
   	 *                   "liquidated_damages": 0
 	 *               }
 	 *           }
	 *        }
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
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
		$transaction_source = $this->config->item('transaction_source');
		$data				= array();
		if(!empty($target) && in_array($target->status,array(5,10))){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}

			$product_info 	= $this->product_model->get($target->product_id);
			$product 		= array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
			);
			
			$virtual_account	= array(
				"bank_code"			=> CATHAY_BANK_CODE,
				"branch_code"		=> CATHAY_BRANCH_CODE,
				"bank_name"			=> CATHAY_BANK_NAME,
				"branch_name"		=> CATHAY_BRANCH_NAME,
				"virtual_account"	=> $target->virtual_account,
			);
			
			$next_repayment = array(
				"date" 			=> "",
				"instalment"	=> "",
				"amount"		=> 0,
				"list"			=> array(),
			);
			
			$fees = array(
				//"debt_transfer_fees" => DEBT_TRANSFER_FEES,
				"sub_loan_fees"		 => SUB_LOAN_FEES,
				"liquidated_damages" => LIQUIDATED_DAMAGES
			);
			
			$transaction = $this->transaction_model->order_by("limit_date","asc")->get_many_by(array("target_id"=>$target->id,"user_from"=>$user_id,"status"=>array(1,2)));
			$remaining_principal = 0;
			
			if($transaction){
				$first = true;
				foreach($transaction as $k => $v){
					if($first && $v->status==1){
						$next_repayment["date"] 		= $v->limit_date;
						$next_repayment["instalment"] 	= $v->instalment_no;
						$first = false;
					}

					if($v->limit_date && $v->limit_date == $next_repayment["date"]){
						$next_repayment["amount"] += $v->amount;
					}
					
					if($v->instalment_no == $next_repayment["instalment"]){
						if(!isset($next_repayment["list"][$v->source]["amount"])){
							$next_repayment["list"][$v->source]["amount"] = 0;
						}
						$next_repayment["list"][$v->source]["amount"] += $v->amount;
						$next_repayment["list"][$v->source]["source_name"] = $transaction_source[$v->source];
					}
					
					if($v->source == SOURCE_AR_PRINCIPAL){
						$remaining_principal += $v->amount;
					}
					if($v->source == SOURCE_PRINCIPAL){
						$remaining_principal -= $v->amount;
					}
				}
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

			$data["remaining_principal"] 	= $remaining_principal;
			$data["remaining_instalment"] 	= $next_repayment["instalment"]?intval($target->instalment - $next_repayment["instalment"])+1:0;
			$data["product"] 		 		= $product;
			$data["virtual_account"] 		= $virtual_account;
			$data["next_repayment"] 		= $next_repayment;
			$data["fees"] 					= $fees;
			$data["amortization_schedule"] 	= $this->target_lib->get_amortization_table($target);

			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /repayment/prepayment/{ID} 借款方 提前還款資訊
      * @apiGroup Repayment
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請金額
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} prepayment 提前還款資訊
	 * @apiSuccess {String} prepayment.remaining_principal 剩餘本金
	 * @apiSuccess {String} prepayment.remaining_instalment 剩餘期數
	 * @apiSuccess {String} prepayment.settlement_date 結息日
	 * @apiSuccess {String} prepayment.liquidated_damages 違約金（提還費）
	 * @apiSuccess {String} prepayment.delay_interest_payable 應付延滯息
	 * @apiSuccess {String} prepayment.interest_payable 應付利息
	 * @apiSuccess {String} prepayment.total 合計
	 * @apiSuccess {json} virtual_account 還款專屬虛擬帳號
	 * @apiSuccess {String} virtual_account.bank_code 銀行代碼
	 * @apiSuccess {String} virtual_account.branch_code 分行代碼
	 * @apiSuccess {String} virtual_account.bank_name 銀行名稱
	 * @apiSuccess {String} virtual_account.branch_name 分行名稱
	 * @apiSuccess {String} virtual_account.virtual_account 虛擬帳號
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
     * 			"instalment":"3",
     * 			"repayment":"等額本息",
     * 			"delay":"0",
     * 			"delay_days":"0",
     * 			"status":"0",
     * 			"created_at":"1520421572",
	 * 	     "prepayment": {
     * 	         "remaining_principal": "50000",
     * 	         "remaining_instalment": "3",
     * 	         "settlement_date": "2018-05-19",
     * 	         "liquidated_damages": "1000",
     * 	         "delay_interest_payable": "0",
     * 	         "interest_payable": "450",
     * 	         "total": "51450"
     * 	     },
	 * 	     "virtual_account": {
     * 	         "bank_code": "013",
     * 	         "branch_code": "0154",
     * 	         "bank_name": "國泰世華商業銀行",
     * 	         "branch_name": "信義分行",
     * 	         "virtual_account": "56631803269743"
     * 	     }
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
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
	public function prepayment_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target) && $target->status == 5 ){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}
			
			$virtual_account	= array(
				"bank_code"			=> CATHAY_BANK_CODE,
				"branch_code"		=> CATHAY_BRANCH_CODE,
				"bank_name"			=> CATHAY_BANK_NAME,
				"branch_name"		=> CATHAY_BRANCH_NAME,
				"virtual_account"	=> $target->virtual_account,
			);
		
			$fields = $this->target_model->simple_fields;
			foreach($fields as $field){
				$data[$field] = isset($target->$field)?$target->$field:"";

				if($field=="repayment"){
					$data[$field] = $repayment_type[$target->$field];
				}
			}

			$data["prepayment"] 		= $this->prepayment_lib->get_prepayment_info($target);
			$data["virtual_account"] 	= $virtual_account;


			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {post} /repayment/prepayment/{ID} 借款方 申請提前還款
     * @apiGroup Repayment
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS"
     *    }
	 *
	 * @apiUse IsInvestor
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
	 *
     * @apiError 903 已申請提前還款或轉換產品
     * @apiErrorExample {json} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
	public function prepayment_post($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		if(!empty($target) && $target->status == 5 ){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}
			
			if($target->sub_status != 0){
				$this->response(array('result' => 'ERROR',"error" => TARGET_HAD_SUBSTATUS ));
			}
			
			$prepayment 	= $this->prepayment_lib->get_prepayment_info($target);
			if($prepayment){
				$rs 		= $this->prepayment_lib->apply_prepayment($target);
				$this->response(array('result' => 'SUCCESS'));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }

}
