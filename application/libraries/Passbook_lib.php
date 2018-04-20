<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Passbook_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/virtual_passbook_model');
		$this->CI->load->model('transaction/frozen_amount_model');
    }
	//放款
	public function recharge($transaction_id,$tx_datetime=""){ 
		$tx_datetime = $tx_datetime?$tx_datetime:date("Y-m-d H:i:s");
		if($transaction_id){
			$source 	= SOURCE_RECHARGE; 
			$where 		= array(
				"id"				=> $transaction_id, 
	 			"source"			=> $source,
				"status"			=> 1,
				"passbook_status"	=> 0
			);
			$rs = $this->CI->transaction_model->update_by($where,array("passbook_status"=>2));
			if($rs){
				$transaction = $this->CI->transaction_model->get($transaction_id);
				if($transaction && $transaction->source==$source && $transaction->passbook_status==2 ){
					$param = array(
						"virtual_account"	=> $transaction->bank_account_to,
						"transaction_id"	=> $transaction_id,
						"amount"			=> intval($transaction->amount),
						"remark"			=> 'source:'.$transaction->source,
						"tx_datetime"		=> $tx_datetime,
					);
						
					$virtual_passbook = $this->CI->virtual_passbook_model->insert($param);
					if($virtual_passbook){
						$this->CI->transaction_model->update($transaction_id,array("passbook_status"=>1));
						return $virtual_passbook;
					}
				}
			}
		}
		return false;
	}
	
	//放款
	public function lending($transaction_id){ 
		if($transaction_id){
			$source 	= SOURCE_LENDING; 
			$where 		= array(
				"id"				=> $transaction_id, 
	 			"source"			=> $source,
				"status"			=> 1,
				"passbook_status"	=> 0
			);
			$rs = $this->CI->transaction_model->update_by($where,array("passbook_status"=>2));
			if($rs){
				$transaction = $this->CI->transaction_model->get($transaction_id);
				if($transaction && $transaction->source==$source && $transaction->passbook_status==2 ){
					$param = array(
						"virtual_account"	=> $transaction->bank_account_from,
						"transaction_id"	=> $transaction_id,
						"amount"			=> intval($transaction->amount)*(-1),
						"remark"			=> 'source:'.$transaction->source,
						"tx_datetime"		=> date("Y-m-d H:i:s"),
					);
						
					$virtual_passbook = $this->CI->virtual_passbook_model->insert($param);
					if($virtual_passbook){
						$this->CI->transaction_model->update($transaction_id,array("passbook_status"=>1));
						return $virtual_passbook;
					}
				}
			}
		}
		return false;
	}
	
	//手續費
	public function platform_fee($transaction_id){
		if($transaction_id){
			$source 	= SOURCE_FEES; 
			$where 		= array(
				"id"				=> $transaction_id, 
				"source"			=> $source,
				"status"			=> 1,
				"passbook_status"	=> 0
			);
			$rs = $this->CI->transaction_model->update_by($where,array("passbook_status"=>2));
			if($rs){
				$transaction = $this->CI->transaction_model->get($transaction_id);
				if($transaction && $transaction->source == $source && $transaction->passbook_status==2 ){
					$param[] = array(
						"virtual_account"	=> $transaction->bank_account_to,
						"transaction_id"	=> $transaction_id,
						"amount"			=> intval($transaction->amount),
						"remark"			=> 'source:'.$transaction->source,
						"tx_datetime"		=> date("Y-m-d H:i:s"),
					);
					
					if(is_virtual_account($transaction->bank_account_from)){
						$param[] = array(
							"virtual_account"	=> $transaction->bank_account_from,
							"transaction_id"	=> $transaction_id,
							"amount"			=> intval($transaction->amount)*(-1),
							"remark"			=> 'source:'.$transaction->source,
							"tx_datetime"		=> date("Y-m-d H:i:s"),
						);
					}

					$virtual_passbook = $this->CI->virtual_passbook_model->insert_many($param);
					if($virtual_passbook){
						$this->CI->transaction_model->update($transaction_id,array("passbook_status"=>1));
						return $virtual_passbook;
					}
				}
			}
		}
		return false;
	}
	
	//虛擬帳戶對虛擬帳戶
	public function internal($transaction_id){
		if($transaction_id){
			$source 	= array(12,14,22,24,26,28,97,99); 
			$where 		= array(
				"id"				=> $transaction_id, 
				"source"			=> $source,
				"status"			=> 1,
				"passbook_status"	=> 0
			);
			$rs = $this->CI->transaction_model->update_by($where,array("passbook_status"=>2));
			if($rs){
				$transaction = $this->CI->transaction_model->get($transaction_id);
				if($transaction && in_array($transaction->source,$source) && $transaction->passbook_status==2 ){
					$param = array(
						array(
							"virtual_account"	=> $transaction->bank_account_from,
							"transaction_id"	=> $transaction_id,
							"amount"			=> intval($transaction->amount)*(-1),
							"remark"			=> 'source:'.$transaction->source,
							"tx_datetime"		=> date("Y-m-d H:i:s"),
						),
						array(
							"virtual_account"	=> $transaction->bank_account_to,
							"transaction_id"	=> $transaction_id,
							"amount"			=> intval($transaction->amount),
							"remark"			=> 'source:'.$transaction->source,
							"tx_datetime"		=> date("Y-m-d H:i:s"),
						),
					);
						
					$virtual_passbook = $this->CI->virtual_passbook_model->insert_many($param);
					if($virtual_passbook){
						$this->CI->transaction_model->update($transaction_id,array("passbook_status"=>1));
						return $virtual_passbook;
					}
				}
			}
		}
		return false;
	}
	
}
