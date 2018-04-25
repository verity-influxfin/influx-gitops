<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Target_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('product/product_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/virtual_account_model');
    }
	
	public function approve_target($target = array()){
		
		if(!empty($target) && $target->status=="0"){
			$user_id 	= $target->user_id;
			$product_id = $target->product_id;
			$this->CI->load->library('credit_lib');
			$credit = $this->CI->credit_lib->get_credit($user_id,$product_id);
			
			if(!$credit){
				$rs 	= $this->CI->credit_lib->approve_credit($user_id,$product_id);
				if($rs)
					$credit = $this->CI->credit_lib->get_credit($user_id,$product_id);
			}
			if($credit){
				$interest_rate	= $this->CI->credit_lib->get_rate($credit['level'],$target->instalment);
				if($interest_rate){
					$loan_amount 	= $target->amount > $credit['amount']?$credit['amount']:$target->amount;
					$platform_fee	= round($loan_amount/100*PLATFORM_FEES,0);
					$platform_fee	= $platform_fee>PLATFORM_FEES_MIN?$platform_fee:PLATFORM_FEES_MIN;
					$contract 		= $this->get_target_contract($loan_amount,$interest_rate);
					$param = array(
						"loan_amount"		=> $loan_amount,
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
							"type"				=> 1,
						);
						$this->CI->virtual_account_model->insert($virtual_data);
					}
					return $rs;
				}
			}
		}
		return false;
	}
 
	private function get_target_contract($amount,$rate){
		return "我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！我就是合約啊！！";
	}
	
	//審核額度
	public function script_approve_target(){
		$this->CI->target_model->update_by(array("status"=>0,"script_status"=>0),array("script_status"=>1));
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
					$certification 	= $this->CI->certification_lib->get_meta_status($value->user_id);
					$finish		 	= true;
					foreach($product_certification as $certification_id){
						if($certification[$certification_id]!="1"){
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
}
