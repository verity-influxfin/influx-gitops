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
		SOURCE_TRANSFER,
		SOURCE_VERIFY_FEE,
		SOURCE_VERIFY_FEE_R,
		SOURCE_REMITTANCE_FEE,
		SOURCE_REMITTANCE_FEE_R,
	);

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/virtual_passbook_model');
    }

	//入帳
	public function enter_account($transaction_id,$tx_datetime=''){
		if($transaction_id){
			$tx_datetime	= empty($tx_datetime)?date('Y-m-d H:i:s'):date('Y-m-d H:i:s',strtotime($tx_datetime));
			$transaction 	= $this->CI->transaction_model->get($transaction_id);
			if($transaction && in_array($transaction->source,$this->credit) && $transaction->status==2 && $transaction->passbook_status==0 ){
				$this->CI->transaction_model->update($transaction_id,['passbook_status'=>2]);//lock

				if(is_virtual_account($transaction->bank_account_to)){
					$param[] = array(
						'virtual_account'	=> $transaction->bank_account_to,
						'transaction_id'	=> $transaction_id,
						'amount'			=> intval($transaction->amount),
						'remark'			=> json_encode([
							'source'	=> $transaction->source,
							'target_id'	=> $transaction->target_id
						]),
						'tx_datetime'		=> $tx_datetime,
					);
				}
				
				if(is_virtual_account($transaction->bank_account_from)){
					$param[] = array(
						'virtual_account'	=> $transaction->bank_account_from,
						'transaction_id'	=> $transaction_id,
						'amount'			=> intval($transaction->amount)*(-1),
						'remark'			=> json_encode([
							'source'	=> $transaction->source,
							'target_id'	=> $transaction->target_id
						]),
						'tx_datetime'		=> $tx_datetime,
					);
				}

				$virtual_passbook = $this->CI->virtual_passbook_model->insert_many($param);
				if($virtual_passbook){
					$this->CI->transaction_model->update($transaction_id,array('passbook_status'=>1));
					return $virtual_passbook;
				}
			}
		}
		return false;
	}
	
	//取得資金資料
	public function get_passbook_list($virtual_account=''){
		$list = [];
		if($virtual_account){
			$virtual_passbook 	= $this->CI->virtual_passbook_model->order_by('tx_datetime,created_at','desc')->get_many_by([
				'virtual_account' => $virtual_account
			]);
			if($virtual_passbook){
				$total 	= 0;
				foreach($virtual_passbook as $key => $value){
					$total	+= $value->amount;
					$list[] = array(
						'amount' 		=> intval($value->amount),
						'bank_amount'	=> $total,
						'remark'		=> $value->remark,
						'tx_datetime'	=> $value->tx_datetime,
						'created_at'	=> intval($value->created_at),
						'action'		=> intval($value->amount)>0?'debit':'credit',
					);
				}
			}
		}
		return $list;
	}

	//餘額大餘1000通知
	public function script_alert_account_remaining(){
		$count  	= 0;
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->library('Notification_lib');
		$virtual_passbook = $this->CI->virtual_passbook_model->order_by('virtual_account','ASC')->get_many_by(array(
			'virtual_account <>' 	=> PLATFORM_VIRTUAL_ACCOUNT,
			'tx_datetime <=' 		=> date('Y-m-d H:i:s'),
		));
		if(!empty($virtual_passbook)){
			foreach($virtual_passbook as $key => $value){
				if(!isset($list[$value->virtual_account])){
					$list[$value->virtual_account] = 0;
					$info[$value->virtual_account] = $this->CI->virtual_account_model->get_by(array(
						'virtual_account' => $value->virtual_account
					));
				}
				$list[$value->virtual_account] += $value->amount;
			}
			foreach($list as $key => $value){
				if($value>=1000){
					$this->CI->notification_lib->account_remaining($info[$key]->user_id,$info[$key]->investor);
					$count++;
				}
				
			}
		}
		return $count;
	}
	
}
