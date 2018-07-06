<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Target_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/target_model');
		$this->CI->load->model('loan/investment_model');
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('product/product_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->model('transaction/frozen_amount_model');
		$this->CI->load->library('Financial_lib');
		$this->CI->load->library('Notification_lib');
    }
	
	//核可額度利率
	public function approve_target($target = array()){
		$this->CI->load->library('credit_lib');
		if(!empty($target) && $target->status=="0"){
			$user_id 		= $target->user_id;
			$product_id 	= $target->product_id;
			$credit 		= $this->CI->credit_lib->get_credit($user_id,$product_id);
			if(!$credit){
				$rs 		= $this->CI->credit_lib->approve_credit($user_id,$product_id);
				if($rs){
					$credit = $this->CI->credit_lib->get_credit($user_id,$product_id);
				}
			}
			
			if($credit){
				$interest_rate	= $this->CI->credit_lib->get_rate($credit['level'],$target->instalment);
				if($interest_rate){
					$used_amount	= 0;
					$target_list 	= $this->CI->target_model->get_many_by(array("id !="=>$target->id,"product_id"=>$product_id,"user_id"=> $user_id,"status <="=>5));
					if($target_list){
						foreach($target_list as $key =>$value){
							$used_amount = $used_amount + intval($value->loan_amount);
						}
					}
					$credit['amount'] 	= $credit['amount'] - $used_amount;
					$loan_amount 		= $target->amount > $credit['amount']?$credit['amount']:$target->amount;
					if($loan_amount>=5000){
						$platform_fee	= round($loan_amount/100*PLATFORM_FEES,0);
						$platform_fee	= $platform_fee>PLATFORM_FEES_MIN?$platform_fee:PLATFORM_FEES_MIN;
						$contract 		= $this->get_target_contract($user_id,$loan_amount,$interest_rate);
						$param = array(
							"loan_amount"		=> $loan_amount,
							"credit_level"		=> $credit['level'],
							"platform_fee"		=> $platform_fee,
							"interest_rate"		=> $interest_rate, 
							"virtual_account" 	=> CATHAY_VIRTUAL_CODE.$target->target_no,
							"contract"			=> $contract,
							"status"			=> "1",
						);
						$rs = $this->CI->target_model->update($target->id,$param);
						if($rs){
							$virtual_data = array(
								"user_id"			=> $user_id,				
								"virtual_account"	=> $param['virtual_account'],
								"investor"			=> 0,
							);
							$this->CI->virtual_account_model->insert($virtual_data);
							$this->CI->notification_lib->approve_target($user_id,"1",$loan_amount);
						}
					}else{
						$param = array(
							"loan_amount"		=> 0,
							"status"			=> "9",
							"remark"			=> "credit not enough",
						);
						$rs = $this->CI->target_model->update($target->id,$param);
						$this->CI->notification_lib->approve_target($user_id,"9");
					}
					
					return $rs;
				}
			}
		}
		return false;
	}

	private function get_target_contract($user_id,$amount,$rate){
		$this->CI->load->model('platform/contract_model');
		$contract = $this->CI->contract_model->get_by(array("alias"=>"lend"));
		return sprintf($contract->content,"",$user_id,$amount,$rate,"");
	}
	
	//判斷流標或結標或凍結投資款項
	function check_bidding($target){
		if( $target && $target->id && $target->status == 3){
			$investments = $this->CI->investment_model->order_by("tx_datetime","asc")->get_many_by(array("target_id"=>$target->id,"status"=>array("0","1")));
			if($investments){
				$amount = 0;
				foreach($investments as $key => $value){
					if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
						$amount += $value->amount;
					}
				}
				//更新invested
				$this->CI->target_model->update($target->id,array("invested"=>$amount));
				if($amount >= $target->loan_amount){
					//結標
					$rs = $this->CI->target_model->update($target->id,array("status"=>4,"loan_status"=>2));
					if($rs){
						$total = 0;
						$ended = true;
						foreach($investments as $key => $value){
							$param = array("status"=>9);
							if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
								$total += $value->amount;
								if($total < $target->loan_amount && $ended){
									$loan_amount 	= $value->amount;
									$schedule 		= $this->CI->financial_lib->get_amortization_schedule($loan_amount,$target->instalment,$target->interest_rate,"",$target->repayment);
									$contract		= $this->get_investment_contract($value->user_id,$target->user_id,$loan_amount,$target->interest_rate,$schedule["total_payment"]);
									$param 			= array("loan_amount"=> $loan_amount,"contract"=>$contract ,"status"=>2);
								}else if($total >= $target->loan_amount && $ended){
									$loan_amount 	= $value->amount + $target->loan_amount - $total;
									$schedule 		= $this->CI->financial_lib->get_amortization_schedule($loan_amount,$target->instalment,$target->interest_rate,"",$target->repayment);
									$contract		= $this->get_investment_contract($value->user_id,$target->user_id,$loan_amount,$target->interest_rate,$schedule["total_payment"]);
									$param 			= array("loan_amount"=> $loan_amount,"contract"=>$contract ,"status"=>2); 
									$ended 			= false;
								}else{
									$this->CI->frozen_amount_model->update($value->frozen_id,array("status"=>0));
								}
							}
							$this->CI->investment_model->update($value->id,$param);
						}
						return true;
					}
				}else{
					if($target->expire_time < time()){
						//流標
						if($target->sub_status==8){
							$this->CI->load->library('Subloan_lib');
							$this->CI->subloan_lib->auction_ended($target);
						}else{
							$this->CI->target_model->update($target->id,array(
								"launch_times"	=> $target->launch_times + 1,
								"expire_time"	=> strtotime("+2 days", $target->expire_time),
								"invested"		=> 0,
							));
						}
						foreach($investments as $key => $value){
							$this->CI->investment_model->update($value->id,array("status"=>9));
							if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
								$this->CI->frozen_amount_model->update($value->frozen_id,array("status"=>0));
							}
						}
					}else{
						//凍結款項
						foreach($investments as $key => $value){
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
											"virtual_account"	=> $virtual_account->virtual_account,
											"amount"			=> intval($value->amount),
											"tx_datetime"		=> $tx_datetime,
										);
										$rs = $this->CI->frozen_amount_model->insert($param);
										if($rs){
											$this->CI->investment_model->update($value->id,array("frozen_status"=>1,"frozen_id"=>$rs,"status"=>1,"tx_datetime"=>$tx_datetime));
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
				if($target->expire_time < time()){
					if($target->sub_status==8){
						$this->CI->load->library('Subloan_lib');
						$this->CI->subloan_lib->auction_ended($target);
					}else{
						$this->CI->target_model->update($target->id,array(
							"launch_times"	=> $target->launch_times + 1,
							"expire_time"	=> strtotime("+2 days", $target->expire_time),
							"invested"		=> 0,
						));
					}
				}
				return true;
			}
		}
		return false;
	}
	
	private function get_investment_contract($user_from,$user_to,$amount,$rate,$PMT){
		$this->CI->load->model('platform/contract_model');
		$contract = $this->CI->contract_model->get_by(array("alias"=>"lend"));
		return sprintf($contract->content,$user_from,$user_to,$amount,$rate,$PMT);
	}
	
	//借款端還款計畫
	public function get_amortization_table($target=array()){

		$schedule		= array(
			"amount"		=> $target->loan_amount,
			"instalment"	=> $target->instalment,
			"rate"			=> $target->interest_rate,
			"total_payment"	=> 0,
			"date"			=> $target->loan_date,
			"sub_loan_fees"	=> 0,
			"platform_fees"	=> 0,
			"list"			=> array(),
		);
		$transactions 	= $this->CI->transaction_model->get_many_by(array(
			"user_from"	=> $target->user_id,
			"target_id" => $target->id,
			"status !=" => 0
		));
		
		$list = array();
		if($transactions){
			foreach($transactions as $key => $value){
				if($value->instalment_no){
					$list[$value->instalment_no] = array(
						"instalment"		=> $value->instalment_no,//期數
						"total_payment"		=> 0,//本期應還款金額
						"repayment"			=> 0,//本期已還款金額
						"interest"			=> 0,//利息
						"principal"			=> 0,//本金
						"delay_interest"	=> 0,//延滯息
						"liquidated_damages"=> 0,//違約金
						"repayment_date"	=> ""//還款日
					);
				}
			}
			
			foreach($transactions as $key => $value){
				switch($value->source){
					case SOURCE_AR_PRINCIPAL: 
						if($value->limit_date){
							$list[$value->instalment_no]['repayment_date'] = $value->limit_date;
						}
						$list[$value->instalment_no]['principal'] 			+= $value->amount;
						break;
					case SOURCE_AR_INTEREST: 
						$list[$value->instalment_no]['interest']  			+= $value->amount;
						break;
					case SOURCE_AR_DAMAGE: 
						$list[$value->instalment_no]['liquidated_damages'] 	+= $value->amount;
						break;
					case SOURCE_AR_DELAYINTEREST: 
						$list[$value->instalment_no]['delay_interest'] 		+= $value->amount;
						break;
					case SOURCE_SUBLOAN_FEE: 
						$schedule["sub_loan_fees"] += $value->amount;
						break;
					case SOURCE_FEES: 
						$schedule["platform_fees"] += $value->amount;
						break;
					case SOURCE_PRINCIPAL: 
					case SOURCE_DELAYINTEREST: 
					case SOURCE_DAMAGE: 
					case SOURCE_INTEREST: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					default:
						break;
				}
				if($value->instalment_no){
					$list[$value->instalment_no]["total_payment"] = 
					$list[$value->instalment_no]["interest"] + 
					$list[$value->instalment_no]["principal"] + 
					$list[$value->instalment_no]["delay_interest"] + 
					$list[$value->instalment_no]["liquidated_damages"];
				}
			}
			foreach($list as $key => $value){
				$schedule["total_payment"] += $value["total_payment"];
			}
		}
		$schedule['list'] = $list;
		return $schedule;
	}
	
	//出借端回款計畫
	public function get_investment_amortization_table($target=array(),$investment=array()){
		
		$xirr_dates		= array($target->loan_date);
		$xirr_value		= array($investment->loan_amount*(-1));
		$schedule		= array(
			"amount"		=> $investment->loan_amount,
			"instalment"	=> $target->instalment,
			"rate"			=> $target->interest_rate,
			"total_payment"	=> 0,
			"XIRR"			=> 0,
			"date"			=> $target->loan_date,
		);
		$users			= array($target->user_id,$investment->user_id);
		$transactions 	= $this->CI->transaction_model->get_many_by(array(
			"user_from"	=> $users,
			"user_to"	=> $users,
			"target_id" => $target->id,
			"status !=" => 0
		));
		$list = array();
		
		if($transactions){
			foreach($transactions as $key => $value){
				if($value->instalment_no){
					$list[$value->instalment_no] = array(
						"instalment"		=> $value->instalment_no,//期數
						"total_payment"		=> 0,//本期應收款金額
						"repayment"			=> 0,//本期已收款金額
						"interest"			=> 0,//利息
						"principal"			=> 0,//本金
						"delay_interest"	=> 0,//應收延滯息
						"repayment_date"	=> $value->limit_date//還款日
					);
				}
			}
			foreach($transactions as $key => $value){
				switch ($value->source) {
					case SOURCE_AR_PRINCIPAL: 
						$list[$value->instalment_no]['principal'] += $value->amount;
						break;
					case SOURCE_AR_INTEREST:
						$list[$value->instalment_no]['interest'] += $value->amount;
						break;
					case SOURCE_AR_DELAYINTEREST: 
						$list[$value->instalment_no]['delay_interest'] 	+= $value->amount;
						break;
					case SOURCE_PRINCIPAL: 
					case SOURCE_DELAYINTEREST: 
					case SOURCE_INTEREST: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					default:
						break;
				}
				if($value->instalment_no){
					$list[$value->instalment_no]["total_payment"] = 
					$list[$value->instalment_no]["interest"] + 
					$list[$value->instalment_no]["principal"] + 
					$list[$value->instalment_no]["delay_interest"];
				}
			}
			foreach($list as $key => $value){
				$schedule["total_payment"] += $value["total_payment"];
				$xirr_dates[] = $value["repayment_date"];
				$xirr_value[] = $value["total_payment"];
			}
			$schedule["XIRR"] = $this->CI->financial_lib->XIRR($xirr_value,$xirr_dates);
		}
		$schedule['list'] = $list;
		return $schedule;
	}
	
	public function script_check_bidding(){
		$script  	= 3;
		$count 		= 0;
		$ids		= array();
		$targets 	= $this->CI->target_model->get_many_by(array("status"=>3,"script_status"=>0));
		if($targets && !empty($targets)){
			foreach($targets as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->target_model->update_many($ids,array("script_status"=>$script));
			if($update_rs){
				foreach($targets as $key => $value){
					$check = $this->check_bidding($value);
					if($check){
						$count++;
					}
					$this->CI->target_model->update($value->id,array("script_status"=>0));
				}
			}
		}
		return $count;
	}
	
	//審核額度
	public function script_approve_target(){
		
		$this->CI->load->library('Certification_lib');
		$targets 	= $this->CI->target_model->get_many_by(array("status"=>0,"script_status"=>0));
		$list 		= array();
		$ids		= array();
		$script  	= 4;
		$count 		= 0;
		if($targets && !empty($targets)){
			foreach($targets as $key => $value){
				$list[$value->product_id][$value->id] = $value;
				$ids[] = $value->id;
			}
			
			$rs = $this->CI->target_model->update_many($ids,array("script_status"=>$script));
			if($rs){
				foreach($list as $product_id => $targets){
					$product 				= $this->CI->product_model->get($product_id);
					$product_certification 	= json_decode($product->certifications,true);
					foreach($targets as $target_id => $value){
						$certifications 	= $this->CI->certification_lib->get_status($value->user_id,0);
						$finish		 	= true;
						foreach($certifications as $certification){
							if(in_array($certification->id,$product_certification) && $certification->user_status !="1"){
								$finish	= false;
							}
						}

						if($finish){
							$count++;
							$this->approve_target($value);
						}
						$this->CI->target_model->update($value->id,array("script_status"=>0));
					}
				}
				return $count;
			}
		}
		return false;
	}
	
	private function get_target_no(){
		$code = date("ymd").rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$result = $this->CI->target_model->get_by('target_no',$code);
		if ($result) {
			return $this->get_target_no();
		}else{
			return $code;
		}
	}

}
