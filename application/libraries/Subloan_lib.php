<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subloan_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/target_model');
		$this->CI->load->model('loan/subloan_model');
		$this->CI->load->library('Financial_lib');
		$this->CI->load->library('Target_lib');
		$this->CI->load->library('Prepayment_lib');
    }

	public function get_info($target=array()){
		$info = $this->CI->prepayment_lib->get_prepayment_info($target);
		if($info){
			$total 					= $info["total"];
			$info["sub_loan_fees"] 	= intval(round( $total * SUB_LOAN_FEES / 100 ,0));
			$total 					+= $info["sub_loan_fees"];
			$info["platform_fees"] 	= intval(round( $total * PLATFORM_FEES / (100-PLATFORM_FEES) ,0));
			$info["platform_fees"] 	= $info["platform_fees"] > PLATFORM_FEES_MIN?$info["platform_fees"]:PLATFORM_FEES_MIN;
			$total 					+= $info["platform_fees"];
			$info["total"]			= $total;
		}  
		return $info;
	}
	
	public function apply_subloan($target,$data){
		if($target && $target->status==5){
			$info  = $this->get_info($target);
			if($info){
				$param = array(
					"target_id"			=> $target->id,
					"settlement_date"	=> $info["settlement_date"],
					"amount"			=> $info["total"],
					"platform_fee"		=> $info["platform_fees"],
					"instalment"		=> $data["instalment"],
					"repayment"			=> $data["repayment"]
				);
				$new_target_id = $this->CI->target_lib->add_subloan_target($target,$param);
				if($new_target_id){
					$param["new_target_id"] = $new_target_id;
					$rs = $this->CI->subloan_model->insert($param);
					if($rs){
						$this->CI->target_model->update($target->id,array("sub_status"=>1));
						return $rs;
					}
				}
			}
		}
		return false;
	}

	public function signing_subloan($subloan,$data){

		if($subloan && $subloan->status==0){
			$target = $this->CI->target_model->get($subloan->new_target_id);
			if($target && $target->status==1){
				$param = array(
					"person_image"	=> $data["person_image"],
					"status"		=> 2
				);
				$rs = $this->CI->target_model->update($target->id,$param);
				if($rs){
					$this->CI->subloan_model->update($subloan->id,array("status"=>1));
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function get_subloan($target){
		if($target){
			$where 		= array("status !="=>8,"target_id"=>$target->id);
			$subloan	= $this->CI->subloan_model->get_by($where);
			if($subloan){
				return $subloan;
			}
		}
		return false;
	}
	
	public function cancel_subloan($subloan){
		if($subloan && $subloan->status==0){
			$rs = $this->CI->subloan_model->update($subloan->id,array("status"=>8));
			if($rs){
				$this->CI->target_model->update($subloan->target_id,array("sub_status"=>0));
				$this->CI->target_model->update($subloan->new_target_id,array("status"=>8));
				return $rs;
			}
		}
		return false;
	}
	
}
