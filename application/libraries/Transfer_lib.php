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
	public function get_pretransfer_info($investment,$bargain_rate=0,$amount=0,$get_data=false,$target=false){
		if($investment && $investment->status==3||$get_data){
			$this->CI->load->model('transaction/transaction_model');
			$this->CI->load->library('Financial_lib');
			$transaction 	= $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by(array(
				'investment_id'	=> $investment->id,
				'status'		=> [1,2]
			));
			if(!$target){
			    $target = $this->CI->target_model->get($investment->target_id);
            }
			
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
							}
						}
					}

					foreach($transaction as $key => $value){
						if($value->status==1 && $value->source==SOURCE_AR_INTEREST && $value->limit_date <= $settlement_date){
							$interest += $value->amount;
						}
						if($value->status==1 && $value->source==SOURCE_AR_INTEREST){
							$accounts_receivable += $value->amount;
						}
					}

					$accounts_receivable = $accounts_receivable + $principal + $delay_interest;
					//190525 顯示不加利息
					//$total = $principal + $interest + $delay_interest;
                    $total = intval($amount!=0?$amount:$principal);
					$total = intval(round($total * (100 + $bargain_rate) /100,0));
					$instalment = $target->instalment - $instalment_paid;
					$fee 		= $this->CI->financial_lib->get_transfer_fee($principal);

					$data 		= [
						'total'						=> $total,
						'instalment'				=> intval($instalment),//剩餘期數
						'principal'					=> intval($principal),
						'interest'					=> intval($interest),
						'delay_interest'			=> intval($delay_interest),
						'bargain_rate'				=> $bargain_rate,
						'accounts_receivable'		=> intval($accounts_receivable),
						'fee'						=> intval($fee),
						'settlement_date'			=> $settlement_date,//結帳日
					];
					return $data;
				}
			}
		}
		return false;
	}
	
	public function apply_transfer($investment,$combination_id=0,$contract=false,$data_arr=[],$i=0,$count){
		if($investment && $investment->status==3 && $investment->transfer_status==0){
            if($contract){
                $contract_id = is_numeric($contract[0])?$contract[0]:$this->CI->contract_lib->sign_contract($combination_id==0?'transfer':'trans_multi',$contract[$i]);
                $investment_param = array(
                    'transfer_status' => 1,
                );
                $rs = $this->CI->investment_model->update($investment->id,$investment_param);
                if($rs){
                    $this->CI->load->library('target_lib');
                    $this->CI->target_lib->insert_investment_change_log($investment->id,$investment_param,$investment->user_id);
                    $infoAmount = $combination_id!=0&&$count==1?$data_arr['total'][$i]:($combination_id==0&&$count==1?$data_arr['total'][$i]:$data_arr['principal'][$i]);
                    $param = [
                        'target_id'				=> $investment->target_id,
                        'investment_id'			=> $investment->id,
                        'transfer_fee'			=> $data_arr['fee'][$i],
                        'amount'				=> $infoAmount,
                        'principal'				=> $data_arr['principal'][$i],
                        'interest'				=> $data_arr['interest'][$i],
                        'delay_interest'		=> $data_arr['delay_interest'][$i],
                        'bargain_rate'			=> $data_arr['bargain_rate'][$i],
                        'instalment'			=> $data_arr['instalment'][$i],
                        'accounts_receivable'	=> $data_arr['accounts_receivable'][$i],
                        'expire_time'			=> strtotime($data_arr['settlement_date'][$i].' 23:59:59'),
                        'combination'			=> $combination_id,
                        'contract_id'			=> $contract_id,
                    ];
                    $res = $this->CI->transfer_model->insert($param);
                    return $combination_id!=0&&$i!=$count?[$contract_id]:$res;
                }
            }
		}
		return false;
	}

	public function cancel_transfer($transfers,$admin=0){
        $transfers_status 	= $this->CI->transfer_model->get_by([
            'id'	    	=> $transfers->id,
        ]);
		if($transfers_status){
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
                            $this->CI->load->model('transaction/frozen_amount_model');
							$this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
						}
					}
				}
				
				$investment_param = ['transfer_status' => 0];
				$this->CI->investment_model->update($transfers->investment_id,$investment_param);
				$this->CI->load->library('target_lib');
				$this->CI->target_lib->insert_investment_change_log($transfers->investment_id,$investment_param,0,$admin);
				return true;
				
			}
		}
		return false;
	}

	public function cancel_combination_transfer($transfers,$admin=0){
		if($transfers){
            foreach($transfers as $k => $v) {
                $transfers_status 	= $this->CI->transfer_model->get_by([
                    'id'	    	=> $v->id,
                    'script_status'	=> 0
                ]);
                if($transfers_status){
                    $rs = $this->CI->transfer_model->update($v->id,['status' => 8]);
                    if($rs){
                        $transfer_investments = $this->CI->transfer_investment_model->get_many_by([
                            'transfer_id'	=> $v->id,
                            'status'		=> [0,1,2]
                        ]);
                        if($transfer_investments){
                            foreach($transfer_investments as $key => $value){
                                $this->CI->transfer_investment_model->update($value->id,['status'=>9]);
                                if($value->frozen_status==1 && $value->frozen_id){
                                    $this->CI->load->model('transaction/frozen_amount_model');
                                    $this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
                                }
                            }
                        }

                        $investment_param = ['transfer_status' => 0];
                        $rs = $this->CI->investment_model->update($v->investment_id,$investment_param);
                        $this->CI->load->library('target_lib');
                        $this->CI->target_lib->insert_investment_change_log($v->investment_id,$investment_param,0,$admin);
                    }
                }
            }
            return true;
		}
		return false;
	}

    public function cancel_transfer_apply($transfers,$user_id)
    {
        $pram = [
            'transfer_id'   => $transfers,
            'user_id'       => $user_id,
            'frozen_status'	=> 0,
            'status'     => [0,1]
        ];
        $transfer_investment = $this->CI->transfer_investment_model->get_many_by($pram);
        if($transfer_investment){
           foreach($transfer_investment as $key => $value){
               $this->CI->transfer_investment_model->update($value->id,array('status'=>8));
           }
           return true;
        }
        return false;
    }


    //案件改變觸發中斷債轉
    function break_transfer($target_id)
    {
        $transfer_list = $this->CI->transfer_model->get_many_by([
            'target_id' => $target_id,
            'status'     => [0,1]
        ]);
        foreach($transfer_list as $key => $value){
            if($value->combination!=0){
                $transfer = $this->CI->transfer_model->get_many_by(['combination' => $value->combination]);
                $this->cancel_combination_transfer($transfer);
            }else{
                $this->cancel_transfer($value);
            }
        }
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
        $r_status = true;
        if ($transfers->combination != 0) {
            $this->CI->load->model('loan/transfer_combination_model');
            $combinations_info = $this->CI->transfer_combination_model->get($transfers->combination);
            //取得打包債權資訊
            $combination_transfers = $this->CI->transfer_model->get_many_by([
                'combination'   => $transfers->combination,
                'status'        => 0
            ]);
            if($combinations_info->count == count($combination_transfers)){
                if($transfers->expire_time < time()){
                    foreach($combination_transfers as $keys => $values) {
                        $this->cancel_transfer($values);
                    }
                    return true;
                }
                else{
                    //取得打包債權所有投資人
                    $combination_transfer_ids = [];
                    foreach($combination_transfers as $keys => $values){
                        $transfer_investment = $this->CI->transfer_investment_model->order_by('tx_datetime','asc')->get_many_by([
                            'transfer_id'	=> $values->id,
                            'status'		=> [0,1],
                        ]);
                        if($transfer_investment){
                            foreach($transfer_investment as $keys2 => $values2) {
                                $combination_transfer_ids[$values2->user_id]['id'][]          = $values2->id;
                                $combination_transfer_ids[$values2->user_id]['transfer_id'][] = $values2->transfer_id;
                            }
                        }
                    }
                }
            }
            else{
                $r_status = false;
            }
        }
		if($transfers && $transfers->status==0 && $r_status){
            $transfer_investments = $this->CI->transfer_investment_model->order_by('tx_datetime','asc')->get_many_by([
				'transfer_id'	=> $transfers->id,
				'status'		=> [0,1]
			]);
            if($transfer_investments){
                $ended = false;
                $this->CI->load->library('Transaction_lib');
                foreach($transfer_investments as $key => $value){
                    $virtual_account = $this->CI->virtual_account_model->get_by([
                        'status'	=> 1,
                        'investor'	=> 1,
                        'user_id'	=> $value->user_id
                    ]);
                    if($virtual_account) {
                        $this->CI->virtual_account_model->update($virtual_account->id, ['status' => 2]);
                        $funds = $this->CI->transaction_lib->get_virtual_funds($virtual_account->virtual_account);
                        $last_recharge_date = strtotime($funds['last_recharge_date']);
                        $tx_datetime = $last_recharge_date < $value->created_at ? $value->created_at : $last_recharge_date;
                        $tx_datetime = date('Y-m-d H:i:s', $tx_datetime);
                        if ($funds){
                            if ($value->status == 0 && $value->frozen_status == 0) {
                                if ($transfers->combination != 0) {
                                    $ids = $combination_transfer_ids[$value->user_id]['id'];
                                    $amount = $combinations_info->amount;
                                }else{
                                    $ids    = [$value->id];
                                    $amount = $value->amount;
                                }
                                $total = intval($funds['total']) - intval($funds['frozen']) - intval($amount);
                                if ($total >= 0) {
                                    $frozen_id = $this->CI->frozen_amount_model->insert([
                                        'type' => 2,
                                        'virtual_account' => $virtual_account->virtual_account,
                                        'amount' => intval($amount),
                                        'tx_datetime' => $tx_datetime,
                                    ]);
                                    if ($frozen_id) {
                                        $this->CI->transfer_investment_model->update_many($ids, [
                                            'frozen_status' => 1,
                                            'frozen_id' => $frozen_id,
                                            'status' => 1,
                                            'tx_datetime' => $tx_datetime
                                        ]);
                                        $ended = true;
                                    }
                                }
                            }
                        }
                        $this->CI->virtual_account_model->update($virtual_account->id, ['status' => 1]);
                    }
				}
				if($ended){
					if($transfers->combination != 0){
						$this->combine_bidding_success($transfers,$combination_transfer_ids);
					}else{
						$this->bidding_success($transfers);
					}
				}
			}
            if ($transfers->expire_time < time()) {
                $this->cancel_transfer($transfers);
            } else {
                $this->CI->load->library('Transaction_lib');
                $this->transaction_lib->transfer_success($transfers->id);
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
                    $this->CI->load->library('contract_lib');
					$ended = true;
					foreach($transfer_investments as $key => $value){
						$param = ['status'=>9];
						if($value->status ==1 && $value->frozen_status==1 && $value->frozen_id){
							if($transfers->amount == $value->amount && $ended){
								$investment  = $this->CI->investment_model->get($transfers->investment_id);
								$target		 = $this->CI->target_model->get($transfers->target_id);
								$contract    = $this->CI->contract_lib->build_contract('transfer',$investment->user_id,$value->user_id,[],[
                                    'user_id'   => [$target->user_id],
                                    'target_no' => [$target->target_no],
                                    'principal' => [$transfers->principal],
                                ],1,$value->amount,0);
								$contract_id = $this->CI->contract_lib->sign_contract('transfer',$contract);
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

	function combine_bidding_success($transfers,$combination_transfer_ids){
        //結標
        if($transfers && $transfers->status==0 && $transfers->combination!=0){
            $transfer_investments = $this->CI->transfer_investment_model->order_by('tx_datetime','asc')->get_many_by([
                'transfer_id'	=> $transfers->id,
                'status'		=> [0,1]
            ]);
            if($transfer_investments){
                $this->CI->load->library('contract_lib');
                $ended = true;
                foreach($transfer_investments as $key => $value){
                    $this->CI->transfer_model->update_many($combination_transfer_ids[$value->user_id]['transfer_id'],['status'=>1]);
                    $param = ['status'=>9];
                    if($transfers->amount == $value->amount && $ended){
                        $raw_contract = $this->CI->contract_lib->raw_contract($transfers->contract_id);
                        $contract     = json_decode($raw_contract->content,true);
                        $contract[1]  = $value->user_id;
                        $contract_id  = $this->CI->contract_lib->sign_contract('trans_multi',$contract);
                        $param 			= [
                            'status'		=> 2,
                            'contract_id'	=> $contract_id
                        ];
                        $ended 			= false;
                    }else{
                        $this->CI->frozen_amount_model->update($value->frozen_id,['status'=>0]);
                    }
                    $this->CI->transfer_investment_model->update_many($combination_transfer_ids[$value->user_id]['id'],$param);
                }
                return true;
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
            $combination_ids = [];
            foreach($transfers as $key => $value){
                if(!in_array($value->combination,$combination_ids)) {
                    $this->CI->transfer_model->update($value->id,['script_status'=>$script]);
                    $value->combination!=0?array_push($combination_ids, $value->combination):null;
                    $check = $this->check_bidding($value);
                    $this->CI->transfer_model->update($value->id,array('script_status'=>0));
                }
                if($check){
                    $count++;
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
            $this->CI->load->library('Transaction_lib');
            $combination_ids = [];
            foreach($transfers as $key => $value){
                if(!in_array($value->combination,$combination_ids)) {
                    $this->CI->transfer_model->update($value->id,['script_status'=>$script]);
                    $value->combination!=0?array_push($combination_ids, $value->combination):null;
                    $success = $this->CI->transaction_lib->transfer_success($value->id,0);
                }
                if($success){
                    $count++;
                }
            }
		}
		return $count;
	}

	public function check_combination($target_id,$new_investment){
		if($new_investment!=0){
            $transfers 	= $this->CI->transfer_model->get_by([
                'target_id'		   => $target_id,
                'new_investment'   => $new_investment,
                'combination !='   => 0,
            ]);
            if($transfers){
                $this->CI->load->model('loan/transfer_combination_model');
                $combination = $this->CI->transfer_combination_model->get($transfers->combination);
                return [$combination->combination_no,$combination->amount,$combination->transfer_fee];
            }
        }
        return false;
	}

	public function is_sub_product($product,$sub_product_id){
		$sub_product_list = $this->CI->config->item('sub_product_list');
		return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
	}

	public function trans_sub_product($product,$sub_product_id){
		$sub_product_list = $this->CI->config->item('sub_product_list');
		$sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
		$product = $this->sub_product_profile($product,$sub_product_data);
		return $product;
	}

	public function sub_product_profile($product,$sub_product){
		return array(
			'id' => $product['id'],
			'visul_id' => $sub_product['visul_id'],
			'type' => $product['type'],
			'identity' => $product['identity'],
			'name' => $sub_product['name'],
			'description' => $sub_product['description'],
			'loan_range_s' => $sub_product['loan_range_s'],
			'loan_range_e' => $sub_product['loan_range_e'],
			'interest_rate_s' => $sub_product['interest_rate_s'],
			'interest_rate_e' => $sub_product['interest_rate_e'],
			'charge_platform' => $sub_product['charge_platform'],
			'charge_platform_min' => $sub_product['charge_platform_min'],
			'certifications' => $sub_product['certifications'],
			'instalment' => $sub_product['instalment'],
			'repayment' => $sub_product['repayment'],
			'targetData' => $sub_product['targetData'],
			'dealer' => $sub_product['dealer'],
			'multi_target' => $sub_product['multi_target'],
			'status' => $sub_product['status'],
		);
	}
}
