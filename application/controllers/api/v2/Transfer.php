<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Transfer extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('loan/transfer_investment_model');
		$this->load->library('Target_lib');
		$this->load->library('Transfer_lib');
		$this->load->library('credit_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
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
     * @api {get} /v2/transfer/list 出借方 取得債權標的列表
	 * @apiVersion 0.2.0
	 * @apiName GetTransferList
     * @apiGroup Transfer
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String=credit_level,instalment,interest_rate} [orderby="credit_level"] 排序值
	 * @apiParam {String=asc,desc} [sort=asc] 降序/升序
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Transfer ID
	 * @apiSuccess {Number} amount 價金
	 * @apiSuccess {Number} principal 剩餘本金
	 * @apiSuccess {Number} interest 已發生利息
	 * @apiSuccess {Number} delay_interest 已發生延滯利息
	 * @apiSuccess {Float} bargain_rate 增減價比率(%)
	 * @apiSuccess {Number} instalment 剩餘期數
	 * @apiSuccess {Number} combination Combination ID
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} accounts_receivable 應收帳款
	 * @apiSuccess {Object} target 原案資訊
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {Number} target.product_id 產品ID
	 * @apiSuccess {Number} target.credit_level 信用指數
	 * @apiSuccess {Number} target.user_id User ID
	 * @apiSuccess {Number} target.loan_amount 核准金額
	 * @apiSuccess {Number} target.interest_rate 核可利率
	 * @apiSuccess {Number} target.instalment 期數
	 * @apiSuccess {Number} target.repayment 還款方式
	 * @apiSuccess {Number} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} target.delay_days 逾期天數
	 * @apiSuccess {String} target.reason 借款原因
	 * @apiSuccess {String} target.remark 備註
	 * @apiSuccess {Number} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Number} target.created_at 申請日期
	 * @apiSuccess {Object} target.user 借款人基本資訊
	 * @apiSuccess {Number} target.user.age 年齡
	 * @apiSuccess {String} target.user.sex 性別 F/M
	 * @apiSuccess {Object} combination_list 整包債權列表
     * @apiSuccess {Number} combination_list.id Combination ID
     * @apiSuccess {String} combination_list.combination_no 整包轉讓號
     * @apiSuccess {Boolean} combination_list.password 是否需要密碼
     * @apiSuccess {Number} combination_list.count 筆數
     * @apiSuccess {Number} combination_list.amount 整包轉讓價金
     * @apiSuccess {Number} combination_list.principal 整包剩餘本金
     * @apiSuccess {Number} combination_list.interest 整包已發生利息
     * @apiSuccess {Number} combination_list.delay_interest 整包已發生延滯息
     * @apiSuccess {Number} combination_list.max_instalment 最大剩餘期數
     * @apiSuccess {Number} combination_list.min_instalment 最小剩餘期數
     * @apiSuccess {Float} combination_list.bargain_rate 增減價比率(%)
     * @apiSuccess {Float} combination_list.interest_rate 平均年表利率(%)
     * @apiSuccess {Number} combination_list.accounts_receivable 整包應收帳款
	 
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id": 17,
     * 				"amount": 4010,
     * 				"principal": 5000,
     * 				"interest": 6,
     * 				"delay_interest": 0,
     * 				"bargain_rate": -19.9,
     * 				"instalment": 18,
     * 				"combination": 2,
     * 				"expire_time": 1547913599,
     * 				"accounts_receivable": 5398,
     * 				"target": {
     * 					"id": 9,
     * 					"target_no": "STN2019011414213",
     * 					"product_id": 1,
     * 					"credit_level": 3,
     * 					"user_id": 19,
     * 					"loan_amount": 5000,
     * 					"interest_rate": 7,
     * 					"instalment": 3,
     * 					"repayment": 1,
     * 					"delay": 0,
     * 					"delay_days": 0,
     * 					"reason": "",
     * 					"remark": "",
     * 					"status": 5,
     * 					"sub_status": 0,
     * 					"created_at": 1547444954,
     * 					"user": {
     * 						"sex": "M",
     * 						"age": 30
     * 					}
     * 				}
     * 			}
     * 			],
     * 			"combination_list": [
     * 			{
     * 				"id": 2,
     * 				"combination_no": "PKG1547810358209546",
     * 				"password": false,
     * 				"count": 3,
     * 				"amount": 12028,
     * 				"principal": 15000,
     * 				"interest": 16,
     * 				"max_instalment": 18,
     * 				"min_instalment": 3,
     * 				"delay_interest": 0,
     * 				"bargain_rate": -19.9,
     * 				"interest_rate": 8.56,
     * 				"accounts_receivable": 15626
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     */
	 	
	public function list_get()
    {
		$input 			= $this->input->get();
		$list			= [];
		$combination_list = [];
		$combination_ids = [];
		$product_list 	= $this->config->item('product_list');
		$orderby 		= isset($input['orderby'])&&in_array($input['orderby'],array('credit_level','instalment','interest_rate'))?$input['orderby']:'';
		$sort			= isset($input['sort'])&&in_array($input['sort'],array('desc','asc'))?$input['sort']:'asc';
		$transfer 		= $this->transfer_lib->get_transfer_list();
		
		if(!empty($transfer)){
			
			foreach($transfer as $key => $value){
				$target 	= $this->target_model->get($value->target_id);
				$user_info 	= $this->user_model->get($target->user_id); 
				$user		= [];
				if($user_info){
					$user = array(
						'sex' 	=> $user_info->sex,
						'age'	=> get_age($user_info->birthday),
					);
				}
				
				$target_info = [
					'id' 				=> intval($target->id),
					'target_no' 		=> $target->target_no,
					'product_id' 		=> intval($target->product_id),
					'credit_level' 		=> intval($target->credit_level),
					'user_id' 			=> intval($target->user_id),
					'loan_amount' 		=> intval($target->loan_amount),
					'interest_rate' 	=> intval($target->interest_rate),
					'instalment' 		=> intval($target->instalment),
					'repayment' 		=> intval($target->repayment),
					'delay' 			=> intval($target->delay),
					'delay_days' 		=> intval($target->delay_days),
					'reason' 			=> $target->reason,
					'remark' 			=> $target->remark,
					'status' 			=> intval($target->status),
					'sub_status' 		=> intval($target->sub_status),
					'created_at' 		=> intval($target->created_at),
					'user' 				=> $user,
				];
				
				$list[] 	= [
					'id'				=> intval($value->id),
					'amount'			=> intval($value->amount),
					'principal'			=> intval($value->principal),
					'interest'			=> intval($value->interest),
					'delay_interest'	=> intval($value->delay_interest),
					'bargain_rate'		=> floatval($value->bargain_rate),
					'instalment'		=> intval($value->instalment),
					'combination'		=> intval($value->combination),
					'expire_time'		=> intval($value->expire_time),
					'accounts_receivable'	=> intval($value->accounts_receivable),
					'target'			=> $target_info,
				];
				if($value->combination > 0){
					$combination_ids[$value->combination] = $value->combination;
				}
			}
			
			if(!empty($combination_ids)){
				$this->load->model('loan/transfer_combination_model');
				$combinations = $this->transfer_combination_model->get_many($combination_ids);
				if($combinations){
					foreach($combinations as $key => $value){
						$combination_list[] 	= [
							'id'				=> intval($value->id),
							'combination_no'	=> $value->combination_no,
							'password'			=> empty($value->password)?false:true,
							'count'				=> intval($value->count),
							'amount'			=> intval($value->amount),
							'principal'			=> intval($value->principal),
							'interest'			=> intval($value->interest),
							'max_instalment'	=> intval($value->max_instalment),
							'min_instalment'	=> intval($value->min_instalment),
							'delay_interest'	=> intval($value->delay_interest),
							'bargain_rate'		=> floatval($value->bargain_rate),
							'interest_rate'		=> floatval($value->interest_rate),
							'accounts_receivable'	=> intval($value->accounts_receivable),
						];
					}
				}
			}
			
			
			if(!empty($orderby) && !empty($sort) && !empty($list)){
				$num = count($list);
				for($i = 0 ; $i < $num ; $i++){
					for ($j=$i+1;$j<$num;$j++) {
						switch($orderby){
							case 'credit_level': 
								$a = $list[$i]['target']['credit_level'];
								$b = $list[$j]['target']['credit_level'];
								break;
							case 'instalment': 
								$a = $list[$i]['instalment'];
								$b = $list[$j]['instalment'];
								break;
							case 'interest_rate': 
								$a = $list[$i]['target']['interest_rate'];
								$b = $list[$j]['target']['interest_rate'];
								break;
							default:
								break;
						}
						if ($sort=='desc') {
							if( $a > $b ){
								$tmp      = $list[$i];
								$list[$i] = $list[$j];
								$list[$j] = $tmp;
							}
						}else{
							if( $a < $b ){
								$tmp      = $list[$i];
								$list[$i] = $list[$j];
								$list[$j] = $tmp;
							}
						}
					}
				}
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ,'combination_list' => $combination_list] ));
    }

	/**
     * @api {get} /v2/transfer/info/:id 出借方 取得債權標的資訊
	 * @apiVersion 0.2.0
	 * @apiName GetTransferInfo
     * @apiGroup Transfer
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id 投資ID
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Transfer ID
	 * @apiSuccess {Number} amount 價金
	 * @apiSuccess {Number} principal 剩餘本金
	 * @apiSuccess {Number} interest 已發生利息
	 * @apiSuccess {Number} delay_interest 已發生延滯利息
	 * @apiSuccess {Float} bargain_rate 增減價比率(%)
	 * @apiSuccess {Number} instalment 剩餘期數
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} accounts_receivable 應收帳款
	 * @apiSuccess {String} contract 債轉合約
	 * @apiSuccess {Object} target 原案資訊
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {Number} target.product_id 產品ID
	 * @apiSuccess {Number} target.credit_level 信用指數
	 * @apiSuccess {Number} target.user_id User ID
	 * @apiSuccess {Number} target.loan_amount 核准金額
	 * @apiSuccess {Number} target.interest_rate 核可利率
	 * @apiSuccess {Number} target.instalment 期數
	 * @apiSuccess {Number} target.repayment 還款方式
	 * @apiSuccess {Number} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} target.delay_days 逾期天數
	 * @apiSuccess {String} target.reason 借款原因
	 * @apiSuccess {String} target.remark 備註
	 * @apiSuccess {Number} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Number} target.created_at 申請日期
	 * @apiSuccess {Object} target.user 借款人基本資訊
	 * @apiSuccess {String} target.user.name 姓名
	 * @apiSuccess {String} target.user.id_number 身分證字號
	 * @apiSuccess {Number} target.user.age 年齡
	 * @apiSuccess {String} target.user.sex 性別 F/M
	 * @apiSuccess {Object} target.certification 借款人認證完成資訊


     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id": 3,
     * 			"amount": 5002,
     * 			"principal": 5000,
     * 			"interest": 2,
     * 			"delay_interest": 0,
     * 			"bargain_rate": 0,
     * 			"instalment": 3,
     * 			"combination": 0,
     * 			"expire_time": 1547654399,
     * 			"accounts_receivable": 5145,
     * 			"contract": "我是合約",
     * 			"target": {
     * 				"id": 9,
     * 				"target_no": "STN2019011414213",
     * 				"product_id": 1,
     * 				"credit_level": 3,
     * 				"user_id": 19,
     * 				"loan_amount": 5000,
     * 				"interest_rate": 7,
     * 				"instalment": 3,
     * 				"repayment": 1,
     * 				"delay": 0,
     * 				"delay_days": 0,
     * 				"reason": "",
     * 				"remark": "",
     * 				"status": 5,
     * 				"sub_status": 0,
     * 				"created_at": 1547444954,
     * 				"user": {
     * 					"name": "你**",
     * 					"id_number": "A1085*****",
     * 					"sex": "M",
     * 					"age": 30,
     * 					"company_name": "國立政治大學"
     * 				},
     * 				"certification": [
     * 					{
     * 						"id": 1,
     * 						"alias": "idcard",
     * 						"name": "實名認證",
     * 						"status": 1,
     * 						"description": "驗證個人身份資訊",
     * 						"user_status": 1
     * 					},
     * 					{
     * 						"id": 2,
     * 						"alias": "student",
     * 						"name": "學生身份認證",
     * 						"status": 1,
     * 						"description": "驗證學生身份",
     * 						"user_status": 1
     * 					}
     * 				]
     * 			}
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
	 
	public function info_get($transfer_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$transfer 			= $this->transfer_lib->get_transfer($transfer_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= [];
		if($transfer && in_array($transfer->status,[0,1])){
			
			$target 		= $this->target_model->get($transfer->target_id);
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

			$contract_data 	= $this->contract_lib->get_contract($transfer->contract_id);
			$contract 		= isset($contract_data['content'])?$contract_data['content']:'';
			$target_info = [
				'id' 				=> intval($target->id),
				'target_no' 		=> $target->target_no,
				'product_id' 		=> intval($target->product_id),
				'credit_level' 		=> intval($target->credit_level),
				'user_id' 			=> intval($target->user_id),
				'loan_amount' 		=> intval($target->loan_amount),
				'interest_rate' 	=> intval($target->interest_rate),
				'instalment' 		=> intval($target->instalment),
				'repayment' 		=> intval($target->repayment),
				'delay' 			=> intval($target->delay),
				'delay_days' 		=> intval($target->delay_days),
				'reason' 			=> $target->reason,
				'remark' 			=> $target->remark,
				'status' 			=> intval($target->status),
				'sub_status' 		=> intval($target->sub_status),
				'created_at' 		=> intval($target->created_at),
				'user' 				=> $user,
				'certification' 	=> $certification,
			];

			$data 	= [
				'id'				=> intval($transfer->id),
				'amount'			=> intval($transfer->amount),
				'principal'			=> intval($transfer->principal),
				'interest'			=> intval($transfer->interest),
				'delay_interest'	=> intval($transfer->delay_interest),
				'bargain_rate'		=> floatval($transfer->bargain_rate),
				'instalment'		=> intval($transfer->instalment),
				'combination'		=> intval($transfer->combination),
				'expire_time'		=> intval($transfer->expire_time),
				'accounts_receivable'=> intval($transfer->accounts_receivable),
				'contract'			=> $contract,
				'target'			=> $target_info
			];
			
			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => TRANSFER_NOT_EXIST ));
    }
	
	/**
     * @api {post} /v2/transfer/apply 出借方 申請債權收購
	 * @apiVersion 0.2.0
	 * @apiName PostTransferApply
     * @apiGroup Transfer
	 *
	 * @apiDescription 可收購多筆，若為整包債轉，一次只能單筆，否則回覆債權轉讓標的不存在
	 *
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} transfer_ids 投資IDs IDs ex: 1,3,10,21
	 * @apiParam {String{4,12}} [password] 整包債轉密碼
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
     * @apiError 804 雙方不可同使用者
     * @apiErrorExample {Object} 804
     *     {
     *       "result": "ERROR",
     *       "error": "804"
     *     }
     *
	 * @apiError 809 債權轉讓標的不存在
     * @apiErrorExample {Object} 809
     *     {
     *       "result": "ERROR",
     *       "error": "809"
     *     }
	 *
     * @apiError 810 已申請收購
     * @apiErrorExample {Object} 810
     *     {
     *       "result": "ERROR",
     *       "error": "810"
     *     }
	 *
     * @apiError 815 整包債權密碼錯誤
     * @apiErrorExample {Object} 815
     *     {
     *       "result": "ERROR",
     *       "error": "815"
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
		$param		= [];
		$password 	= isset($input['password'])?$input['password']:'';
		//必填欄位
		
		if (empty($input['transfer_ids'])) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		$transfer_ids 	= explode(',',$input['transfer_ids']);
		$count 			= count($transfer_ids);
		if(!empty($transfer_ids)){
			foreach($transfer_ids as $key => $id){
				$id = intval($id);
				if(intval($id)<=0 ){
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
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
		
		if($this->user_info->transaction_password==''){
			$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
		}
		
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$this->load->model('user/user_bankaccount_model');
		$bank_account = $this->user_bankaccount_model->get_by(array(
			'investor'	=> $investor,
			'status'	=> 1,
			'user_id'	=> $user_id,
			'verify'	=> 1
		));
		if(!$bank_account){
			$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
		}
		
		$transfer_investment = $this->transfer_investment_model->get_by([
			'transfer_id'	=> $transfer_ids,
			'user_id'		=> $user_id,
			'status'		=> [0,1,10]
		]);
		if($transfer_investment){
			$this->response(array('result' => 'ERROR','error' => TRANSFER_APPLY_EXIST ));
		}
		
		$transfers = $this->transfer_lib->get_transfer_many($transfer_ids);
		if(count($transfers)==count($transfer_ids)){
			if(count($transfers)>1){
				$param = [];
				foreach($transfers as $key => $value){
					if($value->combination==0 && $value->status == 0 ){
						$investment = $this->investment_model->get($value->investment_id);
						if( $user_id == $investment->user_id ){
							$this->response(array('result' => 'ERROR','error' => TARGET_SAME_USER ));
						}
						$param[] = [
							'transfer_id'	=> $value->id,
							'user_id'		=> $user_id,
							'amount'		=> $value->amount,
						];
					}else{
						$this->response(array('result' => 'ERROR','error' => TRANSFER_NOT_EXIST ));
					}
				}
				
				if($param){
					$insert = $this->transfer_investment_model->insert_many($param);
					if($insert){
						$this->response(array('result' => 'SUCCESS'));
					}else{
						$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
					}
				}
			}else{
				$param 		= [];
				$transfer 	= current($transfers);
				if($transfer->status==0){
					$investment = $this->investment_model->get($transfer->investment_id);
					if( $user_id == $investment->user_id ){
						$this->response(array('result' => 'ERROR','error' => TARGET_SAME_USER ));
					}
					
					if($transfer->combination==0){
						$param = [
							'transfer_id'	=> $transfer->id,
							'user_id'		=> $user_id,
							'amount'		=> $transfer->amount,
						];
						$insert = $this->transfer_investment_model->insert($param);
						if($insert){
							$this->response(array('result' => 'SUCCESS'));
						}else{
							$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
						}
					}else{
						$this->load->model('loan/transfer_combination_model');
						$combinations = $this->transfer_combination_model->get($transfer->combination);
						if(!empty($combinations->password) && $combinations->password != $password){
							$this->response(array('result' => 'ERROR','error' => TRANSFER_PASSWORD_ERROR ));
						}
						
						$param = [];
						$transfer_list = $this->transfer_lib->get_transfer_list(['status'=>0,'combination'=>$transfer->combination]);
						if($transfer_list){
							foreach($transfer_list as $key => $value){
								$param[] = [
									'transfer_id'	=> $value->id,
									'user_id'		=> $user_id,
									'amount'		=> $value->amount,
								];
							}
						}
						if($param){
							$insert = $this->transfer_investment_model->insert_many($param);
							if($insert){
								$this->response(array('result' => 'SUCCESS'));
							}else{
								$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
							}
						}
					}
				}
			}
		}
		$this->response(array('result' => 'ERROR','error' => TRANSFER_NOT_EXIST ));
    }
	
	/**
     * @api {get} /v2/transfer/applylist 出借方 債權申請紀錄列表
	 * @apiVersion 0.2.0
	 * @apiName GetTransferApplylist
     * @apiGroup Transfer
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
	 * @apiDescription 只顯示 待付款 待結標 待放款 狀態的申請	 
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Transfer Investments ID
	 * @apiSuccess {Number} amount 投標金額
	 * @apiSuccess {Number} loan_amount 得標金額
	 * @apiSuccess {String} contract 合約內容
	 * @apiSuccess {Number} status 投標狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款 9:流標 10:移轉成功
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {Object} transfer 債轉標的資訊
	 * @apiSuccess {Number} transfer.id Transfer ID
	 * @apiSuccess {Number} transfer.amount 價金
	 * @apiSuccess {Number} transfer.principal 剩餘本金
	 * @apiSuccess {Number} transfer.interest 已發生利息
	 * @apiSuccess {Number} transfer.delay_interest 已發生延滯利息
	 * @apiSuccess {Float} transfer.bargain_rate 增減價比率(%)
	 * @apiSuccess {Number} transfer.instalment 剩餘期數
	 * @apiSuccess {Number} transfer.expire_time 流標時間
	 * @apiSuccess {Number} transfer.accounts_receivable 應收帳款
	 * @apiSuccess {Object} target 標的資訊
	 * @apiSuccess {Number} target.id 產品ID
	 * @apiSuccess {String} target.target_no 標的案號
	 * @apiSuccess {Number} target.product_id 產品ID
	 * @apiSuccess {Number} target.user_id User ID
	 * @apiSuccess {Number} target.loan_amount 標的金額
	 * @apiSuccess {Number} target.credit_level 信用評等
	 * @apiSuccess {Number} target.interest_rate 年化利率
	 * @apiSuccess {String} target.reason 借款原因
	 * @apiSuccess {String} target.remark 備註
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
     * 				"id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
	 * 				"contract":"我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊",
     * 				"status":"0",
     * 				"created_at":"1520421572",
     * 				"transfer": {
     * 					"id": "1",
     * 					"amount": "1804233189",
     * 					"instalment": "5000",
     * 					"expire_time": "123456789"
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
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$param				= [
			'user_id'	=> $user_id,
			'status'	=> [0,1,2]
		];
		$transfer_investment = $this->transfer_investment_model->get_many_by($param);
		$list				= [];
		if(!empty($transfer_investment)){
			foreach($transfer_investment as $key => $value){
				$transfer_info 		= $this->transfer_lib->get_transfer($value->transfer_id);
				$transfer 	= [
					'id'				=> intval($transfer_info->id),
					'amount'			=> intval($transfer_info->amount),
					'principal'			=> intval($transfer_info->principal),
					'interest'			=> intval($transfer_info->interest),
					'delay_interest'	=> intval($transfer_info->delay_interest),
					'bargain_rate'		=> floatval($transfer_info->bargain_rate),
					'instalment'		=> intval($transfer_info->instalment),
					'combination'		=> intval($transfer_info->combination),
					'expire_time'		=> intval($transfer_info->expire_time),
					'accounts_receivable' => intval($transfer_info->accounts_receivable),
				];
			
				$target_info = $this->target_model->get($transfer_info->target_id);
				$target = array(
					'id'			=> intval($target_info->id),
					'target_no'		=> $target_info->target_no,
					'product_id'	=> intval($target_info->product_id),
					'user_id' 		=> intval($target_info->user_id),
					'loan_amount'	=> intval($target_info->loan_amount),
					'credit_level' 	=> intval($target_info->credit_level),
					'interest_rate' => intval($target_info->interest_rate),
					'reason' 		=> $target_info->reason,
					'remark' 		=> $target_info->remark,
					'instalment' 	=> intval($target_info->instalment),
					'repayment' 	=> intval($target_info->repayment),
					'expire_time'	=> intval($target_info->expire_time),
					'invested'		=> intval($target_info->invested),
					'status'		=> intval($target_info->status),
					'sub_status'	=> intval($target_info->sub_status),
				);
				
				$contract = '';
				if($value->contract_id){
					$contract_data = $this->contract_lib->get_contract($value->contract_id);
					$contract = $contract_data['content'];
				}
			
				$list[] = array(
					'id' 				=> intval($value->id),
					'amount' 			=> intval($value->amount),
					'loan_amount' 		=> in_array($value->status,[2,10])?intval($value->amount):0,
					'status' 			=> intval($value->status),
					'created_at' 		=> intval($value->created_at),
					'contract' 			=> $contract,
					'transfer' 			=> $transfer,
					'target' 			=> $target,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => ['list' => $list]));
    }
 
 	/**
     * @api {get} /v2/transfer/batch 出借方 智能收購
	 * @apiName GetTransferBatch
	 * @apiVersion 0.2.0
     * @apiGroup Transfer
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} budget 預算金額
	 * @apiParam {Number} [delay=0] 逾期標的 0:正常標的 1:逾期標的 default:0
     * @apiParam {Number} [user_id] 指定使用者ID
	 * @apiParam {Number} [interest_rate_s] 正常標的-利率區間下限(%)
     * @apiParam {Number} [interest_rate_e] 正常標的-利率區間上限(%)
     * @apiParam {Number} [instalment_s] 正常標的-剩餘期數區間下限(%)
     * @apiParam {Number} [instalment_e] 正常標的-剩餘期數區間上限(%)
     * @apiParam {String} [credit_level=all] 逾期標的-信用評等 全部：all 複選使用逗號隔開6,7,8
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} batch_id 智能收購ID
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均內部報酬率(%)
     * @apiSuccess {Object} debt_transfer_contract 合約列表
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
     * 			"debt_transfer_contract": [
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

		$filter 	= $input = $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$where		= array(
			'user_id !='	=> $user_id,
			'status'		=> 5,
		);
		
		//必填欄位
		if (empty($input['budget']) || intval($input['budget'])<=0) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}else{
			$budget = intval($input['budget']);
		}

		if (isset($input['delay']) && in_array($input['delay'],array(0,1))) {
			$delay 	= intval($input['delay']);
		}else{
			$delay 	= $input['delay'] = 0;
		}
		
		//檢查認證 NOT_VERIFIED
		if(empty($this->user_info->id_number) || $this->user_info->id_number==''){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
		}
		
		//檢查認證 NOT_VERIFIED_EMAIL
		if(empty($this->user_info->email) || $this->user_info->email==''){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
		}
		
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$this->load->model('user/user_bankaccount_model');
		$bank_account = $this->user_bankaccount_model->get_by(array(
			'investor'	=> $investor,
			'status'	=> 1,
			'user_id'	=> $user_id,
			'verify'	=> 1
		));
		if(!$bank_account){
			$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
		}
		
		if($this->user_info->transaction_password==''){
			$this->response(array('result' => 'ERROR','error' => NO_TRANSACTION_PASSWORD ));
		}

		if(get_age($this->user_info->birthday) < 20){
			$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
		}
		
		$transfer 	= $this->transfer_lib->get_transfer_list();
		if($transfer){
			if($delay){
				$where['delay_days >'] 	= GRACE_PERIOD;
				if(isset($input['credit_level']) && !empty($input['credit_level']) && $input['credit_level']!='all' ){
					$where['credit_level'] = explode(',',$input['credit_level']);
				}
			}else{
				
				$where['delay_days <='] = GRACE_PERIOD;
				if(isset($input['interest_rate_s']) && intval($input['interest_rate_s'])>=0){
					$where['interest_rate >='] = intval($input['interest_rate_s']);
				}
				
				if(isset($input['interest_rate_e']) && intval($input['interest_rate_e'])>0){
					$where['interest_rate <='] = intval($input['interest_rate_e']);
				}

				if(isset($input['instalment_s']) && intval($input['instalment_s'])>=0){
					if($transfer){
						foreach($transfer as $key => $value){
							if($value->instalment<intval($input['instalment_s'])){
								unset($transfer[$key]);
							}
						}
					}
				}
				
				if(isset($input['instalment_e']) && intval($input['instalment_e'])>0){
					if($transfer){
						foreach($transfer as $key => $value){
							if($value->instalment>intval($input['instalment_e'])){
								unset($transfer[$key]);
							}
						}
					}
				}
			}
			if($transfer){
				$investment = $this->investment_model->get_many_by(array(
					'user_id'			=> $user_id,
					'status'			=> 3,
					'transfer_status'	=> 1
				));
				if($investment){
					
					$investment_ids = array();
					foreach($investment as $key => $value){
						$investment_ids[] = $value->id;
					}
					foreach($transfer as $key => $value){
						if(in_array($value->investment_id,$investment_ids)){
							unset($transfer[$key]);
						}
					}
				}
			}
			
			if($transfer && isset($input['user_id']) && intval($input['user_id'])>0){
				$investment = $this->investment_model->get_many_by(array('user_id'=>$input['user_id'],'status'=>3,'transfer_status'=>1));
				$investment_ids = array();
				if($investment){
					foreach($investment as $key => $value){
						$investment_ids[] = $value->id;
					}
				}
				
				foreach($transfer as $key => $value){
					if(!in_array($value->investment_id,$investment_ids)){
						unset($transfer[$key]);
					}
				}

			}
			
			if($transfer){
				$transfer_investment = $this->transfer_investment_model->get_many_by(array('user_id'=>$user_id,'status'=>array(0,1,10)));
				if($transfer_investment){
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
				
				$where['id'] 	= $target_ids;
				$targets = $this->target_model->get_many_by($where);
				if($targets){
					$target_ids 	= array();
					$target_list 	= array();
					foreach($targets as $key => $value){
						$target_ids[] = $value->id;
						$target_list[$value->id] = $value;
					}
					
					$where['budget'] = $budget;
					$data = array(
						'total_amount' 		=> 0,
						'total_count' 		=> 0,
						'max_instalment' 	=> 0,
						'min_instalment' 	=> 0,
						'XIRR' 				=> 0,
						'batch_id' 			=> '',
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
								$contract_data 	= $this->contract_lib->get_contract($value->contract_id);
								$data['debt_transfer_contract'][] = $contract_data?$contract_data['content']:'';
								$content[] = $value->id;
								$target = $target_list[$value->target_id];
								$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target->instalment,$target->interest_rate,$target->loan_date,$target->repayment);
								$data['XIRR'] += $amortization_schedule['XIRR'];
							}
						}
					}
					if($data['total_count']){
						$param = array(
							'user_id'	=> $user_id,
							'type'		=> 1,
							'filter'	=> json_encode($filter),
							'content'	=> json_encode($content),
						);
						$this->load->model('loan/batch_model');
						$batch_id = $this->batch_model->insert($param);
						if($batch_id){
							$data['XIRR'] = round($data['XIRR']/$data['total_count'] ,2);
							$data['batch_id'] = $batch_id;
							$this->response(array('result' => 'SUCCESS','data' => $data));
						}else{
							$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
						}
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
			'debt_transfer_contract' => array(),
		)));
    }
	
	/**
     * @api {post} /v2/transfer/batch/:batch_id 出借方 智能收購確認
	 * @apiVersion 0.2.0
	 * @apiName PostTransferBatch
     * @apiGroup Transfer
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} batch_id 智能收購ID
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均內部報酬率(%)
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
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse InputError
	 * @apiUse NotInvestor
	 *
	 * @apiError 811 智能收購不存在
     * @apiErrorExample {Object} 811
     *     {
     *       "result": "ERROR",
     *       "error": "811"
     *     }
	 *
	 * @apiError 812 對此智能收購無權限
     * @apiErrorExample {Object} 812
     *     {
     *       "result": "ERROR",
     *       "error": "812"
     *     }
     */
	 
	public function batch_post($batch_id)
    {
		$input 				= $this->input->post(NULL, TRUE);
		$this->load->model('loan/batch_model');
		if(!$batch_id){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		$user_id 			= $this->user_info->id;
		$batch 				= $this->batch_model->get($batch_id);
		if($batch && $batch->status==0 && $batch->type==1){
			if($batch->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => BATCH_NO_PERMISSION ));
			}
			
			$transfer_ids 	= json_decode($batch->content,true);
			$transfer 		= $this->transfer_lib->get_transfer_list(array('id'=>$transfer_ids,'status'=>0));
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
						$investments = $this->transfer_investment_model->get_by(array(
							'transfer_id'	=> $value->id,
							'user_id'		=> $user_id,
							'status'		=> array(0,1,10)
						));
						if(!$investments){
							$param = array(
								'user_id' 		=> $user_id,
								'transfer_id' 	=> $value->id,
								'amount' 		=> $value->amount,
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
								$data['XIRR'] += $amortization_schedule['XIRR'];
							}
						}
					}
				}
				$data['XIRR'] = $data['total_count']>0?round($data['XIRR']/$data['total_count'] ,2):0;
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
