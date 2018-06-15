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
    }
 
	public function get_prepayment_info($target=array()){
		if($target->status == 5 && $target->delay == 0 ){
			$where 			= array(
				"target_id" => $target->id,
				"status"	=> array(1,2)
			);
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by($where);
	
			if($transaction){
				$entering_date		= get_entering_date();
				$settlement_date 	= date("Y-m-d",strtotime($entering_date.' +2 days'));
				$data = array(
					"remaining_principal"	=> 0,//剩餘本金
					"remaining_instalment"	=> 0,//剩餘期數
					"settlement_date"		=> $settlement_date,//結帳日
					"liquidated_damages"	=> 0,//違約金
					"delay_interest_payable"=> 0,//延滯息
					"interest_payable"		=> 0,//應付利息
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
							
							$instalment = $value->status==2?$value->instalment_no:$instalment;
							$remaining_principal[$value->user_to]	+= $value->amount;
							
							break;
						case SOURCE_PRINCIPAL: 
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
					}
				} 

				$data["remaining_instalment"] 	= $target->instalment - $instalment;
				$days  							= get_range_days($last_settlement_date,$settlement_date);
				$leap_year = $this->CI->financial_lib->leap_year($day0,$target->instalment);
				$year_days = $leap_year?366:365;//今年日數
				if($remaining_principal){
					foreach($remaining_principal as $k => $v){
						$data["remaining_principal"] += $v;
						if(!isset($interest_payable[$k])){
							$interest_payable[$k] = 0;
						}
						$interest_payable[$k] += round( $v * $target->interest_rate / 100 * $days / $year_days ,0);
					}
					foreach($interest_payable as $k => $v){
						$data["interest_payable"] += $v;
					}
					
				}
				$data["liquidated_damages"] = round($data["remaining_principal"]*LIQUIDATED_DAMAGES/100,0);
				$data["total"] 				= $data["remaining_principal"] + $data["interest_payable"] + $data["liquidated_damages"];
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


