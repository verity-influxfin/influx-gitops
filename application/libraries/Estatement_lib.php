<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estatement_lib{

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('user/virtual_account_model');
		$this->CI->load->model('user/user_estatement_model');
		$this->CI->load->model('transaction/virtual_passbook_model');
		$this->CI->load->model('transaction/frozen_amount_model');
		$this->CI->load->library('S3_upload');
		$this->CI->load->library('parser');
		$this->CI->load->library('credit_lib');
		$this->CI->load->library('sendemail');
    }

	public function get_estatement_investor($user_id=0,$sdate="",$edate="",$export=false){
		$user_info = $this->CI->user_model->get($user_id);
		if($user_info){
			$virtual_account = $this->CI->virtual_account_model->get_by(array(
				"status"	=> 1,
				"investor"	=> 1,
				"user_id"	=> $user_id
			));
			$virtual_account_record_count = 0;
			if($virtual_account){
				$date_range			= entering_date_range($edate);
				$edatetime			= $date_range?$date_range["edatetime"]:"";
				$date_range			= entering_date_range($sdate);
				$sdatetime			= $date_range?$date_range["sdatetime"]:"";
				$total 				= $frozen = $interest = $interest_count = $allowance = $allowance_count = 0;
				$ar_principal 		= $ar_interest = $delay_ar_principal = $delay_ar_interest = $ar_total = 0;
				$ar_principal_count = $ar_interest_count = $delay_ar_principal_count = $delay_ar_interest_count = $ar_total_count = 0;
				if($edatetime){
					$virtual_passbook = $this->CI->virtual_passbook_model->order_by("virtual_account","ASC")->get_many_by(array(
						"virtual_account" 	=> $virtual_account->virtual_account,
						"tx_datetime <=" 	=> $edatetime,
					));
					if(!empty($virtual_passbook)){
						foreach($virtual_passbook as $key => $value){
							if($value->tx_datetime>=$sdatetime && $value->amount>0){
								$value->remark = json_decode($value->remark,TRUE);
								if($value->remark['source']==SOURCE_INTEREST || $value->remark['source']==SOURCE_DELAYINTEREST){
									$interest += $value->amount;
									$interest_count++;
								}

								if($value->remark['source']==SOURCE_PREPAYMENT_ALLOWANCE){
									$allowance += $value->amount;
									$allowance_count++;
								}
								$virtual_account_record_count++;
							}
							$total += intval($value->amount);
						}
					}

					$frozen_amount 		= $this->CI->frozen_amount_model->get_many_by(array(
						"virtual_account" 	=> $virtual_account->virtual_account,
						"created_at <=" 	=> strtotime($edatetime),
					));
					if($frozen_amount){
						foreach($frozen_amount as $key => $value){
							if($value->status==1){
								$frozen += intval($value->amount);
							}else if($value->status==0 && $value->updated_at>strtotime($edatetime)){
								$frozen += intval($value->amount);
							}
						}
					}

					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"source"			=> array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST,SOURCE_AR_DELAYINTEREST),
						"user_to" 			=> $user_id,
						"status" 			=> 1
					));

					if($transactions){
						$investment = array(
							"ar_principal"		=> array(),
							"delay_ar_principal"=> array(),
							"delay_ar_interest"	=> array(),
							"ar_interest"		=> array(),
						);

						foreach($transactions as $key => $value){

							if($value->limit_date < $edate){
								$delay_days	= get_range_days($value->limit_date,$edate);
							}else{
								$delay_days = 0;
							}

							switch ($value->source) {
								case SOURCE_AR_PRINCIPAL:
									if($delay_days > GRACE_PERIOD){
										$delay_ar_principal += $value->amount;
										$investment['delay_ar_principal'][$value->investment_id] = $value->investment_id;
									}else{
										$ar_principal += $value->amount;
										$investment['ar_principal'][$value->investment_id] = $value->investment_id;
									}
									break;
								case SOURCE_AR_INTEREST:
									if($delay_days > GRACE_PERIOD){
										$delay_ar_interest += $value->amount;
										$investment['delay_ar_interest'][$value->investment_id] = $value->investment_id;
									}else{
										$ar_interest += $value->amount;
										$investment['ar_interest'][$value->investment_id] = $value->investment_id;
									}
									break;
								case SOURCE_AR_DELAYINTEREST:
									$delay_ar_interest += $value->amount;
									$investment['delay_ar_interest'][$value->investment_id] = $value->investment_id;
									break;
								default:
									break;
							}
						}

						$else_investment 	= array();
						$transactions 		= $this->CI->transaction_model->get_many_by(array(
							"entering_date >"	=> $edate,
							"source"			=> array(SOURCE_PREPAYMENT_ALLOWANCE,SOURCE_TRANSFER),
							"user_to" 			=> $user_id,
							"status" 			=> 2,
							"created_at >" 		=> strtotime($edatetime),
							"created_at <=" 	=> time(),
						));
						if($transactions){
							foreach($transactions as $key => $value){
								$else_investment[$value->investment_id] = $value->investment_id;
							}
						}
						$transactions 	= $this->CI->transaction_model->get_many_by(array(
							"entering_date <="	=> $edate,
							"source"			=> array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST,SOURCE_AR_DELAYINTEREST),
							"user_to" 			=> $user_id,
							"status" 			=> array(0,2),
							"updated_at >" 		=> strtotime($edatetime),
							"updated_at <=" 	=> time(),
						));

						if($transactions){
							foreach($transactions as $key => $value){
								if(
									(isset($else_investment[$value->investment_id]) && $value->status==0) ||
									(!isset($else_investment[$value->investment_id]) && $value->status==2)
								){
									if($value->limit_date < $edate){
										$delay_days	= get_range_days($value->limit_date,$edate);
									}else{
										$delay_days = 0;
									}
									switch ($value->source) {
										case SOURCE_AR_PRINCIPAL:
											if($delay_days > GRACE_PERIOD){
												$delay_ar_principal += $value->amount;
												$investment['delay_ar_principal'][$value->investment_id] = $value->investment_id;
											}else{
												$ar_principal += $value->amount;
												$investment['ar_principal'][$value->investment_id] = $value->investment_id;
											}
											break;
										case SOURCE_AR_INTEREST:
											if($delay_days > GRACE_PERIOD){
												$delay_ar_interest += $value->amount;
												$investment['delay_ar_interest'][$value->investment_id] = $value->investment_id;
											}else{
												$ar_interest += $value->amount;
												$investment['ar_interest'][$value->investment_id] = $value->investment_id;
											}
											break;
										case SOURCE_AR_DELAYINTEREST:
											$delay_ar_interest += $value->amount;
											$investment['delay_ar_interest'][$value->investment_id] = $value->investment_id;
											break;
										default:
											break;
									}
								}
							}
						}
						$ar_principal_count 		= count($investment['ar_principal']);
						$ar_interest_count 			= count($investment['ar_interest']);
						$delay_ar_principal_count 	= count($investment['delay_ar_principal']);
						$delay_ar_interest_count 	= count($investment['delay_ar_interest']);
						$ar_total_count = $ar_principal_count + $delay_ar_principal_count ;
						$ar_total		= $ar_principal + $ar_interest + $delay_ar_principal + $delay_ar_interest;
					}
				}

				// 無任何應收帳款合計&利息收入&違約補貼金&應收本金&應收利息&虛擬帳號出入記錄時，不寄送帳單
                if(!$ar_total_count && !$interest_count && !$allowance_count && !$ar_principal_count && !$ar_interest_count && !$virtual_account_record_count) {
                    $param = array(
                        "user_id"	=> $user_id,
                        "type"		=> "estatement_failed",
                        "investor"	=> 1,
                        "sdate"		=> $sdate,
                        "edate"		=> $edate,
                        "content"	=> "",
                        "url"		=> "",
                        "status"    => 1,
                    );
					$rs = $this->CI->user_estatement_model->insert($param);
					return false;
				}

				$data = array(
					"edate" 					=> $edate,
					"sdate" 					=> $sdate,
					"user_id" 					=> $user_id,
					"user_name"					=> $user_info->name,
					"total"						=> number_format($total - $frozen),
					"frozen"					=> number_format($frozen),
					"interest"					=> number_format($interest),
					"interest_count"			=> number_format($interest_count),
					"allowance"					=> number_format($allowance),
					"allowance_count"			=> number_format($allowance_count),
					"ar_principal"				=> number_format($ar_principal),
					"ar_principal_count"		=> $ar_principal_count,
					"ar_interest"				=> number_format($ar_interest),
					"ar_interest_count"			=> $ar_interest_count,
					"delay_ar_principal"		=> number_format($delay_ar_principal),
					"delay_ar_interest"			=> number_format($delay_ar_interest),
					"delay_ar_principal_count"	=> $delay_ar_principal_count,
					"delay_ar_interest_count"	=> $delay_ar_interest_count,
					"ar_total"					=> number_format($ar_total),
					"ar_total_count"			=> number_format($ar_total_count),
					"virtual_account"			=> $virtual_account->virtual_account,
				);
				$html 	= $this->CI->parser->parse('estatement/investor', $data,TRUE);

                if($export){
                    header('Content-type:application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename=estatement_'.date('Ymd').'.xls');
                    echo $html;
                    return true;
                }

				$update_estatement = $this->CI->user_estatement_model->get_by(array(
					"user_id"	=> $user_id,
					"type"		=> "estatement",
					"investor"	=> 1,
					"sdate"		=> $sdate,
					"edate"		=> $edate,
					"url"		=> "",
				));
				if($update_estatement && $update_estatement->id){
					$rs = $this->CI->user_estatement_model->update($update_estatement->id,array("content"=>$html));
				}else{
					$param = array(
						"user_id"	=> $user_id,
						"type"		=> "estatement",
						"investor"	=> 1,
						"sdate"		=> $sdate,
						"edate"		=> $edate,
						"content"	=> $html,
						"url"		=> "",
					);
					$rs = $this->CI->user_estatement_model->insert($param);
				}

				return $rs;
			}
		}
		return false;
	}

	public function get_estatement_borrower($user_id=0,$sdate="",$edate=""){
		$user_info 	= $this->CI->user_model->get($user_id);
		$product_id	= 1;
		if($user_info){
			$virtual_account = $this->CI->virtual_account_model->get_by(array(
				"status"	=> 1,
				"investor"	=> 0,
				"user_id"	=> $user_id
			));
			if($virtual_account){
				$credit 	 = $this->CI->credit_lib->get_credit($user_id,$product_id);
				$used_credit = 0;
				$target_list 	= $this->CI->target_model->get_many_by(array("product_id"=>$product_id,"user_id"=>$user_id,"status <="=>5));
				if($target_list){
					foreach($target_list as $key =>$value){
						$used_credit += intval($value->loan_amount);
					}
				}

				$date_range	= entering_date_range($edate);
				$edatetime	= $date_range?$date_range["edatetime"]:"";
				$date_range	= entering_date_range($sdate);
				$sdatetime	= $date_range?$date_range["sdatetime"]:"";
				$normal_count 	= 0;
				$total 			= $normal_amount = $normal_rapay = $ar_principal = 0;
				$delay_rapay 	= $delay_amount = $delay_count = 0;
				$normal_target 	= $delay_target = array();
				if($edatetime){
					$virtual_passbook = $this->CI->virtual_passbook_model->order_by("virtual_account","ASC")->get_many_by(array(
						"virtual_account" 	=> $virtual_account->virtual_account,
						"tx_datetime <=" 	=> $edatetime,
					));

					if(!empty($virtual_passbook)){
						foreach($virtual_passbook as $key => $value){
							$total += intval($value->amount);
						}
					}

					//目前逾期案
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"limit_date <="		=> $edate,
						"source"			=> SOURCE_AR_DAMAGE,
						"user_from" 		=> $user_id,
						"status" 			=> 1
					));
					if($transactions){

						foreach($transactions as $key => $value){
							$delay_target[] = $value->target_id;
							$delay_count++;
						}

						$transactions 	= $this->CI->transaction_model->get_many_by(array(
							"limit_date <="	=> $edate,
							"source"			=> array(
								SOURCE_AR_PRINCIPAL,
								SOURCE_AR_INTEREST,
								SOURCE_AR_DELAYINTEREST,
								SOURCE_AR_DAMAGE,
							),
							"target_id"		=> $delay_target,
							"user_from" 	=> $user_id,
							"status" 		=> 1
						));
						if($transactions){
							foreach($transactions as $key => $value){
								$delay_amount += $value->amount;//逾期未還金額
							}
						}
					}

					//本期逾期還款
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"entering_date >="	=> $sdate,
						"source"			=> SOURCE_DAMAGE,
						"user_from" 		=> $user_id,
						"status" 			=> 2
					));
					if($transactions){
						foreach($transactions as $key => $value){
							$delay_target[] = $value->target_id;
							$delay_count++;
						}

						$transactions 	= $this->CI->transaction_model->get_many_by(array(
							"entering_date <="	=> $edate,
							"entering_date >="	=> $sdate,
							"source"			=> array(
								SOURCE_PRINCIPAL,
								SOURCE_INTEREST,
								SOURCE_DELAYINTEREST,
								SOURCE_DAMAGE,
							),
							"target_id"			=> $delay_target,
							"user_from" 		=> $user_id,
							"status" 			=> 2
						));
						if($transactions){
							foreach($transactions as $key => $value){
								$delay_rapay += $value->amount;//逾期已還金額
							}
						}
					}

					//本期應還
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"limit_date <="		=> $edate,
						"limit_date >="		=> $sdate,
						"source"			=> array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST),
						"user_from" 		=> $user_id,
						"status" 			=> array(1,2)
					));
					if($transactions){
						foreach($transactions as $key => $value){
							if(!in_array($value->target_id,$delay_target)){
								switch ($value->source) {
									case SOURCE_AR_PRINCIPAL:
										$normal_target[$value->target_id] = $value->target_id;
										$normal_amount += $value->amount;
										break;
									case SOURCE_AR_INTEREST:
										$normal_amount += $value->amount;
										break;
									default:
										break;
								}
							}
						}
					}

					//剩餘正常案本金
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"source"			=> SOURCE_AR_PRINCIPAL,
						"user_from" 		=> $user_id,
						"status" 			=> 1
					));
					if($transactions){
						foreach($transactions as $key => $value){
							if(!in_array($value->target_id,$delay_target)){
								$ar_principal += $value->amount;
							}
						}
					}

					//提前還款
					$prepayment_count 	= 0;
					$prepayment_amount 	= 0;
					$prepayment_target	= array();
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"entering_date >="	=> $sdate,
						"source"			=> SOURCE_PREPAYMENT_DAMAGE,
						"user_from" 		=> $user_id,
						"status" 			=> 2
					));
					if($transactions){
						foreach($transactions as $key => $value){
							$prepayment_count++;
							$prepayment_target[] = $value->entering_date.'-'.$value->target_id;
						}
					}

					//本期還款
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"entering_date >="	=> $sdate,
						"source"			=> array(
							SOURCE_PRINCIPAL,
							SOURCE_INTEREST,
						),
						"user_from" 		=> $user_id,
						"status" 			=> 2
					));
					if($transactions){
						foreach($transactions as $key => $value){
							if(!in_array($value->target_id,$delay_target)){
								$normal_rapay += $value->amount;
								if(in_array($value->entering_date.'-'.$value->target_id,$prepayment_target)){
									$prepayment_amount += $value->amount;
								}
							}
						}
					}
				}
				$data = array(
					"edate" 			=> $edate,
					"sdate" 			=> $sdate,
					"user_id" 			=> $user_id,
					"user_name"			=> $user_info->name,
					"total"				=> number_format($total),
					"credit_amount"		=> number_format($credit['amount']),
					"used_credit"		=> number_format($used_credit),
					"normal_count"		=> count($normal_target),//本期筆數
					"normal_amount"		=> number_format($normal_amount),//本期應還金額
					"normal_rapay"		=> number_format($normal_rapay),//本期已還金額
					"ar_principal"		=> number_format($ar_principal),//剩餘本金總額
					"prepayment_count"	=> number_format($prepayment_count),
					"prepayment_amount"	=> number_format($prepayment_amount),
					"delay_rapay"		=> number_format($delay_rapay),//逾期已還金額
					"delay_amount"		=> number_format($delay_amount),//逾期未還金額
					"delay_count"		=> $delay_count,//逾期筆數
					"virtual_account"	=> $virtual_account->virtual_account,
				);

				if(!$normal_amount && !$normal_rapay && !$ar_principal && !$delay_rapay && !$delay_amount && !$delay_count) {
					return false;
				}

				$html 	= $this->CI->parser->parse('estatement/borrower', $data,TRUE);
				$update_estatement = $this->CI->user_estatement_model->get_by(array(
					"user_id"	=> $user_id,
					"type"		=> "estatement",
					"investor"	=> 0,
					"sdate"		=> $sdate,
					"edate"		=> $edate,
					"url"		=> "",
				));
				if($update_estatement && $update_estatement->id){
					$rs = $this->CI->user_estatement_model->update($update_estatement->id,array("content"=>$html));
				}else{
					$param = array(
						"user_id"	=> $user_id,
						"type"		=> "estatement",
						"investor"	=> 0,
						"sdate"		=> $sdate,
						"edate"		=> $edate,
						"content"	=> $html,
						"url"		=> "",
					);
					$rs = $this->CI->user_estatement_model->insert($param);
				}
				return $rs;
			}
		}
		return false;
	}

	public function get_estatement_investor_detail($user_id=0,$sdate="",$edate="",$export=false)
	{
		$user_info = $this->CI->user_model->get($user_id);
        $this->CI->load->library('Transfer_lib');
		if($user_info){
			$virtual_account = $this->CI->virtual_account_model->get_by(array(
				"status"	=> 1,
				"investor"	=> 1,
				"user_id"	=> $user_id
			));
			if($virtual_account){
				$date_range	= entering_date_range($edate);
				$edatetime	= $date_range?$date_range["edatetime"]:"";
				$date_range	= entering_date_range($sdate);
				$sdatetime	= $date_range?$date_range["sdatetime"]:"";
				$first		= 0;
				$list 		= array();
				$tmp_list 	= array();
				$transaction_id = array();
				$target_id 		= array();
				$target_list 	= array();
				$combinations_list  = [];
				$combinations_ids   = [];
				$combinations_ids_u = [];
				if($edatetime){
					$virtual_passbook = $this->CI->virtual_passbook_model->order_by("virtual_account","ASC")->get_many_by(array(
						"virtual_account" 	=> $virtual_account->virtual_account,
						"tx_datetime <=" 	=> $edatetime,
					));

					if(!empty($virtual_passbook)){
						foreach($virtual_passbook as $key => $value){
							if($value->tx_datetime>=$sdatetime){
								$transaction_id[] 	= $value->transaction_id;
								$remark 			= json_decode($value->remark,TRUE);
								if($remark['target_id']){
									$target_id[]	= $remark['target_id'];
								}
							}else{
								$first += intval($value->amount);
							}
						}
					}

					$tmp_list[] = array("date"=>date("Y-m-d",strtotime($sdate.' -1 day')),"income_principal"=>$first);
				}

				if($target_id){
					$target_id 	= array_unique($target_id);
					$targets 	= $this->CI->target_model->get_many($target_id);
					if($targets){
						foreach($targets as $key =>$value){
							$target_list[$value->id] = $value;
						}
					}
				}

				if($transaction_id){
					$transactions 	= $this->CI->transaction_model->get_many($transaction_id);
					foreach($transactions as $key => $value){
                        if(!in_array($value->target_id,$combinations_ids)) {
                            $combinations_info = $this->CI->transfer_lib->check_combination($value->target_id, $value->investment_id);
                            $combinations_list[$value->target_id] = $combinations_info;
                            $combinations_info?$combinations_ids[] = $value->target_id:'';
                        }
						switch ($value->source) {
							case SOURCE_TRANSFER_FEE:
                                if(in_array($value->target_id,$combinations_ids)&&!in_array($combinations_info[0],$combinations_ids_u)&&$combinations_info[0]!=null){
                                    $tmp_list[$value->investment_id.'-tc'.$value->entering_date] = array(
                                        "date"			    => $value->entering_date,
                                        "target_no"		    => $combinations_info[0],
                                        "title"			    => "債權出讓",
                                        "income_principal"	=> $combinations_info[1],
                                    );
                                    $combinations_ids_u[] = $combinations_info[0];
                                }
                                $tmp_list[$value->investment_id.'-t'.$value->entering_date] = array(
									"date"			=> $value->entering_date,
									"target_no"		=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
									"title"			=> "債權出讓",
									"cost_fee"		=> intval($value->amount),
									"income_principal"	=> 0,
								);
                                break;
							case SOURCE_PRINCIPAL:
								$instalment = isset($target_list[$value->target_id])?$target_list[$value->target_id]->instalment:"";
								if(isset($tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date])){
									$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['income_principal'] += intval($value->amount);
								}else{
									$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date] = array(
										"date"				=> $value->entering_date,
										"target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
										"title"				=> "收款",
										"income_principal"	=> intval($value->amount),
										"remark" 			=> "帳期：".$value->instalment_no.'/'.$instalment,
										"income_interest" 	=> 0,
										"income_delay_interest" 	=> 0,
										"income_allowance" 	=> 0,
										"cost_fee" 			=> 0,
									);
								}
								break;
							default:
								break;
						}
					}
					foreach($transactions as $key => $value){
						switch ($value->source) {
							case SOURCE_RECHARGE:
								$tmp_list[] = array(
									"date"				=> $value->entering_date,
									"target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
									"title"				=> "代收",
									"income_principal"	=> intval($value->amount),
								);
								break;
							case SOURCE_BANK_WRONG_TX_B:
								$tmp_list[] = array(
									"date"				=> $value->entering_date,
									"target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
									"title"				=> "銀行錯帳撥還",
									"income_principal"	=> intval($value->amount),
								);
								break;
							case SOURCE_WITHDRAW:
								$tmp_list[] = array(
									"date"				=> $value->entering_date,
									"target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
									"title"				=> "提領",
									"cost_principal"	=> intval($value->amount),
								);
								break;
                            case SOURCE_LENDING:
                                $tmp_list[] = array(
                                    "date"				=> $value->entering_date,
                                    "target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
                                    "title"				=> "投資",
                                    "cost_principal"	=> intval($value->amount),
                                );
                                break;
                            case SOURCE_TRANSFER_B:
                                if($value->user_from == $user_id){
                                    $tmp_list[] = array(
                                        "date"				=> $value->entering_date,
                                        "target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
                                        "title"				=> "債權轉讓金沖正",
                                        "cost_principal"	=> intval($value->amount),
                                    );
                                }
                                if($value->user_to == $user_id){
                                    $tmp_list[] = array(
                                        "date"				=> $value->entering_date,
                                        "target_no"			=> isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
                                        "title"				=> "債權轉讓金沖正",
                                        "income_principal"	=> intval($value->amount),
                                    );
                                }
                                break;
                            case SOURCE_TRANSFER:
                                if($value->user_from == $user_id){
                                    if(!in_array($combinations_info[0],$combinations_ids_u)){
                                        $combinations_info = $this->CI->transfer_lib->check_combination($value->target_id, $value->investment_id);
                                        $combinations_list[$value->target_id] = $combinations_info;
                                        $combinations_info?$combinations_ids[] = $value->target_id:'';
                                        $t_target_no = $combinations_info[0];
                                        $combinations_ids_u[] = $combinations_info[0];
                                    }
                                    else{
                                        $t_target_no = isset($target_list[$value->target_id]) ? $target_list[$value->target_id]->target_no : "";
                                    }
                                    $tmp_list[] = array(
                                        "date" => $value->entering_date,
                                        "target_no" => $t_target_no,
                                        "title" => "債權受讓",
                                        "cost_principal" => intval($value->amount),
                                    );
								}
								if($value->user_to == $user_id){
									if(isset($tmp_list[$value->investment_id.'-t'.$value->entering_date])){
									    if(!in_array($value->target_id,$combinations_ids)){
                                            $tmp_list[$value->investment_id.'-t'.$value->entering_date]["income_principal"] += intval($value->amount);
                                        }
									}else{
										$tmp_list[$value->investment_id.'-t'.$value->entering_date] = array(
											"date"				=> $value->entering_date,
											"target_no"		    => isset($target_list[$value->target_id])?$target_list[$value->target_id]->target_no:"",
											"title"				=> "債權出讓",
											"income_principal"	=> intval($value->amount),
										);
									}
								}
								break;
							case SOURCE_INTEREST:
								$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['income_interest'] += intval($value->amount);
								break;
							case SOURCE_DELAYINTEREST:
								$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['income_delay_interest'] += intval($value->amount);
								break;
							case SOURCE_PREPAYMENT_ALLOWANCE:
								$instalment = isset($target_list[$value->target_id])?$target_list[$value->target_id]->instalment:"";
								$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['income_allowance'] += intval($value->amount);
								$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['remark'] = '提前清償<br>帳期：'.$value->instalment_no.'/'.$instalment;
								break;
							case SOURCE_FEES:
								$platform_fee = intval($value->amount);
								// 特殊情境, 退還平台服務費, issue#816
								if ($value->bank_account_from == PLATFORM_VIRTUAL_ACCOUNT) {
									$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['date'] = $value->entering_date;
									$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['title'] = '錯帳退款';
									$platform_fee = $platform_fee * -1;
								}
								$tmp_list[$value->investment_id.'-'.$value->instalment_no.$value->entering_date]['cost_fee'] += $platform_fee;
								break;
                            case SOURCE_LAW_FEE: // 法催執行費
                                $tmp_list[] = array(
                                    'date' => $value->entering_date,
                                    'target_no' => 0,
                                    'title' => '手續費用 - 法訴費用',
                                    'cost_principal' => intval($value->amount),
                                );
                                break;
							default:
								break;
						}
					}
					$field = array(
						"date",
						"target_no",
						"title",
						"income_principal",
						"income_interest",
						"income_delay_interest",
						"income_allowance",
						"cost_principal",
						"cost_fee",
						"amount",
						"remark"
					);
					foreach($tmp_list as $key => $value){
						foreach($field as $k => $v){
							if(!isset($value[$v]) || empty($value[$v])){
								$value[$v] = "";
							}
						}
						$list[] = $value;
					}

					$num = count($list);
					for($i = 0 ; $i < $num ; $i++){
						for ($j=$i+1;$j<$num;$j++) {
							$a = $list[$i]["date"];
							$b = $list[$j]["date"];
							if( $a > $b ){
								$tmp      = $list[$i];
								$list[$i] = $list[$j];
								$list[$j] = $tmp;
							}
						}
					}
					$field = array(
						"income_principal",
						"income_interest",
						"income_delay_interest",
						"income_allowance",
						"cost_principal",
						"cost_fee",
						"amount",
					);
					$amount = 0;
					foreach($list as $key => $value){
						$amount +=
						intval($value["income_principal"]) +
						intval($value["income_interest"])+
						intval($value["income_delay_interest"])+
						intval($value["income_allowance"])-
						intval($value["cost_principal"])-
						intval($value["cost_fee"]);
						$value["amount"] = $amount;
						foreach($field as $k => $v){
							if($value[$v])
								$value[$v] = number_format($value[$v]);
						}
						$list[$key] = $value;
					}
				}

				$data = array(
					"edate" 		=> $edate,
					"sdate" 		=> $sdate,
					"user_id" 		=> $user_id,
					"user_name"		=> $user_info->name,
					"list"			=> $list,
				);
				$html 	= $this->CI->parser->parse('estatement/investor_detail', $data,TRUE);

                if($export){
                    header('Content-type:application/vnd.ms-excel');
                    header('Content-Disposition: attachment; filename=estatementdetail_'.date('Ymd').'.xls');
                    echo $html;
                    return true;
                }
				$update_estatement = $this->CI->user_estatement_model->get_by(array(
					"user_id"	=> $user_id,
					"type"		=> "estatementdetail",
					"investor"	=> 1,
					"sdate"		=> $sdate,
					"edate"		=> $edate,
					"url"		=> "",
				));
				if($update_estatement && $update_estatement->id){
					$rs = $this->CI->user_estatement_model->update($update_estatement->id,array("content"=>$html));
				}else{
					$param = array(
						"user_id"	=> $user_id,
						"type"		=> "estatementdetail",
						"investor"	=> 1,
						"sdate"		=> $sdate,
						"edate"		=> $edate,
						"content"	=> $html,
						"url"		=> "",
					);
					$rs = $this->CI->user_estatement_model->insert($param);
				}
				return $rs;
			}
		}
		return false;
	}

	public function upload_pdf($user_id=0,$html="",$password="",$title="",$file_name="",$path="",$orientation=false,$loaalTemp=false,$bpassword=false){
		if($user_id){
            $spassword = $bpassword ? $bpassword : PDF_OWNER_PASSWORD;
            $orientation?'P':'L';
			$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$permissions  = array(
				'print',
				'modify',
				'copy',
				'annot-forms',
				'fill-forms',
				'extract',
				'assemble',
				'print-high'
			);
			$pdf->SetProtection($permissions , $password , $spassword, 0, null);
			$pdf->SetCreator(GMAIL_SMTP_NAME);
			$pdf->SetAuthor(GMAIL_SMTP_NAME);
			//$pdf->SetTitle($title);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->setFontSubsetting(true);
            $pdf->SetFont('msungstdlight', '', 10);
			$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->AddPage($orientation);
			$pdf->writeHTML($html, 1, 0, true, true, '');
			$files 		= $pdf->Output("","S");
            if($loaalTemp){
                return $files;
            }
			$file_url 	= $this->CI->s3_upload->pdf ($files,$file_name,$user_id,'estatement/'.$path);
			if($file_url){
				return $file_url;
			}
		}
		return false;
	}

    function get_all_investor_user_list($sdate="",$edate=""){
        if (empty($sdate) || empty($edate) || $edate < $sdate) {
            return false;
        }

        $date_range = entering_date_range($edate);
        $edatetime = $date_range ? $date_range["edatetime"] : "";
        $date_range = entering_date_range($sdate);
        $sdatetime = $date_range ? $date_range["sdatetime"] : "";
        if (!$edatetime){
            return [];
        }

        $transaction 	= $this->CI->transaction_model->get_many_by(array(
            "source" 				=> [1,10],
            "bank_account_to like" 	=> CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE."%",
            "entering_date <=" 		=> $edate,
        ));
        if (empty($transaction)){
            return [];
        }

        $user_list = array();

        foreach($transaction as $key => $value){
            $user_list[$value->user_to] = $value->user_to;
        }
        return $user_list;
    }
	function get_investor_user_list($sdate="",$edate=""){
        $user_list = $this->get_all_investor_user_list($sdate,$edate);
        return array_chunk($user_list, 150)[0] ?? [];
    }
    function get_investor_user_list_total_count($sdate="",$edate=""){
        $user_list = $this->get_all_investor_user_list($sdate,$edate);
        return count($user_list);
    }

    private function get_all_investor_user_list_without_exist($sdate = "", $edate = "", $exist_userid_list = []){
        if (empty($sdate) || empty($edate) || $edate < $sdate){
            return [];
        }
        if (!entering_date_range($edate)){
            return [];
        }

        $transaction = $this->CI->transaction_model->get_many_by(array(
            "source" => [1, 10],
            "bank_account_to like" => CATHAY_VIRTUAL_CODE . INVESTOR_VIRTUAL_CODE . "%",
            "entering_date <=" => $edate,
            "user_to not" => $exist_userid_list,
        ));
        if (empty($transaction)) {
            return [];
        }

        $user_list = [];
        foreach ($transaction as $key => $value) {
            $user_list[$value->user_to] = $value->user_to;
        }
        return $user_list;
    }

    /**
     * @param string $sdate
     * @param string $edate
     * @param array $exist_userid_list
     * @return array|bool
     */
    private function get_investor_user_list_without_exist($sdate = "", $edate = "", $exist_userid_list = []): array
    {
        $user_list = $this->get_all_investor_user_list_without_exist($sdate, $edate, $exist_userid_list);
        $user_list = array_chunk($user_list, 150)[0] ?? [];
        return $user_list;
    }
    private function get_investor_user_list_without_exist_total_count($sdate = "", $edate = "", $exist_userid_list = []): int
    {
        $user_list = $this->get_all_investor_user_list_without_exist($sdate, $edate, $exist_userid_list);
        return count($user_list);
    }

    private function get_all_borrower_user_list($sdate="",$edate=""){
        if (empty($sdate) || empty($edate) || $edate < $sdate){
            return [];
        }

        $this->CI->load->model('transaction/target_model');
        $date_range			= entering_date_range($edate);
        $edatetime			= $date_range?$date_range["edatetime"]:"";
        $date_range			= entering_date_range($sdate);
        $sdatetime			= $date_range?$date_range["sdatetime"]:"";
        if(!$edatetime){
            return [];
        }

        $target 		= $this->CI->target_model->get_many_by(array(
            "status" 		=> array(5,10),
            "loan_date <=" 	=> $edate,
        ));
        if (empty($target)){
            return [];
        }
        $user_list 			= [];
        foreach($target as $key => $value){
            $user_list[$value->user_id] = $value->user_id;
        }
        return $user_list;
    }

	function get_borrower_user_list($sdate="",$edate=""){
        $user_list = $this->get_all_borrower_user_list($sdate,$edate);
		return array_chunk($user_list, 150)[0] ?? [];
    }
    function get_borrower_user_list_total_count()
    {
        $user_list = $this->get_all_borrower_user_list();
        return count($user_list);
    }
    /**
     * @param $sdate
     * @param $edate
     * @param $exist_userid_list
     * @return array|false
     */
    function get_all_borrower_user_list_without_exist($sdate = "", $edate = "", $exist_userid_list = []): array
    {
        if (empty($sdate) || empty($edate) || $edate < $sdate) {
            return [];
        }
        if (!entering_date_range($edate)) {
            return [];
        }

        $this->CI->load->model('transaction/target_model');
        $target = $this->CI->target_model->get_many_by(array(
            "status" => [5, 10],
            "loan_date <=" => $edate,
            "user_id not" => $exist_userid_list,
        ));
        if (empty($target)) {
            return [];
        }
        $user_list = [];
        foreach ($target as $key => $value) {
            $user_list[$value->user_id] = $value->user_id;
        }
        return $user_list;
    }
    function get_borrower_user_list_without_exist($sdate = "", $edate = "", $exist_userid_list = []): array
    {
        $user_list = $this->get_all_borrower_user_list_without_exist($sdate, $edate, $exist_userid_list);
        return array_chunk($user_list, 150)[0] ?? [];
    }
    function get_borrower_user_list_without_exist_total_count($sdate = "", $edate = "", $exist_userid_list = []): int
    {
        $user_list = $this->get_all_borrower_user_list_without_exist($sdate, $edate, $exist_userid_list);
        return count($user_list);
    }

	function create_estatement_pdf($user_estatement= array()){
		if($user_estatement->id && $user_estatement->url=="" && !empty($user_estatement->content)){
			$url 		= "";
			$user_info 	= $this->CI->user_model->get($user_estatement->user_id);
			$edate 		= $user_estatement->edate;
			$user_id 	= $user_estatement->user_id;
			$html 		= $user_estatement->content;
			switch($user_estatement->type){
				case "estatement":
					if($user_estatement->investor){
						$url = $this->upload_pdf(
							$user_id,
							$html,
							$user_info->id_number,
							"投資人對帳單-".$edate,
							$user_id."-estatement-".$edate.".pdf",
							"investor/".$edate
						);
					}else{
						$url = $this->upload_pdf(
							$user_id,
							$html,
							$user_info->id_number,
							"借款人對帳單-".$edate,
							$user_id."-estatement-".$edate.".pdf",
							"borrower/".$edate
						);
					}
					break;
				case "estatementdetail":
					if($user_estatement->investor){
						$url = $this->upload_pdf(
							$user_id,
							$html,
							$user_info->id_number,
							"投資人對帳單明細-".$edate,
							$user_id."-estatementdetail-".$edate.".pdf",
							"investor/".$edate
						);
					}else{
						$url = $this->upload_pdf(
							$user_id,
							$html,
							$user_info->id_number,
							"借款人對帳單明細-".$edate,
							$user_id."-estatementdetail-".$edate.".pdf",
							"borrower/".$edate
						);
					}
					break;
                case "promote_code":
                    $url = $this->upload_pdf(
                        $user_id,
                        $html,
                        $user_info->id_number,
                        "推薦有賞對帳單",
                        $user_id."-promote-".$edate.".pdf",
                        "promote_code/".$edate
                    );
                    break;
				default:
					break;
			}

			if(!empty($url)){
				$this->CI->user_estatement_model->update($user_estatement->id,array("url" => $url));
				return $url;
			}
		}
		return false;
	}

	function send_estatement($estatement_id=0){
		if($estatement_id){
			$estatement = $this->CI->user_estatement_model->get($estatement_id);
			if($estatement && $estatement->type=='estatement'){
				$user_info = $this->CI->user_model->get($estatement->user_id);
				if($user_info && $user_info->name && $user_info->email){
                    $estatement_detail_url = '';

                    if ($estatement->investor == USER_INVESTOR) {
                        // 投資人對帳單才需要檢查明細
                        $estatement_detail = $this->CI->user_estatement_model->get_by(array(
                            "type"		=> "estatementdetail",
                            "user_id"	=> $estatement->user_id,
                            "investor"	=> $estatement->investor,
                            "sdate"		=> $estatement->sdate,
                            "edate"		=> $estatement->edate,
                            "url !="    => '',
                            "status"    => 0,
                        ));
                        if (!isset($estatement_detail) || !isset($estatement_detail->url) || !$estatement_detail->url) {
                            return false;
                        }
                        $estatement_detail_url = $estatement_detail->url;
                    }

					$y = date("Y",strtotime($estatement->sdate));
					$m = date("m",strtotime($estatement->sdate));
					$estatement_url  		= $estatement->url;
					$investor_status=$user_info->investor_status;
					$title = '【普匯金融科技交易對帳單】';
					$invest_report_desc = $estatement->investor == USER_INVESTOR ? '<br>普匯官網查看<a href="https://www.influxfin.com/invest-report">投資人報告書</a>' : '';
					$content = '親愛的 '.$user_info->name.' '.($user_info->sex=='M'?'先生':($user_info->sex=='F'?'小姐':'')).'您好：<br> 　　此為您'.$y.'年'.$m.'月帳戶交易對帳單，請您核對確認。<br>若有疑問請洽Line@客服，我們將竭誠為您服務。'.$invest_report_desc.'<br>普匯金融科技有限公司　敬上 <br><p style="color:red;font-size:14px;">＊附件綜合對帳單已設為加密信件，開啟密碼個人戶為身分證字號(英文字母請輸入大寫)，公司戶為統一編號。</p>';

                    $send_result = $this->CI->sendemail->email_file_estatement($user_info->email,$title,$content,$estatement_url,$estatement_detail_url,$investor_status);
                    if(!$send_result){
                        return false;
                    }

                    if (isset($estatement_detail)) {
                            $this->CI->user_estatement_model->update($estatement_detail->id, array("status" => 1));
                    }
					$this->CI->user_estatement_model->update($estatement_id,array("status"=>1));
					return true;
				}
            }
            else if ($estatement && $estatement->type == 'promote_code')
            {
                $user_info = $this->CI->user_model->get($estatement->user_id);
                if ($user_info && $user_info->name && $user_info->email)
                {
                    $this->CI->user_estatement_model->update($estatement_id, array("status" => 1));
                    $estatement_url = $estatement->url;
                    $investor_status = $estatement->investor;
                    $y = date("Y", strtotime($estatement->sdate));
                    $m = date("m", strtotime($estatement->sdate));
                    $title = '【推薦有賞對帳單】';
                    $content = '親愛的 ' . $user_info->name . ' ' . ($user_info->sex == 'M' ? '先生' : ($user_info->sex == 'F' ? '小姐' : '')) . '您好：<br> 　　茲寄送您' . $y . '年' . $m . '月推薦有賞對帳單，請您核對。<br>若有疑問請洽Line@粉絲團客服，我們將竭誠為您服務。<br>普匯金融科技有限公司　敬上 <br><p style="color:red;font-size:14px;">＊附件綜合對帳單已設為加密信件，開啟密碼個人戶為身分證字號(英文字母請輸入大寫)，公司戶為統一編號。</p>';
                    return $this->CI->sendemail->email_file_estatement($user_info->email, $title, $content, $estatement_url, "", $investor_status);
                }
            }
		}
		return false;
	}

	function script_create_estatement_pdf(){
		$list = $this->CI->user_estatement_model->limit(50)->get_many_by(array(
			"url"	=> "",
            "type !="=> "estatement_failed",
		));
		$count = 0;
		if($list){
			foreach($list as $key=>$value){
				$count++;
				$this->create_estatement_pdf($value);
			}
		}
		return $count;
	}

	function script_send_estatement_pdf(){
		$list = $this->CI->user_estatement_model->limit(50)->get_many_by(array(
			"url !="	=> "",
			"status"	=> 0,
            "type"=> "estatement"
		));
		$count = 0;
		if($list){
			foreach($list as $key=>$value){
				$count++;
				$this->send_estatement($value->id);
			}
		}
		return $count;
	}

	function script_create_estatement_content(){
		$day 				= 2;  // Create estatement content on the second day of every month.
		$count				= 0;
		$entering_date		= get_entering_date();
		$date  				= date("Y-m-j",strtotime($entering_date));
		$estatement_date 	= date("Y-m-").$day;
		if($date === $estatement_date){
            $first_day = 1;
			$sdate = date("Y-m-",strtotime($entering_date.' -1 month')).$first_day;
            $next_month_sdate = date("Y-m-d",strtotime($sdate.' +1 month'));
			$edate = date("Y-m-d",strtotime($next_month_sdate.' -1 day'));
			$exist = $this->CI->user_estatement_model->get_by([
				"sdate"	=> $sdate,
				"edate"	=> $edate,
                "type !="=> "estatement_failed"
			]);
			if(!$exist){
				$investor_list 	= $this->get_investor_user_list($sdate,$edate);
				if(!empty($investor_list)){
					foreach($investor_list as $key => $user_id){
						$rs = $this->get_estatement_investor($user_id,$sdate,$edate);
						if($rs) {
							$count++;
							$rs = $this->get_estatement_investor_detail($user_id, $sdate, $edate);
							$count += $rs ? 1 : 0;
						}
					}
				}
				$borrower_list 	= $this->get_borrower_user_list($sdate,$edate);
				if(!empty($borrower_list)){
					foreach($borrower_list as $key => $user_id){
						$rs = $this->get_estatement_borrower($user_id,$sdate,$edate);
						$count++;
					}
				}
			}
		}
		return $count;
	}

    function script_create_investor_estatement_content(): int
    {
        $day = 2;  // Create estatement content on the second day of every month.
        $entering_date = get_entering_date();
        $date = date("Y-m-j", strtotime($entering_date));
        $estatement_date = date("Y-m-") . $day;
        if ($date !== $estatement_date) return 0;
        $first_day = 1;
        $sdate = date("Y-m-", strtotime($entering_date . ' -1 month')) . $first_day;
        $next_month_sdate = date("Y-m-d", strtotime($sdate . ' +1 month'));
        $edate = date("Y-m-d", strtotime($next_month_sdate . ' -1 day'));
        $exist = $this->CI->user_estatement_model->query_table()
            ->select("user_id")
            ->where([
                "sdate" => $sdate,
                "edate" => $edate,
                "investor" => 1,
            ])
            ->where_in("type", ["estatement", "estatement_failed"])
            ->get()->result_array();
        $exist_userid_list = [];
        foreach ($exist as $key => $value) {
            $exist_userid_list[] = $value['user_id'];
        }

        if (count($exist_userid_list) > 0) {
            echo("有資料，排除掉已經處理過的");
            $investor_list = $this->get_investor_user_list_without_exist($sdate, $edate, $exist_userid_list);
        }
        else {
            echo("沒資料，全部都要處理");
            $investor_list = $this->get_investor_user_list($sdate, $edate);
        }
        if (count($investor_list) === 0) return 0;

        $count = 0;
        foreach ($investor_list as $key => $user_id) {
            $rs = $this->get_estatement_investor($user_id, $sdate, $edate);
            if ($rs) {
                $count++;
                $rs = $this->get_estatement_investor_detail($user_id, $sdate, $edate);
                if ($rs) $count++;
            }
        }
        return $count;
    }

    function script_create_borrower_estatement_content(): int
    {
        $day = 2;  // Create estatement content on the second day of every month.
        $entering_date = get_entering_date();
        $date = date("Y-m-j", strtotime($entering_date));
        $estatement_date = date("Y-m-") . $day;
        if ($date !== $estatement_date) return 0;
        $first_day = 1;
        $sdate = date("Y-m-", strtotime($entering_date . ' -1 month')) . $first_day;
        $next_month_sdate = date("Y-m-d", strtotime($sdate . ' +1 month'));
        $edate = date("Y-m-d", strtotime($next_month_sdate . ' -1 day'));
        $exist = $this->CI->user_estatement_model->query_table()->where([
            "sdate" => $sdate,
            "edate" => $edate,
            "investor" => 0,
        ])
            ->where_in("type", ["estatement", "estatement_failed"])
            ->select("user_id")
            ->get()->result_array();
        $exist_userid_list = [];
        foreach ($exist as $key => $value) {
            $exist_userid_list[] = $value['user_id'];
        }

        if (count($exist_userid_list) > 0) {
            echo("有資料，排除掉已經處理過的");
            $borrower_list = $this->get_borrower_user_list_without_exist($sdate, $edate, $exist_userid_list);
        }
        else {
            echo("沒資料，全部都要處理");
            $borrower_list = $this->get_borrower_user_list($sdate, $edate);
        }
        if (count($borrower_list) === 0) return 0;

        $count = 0;
        foreach ($borrower_list as $key => $user_id) {
            $rs = $this->get_estatement_borrower($user_id, $sdate, $edate);
            if ($rs) $count++;
        }
        return $count;
    }

    function script_re_create_estatement_content($user_id,$start,$end,$investor=0,$detail=0){
        $count = 0;
        $sdate = date("Y-m-d",strtotime($start));
        $edate = date("Y-m-d",strtotime($end));
        if($investor==1){
            if($detail==0) {
                $rs = $this->get_estatement_investor($user_id, $sdate, $edate);
                $count++;
            }
            else{
                $rs = $this->get_estatement_investor_detail($user_id,$sdate,$edate);
                $count++;
            }
        }
        else{
            $rs = $this->get_estatement_borrower($user_id,$sdate,$edate);
            $count++;
        }
        return $count;
    }

    private function get_target_month_dict(string $year = '', string $month = ''): array
    {
        if (intval($year) <= 0) {
            $year = date('Y');
        }
        if (!in_array(intval($month), range(1, 12))) {
            $month = date('m');
        }
        $entering_date = "$year-$month-1";
        $sdate = $entering_date;
        $next_month_sdate = date("Y-m-d", strtotime($sdate . ' +1 month'));
        $edate = date("Y-m-d", strtotime($next_month_sdate . ' -1 day'));

        return [
            "sdate" => $sdate,
            "edate" => $edate,
        ];
    }

    public function script_create_investor_estatement_content_count_status(string $year = '', string $month = ''): void
    {
        $date_dict = $this->get_target_month_dict($year, $month);
        $sdate = $date_dict['sdate'];
        $edate = $date_dict['edate'];

        $exist = $this->CI->user_estatement_model->query_table()
            ->select("user_id")
            ->where([
                "sdate" => $sdate,
                "edate" => $edate,
                "investor" => 1,
            ])
            ->where_in("type", ["estatement", "estatement_failed"])
            ->get()->result_array();

        $exist_userid_list = [];
        foreach ($exist as $key => $value) {
            $exist_userid_list[] = $value['user_id'];
        }
        $total_done = count($exist_userid_list);

        if ($total_done > 0) {
            $total_left = $this->get_investor_user_list_without_exist_total_count($sdate, $edate, $exist_userid_list);
        } else {
            $total_left = $this->get_investor_user_list_total_count($sdate, $edate);
        }

        echo json_encode([
            "sdate" => $sdate,
            "edate" => $edate,
            "done" => $total_done,
            "left" => $total_left
        ]);
    }

    public function script_create_borrower_estatement_content_count_status(string $year, string $month): void
    {
        $date_dict = $this->get_target_month_dict($year, $month);
        $sdate = $date_dict['sdate'];
        $edate = $date_dict['edate'];

        $exist = $this->CI->user_estatement_model->query_table()->where([
            "sdate" => $sdate,
            "edate" => $edate,
            "investor" => 0,
        ])
            ->where_in("type", ["estatement", "estatement_failed"])
            ->select("user_id")
            ->get()->result_array();

        $exist_userid_list = [];
        foreach ($exist as $key => $value) {
            $exist_userid_list[] = $value['user_id'];
        }

        $total_done = count($exist_userid_list);
        if ($total_done > 0){
            $total_left = $this->get_borrower_user_list_without_exist_total_count($sdate, $edate, $exist_userid_list);
        }
        else{
            $total_left = $this->get_borrower_user_list_total_count($sdate, $edate);
        }
        echo json_encode([
            "sdate" => $sdate,
            "edate" => $edate,
            "done" => $total_done,
            "left" => $total_left
        ]);
    }
}


