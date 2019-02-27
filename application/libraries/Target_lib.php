<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Target_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->library('Notification_lib');
    }
	
	//新增target
	public function add_target($param){
		if(!empty($param)){
			$param['target_no'] = $this->get_target_no($param['product_id']);
			$insert 			= $this->CI->target_model->insert($param);
			if($insert){
				return $insert;
			}else{
				$param['target_no'] = $this->get_target_no($param['product_id']);
				$insert 			= $this->CI->target_model->insert($param);
				return $insert;
			}
		}
		return false;
	}
	
	//簽約
	public function signing_target( $target_id, $data, $user_id=0 ){
		if($target_id){
			$param = array(
				'person_image'	=> $data['person_image'],
				'status'		=> 2,
			);
			$rs = $this->CI->target_model->update($target_id,$param);
			$this->insert_change_log($target_id,$param,$user_id);
			return $rs;
		}
		return false;
	}
	
	//全案退回
	public function cancel_success_target($target,$admin_id=0){
		if($target && in_array($target->status,[3,4]) && $target->script_status==0 ){
			$param = [
				'status'	=> 9,
				'remark'	=> $target->remark.',整案退回',
			];
			$rs = $this->CI->target_model->update($target->id,$param);
			$this->insert_change_log($target->id,$param,0,$admin_id);
			$this->CI->load->model('loan/investment_model');
			$this->CI->load->model('transaction/frozen_amount_model');
			$investments = $this->CI->investment_model->get_many_by([
				'target_id'	=> $target->id,
				'status'	=> [0,1,2]
			]);
			if($investments){
				foreach($investments as $key => $value){
					$this->insert_investment_change_log($value->id,['status'=>9],0,$admin_id);
					$this->CI->investment_model->update($value->id,['status'=>9]);
					if($value->frozen_status==1 && $value->frozen_id){
						$this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
					}
				}
			}
			if($target->sub_status==8){
				$this->CI->load->library('Subloan_lib');
				$this->CI->subloan_lib->subloan_success_return($target,$admin_id);
			}
			if($target->order_id !=0){
				$this->CI->load->model('transaction/order_model');
				$order = $this->CI->order_model->update($target->order_id,['status'=>0]);
			}
			return $rs;
		}
		return false;
	}
	
	//取消
	public function cancel_target($target=[],$user_id=0){
		if($target){
			$param = [
				'status'		=> 8,
			];
			$rs = $this->CI->target_model->update($target->id,$param);
			$this->insert_change_log($target->id,$param,$user_id);
			if($target->order_id !=0){
				$this->CI->load->model('transaction/order_model');
				$order = $this->CI->order_model->update($target->order_id,['status'=>0]);
			}
			return $rs;
		}
		return false;
	}
	
	//核可額度利率
	public function approve_target($target = []){
		$this->CI->load->library('credit_lib');
		$this->CI->load->library('contract_lib');
		if(!empty($target) && $target->status==0){
			$product_list 	= $this->CI->config->item('product_list');
			$user_id 		= $target->user_id;
			$product_id 	= $target->product_id;
			$product_info 	= $product_list[$product_id];
			$credit 		= $this->CI->credit_lib->get_credit($user_id,$product_id);
			
			if(!$credit){
				$rs 		= $this->CI->credit_lib->approve_credit($user_id,$product_id);
				if($rs){
					$credit = $this->CI->credit_lib->get_credit($user_id,$product_id);
				}
			}
			
			if($credit){
				$interest_rate	= $this->CI->credit_lib->get_rate($credit['level'],$target->instalment,$product_id);
				if($interest_rate){
					$used_amount	= 0;
					$target_list 	= $this->CI->target_model->get_many_by([
						'id !='		=> $target->id,
						'product_id'=> $product_id,
						'user_id'	=> $user_id,
						'status <='	=> 5
					]);
					if($target_list){
						foreach($target_list as $key =>$value){
							$used_amount = $used_amount + intval($value->loan_amount);
						}
					}
					$credit['amount'] 	= $credit['amount'] - $used_amount;
					$loan_amount 		= $target->amount > $credit['amount']?$credit['amount']:$target->amount;
					$platform_fee		= round($loan_amount/100*PLATFORM_FEES,0);
					$platform_fee		= $platform_fee>PLATFORM_FEES_MIN?$platform_fee:PLATFORM_FEES_MIN;
						
					if( ($product_info['type']==1 && $loan_amount >= $product_info['loan_range_s']) ||
						($product_info['type']==2 && $loan_amount == $target->amount)
					) {
						
						if($product_info['type']==1){
							$contract_id	= $this->CI->contract_lib->sign_contract('lend',['',$user_id,$loan_amount,$interest_rate,'']);
							if($contract_id){
								$param = [
									'loan_amount'		=> $loan_amount,
									'credit_level'		=> $credit['level'],
									'platform_fee'		=> $platform_fee,
									'interest_rate'		=> $interest_rate, 
									'contract_id'		=> $contract_id,
									'status'			=> 1,
								];
								$rs = $this->CI->target_model->update($target->id,$param);
								$this->insert_change_log($target->id,$param);
								if($rs){
									$this->CI->notification_lib->approve_target($user_id,'1',$loan_amount);
								}
							}
						}else if($product_info['type']==2){
							$param = [
								'loan_amount'		=> $loan_amount,
								'credit_level'		=> $credit['level'],
								'platform_fee'		=> $platform_fee,
								'interest_rate'		=> $interest_rate, 
								'status'			=> 2,
							];
							$rs = $this->CI->target_model->update($target->id,$param);
							$this->insert_change_log($target->id,$param);
							if($rs){
								$this->CI->notification_lib->approve_target($user_id,'1',$loan_amount);
								$this->CI->load->model('user/user_bankaccount_model');
								$bank_account = $this->CI->user_bankaccount_model->get_by([
									'status'	=> 1,
									'investor'	=> 0,
									'verify'	=> 0,
									'user_id'	=> $user_id 
								]);
								if($bank_account){
									$this->CI->user_bankaccount_model->update($bank_account->id,['verify'=>2]);
								}
							}
						}
					}else{
						$param = [
							'loan_amount'		=> 0,
							'status'			=> '9',
							'remark'			=> '信用不足',
						];
						$rs = $this->CI->target_model->update($target->id,$param);
						$this->insert_change_log($target->id,$param);
						$this->CI->notification_lib->approve_target($user_id,'9');
						if($target->order_id !=0){
							$this->CI->load->model('transaction/order_model');
							$order = $this->CI->order_model->update($target->order_id,['status'=>0]);
						}
					}
					
					return $rs;
				}
			}
		}
		return false;
	}
	
	public function target_verify_success($target = [],$admin_id=0){
		if(!empty($target) && $target->status==2){
			$product_list 	= $this->CI->config->item('product_list');
			$product_info 	= $product_list[$target->product_id];
			$param = [
				'status' 		=> 3 , 
				'expire_time'	=> strtotime('+2 days', time()),
				'launch_times'	=> 1
			];
			$this->CI->target_model->update($target->id, $param);
			$this->insert_change_log($target->id,$param,0,$admin_id);
			$this->CI->notification_lib->target_verify_success($target);
		}
		return false;
	}
	
	public function target_verify_failed($target = [],$admin_id=0,$remark='審批不通過'){
		if(!empty($target)){
			$param = [
				'loan_amount'		=> 0,
				'status'			=> 9,
				'remark'			=> $remark,
			];
			$this->CI->target_model->update($target->id,$param);
			$this->insert_change_log($target->id,$param,0,$admin_id);
			$this->CI->notification_lib->target_verify_failed($target->user_id,0,$remark);
			if($target->order_id !=0){
				$this->CI->load->model('transaction/order_model');
				$order = $this->CI->order_model->update($target->order_id,['status'=>0]);
			}
		}
		return false;
	}
	
	//判斷流標或結標或凍結投資款項
	function check_bidding($target){
		if( $target && $target->status == 3){
			$this->CI->load->model('loan/investment_model');
			$this->CI->load->model('transaction/frozen_amount_model');
			$this->CI->load->model('user/virtual_account_model');
			$this->CI->load->library('Subloan_lib');
			$this->CI->load->library('Contract_lib');
			$this->CI->load->library('Transaction_lib');

			$investments = $this->CI->investment_model->order_by('tx_datetime','asc')->get_many_by([
				'target_id'	=> $target->id,
				'status'	=> [0,1]
			]);
			if($investments){
				
				$amount = 0;
				foreach($investments as $key => $value){
					if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
						$amount += $value->amount;
					}
				}
				//更新invested
				$this->CI->target_model->update($target->id,['invested'=>$amount]);
				if($amount >= $target->loan_amount){
					
					//結標
					$target_update_param = [
						'status'		=> 4,
						'loan_status'	=> 2
					];
					$rs = $this->CI->target_model->update($target->id,$target_update_param);
					$this->insert_change_log($target->id,$target_update_param);
					if($rs){
						$this->CI->notification_lib->auction_closed($target->user_id,0,$target->target_no,$target->loan_amount);
						$total = 0;
						$ended = true;
						foreach($investments as $key => $value){
							$param = ['status'=>9];
							if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
								$total += $value->amount;
								if($total < $target->loan_amount && $ended){
									$loan_amount 	= $value->amount;
									$schedule 		= $this->CI->financial_lib->get_amortization_schedule($loan_amount,$target->instalment,$target->interest_rate,'',$target->repayment);
									$contract_id	= $this->CI->contract_lib->sign_contract('lend',[
										$value->user_id,
										$target->user_id,
										$loan_amount,
										$target->interest_rate,
										$schedule['total_payment']
									]);
									$param 			= [
										'loan_amount'	=> $loan_amount,
										'contract_id'	=> $contract_id ,
										'status'		=> 2
									];
									$this->CI->notification_lib->auction_closed($value->user_id,1,$target->target_no,$loan_amount);
								}else if($total >= $target->loan_amount && $ended){
									$loan_amount 	= $value->amount + $target->loan_amount - $total;
									$schedule 		= $this->CI->financial_lib->get_amortization_schedule($loan_amount,$target->instalment,$target->interest_rate,'',$target->repayment);
									$contract_id	= $this->CI->contract_lib->sign_contract('lend',[
										$value->user_id,
										$target->user_id,
										$loan_amount,
										$target->interest_rate,
										$schedule['total_payment']
									]);
									$param 			= [
										'loan_amount'	=> $loan_amount,
										'contract_id'	=> $contract_id ,
										'status'		=> 2
									]; 
									$this->CI->notification_lib->auction_closed($value->user_id,1,$target->target_no,$loan_amount);
									$ended 			= false;
								}else{
									$this->CI->frozen_amount_model->update($value->frozen_id,array('status'=>0));
								}
							}
							$this->insert_investment_change_log($value->id,$param);
							$this->CI->investment_model->update($value->id,$param);
						}
						$this->CI->load->library('Sendemail');
						$this->CI->sendemail->admin_notification('案件待放款 會員ID：'.$target->user_id,'案件待放款 會員ID：'.$target->user_id.' 案號：'.$target->target_no);
						return true;
					}
				}else{
					if($target->expire_time < time()){
						//流標
						if($target->sub_status==8){
							$this->CI->subloan_lib->auction_ended($target);
						}else{
							$target_update_param = [
								'launch_times'	=> $target->launch_times + 1,
								'expire_time'	=> strtotime('+2 days', $target->expire_time),
								'invested'		=> 0,
							];
							$this->CI->target_model->update($target->id,$target_update_param);
						}
						foreach($investments as $key => $value){
							$this->insert_investment_change_log($value->id,['status'=>9]);
							$this->CI->investment_model->update($value->id,['status'=>9]);
							if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
								$this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
							}
						}
					}else{
						//凍結款項
						foreach($investments as $key => $value){
							if($value->status ==0 && $value->frozen_status==0){
								$virtual_account = $this->CI->virtual_account_model->get_by([
									'status'	=> 1,
									'investor'	=> 1,
									'user_id'	=> $value->user_id
								]);
								if($virtual_account){
									$this->CI->virtual_account_model->update($virtual_account->id,['status'=>2]);
									$funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
									$total = $funds['total'] - $funds['frozen'];
									if(intval($total)-intval($value->amount)>=0){
										$last_recharge_date = strtotime($funds['last_recharge_date']);
										$tx_datetime = $last_recharge_date < $value->created_at?$value->created_at:$last_recharge_date;
										$tx_datetime = date('Y-m-d H:i:s',$tx_datetime);
										$param = [
											'virtual_account'	=> $virtual_account->virtual_account,
											'amount'			=> intval($value->amount),
											'tx_datetime'		=> $tx_datetime,
										];
										$rs = $this->CI->frozen_amount_model->insert($param);
										if($rs){
											$this->insert_investment_change_log($value->id,['frozen_status'=>1,'frozen_id'=>$rs,'status'=>1,'tx_datetime'=>$tx_datetime]);
											$this->CI->investment_model->update($value->id,['frozen_status'=>1,'frozen_id'=>$rs,'status'=>1,'tx_datetime'=>$tx_datetime]);
										}
									}
									$this->CI->virtual_account_model->update($virtual_account->id,['status'=>1]);
								}
							}
						}
					}
					return true;
				}
			}else{
				if($target->expire_time < time()){
					if($target->sub_status==8){
						$this->CI->subloan_lib->auction_ended($target);
					}else{
						$this->CI->target_model->update($target->id,[
							'launch_times'	=> $target->launch_times + 1,
							'expire_time'	=> strtotime('+2 days', $target->expire_time),
							'invested'		=> 0,
						]);
					}
				}
				return true;
			}
		}
		return false;
	}
	
	//借款端還款計畫
	public function get_amortization_table($target=[]){

		$schedule		= [
			'amount'				=> intval($target->loan_amount),
			'remaining_principal'	=> intval($target->loan_amount),
			'instalment'			=> intval($target->instalment),
			'rate'					=> floatval($target->interest_rate),
			'total_payment'			=> 0,
			'date'					=> $target->loan_date,
			'end_date'				=> '',
			'sub_loan_fees'			=> 0,
			'platform_fees'			=> 0,
			'list'					=> [],
			
		];
		$transactions 	= $this->CI->transaction_model->get_many_by([
			'user_from'	=> $target->user_id,
			'target_id' => $target->id,
			'status !=' => 0
		]);
		
		$list = [];
		if($transactions){	
			foreach($transactions as $key => $value){
				if(intval($value->instalment_no) && !isset($list[$value->instalment_no])){
					$list[$value->instalment_no] = [
						'instalment'			=> intval($value->instalment_no),//期數
						'total_payment'			=> 0,//本期應還款金額
						'repayment'				=> 0,//本期已還款金額
						'interest'				=> 0,//利息
						'principal'				=> 0,//本金
						'delay_interest'		=> 0,//延滯息
						'liquidated_damages'	=> 0,//違約金
						'days'					=> 0,//本期天數
						'remaining_principal'	=> 0,//期初本金
						'repayment_date'		=> $value->limit_date//還款日
					];
				}
				switch($value->source){
					case SOURCE_AR_PRINCIPAL: 
						$list[$value->instalment_no]['principal'] 			+= $value->amount;
						break;
					case SOURCE_AR_INTEREST: 
						$list[$value->instalment_no]['interest']  			+= $value->amount;
						break;
					case SOURCE_AR_DAMAGE:
						$list[$value->instalment_no]['liquidated_damages'] 	+= $value->amount;
						break;
					case SOURCE_AR_DELAYINTEREST: 
						$list[$value->instalment_no]['delay_interest'] 		+= $value->amount;
						break;
					case SOURCE_SUBLOAN_FEE: 
						$schedule['sub_loan_fees'] += $value->amount;
						break;
					case SOURCE_FEES: 
						$schedule['platform_fees'] += $value->amount;
						break;
					case SOURCE_PRINCIPAL: 
					case SOURCE_INTEREST: 
					case SOURCE_DELAYINTEREST: 
					case SOURCE_DAMAGE: 
					case SOURCE_PREPAYMENT_DAMAGE: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						if($value->source==SOURCE_PRINCIPAL){
							$schedule['remaining_principal'] -= $value->amount;
						}else if($value->source==SOURCE_PREPAYMENT_DAMAGE){
							$list[$value->instalment_no]['liquidated_damages'] 	+= $value->amount;
						}
						break;
					default:
						break;
				}
			}
			
			$old_date 	= $target->loan_date;
			$total 		= intval($target->loan_amount);
			ksort($list);
			$end 					= end($list);
			$schedule['end_date'] 	= $end['repayment_date'];
			foreach($list as $key => $value){
				$total_payment = $value['interest'] + $value['principal'] + $value['delay_interest'] + $value['liquidated_damages'];
				$list[$key]['total_payment'] 		= $total_payment;
				$schedule['total_payment'] 			+= $total_payment;
				$list[$key]['days'] 				= get_range_days($old_date,$value['repayment_date']);
				$list[$key]['remaining_principal'] 	= $total;
				$old_date 	= $value['repayment_date'];
				$total 		= $total - $value['principal'];
			}
		}
		$schedule['list'] = $list;
		return $schedule;
	}
	
	//出借端回款計畫
	public function get_investment_amortization_table($target=[],$investment=[]){
		
		$xirr_dates		= [$target->loan_date];
		$xirr_value		= [$investment->loan_amount*(-1)];
		$list 			= [];
		$schedule		= [
			'amount'				=> intval($investment->loan_amount),
			'instalment'			=> intval($target->instalment),
			'rate'					=> intval($target->interest_rate),
			'total_payment'			=> 0,
			'XIRR'					=> 0,
			'date'					=> $target->loan_date,
			'remaining_principal' 	=> 0,
		];
		$repayment_principal = 0;
		$transactions 	= $this->CI->transaction_model->get_many_by([
			'investment_id'	=> $investment->id,
			'target_id' 	=> $target->id,
			'status' 		=> [1,2]
		]);
		
		if($transactions){
			foreach($transactions as $key => $value){
				if($value->instalment_no && $value->source==SOURCE_AR_PRINCIPAL){
					$limit_date = $value->limit_date?$value->limit_date:$limit_date;
					$list[$value->instalment_no] = [
						'instalment'		=> intval($value->instalment_no),//期數
						'total_payment'		=> 0,//本期應收款金額
						'repayment'			=> 0,//本期已收款金額
						'interest'			=> 0,//利息
						'principal'			=> 0,//本金
						'delay_interest'	=> 0,//應收延滯息
						'days'				=> 0,//本期天數
						'remaining_principal'=> 0,//期初本金
						'repayment_date'	=> $limit_date,//還款日
						'ar_fees'			=> 0,//應收回款手續費
					]; 
				}
			}
			foreach($transactions as $key => $value){
				switch ($value->source) {
					case SOURCE_AR_PRINCIPAL: 
						$list[$value->instalment_no]['principal'] += $value->amount;
						break;
					case SOURCE_AR_INTEREST:
						$list[$value->instalment_no]['interest'] += $value->amount;
						break;
					case SOURCE_AR_DELAYINTEREST: 
						$list[$value->instalment_no]['delay_interest'] 	+= $value->amount;
						break;
					case SOURCE_PRINCIPAL: 
					case SOURCE_DELAYINTEREST: 
					case SOURCE_INTEREST: 
						$list[$value->instalment_no]['repayment'] += $value->amount;
						if($value->source==SOURCE_PRINCIPAL){
							$repayment_principal += $value->amount;
						}
						break;
					case SOURCE_AR_FEES: 
						$list[$value->instalment_no]['ar_fees'] += $value->amount;
						break;
					default:
						break;
				}
				if($value->instalment_no){
					$list[$value->instalment_no]['total_payment'] = 
					$list[$value->instalment_no]['interest'] + 
					$list[$value->instalment_no]['principal'] + 
					$list[$value->instalment_no]['delay_interest'];
				}
			}
			
			$old_date 	= $target->loan_date;
			$total 		= intval($investment->loan_amount);
			$this->CI->load->model('loan/transfer_model');
			$transfer = $this->CI->transfer_model->get_by([
				'status'			=> 10,
				'new_investment'	=> $investment->id
			]);
			if($transfer){
				$total 	= intval($transfer->principal);
			}
			
			$schedule['remaining_principal'] = $total - $repayment_principal;
			ksort($list);
			foreach($list as $key => $value){
				$list[$key]['days'] 				= get_range_days($old_date,$value['repayment_date']);
				$list[$key]['remaining_principal'] 	= $total;
				$old_date = $value['repayment_date'];
				$total = $total - $value['principal'];
				
				$schedule['total_payment'] += $value['total_payment'];
				$xirr_dates[] = $value['repayment_date'];
				$xirr_value[] = $value['total_payment'];
			}

			$schedule['XIRR'] = $this->CI->financial_lib->XIRR($xirr_value,$xirr_dates);
		}
		$schedule['list'] = $list;
		return $schedule;
	}
	
	public function script_check_bidding(){
		$script  	= 3;
		$count 		= 0;
		$ids		= [];
		$targets 	= $this->CI->target_model->get_many_by([
			'status'		=> 3,
			'script_status'	=> 0
		]);
		if($targets && !empty($targets)){
			foreach($targets as $key => $value){
				$ids[] = $value->id;
			}
			$update_rs 	= $this->CI->target_model->update_many($ids,['script_status'=>$script]);
			if($update_rs){
				foreach($targets as $key => $value){
					$check = $this->check_bidding($value);
					if($check){
						$count++;
					}
					$this->CI->target_model->update($value->id,['script_status'=>0]);
				}
			}
		}
		return $count;
	}
	
	//審核額度
	public function script_approve_target(){
		
		$this->CI->load->library('Certification_lib');
		$targets 	= $this->CI->target_model->get_many_by([
			'status'		=> 0,
			'script_status'	=> 0
		]);
		$list 		= [];
		$ids		= [];
		$script  	= 4;
		$count 		= 0;
		if($targets && !empty($targets)){
			foreach($targets as $key => $value){
				$list[$value->product_id][$value->id] = $value;
				$ids[] = $value->id;
			}

			$rs = $this->CI->target_model->update_many($ids,['script_status'=>$script]);
			if($rs){
				foreach($list as $product_id => $targets){
					$product_list 			= $this->CI->config->item('product_list');
					$product_certification 	= $product_list[$product_id]['certifications'];
					foreach($targets as $target_id => $value){
						$certifications 	= $this->CI->certification_lib->get_status($value->user_id,0);
						$finish		 		= true;
						foreach($certifications as $certification){
							if(in_array($certification['id'],$product_certification) && $certification['user_status']!='1'){
								$finish	= false;
							}
						}
						if($finish){
							$count++;
							$this->approve_target($value);
						}else{
							//自動取消
							$limit_date 	= date('Y-m-d',strtotime('-'.TARGET_APPROVE_LIMIT.' days'));
							$create_date 	= date('Y-m-d',$value->created_at);
							if($limit_date > $create_date){
								$count++;
								$param = [
									'status' => 9,
									'remark' => $value->remark.'系統自動取消'
								];
								$this->CI->target_model->update($value->id,$param);
								$this->insert_change_log($target_id,$param);
								$this->CI->notification_lib->approve_cancel($value->user_id);
								if($value->order_id !=0){
									$this->CI->load->model('transaction/order_model');
									$order = $this->CI->order_model->update($value->order_id,['status'=>0]);
								}
							}
						}
						$this->CI->target_model->update($value->id,['script_status'=>0]);
					}
				}
				return $count;
			}
		}
		return false;
	}
	
	private function get_target_no($product_id=0){
		$product_list 	= $this->CI->config->item('product_list');
		$alias 			= $product_list[$product_id]['alias'];
		$code 			= $alias.date('Ymd').rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$result = $this->CI->target_model->get_by('target_no',$code);
		if ($result) {
			return $this->get_target_no();
		}else{
			return $code;
		}
	}

	public function insert_change_log($target_id,$update_param,$user_id=0,$admin_id=0){
		if($target_id){
			$this->CI->load->model('log/Log_targetschange_model');
			$param		= [
				'target_id'		=> $target_id,
				'change_user'	=> $user_id,
				'change_admin'	=> $admin_id
			];
			$fields 	= ['delay','status','loan_status','sub_status'];
			foreach ($fields as $field) {
				if (isset($update_param[$field])) {
					$param[$field] = $update_param[$field];
				}
			}
			$rs = $this->CI->Log_targetschange_model->insert($param);
			return $rs;
		}
		return false;
	}
	
	public function insert_investment_change_log($investment_id,$update_param,$user_id=0,$admin_id=0){
		if($investment_id){
			$this->CI->load->model('log/Log_investmentschange_model');
			$param		= [
				'investment_id'	=> $investment_id,
				'change_user'	=> $user_id,
				'change_admin'	=> $admin_id
			];
			$fields 	= ['status','transfer_status'];
			foreach ($fields as $field) {
				if (isset($update_param[$field])) {
					$param[$field] = $update_param[$field];
				}
			}
			$rs = $this->CI->Log_investmentschange_model->insert($param);
			return $rs;
		}
		return false;
	}
}
