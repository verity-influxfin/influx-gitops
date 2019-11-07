<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Joint_credit_lib{
	const BREAKER = "--------------------------------------------------------------------------------";
	const BROWSED_HITS = "被查詢次數：";
	const BROWSED_HITS_BY_ELECTRICAL_PAY = "被電子支付或電子票證發行機構查詢紀錄：";
	const BROWSED_HITS_BY_ITSELF = "當事人查詢紀錄：";

	public function __construct(){
		$this->CI = &get_instance();
		$this->CI->load->library('utility/joint_credit_regex', [], 'regex');
	}

	public function check_join_credits($text, &$result){
		$this->check_bank_loan($text, $result);
		$this->check_overdue_and_bad_debts($text, $result);
		$this->check_main_debts($text, $result);
		$this->check_extra_debts($text, $result);
		$this->check_extra_transfer_debts($text,$result);
		$this->check_bounced_checks($text, $result);
		$this->check_lost_contacts($text, $result);
		$this->check_credit_cards($text, $result);
		$this->check_credit_card_accounts($text, $result);
		$this->check_credit_card_debts($text, $result);
		$this->check_browsed_hits($text, $result);
		$this->check_browsed_hits_by_electrical_pay($text, $result);
		$this->check_browsed_hits_by_itself($text, $result);
		$this->check_extra_messages($text, $result);
		$this->check_credit_scores($text, $result);
		print_r($result);
		return $result;
	}

	private function check_bank_loan($text, &$result){
	}

	private function check_overdue_and_bad_debts($text, &$result)
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

	private function check_main_debts($text, &$result){
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

	private function check_extra_debts($text, &$result){

	}
	private function check_extra_transfer_debts($text, &$result){
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

	private function check_bounced_checks($text, &$result){
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

	private function check_lost_contacts($text, &$result){
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

	private function check_credit_cards($text, &$result){
		$credit_date=$this->CI->regex->replaceSpacesToSpace($text);
		$credit_date=$this->CI->regex->findPatternInBetween($credit_date, ' 財團法人金融聯合徵信中心', '其所載信用資訊並非金融機構准駁金融交易之唯一依據');
		$credit_date=substr($credit_date[0], 0, 10);
		$content=$this->CI->regex->findPatternInBetween($text, '【信用卡資訊】', '【信用卡戶帳款資訊】');
		$cards_info[]=[
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
		return 	 $credit_date;
	}
	private function get_credit_cards_info($content,&$result)
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
					$cards_info[]=[
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

	private function check_credit_card_accounts($text, &$result){
	}

	private function check_credit_card_debts($text, &$result){
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

	private function check_browsed_hits($text, &$result){
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

	private function check_browsed_hits_by_electrical_pay($text, &$result){
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

	private function check_browsed_hits_by_itself($text, &$result){
		$matches = $this->CI->regex->findPatternInBetween($text, '【當事人查詢紀錄】', '【附加訊息】');
		$content = $matches[0];
		if ($this->CI->regex->isNoDataFound($content)) {
			print_r($content);
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

	private function check_extra_messages($text, &$result){
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

	private function check_credit_scores($text, &$result){
		(preg_match("/信用評分\:/", $text))?$this->get_scores($text,$result): $result["messages"][] = [
				"stage" => "credit_scores",
				"status" => "pending",
				"message" => "信用評分 : 無"
			];
	}

	private function get_scores($text, &$result)
	{
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
	}
}
