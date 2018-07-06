<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prepayment_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/target_model');
		$this->CI->load->model('loan/prepayment_model');
		$this->CI->load->library('Financial_lib');
		$this->CI->load->library('Transaction_lib');
    }
 
	public function get_prepayment_info($target=array()){
		if($target->status == 5){
			$range_days 	= 2;
			$where 			= array(
				"target_id" => $target->id,
				"user_from" => $target->user_id,
				"status"	=> array(1,2)
			);
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
			if($transaction){
				$entering_date		= get_entering_date();
				if($target->delay_days>0 && $target->delay_days<=GRACE_PERIOD){
					$days = GRACE_PERIOD-$target->delay_days;
					$days = $days>$range_days?$range_days:$days;
					$settlement_date 	= date("Y-m-d",strtotime($entering_date.' +'.$days.' days'));
				}else{
					$settlement_date 	= date("Y-m-d",strtotime($entering_date.' +'.$range_days.' days'));
				}

				$data = array(
					"remaining_principal"		=> 0,//剩餘本金
					"remaining_instalment"		=> 0,//剩餘期數
					"settlement_date"			=> $settlement_date,//結帳日
					"liquidated_damages"		=> 0,//違約金
					"delay_interest_payable"	=> 0,//延滯息
					"interest_payable"			=> 0,//應付利息
					"total"						=> 0,
				);
				$last_settlement_date 	= $target->loan_date;
				$instalment 			= 0;
				$remaining_principal	= array();
				$interest_payable		= array();
				foreach($transaction as $key => $value){
					$remaining_principal[$value->user_to] 	= 0;
					$interest_payable[$value->user_to] 		= 0;
				}
				foreach($transaction as $key => $value){
					switch($value->source){
						case SOURCE_AR_PRINCIPAL: 
							$instalment = $value->status==2?$value->instalment_no:$instalment;
							$remaining_principal[$value->user_to]	+= $value->amount;
							break;
						case SOURCE_PRINCIPAL: 
							$remaining_principal[$value->user_to]	-= $value->amount;
							break;
						case SOURCE_AR_INTEREST: 
							if($value->limit_date <= $settlement_date){
								$interest_payable[$value->user_to]	+= $value->amount;
								if($value->limit_date>$last_settlement_date){
									$last_settlement_date	= $value->limit_date;
								}						
							}
							break;
						case SOURCE_INTEREST: 
							$interest_payable[$value->user_to] -= $value->amount;
							break;
						case SOURCE_AR_DAMAGE: 
							$data['liquidated_damages'] 	+= $value->amount;
							break;
						case SOURCE_AR_DELAYINTEREST: 
							$data['delay_interest_payable'] += $value->amount;
							break;
						case SOURCE_DAMAGE:
							$data['liquidated_damages'] 	-= $value->amount;
							break;
						case SOURCE_DELAYINTEREST:
							$data['delay_interest_payable'] -= $value->amount;
							break;
						default:
							break;
					}
				} 

				$data["remaining_instalment"] 	= $target->instalment - $instalment;
				
				if($remaining_principal){
					if($target->delay_days > GRACE_PERIOD){
						foreach($remaining_principal as $k => $v){
							$data["remaining_principal"] 	+= $v;
							$data["delay_interest_payable"] += $this->CI->financial_lib->get_delay_interest($v,$target->delay_days+$range_days);
						}
					}else{
						$days  		= get_range_days($last_settlement_date,$settlement_date);
						$leap_year 	= $this->CI->financial_lib->leap_year($target->loan_date,$target->instalment);
						$year_days 	= $leap_year?366:365;//今年日數
						foreach($remaining_principal as $k => $v){
							$data["remaining_principal"] += $v;
							$interest_payable[$k] 		 = round( $v * $target->interest_rate / 100 * $days / $year_days ,0);
						}
						$data["liquidated_damages"] 	= $this->CI->financial_lib->get_liquidated_damages($data["remaining_principal"]);
					}
					foreach($interest_payable as $k => $v){
						$data["interest_payable"] 	 += $v;
					}
				}
				
				$data["total"] = 	$data["remaining_principal"] + 
									$data["interest_payable"] + 
									$data["liquidated_damages"] + 
									$data["delay_interest_payable"];
				return $data;
			}
		}
		return false;
	}
	
	public function apply_prepayment($target){
		if($target && $target->status==5){
			$info  = $this->get_prepayment_info($target);
			$param = array(
				"target_id"			=> $target->id,
				"settlement_date"	=> $info["settlement_date"],
				"amount"			=> $info["total"]
			);
			$rs = $this->CI->prepayment_model->insert($param);
			if($rs){
				$this->CI->target_model->update($target->id,array("sub_status"=>3));
				return $rs;
			}
		}
		return false;
	}

	public function get_prepayment($target){
		if($target){
			$where 		= array("target_id"=>$target->id);
			$prepayment	= $this->CI->prepayment_model->order_by("settlement_date","desc")->get_by($where);
			if($prepayment){
				return $prepayment;
			}
		}
		return false;
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
		if($targets && !empty($targets)){
			foreach($targets as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->target_model->update_many($ids,array("script_status"=>$script));
			if($update_rs){
				foreach($targets as $key => $value){
					$prepayment = $this->get_prepayment($value);
					if($prepayment){
						if($date > $prepayment->settlement_date){
							$this->CI->target_model->update($value->id,array("script_status"=>0,"sub_status"=>0));
						}else{
							$this->CI->load->model('user/virtual_account_model');
							$virtual_account = $this->CI->virtual_account_model->get_by(array(
								"status"			=> 1,
								"investor"			=> 0,
								"user_id"			=> $value->user_id,
								"virtual_account"	=> $value->virtual_account
							));
							if($virtual_account){
								$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>2));
								$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
								$total = $funds["total"] - $funds["frozen"];
								if($total >= $prepayment->amount){
									$this->CI->load->library('Charge_lib');
									$this->CI->charge_lib->charge_prepayment_target($value,$prepayment->settlement_date);
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
}


