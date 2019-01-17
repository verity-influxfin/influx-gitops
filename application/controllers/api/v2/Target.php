<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Target extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->library('Contract_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list'];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
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
			
			if($this->request->method != 'get'){
				$this->load->model('log/log_request_model');
				$this->log_request_model->insert(array(
					'method' 	=> $this->request->method,
					'url'	 	=> $this->uri->uri_string(),
					'investor'	=> $tokenData->investor,
					'user_id'	=> $tokenData->id,
					'agent'		=> $tokenData->agent,
				));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }

	/**
     * @api {get} /v2/target/list 出借方 取得標的列表
	 * @apiVersion 0.2.0
	 * @apiName GetTargetList
     * @apiGroup Target
	 * @apiParam {String=credit_level,instalment,interest_rate} [orderby="credit_level"] 排序值
	 * @apiParam {String=asc,desc} [sort=asc] 降序/升序
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Targets ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {Number} product_id 產品ID
	 * @apiSuccess {Number} credit_level 信用評等
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Object} user 借款人基本資訊
	 * @apiSuccess {String} user.sex 性別 F/M
	 * @apiSuccess {Number} user.age 年齡
	 * @apiSuccess {String} user.company_name 單位名稱
	 * @apiSuccess {Number} loan_amount 核准金額
	 * @apiSuccess {Number} interest_rate 年化利率
	 * @apiSuccess {Number} instalment 期數
	 * @apiSuccess {Number} repayment 還款方式
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} invested 目前投標量
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				{
     * 					"id": 30,
     * 					"target_no": "STN2019011414457",
     * 					"product_id": 1,
     * 					"credit_level": 6,
     * 					"user_id": 1,
     * 					"user": {
     * 						"sex": "M",
     * 						"age": 29,
     * 						"company_name": "國立宜蘭大學"
     * 					},
     * 					"loan_amount": 5000,
     * 					"interest_rate": 10,
     * 					"instalment": 3,
     * 					"repayment": 1,
     * 					"expire_time": 1547792055,
     * 					"invested": 0,
     * 					"reason": "",
     * 					"status": 3,
     * 					"sub_status": 0,
     * 					"created_at": 1547455529
     * 				}
     * 			]
     * 		}
     *    }
     */
	 	
	public function list_get()
    {
		$input 			= $this->input->get();
		$list			= [];
		$where			= array( 'status' => 3 );
		$orderby 		= isset($input['orderby'])&&in_array($input['orderby'],array('credit_level','instalment','interest_rate'))?$input['orderby']:'credit_level';
		$sort			= isset($input['sort'])&&in_array($input['sort'],array('desc','asc'))?$input['sort']:'asc';
		$target_list 	= $this->target_model->order_by($orderby,$sort)->get_many_by($where);
		$product_list 	= $this->config->item('product_list');
		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				$user_info 	= $this->user_model->get($value->user_id); 
				$user		= [];
				if($user_info){
					$age  = get_age($user_info->birthday);
					if($product_list[$value->product_id]['identity']==1){
						$user_meta 	= $this->user_meta_model->get_by(['user_id'=>$value->user_id,'meta_key'=>'school_name']);
					}else{
						$user_meta 	= $this->user_meta_model->get_by(['user_id'=>$value->user_id,'meta_key'=>'company_name']);
					}
					
					$user = array(
						'sex' 			=> $user_info->sex,
						'age'			=> $age,
						'company_name'	=> $user_meta?$user_meta->meta_value:'',
					);
				}
				
				$list[] = array(
					'id' 				=> intval($value->id),
					'target_no' 		=> $value->target_no,
					'product_id' 		=> intval($value->product_id),
					'credit_level' 		=> intval($value->credit_level),
					'user_id' 			=> intval($value->user_id),
					'user' 				=> $user,
					'loan_amount' 		=> intval($value->loan_amount),
					'interest_rate' 	=> intval($value->interest_rate),
					'instalment' 		=> intval($value->instalment),
					'repayment' 		=> intval($value->repayment),
					'expire_time' 		=> intval($value->expire_time),
					'invested' 			=> intval($value->invested),
					'reason' 			=> $value->reason,
					'status' 			=> intval($value->status),
					'sub_status' 		=> intval($value->sub_status),
					'created_at' 		=> intval($value->created_at),
				);
			}
		}

		$this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ] ));
    }

	/**
     * @api {get} /v2/target/info/:id 出借方 取得標的資訊
	 * @apiVersion 0.2.0
	 * @apiName GetTargetInfo
     * @apiGroup Target
	 * @apiDescription 限架上案件
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id 標的ID
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Target ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {Number} product_id 產品ID
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Number} loan_amount 借款金額
	 * @apiSuccess {Number} credit_level 信用評等
	 * @apiSuccess {Number} interest_rate 年化利率
	 * @apiSuccess {Number} instalment 期數
	 * @apiSuccess {Number} repayment 還款方式
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} invested 目前投標量
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {Object} certification 借款人認證完成資訊
	 * @apiSuccess {Object} user 借款人基本資訊
	 * @apiSuccess {String} user.name 姓名
	 * @apiSuccess {String} user.id_number 身分證字號
	 * @apiSuccess {Number} user.age 年齡
	 * @apiSuccess {String} user.sex 性別 F/M
	 * @apiSuccess {String} user.company_name 單位名稱
	 * @apiSuccess {Object} amortization_schedule 預計還款計畫
	 * @apiSuccess {Number} amortization_schedule.amount 借款金額
	 * @apiSuccess {Number} amortization_schedule.instalment 借款期數
	 * @apiSuccess {Number} amortization_schedule.rate 年化利率
	 * @apiSuccess {String} amortization_schedule.date 起始時間
	 * @apiSuccess {Number} amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {Boolean} amortization_schedule.leap_year 是否為閏年
	 * @apiSuccess {Number} amortization_schedule.year_days 本年日數
	 * @apiSuccess {Number} amortization_schedule.XIRR 內部報酬率(%)
	 * @apiSuccess {Object} amortization_schedule.schedule 還款計畫
	 * @apiSuccess {Number} amortization_schedule.schedule.instalment 第幾期
	 * @apiSuccess {String} amortization_schedule.schedule.repayment_date 還款日
	 * @apiSuccess {Number} amortization_schedule.schedule.days 本期日數
	 * @apiSuccess {Number} amortization_schedule.schedule.remaining_principal 剩餘本金
	 * @apiSuccess {Number} amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {Number} amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {Number} amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {Object} amortization_schedule.total 還款總計
	 * @apiSuccess {Number} amortization_schedule.total.principal 本金
	 * @apiSuccess {Number} amortization_schedule.total.interest 利息
	 * @apiSuccess {Number} amortization_schedule.total.total_payment 加總

     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":30,
     * 			"target_no":"STN2019011414457",
     * 			"product_id":1,
     * 			"user_id":1,
     * 			"loan_amount":5000,
     * 			"credit_level":6,
     * 			"interest_rate":10,
     * 			"reason":"",
     * 			"remark":"",
     * 			"instalment":3,
     * 			"repayment":1,
     * 			"expire_time":1547792055,
     * 			"invested":0,
     * 			"status":3,
     * 			"sub_status":0,
     * 			"created_at":1547455529,
     * 			"contract":"我就是合約啊",
     *	        "user": {
     *           	"name": "陳**",
     *           	"id_number": "A1154*****",
     *           	"sex": "M",
     *           	"age": 29,
     *           	"company_name": "國立宜蘭大學"
     *          },
     *	        "certification": [
     *           	{
     *           	    "id": 1,
     *           	    "alias": "idcard",
     *           	    "name": "實名認證",
     *           	    "status": 1,
     *           	    "description": "驗證個人身份資訊",
     *           	    "user_status": 1,
     *           	},
     *           	{
     *           	    "id": 2,
     *           	    "alias": "student",
     *           	    "name": "學生身份認證",
     *           	    "status": 1,
     *           	    "description": "驗證學生身份",
     *           	    "user_status": 1,
     *           	}
     *           ],
     *	        "amortization_schedule": {
     *           	"amount": 5000,
     *           	"instalment": 3,
     *           	"rate": 10,
     *           	"date": "2019-01-16",
     *           	"total_payment": 1695,
     *           	"leap_year": false,
     *           	"year_days": 365,
     *           	"XIRR": 10.51,
     *           	"schedule": {
     *           		"1": {
     *           			"instalment": 1,
     *           			"repayment_date": "2019-03-10",
     *           			"days": 53,
     *           			"remaining_principal": 5000,
     *           			"principal": 1622,
     *           			"interest": 73,
     *           			"total_payment": 1695
     *           		},
     *           		"2": {
     *           			"instalment": 2,
     *           			"repayment_date": "2019-04-10",
     *           			"days": 31,
     *           			"remaining_principal": 3378,
     *           			"principal": 1666,
     *           			"interest": 29,
     *           			"total_payment": 1695
     *           		},
     *           		"3": {
     *           			"instalment": 3,
     *           			"repayment_date": "2019-05-10",
     *           			"days": 30,
     *           			"remaining_principal": 1712,
     *           			"principal": 1712,
     *           			"interest": 14,
     *           			"total_payment": 1726
     *           		}
     *           	},
     *           	"total": {
     *           		"principal": 5000,
     *           		"interest": 116,
     *           		"total_payment": 5116
     *           	}
     *	        }
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
		$target 			= $this->target_model->get($target_id);
		$data				= array();
		if(!empty($target) && in_array($target->status,[3,4])){
			
			$product_list 	= $this->config->item('product_list');
			$product_info	= $product_list[$target->product_id];
			$certification	= [];
			$this->load->library('Certification_lib');
			$certification_list	= $this->certification_lib->get_status($target->user_id);
			if(!empty($certification_list)){
				foreach($certification_list as $key => $value){
					if(in_array($key,$product_info['certifications'])){
						unset($value['certification_id']);
						$certification[] = $value;
					}
				}
			}

			$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$date='',$target->repayment);
		
			$user_info 	= $this->user_model->get($target->user_id); 
			$user		= [];
			if($user_info){
				$name 		= mb_substr($user_info->name,0,1,'UTF-8').'**';
				$id_number 	= strlen($user_info->id_number)==10?substr($user_info->id_number,0,5).'*****':'';
				$age  		= get_age($user_info->birthday);
				if($product_info['identity']==1){
					$user_meta 	= $this->user_meta_model->get_by(['user_id'=>$target->user_id,'meta_key'=>'school_name']);
				}else{
					$user_meta 	= $this->user_meta_model->get_by(['user_id'=>$target->user_id,'meta_key'=>'company_name']);
				}
				
				$user = array(
					'name' 			=> $name,
					'id_number'		=> $id_number,
					'sex' 			=> $user_info->sex,
					'age'			=> $age,
					'company_name'	=> $user_meta?$user_meta->meta_value:'',
				);
			}

			$contract_data 	= $this->contract_lib->get_contract($target->contract_id);
			$contract 		= $contract_data?$contract_data['content']:'';
			$data = array(
				'id' 				=> intval($target->id),
				'target_no' 		=> $target->target_no,
				'product_id' 		=> intval($target->product_id),
				'user_id' 			=> intval($target->user_id),
				'loan_amount' 		=> intval($target->loan_amount),
				'credit_level' 		=> intval($target->credit_level),
				'interest_rate' 	=> intval($target->interest_rate),
				'reason' 			=> $target->reason,
				'remark' 			=> $target->remark,
				'instalment' 		=> intval($target->instalment),
				'repayment' 		=> intval($target->repayment),
				'expire_time' 		=> intval($target->expire_time),
				'invested' 			=> intval($target->invested),
				'status' 			=> intval($target->status),
				'sub_status' 		=> intval($target->sub_status),
				'created_at' 		=> intval($target->created_at),
				'contract' 			=> $contract,
				'user' 				=> $user,
				'certification' 	=> $certification,
				'amortization_schedule' => $amortization_schedule,
			);

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST ));
    }
	
	/**
     * @api {post} /v2/target/apply 出借方 申請出借
	 * @apiVersion 0.2.0
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
		$param		= ['user_id' => $user_id];
		
		//必填欄位
		$fields 	= ['target_id','amount'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}

		$target = $this->target_model->get($param['target_id']);
		if($target && $target->status == 3 ){
			
			if( $param['amount'] < TARGET_AMOUNT_MIN || $param['amount'] > $target->loan_amount ){
				$this->response(array('result' => 'ERROR','error' => TARGET_AMOUNT_RANGE ));
			}

			if( $user_id == $target->user_id ){
				$this->response(array('result' => 'ERROR','error' => TARGET_SAME_USER ));
			}

			//檢查認證 NOT_VERIFIED
			if(empty($this->user_info->id_number) || $this->user_info->id_number==''){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
			}
			
			//檢查認證 NOT_VERIFIED_EMAIL
			if(empty($this->user_info->email) || $this->user_info->email==''){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
			}
		
			if(get_age($this->user_info->birthday) < 20){
				$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
			}

			//檢查金融卡綁定 NO_BANK_ACCOUNT
			$this->load->model('user/user_bankaccount_model');
			$bank_account = $this->user_bankaccount_model->get_by([
				'investor'	=> $investor,
				'status'	=> 1,
				'user_id'	=> $user_id,
				'verify'	=> 1
			]);
			if(!$bank_account){
				$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
			}
			
			if($this->user_info->transaction_password==''){
				$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
			}

			$exist = $this->investment_model->get_by([
				'target_id'	=> $target->id,
				'user_id'	=> $user_id,
				'status'	=> [0,1,2,3,10]
			]);
			if($exist){
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
     * @api {get} /v2/target/applylist 出借方 申請紀錄列表
	 * @apiVersion 0.2.0
	 * @apiName GetTargetApplylist
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
	 * @apiDescription 只顯示 待付款 待結標 待放款 狀態的申請
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} amount 投標金額
	 * @apiSuccess {Number} loan_amount 得標金額
	 * @apiSuccess {Number} status 投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {Object} target 標的資訊
	 * @apiSuccess {Number} target.id 產品ID
	 * @apiSuccess {String} target.target_no 標的案號
	 * @apiSuccess {Number} target.product_id 產品ID
	 * @apiSuccess {Number} target.user_id User ID
	 * @apiSuccess {Number} target.loan_amount 標的金額
	 * @apiSuccess {Number} target.credit_level 信用評等
	 * @apiSuccess {Number} target.interest_rate 年化利率
	 * @apiSuccess {Number} target.instalment 期數
	 * @apiSuccess {Number} target.repayment 還款方式
	 * @apiSuccess {Number} target.expire_time 流標時間
	 * @apiSuccess {Number} target.invested 目前投標量
	 * @apiSuccess {Number} target.status 標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"amount": 5000,
     * 				"loan_amount": 0,
     * 				"status": 0,
     * 				"created_at": 1547626406,
     * 				"target": {
     * 					"id": 18,
     * 					"target_no": "STN2019011430611",
     * 					"product_id": 1,
     * 					"user_id": 19,
     * 					"loan_amount": 5000,
     * 					"credit_level": 3,
     * 					"interest_rate": 8,
     * 					"instalment": 6,
     * 					"repayment": 1,
     * 					"expire_time": 1547618700,
     * 					"invested": 0,
     * 					"status": 3,
     * 					"sub_status": 0
     * 				}
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
	public function applylist_get()
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$investments	= $this->investment_model->get_many_by([
			'user_id' 	=> $user_id,
			'status'	=> [0,1,2]
		]);
		$list			= [];
		if(!empty($investments)){
			foreach($investments as $key => $value){
				$target_info = $this->target_model->get($value->target_id);
				$target = array(
					'id'			=> intval($target_info->id),
					'target_no'		=> $target_info->target_no,
					'product_id'	=> intval($target_info->product_id),
					'user_id' 		=> intval($target_info->user_id),
					'loan_amount'	=> intval($target_info->loan_amount),
					'credit_level' 	=> intval($target_info->credit_level),
					'interest_rate' => intval($target_info->interest_rate),
					'instalment' 	=> intval($target_info->instalment),
					'repayment' 	=> intval($target_info->repayment),
					'expire_time'	=> intval($target_info->expire_time),
					'invested'		=> intval($target_info->invested),
					'status'		=> intval($target_info->status),
					'sub_status'	=> intval($target_info->sub_status),
				);
			
				$list[] = array(
					'amount' 			=> intval($value->amount),
					'loan_amount' 		=> intval($value->loan_amount),
					'status' 			=> intval($value->status),
					'created_at' 		=> intval($value->created_at),
					'target' 			=> $target,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ]));
    }
 
 	/**
     * @api {get} /v2/target/batch 出借方 智能出借
	 * @apiVersion 0.2.0
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
			'user_id !=' 	=> $user_id,
			'status'		=> 3,
			'invested'		=> 0
		);
		
		//必填欄位
		if (empty($input['budget']) || intval($input['budget'])<=0) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}else{
			$budget = intval($input['budget']);
		}

		//檢查認證 NOT_VERIFIED
		if(empty($this->user_info->id_number) || $this->user_info->id_number==''){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
		}
		
		//檢查認證 NOT_VERIFIED_EMAIL
		if(empty($this->user_info->email) || $this->user_info->email==''){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
		}
		
		if($this->user_info->transaction_password==''){
			$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
		}

		if(get_age($this->user_info->birthday) < 20){
			$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
		}
		
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$this->load->model('user/user_bankaccount_model');
		$bank_account = $this->user_bankaccount_model->get_by(array('investor'=>$investor,'status'=>1,'user_id'=>$user_id,'verify'=>1));
		if(!$bank_account){
			$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
		}
		
		if(isset($input['interest_rate_s']) && intval($input['interest_rate_s'])>=0){
			$where['interest_rate >='] = intval($input['interest_rate_s']);
		}
		
		if(isset($input['interest_rate_e']) && intval($input['interest_rate_e'])>0){
			$where['interest_rate <='] = intval($input['interest_rate_e']);
		}

		if(isset($input['instalment_s']) && intval($input['instalment_s'])>=0){
			$where['instalment >='] = intval($input['instalment_s']);
		}
		
		if(isset($input['instalment_e']) && intval($input['instalment_e'])>0){
			$where['instalment <='] = intval($input['instalment_e']);
		}
		
		if(isset($input['credit_level']) && !empty($input['credit_level']) && $input['credit_level']!='all' ){
			$where['credit_level'] = explode(',',$input['credit_level']);
		}
		
		$targets = $this->target_model->get_many_by($where);
		if($targets){
			$investments = $this->investment_model->get_many_by(array('user_id'=>$user_id,'status'=>array(0,1,2,3)));
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
			
			if(isset($input['gender']) && !empty($input['gender']) && $input['gender']!='all' ){
				$where['gender'] = $input['gender'];
				if($targets){
					foreach($targets as $key => $value){
						$target_user_info = $this->user_model->get($value->user_id);
						if($target_user_info->sex != $input['gender']){
							unset($targets[$key]);
						}
					}
				}
			}
			
			if(isset($input['system']) && $input['system']!='all' && $input['system']!=''){
				$where['system'] = $input['system'];
				if($targets){
					foreach($targets as $key => $value){
						$user_meta = $this->user_meta_model->get_by(array(
							'user_id'	=> $value->user_id,
							'meta_key'	=> 'school_system'
						));
						if($user_meta){
							if($user_meta->meta_value != $input['system']){
								unset($targets[$key]);
							}
						}else{
							unset($targets[$key]);
						}
					}
				}
			}
			
			if(isset($input['national']) && $input['national']!='all' && $input['national']!=''){
				$this->config->load('school_points',TRUE);
				$school_list = $this->config->item('school_points');
				$where['national'] = $input['national'];
				if($targets){
					foreach($targets as $key => $value){
						$user_meta = $this->user_meta_model->get_by(array(
							'user_id'	=> $value->user_id,
							'meta_key'	=> 'school_name'
						));
						if($user_meta){
							foreach($school_list['school_points'] as $k => $v){
								if(trim($user_meta->meta_value)==$v['name']){
									$school_info = $v;
									break;
								}
							}
							if($school_info['national']!=$input['national']){
								unset($targets[$key]);
							}
						}else{
							unset($targets[$key]);
						}
					}
				}
			}

			if($targets){
				$where['budget'] = $budget;
				$data = array(
					'total_amount' 		=> 0,
					'total_count' 		=> 0,
					'max_instalment' 	=> 0,
					'min_instalment' 	=> 0,
					'XIRR' 				=> 0,
					'batch_id' 			=> '',
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
						$data['contract'][] = $contract_data?$contract_data['content']:'';
						$content[] = $value->id;
						$numerator 		+= $value->loan_amount * $value->instalment * $value->interest_rate;
						$denominator 	+= $value->loan_amount * $value->instalment;
					}
				}

				if($data['total_count']){
					$param = array(
						'user_id'	=> $user_id,
						'type'		=> 0,
						'filter'	=> json_encode($where),
						'content'	=> json_encode($content),
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
			'batch_id' 			=> '',
			'contract' 			=> array(),
		)));
    }
	
	/**
     * @api {post} /v2/target/batch/:batch_id 出借方 智能出借確認
	 * @apiVersion 0.2.0
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
						$investments = $this->investment_model->get_by(array('target_id'=>$value->id,'user_id'=>$user_id,'status'=>array(0,1,2,3,10)));
						if(!$investments){
							$param = array(
								'user_id' 	=> $user_id,
								'target_id' => $value->id,
								'amount' 	=> $value->loan_amount,
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
