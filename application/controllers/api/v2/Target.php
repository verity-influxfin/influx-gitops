<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Target extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->library('Contract_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list','judicial_list','judicial_info'];
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
				$this->log_request_model->insert([
					'method' 	=> $this->request->method,
					'url'	 	=> $this->uri->uri_string(),
					'investor'	=> $tokenData->investor,
					'user_id'	=> $tokenData->id,
					'agent'		=> $tokenData->agent,
				]);
			}

			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;

            if (isset($tokenData->company) && $tokenData->company != 0)
            {
                $this->load->library('judicialperson_lib');
                $this->user_info->naturalPerson = $this->judicialperson_lib->getNaturalPerson($tokenData->id);
            }
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
	 * @apiSuccess {Number} repayment 計息方式
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
        $product_list = $this->config->item('product_list');
        $user_meta = new stdClass();

        if(!empty($target_list)){
            foreach($target_list as $key => $value){
				$user_info 	= $this->user_model->get($value->user_id);
				$user		= [];
				if($user_info){
                    $product = $product_list[$value->product_id];
                    $sub_product_id = $value->sub_product_id;
                    $product_name = $product['name'];
                    if($this->is_sub_product($product,$sub_product_id)){
                        $product = $this->trans_sub_product($product,$sub_product_id);
                        $product_name = $product['name'];
                    }

                    $age = get_age($user_info->birthday);
                    if ($product_list[$value->product_id]['identity'] == 1) {
                        $user_meta = $this->user_meta_model->get_by(['user_id' => $value->user_id, 'meta_key' => 'school_name']);
                        if (is_object($user_meta)) {
                            $user_meta->meta_value = preg_replace('/\(自填\)/', '', $user_meta->meta_value);
                        } else {
                            $user_meta = new stdClass();
                            $user_meta->meta_value = '未提供學校資訊';
                        }
                    } elseif ($product_list[$value->product_id]['identity'] == 2) {
                        $meta_info = $this->user_meta_model->get_many_by([
                            'user_id' => $value->user_id,
                            'meta_key' => ['job_company', 'diploma_name']
                        ]);
                        if ($meta_info) {
                            $job_company = ($meta_info[0]->meta_key == 'job_company'
                                ? $meta_info[0]->meta_value
                                : (isset($meta_info[1]) >= 2
                                    ? $meta_info[1]->meta_value
                                    : false));
                            $diploma_name = $meta_info[0]->meta_key == 'diploma_name'
                                ? $meta_info[0]->meta_value
                                : (isset($meta_info[1]) >= 2
                                    ? $meta_info[1]->meta_value
                                    : false);
                            $user_meta->meta_value = $job_company ? $job_company : $diploma_name;
                        } else {
                            $user_meta = new stdClass();
                            $user_meta->meta_value = '未提供相關資訊';
                        }
                    }

                    $user = array(
                        'sex' 			=> $user_info->sex,
                        'age'			=> $age,
                        'company_name'	=> $user_meta?$user_meta->meta_value:'',
                    );

                    $targetDatas = [];
                    $targetData = json_decode($value->target_data);
                    if($product['visul_id'] == 'DS2P1'){
                        $targetDatas = [
                            'brand' => $targetData->brand,
                            'name' => $targetData->name,
                            'selected_image' => $targetData->selected_image,
                            'purchase_time' => $targetData->purchase_time,
                            'factory_time' => $targetData->factory_time,
                            'product_description' => $targetData->product_description,
                        ];
                        foreach ($product['targetData'] as $skey => $svalue) {
                            if(in_array($skey,['car_photo_front_image','car_photo_back_image','car_photo_all_image','car_photo_date_image','car_photo_mileage_image'])){
                                if(isset($targetData->$key) && !empty($targetData->$key)){
                                    $pic_array = [];
                                    foreach ($targetData->$key as $svalue){
                                        preg_match('/image.+/', $svalue,$matches);
                                        $pic_array[] = FRONT_CDN_URL.'stmps/tarda/'.$matches[0];
                                    }
                                    $targetDatas[$key] = $pic_array;
                                }
                                else{
                                    $targetDatas[$key] = '';
                                }
                            }
                        }
                        $user = array(
                            'name' 			=> $user_info->name,
                            'id_number'		=> '',
                            'sex' 			=> '',
                            'age'			=> '',
                            'company_name'	=> '',
                            'tax_id'	=> $user_info->id_number,
                        );
                    }
				}

                $reason = $value->reason;
                $json_reason = json_decode($reason);
                if(isset($json_reason->reason)){
                    $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                }

                $param = [
                    'id' 				=> intval($value->id),
                    'target_no' 		=> $value->target_no,
                    'product_name' => $product_name,
                    'product_id' 		=> intval($value->product_id),
                    'sub_product_id' => intval($value->sub_product_id),
                    'credit_level' 		=> intval($value->credit_level),
                    'user_id' 			=> intval($value->user_id),
                    'user' 				=> $user,
                    'loan_amount' 		=> intval($value->loan_amount),
                    'interest_rate' 	=> floatval($value->interest_rate),
                    'instalment' 		=> intval($value->instalment),
                    'repayment' 		=> intval($value->repayment),
                    'expire_time' 		=> intval($value->expire_time),
                    'invested' 			=> intval($value->invested),
                    'reason' 			=> $reason,
                    'targetDatas' => $targetDatas,
                    'isTargetOpaque' => $sub_product_id == STAGE_CER_TARGET ? true : false,
                    'status' 			=> intval($value->status),
                    'sub_status' 		=> intval($value->sub_status),
                    'created_at' 		=> intval($value->created_at),
                ];

                isset($targetData->original_interest_rate) && $targetData->original_interest_rate != $value->interest_rate ? $param['is_rate_increase'] = true : '';

				$list[] = $param;
			}
		}

		$this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ] ));
    }

    // 法人標的列表
    // {
    //     "credit_level" : "3",
    //     "comp_type" : "中小企業",
    //     "comp_du_type" : "其他金融業",
    //     "comp_name" : "普惠金融科技股份有限公司",
    //     "target_no" : "SSM2021081991257",
    //     "reason" : "短期營運週轉",
    //     // 設立日期
    //     "comp_set_date" : "2017.12.31",
    //     // 實收資本額
    //     "comp_capital" : 1450,
    //     // 還款來源
    //     "payment_source" : "營業收入",
    //     // 擔保條件
    //     "smeg_guarantee_percent" : "信保基金9.5成",
    //     // 保證人
    //     "pr_name" : "祖法強",
    //     "loan_amount" : 1000000,
    //     "instalment" : 36,
    //     "repayment" : 1,
    //     "loan_rate" : 5,
    //     "status" : 504,
    // }
    // 暫時只有撈普匯微企e秒貸案件資料ˊ
    public function judicial_list_get(){
        $input 			= $this->input->get();
		$list			= [];
		$where			= array( 'status' => [504] );
		$orderby 		= isset($input['orderby'])&&in_array($input['orderby'],array('credit_level','instalment','interest_rate'))?$input['orderby']:'credit_level';
		$sort			= isset($input['sort'])&&in_array($input['sort'],array('desc','asc'))?$input['sort']:'asc';
		$target_list 	= $this->target_model->order_by($orderby,$sort)->get_many_by($where);
        // 資訊不在案件本身內容
        $out_of_target_info = [];

        $product_list = $this->config->item('product_list');
        if(!empty($target_list)){
            foreach($target_list as $key => $value){
                $product = isset($product_list[$value->product_id]) ? $product_list[$value->product_id] : [];
                if(!empty($product)){
                    $param = [];
                    $sub_product_id = $value->sub_product_id;
                    $out_of_target_info['product_name'] = $product['name'];
                    if($this->is_sub_product($product,$sub_product_id)){
                        $product = $this->trans_sub_product($product,$sub_product_id);
                        $out_of_target_info['product_name'] = $product['name'];
                    }

                    $json_reason = json_decode($value->reason);
                    if(isset($json_reason->reason)){
                        $value->reason = $json_reason->reason.' - '.$json_reason->reason_description;
                    }

                    // 撈普匯微企e秒貸貸資料
                    if($product['visul_id'] == 'J2'){
                        $this->load->library('mapping/sk_bank/msgno');
                		$response = $this->msgno->getSKBankInfoByTargetId($value->id);
                        if(!empty($response) && isset($response['data']['send_log']['request_content'])){
                            if(!empty($response['data']['send_log']['request_content'])){
                                $sk_bank_send_log = json_decode($response['data']['send_log']['request_content'],true);
                                $out_of_target_info['comp_type'] = isset($sk_bank_send_log['unencrypted']['Data']['CompType']) ? $sk_bank_send_log['unencrypted']['Data']['CompType'] : '';
                                $out_of_target_info['comp_du_type'] = isset($sk_bank_send_log['unencrypted']['Data']['CompDuType']) ? $sk_bank_send_log['unencrypted']['Data']['CompDuType']: '';
                                $out_of_target_info['comp_name'] = isset($sk_bank_send_log['unencrypted']['Data']['CompName']) ? $sk_bank_send_log['unencrypted']['Data']['CompName']: '';
                                $out_of_target_info['comp_set_date'] = isset($sk_bank_send_log['unencrypted']['Data']['CompSetDate']) ? $sk_bank_send_log['unencrypted']['Data']['CompSetDate']: '';
                                $out_of_target_info['comp_capital'] = isset($sk_bank_send_log['unencrypted']['Data']['CompCapital']) ? $sk_bank_send_log['unencrypted']['Data']['CompCapital']: '';
                                $out_of_target_info['pr_name'] = isset($sk_bank_send_log['unencrypted']['Data']['PrName']) ? $sk_bank_send_log['unencrypted']['Data']['PrName']: '';
                            }
                        }

                        // 撈新光回應資料
                        // TODO: APP案件列表要取得保證成數，故需尋訪所有 target_id 的 msg_no
                        $this->load->model('skbank/LoanTargetMappingMsgNo_model');
                        $loanTargetMappingInfo = $this->LoanTargetMappingMsgNo_model->order_by('id','desc')->limit(1)->get_by(['target_id' => $value->id, 'type' => 'text']);
                        if(!empty($loanTargetMappingInfo) && isset($loanTargetMappingInfo->msg_no)){
                            $this->load->model('skbank/LoanSendRequestLog_model');
                            $loanRequestLogInfo = $this->LoanSendRequestLog_model->get_by(['msg_no' => $loanTargetMappingInfo->msg_no]);
                            if(!empty($loanRequestLogInfo) && isset($loanRequestLogInfo->case_no)){
                                $this->load->model('skbank/LoanResult_model');
                                $loanResult = $this->LoanResult_model->get_by(['case_no' => $loanRequestLogInfo->case_no]);
                                if(!empty($loanResult) && isset($loanResult->smeg_guarantee_percent) && is_numeric($loanResult->smeg_guarantee_percent)){
                                    $out_of_target_info['smeg_guarantee_percent'] = isset($loanResult->smeg_guarantee_percent) ? '信保基金'.number_format($loanResult->smeg_guarantee_percent/10, 1, '.', '').'成' : '';
                                }
                            }
                        }
                    }

                    $param = [
                        'id' 				=> intval($value->id),
                        'target_no' 		=> $value->target_no,
                        'product_name' => isset($out_of_target_info['product_name']) ? $out_of_target_info['product_name'] : '',
                        'product_id' 		=> intval($value->product_id),
                        'sub_product_id' => intval($value->sub_product_id),
                        'credit_level' 		=> intval($value->credit_level),
                        'user_id' 			=> intval($value->user_id),
                        'loan_amount' 		=> intval($value->loan_amount),
                        'interest_rate' 	=> floatval($value->interest_rate),
                        'instalment' 		=> intval($value->instalment),
                        'repayment' 		=> intval($value->repayment),
                        'expire_time' 		=> intval($value->expire_time),
                        'invested' 			=> intval($value->invested),
                        'reason' 			=> $value->reason,
                        'isTargetOpaque' => $sub_product_id == STAGE_CER_TARGET ? true : false,
                        'status' 			=> intval($value->status),
                        'sub_status' 		=> intval($value->sub_status),
                        'created_at' 		=> intval($value->created_at),
                        'comp_type' => isset($out_of_target_info['comp_type']) ? $out_of_target_info['comp_type']: '',
                        'comp_du_type' => isset($out_of_target_info['comp_du_type']) ? $out_of_target_info['comp_du_type']: '',
                        'comp_name' => isset($out_of_target_info['comp_name']) ? $out_of_target_info['comp_name']: '',
                        'comp_set_date' => isset($out_of_target_info['comp_set_date']) ? $out_of_target_info['comp_set_date']: '',
                        'comp_capital' => isset($out_of_target_info['comp_capital']) && is_numeric($out_of_target_info['comp_capital']) ? intval($out_of_target_info['comp_capital']): '',
                        'pr_name' => isset($out_of_target_info['pr_name']) ? $out_of_target_info['pr_name']: '',
                        'smeg_guarantee_percent' => isset($out_of_target_info['smeg_guarantee_percent']) ? $out_of_target_info['smeg_guarantee_percent']: '',
                    ];

                    $list[] = $param;
                }
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
	 * @apiSuccess {Number} repayment 計息方式
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} invested 目前投標量
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {String} contract 合約內容
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
     *	{
     *		"result": "SUCCESS",
     *		"data": {
     *			"id": 24,
     *			"target_no": "STN2019011487405",
     *			"product_id": 1,
     *			"user_id": 19,
     *			"loan_amount": 5000,
     *			"credit_level": 3,
     *			"interest_rate": 7,
     *			"reason": "",
     *			"remark": "",
     *			"instalment": 3,
     *			"repayment": 1,
     *			"expire_time": 1548828283,
     *			"invested": 0,
     *			"status": 3,
     *			"sub_status": 0,
     *			"created_at": 1547445512,
     *			"contract": "借貸契約",
     *			"user": {
     *				"name": "你**",
     *				"id_number": "A1085*****",
     *				"sex": "M",
     *				"age": 30,
     *				"company_name": "國立政治大學"
     *			},
     *			"amortization_schedule": {
     *				"amount": 5000,
     *				"instalment": 3,
     *				"rate": 7,
     *				"date": "2019-01-30",
     *				"total_payment": 1687,
     *				"leap_year": false,
     *				"year_days": 365,
     *				"XIRR": 7.23,
     *				"schedule": {
     *					"1": {
     *						"instalment": 1,
     *						"repayment_date": "2019-03-10",
     *						"days": 39,
     *						"remaining_principal": 5000,
     *						"principal": 1650,
     *						"interest": 37,
     *						"total_payment": 1687
     *					},
     *					"2": {
     *						"instalment": 2,
     *						"repayment_date": "2019-04-10",
     *						"days": 31,
     *						"remaining_principal": 3350,
     *						"principal": 1667,
     *						"interest": 20,
     *						"total_payment": 1687
     *					},
     *					"3": {
     *						"instalment": 3,
     *						"repayment_date": "2019-05-10",
     *						"days": 30,
     *						"remaining_principal": 1683,
     *						"principal": 1683,
     *						"interest": 10,
     *						"total_payment": 1693
     *					}
     *				},
     *				"total": {
     *					"principal": 5000,
     *					"interest": 67,
     *					"total_payment": 5067
     *				}
     *			}
     *		}
     *	}
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
		$data				= [];
        $user_meta = '';

        if(!empty($target) && in_array($target->status,[3,4,504])){
            $this->load->library('loanmanager/product_lib');
            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            $product_name = $product['name'];
            if($this->is_sub_product($product,$sub_product_id)){
                $product = $this->trans_sub_product($product,$sub_product_id);
                    $product_name = $product['name'];
            }

            $target->investor = 1;
			$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target);

			$user_info 	= $this->user_model->get($target->user_id);
			$user		= [];
			if($user_info){
				$name 		= mb_substr($user_info->name,0,1,'UTF-8').'**';
				$id_number 	= strlen($user_info->id_number)==10?substr($user_info->id_number,0,5).'*****':'';
				$age  		= get_age($user_info->birthday);
                $user_meta = new stdClass();
                if($product['identity']==1){
					$user_meta 	            = $this->user_meta_model->get_by(['user_id'=>$target->user_id,'meta_key'=>'school_name']);
                    if(is_object($user_meta)){
                        $user_meta->meta_value =preg_replace('/\(自填\)/', '',$user_meta->meta_value);
                    }
                    else{
                        $user_meta->meta_value='未提供學校資訊';
                    }
				} elseif ($product_list[$target->product_id]['identity'] == 2) {
                    $meta_info = $this->user_meta_model->get_many_by([
                        'user_id' => $target->user_id,
                        'meta_key' => ['job_company', 'diploma_name']
                    ]);
                    if ($meta_info) {
                        $job_company = ($meta_info[0]->meta_key == 'job_company'
                            ? $meta_info[0]->meta_value
                            : (isset($meta_info[1]) >= 2
                                ? $meta_info[1]->meta_value
                                : false));
                        $diploma_name = $meta_info[0]->meta_key == 'diploma_name'
                            ? $meta_info[0]->meta_value
                            : (isset($meta_info[1]) >= 2
                                ? $meta_info[1]->meta_value
                                : false);
                        $user_meta->meta_value = $job_company ? $job_company : $diploma_name;
                    } else {
                        $user_meta->meta_value = '未提供相關資訊';
                    }
                }

				$user = array(
					'name' 			=> $name,
					'id_number'		=> $id_number,
					'sex' 			=> $user_info->sex,
					'age'			=> $age,
					'company_name'	=> isset($user_meta->meta_value)?$user_meta->meta_value:'',
				);
			}

			$targetDatas = [];
            $targetData = json_decode($target->target_data);
            if($product['visul_id'] == 'DS2P1'){
                $targetDatas = [
                    'brand' => $targetData->brand,
                    'name' => $targetData->name,
                    'selected_image' => $targetData->selected_image,
                    'purchase_time' => $targetData->purchase_time,
                    'factory_time' => $targetData->factory_time,
                    'product_description' => $targetData->product_description,
                ];
                foreach ($product['targetData'] as $key => $value) {
                    if(in_array($key,['car_photo_front_image','car_photo_back_image','car_photo_all_image','car_photo_date_image','car_photo_mileage_image'])){
                        if(isset($targetData->$key) && !empty($targetData->$key)){
                            $pic_array = [];
                            foreach ($targetData->$key as $svalue){
                                preg_match('/image.+/', $svalue,$matches);
                                $pic_array[] = FRONT_CDN_URL.'stmps/tarda/'.$matches[0];
                            }
                            $targetDatas[$key] = $pic_array;
                        }
                        else{
                            $targetDatas[$key] = '';
                        }
                    }
                }
                $user = array(
                    'name' 			=> $user_info->name,
                    'id_number'		=> '',
                    'sex' 			=> '',
                    'age'			=> '',
                    'company_name'	=> '',
                    'tax_id'	=> $user_info->id_number,
                );
            }

            $certification_list = [];
            $targetData_cer = isset($targetData->certification_id) ? $targetData->certification_id : false;
            if ($targetData_cer) {
                $this->load->model('user/user_certification_model');
                $this->load->library('Certification_lib');
                $certification = $this->config->item('certifications');
                $certifications = $this->user_certification_model->get_many_by([
                    'id' => $targetData_cer,
                    'user_id' => $target->user_id,
                ]);
                foreach ($certifications as $key => $value) {
                    $cur_cer[$value->certification_id] = $value;
                }
                $product_certs = $this->product_lib->get_product_certs_by_product_id($target->product_id, $target->sub_product_id, []);
                foreach ($product_certs as $key => $value) {
                    $cer = $certification[$value];
                    // 不顯示於 APP 的徵信項目
                    if (($cer['show'] ?? TRUE) == FALSE)
                    {
                        continue;
                    }
                    else if ( ! isset($cur_cer[$value]))
                    {
                        $cer['description'] = '未' . $cer['description'];
                        $cer['user_status'] = 2;
                        $cer['certification_id'] = null;
                        $cer['updated_at'] = null;
                    } else {
                        $description = false;
                        $contents = json_decode($cur_cer[$value]->content);
                        $liveAddress = "";
                        if( $value == 1){
                            $orderList = [['省', '市'], ['縣', '市'], ['鄉', '鎮', '市', '區'], ['村', '里'], ['鄰'], ['路']];
                            $count = 2;
                            $temp_address = $contents->address;
                            while($count) {
                                $orderIdx = 0;
                                while($count && $orderIdx < count($orderList)) {
                                    foreach ($orderList[$orderIdx] as $needle) {
                                        $pos = mb_stripos($temp_address, $needle);
                                        if ($pos !== false) {
                                            $count--;
                                            $liveAddress = $liveAddress . mb_substr($temp_address, 0, $pos+1);
                                            $temp_address = mb_substr($temp_address, $pos+1);
                                            break;
                                        }
                                    }
                                    $orderIdx++;
                                }
                                if($orderIdx == count($orderList))
                                    break;
                            }
                            $description .= '發證地點：' . $contents->id_card_place . '<br>發證時間：' . $contents->id_card_date . '<br>戶籍地址：' . $liveAddress;
                        } elseif( $value == 2){
                            isset($contents->pro_certificate) ? $description .= '有提供專業證書；' . str_replace(',','、', $contents->pro_certificate) : '';
                            $description .= '學門：' . $contents->major. '<br>系所：' . $contents->department . '<br>學制：' . $this->config->item('school_system')[$contents->system];
                        } elseif ($value == 3){
                            $description = '已驗證個人金融帳號';
                        } elseif ($value == 4){
                            $ig = $contents->instagram ?? NULL;
                            $ig_info = $ig->info ?? NULL;
                            $description .= 'FB：已綁定<br>Instagram' . '<br>貼文：' . ($ig_info->allPostCount ?? '') . '<br>追蹤者：' . ($ig_info->allFollowerCount ?? '') . '<br>追蹤中：' . ($ig_info->allFollowingCount ?? '') ;
                        } elseif ($value == 5){
                            $description = '已輸入父母作為緊急聯絡人';
                        } elseif ($value == 6){
                            $description = '已驗證常用電子信箱';
                        } elseif ($value == 7){
                            $financial_input = round((($contents->parttime ?? 0) + ($contents->allowance ?? 0) + ($contents->other_income ?? 0)) + (($contents->scholarship ?? 0) * 2) / 12);
                            $financial_output = round((($contents->restaurant ?? 0) + ($contents->transportation ?? 0) + ($contents->entertainment ?? 0) + ($contents->other_expense ?? 0)));
                            $description = '(自填) 總收入/月：'. $financial_input . '元<br>(自填) 總支出/月：' . $financial_output . '元';
                            isset($contents->labor_image) ? $description .= '有提供最近年度報稅扣繳憑證' : '';
                        } elseif ($value == 8){
                            $description = '最高學歷：' . preg_replace('/\(自填\)/', '', $contents->school) . '(' . $this->config->item('school_system')[$contents->system] . ')';
                        } elseif ($value == 9){
                            if(isset($contents->result)){
                                $info = reset($contents->result);

                                // 負債比
                                $repayment_cert = $cur_cer[CERTIFICATION_REPAYMENT_CAPACITY] ?? NULL;
                                $debt_ratio = NULL;
                                if ($repayment_cert)
                                {
                                    $debt_ratio = json_decode($repayment_cert->content ?? '[]', TRUE)['debt_to_equity_ratio'] ?? NULL;
                                }
                                
                                if(isset($info)) {
                                    $description = "負債比：" . ($debt_ratio ? $debt_ratio . "%" : '') .
                                        "<br>延遲繳款紀錄：" . ($info->creditCardHasDelay ?? '') .
                                        "<br>信用卡使用率：" . (isset($info->creditCardUseRate) ? $info->creditCardUseRate . "%" : '') .
                                        "<br>預借現金：" . ($info->cashAdvanced ?? '');
                                }
                            }
                        } elseif ($value == 10){
                            $employee_range 		  = $this->config->item('employee_range');
                            $seniority_range 		  = $this->config->item('seniority_range');
                            $industry_name 			  = $this->config->item('industry_name');
                            $description .= ('產業別：' . isset($contents->industry) ? $industry_name[$contents->industry] : '').
                                "<br>任職公司規模：".(isset($contents->employee) ? $employee_range[$contents->employee] : '').
                                "<br>投保月薪：" . ($contents->salary ?? '') . '元'.
                                "<br>現職年資：" . (isset($contents->job_seniority) ? $seniority_range[$contents->job_seniority] : '') .
                                "<br>總年資：" . (isset($contents->seniority) ? $seniority_range[$contents->seniority] : '');
                        }
                        // Note: 已經審核過封存的徵信項目，對於媒合上架後都應該是成功的狀態
                        $cer['user_status'] = CERTIFICATION_STATUS_SUCCEED;
                        $cer['certification_id'] = intval($cur_cer[$value]->id);
                        $cer['updated_at'] = intval($cur_cer[$value]->updated_at);
                        $description ? $cer['description'] = $description : '';
                    }
                    unset($cer['optional']);
                    $certification_list[] = $cer;
                }
            }
            $contract_data = $this->contract_lib->get_contract($target->contract_id);
            $contract = $contract_data ? $contract_data['content'] : '';

            $reason = $target->reason;
            $json_reason = json_decode($reason);
            if(isset($json_reason->reason)){
                $reason = $json_reason->reason.' - '.$json_reason->reason_description;
            }

			$data = array(
				'id' 				=> intval($target->id),
				'target_no' 		=> $target->target_no,
                'product_name' => $product_name,
				'product_id' 		=> intval($target->product_id),
                'sub_product_id' => intval($target->sub_product_id),
				'user_id' 			=> intval($target->user_id),
				'loan_amount' 		=> intval($target->loan_amount),
				'credit_level' 		=> intval($target->credit_level),
				'interest_rate' 	=> floatval($target->interest_rate),
				'original_interest_rate' 	=> (isset($targetData->original_interest_rate) ? $targetData->original_interest_rate : null),
				'reason'		=> $reason,
				'remark' 			=> $target->remark,
				'targetDatas' => $targetDatas,
				'instalment' 		=> intval($target->instalment),
				'repayment' 		=> intval($target->repayment),
				'expire_time' 		=> intval($target->expire_time),
				'invested' 			=> intval($target->invested),
                'isTargetOpaque' => $sub_product_id == STAGE_CER_TARGET ? true : false,
                'is_rate_increase' => (isset($targetData->original_interest_rate) && $targetData->original_interest_rate != $target->interest_rate ? true : false),
                'status' 			=> intval($target->status),
				'sub_status' 		=> intval($target->sub_status),
				'created_at' 		=> intval($target->created_at),
				'contract' 			=> $contract,
				'user' 				=> $user,
				'amortization_schedule' => $amortization_schedule,
			);

            count($certification_list)>0 ? $data['certification'] = $certification_list : '';

            if($target->sub_product_id == STAGE_CER_TARGET){
                $target_tips = $this->config->item('target_tips');
                $data['target_tips'] = $target_tips;
            }

            $target->order_id!=0?$data['order_image']=$target->person_image:null;

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST ));
    }

    public function judicial_info_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$target 			= $this->target_model->get($target_id);
		$data				= [];
        $user_meta = '';

        if(!empty($target) && in_array($target->status,[504])){
            $this->load->library('loanmanager/product_lib');

            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $sub_product_id = $target->sub_product_id;
            $product_name = $product['name'];
            if($this->is_sub_product($product,$sub_product_id)){
                $product = $this->trans_sub_product($product,$sub_product_id);
                    $product_name = $product['name'];
            }

            $target->investor = 1;
			$amortization_schedule = $this->financial_lib->get_amortization_schedule($target->loan_amount,$target);

			$user_info 	= $this->user_model->get($target->user_id);
			$user		= [];

			$targetDatas = [];
            $targetData = json_decode($target->target_data);

            $certification_list = [];
            $targetData_cer = isset($targetData->certification_id) ? $targetData->certification_id : false;
            if ($targetData_cer) {
                $this->load->model('user/user_certification_model');
                $this->load->library('Certification_lib');
                $certification = $this->config->item('certifications');
                $certifications = $this->user_certification_model->get_many_by([
                    'id' => $targetData_cer,
                    'user_id' => $target->user_id,
                ]);
                foreach ($certifications as $key => $value) {
                    $cur_cer[$value->certification_id] = $value;
                }

                $product_certs = $this->product_lib->get_product_certs_by_product_id($target->product_id, $target->sub_product_id, []);
                foreach ($product_certs as $key => $value) {
                    $cer = $certification[$value];
                    if (!isset($cur_cer[$value])) {
                        $cer['description'] = '未' . $cer['description'];
                        $cer['user_status'] = 2;
                        $cer['certification_id'] = null;
                        $cer['updated_at'] = null;
                    } else {
                        $description = false;
                        $contents = json_decode($cur_cer[$value]->content);
                        if( $value == 1){
                            $description .= '發證地點：' . $contents->id_card_place . '<br>發證時間：' . $contents->id_card_date;
                        } elseif( $value == 2){
                            isset($contents->pro_certificate) ? $description .= '有提供專業證書；' . str_replace(',','、', $contents->pro_certificate) : '';
                            $description .= '學門：' . $contents->major. '<br>系所：' . $contents->department . '<br>學制：' . $this->config->item('school_system')[$contents->system];
                        } elseif ($value == 3){
                            $description = '已驗證個人金融帳號';
                        } elseif ($value == 4){
                            $ig = isset($contents->instagram) ? $contents->instagram : $contents->info;
                            if(!empty($ig)){
                                $description .= 'Instagram' . '<br>貼文：' . $ig->counts->media . '<br>追蹤者：' . $ig->counts->followed_by . '<br>追蹤中：' . $ig->counts->follows;
                            }
                        } elseif ($value == 5){
                            $description = '已輸入父母作為緊急聯絡人';
                        } elseif ($value == 6){
                            $description = '已驗證常用電子信箱';
                        } elseif ($value == 7){
                            $financial_input = round(($contents->parttime + $contents->allowance + $contents->other_income) + ($contents->scholarship * 2) / 12);
                            $financial_output = round(($contents->restaurant + $contents->transportation + $contents->entertainment + $contents->other_expense));
                            $description = '(自填) 平均月收入：'. $financial_input . '<br>(自填) 平均月支出：' . $financial_output;
                            isset($contents->labor_image) ? $description .= '有提供最近年度報稅扣繳憑證' : '';
                        } elseif ($value == 8){
                            $description = '最高學歷：' . preg_replace('/\(自填\)/', '', $contents->school) . '(' . $this->config->item('school_system')[$contents->system] . ')';
                        } elseif ($value == 9){
                            if(isset($contents->result->messages)){
                                foreach ($contents->result->messages as $creditValue){
                                    $creditValue->stage == 'credit_scores' ? $description .= $creditValue->message : '';
                                }
                            }
                        } elseif ($value == 10){
                            isset($contents->industry) ? $description .= '公司類型：' . $this->config->item('industry_name')[$contents->industry] : '';
                            isset($contents->job_seniority) ? $description .= '<br>此公司工作期間：' . $this->config->item('seniority_range')[$contents->job_seniority] : '';
                        }
                        $cer['user_status'] = $cur_cer[$value]->status == 2 ? 1 : intval($cur_cer[$value]->status);
                        $cer['certification_id'] = intval($cur_cer[$value]->id);
                        $cer['updated_at'] = intval($cur_cer[$value]->updated_at);
                        $description ? $cer['description'] = $description : '';
                    }
                    unset($cer['optional']);
                    $certification_list[] = $cer;
                }
            }
            $contract_data = $this->contract_lib->get_contract($target->contract_id);
            $contract = $contract_data ? $contract_data['content'] : '';

            $reason = $target->reason;
            $json_reason = json_decode($reason);
            if(isset($json_reason->reason)){
                $reason = $json_reason->reason.' - '.$json_reason->reason_description;
            }

            // 資訊不在案件本身內容
            $out_of_target_info = [
                'compName' => '',
                'compIdNum' => '',
                'bornDate' => '',
                'income' => '',
                'owner' => '',
                'scale' => '',
                'comeType' => '',
            ];

            // 新光送出資料
            $this->load->library('mapping/sk_bank/msgno');
            $response = $this->msgno->getSKBankInfoByTargetId($target_id);
            if(!empty($response) && isset($response['data']['send_log']['request_content'])){
                if(!empty($response['data']['send_log']['request_content'])){
                    $sk_bank_send_log = json_decode($response['data']['send_log']['request_content'],true);
                    $out_of_target_info['compName'] = isset($sk_bank_send_log['unencrypted']['Data']['CompName']) ? $sk_bank_send_log['unencrypted']['Data']['CompName']: '';
                    $out_of_target_info['compIdNum'] = isset($sk_bank_send_log['unencrypted']['CompId']) ? $sk_bank_send_log['unencrypted']['CompId']: '';
                    $out_of_target_info['bornDate'] = isset($sk_bank_send_log['unencrypted']['Data']['CompSetDate']) ? $sk_bank_send_log['unencrypted']['Data']['CompSetDate'] : '';
                    $out_of_target_info['income'] = isset($sk_bank_send_log['unencrypted']['Data']['CompCapital']) ? $sk_bank_send_log['unencrypted']['Data']['CompCapital']: '';
                    $out_of_target_info['owner'] = isset($sk_bank_send_log['unencrypted']['Data']['PrName']) ? $sk_bank_send_log['unencrypted']['Data']['PrName'] : '';
                    $out_of_target_info['scale'] = isset($sk_bank_send_log['unencrypted']['Data']['BusinessType']) ? $sk_bank_send_log['unencrypted']['Data']['BusinessType'] : '';
                    $out_of_target_info['comeType'] = isset($sk_bank_send_log['unencrypted']['Data']['CompDuType']) ? $sk_bank_send_log['unencrypted']['Data']['CompDuType'] : '';
                }
            }

            // 案件關係人資料
            $guarantorInfo_list = ['guarantorInfo' => []];

            $this->load->model('loan/target_associate_model');
            $guarantorInfo = $this->target_associate_model->get_many_by(['target_id' => $target_id, 'product_id' => $target->product_id, 'status' => 2, 'guarantor' => 1]);
            if(! empty($guarantorInfo)){
                $guarantor_info_list = [];
                // 關係
                $guarantorInfo = json_decode(json_encode($guarantorInfo),true);
                $guarantor_list = [];
                $guarantor_list = array_map(function($key,$values) {
                    $list = [];
                    if(isset($values['user_id'])){
                        $list['user_id'] = $values['user_id'];
                    }
                    if(isset($values['character'])){
                        $list['character'] = $values['character'];
                    }
                    return [$key=>$list];
                }, array_keys($guarantorInfo), $guarantorInfo);
                $guarantor_info_list = array_reduce($guarantor_list, 'array_merge', array());

                // 關係人基本資訊
                $user_list = array_column($guarantor_info_list, 'user_id');
                $user_info_list = [];
                if(! empty($user_list)){
                    $user_info_list = $this->user_model->get_many($user_list);
                    if(! empty($user_info_list)){
                        $user_info_list = json_decode(json_encode($user_info_list),true);
                        $user_info_list = array_map(function($key,$values) {
                            $list = [];
                            if(isset($values['name'])){
                                $list['userName'] = mb_substr($values['name'],0,1,'UTF-8').'**';
                            }
                            if(isset($values['id_number'])){
                                $list['txtIdNum'] = strlen($values['id_number'])==10?substr($values['id_number'],0,5).'*****':'';
                            }
                            if(isset($values['birthday'])){
                                $list['userAge'] = get_age($values['birthday']);
                            }
                            if(isset($values['id']) && $values['id'] != ''){
                                $list['user_id'] = $values['id'];
                                // 任職公司
                                $meta_info = $this->user_meta_model->get_many_by([
                                    'user_id' => $values['id'],
                                    'meta_key' => ['job_company', 'diploma_name']
                                ]);
                                if (! empty($meta_info)) {
                                    $job_company = ($meta_info[0]->meta_key == 'job_company'
                                        ? $meta_info[0]->meta_value
                                        : (isset($meta_info[1]) >= 2
                                            ? $meta_info[1]->meta_value
                                            : false));
                                    $diploma_name = $meta_info[0]->meta_key == 'diploma_name'
                                        ? $meta_info[0]->meta_value
                                        : (isset($meta_info[1]) >= 2
                                            ? $meta_info[1]->meta_value
                                            : false);
                                    $list['txtCompany'] = $job_company ? $job_company : $diploma_name;
                                } else {
                                    $list['txtCompany'] = '未提供相關資訊';
                                }
                            }

                            return [$key=>$list];
                        }, array_keys($user_info_list), $user_info_list);

                        $user_info_list = array_reduce($user_info_list, 'array_merge', array());

                    }
                }

                if(!empty($user_info_list) && ! empty($guarantor_info_list)){
                    $new_guarantor = [];
                    foreach($guarantor_info_list as $key => $values){
                        if(isset($values['user_id'])){
                            $new_guarantor[$values['user_id']] = $values;
                        }
                    }
                    $new_user = [];
                    foreach($user_info_list as $key => $values){
                        if(isset($values['user_id'])){
                            $new_user[$values['user_id']] = $values;
                        }
                    }
                    if(!empty($new_user) && !empty($new_guarantor)){
                        $guarantorInfo_list['guarantorInfo'] = array_map(function ($a1, $b1) { return $a1 + $b1; }, $new_user, $new_guarantor);
                    }
                }
            }

			$data = array(
				'id' 				=> intval($target->id),
				'target_no' 		=> $target->target_no,
                'product_name' => $product_name,
				'product_id' 		=> intval($target->product_id),
                'sub_product_id' => intval($target->sub_product_id),
				'user_id' 			=> intval($target->user_id),
				'loan_amount' 		=> intval($target->loan_amount),
				'credit_level' 		=> intval($target->credit_level),
				'interest_rate' 	=> floatval($target->interest_rate),
				'original_interest_rate' 	=> (isset($targetData->original_interest_rate) ? $targetData->original_interest_rate : null),
				'reason'		=> $reason,
				'remark' 			=> $target->remark,
				'targetDatas' => $targetDatas,
				'instalment' 		=> intval($target->instalment),
				'repayment' 		=> intval($target->repayment),
				'expire_time' 		=> intval($target->expire_time),
				'invested' 			=> intval($target->invested),
                'isTargetOpaque' => $sub_product_id == STAGE_CER_TARGET ? true : false,
                'is_rate_increase' => (isset($targetData->original_interest_rate) && $targetData->original_interest_rate != $target->interest_rate ? true : false),
                'status' 			=> intval($target->status),
				'sub_status' 		=> intval($target->sub_status),
				'created_at' 		=> intval($target->created_at),
				'contract' 			=> $contract,
				'user' 				=> $user,
				'amortization_schedule' => $amortization_schedule,
			);

            $data = array_merge($data,$out_of_target_info);
            $data = array_merge($data,$guarantorInfo_list);
            count($certification_list)>0 ? $data['certification'] = $certification_list : '';

            if($target->sub_product_id == STAGE_CER_TARGET){
                $target_tips = $this->config->item('target_tips');
                $data['target_tips'] = $target_tips;
            }

            $target->order_id!=0?$data['order_image']=$target->person_image:null;

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST ));
    }

	/**
     * @api {post} /v2/target/apply 出借方 單案申請出借
	 * @apiVersion 0.2.0
	 * @apiName PostTargetApply
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} target_id Target ID
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
     * @apiError 208 未滿18歲
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

        // 僅開放特定法人
        $valid_company_cert = FALSE;
        if(isset($this->user_info->company)&&$this->user_info->company != 0){
            $necessary_certs = [CERTIFICATION_TARGET_APPLY, CERTIFICATION_JUDICIALGUARANTEE, CERTIFICATION_GOVERNMENTAUTHORITIES];
            $this->load->model('user/user_certification_model');
            $user_certification_info = $this->user_certification_model->get_many_by([
                'certification_id' => $necessary_certs,
                'user_id' => $user_id,
                'status' => CERTIFICATION_STATUS_SUCCEED,
                'investor' => USER_INVESTOR
            ]);

            if (count($user_certification_info) <> count($necessary_certs) || empty($this->user_info->transaction_password))
            {
                $this->response(array('result' => 'ERROR', 'error' => IS_COMPANY));
            }

            $company_user_info = $this->user_info;
            $this->user_info = $this->user_info->naturalPerson;
            $this->user_info->company = $company_user_info->company;
            $this->user_info->investor = $company_user_info->investor;

            $valid_company_cert = TRUE;
        }

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
            elseif ($valid_company_cert === TRUE)
            {
                if ($param['amount'] > TARGET_AMOUNT_MAX_COMPANY)
                { // 不得高於法人單筆金額上限
                    $this->response(array('result' => 'ERROR','error' => TARGET_AMOUNT_RANGE ));
                }
                $today_investment = $this->investment_model->get_invest_amount_today($user_id);
                if (($today_investment + $param['amount']) > TARGET_AMOUNT_MAX_COMPANY)
                { // 不得高於法人單日金額上限
                    $this->response(array('result' => 'ERROR','error' => TARGET_AMOUNT_RANGE ));
                }
            }

			if( $user_id == $target->user_id ){
				$this->response(array('result' => 'ERROR','error' => TARGET_SAME_USER ));
			}

			$this->check_adult();

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
     * @api {post} /v2/target/cancel 出借方 取消申請出借
	 * @apiVersion 0.2.0
	 * @apiName PostTargetCancel
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} target_id Target ID
	 *
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
	 * @apiUse NotInvestor
     *
	 * @apiError 801 標的不存在
     * @apiErrorExample {Object} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
	 *
     * @apiError 806 此申請不存在
     * @apiErrorExample {Object} 806
     *     {
     *       "result": "ERROR",
     *       "error": "806"
     *     }
     *
     * @apiError 807 此申請狀態不可操作
     * @apiErrorExample {Object} 807
     *     {
     *       "result": "ERROR",
     *       "error": "807"
     *     }
     *
     * @apiError 817 系統操作中請稍等
     * @apiErrorExample {Object} 817
     *     {
     *       "result": "ERROR",
     *       "error": "817"
     *     }
	 *
     */
	public function cancel_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;

		//必填欄位
		if (empty($input['target_id'])) {
			$this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
		}

		$target_id = intval($input['target_id']);


		$target = $this->target_model->get($target_id);
		if($target){

			if($target->status != 3){
				$this->response(['result' => 'ERROR','error' => TARGET_APPLY_STATUS_ERROR]);
			}

			if($target->script_status != 0){
				$this->response(['result' => 'ERROR','error' => TARGET_IS_BUSY]);
			}

			$investment = $this->investment_model->get_by([
				'target_id'	=> $target->id,
				'user_id'	=> $user_id,
				'status'	=> [0,1,2]
			]);
			if($investment){
				if($investment->status > 1){
					$this->response(['result' => 'ERROR','error' => TARGET_APPLY_STATUS_ERROR]);
				}

				$this->load->library('Target_lib');
				$rs = $this->target_lib->cancel_investment($target,$investment,$user_id);
				if($rs){
					$this->response(['result' => 'SUCCESS']);
				}else{
					$this->response(['result' => 'ERROR','error' => TARGET_IS_BUSY]);
				}
			}else{
				$this->response(['result' => 'ERROR','error' => TARGET_APPLY_NOT_EXIST]);
			}
		}
		$this->response(['result' => 'ERROR','error' => TARGET_NOT_EXIST]);
    }

	/**
     * @api {get} /v2/target/batchpreapply 出借方 批次查詢資訊
	 * @apiVersion 0.2.0
	 * @apiName GetBatchPreTargetApply
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} target_ids 產品IDs IDs ex: 1,3,10,21
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均年利率(%)
	 * @apiSuccess {Object} target_ids Target IDs
	 * @apiSuccess {Object} amortization_schedule 預計還款計畫
	 * @apiSuccess {Object} amortization_schedule.total 還款總計
	 * @apiSuccess {Number} amortization_schedule.total.principal 本金
	 * @apiSuccess {Number} amortization_schedule.total.interest 利息
	 * @apiSuccess {Number} amortization_schedule.total.total_payment 加總
	 * @apiSuccess {Object} amortization_schedule.schedule 還款計畫
	 * @apiSuccess {Number} amortization_schedule.schedule.key 還款日期
	 * @apiSuccess {Number} amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {Number} amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {Number} amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {Object} contracts 借貸合約列表
	 * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"total_amount": 10000,
     * 			"total_count": 2,
     * 			"max_instalment": 6,
     * 			"min_instalment": 3,
     * 			"XIRR": 7.67,
     * 			"target_ids": [
     * 				23,
     * 				24
     * 			],
     * 			"amortization_schedule": {
     * 				"total": {
     * 					"principal": 10000,
     * 					"interest": 194,
     * 					"total_payment": 10194
     * 				},
     * 				"schedule": {
     * 					"2019-03-10": {
     * 						"principal": 1650,
     * 						"interest": 37,
     * 						"total_payment": 1687
     * 					},
     * 					"2019-04-10": {
     * 						"principal": 1667,
     * 						"interest": 20,
     * 						"total_payment": 1687
     * 					},
     * 					"2019-05-10": {
     * 						"principal": 1683,
     * 						"interest": 10,
     * 						"total_payment": 1693
     * 					},
     * 					"2019-06-10": {
     * 						"principal": 836,
     * 						"interest": 17,
     * 						"total_payment": 853
     * 					},
     * 					"2019-07-10": {
     * 						"principal": 842,
     * 						"interest": 11,
     * 						"total_payment": 853
     * 					},
     * 					"2019-08-10": {
     * 						"principal": 856,
     * 						"interest": 6,
     * 						"total_payment": 862
     * 					}
     * 				}
     * 			},
     * 			"contracts": [
     * 			{
     * 				"title": "借貸契約",
     * 				"content": "借貸契約",
     * 				"created_at": "1547445331"
     * 			},
     * 			{
     * 				"title": "借貸契約",
     * 				"content": "借貸契約",
     * 				"created_at": "1547445358"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse InputError
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
     */
	public function batchpreapply_get()
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;

		//必填欄位
		if (empty($input['target_ids'])) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$target_ids 	= explode(',',$input['target_ids']);
		$count 			= count($target_ids);
		if(!empty($target_ids)){
			foreach($target_ids as $key => $id){
				if(intval($id)<=0 ){
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$targets = $this->target_model->get_many_by([
			'id'	=> $target_ids,
			'status'=> 3,
		]);
		if($targets && count($targets)==$count){
			$data = [
				'total_amount' 		=> 0,
				'total_count' 		=> 0,
				'max_instalment' 	=> 0,
				'min_instalment' 	=> 0,
				'XIRR' 				=> 0,
				'target_ids' 		=> [],
			];
			$contract 				= [];
			$amortization_schedule 	= [
				'total'=>[
					'principal'		=> 0,
					'interest'		=> 0,
					'total_payment'	=> 0,
				]
			];
			$numerator 	= $denominator = 0;
			foreach($targets as $key => $value){
				$data['total_amount'] += $value->loan_amount;
				$data['total_count'] ++;
				if($data['max_instalment'] < $value->instalment){
					$data['max_instalment'] = intval($value->instalment);
				}
				if($data['min_instalment'] > $value->instalment || $data['min_instalment']==0){
					$data['min_instalment'] = intval($value->instalment);
				}

				$numerator 		+= $value->loan_amount * $value->instalment * $value->interest_rate;
				$denominator 	+= $value->loan_amount * $value->instalment;
				$contract_data 	= $this->contract_lib->get_contract($value->contract_id);
				$contract[] 	= $contract_data;
				$data['target_ids'][] = intval($value->id);
				$schedule = $this->financial_lib->get_amortization_schedule($value->loan_amount,$value);
				if($schedule){
					foreach($schedule['schedule'] as $k => $v){
						if(!isset($amortization_schedule['schedule'][$v['repayment_date']])){
							$amortization_schedule['schedule'][$v['repayment_date']] = [
								'principal'		=> 0,
								'interest'		=> 0,
								'total_payment'	=> 0,
							];
						}
						$amortization_schedule['schedule'][$v['repayment_date']]['principal'] 		+= $v['principal'];
						$amortization_schedule['schedule'][$v['repayment_date']]['interest'] 		+= $v['interest'];
						$amortization_schedule['schedule'][$v['repayment_date']]['total_payment'] 	+= $v['total_payment'];
						$amortization_schedule['total']['principal'] 					+= $v['principal'];
						$amortization_schedule['total']['interest'] 					+= $v['interest'];
						$amortization_schedule['total']['total_payment'] 				+= $v['total_payment'];
					}
				}
			}
			$data['XIRR'] 					= round($numerator/$denominator ,2);
			$data['amortization_schedule'] 	= $amortization_schedule;
			$data['contracts'] 				= $contract;
			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST ));
    }

	/**
     * @api {post} /v2/target/batchapply 出借方 批次申請出借
	 * @apiVersion 0.2.0
	 * @apiName PostBatchTargetApply
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} target_ids Target IDs ex: 1,3,10,21
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
     * @apiError 208 未滿18歲
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
	public function batchapply_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;

        //暫不開放法人
        if(isset($this->user_info->company)&&$this->user_info->company != 0){
            $this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
        }

        //必填欄位
		if (empty($input['target_ids'])) {
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$target_ids 	= explode(',',$input['target_ids']);
		$count 			= count($target_ids);
		if(!empty($target_ids)){
			foreach($target_ids as $key => $id){
				if(intval($id)<=0 ){
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}
		}else{
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		$this->check_adult();

		$exist = $this->investment_model->get_by([
			'target_id'	=> $target_ids,
			'user_id'	=> $user_id,
			'status'	=> [0,1,2,3,10]
		]);
		if($exist){
			$this->response(array('result' => 'ERROR','error' => TARGET_APPLY_EXIST ));
		}

		$targets = $this->target_model->get_many_by([
			'id'	=> $target_ids,
			'status'=> 3,
		]);
		if($targets && count($targets)==$count){
			$param	= [];
			foreach($targets as $key => $value){
				if( $user_id == $value->user_id ){
					$this->response(array('result' => 'ERROR','error' => TARGET_SAME_USER ));
				}

				$param[] = [
					'target_id'	=> $value->id,
					'amount'	=> intval($value->loan_amount),
					'user_id'	=> $user_id,
				];
			}

			$insert = $this->investment_model->insert_many($param);
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
	 * @apiSuccess {Number} target.repayment 計息方式
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
		$investor 	= $this->user_info->investor;
		$investments	= $this->investment_model->get_many_by([
			'user_id' 	=> $this->user_info->id,
			'status'	=> [0,1,2]
		]);
        $list			= [];
        $user_meta = '';

        $product_list 	= $this->config->item('product_list');

        if(!empty($investments)){
            foreach($investments as $key => $value){
                $target_info      = $this->target_model->get($value->target_id);
                $target_user_info = $this->user_model->get($target_info->user_id);
                $age  = get_age($target_user_info->birthday);
                $user		= [];

                $product = $product_list[$target_info->product_id];
                $sub_product_id = $target_info->sub_product_id;
                $product_name = $product['name'];

                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                    $product_name = $product['name'];
                }
                if ($product_list[$target_info->product_id]['identity'] == 1) {
                    $user_meta = $this->user_meta_model->get_by(['user_id' => $target_info->user_id, 'meta_key' => 'school_name']);
                    if (is_object($user_meta)) {
                        $user_meta->meta_value = preg_replace('/\(自填\)/', '', $user_meta->meta_value);
                    } else {
                        $user_meta = new stdClass();
                        $user_meta->meta_value = '未提供學校資訊';
                    }
                } elseif ($product_list[$target_info->product_id]['identity'] == 2) {
                    $meta_info = $this->user_meta_model->get_many_by([
                        'user_id' => $target_info->user_id,
                        'meta_key' => ['job_company', 'diploma_name']
                    ]);
                    if ($meta_info) {
                        $job_company = ($meta_info[0]->meta_key == 'job_company'
                            ? $meta_info[0]->meta_value
                            : (isset($meta_info[1]) >= 2
                                ? $meta_info[1]->meta_value
                                : false));
                        $diploma_name = $meta_info[0]->meta_key == 'diploma_name'
                            ? $meta_info[0]->meta_value
                            : (isset($meta_info[1]) >= 2
                                ? $meta_info[1]->meta_value
                                : false);
						$user_meta = new stdClass();
						$user_meta->meta_value = $job_company ? $job_company : $diploma_name;
                    } else {
                        $user_meta = new stdClass();
                        $user_meta->meta_value = '未提供相關資訊';
                    }
                }

                $user = array(
                    'sex' 			=> $target_user_info->sex,
                    'age'			=> $age,
                    'company_name'	=> $user_meta?$user_meta->meta_value:'',
                );

                $reason = $target_info->reason;
                $json_reason = json_decode($reason);
                if(isset($json_reason->reason)){
                    $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                }

                $target = [
					'id'			=> intval($target_info->id),
					'target_no'		=> $target_info->target_no,
					'product_id'	=> intval($target_info->product_id),
					'user_id' 		=> intval($target_info->user_id),
                    'user' 			=> $user,
					'loan_amount'	=> intval($target_info->loan_amount),
					'credit_level' 	=> intval($target_info->credit_level),
					'interest_rate' => floatval($target_info->interest_rate),
					'instalment' 	=> intval($target_info->instalment),
					'repayment' 	=> intval($target_info->repayment),
                    'reason' 		=> $reason,
					'expire_time'	=> intval($target_info->expire_time),
					'invested'		=> intval($target_info->invested),
					'status'		=> intval($target_info->status),
					'sub_status'	=> intval($target_info->sub_status),
				];
                $temp = [
                    'amount' 			=> intval($value->amount),
                    'loan_amount' 		=> intval($value->loan_amount),
                    'status' 			=> intval($value->status),
                    'created_at' 		=> intval($value->created_at),
                    'target' 			=> $target,
                ];
                $value->aiBidding == 1 ? $temp['aiBidding'] = true : '';
                $list[] = $temp;
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ]));
    }

 	/**
     * @api {post} /v2/target/batch 出借方 智能出借
	 * @apiVersion 0.2.0
	 * @apiName PostTargetBatch
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} [product_id=all] 產品ID 全部：all 複選使用逗號隔開1,2,3,4
     * @apiParam {Number} [interest_rate_s] 利率區間下限(%)
     * @apiParam {Number} [interest_rate_e] 利率區間上限(%)
     * @apiParam {Number} [instalment_s] 期數區間下限(%)
     * @apiParam {Number} [instalment_e] 期數區間上限(%)
     * @apiParam {String} [credit_level=all] 信用評等 全部：all 複選使用逗號隔開1,2,3,4,5,6,7,8,9,10,11,12,13
     * @apiParam {String=all,0,1} [section=all] 標的狀態 全部:all 全案:0 部分案:1
     * @apiParam {String=all,0,1} [national=all] 信用評等 全部:all 私立:0 國立:1
     * @apiParam {String=all,0,1,2} [system=all] 學制 全部:all 0:大學 1:碩士 2:博士
     * @apiParam {String=all,F,M} [sex=all] 性別 全部:all 女性:F 男性:M
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} total_amount 總金額
	 * @apiSuccess {String} total_count 總筆數
	 * @apiSuccess {String} max_instalment 最大期數
	 * @apiSuccess {String} min_instalment 最小期數
	 * @apiSuccess {String} XIRR 平均年利率(%)
	 * @apiSuccess {Object} target_ids 篩選出的Target ID
	 * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"total_amount": 70000,
     * 			"total_count": 4,
     * 			"max_instalment": 12,
     * 			"min_instalment": 12,
     * 			"XIRR": 8,
     * 			"target_ids": [
     * 				17,
     * 				19,
     * 				21,
     * 				22
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
     */
	public function batch_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
        $targets = false;
        $data = [];
        $content = $filter = [];
        //$this->check_adult();

		$where		= [
			'user_id !=' 	=> $user_id,
			'status'		=> 3
		];

        $allow_aiBidding_product = $this->config->item('allow_aiBidding_product');
        if(isset($input['product_id']) && !empty($input['product_id']) && $input['product_id']!='all'){
            $filter['product_id'] = $input['product_id'];
            foreach(explode(',',$input['product_id']) as $key => $value){
                if(in_array($value, $allow_aiBidding_product)){
                    $where['product_id'] = $value;
                }
            }
		}else{
            $where['product_id'] = $allow_aiBidding_product;
            $filter['product_id'] = 'all';
        }

		if(isset($input['credit_level']) && !empty($input['credit_level']) && $input['credit_level']!='all' ){
			$filter['credit_level'] = $input['credit_level'];
			$where['credit_level'] 	= explode(',',$input['credit_level']);
		}else{
			$filter['credit_level'] = 'all';
		}

        if(isset($input['section']) && $input['section']!='all' ){
            $input['section']  = $input['section']?1:0;
            $filter['section'] = $input['section'];
            if($input['section']){
                $where['invested >'] = 0;
            }else{
                $where['invested'] = 0;
            }
        }else{
            $filter['section'] = 'all';
        }

        $filter['interest_rate_s'] = 0;
		$filter['interest_rate_e'] = 20;
		if(isset($input['interest_rate_e']) && intval($input['interest_rate_e'])>0){
			if(isset($input['interest_rate_s']) && intval($input['interest_rate_e']) >= intval($input['interest_rate_s'])){
				$filter['interest_rate_s'] = intval($input['interest_rate_s']);
				$filter['interest_rate_e'] = intval($input['interest_rate_e']);
				$where['interest_rate >='] = intval($input['interest_rate_s']);
				$where['interest_rate <='] = intval($input['interest_rate_e']);
			}
		}

		$filter['instalment_s'] = 0;
		$filter['instalment_e'] = 24;
		if(isset($input['instalment_e']) && intval($input['instalment_e'])>0){
			if(isset($input['instalment_s']) && intval($input['instalment_e']) >= intval($input['instalment_s'])){
				$filter['instalment_s'] = intval($input['instalment_s']);
				$filter['instalment_e'] = intval($input['instalment_e']);
				$where['instalment >='] = intval($input['instalment_s']);
				$where['instalment <='] = intval($input['instalment_e']);
			}
		}

		$investments = $this->investment_model->get_many_by([
		    'user_id'=>$user_id,
            'status'=>[0,1,2]
        ]);
		if($investments){
			$investment_target = [];
			foreach($investments as $key => $value){
				$investment_target[] = $value->target_id;
			}
			$where['id not'] = $investment_target;
		}

        $targets = $this->target_model->get_many_by($where);
        $data = [
            'total_amount' 		=> 0,
            'total_count' 		=> 0,
            'max_instalment' 	=> 0,
            'min_instalment' 	=> 0,
            'XIRR' 				=> 0,
            'target_ids' 		=> [],
        ];

        if(isset($input['sex']) && !empty($input['sex']) && $input['sex']!='all' ){
            $filter['sex'] = $input['sex'];
            if($targets){
                foreach($targets as $key => $value){
                    $target_user_info = $this->user_model->get($value->user_id);
                    if($target_user_info->sex != $input['sex']){
                        unset($targets[$key]);
                    }
                }
            }
        }else{
            $filter['sex'] = 'all';
        }

        if(isset($input['system']) && $input['system']!='all' && $input['system']!=''){
            $filter['system'] = $input['system'];
            if($targets){
                foreach($targets as $key => $value){
                    $user_meta = $this->user_meta_model->get_by([
                        'user_id'	=> $value->user_id,
                        'meta_key'	=> 'school_system'
                    ]);
                    if($user_meta){
                        if($user_meta->meta_value != $input['system']){
                            unset($targets[$key]);
                        }
                    }else{
                        unset($targets[$key]);
                    }
                }
            }
        }else{
            $filter['system'] = 'all';
        }

        if(isset($input['national']) && $input['national']!='all' && $input['national']!=''){
            $this->config->load('school_points',TRUE);
            $school_list = $this->config->item('school_points');
            $filter['national'] = $input['national'];
            if($targets){
                foreach($targets as $key => $value){
                    $user_meta = $this->user_meta_model->get_by([
                        'user_id'	=> $value->user_id,
                        'meta_key'	=> 'school_name'
                    ]);
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
        }else{
            $filter['national'] = 'all';
        }

        !isset($input['ai_bidding']) ? $input['ai_bidding'] = 0 : '';
        !isset($input['target_amount']) ? $input['target_amount'] = 0 : '';
        !isset($input['daily_amount']) ? $input['daily_amount'] = 0 : '';
        $input['target_amount'] > 20 ? $input['target_amount'] = 20 : '';
        $input['daily_amount'] > 100 ? $input['daily_amount'] = 100 : '';

        $filter['ai_bidding'] = intval($input['ai_bidding']);

        //每案最高投標金額
        $targetAmount = intval($input['target_amount']) * 1000;
        $filter['target_amount'] = intval($input['target_amount']);

        //每日最高投標金額
        $dailyAmount = intval($input['daily_amount']) * 1000;
        $filter['daily_amount'] = intval($input['daily_amount']);

        if($targets){
            $numerator = $denominator = 0;
            foreach($targets as $key => $value){
                $data['total_amount'] += $value->loan_amount;
                $data['total_count'] ++;
                if($data['max_instalment'] < $value->instalment){
                    $data['max_instalment'] = intval($value->instalment);
                }
                if($data['min_instalment'] > $value->instalment || $data['min_instalment']==0){
                    $data['min_instalment'] = intval($value->instalment);
                }
                $content[] 		= intval($value->id);
                $numerator 		+= $value->loan_amount * $value->instalment * $value->interest_rate;
                $denominator 	+= $value->loan_amount * $value->instalment;
            }
            $data['XIRR'] 		= round($numerator/$denominator ,2);

            if($dailyAmount != 0){
                //取得各智能投資的用戶今日投資數字
                $todayInvestments = 0;
                $today = strtotime(date("Y-m-d", time()));
                $this->load->model('loan/investment_model');
                $getTodayInvestments = $this->investment_model->get_many_by([
                    'user_id' => $user_id,
                    'status NOT' => [8, 9],
                    'created_at >=' => $today,
                ]);
                if($getTodayInvestments){
                    foreach($getTodayInvestments as $key => $value){
                        //如已結標則以結標金額
                        $amount = $value->status >= 2 ? $value->loan_amount : $value->amount;

                        //統計投資人今日投資額
                        !isset($todayInvestments) ? $todayInvestments[$value->user_id] = 0 : '';
                        $todayInvestments += $amount;
                    }
                }
                $dailyAmount -= $todayInvestments;
                foreach($targets as $key => $value){
                    $allowAmount = $value->loan_amount - $value->invested;
                    if( $dailyAmount >= 1000 && $allowAmount >= 1000){
                        $dailyAmount -= $targetAmount != 0 ? ($targetAmount >= $allowAmount ? $allowAmount : $targetAmount) : $allowAmount;
                    }else{
                        unset($content[$key]);
                    }
                }
            }

            $data['target_ids'] = $content;
        }

        $this->load->model('loan/batch_model');
        $expireTime = $input['ai_bidding'] == 1 ? strtotime('+ 30 days',time()) : null;
        if($input['ai_bidding'] != 2){
            $batchData = $this->batch_model->order_by('id','desc')->get_by([
                'user_id' => $user_id,
                'type' => 0,
                'status' => 1,
            ]);
            if($batchData){
                $rs = $this->batch_model->update_by([
                    'id' => $batchData->id,
                ], [
                    'filter' => json_encode($filter),
                    'content' => json_encode($content),
                    'expire_time' => $expireTime
                ]);
            }else{
                $this->batch_model->insert([
                    'user_id' => $user_id,
                    'type' => 0,
                    'status' => 1,
                    'filter' => json_encode($filter),
                    'content' => json_encode($content),
                    'expire_time' => $expireTime
                ]);
            }
            $this->load->library('Target_lib');
            if($input['ai_bidding'] == 1){
                $this->target_lib->aiBiddingAllTarget($user_id);
            }
        }
        $this->response(['result' => 'SUCCESS','data' =>$data]);
    }

	/**
     * @api {get} /v2/target/batch 出借方 智能出借前次設定
	 * @apiVersion 0.2.0
	 * @apiName GetTargetBatch
     * @apiGroup Target
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
	 * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {String} product_id 產品ID 全部：all 複選使用逗號隔開
     * @apiSuccess {Number} interest_rate_s 利率區間下限(%)
     * @apiSuccess {Number} interest_rate_e 利率區間上限(%)
     * @apiSuccess {Number} instalment_s 期數區間下限(%)
     * @apiSuccess {Number} instalment_e 期數區間上限(%)
     * @apiSuccess {String} credit_level 信用評等 全部：all 複選使用逗號隔開
     * @apiSuccess {String} section 標的狀態 全部:all 全案:0 部分案:1
     * @apiSuccess {String} national 信用評等 全部:all 私立:0 國立:1
     * @apiSuccess {String} system 學制 全部:all 0:大學 1:碩士 2:博士
     * @apiSuccess {String} sex 性別 全部:all 女性:F 男性:M
	 * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"product_id": "all",
     * 			"credit_level": "all",
     * 			"section": "all",
     * 			"interest_rate_s": 7,
     * 			"interest_rate_e": 10,
     * 			"instalment_s": 12,
     * 			"instalment_e": 12,
     * 			"sex": "all",
     * 			"system": "all",
     * 			"national": "all"
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
	 *
     */

	public function batch_get()
    {
        $batchData = [
            'product_id'		=> 'all',
            'credit_level'		=> 'all',
            'interest_rate_s'	=> 0,
            'interest_rate_e'	=> 20,
            'instalment_s'		=>  0,
            'instalment_e'		=> 24,
            'sex'				=> 'all',
            'system'			=> 'all',
            'national'			=> 'all',
            'ai_bidding' => 0,
            'target_amount' => 0,
            'daily_amount' => 0,
        ];

        $user_id 	= $this->user_info->id;
        $this->load->model('loan/batch_model');
        $data = $this->batch_model->get_by([
            'user_id' => $user_id,
            'type' => 0,
            'status' => 1,
        ]);
        if($data){
            $batchData = json_decode($data->filter);
            if($data->expire_time < time()){
                $batchData->ai_bidding = 0;
                $this->batch_model->update_by([
                    'id' => $data->id,
                ], [
                    'filter' => json_encode($batchData),
                ]);
            }
        }
        $this->response(['result' => 'SUCCESS','data' => $batchData]);
    }

	private function check_adult(){
        $Judicialperson = $this->user_info->investor == 1 && $this->user_info->company == 1?true:false;

		//檢查認證 NOT_VERIFIED
        $this->load->library('Certification_lib');
        $identity = $this->certification_lib->get_certification_info($this->user_info->id,CERTIFICATION_IDENTITY,$this->user_info->investor);
        $email = $this->certification_lib->get_certification_info($this->user_info->id,CERTIFICATION_EMAIL,$this->user_info->investor);
		if(empty($this->user_info->id_number) || !$identity || $identity->status!=1 ||
            !$email || $email->status!=1) {
			$this->response(['result' => 'ERROR','error' => NOT_VERIFIED ]);
		}

        if(!$Judicialperson){
            //檢查認證 NOT_VERIFIED_EMAIL
            if(empty($this->user_info->email) || $this->user_info->email==''){
                $this->response(['result' => 'ERROR','error' => NOT_VERIFIED_EMAIL]);
            }
        }

		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$this->load->model('user/user_bankaccount_model');
		$bank_account = $this->user_bankaccount_model->get_by([
			'investor'	=> $this->user_info->investor,
			'status'	=> 1,
			'user_id'	=> $this->user_info->id,
			'verify'	=> 1
		]);
		if(!$bank_account){
			$this->response(['result' => 'ERROR','error' => NO_BANK_ACCOUNT]);
		}



		if(get_age($this->user_info->birthday) < 18 && !$Judicialperson){
			$this->response(['result' => 'ERROR','error' => UNDER_AGE ]);
		}
	}

    private function sub_product_profile($product,$sub_product){
        return array(
            'id' => $product['id'],
            'visul_id' => $sub_product['visul_id'],
            'type' => $product['type'],
            'identity' => $product['identity'],
            'name' => $sub_product['name'],
            'description' => $sub_product['description'],
            'loan_range_s' => $sub_product['loan_range_s'],
            'loan_range_e' => $sub_product['loan_range_e'],
            'interest_rate_s' => $sub_product['interest_rate_s'],
            'interest_rate_e' => $sub_product['interest_rate_e'],
            'charge_platform' => $sub_product['charge_platform'],
            'charge_platform_min' => $sub_product['charge_platform_min'],
            'certifications' => $sub_product['certifications'],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'checkOwner' => $product['checkOwner'],
            'status' => $sub_product['status'],
        );
    }
    private function is_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }
}
