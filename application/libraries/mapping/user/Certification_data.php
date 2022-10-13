<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Certification_data
{
	public function __construct()
    {
        $this->CI = &get_instance();
    }

	/**
	 * [transformJointCreditToOcrData 聯徵資料格式轉換與ocr,pdf json一致]
	 * @param  array  $data           [聯徵儲存資料]
	 * @return array  $transform_data [專換後資料]
	 */
	public function transformJointCreditToOcrData($data=[]){
		$transform_data = [
			'applierInfo' => [
				'basicInfo' => [
					'personId' => isset($data['personId'])?$data['personId']:'',
					'taxId' => isset($data['taxId'])?$data['taxId']:'',
				],
				'creditInfo' => [
					'printDatetime' => isset($data['printDatetime'])?$data['printDatetime']:'',
					'liabilities' => [
						'totalAmount' => [
							'existCreditInfo' => isset($data['liabilities_totalAmount_existCreditInfo'])?$data['liabilities_totalAmount_existCreditInfo']:'',
						],
						'metaInfo' => [
							'existCreditInfo' => isset($data['liabilities_metaInfo_existCreditInfo'])?$data['liabilities_metaInfo_existCreditInfo']:'',
						],
						'badDebtInfo' => [
							'existCreditInfo' => isset($data['liabilities_badDebtInfo_existCreditInfo'])?$data['liabilities_badDebtInfo_existCreditInfo']:'',
						],
					],
					'creditCard' => [
						'cardInfo' => [
							'existCreditInfo' => isset($data['creditCard_cardInfo_existCreditInfo'])?$data['creditCard_cardInfo_existCreditInfo']:'',
						],
						'totalAmount' => [
							'existCreditInfo' => isset($data['creditCard_totalAmount_existCreditInfo'])?$data['creditCard_totalAmount_existCreditInfo']:'',
						]
					],
					'checkingAccount' => [
						'largeAmount' => [
							'existCreditInfo' => isset($data['checkingAccount_largeAmount_existCreditInfo'])?$data['checkingAccount_largeAmount_existCreditInfo']:'',
						],
						'rejectInfo' => [
							'existCreditInfo' => isset($data['checkingAccount_rejectInfo_existCreditInfo'])?$data['checkingAccount_rejectInfo_existCreditInfo']:'',
						]
					],
					'queryLog' => [
						'queriedLog' => [
							'existCreditInfo' => isset($data['queryLog_queriedLog_existCreditInfo'])?$data['queryLog_queriedLog_existCreditInfo']:'',
						],
						'applierSelfQueriedLog' => [
							'existCreditInfo' => isset($data['queryLog_applierSelfQueriedLog_existCreditInfo'])?$data['queryLog_applierSelfQueriedLog_existCreditInfo']:'',
						]
					],
					'other' => [
						'extraInfo' => [
							'existCreditInfo' => isset($data['other_extraInfo_existCreditInfo'])?$data['other_extraInfo_existCreditInfo']:'',
						],
						'mainInfo' => [
							'existCreditInfo' => isset($data['other_mainInfo_existCreditInfo'])?$data['other_mainInfo_existCreditInfo']:'',
						],
						'metaInfo' => [
							'existCreditInfo' => isset($data['other_metaInfo_existCreditInfo'])?$data['other_metaInfo_existCreditInfo']:'',
						],
						'creditCardTransferInfo' => [
							'existCreditInfo' => isset($data['other_creditCardTransferInfo_existCreditInfo'])?$data['other_creditCardTransferInfo_existCreditInfo']:'',
						]
					],
				]
			],
			'B1' => [
				'dataList' => []
			],
			'B1-extra' => [
				'dataList' => []
			],
			'B2' => [
				'part1' => [
					'dataList' => []
				],
				'part2' => [
					'dataList' => []
				],
				'part3' => [
					'dataList' => []
				]
			],
			'B3' => [
				'dataList' => []
			],
			'K1' => [
				'dataList' => []
			],
			'K2' => [
				'dataList' => []
			],
			'C1' => [
				'dataList' => []
			],
			'C2' => [
				'dataList' => []
			],
			'S1' => [
				'dataList' => []
			],
			'S2' => [
				'dataList' => []
			],
			'01' => [
				'dataList' => []
			],
			'02' => [
				'dataList' => []
			],
			'03' => [
				'dataList' => []
			],
			'04' => [
				'dataList' => []
			],
			'extraA11' => [
				'queryDatetime' => '',
				'personId1' => '',
				'personName1' => '',
				'debtDischargeOtherComment' => '',
				'reportedRecord' => '',
				'personId2' => '',
				'personName2' => '',
				'part1'=> [
					'dataList' => []
				],
				'part2'=> [
					'dataList' => []
				],
				'part3'=> [
					'dataList' => []
				],
			],
			'companyCreditScore' => [
				'scoreComment' => isset($data['scoreComment'])?$data['scoreComment']:'',
				'noCommentReason' => isset($data['noCommentReason'])?$data['noCommentReason']:'',
			],
		];

		if(!empty($data['table_list'])){
			if(!empty($data['table_list']['B1_table'])){
				foreach($data['table_list']['B1_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^B1_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['B1']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['B1-extra_table'])){
				foreach($data['table_list']['B1-extra_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^B1\-extra_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['B1-extra']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['B2-part1_table'])){
				foreach($data['table_list']['B2-part1_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^B2\-part1_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['B2']['part1']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['B2-part2_table'])){
				foreach($data['table_list']['B2-part2_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^B2\-part2_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['B2']['part2']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['B2-part3_table'])){
				foreach($data['table_list']['B2-part3_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^B2\-part3_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['B2']['part3']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['B3_table'])){
				foreach($data['table_list']['B3_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^B3_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['B3']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['K1_table'])){
				foreach($data['table_list']['K1_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^K1_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['K1']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['K2_table'])){
				foreach($data['table_list']['K2_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^K2_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['K2']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['S1_table'])){
				foreach($data['table_list']['S1_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^S1_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['S1']['dataList'][] = $array;
				}
			}
			if(!empty($data['table_list']['S2_table'])){
				foreach($data['table_list']['S2_table'] as $value){
					$array = [];
					foreach($value as $key1 => $value1){
						$key1 = preg_replace('/^S2_|_[0-9]*/','',$key1);
						$array[$key1] = $value1;
					}
					$transform_data['S2']['dataList'][] = $array;
				}
			}
			$transform_data['extraA11']['personId1'] = isset($data['personId1']) ? $data['personId1'] : '';
			$transform_data['extraA11']['personName1'] = isset($data['personName1']) ? $data['personName1'] : '';
			$transform_data['extraA11']['debtDischargeOtherComment'] = isset($data['debtDischargeOtherComment']) ? $data['debtDischargeOtherComment'] : '';
			$transform_data['extraA11']['reportedRecord'] = isset($data['reportedRecord']) ? $data['reportedRecord'] : '';
			$transform_data['extraA11']['personId2'] = isset($data['personId2']) ? $data['personId2'] : '';
			$transform_data['extraA11']['personName2'] = isset($data['personName2']) ? $data['personName2'] : '';
			if(!empty($data['table_list']['A11-part1_table'])){
				foreach($data['table_list']['A11-part1_table'] as $key => $value){
					$num = $key + 1;
					$array_data = [
						'taxId' => isset($value["A11_part1_taxId_{$num}"]) ? $value["A11_part1_taxId_{$num}"] : '',
						'companyName' => isset($value["A11_part1_companyName_{$num}"]) ? $value["A11_part1_companyName_{$num}"] : '',
						'jobTitle' => isset($value["A11_part1_jobTitle_{$num}"]) ? $value["A11_part1_jobTitle_{$num}"] : '',
						'amountOfCapitalContributed' => isset($value["A11_part1_amountOfCapitalContributed_{$num}"]) ? $value["A11_part1_amountOfCapitalContributed_{$num}"]: '',
						'personName' => isset($value["A11_part1_personName_{$num}"]) ? $value["A11_part1_personName_{$num}"] : '',
						'registerStatus' => isset($value["A11_part1_registerStatus_{$num}"]) ? $value["A11_part1_registerStatus_{$num}"] : '',
					];
					$transform_data['extraA11']['part1']['dataList'][] = $array_data;
				}
			}
			if(!empty($data['table_list']['A11-part2_table'])){
				foreach($data['table_list']['A11-part2_table'] as $key => $value){
					$num = $key + 1;
					$array_data = [
						'taxId' => isset($value["A11_part2_taxId_{$num}"]) ? $value["A11_part2_taxId_{$num}"] : '',
						'companyName' => isset($value["A11_part2_companyName_{$num}"]) ? $value["A11_part2_companyName_{$num}"] : '',
						'jobTitle' => isset($value["A11_part2_jobTitle_{$num}"]) ? $value["A11_part2_jobTitle_{$num}"] : '',
						'amountOfCapitalContributed' => isset($value["A11_part2_amountOfCapitalContributed_{$num}"]) ? $value["A11_part2_amountOfCapitalContributed_{$num}"]: '',
						'personName' => isset($value["A11_part2_personName_{$num}"]) ? $value["A11_part2_personName_{$num}"] : '',
						'registerStatus' => isset($value["A11_part2_registerStatus_{$num}"]) ? $value["A11_part2_registerStatus_{$num}"] : '',
					];
					$transform_data['extraA11']['part2']['dataList'][] = $array_data;
				}
			}
			if(!empty($data['table_list']['A11-part3_table'])){
				foreach($data['table_list']['A11-part3_table'] as $key => $value){
					$num = $key + 1;
					$array_data = [
						'taxId' => isset($value["A11_part3_taxId_{$num}"]) ? $value["A11_part3_taxId_{$num}"] : '',
						'companyName' => isset($value["A11_part3_companyName_{$num}"]) ? $value["A11_part3_companyName_{$num}"] : '',
						'jobTitle' => isset($value["A11_part3_jobTitle_{$num}"]) ? $value["A11_part3_jobTitle_{$num}"] : '',
						'amountOfCapitalContributed' => isset($value["A11_part3_amountOfCapitalContributed_{$num}"]) ? $value["A11_part3_amountOfCapitalContributed_{$num}"]: '',
						'personName' => isset($value["A11_part3_personName_{$num}"]) ? $value["A11_part3_personName_{$num}"] : '',
						'registerStatus' => isset($value["A11_part3_registerStatus_{$num}"]) ? $value["A11_part3_registerStatus_{$num}"] : '',
					];
					$transform_data['extraA11']['part3']['dataList'][] = $array_data;
				}
			}
		}

		return $transform_data;
	}
	/**
	 * [transformGovernmentauthoritiesToMeta 變卡 result 轉 meta data]
	 * @param  array  $result    [變卡 result]
	 * @return array  $meta_data [user meta data]
	 */
	public function transformGovernmentauthoritiesToMeta($result=[]){
		$meta_data =[];
		if($result){
			$meta_data = array_reduce($result, 'array_merge', array());
			unset($meta_data['action_user']);
			unset($meta_data['send_time']);
			unset($meta_data['status']);
		}
		return $meta_data;
	}

	/**
	 * [transformIncomestatementToMeta 損益表 result 轉 meta data]
	 * @param  array  $result    [損益表 result]
	 * @return array  $meta_data [user meta data]
	 */
	public function transformIncomestatementToMeta($result=[]){
		$meta_data =[];
		if($result){
			$meta_data = array_column($result, 'input_90', 'report_time');
			foreach($result as $k=>$v){
				$meta_data['input_89'] = isset($v['input_89']) ? $v['input_89']:'';
			}
		}
		return $meta_data;
	}

	/**
	 * [transformEmployeeinsurancelistToMeta 月末投保 result 轉 meta data]
	 * @param  array  $result    [月末投保 result]
	 * @return array  $meta_data [user meta data]
	 */
	public function transformEmployeeinsurancelistToMeta($result=[]){
		$meta_data =[];
		if($result){
			$meta_data = array_reduce($result, 'array_merge', array());
			unset($meta_data['action_user']);
			unset($meta_data['send_time']);
			unset($meta_data['status']);
		}
		return $meta_data;
	}

	/**
	 * [transformJointCreditToResult 聯徵解析資料轉 result]
	 * @param  array  $data [聯徵解析資料]
	 * @return array  $re   [統計結果]
	 * (
	 *  [personId] => 統一編號(自然人)
	 *  [taxId] => 統一編號(法人)
	 *  [printDatetime] => 印表時間
	 *  [scoreComment] => 信用評分
	 *  [liabilities_totalAmount] => 借款資訊B - 借款總餘額資訊,
	 *  [liabilitiesWithoutAssureTotalAmount] => 借款資訊B - 借款總餘額資訊(不含擔保借款),
	 *  [liabilities_metaInfo] => 借款資訊B - 共同債務/從債務/其他債務資訊,
	 *  [liabilities_badDebtInfo] => 借款資訊B - 借款逾期、催收或呆帳記錄,
	 *  [creditCard_cardInfo] => 信用卡資訊K - 信用卡持卡紀錄,
	 *  [creditCard_totalAmount] => 信用卡資訊K - 信用卡帳款總餘額資訊,
	 *  [checkingAccount_largeAmount] => 票信資訊C - 大額存款不足退票資訊,
	 *  [checkingAccount_rejectInfo] => 票信資訊C - 票據拒絕往來資訊,
	 *  [queryLog_queriedLog] => 查詢記錄S - 被查詢記錄,
	 *  [queryLog_applierSelfQueriedLog] => 查詢記錄S - 當事人查詢信用報告記錄,
	 *  [other_extraInfo] => 其他O - 附加訊息資訊,
	 *  [other_mainInfo] => 其他O - 主債務債權轉讓及清償資訊,
	 *  [other_metaInfo] => 其他O - 共同債務/從債務/其他債務轉讓資訊,
	 *  [other_creditCardTransferInfo] => 其他O - 信用卡債權轉讓及清償資訊,
	 *  [totalAmountShort] => 短期放款訂約金額
	 *  [totalAmountMid] => 中期放款訂約金額
	 *  [totalAmountShortMonth] => 短期放款時間
	 *  [totalAmountMidMonth] => 中期放款時間
	 *  [totalAmountLong] => 長期放款訂約金額
	 *  [totalAmountShortAssure] => 短期擔保放款訂約金額
	 *  [totalAmountMidAssure] => 中期擔保放款訂約金額
	 *  [totalAmountLongAssure] => 長期擔保放款訂約金額
	 *  [totalAmountStudentLoans] => 助學貸款放款訂約金額
	 *  [totalAmountCreditCard] => 信用卡借款訂約金額
	 *  [totalAmountCash] => 現金卡借款訂約金額
	 *  [totalAmountShortCount] => 短期放款筆數
	 *  [totalAmountMidCount] => 中期放款筆數
	 *  [totalAmountLongCount] => 長期放款筆數
	 *  [totalAmountShortAssureCount] => 短期擔保放款筆數
	 *  [totalAmountMidAssureCount] => 中期擔保放款筆數
	 *  [totalAmountLongAssureCount] => 長期擔保放款筆數
	 *  [totalAmountStudentLoansCount] => 助學貸款筆數
	 *  [totalAmountCreditCardCount] => 信用卡借款筆數
	 *  [totalAmountCashCount] => 現金卡借款筆數
	 *  [balanceShort] => 短期放款餘額
	 *  [balanceMid] => 中期放款餘額
	 *  [balanceLong] => 長期放款餘額
	 *  [balanceShortAssure] => 短期擔保放款餘額
	 *  [balanceMidAssure] => 中期擔保放款餘額
	 *  [balanceLongAssure] => 長期擔保放款餘額
	 *  [balanceStudentLoans] => 助學貸款餘額
	 *  [balanceCreditCard] => 信用卡借款餘額
	 *  [balanceCash] => 現金卡借款餘額
	 *  [repaymentDelay] => 有無遲延還款
	 *  [bankCount] => 借款家數
	 *  [totalAmountQuota] => 訂約金額總額度
	 *  [creditCardCount] => 信用卡使用中張數
	 *  [creditUtilizationRate] => 信貸額度動用率
	 *  [creditLogCount] => 信用紀錄幾個月
	 *  [cashAdvanced] => 是否預借現金
	 *  [delayLessMonth] => 遲延未滿 1個月紀錄
	 *  [creditCardUseRate] => 近 1個月信用卡使用率
	 *  [S1Count] => 被電子支付或電子票證發行機構查詢紀錄
	 *  [S2Count] => 當事人查詢紀錄
	 *  [commentReason] => 信用評分原因
	 *  [A11TaxId] => 擔任其他企業負責人
	 *  [longAssureMonthlyPayment] => 長期擔保放款月繳(房貸)
	 *  [midAssureMonthlyPayment] => 中期擔保放款月繳(車貸)
	 *  [longMonthlyPayment] => 長期放款月繳
	 *  [midMonthlyPayment] => 中期放款月繳
	 *  [shortMonthlyPayment] => 短期放款月繳
	 *  [studentLoansMonthlyPayment] => 助學貸款月繳
	 *  [creditCardMonthlyPayment] => 信用卡月繳
	 *  [totalMonthlyPayment] => 總共月繳
	 *  [creditCardHasDelay] => 信用卡是否有延遲繳款紀錄
	 *  [creditCardHasBadDebt] => 信用卡是否有逾期、催收、呆帳紀錄
     *  [totalAppropriationAmount] => 總撥款金額
     *  [totalRepaymentAmount] => 總還款金額
     *  [newBalanceShortAssure] => 短期擔保放款餘額(新算法)
     *  [newBalanceMidAssure] => 中期擔保放款餘額(新算法)
     *  [newBalanceLongAssure] => 長期擔保放款餘額(新算法)
	 * )
	 */
	// to do : 先以普匯微企e秒貸為主
	// 需增加聯徵 UI顯示需求相關數值
	public function transformJointCreditToResult($data=[]){
		$res = [
			'personId' => '',
			'taxId' => '',
			'liabilities_totalAmount'=>'',
			'liabilities_metaInfo'=>'',
			'liabilities_badDebtInfo'=>'',
			'creditCard_cardInfo'=>'',
			'creditCard_totalAmount'=>'',
			'checkingAccount_largeAmount'=>'',
			'checkingAccount_rejectInfo'=>'',
			'queryLog_queriedLog'=>'',
			'queryLog_applierSelfQueriedLog'=>'',
			'other_extraInfo'=>'',
			'other_mainInfo'=>'',
			'other_metaInfo'=>'',
			'other_creditCardTransferInfo'=>'',
			'printDatetime'=>'',
			'scoreComment' => '',
			'totalAmountShort' => 0,
			'totalAmountShortMonth' => '',
			'totalAmountMid' => 0,
			'totalAmountMidMonth' => '',
			'totalAmountLong' => 0,
			'totalAmountShortAssure' => 0,
			'totalAmountMidAssure' => 0,
			'totalAmountLongAssure' => 0,
			'totalAmountStudentLoans' => 0,
			'totalAmountCreditCard' => 0,
			'totalAmountCash' => 0,
			'totalAmountShortCount' => 0,
			'totalAmountMidCount' => 0,
			'totalAmountLongCount' => 0,
			'totalAmountShortAssureCount' => 0,
			'totalAmountMidAssureCount' => 0,
			'totalAmountLongAssureCount' => 0,
			'totalAmountStudentLoansCount' => 0,
			'totalAmountCreditCardCount' => 0,
			'totalAmountCashCount' => 0,
			'balanceShort' => 0,
			'balanceMid' => 0,
			'balanceLong' => 0,
			'balanceShortAssure' => 0,
			'balanceMidAssure' => 0,
			'balanceLongAssure' => 0,
			'balanceStudentLoans' => 0,
			'balanceCreditCard' => 0,
			'balanceCash' => 0,
			'repaymentDelay' => '無',
			'bankCount' => 0,
			'totalAmountQuota' => 0,
			'balanceQuota' => 0,
			'creditUtilizationRate' => 0,
			'creditCardCount' => 0,
			'creditLogCount' => '',
			'cashAdvanced' => '無',
			'delayLessMonth' => 0,
			'delayMoreMonth' => 0,
			'creditCardUseRate' => 0,
			'S1Count' => 0,
			'S2Count' => 0,
			'commentReason' => '',
			'A11TaxId' => '',
			'creditCardHasDelay' => '無',
			'creditCardHasBadDebt' => '無',
            'totalAppropriationAmount' => 0,
            'totalRepaymentAmount' => 0,
            'newBalanceShortAssure' => 0,
            'newBalanceMidAssure' => 0,
            'newBalanceLongAssure' => 0,
		];

		if($data){
			// 印表時間
			$res['printDatetime'] = isset($data['applierInfo']['creditInfo']['printDatetime']) ? $data['applierInfo']['creditInfo']['printDatetime'] : '';
			// 信用評分
			$res['scoreComment'] = isset($data['companyCreditScore']['scoreComment']) ? $data['companyCreditScore']['scoreComment'] : '';
			// 統一編號
			$res['personId'] = isset($data['applierInfo']['basicInfo']['personId']) ? $data['applierInfo']['basicInfo']['personId'] : '';
			$res['taxId'] = isset($data['applierInfo']['basicInfo']['taxId']) ? $data['applierInfo']['basicInfo']['taxId'] : '';
			// 有無信用資訊項目
			if(! empty($data['applierInfo']['creditInfo'])){
				$ConvertIntegerMultiplier = ['liabilities' => ['totalAmount' => 1000], 'totalAmount' => ['totalAmount' => 1]];
				foreach($data['applierInfo']['creditInfo'] as $k=>$v){
					if(is_array($v)){
						foreach($v as $k1=>$v1){
							if(is_array($v1)){
								foreach($v1 as $k2=>$v2){
									$name = $k.'_'.$k1;
									if(isset($res[$name])){
										$res[$name] = $v1['existCreditInfo'];
										if(isset($ConvertIntegerMultiplier[$k][$k1]) && $ConvertIntegerMultiplier[$k][$k1]) {
											preg_match('/(\d+[,]*)+/', $res[$name], $regexResult);
											if (!empty($regexResult)) {
												$res[$name] = intval(str_replace(",","",$regexResult[0])) * $ConvertIntegerMultiplier[$k][$k1];
											}
										}
									}
								}
							}
						}
					}
				}
			}
			// 借款餘額
			if(!empty($data['B1']['dataList'])){
				$bank_array = [];

				foreach($data['B1']['dataList'] as $value){
					$value['totalAmount'] = preg_replace('/\,|千元/','',$value['totalAmount']);
					$value['noDelayAmount'] = preg_replace('/\,|千元/','',$value['noDelayAmount']);
					$value['delayAmount'] = preg_replace('/\,|千元/','',$value['delayAmount']);

					if(is_numeric($value['totalAmount']) && is_numeric($value['noDelayAmount']) && is_numeric($value['delayAmount'])){
						// 訂約金額總額度
						$res['totalAmountQuota'] += $value['totalAmount'];
						// 借款餘額總額度
						$res['balanceQuota'] += $value['noDelayAmount'] + $value['delayAmount'];

						if($value['pastOneYearDelayRepayment'] != '無'){
							$res['repaymentDelay'] = '有';
						}

						$bank_name = preg_replace('/銀行.*分行$/',"",$value['bankName']);
						if(! in_array($bank_name,$bank_array)){
							$bank_array[] = $bank_name;
						}

						if($value['accountDescription']=='短期放款'||$value['accountDescription']=='其他短期放款'){
							$res['totalAmountShort'] += $value['totalAmount'];
							$res['totalAmountShortMonth'] = $value['yearMonth'];
							$res['totalAmountShortCount'] += 1;
							$res['balanceShort'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if($value['accountDescription']=='中期放款'){
							$res['totalAmountMid'] += $value['totalAmount'];
							$res['totalAmountMidMonth'] = $value['yearMonth'];
							$res['totalAmountMidCount'] += 1;
							$res['balanceMid'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if($value['accountDescription']=='長期放款'){
							$res['totalAmountLong'] += $value['totalAmount'];
							$res['totalAmountLongCount'] += 1;
							$res['balanceLong'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if($value['accountDescription']=='短期擔保放款'||$value['accountDescription']=='其他短期擔保放款'){
							$res['totalAmountShortAssure'] += $value['totalAmount'];
							$res['totalAmountShortAssureCount'] += 1;
							$res['balanceShortAssure'] += $value['noDelayAmount'] + $value['delayAmount'];
                            $res['newBalanceShortAssure'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if($value['accountDescription']=='中期擔保放款'){
							$res['totalAmountMidAssure'] += $value['totalAmount'];
							$res['totalAmountMidAssureCount'] += 1;
							$res['balanceMidAssure'] += $value['noDelayAmount'] + $value['delayAmount'];
                            $res['newBalanceMidAssure'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if($value['accountDescription']=='長期擔保放款'){
							$res['totalAmountLongAssure'] += $value['totalAmount'];
							$res['totalAmountLongAssureCount'] += 1;
							$res['balanceLongAssure'] += $value['noDelayAmount'] + $value['delayAmount'];
                            $res['newBalanceLongAssure'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if($value['accountDescription']=='助學貸款'){
							$res['totalAmountStudentLoans'] += $value['totalAmount'];
							$res['totalAmountStudentLoansCount'] += 1;
							$res['balanceStudentLoans'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if(preg_match('/信用卡/',$value['accountDescription'])){
							$res['totalAmountCreditCard'] += $value['totalAmount'];
							$res['totalAmountCreditCardCount'] += 1;
							$res['balanceCreditCard'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
						if(preg_match('/現金卡/',$value['accountDescription'])){
							$res['totalAmountCash'] += $value['totalAmount'];
							$res['totalAmountCashCount'] += 1;
							$res['balanceCash'] += $value['noDelayAmount'] + $value['delayAmount'];
						}
					}
				}

				foreach($data['B1-extra']['dataList'] as $value){
					$value['appropriationAmount'] = isset($value['appropriationAmount'])?preg_replace('/\,|千元/','',$value['appropriationAmount']):0;
					$value['repaymentAmount'] = isset($value['appropriationAmount'])?preg_replace('/\,|千元/','',$value['repaymentAmount']):0;

					// 有還款也有撥款: 銀行補撥款，會是跟B1的同一筆，不需要再增加額外貸款金額跟借款筆數
					// 有還款沒有撥款: 只單純抵銷借款金額，並不能當作借款筆數
					// 沒還款有撥款: 新的借款，需要增加貸款金額及貸款筆數
					if(is_numeric($value['appropriationAmount']) && is_numeric($value['repaymentAmount'])){
                        $res['totalAppropriationAmount'] += $value['appropriationAmount'];
                        $res['totalRepaymentAmount'] += $value['repaymentAmount'];

						if($value['accountDescription']=='短期擔保放款'||$value['accountDescription']=='其他短期擔保放款'){
							if($value['appropriationAmount'] > 0 && $value['repaymentAmount'] == 0) {
								$res['totalAmountShortAssureCount'] += 1;
								$res['totalAmountShortAssure'] += $value['appropriationAmount'];
								$res['balanceShortAssure'] += $value['appropriationAmount'];
							}else{
								$res['balanceShortAssure'] -= $value['repaymentAmount'];
							}
                            $res['newBalanceShortAssure'] += ($value['appropriationAmount'] - $value['repaymentAmount']);
						}
						if($value['accountDescription']=='中期擔保放款'){
							if($value['appropriationAmount'] > 0 && $value['repaymentAmount'] == 0) {
								$res['totalAmountMidAssureCount'] += 1;
								$res['totalAmountMidAssure'] += $value['appropriationAmount'];
								$res['balanceMidAssure'] += $value['appropriationAmount'];
							}else{
								$res['balanceMidAssure'] -= $value['repaymentAmount'];
							}
                            $res['newBalanceMidAssure'] += ($value['appropriationAmount'] - $value['repaymentAmount']);
						}
						if($value['accountDescription']=='長期擔保放款'){
							if($value['appropriationAmount'] > 0 && $value['repaymentAmount'] == 0) {
								$res['totalAmountLongAssureCount'] += 1;
								$res['totalAmountLongAssure'] += $value['appropriationAmount'];
								$res['balanceLongAssure'] += $value['appropriationAmount'];
							}else{
								$res['balanceMidAssure'] -= $value['repaymentAmount'];
							}
                            $res['newBalanceLongAssure'] += ($value['appropriationAmount'] - $value['repaymentAmount']);
						}
					}
				}

				// 借款家數
				$res['bankCount'] = count($bank_array);
				// 訂約金額總額度
				if($res['totalAmountShort'] != 0 || $res['totalAmountMid'] != 0 || $res['totalAmountLong'] != 0){
					$res['creditUtilizationRate'] = round(($res['balanceShort'] + $res['balanceMid'] + $res['balanceLong'])/($res['totalAmountShort'] + $res['totalAmountMid'] + $res['totalAmountLong'])*100, 2);
				}else{
					$res['creditUtilizationRate'] = 0;
				}

			}
			// 信用卡持卡紀錄
			if(!empty($data['K1']['dataList'])){
				$bank_array = [];
				$res['creditCardCount'] = 0;
				foreach ($data['K1']['dataList'] as $key => $value) {
					if(preg_match('/使用中/',$value['status'])) {
						if (!in_array($value['authority'], $bank_array)){
							$bank_array[] = $value['authority'];
						}
						$res['creditCardCount']++;
					}
				}
			}
			// 信用卡帳款總餘額資訊
			if(!empty($data['K2']['dataList'])){
				$this->CI->load->library('mapping/time');
				$res['creditLogCount'] = 0;
				$creditLogCountStatus = 1;

				// 前一個月(當期)
				$end_date = '';
				// 前兩個月(前一期)
				$end_date_before = '';
				$bank_array = [];
				$totalAmount = 0;
				$totalQuota = 0;

				// 依照時間對 K2 紀錄遞減排序
				usort($data['K2']['dataList'], function($a1, $a2) {
					$v1 = $this->CI->time->ROCDateToUnixTimestamp($a1['date']);
					$v2 = $this->CI->time->ROCDateToUnixTimestamp($a2['date']);
					return $v2 - $v1; // $v2 - $v1 to reverse direction
				});

				if(isset($data['K2']['dataList'][0]['date']) && preg_match('/[0-9]{3}\/[0-9]{2}\/[0-9]{2}/',$data['K2']['dataList'][0]['date'])){
					$last_date = $this->CI->time->ROCDateToUnixTimestamp($data['K2']['dataList'][0]['date']);

					if($last_date){
						$end_date = strtotime(date('Y/m/d',$last_date).' - 1 month');
						$end_date_before = strtotime(date('Y/m/d',$last_date).' - 2 month');
					}
				}

				$totalQuota = 0;
				$currentRecordDate = 0;
				$recordDate = new \DateTime();
				foreach($data['K2']['dataList'] as $key => $value){
					// 信用紀錄幾個月
					$value['previousPaymentStatus'] = preg_replace('/\s+/','',$value['previousPaymentStatus']);

					$recordDate->setTimestamp($this->CI->time->ROCDateToUnixTimestamp($value['date']));
					if(preg_match('/不須繳款|^全額繳清.*無遲延$/',$value['previousPaymentStatus']) && $creditLogCountStatus){
						if($recordDate->format('Y-m') != $currentRecordDate) {
							$res['creditLogCount'] += 1;
							$currentRecordDate = $recordDate->format('Y-m');
						}
					}else{
						if(preg_match('/.*遲延.*個月$|.*遲延.*個月以上$/',$value['previousPaymentStatus'])){
							$res['creditCardHasDelay'] = '有';
						}
						if(preg_match('/逾期|催收|呆帳/',$value['claimsStatus'])){
							$res['creditCardHasBadDebt'] = '有';
						}

						$creditLogCountStatus = 0;
					}

					// 是否預借現金
					if($value['cashAdvanced'] != '無'){
						$res['cashAdvanced'] = '有';
					}

					// 延遲未滿一個月次數
					$value['previousPaymentStatus'] = mb_convert_kana($value['previousPaymentStatus'], 'n');
					if(preg_match('/遲延未滿1個月|遲延未滿１個月/',$value['previousPaymentStatus'])){
						$res['delayLessMonth'] += 1;
					}else if(preg_match('/遲延未滿[0-9]+個月|遲延[0-9]+個月以上/',$value['previousPaymentStatus'])){
						$res['delayMoreMonth'] += 1;
					}

					if($end_date_before && preg_match('/[0-9]{3}\/[0-9]{2}\/[0-9]{2}/',$value['date'])){
						$value['date'] = $this->CI->time->ROCDateToUnixTimestamp($value['date']);
						if($end_date_before < $value['date']){
							$value['quotaAmount'] = preg_replace('/\,|元/','',$value['quotaAmount']);
							$value['currentAmount'] = preg_replace('/\,|元/','',$value['currentAmount']);
							$value['nonExpiredAmount'] = preg_replace('/\,|元/','',$value['nonExpiredAmount']);
							if(is_numeric($value['quotaAmount']) && is_numeric($value['currentAmount']) && is_numeric($value['nonExpiredAmount'])){
								// 同一間銀行的額度皆相同，多張信用卡只需算一次額度
								if(!in_array($value['bank'],$bank_array)){
									$bank_array[] = $value['bank'];
									$totalQuota += $value['quotaAmount'];
								}
							}
						}
					}
				}

				// 信用卡使用率
				if($totalQuota != 0) {
					$totalAmount = intval(preg_replace('/\,|千元/','',$res['creditCard_totalAmount']));
					$res['creditCardUseRate'] = round($totalAmount/$totalQuota*100, 2);
				}
			}
			// 被查詢記錄
			$mappingInstitutionList = ['中信' => '中國信託'];
			if(!empty($data['S1']['dataList'])){
				$institution_array = [];
				foreach($data['S1']['dataList'] as $key => $value){
					if(preg_match('/新業務申請/',$value['reason']) && preg_match('/信貸|信用貸款|個金|個人金融|消金|消費金融|風控|風險控管|審查|授信|放款/',$value['institution'])){
						$institution_name = preg_replace('/銀.*/',"",$value['institution']);
						if(array_key_exists($institution_name, $mappingInstitutionList)) {
							$institution_name = $mappingInstitutionList[$institution_name];
						}
						if(! in_array($institution_name,$institution_array)){
							$institution_array[] = $institution_name;
							$res['S1Count'] += 1;
						}
					}
				}
			}

			// 當事人查詢查詢信用報告紀錄
			if(!empty($data['S2']['dataList'])){
				$res['S2Count'] = count($data['S2']['dataList']);
			}

			// 評分原因
			if(! empty($data['companyCreditScore']['noCommentReason'])){
				$res['commentReason'] = $data['companyCreditScore']['noCommentReason'];
			}

			// A11
			$taxid_array = [];
			if(!empty($data['extraA11']['part1']['dataList'])){
				foreach($data['extraA11']['part1']['dataList'] as $v){
					if($v['registerStatus'] == ''){
						$taxid_array[] = $v['taxId'];
					}
				}
			}
			if(!empty($data['extraA11']['part2']['dataList'])){
				foreach($data['extraA11']['part2']['dataList'] as $v){
					if($v['registerStatus'] == ''){
						$taxid_array[] = $v['taxId'];
					}
				}
			}
			if(!empty($data['extraA11']['part3']['dataList'])){
				foreach($data['extraA11']['part3']['dataList'] as $v){
					if($v['registerStatus'] == ''){
						$taxid_array[] = $v['taxId'];
					}
				}
			}
			if($taxid_array){
				$res['A11TaxId'] = implode(',',$taxid_array);
			}
		}

		return $res;
	}

	/**
	 * [transformJointCreditToMeta 聯徵 result 轉 meta data]
	 * @param  array  $result    [聯徵 result]
	 * @return array  $meta_data [user meta data]
	 * (
	 *  [printDatetime] => 印表時間
	 *  [scoreComment] => 信用評分
	 *  [totalAmountShort] => 短期放款金額
	 *  [totalAmountMid] => 中期放款金額
	 *  [totalAmountShortMonth] => 短期放款時間
	 *  [totalAmountMidMonth] => 中期放款時間
	 *  [totalAmountLong] => 長期放款金額
	 *  [totalAmountShortAssure] => 短期擔保放款金額
	 *  [totalAmountMidAssure] => 中期擔保放款金額
	 *  [totalAmountLongAssure] => 長期擔保放款金額
	 *  [totalAmountCreditCard] => 信用卡借款金額
	 *  [totalAmountCash] => 現金卡借款金額
	 * )
	 */
	// to do : 先以普匯微企e秒貸為主
	// 需增加聯徵 UI顯示需求相關數值
	public function transformJointCreditToMeta($result=[]){
		$meta_data =[];
		if($result){
			$meta_data = array_reduce($result, 'array_merge', array());
			unset($meta_data['action_user']);
			unset($meta_data['send_time']);
			unset($meta_data['status']);
		}
		return $meta_data;
	}

    /**
     * [transformProfileToMeta 個人資料表 result 轉 meta data]
     * @param  array  $result    [個人資料表 result]
     * @return array  $meta_data [user meta data]
     */
    // to do : 先以普匯微企e秒貸為主
    // 需增加聯徵 UI顯示需求相關數值
    public function transformProfileToMeta($result=[]){
        $meta_data =[];
        if($result){
            $meta_data = array_reduce($result, 'array_merge', array());
            unset($meta_data['action_user']);
            unset($meta_data['send_time']);
            unset($meta_data['status']);
        }
        return $meta_data;
    }

    /**
     * [transformProfilejudicialToMeta 公司資料表 result 轉 meta data]
     * @param  array  $result    [公司資料表 result]
     * @return array  $meta_data [user meta data]
     */
    // to do : 先以普匯微企e秒貸為主
    // 需增加聯徵 UI顯示需求相關數值
    public function transformProfilejudicialToMeta($result=[]){
        $meta_data =[];
        if($result){
            $meta_data = array_reduce($result, 'array_merge', array());
            unset($meta_data['action_user']);
            unset($meta_data['send_time']);
            unset($meta_data['status']);
        }
        return $meta_data;
    }

	/**
	 * [transformJobToResult 工作認證資料轉 result]
	 * @param  array  $data [勞保異動明細解析資料]
	 * @return array  $res
	 * (
	 *   [name] => 投保人姓名
	 *   [person_id] => 身分證字號
	 *   [report_date] => 截至印表日期
	 *   [last_insurance_info] => 現在投保公司資訊
	 *   [total_count] => 總工作年資
	 *   [this_company_count] => 現在投保工作年資
	 * )
	 */
	public function transformJobToResult($data=[]){
		$res = [
			'name' => '',
			'person_id' => '',
			'report_date' => '',
			'last_insurance_info' => [],
			'total_count' => 0,
			'this_company_count' => 0,
		];
		$total_count_end_date = '';
		$total_count_start_date = '';
		$has_job = false;
        $company_list = [];
        $last_insurance_info = [];
        $previous_end_date_null = false;

		if(!empty($data['pageList'])){
            $this->CI->load->library('mapping/time');
			$res['name'] = isset($data['pageList'][0]['name']) ? $data['pageList'][0]['name'] : '';
			$res['person_id'] = isset($data['pageList'][0]['personId']) ? $data['pageList'][0]['personId'] : '';
			$res['report_date'] = isset($data['pageList'][0]['reportDate']) ? $data['pageList'][0]['reportDate'] : '';

            // 找總年資起始日期
            foreach($data['pageList'] as $key => $page_info){
                if(isset($page_info['insuranceList'])){
                    foreach($page_info['insuranceList'] as $page_info_key => $page_info_value){
                        if(! preg_match('/部分工時/',$page_info_value['detailList'][0]['comment']) && preg_match('/[0-9]{7}/',$page_info_value['detailList'][0]['startDate'])){
                            $time = substr($page_info_value['detailList'][0]['startDate'], 0,3).'/'.substr($page_info_value['detailList'][0]['startDate'], 3,2).'/'.substr($page_info_value['detailList'][0]['startDate'], 5);
                            $time = $this->CI->time->ROCDateToUnixTimestamp($time);
                            $total_count_start_date = date_create(date('Ymd',$time));
                            break 2;
                        }
                    }
                }
            }
            // 總年資結束日期
            if(preg_match('/[0-9]{7}/',$res['report_date'])){
				$time = substr($res['report_date'], 0,3).'/'.substr($res['report_date'], 3,2).'/'.substr($res['report_date'], 5);
				$time = $this->CI->time->ROCDateToUnixTimestamp($time);
				$total_count_end_date = date_create(date('Ymd',$time));
			}
			// 工作總年資
			if(!empty($total_count_end_date) &&  !empty($total_count_start_date)){
				$diff=date_diff($total_count_start_date,$total_count_end_date);
				$res['total_count'] += $diff->format("%y")*12;
				$res['total_count'] += $diff->format("%m");
			}

            // 全公司
            foreach($data['pageList'] as $key => $page_info){
                if(isset($page_info['insuranceList'])){
                    foreach($page_info['insuranceList'] as $page_info_key => $page_info_value){
                        if(preg_match('/[0-9]{7}/',$page_info_value['detailList'][0]['startDate']) && (preg_match('/[0-9]{7}/',$page_info_value['detailList'][0]['endDate']) || $page_info_value['detailList'][0]['endDate'] == '')){
                            $insurance_id = $page_info_value['insuranceId'];
                            if(isset($company_list[$insurance_id])){
                                if($company_list[$insurance_id]['startDate'] < $page_info_value['detailList'][0]['startDate']){
                                    $company_list[$insurance_id]['companyName'] = $page_info_value['companyName'];
                                    $company_list[$insurance_id]['insuranceSalary'] = $page_info_value['detailList'][0]['insuranceSalary'];
                                    $company_list[$insurance_id]['startDate'] = $page_info_value['detailList'][0]['startDate'];
                                    $company_list[$insurance_id]['endDate'] = $page_info_value['detailList'][0]['endDate'];
                                    $company_list[$insurance_id]['comment'] = $page_info_value['detailList'][0]['comment'];
                                    $company_list[$insurance_id]['arrearage'] = $page_info_value['detailList'][0]['arrearage'];
                                }
                            }
                            if(!isset($company_list[$insurance_id])){
                                $company_list[$insurance_id]['companyName'] = $page_info_value['companyName'];
                                $company_list[$insurance_id]['insuranceSalary'] = $page_info_value['detailList'][0]['insuranceSalary'];
                                $company_list[$insurance_id]['startDate'] = $page_info_value['detailList'][0]['startDate'];
                                $company_list[$insurance_id]['endDate'] = $page_info_value['detailList'][0]['endDate'];
                                $company_list[$insurance_id]['comment'] = $page_info_value['detailList'][0]['comment'];
                                $company_list[$insurance_id]['arrearage'] = $page_info_value['detailList'][0]['arrearage'];

                            }
                        }
                    }
                }
            }
            // 是否有公司投保
            $keys = array_keys(array_combine(array_keys($company_list), array_column($company_list, 'endDate')),'');
            if(!empty($keys)){
                // 多家公司投保
                if(count($keys)>1){
                	$employed_company_list = array_intersect_key($company_list,array_flip($keys));
                    $numbers = array_column($employed_company_list, 'insuranceSalary');
                    $max = max($numbers);
                    $keys = array_keys(array_combine(array_keys($employed_company_list), $numbers),$max);
                    $insurance_id = $keys[0];
                    $last_insurance_info = $company_list[$insurance_id];
                }
                $has_job = true;
            }else{
                // 找最後投保資訊
                $numbers = array_column($company_list, 'endDate');
                $max = max($numbers);
                $keys = array_keys(array_combine(array_keys($company_list), array_column($company_list, 'endDate')),$max);
            }
            $insurance_id = $keys[0];
            $res['last_insurance_info'] = isset($company_list[$insurance_id]) ? $company_list[$insurance_id] : [];
            $res['last_insurance_info']['insuranceId'] = !empty($insurance_id) ? $insurance_id : '';

            // 現在任職公司年資
            if(!empty($res['last_insurance_info']) && $res['last_insurance_info']['insuranceId'] != '' && $has_job === true){
                foreach($data['pageList'] as $key => $page_info){
                    foreach($page_info['insuranceList'] as $page_info_key => $page_info_value){
                        if(!preg_match('/部分工時/',$page_info_value['detailList'][0]['comment']) && preg_match('/[0-9]{7}/',$page_info_value['detailList'][0]['startDate']) && $res['last_insurance_info']['insuranceId'] == $page_info_value['insuranceId']){
                            if($page_info_value['detailList'][0]['endDate'] == ''){
                                if(!$previous_end_date_null){
                                    $start_time = substr($page_info_value['detailList'][0]['startDate'], 0,3).'/'.substr($page_info_value['detailList'][0]['startDate'], 3,2).'/'.substr($page_info_value['detailList'][0]['startDate'], 5);
                                }
                                // 最後投保日期
                                if(preg_match('/[0-9]{7}/',$res['report_date']) && $res['last_insurance_info']['startDate'] == $page_info_value['detailList'][0]['startDate']){
                                    $end_time = substr($res['report_date'], 0,3).'/'.substr($res['report_date'], 3,2).'/'.substr($res['report_date'], 5);

                                    // 計算年資
                                    if($start_time && $end_time){
                                        $start_time = $this->CI->time->ROCDateToUnixTimestamp($start_time);
                                        $end_time = $this->CI->time->ROCDateToUnixTimestamp($end_time);

                                        $start_time = date_create(date('Ymd',$start_time));
                                        $end_time = date_create(date('Ymd',$end_time));

                                        $diff=date_diff($start_time,$end_time);
                                        $start_time = '';
                                        $end_time = '';
                                        $res['this_company_count'] += $diff->format("%y")*12;
                                        $res['this_company_count'] += $diff->format("%m");
                                    }
                                }
                                $previous_end_date_null = true;
                            }
                            if($page_info_value['detailList'][0]['endDate'] != ''){
                                if(!$previous_end_date_null){
		                            $start_time = substr($page_info_value['detailList'][0]['startDate'], 0,3).'/'.substr($page_info_value['detailList'][0]['startDate'], 3,2).'/'.substr($page_info_value['detailList'][0]['startDate'], 5);
								}
                                $end_time = substr($page_info_value['detailList'][0]['endDate'], 0,3).'/'.substr($page_info_value['detailList'][0]['endDate'], 3,2).'/'.substr($page_info_value['detailList'][0]['endDate'], 5);
								$previous_end_date_null = false;

                                // 計算年資
                                if($start_time && $end_time){
                                    $start_time = $this->CI->time->ROCDateToUnixTimestamp($start_time);
                                    $end_time = $this->CI->time->ROCDateToUnixTimestamp($end_time);

                                    $start_time = date_create(date('Ymd',$start_time));
                                    $end_time = date_create(date('Ymd',$end_time));

                                    $diff=date_diff($start_time,$end_time);
                                    $start_time = '';
                                    $end_time = '';
                                    $res['this_company_count'] += $diff->format("%y")*12;
                                    $res['this_company_count'] += $diff->format("%m");
                                }
                            }
                        }
                    }
                }
            }
		}
		return $res;
	}

	/**
	 * [transformSimplificationjobToMeta 工作資料 result 轉 meta data]
	 * @param  array  $result    [工作資料 result]
	 * @return array  $meta_data [user meta data]
	 */
	public function transformSimplificationjobToMeta($result=[]){
		$meta_data =[];
        if($result){
            $meta_data = array_reduce($result, 'array_merge', array());
            unset($meta_data['action_user']);
            unset($meta_data['send_time']);
            unset($meta_data['status']);
        }
        return $meta_data;
	}

    /**
     * [transformCompanyEmailMeta 公司電子信箱 result 轉 meta data]
     * @param  array  $result    [公司電子信箱 result]
     * @return array  $meta_data [user meta data]
     */
    // to do : 先以普匯微企e秒貸為主
    // 需增加聯徵 UI顯示需求相關數值
    public function transformCompanyEmailMeta($result=[]){
        $meta_data =[];
        if($result){
            $meta_data = array_reduce($result, 'array_merge', array());
        }
        return $meta_data;
    }

    /**
     * 聯徵解析資料 轉存為 還款力計算
     * @param array $investigation_content
     * @return array
     * [
     *     'printDatetime' => '',                       // 印表時間
     *     'totalAmountQuota' => 0,                     // 訂約金額總額度
     *     'longAssure' => 0,                           // 長期擔保放款訂約總金額
     *     'longAssureMonthlyPayment' => 0,             // 長期擔保放款月繳(房貸月繳金額) = 長期擔保放款訂約總金額 * ...
     *     'midAssure' => 0,                            // 中期擔保放款訂約總金額
     *     'midAssureMonthlyPayment' => 0,              // 中期擔保放款月繳(車貸月繳金額) = 中期擔保放款訂約總金額 * ...
     *     'long' => 0,                                 // 長期放款訂約總金額
     *     'longMonthlyPayment' => 0,                   // 長期放款月繳(長期信貸月繳金額) = 長期放款訂約總金額 * ...
     *     'mid' => 0,                                  // 中期放款訂約總金額
     *     'midMonthlyPayment' => 0,                    // 中期放款月繳(中期信貸月繳金額) = 中期放款訂約總金額 * ...
     *     'short' => 0,                                // 短期放款訂約總金額
     *     'shortMonthlyPayment' => 0,                  // 短期放款月繳(短期信貸月繳金額) = 短期放款訂約總金額 * ...
     *     'studentLoans' => 0,                         // 助學貸款訂約總金額
     *     'studentLoansMonthlyPayment' => 0,           // 助學貸款月繳 = 助學貸款訂約總金額 * ...
     *     'studentLoansCount' => 0,                    // 助學貸款總筆數
     *     'creditCard' => 0,                           // 信用卡帳款總餘額
     *     'creditCardMonthlyPayment' => 0,             // 信用卡月繳 = 信用卡帳款總餘額 * ...
     *     'totalMonthlyPayment' => 0,                  // 總共月繳
     *     'liabilitiesWithoutAssureTotalAmount' => '', // 借款總餘額
     *     'totalEffectiveDebt' => 0                    // 信用借款+信用卡+現金卡總餘額
     * ]
     */
    public function transform_joint_credit_to_repayment_capacity(array $investigation_content)
    {
        $group_id = $investigation_content['group_id'];
        $data = $investigation_content['result'][$group_id];
        $print_date_time = $data['printDatetime'] ?? '';
        $total_amount_quota = $data['totalAmountQuota'] ?? 0;
        $long_assure = $data['totalAmountLongAssure'] ?? 0;
        $long_assure_monthly_payment = $this->CI->certification_lib->get_long_assure_monthly_payment($long_assure);
        $mid_assure = $data['totalAmountMidAssure'] ?? 0;
        $mid_assure_monthly_payment = $this->CI->certification_lib->get_mid_assure_monthly_payment($mid_assure);
        $long = $data['totalAmountLong'] ?? 0;
        $long_monthly_payment = $this->CI->certification_lib->get_long_monthly_payment($long);
        $mid = $data['totalAmountMid'] ?? 0;
        $mid_monthly_payment = $this->CI->certification_lib->get_mid_monthly_payment($mid);
        $short = $data['totalAmountShort'] ?? 0;
        $short_monthly_payment = $this->CI->certification_lib->get_short_monthly_payment($short);
        $student_loans = $data['totalAmountStudentLoans'] ?? 0;
        $student_loans_monthly_payment = $this->CI->certification_lib->get_student_loans_monthly_payment(
            $student_loans,
            $data['totalAmountStudentLoansCount'] ?? 0
        );
        $student_loans_count = $data['totalAmountStudentLoansCount'] ?? 0;

        // 信用卡帳款總餘額
        $credit_card_total_amount = $data['creditCard_totalAmount'] ?? '0';
        $credit_card = intval(preg_replace('/\,/','',$credit_card_total_amount));

        $credit_card_monthly_payment = $this->CI->certification_lib->get_credit_card_monthly_payment($credit_card);
        $total_monthly_payment = $long_assure_monthly_payment + $mid_assure_monthly_payment + $long_monthly_payment + $mid_monthly_payment + $short_monthly_payment + $student_loans_monthly_payment + $credit_card_monthly_payment;

        // 至查詢日止借款總餘額
        $total_appropriation_amount = $data['totalAppropriationAmount'] ?? 0;
        $total_repayment_amount = $data['totalRepaymentAmount'] ?? 0;
        $balance_quota = $data['balanceQuota'] ?? 0;
        $liabilities_without_assure_total_amount = $balance_quota + $total_appropriation_amount - $total_repayment_amount;

        // 信用借款+信用卡+現金卡總餘額
        $balance_short_assure = $data['newBalanceShortAssure'] ?? 0;
        $balance_mid_assure = $data['newBalanceMidAssure'] ?? 0;
        $balance_long_assure = $data['newBalanceLongAssure'] ?? 0;
        $balance_assure = $balance_short_assure + $balance_mid_assure + $balance_long_assure;
        $total_effective_debt = $liabilities_without_assure_total_amount - $balance_assure + $credit_card / 1000;

        return [
            'printDatetime' => $print_date_time,
            'totalAmountQuota' => $total_amount_quota,
            'longAssure' => $long_assure,
            'longAssureMonthlyPayment' => $long_assure_monthly_payment,
            'midAssure' => $mid_assure,
            'midAssureMonthlyPayment' => $mid_assure_monthly_payment,
            'long' => $long,
            'longMonthlyPayment' => $long_monthly_payment,
            'mid' => $mid,
            'midMonthlyPayment' => $mid_monthly_payment,
            'short' => $short,
            'shortMonthlyPayment' => $short_monthly_payment,
            'studentLoans' => $student_loans,
            'studentLoansMonthlyPayment' => $student_loans_monthly_payment,
            'studentLoansCount' => $student_loans_count,
            'creditCard' => $credit_card,
            'creditCardMonthlyPayment' => $credit_card_monthly_payment,
            'totalMonthlyPayment' => $total_monthly_payment,
            'liabilitiesWithoutAssureTotalAmount' => $liabilities_without_assure_total_amount * 1000,  // 單位：「千元」->「元」
            'totalEffectiveDebt' => $total_effective_debt * 1000  // 單位：「千元」->「元」
        ];
    }
}
