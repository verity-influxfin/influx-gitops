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
		$this->CI->load->library('Financial_lib');
    }

	//儲值
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

	
	//凍結投資款項
	function frozen($target_id){
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 3){
				$investments = $this->CI->investment_model->get_many_by(array("target_id"=>$target->id,"status"=>0,"frozen_status"=>0));
				if($investments){
					dump($investments);
					foreach($investments as $key => $value){
						//$virtual_account = $this->CI->virtual_account_model->get_by(array("user_id"=>$value->user_id,"type"=>0));
						
					}
				}
			}
		}
		return false;
	}

	
	//判斷結標
	function check_bidding($target_id){
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 3){
				$investments = $this->CI->investment_model->order_by("tx_datetime","asc")->get_many_by(array("target_id"=>$target->id));
				if($investments){
					$amount = 0;
					foreach($investments as $key => $value){
						if($value->status ==1 && $value->frozen_status==1){
							$amount += $value->amount;
						}
					}
 
					if($amount >= $target->loan_amount){
						$rs = $this->CI->target_model->update($target_id,array("status"=>4));
						$rs = true;
						if($rs){
							$total = 0;
							$ended = true;
							foreach($investments as $key => $value){
								if($value->status ==1 && $value->frozen_status==1){
									$total += $value->amount;
									$param 	= array(); 
									if($total < $target->loan_amount && $ended){
										$param = array("loan_amount"=>$value->amount,"status"=>2);
									}else if($total>=$target->loan_amount && $ended){
										$param = array("loan_amount"=>$value->amount+$target->loan_amount-$total,"status"=>2); 
										$ended = false;
									}else{
										$param = array("status"=>9);
									}
								}else{
									$param = array("status"=>9);
								}
								$this->CI->investment_model->update($value->id,$param);
							}
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	//放款成功
	function lending_success($target_id,$date=""){
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 4 ){
				$investments = $this->CI->investment_model->get_many_by(array("target_id"=>$target->id,"status"=>2,"loan_amount >"=>0,"frozen_status"=>1));
				if($investments){
					$transaction = array();
					foreach($investments as $key => $value){
						$virtual_account 		= $this->CI->virtual_account_model->get_by(array("user_id"=>$value->user_id,"type"=>0));
						$amortization_schedule 	= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target->instalment,$target->interest_rate,$date);
						if($amortization_schedule && $amortization_schedule["amount"]==$value->loan_amount){
							foreach($amortization_schedule['schedule'] as $instalment_no => $payment){
								$transaction[]	= array(
									"source"			=> SOURCE_AR_PRINCIPAL,
									"user_from"			=> $target->user_id,
									"bank_code_from"	=> CATHAY_BANK_CODE,
									"bank_account_from"	=> $target->virtual_account,
									"amount"			=> intval($payment['principal']),
									"target_id"			=> $target->id,
									"instalment_no"		=> $instalment_no,
									"user_to"			=> $value->user_id,
									"bank_code_to"		=> CATHAY_BANK_CODE,
									"bank_account_to"	=> $virtual_account->virtual_account,
									"limit_date"		=> $payment['repayment_date'],
								);
								
								$transaction[]	= array(
									"source"			=> SOURCE_AR_INTEREST,
									"user_from"			=> $target->user_id,
									"bank_code_from"	=> CATHAY_BANK_CODE,
									"bank_account_from"	=> $target->virtual_account,
									"amount"			=> intval($payment['interest']),
									"target_id"			=> $target->id,
									"instalment_no"		=> $instalment_no,
									"user_to"			=> $value->user_id,
									"bank_code_to"		=> CATHAY_BANK_CODE,
									"bank_account_to"	=> $virtual_account->virtual_account,
									"limit_date"		=> $payment['repayment_date'],
								);
							}
						}
					}
					
					$rs  = $this->CI->transaction_model->insert_many($transaction);
					if($rs && count($rs)==count($transaction)){
						$this->CI->target_model->update($target_id,array("status"=>5));
						$this->CI->investment_model->update_by(array("target_id"=>$target->id,"status"=>2,"loan_amount >"=>0,"frozen_status"=>1),array("status"=>3));
						return true;
					}
				}
			}
		}
		return false;
	}
}
