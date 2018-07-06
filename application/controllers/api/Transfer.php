<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Transfer extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('product/product_model');
		$this->load->model('platform/certification_model');
		$this->load->model('loan/target_model');
		$this->load->model('loan/investment_model');
		$this->load->model('loan/transfer_investment_model');
		$this->load->model('loan/batch_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('Certification_lib');
		$this->load->library('Target_lib');
		$this->load->library('Transfer_lib');
		$this->load->library('credit_lib');
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
     * @api {get} /transfer/list 出借方 取得債權標的列表
     * @apiGroup Transfer
	 * @apiParam {String} orderby 排序值 credit_level(default)、instalment、interest_rate
	 * @apiParam {String} sort 降序/升序 desc/asc(default)
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Transfer ID
	 * @apiSuccess {String} amount 借款轉讓金額
	 * @apiSuccess {String} instalment 借款剩餘期數
	 * @apiSuccess {String} expire_time 流標時間
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {json} target 原案資訊
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {String} ctarget.redit_level 信用指數
	 * @apiSuccess {String} target.user_id User ID
	 * @apiSuccess {String} target.loan_amount 核准金額
	 * @apiSuccess {String} target.interest_rate 核可利率
	 * @apiSuccess {String} target.instalment 期數
	 * @apiSuccess {String} target.repayment 還款方式
	 * @apiSuccess {String} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} target.delay_days 逾期天數
	 * @apiSuccess {String} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} target.created_at 申請日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"amount": "5000",
     * 				"instalment": "12",
     * 				"expire_time": "1527865369",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸"
     * 				},
     * 				"target": {
     * 					"id": "2",
     * 					"target_no": "1805281652",
     * 					"credit_level": "4",
     * 					"user_id": "2",
     * 					"loan_amount": "5000",
     * 					"interest_rate": "10",
     * 					"instalment": "12期",
     * 					"repayment": "等額本息",
     * 					"delay": "1",
     * 					"delay_days": "0",
     * 					"status": "5",
     * 					"sub_status": "3",
     * 					"created_at": "1527490889"
     * 				}
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
		$input 	= $this->input->get();
		$data	= array();
		$list	= array();
		
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$transfer 			= $this->transfer_lib->get_transfer_list();
		
		if(!empty($transfer)){
			
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

			foreach($transfer as $key => $value){
				$target 				= $this->target_model->get($value->target_id);
				$target_info = array(
					"id" 				=> $target->id,
					"target_no" 		=> $target->target_no,
					"credit_level" 		=> $target->credit_level,
					"user_id" 			=> $target->user_id,
					"loan_amount" 		=> $target->loan_amount?$target->loan_amount:"",
					"interest_rate" 	=> $target->interest_rate?$target->interest_rate:"",
					"instalment" 		=> $instalment_list[$target->instalment],
					"repayment" 		=> $repayment_type[$target->repayment],
					"delay" 			=> $target->delay,
					"delay_days" 		=> $target->delay_days,
					"status" 			=> $target->status,
					"sub_status" 		=> $target->sub_status,
					"created_at" 		=> $target->created_at,
				);
				$product	= $product_list[$target->product_id];
				$list[] 	= array(
					"id"			=> $value->id,
					"amount"		=> $value->amount,
					"instalment"	=> $value->instalment,
					"expire_time"	=> $value->expire_time,
					"product"		=> $product,
					"target"		=> $target_info,
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }

	/**
     * @api {get} /transfer/info/{ID} 出借方 取得債權標的資訊
     * @apiGroup Transfer
	 * @apiParam {number} ID 投資ID
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Transfer ID
	 * @apiSuccess {String} amount 借款轉讓金額
	 * @apiSuccess {String} instalment 借款剩餘期數
	 * @apiSuccess {String} debt_transfer_contract 債權轉讓合約
	 * @apiSuccess {String} expire_time 流標時間
	 * @apiSuccess {json} target 原案資訊
	 * @apiSuccess {String} target.id Target ID
	 * @apiSuccess {String} target.target_no 標的號
	 * @apiSuccess {String} target.user_id User ID
	 * @apiSuccess {String} target.loan_amount 借款金額
	 * @apiSuccess {String} target.credit_level 信用指數
	 * @apiSuccess {String} target.interest_rate 年化利率
	 * @apiSuccess {String} target.total_interest 總利息
	 * @apiSuccess {String} target.instalment 期數
	 * @apiSuccess {String} target.repayment 還款方式
	 * @apiSuccess {String} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} target.delay_days 逾期天數
	 * @apiSuccess {String} target.remark 備註
	 * @apiSuccess {String} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} target.created_at 申請日期
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} product.name 產品名稱
	 * @apiSuccess {json} certification 借款人認證完成資訊
	 * @apiSuccess {json} user 借款人基本資訊
	 * @apiSuccess {String} user.name 姓名
	 * @apiSuccess {String} user.age 年齡
	 * @apiSuccess {String} user.school_name 學校名稱
	 * @apiSuccess {String} user.id_number 身分證字號

     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
	 *			"id":"1",
	 *			"amount":"5000",
	 *			"instalment":"12",
	 *			"debt_transfer_contract":"我是合約！！",
	 *			"expire_time":"1527865369",
     * 			"target":{
     * 				"id":"1",
     * 				"target_no": "1803269743",
     * 				"user_id":"1",
     * 				"loan_amount":"12000",
	 * 				"credit_level":"4",
     * 				"interest_rate":"9",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay": "0",
     * 				"delay_days": "0",
     * 				"status":"4",
     * 				"sub_status":"0",
     * 				"created_at":"1520421572",
     * 			},
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
     *       }
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
	 
	public function info_get($transfer_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$transfer 			= $this->transfer_lib->get_transfer($transfer_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($transfer)){
			$target 		= $this->target_model->get($transfer->target_id);
			$product_info 	= $this->product_model->get($target->product_id);
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

			$data 	= array(
				"id"			=> $transfer->id,
				"amount"		=> $transfer->amount,
				"instalment"	=> $transfer->instalment,
				"debt_transfer_contract" => $transfer->contract,
				"expire_time"	=> $transfer->expire_time,
			);
				
			$data["target"] = array(
				"id" 				=> $target->id,
				"target_no" 		=> $target->target_no,
				"user_id" 			=> $target->user_id,
				"loan_amount" 		=> $target->loan_amount,
				"credit_level" 		=> $target->credit_level,
				"interest_rate" 	=> $target->interest_rate,
				"remark" 			=> $target->remark,
				"instalment" 		=> $instalment_list[$target->instalment],
				"repayment" 		=> $repayment_type[$target->repayment],
				"delay" 			=> $target->delay,
				"delay_days" 		=> $target->delay_days,
				"status" 			=> $target->status,
				"sub_status" 		=> $target->sub_status,
				"created_at" 		=> $target->created_at,
			);

			$data["user"] 			= $user;
			$data["product"] 		= $product;
			$data["certification"] 	= $certification;

			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => TRANSFER_NOT_EXIST ));
    }
	
	/**
     * @api {post} /transfer/apply 出借方 申請債權收購
     * @apiGroup Transfer
	 * @apiParam {number} transfer_id (required) 投資ID
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
	 * @apiError 809 債權轉讓標的不存在
     * @apiErrorExample {json} 809
     *     {
     *       "result": "ERROR",
     *       "error": "809"
     *     }
	 *
     * @apiError 810 已申請收購
     * @apiErrorExample {json} 810
     *     {
     *       "result": "ERROR",
     *       "error": "810"
     *     }
	 *
     * @apiError 804 雙方不可同使用者
     * @apiErrorExample {json} 804
     *     {
     *       "result": "ERROR",
     *       "error": "804"
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
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {json} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
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
		$fields 	= ['transfer_id'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$input[$field] = intval($input[$field]);
				$param[$field] = $input[$field];
			}
		}
		
		$transfer = $this->transfer_lib->get_transfer($input['transfer_id']);
		if($transfer && $transfer->status == 0 ){
			$param["amount"] = $transfer->amount;
			$investment = $this->investment_model->get($transfer->investment_id);
			if( $user_id == $investment->user_id ){
				$this->response(array('result' => 'ERROR',"error" => TARGET_SAME_USER ));
			}


			if(get_age($this->user_info->birthday) < 20){
				$this->response(array('result' => 'ERROR',"error" => UNDER_AGE ));
			}
			
			$transfer_investment = $this->transfer_investment_model->get_by(array("transfer_id"=>$input['transfer_id'],"user_id"=>$user_id,"status"=>array(0,1,10)));
			if($transfer_investment){
				$this->response(array('result' => 'ERROR',"error" => TRANSFER_APPLY_EXIST ));
			}

			//檢查認證 NOT_VERIFIED
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
			
			if($this->user_info->transaction_password==""){
				$this->response(array('result' => 'ERROR',"error" => NO_TRANSACTION_PASSWORD ));
			}
			
			$insert = $this->transfer_investment_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		
		$this->response(array('result' => 'ERROR',"error" => TRANSFER_NOT_EXIST ));
    }

	 
 	/**
     * @api {get} /transfer/batch 出借方 智能收購
     * @apiGroup Transfer
	 * @apiParam {number} budget (required) 預算金額
	 * @apiParam {number} delay (required) 逾期標的 0:正常標的 1:逾期標的 default:0
     * @apiParam {number} user_id 指定使用者ID
	 * @apiParam {number} interest_rate_s 正常標的-利率區間下限(%)
     * @apiParam {number} interest_rate_e 正常標的-利率區間上限(%)
     * @apiParam {number} instalment_s 正常標的-剩餘期數區間下限(%)
     * @apiParam {number} instalment_e 正常標的-剩餘期數區間上限(%)
     * @apiParam {String} credit_level 逾期標的-信用評等 全部：all 複選使用逗號隔開6,7,8 default:all
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} batch_id 智能收購ID
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均內部報酬率(%)
     * @apiSuccess {json} debt_transfer_contract 合約列表
	 * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"total_amount": 70000,
     * 			"total_count": 1,
     * 			"max_instalment": "12",
     * 			"min_instalment": "12",
     * 			"XIRR": 10.47,
     * 			"batch_id": 2,
     * 			"debt_transfer_contract": [
     * 				"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！"
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
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
     * @apiError 208 未滿20歲
     * @apiErrorExample {json} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {json} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
     *     }
	 *
     */
	public function batch_get()
    {

		$filter 	= $input = $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$where		= array(
			"user_id !="	=> $user_id,
			"status"		=> 5,
		);
		
		//必填欄位
		if (empty($input['budget']) || intval($input['budget'])<=0) {
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}else{
			$budget = intval($input['budget']);
		}

		if (isset($input['delay']) && in_array($input['delay'],array(0,1))) {
			$delay 	= intval($input['delay']);
		}else{
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}

		//檢查認證 NOT_VERIFIED
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
		
		if($this->user_info->transaction_password==""){
			$this->response(array('result' => 'ERROR',"error" => NO_TRANSACTION_PASSWORD ));
		}

		if(get_age($this->user_info->birthday) < 20){
			$this->response(array('result' => 'ERROR',"error" => UNDER_AGE ));
		}
		
		$transfer 	= $this->transfer_lib->get_transfer_list();
		if($transfer){
			
			if($delay){
				$where["delay_days >"] 	= GRACE_PERIOD;
				if(isset($input["credit_level"]) && !empty($input["credit_level"]) && $input["credit_level"]!='all' ){
					$where["credit_level"] = explode(",",$input["credit_level"]);
				}
			}else{
				$where["delay_days <="] = GRACE_PERIOD;
				if(isset($input["interest_rate_s"]) && intval($input["interest_rate_s"])>=0){
					$where["interest_rate >="] = intval($input["interest_rate_s"]);
				}
				
				if(isset($input["interest_rate_e"]) && intval($input["interest_rate_e"])>0){
					$where["interest_rate <="] = intval($input["interest_rate_e"]);
				}

				if(isset($input["instalment_s"]) && intval($input["instalment_s"])>=0){
					if($transfer){
						foreach($transfer as $key => $value){
							if($value->instalment<intval($input["instalment_s"])){
								unset($transfer[$key]);
							}
						}
					}
				}
				
				if(isset($input["instalment_e"]) && intval($input["instalment_e"])>0){
					if($transfer){
						foreach($transfer as $key => $value){
							if($value->instalment>intval($input["instalment_e"])){
								unset($transfer[$key]);
							}
						}
					}
				}
			
			}
			$transfer_investment = $this->transfer_investment_model->get_by(array("user_id"=>$user_id,"status"=>array(0,1,10)));
			if($transfer_investment){
				if($transfer){
					$transfer_investment_target = array();
					foreach($transfer_investment as $key => $value){
						$transfer_investment_target[] = $value->transfer_id;
					}
					
					foreach($transfer as $key => $value){
						if(in_array($value->id,$transfer_investment_target)){
							unset($transfer[$key]);
						}
					}
				}
			}
			
			if($transfer){
				$target_ids = array();
				foreach($transfer as $key => $value){
					$target_ids[] = $value->target_id;
				}
				$where["id"] 	= $target_ids;
				$targets = $this->target_model->get_many_by($where);
				if($targets){
					$target_ids 	= array();
					$target_list 	= array();
					foreach($targets as $key => $value){
						$target_ids[] = $value->id;
						$target_list[$value->id] = $value;
					}
					
					$where["budget"] = $budget;
					$data = array(
						'total_amount' 		=> 0,
						'total_count' 		=> 0,
						'max_instalment' 	=> 0,
						'min_instalment' 	=> 0,
						'XIRR' 				=> 0,
						'batch_id' 			=> "",
						'debt_transfer_contract' => array(),
					);
					foreach($transfer as $key => $value){
						if(in_array($value->target_id,$target_ids)){
							
							$next = $data['total_amount'] + $value->amount;
							if($next <= $budget){
								$data['total_amount'] += $value->amount;
								$data['total_count'] ++;
								if($data['max_instalment'] < $value->instalment){
									$data['max_instalment'] = $value->instalment;
								}
								if($data['min_instalment'] > $value->instalment || $data['min_instalment']==0){
									$data['min_instalment'] = $value->instalment;
								}
								$data['debt_transfer_contract'][] = $value->contract;
								$content[] = $value->id;
								$target = $target_list[$value->target_id];
								$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$target->loan_date,$target->repayment);
								$data['XIRR'] += $amortization_schedule["XIRR"];
							}
						}
					}
					if($data['total_count']){
						$param = array(
							"user_id"	=> $user_id,
							"type"		=> 1,
							"filter"	=> json_encode($filter),
							"content"	=> json_encode($content),
						);
						$batch_id = $this->batch_model->insert($param);
						if($batch_id){
							$data['XIRR'] = round($data['XIRR']/$data['total_count'] ,2);
							$data['batch_id'] = $batch_id;
							$this->response(array('result' => 'SUCCESS',"data" => $data));
						}else{
							$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
						}
					}
				}
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array(
			'total_amount' 		=> 0,
			'total_count' 		=> 0,
			'max_instalment' 	=> 0,
			'min_instalment' 	=> 0,
			'XIRR' 				=> 0,
			'batch_id' 			=> "",
			'debt_transfer_contract' => array(),
		)));
    }
	
	/**
     * @api {post} /transfer/batch/{batch_id} 出借方 智能收購確認
     * @apiGroup Transfer
	 * @apiParam {number} batch_id (required) 智能收購ID
     *
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均內部報酬率(%)
	 * @apiSuccessExample {json} SUCCESS
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
	 * @apiUse TokenError
	 * @apiUse InputError
	 * @apiUse NotInvestor
	 *
	 * @apiError 811 智能收購不存在
     * @apiErrorExample {json} 811
     *     {
     *       "result": "ERROR",
     *       "error": "811"
     *     }
	 *
	 * @apiError 812 對此智能收購無權限
     * @apiErrorExample {json} 812
     *     {
     *       "result": "ERROR",
     *       "error": "812"
     *     }
     */
	 
	public function batch_post($batch_id)
    {
		$input 				= $this->input->post(NULL, TRUE);
		if(!$batch_id){
			$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
		}
		$user_id 			= $this->user_info->id;
		$batch 				= $this->batch_model->get($batch_id);
		if($batch && $batch->status==0 && $batch->type==1){
			if($batch->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => BATCH_NO_PERMISSION ));
			}
			
			$transfer_ids 	= json_decode($batch->content,true);
			$transfer 		= $this->transfer_lib->get_transfer_list(array("id"=>$transfer_ids,"status"=>0));
			if($transfer){
				$data = array(
					'total_amount' 		=> 0,
					'total_count' 		=> 0,
					'max_instalment' 	=> 0,
					'min_instalment' 	=> 0,
					'XIRR' 				=> 0,
				);
				foreach($transfer as $key => $value){
					if($value->status == 0 ){
						$investments = $this->transfer_investment_model->get_by(array("transfer_id"=>$value->id,"user_id"=>$user_id,"status"=>array(0,1,10)));
						if(!$investments){
							$param = array(
								"user_id" 		=> $user_id,
								"transfer_id" 	=> $value->id,
								"amount" 		=> $value->amount,
							);
							$investment_id = $this->transfer_investment_model->insert($param);
							if($investment_id){
								$data['total_amount'] += $value->amount;
								$data['total_count'] ++;
								if($data['max_instalment'] < $value->instalment){
									$data['max_instalment'] = $value->instalment;
								}
								if($data['min_instalment'] > $value->instalment || $data['min_instalment']==0){
									$data['min_instalment'] = $value->instalment;
								}
								$target = $this->target_model->get($value->target_id);
								$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$target->loan_date,$target->repayment);
								$data['XIRR'] += $amortization_schedule["XIRR"];
							}
						}
					}
				}
				$data['XIRR'] = $data['total_count']>0?round($data['XIRR']/$data['total_count'] ,2):0;
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'SUCCESS',"data" => array(
				'total_amount' 		=> 0,
				'total_count' 		=> 0,
				'max_instalment' 	=> 0,
				'min_instalment' 	=> 0,
				'XIRR' 				=> 0,
			)));
		}
		$this->response(array('result' => 'ERROR',"error" => BATCH_NOT_EXIST ));
    }
}
