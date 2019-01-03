<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Target extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/product_model');
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->library('Contract_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list' ,'info'];
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
			
			if(isset($tokenData->company) && $tokenData->company != 0 ){
				$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }

	/**
     * @api {get} /target/list 出借方 取得標的列表
	 * @apiVersion 0.1.0
	 * @apiName GetTargetList
     * @apiGroup Target
	 * @apiParam {String=credit_level,instalment,interest_rate} [orderby="credit_level"] 排序值
	 * @apiParam {String=asc,desc} [sort=asc] 降序/升序
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {Object} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {String} credit_level 信用評等
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} expire_time 流標時間
	 * @apiSuccess {String} invested 目前投標量
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
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
	 * 				"credit_level":"4",
     * 				"user_id":"1",
     * 				"loan_amount":"5000",
     * 				"interest_rate":"12",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"delay": "0",
     * 				"delay_days": "0",
     * 				"expire_time": "1525449600",
     * 				"invested": "50000",
     * 				"status":"3",
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
		$where	= array( "status" => 3 );
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$orderby 			= isset($input['orderby'])&&in_array($input['orderby'],array("credit_level","instalment","interest_rate"))?$input['orderby']:"credit_level";
		$sort				= isset($input['sort'])&&in_array($input['sort'],array("desc","asc"))?$input['sort']:"asc";
		$target_list 		= $this->target_model->order_by($orderby,$sort)->get_many_by($where);

		if(!empty($target_list)){
			$product_list = array();
			$products = $this->product_model->get_many_by(array("status"=>1));
			if($products){
				foreach($products as $key => $value){
					$product_list[$value->id] = array(
						"id"			=> $value->id,
						"name"			=> $value->name,
					);
				}
			}

			foreach($target_list as $key => $value){
				$list[] = array(
					"id" 				=> $value->id,
					"target_no" 		=> $value->target_no,
					"product" 			=> $product_list[$value->product_id],
					"credit_level" 		=> $value->credit_level,
					"user_id" 			=> $value->user_id,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"instalment" 		=> $instalment_list[$value->instalment],
					"repayment" 		=> $repayment_type[$value->repayment],
					"delay" 			=> $value->delay,
					"delay_days" 		=> $value->delay_days,
					"expire_time" 		=> $value->expire_time,
					"invested" 			=> $value->invested,
					"status" 			=> $value->status,
					"sub_status" 		=> $value->sub_status,
					"created_at" 		=> $value->created_at,
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }

	/**
     * @api {get} /target/info/:id 出借方 取得標的資訊
	 * @apiVersion 0.1.0
	 * @apiName GetTargetInfo
     * @apiGroup Target
	 * @apiParam {Number} id 標的ID
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Target ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} loan_amount 借款金額
	 * @apiSuccess {String} credit_level 信用評等
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} expire_time 流標時間
	 * @apiSuccess {String} invested 目前投標量
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {Object} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {Object} certification 借款人認證完成資訊
	 * @apiSuccess {Object} user 借款人基本資訊
	 * @apiSuccess {String} user.name 姓名
	 * @apiSuccess {String} user.age 年齡
	 * @apiSuccess {String} user.school_name 學校名稱
	 * @apiSuccess {String} user.id_number 身分證字號
	 * @apiSuccess {Object} amortization_schedule 預計還款計畫
	 * @apiSuccess {String} amortization_schedule.amount 借款金額
	 * @apiSuccess {String} amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} amortization_schedule.rate 年化利率
	 * @apiSuccess {String} amortization_schedule.date 起始時間
	 * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} amortization_schedule.leap_year 是否為閏年
	 * @apiSuccess {String} amortization_schedule.year_days 本年日數
	 * @apiSuccess {String} amortization_schedule.XIRR 內部報酬率(%)
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

     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"target_no": "1803269743",
     * 			"user_id":"1",
     * 			"loan_amount":"12000",
	 * 			"credit_level":"4",
     * 			"interest_rate":"9",
     * 			"instalment":"3期",
     * 			"repayment":"等額本息",
     * 			"contract":"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！",
     * 			"remark":"",
     * 			"delay": "0",
     * 			"delay_days": "0",
     * 			"expire_time": "1525449600",
     * 			"invested": "50000",
     * 			"status":"4",
     * 			"sub_status":"0",
     * 			"created_at":"1520421572",
     * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸"
     * 			},
     *	        "certification": [
     *           	{
     *           	     "id": "1",
     *           	     "name": "身分證認證",
     *           	     "description": "身分證認證",
     *           	     "alias": "id_card",
     *            	    "user_status": "1"
     *           	},
     *           	{
     *           	    "id": "2",
     *            	    "name": "學生證認證",
     *           	    "description": "學生證認證",
     *            	   "alias": "student",
     *            	   "user_status": "1"
     *           	}
     *           ],
     *       "user": {
      *          "name": "陳XX",
     *           "age": 28,
     *           "school_name": "國立宜蘭大學",
     *           "id_number": "G1231XXXXX"
     *       },
  	 *       "amortization_schedule": {
  	 *           "amount": "12000",
  	 *           "instalment": "6",
  	 *           "rate": "9",
  	 *           "date": "2018-04-17",
  	 *           "total_payment": 2053,
  	 *           "leap_year": false,
  	 *           "year_days": 365,
  	 *           "XIRR": 9.39,
  	 *           "schedule": {
 	 *                "1": {
   	 *                  "instalment": 1,
   	 *                  "repayment_date": "2018-06-10",
   	 *                  "days": 54,
   	 *                  "remaining_principal": "12000",
   	 *                  "principal": 1893,
   	 *                  "interest": 160,
   	 *                  "total_payment": 2053
   	 *              },
   	 *              "2": {
  	 *                   "instalment": 2,
   	 *                  "repayment_date": "2018-07-10",
   	 *                  "days": 30,
  	 *                   "remaining_principal": 10107,
  	 *                   "principal": 1978,
  	 *                   "interest": 75,
 	 *                    "total_payment": 2053
  	 *               },
   	 *              "3": {
 	 *                    "instalment": 3,
 	 *                    "repayment_date": "2018-08-10",
 	 *                    "days": 31,
 	 *                    "remaining_principal": 8129,
  	 *                   "principal": 1991,
  	 *                   "interest": 62,
 	 *                    "total_payment": 2053
 	 *                }
 	 *            },
  	 *           "total": {
 	 *                "principal": 12000,
 	 *                "interest": 391,
 	 *                "total_payment": 12391
	 *            }
	 *        }
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
	 * @apiError 801 標的不存在
     * @apiErrorExample {Object} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
     */
	 
	public function info_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		//$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target) && in_array($target->status,array(3,4,5,10))){
			$product_info = $this->product_model->get($target->product_id);
			$product = array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
			);
			$product_info->certifications 	= json_decode($product_info->certifications,TRUE);
			$certification					= array();
			$this->load->library('Certification_lib');
			$certification_list				= $this->certification_lib->get_status($target->user_id);
			if(!empty($certification_list)){
				foreach($certification_list as $key => $value){
					if(in_array($key,$product_info->certifications)){
						$certification[] = $value;
					}
				}
			}

			$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$date="",$target->repayment);
		
			$user_info 	= $this->user_model->get($target->user_id); 
			$user		= array();
			if($user_info){
				$name 		= mb_substr($user_info->name,0,1,"UTF-8")."**";
				$age  		= get_age($user_info->birthday);
				$user_meta 	= $this->user_meta_model->get_by(array("user_id"=>$target->user_id,"meta_key"=>"school_name"));
				$school_name= $user_meta?$user_meta->meta_value:"";
				$id_number 	= strlen($user_info->id_number)==10?substr($user_info->id_number,0,5)."*****":"";
				$user = array(
					"name" 			=> $name,
					"age"			=> $age,
					"school_name"	=> $school_name,
					"id_number"		=> $id_number,
				);
			}
			
			
			$contract_data 	= $this->contract_lib->get_contract($target->contract_id);
			$contract 		= $contract_data?$contract_data["content"]:"";
			$data = array(
				"id" 				=> $target->id,
				"target_no" 		=> $target->target_no,
				"user_id" 			=> $target->user_id,
				"loan_amount" 		=> $target->loan_amount,
				"credit_level" 		=> $target->credit_level,
				"interest_rate" 	=> $target->interest_rate,
				"remark" 			=> $target->remark,
				"instalment" 		=> $instalment_list[$target->instalment],
				"repayment" 		=> $repayment_type[$target->repayment],
				"contract" 			=> $contract,
				"delay" 			=> $target->delay,
				"delay_days" 		=> $target->delay_days,
				"expire_time" 		=> $target->expire_time,
				"invested" 			=> $target->invested,
				"status" 			=> $target->status,
				"sub_status" 		=> $target->sub_status,
				"created_at" 		=> $target->created_at,
			);

			$data["user"] 					= $user;
			$data["product"] 				= $product;
			$data["certification"] 			= $certification;
			$data["amortization_schedule"] 	= $amortization_schedule;

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST ));
    }
	
	/**
     * @api {post} /target/apply 出借方 申請出借
	 * @apiVersion 0.1.0
	 * @apiName PostTargetApply
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} target_id 產品ID
     * @apiParam {Number} amount 出借金額
	 * 
	 * 
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
     *
	 * @apiError 801 標的不存在
     * @apiErrorExample {Object} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
	 *
     * @apiError 802 金額過高或過低
     * @apiErrorExample {Object} 802
     *     {
     *       "result": "ERROR",
     *       "error": "802"
     *     }
	 *
     * @apiError 803 已申請出借
     * @apiErrorExample {Object} 803
     *     {
     *       "result": "ERROR",
     *       "error": "803"
     *     }
	 *
     * @apiError 804 雙方不可同使用者
     * @apiErrorExample {Object} 804
     *     {
     *       "result": "ERROR",
     *       "error": "804"
     *     }
	 *
     * @apiError 202 未通過所需的驗證(實名驗證)
     * @apiErrorExample {Object} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 金融帳號驗證尚未通過
     * @apiErrorExample {Object} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 208 未滿20歲
     * @apiErrorExample {Object} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {Object} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
     *     }
	 *
     * @apiError 212 未通過所需的驗證(Email)
     * @apiErrorExample {Object} 212
     *     {
     *       "result": "ERROR",
     *       "error": "212"
     *     }
	 *
     */
	public function apply_post()
    {

		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= array("user_id"=> $user_id);
		
		//必填欄位
		$fields 	= ['target_id','amount'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$input[$field] = intval($input[$field]);
				$param[$field] = $input[$field];
			}
		}

		$target = $this->target_model->get($input['target_id']);
		if($target && $target->status == 3 ){
			
			if( $input['amount'] < TARGET_AMOUNT_MIN || $input['amount'] > $target->loan_amount ){
				$this->response(array('result' => 'ERROR','error' => TARGET_AMOUNT_RANGE ));
			}

			if( $user_id == $target->user_id ){
				$this->response(array('result' => 'ERROR','error' => TARGET_SAME_USER ));
			}

			//檢查認證 NOT_VERIFIED
			if(empty($this->user_info->id_number) || $this->user_info->id_number==""){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
			}
			
			//檢查認證 NOT_VERIFIED_EMAIL
			if(empty($this->user_info->email) || $this->user_info->email==""){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
			}
		
			if(get_age($this->user_info->birthday) < 20){
				$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
			}

			//檢查金融卡綁定 NO_BANK_ACCOUNT
			$this->load->model('user/user_bankaccount_model');
			$bank_account = $this->user_bankaccount_model->get_by(array("investor"=>$investor,"status"=>1,"user_id"=>$user_id,"verify"=>1));
			if(!$bank_account){
				$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
			}
			
			if($this->user_info->transaction_password==""){
				$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
			}

			$investments = $this->investment_model->get_by(array("target_id"=>$target->id,"user_id"=>$user_id,"status"=>array(0,1,2,3,10)));
			if($investments){
				$this->response(array('result' => 'ERROR','error' => TARGET_APPLY_EXIST ));
			}
			
			$insert = $this->investment_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		
		$this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST ));
    }
	
	/**
     * @api {get} /target/applylist 出借方 申請紀錄列表
	 * @apiVersion 0.1.0
	 * @apiName GetTargetApplylist
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Investments ID
	 * @apiSuccess {String} amount 投標金額
	 * @apiSuccess {String} loan_amount 得標金額
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {String} status 投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
	 * @apiSuccess {String} transfer_status 債權轉讓狀態 0:無 1:已申請 2:移轉成功
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {Object} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {Object} target 標的資訊
	 * @apiSuccess {String} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} target.loan_amount 標的金額
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {String} target.expire_time 流標時間
	 * @apiSuccess {String} target.invested 目前投標量
	 * @apiSuccess {String} target.status 標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Object} bank_account 綁定金融帳號
	 * @apiSuccess {String} bank_account.bank_code 銀行代碼
	 * @apiSuccess {String} bank_account.branch_code 分行代碼
	 * @apiSuccess {String} bank_account.bank_account 銀行帳號
	 * @apiSuccess {Object} virtual_account 專屬虛擬帳號
	 * @apiSuccess {String} virtual_account.bank_code 銀行代碼
	 * @apiSuccess {String} virtual_account.branch_code 分行代碼
	 * @apiSuccess {String} virtual_account.bank_name 銀行名稱
	 * @apiSuccess {String} virtual_account.branch_name 分行名稱
	 * @apiSuccess {String} virtual_account.virtual_account 虛擬帳號
	 * @apiSuccess {Object} funds 資金資訊
	 * @apiSuccess {String} funds.total 資金總額
	 * @apiSuccess {String} funds.last_recharge_date 最後一次匯入日
	 * @apiSuccess {String} funds.frozen 待交易餘額
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
	 * 				"contract":"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊",
     * 				"status":"3",
     * 				"transfer_status":"0",
     * 				"created_at":"1520421572",
	 * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"target": {
     * 					"id": "19",
     * 					"target_no": "1804233189",
     * 					"invested": "5000",
     * 					"expire_time": "123456789",
     * 					"delay": "0",
     * 					"status": "5"
     * 				}
     * 			}
     * 			],
     * 	        "bank_account": {
     * 	            "bank_code": "013",
     * 	            "branch_code": "1234",
     * 	            "bank_account": "12345678910"
     * 	        },
     * 	        "virtual_account": {
     * 	            "bank_code": "013",
     * 	            "branch_code": "0154",
     * 	            "bank_name": "國泰世華商業銀行",
     * 	            "branch_name": "信義分行",
     * 	            "virtual_account": "56639100000001"
     * 	        },
     * 	        "funds": {
     * 	            "total": 500,
     * 	            "last_recharge_date": "2018-05-03 19:15:42",
     * 	            "frozen": 0
     * 	        }
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
	 *
     * @apiError 202 未通過所需的驗證(實名驗證)
     * @apiErrorExample {Object} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 金融帳號驗證尚未通過
     * @apiErrorExample {Object} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 208 未滿20歲
     * @apiErrorExample {Object} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {Object} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
     *     }
     */
	public function applylist_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;

		//檢查認證 NOT_VERIFIED
		if(empty($this->user_info->id_number) || $this->user_info->id_number==""){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
		}

		if(get_age($this->user_info->birthday) < 20){
			$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
		}

		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$this->load->model('user/user_bankaccount_model');
		$user_bankaccount = $this->user_bankaccount_model->get_by(array("investor"=>$investor,"status"=>1,"user_id"=>$user_id,"verify"=>1));
		if(!$user_bankaccount){
			$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
		}
		
		if($this->user_info->transaction_password==""){
			$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
		}
		
		$this->load->model('user/virtual_account_model');
		$virtual		 	= $this->virtual_account_model->get_by(array("user_id"=>$user_id,"investor"=>$investor));
		$virtual_account	= array(
			"bank_code"			=> CATHAY_BANK_CODE,
			"branch_code"		=> CATHAY_BRANCH_CODE,
			"bank_name"			=> CATHAY_BANK_NAME,
			"branch_name"		=> CATHAY_BRANCH_NAME,
			"virtual_account"	=> $virtual->virtual_account,
		);
		$bank_account 		= array(
			"bank_code"		=> $user_bankaccount->bank_code,
			"branch_code"	=> $user_bankaccount->branch_code,
			"bank_account"	=> $user_bankaccount->bank_account,
		);
		
		$this->load->library('Transaction_lib');
		$funds 				= $this->transaction_lib->get_virtual_funds($virtual->virtual_account);
		$param				= array( "user_id"=> $user_id);
		$investments		= $this->investment_model->get_many_by($param);
		$list				= array();
		if(!empty($investments)){
			foreach($investments as $key => $value){
				
				$target_info = $this->target_model->get($value->target_id);
				$target = array(
					"id"			=> $target_info->id,
					"loan_amount"	=> $target_info->loan_amount,
					"target_no"		=> $target_info->target_no,
					"invested"		=> $target_info->invested,
					"expire_time"	=> $target_info->expire_time,
					"delay"			=> $target_info->delay,
					"status"		=> $target_info->status,
					"sub_status"	=> $target_info->sub_status,
				);
				
				$product_info = $this->product_model->get($target_info->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
				);
				
				$contract = "";
				if($value->contract_id){
					$contract_data = $this->contract_lib->get_contract($value->contract_id);
					$contract = $contract_data["content"];
				}
			
				$list[] = array(
					"id" 				=> $value->id,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"contract" 			=> $contract,
					"status" 			=> $value->status,
					"transfer_status" 	=> $value->transfer_status,
					"created_at" 		=> $value->created_at,
					"product" 			=> $product,
					"target" 			=> $target,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array(
			"list" 				=> $list,
			"bank_account"		=> $bank_account,
			"virtual_account"	=> $virtual_account,
			"funds"				=> $funds
		)));
    }
 
 	/**
     * @api {get} /target/batch 出借方 智能出借
	 * @apiVersion 0.1.0
	 * @apiName GetTargetBatch
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} budget 預算金額
     * @apiParam {Number} [interest_rate_s] 利率區間下限(%)
     * @apiParam {Number} [interest_rate_e] 利率區間上限(%)
     * @apiParam {Number} [instalment_s] 期數區間下限(%)
     * @apiParam {Number} [instalment_e] 期數區間上限(%)
     * @apiParam {String} [credit_level=all] 信用評等 全部：all 複選使用逗號隔開1,2,3,4,5,6,7,8
     * @apiParam {String=all,0,1} [national=all] 信用評等 全部:all 私立:0 國立:1
     * @apiParam {String=all,0,1,2} [system=all] 學制 全部:all 0:大學 1:碩士 2:博士
     * @apiParam {String=all,F,M} [gender=all] 性別 全部:all 女性:F 男性:M
	 * 
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} batch_id 智能出借ID
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均年利率(%)
     * @apiSuccess {Object} contract 合約列表
	 * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"total_amount": 70000,
     * 			"total_count": 1,
     * 			"max_instalment": "12",
     * 			"min_instalment": "12",
     * 			"XIRR": 10.47,
     * 			"batch_id": 2,
     * 			"contract": [
     * 				"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！"
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
	 *
     * @apiError 202 未通過所需的驗證(實名驗證)
     * @apiErrorExample {Object} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 金融帳號驗證尚未通過
     * @apiErrorExample {Object} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 208 未滿20歲
     * @apiErrorExample {Object} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {Object} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
     *     }
	 *
     * @apiError 212 未通過所需的驗證(Email)
     * @apiErrorExample {Object} 212
     *     {
     *       "result": "ERROR",
     *       "error": "212"
     *     }
	 *
     */
	public function batch_get()
    {

		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$where		= array(
			"user_id !=" 	=> $user_id,
			"status"		=> 3,
			"invested"		=> 0
		);
		
		//必填欄位
		if (empty($input['budget']) || intval($input['budget'])<=0) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}else{
			$budget = intval($input['budget']);
		}

		//檢查認證 NOT_VERIFIED
		if(empty($this->user_info->id_number) || $this->user_info->id_number==""){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
		}
		
		//檢查認證 NOT_VERIFIED_EMAIL
		if(empty($this->user_info->email) || $this->user_info->email==""){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
		}
		
		if($this->user_info->transaction_password==""){
			$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
		}

		if(get_age($this->user_info->birthday) < 20){
			$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
		}
		
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$this->load->model('user/user_bankaccount_model');
		$bank_account = $this->user_bankaccount_model->get_by(array("investor"=>$investor,"status"=>1,"user_id"=>$user_id,"verify"=>1));
		if(!$bank_account){
			$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
		}
		
		if(isset($input["interest_rate_s"]) && intval($input["interest_rate_s"])>=0){
			$where["interest_rate >="] = intval($input["interest_rate_s"]);
		}
		
		if(isset($input["interest_rate_e"]) && intval($input["interest_rate_e"])>0){
			$where["interest_rate <="] = intval($input["interest_rate_e"]);
		}

		if(isset($input["instalment_s"]) && intval($input["instalment_s"])>=0){
			$where["instalment >="] = intval($input["instalment_s"]);
		}
		
		if(isset($input["instalment_e"]) && intval($input["instalment_e"])>0){
			$where["instalment <="] = intval($input["instalment_e"]);
		}
		
		if(isset($input["credit_level"]) && !empty($input["credit_level"]) && $input["credit_level"]!='all' ){
			$where["credit_level"] = explode(",",$input["credit_level"]);
		}
		
		$targets = $this->target_model->get_many_by($where);
		if($targets){
			$investments = $this->investment_model->get_many_by(array("user_id"=>$user_id,"status"=>array(0,1,2,3)));
			if($investments){
				$investment_target = array();
				foreach($investments as $key => $value){
					$investment_target[] = $value->target_id;
				}
				
				foreach($targets as $key => $value){
					if(in_array($value->id,$investment_target)){
						unset($targets[$key]);
					}
				}
			}
			
			if(isset($input["gender"]) && !empty($input["gender"]) && $input["gender"]!='all' ){
				$where["gender"] = $input["gender"];
				if($targets){
					foreach($targets as $key => $value){
						$target_user_info = $this->user_model->get($value->user_id);
						if($target_user_info->sex != $input["gender"]){
							unset($targets[$key]);
						}
					}
				}
			}
			
			if(isset($input["system"]) && $input["system"]!='all' && $input["system"]!=''){
				$where["system"] = $input["system"];
				if($targets){
					foreach($targets as $key => $value){
						$user_meta = $this->user_meta_model->get_by(array(
							"user_id"	=> $value->user_id,
							"meta_key"	=> "school_system"
						));
						if($user_meta){
							if($user_meta->meta_value != $input["system"]){
								unset($targets[$key]);
							}
						}else{
							unset($targets[$key]);
						}
					}
				}
			}
			
			if(isset($input["national"]) && $input["national"]!='all' && $input["national"]!=''){
				$this->config->load('school_points',TRUE);
				$school_list = $this->config->item('school_points');
				$where["national"] = $input["national"];
				if($targets){
					foreach($targets as $key => $value){
						$user_meta = $this->user_meta_model->get_by(array(
							"user_id"	=> $value->user_id,
							"meta_key"	=> "school_name"
						));
						if($user_meta){
							foreach($school_list['school_points'] as $k => $v){
								if(trim($user_meta->meta_value)==$v['name']){
									$school_info = $v;
									break;
								}
							}
							if($school_info["national"]!=$input["national"]){
								unset($targets[$key]);
							}
						}else{
							unset($targets[$key]);
						}
					}
				}
			}

			if($targets){
				$where["budget"] = $budget;
				$data = array(
					'total_amount' 		=> 0,
					'total_count' 		=> 0,
					'max_instalment' 	=> 0,
					'min_instalment' 	=> 0,
					'XIRR' 				=> 0,
					'batch_id' 			=> "",
					'contract' 			=> array(),
				);
				$numerator = $denominator = 0;
				foreach($targets as $key => $value){
					$next = $data['total_amount'] + $value->loan_amount;
					if($next <= $budget){
						$data['total_amount'] += $value->loan_amount;
						$data['total_count'] ++;
						if($data['max_instalment'] < $value->instalment){
							$data['max_instalment'] = $value->instalment;
						}
						if($data['min_instalment'] > $value->instalment || $data['min_instalment']==0){
							$data['min_instalment'] = $value->instalment;
						}
						$contract_data 	= $this->contract_lib->get_contract($value->contract_id);
						$data['contract'][] = $contract_data?$contract_data["content"]:"";
						$content[] = $value->id;
						$numerator 		+= $value->loan_amount * $value->instalment * $value->interest_rate;
						$denominator 	+= $value->loan_amount * $value->instalment;
					}
				}

				if($data['total_count']){
					$param = array(
						"user_id"	=> $user_id,
						"type"		=> 0,
						"filter"	=> json_encode($where),
						"content"	=> json_encode($content),
					);
					$this->load->model('loan/batch_model');
					$batch_id = $this->batch_model->insert($param);
					if($batch_id){
						$data['XIRR'] = round($numerator/$denominator ,2);
						$data['batch_id'] = $batch_id;
						$this->response(array('result' => 'SUCCESS','data' => $data));
					}else{
						$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
					}
				}
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array(
			'total_amount' 		=> 0,
			'total_count' 		=> 0,
			'max_instalment' 	=> 0,
			'min_instalment' 	=> 0,
			'XIRR' 				=> 0,
			'batch_id' 			=> "",
			'contract' 			=> array(),
		)));
    }
	
	/**
     * @api {post} /target/batch/:batch_id 出借方 智能出借確認
	 * @apiVersion 0.1.0
	 * @apiName PostTargetBatch
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} batch_id 智能出借ID
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均年利率(%)
	 * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"total_amount": 50000,
     * 			"total_count": 1,
     * 			"max_instalment": "12",
     * 			"min_instalment": "12",
     * 			"XIRR": 10.47
     * 		}
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
	 *
	 * @apiError 811 智能出借不存在
     * @apiErrorExample {Object} 811
     *     {
     *       "result": "ERROR",
     *       "error": "811"
     *     }
	 *
	 * @apiError 812 對此智能出借無權限
     * @apiErrorExample {Object} 812
     *     {
     *       "result": "ERROR",
     *       "error": "812"
     *     }
     */
	 
	public function batch_post($batch_id=0)
    {
		$input 				= $this->input->post(NULL, TRUE);
		$this->load->model('loan/batch_model');
		
		if(!$batch_id){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		$user_id 			= $this->user_info->id;
		$batch 				= $this->batch_model->get($batch_id);
		if($batch && $batch->status==0 && $batch->type==0){
			if($batch->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => BATCH_NO_PERMISSION ));
			}
			
			$target_ids = json_decode($batch->content,true);
			$targets 	= $this->target_model->get_many($target_ids);
			if($targets){
				$data = array(
					'total_amount' 		=> 0,
					'total_count' 		=> 0,
					'max_instalment' 	=> 0,
					'min_instalment' 	=> 0,
					'XIRR' 				=> 0,
				);
				$numerator = $denominator = 0;
				foreach($targets as $key => $value){
					if($value->status == 3 ){
						$investments = $this->investment_model->get_by(array("target_id"=>$value->id,"user_id"=>$user_id,"status"=>array(0,1,2,3,10)));
						if(!$investments){
							$param = array(
								"user_id" 	=> $user_id,
								"target_id" => $value->id,
								"amount" 	=> $value->loan_amount,
							);
							$investment_id = $this->investment_model->insert($param);
							if($investment_id){
								$data['total_amount'] += $value->loan_amount;
								$data['total_count'] ++;
								if($data['max_instalment'] < $value->instalment){
									$data['max_instalment'] = $value->instalment;
								}
								if($data['min_instalment'] > $value->instalment || $data['min_instalment']==0){
									$data['min_instalment'] = $value->instalment;
								}
								$numerator 		+= $value->loan_amount * $value->instalment * $value->interest_rate;
								$denominator 	+= $value->loan_amount * $value->instalment;
							}
						}
					}
				}
				$data['XIRR'] = $data['total_count']>0?round($numerator/$denominator ,2):0;
				$this->response(array('result' => 'SUCCESS','data' => $data));
			}
			$this->response(array('result' => 'SUCCESS','data' => array(
				'total_amount' 		=> 0,
				'total_count' 		=> 0,
				'max_instalment' 	=> 0,
				'min_instalment' 	=> 0,
				'XIRR' 				=> 0,
			)));
		}
		$this->response(array('result' => 'ERROR','error' => BATCH_NOT_EXIST ));
    }
}
