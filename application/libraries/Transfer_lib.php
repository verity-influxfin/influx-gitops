<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/transfer_model');
		$this->CI->load->model('loan/investment_model');
		$this->CI->load->model('loan/transfer_investment_model');
		$this->CI->load->library('Transaction_lib');
		$this->CI->load->library('contract_lib');
    }

	public function get_pretransfer_info($investment){
		if($investment && $investment->status==3){
			$entering_date		= get_entering_date();
			$settlement_date 	= date("Y-m-d",strtotime($entering_date.' +'.TRANSFER_RANGE_DAYS.' days'));
			$target 			= $this->CI->target_model->get($investment->target_id);
			$this->CI->load->model('transaction/transaction_model');
			$transaction 	= $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by(array(
				"investment_id"	=> $investment->id,
				"status"		=> array(1,2)
			));
			if($transaction){
				$instalment_paid 		= 0;//已還期數
				$next_instalment 		= true;//下期
				$principal				= 0;//本金
				$interest				= 0;//利息
				$platform_fee			= 0;//應付服務費
				foreach($transaction as $key => $value){
					if($value->status==2 && $value->source==SOURCE_PRINCIPAL){
						$instalment_paid = $value->instalment_no;
					}
				}
				foreach($transaction as $key => $value){
					if($value->status==1){
						switch($value->source){
							case SOURCE_AR_PRINCIPAL: 
								$principal 		+= $value->amount;
								break;
							case SOURCE_AR_INTEREST: 
								if($value->limit_date <= $settlement_date){
									$interest	+= $value->amount;
									if($value->limit_date == $settlement_date){
										$next_instalment = false;
									}
								}else if($next_instalment && $value->limit_date > $settlement_date && $value->instalment_no==($instalment_paid+1)){
									$interest	+= $value->amount;
								}
								break;
							case SOURCE_AR_FEES: 
								if($value->limit_date <= $settlement_date){
									$platform_fee	+= $value->amount;
									if($value->limit_date == $settlement_date){
										$next_instalment = false;
									}
								}else if($next_instalment && $value->limit_date > $settlement_date && $value->instalment_no==($instalment_paid+1)){
									$platform_fee	+= $value->amount;
								}
								break;
							default:
								break;
						}
					}
				}
				$total = $principal + $interest - $platform_fee;

				$contract = $this->CI->contract_lib->pretransfer_contract([
					$investment->user_id,
					"",
					$target->user_id,
					$target->target_no,
					$principal,
					$principal,
					$total,
					$target->user_id,
					$target->user_id,
					$target->user_id,
				]);
				$instalment = $target->instalment - $instalment_paid;
				$fee 		= intval(round($principal*DEBT_TRANSFER_FEES/100,0));
				$data 		= array(
					"total"						=> $total,
					"instalment"				=> $instalment,//剩餘期數
					"principal"					=> $principal,
					"interest"					=> $interest,
					"platform_fee"				=> $platform_fee,
					"fee"						=> $fee,
					"debt_transfer_contract" 	=> $contract,
					"settlement_date"			=> $settlement_date,//結帳日
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
				$principal 	= $info["principal"];
				$total 		= $info["total"];
				$contract 	= $this->CI->contract_lib->sign_contract("transfer",[
					$investment->user_id,
					"",
					$target->user_id,
					$target->target_no,
					$principal,
					$principal,
					$total,
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
						$this->CI->load->library('target_lib');
						$this->CI->target_lib->insert_investment_change_log($investment->id,$investment_param,$investment->user_id);
						$param = array(
							"target_id"				=> $investment->target_id,
							"investment_id"			=> $investment->id,
							"transfer_fee"			=> $info["fee"],
							"amount"				=> $info["total"],
							"principal"				=> $info["principal"],
							"interest"				=> $info["interest"],
							"platform_fee"			=> $info["platform_fee"],
							"instalment"			=> $info["instalment"],
							"expire_time"			=> strtotime("+2 days", time()),
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

	
	public function cancel_transfer($transfers){
		if($transfers && $transfers->status==0){
			$rs = $this->CI->transfer_model->update($transfers->id,array(
				"status"	=> 8
			));
			if($rs){
				$investment_param = array(
					"transfer_status"	=> 0,
				);
				$rs = $this->CI->investment_model->update($transfers->investment_id,$investment_param);
				$this->CI->load->library('target_lib');
				$this->CI->target_lib->insert_investment_change_log($transfers->investment_id,$investment_param);
				return true;
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
			$transfer_investments = $this->CI->transfer_investment_model->order_by("tx_datetime","asc")->get_many_by(array(
				"transfer_id"	=> $transfers->id,
				"status"		=> array("0","1")
			));
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
										$target->user_id,
										$target->target_no,
										$transfers->principal,
										$transfers->principal,
										$value->amount,
										$target->user_id,
										$target->user_id,
										$target->user_id,
									]);
									$param 			= array(
										"status"		=> 2,
										"contract_id"	=> $contract_id
									);
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
						$this->cancel_transfer($transfers);
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
											$this->CI->transfer_investment_model->update($value->id,array(
												"frozen_status"		=> 1,
												"frozen_id"			=> $rs,
												"status"			=> 1,
												"tx_datetime"		=> $tx_datetime
											));
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
					$this->cancel_transfer($transfers);
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
