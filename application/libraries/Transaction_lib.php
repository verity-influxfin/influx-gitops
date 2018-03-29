<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/payment_model');
		$this->CI->load->model('transaction/investment_model');
		$this->CI->load->model('transaction/virtual_passbook_model');
		$this->CI->load->model('transaction/frozen_amount_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/virtual_account_model');
    }

	public function recharge($payment_id){
		if($payment_id){
			$payment 	= $this->CI->payment_model->get($payment_id);
			if($payment->status != "1" && $payment->amount > 0  && !empty($payment->virtual_account)){
				$rs = $this->CI->payment_model->update($payment_id,array("status"=>1));
				if($rs){
					$user_account = $this->CI->virtual_account_model->get_by(array("virtual_account"=>$payment->virtual_account));
					if($user_account){
						$bank 			= bankaccount_substr($payment->bank_acc);
						$bank_code		= $bank['bank_code'];
						$bank_account	= $bank['bank_account'];
						$transaction	= array(
							"source"			=> SOURCE_RECHARGE,
							"user_from"			=> $user_account->user_id,
							"bank_code_from"	=> $bank_code,
							"bank_account_from"	=> $bank_account,
							"amount"			=> intval($payment->amount),
							"user_to"			=> $user_account->user_id,
							"bank_code_to"		=> CATHAY_BANK_CODE,
							"bank_account_to"	=> $payment->virtual_account,
						);
						$transaction_id = $this->CI->transaction_model->insert($transaction);
						if($transaction_id){
							$passbook = array(
								"virtual_account"	=> $payment->virtual_account,
								"transaction_id"	=> $transaction_id,
								"amount"			=> intval($payment->amount),
								"remark"			=> $payment->tx_datetime.' paymentID:'.$payment_id,
								"tx_datetime"		=> $payment->tx_datetime,
							);
							$virtual_passbook = $this->CI->virtual_passbook_model->insert($passbook);
							return $virtual_passbook;
						}
					}
				}
			}
		}
		return false;
	}
	
	//取得資金資料
	public function get_virtual_funds($virtual_account=""){
		if($virtual_account){
			$total  = 0;
			$frozen = 0;
			$virtual_passbook 	= $this->CI->virtual_passbook_model->get_many_by(array("virtual_account"=>$virtual_account));
			$frozen_amount 		= $this->CI->frozen_amount_model->get_many_by(array("virtual_account"=>$virtual_account,"status"=>1));
			if($virtual_passbook){
				foreach($virtual_passbook as $key => $value){
					$total = $total + intval($value->amount);
				}
			}
			
			if($frozen_amount){
				foreach($frozen_amount as $key => $value){
					$frozen = $frozen + intval($value->amount);
				}
			}
			
			return array("total"=>$total,"frozen"=>$frozen);
		}
		return false;
	}
}
