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

	public function check_join_credits($userId, $text, &$result){
		$this->setCurrentTime(time());
		if (!$this->is_id_match($userId, $text)) {
			return [
				"status" => "pending",
				"messages" => [
					[
						"stage" => "id_card",
						"status" => "failure",
						"message" => "非本人"
					]
				]
			];
		}
        $result["messages"][] = [
            "stage" => "id_card",
            "status" => "success",
            "message" => "本人"
        ];
        $this->check_report_expirations($text, $result);
        $this->check_report_range($text, $result);
        $this->check_bank_loan($text, $result);
        $this->check_overdue_and_bad_debts($text, $result);
        $this->check_main_debts($text, $result);
        $this->check_extra_debts($text, $result);
        $this->check_extra_transfer_debts($text,$result);
        $this->check_bounced_checks($text, $result);
        $this->check_lost_contacts($text, $result);
        $creditCardInfo = $this->check_credit_cards($text, $result);

        $input = [
			'appliedTime' => $this->get_credit_date($text),
			'allowedAmount' => $creditCardInfo["allowedAmount"]
		];
        $this->check_credit_card_accounts($text, $input, $result);
        $this->check_credit_card_debts($text, $result);
        $this->check_browsed_hits($text, $result);
        $this->check_browsed_hits_by_electrical_pay($text, $result);
        $this->check_browsed_hits_by_itself($text, $result);
        $this->check_extra_messages($text, $result);
        $this->check_credit_scores($text, $result);
//        $this->check_credit_result($text, $result);
		$this->aggregate($result);

		return $result;
	}

	private function getIdCardNumber($text){
		$matches = $this->CI->regex->findPatternInBetween($text, "身分證號：", "【銀行借款資訊】");
		if (!$matches) {
			return;
		}
		$id = $this->CI->regex->replaceEqualBreaker($matches[0]);
		return trim($id);
	}

	public function is_id_match($userId, $text){
		$user = $this->CI->user_model->get($userId);
		$idCardNumber = $this->getIdCardNumber($text);
		return $user && $user->id_number == $idCardNumber && strlen($idCardNumber) > 0;
	}

	public function check_bank_loan($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【銀行借款資訊】', '【逾期、催收或呆帳資訊】');
        $expire = $expire = $this->expire_check($text, $result);
		if ($this->CI->regex->isNoDataFound($content[0])) {
		    $obj = [
                "stage" => "bank_loan",
                "status" => "success",
                "message" => "銀行借款家數：無"
            ];
		    $obj = $this->serExpireFailure($obj, $expire);
			$result["messages"][] = $obj;
			return ;
		}

		if(preg_match("/有無延遲還款/", $content['0'])){
			$content_data=$this->CI->regex->replaceSpacesToSpace($content['0']);
			$content_data= explode(" ", $content_data);
			foreach($content_data as $key => $value){
				if (preg_match("/分行|營業部|北分/", $value)) {
                    $getProportion= $this->get_loan_proportion($content_data[$key + 1], $content_data[$key + 2], $content_data[$key + 3]);
                    $getStudentLoanStatus = $this->get_student_loan($content_data[$key + 2], $content_data[$key + 3]);
                    $getBankname= $this->get_loan_bankname($value,$content_data[$key + 3]);
                    $getMidTermLoan= $this->get_mid_term_loan($content_data[$key + 2],$content_data[$key + 3]);
                    $getLongTermLoanBankname= $this->get_long_term_loan_bankname($value,$content_data[$key + 3]);
					if(!empty($getProportion)){
						$get_proportion[]=$getProportion;
					}
					if(!empty($getBankname)){
						$get_bankname[]=$getBankname;
					}
					if(!empty($getStudentLoanStatus)){
						$get_student_loan[]=$getStudentLoanStatus;
					}
					if(!empty($getMidTermLoan)){
						$get_mid_term_loan[]=$getMidTermLoan;
					}
					if(!empty($getLongTermLoanBankname)){
						$get_long_term_loan_bankname[]=$getLongTermLoanBankname;
					}
				}
			}
            $getStudentLoan = [
                isset($get_student_loan) ? array_sum($get_student_loan) : 0,
                count($get_student_loan)
            ];
            $getCountALLMidTermLoan = isset($get_mid_term_loan) ? array_sum($get_mid_term_loan) : 0;
            $getALLLongTermLoanBankname=(isset($get_long_term_loan_bankname))?$get_long_term_loan_bankname:Null;
            $getCountALLLongTermLoanBank=(!empty($getALLLongTermLoanBankname))?count(array_flip(array_flip($getALLLongTermLoanBankname))):0;


			$getAllBanknameWithoutSchoolLoan=(isset($get_bankname))?$get_bankname:Null;
			$getAllProportion=(isset($get_proportion))?$get_proportion:[0];
			$getCountAllBanknameWithoutSchoolLoan=(!empty($getAllBanknameWithoutSchoolLoan))?count(array_flip(array_flip($getAllBanknameWithoutSchoolLoan))):0;
			$keyword=$this->CI->regex->findPatternInBetween($text, '有無延遲還款', '【逾期、催收或呆帳資訊】');
			if (preg_match("/有/", $keyword[0])) {
			    $obj = [
                    "stage" => "bank_loan",
                    "status" => "failure",
                    "message" => [
                        "有無延遲還款 : 有",
                        "銀行借款家數 : $getCountAllBanknameWithoutSchoolLoan"
                    ],
                    "rejected_message" => [
                        "最近十二個月有無延遲還款 : 有"
                    ]
                ];
                $obj = $this->serExpireFailure($obj, $expire);
                $result["messages"][] = $obj;
			} else {
				$this->get_loan_info($getCountAllBanknameWithoutSchoolLoan,$getAllProportion,$getCountALLLongTermLoanBank,$getCountALLMidTermLoan,$getStudentLoan, $result, $expire);
			}
		}
	}
	private function get_loan_info($getCountAllBanknameWithoutSchoolLoan, $getAllProportion, $getCountALLLongTermLoanBank, $getCountALLMidTermLoan, $getStudentLoan, &$result, $expire)
	{
		$getAllProportion = array_pad($getAllProportion, 3, 0);
		$longTermLoan = "長期放款借款餘額比例 : 0%";
        $getStudentLoanStatusMsg = $getStudentLoan[1] == 0 ? ' 無' : '<br />助學貸款借款餘額 ( 千元 ) 合計 / 筆數 : '  . $getStudentLoan[0] . ' / ' . $getStudentLoan[1] . ' 筆';
        $getCountALLMidTermLoanMsg = '中期借款借款餘額 ( 千元 ) 合計 : ' . $getCountALLMidTermLoan;
		if ($getCountAllBanknameWithoutSchoolLoan > 3) {
			$result["status"]= "failure";
            $obj = [
				"stage" => "bank_loan",
				"status" => "failure",
				"message" => [
					"有無延遲還款 : 無",
					"是否有助學貸款 : ".$getStudentLoanStatusMsg,
					"銀行借款家數 : $getCountAllBanknameWithoutSchoolLoan",
                    $getCountALLMidTermLoanMsg,
					$longTermLoan
				],
				"rejected_message" => [
					"銀行借款家數超過3家"
				]
			];
            $obj = $this->serExpireFailure($obj, $expire);
            $result["messages"][] = $obj;
		} elseif ($getCountAllBanknameWithoutSchoolLoan == 3) {
			$result["status"]= "pending";
            $obj = [
				"stage" => "bank_loan",
				"status" => "pending",
				"message" => [
					"有無延遲還款 : 無",
                    "是否有助學貸款 : ".$getStudentLoanStatusMsg,
                    "銀行借款家數 : $getCountAllBanknameWithoutSchoolLoan",
                    $getCountALLMidTermLoanMsg,
					$longTermLoan
				]
			];
            $obj = $this->serExpireFailure($obj, $expire);
            $result["messages"][] = $obj;
		} else {
			if (in_array(1, $getAllProportion)) {
				$result["status"]= "failure";
                $obj = [
					"stage" => "bank_loan",
					"status" => "failure",
					"message" => [
						"有無延遲還款 : 無",
                        "是否有助學貸款 : ".$getStudentLoanStatusMsg,
                        "銀行借款家數 : $getCountAllBanknameWithoutSchoolLoan",
                        $getCountALLMidTermLoanMsg,
						$longTermLoan
					],
					"rejected_message" => [
						"長期放款的借款餘額等於訂約金額"
					]
				];
                $obj = $this->serExpireFailure($obj, $expire);
                $result["messages"][] = $obj;
				return;
			}

			foreach ($getAllProportion as $key => $value) {
				$is_InStandard[] = ($value < 0.7) ? true : false;
			}
			$is_InStandard=(isset($is_InStandard))?$is_InStandard:0;
			if ((in_array(false, $is_InStandard) == 0) && $getCountAllBanknameWithoutSchoolLoan <= 2) {
                $obj = [
					"stage" => "bank_loan",
					"status" => "success",
					"message" => [
						"有無延遲還款 : 無",
                        "是否有助學貸款 : ".$getStudentLoanStatusMsg,
                        "銀行借款家數 : $getCountAllBanknameWithoutSchoolLoan",
                        $getCountALLMidTermLoanMsg,
						"長期放款家數 : $getCountALLLongTermLoanBank",
					]
				];
				$result["status"]= "success";
                $obj = $this->serExpireFailure($obj, $expire);
                $result["messages"][] = $obj;
				foreach ($getAllProportion as $value) {
					$result["messages"][3]["message"][] = "長期放款借款餘額比例 : " . ($value * 100) . '%';
				}
			} else {
                $obj = [
					"stage" => "bank_loan",
					"status" => "pending",
					"message" => [
						"有無延遲還款 : 無",
                        "是否有助學貸款 : ".$getStudentLoanStatusMsg,
                        "銀行借款家數 : $getCountAllBanknameWithoutSchoolLoan",
                        $getCountALLMidTermLoanMsg,
						"長期放款家數 : $getCountALLLongTermLoanBank",
					]
				];
				$result["status"]= "pending";
                $obj = $this->serExpireFailure($obj, $expire);
                $result["messages"][] = $obj;
				foreach ($getAllProportion as $value) {
					$result["messages"][3]["message"][] = "長期放款借款餘額比例 : " . ($value * 100) . '%';
				}
			}
		}
	}
	private function get_student_loan($value,$subject)
	{
		if ($subject == '助學貸款') {
            $totalAmount = preg_replace('/\D+/', '', $value);;
            return	$totalAmount;
		}
	}

	private function get_loan_bankname($value,$subject)
	{
		if ($subject !== '助學貸款') {
			$bankname = $value;
			return	$bankname;
		}
	}

	private function get_mid_term_loan($value,$subject)
	{
		if (($subject == '中期放款')||($subject == '中期擔保放款')) {
			$totalAmount = preg_replace('/\D+/', '', $value);;
			return	$totalAmount;
		}
	}

	private function get_long_term_loan_bankname($value,$subject)
	{
		if (($subject == '長期放款')||($subject == '長期擔保放款')) {
			$bankname = $value;
			return	$bankname;
		}
	}

	private function get_loan_proportion($contract_money,$balance_of_loans,$subject)
	{
		if (($subject == '長期放款')||($subject == '長期擔保放款')) {
			preg_match('!\d+!', $contract_money, $ContractMoney);
			preg_match('!\d+!', $balance_of_loans, $BalanceOfLoan);
			$proportion = round($BalanceOfLoan[0] / $ContractMoney[0],3);
			return $proportion;
		}
	}

	public function check_overdue_and_bad_debts($text, &$result)
	{
        $expire = $expire = $this->expire_check($text, $result);
		$content = $this->CI->regex->findPatternInBetween($text, '【逾期、催收或呆帳資訊】', '【主債務債權再轉讓及清償資訊】');
        $obj = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "bad_debts",
			"status" => "success",
			"message" => "逾期、催收或呆帳資訊：無"
		] : [
			"stage" => "bad_debts",
			"status" => "failure",
			"message" => "逾期、催收或呆帳資訊：有",
			"rejected_message" => [
				"逾期、催收或呆帳"
			]
		];
        $obj = $this->serExpireFailure($obj, $expire);
        $result["messages"][] = $obj;
	}

	public function check_main_debts($text, &$result){
        $expire = $expire = $this->expire_check($text, $result);
		$content=$this->CI->regex->findPatternInBetween($text, '【主債務債權再轉讓及清償資訊】', '【共同債務\/從債務\/其他債務資訊】');
		$obj = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "main_debts",
			"status" => "success",
			"message" => "主債務債權再轉讓及清償資訊：無"
		] : [
			"stage" => "main_debts",
			"status" => "failure",
			"message" => "主債務債權再轉讓及清償資訊：有",
			"rejected_message" => [
				"逾期、催收或呆帳"
			]
		];
        $obj = $this->serExpireFailure($obj, $expire);
        $result["messages"][] = $obj;
	}

	private function initializeEmptyExtraDebtRows(){
		return [
			'台端' => '',
			"科目" => '',
			'承貸行' => '',
			'未逾期' => '',
			'逾期未還金額' => '',
		];
	}

	private function readExtraDebtRow($content){
		$row = [];
		$rows = [];
		$content = $this->CI->regex->replaceSpacesToSpace($content);
		$content = $this->CI->regex->removeExtraDebtsStopWords($content);
		$elements = explode(" ", $content);

		$iters = count($elements);
		$isIrrelevant = false;
		$prevKey = null;
		for ($i = 0; $i < $iters; $i++) {
			$element = $elements[$i];
			if ($this->CI->regex->isDateTimeFormat($element)) {
				$isIrrelevant = true;
			}
			if (strpos($element, "台端") !== false) {
				$isIrrelevant = false;
				if ($row) {
					$rows[] = $row;
				}
				$row = $this->initializeEmptyExtraDebtRows();
			}
			if ($isIrrelevant) {
				continue;
			}
			if ($prevKey) {
				$row[$prevKey] = $element;
				$prevKey = null;
			}
			$keys = ["台端", "承貸行", "台端", "未逾期", "逾期未還金額", "未逾期餘額"];
			foreach ($keys as $key) {
				if (strpos($element, $key) !== false) {
					$row[$key] = $element;
				}
			}
			if (strpos($element, "科目") !== false) {
				$prevKey = "科目";
			}
		}

		if ($row) {
			$rows[] = $row;
		}
		return $rows;
	}

	public function check_extra_debts($text, &$result){
		//3 15 29
        $expire = $expire = $this->expire_check($text, $result);
		$message = ["stage" => "extra_debts", "status" => "success", "message" => []];
		$matches = $this->CI->regex->findPatternInBetween($text, '【共同債務\/從債務\/其他債務資訊】', '【共同債務\/從債務\/其他債務轉讓資訊】');
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			$message["status"] = "success";
			$message["message"] = self::EXTRA_DEBITS_DATA . "無";
            $obj = $message;
            $obj = $this->serExpireFailure($obj, $expire);
            $result["messages"][] = $obj;
			return;
		}

		$rows = [];
		$commonDebt1 = $this->CI->regex->findPatternInBetween($content, '共同債務', '從債務');
		$commonDebt2 = $this->CI->regex->findPatternInBetween($content, '共同債務', '擔保品提供人');
		$commonDebt3 = $this->CI->regex->findPatternInBetween($content, '共同債務', '');

		$commonDebt = $commonDebt1 ? $commonDebt1 : $commonDebt2 ? $commonDebt2 : $commonDebt3;
		if ($commonDebt) {
			$currentRows = $this->readExtraDebtRow($commonDebt[0]);
			$rows = array_merge($rows, $currentRows);
		}

		$secondaryLiability1 = $this->CI->regex->findPatternInBetween($content, '從債務', '擔保品提供人');
		$secondaryLiability2 = $this->CI->regex->findPatternInBetween($content, '從債務', '');
		$secondaryLiability = $secondaryLiability1 ? $secondaryLiability1 : $secondaryLiability2;
		if ($secondaryLiability) {
			$currentRows = $this->readExtraDebtRow($secondaryLiability[0]);
			$rows = array_merge($rows, $currentRows);
		}

		$collateralProvider = $this->CI->regex->findPatternInBetween($content, '擔保品提供人', '');
		if ($collateralProvider) {
			$currentRows = $this->readExtraDebtRow($secondaryLiability[0]);
			$rows = array_merge($rows, $currentRows);
		}

		if ($rows) {
			foreach ($rows as $row) {
				if (!$row["科目"]) {
					continue;
				}
				if (strpos($row["科目"], '助學貸款') !== false) {
					if (!$this->CI->regex->getZeroOverdueAmount($row["逾期未還金額"])) {
						$message["status"] = "failure";
						$message["rejected_message"][] = "助學貸款，逾期未還金額>0";
					}
				}
				if (
					strpos($row["科目"], '長期擔保') !== false
					|| strpos($row["科目"], '房貸') !== false
					|| strpos($row["科目"], '不動產擔保') !== false
				) {
					$message["status"] = "pending";
				}

				if ($this->CI->regex->isGuarantor($row["台端"])) {
					$message["message"][] = $row["台端"];
				}
				$message["message"][] = "科目：" . $row["科目"];
				$message["message"][] = $row["未逾期餘額"];
			}
		}
        $obj = $message;
        $obj = $this->serExpireFailure($obj, $expire);
        $result["messages"][] = $obj;
	}

	public function check_extra_transfer_debts($text, &$result){
        $expire = $expire = $this->expire_check($text, $result);
		$content=$this->CI->regex->findPatternInBetween($text, '【共同債務\/從債務\/其他債務轉讓資訊】', '【退票資訊】');
        $obj = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "transfer_debts",
			"status" => "success",
			"message" => "共同債務/從債務/其他債務轉讓資訊：無"
		] : [
			"stage" => "transfer_debts",
			"status" => "pending",
			"message" => "共同債務/從債務/其他債務轉讓資訊：有"
		];
        $obj = $this->serExpireFailure($obj, $expire);
        $result["messages"][] = $obj;
	}

	public function check_bounced_checks($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【退票資訊】', '【拒絕往來資訊】');
        $getDay = $this->getDay($content[0]);
        $sevenDays = strtotime("-7 days", strtotime(($result['appliedTime'][0] + 1911).$result['appliedTime'][1].$result['appliedTime'][2]));
        $dataTime = strtotime(($getDay[0] + 1911) . $getDay[1] . $getDay[2]);
		$obj = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "bounced_checks",
			"status" => "success",
			"message" => "退票資訊：無"
		] : [
			"stage" => "bounced_checks",
			"status" => "pending",
			"message" => "退票資訊：有"
		];
		if($dataTime <= $sevenDays || $getDay[0] == ''){
            $obj['rejected_message'] = '非七天內' . ($getDay[0] == '' ? '(資料無日期)' : '');
            $obj['status'] = 'pending';
        }
		else{
            $obj['message'] = (isset($obj['message']) ? $obj['message'] . '<br / >' :''). $getDay[0] . '/' . $getDay[1] . '/' . $getDay[2] .'<br />為七天內';
        }
        $result["messages"][] = $obj;
    }

	public function check_lost_contacts($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【拒絕往來資訊】', '【信用卡資訊】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "lost_contacts",
			"status" => "success",
			"message" => "拒絕往來資訊：無"
		] : [
			"stage" => "lost_contacts",
			"status" => "pending",
			"message" => "拒絕往來資訊：有"
		];
	}

	public function check_credit_cards($text, &$result){
		$credit_date=$this->CI->regex->replaceSpacesToSpace($text);
		$credit_date=$this->CI->regex->findPatternInBetween($credit_date, ' 財團法人金融聯合徵信中心', '其所載信用資訊並非金融機構准駁金融交易之唯一依據');
		$credit_date=substr($credit_date[0], 0, 10);
		$content=$this->CI->regex->findPatternInBetween($text, '【信用卡資訊】', '【信用卡戶帳款資訊】');
		$cards_info =[
			"allowedAmount" => 0,
		];
		$this->CI->regex->isNoDataFound($content[0]) ?	$result["messages"][] = [
			"stage" => "credit_card_debts",
			"status" => "success",
			"message" => "信用卡資訊：無"
		] : $cards_info=$this->get_credit_cards_info($content,$result);
		return 	 $cards_info;
	}

	private function get_credit_date($text)
	{
		$credit_date=$this->CI->regex->replaceSpacesToSpace($text);
		$credit_date=$this->CI->regex->findPatternInBetween($credit_date, ' 財團法人金融聯合徵信中心', '其所載信用資訊並非金融機構准駁金融交易之唯一依據');
		$credit_date=substr($credit_date[0], 0, 19);
		return trim($credit_date);
	}

	public function get_credit_cards_info($content,&$result)
	{
		$case =	preg_match("/強制/", $content['0']) ? 'deactivated' : 'check_in_used';
		switch ($case) {
			case 'deactivated':
				$count_credit_cards = substr_count($content[0], "使用中");
				if ($count_credit_cards > 0) {
					$used = explode("使用中", $content[0]);
					$size = count($used);
					for ($i = 0; $i < $size - 1; $i++) {
						$amount[] = substr($used[$i], -26, 5);
					}
					$allowedAmount = (int) array_sum($amount);
					$result["messages"][] = [
						"stage" => "credit_card_info",
						"status" => "failure",
						"message"  => [
							"信用卡資訊：有",
							"信用卡使用中張數：{$count_credit_cards}",
							"信用卡總額度（仟元）：{$allowedAmount}"
						],
						"rejected_message" => [
							"有強制停用或強制停卡"
						]
					];
				} else {
					$result["messages"][] = [
						"stage" => "credit_card_info",
						"status" => "failure",
						"message" => [
							"信用卡資訊：強制停用或強制停卡",
							"信用卡使用中張數：0",
							"信用卡總額度（仟元）：0"
						]
					];
				}
				break;
			case 'check_in_used':
				$count_credit_cards = substr_count($content[0], "使用中");
				if ($count_credit_cards > 0) {
					$used = explode("使用中", $content[0]);
					$size = count($used);
					$banks = [];
					for ($i = 0; $i < $size - 1; $i++) {
                        $bank = $i == 0 ? preg_replace('/\\n/','',explode(' ', explode('使用狀態', $used[$i])[1])[0]) : preg_replace('/\\n/','',explode(' ', $used[$i])[0]);
						if(!in_array($bank,$banks) && mb_strlen($bank) <= 4){
                            $amount[] = substr($used[$i], -26, 5);
                            $banks[] = $bank;
                        }
					}
					$allowedAmount = (int)array_sum($amount);
					(!(preg_match("/其他/", $content['0'])||preg_match("/側錄/", $content['0'])||preg_match("/掛失/", $content['0'])||preg_match("/不明/", $content['0'])||preg_match("/偽冒/", $content['0'])))?$status='success':$status='pending';
					$result["messages"][] = [
						"stage" => "credit_card_info",
						"status" => $status,
						"message"  => [
							"信用卡資訊：有",
							"信用卡使用中張數：{$count_credit_cards}",
							"信用卡總額度（仟元）：{$allowedAmount}"
						]
					];
					$cards_info = [
						"allowedAmount" => $allowedAmount,
					];
					return $cards_info;
				} else {
					$result["messages"][] = [
						"stage" => "credit_card_info",
						"status" => "pending",
						"message"  => [
							"信用卡資訊：有",
							"信用卡使用中張數：0",
							"信用卡總額度（仟元）：0"
						]
					];
				}
				break;
		}
	}

	public function readRow($template, $row){
		if (!$row) return;

		$template["結帳日"] = $row[0];
		$template["發卡機構"] = $row[1];
		$index = 2;
		if (
			strpos($row[$index], "VISA") !== false
			|| strpos($row[$index], "MASTER") !== false
			|| strpos($row[$index], "JCB") !== false
			|| strpos($row[$index], "AE") !== false
		) {
			$template["卡名"] = $row[$index++];
		}

		$template["額度(千元)"] = $row[$index++];
		$template["預借現金"] = $row[$index++];

		if (!is_numeric($row[$index])) {
			if (!is_numeric($row[$index+2])) {
				$template["結案"] = $row[$index++];
			}

			if (!is_numeric($row[$index+1])) {
				$template["上期繳款狀況"] = $row[$index] . " " . $row[$index+1];
				$index+=2;
			} else {
				$template["上期繳款狀況"] = $row[$index++];
			}
		}

		if (
			!is_numeric($row[$index])
			|| !is_numeric($row[$index+1])
			|| !is_numeric($template["額度(千元)"])
		) {
			return;
		}
		$template["本期應付帳款(元)"] = $row[$index++];
		$template["未到期待付款(元)"] = $row[$index++];

		if (isset($row[$index])) {
			$template["債權狀態"] = $row[$index];
		}

		return $template;
	}

	public function format_credit_card_account_input($creditCardAccounts){
		$template = [];
		$rows = [];
		$rowIndex = 0;
		$length = count($creditCardAccounts);
		for ($i = 1; $i < $length; $i++) {
			$creditCardPayByMonth = $creditCardAccounts[$i];
			$each = $this->CI->regex->replaceSpacesToSpace($creditCardPayByMonth);
			$result = explode(" ", $each);

			if ($i == 1) {
				foreach ($result as $keyName) {
					$template[$keyName] = null;
				}
				continue;
			}

			$currentRow = [];
			foreach ($result as $value) {
				if ($currentRow && $this->CI->regex->isDateTimeFormat($value)) {
					$filledTemplate = $this->readRow($template, $currentRow);
					if ($filledTemplate) {
						$rows[] = $filledTemplate;
						$rowIndex++;
					}
					$currentRow = [];
				}
				$currentRow[] = $value;
			}
			if ($currentRow && $this->CI->regex->isDateTimeFormat($currentRow[0])) {
				$filledTemplate = $this->readRow($template, $currentRow);
				if ($filledTemplate) {
					$rows[] = $filledTemplate;
					$rowIndex++;
				}
			}
		}

		return $rows;
	}

	private function aggregateScores($scores){
		if (!$scores) {
			return 0;
		}

		$sum = 0;
		foreach ($scores as $timeDiff => $score) {
			if ($timeDiff > 6) {
				continue;
			}
			$sum += $score;
		}

		$numScores = count($scores);
		return floatval($sum/$numScores);
	}

	private function cashAdvanceWithDelayInThreshold($cashAdvances, $delays){
		$cashAdvanceWithDelayInThreshold = true;
		for ($i = 0; $i < 12; $i++) {
			if ($cashAdvances[$i] && $delays[$i] > 1) {
				$cashAdvanceWithDelayInThreshold = false;
			}
		}
		return $cashAdvanceWithDelayInThreshold;
	}

	public function check_credit_card_accounts($text, $input, &$result){
		$message = ["stage" => "credit_card_accounts", "status" => "failure", "message" => []];

		$matches = $this->CI->regex->findPatternInBetween($text, "【信用卡戶帳款資訊】", "【信用卡債權再轉讓及清償資訊】");
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			$message["status"] = "success";
			$message["message"] = self::NO_DATA;
			$result["messages"] [] = $message;
			return;
		}

		$creditCardAccounts = explode(self::BREAKER, $content);
		$length = count($creditCardAccounts);
		if ($length <= 2) {
			$message["status"] = "pending";
			$message["message"] = self::NO_DATA;
			$result["messages"][] = $message;
			return;
		}

		$rows = $this->format_credit_card_account_input($creditCardAccounts);

		$scores = array_fill(0, 12, 0);
		$delays = array_fill(0, 12, 0);
		$cashAdvances = array_fill(0, 12, false);
		$numbersOfDelayInAMonth = 0;
		$numbersOfDelayMoreThanAMonth = 0;
		$numbersOfGreatCredit = 0;
		$creditRange = [
			"range" => 0,
			"isGreat" => true,
		];
		$maxTimeDiffFromAppliedAt = null;
		$hasBadCredit = false;
		$doesObtainCashAdvance = false;
		$isDelayPayAndCashAdvance = false;
		$isOverdueOrBadDebits = false;
		$needFurtherInvestigationForFinishedCase = false;
		$allowedAmount = $input["allowedAmount"] * 1000;
		$appliedTimes = explode("/", $input["appliedTime"]);

		foreach ($rows as $row) {
			$isCurrentBadCredit = false;
			$amountDue = $row["結帳日"];
			$amountDue = explode("/", $amountDue);

			$timeDiffFromAppliedAt = (intval($appliedTimes[0]) - intval($amountDue[0])) * 12 + intval($appliedTimes[1]) - intval($amountDue[1]);

			$score = $allowedAmount > 0
				? (intval($row["本期應付帳款(元)"]) + intval($row["未到期待付款(元)"])) / intval($allowedAmount)
				: 0;

			$scores[$timeDiffFromAppliedAt] += $score;
			$delay = $this->CI->regex->getDelayByMonth($row["上期繳款狀況"]);
			if ($delay > 1) {
				$numbersOfDelayMoreThanAMonth++;
			} elseif ($delay == 1) {
				$numbersOfDelayInAMonth++;
				$delays[$timeDiffFromAppliedAt]++;
			}

			if ($maxTimeDiffFromAppliedAt === null) {
				$maxTimeDiffFromAppliedAt = $timeDiffFromAppliedAt;
			}

			if ($maxTimeDiffFromAppliedAt < $timeDiffFromAppliedAt) {
				$maxTimeDiffFromAppliedAt = $timeDiffFromAppliedAt;
				if ($creditRange["isGreat"]) {
					$numbersOfGreatCredit += $creditRange["range"];
					$creditRange["range"] = 0;
					$creditRange["isGreat"] = false;
				}
			}

			if ($maxTimeDiffFromAppliedAt == $timeDiffFromAppliedAt) {
				if ($this->CI->regex->isGreatCredit($row["上期繳款狀況"]) && !$hasBadCredit) {
					$creditRange["range"] = 1;
					$creditRange["isGreat"] = true;
				}
			}

			if (
				$this->CI->regex->onlyPayMinimumOrUnder($row["上期繳款狀況"])
				|| $numbersOfDelayMoreThanAMonth > 0
				|| $numbersOfDelayInAMonth > 0
			) {
				$hasBadCredit = true;
			}

			$cashAdvances[$timeDiffFromAppliedAt] = $row["預借現金"] != "無";
			$doesObtainCashAdvance = $doesObtainCashAdvance || $row["預借現金"] != "無";
			$isDelayPayAndCashAdvance = $isDelayPayAndCashAdvance || $row["預借現金"] != "無" && $delay == 1;
			$isOverdueOrBadDebits = $isOverdueOrBadDebits || $this->CI->regex->isOverdueOrBadDebits($row["債權狀態"]);
			$needFurtherInvestigationForFinishedCase = $needFurtherInvestigationForFinishedCase
				  									   || $this->CI->regex->needFurtherInvestigationForFinishedCase($row["結案"]);
		}

		if ($creditRange["isGreat"]) {
			$numbersOfGreatCredit += $creditRange["range"];
		}

		$message["message"][] = "信用紀錄幾個月：{$numbersOfGreatCredit}";
		$message["message"][] = "當月信用卡使用率：" . round($scores[0] * 100, 2) . "%";
		$message["message"][] = "近一月信用卡使用率：" . round($scores[1] * 100, 2) . "%";
		$message["message"][] = "近二月信用卡使用率：" . round($scores[2] * 100, 2) . "%";
		$message["status"] = "success";
		if ($needFurtherInvestigationForFinishedCase) {
			$message["status"] = "pending";
		}
		if ($numbersOfDelayInAMonth == 2) {
			$message["status"] = "pending";
		} elseif ($numbersOfDelayInAMonth > 2) {
			$message["status"] = "failure";
			$message["rejected_message"][] = "延遲紀錄超過3次";
		}

		if ($doesObtainCashAdvance) {
			$averageScores = $this->aggregateScores($scores);
			$cashAdvanceWithDelayInThreshold = $this->cashAdvanceWithDelayInThreshold($cashAdvances, $delays);
			if ($averageScores < 70 && $cashAdvanceWithDelayInThreshold) {
				$message["status"] = "pending";
			} else {
				$message["status"] = "failure";
				if ($averageScores > 70) {
					$message["rejected_message"][] = "有預借現金且半年信用卡使用率超過70%";
				}
				if (!$cashAdvanceWithDelayInThreshold) {
					$message["rejected_message"][] = "預借現金該月有延遲紀錄";
				}
			}
		}

		if ($numbersOfDelayMoreThanAMonth > 0) {
			$message["status"] = "failure";
			$message["message"][] = self::DELAY_PAYMENT_MORE_THAN_ONE_MONTH . $numbersOfDelayMoreThanAMonth;
		}

		if ($isOverdueOrBadDebits) {
			$message["status"] = "failure";
			$message["rejected_message"][] = "債權狀態：呆帳或催收";
		}

		$message["message"][] = self::DOES_OBTAIN_CASH_ADVANCE . ($doesObtainCashAdvance ? "有" : "無");
		$message["message"][] = self::DELAY_PAYMENT_IN_A_MONTH_EXCEEDED . $numbersOfDelayInAMonth;
		$result["messages"][] = $message;
	}

	public function check_credit_card_debts($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【信用卡債權再轉讓及清償資訊】', '【被查詢紀錄】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "credit_card_debts",
			"status" => "success",
			"message" => "信用卡債權再轉讓及清償資訊：無"
		] : [
			"stage" => "credit_card_debts",
			"status" => "pending",
			"message"=> "信用卡債權再轉讓及清償資訊：有"
		];

	}

	private function readBrowsedRow($content, $record){
		if (count($content) != 3) {
			return $record;
		}

		if ($this->CI->regex->isHoursMinutesSecondsFormat($content[1])) {
			return $record;
		}

		$record["rows"] += 1;
		if ($content[2] == "新業務") {
			$record["patterns"] += 1;
		}

		return $record;
	}

	public function check_browsed_hits($text, &$result){
		$matches = $this->CI->regex->findPatternInBetween($text, '【被查詢紀錄】', '【被電子支付機構及電子票證發行機構查詢紀錄】');
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			$result["messages"] [] = [
				"stage" => "browsed_hits",
				"status" => "success",
				"message" => self::BROWSED_HITS . '0'
			];
			return;
		}

		$contents = explode(self::BREAKER, $content);
		$iters = count($contents);
		$record = ["rows" => 0, "patterns" => 0];
		for ($i = 1; $i < $iters; $i++) {
			$row = $contents[$i];
			$row = $this->CI->regex->replaceEqualBreaker($row);
			$row = $this->CI->regex->replaceSpacesToSpace($row);
			$rowElements = explode(" ", $row);
			$currentElements = [];
			$numElements = count($rowElements);
			for ($i = 3; $i < $numElements; $i++) {
				$rowElement = $rowElements[$i];
				if (
					$currentElements
					&& $rowElement
					&& $this->CI->regex->isDateTimeFormat($rowElement)
				) {
					$record = $this->readBrowsedRow($currentElements, $record);
					$currentElements = [];
				}
				$currentElements[] = $rowElement;
			}
			if ($currentElements && $this->CI->regex->isDateTimeFormat($currentElements[0])) {
				$record = $this->readBrowsedRow($currentElements, $record);
			}
		}
		$message = [
			"stage" => "browsed_hits",
			"status" => "failure",
			"message" => self::BROWSED_HITS . $record["patterns"]
		];
		if ($record["patterns"] <= 3) {
			$message["status"] = "success";
		}
		if ($record["patterns"] > 3 && $record["patterns"] <= 10) {
			$message["status"] = "pending";
		}
		if ($message["status"] == "failure") {
			$message["rejected_message"][] = "新業務之次數>10";
		}
		$result["messages"][] = $message;
	}

	private function readBrowsedByElectricalHitsRow($content, $record){
		if (count($content) <= 2) {
			return $record;
		}

		if ($this->CI->regex->isHoursMinutesSecondsFormat($content[1])) {
			return $record;
		}

		$record["rows"] += 1;

		return $record;
	}

	public function check_browsed_hits_by_electrical_pay($text, &$result){
		$matches = $this->CI->regex->findPatternInBetween($text, '【被電子支付機構及電子票證發行機構查詢紀錄】', '【當事人查詢紀錄】');
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			$result["messages"] [] = [
				"stage" => "browsed_hits_by_electrical_pay",
				"status" => "success",
				"message" => self::BROWSED_HITS_BY_ELECTRICAL_PAY . '0'
			];
			return;
		}

		$contents = explode(self::BREAKER, $content);
		$iters = count($contents);
		$record = ["rows" => 0];
		for ($i = 1; $i < $iters; $i++) {
			$row = $contents[$i];
			$row = $this->CI->regex->replaceEqualBreaker($row);
			$row = $this->CI->regex->replaceSpacesToSpace($row);

			$rowElements = explode(" ", $row);
			$currentElements = [];
			$numElements = count($rowElements);
			for ($i = 2; $i < $numElements; $i++) {
				$rowElement = $rowElements[$i];
				if (
					$currentElements
					&& $rowElement
					&& $this->CI->regex->isDateTimeFormat($rowElement)
				) {
					$record = $this->readBrowsedByElectricalHitsRow($currentElements, $record);
					$currentElements = [];
				}
				$currentElements[] = $rowElement;
			}
			if ($currentElements && $this->CI->regex->isDateTimeFormat($currentElements[0])) {
				$record = $this->readBrowsedByElectricalHitsRow($currentElements, $record);
			}
		}
		$message = [
			"stage" => "browsed_hits_by_electrical_pay",
			"status" => "pending",
			"message" => self::BROWSED_HITS_BY_ELECTRICAL_PAY . $record["rows"]
		];
		if ($record["rows"] <= 2) {
			$message["status"] = "success";
		}

		$result["messages"][] = $message;
	}

	private function readBrowsedByItselfRow($content, $record){
		if (count($content) != 3 && count($content) != 4) {
			return $record;
		}

		if ($this->CI->regex->isHoursMinutesSecondsFormat($content[1])) {
			return $record;
		}

		$record["rows"] += 1;

		return $record;
	}

	public function check_browsed_hits_by_itself($text, &$result){
		$matches = $this->CI->regex->findPatternInBetween($text, '【當事人查詢紀錄】', '【附加訊息】');
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			$result["messages"] [] = [
				"stage" => "browsed_hits_by_itself",
				"status" => "success",
				"message" => self::BROWSED_HITS_BY_ITSELF . '0'
			];
			return;
		}

		$contents = explode(self::BREAKER, $content);
		$iters = count($contents);
		$record = ["rows" => 0];
		for ($i = 1; $i < $iters; $i++) {
			$row = $contents[$i];
			$row = $this->CI->regex->replaceEqualBreaker($row);
			$row = $this->CI->regex->replaceSpacesToSpace($row);
			$rowElements = explode(" ", $row);
			$currentElements = [];
			$numElements = count($rowElements);
			for ($i = 3; $i < $numElements; $i++) {
				$rowElement = $rowElements[$i];
				if (
					$currentElements
					&& $rowElement
					&& $this->CI->regex->isDateTimeFormat($rowElement)
				) {
					$record = $this->readBrowsedByItselfRow($currentElements, $record);
					$currentElements = [];
				}
				$currentElements[] = $rowElement;
			}
			if ($currentElements && $this->CI->regex->isDateTimeFormat($currentElements[0])) {
				$record = $this->readBrowsedByItselfRow($currentElements, $record);
			}
		}
		$message = [
			"stage" => "browsed_hits_by_itself",
			"status" => "pending",
			"message" => self::BROWSED_HITS_BY_ITSELF . $record["rows"]
		];
		if ($record["rows"] <= 2) {
			$message["status"] = "success";
		}

		$result["messages"][] = $message;
	}

	public function check_extra_messages($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【附加訊息】', '【信用評分】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ?  [
			"stage" => "extra_messages",
			"status" => "success",
			"message" => "附加訊息：無"
		] : [
			"stage" => "extra_messages",
			"status" => "failure",
			"message" => "附加訊息：有",
		];
	}

	public function check_credit_scores($text, &$result){
		(preg_match("/信用評分\:/", $text))?$this->get_scores($text,$result): $result["messages"][] = [
				"stage" => "credit_scores",
				"status" => "pending",
				"message" => "信用評分 : 無"
			];
	}
//
//    public function check_credit_result($text, &$result){
//        $content=$this->CI->regex->findPatternInBetween($text, '台端之信用評分位於上述百分位區間之主要原因依序說明如下：', '※本項評分數值係依據本中心資料');
//        $result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ?  [
//            "stage" => "credit_result",
//            "status" => "success",
//            "message" => "附加訊息：無"
//        ] : [
//            "stage" => "credit_result",
//            "status" => "failure",
//            "message" => "附加訊息：有",
//        ];
//    }

    public function get_scores($text, &$result)
	{
		if (preg_match("/台端為給予固定評分/", $text)) {
			$result["messages"][] = [
				"stage" => "credit_scores",
				"status" => "pending",
				"message" => "信用評分 : 200"
			];
		} else if(preg_match("/此次所有受評者中\，有/", $text)){
			$content = $this->CI->regex->findPatternInBetween($text, '信用評分:', '此次所有受評者中，有');
			$content = $this->CI->regex->replaceSpacesToSpace($content[0]);
			$scores = substr($content, 0, 3);
			((int) $scores >= 460) ?
				$result["messages"][] = [
					"stage" => "credit_scores",
					"status" => "success",
					"message" => "信用評分 : " . $scores
				] : $result["messages"][] = [
					"stage" => "credit_scores",
					"status" => "pending",
					"message" => "信用評分 : " . $scores
				];

		}else{
			$result["messages"][] = [
				"stage" => "credit_scores",
				"status" => "pending",
				"message" => "信用評分 : 此次暫時無法評分"
			];
		}
	}

	public function  check_report_expirations($text, &$result){
		$date = $this->get_credit_date($text);
		$dateArray = explode("/", $date);
		$appliedTime = mktime(0, 0, 0, intval($dateArray[1]), intval($dateArray[2]), 1911 + intval($dateArray[0]));
		$thirtyOneDays = 86400 * 31;
        $result["lastmonth"] = [
            intval($dateArray[0]),
            intval($dateArray[1] - ($dateArray[2] > 22 ? 1 : 2))
        ];
        $result["appliedTime"] = [
            $dateArray[0],
            $dateArray[1],
            $dateArray[2],
        ];
        $message = [
            "stage" => "report_expirations",
            "status" => "failure",
            "message" => $date . "<br />文件與申請日超過一個月"
        ];
		if ($this->currentTime - $appliedTime < $thirtyOneDays) {
			$message["status"] = "success";
			$message["message"] = $date . "<br />符合";
		}

		$result["messages"][] = $message;
	}

    public function check_report_range($text, &$result){
        $date = $dateArray = preg_split('/\//',preg_split('/\s/',$this->CI->regex->findPatten($text, '授信最新資料日期為\s\d{3}\/\d{2}\s底')[0])[1]);
        $message = [
            "stage" => "report_range",
            "status" => "failure",
            "message" => $date
        ];
        if ($date) {
            $message["status"] = "success";
            $message["message"] = $date[0] .'年'. $date[1] .'月底'.  "<br />符合";
        }
        $result["messages"][] = $message;
	}

	public function aggregate(&$result){
		if (!$result) {
			$result = ["status" => "pending", "messages" => []];
		}
		foreach ($result["messages"] as $stage) {
			if (!$result["status"]) {
				$result["status"] = "success";
			}
			if ($stage["status"] == "failure") {
				$result["status"] = "failure";
			}
			if ($stage["status"] == "pending" && $result["status"] == "success") {
				$result["status"] = "pending";
			}
		}
		return $result;
	}

	public function setCurrentTime($currentTime){
		$this->currentTime = $currentTime;
	}

	private function getMonth($text){
        return preg_split('/年/',preg_replace('/月底/','',$this->CI->regex->findPatten($text, '\d{3}年\d{2}月底')[0]));
    }

	private function getDay($text){
        return preg_split('/\//', $this->CI->regex->findPatten($text, '\d{3}\/\d{2}\/\d{2}')[0]);
    }

    private function expire_check($text, $result){
        $lastAllowMonth = $this->getMonth($text);
        return $result['lastmonth'][0] < $lastAllowMonth[0] && $result['lastmonth'][1] >= $lastAllowMonth[1] ? '<br / >資料須為' . intval($lastAllowMonth[0]) . '年' . intval($lastAllowMonth[1]) . '月底為最新資訊' : false;
    }

    private function serExpireFailure($obj, $expire){
	    if($expire){
            $obj['status'] = 'failure';
            $obj['rejected_message'][] = (isset($obj['rejected_message']) ? '<br / >' :''). $expire;
        }
        return $obj;
    }
}
