<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Judicialperson extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/judicial_person_model');
		
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
			
			//只限出借人
			if($tokenData->investor != 1){
				$this->response(array('result' => 'ERROR','error' => NOT_INVERTOR ));
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
     * @api {get} /v2/judicialperson/list 出借方 已申請法人列表
	 * @apiVersion 0.2.0
	 * @apiName GetJudicialpersonList
     * @apiGroup Judicialperson
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} company_type 公司類型
	 * @apiSuccess {String} company 公司名稱
	 * @apiSuccess {String} tax_id 公司統一編號
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:審核中 1:審核通過 2:審核失敗
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"company_type":"1",
     * 				"company":"50000",
     * 				"tax_id":"",
     * 				"remark":"3",
     * 				"status":"3",
     * 				"transfer_status":"0",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
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
					"credit_level"	=> $target_info->credit_level,
					"delay"			=> $target_info->delay,
					"delay_days"	=> $target_info->delay_days,
					"status"		=> $target_info->status,
					"sub_status"	=> $target_info->sub_status,
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
					"id"				=> $product_info->id,
					"name"				=> $product_info->name,
				);
				
				$list[] = array(          
					"id" 				=> $value->id,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"status" 			=> $value->status,
					"transfer_status" 	=> $value->transfer_status,
					"created_at" 		=> $value->created_at,
					"product" 			=> $product,
					"target" 			=> $target,
					"next_repayment" 	=> $next_repayment,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array("list" => $list) ));
    }
	
	/**
     * @api {get} /v2/judicialperson/info/:id 出借方 已申請法人資訊
	 * @apiVersion 0.2.0
	 * @apiName GetJudicialpersonInfo
     * @apiGroup Judicialperson
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id Judicialperson ID
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Investments ID
	 * @apiSuccess {String} loan_amount 出借金額
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {String} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
	 * @apiSuccess {String} transfer_status 債權轉讓狀態 0:無 1:已申請 2:移轉成功
	 * @apiSuccess {Object} transfer 債轉資訊
	 * @apiSuccess {String} transfer.amount 債權轉讓本金
	 * @apiSuccess {String} transfer.transfer_fee 債權轉讓手續費
	 * @apiSuccess {String} transfer.contract 債權轉讓合約
	 * @apiSuccess {String} transfer.transfer_date 債權轉讓日期
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {Object} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {Object} target 標的資訊
	 * @apiSuccess {String} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} target.credit_level 信用指數
	 * @apiSuccess {String} target.delay_days 逾期天數
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {String} target.instalment 期數
	 * @apiSuccess {String} target.repayment 還款方式
	 * @apiSuccess {String} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Object} amortization_schedule 回款計畫
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
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"amount":"50000",
     * 			"loan_amount":"",
     * 			"status":"3",
	 * 			"transfer_status":"1",
     * 			"created_at":"1520421572",
	 * 			"transfer":{
     * 				"amount":"5000",
     * 				"transfer_fee":"25",
     * 				"contract":"我是合約，我是合約",
     * 				"transfer_date": null
     * 			},
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
  	 *           	"list": {
 	 *              	"1": {
   	 *                  	"instalment": 1,
   	 *                  	"repayment_date": "2018-06-10",
   	 *                  	"repayment": 0,
   	 *                  	"principal": 1893,
   	 *                  	"interest": 160,
   	 *                  	"total_payment": 2053
   	 *              	},
   	 *              	"2": {
  	 *                  	"instalment": 2,
   	 *                   	"repayment_date": "2018-07-10",
   	 *                   	"repayment": 0,
  	 *                   	"principal": 1978,
  	 *                   	"interest": 75,
 	 *                   	"total_payment": 2053
  	 *               	},
   	 *              	"3": {
 	 *                    	"instalment": 3,
 	 *                    	"repayment_date": "2018-08-10",
 	 *                    	"repayment": 0,
  	 *                    	"principal": 1991,
  	 *                    	"interest": 62,
 	 *                    	"total_payment": 2053
 	 *               	}
 	 *            	}
	 *        	}
     *		 }
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
	 *
     * @apiError 806 此申請不存在
     * @apiErrorExample {Object} 806
     *     {
     *       "result": "ERROR",
     *       "error": "806"
     *     }
	 *
     * @apiError 805 對此申請無權限
     * @apiErrorExample {Object} 805
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
				$this->response(array('result' => 'ERROR','error' => TARGET_APPLY_NO_PERMISSION ));
			}

			$target_info = $this->target_model->get($investment->target_id);
			$target = array(
				"id"			=> $target_info->id,
				"target_no"		=> $target_info->target_no,
				"credit_level"	=> $target_info->credit_level,
				"delay"			=> $target_info->delay,
				"delay_days"	=> $target_info->delay_days,
				"status"		=> $target_info->status,
				"sub_status"	=> $target_info->sub_status,
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
			
			$transfer 		= array();
			if($investment->transfer_status!=0){
				$transfer_info = $this->transfer_lib->get_transfer_investments($investment->id);
				if($transfer_info){
					$contract_data 	= $this->contract_lib->get_contract($transfer_info->contract_id);
					$contract 		= isset($contract_data["content"])?$contract_data["content"]:"";
					$transfer = array(
						"transfer_fee"	=> $transfer_info->transfer_fee,
						"amount"		=> $transfer_info->amount,
						"contract"		=> $contract,
						"transfer_at"	=> $transfer_info->transfer_date,
					);
				}
			}
			
			$investment_contract = $this->contract_lib->get_contract($investment->contract_id);
			$data = array(
				"id" 					=> $investment->id,
				"loan_amount" 			=> $investment->loan_amount?$investment->loan_amount:"",
				"contract" 				=> $investment_contract["content"],
				"status" 				=> $investment->status,
				"transfer_status" 		=> $investment->transfer_status,
				"created_at" 			=> $investment->created_at,
				"transfer" 				=> $transfer,
				"product" 				=> $product,
				"target" 				=> $target,
				"amortization_schedule" => $this->target_lib->get_investment_amortization_table($target_info,$investment),
			);
			
			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

}