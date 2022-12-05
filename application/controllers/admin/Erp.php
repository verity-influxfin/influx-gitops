<?php require(APPPATH . '/libraries/MY_Admin_Controller.php');

use GuzzleHttp\Client;

/**
 * ERP 帳務 Controller
 */
class ERP extends MY_Admin_Controller
{
    public $host;
    private $erp_client;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('utils');

        // 載入 ERP library
        $this->load->library('sisyphus/erp_lib', [
            'host' => getenv('ENV_ERP_HOST')
        ]);

        $this->erp_client = new Client([
            'base_uri' => getenv('ENV_ERP_HOST'),
            'timeout' => 300,
        ]);

    }
     
    /**
     * 輸出 Json 資料
     * 
     * @param    array    $data      輸出資料
     * 
     * @created_at                   2021-07-30
     * @created_by                   Jack
     */
    protected function _output_json($data)
    {
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    /**
     * 債權明細表
     * 
     * @created_at                   2022-10-12
     * @created_by                   Allan
     */
    public function assets_sheet(){
        $this->load->view(
            'admin/erp/assets_sheet',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/assets_sheet.js'
                ]
            ]
        );
    }

    /**
     * 債權明細表 資料
     * 
     * @created_at                   2022-10-12
     * @created_by                   Allan
     */
    public function get_assets_sheet_data(){

        $data = $this->erp_client->request('GET', 'assets_sheet', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 債權明細表 excel
     * 
     * @created_at                   2022-10-12
     * @created_by                   Allan
     */
    public function assets_sheet_spreadsheet(){
        // get file from guzzle assets_sheet/excel
        $res = $this->erp_client->request('GET', 'assets_sheet/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        setcookie('fileDownload', 'true', 0, '/');
        echo $data;
        die();
    }
    

    /**
     * 借款案帳務轉移  UI
     * 
     * @created_at      2021-08-25
     * @created_by      Jack
     * @updated_at      2021-09-14
     * @updated_by      Jack
     */
    public function target_porting()
    {
        $this->load->view(
            'admin/erp/target_porting',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/target_porting.js'
                ]
            ]
        );
    }

    /**
     * 本攤表 UI
     * 
     * @created_at      2021-07-30
     * @created_by      Jack
     */
    public function etpr()
    {
        $this->load->view(
            'admin/erp/etpr',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/etpr.js'
                ]
            ]
        );
    }

    /**
     * 本攤表 Excel 下載
     * 
     * @created_at      2021-07-30
     * @created_by      Jack
     */
    public function etpr_spreadsheet()
    {
        // get file from guzzle replayment_schedule/excel
        $res = $this->erp_client->request('GET', '/v1/replayment_schedule/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        setcookie('fileDownload', 'true', 0, '/');
        echo $data;
        die();
    }

    /**
     * 取得本攤表 API 資料
     * 
     * @created_at            2021-08-03
     * @created_at            Frankie
     * @updated_at            2021-10-12
     * @updated_by            Allan
     */
    public function get_etpr_data()
    {
        $data = $this->erp_client->request('GET', '/v1/replayment_schedule', [
            'query' => $this->input->get() 
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 本攤表v2 UI
     * 
     * @created_at      2022-10-27
     * @created_by      Allan
     */
    public function replayment()
    {
        $this->load->view(
            'admin/erp/replayment',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/replayment.js'
                ]
            ]
        );
    }

    /**
     * 取得本攤表v2 API 資料
     * 
     * @created_at            2021-10-27
     * @created_at            Allan
     */
    public function get_replayment_data()
    {
        $data = $this->erp_client->request('GET', '/v2/replayment_schedule_list', [
            'query' => $this->input->get() 
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 取得堆疊後本攤表 API 資料
     * 
     * @created_at            2021-11-03
     * @created_at            Allan
     */
    public function get_stack_replayment_schedule()
    {
        $data = $this->erp_client->request('GET', '/stack_replayment_schedule', [
            'query' => $this->input->get() 
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 資產負債表 UI
     * 
     * @created_at      2021-07-30
     * @created_by      Jack
     */
    public function sofp()
    {
        $this->load->view(
            'admin/erp/sofp',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/sofp.js'
                ]
            ]
        );
    }

    /**
     * 取得資產負債表 API 資料
     * 
     * @created_at            2021-08-13
     * @created_at            Jack
     * @updated_at            2021-09-14
     * @updated_by            Jack
     */
    public function get_sofp_data()
    {
        $data = $this->erp_client->request('GET', 'sofp', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 資產負債表 Excel 下載
     * 
     * @created_at      2021-08-16
     * @created_by      Jack
     */
    public function sofp_spreadsheet()
    {
        $res = $this->erp_client->request('GET', 'sofp/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        setcookie('fileDownload', 'true', 0, '/');
        echo $data;
        die();
    }

    /**
     * 損益表 UI
     * 
     * @created_at      2021-07-30
     * @created_by      Jack
     */
    public function soci()
    {
        $this->load->view(
            'admin/erp/soci',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/soci.js'
                ]
            ]
        );
    }

    /**
     * 取得損益表 API 資料
     * 
     * @created_at            2022-02-14
     * @created_at            Jack
     * @updated_at            2021-10-12
     * @updated_by            Allan
     */
    public function get_soci_data()
    {
        $data = $this->erp_client->request('GET', 'soci', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }
    
    public function soci_spreadsheet()
    {
        $res = $this->erp_client->request('GET', 'soci/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        setcookie('fileDownload', 'true', 0, '/');
        echo $data;
        die();
    }

    public function journal()
    {
        $this->load->view(
            'admin/erp/journal',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/journal.js'
                ]
            ]
        );
    }

    /**
     * 取得日記簿 API 資料
     * 
     * @created_at            2021-08-13
     * @created_at            Jack
     * @updated_at            2021-09-14
     * @updated_by            Jack
     */
    public function get_journal_data()
    {
        $data = $this->erp_client->request('GET', 'entry', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 資產負債表 Excel 下載
     * 
     * @created_at      2021-08-16
     * @created_by      Jack
     */
    public function journal_spreadsheet()
    {
        if ($data = $this->input->post('data'))
        {
            $data = json_decode(urldecode(base64_decode($data)), TRUE);
            $sheet = utility('spreadsheet')->from_template('erp_journal');

            $sheet->insert_row(3, count($data));

            $sheet->from_array(array_map(function($row) {
                return [

                    // 日期
                    $row['date'],

                    // 傳票編號
                    $row['voucher_no'],

                    // 會計科目
                    $row['grade'],

                    // 科目名稱
                    $row['account'],

                    // 摘要
                    $row['details'],

                    // 借方金額
                    $row['is_debit'] ? $row['amount'] : '',

                    // 貸方金額
                    $row['is_debit'] ? '' : $row['amount'],

                    // 備註
                    $row['remark'],

                ];
            }, $data), 'A2');

            $sheet->output('journal_' . date('His'));
        }
        exit;
    }

    public function balance_sheet()
    {
        $this->load->view(
            'admin/erp/balance_sheet',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/balance_sheet.js'
                ]
            ]
        );
    }

    /**
     * 取得開帳表字典 API 資料
     * 
     * @created_at            2022-10-21
     * @created_at            Allan
     */
    public function get_balance_sheet_dict()
    {
        $data = $this->erp_client->request('GET', 'balance_sheet/dict', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }

     /**
     * 取得開帳表差異 API 資料
     * 
     * @created_at            2022-10-21
     * @created_at            Allan
     */
    public function get_balance_sheet_diff()
    {
        $data = $this->erp_client->request('GET', 'balance_sheet/diff', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }

    /**
     * 分類帳 UI
     * 
     * @created_at      2021-08-31
     * @created_by      Jack
     */
    public function ledger()
    {
        $this->load->view(
            'admin/erp/ledger',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/ledger.js'
                ]
            ]
        );
    }

    /**
     * 取得分類帳 API 資料
     * 
     * @created_at            2021-08-13
     * @created_at            Jack
     * @updated_at            2021-09-14
     * @updated_by            Jack
     */
    public function get_ledger_data()
    {

        try
        {
            // 取得 request data
            $data = base64_decode($this->input->get_post('data'));
            $data = json_decode(urldecode($data), TRUE);

            $year    = $data['year'] ?? null;
            $month   = $data['month'] ?? null;
            $user_id = $data['user_id'] ?? null;
            $role    = $data['role'] ?? 'investor';
        }
        catch (Exception $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->message
            ]);
        }

        // 呼叫 ERP API 取得結果
        $this->_output_json([
            'success' => TRUE,
            'data'    => $this->erp_lib->get_report('subsidiary_ledger', [
                'year'    => (int) $year,
                'month'   => (int) $month,
                'user_id' => $user_id,
                'role'    => $role,
            ])
        ]);
    }

    /**
     * 分類帳 Excel 下載
     * 
     * @created_at      2021-08-16
     * @created_by      Jack
     */
    public function ledger_spreadsheet()
    {
        if ($data = $this->input->post('data'))
        {
            $data = json_decode(urldecode(base64_decode($data)), TRUE);
            // $sheet = utility('spreadsheet')->from_template('erp_ledger');

            // $sheet->insert_row(3, count($data));

            // $sheet->from_array(array_map(function($row) {
            //     return [

            //         // 日期
            //         $row['date'],

            //         // 傳票編號
            //         $row['voucher_no'],

            //         // 會計科目
            //         $row['grade'],

            //         // 科目名稱
            //         $row['account'],

            //         // 摘要
            //         $row['details'],

            //         // 借方金額
            //         $row['is_debit'] ? $row['amount'] : '',

            //         // 貸方金額
            //         $row['is_debit'] ? '' : $row['amount'],

            //     ];
            // }, $data), 'A2');

            // $sheet->output('journal_' . date('His'));
        }
        exit;
    }

    /**
     * 取得借款案列表
     * 
     * @created_at            2021-08-13
     * @created_at            Jack
     * @updated_at            2021-09-14
     * @updated_by            Jack
     */
    public function get_targets()
    {
        try
        {
            // 取得 request data
            $data = base64_decode($this->input->get_post('data'));
            $data = json_decode(urldecode($data), TRUE);

            $this->load->model('loan/target_model');

            $this->_output_json([
                'success' => TRUE,
                'data'    => $this->target_model->search_target_list(
                    $data['target_type'] ?? 'normal',
                    $data['start_date'],
                    $data['end_date'],
                    empty($data['investor_id']) ? null : (int) $data['investor_id'],
                    empty($data['borrower_id']) ? null : (int) $data['borrower_id']
                )
            ]);
        }
        catch (Exception $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->message
            ]);
        }
    }

    /**
     * 取得借款案內容
     * 
     * @created_at            2021-08-13
     * @created_at            Jack
     * @updated_at            2021-09-14
     * @updated_by            Jack
     */
    public function get_investments()
    {
        try
        {
            // 取得 request data
            $data = base64_decode($this->input->get_post('data'));
            $data = json_decode(urldecode($data), TRUE);

            $this->load->model('loan/investment_model');
            $this->load->library('contract_lib');

            $investments = $this->investment_model->find_investments_by_target_no($data['target_no']);

            switch (strtolower($data['target_type'] ?? 'normal'))
            {
                case 'overdue':
                    $output_data = array_map(function(&$item) {
                        $item['contract'] = $this->contract_lib->get_overdue_contract($item['contract_id']);
                        return $item;
                    }, $investments);
                    break;

                case 'normal':
                default:
                    $output_data = array_map(function(&$item) {
                        $item['contract'] = $this->contract_lib->get_contract($item['contract_id']);
                        return $item;
                    }, $investments);
                    break;
            }

            $this->_output_json([
                'success' => TRUE,
                'data'    => $output_data
            ]);
        }
        catch (Exception $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->message
            ]);
        }
    }

    /**
     * 移轉帳務至 ERP 帳務
     * 
     * @created_at            2021-09-14
     * @created_by            Jack
     */
    public function target_testfy()
    {
        try
        {
            // 取得 request data
            $data = base64_decode($this->input->get_post('data'));
            $data = json_decode(urldecode($data), TRUE);

            Service('target', $data['target_no'])->testfy();

        }
        catch (Throwable $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->getMessage()
            ]);
        }

        $this->_output_json([
            'success' => TRUE
        ]);
    }

    public function open_booking()
    {
        $this->load->view(
            'admin/erp/open_booking',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/open_booking.js'
                ]
            ]
        );
    }

    /**
     * 取得投資人開帳作業資料
     */
    public function get_open_booking_data()
    {
        try
        {
            // 取得 request data
            $data = base64_decode($this->input->get_post('data'));
            $data = json_decode(urldecode($data), TRUE);

            if (empty($data['investor_id']))
            {
                throw new Exception('User Not Found.');
            }

            $user = Service('User')->find_investor($data['investor_id']);

            $targets = [];
            foreach ($user->targets as $target)
            {
                $targets[] = [
                    'target_id'   => $target->id,
                    'target_no'   => $target->target_no,
                    'type'        => $target->type,
                    'borrower_id' => $target->borrower_id,
                    'loan_amount' => $target->loan_amount,
                    'instalment'  => $target->instalment,
                    'status'      => $target->status,
                    'loan_date'   => $target->loan_date
                ];
            }
        }
        catch (Throwable $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->getMessage()
            ]);
        }

        $this->_output_json([
            'success' => TRUE,
            'data'    => [
                'targets' => $targets
            ]
        ]);
    }

    public function open_booking_process()
    {
        try
        {
            // 取得 request data
            $payload = base64_decode($this->input->get_post('data'));
            $payload = json_decode(urldecode($data), TRUE);

            // TODO: 取得所有本攤表資料
            // 投資人角度 user_id filter
            // 正常案: 每期本金 利息 回款手續費 借款給平台手續費 (開案結果)
            // 正常案(產轉): 每期本金 利息 回款手續費 借款給平台手續費 產轉給平台手續費 (開案結果)

            // 逾期案: 當期應還利息 應還違約金 應還延滯息 當期已還利息 已還違約金 已還延滯息 回款手續費 還款幾期 第幾期開始逾期
            // 債轉轉出案: 哪期轉出 價金 債轉手續費
            // 債轉轉入案: 哪期轉入 價金
            // 債轉包轉出案: 每債權哪期轉出 價金等比例(4捨5入) 債轉手續費
            // 債轉包轉入案: 每債權哪期轉入 價金等比例(4捨5入)
            // 提前清償案: 每期本金 利息 違約金 回款手續費 補償金 第幾期清償

            $payload['user_id'] = 47206;

            $user = Service('User')->find_investor((int) $payload['user_id']);

            $data = [
                'datetime'  => date(DATE_ISO8601),
                'projects'  => [],
                'contracts' => [],
                'investors' => [],
            ];

            $data['investors'][] = [
                'user_id'         => $user->id,
                'title'           => $user->title,
                'bank_account'    => $user->bank_account,
                'virtual_account' => $user->virtual_account,

                // 虛擬帳戶餘額
                'virtual_account_balance' => $user->virtual_account_balance,

                // 待提領金額
                'pending_withdraw' => $user->pending_withdraw,

                // 得標待結轉金額 (債權投資)
                'pending_carried_forward' => $user->pending_carried_forward,

                // 得標待結轉金額 (債權轉讓投資)
                'pending_transfer_carried_forward' => $user->pending_transfer_carried_forward,

                // 本金餘額
                'principal_balance'    => $user->principal_balance,

                // 應收利息
                'interest_receivable'  => $user->interest_receivable,

                // 應收延滯息
                'demurrage_receivable' => $user->demurrage_receivable,
            ];

            // 依 user 取 target 測試帳務總額
            foreach ($user->targets as $target)
            {
                switch (TRUE)
                {
                    case empty($project = $target->p2p_project):
                    case empty($contracts = $project->get_contracts_by_investor($user->id)):
                        continue 2;
                }

                $project_info = [
                    'type'           => $target->type,
                    'current_period' => $project->current_period,
                ];

                switch (TRUE)
                {
                    case $target->is_cleanup:
                        $project_info['penalty'] = $project->penalty;
                        break;
                }

                $data['projects'][] = array_merge(
                    $project->__serialize(),
                    $project_info
                );

                foreach ($contracts as $contract)
                {

                    // 正常案
                    // $amortization_schedule = $target->amortization_schedule;
                    // var_dump($amortization_schedule);exit;
                    // $platform_fee          = $target->platform_fee;
                    // $subloan_fee           = $target->subloan_fee;
                    // $transactions          = $target->transactions;

                    // 逾期案
                    // 應還本金 SOURCE_AR_PRINCIPAL && status == 1
                    // 應還違約金 SOURCE_AR_DAMAGE && status == 1
                    // 應收延滯息 SOURCE_AR_DELAYINTEREST && status == 1
                    // 當期應還利息 SOURCE_AR_INTEREST && status == 1
                    // 已還本金 SOURCE_PRINCIPAL
                    // 已還違約金 SOURCE_DAMAGE
                    // 已還延滯息 SOURCE_DELAYINTEREST
                    // 已還應還利 SOURCE_INTEREST
                    // 還款幾期 = 應還本金 transaction 的期數 - 1期
                    // 第幾期開始逾期 = 應還本金 transaction 的期數

                    // 債轉轉出案
                    // 哪期轉出: p2p_loan.transfers 表格的 instalment 是剩餘期數 (用總期數-剩餘期數)
                    // 價金: p2p_loan.transfers 表格的 amount
                    // 債轉手續費: p2p_loan.transfers 表格的 transfer_fee

                    // 債轉轉入案
                    // 哪期轉入: p2p_loan.transfer_investment transfer_id 關聯之 p2p_loan.transfers 表格的 instalment 是剩餘期數 (用總期數-剩餘期數)
                    // 價金: p2p_loan.transfer_investment 的 amount

                    // 債轉包 p2p_loan.transfers 的 combination 為非 0 時，不再參考 p2p_loan.transfers 之金額
                    // 而是以 p2p_loan.transfer_combination 的 amount principal interest 等欄位做計算
                    // 故需對所有 p2p_loan.transfers 的 combination 做搜尋篩選

                    // 提前清償案:
                    // 剩餘本金: p2p_loan.prepayment 的 principal
                    // 利息:  p2p_loan.prepayment 的 interest
                    // 違約金:  p2p_loan.prepayment 的 damage
                    // 回款手續費: SOURCE_FEES
                    // 補償金: SOURCE_PREPAYMENT_ALLOWANCE
                    // 第幾期清償: 對應 transaction 之 SOURCE_PRINCIPAL && status == 2 的最後一筆之期數

                    $info = [
                        'type' => $contract->type
                    ];
                    switch (TRUE)
                    {

                        // 債轉轉入
                        case $contract->is_transfered_in:
                            // var_dump($contract->id, 'transfered_in');
                            break;

                        // 債轉轉出
                        case $contract->is_transfered_out:
                            // var_dump($contract->id, 'transfered_out');
                            break;

                        // 逾期
                        case $contract->is_overdue:
                            // var_dump($contract->id, 'overdue');
                            break;

                        // 提前清償
                        case $contract->is_cleanup:

                            // 剩餘本金
                            $info['principal_balance'] = $contract->principal_balance ?? 0;

                            // 當期應收利息 (已過天數利息)
                            $info['interest_amount'] = $contract->interest_amount_by_days;

                            // 回款手續費
                            $info['payback_fee'] = round(

                                // 還款金額 1%
                                ($contract->principal_balance + $contract->interest_amount) * 0.01
                            );

                            // 補償金 (補貼)
                            $info['subsidy'] = $contract->subsidy ?? 0;
                            break;

                        // 正常案
                        case $contract->is_normal:
                            break;

                        default:
                            continue 2;
                    }

                    // var_dump($info);exit;

                    $data['contracts'][] = array_merge($contract->__serialize(), $info);
                }
            }
            echo json_encode($data);exit;
            $result = $this->erp_lib->transaction('open_booking', $data);
            var_dump($result);
            exit;
        }
        catch (Throwable $e)
        {
            $this->_output_json([
                'success' => FALSE,
                'message' => $e->getMessage()
            ]);
        }

        $this->_output_json([
            'success' => TRUE
        ]);
    }
}
