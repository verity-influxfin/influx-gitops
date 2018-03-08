<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/investment_model');
		$this->CI->load->model('user/user_bankaccount_model');
    }
	
	public function approve_target($target_id=""){
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if(!empty($target) && $target->status=="0"){
				$user_id 	= $target->user_id;
				$product_id = $target->product_id;
				$this->CI->load->library('credit_lib',array("user_id"=>$user_id,"product_id"=>$product_id));
				if($this->CI->credit_lib->check_credit_amount()){
					$loan_amount 	= $this->CI->credit_lib->get_credit_amount();
					$interest_rate 	= $this->CI->credit_lib->get_interest_rate();
					if($loan_amount > $target->amount){
						$loan_amount = $target->amount;
					}
					$total_interest = $this->get_total_interest($loan_amount,$interest_rate,$target->instalment);
					$contract 		= $this->get_target_contract($loan_amount,$interest_rate);
					$bank_account 	= $this->CI->user_bankaccount_model->get_by(array("status"=>1 , "user_id"=>$user_id ));
					if($bank_account){
						$param = array(
							"loan_amount"	=> $loan_amount,
							"interest_rate"	=> $interest_rate, 
							"bank_code"		=> $bank_account->bank_code,
							"bank_account"	=> $bank_account->bank_account,
							"total_interest"=> intval($total_interest),
							"contract"		=> $contract,
							"status"		=> "1",
						);
						$rs = $this->CI->target_model->update($target_id,$param);
						return $rs;
					}
				}
			}
		}
		return false;
	}

	private function get_total_interest($amount,$rate,$instalment){
		return round($amount*$rate*$instalment/1200,0);
	}
	
	private function get_target_contract($amount,$rate){
		return "我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！";
	}
}
