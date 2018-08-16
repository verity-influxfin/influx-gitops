<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/investment_model');
		$this->CI->load->model('transaction/frozen_amount_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->library('Financial_lib');
		$this->CI->load->library('Passbook_lib');
    }

	//取得資金資料
	public function get_virtual_funds($virtual_account=""){
		if($virtual_account){
			$total  = 0;
			$frozen = 0;
			$last_recharge_date	= "";
			$this->CI->load->model('transaction/virtual_passbook_model');
			$virtual_passbook 	= $this->CI->virtual_passbook_model->get_many_by(array("virtual_account" => $virtual_account));
			$frozen_amount 		= $this->CI->frozen_amount_model->get_many_by(array("virtual_account" => $virtual_account,"status" => 1));
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
	
	//儲值
	public function recharge($payment_id=0){
		if($payment_id){
			$this->CI->load->model('transaction/payment_model');
			$payment 	= $this->CI->payment_model->get($payment_id);
			if($payment->status != "1" && $payment->amount > 0  && !empty($payment->virtual_account)){
				$rs = $this->CI->payment_model->update($payment_id,array("status"=>1));
				if($rs){
					$user_account = $this->CI->virtual_account_model->get_by(array("virtual_account"=>$payment->virtual_account));
					if($user_account){
						$bank 			= bankaccount_substr($payment->bank_acc);
						$bank_account	= $bank['bank_account'];
						$transaction	= array(
							"source"			=> SOURCE_RECHARGE,
							"entering_date"		=> date("Y-m-d"),
							"user_from"			=> $user_account->user_id,
							"bank_account_from"	=> $bank_account,
							"amount"			=> intval($payment->amount),
							"user_to"			=> $user_account->user_id,
							"bank_account_to"	=> $payment->virtual_account,
							"status"			=> 2
						);
						$transaction_id = $this->CI->transaction_model->insert($transaction);
						if($transaction_id){
							$virtual_passbook = $this->CI->passbook_lib->enter_account($transaction_id,$payment->tx_datetime);
							return $virtual_passbook;
						}
					}
				}
			}
		}
		return false;
	}
	
	//提領
	public function withdraw($user_id,$amount=0){
		if($user_id && $amount > 31 ){
			$investor = 1; 
			$virtual_account = $this->CI->virtual_account_model->get_by(array("status"=>1,"investor"=>$investor,"user_id"=>$user_id));
			if($virtual_account){
				$withdraw = false;
				$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>2));
				$funds = $this->get_virtual_funds($virtual_account->virtual_account);
				$total = $funds["total"] - $funds["frozen"];
				if(intval($total)-intval($amount)>=0){
					$param = array(
						"type"				=> 3,
						"virtual_account"	=> $virtual_account->virtual_account,
						"amount"			=> intval($amount),
						"tx_datetime"		=> date("Y-m-d :H:i:s"),
					);
					$rs = $this->CI->frozen_amount_model->insert($param);
					if($rs){
						$data = array(
							"user_id"			=> $user_id,
							"investor"			=> $investor,
							"virtual_account" 	=> $virtual_account->virtual_account,
							"amount"			=> $amount,
							"frozen_id"			=> $rs,
						);
						$this->CI->load->model('transaction/withdraw_model');
						$withdraw = $this->CI->withdraw_model->insert($data);
					}
				}
				$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>1));
				return $withdraw;
			}
		}
		return false;
	}

	//提領成功
	function withdraw_success($withdraw_id){
		$date 			= get_entering_date();
		$transaction 	= array();
		if($withdraw_id){
			$this->CI->load->model('transaction/withdraw_model');
			$withdraw = $this->CI->withdraw_model->get($withdraw_id);
			if( $withdraw && $withdraw->status == 2 ){
				$virtual_account 	= $this->CI->virtual_account_model->get_by(array("user_id"=>$withdraw->user_id,"virtual_account"=>$withdraw->virtual_account,"status"=>1));
				if($virtual_account){
					$where = array(
						"user_id"	=> $withdraw->user_id,
						"status"	=> 1,
						"verify"	=> 1,
						"investor"	=> $withdraw->investor
					);

					$this->CI->load->model('user/user_bankaccount_model');
					$user_bankaccount 	= $this->CI->user_bankaccount_model->get_by($where);
					if($user_bankaccount){
						$this->CI->load->library('Notification_lib');
						$this->CI->notification_lib->withdraw_success($withdraw->user_id,$withdraw->investor,$withdraw->amount,$user_bankaccount->bank_account);

						//放款
						$transaction		= array(
							"source"			=> SOURCE_WITHDRAW,
							"entering_date"		=> $date,
							"user_from"			=> $withdraw->user_id,
							"bank_account_from"	=> $virtual_account->virtual_account,
							"amount"			=> intval($withdraw->amount),
							"user_to"			=> $withdraw->user_id,
							"bank_account_to"	=> $user_bankaccount->bank_account,
							"status"			=> 2
						);

						$rs  = $this->CI->transaction_model->insert($transaction);
						if($rs){
							$this->CI->withdraw_model->update($withdraw_id,array("status"=>1,"transaction_id"=>$rs));
							$this->CI->frozen_amount_model->update($withdraw->frozen_id,array("status"=>0));
							$this->CI->passbook_lib->enter_account($rs);
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
		$entering_date 	= get_entering_date();
		$date 			= $date?$date:$entering_date;
		$transaction 	= array();
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 4 && $target->loan_status == 1){
				$target_account 	= $this->CI->virtual_account_model->get_by(array("user_id"=>$target->user_id,"investor"=>0,"status"=>1));
				if($target_account){
					$where = array(
						"user_id"	=> $target->user_id,
						"status"	=> 1,
						"verify"	=> 1,
						"investor"	=> 0
					);

					$this->CI->load->model('user/user_bankaccount_model');
					$user_bankaccount 	= $this->CI->user_bankaccount_model->get_by($where);
					if($user_bankaccount){
						$this->CI->load->library('sms_lib');
						$this->CI->sms_lib->lending_success($target->user_id,0,$target->target_no,$target->loan_amount,$user_bankaccount->bank_account);
						$this->CI->load->library('Notification_lib');
						$this->CI->notification_lib->lending_success($target->user_id,0,$target->target_no,$target->loan_amount,$user_bankaccount->bank_account);
						//手續費
						$transaction[]	= array(
							"source"			=> SOURCE_FEES,
							"entering_date"		=> $date,
							"user_from"			=> $target->user_id,
							"bank_account_from"	=> $user_bankaccount->bank_account,
							"amount"			=> intval($target->platform_fee),
							"target_id"			=> $target->id,
							"instalment_no"		=> 0,
							"user_to"			=> 0,
							"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
							"status"			=> 2
						);
						

						$investment_ids = array();
						$frozen_ids 	= array();
						$investments 	= $this->CI->investment_model->get_many_by(array(
							"target_id"		=> $target->id,
							"status"		=> 2,
							"loan_amount >"	=> 0,
							"frozen_status"	=> 1
						));
						if($investments){
							foreach($investments as $key => $value){
								$investment_ids[]	= $value->id;
								$frozen_ids[]		= $value->frozen_id;
								$virtual_account 	= $this->CI->virtual_account_model->get_by(array("user_id"=>$value->user_id,"investor"=>1,"status"=>1));
								$this->CI->notification_lib->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,"");
								$this->CI->sms_lib->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,"");
								
								//放款
								$transaction[]		= array(
									"source"			=> SOURCE_LENDING,
									"entering_date"		=> $date,
									"user_from"			=> $value->user_id,
									"bank_account_from"	=> $virtual_account->virtual_account,
									"amount"			=> intval($value->loan_amount),
									"target_id"			=> $target->id,
									"investment_id"		=> $value->id,
									"instalment_no"		=> 0,
									"user_to"			=> $target->user_id,
									"bank_account_to"	=> $user_bankaccount->bank_account,
									"status"			=> 2
								);

								
								//攤還表
								$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target->instalment,$target->interest_rate,$date,$target->repayment);
								if($amortization_schedule){
									foreach($amortization_schedule['schedule'] as $instalment_no => $payment){
										$transaction[]	= array(
											"source"			=> SOURCE_AR_PRINCIPAL,
											"entering_date"		=> $date,
											"user_from"			=> $target->user_id,
											"bank_account_from"	=> $target_account->virtual_account,
											"amount"			=> intval($payment['principal']),
											"target_id"			=> $target->id,
											"investment_id"		=> $value->id,
											"instalment_no"		=> $instalment_no,
											"user_to"			=> $value->user_id,
											"bank_account_to"	=> $virtual_account->virtual_account,
											"limit_date"		=> $payment['repayment_date'],
										);
										
										$transaction[]	= array(
											"source"			=> SOURCE_AR_INTEREST,
											"entering_date"		=> $date,
											"user_from"			=> $target->user_id,
											"bank_account_from"	=> $target_account->virtual_account,
											"amount"			=> intval($payment['interest']),
											"target_id"			=> $target->id,
											"investment_id"		=> $value->id,
											"instalment_no"		=> $instalment_no,
											"user_to"			=> $value->user_id,
											"bank_account_to"	=> $virtual_account->virtual_account,
											"limit_date"		=> $payment['repayment_date'],
										);
										
										$total 	= intval($payment['interest'])+intval($payment['principal']);
										$ar_fee = intval(round($total/100*REPAYMENT_PLATFORM_FEES,0));
										$transaction[]	= array(
											"source"			=> SOURCE_AR_FEES,
											"entering_date"		=> $date,
											"user_from"			=> $value->user_id,
											"bank_account_from"	=> $virtual_account->virtual_account,
											"amount"			=> $ar_fee,
											"target_id"			=> $target->id,
											"investment_id"		=> $value->id,
											"instalment_no"		=> $instalment_no,
											"user_to"			=> 0,
											"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
											"limit_date"		=> $payment['repayment_date'],
										);
									}
								}
							}
							
							$rs  = $this->CI->transaction_model->insert_many($transaction);
							if($rs && is_array($rs)){
								$this->CI->target_model->update($target_id,array("status"=>5,"loan_date"=>$date));
								$this->CI->investment_model->update_many($investment_ids,array("status"=>3));
								$this->CI->frozen_amount_model->update_many($frozen_ids,array("status"=>0));
								foreach($rs as $key => $value){
									$this->CI->passbook_lib->enter_account($value);
								}
								
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}

	
	//債轉成功
	function transfer_success($transfer_id,$date=""){
		$entering_date 	= get_entering_date();
		$date 			= $date?$date:$entering_date;
		$transaction 	= array();
		if($transfer_id){
			$this->CI->load->model('loan/transfer_model');
			$this->CI->load->model('loan/transfer_investment_model');
			$transfer = $this->CI->transfer_model->get($transfer_id);
			if( $transfer && $transfer->status == 1){
				$target 	= $this->CI->target_model->get($transfer->target_id);
				$investment = $this->CI->investment_model->get($transfer->investment_id);
				if($investment && $investment->status==3 && $target && $target->script_status==0){
					$this->CI->target_model->update($target->id,array("script_status"=>10));
					$transaction_list 	= $this->CI->transaction_model->get_many_by(array("investment_id"=>$investment->id,"status !="=>0));
					$principal			= 0;
					if($transaction_list){
						foreach($transaction_list as $k => $v){
							if($v->source == SOURCE_AR_PRINCIPAL){
								$principal 		+= $v->amount;
							}
							if($v->source == SOURCE_PRINCIPAL){
								$principal 		-= $v->amount;
							}
						}
					}
					
					$transfer_investments 	= $this->CI->transfer_investment_model->get_by(array(
						"transfer_id"	=> $transfer_id,
						"status"		=> 2,
						"frozen_status"	=> 1
					));
						
					if($principal>0 && $transfer_investments){

						$investment_data = array(
							"target_id"		=> $investment->target_id,
							"user_id"		=> $transfer_investments->user_id,
							"amount"		=> intval($principal),
							"loan_amount"	=> intval($principal),
							"contract_id"	=> $transfer_investments->contract_id,
							"status"		=> $investment->status,
						);
						
						$new_investment = $this->CI->investment_model->insert($investment_data);
						if($new_investment){
							
							$this->CI->investment_model->update($investment->id,array("status"=>10,"transfer_status"=>2));
							$this->CI->transfer_investment_model->update($transfer_investments->id,array("status"=>10));
							$this->CI->frozen_amount_model->update($transfer_investments->frozen_id,array("status"=>0));
							
							$transfer_account 	= $this->CI->virtual_account_model->get_by(array("user_id"=>$investment->user_id,"investor"=>1));
							$virtual_account 	= $this->CI->virtual_account_model->get_by(array("user_id"=>$transfer_investments->user_id,"investor"=>1));
							if($transfer_account && $virtual_account){
							
								//手續費
								$transaction[]	= array(
									"source"			=> SOURCE_TRANSFER_FEE,
									"entering_date"		=> $date,
									"user_from"			=> $investment->user_id,
									"bank_account_from"	=> $transfer_account->virtual_account,
									"amount"			=> intval(round($principal*DEBT_TRANSFER_FEES/100,0)),
									"target_id"			=> $target->id,
									"investment_id"		=> $new_investment,
									"instalment_no"		=> 0,
									"user_to"			=> 0,
									"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
									"status"			=> 2
								);
								
								//放款
								$transaction[]		= array(
									"source"			=> SOURCE_TRANSFER,
									"entering_date"		=> $date,
									"user_from"			=> $transfer_investments->user_id,
									"bank_account_from"	=> $virtual_account->virtual_account,
									"amount"			=> intval($principal),
									"target_id"			=> $target->id,
									"investment_id"		=> $new_investment,
									"instalment_no"		=> 0,
									"user_to"			=> $investment->user_id,
									"bank_account_to"	=> $transfer_account->virtual_account,
									"status"			=> 2
								);

								
								//攤還表
								if($transaction_list){
									foreach($transaction_list as $k => $v){
										if($v->status ==1){
											if($v->user_from==$investment->user_id){
												$transaction[]	= array(
													"source"			=> $v->source,
													"entering_date"		=> $date,
													"user_from"			=> $transfer_investments->user_id,
													"bank_account_from"	=> $virtual_account->virtual_account,
													"amount"			=> intval($v->amount - $v->charged_amount),
													"target_id"			=> $v->target_id,
													"investment_id"		=> $new_investment,
													"instalment_no"		=> $v->instalment_no,
													"user_to"			=> $v->user_to,
													"bank_account_to"	=> $v->bank_account_to,
													"limit_date"		=> $v->limit_date,
												);
											}else if($v->user_to==$investment->user_id){
												$transaction[]	= array(
													"source"			=> $v->source,
													"entering_date"		=> $date,
													"user_from"			=> $v->user_from,
													"bank_account_from"	=> $v->bank_account_from,
													"amount"			=> intval($v->amount - $v->charged_amount),
													"target_id"			=> $v->target_id,
													"investment_id"		=> $new_investment,
													"instalment_no"		=> $v->instalment_no,
													"user_to"			=> $transfer_investments->user_id,
													"bank_account_to"	=> $virtual_account->virtual_account,
													"limit_date"		=> $v->limit_date,
												);
											}
											
											if($v->charged_amount>0){
												$this->CI->transaction_model->update($v->id,array("status"=>2));
											}else{
												$this->CI->transaction_model->update($v->id,array("status"=>0));
											}
										}
									}
								}
								
								$rs  = $this->CI->transaction_model->insert_many($transaction);
								if($rs && is_array($rs)){
									$this->CI->transfer_model->update($transfer_id,array("status"=>10,"transfer_date"=>$date,"new_investment"=>$new_investment));
									foreach($rs as $key => $value){
										$this->CI->passbook_lib->enter_account($value);
									}
									return true;
								}
							}
						}
					}
					$this->CI->target_model->update($target->id,array("script_status"=>0));
				}
			}
		}
		return false;
	}
}
