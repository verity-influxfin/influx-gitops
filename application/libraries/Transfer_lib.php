<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/transfer_model');
		$this->CI->load->model('transaction/investment_model');
		$this->CI->load->library('Financial_lib');
		$this->CI->load->library('Target_lib');
		$this->CI->load->library('Prepayment_lib');
    }

	public function get_pretransfer_info($investment){
		if($investment){
			$transaction = $this->CI->transaction_model->order_by("limit_date","asc")->get_many_by(array("investment_id"=>$investment->id,"user_to"=>$investment->user_id,"status"=>array(1,2)));
			if($transaction){
				$instalment 		= 0;
				$instalment_paid 	= 0;
				$principal			= 0;
				foreach($transaction as $k => $v){
					if($v->source == SOURCE_AR_PRINCIPAL){
						$principal += $v->amount;
						$instalment		 = $v->instalment_no;
					}
					if($v->source == SOURCE_PRINCIPAL){
						$principal -= $v->amount;
						$instalment_paid = $v->instalment_no;
					}
				}
				$instalment = $instalment - $instalment_paid;
				$fee 		= round($principal*DEBT_TRANSFER_FEES/100,0);
				$data 		= array(
					"instalment"				=> $instalment,
					"principal"					=> $principal,
					"fee"						=> $fee,
					"debt_transfer_contract" 	=> "我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約，我是合約",
				);
				return $data;
			}

		}
		return false;
	}
	
	public function apply_transfer($investment){
		if($investment && $investment->status==3){
			$info  = $this->get_pretransfer_info($investment);
			if($info){
				$param = array(
					"transfer_status"		=> 1,
					"transfer_expire_time"	=> strtotime("+2 days", time()),
					"transfer_amount"		=> $info["principal"],
					"transfer_fee"			=> $info["fee"],
					"transfer_launch_times"	=> 1,
					"transfer_contract"		=> $info["debt_transfer_contract"],
				);
				$rs = $this->CI->investment_model->update($investment->id,$param);
				return $rs;
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
