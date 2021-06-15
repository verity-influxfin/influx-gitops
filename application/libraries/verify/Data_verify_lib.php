<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data_verify_lib{

		public $pass_case_config = [
			'J2' => [
				'1002_incomestatement' => 'check_incomestatement',
				'1003_credit_investigation' => 'check_credit_investigation',
				'1017_employeeinsurancelist' => 'check_employeeinsurancelist',
				'1007_governmentauthorities' => 'check_amendment_of_register',
				'9_investigation' => 'check_investigation',
			],
		];

		public function __construct(){
			$this->CI = &get_instance();
			$this->CI->load->model('user/user_meta_model');
			$this->CI->load->library('Notification_lib');
			$this->CI->load->library('Certification_lib');
			$this->certification = $this->CI->config->item('certifications');
			$this->product = $this->CI->config->item('product_list');
		}

		/**
		 * [check_amendment_of_register 變卡信保標準檢核]
		 * @param  array $data [變卡 meta data]
		 * @return array $res  [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_amendment_of_register($data=[]){
			$res = [
				'status_code' => 1,
				'error_message' => [],
			];
			foreach($data as  $k=>$v){
				// 實收資本額
				if($k =='capital'){
					if($v<30000000){
						$res['status_code'] = 3;
						$res['error_message'][] = '資本額不符合信保標準';
					}
				}
				// 公司名稱
				if($k =='name'){
					if(preg_match('/基金會$|機構$|^財團法人|醫院$|學校|分公司$/',$v)){
						$res['status_code'] = 3;
						$res['error_message'][] = '公司名稱不符合信保標準';
					}
				}
      }

			return $res;
		}

		/**
		 * [check_incomestatement 損益表信保過件標準檢核]
		 * @param  array $data [損益表 meta data]
		 * @return array $res  [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_incomestatement($data=[]){
			$res = [
				'status' => 1,
				'error_message' => [],
			];
			foreach($data as  $k=>$v){
				// 標準代號(欄位89)
				if($k == 'input_89'){
					if(preg_match('/^64|^65^65|^94|^9323/',$v)){
						$res['status'] = 2;
						$res['error_message'][] ='產業別不符合信保標準';
					}
				}
			}
			return $res;
		}

		/**
		 * [check_credit_investigation 法人聯徵信保標準檢核]
		 * @param  array $data [法人聯徵 user meta]
		 * @return array $res  [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_credit_investigation($data=[]){
			$res = [
				'status_code' => 1,
				'error_message' => [],
			];
			foreach($data as  $k=>$v){
				// 借款逾期、催收或呆帳記錄
				if($k == 'liabilities_badDebtInfo'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵資料不符合信保標準';
					}
				}
				// 大額存款不足退票資訊
				if($k == 'checkingAccount_largeAmount'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵資料不符合信保標準';
					}
				}
				// 票據拒絕往來資訊
				if($k == 'checkingAccount_rejectInfo'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵資料不符合信保標準';
					}
				}
			}
			return $res;
		}

		/**
		 * [check_credit_investigation 自然人聯徵信保標準檢核]
		 * @param CertificationResult $verifiedResult [檢核結果]
		 * @param  array $data [自然人聯徵 user meta]
		 * @return CertificationResult [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_investigation(CertificationResult $verifiedResult, $data=[], $certification_content){
			if($data['scoreComment'] < 450) {
				$verifiedResult->addMessage('信用評分低於 450 分', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['totalMonthlyPayment'] >= $certification_content['monthly_repayment']) {
				$verifiedResult->addMessage('還款力計算 >= 投保薪資', 3, MassageDisplay::Backend);
			}

			if($data['debt_to_equity_ratio'] > 100) {
				$verifiedResult->addMessage('負債比計算 > 100%', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}else if($data['debt_to_equity_ratio'] >= 70) {
				$verifiedResult->addMessage('負債比計算 >= 70%', 3, MassageDisplay::Backend);
			}

			if($data['totalAmountQuota'] >= $certification_content['total_repayment']) {
				$verifiedResult->addMessage('借款總餘額 >= 投保薪資22倍', 3, MassageDisplay::Backend);
			}

			if($data['creditUtilizationRate'] > 100) {
				$verifiedResult->addMessage('信貸額度動用率 > 100%', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}else if($data['creditUtilizationRate'] >= 80) {
				$verifiedResult->addMessage('負債比計算 >= 80%', 3, MassageDisplay::Backend);
			}

			if($data['liabilities_badDebtInfo'] != '無') {
				$verifiedResult->addMessage('有借款餘額、催收或呆帳紀錄', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['creditLogCount'] < 1) {
				$verifiedResult->addMessage('無信用記錄', 3, MassageDisplay::Backend);
			}

			if($data['creditCardUseRate'] > 90) {
				$verifiedResult->addMessage('近一個月信用卡使用率 > 90%', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}else if($data['creditUtilizationRate'] >= 70) {
				$verifiedResult->addMessage('近一個月信用卡使用率 >= 70%', 3, MassageDisplay::Backend);
			}

			if($data['delayLessMonth'] > 1) {
				$verifiedResult->addMessage('延遲未滿一個月次數 > 1', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['delayMoreMonth'] > 0) {
				$verifiedResult->addMessage('延遲超過一個月次數 > 0', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['creditCardHasBadDebt'] != '無') {
				$verifiedResult->addMessage('有信用卡催收、呆帳紀錄', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['checkingAccount_largeAmount'] != '無') {
				$verifiedResult->addMessage('有大額存款不足退票資訊紀錄', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['checkingAccount_rejectInfo'] != '無') {
				$verifiedResult->addMessage('有票據拒絕往來資訊紀錄', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['S1Count'] >= 3) {
				$verifiedResult->addMessage('被電子支付或電子票證發行機構查詢紀錄 >= 3', 3, MassageDisplay::Backend);
			}

			return $verifiedResult;
		}

		/**
		 * [check_job 工作認證過件檢核]
		 * @param CertificationResult $verifiedResult [檢核結果]
		 * @param string $user_id [使用者 ID]
		 * @param  array  $data [工作認證 user meta]
		 * @param  array  $content [工作認證解析完資料]
		 * @return CertificationResult [檢核結果]
		 */
		public function check_job($verifiedResult, $user_id='', $data=[], $content=[]){

//			$this->CI->config->load('top_enterprise');
//			$top_enterprise = $this->CI->config->item("top_enterprise");

			if($data['total_count'] < 3 || $data['this_company_count'] < 3 ) {
				$verifiedResult->addMessage('總工作年資或現任公司年資沒有超過 3 個月', 3, MassageDisplay::Backend);
			}

			if($data['last_insurance_info']['insuranceSalary'] < 23800) {
				$verifiedResult->addMessage('投保薪資月薪小於 23800', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if($data['last_insurance_info']['endDate'] != "" ||
				preg_match('/部分工時/', $data['last_insurance_info']['comment']) ||
				preg_match('/不適用就業保險/', $data['last_insurance_info']['comment']) ||
				preg_match('/F/', $data['last_insurance_info']['comment']) ||
				preg_match('/D/', $data['last_insurance_info']['comment'])
				) {
				$verifiedResult->addMessage('註記有部分工時、不適用就業保險、F、D', 2, MassageDisplay::Backend);
				$verifiedResult->setBanResubmit();
			}

			if(!empty($content) && isset($content['gcis_info']['Company_Status_Desc'])) {
				if(preg_match('/解散/', $content['gcis_info']['Company_Status_Desc'])) {
					$verifiedResult->addMessage('任職公司非為營業中', 2, MassageDisplay::Client);
				}else if(!preg_match('/核准設立/', $content['gcis_info']['Company_Status_Desc'])) {
					$verifiedResult->addMessage('任職公司非為營業中', 3, MassageDisplay::Client);
				}
			}else{
				$verifiedResult->addMessage('沒有查詢到公司狀態', 3, MassageDisplay::Backend);
			}

			return $verifiedResult;
		}

		/**
		 * [check_employeeinsurancelist 月末投保信保標準檢核]
		 * @param  array  $data [月末投保 user meta]
		 * @return array $res  [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_employeeinsurancelist($data=[]){
			$res = [
				'status_code' => 1,
				'error_message' => [],
			];
			foreach($data as  $k=>$v){
				if($k=='average'){
					if($v>200){
						$res['status_code'] = 3;
						$res['error_message'][] = '平均員工人數不符合信保標準';
					}
				}
			}
			return $res;
		}

		/**
		 * [check_profilejudicial 公司資料表信保標準檢核]
		 * @param  array  $data [公司資料表 user meta]
		 * @return array $res  [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_profilejudicial($data=[]){
			$res = [
				'status_code' => 1,
				'error_message' => [],
			];
			foreach($data as  $k=>$v){
				if($k=='BizTaxFileWay'){
					if($v !='使用統一發票'){
						$res['status_code'] = 3;
						$res['error_message'][] = '營業稅申報方式不符合信保標準';
					}
				}
				if($k=='CompDuType'){
					if(preg_match('/7[4|5|6|7|8]/',$v)){
						$res['status_code'] = 3;
						$res['error_message'][] = '產業別不符合信保標準';
					}
				}
			}
			return $res;
		}

		// 信用卡動用率
		// credit_card_rate = 信用卡動用率
		public function limit_credit_card_rate($credit_card_rate){
			if(!$credit_card_rate && !is_numeric($credit_card_rate)){
				return '信用卡動用率不得為空';
			}
			if($credit_card_rate < 70){
				return true;
			}else{
				return false;
			}
		}

		// 聯徵資料格式轉換用

		// 日期民國轉西元
		public function transform_date($date='',$symbol=''){
			$transform_date = '';
			if(!$date || !$symbol){
				return false;
			}
			$date_array = explode($symbol,$date);
			if(count($date_array) == 3){
			 	(int)$date_array[0] += 1911;
				$transform_date = date('Y-m-d',strtotime("{$date_array[0]}/{$date_array[1]}/{$date_array[2]}"));
			}
			return $transform_date;
		}

		// 本息均攤公式
		// rate = 年利率, nper = 總期數, pv = 金額
		public function PMT_calculate(int $rate = 0,int $nper = 0,int $pv = 0){
			if($nper == 0 || $pv == 0){
				return '期數與貸款金額不得為0';
			}
			$month_rate = $rate / 100 / 12;
			$months = $nper * 12;
			$response = pow((1 + $month_rate),$months) * $month_rate / (pow((1 + $month_rate),$months) -1) * $pv;
			// $response = $response/
			return $response;
		}

		// 繳息不還本公式
		// rate = 年利率, nper = 總期數, pv = 金額
		public function RepaymentDue_calculate(int $rate = 0,int $nper = 0,int $pv = 0){
			if($nper == 0 || $pv == 0){
				return '期數與貸款金額不得為0';
			}
			$month_rate = $rate / 100 / 12;
			$response = $month_rate * $pv;
			return $response;
		}
		// 月末投保平均200人以下


    // 信用貸款申請
    public function productVerify($product = [], $target = [])
    {
		$response = [
			'status_code' => '3',
			'error_message' => [],
		];
        $method = $product['visul_id'];
        if (method_exists($this, $method)) {
            $response = $this->$method($product, $target);
        }
        return $response;
    }

    private function J2($product, $target){
		$response = [
			'status_code' => '3',
			'error_message' => [],
		];
		foreach($this->pass_case_config['J2'] as $key=>$value){
			$meta_info = $this->CI->user_meta_model->get_meta_value_by_user_id_and_meta_key($target->user_id,[$key]);
			$function_name = $value;
			$meta_data = isset($meta_info->meta_key) ? json_decode($meta_info->meta_key,true): [];
			if($meta_data && function_exists($function_name)){
				$meragre_response = $this->$function_name($meta_data);
				array_merge($response['error_message'],$meragre_response['error_message']);
				if($response['status_code'] = ''){
					$response['status_code'] = $meragre_response['status_code'];
				}elseif($meragre_response['status_code'] == 2) {
					$response['status_code'] = $meragre_response['status_code'];
				}
			}
		}
        return $response;
    }

}
