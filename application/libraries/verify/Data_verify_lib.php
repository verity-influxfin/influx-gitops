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
		 * @param  array $data [自然人聯徵 user meta]
		 * @return array $res  [檢核結果]
		 * (
		 *  [status_code] => 1(通過)/2(退件)/3(轉人工)
		 *  []error_message] => 不過件原因
		 * )
		 */
		public function check_investigation($data=[]){
			// todo list 暫時轉人工
			$res = [
				'status_code' => 3,
				'error_message' => [],
			];
			foreach($data as  $k=>$v){
				// 借款逾期、催收或呆帳記錄
				if($k == 'liabilities_badDebtInfo'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵借款逾期、催收或呆帳記錄不符合申貸標準';
					}
				}
				// 大額存款不足退票資訊
				if($k == 'checkingAccount_largeAmount'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵大額存款不足退票資訊不符合申貸標準';
					}
				}
				// 票據拒絕往來資訊
				if($k == 'checkingAccount_rejectInfo'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵票據拒絕往來資訊不符合申貸標準';
					}
				}
				// 信用卡帳款總餘額資訊(有逾期紀錄)
				if($k == 'creditCardHasDelay'){
					if($v!='無'){
						$res['status_code'] = 3;
						$res['error_message'][] = '聯徵信用卡帳款總餘額資訊不符合申貸標準';
					}
				}
				// 信用卡使用率
				if($k == 'creditCardUseRate'){
					if($v >= 100){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵信用卡使用率不符合申貸標準';
					}
				}
				// 信用卡有無催收、逾期、呆帳紀錄
				if($k == 'creditCardHasBadDebt'){
					if($v!='無'){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵信用卡有無催收、逾期、呆帳紀錄不符合申貸標準';
					}
				}
				// 信用卡延遲未滿一個月次數
				if($k == 'delayLessMonth'){
					if($v > 2){
						$res['status_code'] = 2;
						$res['error_message'][] = '聯徵信用卡延遲未滿一個月次數不符合申貸標準';
					}
				}
				// 銀行借款家數
				if($k == 'bankCount'){
					if($v > 3){
						$res['status_code'] = 3;
						$res['error_message'][] = '聯徵銀行借款家數不符合申貸標準';
					}
				}
			}
			return $res;
		}

		/**
		 * [check_job 工作認證過件檢核]
		 * @param  array  $data [工作認證 user meta]
		 * @return array  $res  [檢核結果]
		 */
		public function check_job($data=[]){
			$res = [
				'status_code' => 1,
				'error_message' => [],
			];

			$this->CI->config->load('top_enterprise');
			$top_enterprise = $this->CI->config->item("top_enterprise");

			// foreach($data as $key => $value){
			// 	if($key == 'company_name'){
			// 		if(in_array($value,$top_enterprise)){
			//
			// 		}
			// 	}
			// }

			return $res;
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
