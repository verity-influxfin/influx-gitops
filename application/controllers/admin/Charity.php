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

    public function charity_export()
    {
        $export_file_name = '捐款明細_' . date('ymdhis');

        $post = $this->input->post(NULL, TRUE);
        $sdate = $post['sdate'] ?? '';
        $edate = $post['edate'] ?? '';

        $data = $this->charity_model->get_download_info($sdate, $edate);

        $cell1 = [];
        $cell2 = [
            [
                '20220304',
                '跨行轉帳手續費',
                '15',
            ],
        ];

        if ($data)
        {
            foreach ($data as $key => $value)
            {
                $cell1[] = [
                    $value['name'],
                    $value['id_number'],
                    $value['phone'],
                    $value['phone'],
                    $value['email'],
                    '郵遞區號',
                    '縣市',
                    '鄉鎮市區',
                    '地址',
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
        $spreadsheet->getProperties()->setTitle('settitle捐款明細');

        foreach ($excel_contents as $sheet => $contents)
        {
            $sheet > 0 ? $spreadsheet->createSheet() : '';
            $row = 1;

            foreach ($contents['title'] as $titleIndex => $title)
            {
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha($titleIndex) . ($row), $title);
                $spreadsheet->getActiveSheet($sheet)->getStyle($this->num2alpha($titleIndex) . ($row))->getAlignment()->setHorizontal('center');
            }
            $row++;

            foreach ($contents['content'] as $rowInddex => $rowContent)
            {
                foreach ($rowContent as $rowContentInddex => $rowValue)
                {
                    $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($this->num2alpha($rowContentInddex) . ($row), $rowValue);
                    $spreadsheet->getActiveSheet($sheet)->getStyle($this->num2alpha($rowContentInddex) . ($row))->getAlignment()->setHorizontal('center');
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
        header('Cache-Control: max-age=1');

        $writer->save('php://output');
        exit;
    }

    private function num2alpha($n)
    {
        for ($r = ''; $n >= 0; $n = intval($n / 26) - 1)
        {
            $r = chr($n % 26 + 0x41) . $r;
        }

        return $r;
    }
}
