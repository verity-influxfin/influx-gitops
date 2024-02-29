<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use CertificationResult\CertificationResult;
use CertificationResult\MessageDisplay;
class Data_legalize_lib{

	public function __construct(){
    $this->CI = &get_instance();
		$this->certification = $this->CI->config->item('certifications');
  }

	/**
	 * [legalize_governmentauthorities 變卡正確性認證]
	 * @param  string $$user_id   [法人ID]
	 * @param  array  $data       [認證資料]
	 * (
	 * 	[圖片ID] =>
	 * 	   (
	 *  	[company_owner] => 公司登記負責人姓名
	 * 		[owner_id] => 公司登記負責人統一編號
	 * 		[tax_id] => 公司統一編號
	 * 	 	[company_address] => 公司登記地址
	 *  	[company_name] => 公司登記名稱
	 *  	[capital_amount] => 資本總額
	 *  	[paid_in_capital_amount] => 實收資本額
	 * 	   )
	 * )
	 * @return array  $res        [驗證結果]
	 * (
	 *  [error_location] => 資料不正確欄位
	 *  [error_message] => 資料錯誤說明備註
	 *  [result] =>
	 *           (
	 *            [company_type] => 公司型態
	 *            [company_name] => 公司登記名稱
	 *            [company_address] => 公司登記地址
	 *            [company_owner] => 公司登記負責人
	 *            [capital_amount] => 資本總額|實收資本額(實收資本額：如果公司型態為股份有限)
	 *           )
	 * )
	 */
	public function legalize_governmentauthorities($user_id='',$data=[]){
		$res = [
			'error_location' => [],
			'error_message' => [],
			'result' => [
				'company_type' => '',
				'company_name' => '',
				'company_address' => '',
				'company_owner' => '',
				'capital_amount' => '',
			],
		];
		$business_info = [];
		$company_info = [];
		foreach($data as $k=>$v){
			$group_id = $k;
			break;
		}
		$data = array_reduce($data, 'array_merge', array());

		$this->CI->load->model('user/user_model');
		// 商業司查詢
		$this->CI->load->library('gcis_lib');

		// 公司
		if($data['tax_id']){
			$company_info = $this->CI->gcis_lib->account_info($data['tax_id']);
			if($company_info){
				if($company_info['Company_Status_Desc'] != '核准設立'){
					$res['error_message'][] = '公司狀態不為核准設立';
					$res['error_location'][$group_id][] = 'tax_id';
				}
				// 公司名稱
				if($company_info['Company_Name'] != $data['company_name']){
					$res['error_message'][] = '公司名稱與商業司查詢不一致';
					$res['error_location'][$group_id][] = 'name';
				}
				$res['result']['company_name'] = $data['company_name'];
				// 公司負責人
				if($company_info['Responsible_Name'] != $data['company_owner']){
					$res['error_message'][] = '公司負責人與商業司查詢不一致';
					$res['error_location'][$group_id][] = 'owner';
				}
				$res['result']['company_owner'] = $data['company_owner'];
				// 公司地址
				if($company_info['Company_Location'] != $data['company_address']){
					$res['error_message'][] = '公司地址與商業司查詢不一致';
					$res['error_location'][$group_id][] = 'address';
				}
				$res['result']['company_address'] = $data['company_address'];
				// 公司型態
				if(preg_match('/股份有限公司/',$company_info['Company_Name'])){
					$res['result']['company_type'] = 4;
					if($company_info['Paid_In_Capital_Amount'] != $data['capital_amount']){
						$res['error_message'][] = '公司實收資本額與商業司查詢不一致';
						$res['error_location'][$group_id][] = 'capital';
					}
					$res['result']['capital_amount'] = $data['capital_amount'];
				}else{
					if(preg_match('/有限公司/',$company_info['Company_Name'])){
						$res['gcis_info']['company_type'] = 3;
						if($company_info['Capital_Stock_Amount'] != $data['capital_amount']){
							$res['error_message'][] = '公司資本總額與商業司查詢不一致';
							$res['error_location'][$group_id][] = 'capital';
						}
						$res['result']['capital_amount'] = $data['capital_amount'];
					}else{
						$res['error_message'][] = '無法判斷公司型態';
					}
				}

			}

			// 商業(行)
			$business_info = $this->CI->gcis_lib->account_info_businesss($data['tax_id']);
			if($business_info){
				if($company_info['Business_Current_Status_Desc'] != '核准設立'){
					$res['error_message'][] = '商業狀態不為核准設立';
					$res['error_location'][$group_id][] = 'tax_id';
				}
				// 公司名稱
				if($business_info['Business_Name'] != $data['company_name']){
					$res['error_message'][] = '公司名稱與商業司查詢不一致';
					$res['error_location'][$group_id][] = 'name';
				}
				$res['result']['company_name'] = $business_info['Business_Name'];
				// 公司負責人
				if($business_info['Responsible_Name'] != $data['company_owner']){
					$res['error_message'][] = '公司負責人與商業司查詢不一致';
					$res['error_location'][$group_id][] = 'owner';
				}
				$res['result']['company_owner'] = $business_info['Responsible_Name'];
				// 公司地址
				if($business_info['Business_Address'] != $data['company_address']){
					$res['error_message'][] = '公司地址與商業司查詢不一致';
					$res['error_location'][$group_id][] = 'address';
				}
				$res['result']['company_address'] = $business_info['Business_Address'];
				// 公司型態
				if($business_info['Business_Organization_Type_Desc'] == '獨資'){
					$res['result']['company_type'] = 1;
					if($company_info['Capital_Stock_Amount'] != $data['capital_amount']){
						$res['error_message'][] = '公司資本總額與商業司查詢不一致';
						$res['error_location'][$group_id][] = 'capital';
					}
					$res['result']['capital_amount'] = $company_info['Capital_Stock_Amount'];
				}
				if($business_info['Business_Organization_Type_Desc'] == '合夥'){
					$res['result']['company_type'] = 2;
					if($company_info['Capital_Stock_Amount'] != $data['capital_amount']){
						$res['error_message'][] = '公司資本總額與商業司查詢不一致';
						$res['error_location'][$group_id][] = 'capital';
					}
					$res['result']['capital_amount'] = $company_info['Capital_Stock_Amount'];
				}

			}

			// 商業司無資料 (商業與公司皆無資料)
			if(!$business_info && !$company_info){
				$res['error_message'][] = '統一編號查無商業司結果';
				$res['error_location'][$group_id][] = 'tax_id';
			}
		}

		// 變卡比對自然人實名
		if($user_id){
			$company_info = $this->CI->user_model->get_by(['id'=>$user_id,'company_status'=>1]);
			if($company_info){
				if(isset($company_info->phone)){
					$user_info = $this->CI->user_model->get_by(['phone'=>$company_info->phone,'company_status'=>0]);
					if(isset($user_info->name)){
						if($user_info->name != $data['company_owner']){
							$res['error_message'][] = '公司負責人姓名與自然人實名查詢不一致';
							$res['error_location'][$group_id][] = 'owner';
						}
                        if ( ! isset($data['owner_id']) || empty($data['owner_id']) || $user_info->id_number != $data['owner_id'])
                        {
							$res['error_message'][] = '公司負責人統一編號與自然人統一編號查詢不一致';
							$res['error_location'][$group_id][] = 'owner_id';
						}
					}else{
						$res['error_message'][] = '查無自然人資料';
						$res['error_location'][$group_id][] = 'owner';
					}
				}
			}else{
				$res['error_message'][] = '查無法人資料';
				$res['error_location'][$group_id][] = 'tax_id';
			}
		}

		return $res;
	}

	/**
	 * [legalize_incomestatement 損益表正確性驗證]
	 * @param  string $user_id [法人ID]
	 * @param  array  $data    [驗證資料]
	 * (
	 *  [0] =>
	 *      (
	 *       [id] => 圖片ID
	 *       [report_time] => 所得期間(以結算日為準)
	 *       [company_name] => 公司名稱
	 *       [company_tax_id] => 公司統一編號
	 *       [input_89] => 標準代號(89)
	 *      )
	 *  ...
	 * )
	 * @return array  $res     [驗證結果]
	 * (
	 *  [error_location] => 資料不正確欄位
	 *  [error_message] => 資料錯誤說明備註
	 *  [result] =>
	 *           (
	 *            [0] =>
	 *                (
	 *                 [report_time] => 所得期間
	 *                 [input_89] => 標準代號
	 *                )
	 *            ...
	 *           )
	 * )
	 */
	public function legalize_incomestatement($user_id='',$data=[]){
		$res = [
			'error_location' => [],
			'error_message' => [],
			'result' => [],
		];
		$time_list = [];

		// 財政部爬蟲
		$this->CI->load->library('scraper/business_registration_lib');
		$this->CI->load->library('mapping/time');
		$this->CI->load->model('user/user_meta_model');

		foreach ($data as $key => $value) {
			$timestamp = $this->CI->time->ROCDateToUnixTimestamp($value['report_time']);
			$time_list[] = date('Y-m',$timestamp);
			$res['result'][$key]['report_time'] = $value['report_time'];

			// 找變卡 user meta
			$meta_info = $this->CI->user_meta_model->get_by(['user_id'=>$user_id,'meta_key'=>'1007_governmentauthorities']);
			$meta_info = isset($meta_info->meta_value) && json_decode($meta_info->meta_value,true) ? json_decode($meta_info->meta_value,true) : [];

			if($meta_info){
				if($meta_info['name'] != $value['company_name']){
					$res['error_message'][] = '公司名稱與法人實名資料不一致(所得期間：'.$value['report_time'].')';
					$res['error_location'][$value['id']][] = 'name';
				}
				if($meta_info['tax_id'] != $value['company_tax_id']){
					$res['error_message'][] = '公司統一編號與法人實名資料不一致(所得期間：'.$value['report_time'].')';
					$res['error_location'][$value['id']][] = 'tax_id';
				}

				// 標準代號(財政部)
				$business_registration_info = $this->CI->business_registration_lib->getResultByBusinessId($meta_info['tax_id']);
				if(isset($business_registration_info->response->industryCode)){
					if($business_registration_info->response->industryCode != $value['input_89']){
						$res['error_message'][] = '標準代號與政部爬蟲結果不一致(所得期間：'.$value['report_time'].')';
						$res['error_location'][$value['id']][] = 'input_89';
					}
				}else{
					$res['error_message'][] = '統一編號查無財政部爬蟲結果(所得期間：'.$value['report_time'].')';
					$res['error_location'][$value['id']][] = 'input_89';
				}
				$res['result'][$key]['input_89'] = $value['input_89'];
			}else{
				$res['error_message'][] = '查無法人實名資料';
				$res['error_location'][$value['id']][] = 'tax_id';
			}
		}

		$this_year = date('Y');
		$this_month = date('m');
		if($this_month<=5){
			$this_year -=1;
			$check_date = $this_year.'-12';
		}else{
			$check_date = $this_year.'-12';
		}
		if(!in_array($check_date,$time_list)){
			$res['error_message'][] = '損益表沒有提供近一年資料';
			foreach($data as $key => $value){
				$res['error_location'][$value['id']][] = 'report_time';
			}
		}
		return $res;
	}

	// to do : 月底生效人數表格格式要修改
	/**
	 * [legalize_employeeinsurancelist 投保單位人數資料表正確性驗證]
	 * @param  string $user_id [法人ID]
	 * @param  array  $data    [驗證資料]
	 * (
	 *  [company_name] => 單位名稱(公司)
	 *  [range] => 計費年月(區間)
	 *  [report_time] => 印表日期
	 *  [table] =>
	 *          (
	 *           [0] => 月底生效人數
	 *           ...
	 *          )
	 * )
	 * @return array  $res     [驗證結果]
	 * (
	 *  [error_location] => 資料不正確欄位
	 *  [error_message] => 資料錯誤說明備註
	 *  [result] =>
	 *           (
	 *            [company_name] => 投保單位名稱
	 *            [range] => 計費年月
	 *            [report_time] => 印表日期
	 *            [average] => 近一年平均投保人數
	 *            [list] =>
	 *                   (
	 *                    [日期] => 投保人數
	 *                    ...
	 *                   )
	 *           )
	 * )
	 */
	public function legalize_employeeinsurancelist($user_id='',$data=[]){
		$res = [
			'error_location' => [],
			'error_message' => [],
			'result' => [],
		];
		$average = 0;
		$data = array_reduce($data, 'array_merge', array());

		foreach($data as $k=>$v){
			if(gettype($v) == 'string' && preg_match('/\:/u',$v)){
				$data[$k] = preg_replace('/.*\:/u','',$v);
			}
		}

		// 計費年月
		$year = date('Y');
		$month = date('m');
		$roc_year = $year - 1911;
		if(preg_match('/[0-9].*\~[0-9].*/',$data['range'])){
			$end_range = preg_replace('/.*\~/','',$data['range']);
			$last_month = $roc_year.$month;
			if($end_range != $last_month){
				$res['error_message'][] = '計費年月內沒有上個月投保人數資訊';
				$res['error_location'][] = 'range';
			}
		}else{
			$res['error_message'][] = '計費年月格式有誤';
			$res['error_location'][] = 'range';
		}

		$res['result']['list'] = [];

		$count = count((array)$data['table']);
		if($count >= 12){
			for($i=$count;$i>$count-12;$i--){
				$res['result']['list'][$data['table'][$i-1]['yearMonth']] = $data['table'][$i-1]['insuredCount'];
				if(is_numeric($data['table'][$i-1]['insuredCount'])){
					$average += $data['table'][$i-1]['insuredCount'];
				}
			}
			// for($i=1;$i<=12;$i++){
			// 	$number = $count-$i;
			// 	$list_date = date('Ym',strtotime("{$year}-{$month} -{$i} months"));
			// 	$res['result']['list'][$list_date] = property_exists($data['table'],"$number") ? $data['table']->{"$number"} : 0;
			// 	if(property_exists($data['table'],"$number") && is_numeric($data['table']->{"$number"})){
			// 		$average += $data['table']->{"$number"};
			// 	}
			// }
			$average /= 12;
		}else{
			for($i=$count;$i>$count;$i--){
				$res['result']['list'][$data['table'][$i-1]['yearMonth']] = $data['table'][$i-1]['insuredCount'];
				if(is_numeric($data['table'][$i-1]['insuredCount'])){
					$average += $data['table'][$i-1]['insuredCount'];
				}
			}
			// $i = 0;
			// foreach($data['table'] as $v){
			// 	$less_months = $count - $i;
			// 	$list_date = date('Ym',strtotime("{$year}/{$month} - {$less_months} months"));
			// 	$res['result']['list'][$list_date] = isset($v) ? $v : 0;
			// 	if(is_numeric($v)){
			// 		$average += $v;
			// 	}
			// }
			$average /= $count;
		}

		$res['result']['range'] = $data['range'];
		$res['result']['company_name'] = $data['company_name'];
		$res['result']['report_time'] = $data['report_time'];
		$res['result']['average'] = $average;

		// 找變卡 user meta
		$this->CI->load->model('user/user_meta_model');
		$meta_info = $this->CI->user_meta_model->get_by(['user_id'=>$user_id,'meta_key'=>'1007_governmentauthorities']);
		$meta_info = isset($meta_info->meta_value) && json_decode($meta_info->meta_value,true) ? json_decode($meta_info->meta_value,true) : [];
		if($meta_info){
			if($meta_info['name'] != $data['company_name']){
				$res['error_message'][] = '公司名稱與法人實名資料不一致';
				$res['error_location'][] = 'company_name';
			}
		}else{
			$res['error_message'][] = '查無法人實名資料';
			$res['error_location'][] = 'company_name';
		}
		return $res;
	}

    /**
     * [legalize_investigation description]
     * @param CertificationResult $verifiedResult [驗證結果]
     * @param string $user_id [使用者 ID]
     * @param array $result
     * @param int $created_at
     * @param int $hsa_a11
     * @return CertificationResult  $res     [驗證結果]
     * (
     *  [error_location] => 資料不正確欄位
     *  [error_message] => 資料錯誤說明備註
     *  [result] =>
     *           (
     *            [id] => 統一編號
     *           )
     * )
     */
	public function legalize_investigation(CertificationResult $verifiedResult, $user_id='', $result=[], $created_at=0, $hsa_a11=0){
		if($user_id) {
			$this->CI->load->model('user/user_model');
			$user_info = $this->CI->user_model->get_by(['id' => $user_id]);
			if (isset($user_info) && isset($result['personId'])) {

				if ($result['personId'] != $user_info->id_number) {
					$verifiedResult->addMessage('聯徵報告身分證字號與實名認證不符', 2, MessageDisplay::Client);
				}

				// TODO: 普匯微企e秒貸的聯徵a11 return 及 $res 要改
//				$res['result']['id'] = $data['id'];
//				if($hsa_a11){
//					if($data['id'] != $user_info->id_number){
//						$verifiedResult->addPendingMessage('A11名稱與該實名用戶統一編號不一致');
//					}
//					$res['result']['a11_id'] = $data['a11_id'];
//				}

			} else {
				$verifiedResult->addMessage('待人工驗證：查無使用者相關資訊', 3, MessageDisplay::Backend);
			}
		}

		if(isset($result['printDatetime']) && $result['printDatetime']) {
			$this->CI->load->library('mapping/time');
			$reportTime = preg_replace('/\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/', '', $result['printDatetime']);
			$reportTime = $this->CI->time->ROCDateToUnixTimestamp($reportTime);

			// 印表日期
			$validReportTime = strtotime(date('Y-m-d H:i:s', $created_at) . " - 1 month");
			if($reportTime < $validReportTime) {
				$verifiedResult->addMessage('聯徵報告非近一個月申請', 2, MessageDisplay::Client);
                $verifiedResult->setSubStatus(CERTIFICATION_SUBSTATUS_NOT_ONE_MONTH);
			}
		}

		return $verifiedResult;
	}

	/**
	 * [legalize_simplificationjob description]
	 * @param  string $user_id [使用者 ID]
	 * @param  array  $data    [description]
	 * @return array  $res     [驗證結果]
	 * (
	 *  [error_location] => 資料不正確欄位
	 *  [error_message] => 資料錯誤說明備註
	 *  [result] =>
	 *           (
	 *            [id] => 統一編號
	 *           )
	 * )
	 */
	public function legalize_simplificationjob($user_id='',$data=[]){
		$res = [
			'error_location' => [],
			'error_message' => [],
			'result' => [],
		];

		if($user_id && $data){
			$this->CI->load->model('user/user_model');
			$user_info = $this->CI->user_model->get_by(['id'=>$user_id]);
			if($user_info && $data['id_card']){
				if($data['id_card'] != $user_info->id_number){
					$res['error_message'][] = '身分證號與該實名用戶統一編號不一致';
					$res['error_location'][] = 'id_card';
				}
			}else{
				$res['error_message'][] = '查無使用者身分證資訊';
			}
			$res['result']['id_card'] = $data['id_card'];

			if($user_info && $data['name']){
				if($data['name'] != $user_info->name){
					$res['error_message'][] = '姓名與該實名用戶姓名不一致';
					$res['error_location'][] = 'name';
				}
			}else{
				$res['error_message'][] = '查無使用者姓名資訊';
			}
			$res['result']['name'] = $data['name'];

			if($user_info && isset($data['birthday'])){
				if($data['birthday'] != $user_info->birthday){
					$res['error_message'][] = '出生日期與該實名用戶生日不一致';
					$res['error_location'][] = 'birthday';
				}
			}else{
				$res['error_message'][] = '查無使用者出生日期資訊';
			}
			$res['result']['birthday'] = $data['birthday'];
			$res['result']['salary'] = $data['salary'];
			$res['result']['search_day'] = $data['search_day'];
		}

		return $res;
	}

	/**
	 * [legalize_job 工作認證正確性驗證]
	 * @param CertificationResult $res [驗證結果]
	 * @param string $user_id [使用者 ID]
	 * @param array $data [驗證資料]
	 * @param array $content
	 * @param int $created_at
	 * @return CertificationResult  $res     [返回結果]
	 */
	public function legalize_job(CertificationResult $res, $user_id='', $data=[], $content=[], $created_at=0){

		if($user_id && !empty($data) && !empty($content)) {
			$this->CI->load->model('user/user_model');
			$user_info = $this->CI->user_model->get_by(['id'=>$user_id]);
			if($user_info && $data['person_id']){
				if($data['person_id'] != $user_info->id_number){
					$res->addMessage('勞保資料身分證字號與實名認證不符', 2, MessageDisplay::Client);
				}
				if($data['name'] != $user_info->name){
					$res->addMessage('勞保資料姓名與實名認證不符', 2, MessageDisplay::Client);
				}
				if(!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $data['last_insurance_info']['companyName'])) {
					$res->addMessage('勞保PDF資料解析完的公司名稱有非中文字元', 3, MessageDisplay::Backend);
				}else if($data['last_insurance_info']['companyName'] != $content['company']){
					$res->addMessage('任職公司名稱與勞保資料不符', 2, MessageDisplay::Client);
				}
			}else{
				$res->addMessage('解析失敗：查無使用者身分證字號資訊', 3, MessageDisplay::Backend);
			}


			/* TODO: 更改為使用公司名稱進行勾稽 (商行號無法使用API查詢)
			if(isset($content['gcis_info']) && !empty($content['gcis_info'])){
				if(mb_substr($content['gcis_info']['Company_Name'], 0, 4, "utf-8") != mb_substr($data['last_insurance_info']['companyName'], 0, 4, "utf-8")){
					$res->addMessage('任職公司名稱與勞保資料不符', 2, MessageDisplay::Client);
				}
				if($content['gcis_info']['Business_Accounting_NO'] != $content['tax_id']){
					$res->addMessage('任職公司統編錯誤', 2, MessageDisplay::Client);
				}
			}
			else{
				$res->addMessage('查無商業司資料', 3, MessageDisplay::Backend);
			}
			*/
		}

		preg_match('/^(?<year>(1[0-9]{2}|[0-9]{2}))(?<month>(0?[1-9]|1[012]))(?<day>(0?[1-9]|[12][0-9]|3[01]))$/', $data['report_date'] ?? '', $regexResult);
		if(!empty($regexResult)) {
			$date = sprintf("%d-%'.02d-%'.02d", intval($regexResult['year'])+1911,
				intval($regexResult['month']), intval($regexResult['day']));
			$reportDate = DateTime::createFromFormat('Y-m-d', $date);
			$certificationSubmitDate = new DateTime();
			$diffDate = $certificationSubmitDate->diff($reportDate);
			if($diffDate->m >= 1) {
				$res->addMessage('勞保非近一個月申請', 2, MessageDisplay::Client);
                $res->setSubStatus(CERTIFICATION_SUBSTATUS_NOT_ONE_MONTH);
			}
		}else{
			$res->addMessage('解析失敗：勞保印表日期解析失敗', 3, MessageDisplay::Backend);
		}

		return $res;
	}

}
