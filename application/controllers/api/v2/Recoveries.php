<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . '/libraries/REST_Controller.php');
use Symfony\Component\HttpClient\HttpClient;

class Recoveries extends REST_Controller
{

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user/virtual_account_model');
        $this->load->model('user/user_bankaccount_model');
        $this->load->model('loan/investment_model');
        $this->load->model('transaction/transaction_model');
        $this->load->library('Transaction_lib');
        $this->load->library('Target_lib');
        $this->load->library('Transfer_lib');
        $this->load->library('Passbook_lib');

        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
        if (!in_array($method, $nonAuthMethods)) {
            $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : "";
            $tokenData = AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
                $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            //只限出借人
            if ($tokenData->investor != 1) {
                $this->response(array('result' => 'ERROR', 'error' => NOT_INVERTOR));
            }

            $this->user_info = $this->user_model->get($tokenData->id);
            if ($tokenData->auth_otp != $this->user_info->auth_otp) {
                $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            if ($this->user_info->block_status != 0) {
                $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
            }

            if ($this->request->method != 'get') {
                $this->load->model('log/log_request_model');
                $this->log_request_model->insert(
                    array(
                        'method' => $this->request->method,
                        'url' => $this->uri->uri_string(),
                        'investor' => $tokenData->investor,
                        'user_id' => $tokenData->id,
                        'agent' => $tokenData->agent,
                    )
                );
            }

            $this->user_info->investor = $tokenData->investor;
            $this->user_info->company = $tokenData->company;
            $this->user_info->incharge = $tokenData->incharge;
            $this->user_info->agent = $tokenData->agent;
            $this->user_info->expiry_time = $tokenData->expiry_time;
        }
    }


    /**
     * @api {get} /v2/recoveries/dashboard 出借方 我的帳戶
     * @apiVersion 0.2.0
     * @apiName GetRecoveriesDashboard
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} payable 待匯款
     * @apiSuccess {Object} accounts_receivable 應收帳款
     * @apiSuccess {Number} accounts_receivable.principal 應收本金
     * @apiSuccess {Number} accounts_receivable.interest 應收利息
     * @apiSuccess {Number} accounts_receivable.delay_interest 應收延滯息
     * @apiSuccess {Object} income 收入
     * @apiSuccess {Number} income.interest 已收利息
     * @apiSuccess {Number} income.delay_interest 已收延滯息
     * @apiSuccess {Number} income.other 已收補貼
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
     *        "result":"SUCCESS",
     *        "data":{
     *            "payable": "50000",
     *            "accounts_receivable": {
     *                "principal": 40000,
     *                "interest": 1280,
     *                "delay_interest": 0
     *            },
     *            "income": {
     *                "interest": 0,
     *                "delay_interest": 0,
     *                "other": 0
     *            },
     *            "funds": {
     *                "total": 960000,
     *                "last_recharge_date": "2019-01-14 14:12:10",
     *                "frozen": 0
     *            },
     *            "bank_account": {
     *                "bank_code": "004",
     *                "branch_code": "0037",
     *                "bank_account": "123123123132"
     *            },
     *            "virtual_account": {
     *                "bank_code": "013",
     *                "branch_code": "0154",
     *                "bank_name": "國泰世華商業銀行",
     *                "branch_name": "信義分行",
     *                "virtual_account": "56639164278638"
     *            }
     *        }
     *    }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse NotInvestor
     *
     */

    public function dashboard_get()
    {
        $user_id = $this->user_info->id;
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            getenv('ENV_ERP_HOST') . '/recoveries/dashboard',
            [
                'query' => ['user_id' => $user_id],
                'headers' => ['Content-Type' => 'application/json'],
            ]
        );
        $this->response(array('result' => 'SUCCESS', 'data' => $response->toArray()));
    }

    /**
     * @api {get} /v2/recoveries/list 出借方 還款中債權列表
     * @apiVersion 0.2.0
     * @apiName GetRecoveriesList
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {String} id Investments ID
     * @apiSuccess {String} loan_amount 債權金額
     * @apiSuccess {String} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
     * @apiSuccess {String} transfer_status 債權轉讓狀態 0:無 1:已申請 2:移轉成功
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
     * @apiSuccess {Number} target.delay 是否逾期 0:無 1:逾期中
     * @apiSuccess {Number} target.delay_days 逾期天數
     * @apiSuccess {String} target.loan_date 放款日期
     * @apiSuccess {Number} target.status 標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
     * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
     * @apiSuccess {Object} next_repayment 最近一期還款
     * @apiSuccess {String} next_repayment.date 還款日
     * @apiSuccess {String} next_repayment.instalment 期數
     * @apiSuccess {String} next_repayment.amount 金額
     * @apiSuccess {Object} accounts_receivable 應收帳款
     * @apiSuccess {Number} accounts_receivable.principal 應收本金
     * @apiSuccess {Number} accounts_receivable.interest 應收利息
     * @apiSuccess {Number} accounts_receivable.delay_interest 應收延滯息
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *        "result":"SUCCESS",
     *        "data":{
     *            "list":[
     *            {
     *                "id":"1",
     *                "amount":"50000",
     *                "loan_amount":"",
     *                "status":"3",
     *                "transfer_status":"0",
     *                "target": {
     *                    "id": 9,
     *                    "target_no": "STN2019011414213",
     *                    "product_id": 1,
     *                    "user_id": 19,
     *                    "loan_amount": 5000,
     *                    "credit_level": 3,
     *                    "interest_rate": 7,
     *                    "instalment": 3,
     *                    "repayment": 1,
     *                    "delay": 0,
     *                    "delay_days": 0,
     *                    "loan_date": "2019-01-14",
     *                    "status": 5,
     *                    "sub_status": 0
     *                },
     *                "next_repayment": {
     *                    "date": "2019-03-10",
     *                    "instalment": 1,
     *                    "amount": 1687
     *                },
     *                "accounts_receivable": {
     *                    "principal": 5000,
     *                    "interest": 83,
     *                    "delay_interest": 0
     *                },
     *                "legal_collection": 1
     *            }
     *            ]
     *        }
     *    }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse NotInvestor
     *
     */
    public function list_get()
    {
        $input = $this->input->get(NULL, TRUE);
        $product_list = $this->config->item('product_list');
        $this->config->load('loanmanager');
        $pushTool = $this->config->item('pushTool');
        $pushType = $this->config->item('pushType');
        $pushResultStatus = $this->config->item('pushResultStatus');
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $investments = $this->investment_model->get_many_by([
            'user_id' => $user_id,
            'status' => 3
        ]);
        $list = [];
        if (!empty($investments)) {
            $instalment_data = [];
            $transaction = $this->transaction_model->order_by('limit_date', 'asc')->get_many_by([
                'user_to' => $user_id,
                'status' => 1
            ]);
            if ($transaction) {
                foreach ($transaction as $key => $value) {
                    if (!isset($instalment_data[$value->investment_id])) {
                        $instalment_data[$value->investment_id] = [
                            'next_repayment' => [
                                'date' => $value->limit_date,
                                'instalment' => intval($value->instalment_no),
                                'amount' => 0,
                            ],
                            'accounts_receivable' => [
                                'principal' => 0,
                                'interest' => 0,
                                'delay_interest' => 0,
                            ]
                        ];
                    }
                    if ($value->limit_date == $instalment_data[$value->investment_id]['next_repayment']['date']) {
                        $instalment_data[$value->investment_id]['next_repayment']['amount'] += $value->amount;
                    }
                    switch ($value->source) {
                        case SOURCE_AR_PRINCIPAL:
                            $instalment_data[$value->investment_id]['accounts_receivable']['principal'] += $value->amount;
                            break;
                        case SOURCE_AR_INTEREST:
                            $instalment_data[$value->investment_id]['accounts_receivable']['interest'] += $value->amount;
                            break;
                        case SOURCE_AR_DELAYINTEREST:
                            $instalment_data[$value->investment_id]['accounts_receivable']['delay_interest'] += $value->amount;
                            break;
                        default:
                            break;
                    }
                }
            }

            $this->load->model('loanmanager/loan_manager_target_model');
            $pushData = [];
            foreach ($investments as $key => $value) {
                $target_info = $this->target_model->get($value->target_id);
                $product = $product_list[$target_info->product_id];
                $sub_product_id = $target_info->sub_product_id;
                $product_name = $product['name'];
                if ($this->is_sub_product($product, $sub_product_id)) {
                    $product = $this->trans_sub_product($product, $sub_product_id);
                    $product_name = $product['name'];
                }
                $targetData = json_decode($target_info->target_data);
                $target = array(
                    'id' => intval($target_info->id),
                    'target_no' => $target_info->target_no,
                    'product_name' => $product_name,
                    'product_id' => intval($target_info->product_id),
                    'sub_product_id' => intval($target_info->sub_product_id),
                    'user_id' => intval($target_info->user_id),
                    'loan_amount' => intval($target_info->loan_amount),
                    'credit_level' => intval($target_info->credit_level),
                    'interest_rate' => floatval($target_info->interest_rate),
                    'instalment' => intval($target_info->instalment),
                    'repayment' => intval($target_info->repayment),
                    'delay' => intval($target_info->delay),
                    'delay_days' => intval($target_info->delay_days),
                    'loan_date' => $target_info->loan_date,
                    'status' => intval($target_info->status),
                    'sub_status' => intval($target_info->sub_status),
                    'is_rate_increase' => (isset($targetData->original_interest_rate) && $targetData->original_interest_rate != $target_info->interest_rate ? true : false),
                );

                if (!isset($pushData[$target_info->user_id])) {
                    $temp = [];
                    $getUserLoginLog = $this->loan_manager_target_model->getUserServiceLog($target_info->user_id);
                    foreach ($getUserLoginLog as $skey => $svalue) {
                        if (isset($svalue->message)) {

                        }
                        $temp[] = [
                            'type' => 0,
                            'date' => date("Y/m/d", $svalue->end_time),
                            'title' => ($pushTool[$svalue->push_by] . ' / ' . $pushType[$svalue->push_type]),
                            'msg' => $pushResultStatus[$svalue->result] . ($svalue->invest_message != '' ? ' - ' . $svalue->invest_message : ''),
                        ];
                    }
                    $pushData[$target_info->user_id] = $temp;
                }
                if (count($pushData[$target_info->user_id]) > 0) {
                    $target['target_message'] = $pushData[$target_info->user_id];
                }

                $temp = array(
                    'id' => intval($value->id),
                    'loan_amount' => intval($value->loan_amount),
                    'status' => intval($value->status),
                    'transfer_status' => intval($value->transfer_status),
                    'target' => $target,
                    'next_repayment' => isset($instalment_data[$value->id]) ? $instalment_data[$value->id]['next_repayment'] : [],
                    'accounts_receivable' => isset($instalment_data[$value->id]) ? $instalment_data[$value->id]['accounts_receivable'] : [],
                    'legal_collection' => $this->target_lib->isLegalCollection($value->legal_collection_at),
                );

                $this->load->model('log/log_legaldoc_status_model');
                $legal_log = $this->log_legaldoc_status_model->order_by('id', 'DESC')->limit(1)->get_by([
                    'target_id' => $target_info->id
                ]);
                if (isset($legal_log)) {
                    $temp['lc_description'] = $this->log_legaldoc_status_model->process_status[$legal_log->status];
                    $temp['lc_handle_time'] = $legal_log->created_at;
                }

                $value->aiBidding == 1 ? $temp['aiBidding'] = true : '';
                $list[] = $temp;
            }
        }
        $this->response(array('result' => 'SUCCESS', 'data' => array('list' => $list)));
    }

    /**
     * @api {get} /v2/recoveries/finish 出借方 已結案債權列表
     * @apiVersion 0.2.0
     * @apiName GetRecoveriesFinish
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {String} id Investments ID
     * @apiSuccess {String} loan_amount 債權金額
     * @apiSuccess {String} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
     * @apiSuccess {String} transfer_status 債權轉讓狀態 0:無 1:已申請 2:移轉成功
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
     * @apiSuccess {Number} target.delay 是否逾期 0:無 1:逾期中
     * @apiSuccess {Number} target.delay_days 逾期天數
     * @apiSuccess {String} target.loan_date 放款日期
     * @apiSuccess {Number} target.status 標的狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
     * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
     * @apiSuccess {Object} income 收入
     * @apiSuccess {Number} income.principal 已收本金
     * @apiSuccess {Number} income.interest 已收利息
     * @apiSuccess {Number} income.delay_interest 已收延滯息
     * @apiSuccess {Number} income.other 已收補貼
     * @apiSuccess {Number} income.transfer 債轉價金
     * @apiSuccess {Object} invest 投資
     * @apiSuccess {Number} invest.start_date 開始投資日期
     * @apiSuccess {Number} invest.end_date 結束投資日期
     * @apiSuccess {Number} invest.amount 投資金額
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *        "result":"SUCCESS",
     *        "data":{
     *            "list":[
     *            {
     *                "id":"1",
     *                "amount":"50000",
     *                "loan_amount":"",
     *                "status":"3",
     *                "transfer_status":"0",
     *                "target": {
     *                    "id": 9,
     *                    "target_no": "STN2019011414213",
     *                    "product_id": 1,
     *                    "user_id": 19,
     *                    "loan_amount": 5000,
     *                    "credit_level": 3,
     *                    "interest_rate": 7,
     *                    "instalment": 3,
     *                    "repayment": 1,
     *                    "delay": 0,
     *                    "delay_days": 0,
     *                    "loan_date": "2019-01-14",
     *                    "status": 5,
     *                    "sub_status": 0
     *                },
     *                "income": {
     *                    "principal": 0,
     *                    "interest": 0,
     *                    "delay_interest": 0,
     *                    "other": 0,
     *                    "transfer": "5003"
     *                },
     *                "invest": {
     *                    "start_date": "2019-01-05",
     *                    "end_date": "2019-01-17",
     *                    "amount": "5000"
     *                }
     *            }
     *            ]
     *        }
     *    }
     *
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse NotInvestor
     *
     */
    public function finish_get()
    {
        $input = $this->input->get(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $investments = $this->investment_model->get_many_by([
            'user_id' => $user_id,
            'status' => 10
        ]);
        $list = [];
        $last_date = [];
        if (!empty($investments)) {
            $instalment_income = [];
            $transaction = $this->transaction_model->order_by('entering_date', 'desc')->get_many_by([
                'user_to' => $user_id,
                'status' => 2
            ]);
            if ($transaction) {
                foreach ($transaction as $key => $value) {
                    if (!isset($instalment_income[$value->investment_id])) {
                        $instalment_income[$value->investment_id] = [
                            'principal' => 0,
                            'interest' => 0,
                            'delay_interest' => 0,
                            'other' => 0,
                            'transfer' => 0,
                        ];
                        $last_date[$value->investment_id] = $value->entering_date;
                    }
                    switch ($value->source) {
                        case SOURCE_PRINCIPAL:
                            $instalment_income[$value->investment_id]['principal'] += $value->amount;
                            break;
                        case SOURCE_INTEREST:
                            $instalment_income[$value->investment_id]['interest'] += $value->amount;
                            break;
                        case SOURCE_DELAYINTEREST:
                            $instalment_income[$value->investment_id]['delay_interest'] += $value->amount;
                            break;
                        case SOURCE_PREPAYMENT_ALLOWANCE:
                            $instalment_income[$value->investment_id]['other'] += $value->amount;
                            break;
                        default:
                            break;
                    }
                }
            }

            foreach ($investments as $key => $value) {
                $target_info = $this->target_model->get($value->target_id);
                $target = array(
                    'id' => intval($target_info->id),
                    'target_no' => $target_info->target_no,
                    'product_id' => intval($target_info->product_id),
                    'sub_product_id' => intval($target_info->sub_product_id),
                    'user_id' => intval($target_info->user_id),
                    'loan_amount' => intval($target_info->loan_amount),
                    'credit_level' => intval($target_info->credit_level),
                    'interest_rate' => floatval($target_info->interest_rate),
                    'instalment' => intval($target_info->instalment),
                    'repayment' => intval($target_info->repayment),
                    'delay' => intval($target_info->delay),
                    'delay_days' => intval($target_info->delay_days),
                    'loan_date' => $target_info->loan_date,
                    'status' => intval($target_info->status),
                    'sub_status' => intval($target_info->sub_status),
                );

                if (!isset($instalment_income[$value->id])) {
                    $instalment_income[$value->id] = [
                        'principal' => 0,
                        'interest' => 0,
                        'delay_interest' => 0,
                        'other' => 0,
                        'transfer' => 0,
                    ];
                }

                if ($value->transfer_status == 2) {
                    $transfer_info = $this->transfer_lib->get_transfer_investments($value->id);
                    if ($transfer_info && $transfer_info->status == 10) {
                        $instalment_income[$value->id]['transfer'] = intval($transfer_info->amount);
                        $last_date[$value->id] = $transfer_info->transfer_date;
                    }
                }
                $instalment_invest = [
                    'start_date' => '',
                    'end_date' => isset($last_date[$value->id]) ? $last_date[$value->id] : '****-**-**',
                    'amount' => 0,
                ];
                $transaction = $this->transaction_model->get_by([
                    'source' => [SOURCE_TRANSFER, SOURCE_LENDING, SOURCE_TRANSFER_FEE],
                    'user_from' => $user_id,
                    'target_id' => $value->target_id,
                    'investment_id' => $value->id,
                    'status' => 2
                ]);
                if ($transaction) {
                    $instalment_invest['start_date'] = $transaction->entering_date;
                    $instalment_invest['amount'] = intval($transaction->amount);
                }

                $temp = array(
                    'id' => intval($value->id),
                    'loan_amount' => intval($value->loan_amount),
                    'status' => intval($value->status),
                    'transfer_status' => intval($value->transfer_status),
                    'target' => $target,
                    'income' => $instalment_income[$value->id],
                    'invest' => $instalment_invest,
                );
                $value->aiBidding == 1 ? $temp['aiBidding'] = true : '';
                $list[] = $temp;
            }
        }
        $this->response(array('result' => 'SUCCESS', 'data' => array('list' => $list)));
    }

    /**
     * @api {get} /v2/recoveries/info/:id 出借方 已出借資訊
     * @apiVersion 0.2.0
     * @apiName GetRecoveriesInfo
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {Number} id Investments ID
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} id Investments ID
     * @apiSuccess {Number} loan_amount 出借金額
     * @apiSuccess {Number} status 狀態 0:待付款 1:待結標(款項已移至待交易) 2:待放款(已結標) 3:還款中 8:已取消 9:流標 10:已結案
     * @apiSuccess {Number} transfer_status 債權轉讓狀態 0:無 1:已申請 2:移轉成功
     * @apiSuccess {Number} created_at 申請日期
     * @apiSuccess {String} contract 合約內容
     * @apiSuccess {Object} transfer 債轉資訊
     * @apiSuccess {Number} transfer.amount 債權轉讓價金
     * @apiSuccess {Number} transfer.transfer_fee 債權轉讓手續費
     * @apiSuccess {Number} transfer.principal 剩餘本金
     * @apiSuccess {Number} transfer.interest 已發生利息
     * @apiSuccess {Number} transfer.delay_interest 已發生延滯利息
     * @apiSuccess {Float}  transfer.bargain_rate 增減價比率(%)
     * @apiSuccess {Number} transfer.instalment 剩餘期數
     * @apiSuccess {Number} transfer.combination Combination ID
     * @apiSuccess {Number} transfer.accounts_receivable 應收帳款
     * @apiSuccess {String} transfer.contract 債權轉讓合約
     * @apiSuccess {String} transfer.transfer_date 債權轉讓日期
     * @apiSuccess {Object} target 標的資訊
     * @apiSuccess {Number} target.id Target ID
     * @apiSuccess {String} target.target_no 標的號
     * @apiSuccess {Number} target.product_id 產品ID
     * @apiSuccess {Number} target.user_id User ID
     * @apiSuccess {Number} target.loan_amount 借款金額
     * @apiSuccess {Number} target.credit_level 信用評等
     * @apiSuccess {Number} target.interest_rate 年化利率
     * @apiSuccess {Number} target.instalment 期數
     * @apiSuccess {Number} target.repayment 計息方式
     * @apiSuccess {String} target.reason 借款原因
     * @apiSuccess {String} target.remark 備註
     * @apiSuccess {Number} target.delay 狀態 0:無 1:逾期
     * @apiSuccess {Number} target.delay_days 逾期天數
     * @apiSuccess {String} target.loan_date 放款日期
     * @apiSuccess {Number} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
     * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
     * @apiSuccess {Number} target.created_at 申請日期
     * @apiSuccess {Object} target.user 借款人基本資訊
     * @apiSuccess {String} target.user.name 姓名
     * @apiSuccess {String} target.user.id_number 身分證字號
     * @apiSuccess {Number} target.user.age 年齡
     * @apiSuccess {String} target.user.sex 性別 F/M
     * @apiSuccess {String} target.user.company_name 單位名稱
     * @apiSuccess {Object} amortization_schedule 回款計畫
     * @apiSuccess {String} amortization_schedule.amount 借款金額
     * @apiSuccess {String} amortization_schedule.instalment 借款期數
     * @apiSuccess {String} amortization_schedule.rate 年利率
     * @apiSuccess {String} amortization_schedule.date 起始時間
     * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
     * @apiSuccess {String} amortization_schedule.list 回款計畫
     * @apiSuccess {String} amortization_schedule.list.instalment 第幾期
     * @apiSuccess {String} amortization_schedule.list.repayment_date 還款日
     * @apiSuccess {String} amortization_schedule.list.days 本期日數
     * @apiSuccess {String} amortization_schedule.list.principal 還款本金
     * @apiSuccess {String} amortization_schedule.list.interest 還款利息
     * @apiSuccess {String} amortization_schedule.list.total_payment 本期還款金額
     * @apiSuccess {String} amortization_schedule.list.repayment 已還款金額
     * @apiSuccess {Number} legal_collection 進行法催中
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *        "result":"SUCCESS",
     *        "data": {
     *            "id": 10,
     *            "loan_amount": 5000,
     *            "status": 3,
     *            "transfer_status": 0,
     *            "created_at": 1547446446,
     *            "contract": "借貸契約",
     *            "transfer": {
     *                "amount": 5120,
     *                "transfer_fee": 25,
     *                "principal": 5000,
     *                "interest": 10,
     *                "delay_interest": 0,
     *                "bargain_rate": 2.2,
     *                "instalment": 18,
     *                "accounts_receivable": 5398,
     *                "transfer_at": "2019-01-21",
     *                "contract": "應收帳款債權買賣"
     *            },
     *            "target": {
     *                "id": 15,
     *                "target_no": "STN2019011452727",
     *                "product_id": 1,
     *                "user_id": 19,
     *                "user": {
     *                    "name": "你**",
     *                    "id_number": "A1085*****",
     *                    "sex": "M",
     *                    "age": 30,
     *                    "company_name": "國立政治大學"
     *                },
     *                "loan_amount": 5000,
     *                "credit_level": 3,
     *                "interest_rate": 8,
     *                "reason": "",
     *                "remark": "",
     *                "instalment": 12,
     *                "repayment": 1,
     *                "delay": 0,
     *                "delay_days": 0,
     *                "loan_date": "2019-01-14",
     *                "status": 5,
     *                "sub_status": 0,
     *                "created_at": 1547445312
     *            },
     *            "amortization_schedule": {
     *                "amount": 5000,
     *                "instalment": 12,
     *                "rate": 8,
     *                "total_payment": 5249,
     *                "XIRR": 8.28,
     *                "date": "2019-01-14",
     *                "remaining_principal": 5000,
     *                "list": {
     *                    "1": {
     *                        "instalment": "1",
     *                        "total_payment": 435,
     *                        "repayment": 0,
     *                        "interest": 60,
     *                        "principal": 375,
     *                        "delay_interest": 0,
     *                        "days": 55,
     *                        "remaining_principal": "5000",
     *                        "repayment_date": "2019-03-10",
     *                        "ar_fees": 4
     *                    },
     *                    "2": {
     *                        "instalment": "2",
     *                        "total_payment": 435,
     *                        "repayment": 0,
     *                        "interest": 31,
     *                        "principal": 404,
     *                        "delay_interest": 0,
     *                        "days": 31,
     *                        "remaining_principal": 4625,
     *                        "repayment_date": "2019-04-10",
     *                        "ar_fees": 4
     *                    },
     *                    "3": {
     *                        "instalment": "3",
     *                        "total_payment": 435,
     *                        "repayment": 0,
     *                        "interest": 28,
     *                        "principal": 407,
     *                        "delay_interest": 0,
     *                        "days": 30,
     *                        "remaining_principal": 4221,
     *                        "repayment_date": "2019-05-10",
     *                        "ar_fees": 4
     *                    }
     *                }
     *            },
     *			  "legal_collection" :0,
     *        }
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
        $input = $this->input->get(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investment = $this->investment_model->get($investment_id);
        if (!empty($investment) && in_array($investment->status, [3, 10])) {
            if ($investment->user_id != $user_id) {
                $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_NO_PERMISSION));
            }

            $target_info = $this->target_model->get($investment->target_id);
            $user_info = $this->user_model->get($target_info->user_id);
            $product_list = $this->config->item('product_list');
            $product_info = $product_list[$target_info->product_id];
            $user = [];
            if ($user_info) {
                $name = mb_substr($user_info->name, 0, 1, 'UTF-8') . '**';
                $id_number = strlen($user_info->id_number) == 10 ? substr($user_info->id_number, 0, 5) . '*****' : '';
                $age = get_age($user_info->birthday);
                $this->load->model('user/user_meta_model');
                if ($product_info['identity'] == 1) {
                    $user_meta = $this->user_meta_model->get_by(['user_id' => $target_info->user_id, 'meta_key' => 'school_name']);
                    if (is_object($user_meta)) {
                        $user_meta->meta_value = preg_replace('/\(自填\)/', '', $user_meta->meta_value);
                    } else {
                        $user_meta = new stdClass();
                        $user_meta->meta_value = '未提供學校資訊';
                    }
                } else {
                    $user_meta = $this->user_meta_model->get_by(['user_id' => $target_info->user_id, 'meta_key' => 'diploma_name']);
                }

                $user = array(
                    'name' => $name,
                    'id_number' => $id_number,
                    'sex' => $user_info->sex,
                    'age' => $age,
                    'company_name' => $user_meta ? $user_meta->meta_value : '',
                );
            }

            $product = $product_list[$target_info->product_id];
            $sub_product_id = $target_info->sub_product_id;
            $product_name = $product['name'];
            if ($this->is_sub_product($product, $sub_product_id)) {
                $product = $this->trans_sub_product($product, $sub_product_id);
                $product_name = $product['name'];
            }

            $targetDatas = [];
            $targetData = json_decode($target_info->target_data);
            if ($product['visul_id'] == 'DS2P1') {
                $cer_group['car_file'] = [1, '車籍文件'];
                $cer_group['car_pic'] = [1, '車輛外觀照片'];
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
                    if (in_array($key, ['car_photo_front_image', 'car_photo_back_image', 'car_photo_all_image', 'car_photo_date_image', 'car_photo_mileage_image'])) {
                        if (isset($targetData->$key)) {
                            $pic_array = [];
                            foreach ($targetData->$key as $svalue) {
                                preg_match('/image.+/', $svalue, $matches);
                                $pic_array[] = FRONT_CDN_URL . 'stmps/tarda/' . $matches[0];
                            }
                            $targetDatas[$key] = $pic_array;
                        } else {
                            $targetDatas[$key] = '';
                        }
                    }
                }
                $user = array(
                    'name' => $user_info->name,
                    'id_number' => '',
                    'sex' => '',
                    'age' => '',
                    'company_name' => '',
                    'tax_id' => $user_info->id_number,
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
                    'user_id' => $target_info->user_id,
                ]);
                foreach ($certifications as $key => $value) {
                    $cer = $certification[$value->certification_id];
                    $cer['user_status'] = intval($value->status);
                    $cer['certification_id'] = intval($value->id);
                    $cer['updated_at'] = intval($value->updated_at);
                    $certification_list[] = $cer;
                }
            }

            $reason = $target_info->reason;
            $json_reason = json_decode($reason);
            if (isset($json_reason->reason)) {
                $reason = $json_reason->reason . ' - ' . $json_reason->reason_description;
            }

            $target = [
                'id' => intval($target_info->id),
                'target_no' => $target_info->target_no,
                'product_name' => $product_name,
                'product_id' => intval($target_info->product_id),
                'sub_product_id' => intval($target_info->sub_product_id),
                'user_id' => intval($target_info->user_id),
                'user' => $user,
                'loan_amount' => intval($target_info->loan_amount),
                'credit_level' => intval($target_info->credit_level),
                'interest_rate' => floatval($target_info->interest_rate),
                'reason' => $reason,
                'remark' => $target_info->remark,
                'targetDatas' => $targetDatas,
                'instalment' => intval($target_info->instalment),
                'repayment' => intval($target_info->repayment),
                'delay' => intval($target_info->delay),
                'delay_days' => intval($target_info->delay_days),
                'loan_date' => $target_info->loan_date,
                'isTargetOpaque' => $target_info->sub_product_id == STAGE_CER_TARGET ? true : false,
                'status' => intval($target_info->status),
                'sub_status' => intval($target_info->sub_status),
                'created_at' => intval($target_info->created_at),
            ];

            count($certification_list) > 0 ? $target['certification'] = $certification_list : '';

            $repayment_detail = [];
            $transaction = $this->transaction_model->order_by('limit_date', 'asc')->get_many_by(
                array(
                    'target_id' => $target_info->id,
                    'user_to' => $user_id,
                    'status' => [1, 2]
                )
            );
            if ($transaction) {
                foreach ($transaction as $k => $v) {
                    if (in_array($v->source, [SOURCE_AR_PRINCIPAL, SOURCE_AR_INTEREST])) {
                        $repayment_detail[$v->limit_date] = isset($repayment_detail[$v->limit_date]) ? $repayment_detail[$v->limit_date] + $v->amount : $v->amount;
                    }
                }
            }

            $transfer = [];
            if ($investment->transfer_status != 0) {
                $transfer_info = $this->transfer_lib->get_transfer_investments($investment->id);
                if ($transfer_info) {
                    $contract_data = $this->contract_lib->get_contract($transfer_info->contract_id);
                    $contract = isset($contract_data['content']) ? $contract_data['content'] : '';
                    $transfer = [
                        'amount' => intval($transfer_info->amount),
                        'transfer_fee' => intval($transfer_info->transfer_fee),
                        'principal' => intval($transfer_info->principal),
                        'interest' => intval($transfer_info->interest),
                        'delay_interest' => intval($transfer_info->delay_interest),
                        'bargain_rate' => floatval($transfer_info->bargain_rate),
                        'instalment' => intval($transfer_info->instalment),
                        'combination' => intval($transfer_info->combination),
                        'accounts_receivable' => intval($transfer_info->accounts_receivable),
                        'transfer_at' => $transfer_info->transfer_date,
                        'contract' => $contract,
                    ];
                }
            }
            $investment_contract = $this->contract_lib->get_contract($investment->contract_id);

            $data = [
                'id' => intval($investment->id),
                'loan_amount' => intval($investment->loan_amount),
                'status' => intval($investment->status),
                'transfer_status' => intval($investment->transfer_status),
                'created_at' => intval($investment->created_at),
                'contract' => $investment_contract['content'],
                'transfer' => $transfer,
                'target' => $target,
                'amortization_schedule' => $this->target_lib->get_investment_amortization_table($target_info, $investment),
                'legal_collection' => $this->target_lib->isLegalCollection($investment->legal_collection_at),
            ];

            $this->load->model('log/log_legaldoc_status_model');
            $legal_log = $this->log_legaldoc_status_model->order_by('id', 'DESC')->limit(1)->get_by([
                'target_id' => $target_info->id
            ]);
            if (isset($legal_log)) {
                $data['lc_description'] = $this->log_legaldoc_status_model->process_status[$legal_log->status];
                $data['lc_handle_time'] = $legal_log->created_at;
            }

            $investment->aiBidding == 1 ? $data['aiBidding'] = true : '';

            $this->response(['result' => 'SUCCESS', 'data' => $data]);
        }
        $this->response(['result' => 'ERROR', 'error' => APPLY_NOT_EXIST]);
    }

    /**
     * @api {post} /v2/recoveries/withdraw 出借方 提領申請
     * @apiVersion 0.2.0
     * @apiName PostRecoveriesWithdraw
     * @apiGroup Recoveries
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
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        //必填欄位
        $fields = ['amount', 'transaction_password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        //檢查認證 NOT_VERIFIED
        if (empty($this->user_info->id_number) || $this->user_info->id_number == '') {
            $this->response(array('result' => 'ERROR', 'error' => NOT_VERIFIED));
        }

        //檢查金融卡綁定 NO_BANK_ACCOUNT
        $bank_account = $this->user_bankaccount_model->get_by(
            array(
                'investor' => $investor,
                'status' => 1,
                'user_id' => $user_id,
                'verify' => 1
            )
        );
        if (!$bank_account) {
            $this->response(array('result' => 'ERROR', 'error' => NO_BANK_ACCOUNT));
        }

        if ($this->user_info->transaction_password == '') {
            $this->response(array('result' => 'ERROR', 'error' => NO_TRANSACTION_PASSWORD));
        }

        if ($this->user_info->transaction_password != sha1($input['transaction_password'])) {
            $this->response(array('result' => 'ERROR', 'error' => TRANSACTION_PASSWORD_ERROR));
        }

        $input['amount'] = (int) $input['amount'];
        if ($this->transaction_lib->check_minimum_withdraw_amount($input['amount']) === FALSE) {
            $this->response(['result' => 'ERROR', 'error' => LOW_WITHDRAW_AMOUNT]);
        }
        $withdraw = $this->transaction_lib->withdraw($user_id, $input['amount']);
        if ($withdraw) {
            $this->response(array('result' => 'SUCCESS'));
        } else {
            $this->response(array('result' => 'ERROR', 'error' => NOT_ENOUGH_FUNDS));
        }
    }

    /**
     * @api {get} /v2/recoveries/passbook 出借方 虛擬帳戶明細
     * @apiVersion 0.2.0
     * @apiName GetRecoveriesPassbook
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} amount 金額
     * @apiSuccess {String} bank_amount 帳戶餘額
     * @apiSuccess {String} remark 備註
     * @apiSuccess {String} tx_datetime 交易時間
     * @apiSuccess {Number} created_at 入帳時間
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *        "data":{
     *            "list":[
     *                {
     *                    "amount": 1000000,
     *                    "bank_amount": 1000000,
     *                    "remark": "平台代收",
     *                    "tx_datetime": "2019-01-14 14:12:10",
     *                    "created_at": 1547446336
     *                },
     *                {
     *                    "amount": -5000,
     *                    "bank_amount": 995000,
     *                    "remark": "出借款",
     *                    "tx_datetime": "2019-01-14 14:13:00",
     *                    "created_at": 1547446380
     *                }
     *            ]
     *        }
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
        $input = $this->input->get(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $list = [];
        //檢查認證 NOT_VERIFIED
        if (empty($this->user_info->id_number) || $this->user_info->id_number == '') {
            $this->response(array('result' => 'ERROR', 'error' => NOT_VERIFIED));
        }

        //檢查金融卡綁定 NO_BANK_ACCOUNT
        $bank_account = $this->user_bankaccount_model->get_by([
            'investor' => $investor,
            'status' => 1,
            'user_id' => $user_id,
            'verify' => 1
        ]);
        if (!$bank_account) {
            $this->response(array('result' => 'ERROR', 'error' => NO_BANK_ACCOUNT));
        }

        $virtual_account = $this->virtual_account_model->get_by([
            'investor' => 1,
            'user_id' => $user_id
        ]);
        $passbook_list = $this->passbook_lib->get_passbook_list($virtual_account->virtual_account, 150);
        if ($passbook_list) {
            $transaction_source = $this->config->item('transaction_source');
            foreach ($passbook_list as $key => $value) {
                $value['remark'] = json_decode($value['remark'], TRUE);
                $remark = isset($value['remark']['source']) && $value['remark']['source'] ? $transaction_source[$value['remark']['source']] : '';
                $list[] = [
                    'amount' => $value['amount'],
                    'bank_amount' => $value['bank_amount'],
                    'remark' => $remark,
                    'tx_datetime' => $value['tx_datetime'],
                    'created_at' => $value['created_at'],
                ];
            }
        }

        $this->response(array('result' => 'SUCCESS', 'data' => ['list' => $list]));
    }

    /**
     * @api {post} /v2/recoveries/pretransfer 出借方 我要轉讓
     * @apiVersion 0.2.0
     * @apiName PostRecoveriesPretransfer
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} ids Investments IDs ex: 1,3,10,21
     * @apiParam {Float{-20.0~20.0}} [bargain_rate=0] 增減價比率(%)
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} count 筆數
     * @apiSuccess {Number} amount 轉讓價金
     * @apiSuccess {Number} principal 剩餘本金
     * @apiSuccess {Number} interest 已發生利息
     * @apiSuccess {Number} delay_interest 已發生延滯息
     * @apiSuccess {Number} fee 預計轉讓手續費
     * @apiSuccess {Number} max_instalment 最大剩餘期數
     * @apiSuccess {Number} min_instalment 最小剩餘期數
     * @apiSuccess {String} settlement_date 結息日
     * @apiSuccess {Float} bargain_rate 增減價比率(%)
     * @apiSuccess {Float} interest_rate 平均年表利率(%)
     * @apiSuccess {Number} accounts_receivable 應收帳款
     * @apiSuccess {Object} contract 轉讓合約(多份)
     * @apiSuccess {Object} legal_collection_list 進行法催的 investment id
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *        "data": {
     *              "count": 4,
     *              "amount": 15015,
     *              "principal": 15000,
     *              "interest": 15,
     *              "delay_interest": 0,
     *              "fee": 75,
     *              "max_instalment": 12,
     *              "min_instalment": 3,
     *              "settlement_date": "2019-01-19",
     *              "bargain_rate": 5.1,
     *              "interest_rate": 8.38,
     *              "accounts_receivable": 15477,
     *              "contract": [
     *                "我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約",
     *                "我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約"
     *              ],
     * 				"legal_collection_list" : [
     * 					1,
     * 					10
     * 				],
     *          }
     *    }
     *
     * @apiUse InputError
     * @apiUse TokenError
     * @apiUse BlockUser
     * @apiUse NotInvestor
     *
     * @apiError 807 此申請狀態不符
     * @apiErrorExample {Object} 807
     *     {
     *       "result": "ERROR",
     *       "error": "807"
     *     }
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
     *
     * @apiError 808 已申請過債權轉出
     * @apiErrorExample {Object} 808
     *     {
     *       "result": "ERROR",
     *       "error": "808"
     *     }
     *
     * @apiError 813 價金過高
     * @apiErrorExample {Object} 813
     *     {
     *       "result": "ERROR",
     *       "error": "813"
     *     }
     */
    public function pretransfer_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $ids = array();
        $amount = isset($input['amount']) ? $input['amount'] : 0;
        $combination = isset($input['combination']) && intval($input['combination']) ? 1 : 0;

        //必填欄位
        if (empty($input['ids'])) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }
        $bargain_rate = isset($input['bargain_rate']) ? round(floatval($input['bargain_rate']), 1) : 0;
        if ($bargain_rate < -20 || $bargain_rate > 20) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $ids = explode(',', $input['ids']);
        $count = count($ids);
        if (!empty($ids)) {
            foreach ($ids as $key => $id) {
                $id = intval($id);
                if (intval($id) <= 0) {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                }
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }


        $investments = $this->investment_model->get_many($ids);

        if (count($investments) == $count) {
            foreach ($investments as $key => $value) {
                if ($value->user_id != $user_id) {
                    $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_NO_PERMISSION));
                }
                if ($value->status != 3) {
                    $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_STATUS_ERROR));
                }
                if ($value->transfer_status != 0) {
                    $this->response(array('result' => 'ERROR', 'error' => TRANSFER_EXIST));
                }
            }

            $data = [
                'count' => 0,
                'amount' => 0,
                'principal' => 0,
                'interest' => 0,
                'delay_interest' => 0,
                'fee' => 0,
                'max_instalment' => 0,
                'min_instalment' => 0,
                'settlement_date' => '',
                'bargain_rate' => $bargain_rate,
                'interest_rate' => 0,
                'accounts_receivable' => 0,
                'contract' => [],
            ];

            $interest_rate_n = 0;
            $interest_rate_d = 0;
            $data_arr['user_id'] = [];
            $data_arr['target_no'] = [];
            $data_arr['loan_amount'] = [];
            $data_arr['interest_rate'] = [];
            $data_arr['principal'] = [];
            $data_arr['interest'] = [];
            $data_arr['delay_interest'] = [];
            $data_arr['fee'] = [];

            foreach ($investments as $key => $value) {
                $target = $this->target_model->get($value->target_id);
                $interest_rate_n += $value->loan_amount * $target->interest_rate * $target->instalment;
                $interest_rate_d += $value->loan_amount * $target->instalment;

                $info = $this->transfer_lib->get_pretransfer_info($value, $bargain_rate, $amount, false, $target);
                if ($info) {
                    $amortization_table = $this->target_lib->get_investment_amortization_table($target, $value);
                    $data['count']++;
                    $data_arr['user_id'][] = $target->user_id;
                    $data_arr['target_no'][] = $target->target_no;
                    $data_arr['loan_amount'][] = $target->loan_amount;
                    $data_arr['interest_rate'][] = $target->interest_rate;
                    $data_arr['principal'][] = $info['principal'];
                    $data_arr['interest'][] = $info['interest'];
                    $data_arr['delay_interest'][] = $info['delay_interest'];
                    $data_arr['fee'][] = $info['fee'];
                    $data['accounts_receivable'] += $info['accounts_receivable'];

                    foreach ($amortization_table['list'] as $k => $v) {
                        if ($v['repayment'] == 0) {
                            if (!isset($data['total_payment'][$v['repayment_date']])) {
                                $data['total_payment'][$v['repayment_date']] = 0;
                            }
                            $data['total_payment'][$v['repayment_date']] += $v['total_payment'];
                        }
                    }

                    if ($data['max_instalment'] < $info['instalment']) {
                        $data['max_instalment'] = $info['instalment'];
                    }
                    if ($data['min_instalment'] > $info['instalment'] || $data['min_instalment'] == 0) {
                        $data['min_instalment'] = $info['instalment'];
                    }
                    if ($data['settlement_date'] > $info['settlement_date'] || $data['settlement_date'] == '') {
                        $data['settlement_date'] = $info['settlement_date'];
                    }
                }
            }
            $data['principal'] = array_sum($data_arr['principal']);
            $data['interest'] = array_sum($data_arr['interest']);
            $data['delay_interest'] = array_sum($data_arr['delay_interest']);
            $data['fee'] = array_sum($data_arr['fee']);
            $data['amount'] = $amount != 0 ? ($count == 1 || $combination == 1 ? $amount : $data['principal']) : $data['principal'];
            $minAmount = 1;
            $maxAmount = $data['accounts_receivable'];

            if ($amount != 0 && ($amount < $data['fee'])) {
                $this->response(array('result' => 'ERROR', 'error' => TRANSFER_AMOUNT_ERROR, 'data' => ['description' => '您的價金應高於' . $data['fee'] . '元的債轉手續費，請重新輸入。']));
            }
            if ($amount != 0 && ($amount < $minAmount || $amount > $maxAmount)) {
                $this->response(array('result' => 'ERROR', 'error' => TRANSFER_AMOUNT_ERROR));
            }
            if ($combination == 1) {
                $contract[] = $this->contract_lib->build_contract('trans_multi', $user_id, '', $data, $data_arr, $count, $amount, 0, 1);
            } else {
                for ($i = 0; $i < $count; $i++) {
                    $contract[] = $this->contract_lib->build_contract('transfer', $user_id, '', $data, $data_arr, $count, $amount, $i, 1);
                }
            }
            $data['contract'] = $contract;

            if ($interest_rate_n && $interest_rate_d) {
                $data['interest_rate'] = round($interest_rate_n / $interest_rate_d, 2);
            }

            $legal_collection = $this->investment_model->get_many_by([
                'id' => $ids,
                'legal_collection_at >' => '1911-01-01'
            ]);
            $data['legal_collection_list'] = [];
            if (isset($legal_collection) && count($legal_collection)) {
                $data['legal_collection_list'] = array_column(json_decode(json_encode($legal_collection), true), 'id');
            }

            $this->response(array('result' => 'SUCCESS', 'data' => $data));
        }
        $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_NOT_EXIST));
    }

    /**
     * @api {post} /v2/recoveries/transfer 出借方 轉讓申請
     * @apiVersion 0.2.0
     * @apiName PostRecoveriesTransfer
     * @apiGroup Recoveries
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} ids Investments IDs (複選使用逗號隔開1,3,10,21)
     * @apiParam {Float{-20..20}} [bargain_rate=0] 增減價比率(%)
     * @apiParam {Number{0,1}} [combination=0] 是否整包
     * @apiParam {String{4,12}} [password] 設置密碼
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
     * @apiError 807 此申請狀態不符
     * @apiErrorExample {Object} 807
     *     {
     *       "result": "ERROR",
     *       "error": "807"
     *     }
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
     *
     * @apiError 808 已申請過債權轉出
     * @apiErrorExample {Object} 808
     *     {
     *       "result": "ERROR",
     *       "error": "808"
     *     }
     *
     * @apiError 813 價金過高
     * @apiErrorExample {Object} 813
     *     {
     *       "result": "ERROR",
     *       "error": "813"
     *     }
     *
     * @apiError 814 整包狀態不一致
     * @apiErrorExample {Object} 814
     *     {
     *       "result": "ERROR",
     *       "error": "814"
     *     }
     */
    public function transfer_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $user_id = $this->user_info->id;
        $investor = $this->user_info->investor;
        $ids = array();

        //必填欄位
        if (empty($input['ids'])) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $bargain_rate = isset($input['bargain_rate']) ? round(floatval($input['bargain_rate']), 1) : 0;
        if ($bargain_rate < -20 || $bargain_rate > 20) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        if (!isset($input['amount']) || empty($input['amount'])) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }
        $amount = $input['amount'];

        $combination = isset($input['combination']) && intval($input['combination']) ? 1 : 0;
        $combination_id = 0;
        $password = isset($input['password']) ? $input['password'] : '';
        if ($combination == 1 && $password != '') {
            if (strlen($password) < 4 || strlen($password) > 12) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        $ids = explode(',', $input['ids']);
        $count = count($ids);
        $legal_collection = $this->investment_model->get_many_by([
            'id' => $ids,
            'legal_collection_at >' => '1911-01-01'
        ]);
        if (isset($legal_collection) && count($legal_collection)) {
            $this->response(array('result' => 'ERROR', 'error' => TARGET_IN_LEGAL_COLLECTION));
        }

        if (!empty($ids)) { //&&count($ids)==1
            foreach ($ids as $key => $id) {
                $id = intval($id);
                if (empty($id)) {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                }
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

        $investments = $this->investment_model->get_many($ids);
        if (count($investments) == $count) {
            foreach ($investments as $key => $value) {
                if ($value->user_id != $user_id) {
                    $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_NO_PERMISSION));
                }
                if ($value->status != 3) {
                    $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_STATUS_ERROR));
                }
                if ($value->transfer_status != 0) {
                    $this->response(array('result' => 'ERROR', 'error' => TRANSFER_EXIST));
                }
            }

            $first_investment = current($investments);
            $first_info = $this->transfer_lib->get_pretransfer_info($first_investment, $bargain_rate);
            if ($first_info['delay_interest'] > 0) {
                $delay = 1;
            } else {
                $delay = 0;
            }

            $data = [
                'password' => $password,
                'transfer_fee' => 0,
                'count' => 0,
                'amount' => 0,
                'principal' => 0,
                'interest' => 0,
                'max_instalment' => 0,
                'min_instalment' => 0,
                'delay_interest' => 0,
                'bargain_rate' => $bargain_rate,
                'interest_rate' => 0,
                'accounts_receivable' => 0,
            ];

            $interest_rate_n = 0;
            $interest_rate_d = 0;
            $data_arr['user_id'] = [];
            $data_arr['target_no'] = [];
            $data_arr['loan_amount'] = [];
            $data_arr['interest_rate'] = [];
            $data_arr['principal'] = [];
            $data_arr['interest'] = [];
            $data_arr['delay_interest'] = [];
            $data_arr['fee'] = [];
            $data_arr['bargain_rate'] = [];
            $data_arr['instalment'] = [];
            $data_arr['accounts_receivable'] = [];
            $data_arr['total'] = [];
            $data_arr['settlement_date'] = [];

            foreach ($investments as $key => $value) {
                $target = $this->target_model->get($value->target_id);
                $interest_rate_n += $value->loan_amount * $target->interest_rate * $target->instalment;
                $interest_rate_d += $value->loan_amount * $target->instalment;

                $info = $this->transfer_lib->get_pretransfer_info($value, $bargain_rate, $amount, false, $target);
                if ($delay == 1 && $info['delay_interest'] == 0) {
                    $this->response(array('result' => 'ERROR', 'error' => TRANSFER_COMBINE_STATUS));
                }
                if ($delay == 0 && $info['delay_interest'] > 0) {
                    $this->response(array('result' => 'ERROR', 'error' => TRANSFER_COMBINE_STATUS));
                }
                if ($info['settlement_date'] != $first_info['settlement_date']) {
                    $this->response(array('result' => 'ERROR', 'error' => TRANSFER_COMBINE_STATUS));
                }

                $data['count']++;
                $data_arr['user_id'][] = $target->user_id;
                $data_arr['target_no'][] = $target->target_no;
                $data_arr['loan_amount'][] = $target->loan_amount;
                $data_arr['interest_rate'][] = $target->interest_rate;
                $data_arr['principal'][] = $info['principal'];
                $data_arr['interest'][] = $info['interest'];
                $data_arr['delay_interest'][] = $info['delay_interest'];
                $data_arr['fee'][] = $info['fee'];
                $data_arr['bargain_rate'][] = $info['bargain_rate'];
                $data_arr['instalment'][] = $info['instalment'];
                $data_arr['accounts_receivable'][] = $info['accounts_receivable'];
                $data_arr['total'][] = $info['total'];
                $data_arr['settlement_date'][] = $info['settlement_date'];
                $data['accounts_receivable'] += $info['accounts_receivable'];

                if ($data['max_instalment'] < $info['instalment']) {
                    $data['max_instalment'] = $info['instalment'];
                }
                if ($data['min_instalment'] > $info['instalment'] || $data['min_instalment'] == 0) {
                    $data['min_instalment'] = $info['instalment'];
                }
            }
            $data['principal'] = array_sum($data_arr['principal']);
            $data['interest'] = array_sum($data_arr['interest']);
            $data['delay_interest'] = array_sum($data_arr['delay_interest']);
            $data['transfer_fee'] = array_sum($data_arr['fee']);
            $data['amount'] = $amount != 0 ? ($count == 1 || $combination == 1 ? $amount : $data['principal']) : $data['principal'];

            $minAmount = 1;
            $maxAmount = $data['accounts_receivable'];
            if ($amount < $data['transfer_fee']) {
                $this->response(array('result' => 'ERROR', 'error' => TRANSFER_AMOUNT_ERROR, 'data' => ['description' => '您的價金應高於' . $data['transfer_fee'] . '元的債轉手續費，請重新輸入。']));
            }
            if ($amount < $minAmount || $amount > $maxAmount) {
                $this->response(array('result' => 'ERROR', 'error' => TRANSFER_AMOUNT_ERROR));
            }
            if ($interest_rate_n && $interest_rate_d) {
                $data['interest_rate'] = round($interest_rate_n / $interest_rate_d, 2);
            }
            if ($combination == 1) {
                $contract[] = $this->contract_lib->build_contract('trans_multi', $user_id, '', $data, $data_arr, $count, $amount);
                $this->load->model('loan/transfer_combination_model');
                $combination_id = $this->transfer_combination_model->insert($data);
            } else {
                for ($i = 0; $i < $count; $i++) {
                    $contract[] = $this->contract_lib->build_contract('transfer', $user_id, '', $data, $data_arr, $count, $amount, $i);
                }
            }
            $rs = '';
            foreach ($investments as $key => $value) {
                if (is_array($rs)) {
                    $contract = $rs;
                }
                $rs = $this->transfer_lib->apply_transfer($value, $combination_id, $contract, $data_arr, $key, $count);
            }

            $this->response(array('result' => 'SUCCESS'));
        }
        $this->response(array('result' => 'ERROR', 'error' => TARGET_APPLY_NOT_EXIST));
    }

    public function amortization_get()
    {
        $user_id = $this->user_info->id;
        $normal_list = [];
        $delay_list = [];
        $investments = $this->investment_model->get_many_by(
            array(
                'user_id' => $user_id,
                'status' => 3
            )
        );
        if ($investments) {
            foreach ($investments as $key => $value) {
                $target = $this->target_model->get($value->target_id);
                if ($target->status == 5) {
                    $amortization_table = $this->target_lib->get_investment_amortization_table($target, $value);
                    if ($amortization_table && !empty($amortization_table['list'])) {
                        if ($target->delay == 0 || ($target->delay_days - GRACE_PERIOD <= 0)) {
                            foreach ($amortization_table['list'] as $k => $v) {
                                if (!isset($normal_list[$v['repayment_date']])) {
                                    $normal_list[$v['repayment_date']] = array(
                                        'principal' => 0,
                                        'interest' => 0,
                                        'total_payment' => 0,
                                        'recorded' => 0,
                                        'unrecorded' => 0,
                                    );
                                }
                                $normal_list[$v['repayment_date']]['principal'] += $v['principal'];
                                $normal_list[$v['repayment_date']]['interest'] += $v['interest'];
                                $normal_list[$v['repayment_date']]['total_payment'] += $v['repayment'];
                                if ($v['repayment'] !== 0) {
                                    $normal_list[$v['repayment_date']]['recorded']++;
                                } else {
                                    $normal_list[$v['repayment_date']]['unrecorded']++;
                                }
                            }
                        } elseif ($target->delay == 1) {
                            foreach ($amortization_table['list'] as $k => $v) {
                                if (!isset($delay_list[$v['repayment_date']])) {
                                    $delay_list[$v['repayment_date']] = array(
                                        'principal' => 0,
                                        'interest' => 0,
                                        'delay_interest' => 0,
                                        'total_payment' => 0,
                                    );
                                }
                                $delay_list[$v['repayment_date']]['principal'] += $v['principal'];
                                $delay_list[$v['repayment_date']]['interest'] += $v['interest'];
                                $delay_list[$v['repayment_date']]['delay_interest'] += $v['delay_interest'];
                                $delay_list[$v['repayment_date']]['total_payment'] += $v['repayment'];
                            }
                        }
                    }
                }
            }
        }
        $data = array(
            'normal' => $normal_list,
            'delay' => $delay_list,
        );
        $this->response(array('result' => 'SUCCESS', 'data' => $data));
    }

    private function sub_product_profile($product, $sub_product)
    {
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
            'checkOwner' => isset($product['checkOwner']) ? $product['checkOwner'] : false,
            'status' => $sub_product['status'],
        );
    }

    private function is_sub_product($product, $sub_product_id)
    {
        $sub_product_list = $this->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id, $product['sub_product']);
    }

    private function trans_sub_product($product, $sub_product_id)
    {
        $sub_product_list = $this->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product, $sub_product_data);
        return $product;
    }
}
