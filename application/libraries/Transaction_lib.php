<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Controller $CI
 */
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
		$this->CI->load->library('Sendemail');
    }

	//取得資金資料
	public function get_virtual_funds($virtual_account='')
	{
		if($virtual_account){
			$total  = 0;
            $frozen = 0;
            $frozenes = ['1' => 0,'2' => 0,'3' => 0,'4' => 0];
            $frozen_arr = $frozenes;
			$last_recharge_date	= '';
			$this->CI->load->model('transaction/virtual_passbook_model');
			$this->CI->load->model('transaction/transaction_model');
			$virtual_passbook 	= $this->CI->virtual_passbook_model->get_many_by(array('virtual_account' => $virtual_account));

			$frozen_amount 		= $this->CI->frozen_amount_model->get_many_by(array('virtual_account' => $virtual_account,'status' => 1));
			if($virtual_passbook){
				foreach($virtual_passbook as $key => $value){
					$total = $total + intval($value->amount);
					if(intval($value->amount) > 0 && $value->tx_datetime > $last_recharge_date){
                        $remark = json_decode($value->remark, TRUE);
                        if (isset($remark['source']) && in_array($remark['source'], [SOURCE_RECHARGE, SOURCE_LENDING]))
                        {
                            continue;
                        }
						$last_recharge_date = $value->tx_datetime;
					}
				}
			}

			if($frozen_amount){
				foreach($frozen_amount as $key => $value){
                    $frozen = $frozen + intval($value->amount);
                    $frozen_arr[$value->type] = $frozen_arr[$value->type] + intval($value->amount);
				}
                $frozenes = [
                    'invest'   => $frozen_arr[1],
                    'transfer' => $frozen_arr[2],
                    'withdraw' => $frozen_arr[3],
                    'other'    => $frozen_arr[4]
                ];
			}
			else{
                $frozenes = ['invest' => 0,'transfer' => 0,'withdraw' => 0,'other' => 0];
            }
			return array('total'=>$total,'last_recharge_date'=>$last_recharge_date,'frozen'=>$frozen,'frozenes'=>$frozenes);
		}
		return false;
	}

	//代收
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
        if ($user_id && $this->check_minimum_withdraw_amount($amount))
        {
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
            $this->CI->load->model('transaction/virtual_passbook_model');
            $this->CI->withdraw_model->trans_begin();
            $this->CI->frozen_amount_model->trans_begin();
            $this->CI->virtual_passbook_model->trans_begin();
            $transaction_commit = function () {
                if($this->CI->withdraw_model->trans_status() === FALSE ||
                    $this->CI->frozen_amount_model->trans_status() === FALSE ||
                    $this->CI->virtual_passbook_model->trans_status() === FALSE)
                    throw new Exception('insert error.');
                $this->CI->withdraw_model->trans_commit();
                $this->CI->frozen_amount_model->trans_commit();
                $this->CI->virtual_passbook_model->trans_commit();
            };

			$withdraw = $this->CI->withdraw_model->db->select_for_update('*')
                ->where('id', $withdraw_id)
                ->from('`p2p_transaction`.`withdraw`')
                ->get()->result();
			if(!empty($withdraw))
                $withdraw = reset($withdraw);

			try{
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
                            $transaction_commit();
                            return true;
                        }else{
                            throw new Exception('insert error.');
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
                                    $transaction_commit();
                                    $this->CI->notification_lib->withdraw_success($withdraw->user_id,$withdraw->investor,$withdraw->amount,$user_bankaccount->bank_account);
                                    return true;
                                }else{
                                    throw new Exception('insert error.');
                                }
                            }
                        }
                    }
                }
            }catch(Exception $e) {
                $this->CI->withdraw_model->trans_rollback();
                $this->CI->frozen_amount_model->trans_rollback();
                $this->CI->virtual_passbook_model->trans_rollback();
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
                $virtual = $target->product_id != PRODUCT_FOREX_CAR_VEHICLE ? CATHAY_VIRTUAL_CODE : TAISHIN_VIRTUAL_CODE;
                $target_account = $this->CI->virtual_account_model->get_by([
                    'user_id'	=> $target->user_id,
                    'investor'	=> 0,
                    'status' => 1,
                    'virtual_account like' => $virtual . '%'
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
						$this->CI->notification_lib->lending_success($target->user_id,0,$target->target_no,$target->product_id,$target->sub_product_id,$target->loan_amount,$user_bankaccount->bank_account);
						$this->CI->sendemail->lending_success($target->user_id,0,$target->target_no,$target->loan_amount,$user_bankaccount->bank_account);

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
							$this->CI->load->model('user/user_model');
							foreach($investments as $key => $value){
								$investment_ids[]	= $value->id;
								$frozen_ids[]		= $value->frozen_id;
								$virtual_account 	= $this->CI->virtual_account_model->get_by(array('user_id'=>$value->user_id,'investor'=>1,'status'=>1));
								$this->CI->notification_lib->lending_success($value->user_id,1,$target->target_no,$target->product_id,$target->sub_product_id,$value->loan_amount,'');
								$this->CI->sendemail->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,'',$target->user_id);

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
								$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target,$date);
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
										$ar_fee = $target->product_id != PRODUCT_FOREX_CAR_VEHICLE ? $this->CI->financial_lib->get_ar_fee($total) : 0;
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
								// $this->CI->target_model->update($target_id,$target_update_param);

								# issue_898
				                $updateSql = sprintf("UPDATE `%s`.`%s` SET `status` = '5', `loan_status` = '1', `loan_date` = '%s', `updated_at` = '%s' WHERE `id` = '%s'", P2P_LOAN_DB, P2P_LOAN_TARGET_TABLE, $date, time(), $target_id);
								$this->CI->db->query($updateSql);


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

                                $this->reset_credit_expire_time($target);
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}

	//分期成功
	function order_success($target_id,$admin_id=0){
		$date 			= get_entering_date();
		$transaction 	= [];
		if($target_id){
			$target = $this->CI->target_model->get($target_id);
			if( $target && $target->status == 24 && $target->order_id != 0){
				$this->CI->load->model('transaction/order_model');
				$order = $this->CI->order_model->get($target->order_id);
				if($order && $order->status==3){

					$target_account 	= $this->CI->virtual_account_model->get_by([
						'user_id'	=> $target->user_id,
						'investor'	=> 0,
						'status'	=> 1
					]);
					$company_account 	= $this->CI->virtual_account_model->get_by([
						'user_id'	=> $order->company_user_id,
						'investor'	=> 1,
						'status'	=> 1
					]);

					if($target_account && $company_account){
                        $transfer_fee = intval($order->transfer_fee);
					    $total_amount = $target->loan_amount;

						$investment_id 	= $this->CI->investment_model->insert([
							'target_id'		=> $target->id,
							'user_id'		=> $company_account->user_id,
							'amount'		=> $total_amount,
							'loan_amount'	=> $total_amount,
							'frozen_status'	=> 1,
							'tx_datetime'	=> date("Y-m-d H:i:s"),
							'contract_id'	=> $target->contract_id,
							'status'		=> 3,
						]);

						if($investment_id){
							//攤還表
							$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($total_amount,$target,$date);
							if($amortization_schedule){
								foreach($amortization_schedule['schedule'] as $instalment_no => $payment){
									$transaction[]	= [
										'source'			=> SOURCE_AR_PRINCIPAL,
										'entering_date'		=> $date,
										'user_from'			=> $target->user_id,
										'bank_account_from'	=> $target_account->virtual_account,
										'amount'			=> intval($payment['principal']),
										'target_id'			=> $target->id,
										'investment_id'		=> $investment_id,
										'instalment_no'		=> $instalment_no,
										'user_to'			=> $company_account->user_id,
										'bank_account_to'	=> $company_account->virtual_account,
										'limit_date'		=> $payment['repayment_date'],
									];

									$transaction[]	= [
										'source'			=> SOURCE_AR_INTEREST,
										'entering_date'		=> $date,
										'user_from'			=> $target->user_id,
										'bank_account_from'	=> $target_account->virtual_account,
										'amount'			=> intval($payment['interest']),
										'target_id'			=> $target->id,
										'investment_id'		=> $investment_id,
										'instalment_no'		=> $instalment_no,
										'user_to'			=> $company_account->user_id,
										'bank_account_to'	=> $company_account->virtual_account,
										'limit_date'		=> $payment['repayment_date'],
									];

									$total 	= intval($payment['interest'])+intval($payment['principal']);
									$ar_fee = $this->CI->financial_lib->get_ar_fee($total);
									$transaction[]	= [
										'source'			=> SOURCE_AR_FEES,
										'entering_date'		=> $date,
										'user_from'			=> $company_account->user_id,
										'bank_account_from'	=> $company_account->virtual_account,
										'amount'			=> $ar_fee,
										'target_id'			=> $target->id,
										'investment_id'		=> $investment_id,
										'instalment_no'		=> $instalment_no,
										'user_to'			=> 0,
										'bank_account_to'	=> PLATFORM_VIRTUAL_ACCOUNT,
										'limit_date'		=> $payment['repayment_date'],
									];
								}
							}

                            $this->CI->load->library('coop_lib');
                            $result = $this->CI->coop_lib->coop_request('order/supdate',[
                                'merchant_order_no' => $order->merchant_order_no,
                                'phone'             => $order->phone,
                                'type'              => 'approve',
                            ],0);
                            if(isset($result->result) && $result->result == 'SUCCESS') {
                                $rs = $this->CI->transaction_model->insert_many($transaction);
                                if ($rs && is_array($rs)) {
                                    foreach ($rs as $key => $value) {
                                        $this->CI->passbook_lib->enter_account($value);

                                    }
                                    $this->CI->load->library('order_lib');
                                    $rs2 = $this->CI->order_lib->order_change($order->id,3, [
                                        'status'          => 5,
                                    ],$order->company_user_id);
                                    if($rs2){
                                        $this->CI->load->library('target_lib');
                                        $rs3 = $this->CI->target_lib->order_target_change($order->id,24,[
                                            'status'      => 5,
                                            'sub_status'  => 0,
                                            'loan_date'   => $date,
                                            'loan_status' => 1,
                                        ],$order->company_user_id);
                                        if(!$rs3){
                                            $this->CI->order_lib->order_change($order->id,5, [
                                                'status'            => 3,
                                            ],$order->company_user_id);
                                        }
                                    }

                                    $investment = $this->CI->investment_model->get($investment_id);
                                    $entering_date		= get_entering_date();
                                    $settlement_date 	= date('Y-m-d',strtotime($entering_date.' +'.ORDER_TRANSFER_RANGE_DAYS.' days'));
                                    if ($investment) {
                                        $data_arr['principal']           = [];
                                        $data_arr['interest']            = [];
                                        $data_arr['delay_interest']      = [];
                                        $data_arr['fee']                 = [];
                                        $data_arr['bargain_rate']        = [];
                                        $data_arr['instalment']          = [];
                                        $data_arr['accounts_receivable'] = [];
                                        $data_arr['total']               = [];
                                        $data_arr['settlement_date']     = [];
                                        $target = $this->CI->target_model->get($investment->target_id);
                                        $this->CI->load->library('Transfer_lib');
                                        $info  	= $this->CI->transfer_lib->get_pretransfer_info($investment,0,$total_amount,false,$target);
                                        $data_arr['user_id'][] 		         = $target->user_id;
                                        $data_arr['target_no'][]	         = $target->target_no;
                                        $data_arr['principal'][] 			 = $info['principal'];
                                        $data_arr['interest'][] 			 = $info['interest'];
                                        $data_arr['delay_interest'][] 	     = $info['delay_interest'];
                                        $data_arr['fee'][] 	                 = $transfer_fee;
                                        $data_arr['bargain_rate'][] 	     = $info['bargain_rate'];
                                        $data_arr['instalment'][] 	         = $info['instalment'];
                                        $data_arr['accounts_receivable'][] 	 = $info['accounts_receivable'];
                                        $data_arr['total'][] 	             = $info['total'];
                                        $data_arr['settlement_date'][] 	     = $settlement_date;
                                        $this->CI->load->library('contract_lib');
                                        $contract[] = $this->CI->contract_lib->build_contract('transfer',$company_account->user_id,'','',$data_arr,0,$total_amount);
                                        $this->CI->transfer_lib->apply_transfer($investment, 0,$contract,$data_arr,0,1);
                                        $this->reset_credit_expire_time($target);
                                        return true;
                                    }
                                }
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
    function transfer_success($transfer_id, $admin_id = 0)
    {
        $date = get_entering_date();
        if (!$transfer_id) {
            return false;
        }
        $this->CI->config->item('product_list');
        $this->CI->load->model('loan/transfer_model');
        $this->CI->load->model('loan/transfer_investment_model');
        $this->CI->load->model('transaction/order_model');
        $this->CI->load->library('Estatement_lib');
        $this->CI->load->library('Contract_lib');
        $this->CI->load->library('target_lib');
        $o_transfer = $this->CI->transfer_model->get($transfer_id);
        $amount = $o_transfer->amount;
        $target_no = false;
        $transfers = [];
        if ($o_transfer->combination != 0) {
            $this->CI->load->model('loan/transfer_combination_model');
            $combinations_info = $this->CI->transfer_combination_model->get($o_transfer->combination);
            $amount = $combinations_info->amount;
            $target_no = $combinations_info->combination_no;
            //取得打包債權資訊
            $combination_transfers = $this->CI->transfer_model->get_many_by([
                'combination' => $o_transfer->combination,
                'status' => 1
            ]);
            $combinations_info->count == count($combination_transfers) ? $transfers = $combination_transfers : null;
        }
        else {
            $transfers[] = $o_transfer;
        }

        if (!$transfers) {
//                如果沒有transfer，後續的迴圈不會執行，所以直接return true
            return true;
        }

        //lock target
        $unlock = true;
//        $is_order = false;
        $transfer_info = [];
        $target_ids = [];
        $transfer_ids = [];
        $user_info = [];
        $contract = false;
        $invest_list = [];
        $invest_target = [];

        $skip_process = false;//檢查是否要跳過本次處理
        $need_cancel_transfer = false;//當其中的一筆債權失敗，則全部取消

        foreach ($transfers as $tc => $transfer_check) {
            $infos = [];
            $target_ids[] = $transfer_check->target_id;
            $transfer_ids[] = $transfer_check->id;
            $infos['targets'] = $this->CI->target_model->get($transfer_check->target_id);
            $infos['investment'] = $this->CI->investment_model->get($transfer_check->investment_id);
            $transfer_info[] = $infos;

            //$infos
            if ($infos['targets']->script_status != 0) {
//                    其中一筆有其他跑批正在執行 -> 不會自動退回/不會自動放行
                $skip_process = true;
            }
            elseif ($infos['investment']->status != 3) {
//                    investment 不是 還款 -> 全部自動退回
                $unlock = false;
                $need_cancel_transfer = true;
            }
        }
        if ($skip_process) {
            $this->CI->transfer_model->update_many($transfer_ids, array('script_status' => 0));
            return true;
        }

        $mrs = $rs = false;
        if ($unlock) {
            $mrs = $this->CI->transfer_model->update_many($transfer_ids, array('script_status' => 14));
            $rs = $this->CI->target_model->update_many($target_ids, array('script_status' => 10));
        }
        if ($mrs && $rs) {
            $transfer_dict = [];
            try {
                $this->CI->investment_model->trans_begin();

                foreach ($transfers as $t => $transfer) {

                    if ($transfer->status != 1) {
                        throw new Exception($transfer->id . '債轉非待結案');
                    }

                    $target = $transfer_info[$t]['targets'];
                    $investment = $transfer_info[$t]['investment'];
                    $transaction_list = $this->CI->transaction_model->get_many_by(array(
                        'investment_id' => $investment->id,
                        'status' => 1
                    ));
                    $principal = 0;
                    if ($transaction_list) {
                        foreach ($transaction_list as $k => $v) {
                            if ($v->source == SOURCE_AR_PRINCIPAL) {
                                $principal += $v->amount;
                            }
                        }
                    }
                    if ($principal != $transfer->principal) {
                        throw new Exception($transfer->id . "債轉的原投資債權案件的（正常的)內帳交易紀錄(應收借款本金)的金額不等於 債轉的剩餘本金");
                    }

                    $transfer_investments = $this->CI->transfer_investment_model->get_by(array(
                        'transfer_id' => $transfer_ids[$t],
                        'status' => 2,
                        'frozen_status' => 1
                    ));
                    if (!$transfer_investments) {
                        throw new Exception($transfer->id . "債轉 沒有 待放款且凍結中的債轉案買方投標歷程紀錄");
                    }

                    $investment_data = array(
                        'target_id' => $investment->target_id,
                        'user_id' => $transfer_investments->user_id,
                        'amount' => intval($transfer->principal),
                        'loan_amount' => intval($transfer->principal),
                        'contract_id' => $transfer_investments->contract_id,
                        'status' => $investment->status,
                    );
                    $transfer_account = $this->CI->virtual_account_model->get_by(['user_id' => $investment->user_id, 'investor' => 1]);
                    if (!$transfer_account) {
                        throw new Exception($investment->user_id . "沒有「原投資債權案件的虛擬帳號」");
                    }

                    $virtual_account = $this->CI->virtual_account_model->get_by(['user_id' => $transfer_investments->user_id, 'investor' => 1]);
                    if (!$virtual_account) {
                        throw new Exception($transfer_investments->user_id . "沒有「債轉案買方的 虛擬帳號」");
                    }


                    $new_investment = $this->CI->investment_model->insert($investment_data);

                    if (!$new_investment) {
                        throw new Exception($transfer->id . "新增投資失敗");
                    }


                    $invest_list[$target->product_id][] = $new_investment;
                    $invest_target[$new_investment] = $target->target_no;

                    $transfer_dict[$transfer->id] = [
                        'target' => $target,
                        'investment' => $investment,
                        'transfer_investments' => $transfer_investments,
                        'transfer_account' => $transfer_account,
                        'virtual_account' => $virtual_account,
                        'new_investment' => $new_investment,
                        'transaction_list' => $transaction_list,
                    ];
                }
                $this->CI->investment_model->trans_commit();


                echo "success" . "<br/>";
            } catch (Exception $e) {
                $this->CI->investment_model->trans_rollback();

                echo "failed:" . $e->getMessage() . "<br/>";
                $need_cancel_transfer = true;
            }

            if (!$need_cancel_transfer) {
                try {
                    foreach ($transfers as $t => $transfer) {

                        $target = $transfer_dict[$transfer->id]['target'];
                        $platform_fee = 0;
                        $transfer_fee = 0;
                        $is_order = false;
                        if ($target->order_id != 0) {
                            $target_inves = $this->CI->investment_model->get_many_by([
                                'target_id' => $target->id,
                                'status' => 10,
                                'transfer_status' => 2,
                            ]);
                            $is_order = $target_inves == false;
                            if ($is_order) {
                                $order = $this->CI->order_model->get($target->order_id);
                                $platform_fee = intval($order->platform_fee);
                                $transfer_fee = intval($order->transfer_fee);
                                $amount -= ($platform_fee + $transfer_fee);
                            }
                        }

                        $investment = $transfer_dict[$transfer->id]['investment'];
                        $transfer_investments = $transfer_dict[$transfer->id]['transfer_investments'];

                        $this->CI->investment_model->update($investment->id, ['status' => 10, 'transfer_status' => 2]);
                        $this->CI->transfer_investment_model->update($transfer_investments->id, ['status' => 10]);
                        $this->CI->target_lib->insert_investment_change_log($investment->id, ['status' => 10, 'transfer_status' => 2], 0, $admin_id);


                        $transaction = [];
                        $new_investment = $transfer_dict[$transfer->id]['new_investment'];
                        $virtual_account = $transfer_dict[$transfer->id]['virtual_account'];
                        $transfer_account = $transfer_dict[$transfer->id]['transfer_account'];

                    if ($t == 0) {
                        $this->CI->frozen_amount_model->update($transfer_investments->frozen_id, ['status' => 0]);
                        //放款
                        $transaction[] = [
                            'source' => SOURCE_TRANSFER,
                            'entering_date' => $date,
                            'user_from' => $transfer_investments->user_id,
                            'bank_account_from' => $virtual_account->virtual_account,
                            'amount' => intval($amount),
                            'target_id' => $target->id,
                            'investment_id' => $new_investment,
                            'instalment_no' => 0,
                            'user_to' => $investment->user_id,
                            'bank_account_to' => $transfer_account->virtual_account,
                            'status' => 2
                        ];
                    }

                    if ($is_order) {
                        //放款(經銷商債轉服務費)
                        $transaction[] = [
                            'source' => SOURCE_TRANSFER,
                            'entering_date' => $date,
                            'user_from' => $transfer_investments->user_id,
                            'bank_account_from' => $virtual_account->virtual_account,
                            'amount' => $transfer_fee,
                            'target_id' => $target->id,
                            'investment_id' => 0,
                            'instalment_no' => 0,
                            'user_to' => $investment->user_id,
                            'bank_account_to' => $transfer_account->virtual_account,
                            'status' => 2
                        ];
                    }

                    //手續費
                        $transaction[] = [
                            'source' => SOURCE_TRANSFER_FEE,
                            'entering_date' => $date,
                            'user_from' => $investment->user_id,
                            'bank_account_from' => $transfer_account->virtual_account,
                            'amount' => $transfer->transfer_fee,
                            'target_id' => $target->id,
                            'investment_id' => $new_investment,
                            'instalment_no' => 0,
                            'user_to' => 0,
                            'bank_account_to' => PLATFORM_VIRTUAL_ACCOUNT,
                            'status' => 2
                        ];

                        if ($is_order) {
                            $user_bankaccount = $this->CI->virtual_account_model->get_by([
                                'user_id' => $target->user_id,
                                'investor' => 0
                            ]);

                        //放款(消費貸平台服務費)
                        $transaction[] = [
                            'source' => SOURCE_TRANSFER,
                            'entering_date' => $date,
                            'user_from' => $transfer_investments->user_id,
                            'bank_account_from' => $virtual_account->virtual_account,
                            'amount' => $platform_fee,
                            'target_id' => $target->id,
                            'investment_id' => 0,
                            'instalment_no' => 0,
                            'user_to' => $target->user_id,
                            'bank_account_to' => $user_bankaccount->virtual_account,
                            'status' => 2
                        ];

                            //消費貸消費者平台手續費
                            $transaction[] = array(
                                'source' => SOURCE_FEES,
                                'entering_date' => $date,
                                'user_from' => $target->user_id,
                                'bank_account_from' => $user_bankaccount->virtual_account,
                                'amount' => $platform_fee,
                                'target_id' => $target->id,
                                'investment_id' => 0,
                                'instalment_no' => 0,
                                'user_to' => 0,
                                'bank_account_to' => PLATFORM_VIRTUAL_ACCOUNT,
                                'status' => 2
                            );
                        }

                        $transaction_list = $transfer_dict[$transfer->id]['transaction_list'];
                        //攤還表
                        if ($transaction_list) {
                            foreach ($transaction_list as $k => $v) {
                                if ($v->user_from == $investment->user_id) {
                                    $transaction[] = [
                                        'source' => $v->source,
                                        'entering_date' => $date,
                                        'user_from' => $transfer_investments->user_id,
                                        'bank_account_from' => $virtual_account->virtual_account,
                                        'amount' => intval($v->amount),
                                        'target_id' => $v->target_id,
                                        'investment_id' => $new_investment,
                                        'instalment_no' => $v->instalment_no,
                                        'user_to' => $v->user_to,
                                        'bank_account_to' => $v->bank_account_to,
                                        'limit_date' => $v->limit_date,
                                    ];
                                }
                                else if ($v->user_to == $investment->user_id) {
                                    $transaction[] = [
                                        'source' => $v->source,
                                        'entering_date' => $date,
                                        'user_from' => $v->user_from,
                                        'bank_account_from' => $v->bank_account_from,
                                        'amount' => intval($v->amount),
                                        'target_id' => $v->target_id,
                                        'investment_id' => $new_investment,
                                        'instalment_no' => $v->instalment_no,
                                        'user_to' => $transfer_investments->user_id,
                                        'bank_account_to' => $virtual_account->virtual_account,
                                        'limit_date' => $v->limit_date,
                                    ];
                                }
                                $this->CI->transaction_model->update($v->id, array('status' => 0));
                            }
                        }

                        if ($transaction) {
                            $this->CI->transfer_model->update($transfer_ids[$t], array(
                                'status' => 10,
                                'transfer_date' => $date,
                                'new_investment' => $new_investment
                            ));
                            $rs = $this->CI->transaction_model->insert_many($transaction);
                            if ($rs && is_array($rs)) {
                                foreach ($rs as $key => $value) {
                                    $this->CI->passbook_lib->enter_account($value);
                                }
                                $this->CI->notification_lib->transfer_success($target->user_id, 0, 0, $target->target_no, $transfer->amount, $transfer_investments->user_id, $date);
                                !isset($user_info[$target->user_id]) ? ($user_info[$target->user_id] = $this->CI->user_model->get($target->user_id)) : '';
                                if ($t == (count($transfers) - 1)) {
                                    $amount = $amount + $platform_fee + $transfer_fee;
                                    $target_no == false ? $target_no = $target->target_no : '';
                                    !isset($user_info[$investment->user_id]) ? ($user_info[$investment->user_id] = $this->CI->user_model->get($investment->user_id)) : '';
                                    !isset($user_info[$transfer_investments->user_id]) ? ($user_info[$transfer_investments->user_id] = $this->CI->user_model->get($transfer_investments->user_id)) : '';

                                //create contract
                                $contract = $this->CI->contract_lib->get_contract($transfer_investments->contract_id, $user_info, false);
                                $create_date = date("YmdHis", $contract['created_at']);
                                $file_name = $create_date . "-" . $target_no . "_transfer_contract";
                                $contract_format = $this->CI->parser->parse('email/contract_content', [
                                    "title" => $contract['title'],
                                    "content" => nl2br($contract['content']),
                                    "time" => nl2br("\n\n中華民國 " . (date('Y', $contract['created_at']) - 1911) . ' ' . date('年 m 月 d 日', $contract['created_at'])),
                                ], TRUE);
                                $attach[$file_name] = $this->CI->estatement_lib->upload_pdf(
                                    $target->user_id,
                                    $contract_format,
                                    $user_info[$investment->user_id]->id_number,
                                    "",
                                    ($file_name . ".pdf"),
                                    "temp",
                                    true,
                                    false,
                                    $user_info[$transfer_investments->user_id]->id_number
                                );
                                //crate amortization
                                $xlsxs = $this->transfer_amortization($invest_list, $invest_target, $target->delay_days, $investment->user_id, $transfer_investments->user_id, $target);

                                $attach = array_merge($attach, $xlsxs);
                                $this->CI->notification_lib->transfer_success($investment->user_id, 1, 0, $target_no, $amount, $transfer_investments->user_id, $date, $attach);
                                $this->CI->notification_lib->transfer_success($transfer_investments->user_id, 1, 1, $target_no, $amount, $transfer_investments->user_id, $date, $attach);
                            }
                        }
                    }


                    if ($is_order) {
                        $this->CI->load->model('transaction/order_model');
                        $order = $this->CI->order_model->get($target->order_id);
                        $this->CI->load->library('coop_lib');
                        $result = $this->CI->coop_lib->coop_request('order/supdate', [
                            'merchant_order_no' => $order->merchant_order_no,
                            'phone' => $order->phone,
                            'type' => 'finish',
                        ], $admin_id);
                        if ($result) {
                            $this->CI->load->library('order_lib');
                            $this->CI->order_lib->order_change($order->id, 5, [
                                'status' => 10,
                            ], $admin_id);
                        }
                    }
                }

                } catch (Exception $e) {
                    echo "failed:" . $e->getMessage() . "<br/>";
                }
            }
        }

        //unlock target
        $this->CI->target_model->update_many($target_ids, array('script_status' => 0));
        $this->CI->transfer_model->update_many($transfer_ids, array('script_status' => 0));

        if ($need_cancel_transfer) {
            $this->CI->load->library('transfer_lib');
            if (count($transfers) > 1) {
                $this->CI->transfer_lib->cancel_combination_transfer($transfers);
            }
            else {
                $this->CI->transfer_lib->cancel_transfer($transfers[0]);
            }
        }
        return true;
    }

	//放款成功
	function subloan_success($target_id,$admin_id=0){
		$date 			= get_entering_date();
		$transaction 	= array();
		if($target_id){
			$target 	= $this->CI->target_model->get($target_id);
            $this->CI->load->library('target_lib');
            if ($target && $target->status == 4 && $target->loan_status == 2 && $this->CI->target_lib->is_sub_loan($target->target_no) === TRUE)
            {
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
							$this->CI->load->model('user/user_model');
							foreach($investments as $key => $value){
								$investment_ids[]	= $value->id;
								$frozen_ids[]		= $value->frozen_id;
								$virtual_account 	= $this->CI->virtual_account_model->get_by(array('user_id'=>$value->user_id,'investor'=>1,'status'=>1));
								$this->CI->notification_lib->lending_success($value->user_id,1,$target->target_no,$target->product_id,$target->sub_product_id,$value->loan_amount,'');
								$this->CI->sendemail->lending_success($value->user_id,1,$target->target_no,$value->loan_amount,'',$target->user_id);

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
								$amortization_schedule 		= $this->CI->financial_lib->get_amortization_schedule($value->loan_amount,$target,$date);
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
										$ar_fee = $this->CI->financial_lib->get_ar_fee($total);
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
                                    'sub_status'    => 0,
									'loan_status'	=> 1,
									'loan_date'		=> $date
								);
								// $this->CI->target_model->update($target_id,$target_update_param);

								# issue_898
								$updateSql = sprintf("UPDATE `%s`.`%s` SET `status` = '5', `sub_status` = '0', `loan_status` = '1', `loan_date` = '%s', `updated_at` = '%s' WHERE `id` = '%s'", P2P_LOAN_DB, P2P_LOAN_TARGET_TABLE, $date, time(), $target_id);
								$this->CI->db->query($updateSql);

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
                                $this->reset_credit_expire_time($target);
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}

    public function transfer_amortization($invest_list,$invest_target,$delay_days=0,$investments,$transfer_investments,$target){
        $xlsxs = false;
        $this->CI->load->library('target_lib');
        $product_list = $this->CI->config->item('product_list');
        foreach ($invest_list as $product_id => $value){
            $investment_amortization = [];
            $contents = [];
            $cell     = [];
            $no_sum   = [3,4,5];
            $sum      = [3,6];
            $sheetTItle = ['案號','應還日期','逾期天數','本金餘額','應收利息','應收延滯息','違約金'];
            $normal_title = ['日期','應收本金','應收利息','合計','當期本金餘額'];
            if($delay_days <= GRACE_PERIOD){
                $sheetTItle=$normal_title;
                foreach ($value as $key => $investment){
                    $get_investment = $this->CI->investment_model->order_by('target_id','ASC')->get_many($investment);
                    $investment_amortization[$investment] = $this->get_investment_amortization($get_investment);
                }
                $no_sum = [4];
                $sum    = [];
            }

            $total_investments = $this->CI->investment_model->order_by('target_id','ASC')->get_many($value);
            $all_investment_amortization = $this->get_investment_amortization($total_investments,$delay_days,$invest_target,$target);
            foreach ($all_investment_amortization as $sheets => $sheetDatas) {
                $array = array_values($sheetDatas);
                array_unshift($array,$sheets);
                $cell[] = $array;
            }
            $contents[] = [
                'sheet'   => '總表',
                'title'   => $sheetTItle,
                'content' => $cell,
            ];
            foreach ($investment_amortization as $investment => $investmentData) {
                $cell  = [];
                foreach ($investmentData as $sheet => $sheetData) {
                    $array = array_values($sheetData);
                    array_unshift($array, $sheet);
                    $cell[] = $array;
                }
                $contents[] = [
                    'sheet' => $invest_target[$investment],
                    'title' => $sheetTItle,
                    'content' => $cell,
                ];
            }

            $descri = '普匯inFlux 使用者 [ '.$investments.' 債權轉換 '.$transfer_investments.' ]';
            $this->CI->load->library('Phpspreadsheet_lib');
            $file_name = date("YmdHis",time()).'-'.$product_list[$product_id]['alias'].'-'.$investments.'To'.$transfer_investments.'_transfer_targets_amortization.xlsx';
            $xlsxs[$file_name] = $this->CI->phpspreadsheet_lib->excel($file_name,$contents,'債權轉讓攤還表','總表與各案件攤還表',$descri,$transfer_investments,false,$no_sum,$sum);

        }
        return $xlsxs;
    }

    private function get_investment_amortization($investments,$delay_days = 0,$target_nos='',$target=''){
        $list= [];
        foreach($investments as $key => $value){
            $i=1;
            $targets = $this->CI->target_model->get($value->target_id);
            $amortization_table = $this->CI->target_lib->get_investment_amortization_table($targets,$value);
            if($amortization_table && !empty($amortization_table['list'])){
                foreach($amortization_table['list'] as $k => $v){
                    if(!isset($list[$v['repayment_date']])){
                        if($delay_days <= GRACE_PERIOD){
                            $list[$v['repayment_date']] = array(
                                'principal'	=> 0,
                                'interest'	=> 0,
                                'total'	=> 0,
                                'remaining_principal'	=> 0,
                            );
                        }
                    }
                    if($delay_days > GRACE_PERIOD){
                        if(count($amortization_table['list'])==$i){
                            $target_no = $target_nos[$value->id];
                            $list[$target_no]['repayment_date']      = $v['repayment_date'];
                            $list[$target_no]['days']                = $v['days'];
                            $list[$target_no]['remaining_principal'] = $v['remaining_principal'];
                            $list[$target_no]['interest']            = $v['interest'];
                            $list[$target_no]['delay_interest']      = $v['delay_interest'];
                            $transaction = $this->CI->transaction_model->order_by('limit_date','asc')->get_many_by(array(
                                'target_id'	=> $target->id,
                                'user_from'	=> $target->user_id,
                                'status'	=> 1,
                                'source'	=> 91,
                            ));
                            $list[$target_no]['liquidated_damages']  = 0;
                            foreach($transaction as $key => $value){
                                $list[$target_no]['liquidated_damages'] += $value->amount;
                            }
                        }
                    }
                    else{
                        $list[$v['repayment_date']]['principal'] 	        += $v['principal'];
                        $list[$v['repayment_date']]['interest'] 	        += $v['interest'];
                        $list[$v['repayment_date']]['total'] 	            += $v['principal']+$v['interest'];
                        $list[$v['repayment_date']]['remaining_principal'] 	+= $v['remaining_principal'];
                    }
                    $i++;
                }
            }
        }
        return $list;
    }

    private function reset_credit_expire_time($target=[]){
        $this->CI->load->library('credit_lib');
        $credit = $this->CI->credit_lib->get_credit($target->user_id,$target->product_id, $target->sub_product_id);
        $expire_time = $credit['expire_time'];
        $max_time = strtotime("+2 months", time());
        $this->CI->load->model('loan/credit_model');
        $this->CI->credit_model->update($credit['id'],[
            'expire_time'=> ($expire_time>=$max_time)?$max_time:$expire_time,
        ]);
        return true;
    }

    public function getDelayUserList()
    {
        return $this->CI->transaction_model->getDelayUserList();
    }

    /**
     * 外部慈善捐款的金流另外處理
     **/
    public function charity_recharge($payment, $user_id, $charity_institution_id)
    {
        if ($payment->status != '1' &&
            $payment->amount > 0 &&
            ! empty($payment->virtual_account))
        {
            $bank = bankaccount_substr($payment->bank_acc);

            $trans_db = $this->CI->load->database('transaction', TRUE);
            $trans_db->trans_begin();

            // 寫入 transaction
            $transactions = [
                'source' => SOURCE_CHARITY,
                'entering_date' => get_entering_date(),
                'user_from' => 0,
                'bank_account_from' => $bank['bank_account'],
                'amount' => (int) $payment->amount,
                'user_to' => $user_id,
                'bank_account_to' => $payment->virtual_account,
                'status' => TRANSACTION_STATUS_PAID_OFF,
                'passbook_status' => 1, // passbook 成功寫入後會紀錄 1
                'created_at' => time(),
                'created_ip' => get_ip(),
                'updated_at' => time(),
                'updated_ip' => get_ip(),
            ];
            $trans_db->insert('transactions', $transactions);
            $transaction_id = $trans_db->insert_id();

            // 新增慈善捐款紀錄
            $donate_data = [
                'payment_id' => $payment->id,
                'transaction_id' => $transaction_id,
                'charity_institution_id' => $charity_institution_id,
                'last5' => substr($bank['bank_account'], -5),
                'amount' => (int) $payment->amount,
                'created_at' => date('Y-m-d H:i:s'),
                'created_ip' => get_ip(),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_ip' => get_ip(),
            ];
            $trans_db->insert('anonymous_donate', $donate_data);
            $donate_id = $trans_db->insert_id();

            // 寫入 virtual_passbook
            $passbook_record = [
                'virtual_account' => $payment->virtual_account,
                'transaction_id' => $transaction_id,
                'amount' => (int) $payment->amount,
                'remark' => json_encode([
                    'source' => SOURCE_CHARITY,
                    'target_id' => 0,
                ]),
                'tx_datetime' => $payment->tx_datetime,
                'created_at' => time(),
                'created_ip' => get_ip(),
            ];
            $trans_db->insert('virtual_passbook', $passbook_record);

            // 更新 payment 處理狀態
            $trans_db->where('id', $payment->id);
            $trans_db->update('payments', ['status' => 1]);

            if ($trans_db->trans_status() === FALSE)
            {
                $trans_db->trans_rollback();
                return FALSE;
            }
            else
            {
                $trans_db->trans_commit();
                return $donate_id;
            }
        }

        return FALSE;
    }

    /**
     * 檢查提領金額是否符合最低限制
     * @param $amount
     * @return bool
     */
    public function check_minimum_withdraw_amount($amount): bool
    {
        $amount = (int) $amount;
        return $amount >= MINIMUM_WITHDRAW_AMOUNT;
    }
}
