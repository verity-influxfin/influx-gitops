<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transfer_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/transfer_model');
		$this->CI->load->model('loan/investment_model');
		$this->CI->load->model('loan/transfer_investment_model');
		$this->CI->load->library('contract_lib');
    }

	/*
	正常
	債轉期間沒跨到還款日：依照前一次還款日期至結息日，日數計算利息
	債轉期間有跨到還款日：結息日調整至還款日，利息為本期利息

	逾期
	債轉期間於寬限期內且沒有跨到逾期日：利息為上期利息金額
	債轉期間於寬限期內且跨到逾期日：結息日調整至逾期前一日，利息為上期利息金額
	逾期債轉：已發生利息 + 依照逾期日計算延滯息
	*/
	public function get_pretransfer_info($investment,$bargain_rate=0,$amount=0){
		if($investment && $investment->status==3){
			$this->CI->load->model('transaction/transaction_model');
			$this->CI->load->library('Financial_lib');
			$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by(array(
				'investment_id'	=> $investment->id,
				'status'		=> [1,2]
			));
			$target = $this->CI->target_model->get($investment->target_id);
			
			if($transaction && $target){
				$principal				= 0;//本金
				$interest				= 0;//利息
				$delay_interest			= 0;//延滯息
				$instalment_paid		= 0;//已還期數
				$accounts_receivable	= 0;//應收帳款
				$last_paid_date 		= $target->loan_date;//上次已還款日期
				$next_pay_date 			= '';//下期還款日期
				foreach($transaction as $key => $value){
					if($value->source==SOURCE_AR_PRINCIPAL){
						if($value->status==2 && $value->limit_date > $last_paid_date){
							$last_paid_date 	= $value->limit_date;
							$instalment_paid 	= $value->instalment_no;
						}else if($value->status==1){
							if($value->limit_date < $next_pay_date || $next_pay_date==''){
								$next_pay_date = $value->limit_date;
							}
							$principal += $value->amount;
							//print('$principal:'.$value->amount);
						}
					}
				}
				if($next_pay_date != '' && $last_paid_date != ''){
					$entering_date		= get_entering_date();
					$settlement_date 	= date('Y-m-d',strtotime($entering_date.' +'.TRANSFER_RANGE_DAYS.' days'));
					//正常
					if($settlement_date < $next_pay_date){
						$range_days = get_range_days($last_paid_date,$settlement_date);
						$interest 	= $this->CI->financial_lib->get_interest_by_days($range_days,$principal,$target->instalment,$target->interest_rate,$target->loan_date);
					}else{
						if($next_pay_date >= $entering_date && $next_pay_date <= $settlement_date){
							//正常-跨到還款日
							$settlement_date = $next_pay_date;
						}else{
							$range_days = get_range_days($next_pay_date,$settlement_date);
							if($range_days > GRACE_PERIOD && $range_days <= (GRACE_PERIOD + TRANSFER_RANGE_DAYS)){
								//逾期-跨到逾期日
								$settlement_date = date('Y-m-d',strtotime($next_pay_date.' +'.GRACE_PERIOD.' days'));
							}elseif($range_days > GRACE_PERIOD){
								$delay_interest = $this->CI->financial_lib->get_delay_interest($principal,$range_days);
                                print('$delay_interest:'.$value->amount);
							}
						}
					}

					foreach($transaction as $key => $value){
						if($value->status==1 && $value->source==SOURCE_AR_INTEREST && $value->limit_date <= $settlement_date){
							$interest += $value->amount;
                            //print('$interest:'.$value->amount);
						}
						if($value->status==1 && $value->source==SOURCE_AR_INTEREST){
							$accounts_receivable += $value->amount;
                            //print('$accounts_receivable:'.$value->amount);
						}
					}
print('$accounts_receivable'.$accounts_receivable .'$principal'. $principal .'$delay_interest'. $delay_interest.'$interest'. $interest);

					$accounts_receivable = $accounts_receivable + $principal + $delay_interest;
					//190525 顯示不加利息
					//$total = $principal + $interest + $delay_interest;
                    $total = intval($amount);
					$total = intval(round($total * (100 + $bargain_rate) /100,0));
					$contract = $this->CI->contract_lib->pretransfer_contract('transfer',[
						$investment->user_id,
						'',
						$target->user_id,
						$target->target_no,
						$principal,
						$principal,
						$total,
						$target->user_id,
						$target->user_id,
						$target->user_id,
					]);
					$instalment = $target->instalment - $instalment_paid;
					$fee 		= intval(round($principal*DEBT_TRANSFER_FEES/100,0));
					print('$fee'.$fee);
					$data 		= [
						'total'						=> $total,
						'instalment'				=> intval($instalment),//剩餘期數
						'principal'					=> intval($principal),
						'interest'					=> intval($interest),
						'delay_interest'			=> intval($delay_interest),
						'bargain_rate'				=> $bargain_rate,
						'accounts_receivable'		=> intval($accounts_receivable),
						'fee'						=> intval($fee),
						'debt_transfer_contract' 	=> $contract,
						'settlement_date'			=> $settlement_date,//結帳日
					];
					return $data;
				}
			}
		}
		return false;
	}
	
	public function apply_transfer($investment,$bargain_rate=0,$combination=0,$amount=0){
		if($investment && $investment->status==3 && $investment->transfer_status==0){
			$target 	= $this->CI->target_model->get($investment->target_id);
			$info  		= $this->get_pretransfer_info($investment,$bargain_rate,$amount);
			if($info){
				$principal 	= $info['principal'];
				$total 		= $info['total'];
				$contract 	= $this->CI->contract_lib->sign_contract('transfer',[
					$investment->user_id,
					'',
					$target->user_id,
					$target->target_no,
					$principal,
					$principal,
					$total,
					$target->user_id,
					$target->user_id,
					$target->user_id,
				]);
				
				if($contract){
					$investment_param = array(
						'transfer_status'		=> 1,
					);
					$rs = $this->CI->investment_model->update($investment->id,$investment_param);
					if($rs){
						$this->CI->load->library('target_lib');
						$this->CI->target_lib->insert_investment_change_log($investment->id,$investment_param,$investment->user_id);
						$param = [
							'target_id'				=> $investment->target_id,
							'investment_id'			=> $investment->id,
							'transfer_fee'			=> $info['fee'],
							'amount'				=> $info['total'],
							'principal'				=> $info['principal'],
							'interest'				=> $info['interest'],
							'delay_interest'		=> $info['delay_interest'],
							'bargain_rate'			=> $info['bargain_rate'],
							'instalment'			=> $info['instalment'],
							'accounts_receivable'	=> $info['accounts_receivable'],
							'expire_time'			=> strtotime($info['settlement_date'].' 23:59:59'),
							'combination'			=> $combination,
							'contract_id'			=> $contract,
						];
						$res = $this->CI->transfer_model->insert($param);
						return $res;
					}
				}
			}
		}
		return false;
	}

	public function cancel_transfer($transfers,$admin=0){
		if($transfers){
			$rs = $this->CI->transfer_model->update($transfers->id,['status' => 8]);
			if($rs){
				$transfer_investments = $this->CI->transfer_investment_model->get_many_by([
					'transfer_id'	=> $transfers->id,
					'status'		=> [0,1,2]
				]);
				if($transfer_investments){
					foreach($transfer_investments as $key => $value){
						$this->CI->transfer_investment_model->update($value->id,['status'=>9]);
						if($value->frozen_status==1 && $value->frozen_id){
							$this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
						}
					}
				}
				
				$investment_param = ['transfer_status' => 0];
				$rs = $this->CI->investment_model->update($transfers->investment_id,$investment_param);
				$this->CI->load->library('target_lib');
				$this->CI->target_lib->insert_investment_change_log($transfers->investment_id,$investment_param,0,$admin);
				return true;
				
			}
		}
		return false;
	}

    public function cancel_transfer_apply($transfers_id,$user_id)
    {
        $this->CI->transfer_investment_model->update_by(array(
            'transfer_id'   => $transfers_id,
            'user_id'       => $user_id,
            'frozen_status'	=> 0
        ),array('status'=>8));
        return true;
    }
	
	public function get_transfer_list($where = ['status' => 0]){
		$list 	= [];
		$rs 	= $this->CI->transfer_model->get_many_by($where);
		if($rs){
			$list = $rs;
		}
		return $list;
	}
	
	public function get_transfer_many($ids=[]){
		$list = [];
		if($ids && is_array($ids)){
			$list = $this->CI->transfer_model->get_many($ids);
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
	
	public function get_transfer_investments($investment_id){
		if($investment_id){
			$transfer = $this->CI->transfer_model->order_by('created_at','desc')->get_by(array('investment_id'=>$investment_id));
			return $transfer;
		}
		return false;
	}
	
	//判斷流標或結標或凍結款項
	function check_bidding($transfers=[]){
		if($transfers && $transfers->status==0){
			$transfer_investments = $this->CI->transfer_investment_model->order_by('tx_datetime','asc')->get_many_by([
				'transfer_id'	=> $transfers->id,
				'status'		=> [0,1]
			]);
			if($transfer_investments){
				$ended = false;
				$this->CI->load->library('Transaction_lib');
				foreach($transfer_investments as $key => $value){
					if($value->status ==0 && $value->frozen_status==0){
						$virtual_account = $this->CI->virtual_account_model->get_by([
							'status'	=> 1,
							'investor'	=> 1,
							'user_id'	=> $value->user_id
						]);
						if($virtual_account){
							$this->CI->virtual_account_model->update($virtual_account->id,['status'=>2]);
							$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
							if($funds){
								$total = intval($funds['total']) - intval($funds['frozen']) - intval($value->amount);
								if($total >= 0 ){
									$last_recharge_date = strtotime($funds['last_recharge_date']);
									$tx_datetime = $last_recharge_date < $value->created_at?$value->created_at:$last_recharge_date;
									$tx_datetime = date('Y-m-d H:i:s',$tx_datetime);
									$param = [
										'type'				=> 2,
										'virtual_account'	=> $virtual_account->virtual_account,
										'amount'			=> intval($value->amount),
										'tx_datetime'		=> $tx_datetime,
									];
									$frozen_id = $this->CI->frozen_amount_model->insert($param);
									if($frozen_id){
										$this->CI->transfer_investment_model->update($value->id,[
											'frozen_status'		=> 1,
											'frozen_id'			=> $frozen_id,
											'status'			=> 1,
											'tx_datetime'		=> $tx_datetime
										]);
										$ended = true;
									}
								}
							}
							$this->CI->virtual_account_model->update($virtual_account->id,['status'=>1]);
						}
					}
				}
				
				if($ended){
					if($transfers->combination != 0){
						$this->combine_bidding_success($transfers);
					}else{
						$this->bidding_success($transfers);
					}
				}
			}
			if($transfers->expire_time < time()){
				$this->cancel_transfer($transfers);
			}
			return true;
		}
		return false;
	}
	
	function bidding_success($transfers=[]){
		//結標
		if($transfers && $transfers->status==0 && $transfers->combination==0){
			$transfer_investments = $this->CI->transfer_investment_model->order_by('tx_datetime','asc')->get_many_by([
				'transfer_id'	=> $transfers->id,
				'status'		=> [0,1]
			]);
			if($transfer_investments){
				$rs = $this->CI->transfer_model->update($transfers->id,['status'=>1]);
				if($rs){
					$ended = true;
					foreach($transfer_investments as $key => $value){
						$param = ['status'=>9];
						if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
							if($transfers->amount == $value->amount && $ended){
								$investment  = $this->CI->investment_model->get($transfers->investment_id);
								$target		 = $this->CI->target_model->get($transfers->target_id);
								$contract_id = $this->CI->contract_lib->sign_contract('transfer',[
									$investment->user_id,
									$value->user_id,
									$target->user_id,
									$target->target_no,
									$transfers->principal,
									$transfers->principal,
									$value->amount,
									$target->user_id,
									$target->user_id,
									$target->user_id,
								]);
								$param 			= [
									'status'		=> 2,
									'contract_id'	=> $contract_id
								];
								$ended 			= false;
							}else{
								$this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
							}
						}
						$this->CI->transfer_investment_model->update($value->id,$param);
					}
					return true;
				}
			}
		}
		return false;
	}

	function combine_bidding_success($transfers=[]){
		//結標
		if($transfers && $transfers->status==0 && $transfers->combination!=0){
			$combination_list = $this->CI->transfer_model->get_many_by([
				'combination'	=> $transfers->combination,
				'status'		=> $transfers->status,
			]);
			if($combination_list){
				$success_user	 = 0;
				$success 		 = [];
				$combination_ids = [];
				$transfers_list  = [];
				foreach($combination_list as $key => $value){
					$combination_ids[] = $value->id;
					$transfers_list[$value->id] = $value;
				}
				$transfer_investments = $this->CI->transfer_investment_model->order_by('tx_datetime','asc')->get_many_by([
					'transfer_id'	=> $combination_ids,
					'status'		=> [0,1]
				]);
				if($transfer_investments){
					foreach($transfer_investments as $key => $value){
						if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
							$success[$value->user_id][] = $value->transfer_id;
							
							if($success_user==0 && count($success[$value->user_id])==count($combination_ids)){
								$success_user = $value->user_id;
							}
						}
					}
					
					if($success_user != 0){
						$this->CI->transfer_model->update_many($combination_ids,['status'=>1]);
						
						foreach($transfer_investments as $key => $value){
							$param = ['status'=>9];
							if($value->status==1 && $value->frozen_status==1 && $value->frozen_id){
								if($value->user_id==$success_user){
									$transfer 	 = $transfers_list[$value->transfer_id];
									$investment  = $this->CI->investment_model->get($transfer->investment_id);
									$target		 = $this->CI->target_model->get($transfer->target_id);
									$contract_id = $this->CI->contract_lib->sign_contract('transfer',[
										$investment->user_id,
										$value->user_id,
										$target->user_id,
										$target->target_no,
										$transfer->principal,
										$transfer->principal,
										$value->amount,
										$target->user_id,
										$target->user_id,
										$target->user_id,
									]);
									$param 			= [
										'status'		=> 2,
										'contract_id'	=> $contract_id
									];
									$ended 			= false;
								}else{
									$this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
								}
							}
							$this->CI->transfer_investment_model->update($value->id,$param);
						}
					}
					return true;
				}
			}
		}
		return false;
	}
	
	public function script_check_bidding(){
		$script  	= 5;
		$count 		= 0;
		$ids		= [];
		$transfers 	= $this->CI->transfer_model->get_many_by([
			'status'		=> 0,
			'script_status'	=> 0
		]);
		if($transfers && !empty($transfers)){
			foreach($transfers as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->transfer_model->update_many($ids,['script_status'=>$script]);
			if($update_rs){
				foreach($transfers as $key => $value){
					$check = $this->check_bidding($value);
					if($check){
						$count++;
					}
					$this->CI->transfer_model->update($value->id,array('script_status'=>0));
				}
			}
		}
		return $count;
	}
	
	public function script_transfer_success(){
		$script  	= 14;
		$count 		= 0;
		$ids		= [];
		$transfers 	= $this->CI->transfer_model->limit(30)->get_many_by([
			'status'		=> 1,
			'script_status'	=> 0
		]);
		
		if($transfers && !empty($transfers)){
			foreach($transfers as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->transfer_model->update_many($ids,['script_status'=>$script]);
			if($update_rs){
				$this->CI->load->library('Transaction_lib');
				foreach($transfers as $key => $value){
					$success = $this->CI->transaction_lib->transfer_success($value->id,0);
					if($success){
						$count++;
					}
					$this->CI->transfer_model->update($value->id,['script_status'=>0]);
				}
			}
		}
		return $count;
	}
}
