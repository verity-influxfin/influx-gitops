<?php defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/MY_Admin_Controller.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Charity extends MY_Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('transaction/charity_model');
    }

    public function index()
    {
        $get = $this->input->get(NULL, TRUE);
        $sdate = $get['sdate'] ?? '';
        $edate = $get['edate'] ?? '';

        $page_data['list'] = $this->charity_model->get_donor_list($sdate, $edate);
        $page_data['sdate'] = $sdate;
        $page_data['edate'] = $edate;
        $page_data['receipt_type_list'] = [
            0 => '單筆收據',
            1 => '不寄收據',
        ];

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/charity.php', $page_data);
        $this->load->view('admin/_footer');
    }

    public function export()
    {
        $export_file_name = '捐款明細_' . date('ymd');

        $post = $this->input->post(NULL, TRUE);
        $sdate = $post['sdate'] ?? '';
        $edate = $post['edate'] ?? '';

        // 取得區間內的報表資料 & 提款手續費紀錄
        $data_donate = $this->charity_model->get_download_info($sdate, $edate);
        $data_fees = [];

        // 取得捐款單位相關資料
        $this->load->model('user/charity_institution_model');
        $charity_alias = $this->charity_institution_model->TaiwanUnivHospitalAliasName;
        $institution = $this->charity_institution_model->get_by([
            'alias' => $charity_alias,
            'status' => 1,
        ]);

        // $institution->judicial_person_id
        $this->load->model('user/judicial_person_model');
        $judicial_person = $this->judicial_person_model->get($institution->judicial_person_id);

        // $judicial_person->user_id
        $this->load->model('user/user_bankaccount_model');
        $user_bankaccount = $this->user_bankaccount_model->get_by([
            'user_id' => $judicial_person->user_id,
            'status' => 1,
            'verify' => 1,
        ]);

        if ($user_bankaccount)
        {
            $user_back_acc = $user_bankaccount->bank_code . str_pad($user_bankaccount->bank_account, 16, '0', STR_PAD_LEFT);
            $this->load->model('transaction/payment_model');
            $data_fees = $this->payment_model->get_chraity_payment_info($user_back_acc, $sdate, $edate);
        }

        $cell1 = [];
        $cell2 = $data_fees;

        if ($data_donate)
        {
            foreach ($data_donate as $key => $value)
            {
                $address_array = $this->_parser_address($value['address_data'] ?? '');
                $cell1[] = [
                    $value['name'],
                    $value['id_number'],
                    $value['phone'],
                    $value['phone'],
                    $value['email'],
                    $address_array['code'],
                    $address_array['city'],
                    $address_array['area'],
                    $address_array['address'],
                    $value['donate_day'],
                    '普匯APP',
                    '專案捐款',
                    '111孩子健康看見希望',
                    $value['amount'],
                    $value['receipt_type'],
                    $value['name'],
                    $value['name'],
                    $value['upload'],
                ];
            }
        }

        $sheet_title1 = ['捐款人', '身分證/統編/居留證', '電話', '行動電話', '電子信箱', '郵遞區號', '縣市', '鄉鎮市區', '地址', '捐款日期', '捐款方式', '捐款用途', '專案活動', '捐款金額', '收據開立', '收據抬頭', '徵信抬頭', '是否願意將捐款資料上傳國稅局報稅'];
        $sheet_title2 = ['處理日', '項目', '金額'];
        $excel_contents = [
            [
                'sheet' => '代收捐款明細',
                'title' => $sheet_title1,
                'content' => $cell1,
            ],
            [
                'sheet' => '代收費用明細',
                'title' => $sheet_title2,
                'content' => $cell2,
            ],
        ];

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setTitle('捐款明細');

        foreach ($excel_contents as $sheet => $contents)
        {
            $sheet > 0 ? $spreadsheet->createSheet() : '';
            $row = 1;

            foreach ($contents['title'] as $titleIndex => $title)
            {
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->_num2alpha($titleIndex) . ($row), $title);
                $spreadsheet->getActiveSheet($sheet)->getStyle($this->_num2alpha($titleIndex) . ($row))->getAlignment()->setHorizontal('center');
            }
            $row++;

            foreach ($contents['content'] as $rowInddex => $rowContent)
            {
                foreach ($rowContent as $rowContentInddex => $rowValue)
                {
                    $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->_num2alpha($rowContentInddex) . ($row), $rowValue);
                    $spreadsheet->getActiveSheet($sheet)->getStyle($this->_num2alpha($rowContentInddex) . ($row))->getAlignment()->setHorizontal('center');
                }
                $row++;
            }

            $spreadsheet->setActiveSheetIndex($sheet)->setTitle($contents['sheet']);
            $spreadsheet->getActiveSheet($sheet)->getDefaultColumnDimension()->setWidth(20);

        }
        $spreadsheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $export_file_name . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function _num2alpha($n)
    {
        for ($r = ''; $n >= 0; $n = intval($n / 26) - 1)
        {
            $r = chr($n % 26 + 0x41) . $r;
        }

        return $r;
    }

    // 處理地址相關參數
    private function _parser_address($address)
    {
        $address_data = [
            'code' => '',
            'city' => '',
            'area' => '',
            'address' => '',
        ];

        if (empty($address))
        {
            return $address_data;
        }

        $this->load->library('mapping/address');
        $split_address = $this->address->splitAddress($address);

        $address_data = [
            'code' => $this->address->getZipAdrNumber($address),
            'city' => $split_address['city'],
            'area' => $split_address['area'],
            'address' => $split_address['road'] . $split_address['part'] . $split_address['lane'] . $split_address['alley'] . $split_address['number'] . $split_address['sub_number'] . $split_address['floor'] . $split_address['floor'],
        ];

        // 如果用戶有填寫內容但無法解析出縣市地址，就直接把他寫的東西丟進 address
        if (empty($address_data['city'])
            && empty($address_data['area'])
            && empty($address_data['address']))
        {
            $address_data['address'] = $address;
        }

        return $address_data;
    }
}
