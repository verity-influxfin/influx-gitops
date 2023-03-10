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

        $this->erp_client = new Client([
            'base_uri' => getenv('ENV_ERP_HOST'),
            'timeout' => 300,
        ]);

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
     * 取得本攤表v2 Excel 下載
     * 
     * @created_at            2021-10-27
     * @created_at            Allan
     */
    public function get_replayment_spreadsheet()
    {
        // get file from guzzle replayment_schedule/excel
        $res = $this->erp_client->request('GET', '/coded_replayment_schedules_list/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        echo $data;
        die();
    }

    /**
     * 取得堆疊後本攤表 API 資料
     * 
     * @created_at            2021-11-03
     * @created_at            Allan
     */
    public function get_stack_replayment_schedule_data()
    {
        $data = $this->erp_client->request('GET', '/stack_replayment_schedule', [
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
    public function get_stack_replayment_schedule_spreadsheet()
    {
      $res = $this->erp_client->request('GET', '/stack_replayment_schedule/excel', [
          'query' => $this->input->get()
      ]);
      $des = $res->getHeader('content-disposition')[0];
      $data = $res->getBody()->getContents();
      // create download file by data
      header('content-type: application/octet-stream');
      header('content-disposition:' . $des);
      header('content-length: ' . strlen($data));
      echo $data;
      die();
    }

    /**
     * 資產負債表 UI
     * 
     * @created_at      2021-07-30
     * @created_by      Jack
     * @updated_by      Allan
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
     * @updated_by            Allan
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
     * @updated_by      Allan
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
     * 取得平台分類帳 API 資料
     * 
     * @created_at            2022-02-24
     * @created_at            Allan
     */
    public function erp_balance_sheet()
    {
        $data = $this->erp_client->request('GET', 'erp_balance_sheet', [
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
     * @updated_by      Allan
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
     * 發票資料查詢 UI
     * 
     * @created_at      2023-03-07
     * @updated_by      Allan
     */
    public function receipt(){
        $this->load->view(
            'admin/erp/receipt',
            $data = [
                'menu'      => $this->menu,
                'use_vuejs' => TRUE,
                'scripts'   => [
                    '/assets/admin/js/erp/receipt.js'
                ]
            ]
        );
    }
    public function get_receipt()
    {
        $data = $this->erp_client->request('GET', 'receipt', [
            'query' => $this->input->get()
        ])->getBody()->getContents();
        echo $data;
        die();
    }
    public function receipt_spreadsheet()
    {
        $res = $this->erp_client->request('GET', 'receipt/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        echo $data;
        die();
    } 
}
