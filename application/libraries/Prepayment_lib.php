<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prepayment_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/prepayment_model');
		$this->CI->load->library('Transaction_lib');
    }
 
	public function get_prepayment_info($target=array()){
		if($target->status == 5 && $target->delay_days==0){
			$where 			= array(
				"target_id" => $target->id,
				"user_from" => $target->user_id,
				"status"	=> array(1,2)
			);
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
			if($transaction){
				$entering_date		= get_entering_date();
				$settlement_date 	= date("Y-m-d",strtotime($entering_date.' +'.PREPAYMENT_RANGE_DAYS.' days'));
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
					if(!isset($remaining_principal[$value->user_to]))
						$remaining_principal[$value->user_to] = 0;
					if(!isset($interest_payable[$value->user_to]))
						$interest_payable[$value->user_to] = 0;

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
								if($value->limit_date > $last_settlement_date){
									$last_settlement_date	= $value->limit_date;
								}						
							}
							break;
						case SOURCE_INTEREST: 
							$interest_payable[$value->user_to] -= $value->amount;
							break;
						default:
							break;
					}
				} 

				$data["remaining_instalment"] 	= $target->instalment - $instalment;
				
				if($remaining_principal){
					$days  		= get_range_days($last_settlement_date,$settlement_date);
					$leap_year 	= $this->CI->financial_lib->leap_year($target->loan_date,$target->instalment);
					$year_days 	= $leap_year?366:365;//今年日數
					foreach($remaining_principal as $k => $v){
						$interest_payable[$k] 		 = round( $v * $target->interest_rate / 100 * $days / $year_days ,0);
					}
					$data["remaining_principal"] 	= array_sum($remaining_principal);
					$data["interest_payable"] 		= array_sum($interest_payable);
					$data["liquidated_damages"] 	= $this->CI->financial_lib->get_liquidated_damages($data["remaining_principal"],$target->damage_rate);
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
		if($target && $target->status==5 && $target->delay_days==0){
			$info  = $this->get_prepayment_info($target);
			$param = array(
				"target_id"			=> $target->id,
				"settlement_date"	=> $info["settlement_date"],
				"amount"			=> $info["total"]
			);
			$rs = $this->CI->prepayment_model->insert($param);
			if($rs){
				$update_data = array( "sub_status" => 3 );
				$this->CI->load->library('Target_lib');
				$this->CI->target_lib->insert_change_log($target->id,$update_data,0,0);
				$this->CI->target_model->update($target->id,$update_data);
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

}


