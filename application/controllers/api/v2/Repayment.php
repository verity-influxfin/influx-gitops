<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Repayment extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('transaction/transaction_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('Target_lib');
		$this->load->library('Prepayment_lib');
		$this->load->library('Contract_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
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
			//if(isset($tokenData->company) && $tokenData->company != 0 ){
			//	$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
			//}

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
     * @api {get} /v2/repayment/dashboard 借款端 我的還款
	 * @apiVersion 0.2.0
	 * @apiName GetRepaymentDashboard
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Object} next_repayment 當期待還
	 * @apiSuccess {Number} next_repayment.date 還款日期
	 * @apiSuccess {Number} next_repayment.amount 還款金額
	 * @apiSuccess {Object} accounts_payable 應付帳款
	 * @apiSuccess {Number} accounts_payable.principal 應付本金
	 * @apiSuccess {Number} accounts_payable.interest 應付利息
	 * @apiSuccess {Number} accounts_payable.delay_interest 應付延滯息
	 * @apiSuccess {Number} accounts_payable.liquidated_damages 應付違約金
	 * @apiSuccess {Object} funds 資金資訊
	 * @apiSuccess {Number} funds.total 資金總額
	 * @apiSuccess {String} funds.last_recharge_date 最後一次匯入日
	 * @apiSuccess {Number} funds.frozen 待交易餘額
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
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data": {
     * 			"accounts_payable": {
     * 				"principal": 39000,
     * 				"interest": 1236,
     * 				"delay_interest": 0,
     * 				"liquidated_damages": 0
     * 			},
     * 			"next_repayment": {
     * 				"date": "2019-03-10",
     * 				"amount": 8849
     * 			},
     * 			"funds": {
     * 				"total": 494745,
     * 				"last_recharge_date": "2019-01-17 19:57:50",
     * 				"frozen": 500
     * 			},
     * 				"bank_account": {
     * 				"bank_code": "005",
     * 				"branch_code": "0027",
     * 				"bank_account": "45645645645645"
     * 			},
     * 				"virtual_account": {
     * 				"bank_code": "013",
     * 				"branch_code": "0154",
     * 				"bank_name": "國泰世華商業銀行",
     * 				"branch_name": "信義分行",
     * 				"virtual_account": "56631108557856"
     * 			}
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
	 *
     */

	public function dashboard_get()
    {
		$input 		 			= $this->input->get();
		$user_id 	 			= $this->user_info->id;
        $company = isset($this->user_info->company)?$this->user_info->company:false;
        $virtual = CATHAY_VIRTUAL_CODE;
        $account = array(
            'bank_code'			=> CATHAY_BANK_CODE,
            'branch_code'		=> CATHAY_BRANCH_CODE,
            'bank_name'			=> CATHAY_BANK_NAME,
            'branch_name'		=> CATHAY_BRANCH_NAME,
        );

        if($company){
            $this->load->model('user/judicial_person_model');
            $judicial_person = $this->judicial_person_model->get_by(array('company_user_id'=>$this->user_info->id));
            if ($judicial_person) {
                $selling_type = $judicial_person->selling_type;

                if($selling_type == FOREX_CAR_DEALER){
                    $virtual = TAISHIN_VIRTUAL_CODE;
                    $account = [
                        'bank_code'			=> TAISHIN_BANK_CODE,
                        'branch_code'		=> TAISHIN_BRANCH_CODE,
                        'bank_name'			=> TAISHIN_BANK_NAME,
                        'branch_name'		=> TAISHIN_BRANCH_NAME,
                    ];
                }
            }
        }

		$next_repayment = [
			'date'	 => '',
			'amount' => 0,
		];
		$accounts_payable	= [
			'principal'			 => 0,
			'interest'			 => 0,
			'delay_interest'	 => 0,
			'liquidated_damages' => 0,
		];

        $transaction = $this->transaction_model->order_by('limit_date','desc')->get_many_by([
			'user_from'	=> $user_id,
			'status'	=> 1,
			'source' 	=> [
				SOURCE_AR_PRINCIPAL,
				SOURCE_AR_INTEREST,
				SOURCE_AR_DAMAGE,
				SOURCE_AR_DELAYINTEREST
			],
		]);

		if($transaction){
            // 全部案件
            $all_targets = array_column($transaction, 'limit_date', 'target_id');
            $all_targets_id = array_keys($all_targets);
            // 逾期案件
            $delay_targets = $this->target_model->as_array()->order_by('delay_days', 'desc')->get_many_by([
                'id' => $all_targets_id,
                'delay' => 1,
                'delay_days >' => GRACE_PERIOD,
                'status' => TARGET_REPAYMENTING
            ]);
            $delay_targets_id = array_column($delay_targets, 'id');
            $first_delay_repayment_date = $all_targets[current($delay_targets_id)] ?? '';
            $delay_repayment_amount = 0;
            // 正常案件
            $normal_targets_param = [
                'id' => $all_targets_id,
                'status' => TARGET_REPAYMENTING
            ];
            if ( ! empty($delay_targets_id))
            {
                $normal_targets_param['id NOT'] = $delay_targets_id;
            }
            $normal_targets = $this->target_model->as_array()->order_by('loan_date', 'asc')->get_many_by($normal_targets_param);
            $normal_targets_id = array_column($normal_targets, 'id');
            $first_normal_repayment_date = $all_targets[current($normal_targets_id)] ?? '';
            $normal_repayment_amount = 0;

			foreach($transaction as $key => $value){
				switch($value->source){
					case SOURCE_AR_PRINCIPAL:
						$accounts_payable['principal'] 			+= $value->amount;
						break;
					case SOURCE_AR_INTEREST:
						$accounts_payable['interest'] 			+= $value->amount;
						break;
					case SOURCE_AR_DELAYINTEREST:
						$accounts_payable['delay_interest'] 	+= $value->amount;
						break;
					case SOURCE_AR_DAMAGE:
						$accounts_payable['liquidated_damages'] += $value->amount;
						break;
					default:
						break;
				}

                if (in_array($value->target_id, $delay_targets_id))
                {
                    $delay_repayment_amount += $value->amount;
                }

                if (in_array($value->target_id, $normal_targets_id) && $value->limit_date <= $first_normal_repayment_date)
                {
                    $normal_repayment_amount += $value->amount;
                }
            }
            $next_repayment['amount'] = $delay_repayment_amount === 0 ? $normal_repayment_amount : $delay_repayment_amount + $normal_repayment_amount;
            // 若有逾期案件，以首逾日期示之
            $next_repayment['date'] = ! empty($first_delay_repayment_date) ? $first_delay_repayment_date : $first_normal_repayment_date;
		}

        $virtualAcount = $this->virtual_account_model->get_by([
            'investor'	=> 0,
            'user_id'	=> $user_id,
            'virtual_account like' => $virtual . '%'
        ]);
		if($virtualAcount){
            $account['virtual_account'] = $virtualAcount->virtual_account;
            $virtual_account = $account;
			$funds = $this->transaction_lib->get_virtual_funds($virtualAcount->virtual_account);
		}else{
			$funds			 = array(
				'total'					=> 0,
				'last_recharge_date'	=> '',
				'frozen'				=> 0,
                'frozenes'              => [
                    'invest'   => 0,
                    'transfer' => 0,
                    'withdraw' => 0,
                    'other'    => 0
                ]
			);
			$virtual_account	= array(
				'bank_code'			=> '',
				'branch_code'		=> '',
				'bank_name'			=> '',
				'branch_name'		=> '',
				'virtual_account'	=> '',
			);
		}

		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$user_bankaccount 	= $this->user_bankaccount_model->get_by([
			'investor'	=> 0,
			'status'	=> 1,
			'user_id'	=> $user_id,
			'verify'	=> 1
		]);
		if($user_bankaccount){
			$bank_account 		= array(
				'bank_code'		=> $user_bankaccount->bank_code,
				'branch_code'	=> $user_bankaccount->branch_code,
				'bank_account'	=> $user_bankaccount->bank_account,
			);
		}else{
			$bank_account 		= array(
				'bank_code'		=> '',
				'branch_code'	=> '',
				'bank_account'	=> '',
			);
		}

		$data	= array(
			'accounts_payable'	=> $accounts_payable,
			'next_repayment'	=> $next_repayment,
			'funds'				=> $funds,
			'bank_account'		=> $bank_account,
			'virtual_account'	=> $virtual_account,
		);

		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }

	/**
     * @api {get} /v2/repayment/list 借款方 我的還款列表
	 * @apiVersion 0.2.0
	 * @apiName GetRepaymentList
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {Number} product_id 產品ID
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Number} amount 申請金額
	 * @apiSuccess {Number} loan_amount 核准金額
	 * @apiSuccess {Number} interest_rate 年化利率
	 * @apiSuccess {Number} instalment 期數
	 * @apiSuccess {Number} repayment 計息方式
	 * @apiSuccess {Number} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} delay_days 逾期天數
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
     * @apiSuccess {Number} remaining_principal 剩餘本金
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {Object} next_repayment 最近一期應還款
     * @apiSuccess {String} next_repayment.date 還款日
     * @apiSuccess {Number} next_repayment.instalment 期數
     * @apiSuccess {Number} next_repayment.amount 金額
     * @apiSuccess {Object} prepayment_info 提前還款資訊
     * @apiSuccess {String} prepayment_info.settlement_date 結息日
     * @apiSuccess {Number} prepayment_info.remaining_instalment 剩餘期數
     * @apiSuccess {Number} prepayment_info.remaining_principal 剩餘本金
     * @apiSuccess {Number} prepayment_info.interest_payable 應付利息
     * @apiSuccess {Number} prepayment_info.liquidated_damages 違約金（提還違約金）
     * @apiSuccess {Number} prepayment_info.total 合計
     *
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id": 9,
     * 				"target_no": "STN2019011414213",
     * 				"product_id": 1,
     * 				"user_id": 19,
     * 				"amount": 5000,
     * 				"loan_amount": 5000,
     * 				"interest_rate": 7,
     * 				"instalment": 3,
     * 				"repayment": 1,
     * 				"delay": 0,
     * 				"delay_days": 0,
     * 				"status": 5,
     * 				"sub_status": 0,
     *              “remaining_principal”： 0,
     * 				"created_at": 1547444954,
     * 				"next_repayment": {
     * 					"date": "2019-03-10",
     * 					"instalment": 1,
     * 					"amount": 1687
     * 				},
     *              "prepayment_info": {
     *                  "settlement_date": "2019-01-25",
     *                  "remaining_instalment": 3,
     *                  "remaining_principal": 5000,
     *                  "interest_payable": 11,
     *                  "liquidated_damages": 250,
     *                  "total": 5261
     *              }
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse IsInvestor
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     *
     */
	public function list_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
        $product_list = $this->config->item('product_list');
		$user_id 			= $this->user_info->id;
		$targets 			= $this->target_model->get_many_by([
			'user_id'	=> $user_id,
            'status' => [TARGET_REPAYMENTING, TARGET_REPAYMENTED, TARGET_BANK_REPAYMENTING, TARGET_BANK_REPAYMENTED]
		]);
		$list				= [];
		if(!empty($targets)){
			foreach($targets as $key => $value){
                $product = $product_list[$value->product_id];
                $sub_product_id = $value->sub_product_id;
                $product_name = $product['name'];
                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                    $product_name = $product['name'];
                }

                $this->load->library('target_lib');
                $repayment_schedule = $this->target_lib->get_repayment_schedule($value);
                if ( ! empty($repayment_schedule))
                {
                    $next_repayment = $repayment_schedule[array_key_first($repayment_schedule)];
                }
                else
                {
                    $next_repayment = ['instalment' => '', 'date' => '', 'amount' => 0];
                }

                $pay_off_at = $this->target_lib->get_pay_off_date($value);

                $list[] = [
					'id' 				=> intval($value->id),
					'target_no' 		=> $value->target_no,
                    'product_name' => $product_name,
					'product_id' 		=> intval($value->product_id),
					'sub_product_id' 	=> intval($value->sub_product_id),
					'user_id' 			=> intval($value->user_id),
					'amount' 			=> intval($value->amount),
					'loan_amount' 		=> intval($value->loan_amount),
					'interest_rate' 	=> floatval($value->interest_rate),
					'instalment' 		=> intval($value->instalment),
					'repayment' 		=> intval($value->repayment),
					'delay' 			=> intval($value->delay),
					'delay_days' 		=> intval($value->delay_days),
					'status' 			=> intval($value->status),
					'sub_status' 		=> intval($value->sub_status),
                    'remaining_principal' => intval($this->target_lib->get_amortization_table($value)["remaining_principal"]),
					'created_at' 		=> intval($value->created_at),
					'next_repayment' 	=> $next_repayment,
					'pay_off_at' 	    => $pay_off_at,
                    'prepayment_info'   => $this->prepayment_lib->get_prepayment_info($value)??[]
				];

			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
    }

	/**
     * @api {get} /v2/repayment/info/:id 借款方 我的還款資訊
	 * @apiVersion 0.2.0
	 * @apiName GetRepaymentInfo
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id Targets ID
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Target ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {Number} product_id 產品ID
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Number} amount 申請金額
	 * @apiSuccess {Number} loan_amount 借款金額
	 * @apiSuccess {Number} platform_fee 平台手續費
	 * @apiSuccess {Number} credit_level 信用評等
	 * @apiSuccess {Number} interest_rate 年化利率(%)
	 * @apiSuccess {Number} damage_rate 違約金率(%)
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {Number} instalment 期數
	 * @apiSuccess {Number} repayment 計息方式
	 * @apiSuccess {Number} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} delay_days 逾期天數
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
	 * @apiSuccess {Number} created_at 申請日期
	 * @apiSuccess {Object} next_repayment 最近一期應還款
	 * @apiSuccess {String} next_repayment.date 還款日
	 * @apiSuccess {Number} next_repayment.instalment 期數
	 * @apiSuccess {Number} next_repayment.amount 金額
	 * @apiSuccess {Number} next_repayment.principal 本金
	 * @apiSuccess {Number} next_repayment.interest 利息
	 * @apiSuccess {Number} next_repayment.delay_interest 延滯息
	 * @apiSuccess {Number} next_repayment.liquidated_damages 違約金
	 * @apiSuccess {Object} amortization_schedule 還款計畫
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
	 * @apiSuccess {Number} legal_collection 法催中
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id": 9,
     * 			"target_no": "STN2019011414213",
     * 			"product_id": 1,
     * 			"user_id": 19,
     * 			"amount": 5000,
     * 			"loan_amount": 5000,
     * 			"platform_fee": 500,
     * 			"credit_level": 3,
     * 			"interest_rate": 7,
     * 			"damage_rate": 5,
     * 			"reason": "",
     * 			"remark": "",
     * 			"instalment": 3,
     * 			"repayment": 1,
     * 			"delay": 0,
     * 			"delay_days": 0,
     * 			"status": 5,
     * 			"sub_status": 0,
     * 			"created_at": 1547444954,
     * 			"next_repayment": {
     * 				"date": "2019-03-10",
     * 				"instalment": 1,
     * 				"amount": 1687,
     * 				"principal": 1634,
     * 				"interest": 53,
     * 				"delay_interest": 0,
     * 				"liquidated_damages": 0
     * 			},
     * 			"amortization_schedule": {
     * 				"amount": 5000,
     * 				"remaining_principal": 5000,
     * 				"instalment": 3,
     * 				"rate": 7,
     * 				"total_payment": 5083,
     * 				"date": "2019-01-14",
     * 				"end_date": "2019-05-10",
     * 				"sub_loan_fees": 0,
     * 				"platform_fees": 500,
     * 				"list": {
     * 					"1": {
     * 						"instalment": 1,
     * 						"total_payment": 1687,
     * 						"repayment": 0,
     * 						"interest": 53,
     * 						"principal": 1634,
     * 						"delay_interest": 0,
     * 						"liquidated_damages": 0,
     * 						"days": 55,
     * 						"remaining_principal": 5000,
     * 						"repayment_date": "2019-03-10"
     * 					},
     * 					"2": {
     * 						"instalment": 2,
     * 						"total_payment": 1687,
     * 						"repayment": 0,
     * 						"interest": 20,
     * 						"principal": 1667,
     * 						"delay_interest": 0,
     * 						"liquidated_damages": 0,
     * 						"days": 31,
     * 						"remaining_principal": 3366,
     * 						"repayment_date": "2019-04-10"
     * 					},
     * 					"3": {
     * 						"instalment": 3,
     * 						"total_payment": 1709,
     * 						"repayment": 0,
     * 						"interest": 10,
     * 						"principal": 1699,
     * 						"delay_interest": 0,
     * 						"liquidated_damages": 0,
     * 						"days": 30,
     * 						"remaining_principal": 1699,
     * 						"repayment_date": "2019-05-10"
     * 					}
     * 				}
     *    		},
	 * 			"legal_collection": 0
     *    	}
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
     */
	public function info_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$data				= [];
		if(!empty($target) && in_array($target->status,[5,10])){

			if($target->user_id != $user_id){
				$this->response([ 'result' => 'ERROR','error' => APPLY_NO_PERMISSION ]);
			}

            $product_list = $this->config->item('product_list');
            $product = $product_list[$target->product_id];
            $product_name = $product['name'];
            $sub_product_id = $target->sub_product_id;
            if($this->is_sub_product($product,$sub_product_id)){
                $product = $this->trans_sub_product($product,$sub_product_id);
                $product_name = $product['name'];
            }

            $targetDatas = [];
            if($product['visul_id'] == 'DS2P1'){
                $targetData = json_decode($target->target_data);
                $cer_group['car_file'] = [1,'車籍文件'];
                $cer_group['car_pic'] = [1,'車輛外觀照片'];
                $targetDatas = [
                    'brand' => $targetData->brand,
                    'name' => $targetData->name,
                    'selected_image' => $targetData->selected_image,
                    'vin' => $targetData->vin,
                    'purchase_time' => $targetData->purchase_time,
                    'factory_time' => $targetData->factory_time,
                    'product_description' => $targetData->product_description,
                ];
                foreach ($product['targetData'] as $key => $value) {
                    if(in_array($key,['car_history_image','car_title_image','car_import_proof_image','car_artc_image','car_others_image','car_photo_front_image','car_photo_back_image','car_photo_all_image','car_photo_date_image','car_photo_mileage_image'])){
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

                $virtual_account	= array(
                    'bank_code'			=> '',
                    'branch_code'		=> '',
                    'bank_name'			=> '',
                    'branch_name'		=> '',
                    'virtual_account'	=> '',
                );
                $bank_account 		= array(
                    'bank_code'		=> '',
                    'branch_code'	=> '',
                    'bank_account'	=> '',
                );

                $virtual 	= $this->virtual_account_model->get_by([
                    'investor' => 0,
                    'user_id' => $user_id,
                    'virtual_account like' => TAISHIN_VIRTUAL_CODE.'%'
                ]);
                if($virtual){
                    $virtual_account	= array(
                        'bank_code'			=> TAISHIN_BANK_CODE,
                        'branch_code'		=> TAISHIN_BRANCH_CODE,
                        'bank_name'			=> TAISHIN_BANK_NAME,
                        'branch_name'		=> TAISHIN_BRANCH_NAME,
                        'virtual_account'	=> $virtual->virtual_account,
                    );
                }

                //檢查金融卡綁定 NO_BANK_ACCOUNT
                $user_bankaccount 	= $this->user_bankaccount_model->get_by([
                    'investor'	=> 0,
                    'status'	=> 1,
                    'user_id'	=> $user_id,
                    'verify'	=> 1
                ]);
                if($user_bankaccount){
                    $bank_account 		= array(
                        'bank_code'		=> $user_bankaccount->bank_code,
                        'branch_code'	=> $user_bankaccount->branch_code,
                        'bank_account'	=> $user_bankaccount->bank_account,
                    );
                }
                $targetDatas['virtual_account'] = $virtual_account;
                $targetDatas['bank_account'] = $bank_account;
            }

			$next_repayment = [
				'date' 				=> '',
				'instalment'		=> 0,
				'amount'			=> 0,
				'principal'			=> 0,
				'interest'			=> 0,
				'delay_interest'	=> 0,
				'liquidated_damages'=> 0,

			];
			if($target->status==5){
				$transaction = $this->transaction_model->order_by('limit_date','asc')->get_many_by(array(
					'target_id'	=> $target->id,
					'user_from'	=> $user_id,
					'status'	=> 1
				));

				if($transaction){
					$first 							= current($transaction);
					$next_repayment['date'] 		= $first->limit_date;
					$next_repayment['instalment']  	= intval($first->instalment_no);
					foreach($transaction as $key => $value){
						if($value->limit_date == $next_repayment['date']){
							$next_repayment['amount'] += $value->amount;
							switch($value->source){
								case SOURCE_AR_PRINCIPAL:
									$next_repayment['principal'] 		+= $value->amount;
									break;
								case SOURCE_AR_INTEREST:
									$next_repayment['interest'] 		+= $value->amount;
									break;
								case SOURCE_AR_DELAYINTEREST:
									$next_repayment['delay_interest'] 	+= $value->amount;
									break;
								case SOURCE_AR_DAMAGE:
									$next_repayment['liquidated_damages'] += $value->amount;
									break;
								default:
									break;
							}
						}
					}
				}
			}
            $subloan_target_id ='';
			if($target->sub_status==1){
                $this->load->library('Subloan_lib');
                $subloan = $this->subloan_lib->get_subloan($target);
                $subloan_target_id = $subloan?intval($subloan->new_target_id):'';
            }

            $reason = $target->reason;
            $json_reason = json_decode($reason);
            if(isset($json_reason->reason)){
                $reason = $json_reason->reason.' - '.$json_reason->reason_description;
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

			$data = [
				'id' 				=> intval($target->id),
				'target_no' 		=> $target->target_no,
                'product_name' => $product_name,
				'product_id' 		=> intval($target->product_id),
				'sub_product_id' 	=> intval($target->sub_product_id),
				'user_id' 			=> intval($target->user_id),
				'amount' 			=> intval($target->amount),
				'loan_amount' 		=> intval($target->loan_amount),
				'platform_fee' 		=> intval($target->platform_fee),
				'credit_level' 		=> intval($target->credit_level),
				'interest_rate' 	=> floatval($target->interest_rate),
				'damage_rate' 		=> intval($target->damage_rate),
				'reason'		=> $reason,
				'remark' 			=> $target->remark,
                'targetDatas' => $targetDatas,
                'instalment' 		=> intval($target->instalment),
				'repayment' 		=> intval($target->repayment),
				'delay' 			=> intval($target->delay),
				'delay_days' 		=> intval($target->delay_days),
				'status' 			=> intval($target->status),
				'sub_status' 		=> intval($target->sub_status),
				'subloan_target_id' => $subloan_target_id,
				'created_at' 		=> intval($target->created_at),
				'next_repayment' 	=> $next_repayment,
				'amortization_schedule' => $this->target_lib->get_amortization_table($target),
				'legal_collection'	=> $legalCollection
			];

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

	/**
     * @api {get} /v2/repayment/prepayment/:id 借款方 提前還款資訊
	 * @apiVersion 0.2.0
	 * @apiName GetRepaymentPrepayment
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token

	 * @apiParam {Number} id Targets ID
	 * @apiDescription 只有正常還款的狀態才可申請，逾期或寬限期內都將不通過
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} settlement_date 結息日
	 * @apiSuccess {Number} remaining_instalment 剩餘期數
	 * @apiSuccess {Number} remaining_principal 剩餘本金
	 * @apiSuccess {Number} interest_payable 應付利息
	 * @apiSuccess {Number} liquidated_damages 違約金（提還違約金）
	 * @apiSuccess {Number} total 合計
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 	"result": "SUCCESS",
     * 		"data": {
     * 			"settlement_date": "2019-01-25",
     * 			"remaining_instalment": 3,
     * 			"remaining_principal": 5000,
     * 			"interest_payable": 11,
     * 			"liquidated_damages": 250,
     * 			"total": 5261
     * 		}
     * }
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
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {Object} 407
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
     */
	public function prepayment_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($input['target_id']);
		$data				= [];
		if(!empty($target)){

			if($target->user_id != $user_id){
				$this->response(['result' => 'ERROR','error' => APPLY_NO_PERMISSION]);
			}

			if($target->status != 5 || $target->delay_days > 0 ){
				$this->response(['result' => 'ERROR','error' => APPLY_STATUS_ERROR]);
			}

			$data = $this->prepayment_lib->get_prepayment_info($target);
			$this->response(['result' => 'SUCCESS','data' => $data]);
		}
		$this->response(['result' => 'ERROR','error' => APPLY_NOT_EXIST]);
    }

	/**
     * @api {post} /v2/repayment/prepayment/:id 借款方 申請提前還款
	 * @apiVersion 0.2.0
	 * @apiName PostRepaymentPrepayment
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id Targets ID
	 * @apiDescription 只有正常還款的狀態才可申請，逾期或寬限期內都將不通過
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
     * @apiError 903 已申請提前還款或產品轉換
     * @apiErrorExample {Object} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
	public function prepayment_post()
    {
		$input 				= $this->input->post(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($input['target_id']);
		if(!empty($target)){

			if($target->status != 5 || $target->delay_days > 0 || $target->script_status != 0){
				$this->response(['result' => 'ERROR','error' => APPLY_STATUS_ERROR]);
			}

			if($target->user_id != $user_id){
				$this->response(['result' => 'ERROR','error' => APPLY_NO_PERMISSION]);
			}

            if(!in_array($target->sub_status,[0,8,10])){
                $this->response(array('result' => 'ERROR','error' => TARGET_HAD_SUBSTATUS ));
            }

			$rs = $this->prepayment_lib->apply_prepayment($target);
			if($rs){
				$this->response(['result' => 'SUCCESS']);
			}else{
				$this->response(['result' => 'ERROR','error' => NOT_ENOUGH_FUNDS]);
			}
		}
		$this->response(['result' => 'ERROR','error' => APPLY_NOT_EXIST]);
    }

    /**
     * @api {post} /v2/repayment/prepayment_list/:id 借款方 申請提前還款
     * @apiVersion 0.2.0
     * @apiName PostRepaymentPrepayment
     * @apiGroup Repayment
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} id Targets ID，用「,」分割Target的id
     * @apiDescription 只有正常還款的狀態才可申請，逾期或寬限期內都將不通過
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
     * @apiError 903 已申請提前還款或產品轉換
     * @apiErrorExample {Object} 903
     *     {
     *       "result": "ERROR",
     *       "error": "903"
     *     }
     */
    public function prepayment_list_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;

        if(isset($input['target_id'])){
            $target_ids = explode(',', $input['target_id']);
            $list = $this->target_model->get_many_by([
                'id' => $target_ids,
            ]);
            if ($list && count($list) == count($target_ids)) {

                $virtualAccountParm = [
                    'status'		=> 1,
                    'investor'		=> 0,
                    'user_id'		=> $user_id
                ];
                $this->load->model('user/virtual_account_model');
                $virtual_account = $this->virtual_account_model->get_by($virtualAccountParm);
        
                if($virtual_account){
                    $funds = $this->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
                    $virtual_account_balance = $funds['total'] - $funds['frozen'];
                }else{
                    $this->response(['result' => 'ERROR','error' => NOT_ENOUGH_FUNDS]);
                }
                foreach ($list as $k => $target) {
    
                    if($target->status != 5 || $target->delay_days > 0 || $target->script_status != 0){
                        $this->response(['result' => 'ERROR','error' => APPLY_STATUS_ERROR]);
                    }
    
                    if($target->user_id != $user_id){
                        $this->response(['result' => 'ERROR','error' => APPLY_NO_PERMISSION]);
                    }
    
                    if(!in_array($target->sub_status,[0,8,10])){
                        $this->response(array('result' => 'ERROR','error' => TARGET_HAD_SUBSTATUS ));
                    }
                    $info = $this->prepayment_lib->get_prepayment_info($target);
                    $virtual_account_balance -= intval($info['total']);
                    if($virtual_account_balance < 0 || empty($info)){
                        $this->response(['result' => 'ERROR','error' => NOT_ENOUGH_FUNDS]);
                    }
                }
                foreach ($list as $k => $target) {
                    $rs = $this->prepayment_lib->apply_prepayment($target);
                }
                if($rs){
                        $this->response(['result' => 'SUCCESS']);
                        
                    }else{
                        $this->response(['result' => 'ERROR','error' => APPLY_STATUS_ERROR]);
                    }
            }
        }
        $this->response(['result' => 'ERROR','error' => APPLY_NOT_EXIST]);
    }

	/**
     * @api {get} /v2/repayment/contract/:id 借款方 合約列表
	 * @apiName GetRepaymentContract
	 * @apiVersion 0.2.0
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} id Targets ID
	 *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} title 合約標題
	 * @apiSuccess {String} contract 合約內容
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
	 *				{
	 *					"title": "借貸契約書",
	 * 					"contract":"我就是合約啊！！我就是合約啊！！我就是合約啊！！"
	 *				}
	 *			]
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
     */
	public function contract_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$data				= array();
		if(!empty($target)){

			$list = array();

			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => APPLY_NO_PERMISSION ));
			}

			if(in_array($target->status,array(5,10))){
				$where = array(
					'target_id'	=> $target->id,
					'status'	=> array(3,10)
				);
				$investments = $this->investment_model->get_many_by($where);
				if($investments){
					foreach($investments as $key => $value){
						$contract_data = $this->contract_lib->get_contract($value->contract_id);
						$contract = $contract_data['content'];
						$list[] = array('title'=>$contract_data['title'],'contract'=>$contract_data['content']);
					}
				}
			}else if(in_array($target->status,array(1,2,3,4))){
				$contract_data 	= $this->contract_lib->get_contract($target->contract_id);
				$contract 		= $contract_data['content'];
				$list[] 		= array('title'=>$contract_data['title'],'contract'=>$contract_data['content']);
			}

			$data['list'] 	= $list;

			$this->response(array('result' => 'SUCCESS','data' => $data ));
		}
		$this->response(array('result' => 'ERROR','error' => APPLY_NOT_EXIST ));
    }

	/**
     * @api {post} /v2/repayment/withdraw 借款方 提領申請
	 * @apiVersion 0.2.0
	 * @apiName PostRepaymentWithdraw
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {Number} amount 提領金額
     * @apiParam {String} transaction_password 交易密碼
	 *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {String} target_id Targets ID
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
     * @apiError 209 未設置交易密碼
     * @apiErrorExample {Object} 209
     *     {
     *       "result": "ERROR",
     *       "error": "209"
     *     }
	 *
     * @apiError 210 交易密碼錯誤
     * @apiErrorExample {Object} 210
     *     {
     *       "result": "ERROR",
     *       "error": "210"
     *     }
	 *
     * @apiError 211 可用餘額不足
     * @apiErrorExample {Object} 211
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
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}

		//檢查認證 NOT_VERIFIED
		if(empty($this->user_info->id_number) || $this->user_info->id_number==''){
			$this->response(['result' => 'ERROR','error' => NOT_VERIFIED ]);
		}

		//檢查金融卡綁定 NO_BANK_ACCOUNT
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

		if($this->user_info->transaction_password != sha1($input['transaction_password'])){
			$this->response(array('result' => 'ERROR','error' => TRANSACTION_PASSWORD_ERROR ));
		}

        $input['amount'] = (int) $input['amount'];
        if ($this->transaction_lib->check_minimum_withdraw_amount($input['amount']) === FALSE)
        {
            $this->response(['result' => 'ERROR', 'error' => LOW_WITHDRAW_AMOUNT]);
        }
        $withdraw = $this->transaction_lib->withdraw($user_id, $input['amount'], 0);
		if($withdraw){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR','error' => NOT_ENOUGH_FUNDS ));
		}
    }

	/**
     * @api {get} /v2/repayment/passbook 借款方 虛擬帳戶明細
	 * @apiVersion 0.2.0
	 * @apiName GetRepaymentPassbook
     * @apiGroup Repayment
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} amount 金額
     * @apiSuccess {String} bank_amount 帳戶餘額
     * @apiSuccess {String} remark 備註
     * @apiSuccess {int} source 備註代號
     * @apiSuccess {String} tx_datetime 交易時間
     * @apiSuccess {Number} created_at 入帳時間
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				{
     * 					"amount": 500000,
     * 					"bank_amount": 500000,
     * 					"remark": "平台代收",
     *                  "source": 1,
     * 					"tx_datetime": "2019-01-17 19:57:50",
     * 					"created_at": 1547726281
     * 				},
     * 				{
     * 					"amount": -5,
     * 					"bank_amount": 499995,
     * 					"remark": "還款利息",
     *                  "source": 14,
     * 					"tx_datetime": "2019-01-17 19:59:24",
     * 					"created_at": 1547726364
     * 				},
     * 				{
     * 					"amount": -5000,
     * 					"bank_amount": 494995,
     * 					"remark": "還款本金",
     *                  "source": 12,
     * 					"tx_datetime": "2019-01-17 19:59:24",
     * 					"created_at": 1547726364
     * 				},
     * 				{
     * 					"amount": -250,
     * 					"bank_amount": 494745,
     * 					"remark": "提前還款違約金",
     *                  "source": 8,
     * 					"tx_datetime": "2019-01-17 19:59:25",
     * 					"created_at": 1547726365
     * 				}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse InputError
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
     */
	public function passbook_get()
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$list		= [];
		//檢查認證 NOT_VERIFIED
		if(empty($this->user_info->id_number) || $this->user_info->id_number==''){
			$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
		}
		//檢查金融卡綁定 NO_BANK_ACCOUNT
		$bank_account = $this->user_bankaccount_model->get_by([
			'investor'	=> $investor,
			'status'	=> 1,
			'user_id'	=> $user_id,
			'verify'	=> 1
		]);
		if(!$bank_account){
			$this->response(array('result' => 'ERROR','error' => NO_BANK_ACCOUNT ));
		}

		$virtual_account = $this->virtual_account_model->get_by([
			'investor'	=> 0,
			'user_id'	=> $user_id
		]);
		$passbook_list = $this->passbook_lib->get_passbook_list($virtual_account->virtual_account,150);
		if($passbook_list){
			$transaction_source = $this->config->item('transaction_source');
			foreach($passbook_list as $key => $value){
				$value['remark'] = json_decode($value['remark'],TRUE);
				$remark = isset($value['remark']['source']) && $value['remark']['source']?$transaction_source[$value['remark']['source']]:'';
				$list[] = [
					'amount' 		=> $value['amount'],
					'bank_amount'	=> $value['bank_amount'],
					'remark'		=> $remark,
                    'source'        => intval($value['remark']['source'] ?? ''),
					'tx_datetime'	=> $value['tx_datetime'],
					'created_at'	=> $value['created_at'],
				];
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => ['list' => $list]));
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
