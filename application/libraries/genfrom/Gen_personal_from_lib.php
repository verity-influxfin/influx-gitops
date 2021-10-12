<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gen_personal_from_lib
{
    public $input_data;
    public $empty = '<img src="/assets/slash.png" style="height:20px;width:300px"/>';
    public $select = '<span style="height:25px;width:25px;background-color:black;color:black">11</span>';
    public $unselect = '&#9633;';

    public function __construct($data)
    {
        $this->CI = &get_instance();

        $this->input_data = $data; //用這東西來解析與塞資料

        $this->pdf = new TCPDF('', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->pdf->SetFont('msungstdlight', '', 10);
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetDefaultMonospacedFont('courier');
        $this->pdf->SetMargins(20, 20, 20, 20);
        $this->pdf->setFontSubsetting(true);
    }

    public function create_pdf_form()
    {
        $business = $this->_create_business_table();
        $economic = $this->_create_economic_table();

        $html = '
            <h1 style="text-align:center;">個人資料表</h1>

            <div>
                <span>擔任本案之</span>' .
            $this->select . '<span>負責人</span>' .
            $this->unselect . '<span>負責人配偶</span>' .
            $this->unselect . '<span>保證人</span>' .
            $this->unselect . '<span>實際負責人（可複選）</span>
            </div>
            <br>
            <br>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="2">姓名</td>
                    <td rowspan="2">xxxxx</td>
                    <td rowspan="2">性別</td>
                    <td rowspan="2">xcvxv</td>
                    <td rowspan="2">出生地</td>
                    <td rowspan="2">xxxxx</td>
                    <td rowspan="2">出生年月日</td>
                    <td rowspan="2">xxxxx</td>
                    <td colspan="2">身分證統一編號</td>
                </tr>
                <tr><td colspan="2">xxxxxxxxx</td></tr>
                <tr><td>住址</td><td colspan="7"><div>xxxxxxxxx</div><div style="text-align:start">' . $this->unselect . '同戶籍地址</div></td><td>聯絡電話</td><td>xxxxxxxxx</td></tr>
                <tr><td>學歷</td><td colspan="7">xxxxxxxxx</td><td>行動電話</td><td>xxxxxxxxx</td></tr>
                <tr><td>經歷</td><td colspan="9" rowspan="2">xxxxxxxxx</td></tr>
            </table>

            <h2>經營事業（或職業）</h2>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td>事業（或服務單位）名稱</td>
                    <td>統一編號</td>
                    <td>實收資本額</td>
                    <td>出資額</td>
                    <td>職位</td>
                </tr>
                ' . $business . '
            </table>

            <h2>經濟狀況（<input type="text" style="width:40px;border:0px;border-bottom: 1px solid #000000;">年<input type="text" style="width:40px;border:0px;border-bottom: 1px solid #000000;">月<input type="text" style="width:40px;border:0px;border-bottom: 1px solid #000000;">日）</h2>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td>項目</td>
                    <td colspan="2">摘要</td>
                    <td>金額</td>
                    <td>項目</td>
                    <td colspan="2">摘要</td>
                    <td>金額</td>
                </tr>
                ' . $economic . '
            </table>
            
            <h2>其他說明：</h2>
            <p style="width: 100%;height: 20px;margin-bottom: 10px;border-bottom: 1px solid #000000;">xxxxxxxxx</p>
            <p style="width: 100%;height: 20px;margin-bottom: 10px;border-bottom: 1px solid #000000;">xxxxxxxxx</p>
            <p style="width: 100%;height: 20px;margin-bottom: 10px;border-bottom: 1px solid #000000;">xxxxxxxxx</p>
            <p style="width: 100%;height: 20px;margin-bottom: 10px;border-bottom: 1px solid #000000;">xxxxxxxxx</p>
            <p style="width: 100%;height: 20px;margin-bottom: 10px;border-bottom: 1px solid #000000;">xxxxxxxxx</p>
            <p style="width: 100%;height: 20px;margin-bottom: 10px;border-bottom: 1px solid #000000;">xxxxxxxxx</p>
        ';

        $this->pdf->AddPage('', 'A4');
        $this->pdf->writeHTML($html);
        $this->pdf->Output(FCPATH . '/genpdf/personal.pdf', 'F');
    }

    private function _create_business_table($data = '')
    {
        $html = '
            <tr>
                <td><img src="/assets/slash.png" style="height:45px;width:300px"/></td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
            </tr>
            <tr>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
            </tr>
            <tr>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
                <td>xxxxxxxxx</td>
            </tr>
        ';

        return $html;
    }

    private function _create_economic_table()
    {
        $html = '
        <tr>
            <td rowspan="3">存款</td>
            <td>銀行別</td>
            <td>存款種類及帳號</td>
            <td></td>
            <td rowspan="3">銀行借款</td>
            <td>銀行別</td>
            <td>借款種類</td>
            <td></td>
        </tr>
        <tr>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
        </tr>
        <tr>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
            <td>xxxxxxxxx</td>
        </tr>
    ';

        return $html;
    }
}
