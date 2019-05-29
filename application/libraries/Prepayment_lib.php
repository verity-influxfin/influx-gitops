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
 
	public function get_prepayment_info($target=[]){
		if($target->status == 5 && $target->delay_days==0){
			$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by([
				'target_id' => $target->id,
				'user_from' => $target->user_id,
				'status'	=> [1,2]
			]);
			if($transaction){
				$entering_date	= get_entering_date();
				$data = [
					'settlement_date'		=> $entering_date,//剩餘本金
					'remaining_instalment'	=> 0,//剩餘期數
					'remaining_principal'	=> 0,//剩餘本金
					'interest_payable'		=> 0,//應付利息
					'liquidated_damages'	=> 0,//違約金
					'total'					=> 0,
				];
				$last_settlement_date 	= $target->loan_date;
				$instalment_paid 		= 0;
				$remaining_principal	= [];
				$interest_payable		= [];
				foreach($transaction as $key => $value){
					if(!isset($remaining_principal[$value->investment_id]) || !isset($interest_payable[$value->investment_id])){
						$remaining_principal[$value->investment_id] 	= 0;
						$interest_payable[$value->investment_id] 		= 0;
					}

					if($value->status==2 && $value->source==SOURCE_PRINCIPAL){
						$instalment_paid 		= $value->instalment_no;
						$last_settlement_date 	= $value->limit_date;
					}

					if($value->status==1){
						switch($value->source){
							case SOURCE_AR_PRINCIPAL: 
								$remaining_principal[$value->investment_id]	+= $value->amount;
								break;
							case SOURCE_AR_INTEREST: 
								if($value->limit_date <= $entering_date){
									$last_settlement_date = $value->limit_date;
									$interest_payable[$value->investment_id] += $value->amount;
								}
								break;
							default:
								break;
						}
					}
				} 

				$data['remaining_instalment'] 	= $target->instalment - $instalment_paid;
				if($remaining_principal){
					$days  = get_range_days($last_settlement_date,$entering_date);
					if($days){
						foreach($remaining_principal as $k => $v){
							$interest_payable[$k] 	= $this->CI->financial_lib->get_interest_by_days($days,$v,$target->instalment,$target->interest_rate,$target->loan_date);
						}
					}
					$data['remaining_principal'] 	= array_sum($remaining_principal);
					$data['interest_payable'] 		= array_sum($interest_payable);
					$data['liquidated_damages'] 	= $this->CI->financial_lib->get_liquidated_damages($data['remaining_principal'],$target->damage_rate);
				}
				
				$data['total'] = $data['remaining_principal'] + $data['interest_payable'] + $data['liquidated_damages'];
				return $data;
			}
		}
		return false;
	}
	
	public function apply_prepayment($target){
		if($target && $target->status==5 && $target->delay_days==0){
			$info  = $this->get_prepayment_info($target);
			$this->CI->load->model('user/virtual_account_model');
			$virtual_account = $this->CI->virtual_account_model->get_by(array(
				'status'		=> 1,
				'investor'		=> 0,
				'user_id'		=> $target->user_id
			));
			if($info && $virtual_account){
				$this->CI->load->library('Transaction_lib');
				$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
				$total = $funds['total'] - $funds['frozen'];
				if(intval($total)-intval($info['total'])>=0){
					$rs = $this->CI->prepayment_model->insert([
						'target_id'			=> $target->id,
						'settlement_date'	=> $info['settlement_date'],
						'amount'			=> $info['total'],
						'principal'			=> $info['remaining_principal'],
						'interest'			=> $info['interest_payable'],
						'damage'			=> $info['liquidated_damages'],
					]);
					if($rs){
						$this->CI->load->library('Target_lib');
						$this->CI->target_lib->insert_change_log($target->id,['sub_status'=>3],0,0);
						$this->CI->target_model->update($target->id,['sub_status'=>3]);
						$this->CI->load->library('Charge_lib');
						
						$target 	= $this->CI->target_model->get($target->id);
						$prepayment = $this->CI->prepayment_model->get($rs);
						$this->CI->charge_lib->charge_prepayment_target($target,$prepayment);
						return $rs;
					}
				}
			}
		}
		return false;
	}

	public function get_prepayment($target){
		if($target){
			$prepayment	= $this->CI->prepayment_model->order_by('settlement_date','desc')->get_by(['target_id'=>$target->id]);
			if($prepayment){
				return $prepayment;
			}
		}
		return false;
	}

}


