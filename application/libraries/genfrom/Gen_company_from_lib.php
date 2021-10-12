<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gen_company_from_lib
{
    public $input_data;
    public $empty = '<img src="/assets/slash.png" style="height:20px;width:300px"/>';
    public $select = '<span style="height:25px;width:25px;background-color:black;color:black">11</span>';
    public $unselect ='&#9633;';
	public $select_mapping = [
		'CompType' => [
			// 獨資
			'41' => [
				'ProprietorShipSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
				'SMEsSelect' => '&#9633;',
			],
			// 中小企業
			'21' => [
				'ProprietorShipSelect' => '&#9633;',
				'SMEsSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
			],
		],
		'BusinessType' => [
			// 製造
			'A' => [
				'ManufacturingSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
				'SellingSelect' => '&#9633;',
				'OtherSelect' => '&#9633;',
			],
			// 買賣
			'B' => [
				'ManufacturingSelect' => '&#9633;',
				'SellingSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
				'OtherSelect' => '&#9633;',
			],
			// 其他
			'C' => [
				'ManufacturingSelect' => '&#9633;',
				'SellingSelect' => '&#9633;',
				'OtherSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
			],
		],
		'BizTaxFileWay' => [
			'A' => [
				'UseInvoiceSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
				'InvoiceExceptionSelect' => '&#9633;',
				'NotDutySelect' => '&#9633;',
				'DutyFreeSelect' => '&#9633;',
			],
			'B' => [
				'UseInvoiceSelect' => '&#9633;',
				'InvoiceExceptionSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
				'NotDutySelect' => '&#9633;',
				'DutyFreeSelect' => '&#9633;',
			],
			'C' => [
				'UseInvoiceSelect' => '&#9633;',
				'InvoiceExceptionSelect' => '&#9633;',
				'NotDutySelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
				'DutyFreeSelect' => '&#9633;',
			],
			'D' => [
				'UseInvoiceSelect' => '&#9633;',
				'InvoiceExceptionSelect' => '&#9633;',
				'NotDutySelect' => '&#9633;',
				'DutyFreeSelect' => '<span style="height:25px;width:25px;background-color:black;color:black">11</span>',
			],
		],
	];

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
        $this->pdf->SetAutoPageBreak(true);
    }

    public function create_pdf_form()
    {
		foreach($this->input_data as $key => $value){
			if(isset($this->select_mapping[$key])){
				// print_r($this->select_mapping[$key][$value]);exit;
				$this->input_data[$key] = $this->select_mapping[$key][$value];
			}
		}
		// print_r($this->input_data);exit;
        $this->_create_cover();
        $this->_create_content();

        $this->pdf->Output(FCPATH . '/genpdf/company.pdf', 'F');
    }

    private function _create_cover()
    {
        $html = '
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <h1 style="text-align:center;">公司資料表</h1>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div style="text-align: center;"><label>企業名稱：</label><span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Name'].'</span></div>
        <br/>
        <br/>
        <br/>
        <br/>
        <div style="text-align: center;"><label>地址：</label><span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Address'].'</span></div>
        <br/>
        <br/>
        <br/>
        <br/>
        <div style="text-align: center;"><label>電話：</label><span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Phone'].'</span></div>
        ';

        $this->pdf->AddPage('', 'A4');
        $this->pdf->writeHTML($html);
    }

    private function _create_content()
    {
        $organization = $this->_create_organization_table();
        $directors = $this->_create_directors_table();
        $operators = $this->_create_operators_table();
        $estate = $this->_create_estate_table();
        $business = $this->_create_business_table();
        $bank = $this->_create_bank_table();

		// 組織沿革表格
		$organization_table_rowspan = count($this->input_data['history']) >= 3 ? count($this->input_data['history'])+1 :'4';
		// 董監事名冊表格
		$directors_table_rowspan = count($this->input_data['directors']) >= 3 ? count($this->input_data['directors'])+1 :'4';

        $html = '
            <table>
                <tr><td>營利事業名稱：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Name'].'</span> 營利事業統一編號：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Id'].'</span></td></tr>
                <tr><td>負責人姓名：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Owner'].'</span> 身分證統一編號：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['OwnerId'].'</span></td></tr>
                <tr><td>營業所在地：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['RealAddress'].'</span>'.$this->unselect.'同登記地址 電話：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Phone'].'</span> 聯絡人：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['ContectName'].'</span></td></tr>
                <tr><td>（實收）資本額：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['Capital'].'</span> 設立日期：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['CreateYear'].'</span> 年<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['CreateMonth'].'</span> 月<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['CreateDate'].'</span> 日</td></tr>
                <tr><td>組織（公司型態）：<span style="margin-left:5px;">'.$this->input_data['CompType']['SMEsSelect'].'中小企業</span><span style="margin-left:5px;">'.$this->input_data['CompType']['ProprietorShipSelect'].'獨資</span></td></tr>
                <tr><td>主要營業種類：<span style="margin-left:5px;">'.$this->input_data['BusinessType']['ManufacturingSelect'].'製造</span><span style="margin-left:5px;">'.$this->input_data['BusinessType']['SellingSelect'].'買賣</span><span style="margin-left:5px;">'.$this->input_data['BusinessType']['OtherSelect'].'其他</span></td></tr>
                <tr><td>公司產業別：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['CompDuType'].'</span></td></tr>
                <tr><td>營業稅申報方式：<span style="margin-left:5px;">'.$this->input_data['BizTaxFileWay']['UseInvoiceSelect'].'使用統一發票</span><span style="margin-left:5px;">'.$this->input_data['BizTaxFileWay']['InvoiceExceptionSelect'].'免用統一發票核定繳納營業稅</span><span style="margin-left:5px;">'.$this->input_data['BizTaxFileWay']['NotDutySelect'].'未達課稅起徵點</span><span style="margin-left:5px;">'.$this->input_data['BizTaxFileWay']['DutyFreeSelect'].'免徵營業稅或執行業務</span></td></tr>
            </table>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="'.$organization_table_rowspan.'">組織沿革</td>
                    <td>日期</td>
                    <td>負責人</td>
                    <td>重大事項</td>
                    <td>資本額（仟元）</td>
                </tr>
                ' . $organization . '
            </table>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="'.$directors_table_rowspan.'">董監事名冊</td>
                    <td>職稱</td>
                    <td>姓名</td>
                    <td>身分證字號</td>
                    <td>所代表法人</td>
                </tr>
                ' . $directors . '
            </table>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="3">實際經營者</td>
                    <td>職稱</td>
                    <td>姓名</td>
                    <td>出生年月日</td>
                    <td>任職公司</td>
                    <td>持股比率（％）</td>
                    <td>與負責人之關係</td>
                </tr>
                ' . $operators . '
            </table>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;">
                <tr>
                    <td style="padding: 5px;">
						<div>員工狀況：</div>
						<div>目前員工人數共<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['now'].'</span>名</div>
						<div>月末投保資料<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['number_of_insured'].'</span>名</div>
					</td>
                </tr>
                <tr>
                    <td style="padding: 5px;">
                        <div>近一年來最高員工人數：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['max']['year'].'</span>年<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['max']['month'].'</span>月<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['max']['date'].'</span>日<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['max']['people'].'</span>人</div>
                        <div>近一年來最低員工人數：<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['min']['year'].'</span>年<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['min']['month'].'</span>月<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['min']['date'].'</span>日<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">'.$this->input_data['insurance']['min']['people'].'</span>人</div>
                    </td>
                </tr>
            </table>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="3">不動產</td>
                    <td>座落所在地區段、地址</td>
                    <td>門牌地址（包含郵遞區號）</td>
                    <td>所有權人</td>
                    <td>他項權利設定</td>
                </tr>
                ' . $estate . '
            </table>
            <p>註：檢附所有權狀影本或謄本</p>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="4">營業概況</td>
                    <td>主要業務項目</td>
                    <td>佔營收比重（％）</td>
                    <td>主要商品名稱</td>
                    <td>佔營收比重（％）</td>
                </tr>
                ' . $business . '
            </table>
            <br/>
            <br/>
            <table border="1" style="width:100%;border-collapse: collapse;text-align: center;">
                <tr>
                    <td rowspan="4">銀行往來</td>
                    <td rowspan="2">銀行名稱</td>
                    <td colspan="3">存款（<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">xx</span>年<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">xx</span>月<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">xx</span>日）</td>
                    <td colspan="3">借款（<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">xx</span>年<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">xx</span>月<span style="margin:0px 5px;padding:0px 5px;border-bottom:1px solid #000000">xx</span>日）</td>
                </tr>
                <tr>
                    <td>種類</td>
                    <td>帳號</td>
                    <td>餘額</td>
                    <td>種類</td>
                    <td>額度</td>
                    <td>餘額</td>
                </tr>
                ' . $bank . '
            </table>
			<p>（不便提供）</p>
        ';

        $this->pdf->AddPage('', 'A4');
        $this->pdf->writeHTML($html);
    }

    private function _create_organization_table()
    {
		$html = '';
		if(count($this->input_data['history'])<=3){
			for($i=0;$i<3;$i++){
				if(isset($this->input_data['history'][$i])){
					$html .= '
					<tr>
						<td>'.$this->input_data['history'][$i]['date'].'</td>
						<td>'.$this->input_data['history'][$i]['owner'].'</td>
						<td>'.$this->input_data['history'][$i]['name'].'</td>
						<td>'.$this->input_data['history'][$i]['capitalt'].'</td>
					</tr>';
				}else{
					$html .= '
					<tr>
						<td>'.$this->empty.'</td>
						<td>'.$this->empty.'</td>
						<td>'.$this->empty.'</td>
						<td>'.$this->empty.'</td>
					</tr>';
				}
			}
		}else{
			foreach($this->input_data['history'] as $value){
				$html .='
				<tr>
					<td>'.$value['date'].'</td>
					<td>'.$value['owner'].'</td>
					<td>'.$value['name'].'</td>
					<td>'.$value['capitalt'].'</td>
				</tr>';
			}
		}
        // $html = '
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>' . $this->empty . '</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        // ';

        return $html;
    }

    private function _create_directors_table()
    {
		$html = '';
		if(count($this->input_data['directors'])<=3){
			for($i=0;$i<3;$i++){
				if(isset($this->input_data['directors'][$i])){
					$html .= '
					<tr>
						<td>'.$this->input_data['directors'][$i]['job'].'</td>
						<td>'.$this->input_data['directors'][$i]['name'].'</td>
						<td>'.$this->input_data['directors'][$i]['id'].'</td>
						<td>'.$this->input_data['directors'][$i]['judicialPerson'].'</td>
					</tr>';
				}else{
					$html .= '
					<tr>
						<td>'.$this->empty.'</td>
						<td>'.$this->empty.'</td>
						<td>'.$this->empty.'</td>
						<td>'.$this->empty.'</td>
					</tr>';
				}
			}
		}else{
			foreach($this->input_data['directors'] as $value){
				$html .='
				<tr>
					<td>'.$value['job'].'</td>
					<td>'.$value['name'].'</td>
					<td>'.$value['id'].'</td>
					<td>'.$value['judicialPerson'].'</td>
				</tr>';
			}
		}
        // $html = '
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        // ';

        return $html;
    }

    private function _create_operators_table()
    {
        $html = '
            <tr>
                <td>'.$this->input_data['operators']['job'].'</td>
                <td>'.$this->input_data['operators']['name'].'</td>
                <td>'.$this->input_data['operators']['birthday'].'</td>
                <td>'.$this->input_data['operators']['company'].'</td>
                <td>'.$this->input_data['operators']['rate_of_hold_shares'].'</td>
                <td>'.$this->input_data['operators']['relationship'].'</td>
            </tr>
            <tr>
                <td>'.$this->empty.'</td>
                <td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
            </tr>
        ';

        return $html;
    }

    private function _create_estate_table()
    {
		$html = '';

		for($i=0;$i<2;$i++){
			if(isset($this->input_data['estate'][$i])){
				$html .= '
				<tr>
					<td>'.$this->input_data['estate'][$i]['sec_address'].'</td>
					<td>'.$this->input_data['estate'][$i]['address'].'</td>
					<td>'.$this->input_data['estate'][$i]['owner'].'</td>
					<td>'.$this->input_data['estate'][$i]['other_right'].'</td>
				</tr>';
			}else{
				$html .= '
				<tr>
					<td>'.$this->empty.'</td>
					<td>'.$this->empty.'</td>
					<td>'.$this->empty.'</td>
					<td>'.$this->empty.'</td>
				</tr>
				';
			}
		}

        // $html = '
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>（營業地址）</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        // ';

        return $html;
    }

    private function _create_business_table()
    {

		$html = '';

		for($i=0;$i<3;$i++){
			if(isset($this->input_data['main_business'][$i])){
				$html .= '
				<tr>
					<td>'.$this->input_data['main_business'][$i]['name'].'</td>
					<td>'.$this->input_data['main_business'][$i]['revenue_proportion'].'</td>
				';
			}else{
				$html .= '
				<tr>
					<td>'.$this->empty.'</td>
					<td>'.$this->empty.'</td>
				';
			}
			if(isset($this->input_data['main_product'][$i])){
				$html .= '
					<td>'.$this->input_data['main_product'][$i]['name'].'</td>
					<td>'.$this->input_data['main_product'][$i]['revenue_proportion'].'</td>
				';
			}else{
				$html .= '
					<td>'.$this->empty.'</td>
					<td>'.$this->empty.'</td>
				';
			}
			$html .= '</tr>';
		}
        // $html = '
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        //     <tr>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //         <td>xxxxxxxxx</td>
        //     </tr>
        // ';

        return $html;
    }

    private function _create_bank_table()
    {
		$html = '';
		if(count($this->input_data['directors'])>0){
			
		}else{
			$html = '
	        <tr>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
	            <td>'.$this->empty.'</td>
	            <td>'.$this->empty.'</td>
	            <td>'.$this->empty.'</td>
	        </tr>
	        <tr>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
				<td>'.$this->empty.'</td>
	        </tr>
	        ';
		}
        // $html = '
        // <tr>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        // </tr>
        // <tr>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        //     <td>xxxxxxxxx</td>
        // </tr>
        // ';


        return $html;
    }
}
