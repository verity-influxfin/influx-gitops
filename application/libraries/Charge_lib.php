<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Charge_lib
{
	
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
		));
		if($transaction){
			$amount			= 0;
			$limit_date		= "";
			$user_to		= array();
			$source_list 	= array(
				SOURCE_AR_PRINCIPAL,
				SOURCE_AR_INTEREST,
				SOURCE_AR_DAMAGE,
				SOURCE_AR_DELAYINTEREST
			);
			$charge_source_list = array(
				SOURCE_AR_PRINCIPAL		=> SOURCE_PRINCIPAL,
				SOURCE_AR_INTEREST		=> SOURCE_INTEREST,
				SOURCE_AR_DAMAGE		=> SOURCE_DAMAGE,
				SOURCE_AR_DELAYINTEREST	=> SOURCE_DELAYINTEREST,
				SOURCE_AR_FEES			=> SOURCE_FEES,
			);
			foreach($transaction as $key => $value){
				if(in_array($value->source,$source_list) && $value->user_from==$target->user_id){
					$amount += $value->amount;
					if(!isset($user_to[$value->user_to])){
						$user_to[$value->user_to] = 0;
					}
					$user_to[$value->user_to] += $value->amount;
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
					if($total >= $amount){
						$transaction_param 	= array();
						$pass_book			= array();
						foreach($transaction as $key => $value){
							$rs = $this->CI->transaction_model->update($value->id,array("status"=>2));
							if($rs){
								$charge_source 			= $charge_source_list[$value->source];
								$pass_book[] 			= $value->id;
								$transaction_param[] 	= array(
									"source"			=> $charge_source,
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
								
								if($target->delay){
									$update_data = array(
										"delay"		  => 0,
										"delay_days"  => 0
									);

									$this->CI->load->library('Target_lib');
									$this->CI->target_lib->insert_change_log($target->id,$update_data,0,0);
									$this->CI->target_model->update($target->id,$update_data);
								}
								
								$this->CI->load->library('Notification_lib');
								$this->CI->notification_lib->repay_success($target->user_id,0,$target->target_no,$amount);
								foreach($user_to as $user_to_id => $user_to_amount){
									$this->CI->notification_lib->repay_success($user_to_id,1,$target->target_no,$user_to_amount);
								}
							}
						}
						$this->check_finish($target);
					}else{
						$this->notice_delay_target($target);
					}
					
					$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>1));
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
					$instalment_paid		= 0;
					$liquidated_damages		= 0;
					$instalment				= 1;
					$next_instalment 		= true;//下期
					foreach($transaction as $key => $value){
						if($value->source==SOURCE_AR_PRINCIPAL){
							$user_to_info[$value->investment_id] 	= array(
								"user_to"					=> $value->user_to,
								"bank_account_to"			=> $value->bank_account_to,
								"investment_id"				=> $value->investment_id,
								"total_amount"				=> 0,
								"remaining_principal"		=> 0,
								"interest_payable"			=> 0,
								"platform_fee"				=> 0,
							);
						}
						
						if($value->status==2 && $value->source==SOURCE_AR_PRINCIPAL){
							$instalment_paid = $value->instalment_no;
							if($value->limit_date > $last_settlement_date){
								$last_settlement_date	= $value->limit_date;
							}
						}
					}
					$instalment = $instalment_paid + 1;
					foreach($transaction as $key => $value){
						if($value->status==1){
							switch($value->source){
								case SOURCE_AR_PRINCIPAL: 
									$user_to_info[$value->investment_id]["remaining_principal"]	+= $value->amount;
									break;
								case SOURCE_AR_FEES: 
									if($value->limit_date <= $settlement_date){
										$user_to_info[$value->investment_id]["platform_fee"]	+= $value->amount;
										if($value->limit_date == $settlement_date){
											$next_instalment = false;
										}
									}else if($next_instalment && $value->limit_date > $settlement_date && $value->instalment_no==$instalment){
										$user_to_info[$value->investment_id]["platform_fee"]	+= $value->amount;
									}
									break;
								default:
									break;
							}
							$this->CI->transaction_model->update($value->id,array("status"=>0));
						}
					}

					if($user_to_info){
						$days  		= get_range_days($last_settlement_date,$settlement_date);
						$leap_year 	= $this->CI->financial_lib->leap_year($target->loan_date,$target->instalment);
						$year_days 	= $leap_year?366:365;//今年日數
						$total_remaining_principal = 0;
						foreach($user_to_info as $investment_id => $value){
							$total_remaining_principal 	+= $value["remaining_principal"];
							$user_to_info[$investment_id]["interest_payable"] = intval(round( $value["remaining_principal"] * $target->interest_rate / 100 * $days / $year_days ,0));
						}
						$liquidated_damages = $this->CI->financial_lib->get_liquidated_damages($total_remaining_principal,$target->damage_rate);


						$project_source = array(
							"interest_payable"			=> array(SOURCE_AR_INTEREST,SOURCE_INTEREST),
							"remaining_principal"		=> array(SOURCE_AR_PRINCIPAL,SOURCE_PRINCIPAL),
						);
						foreach($user_to_info as $investment_id => $value){
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
										"investment_id"		=> $user_to_info[$investment_id]["investment_id"],
										"instalment_no"		=> $instalment,
										"user_to"			=> $user_to_info[$investment_id]["user_to"],
										"limit_date"		=> $settlement_date,
										"bank_account_to"	=> $user_to_info[$investment_id]["bank_account_to"],
										"status"			=> 2
									);
									$transaction_param[] = array(
										"source"			=> $v[1],
										"entering_date"		=> $date,
										"user_from"			=> $target->user_id,
										"bank_account_from"	=> $virtual_account->virtual_account,
										"amount"			=> $amount,
										"target_id"			=> $target->id,
										"investment_id"		=> $user_to_info[$investment_id]["investment_id"],
										"instalment_no"		=> $instalment,
										"user_to"			=> $user_to_info[$investment_id]["user_to"],
										"bank_account_to"	=> $user_to_info[$investment_id]["bank_account_to"],
										"status"			=> 2
									);
									$value["total_amount"] += $amount;
								}
							}
							
							if($value["total_amount"]>0){
								//回款手續費
								$transaction_param[] = array(
									"source"			=> SOURCE_FEES,
									"entering_date"		=> $date,
									"user_from"			=> $user_to_info[$investment_id]["user_to"],
									"bank_account_from"	=> $value["bank_account_to"],
									"amount"			=> $user_to_info[$investment_id]["platform_fee"],
									"target_id"			=> $target->id,
									"investment_id"		=> $value["investment_id"],
									"instalment_no"		=> $instalment,
									"user_to"			=> 0,
									"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
									"status"			=> 2
								);
								$prepayment_allowance	= intval(round($value["remaining_principal"]/100*PREPAYMENT_ALLOWANCE_FEES,0));//提還補貼金
								$transaction_param[] = array(
									"source"			=> SOURCE_PREPAYMENT_ALLOWANCE,
									"entering_date"		=> $date,
									"user_from"			=> 0,
									"bank_account_from"	=> PLATFORM_VIRTUAL_ACCOUNT,
									"amount"			=> $prepayment_allowance,
									"target_id"			=> $target->id,
									"investment_id"		=> $value["investment_id"],
									"instalment_no"		=> $instalment,
									"user_to"			=> $user_to_info[$investment_id]["user_to"],
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
								"limit_date"		=> $settlement_date,
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
				$this->CI->load->model('loan/investment_model');
				$this->CI->load->library('Target_lib');
				if($target->sub_status==3){
					$this->CI->target_model->update($target->id,array("status"=>10,"sub_status"=>4));
				}else if($target->sub_status==1){
					$this->CI->target_model->update($target->id,array("status"=>10,"sub_status"=>2));
				}else{
					$this->CI->target_model->update($target->id,array("status"=>10));
				}
				$this->CI->investment_model->update_by(array("target_id" => $target->id,"status"=> 3),array("status"=>10));
				$this->CI->target_lib->insert_change_log($target->id,array("status"=>10),0,0);

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
					$transaction = $this->CI->transaction_model->order_by("limit_date","ASC")->get_by(array(
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
					}else{
						$this->notice_normal_target($value);
					}
					
					$this->CI->target_model->update($value->id,array("script_status"=>0));
				}
			}
		}
		return $count;
	}
	
	public function script_prepayment_targets(){
		$script  	= 7;
		$count 		= 0;
		$date		= get_entering_date();
		$ids		= array();
		$targets 	= $this->CI->target_model->get_many_by(array(
			"status"			=> 5,//還款中
			"sub_status"		=> 3,
			"script_status"		=> 0
		));
		if($targets){
			foreach($targets as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->target_model->update_many($ids,array("script_status"=>$script));
			if($update_rs){
				$this->CI->load->library('Target_lib');
				$this->CI->load->library('Prepayment_lib');
				foreach($targets as $key => $value){
					$prepayment = $this->CI->prepayment_lib->get_prepayment($value);
					if($prepayment){
						if($date > $prepayment->settlement_date){
							$update_data = array(
								"script_status"	=> 0,
								"sub_status"	=> 0
							);
							$this->CI->target_lib->insert_change_log($value->id,$update_data,0,0);
							$this->CI->target_model->update($value->id,$update_data);
							$this->CI->load->library('Notification_lib');
							$this->CI->notification_lib->prepay_failed($value->user_id,$value->target_no);
						}else{
							$virtual_account = $this->CI->virtual_account_model->get_by(array(
								"status"	=> 1,
								"investor"	=> 0,
								"user_id"	=> $value->user_id
							));
							if($virtual_account){
								$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>2));
								$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
								$total = $funds["total"] - $funds["frozen"];
								if($total >= $prepayment->amount){
									$this->charge_prepayment_target($value,$virtual_account,$prepayment->settlement_date);
								}else{
									$this->notice_normal_target($value);
								}
								$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>1));
							}
							$this->CI->target_model->update($value->id,array("script_status"=>0));
						}
						$count++;
					}
				}
			}
		}
		return $count;
	}
	
	public function notice_normal_target($target){
		$date			= get_entering_date();
		$next_date		= "";
		$range_days		= 0;
		if($target->handle_date < $date){
			$transaction = $this->CI->transaction_model->order_by("limit_date","ASC")->get_by(array(
				"target_id"		=> $target->id,
				"status"		=> 1,
				"user_from"		=> $target->user_id
			));
			if($transaction){
				$next_date 	= $transaction->limit_date;
				$range_days	= get_range_days($date,$next_date);
				$amount		= 0;
				if(in_array($range_days,array(1,3,7))){
					$transaction = $this->CI->transaction_model->get_many_by(array(
						"target_id"		=> $target->id,
						"status"		=> 1,
						"user_from"		=> $target->user_id,
						"limit_date"	=> $next_date,
					));
					foreach($transaction as $key => $value){
						$amount += $value->amount;
					}
					if($amount){
						$this->CI->load->library('Notification_lib');
						$this->CI->notification_lib->notice_normal_target($target->user_id,$amount,$target->target_no,$next_date);
						if($range_days==1){
							$this->CI->load->library('sms_lib');
							$this->CI->sms_lib->notice_normal_target($target->user_id,$amount,$target->target_no,$next_date);
						}
					}
				}
			}else{
				$this->check_finish($target);
			}
			$this->CI->target_model->update($target->id,array("handle_date"=>$date));
		}
		return false;
	}
	
	public function notice_delay_target($target){
		$date		= get_entering_date();
		$next_date	= "";
		$range_days	= 0;
		$amount		= 0;
		$last_date	= "";
		$delay_days	= 0;
		$update_data= array( "handle_date" => $date );
		if($target->handle_date < $date){
			$transaction = $this->CI->transaction_model->order_by("limit_date","ASC")->get_many_by(array(
				"target_id"		=> $target->id,
				"status"		=> 1,
				"limit_date <=" => $date,
				"user_from"		=> $target->user_id
			));
			if($transaction){
				foreach($transaction as $key => $value){
					$amount += $value->amount;
					if($last_date == "" || $last_date > $value->limit_date){
						$last_date = $value->limit_date;
					}
				}
				
				$delay_days	= get_range_days($last_date,$date);
				if( $delay_days > 0 ){

					if($amount){
						$this->CI->load->library('Notification_lib');
						$this->CI->load->library('sms_lib');
						
						if($delay_days > 0 && in_array($delay_days,array(1,3,7))){
							$this->CI->notification_lib->notice_delay_target($target->user_id,$amount,$target->target_no);
							$this->CI->sms_lib->notice_delay_target($target->user_id,$amount,$target->target_no);
						}
					}
					
					$update_data = array(
						"delay"		  => 1,
						"delay_days"  => $delay_days,
						"handle_date" => $date
					);
					if($target->delay==0){
						$this->CI->load->library('Target_lib');
						$this->CI->target_lib->insert_change_log($target->id,$update_data,0,0);
					}
				}
			}
			$this->CI->target_model->update($target->id,$update_data);
			if($delay_days > GRACE_PERIOD){
				$this->handle_delay_target($target,$delay_days);
			}
			return true;
		}
		return false;
	}
	
	public function handle_delay_target($target,$delay_days=0){
		if($target->status == 5 && $delay_days > GRACE_PERIOD){
			if(in_array($delay_days,array(8,31,61))){
				$this->CI->load->library('credit_lib');
				$level = $this->CI->credit_lib->delay_credit($target->user_id,$delay_days);
				if($level){
					$this->CI->target_model->update($target->id,array("credit_level"=>$level));
				}
			}
			$date			= get_entering_date();
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by(array(
				"target_id" => $target->id,
				"status"	=> 1
			));
			if($transaction){
				$settlement 			= true;
				$user_to_info 			= array();
				$transaction_param 		= array();
				$bank_account_from		= "";
				$liquidated_damages		= 0;
				$instalment				= 1;
				$limit_date				= "";
				foreach($transaction as $key => $value){
					if($value->source==SOURCE_AR_PRINCIPAL){
						if(!isset($user_to_info[$value->investment_id])){
							$user_to_info[$value->investment_id] 	= array(
								"user_to"				=> $value->user_to,
								"bank_account_to"		=> $value->bank_account_to,
								"investment_id"			=> $value->investment_id,
								"remaining_principal"	=> 0,
								"delay_interest"		=> 0,
							);
						}
						$bank_account_from 	= $value->bank_account_from;
						$user_to_info[$value->investment_id]["remaining_principal"]	+= $value->amount;
						if($value->limit_date < $date){
							$instalment = $value->instalment_no;
							$limit_date = $value->limit_date;
						}
					}else if($value->source==SOURCE_AR_DELAYINTEREST){
						$settlement = false;
					}
				}
				
				if($settlement){
					foreach($transaction as $key => $value){
						if($value->limit_date > $date || $value->source==SOURCE_AR_PRINCIPAL){
							$this->CI->transaction_model->update($value->id,array("status"=>0));
						}
					}

					if($user_to_info){
						$total_remaining_principal = 0;
						foreach($user_to_info as $investment_id => $value){
							$user_to_info[$investment_id]["delay_interest"] = $this->CI->financial_lib->get_delay_interest($value["remaining_principal"],$delay_days);
							$total_remaining_principal 	+= $value["remaining_principal"];
						}
						
						$liquidated_damages = $this->CI->financial_lib->get_liquidated_damages($total_remaining_principal,$target->damage_rate);

						foreach($user_to_info as $investment_id => $value){
							$transaction_param[] = array(
								"source"			=> SOURCE_AR_PRINCIPAL,
								"entering_date"		=> $date,
								"user_from"			=> $target->user_id,
								"bank_account_from"	=> $bank_account_from,
								"amount"			=> $value["remaining_principal"],
								"target_id"			=> $target->id,
								"investment_id"		=> $value["investment_id"],
								"instalment_no"		=> $instalment,
								"user_to"			=> $value["user_to"],
								"limit_date"		=> $limit_date,
								"bank_account_to"	=> $value["bank_account_to"],
								"status"			=> 1
							);
							
							$transaction_param[] = array(
								"source"			=> SOURCE_AR_DELAYINTEREST,
								"entering_date"		=> $date,
								"user_from"			=> $target->user_id,
								"bank_account_from"	=> $bank_account_from,
								"amount"			=> $value["delay_interest"],
								"target_id"			=> $target->id,
								"investment_id"		=> $value["investment_id"],
								"instalment_no"		=> $instalment,
								"user_to"			=> $value["user_to"],
								"limit_date"		=> $limit_date,
								"bank_account_to"	=> $value["bank_account_to"],
								"status"			=> 1
							);
						}
						
						if(intval($liquidated_damages)>0){
							$transaction_param[] = array(
								"source"			=> SOURCE_AR_DAMAGE,
								"entering_date"		=> $date,
								"user_from"			=> $target->user_id,
								"bank_account_from"	=> $bank_account_from,
								"amount"			=> $liquidated_damages,
								"target_id"			=> $target->id,
								"investment_id"		=> 0,
								"instalment_no"		=> $instalment,
								"user_to"			=> 0,
								"limit_date"		=> $limit_date,
								"bank_account_to"	=> PLATFORM_VIRTUAL_ACCOUNT,
								"status"			=> 1
							);
						}
						
						if($transaction_param){
							$rs  = $this->CI->transaction_model->insert_many($transaction_param);
						}
					}
				}else{
					if($user_to_info){
						foreach($user_to_info as $investment_id => $value){
							$delay_interest = $this->CI->financial_lib->get_delay_interest($value["remaining_principal"],$delay_days);
							$this->CI->transaction_model->update_by(
								array(
									"source" 		=> SOURCE_AR_DELAYINTEREST,
									"investment_id" => $value["investment_id"],
								),
								array(
									"amount" => $delay_interest
								)
							);
						}
					}
				}
				return true;
			}
		}
		return false;
	}
}
