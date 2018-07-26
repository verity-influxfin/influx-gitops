<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/target_model');
		$this->CI->load->model('loan/transfer_model');
		$this->CI->load->model('loan/investment_model');
		$this->CI->load->model('loan/transfer_investment_model');
		$this->CI->load->library('Transaction_lib');
		$this->CI->load->library('contract_lib');
    }

	public function get_pretransfer_info($investment){
		if($investment){
			$target 		= $this->CI->target_model->get($investment->target_id);
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by(array("investment_id"=>$investment->id,"user_to"=>$investment->user_id,"status"=>array(1,2)));
			if($transaction){
				$instalment 		= 0;
				$instalment_paid 	= 0;
				$principal			= 0;
				foreach($transaction as $k => $v){
					if($v->source == SOURCE_AR_PRINCIPAL){
						$principal 		+= $v->amount;
						$instalment		 = $v->instalment_no;
					}
					if($v->source == SOURCE_PRINCIPAL){
						$principal 		-= $v->amount;
						$instalment_paid = $v->instalment_no;
					}
				}
				
				$contract = $this->CI->contract_lib->pretransfer_contract([
					$investment->user_id,
					"",
					$investment->user_id,
					$target->target_no,
					$principal,
					$investment->user_id,
					$target->target_no,
					$principal,
					$principal,
					$principal,
					"",
					$principal,
					$target->user_id,
					$target->user_id,
					$target->user_id,
				]);
				
				$instalment = $instalment - $instalment_paid;
				$fee 		= intval(round($principal*DEBT_TRANSFER_FEES/100,0));
				$data 		= array(
					"instalment"				=> $instalment,
					"principal"					=> $principal,
					"fee"						=> $fee,
					"debt_transfer_contract" 	=> $contract,
				);
				return $data;
			}

		}
		return false;
	}
	
	public function apply_transfer($investment){
		if($investment && $investment->status==3){
			$target 	= $this->CI->target_model->get($investment->target_id);
			$info  		= $this->get_pretransfer_info($investment);
			if($info){
				$principal = $info["principal"];
				$contract = $this->CI->contract_lib->sign_contract("transfer",[
					$investment->user_id,
					"",
					$investment->user_id,
					$target->target_no,
					$principal,
					$investment->user_id,
					$target->target_no,
					$principal,
					$principal,
					$principal,
					"",
					$principal,
					$target->user_id,
					$target->user_id,
					$target->user_id,
				]);
				if($contract){
					$investment_param = array(
						"transfer_status"		=> 1,
					);
					$rs = $this->CI->investment_model->update($investment->id,$investment_param);
					if($rs){
						$param = array(
							"target_id"				=> $investment->target_id,
							"investment_id"			=> $investment->id,
							"transfer_fee"			=> $info["fee"],
							"amount"				=> $info["principal"],
							"instalment"			=> $info["instalment"],
							"expire_time"			=> strtotime("+2 days", time()),
							"launch_times"			=> 1,
							"contract_id"			=> $contract,
						);
						$res = $this->CI->transfer_model->insert($param);
						return $res;
					}
				}
				
			}
		}
		return false;
	}

	public function get_transfer_list($where = array("status" => 0)){
		$list 	= array();
		$rs = $this->CI->transfer_model->get_many_by($where);
		if($rs){
			$list = $rs;
		}
		return $list;
	}
	

	public function get_transfer($id){
		
		if($id){
			$transfer = $this->CI->transfer_model->get($id);
			return $transfer;
		}
		return false;
		
	}
	
	public function get_transfer_investments($investment_id){
		
		if($investment_id){
			$transfer = $this->CI->transfer_model->get_by(array("investment_id"=>$investment_id));
			return $transfer;
		}
		return false;
		
	}
	
	//判斷流標或結標或凍結款項
	function check_bidding($transfers){
		if($transfers && $transfers->status==0){
			$transfer_investments = $this->CI->transfer_investment_model->order_by("tx_datetime","asc")->get_many_by(array("transfer_id"=>$transfers->id,"status"=>array("0","1")));
			if($transfer_investments){
				$amount = 0;
				foreach($transfer_investments as $key => $value){
					if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
						$amount += $value->amount;
					}
				}
				
				if($amount >= $transfers->amount){
					//結標
					$rs = $this->CI->transfer_model->update($transfers->id,array("status"=>1));
					if($rs){
						$ended = true;
						foreach($transfer_investments as $key => $value){
							$param = array("status"=>9);
							if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
								$investment = $this->CI->investment_model->get($transfers->investment_id);
								$target		= $this->CI->target_model->get($transfers->target_id);
								if($transfers->amount == $value->amount && $ended){
									$contract_id 	= $this->CI->contract_lib->sign_contract("transfer",[
										$investment->user_id,
										$value->user_id,
										$investment->user_id,
										$target->target_no,
										$value->amount,
										$investment->user_id,
										$target->target_no,
										$value->amount,
										$value->amount,
										$value->amount,
										$value->user_id,
										$value->amount,
										$target->user_id,
										$target->user_id,
										$target->user_id,
									]);
									$param 			= array("status"=>2,"contract_id"=>$contract_id);
									$ended 			= false;
								}else{
									$this->CI->frozen_amount_model->update($value->frozen_id,array("status"=>0));
								}
							}
							$this->CI->transfer_investment_model->update($value->id,$param);
						}
						return true;
					}
				}else{
					if($transfers->expire_time < time()){
						//流標
						$this->CI->transfer_model->update($transfers->id,array(
							"launch_times"	=> $transfers->launch_times + 1,
							"expire_time"	=> strtotime("+2 days", $transfers->expire_time)
						));
						foreach($transfer_investments as $key => $value){
							$this->CI->transfer_investment_model->update($value->id,array("status"=>9));
							if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
								$this->CI->frozen_amount_model->update($value->frozen_id,array("status"=>0));
							}
						}
					}else{
						//凍結款項
						foreach($transfer_investments as $key => $value){
							if($value->status ==0 && $value->frozen_status==0){
								$virtual_account = $this->CI->virtual_account_model->get_by(array("status"=>1,"investor"=>1,"user_id"=>$value->user_id));
								if($virtual_account){
									$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>2));
									$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
									$total = $funds["total"] - $funds["frozen"];
									if(intval($total)-intval($value->amount)>=0){
										$last_recharge_date = strtotime($funds['last_recharge_date']);
										$tx_datetime = $last_recharge_date < $value->created_at?$value->created_at:$last_recharge_date;
										$tx_datetime = date("Y-m-d H:i:s",$tx_datetime);
										$param = array(
											"type"				=> 2,
											"virtual_account"	=> $virtual_account->virtual_account,
											"amount"			=> intval($value->amount),
											"tx_datetime"		=> $tx_datetime,
										);
										$rs = $this->CI->frozen_amount_model->insert($param);
										if($rs){
											$this->CI->transfer_investment_model->update($value->id,array("frozen_status"=>1,"frozen_id"=>$rs,"status"=>1,"tx_datetime"=>$tx_datetime));
										}
									}
									$this->CI->virtual_account_model->update($virtual_account->id,array("status"=>1));
								}
							}
						}
					}
					return true;
				}
			}else{
				if($transfers->expire_time < time()){
					$this->CI->transfer_model->update($transfers->id,array(
						"launch_times"	=> $transfers->launch_times + 1,
						"expire_time"	=> strtotime("+2 days", $transfers->expire_time)
					));
				}
				return true;
			}
		}
		return false;
	}
	
	public function script_check_bidding(){
		$script  	= 5;
		$count 		= 0;
		$ids		= array();
		$transfers 	= $this->CI->transfer_model->get_many_by(array("status"=>0,"script_status"=>0));
		if($transfers && !empty($transfers)){
			foreach($transfers as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->transfer_model->update_many($ids,array("script_status"=>$script));
			if($update_rs){
				foreach($transfers as $key => $value){
					$check = $this->check_bidding($value);
					if($check){
						$count++;
					}
					$this->CI->transfer_model->update($value->id,array("script_status"=>0));
				}
			}
		}
		return $count;
	}
	
}
