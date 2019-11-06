<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Joint_credit_lib{
	const BREAKER = "--------------------------------------------------------------------------------";
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
		return $result;
	}

	private function check_bank_loan($text, &$result){
	}

	private function check_overdue_and_bad_debts($text, &$result)
	{
		$content=$this->CI->regex->findPatternInBetween($text, '【逾期、催收或呆帳資訊】', '【主債務債權再轉讓及清償資訊】');
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
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
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
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
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
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
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
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
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
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
		(preg_match("/查資料庫中無/", $content['0'])) ?	$result["messages"][] = [
			"stage" => "credit_card_debts",
			"status" => "success",
			"message" => "信用卡資訊：無"
		] : $this->get_credit_cards_info($content,$credit_date);	
	}
	private function get_credit_date($content)
	{

	}
	private function get_credit_cards_info($content,$credit_date)
	{
		$case =	preg_match("/強制/", $content['0']) ? 'deactivated' : 'need_pending';
		switch ($case) {
			case 'deactivated':
				$result["messages"][] = [
					"stage" => "credit_card_debts",
					"status" => "failure",
					"message" => "信用卡資訊：強制停用或強制停卡"
				];
				break;
			case 'need_pending':
				$count_credit_cards = substr_count($content[0], "使用中");
				if ($count_credit_cards > 0) {
					$used = explode("使用中", $content[0]);
					$size = count($used);
					for ($i = 0; $i < $size - 1; $i++) {
						$amount[] = substr($used[$i], -26, 5);
					}
					$allowedAmount = (int)array_sum($amount);
					$result["messages"][] = [
						"stage" => "credit_card_debts",
						"status" => "pending",
						"message"  => [
							"信用卡使用中張數" => $count_credit_cards,
							"信用卡總額度（元）" => $allowedAmount
						]
					];
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
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
			"stage" => "credit_card_debts",
			"status" => "success",
			"message" => "信用卡債權再轉讓及清償資訊：無"
		] : [
			"stage" => "credit_card_debts",
			"status" => "failure",
			"message"=> "信用卡債權再轉讓及清償資訊：有"
		];

	}

	private function check_browsed_hits($text, &$result){

	}

	private function check_browsed_hits_by_electrical_pay($text, &$result){

	}

	private function check_browsed_hits_by_itself($text, &$result){

	}

	private function check_extra_messages($text, &$result){
		$content=$this->CI->regex->findPatternInBetween($text, '【附加訊息】', '【信用評分】');
		$result["messages"][] = preg_match("/查資料庫中無/", $content['0']) ? [
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

	}
}
