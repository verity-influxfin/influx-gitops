<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Target_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('product/product_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->library('credit_lib');
    }
	
	//核可額度利率
	public function approve_target($target = array()){
		
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
						$contract 		= $this->get_target_contract($loan_amount,$interest_rate);
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
						}
					}else{
						$param = array(
							"loan_amount"		=> 0,
							"status"			=> "8",
							"remark"			=> "credit not enough",
						);
						$rs = $this->CI->target_model->update($target->id,$param);
					}
					
					return $rs;
				}
			}
		}
		return false;
	}

	public function add_subloan_target($target,$subloan){
		
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
					$contract 		= $this->get_target_contract($subloan["amount"],$interest_rate);
					$target_no 		= $this->get_target_no();
					$param = array(
						"product_id"		=> $product_id,
						"user_id"			=> $user_id,
						"target_no"			=> $target_no,
						"amount"			=> $subloan["amount"],
						"loan_amount"		=> $subloan["amount"],
						"instalment"		=> $subloan["instalment"],
						"repayment"			=> $subloan["repayment"],
						"credit_level"		=> $credit['level'],
						"platform_fee"		=> $subloan["platform_fee"],
						"interest_rate"		=> $interest_rate,
						"virtual_account" 	=> CATHAY_VIRTUAL_CODE.$target_no,
						"contract"			=> $contract,
						"status"			=> "1",
						"sub_status"		=> "8",
						"remark"			=> "轉換產品",
					);

					$rs = $this->CI->target_model->insert($param);
					if($rs){
						$virtual_data = array(
							"user_id"			=> $user_id,				
							"virtual_account"	=> $param['virtual_account'],
							"investor"			=> 0,
						);
						$this->CI->virtual_account_model->insert($virtual_data);
					}
					return $rs;
				}
			}

		return false;
	}
	
	private function get_target_contract($amount,$rate){
		return "我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！";
	}
	
	//審核額度
	public function script_approve_target(){
		$rs = $this->CI->target_model->update_by(array("status"=>0,"script_status"=>0),array("script_status"=>1));
		$targets 	= $this->CI->target_model->get_many_by(array("script_status"=>1));
		$list 		= array();
		if($targets && !empty($targets)){
			$this->CI->load->library('Certification_lib');
			$count = 0;
			foreach($targets as $key => $value){
				$list[$value->product_id][$value->id] = $value;
			}
			
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
		return false;
	}
	
	//借款端還款計畫
	public function get_amortization_table($target=array()){

		$schedule		= array(
			"amount"		=> $target->loan_amount,
			"instalment"	=> $target->instalment,
			"rate"			=> $target->interest_rate,
			"total_payment"	=> 0,
			"date"			=> "",
			"sub_loan_fees"	=> 0,
			"platform_fees"	=> 0,
			"list"			=> array(),
		);
		$transactions 	= $this->CI->transaction_model->get_many_by(array("target_id" => $target->id,"status !=" => 0));
		$list = array();
		
		if($transactions){
			foreach($transactions as $key => $value){
				
				if($value->instalment_no && !isset($list[$value->instalment_no])){
					$list[$value->instalment_no] = array(
						"instalment"		=> $value->instalment_no,//期數
						"total_payment"		=> 0,//本期還款金額
						"interest"			=> 0,//還款利息
						"principal"			=> 0,//還款本金
						"delay_interest"	=> 0,//延滯息
						"liquidated_damages"=> 0,//違約金
						"repayment"			=> 0,//已還款金額
						"repayment_date"	=> $value->limit_date
					);
				}
				
				switch($value->source){
					case SOURCE_LENDING: 
						$schedule["date"] 	= $value->entering_date;
						break;
					case SOURCE_AR_PRINCIPAL: 
						$list[$value->instalment_no]['principal'] 		+= $value->amount;
						$list[$value->instalment_no]['total_payment'] 	+= $value->amount;
						$schedule["total_payment"] += $value->amount;
						break;
					case SOURCE_PRINCIPAL: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					case SOURCE_AR_INTEREST: 
						$list[$value->instalment_no]['interest'] 		+= $value->amount;
						$list[$value->instalment_no]['total_payment'] 	+= $value->amount;
						$schedule["total_payment"] += $value->amount;
						break;
					case SOURCE_INTEREST: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					case SOURCE_AR_DAMAGE: 
						$list[$value->instalment_no]['liquidated_damages'] 	+= $value->amount;
						$list[$value->instalment_no]['total_payment'] 		+= $value->amount;
						$schedule["total_payment"] += $value->amount;
						break;
					case SOURCE_DAMAGE: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					case SOURCE_AR_DELAYINTEREST: 
						$list[$value->instalment_no]['delay_interest'] 	+= $value->amount;
						$list[$value->instalment_no]['total_payment'] 	+= $value->amount;
						$schedule["total_payment"] += $value->amount;
						break;
					case SOURCE_DELAYINTEREST: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					case SOURCE_SUBLOAN_FEE: 
						$schedule["sub_loan_fees"] += $value->amount;
						break;
					case SOURCE_FEES: 
						if($value->user_from == $target->user_id){
							$schedule["platform_fees"] += $value->amount;
						}
						break;
				}

			}
		}
		$schedule['list'] = $list;
		return $schedule;
	}
	
	//出借端回款計畫
	public function get_investment_amortization_table($target=array(),$investment=array()){
		
		$xirr_dates		= array();
		$xirr_value		= array();
		$schedule		= array(
			"amount"		=> $investment->loan_amount,
			"instalment"	=> $target->instalment,
			"rate"			=> $target->interest_rate,
			"total_payment"	=> 0,
			"date"			=> "",
		);
		$users			= array($target->user_id,$investment->user_id);
		$transactions 	= $this->CI->transaction_model->get_many_by(array(	"target_id" => $target->id,"user_from"=>$users,"user_to"=>$users));
		$list = array();
		
		if($transactions){
			foreach($transactions as $key => $value){
				if($value->instalment_no && !isset($list[$value->instalment_no])){
					$list[$value->instalment_no] = array(
						"instalment"		=> $value->instalment_no,
						"total_payment"		=> 0,
						"interest"			=> 0,
						"principal"			=> 0,
						"repayment"			=> 0,
						"repayment_date"	=> $value->limit_date
					);
				}
				switch ($value->source) {
					case SOURCE_LENDING: 
						$schedule["date"] 	= $value->entering_date;
						break;
					case SOURCE_AR_PRINCIPAL: 
						$list[$value->instalment_no]['principal'] += $value->amount;
						$schedule["total_payment"] += $value->amount;
						$list[$value->instalment_no]['repayment_date'] = $value->limit_date;
						break;
					case SOURCE_AR_INTEREST:
						$list[$value->instalment_no]['interest'] += $value->amount;
						$schedule["total_payment"] += $value->amount;
						$list[$value->instalment_no]['repayment_date'] = $value->limit_date;
						break;					
					case SOURCE_PRINCIPAL: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;					
					case SOURCE_INTEREST: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						break;
					default:
						break;
				}
				if($value->instalment_no){
					$list[$value->instalment_no]['total_payment'] = $list[$value->instalment_no]['interest'] + $list[$value->instalment_no]['principal'];
				}
			}
		}
		$schedule['list'] = $list;
		return $schedule;
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
