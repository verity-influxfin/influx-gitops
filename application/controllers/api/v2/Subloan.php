<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Subloan extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->library('Subloan_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }

			//只限借款人
			if($tokenData->investor != 0){
				$this->response(array('result' => 'ERROR','error' => IS_INVERTOR ));
			}

			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
			}

			if($this->user_info->block_status != 0){
				$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
			}

			//暫不開放法人
			if(isset($tokenData->company) && $tokenData->company != 0 ){
				$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
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
     * @api {get} /v2/subloan/preapply/:id 借款方 產品轉換前資訊
	 * @apiVersion 0.2.0
	 * @apiName GetSubloanPreapply
     * @apiGroup Subloan
	 * @apiParam {Number} id Targets ID
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} amount 金額
	 * @apiSuccess {Object} instalment 期數
	 * @apiSuccess {Object} repayment 還款方式
	 * @apiSuccess {Number} legal_collection 法催中
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 	       "amount": 51493,
     * 	       "instalment": [
     * 	       	{
     * 	       		"name": "3期",
     * 	       		"value": 3
     * 	       	},
     * 	       	{
     * 	       		"name": "6期",
     * 	       		"value": 6
     * 	       	},
     * 	       	{
     * 	       		"name": "12期",
     * 	       		"value": 12
     * 	       	},
     * 	       	{
     * 	       		"name": "18期",
     * 	       		"value": 18
     * 	       	},
     * 	       	{
     * 	       		"name": "24期",
     * 	       		"value": 24
     * 	       	}
     * 	       ],
     * 	       "repayment": {
     * 	       		"1": {
     * 	       			"name": "等額本息",
     * 	       			"value": 1
     * 	       		},
     * 	       		"2": {
     * 	       			"name": "先息後本",
     * 	       			"value": 2
     * 	       		}
     * 	       },
	 *         "legal_collection": 0,
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
	 *
     * @apiError 407 目前狀態無法完成此動作(需逾期且過寬限期)
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 903 已申請提前還款或產品轉換
     * @apiErrorExample {Object} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
	public function preapply_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$data				= array();
        $subloan_keyword			= $this->config->item('action_Keyword')[0];

		if(!empty($target) && $target->status == 5 ){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

            $this->load->library('target_lib');
            // APM 要求調整 target.sub_status IN (8,9,10)
            if ( ! in_array($target->sub_status, [0, 8, TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET]) && $this->target_lib->is_sub_loan($target->target_no) === FALSE)
            {
                $this->response(array('result' => 'ERROR','error' => TARGET_HAD_SUBSTATUS ));
            }

			if($target->delay == 0 || $target->delay_days < GRACE_PERIOD){
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}

            //計算該user轉換產品次數
            $subloan_count = count($this->target_model->get_many_by(
                array(
                    'user_id'     => $user_id,
                    'status !='     => "9",
                    'remark like' => '%'.$subloan_keyword.'%'
                )
            ));

			$product_list 	= $this->config->item('product_list');
			$product 		= $product_list[$target->product_id];
            $subloan_count >= BALLOON_MORTGAGE_RULE?array_push($product['repayment'],2):null;

            //認證狀態
            $certification 	= [];
            $this->load->library('Certification_lib');
            $certification_list	= $this->certification_lib->get_status($this->user_info->id,$this->user_info->investor);
            if(!empty($certification_list)){
                foreach($certification_list as $k => $v){
                    if(in_array($k,$product_list[1]['certifications'])){
                        $certification[] = $v;
                    }
                }
            }

			$legal_collection = $this->investment_model->getLegalCollectionInvestment([
				't.id' => $target->id
			],[
				'legal_collection_at >=' => '1911-01-01',
				'status' => 3
			]);
			$legalCollection = 0;
			if(isset($legal_collection) && count($legal_collection)) {
				$legalCollection = 1;
			}

			$info 			= $this->subloan_lib->get_info($target);
			$data			= array(
				'amount' 		 => $info['total'],
				'instalment'	 => $product['instalment'],
				'repayment'		 => $product['repayment'],
                'certification' => $certification,
                'legal_collection' => $legalCollection,
			);

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

	/**
     * @api {post} /v2/subloan/apply/ 借款方 產品轉換申請
	 * @apiVersion 0.2.0
	 * @apiName PostSubloanApply
     * @apiGroup Subloan
	 * @apiParam {Number} target_id Target ID
	 * @apiParam {Number} instalment 申請期數
	 * @apiParam {Number} repayment 還款方式
	 *
	 * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS"
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse InsertError
	 * @apiUse IsCompany
	 *
	 *
     * @apiError 403 不支援此期數
     * @apiErrorExample {Object} 403
     *     {
     *       "result": "ERROR",
     *       "error": "403"
     *     }
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作(需逾期且過寬限期)
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 409 不支援此計息方式
     * @apiErrorExample {Object} 409
     *     {
     *       "result": "ERROR",
     *       "error": "409"
     *     }
	 *
     * @apiError 903 已申請提前還款或產品轉換
     * @apiErrorExample {Object} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
	public function apply_post()
    {
		$input 				= $this->input->post(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$param				= array('user_id'=> $user_id);
		$repayment_type 	= $this->config->item('repayment_type');
		//必填欄位
		$fields 	= ['target_id','instalment','repayment'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}

		isset($input['product_id'])?$param['product_id']=$input['product_id']:1;

		$target = $this->target_model->get($input['target_id']);
		if(!empty($target) && $target->status == TARGET_REPAYMENTING ){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			$legal_collection = $this->investment_model->getLegalCollectionInvestment([
				't.id' => $input['target_id']
			],[
				'legal_collection_at >=' => '1911-01-01',
				'status' => 3
			]);
			if(isset($legal_collection) && count($legal_collection)) {
				$this->response(array('result' => 'ERROR','error' => TARGET_IN_LEGAL_COLLECTION ));
			}

            $this->load->library('target_lib');
            if ( ! in_array($target->sub_status, [TARGET_SUBSTATUS_NORNAL, TARGET_SUBSTATUS_SUBLOAN_TARGET, TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET]) &&
                $this->target_lib->is_sub_loan($target->target_no) === FALSE
            )
            {
                $this->response(array('result' => 'ERROR','error' => TARGET_HAD_SUBSTATUS ));
            }

			if($target->delay == 0 || $target->delay_days < GRACE_PERIOD){
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}

			$product_list 	= $this->config->item('product_list');
			$product 		= $product_list[$target->product_id];
			if(!in_array($input['instalment'],$product['instalment'])){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_INSTALMENT_ERROR ));
			}

			if(!isset($repayment_type[$input['repayment']])){
				$this->response(array('result' => 'ERROR','error' => PRODUCT_REPAYMENT_ERROR ));
			}

			$target_id = $this->subloan_lib->apply_subloan($target,$param);
			if($target_id){
                $this->load->library('Certification_lib');
                $this->certification_lib->expire_certification($user_id);
				$this->response(array('result' => 'SUCCESS','data' => [
                    'target_id' => intval($target_id),
                ]));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

	/**
     * @api {get} /v2/subloan/applyinfo/:id 借款方 產品轉換紀錄資訊
	 * @apiVersion 0.2.0
	 * @apiName GetSubloanApplyinfo
     * @apiGroup Subloan
	 * @apiParam {Number} id Targets ID
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} target_id Target ID
	 * @apiSuccess {String} amount 產品轉換金額
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 計息方式
	 * @apiSuccess {String} settlement_date 結息日
	 * @apiSuccess {String} status 產品轉換狀態 0:待簽約 1:轉貸中 2:成功 8:已取消 9:申請失敗
	 * @apiSuccess {String} created_at 申請日期
	 * @apiSuccess {Object} subloan_target 產品轉換產生的Target
	 * @apiSuccess {String} subloan_target.id Target ID
	 * @apiSuccess {String} subloan_target.target_no 案號
	 * @apiSuccess {String} subloan_target.product_id Product ID
	 * @apiSuccess {String} subloan_target.user_id User ID
	 * @apiSuccess {String} subloan_target.amount 申請金額
	 * @apiSuccess {String} subloan_target.loan_amount 核准金額
	 * @apiSuccess {String} subloan_target.platform_fee 平台服務費
	 * @apiSuccess {String} subloan_target.interest_rate 核可利率
	 * @apiSuccess {String} subloan_target.instalment 期數
	 * @apiSuccess {String} subloan_target.repayment 計息方式
	 * @apiSuccess {String} subloan_target.remark 備註
	 * @apiSuccess {String} subloan_target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} subloan_target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} subloan_target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {String} subloan_target.created_at 申請日期
	 * @apiSuccess {String} subloan_target.contract 合約內容
	 * @apiSuccess {Object} subloan_target.product 產品資訊
	 * @apiSuccess {String} subloan_target.product.name 產品名稱
	 * @apiSuccess {Object} subloan_target.amortization_schedule 預計還款計畫(簽約後不出現)
	 * @apiSuccess {String} subloan_target.amortization_schedule.amount 借款金額
	 * @apiSuccess {String} subloan_target.amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} subloan_target.amortization_schedule.rate 年利率
	 * @apiSuccess {String} subloan_target.amortization_schedule.date 起始時間
	 * @apiSuccess {String} subloan_target.amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} subloan_target.amortization_schedule.leap_year 是否為閏年
	 * @apiSuccess {String} subloan_target.amortization_schedule.year_days 本年日數
	 * @apiSuccess {String} subloan_target.amortization_schedule.XIRR XIRR
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule 還款計畫
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.instalment 第幾期
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.repayment_date 還款日
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.days 本期日數
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.remaining_principal 剩餘本金
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {String} subloan_target.amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {String} subloan_target.amortization_schedule.total 還款總計
	 * @apiSuccess {String} subloan_target.amortization_schedule.total.principal 本金
	 * @apiSuccess {String} subloan_target.amortization_schedule.total.interest 利息
	 * @apiSuccess {String} subloan_target.amortization_schedule.total.total_payment 加總
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"target_id":"1",
     * 			"amount":"53651",
     * 			"instalment":"3期",
     * 			"repayment":"先息後本",
     * 			"settlement_date":"2018-05-26",
     * 			"status":"0",
     * 			"created_at":"1527151277",
	 * 			"subloan_target": {
     * 				"id":"35",
     * 				"target_no": "1805247784",
     * 				"product_id":"1",
     * 				"user_id":"1",
     * 				"amount":"53651",
     * 				"loan_amount":"53651",
     * 				"platform_fee":"1610",
     * 				"interest_rate":"9",
     * 				"instalment":"3期",
     * 				"repayment":"先息後本",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"1",
     * 				"sub_status":"8",
     * 				"created_at":"1520421572",
  	 *       		"amortization_schedule": {
  	 *           		"amount": "12000",
  	 *           		"instalment": "6",
  	 *           		"rate": "9",
  	 *           		"date": "2018-04-17",
  	 *           		"total_payment": 2053,
  	 *           		"leap_year": false,
  	 *           		"year_days": 365,
  	 *           		"XIRR": 0.0939,
  	 *           		"schedule": {
 	 *                		"1": {
   	 *                  		"instalment": 1,
   	 *                  		"repayment_date": "2018-06-10",
   	 *                  		"days": 54,
   	 *                  		"remaining_principal": "12000",
   	 *                  		"principal": 1893,
   	 *                  		"interest": 160,
   	 *                  		"total_payment": 2053
   	 *              		},
   	 *              		"2": {
  	 *                   		"instalment": 2,
   	 *                  		"repayment_date": "2018-07-10",
   	 *                  		"days": 30,
  	 *                   		"remaining_principal": 10107,
  	 *                   		"principal": 1978,
  	 *                   		"interest": 75,
 	 *                    		"total_payment": 2053
  	 *               		},
   	 *              		"3": {
 	 *                    		"instalment": 3,
 	 *                    		"repayment_date": "2018-08-10",
 	 *                    		"days": 31,
 	 *                    		"remaining_principal": 8129,
  	 *                   		"principal": 1991,
  	 *                   		"interest": 62,
 	 *                    		"total_payment": 2053
 	 *                		}
 	 *            		},
  	 *           		"total": {
 	 *                		"principal": 12000,
 	 *                		"interest": 391,
 	 *                		"total_payment": 12391
	 *            		}
	 *        		}
     * 			}
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 904 尚未申請產品轉換
     * @apiErrorExample {Object} 904
     *     {
     *       "result": "ERROR",
     *       "error": "904"
     *     }
     */
	public function applyinfo_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$data				= array();
		$subloan_target		= array();
		if(!empty($target)){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			$subloan = $this->subloan_lib->get_subloan(false,$target);
			if(!$subloan){
				$this->response(array('result' => 'ERROR','error' => TARGET_SUBLOAN_NOT_EXIST ));
			}

			$data = array(
				'target_id'			=> $subloan->target_id,
				'amount'			=> $subloan->amount,
				'instalment'		=> $subloan->instalment,
				'repayment'			=> $subloan->repayment,
				'settlement_date'	=> $subloan->settlement_date,
				'status'			=> $subloan->status,
				'created_at'		=> $subloan->created_at,
			);

			$new_target  = $this->target_model->get($subloan->new_target_id);

            $product_list 		= $this->config->item('product_list');
            $product 			= $product_list[$target->product_id];
            $certification		= [];
            $this->load->library('Certification_lib');
            $this->load->library('loanmanager/product_lib');
            $certification_list	= $this->certification_lib->get_status($user_id,0);
            $product_certs = $this->product_lib->get_product_certs_by_product_id($target->product_id, $target->sub_product_id, [ASSOCIATES_CHARACTER_REGISTER_OWNER]);
            if(!empty($certification_list)){
                foreach($certification_list as $key => $value){
                    if (in_array($key, $product_certs))
                    {
                        $certification[] = $value;
                    }
                }
            }

			$amortization_schedule = array();
			if($new_target->status==1){
				$amortization_schedule = $this->financial_lib->get_amortization_schedule($new_target->loan_amount,$new_target);
			}

			$contract = '';
			if($new_target->contract_id){
				$this->load->library('Contract_lib');
				$contract_data = $this->contract_lib->get_contract($new_target->contract_id);
				$contract = $contract_data['content'];
			}

			$fields = $this->target_model->detail_fields;
			foreach($fields as $field){
				$subloan_target[$field] = isset($new_target->$field)?$new_target->$field:'';
			}

            // 特定欄位需強制轉型
            $integer_fields = $this->target_model->integer_fields;
            foreach ($integer_fields as $field)
            {
                if ( ! isset($subloan_target[$field]))
                {
                    continue;
                }
                $subloan_target[$field] = (int)$subloan_target[$field];
            }
            $subloan_target['certificate_status'] = intval($new_target->certificate_status);

			$subloan_target['contract'] 				= $contract;
			$subloan_target['amortization_schedule'] 	= $amortization_schedule;
			$data['subloan_target']						= $subloan_target;
            $data['certification']						= $certification;
			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

	/**
     * @api {post} /v2/subloan/signing 借款方 產品轉換簽約
	 * @apiVersion 0.2.0
	 * @apiName PostSubloanSigning
     * @apiGroup Subloan
	 * @apiParam {Number} target_id Targets ID
	 * @apiParam {file} person_image 本人照
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
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 206 人臉辨識不通過
     * @apiErrorExample {Object} 206
     *     {
     *       "result": "ERROR",
     *       "error": "206"
     *     }
	 *
     * @apiError 904 尚未申請產品轉換
     * @apiErrorExample {Object} 904
     *     {
     *       "result": "ERROR",
     *       "error": "904"
     *     }
	 *
     */
	public function signing_post()
    {
		$this->load->library('S3_upload');
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= array();

		//必填欄位
		$fields 	= ['target_id'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}

		//上傳檔案欄位
		$file_fields 	= ['person_image'];
		foreach ($file_fields as $field) {
			if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
				$image 	= $this->s3_upload->image($_FILES,$field,$user_id,'signing_target');
				if($image){
					$param[$field] = $image;
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}

		$target 	= $this->target_model->get($param['target_id']);
		if(!empty($target)){

			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			$subloan = $this->subloan_lib->get_subloan(false,$target);
			if(!$subloan){
				$this->response(array('result' => 'ERROR','error' => TARGET_SUBLOAN_NOT_EXIST ));
			}

			if($subloan->status == 0){
				$rs = $this->subloan_lib->signing_subloan($subloan,$param);
				if($rs){
					$this->response(array('result' => 'SUCCESS'));
				}
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}else{
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}

			$this->response(array('result' => 'ERROR','error' => PRODUCT_NOT_EXIST ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

	/**
     * @api {get} /v2/subloan/cancel/:id 借款方 取消產品轉換
	 * @apiVersion 0.2.0
	 * @apiName GetSubloanCancel
     * @apiGroup Subloan
	 * @apiParam {Number} id Targets ID
	 *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsInvestor
	 * @apiUse IsCompany
     *
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {Object} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {Object} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 904 尚未申請產品轉換
     * @apiErrorExample {Object} 904
     *     {
     *       "result": "ERROR",
     *       "error": "904"
     *     }
	 *
     */
	public function cancel_get($target_id)
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$target 	= $this->target_model->get($target_id);
		if(!empty($target)){

			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			$subloan = $this->subloan_lib->get_subloan(false,$target);
			if(!$subloan){
				$this->response(array('result' => 'ERROR','error' => TARGET_SUBLOAN_NOT_EXIST ));
			}

			if($subloan->status == 0 ){
				$this->subloan_lib->cancel_subloan($subloan);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => APPLY_STATUS_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }
}
