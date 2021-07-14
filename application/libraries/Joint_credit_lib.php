<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Joint_credit_lib{
	const BREAKER = "--------------------------------------------------------------------------------";
	const BROWSED_HITS = "被查詢次數：";
	const BROWSED_HITS_BY_ELECTRICAL_PAY = "被電子支付或電子票證發行機構查詢紀錄：";
	const BROWSED_HITS_BY_ITSELF = "當事人查詢紀錄：";

	const EXTRA_DEBITS_DATA = "共同債務/從債務/其他債務資訊 : ";

	const NO_DATA = "查無資料";
	const DOES_OBTAIN_CASH_ADVANCE = "是否有預借現金 : ";
	const DELAY_PAYMENT_MORE_THAN_ONE_MONTH = "超過一個月延遲繳款：";
	const DELAY_PAYMENT_IN_A_MONTH_EXCEEDED = "延遲未滿一個月次數：";

	public function __construct(){
		$this->CI = &get_instance();
		$this->CI->load->library('utility/joint_credit_regex', [], 'regex');
		$this->CI->load->model('user/user_model');
	}

	// 找身分證號
	private function getIdCardNumber($text){
		preg_match('/身分證號：.*[a-z,A-Z]{1}[0-9]{9}/',$text,$id_card);
		$id_card = isset($id_card[0]) ? $id_card[0] : [];
		preg_match('/[a-z,A-Z]{1}[0-9]{9}/',$id_card,$id_card);
		$id_card = isset($id_card[0]) ? $id_card[0] : [];
		return $id_card;
	}

	// 找印表日期
	private function getReportTime($text){
		preg_match('/[0-9]{3}\/[0-9]{2}\/[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}/',$text,$report_time);
		$report_time = isset($report_time[0]) ? $report_time[0] : '';
		return $report_time;
	}

	// 所有信用資訊項目切割抓取是否有信用紀錄資訊
	// 格式： content = 檢驗內容, start_key_word = 切割開頭關鍵字, end_key_word = 切割結尾關鍵字, mapping_array = 對照資料陣列
	private function split_info($content,$start_key_word,$end_key_word,$mapping_array){
		$has_info_array = [];
		$res = [];
		$data = $this->CI->regex->findPatternInBetween($content,$start_key_word,$end_key_word);
		$data = isset($data[0]) ? $data[0] : '';
		if($data){
			$data = array_filter(preg_split('/\s/',$data));
			$num = 0;
			foreach($data as $k=>$v){
				if(preg_match('/[1-9].*\..*/',$v)){
					$v = preg_replace('/[1-9].*\./','',$v);
					if(isset($mapping_array[$v])){
						$res[$mapping_array[$v]] = '';
					}
				}else{
					$has_info_array[] = $v;
				}
			}
			foreach($res as $k=>$v){
				$res[$k] = $has_info_array[$num];
				$num += 1;
			}
		}
		return $res;
	}

	/**
	* [checkHasInfo 信用資訊項目總表解析]
	* @param  string $text [pdf解析字串]
	* @return array  $res  [信用資訊項目有/無信用資訊]
	* (
	*  [liabilities] => array
	*   (
	*    [totalAmount] => 借款總餘額資訊
	*    [metaInfo] => 共同債務/從債務/其他債務資訊
	*    [badDebtInfo] => 借款逾期、催收或呆帳記錄
	*   )
	*  [creditCard] => array
	*   (
	*    [cardInfo] => 信用卡持卡紀錄
	*    [totalAmount] => 信用卡帳款總餘額資訊
	*   )
	*  [checkingAccount] => array
	*   (
	*    [largeAmount] => 大額存款不足退票資訊
	*    [rejectInfo] => 票據拒絕往來資訊
	*   )
	*  [queryLog] => array
	*   (
	*    [queriedLog] => 被查詢記錄
	*    [applierSelfQueriedLog] => 當事人查詢信用報告記錄
	*   )
	*  [other] => array
	*   (
	*    [extraInfo] => 附加訊息資訊
	*    [mainInfo] => 主債務債權轉讓及清償資訊
	*    [metaInfo] => 共同債務/從債務/其他債務轉讓資訊
	*    [creditCardTransferInfo] => 信用卡債權轉讓及清償資訊
	*   )
	* )
	*/
	private function checkHasInfo($text=''){
		$res = [];
		$has_info = [];

		preg_match('/第 1 頁\s.*\( 共 [0-9].* 頁 \).*/',$text,$page_info);
		$end_key_word = isset($page_info[0]) ? $page_info[0] : '';
		if($end_key_word){
			$end_key_word = preg_replace('/\(/','\(',$end_key_word);
			$end_key_word = preg_replace('/\)/','\)',$end_key_word);
			$content = $this->CI->regex->findPatternInBetween($text, '參閱信用明細', $end_key_word);
			$content = isset($content[0]) ? $content[0] : '';
			if($content){
				$content = preg_replace('/--|表[a-zA-Z].*[0-9].*/','',$content);
				// 一、借款資訊
				$mapping_array = ['借款總餘額資訊'=>'totalAmount','共同債務/從債務/其他債務資訊'=>'metaInfo','借款逾期、催收或呆帳紀錄'=>'badDebtInfo'];
				$has_info['liabilities'] = $this->split_info($content, '一、借款資訊', '二、信用卡資訊',$mapping_array);
				$res = array_merge($has_info,$res);
				// 二、信用卡資訊
				$mapping_array = ['信用卡持卡紀錄'=>'cardInfo','信用卡帳款總餘額資訊'=>'totalAmount'];
				$has_info['creditCard'] = $this->split_info($content, '二、信用卡資訊', '三、票信資訊',$mapping_array);
				$res = array_merge($has_info,$res);
				// 三、票信資訊
				$mapping_array = ['大額存款不足退票資訊'=>'largeAmount','票據拒絕往來資訊'=>'rejectInfo'];
				$has_info['checkingAccount'] = $this->split_info($content, '三、票信資訊', '四、查詢紀錄',$mapping_array);
				$res = array_merge($has_info,$res);
				// 四、查詢紀錄
				$mapping_array = ['被查詢紀錄'=>'queriedLog','當事人查詢信用報告紀錄'=>'applierSelfQueriedLog'];
				$has_info['queryLog'] = $this->split_info($content, '四、查詢紀錄', '五、其他',$mapping_array);
				$res = array_merge($has_info,$res);
				// 五、其他
				$mapping_array = ['附加訊息資訊'=>'extraInfo','主債務債權轉讓及清償資訊'=>'mainInfo','共同債務/從債務/其他債務轉讓資訊'=>'metaInfo','信用卡債權轉讓及清償資訊'=>'creditCardTransferInfo'];
				$has_info['other'] = $this->split_info($content, '五、其他', '',$mapping_array);
				$res = array_merge($has_info,$res);
			}
		}
		return $res;
	}

	/**
	 * [deletePageInfo 消除跨頁及第一頁資訊]
	 * @param  string $text    [pdf解析字串]
	 * @return string $content [消除後字串]
	 */
	private function deletePageInfo($text=''){
		$content = '';
		preg_match('/第 1 頁\s.*\( 共 [0-9].* 頁 \).*/',$text,$page_info);
		$end_key_word = isset($page_info[0]) ? $page_info[0] : '';
		if($end_key_word){
			$end_key_word = preg_replace('/\(/','\(',$end_key_word);
			$end_key_word = preg_replace('/\)/','\)',$end_key_word);
			$content = $this->CI->regex->findPatternInBetween($text, $end_key_word, '');
			$content = isset($content[0]) ? $content[0] : '';
			if($content){
				$content = preg_replace('/E\d{13}|第 [0-9].* 頁\s.*\( 共 [0-9].* 頁 \).*/','',$content);
			}
		}
		return $content;
	}

	/**
	 * [getTotalLoanInfo 找借款總餘額資訊]
	 * @param  string $text       [pdf字串]
	 * @return array  $data_array [借款資訊項目解析資料]
	 */
	private function getTotalLoanInfo($text=''){
		// print_r($text);exit;
		$data_array = [];
		$b2_mapping = [
			'共同債務資訊' => 'part1',
			'從債務資訊' => 'part2',
			'其他債務資訊' => 'part3',
		];
		if($text){
			// 資訊項目：借款總餘額資訊
			$content = $this->CI->regex->findPatternInBetween($text, '', '借款餘額.\w.*千元');
			$content = isset($content[0]) ? $content[0] : [];
			// print_r($content);exit;
			if($content){
				$content = $this->CI->regex->findPatternInBetween($content, '[0-9]{3}\/[0-9]{2}\~[0-9]{3}\/[0-9]{2}', '');
				$content = isset($content[0]) ? $content[0] : [];
				// print_r($content);exit;
				if($content){
					// preg_match_all('/(\W){1,}.*[0-9]{3}\/[0-9]{2}.*/',$content,$content);
					preg_match_all('/((\W){1,}.*[0-9]{3}\/[0-9]{2}.*(無|有\s.*\s遲延狀態：(.*\s.*月|.*)))/',$content,$content);
					$content = isset($content[0]) ? $content[0] : [];
				}
			}
			// print_r($content);exit;
			if($content){
				foreach($content as $k=>$v){
					$content[$k] = array_values(array_filter(preg_split('/\t/',$v)));
				}
				// print_r($content);exit;
				foreach($content as $k=>$v){
					$data_array['B1']['dataList'][$k]['bankName'] = isset($content[$k][0]) ? preg_replace('/\s/','',$content[$k][0]) : '';
					$data_array['B1']['dataList'][$k]['yearMonth'] = isset($content[$k][1]) ? preg_replace('/\s/','',$content[$k][1]) : '';
					$data_array['B1']['dataList'][$k]['totalAmount'] = isset($content[$k][2]) ? preg_replace('/\s/','',$content[$k][2]) : '';
					$data_array['B1']['dataList'][$k]['noDelayAmount'] = isset($content[$k][3]) ? preg_replace('/\s/','',$content[$k][3]) : '';
					$data_array['B1']['dataList'][$k]['delayAmount'] = isset($content[$k][4]) ? preg_replace('/\s/','',$content[$k][4]) : '';
					$data_array['B1']['dataList'][$k]['accountDescription'] = isset($content[$k][5]) ? preg_replace('/\s/','',$content[$k][5]) : '';
					$data_array['B1']['dataList'][$k]['purpose'] = isset($content[$k][6]) ? preg_replace('/\s/','',$content[$k][6]) : '';
					$data_array['B1']['dataList'][$k]['pastOneYearDelayRepayment'] = isset($content[$k][7]) ? preg_replace('/\s/','',$content[$k][7]) : '';
				}
			}
			// 借款餘額小計
			preg_match('/借款餘額.\w.*千元/',$text,$page_info);
			$page_info = isset($page_info[0]) ? $page_info[0] : '';
			if($page_info){
				preg_match('/小計.*.千元$/',$page_info,$page_info);
				$page_info = isset($page_info[0]) ? $page_info[0] : '';
				$page_info = preg_replace('/(小計)|(千元)/','',$page_info);
				$page_info = isset($page_info) ? $page_info : '';
			}
			// $data_array['total-loan-subtotal'] = isset($page_info) ? $page_info : '';

			// 資訊項目：借款總餘額資訊(未滿一個月)
			$content = $this->CI->regex->findPatternInBetween($text, '遲延還款紀錄\s[0-9]{3}\/[0-9]{2}\/[0-9]{2}\~[0-9]{3}\/[0-9]{2}\/[0-9]{2}', '小計');
			$content = isset($content[0]) ? $content[0] : [];
			// print_r($text);exit;
			if($content){
				$content = preg_replace('/當事人綜合信用報告|金融機構.*\s.*[0-9]{3}\/[0-9]{2}\/[0-9]{2}\~[0-9]{3}\/[0-9]{2}\/[0-9]{2}|[0-9]{3}\/[0-9]{2}\/[0-9]{2}\~[0-9]{3}\/[0-9]{2}\/[0-9]{2}/','',$content);
				preg_match_all('/((\W){1,}.*[0-9]{3}\/[0-9]{2}.*(無|有\s.*\s遲延狀態：.*\s.*月))/',$content,$content);
				// $content = preg_split('/\t/',$content);
				$content = isset($content[0]) ? $content[0] : [];
				if($content){
					// 外層去空格
					foreach($content as $k=>$v){
						$content[$k] = array_values(array_filter(preg_split('/\t/',$v)));
					}
					foreach($content as $k=>$v){
						// 裏層去空格
						foreach($v as $k1=>$v1){
							$content[$k][$k1] = preg_replace('/\s/','',$v1);
						}
						$content[$k] = array_values(array_filter($content[$k]));

						$data_array['B1-extra']['dataList'][$k]['bankName'] = isset($content[$k][0]) ? preg_replace('/\s/','',$content[$k][0]) : '';
						$data_array['B1-extra']['dataList'][$k]['yearMonth'] = isset($content[$k][1]) ? preg_replace('/\s/','',$content[$k][1]) : '';
						$data_array['B1-extra']['dataList'][$k]['撥款'] = isset($content[$k][2]) ? preg_replace('/\s/','',$content[$k][2]) : '';
						$data_array['B1-extra']['dataList'][$k]['還款'] = isset($content[$k][3]) ? preg_replace('/\s/','',$content[$k][3]) : '';
						$data_array['B1-extra']['dataList'][$k]['accountDescription'] = isset($content[$k][4]) ? preg_replace('/\s/','',$content[$k][4]) : '';
						$data_array['B1-extra']['dataList'][$k]['purpose'] = isset($content[$k][5]) ? preg_replace('/\s/','',$content[$k][5]) : '';
						$data_array['B1-extra']['dataList'][$k]['pastOneYearDelayRepayment'] = isset($content[$k][6]) ? preg_replace('/\s/','',$content[$k][6]) : '';
					}
				}
			}

			// 資訊項目：共同債務
			if(preg_match('/共同債務\/從債務\/其他債務資訊/u',$text)){
				$content = '';
				preg_match('/.*共同債務\/從債務\/其他債務資訊.*(\s.*)*/u',$text, $match);
				$match = isset($match[0]) ? $match[0] : '';
				if($match){
					$content = preg_replace('/(.*共同債務\/從債務\/其他債務資訊.*)|([0-9]{3}\/[0-9]{2}.*)|(當事人綜合信用報告)/','',$match);
					$content = preg_split('/(從債務資訊\s.*\s|共同債務資訊\s.*\s|其他債務資訊\s.*\s)/u',$content,-1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
					foreach($content as $k=>$v){
						$v = preg_replace('/\s/','',$v);
						if(!$v){
							unset($content[$k]);
						}
					}
					// $content = array_filter($content);
					$content = array_chunk($content,2);
					foreach($content as $v){
						$title = '';
						foreach($v as $v1){
							// 從債務項目種類解析
							if(preg_match('/(從債務資訊\s.*|共同債務資訊\s.*|其他債務資訊\s.*)/u',$v1)){
								$title = preg_replace('/\s/','',preg_replace('/\s.*/u','',$v1));
								if(isset($b2_mapping[$title])){
									$data_array['B2'][$b2_mapping[$title]]['description'] = $title;
								}
							}

							if($title && preg_match('/(\W*\s)*.*(.*放款|催收|呆帳|助學貸款|逾期)/',$v1)){
								$v1 = preg_replace('/主借款戶.*科目/','',$v1);
								preg_match_all('/(\W*\s)*.*(.*放款|催收|呆帳|助學貸款)/',$v1,$v1);
								$value = isset($v1[0]) ? $v1[0] : [];
								foreach($value as $k2=>$v2){
									$list[$k2] = array_values(array_filter(preg_split('/\s/',preg_replace('/(\t\n)/','',$v2))));
								}

								foreach($list as $k2=>$v2){
									if(count($v2) ==5){
										$data_array['B2'][$b2_mapping[$title]]['dataList'][] = [
											'主借款戶' => isset($v2[0]) ? $v2[0] : '',
											'承貸金融機構' => isset($v2[1]) ? $v2[1] : '',
											'未逾期金額' => isset($v2[2]) ? $v2[2] : '',
											'逾期金額' => isset($v2[3]) ? $v2[3] : '',
											'科目' => isset($v2[4]) ? $v2[4] : '',

										];
									}
									if(count($v2) ==4){
										preg_match('/.*公司/',$v2[0],$main_borrower);
										preg_match('/(?<=(公司)).*(?=.*)/',$v2[0],$agency_name);
										$data_array['B2'][$b2_mapping[$title]]['dataList'][] = [
											'主借款戶' => isset($main_borrower[0]) ? $main_borrower[0] : '',
											'承貸金融機構' => isset($agency_name[0]) ? $agency_name[0] : '',
											'未逾期金額' => isset($v2[1]) ? $v2[1] : '',
											'逾期金額' => isset($v2[2]) ? $v2[2] : '',
											'科目' => isset($v2[3]) ? $v2[3] : '',

										];
									}
								}

							}
						}
					}
				}
			}

			// 資訊項目：借款逾期、催收或呆帳紀錄
			if(preg_match('/若結案日期欄位有結案日期係指您與金融機構間已無債權債務關係/',$text)){
				// print_r($text);exit;
				preg_match('/(?<=(借款逾期、催收或呆帳紀錄))(.*\s)*/',$text,$content);
				$content = isset($content[0]) ? $content[0] : '';
				// print_r($content);exit;
				if($content){
					$content = preg_replace('/(\(若結案日期欄位有結案日期係指您與金融機構間已無債權債務關係\))|金融機構名稱|資料日期|金額|科目|結案日期|當事人綜合信用報告|說明\：.*/','',$content);
					// print_r($content);exit;
					// preg_match_all('/.*[0-9]{3}\/[0-9]{2}.*[0-9]{3}\/[0-9]{2}/',$content,$content);
					preg_match_all('/.*[0-9]{3}\/[0-9]{2}.*/',$content,$content);
					$content = isset($content[0]) ? $content[0] : '';
					// print_r($content);exit;
					if($content){
						foreach($content as $k=>$v){
							$v = array_values(array_filter(preg_split('/\s/',$v)));
							if(count($v)==5){
								$data_array['B3']['dataList'][] = [
									'金融機構名稱' => isset($v[0]) ? $v[0] : '',
									'資料日期' => isset($v[1]) ? $v[1] : '',
									'金額' => isset($v[2]) ? $v[2] : '',
									'科目' => isset($v[3]) ? $v[3] : '',
									'結案日期' => isset($v[4]) ? $v[4] : '',
								];
							}

							if(count($v)==4){
								$data_array['B3']['dataList'][] = [
									'金融機構名稱' => isset($v[0]) ? $v[0] : '',
									'資料日期' => isset($v[1]) ? $v[1] : '',
									'金額' => isset($v[2]) ? $v[2] : '',
									'科目' => isset($v[3]) ? $v[3] : '',
									'結案日期' => isset($v[4]) ? $v[4] : '',
								];
							}
						}
					}
				}
			}
// print_r($data_array);exit;
		}
		return $data_array;
	}

		/**
		 * [getCreditCardsInfo 找信用卡持卡紀錄]
		 * @param  string $text       [pdf字串]
		 * @return array  $data_array [信用卡持卡紀錄]
		 */
		private function getCreditCardsInfo($text=''){
			$data_array = [
				'K1' => [
					'description' => '信用卡持卡記錄'
				]
			];
			if($text){
				$text = preg_replace('/信用卡持卡紀錄|表|K1|說明：|發卡機構|卡名|發卡日期|停卡日期|使用狀態/','',$text);
				preg_match_all('/(.*正卡.[0-9]{3}\/[0-9]{2}\/[0-9]{2}.*)|(.*附卡.[0-9]{3}\/[0-9]{2}\/[0-9]{2}.*\s.*)/',$text,$content);
				$content = isset($content[0]) ? $content[0] : [];
				if($content){
					foreach($content as $k=>$v){
						$content[$k] = array_values(array_filter(preg_split('/\s/',$v)));
					}

					foreach($content as $k=>$v){
						$data_array['K1']['dataList'][$k]['authority'] = isset($content[$k][0]) ? $content[$k][0] : '';
						// $name_of_credit_card = isset($content[$k][1]) && isset($content[$k][2]) && isset($content[$k][3]) ? $content[$k][1].$content[$k][2].$content[$k][3] : '';
						$data_array['K1']['dataList'][$k]['cardName']['type'] = $content[$k][1];
						$data_array['K1']['dataList'][$k]['cardName']['level'] = $content[$k][2];
						$data_array['K1']['dataList'][$k]['cardName']['primaryType'] = $content[$k][3];
						$data_array['K1']['dataList'][$k]['authorizedDate'] = isset($content[$k][4]) ? $content[$k][4] : '';
						if(count($v) == 6){
							$data_array['K1']['dataList'][$k]['status'] = isset($content[$k][5]) ? $content[$k][5] : '';
						}else{
							$data_array['K1']['dataList'][$k]['deauthorizedDate'] = isset($content[$k][5]) ? $content[$k][5] : '';
							$data_array['K1']['dataList'][$k]['status'] = isset($content[$k][6]) ? $content[$k][6] : '';
						}
					}
				}
			}
			return $data_array;
		}

	// 信用卡帳款總餘額資訊
	/**
	 * [getCreditCardAccounts 找信用卡帳款總餘額資訊]
	 * @param  string $text       [pdf字串]
	 * @return array  $data_array [信用卡帳款總餘額資訊]
	 */
	private function getCreditCardAccounts($text=''){
		$data_array = [];
		if($text){
			$content = preg_replace('/當事人綜合信用報告(\s)*.(\W)*|信用卡戶帳款資訊|表|K2|.*\s.*但最長不超過自停卡日期起7年.*/','',$text);
			$content = preg_replace('/債權結案|結帳日|發卡機構|卡名|額度|本期|應付帳款|未到期|待付款|上期|繳款狀況|是否|預借現金|債權|狀態|債權結案/','',$content);
			$content = preg_replace('/.*信用卡帳款總餘額.*/','',$content);
			if($content){
				$array = preg_split('/([0-9]{3}\/[0-9]{2}\/[0-9]{2})/',$content,-1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				$validFlag = false;
				foreach($array as $k=>$v){
					if(!$validFlag) {
						preg_match('/([0-9]{3}\/[0-9]{2}\/[0-9]{2})/', $v, $matches);
						if(empty($matches)) {
							unset($array[$k]);
							continue;
						}
						$validFlag = true;
					}
					$array[$k] = preg_replace('/(^\s*)|(\s*$)/','',preg_replace('/[\r\n]/','',$v));
				}

				// Filter null/empty object with array_filter
				$array = array_values(array_filter($array));
				$result = array();
				for( $i = 0, $count = count( $array); $i < $count-1; $i += 2){
						$v = preg_split('/\t/',$array[$i] ."\t". $array[$i + 1]);

						$data_array['K2']['dataList'][] = [
							'date' => isset($v[0]) ? $v[0] : '',
							'bank' => isset($v[1]) ? $v[1] : '',
							'cardType' => isset($v[2]) ? $v[2] : '',
							'quotaAmount' => isset($v[3]) ? $v[3] : '',
							'currentAmount' => isset($v[4]) ? $v[4] : '',
							'nonExpiredAmount' => isset($v[5]) ? $v[5] : '',
							'previousPaymentStatus' => isset($v[6]) ? $v[6] : '',
							'cashAdvanced' => isset($v[7]) ? $v[7] : '',
							'claimsStatus' => isset($v[8]) ? $v[8] : '',
							'claimsClosed' => isset($v[9]) ? $v[9] : ''
						];
				}
			}
		}
		return $data_array;
	}

	/**
	 * [getQueryLogInfo 找查詢記錄]
	 * @param  string $text       [pdf字串]
	 * @param  string $info_item  [信用資訊項目]
	 * S1|S2(被查詢記錄|當事人查詢信用報告紀錄)
	 * @return array  $data_array [被查詢記錄|當事人查詢信用報告紀錄]
	 */
	private function getQueryLogInfo($text='',$info_item=''){
		$data_array = [];

		if($text){
			preg_match_all('/.*[0-9]{3}\/[0-9]{2}\/[0-9]{2}.*/',$text,$content);
			$content = isset($content[0]) ? $content[0] : [];
			if($content){
				foreach($content as $k=>$v){
					$content[$k] = array_filter(preg_split('/\s/',$v));
				}
				if($info_item){
					// 被查詢記錄
					if($info_item=='S1'){
						foreach($content as $k=>$v){
							$data_array['S1']['dataList'][$k]['date'] = isset($content[$k][0]) ? $content[$k][0] : '';
							$data_array['S1']['dataList'][$k]['institution'] = isset($content[$k][1]) ? $content[$k][1] : '';
							$data_array['S1']['dataList'][$k]['reason'] = isset($content[$k][2]) ? $content[$k][2] : '';
						}
					}
					// 當事人查詢信用報告紀錄
					if($info_item=='S2'){
						foreach($content as $k=>$v){
							$data_array['S2']['dataList'][$k]['date'] = isset($content[$k][0]) ? $content[$k][0] : '';
							$data_array['S2']['dataList'][$k]['applyType'] = isset($content[$k][1]) ? $content[$k][1] : '';
							$data_array['S2']['dataList'][$k]['creditReportType'] = isset($content[$k][2]) ? $content[$k][2] : '';
							$data_array['S2']['dataList'][$k]['revealToBank'] = isset($content[$k][3]) ? $content[$k][3] : '';
						}
					}
				}
			}
		}

		return $data_array;
	}

	// 信用評分
	/**
	 * [getCreditScore 找信用評分資訊]
	 * @param  string $text       [pdf字串]
	 * @return array  $data_array [信用評分]
	 * (
	 *  [scoreComment] => 信用評分
	 * )
	 */
	private function getCreditScore($text=''){
		$data_array = [];
		if($text){
			preg_match('/信用評分:(\s)?[0-9]{3}分/',$text,$score);
			$score = isset($score[0]) ? $score[0] : '';
			if($score){
				preg_match('/[0-9]{3}/',$score,$score);
				$score = isset($score[0]) ? $score[0] : '';
			}
			$data_array['scoreComment'] = $score;
		}
		return $data_array;
	}

	/**
	 * [getCreditScoreReason 找評分原因資訊]
	 * @param  string $text       [pdf字串]
	 * @return array  $data_array [評分原因]
	 * (
	 *  [noCommentReason] =>
	 *    (
	 *     '評分原因'
	 *     ...
	 *    )
	 * )
	 */
	private function getCreditScoreReason($text=''){
		$data_array['noCommentReason'] = '';
		if($text){
			preg_match_all('/＊.*/',$text,$content);
			$content = isset($content[0]) ? $content[0] : [];
			if($content){
				foreach($content as $k=>$v){
					$data_array['noCommentReason'] .= $v.'
					';
				}
			}
		}
		return $data_array;
	}

	/**
	 * [searchEndKey 尋找信用項目切段結尾字串]
	 * @param  array  $credit_info [信用資訊項目總表]
	 *(
	 * [liabilities] =>
	 *   (
	 *    [totalAmount] => 借款總餘額資訊
	 *    [metaInfo] => 共同債務/從債務/其他債務資訊
	 *    [badDebtInfo] => 借款逾期、催收或呆帳紀錄
	 *   )
	 * [creditCard] =>
	 *   (
	 *    [cardInfo] => 信用卡持卡紀錄
	 *    [totalAmount] => 信用卡帳款總餘額資訊
	 *   )
	 * [checkingAccount] =>
	 *   (
	 *    [largeAmount] => 大額存款不足退票資訊
	 *    [rejectInfo] => 票據拒絕往來資訊
	 *   )
	 * [queryLog] =>
	 *   (
	 *    [queriedLog] => 被查詢紀錄
	 *    [applierSelfQueriedLog] => 當事人查詢信用報告紀錄
	 *   )
	 * [other] =>
	 *   (
	 *    [extraInfo] => 附加訊息資訊
	 *    [mainInfo] => 主債務債權轉讓及清償資訊
	 *    [metaInfo] => 共同債務/從債務/其他債務轉讓資訊
	 *    [creditCardTransferInfo] => 信用卡債權轉讓及清償資訊
	 *   )
	 *)
	 * @param  string $start_key   [擷取信用項目開頭類別]
	 * @return string $end_key     [擷取信用項目結尾類別]
	 */
	public function searchEndKey($credit_info=[],$start_key=''){
		$end_key = '';
		// $end_key_1 = '';
		$is_after_start_key=0;
		$mapping_array = [
			'liabilities' => '一、借款資訊',
			'creditCard' => '二、信用卡資訊',
			'checkingAccount' => '三、票信資訊',
			'queryLog' => '四、查詢紀錄',
			'other' => '五、其他',
		];

		foreach($credit_info as $k=>$v){
			if($k == $start_key){
				$is_after_start_key=1;
				continue;
			}
			if($is_after_start_key){
				foreach($v as $k1=>$v1){
					if($v1 != '' && $v1 != '無'){
						$end_key = $k;
						break 2;
					}
				}
			}
		}

		if($end_key){
			$end_key = isset($mapping_array[$end_key]) ? $mapping_array[$end_key] : '';
		}

		return $end_key;
	}

	// 轉換 pdf 解析資料格式
	public function transfrom_pdf_data($text){

		$response = [
			'applierInfo' => [
				'basicInfo' =>[
					'personId' => '',
				],
				'creditInfo' =>[
					'printDatetime' => '',
					'liabilities' => [
						'description' => '一. 借款資訊B',
						'totalAmount' => [
							'description' => "1. 借款總餘額資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'metaInfo' => [
							'description' => "2. 共同債務/從債務/其他債務資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'badDebtInfo' => [
							'description' => "3. 借款逾期、催收或呆帳記錄",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
					],
					'creditCard' => [
						'description' => "二. 信用卡資訊K",
						'cardInfo' => [
							'description' => "1. 信用卡持卡紀錄",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'totalAmount' => [
							'description' => "2. 信用卡帳款總餘額資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
					],
					'checkingAccount' => [
						'description' => "三. 票信資訊C",
						'largeAmount' =>[
							'description' => "1. 大額存款不足退票資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'rejectInfo' => [
							'description' => "2. 票據拒絕往來資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						]
					],
					'queryLog' => [
						'description' => "四. 查詢記錄S",
						'queriedLog' => [
							'description' => "1. 被查詢記錄",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'applierSelfQueriedLog' => [
							'description' => "2. 當事人查詢信用報告記錄",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						]
					],
					'other' => [
						'description' => "五. 其他O",
						'extraInfo' => [
							'description' => "1. 附加訊息資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'mainInfo' => [
							'description' => "2. 主債務債權轉讓及清償資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'metaInfo' =>[
							'description' => "3. 共同債務/從債務/其他債務轉讓資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						],
						'creditCardTransferInfo' => [
							'description' => "4. 信用卡債權轉讓及清償資訊",
							'existCreditInfo' => "有/無信用資訊",
							'creditDetail' => "參閱信用明細"
						]
					]
				]
			],
			'B1' => [
				'dataList' => [],
			],
			'B1-extra' => [
				'dataList' => [],
			],
			'B2' => [
				'part1' => [
					'dataList' => [],
				],
				'part2' => [
					'dataList' => [],
				],
				'part3' => [
					'dataList' => [],
				]
			],
			'B3' => [
				'dataList' => [],
			],
			'K1' => [
				'dataList' => [],
			],
			'K2' => [
				'dataList' => [],
			],
			'C1' => [
				'dataList' => [],
			],
			'C2' => [
				'dataList' => [],
			],
			'S1' => [
				'dataList' => [],
			],
			'S2' => [
				'dataList' => [],
			],
			'01' => [
				'dataList' => [],
			],
			'02' => [
				'dataList' => [],
			],
			'03' => [
				'dataList' => [],
			],
			'04' => [
				'dataList' => [],
			],
			'companyCreditScore' => [
				'scoreComment' => '',
				'noCommentReason' => [],
			]
		];

		// 統一編號
		$response['applierInfo']['basicInfo']['personId'] = $this->getIdCardNumber($text);

		// 截至印表時間
		$report_time = $this->getReportTime($text);
		$response['applierInfo']['creditInfo']['printDatetime'] = $report_time;

		// 第一頁總表資訊
		$credit_table_info = $this->checkHasInfo($text);
		$ConvertIntegerMultiplier = ['liabilities' => ['totalAmount' => 1000], 'totalAmount' => ['totalAmount' => 1]];
		if($credit_table_info){
			foreach($credit_table_info as $k=>$v){
				foreach($v as $k1=>$v1){
					$response['applierInfo']['creditInfo'][$k][$k1]['existCreditInfo'] = $v1;
					if(isset($ConvertIntegerMultiplier[$k][$k1]) && $ConvertIntegerMultiplier[$k][$k1]) {
						preg_match('/(\d+[,]*)+/', $v1, $regexResult);
						if (!empty($regexResult)) {
							$response['applierInfo']['creditInfo'][$k][$k1]['existCreditInfo'] = intval(str_replace(",","",$regexResult[0])) * $ConvertIntegerMultiplier[$k][$k1];
						}
					}
				}
			}
		}

		// 消除首頁與跨頁資訊
		$new_text = $this->deletePageInfo($text);

		$item_check = [];
		foreach($credit_table_info as $k=>$v){
			foreach($v as $k1=>$v1){
				if($v1 != '無' && $v1 != ''){
					$item_check[$k] = 1;
				}
			}
		}

		// 一、借款資訊
		if(isset($item_check['liabilities']) && $item_check['liabilities']==1){
			$end_key = $this->searchEndKey($credit_table_info,'liabilities');
			$content = $this->CI->regex->findPatternInBetween($new_text, '一、借款資訊', $end_key);
			$content = isset($content[0]) ? $content[0] : '';
			$res = $this->getTotalLoanInfo($content);
			$response = array_merge($response,$res);
		}

		// 二、信用卡資訊
		if(isset($item_check['creditCard']) && $item_check['creditCard']==1){
			$end_key = $this->searchEndKey($credit_table_info,'creditCard');
			$content = $this->CI->regex->findPatternInBetween($new_text, '二、信用卡資訊', $end_key);
			$content = isset($content[0]) ? $content[0] : '';

			// 信用卡持卡紀錄
			if($response['applierInfo']['creditInfo']['creditCard']['cardInfo']['existCreditInfo']=='有'){
				$sub_content = $this->CI->regex->findPatternInBetween($content, '', '信用卡資料揭露期限');
				$sub_content = isset($sub_content[0]) ? $sub_content[0] : '';
				$res = $this->getCreditCardsInfo($sub_content);
				$response = array_merge($response,$res);
			}

			// 信用卡戶帳款資訊
			if(preg_match('/有|[0-9]*(,[0-9]*)*元/',$response['applierInfo']['creditInfo']['creditCard']['totalAmount']['existCreditInfo'])){
				if(preg_match('/([0-9]*(,[0-9]*)*元)?\s信用卡帳款總餘額\s([0-9]*(,[0-9]*)*元)?/',$content)){
					preg_match('/([0-9]*(,[0-9]*)*元)?\s信用卡帳款總餘額\s([0-9]*(,[0-9]*)*元)?/',$content,$sub_content);
					$sub_content = isset($sub_content[0]) ? $sub_content[0] : '';
					if($sub_content){
						$response['K2']['totalAmount'] = preg_replace('/\s信用卡帳款總餘額\s/','',$sub_content);
					}
				}
				if($response['applierInfo']['creditInfo']['creditCard']['cardInfo']['existCreditInfo']=='有'){
					$start_key = '信用卡資料揭露期限';
				}else{
					$start_key = '';
				}
				$sub_content = $this->CI->regex->findPatternInBetween($content, $start_key, '信用卡戶帳款資料揭露期限');
				$sub_content = isset($sub_content[0]) ? $sub_content[0] : '';
				$res = $this->getCreditCardAccounts($sub_content);
				$response = array_merge_recursive($response,$res);
			}
		}

		// 四、查詢紀錄
		if(isset($item_check['queryLog']) && $item_check['queryLog']==1){
			$end_key = $this->searchEndKey($credit_table_info,'queryLog');
			$content = $this->CI->regex->findPatternInBetween($new_text, '四、查詢紀錄', $end_key);
			$content = isset($content[0]) ? $content[0] : '';

			// 被查詢紀錄
			if(preg_match('/被查詢紀錄/',$content)){
				$sub_content = $this->CI->regex->findPatternInBetween($content, '', '被金融機構或電子支付機構或電子票證發行機構查詢紀錄');
				$sub_content = isset($sub_content[0]) ? $sub_content[0] : '';
				$res = $this->getQueryLogInfo($sub_content,'S1');
				$response = array_merge($response,$res);
			}

			// 當事人查詢信用報告紀錄
			if(preg_match('/當事人查詢信用報告紀錄/',$content)){
				if($response['applierInfo']['creditInfo']['queryLog']['queriedLog']['existCreditInfo']=='有'){
					$start_key = '被金融機構或電子支付機構或電子票證發行機構查詢紀錄';
				}else{
					$start_key = '';
				}
				$sub_content = $this->CI->regex->findPatternInBetween($content, $start_key, '當事人查詢信用報告紀錄');
				$sub_content = isset($sub_content[0]) ? $sub_content[0] : '';
				$res = $this->getQueryLogInfo($sub_content,'S2');
				$response = array_merge($response,$res);
			}
		}

		// 信用評分
		$res = $this->getCreditScore($new_text);
		$response['companyCreditScore'] = array_merge($response['companyCreditScore'],$res);

		// 信用評分說明
		$res = $this->getCreditScoreReason($new_text);
		$response['companyCreditScore'] = array_merge($response['companyCreditScore'],$res);

		return $response;
	}
}
