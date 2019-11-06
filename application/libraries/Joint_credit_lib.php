<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Joint_credit_lib{
	public function check_join_credits($text, &$result){
		$this->check_bank_loan($text, $result);
		$this->check_overdue_and_bad_debts($text, $result);
		$this->check_main_debts($text, $result);
		$this->check_extra_debts($text, $result);
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

	private function check_overdue_and_bad_debts($text, &$result){

	}

	private function check_main_debts($text, &$result){

	}

	private function check_extra_debts($text, &$result){

	}

	private function check_bounced_checks($text, &$result){

	}

	private function check_lost_contacts($text, &$result){

	}

	private function check_credit_cards($text, &$result){

	}

	private function check_credit_card_accounts($text, &$result){
		preg_match();
	}

	private function check_credit_card_debts($text, &$result){

	}

	private function check_browsed_hits($text, &$result){

	}

	private function check_browsed_hits_by_electrical_pay($text, &$result){

	}

	private function check_browsed_hits_by_itself($text, &$result){

	}

	private function check_extra_messages($text, &$result){

	}

	private function check_credit_scores($text, &$result){

	}
}
