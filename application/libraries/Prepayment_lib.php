<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prepayment_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/prepayment_model');
		$this->CI->load->library('Financial_lib');
    }

	/*public function get_prepayment_info($target=array()){
		if($target->status == 5){
			$today_close_time		= strtotime(date("Y-m-d").' '.CLOSING_TIME);
			$settlement_date 		= time()>$today_close_time?date("Y-m-d",strtotime('+2 day')):date("Y-m-d",strtotime('+1 day'));
			$liquidated_damages		= 0;
			$where 					= array("target_id"=>$target->id,"status"=>array(1 ,2));
			$transactions 			= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
			if($transactions){
				$transaction_list 	= array();
				foreach($transactions as $key => $value){
					$transaction_list[$value->investment_id][] = $value;
				}

				foreach($transaction_list as $investment_id => $transaction){
					$data = array(
						"remaining_principal"	=> 0,
						"instalment"			=> 0,
						"delay_interest_payable"=> 0,
						"interest_payable"		=> 0,
						"last_settlement_date"	=> "",
						"day0"					=> ""
					);
					foreach($transaction as $key => $value){
						switch($value->source){
							case SOURCE_LENDING: 
								$data["last_settlement_date"] 	= $value->entering_date;
								$data["day0"] 					= $value->entering_date;
								break;
							case SOURCE_AR_PRINCIPAL: 
								$data["remaining_principal"] 	+= $value->amount;
								if($value->status==2){
									$data["instalment"] 		= $value->instalment_no;
								}
								break;
							case SOURCE_PRINCIPAL: 
								$data["remaining_principal"] 	-= $value->amount;
								break;
							case SOURCE_AR_INTEREST: 
								if($value->limit_date <= $settlement_date){
									$data["interest_payable"]	+= $value->amount;
									$data["last_settlement_date"]= $value->limit_date;
								}
								break;
							case SOURCE_INTEREST: 
								$data["interest_payable"] 		-= $value->amount;
								break;
							case SOURCE_AR_DAMAGE: 
								$liquidated_damages 			+= $value->amount;
								break;
							case SOURCE_DAMAGE: 
								$liquidated_damages 			-= $value->amount;
								break;
							case SOURCE_AR_DELAYINTEREST: 
								$data["delay_interest_payable"] += $value->amount;
								break;
							case SOURCE_DELAYINTEREST: 
								$data["delay_interest_payable"] -= $value->amount;
								break;
						}
					}
					dump($data);
				}
				
				die();

	
				
				
				$data["remaining_instalment"] 	= $target->instalment - $instalment;
				if($target->delay && $target->delay_days > 7){
					$days  						= 7;
					$data["liquidated_damages"] = $data["liquidated_damages"];
				}else{
					$days  						= get_range_days($last_settlement_date,$settlement_date);
					$data["liquidated_damages"] = round($data["remaining_principal"]*LIQUIDATED_DAMAGES/100,0);
				}
				
				$leap_year = $this->CI->financial_lib->leap_year($day0,$target->instalment);
				$year_days = $leap_year?366:365;//今年日數
				if($remaining_principal){
					foreach($remaining_principal as $k => $v){
						if(!isset($interest_payable[$k])){
							$interest_payable[$k] = 0;
						}
						$interest_payable[$k] += round( $v * $target->interest_rate / 100 * $days / $year_days ,0);
					}
					foreach($interest_payable as $k => $v){
						$data["interest_payable"] += $v;
					}
					
				}
				$data["total"] = $data["remaining_principal"] + $data["liquidated_damages"] + $data["delay_interest_payable"] + $data["interest_payable"];
				return $data;
			}
		}
		return false;
	}
	*/
	public function get_prepayment_info($target=array()){
		if($target->status == 5){
			$where 			= array("target_id"=>$target->id,"status"=>array(1,2));
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
			if($transaction){
				$settlement_date 	= time()>strtotime(date("Y-m-d").' '.CLOSING_TIME)?date("Y-m-d",strtotime('+2 day')):date("Y-m-d",strtotime('+1 day'));
				$data = array(
					"remaining_principal"	=> 0,
					"remaining_instalment"	=> 0,
					"settlement_date"		=> $settlement_date,//結帳日
					"liquidated_damages"	=> 0,
					"delay_interest_payable"=> 0,
					"interest_payable"		=> 0,
					"total"					=> 0,
				);
				$instalment 			= 0;
				$remaining_principal	= array();
				$interest_payable		= array();
				foreach($transaction as $key => $value){
					switch($value->source){
						case SOURCE_LENDING: 
							$last_settlement_date = $day0 = $value->entering_date;
							break;
						case SOURCE_AR_PRINCIPAL: 
							if(!isset($remaining_principal[$value->user_to])){
								$remaining_principal[$value->user_to] = 0;
							}
							$data["remaining_principal"] 			+= $value->amount;
							$remaining_principal[$value->user_to]	+= $value->amount;
							if($value->status==2){
								$instalment = $value->instalment_no;
							}
							break;
						case SOURCE_PRINCIPAL: 
							$data["remaining_principal"] 			-= $value->amount;
							$remaining_principal[$value->user_to]	-= $value->amount;
							break;
						case SOURCE_AR_INTEREST: 
							if($value->limit_date <= $settlement_date){
								if(!isset($interest_payable[$value->user_to])){
									$interest_payable[$value->user_to] = 0;
								}
								$interest_payable[$value->user_to]	+= $value->amount;
								$last_settlement_date				= $value->limit_date;
							}
							break;
						case SOURCE_INTEREST: 
							$interest_payable[$value->user_to] -= $value->amount;
							break;
						case SOURCE_AR_DAMAGE: 
							$data["liquidated_damages"] += $value->amount;
							break;
						case SOURCE_DAMAGE: 
							$data["liquidated_damages"] -= $value->amount;
							break;
						case SOURCE_AR_DELAYINTEREST: 
							$data["delay_interest"] += $value->amount;
							break;
						case SOURCE_DELAYINTEREST: 
							$data["delay_interest"] -= $value->amount;
							break;
					}
				} 

				$data["remaining_instalment"] 	= $target->instalment - $instalment;
				if($target->delay && $target->delay_days > 7){
					$days  						= 7;
					$data["liquidated_damages"] = $data["liquidated_damages"];
				}else{
					$days  						= get_range_days($last_settlement_date,$settlement_date);
					$data["liquidated_damages"] = round($data["remaining_principal"]*LIQUIDATED_DAMAGES/100,0);
				}
				
				$leap_year = $this->CI->financial_lib->leap_year($day0,$target->instalment);
				$year_days = $leap_year?366:365;//今年日數
				if($remaining_principal){
					foreach($remaining_principal as $k => $v){
						if(!isset($interest_payable[$k])){
							$interest_payable[$k] = 0;
						}
						$interest_payable[$k] += round( $v * $target->interest_rate / 100 * $days / $year_days ,0);
					}
					foreach($interest_payable as $k => $v){
						$data["interest_payable"] += $v;
					}
					
				}
				$data["total"] = $data["remaining_principal"] + $data["liquidated_damages"] + $data["delay_interest_payable"] + $data["interest_payable"];
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

}


