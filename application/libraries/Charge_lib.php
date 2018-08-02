<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Charge_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->library('Passbook_lib');
		$this->CI->load->library('Transaction_lib');
    }
	
	public function charge_normal_target($target){
		$date			= get_entering_date();
		$transaction 	= $this->CI->transaction_model->get_many_by(array(
			"target_id"		=> $target->id,
			"limit_date <=" => $date,
			"status"		=> 1,
			//"user_from"		=> $target->user_id
		));
		if($transaction){
			$amount			= 0;
			$limit_date		= "";
			$source_list 	= array(
				SOURCE_AR_PRINCIPAL,
				SOURCE_AR_INTEREST,
				SOURCE_AR_DAMAGE,
				SOURCE_AR_DELAYINTEREST
			);
			foreach($transaction as $key => $value){
				if(in_array($value->source,$source_list) && $value->user_from==$target->user_id){
					$amount += $value->amount;
				}
			}
			if($amount>0){
				$virtual_account = $this->CI->virtual_account_model->get_by(array(
					"status"			=> 1,
					"investor"			=> 0,
					"user_id"			=> $target->user_id
				));
				if($virtual_account){
					$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>2));
					$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
					$total = $funds["total"] - $funds["frozen"];
					if($total > $amount){
						$transaction_param 	= array();
						$pass_book			= array();
						foreach($transaction as $key => $value){
							$rs = $this->CI->transaction_model->update($value->id,array("status"=>2,"charged_amount"=>$value->amount));
							if($rs){
								$pass_book[] = $value->id;
								switch($value->source){
									case SOURCE_AR_PRINCIPAL: 
										$transaction_param[] = array(
											"source"			=> SOURCE_PRINCIPAL,
											"entering_date"		=> $date,
											"user_from"			=> $value->user_from,
											"bank_account_from"	=> $value->bank_account_from,
											"amount"			=> intval($value->amount),
											"target_id"			=> $value->target_id,
											"investment_id"		=> $value->investment_id,
											"instalment_no"		=> $value->instalment_no,
											"user_to"			=> $value->user_to,
											"bank_account_to"	=> $value->bank_account_to,
											"status"			=> 2
										);
										break;
									case SOURCE_AR_INTEREST: 
										$transaction_param[] = array(
											"source"			=> SOURCE_INTEREST,
											"entering_date"		=> $date,
											"user_from"			=> $value->user_from,
											"bank_account_from"	=> $value->bank_account_from,
											"amount"			=> intval($value->amount),
											"target_id"			=> $value->target_id,
											"investment_id"		=> $value->investment_id,
											"instalment_no"		=> $value->instalment_no,
											"user_to"			=> $value->user_to,
											"bank_account_to"	=> $value->bank_account_to,
											"status"			=> 2
										);
										break;
									case SOURCE_AR_FEES: 
										$transaction_param[] = array(
											"source"			=> SOURCE_FEES,
											"entering_date"		=> $date,
											"user_from"			=> $value->user_from,
											"bank_account_from"	=> $value->bank_account_from,
											"amount"			=> intval($value->amount),
											"target_id"			=> $value->target_id,
											"investment_id"		=> $value->investment_id,
											"instalment_no"		=> $value->instalment_no,
											"user_to"			=> $value->user_to,
											"bank_account_to"	=> $value->bank_account_to,
											"status"			=> 2
										);
										break;
									default:
										break;
								}
							}
						}
						if($transaction_param){
							$rs  = $this->CI->transaction_model->insert_many($transaction_param);
							if($rs){
								foreach($rs as $key => $value){
									$this->CI->passbook_lib->enter_account($value);
								}
								
								foreach($pass_book as $key => $value){
									$this->CI->passbook_lib->enter_account($value);
								}
							}
						}
					}
					$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>1));
					$this->check_finish($target);
					return true;
				}
			}
		}
		return false;
	}

	public function charge_prepayment_target($target,$virtual_account,$settlement_date=""){
		if($target->status == 5 && $settlement_date){
			$date			= get_entering_date();
			if($virtual_account){
				$where 			= array(
					"target_id" => $target->id,
					"status"	=> array(1,2)
				);			
				$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
				if($transaction){
					$last_settlement_date 	= $target->loan_date;
					$user_to_info 			= array();
					$transaction_param 		= array();
					$liquidated_damages		= 0;
					$instalment				= 1;
					foreach($transaction as $key => $value){
						if($value->user_to && $value->user_to!=$target->user_id){
							$user_to_info[$value->user_to] 	= array(
								"bank_account_to"			=> $value->bank_account_to,
								"investment_id"				=> $value->investment_id,
								"total_amount"				=> 0,
								"remaining_principal"		=> 0,
								"interest_payable"			=> 0,
								"delay_interest_payable"	=> 0,
							);
						}
					}
					foreach($transaction as $key => $value){
						switch($value->source){
							case SOURCE_AR_PRINCIPAL: 
								$instalment = $value->status==2?$value->instalment_no:$instalment;
								$user_to_info[$value->user_to]["remaining_principal"]	+= $value->amount;
								break;
							case SOURCE_PRINCIPAL: 
								$user_to_info[$value->user_to]["remaining_principal"]	-= $value->amount;
								break;
							case SOURCE_AR_INTEREST: 
								if($value->limit_date <= $settlement_date){
									$user_to_info[$value->user_to]["interest_payable"]	+= $value->amount;
									if($value->limit_date > $last_settlement_date){
										$last_settlement_date	= $value->limit_date;
									}		
								}
								break;
							case SOURCE_INTEREST: 
								$user_to_info[$value->user_to]["interest_payable"] 		-= $value->amount;
								break;
							case SOURCE_AR_DAMAGE:
								if($value->status==1){
									$liquidated_damages = intval($value->amount) - intval($value->charged_amount);
								}
								break;
							case SOURCE_AR_DELAYINTEREST: 
								$user_to_info[$value->user_to]["delay_interest_payable"]	+= $value->amount;
								break;
							case SOURCE_DELAYINTEREST:
								$user_to_info[$value->user_to]["delay_interest_payable"]	-= $value->amount;
								break;
							default:
								break;
						}
						if($value->status==1){
							if(intval($value->charged_amount)>0){
								$this->CI->transaction_model->update($value->id,array("status"=>2));
							}else{
								$this->CI->transaction_model->update($value->id,array("status"=>0));
							}
						}
					}
					
					if($user_to_info){
						if($target->delay_days > GRACE_PERIOD){
							$days  = get_range_days($date,$settlement_date);
							foreach($user_to_info as $user_to => $value){
								$user_to_info[$user_to]["delay_interest_payable"] += $this->CI->financial_lib->get_delay_interest($value["remaining_principal"],$target->delay_days+$days);
							}
						}else{
							$days  		= get_range_days($last_settlement_date,$settlement_date);
							$leap_year 	= $this->CI->financial_lib->leap_year($target->loan_date,$target->instalment);
							$year_days 	= $leap_year?366:365;//今年日數
							$total_remaining_principal = 0;
							foreach($user_to_info as $user_to => $value){
								$total_remaining_principal 	+= $value["remaining_principal"];
								$user_to_info[$user_to]["interest_payable"] = intval(round( $value["remaining_principal"] * $target->interest_rate / 100 * $days / $year_days ,0));
							}
							$liquidated_damages = $this->CI->financial_lib->get_liquidated_damages($total_remaining_principal);
						}

						$project_source = array(
							"delay_interest_payable"	=> array(SOURCE_AR_DELAYINTEREST,SOURCE_DELAYINTEREST),
							"interest_payable"			=> array(SOURCE_AR_INTEREST,SOURCE_INTEREST),
							"remaining_principal"		=> array(SOURCE_AR_PRINCIPAL,SOURCE_PRINCIPAL),
						);
						foreach($user_to_info as $user_to => $value){
							foreach($project_source as $k => $v){
								$amount = $value[$k];
								if(intval($amount)>0){
									$transaction_param[] = array(
										"source"			=> $v[0],
										"entering_date"		=> $date,
										"user_from"			=> $target->user_id,
										"bank_account_from"	=> $virtual_account->virtual_account,
										"amount"			=> $amount,
										"target_id"			=> $target->id,
										"investment_id"		=> $user_to_info[$user_to]["investment_id"],
										"instalment_no"		=> $instalment,
										"user_to"			=> $user_to,
										"limit_date"		=> $settlement_date,
										"bank_account_to"	=> $user_to_info[$user_to]["bank_account_to"],
										"status"			=> 2
									);
									$transaction_param[] = array(
										"source"			=> $v[1],
										"entering_date"		=> $date,
										"user_from"			=> $target->user_id,
										"bank_account_from"	=> $virtual_account->virtual_account,
										"amount"			=> $amount,
										"target_id"			=> $target->id,
										"investment_id"		=> $user_to_info[$user_to]["investment_id"],
										"instalment_no"		=> $instalment,
										"user_to"			=> $user_to,
										"bank_account_to"	=> $user_to_info[$user_to]["bank_account_to"],
										"status"			=> 2
									);
									$value["total_amount"] += $amount;
								}
							}
							
							if($value["total_amount"]>0){
								$platform_fee	= intval(round($value["total_amount"]/100*REPAYMENT_PLATFORM_FEES,0));
								$transaction_param[] = array(
									"source"			=> SOURCE_FEES,
									"entering_date"		=> $date,
									"user_from"			=> $user_to,
									"bank_account_from"	=> $value["bank_account_to"],
									"amount"			=> $platform_fee,
									"target_id"			=> $target->id,
									"investment_id"		=> $value["investment_id"],
									"instalment_no"		=> $instalment,
									"user_to"			=> 0,
									"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
									"status"			=> 2
								);
								$platform_fee	= intval(round($value["remaining_principal"]/100*PREPAYMENT_ALLOWANCE_FEES,0));
								$transaction_param[] = array(
									"source"			=> SOURCE_PREPAYMENT_ALLOWANCE,
									"entering_date"		=> $date,
									"user_from"			=> 0,
									"bank_account_from"	=> PLATFORM_VIRTUAL_ACCOUNT,
									"amount"			=> $platform_fee,
									"target_id"			=> $target->id,
									"investment_id"		=> $value["investment_id"],
									"instalment_no"		=> $instalment,
									"user_to"			=> $user_to,
									"bank_account_to"	=> $value["bank_account_to"],
									"status"			=> 2
								);
							}
						}

						if(intval($liquidated_damages)>0){
							$transaction_param[] = array(
								"source"			=> SOURCE_PREPAYMENT_DAMAGE,
								"entering_date"		=> $date,
								"user_from"			=> $target->user_id,
								"bank_account_from"	=> $virtual_account->virtual_account,
								"amount"			=> $liquidated_damages,
								"target_id"			=> $target->id,
								"investment_id"		=> 0,
								"instalment_no"		=> $instalment,
								"user_to"			=> 0,
								"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
								"status"			=> 2
							);
						}
						
						if($transaction_param){
							$rs  = $this->CI->transaction_model->insert_many($transaction_param);
							if($rs){
								foreach($rs as $key => $value){
									$this->CI->passbook_lib->enter_account($value);
								}
							}
						}
						$this->check_finish($target);
						return true;
					}
				}
			}
		}
		return false;
	}

	public function check_finish($target){
		if($target->status == 5){
			$where 			= array(
				"target_id" => $target->id,
				"status"	=> 1
			);
			$transaction 	= $this->CI->transaction_model->get_by($where);
			if(!$transaction){
				$this->CI->target_model->update($target->id,array("status"=>10));
				$this->CI->load->model('loan/investment_model');
				$this->CI->investment_model->update_by(array("target_id" => $target->id,"status"=> 3),array("status"=>10));
				return true;
			}
		}
		return false;
	}
	
	public function script_charge_targets(){
		$script  	= 6;
		$count 		= 0;
		$date		= get_entering_date();
		$ids		= array();
		$targets 	= $this->CI->target_model->get_many_by(array(
			"status"			=> 5,//還款中
			"delay_days <="		=> 7,//未逾期
			"sub_status !="		=> 3,
			"script_status"		=> 0
		));
		if($targets && !empty($targets)){
			foreach($targets as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->target_model->update_many($ids,array("script_status"=>$script));
			if($update_rs){
				foreach($targets as $key => $value){
					$transaction = $this->CI->transaction_model->get_by(array(
						"target_id"		=> $value->id,
						"limit_date <=" => $date,
						"status"		=> 1,
						"user_from"		=> $value->user_id
					));
					if($transaction){
						$check = $this->charge_normal_target($value);
						if($check){
							$count++;
						}
					}
					
					$this->CI->target_model->update($value->id,array("script_status"=>0));
				}
			}
		}
		return $count;
	}
	
}
