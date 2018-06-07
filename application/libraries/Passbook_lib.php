<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Passbook_lib{
	
	public $accounts_receivable = array(//應收帳款
		SOURCE_AR_FEES,
		SOURCE_AR_PRINCIPAL,
		SOURCE_AR_INTEREST,
		SOURCE_AR_DAMAGE,
		SOURCE_AR_DELAYINTEREST
	);

	public $credit = array(//貸方資產增加
		SOURCE_RECHARGE,
		SOURCE_WITHDRAW,
		SOURCE_LENDING,
		SOURCE_FEES,
		SOURCE_SUBLOAN_FEE,
		SOURCE_TRANSFER_FEE,
		SOURCE_PREPAYMENT_ALLOWANCE,
		SOURCE_PREPAYMENT_DAMAGE,
		SOURCE_PRINCIPAL,
		SOURCE_INTEREST,
		SOURCE_DAMAGE,
		SOURCE_DELAYINTEREST,
	);

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/virtual_passbook_model');
    }

	//入帳
	public function enter_account($transaction_id,$tx_datetime=""){
		if($transaction_id){
			$tx_datetime	= empty($tx_datetime)?date("Y-m-d H:i:s"):date("Y-m-d H:i:s",strtotime($tx_datetime));
			$transaction 	= $this->CI->transaction_model->get($transaction_id);
			if($transaction && in_array($transaction->source,$this->credit) && $transaction->status==2 && $transaction->passbook_status==0 ){
				$this->CI->transaction_model->update($transaction_id,array("passbook_status"=>2));//lock

				if(is_virtual_account($transaction->bank_account_to)){
					$param[] = array(
						"virtual_account"	=> $transaction->bank_account_to,
						"transaction_id"	=> $transaction_id,
						"amount"			=> intval($transaction->amount),
						"remark"			=> json_encode(array("source"=>$transaction->source)),
						"tx_datetime"		=> $tx_datetime,
					);
				}
				
				if(is_virtual_account($transaction->bank_account_from)){
					$param[] = array(
						"virtual_account"	=> $transaction->bank_account_from,
						"transaction_id"	=> $transaction_id,
						"amount"			=> intval($transaction->amount)*(-1),
						"remark"			=> json_encode(array("source"=>$transaction->source)),
						"tx_datetime"		=> $tx_datetime,
					);
				}

				$virtual_passbook = $this->CI->virtual_passbook_model->insert_many($param);
				if($virtual_passbook){
					$this->CI->transaction_model->update($transaction_id,array("passbook_status"=>1));
					return $virtual_passbook;
				}
			}
		}
		return false;
	}
	

	
	//取得資金資料
	public function get_passbook_list($virtual_account=""){
		$list = array();
		if($virtual_account){
			$virtual_passbook 	= $this->CI->virtual_passbook_model->order_by("tx_datetime,created_at","asc")->get_many_by(array("virtual_account"=>$virtual_account));
			if($virtual_passbook){
				$total 	= 0;
				foreach($virtual_passbook as $key => $value){
					$total	+= $value->amount;
					$list[] = array(
						"amount" 		=> $value->amount,
						"bank_amount"	=> $total,
						"remark"		=> $value->remark,
						"tx_datetime"	=> $value->tx_datetime,
						"created_at"	=> $value->created_at,
						"action"		=> $value->amount>0?"debit":"credit",
					);
				}
			}
		}
		return $list;
	}
	
}
