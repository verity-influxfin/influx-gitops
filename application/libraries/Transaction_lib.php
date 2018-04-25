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
		$this->CI->load->library('Passbook_lib');
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
							$virtual_passbook = $this->CI->passbook_lib->recharge($transaction_id,$payment->tx_datetime);
							return $virtual_passbook;
						}
					}
				}
			}
		}
		return false;
	}
	
	//扣款
	public function charge($transaction_id){
		
	
	}
	
	//取得資金資料
	public function get_virtual_funds($virtual_account=""){
		if($virtual_account){
			$total  = 0;
			$frozen = 0;
			$last_recharge_date	= "";
			$virtual_passbook 	= $this->CI->virtual_passbook_model->get_many_by(array("virtual_account"=>$virtual_account));
			$frozen_amount 		= $this->CI->frozen_amount_model->get_many_by(array("virtual_account"=>$virtual_account,"status"=>1));
			if($virtual_passbook){
				foreach($virtual_passbook as $key => $value){
					$total = $total + intval($value->amount);
					if($value->tx_datetime > $last_recharge_date){
						$last_recharge_date = $value->tx_datetime;
					}
				}
			}
			
			if($frozen_amount){
				foreach($frozen_amount as $key => $value){
					$frozen = $frozen + intval($value->amount);
				}
			}
			
			return array("total"=>$total,"last_recharge_date"=>$last_recharge_date,"frozen"=>$frozen);
		}
		return false;
	}

	
	//判斷結標或凍結投資款項
	function check_bidding($target){
		if( $target && $target->id && $target->status == 3){
			$investments = $this->CI->investment_model->order_by("tx_datetime","asc")->get_many_by(array("target_id"=>$target->id,"status"=>array("0","1")));
			if($investments){
				$amount = 0;
				foreach($investments as $key => $value){
					if($value->status ==1 && $value->frozen_status==1){
						$amount += $value->amount;
					}
				}

				if($amount >= $target->loan_amount){
					//結標
					$rs = $this->CI->target_model->update($target->id,array("status"=>4,"loan_status"=>2));
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
							if($param["status"]==9){
								$this->CI->frozen_amount_model->update_by(array("investment_id"=>$value->id),array("status"=>0));
							}
							$this->CI->investment_model->update($value->id,$param);
						}
						return true;
					}
				}else{
					//凍結款項
					foreach($investments as $key => $value){
						if($value->status ==0 && $value->frozen_status==0){
							$virtual_account = $this->CI->virtual_account_model->get_by(array("type"=>"0","user_id"=>$value->user_id));
							if($virtual_account){
								$funds = $this->get_virtual_funds($virtual_account->virtual_account);
								$total = $funds["total"] - $funds["frozen"];
								if(intval($total)-intval($value->amount)>0){
									$last_recharge_date = strtotime($funds['last_recharge_date']);
									$tx_datetime = $last_recharge_date < $value->created_at?$value->created_at:$last_recharge_date;
									$tx_datetime = date("Y-m-d H:i:s",$tx_datetime);
									$param = array(
										"investment_id"		=> $value->id,
										"virtual_account"	=> $virtual_account->virtual_account,
										"amount"			=> intval($value->amount),
										"tx_datetime"		=> $tx_datetime,
									);
									$rs = $this->CI->frozen_amount_model->insert($param);
									if($rs){
										$this->CI->investment_model->update($value->id,array("frozen_status"=>1,"status"=>1,"tx_datetime"=>$tx_datetime));
									}
								}
							}
						}
					}
					return true;
				}
			}
		}
		return false;
	}
	
	//放款成功
	function lending_success($target_id,$date=""){
		$date = $date?$date:date("Y-m-d");
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 4 && $target->loan_status == 1){
				$user_bankaccount 	= $this->CI->user_bankaccount_model->get_by(array("user_id"=>$target->user_id,"status"=>1,"verify"=>1));
				//手續費
				$transaction_fee	= array(
					"source"			=> SOURCE_FEES,
					"user_from"			=> $target->user_id,
					"bank_code_from"	=> $user_bankaccount->bank_code,
					"bank_account_from"	=> $user_bankaccount->bank_account,
					"amount"			=> intval($target->platform_fee),
					"target_id"			=> $target->id,
					"instalment_no"		=> 0,
					"user_to"			=> 0,
					"bank_code_to"		=> CATHAY_BANK_CODE,
					"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
				);
				
				$fee_transaction_id  = $this->CI->transaction_model->insert($transaction_fee);
				if($fee_transaction_id){
					$this->CI->passbook_lib->platform_fee($fee_transaction_id);
					$investment_ids = array();
					$investments 	= $this->CI->investment_model->get_many_by(array("target_id"=>$target->id,"status"=>2,"loan_amount >"=>0,"frozen_status"=>1));
					if($investments){
						
						//攤還表
						$transaction 	= array();
						foreach($investments as $key => $value){
							$investment_ids[]		= $value->id;
							$virtual_account 		= $this->CI->virtual_account_model->get_by(array("user_id"=>$value->user_id,"type"=>0));
							
							$transaction_lending	= array(
								"source"			=> SOURCE_LENDING,
								"user_from"			=> $value->user_id,
								"bank_code_from"	=> CATHAY_BANK_CODE,
								"bank_account_from"	=> $virtual_account->virtual_account,
								"amount"			=> intval($value->loan_amount),
								"target_id"			=> $target->id,
								"instalment_no"		=> 0,
								"user_to"			=> $target->user_id,
								"bank_code_to"		=> $user_bankaccount->bank_code,
								"bank_account_to"	=> $user_bankaccount->bank_account,
							);
							
							$lending_transaction_id  	= $this->CI->transaction_model->insert($transaction_lending);
							if($lending_transaction_id){
								$this->CI->passbook_lib->lending($lending_transaction_id); 
								$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target->instalment,$target->interest_rate,$date,$target->repayment);
								if($amortization_schedule){
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
						}
						
						$rs  = $this->CI->transaction_model->insert_many($transaction);
						if($rs && count($rs)==count($transaction)){
							$this->CI->target_model->update($target_id,array("status"=>5));
							$this->CI->target_model->update($target_id,array("status"=>5));
							$this->CI->investment_model->update_by(array("id"=>$investment_ids),array("status"=>3));
							$this->CI->frozen_amount_model->update_by(array("investment_id"=>$investment_ids),array("status"=>0));
							return true;
						}
					}
				}
			}
		}
		return false;
	}
	
	//取得最近還款
	function get_next_repayment($target){
		if( $target && $target->id){
			$transactions = $this->CI->transaction_model->order_by("limit_date","asc")->get_by(array("source"=>array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST),"target_id"=>$target->id,"status"=>"1"));
			if($transactions){
				dump($transaction);
			}
		}
		return false;
	}
	
	
	public function script_check_bidding(){
		$this->CI->target_model->update_by(array("status"=>3,"script_status"=>0),array("script_status"=>3));
		$targets = $this->CI->target_model->get_many_by(array("script_status"=>3));
		if($targets && !empty($targets)){
			$count = 0;
			foreach($targets as $key => $value){
				$rs = $this->check_bidding($value);
				if($rs){
					$count++;
				}
				$this->CI->target_model->update($value->id,array("script_status"=>0));
			}
			return $count;
		}
		return false;
	}
}
