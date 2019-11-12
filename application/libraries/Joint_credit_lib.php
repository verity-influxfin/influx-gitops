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
	const DELAY_PAYMENT_MORE_THAN_ONE_MONTH = "超過一個月延遲繳款 : ";
	const DELAY_PAYMENT_IN_A_MONTH_EXCEEDED = "延遲未滿一個月次數：";

	public function __construct(){
		$this->CI = &get_instance();
		$this->CI->load->library('utility/joint_credit_regex', [], 'regex');
	}

	public function check_join_credits($text, &$result){
		$this->setCurrentTime(time());
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
		$this->check_report_expirations($text, $result);
		print_r($result);
		return $result;
	}

	public function check_bank_loan($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【銀行借款資訊】', '【逾期、催收或呆帳資訊】');
		if ($this->CI->regex->isNoDataFound($content[0])) {
			$result["messages"][] = [
				"stage" => "bank_loan",
				"status" => "success",
				"message" => "銀行借款家數：無"
			];
			return ;
		}elseif(preg_match("/有無延遲還款/", $content['0'])){
			$content_data=$this->CI->regex->replaceSpacesToSpace($content['0']);
			$content_data= explode(" ", $content_data);
			foreach($content_data as $key => $value){
				if (preg_match("/分行/", $value)) {
					$getProportion= $this->get_loan_proportion($content_data[$key + 1], $content_data[$key + 2], $content_data[$key + 3]);
					$getBankname= $this->get_loan_bankname($value,$content_data[$key + 3]);
					if(!empty($getProportion)){
						$get_proportion[]=$getProportion;
					}
					if(!empty($getBankname)){
						$get_bankname[]=$getBankname;
					}
				}
			}
			$getAllBanknameWithoutSchoolLoan=(isset($get_bankname))?$get_bankname:Null;
			$getAllProportion=(isset($get_proportion))?$get_proportion:Null;
			$getCountAllBanknameWithoutSchoolLoan=(!empty($getAllBanknameWithoutSchoolLoan))?count(array_flip(array_flip($getAllBanknameWithoutSchoolLoan))):0;
			$keyword=$this->CI->regex->findPatternInBetween($text, '有無延遲還款', '【逾期、催收或呆帳資訊】');
			(preg_match("/有/", $keyword[0]))?$result["messages"][] = [
				"stage" => "bank_loan",
				"status" => "failure",
				"message" => [
					"最近十二個月有無延遲還款" => '有',
					"銀行借款家數" => $getCountAllBanknameWithoutSchoolLoan
				]
			]:$result=$this->get_loan_info($getCountAllBanknameWithoutSchoolLoan,$getAllProportion);
		}
	}
	private function get_loan_info($getCountAllBanknameWithoutSchoolLoan, $getAllProportion)
	{
		switch ($getCountAllBanknameWithoutSchoolLoan) {
			case ($getCountAllBanknameWithoutSchoolLoan > 3):
				$result["messages"][] = [
					"stage" => "bank_loan",
					"status" => "failure",
					"message" => [
						"銀行借款家數" => $getCountAllBanknameWithoutSchoolLoan
					]
				];
				break;
			case ($getCountAllBanknameWithoutSchoolLoan = 3):
				$result["messages"][] = [
					"stage" => "bank_loan",
					"status" => "failure",
					"message" => [
						"銀行借款家數" => $getCountAllBanknameWithoutSchoolLoan
					]
				];
				break;
			case (($getCountAllBanknameWithoutSchoolLoan < 3)&&($getCountAllBanknameWithoutSchoolLoan >=0)):
				if (in_array(1, $getAllProportion)) {
					$result["messages"][] = [
						"stage" => "bank_loan",
						"status" => "failure",
						"message" => [
							"銀行借款家數" => $getCountAllBanknameWithoutSchoolLoan,
							"長期放款借款餘額比例" => 1
						]
					];
					return;
				} else {
					foreach ($getAllProportion as $key => $value) {
						$is_InStandard[] = ($value < 0.7) ? true : false;
					}
					((in_array(false, $is_InStandard) == 0) && $getCountAllBanknameWithoutSchoolLoan <= 2) ? $result["messages"][] = [
						"stage" => "bank_loan",
						"status" => "success",
						"message" => [
							"銀行借款家數" => $getCountAllBanknameWithoutSchoolLoan,
							"長期放款借款餘額比例" => $getAllProportion
						]
					] : $result["messages"][] = [
						"stage" => "bank_loan",
						"status" => "pending",
						"message" => [
							"銀行借款家數" => $getCountAllBanknameWithoutSchoolLoan,
							"長期放款借款餘額比例" => $getAllProportion
						]
					];
				}
				break;
		}
		return $result;
	}
	private function get_loan_bankname($value,$subject)
	{ 
		if ($subject !== '助學貸款') {
			$bankname = $value;
			return	$bankname;
		}
	}
	
	private function get_loan_proportion($contract_money,$balance_of_loans,$subject)
	{
		if (($subject == '長期放款')||($subject == '長期擔保放款')) {
			preg_match('!\d+!', $contract_money, $ContractMoney);
			preg_match('!\d+!', $balance_of_loans, $BalanceOfLoan);
			$proportion = round($BalanceOfLoan[0] / $ContractMoney[0],2);
			return $proportion;
		}
	}

	public function check_overdue_and_bad_debts($text, &$result)
	{
		$content=$this->CI->regex->findPatternInBetween($text, '【逾期、催收或呆帳資訊】', '【主債務債權再轉讓及清償資訊】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "bad_debts",
			"status" => "success",
			"message" => "逾期、催收或呆帳資訊：無"
		] : [
			"stage" => "bad_debts",
			"status" => "failure",
			"message" => "逾期、催收或呆帳資訊：有"
		]; 
	}

	public function check_main_debts($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【主債務債權再轉讓及清償資訊】', '【共同債務\/從債務\/其他債務資訊】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "main_debts",
			"status" => "success",
			"message" => "主債務債權再轉讓及清償資訊：無"
		] : [
			"stage" => "main_debts",
			"status" => "failure",
			"message" => "主債務債權再轉讓及清償資訊：有"
		]; 
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
			$keys = ["台端", "承貸行", "台端", "未逾期", "逾期未還金額"];
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
		$message = ["stage" => "extra_debts", "status" => "success", "message" => []];
		$matches = $this->CI->regex->findPatternInBetween($text, '【共同債務\/從債務\/其他債務資訊】', '【共同債務\/從債務\/其他債務轉讓資訊】');
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			$message["status"] = "success";
			$message["message"] = self::EXTRA_DEBITS_DATA . "無";
			$result["messages"][] = $message;
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
				$message["message"][] = $row["逾期未還金額"];
			}
		}
		$result["messages"][] = $message;
	}

	public function check_extra_transfer_debts($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【共同債務\/從債務\/其他債務轉讓資訊】', '【退票資訊】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "transfer_debts",
			"status" => "success",
			"message" => "共同債務/從債務/其他債務轉讓資訊：無"
		] : [
			"stage" => "transfer_debts",
			"status" => "failure",
			"message" => "共同債務/從債務/其他債務轉讓資訊：有"
		]; 
	}

	public function check_bounced_checks($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【退票資訊】', '【拒絕往來資訊】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "bounced_checks",
			"status" => "success",
			"message" => "退票資訊：無"
		] : [
			"stage" => "bounced_checks",
			"status" => "failure",
			"message" => "退票資訊：有"
		]; 
	}

	public function check_lost_contacts($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【拒絕往來資訊】', '【信用卡資訊】');
		$result["messages"][] = $this->CI->regex->isNoDataFound($content[0]) ? [
			"stage" => "lost_contacts",
			"status" => "success",
			"message" => "拒絕往來資訊：無"
		] : [
			"stage" => "lost_contacts",
			"status" => "failure",
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
		$credit_date=substr($credit_date[0], 0, 10);
		return trim($credit_date);
	}

	public function get_credit_cards_info($content,&$result)
	{
		$case =	preg_match("/強制/", $content['0']) ? 'deactivated' : 'check_in_used';
		switch ($case) {
			case 'deactivated':
				$result["messages"][] = [
					"stage" => "credit_card_debts",
					"status" => "failure",
					"message" => "信用卡資訊：強制停用或強制停卡"
				];
				break;
			case 'check_in_used':
				$count_credit_cards = substr_count($content[0], "使用中");
				if ($count_credit_cards > 0) {
					$used = explode("使用中", $content[0]);
					$size = count($used);
					for ($i = 0; $i < $size - 1; $i++) {
						$amount[] = substr($used[$i], -26, 5);
					}
					$allowedAmount = (int)array_sum($amount);
					(!(preg_match("/其他/", $content['0'])||preg_match("/側錄/", $content['0'])||preg_match("/掛失/", $content['0'])||preg_match("/不明/", $content['0'])||preg_match("/偽冒/", $content['0'])))?$status='success':$status='pending';
					$result["messages"][] = [
						"stage" => "credit_card_debts",
						"status" => $status,
						"message"  => [
							"信用卡使用中張數" => $count_credit_cards,
							"信用卡總額度（元）" => $allowedAmount
						]
					];
					$cards_info = [
						"allowedAmount" => $allowedAmount,
					];
					return $cards_info;
				} else {
					$result["messages"][] = [
						"stage" => "credit_card_debts",
						"status" => "pending",
						"message"  => [
							"信用卡使用中張數" => 0,
							"信用卡總額度（元）" => 0
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
		$numbersOfDelayInAMonth = 0;
		$numbersOfDelayMoreThanAMonth = 0;
		$doesObtainCashAdvance = false;
		$isDelayPayAndCashAdvance = false;
		$isOverdueOrBadDebits = false;
		$needFurtherInvestigationForFinishedCase = false;
		$allowedAmount = $input["allowedAmount"] * 1000;
		$appliedTimes = explode("/", $input["appliedTime"]);

		foreach ($rows as $row) {
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
			}

			$doesObtainCashAdvance = $doesObtainCashAdvance || $row["預借現金"] != "無";
			$isDelayPayAndCashAdvance = $isDelayPayAndCashAdvance || $row["預借現金"] != "無" && $delay == 1;
			$isOverdueOrBadDebits = $isOverdueOrBadDebits || $this->CI->regex->isOverdueOrBadDebits($row["債權狀態"]);
			$needFurtherInvestigationForFinishedCase = $needFurtherInvestigationForFinishedCase
				  									   || $this->CI->regex->needFurtherInvestigationForFinishedCase($row["結案"]);
		}

		$messages[] = "當月信用卡使用率：" . ($scores[1] * 100) . "%";
		$messages[] = "近一月信用卡使用率：" . ($scores[2] * 100) . "%";
		$messages[] = "近二月信用卡使用率：" . ($scores[3] * 100) . "%";
		if (!$doesObtainCashAdvance) {
			$message["status"] = "success";
		}
		if ($needFurtherInvestigationForFinishedCase) {
			$message["status"] = "pending";
		}
		if ($numbersOfDelayInAMonth == 2) {
			$message["status"] = "pending";
		} elseif ($numbersOfDelayInAMonth > 2) {
			$message["status"] = "failure";
		}

		if ($numbersOfDelayMoreThanAMonth > 0) {
			$message["status"] = "failure";
			$message["message"][] = self::DELAY_PAYMENT_MORE_THAN_ONE_MONTH . $numbersOfDelayMoreThanAMonth;
		}

		if ($isOverdueOrBadDebits) {
			$message["status"] = "failure";
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
			"status" => "failure",
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
		$result["messages"][] = $message;
	}

	private function readBrowsedByElectricalHitsRow($content, $record){
		if (count($content) != 2) {
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
				"stage" => "browsed_hits",
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
			"stage" => "browsed_hits",
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
			"message"=> "附加訊息：有"
		];
	}

	public function check_credit_scores($text, &$result){
		(preg_match("/信用評分\:/", $text))?$this->get_scores($text,$result): $result["messages"][] = [
				"stage" => "credit_scores",
				"status" => "pending",
				"message" => "信用評分 : 無"
			];
	}

	public function get_scores($text, &$result)
	{
		if (preg_match("/台端為給予固定評分/", $text)) {
			$result["messages"][] = [
				"stage" => "get_scores",
				"status" => "pending",
				"message" => "信用評分 : 200"
			];
		} else if(preg_match("/此次所有受評者中\，有/", $text)){
			$content = $this->CI->regex->findPatternInBetween($text, '信用評分:', '此次所有受評者中，有');
			$content = $this->CI->regex->replaceSpacesToSpace($content[0]);
			$scores = substr($content, 0, 3);
			((int) $scores > 540) ?
				$result["messages"][] = [
					"stage" => "get_scores",
					"status" => "success",
					"message" => "信用評分 : " . $scores
				] : $result["messages"][] = [
					"stage" => "get_scores",
					"status" => "pending",
					"message" => "信用評分 : " . $scores
				];
				
		}else{
			$result["messages"][] = [
				"stage" => "get_scores",
				"status" => "pending",
				"message" => "信用評分 : 此次暫時無法評分" 
			];
		}
	}

	public function check_report_expirations($text, &$result){
		$message = ["stage" => "report_expirations", "status" => "failure", "message" => ""];
		$date = $this->get_credit_date($text);
		$dateArray = explode("/", $date);
		$appliedTime = mktime(0, 0, 0, intval($dateArray[1]), intval($dateArray[2]), 1911 + intval($dateArray[0]));
		$thirtyOneDays = 86400 * 31;

		if ($this->currentTime - $appliedTime < $thirtyOneDays) {
			$message["status"] = "success";
		}

		$result["messages"][] = $message;
	}

	public function setCurrentTime($currentTime){
		$this->currentTime = $currentTime;
	}
}
