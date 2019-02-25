<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('loan/investment_model');
		$this->CI->load->model('transaction/frozen_amount_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->library('Passbook_lib');
		$this->CI->load->library('Notification_lib');
    }

	//取得資金資料
	public function get_virtual_funds($virtual_account=''){
		if($virtual_account){
			$total  = 0;
			$frozen = 0;
			$last_recharge_date	= '';
			$this->CI->load->model('transaction/virtual_passbook_model');
			$virtual_passbook 	= $this->CI->virtual_passbook_model->get_many_by(array('virtual_account' => $virtual_account));
			$frozen_amount 		= $this->CI->frozen_amount_model->get_many_by(array('virtual_account' => $virtual_account,'status' => 1));
			if($virtual_passbook){
				foreach($virtual_passbook as $key => $value){
					$total = $total + intval($value->amount);
					if(intval($value->amount) > 0 && $value->tx_datetime > $last_recharge_date){
						$last_recharge_date = $value->tx_datetime;
					}
				}
			}
			
			if($frozen_amount){
				foreach($frozen_amount as $key => $value){
					$frozen = $frozen + intval($value->amount);
				}
			}
			
			return array('total'=>$total,'last_recharge_date'=>$last_recharge_date,'frozen'=>$frozen);
		}
		return false;
	}
	
	//儲值
	public function recharge($payment_id=0){
		$date = get_entering_date();
		if($payment_id){
			$this->CI->load->model('transaction/payment_model');
			$payment 	= $this->CI->payment_model->get($payment_id);
			if($payment->status != '1' && $payment->amount > 0  && !empty($payment->virtual_account)){
				$rs = $this->CI->payment_model->update($payment_id,array('status'=>1));
				if($rs){
					$user_account = $this->CI->virtual_account_model->get_by(array('virtual_account'=>$payment->virtual_account));
					if($user_account){
						$bank 			= bankaccount_substr($payment->bank_acc);
						$bank_account	= $bank['bank_account'];
						$transaction	= array(
							'source'			=> SOURCE_RECHARGE,
							'entering_date'		=> $date,
							'user_from'			=> $user_account->user_id,
							'bank_account_from'	=> $bank_account,
							'amount'			=> intval($payment->amount),
							'user_to'			=> $user_account->user_id,
							'bank_account_to'	=> $payment->virtual_account,
							'status'			=> 2
						);
						$transaction_id = $this->CI->transaction_model->insert($transaction);
						if($transaction_id){
							$this->CI->notification_lib->recharge_success($user_account->user_id,$user_account->investor);
							$virtual_passbook = $this->CI->passbook_lib->enter_account($transaction_id,$payment->tx_datetime);
							return $virtual_passbook;
						}
					}
				}
			}
		}
		return false;
	}
	
	function verify_fee($payment){
		$date = get_entering_date();
		if($payment && $payment->status != '1'){
			$this->CI->load->model('transaction/payment_model');
			$transaction = array();
			switch ($payment->amount) {
				case -1: 
					$transaction[]	= array(
						'source'			=> SOURCE_VERIFY_FEE,
						'entering_date'		=> $date,
						'user_from'			=> 0,
						'bank_account_from'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'amount'			=> 1,
						'user_to'			=> 0,
						'bank_account_to'	=> BANK_COST_ACCOUNT,
						'status'			=> 2
					);
					break;
				case -30:
					$transaction[]	= array(
						'source'			=> SOURCE_REMITTANCE_FEE,
						'entering_date'		=> $date,
						'user_from'			=> 0,
						'bank_account_from'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'amount'			=> 30,
						'user_to'			=> 0,
						'bank_account_to'	=> BANK_COST_ACCOUNT,
						'status'			=> 2
					);
					break;
				case -31: 
					$transaction[]	= array(
						'source'			=> SOURCE_VERIFY_FEE,
						'entering_date'		=> $date,
						'user_from'			=> 0,
						'bank_account_from'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'amount'			=> 1,
						'user_to'			=> 0,
						'bank_account_to'	=> BANK_COST_ACCOUNT,
						'status'			=> 2
					);
					$transaction[]	= array(
						'source'			=> SOURCE_REMITTANCE_FEE,
						'entering_date'		=> $date,
						'user_from'			=> 0,
						'bank_account_from'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'amount'			=> 30,
						'user_to'			=> 0,
						'bank_account_to'	=> BANK_COST_ACCOUNT,
						'status'			=> 2
					);
					break;
				case 1: 
					$transaction[]	= array(
						'source'			=> SOURCE_VERIFY_FEE_R,
						'entering_date'		=> $date,
						'user_from'			=> 0,
						'bank_account_from'	=> BANK_COST_ACCOUNT,
						'amount'			=> 1,
						'user_to'			=> 0,
						'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'status'			=> 2
					);
					break;
				case 30: 
					$transaction[]	= array(
						'source'			=> SOURCE_REMITTANCE_FEE_R,
						'entering_date'		=> $date,
						'user_from'			=> 0,
						'bank_account_from'	=> BANK_COST_ACCOUNT,
						'amount'			=> 30,
						'user_to'			=> 0,
						'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'status'			=> 2
					);
					break;
				default:
					break;
			}
			if($transaction){
				$this->CI->payment_model->update($payment->id,array('status'=>1));
				$rs  = $this->CI->transaction_model->insert_many($transaction);
				if($rs && is_array($rs)){
					foreach($rs as $key => $value){
						$this->CI->passbook_lib->enter_account($value);
					}
					return true;
				}
			}
		}
		return false;
	}
	
	//提領
	public function withdraw($user_id,$amount=0,$investor=1){
		if($user_id && $amount > 31 ){
			$virtual_account = $this->CI->virtual_account_model->get_by([
				'status'	=> 1,
				'investor'	=> $investor,
				'user_id'	=> $user_id
			]);
			if($virtual_account){
				$withdraw = false;
				$this->CI->virtual_account_model->update($virtual_account->id,['status'=>2]);
				$funds = $this->get_virtual_funds($virtual_account->virtual_account);
				$total = $funds['total'] - $funds['frozen'];
				if(intval($total)-intval($amount)>=0){
					$param = array(
						'type'				=> 3,
						'virtual_account'	=> $virtual_account->virtual_account,
						'amount'			=> intval($amount),
						'tx_datetime'		=> date('Y-m-d :H:i:s'),
					);
					$rs = $this->CI->frozen_amount_model->insert($param);
					if($rs){
						$data = array(
							'user_id'			=> $user_id,
							'investor'			=> $investor,
							'virtual_account' 	=> $virtual_account->virtual_account,
							'amount'			=> $amount,
							'frozen_id'			=> $rs,
						);
						$this->CI->load->model('transaction/withdraw_model');
						$withdraw = $this->CI->withdraw_model->insert($data);
					}
				}
				$this->CI->virtual_account_model->update($virtual_account->id,array('status'=>1));
				return $withdraw;
			}
		}
		return false;
	}

	//平台提領
	public function platform_withdraw($amount=0){
		if($amount > 31 ){
			$withdraw = false;
			$funds = $this->get_virtual_funds(PLATFORM_VIRTUAL_ACCOUNT);
			$total = $funds['total'] - $funds['frozen'];
			if(intval($total)-intval($amount)>=0){
				$param = array(
					'type'				=> 3,
					'virtual_account'	=> PLATFORM_VIRTUAL_ACCOUNT,
					'amount'			=> intval($amount),
					'tx_datetime'		=> date('Y-m-d :H:i:s'),
				);
				$rs = $this->CI->frozen_amount_model->insert($param);
				if($rs){
					$data = array(
						'user_id'			=> 0,
						'investor'			=> 0,
						'virtual_account' 	=> PLATFORM_VIRTUAL_ACCOUNT,
						'amount'			=> $amount,
						'frozen_id'			=> $rs,
					);
					$this->CI->load->model('transaction/withdraw_model');
					$withdraw = $this->CI->withdraw_model->insert($data);
				}
			}
			return $withdraw;
		}
		return false;
	}
	
	//提領成功
	function withdraw_success($withdraw_id){
		$date 			= get_entering_date();
		$transaction 	= array();
		if($withdraw_id){
			$this->CI->load->model('transaction/withdraw_model');
			$withdraw = $this->CI->withdraw_model->get($withdraw_id);
			if( $withdraw && $withdraw->status == 2 ){
				if($withdraw->user_id==0 && $withdraw->virtual_account==PLATFORM_VIRTUAL_ACCOUNT){
					//放款
					$transaction		= array(
						'source'			=> SOURCE_WITHDRAW,
						'entering_date'		=> $date,
						'user_from'			=> $withdraw->user_id,
						'bank_account_from'	=> PLATFORM_VIRTUAL_ACCOUNT,
						'amount'			=> intval($withdraw->amount),
						'user_to'			=> $withdraw->user_id,
						'bank_account_to'	=> CATHAY_COMPANY_ACCOUNT,
						'status'			=> 2
					);

					$rs  = $this->CI->transaction_model->insert($transaction);
					if($rs){
						$this->CI->withdraw_model->update($withdraw_id,array('status'=>1,'transaction_id'=>$rs));
						$this->CI->frozen_amount_model->update($withdraw->frozen_id,array('status'=>0));
						$this->CI->passbook_lib->enter_account($rs);
						return true;
					}
				}else{
					$virtual_account 	= $this->CI->virtual_account_model->get_by(array('user_id'=>$withdraw->user_id,'virtual_account'=>$withdraw->virtual_account,'status'=>1));
					if($virtual_account){
						$where = array(
							'user_id'	=> $withdraw->user_id,
							'status'	=> 1,
							'verify'	=> 1,
							'investor'	=> $withdraw->investor
						);

						$this->CI->load->model('user/user_bankaccount_model');
						$user_bankaccount 	= $this->CI->user_bankaccount_model->get_by($where);
						if($user_bankaccount){
							$this->CI->notification_lib->withdraw_success($withdraw->user_id,$withdraw->investor,$withdraw->amount,$user_bankaccount->bank_account);

							//放款
							$transaction		= array(
								'source'			=> SOURCE_WITHDRAW,
								'entering_date'		=> $date,
								'user_from'			=> $withdraw->user_id,
								'bank_account_from'	=> $virtual_account->virtual_account,
								'amount'			=> intval($withdraw->amount),
								'user_to'			=> $withdraw->user_id,
								'bank_account_to'	=> $user_bankaccount->bank_account,
								'status'			=> 2
							);

							$rs  = $this->CI->transaction_model->insert($transaction);
							if($rs){
								$this->CI->withdraw_model->update($withdraw_id,array('status'=>1,'transaction_id'=>$rs));
								$this->CI->frozen_amount_model->update($withdraw->frozen_id,array('status'=>0));
								$this->CI->passbook_lib->enter_account($rs);
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}
	
	//放款成功
	function lending_success($target_id,$admin_id=0){
		$date 			= get_entering_date();
		$transaction 	= [];
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 4 && $target->loan_status == 3){
				$target_account 	= $this->CI->virtual_account_model->get_by([
					'user_id'	=> $target->user_id,
					'investor'	=> 0,
					'status'	=> 1
				]);
				if($target_account){

					$this->CI->load->model('user/user_bankaccount_model');
					$user_bankaccount 	= $this->CI->user_bankaccount_model->get_by([
						'user_id'	=> $target->user_id,
						'status'	=> 1,
						'verify'	=> 1,
						'investor'	=> 0
					]);
					if($user_bankaccount){
						$this->CI->load->library('sms_lib');
						$this->CI->sms_lib->lending_success($target->user_id,0,$target->target_no,$target->loan_amount,$user_bankaccount->bank_account);
						$this->CI->notification_lib->lending_success($target->user_id,0,$target->target_no,$target->loan_amount,$user_bankaccount->bank_account);
						//手續費
						$transaction[]	= [
							'source'			=> SOURCE_FEES,
							'entering_date'		=> $date,
							'user_from'			=> $target->user_id,
							'bank_account_from'	=> $user_bankaccount->bank_account,
							'amount'			=> intval($target->platform_fee),
							'target_id'			=> $target->id,
							'instalment_no'		=> 0,
							'user_to'			=> 0,
							'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
							'status'			=> 2
						];
						

						$investment_ids = [];
						$frozen_ids 	= [];
						$investments 	= $this->CI->investment_model->get_many_by([
							'target_id'		=> $target->id,
							'status'		=> 2,
							'loan_amount >'	=> 0,
							'frozen_status'	=> 1
						]);
						if($investments){
							foreach($investments as $key => $value){
								$investment_ids[]	= $value->id;
								$frozen_ids[]		= $value->frozen_id;
								$virtual_account 	= $this->CI->virtual_account_model->get_by(array('user_id'=>$value->user_id,'investor'=>1,'status'=>1));
								$this->CI->notification_lib->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,'');
								$this->CI->sms_lib->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,'');
								
								//放款
								$transaction[]		= [
									'source'			=> SOURCE_LENDING,
									'entering_date'		=> $date,
									'user_from'			=> $value->user_id,
									'bank_account_from'	=> $virtual_account->virtual_account,
									'amount'			=> intval($value->loan_amount),
									'target_id'			=> $target->id,
									'investment_id'		=> $value->id,
									'instalment_no'		=> 0,
									'user_to'			=> $target->user_id,
									'bank_account_to'	=> $user_bankaccount->bank_account,
									'status'			=> 2
								];

								
								//攤還表
								$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target->instalment,$target->interest_rate,$date,$target->repayment);
								if($amortization_schedule){
									foreach($amortization_schedule['schedule'] as $instalment_no => $payment){
										$transaction[]	= [
											'source'			=> SOURCE_AR_PRINCIPAL,
											'entering_date'		=> $date,
											'user_from'			=> $target->user_id,
											'bank_account_from'	=> $target_account->virtual_account,
											'amount'			=> intval($payment['principal']),
											'target_id'			=> $target->id,
											'investment_id'		=> $value->id,
											'instalment_no'		=> $instalment_no,
											'user_to'			=> $value->user_id,
											'bank_account_to'	=> $virtual_account->virtual_account,
											'limit_date'		=> $payment['repayment_date'],
										];
										
										$transaction[]	= [
											'source'			=> SOURCE_AR_INTEREST,
											'entering_date'		=> $date,
											'user_from'			=> $target->user_id,
											'bank_account_from'	=> $target_account->virtual_account,
											'amount'			=> intval($payment['interest']),
											'target_id'			=> $target->id,
											'investment_id'		=> $value->id,
											'instalment_no'		=> $instalment_no,
											'user_to'			=> $value->user_id,
											'bank_account_to'	=> $virtual_account->virtual_account,
											'limit_date'		=> $payment['repayment_date'],
										];
										
										$total 	= intval($payment['interest'])+intval($payment['principal']);
										$ar_fee = intval(round($total/100*REPAYMENT_PLATFORM_FEES,0));
										$transaction[]	= [
											'source'			=> SOURCE_AR_FEES,
											'entering_date'		=> $date,
											'user_from'			=> $value->user_id,
											'bank_account_from'	=> $virtual_account->virtual_account,
											'amount'			=> $ar_fee,
											'target_id'			=> $target->id,
											'investment_id'		=> $value->id,
											'instalment_no'		=> $instalment_no,
											'user_to'			=> 0,
											'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
											'limit_date'		=> $payment['repayment_date'],
										];
									}
								}
							}
							
							$rs  = $this->CI->transaction_model->insert_many($transaction);
							if($rs && is_array($rs)){
								$target_update_param = [
									'status'		=> 5,
									'loan_status'	=> 1,
									'loan_date'		=> $date
								];
								$this->CI->target_model->update($target_id,$target_update_param);
								$this->CI->load->library('target_lib');
								$this->CI->target_lib->insert_change_log($target_id,$target_update_param,0,$admin_id);
								
								$this->CI->investment_model->update_many($investment_ids,['status'=>3]);
								foreach($investment_ids as $k => $investment_id){
									$this->CI->target_lib->insert_investment_change_log($investment_id,['status'=>3],0,$admin_id);
								}
								$this->CI->frozen_amount_model->update_many($frozen_ids,['status'=>0]);
								foreach($rs as $key => $value){
									$this->CI->passbook_lib->enter_account($value);
								}
								
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}

	//放款失敗重新操作
	function lending_failed($target_id,$admin_id=0){
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 4 && $target->loan_status == 3){
				$target_update_param = array(
					'loan_status'	=> 2,
				);
				$this->CI->load->library('target_lib');
				$this->CI->target_lib->insert_change_log($target_id,$target_update_param,0,$admin_id);
				return $this->CI->target_model->update($target_id,$target_update_param);
			}
		}
	}
	
	//債轉成功
	function transfer_success($transfer_id,$admin_id=0){
		$date 			= get_entering_date();
		$transaction 	= [];
		if($transfer_id){
			$this->CI->load->model('loan/transfer_model');
			$this->CI->load->model('loan/transfer_investment_model');
			$transfer = $this->CI->transfer_model->get($transfer_id);
			if( $transfer && $transfer->status == 1){
				$target 	= $this->CI->target_model->get($transfer->target_id);
				$investment = $this->CI->investment_model->get($transfer->investment_id);
				if($investment && $investment->status==3 && $target && $target->script_status==0){
					$this->CI->target_model->update($target->id,array('script_status'=>10));
					$transaction_list 	= $this->CI->transaction_model->get_many_by(array(
						'investment_id'	=> $investment->id,
						'status'		=> 1
					));
					$principal			= 0;
					if($transaction_list){
						foreach($transaction_list as $k => $v){
							if($v->source == SOURCE_AR_PRINCIPAL){
								$principal 	+= $v->amount;
							}
						}
					}
					
					$transfer_investments 	= $this->CI->transfer_investment_model->get_by(array(
						'transfer_id'	=> $transfer_id,
						'status'		=> 2,
						'frozen_status'	=> 1
					));
						
					if($principal==$transfer->principal && $transfer_investments){

						$investment_data = array(
							'target_id'		=> $investment->target_id,
							'user_id'		=> $transfer_investments->user_id,
							'amount'		=> intval($transfer->principal),
							'loan_amount'	=> intval($transfer->principal),
							'contract_id'	=> $transfer_investments->contract_id,
							'status'		=> $investment->status,
						);
						
						$new_investment = $this->CI->investment_model->insert($investment_data);
						if($new_investment){
							
							$this->CI->investment_model->update($investment->id,['status'=>10,'transfer_status'=>2]);
							$this->CI->load->library('target_lib');
							$this->CI->target_lib->insert_investment_change_log($investment->id,['status'=>10,'transfer_status'=>2],0,$admin_id);
							$this->CI->transfer_investment_model->update($transfer_investments->id,['status'=>10]);
							$this->CI->frozen_amount_model->update($transfer_investments->frozen_id,['status'=>0]);
							
							$transfer_account 	= $this->CI->virtual_account_model->get_by(['user_id'=>$investment->user_id,'investor'=>1]);
							$virtual_account 	= $this->CI->virtual_account_model->get_by(['user_id'=>$transfer_investments->user_id,'investor'=>1]);
							if($transfer_account && $virtual_account){
							
								//手續費
								$transaction[]	= [
									'source'			=> SOURCE_TRANSFER_FEE,
									'entering_date'		=> $date,
									'user_from'			=> $investment->user_id,
									'bank_account_from'	=> $transfer_account->virtual_account,
									'amount'			=> $transfer->transfer_fee,
									'target_id'			=> $target->id,
									'investment_id'		=> $new_investment,
									'instalment_no'		=> 0,
									'user_to'			=> 0,
									'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
									'status'			=> 2
								];
								
								//放款
								$transaction[]		= [
									'source'			=> SOURCE_TRANSFER,
									'entering_date'		=> $date,
									'user_from'			=> $transfer_investments->user_id,
									'bank_account_from'	=> $virtual_account->virtual_account,
									'amount'			=> intval($transfer->amount),
									'target_id'			=> $target->id,
									'investment_id'		=> $new_investment,
									'instalment_no'		=> 0,
									'user_to'			=> $investment->user_id,
									'bank_account_to'	=> $transfer_account->virtual_account,
									'status'			=> 2
								];

								
								//攤還表
								if($transaction_list){
									foreach($transaction_list as $k => $v){
										if($v->user_from==$investment->user_id){
											$transaction[]	= [
												'source'			=> $v->source,
												'entering_date'		=> $date,
												'user_from'			=> $transfer_investments->user_id,
												'bank_account_from'	=> $virtual_account->virtual_account,
												'amount'			=> intval($v->amount),
												'target_id'			=> $v->target_id,
												'investment_id'		=> $new_investment,
												'instalment_no'		=> $v->instalment_no,
												'user_to'			=> $v->user_to,
												'bank_account_to'	=> $v->bank_account_to,
												'limit_date'		=> $v->limit_date,
											];
										}else if($v->user_to==$investment->user_id){
											$transaction[]	= [
												'source'			=> $v->source,
												'entering_date'		=> $date,
												'user_from'			=> $v->user_from,
												'bank_account_from'	=> $v->bank_account_from,
												'amount'			=> intval($v->amount),
												'target_id'			=> $v->target_id,
												'investment_id'		=> $new_investment,
												'instalment_no'		=> $v->instalment_no,
												'user_to'			=> $transfer_investments->user_id,
												'bank_account_to'	=> $virtual_account->virtual_account,
												'limit_date'		=> $v->limit_date,
											];
										}
										$this->CI->transaction_model->update($v->id,array('status'=>0));
									}
								}
								
								$rs  = $this->CI->transaction_model->insert_many($transaction);
								if($rs && is_array($rs)){
									$this->CI->transfer_model->update($transfer_id,array(
										'status'			=> 10,
										'transfer_date'		=> $date,
										'new_investment'	=> $new_investment
									));
									foreach($rs as $key => $value){
										$this->CI->passbook_lib->enter_account($value);
									}
									
									$this->CI->notification_lib->transfer_success($investment->user_id,1,0,$target->target_no,$transfer->amount, $transfer_investments->user_id,$date);
									$this->CI->notification_lib->transfer_success($transfer_investments->user_id,1,1,$target->target_no,$transfer->amount, $transfer_investments->user_id,$date);
									$this->CI->notification_lib->transfer_success($target->user_id,0,0,$target->target_no,$transfer->amount, $transfer_investments->user_id,$date);
								}
							}
						}
					}
					$this->CI->target_model->update($target->id,array('script_status'=>0));
					return true;
				}
			}
		}
		return false;
	}
	
	//放款成功
	function subloan_success($target_id,$admin_id=0){
		$date 			= get_entering_date();
		$transaction 	= array();
		if($target_id){
			$target 	= $this->CI->target_model->get($target_id);
			if( $target && $target->status == 4 && $target->loan_status == 2 && $target->sub_status == 8){
				$this->CI->load->model('loan/subloan_model');
				$subloan	= $this->CI->subloan_model->get_by(array(
					'status'		=> 2,
					'new_target_id'	=> $target_id
				));
				if($subloan && $subloan->settlement_date >= $date){
					$target_account 	= $this->CI->virtual_account_model->get_by([
						'user_id'	=> $target->user_id,
						'investor'	=> 0,
						'status'	=> 1
					]);
					if($target_account){
						$this->CI->load->library('sms_lib');
						$this->CI->sms_lib->lending_success($target->user_id,0,$target->target_no,$target->loan_amount,$target_account->virtual_account);
						$this->CI->notification_lib->subloan_success($target->user_id,$target->target_no,$target->loan_amount);
						//轉換產品手續費
						$transaction[]	= array(
							'source'			=> SOURCE_SUBLOAN_FEE,
							'entering_date'		=> $date,
							'user_from'			=> $target->user_id,
							'bank_account_from'	=> $target_account->virtual_account,
							'amount'			=> intval($subloan->subloan_fee),
							'target_id'			=> $subloan->target_id,
							'instalment_no'		=> 0,
							'user_to'			=> 0,
							'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
							'status'			=> 2
						);
						
						//手續費
						$transaction[]	= array(
							'source'			=> SOURCE_FEES,
							'entering_date'		=> $date,
							'user_from'			=> $target->user_id,
							'bank_account_from'	=> $target_account->virtual_account,
							'amount'			=> intval($target->platform_fee),
							'target_id'			=> $target->id,
							'instalment_no'		=> 0,
							'user_to'			=> 0,
							'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
							'status'			=> 2
						);
						

						$investment_ids = array();
						$frozen_ids 	= array();
						$investments 	= $this->CI->investment_model->get_many_by(array(
							'target_id'		=> $target->id,
							'status'		=> 2,
							'loan_amount >'	=> 0,
							'frozen_status'	=> 1
						));
						if($investments){
							foreach($investments as $key => $value){
								$investment_ids[]	= $value->id;
								$frozen_ids[]		= $value->frozen_id;
								$virtual_account 	= $this->CI->virtual_account_model->get_by(array('user_id'=>$value->user_id,'investor'=>1,'status'=>1));
								$this->CI->notification_lib->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,'');
								$this->CI->sms_lib->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,'');
								
								//放款
								$transaction[]		= array(
									'source'			=> SOURCE_LENDING,
									'entering_date'		=> $date,
									'user_from'			=> $value->user_id,
									'bank_account_from'	=> $virtual_account->virtual_account,
									'amount'			=> intval($value->loan_amount),
									'target_id'			=> $target->id,
									'investment_id'		=> $value->id,
									'instalment_no'		=> 0,
									'user_to'			=> $target->user_id,
									'bank_account_to'	=> $target_account->virtual_account,
									'status'			=> 2
								);

								
								//攤還表
								$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target->instalment,$target->interest_rate,$date,$target->repayment);
								if($amortization_schedule){
									foreach($amortization_schedule['schedule'] as $instalment_no => $payment){
										$transaction[]	= array(
											'source'			=> SOURCE_AR_PRINCIPAL,
											'entering_date'		=> $date,
											'user_from'			=> $target->user_id,
											'bank_account_from'	=> $target_account->virtual_account,
											'amount'			=> intval($payment['principal']),
											'target_id'			=> $target->id,
											'investment_id'		=> $value->id,
											'instalment_no'		=> $instalment_no,
											'user_to'			=> $value->user_id,
											'bank_account_to'	=> $virtual_account->virtual_account,
											'limit_date'		=> $payment['repayment_date'],
										);
										
										$transaction[]	= array(
											'source'			=> SOURCE_AR_INTEREST,
											'entering_date'		=> $date,
											'user_from'			=> $target->user_id,
											'bank_account_from'	=> $target_account->virtual_account,
											'amount'			=> intval($payment['interest']),
											'target_id'			=> $target->id,
											'investment_id'		=> $value->id,
											'instalment_no'		=> $instalment_no,
											'user_to'			=> $value->user_id,
											'bank_account_to'	=> $virtual_account->virtual_account,
											'limit_date'		=> $payment['repayment_date'],
										);
										
										$total 	= intval($payment['interest'])+intval($payment['principal']);
										$ar_fee = intval(round($total/100*REPAYMENT_PLATFORM_FEES,0));
										$transaction[]	= array(
											'source'			=> SOURCE_AR_FEES,
											'entering_date'		=> $date,
											'user_from'			=> $value->user_id,
											'bank_account_from'	=> $virtual_account->virtual_account,
											'amount'			=> $ar_fee,
											'target_id'			=> $target->id,
											'investment_id'		=> $value->id,
											'instalment_no'		=> $instalment_no,
											'user_to'			=> 0,
											'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
											'limit_date'		=> $payment['repayment_date'],
										);
									}
								}
							}
							
							$rs  = $this->CI->transaction_model->insert_many($transaction);
							if($rs && is_array($rs)){
								$target_update_param = array(
									'status'		=> 5,
									'loan_status'	=> 1,
									'loan_date'		=> $date
								);
								$this->CI->target_model->update($target_id,$target_update_param);
								$this->CI->load->library('target_lib');
								$this->CI->target_lib->insert_change_log($target_id,$target_update_param,0,$admin_id);
								
								$this->CI->investment_model->update_many($investment_ids,array('status'=>3));
								foreach($investment_ids as $k => $investment_id){
									$this->CI->target_lib->insert_investment_change_log($investment_id,array('status'=>3),0,$admin_id);
								}
								$this->CI->frozen_amount_model->update_many($frozen_ids,array('status'=>0));
								foreach($rs as $key => $value){
									$this->CI->passbook_lib->enter_account($value);
								}
								
								
								$this->CI->subloan_model->update($subloan->id,array('status'=>10));
								$old_target = $this->CI->target_model->get($subloan->target_id);
								$this->CI->load->library('charge_lib');
								$this->CI->charge_lib->charge_normal_target($old_target);
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}
}
