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
						"contract"				=> $info["debt_transfer_contract"],
					);
					$res = $this->CI->transfer_model->insert($param);
					return $res;
				}
			}
		}
		return false;
	}

	public function get_transfer_list(){
		$list 	= array();
		$where 	= array(
			"status" => 0
		);
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
	
}
