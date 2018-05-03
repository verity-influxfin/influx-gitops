<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Target extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('product/product_model');
		$this->load->model('platform/certification_model');
		$this->load->model('transaction/target_model');
		$this->load->model('transaction/investment_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('Certification_lib');
		$this->load->library('Target_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list'];
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
     * @api {get} /target/list 出借方 取得標的列表
     * @apiGroup Target
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} credit_level 信用指數
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} loan_amount 核准金額
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
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
     * 					"name":"輕鬆學貸"
     * 				},
	 * 				"credit":{
     * 					"level":"1",
     * 					"points":"1985",
     * 					"amount":"45000",
     * 					"created_at":"1520421572"
     * 				},
     * 				"user_id":"1",
     * 				"loan_amount":"5000",
     * 				"interest_rate":"12",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"status":"2",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
     */
	 	
	public function list_get()
    {
		$this->load->library('credit_lib');
		$input 	= $this->input->get();
		$data	= array();
		$list	= array();
		$where	= array( "status" => 3 );
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$target_list 		= $this->target_model->get_many_by($where);

		if(!empty($target_list)){
			$product_list = array();
			$products = $this->product_model->get_all();
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
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} loan_amount 借款金額
	 * @apiSuccess {String} credit_level 信用指數
	 * @apiSuccess {String} interest_rate 年化利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} delay_days 逾期天數
	 * @apiSuccess {String} bank_account 借款人收款帳號
	 * @apiSuccess {String} virtual_account 還款虛擬帳號
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {json} certification 借款人認證完成資訊(
	 * @apiSuccess {json} user 借款人基本資訊
	 * @apiSuccess {String} user.name 姓名
	 * @apiSuccess {String} user.age 年齡
	 * @apiSuccess {String} user.school_name 學校名稱
	 * @apiSuccess {String} user.id_number 身分證字號
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
  	 *           "XIRR": 0.0939,
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
	 * @apiUse NotInvestor
	 * @apiError 801 標的不存在
     * @apiErrorExample {json} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
     */
	 
	public function info_get($target_id)
    {
		$this->load->library('Financial_lib');
		$this->load->library('credit_lib');
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target)){

			$product_info = $this->product_model->get($target->product_id);
			$product = array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
			);
			$product_info->certifications 	= json_decode($product_info->certifications,TRUE);
			$certification					= array();
			$certification_list			= $this->certification_lib->get_status($target->user_id);
			if(!empty($certification_list)){
				foreach($certification_list as $key => $value){
					if(in_array($value->id,$product_info->certifications)){
						$certification[] = array(
							"id" 			=> $value->id,
							"name" 			=> $value->name,
							"description" 	=> $value->description,
							"alias" 		=> $value->alias,
							"user_status" 	=> $value->user_status,
						);
					}
				}
			}

			$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$date="",$target->repayment);
		
			$user_info 	= $this->user_model->get($target->user_id); 
			$user		= array();
			if($user_info){
				$name 		= mb_substr($user_info->name,0,1,"UTF-8")."XX";
				$age  		= get_age($user_info->birthday);
				$user_meta 	= $this->user_meta_model->get_by(array("user_id"=>$target->user_id,"meta_key"=>"school_name"));
				$school_name= $user_meta?$user_meta->meta_value:"";
				$id_number 	= strlen($user_info->id_number)==10?substr($user_info->id_number,0,5)."XXXXX":"";
				$user = array(
					"name" 			=> $name,
					"age"			=> $age,
					"school_name"	=> $school_name,
					"id_number"		=> $id_number,
				);
			}
			
			$user_info 	= $this->user_model->get($target->user_id); 
			$user		= array();
			if($user_info){
				$name 		= mb_substr($user_info->name,0,1,"UTF-8")."XX";
				$age  		= get_age($user_info->birthday);
				$user_meta 	= $this->user_meta_model->get_by(array("user_id"=>$target->user_id,"meta_key"=>"school_name"));
				$school_name= $user_meta?$user_meta->meta_value:"";
				$id_number 	= strlen($user_info->id_number)==10?substr($user_info->id_number,0,5)."XXXXX":"";
				$user = array(
					"name" 			=> $name,
					"age"			=> $age,
					"school_name"	=> $school_name,
					"id_number"		=> $id_number,
				);
			}
			
			$data = array(
				"id" 				=> $target->id,
				"target_no" 		=> $target->target_no,
				"user_id" 			=> $target->user_id,
				"loan_amount" 		=> $target->loan_amount,
				"credit_level" 		=> $target->credit_level,
				"interest_rate" 	=> $target->interest_rate,
				"instalment" 		=> $instalment_list[$target->instalment],
				"repayment" 		=> $repayment_type[$target->repayment],
				"delay" 			=> $target->delay,
				"delay_days" 		=> $target->delay_days,
				"status" 			=> $target->status,
				"created_at" 		=> $target->created_at,
			);

			$data["user"] 					= $user;
			$data["product"] 				= $product;
			$data["certification"] 			= $certification;
			$data["amortization_schedule"] 	= $amortization_schedule;

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
     * @apiError 803 已申請出借
     * @apiErrorExample {json} 803
     *     {
     *       "result": "ERROR",
     *       "error": "803"
     *     }
	 *
     * @apiError 804 雙方不可同使用者
     * @apiErrorExample {json} 804
     *     {
     *       "result": "ERROR",
     *       "error": "804"
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
     * @apiError 203 金融帳號驗證尚未通過
     * @apiErrorExample {json} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 208 未滿20歲
     * @apiErrorExample {json} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
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
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$input[$field] = intval($input[$field]);
				$param[$field] = $input[$field];
			}
		}

		$target = $this->target_model->get($input['target_id']);
		if($target && $target->status == 3 ){
			
			if( $input['amount'] > $target->loan_amount ){
				$this->response(array('result' => 'ERROR',"error" => TARGET_AMOUNT_RANGE ));
			}

			if( $user_id == $target->user_id ){
				$this->response(array('result' => 'ERROR',"error" => TARGET_SAME_USER ));
			}


			if(get_age($this->user_info->birthday) < 20){
				$this->response(array('result' => 'ERROR',"error" => UNDER_AGE ));
			}
			
			$investments = $this->investment_model->get_by(array("target_id"=>$target->id,"user_id"=>$user_id,"status"=>array(0,1,2,3,10)));
			if($investments){
				$this->response(array('result' => 'ERROR',"error" => TARGET_APPLY_EXIST ));
			}
			

			//檢查認證 NOT_VERIFIED
			$this->load->library('Certification_lib');
			$certification_list	= $this->certification_lib->get_status($user_id,$investor);
			foreach($certification_list as $key => $value){
				if( $value->alias=='id_card' && $value->user_status!=1 ){
					$this->response(array('result' => 'ERROR',"error" => NOT_VERIFIED ));
				}
			}
			
			//檢查金融卡綁定 NO_BANK_ACCOUNT
			$bank_account = $this->user_bankaccount_model->get_by(array("investor"=>$investor,"status"=>1,"user_id"=>$user_id,"verify"=>1));
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
	 * @apiSuccess {String} id Investments ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {json} target 標的資訊
	 * @apiSuccess {String} amount 投標金額
	 * @apiSuccess {String} loan_amount 得標金額
	 * @apiSuccess {String} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
	 * @apiSuccess {String} created_at 申請日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"target": {
     * 					"id": "19",
     * 					"target_no": "1804233189",
     * 					"instalment": "3期",
     * 					"repayment": "等額本息",
     * 					"delay": "0",
     * 					"status": "3"
     * 				},
     * 				"amount":"5000",
     * 				"loan_amount":"",
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
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$param				= array( "user_id"=> $user_id);
		$investments		= $this->investment_model->get_many_by($param);
		$list				= array();
		if(!empty($investments)){
			foreach($investments as $key => $value){
				
				$target_info = $this->target_model->get($value->target_id);
				$target = array(
					"id"			=> $target_info->id,
					"target_no"		=> $target_info->target_no,
					"instalment" 	=> $instalment_list[$target_info->instalment],
					"repayment" 	=> $repayment_type[$target_info->repayment],
					"delay"			=> $target_info->delay,
					"status"		=> $target_info->status,
				);
				
				$product_info = $this->product_model->get($target_info->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
				);
				
				$list[] = array(
					"id" 				=> $value->id,
					"product" 			=> $product,
					"target" 			=> $target,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
 
}
