<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

use GuzzleHttp\Client;

class Account extends MY_Admin_Controller {

	protected $edit_method = array();

	public function __construct() {
		parent::__construct();
		$this->load->model('user/virtual_account_model');
		$this->load->model('transaction/virtual_passbook_model');
		$this->load->model('transaction/transaction_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('user/user_estatement_model');
        $this->load->library('Transfer_lib');

        $this->daily_report_client = new Client([
            'base_uri' => getenv('ENV_ERP_HOST'),
            'timeout' => 300,
        ]);
	}

	public function index(){
		$get 		= $this->input->get(NULL, TRUE);
		$date		= get_entering_date();
		$sdate 		= isset($get['sdate'])&&$get['sdate']?$get['sdate']:date("Y-m",strtotime($date)).'-01';
		$edate 		= isset($get['edate'])&&$get['edate']?$get['edate']:date("Y-m-t",strtotime($date));
		$source		= array(
			SOURCE_FEES,
			SOURCE_SUBLOAN_FEE,
			SOURCE_TRANSFER_FEE,
            SOURCE_FEES_B,
            SOURCE_TRANSFER_B,
            SOURCE_TRANSFER_FEES_B,
            SOURCE_BANK_WRONG_TX_B,
			SOURCE_PREPAYMENT_DAMAGE,
			SOURCE_VERIFY_FEE_R,
			SOURCE_REMITTANCE_FEE_R,
			SOURCE_DAMAGE,
			SOURCE_PREPAYMENT_ALLOWANCE,
			SOURCE_VERIFY_FEE,
			SOURCE_REMITTANCE_FEE,
		);
		$list 		= array();

		if($sdate=='all' || $edate=='all'){
			$where	= array(
				"source"		=> $source,
				"status <>"		=> 0
			);
		}else{
			$where	= array(
				"entering_date >="	=> $sdate,
				"entering_date <="	=> $edate,
				"source"			=> $source,
				"status <>"			=> 0
			);
		}

		$page_data 	= array(
			"type" 	=> "list",
			"sdate"	=> $sdate,
			"edate"	=> $edate
		);
		$data 		= $this->transaction_model->order_by("source","ASC")->get_many_by($where);
		$return     = [SOURCE_VERIFY_FEE_R,SOURCE_REMITTANCE_FEE_R];
		if(!empty($data)){
			foreach($data as $key =>$value){
				$value->bank_account_to = trim($value->bank_account_to);
				if(!isset($list[$value->source])){
					$list[$value->source] = array("debit"=>0,"credit"=>0);
				}

                if($value->bank_account_from==PLATFORM_VIRTUAL_ACCOUNT||in_array($value->source,$return)){
                    if(in_array($value->source,$return)){
                        $list[$value->source]['credit'] -= $value->amount;
                    }
                    else{
                        $list[$value->source]['credit'] += $value->amount;
                    }
                }

                if($value->bank_account_to==PLATFORM_VIRTUAL_ACCOUNT&&!in_array($value->source,$return)){
                    $list[$value->source]['debit'] += $value->amount;
                }
			}
		}
		$page_data['list'] 					= $list;
		$page_data['sum'] 					= array("debit"=>0,"credit"=>0);
		$page_data['transaction_source'] 	= $this->config->item('internal_transaction_source');
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/account_report',$page_data);
		$this->load->view('admin/_footer');
	}

	function daily_report(){
		$get 		= $this->input->get(NULL, TRUE);
		$display 	= isset($get['display'])&&$get['display']?$get['display']:"";
		$sdate 		= isset($get['sdate'])&&$get['sdate']?$get['sdate']:get_entering_date();
		$edate 		= isset($get['edate'])&&$get['edate']?$get['edate']:get_entering_date();
        $targetId 	= isset($get['target'])&&$get['target']?$get['target']:false;
		$source		= array(
			SOURCE_AR_FEES,
			SOURCE_AR_PRINCIPAL,
			SOURCE_AR_INTEREST,
			SOURCE_AR_DAMAGE,
			SOURCE_AR_DELAYINTEREST,
            SOURCE_AR_LAW_FEE,
			SOURCE_VERIFY_FEE,
			SOURCE_VERIFY_FEE_R,
			SOURCE_REMITTANCE_FEE,
			SOURCE_REMITTANCE_FEE_R
		);
		$list 		= array();

		if($sdate=='all' || $edate=='all'){
			$where	= array(
				"source not"	=> $source,
				"status <>"		=> 0
			);
		}else{
			$where	= array(
				"entering_date >="	=> $sdate,
				"entering_date <="	=> $edate,
				"source not"		=> $source,
				"status <>"			=> 0
			);
		}

        $targetId ? $where['target_id'] = $targetId : '';

		$page_data 	= array(
			"type" 	=> "list",
			"sdate"	=> $sdate,
			"edate"	=> $edate
		);
		$data 		= $this->transaction_model->order_by("entering_date","ASC")->get_many_by($where);
		$user_id	= array();
		$target_id	= array();
		$damage_target	= array();
		$lending_target	= array();
		if(!empty($data)){
			foreach($data as $key =>$value){
				$user_id[] = $value->user_to;
				$user_id[] = $value->user_from;
				if($value->target_id){
					$target_id[] = $value->target_id;
				}


				if(is_virtual_account($value->bank_account_from)){

					$data[$key]->v_bank_account_from = $value->bank_account_from;
					$data[$key]->v_amount_from 		= $value->amount;
					$data[$key]->bank_account_from 	= "";
					$data[$key]->amount_from 		= "";
				}else{
					$data[$key]->bank_account_from 	= $value->bank_account_from;
					$data[$key]->amount_from 		= $value->amount;
					$data[$key]->v_bank_account_from = "";
					$data[$key]->v_amount_from 		= "";
				}

				if(is_virtual_account($value->bank_account_to)){

					$data[$key]->v_bank_account_to 	= $value->bank_account_to;
					$data[$key]->v_amount_to 		= $value->amount;
					$data[$key]->bank_account_to 	= "";
					$data[$key]->amount_to 			= "";
				}else{
					$data[$key]->bank_account_to 	= $value->bank_account_to;
					$data[$key]->amount_to 			= $value->amount;
					$data[$key]->v_bank_account_to 	= "";
					$data[$key]->v_amount_to 		= "";
				}
			}

			$user_id 	= array_unique($user_id);
			$user_list = $this->user_model->get_many($user_id);
			$user_name = array();
			foreach($user_list as $key => $value){
				$user_name[$value->id] = $value->name;
			}

			$target_no 		= array();
			if($target_id){
				$target_id 		= array_unique($target_id);
				$target_list 	= $this->target_model->get_many($target_id);
				foreach($target_list as $key => $value){
					$target_no[$value->id] = $value->target_no;
				}
			}
            $id_use  = [];
			foreach($data as $key =>$value){
				if($value->target_id){
					$value->target_no = $target_no[$value->target_id];
				}else{
					$value->target_no = "";
				}

                // 1, 2
				if($value->source == SOURCE_RECHARGE || $value->source == SOURCE_WITHDRAW){
					$switch  		= array(SOURCE_RECHARGE=>'recharge',SOURCE_WITHDRAW=>'withdraw');
					$source_type 	= $switch[$value->source];
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
                        "target_id"				=> $value->target_id,
						"source_type"			=> $source_type,
						"user_from"				=> isset($user_name[$value->user_from])?$user_name[$value->user_from]:0,
						"bank_account_from"		=> $value->bank_account_from,
						"amount_from"			=> $value->amount_from,
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $value->v_amount_from,
						"user_to"				=> isset($user_name[$value->user_to])?$user_name[$value->user_to]:0,
						"bank_account_to"		=> $value->bank_account_to,
						"amount_to"				=> $value->amount_to,
						"v_bank_account_to"		=> $value->v_bank_account_to,
						"v_amount_to"			=> $value->v_amount_to,
						"created_at"			=> $value->created_at,
					);
				}
                if($value->source == SOURCE_PROMOTE_REWARD){
                    $list[] = array(
                        "entering_date"			=> $value->entering_date,
                        "target_no"				=> '',
                        "target_id"				=> '',
                        "source_type"			=> 'promote',
                        "user_from"				=> "平台",
                        "bank_account_from"		=> $value->bank_account_from,
                        "amount_from"			=> $value->amount_from,
                        "v_bank_account_from"	=> $value->v_bank_account_from,
                        "v_amount_from"			=> $value->v_amount_from,
                        "user_to"				=> $user_name[$value->user_to] ?? 0,
                        "bank_account_to"		=> $value->bank_account_to,
                        "amount_to"				=> $value->amount_to,
                        "v_bank_account_to"		=> $value->v_bank_account_to,
                        "v_amount_to"			=> $value->v_amount_to,
                        "created_at"			=> $value->created_at,
                    );
                }

                // 50
				if($value->source == SOURCE_TRANSFER_B) {
                    $item_no           = $value->target_no;
                    $item_platform_fee = 0;
                    $sub_list 	= array();
                    $sub_list[] = array(
                        "user_to"				=> $user_name[$value->user_to],
                        "bank_account_to"		=> $value->bank_account_to,
                        "amount_to"				=> $value->amount_to,
                        "v_bank_account_to"		=> $value->v_bank_account_to,
                        "v_amount_to"			=> $value->v_amount_to,
                        "platform_fee"			=> $item_platform_fee,
                    );
                    $list[] = array(
                        "entering_date"			=> $value->entering_date,
                        "target_no"				=> $item_no,
                        "target_id"				=> $value->target_id,
                        "source_type"			=> 'transfer_b',
                        "user_from"				=> isset($user_name[$value->user_from])?$user_name[$value->user_from]:'',
                        "bank_account_from"		=> $value->bank_account_from,
                        "amount_from"			=> $value->amount_from,
                        "v_bank_account_from"	=> $value->v_bank_account_from,
                        "v_amount_from"			=> $value->v_amount_from,
                        "sub_list"				=> $sub_list,
                        "created_at"			=> $value->created_at,
                    );
                }
                // 53
                if($value->source == SOURCE_BANK_WRONG_TX_B) {
                    $item_no           = $value->target_no;
                    $item_platform_fee = 0;
                    $sub_list 	= array();
                    $sub_list[] = array(
                        "user_to"				=> $user_name[$value->user_to],
                        "bank_account_to"		=> $value->bank_account_to,
                        "amount_to"				=> $value->amount_to,
                        "v_bank_account_to"		=> $value->v_bank_account_to,
                        "v_amount_to"			=> $value->v_amount_to,
                        "platform_fee"			=> $item_platform_fee,
                    );
                    $list[] = array(
                        "entering_date"			=> $value->entering_date,
                        "target_no"				=> $item_no,
                        "target_id"				=> $value->target_id,
                        "source_type"			=> 'bank_wrong_tx',
                        "user_from"				=> isset($user_name[$value->user_from])?$user_name[$value->user_from]:'',
                        "bank_account_from"		=> $value->bank_account_from,
                        "amount_from"			=> $value->amount_from,
                        "v_bank_account_from"	=> $value->v_bank_account_from,
                        "v_amount_from"			=> $value->v_amount_from,
                        "sub_list"				=> $sub_list,
                        "created_at"			=> $value->created_at,
                    );
                }
                // 10
				if($value->source == SOURCE_TRANSFER){
				    $item_no           = $value->target_no;
				    $item_platform_fee = 0;
                    $combinations_info = $this->transfer_lib->check_combination($value->target_id,$value->investment_id);
                    if($combinations_info){
                        $item_no            = $combinations_info[0];
                        $item_platform_fee  = $combinations_info[2];
                        $id_use[] = $item_no;
                    }
					$sub_list = array();
					foreach($data as $k =>$v){
                        if($v->source==SOURCE_TRANSFER_FEE && $v->investment_id==$value->investment_id && $v->user_from==$value->user_to ||$v->source == SOURCE_FEES && $v->investment_id==$value->investment_id){
                            $source_fee = ($v->source == SOURCE_FEES&&$v->amount==$value->amount?$v->amount:(!$combinations_info&&$v->source != SOURCE_FEES?$v->amount:''));
                            if(count($sub_list)==0){
                                $sub_list[] = array(
                                    "user_to"				=> isset($user_name[$value->user_to])?$user_name[$value->user_to]:'',
                                    "bank_account_to"		=> $value->bank_account_to,
                                    "amount_to"				=> $value->amount_to,
                                    "v_bank_account_to"		=> $value->v_bank_account_to,
                                    "v_amount_to"			=> ($combinations_info?$value->v_amount_to - $v->amount:($v->investment_id==0?$value->amount:$value->v_amount_to - $v->amount)),
                                    "platform_fee"			=> $combinations_info?$item_platform_fee:$source_fee,
                                );
                            }
						}
					}

					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $item_no,
                        "target_id"				=> $value->target_id,
						"source_type"			=> 'transfer',
						"user_from"				=> $user_name[$value->user_from],
						"bank_account_from"		=> $value->bank_account_from,
						"amount_from"			=> $value->amount_from,
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $value->v_amount_from,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
                // 8
				if($value->source == SOURCE_PREPAYMENT_DAMAGE){
					$damage_target[] = $value->target_id.'_'.$value->instalment_no.'_'.$value->entering_date;
					$sub_list 		= array();
					$user_to_info 	= array();
					$damages 	= 0;
					$amount 	= 0;
					foreach($data as $k =>$v){
						if($v->entering_date==$value->entering_date && $v->target_id==$value->target_id && $v->instalment_no==$value->instalment_no){
							if($v->investment_id && !isset($user_to_info[$v->investment_id])){
								$user_to_info[$v->investment_id] = array(
									"principal"		=> 0,
									"interest"		=> 0,
									"platform_fee"	=> 0,
									"allowance"		=> 0,
								);
							}
							switch($v->source){
								case SOURCE_PRINCIPAL:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["principal"]			+= $v->amount;
									$user_to_info[$v->investment_id]["user_to"]				= $user_name[$v->user_to];
									$user_to_info[$v->investment_id]["v_bank_account_to"]	= $v->v_bank_account_to;
									break;
								case SOURCE_INTEREST:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["interest"]	+= $v->amount;
									break;
								case SOURCE_PREPAYMENT_DAMAGE:
									$damages	+= $v->amount;
									break;
								case SOURCE_FEES:
									$user_to_info[$v->investment_id]["platform_fee"]+= $v->amount;
									break;
								case SOURCE_PREPAYMENT_ALLOWANCE:
									$user_to_info[$v->investment_id]["allowance"]	+= $v->amount;
									break;
								default:
									break;
							}
						}
					}
					if($user_to_info){
						foreach($user_to_info as $k =>$v){
							$sub_list[] = array(
								"user_to"				=> $v['user_to'],
								"v_bank_account_to"		=> $v['v_bank_account_to'],
								"v_amount_to"			=> $v['principal'] + $v['interest'] + $v['allowance'] - $v['platform_fee'],
								"principal"				=> $v['principal'],
								"interest"				=> $v['interest'],
								"platform_fee"			=> $v['platform_fee'],
								"allowance"				=> $v['allowance'],
							);
						}
					}
					$amount += $damages;
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
                        "target_id"				=> $value->target_id,
						"source_type"			=> 'prepayment',
						"user_from"				=> $user_name[$value->user_from],
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $amount,
						"damages"				=> $damages,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
                // 92
				if($value->source == SOURCE_DAMAGE){
					$damage_target[] 	= $value->target_id.'_'.$value->instalment_no.'_'.$value->entering_date;
					$sub_list 			= array();
					$user_to_info 		= array();
					$damages 	= 0;
					$amount 	= 0;
					foreach($data as $k =>$v){
						if($v->entering_date==$value->entering_date && $v->target_id==$value->target_id && $v->instalment_no==$value->instalment_no){
							if($v->investment_id && !isset($user_to_info[$v->investment_id])){
								$user_to_info[$v->investment_id] = array(
									"principal"		=> 0,
									"interest"		=> 0,
									"platform_fee"	=> 0,
									"allowance"		=> 0,
									"delay_interest"=> 0,
								);
							}
							switch($v->source){
								case SOURCE_PRINCIPAL:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["principal"]			+= $v->amount;
									$user_to_info[$v->investment_id]["user_to"]				= $user_name[$v->user_to];
									$user_to_info[$v->investment_id]["v_bank_account_to"]	= $v->v_bank_account_to;
									break;
								case SOURCE_INTEREST:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["interest"]	+= $v->amount;
									break;
								case SOURCE_DAMAGE:
									$damages	+= $v->amount;
									break;
								case SOURCE_FEES:
								    if(!isset($user_to_info[$v->investment_id]["platform_fee"])){
                                        $user_to_info[$v->investment_id]["platform_fee"] = $v->amount;
                                    }else{
                                        $user_to_info[$v->investment_id]["platform_fee"]+= $v->amount;
                                    }
                                    break;
								case SOURCE_DELAYINTEREST:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["delay_interest"]	+= $v->amount;
									break;
								default:
									break;
							}
						}
					}
					if($user_to_info){
						foreach($user_to_info as $k =>$v){
							$sub_list[] = array(
								"user_to"				=> isset($v['user_to'])?$v['user_to']:'',
								"v_bank_account_to"		=> isset($v['v_bank_account_to'])?$v['v_bank_account_to']:'',
								"v_amount_to"			=> (isset($v['principal'])?$v['principal']:0) + (isset($v['interest'])?$v['interest']:0) + (isset($v['delay_interest'])?$v['delay_interest']:0) - (isset($v['platform_fee'])?$v['platform_fee']:0),
								"principal"				=> isset($v['principal'])?$v['principal']:0,
								"interest"				=> isset($v['interest'])?$v['interest']:0,
								"platform_fee"			=> $v['platform_fee'],
								"delay_interest"		=> isset($v['delay_interest'])?$v['delay_interest']:0,
							);
						}
					}
					$amount += $damages;
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
                        "target_id"				=> $value->target_id,
						"source_type"			=> 'charge_delay',
						"user_from"				=> $user_name[$value->user_from],
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $amount,
						"damages"				=> $damages,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
                // 3
				if($value->source == SOURCE_LENDING && !in_array($value->target_id,$lending_target)){
					$lending_target[] = $value->target_id;
					$sub_list 		= array();
					$user_to_info 	= array();
					$platform_fee 	= 0;
					$amount 		= 0;
					foreach($data as $k =>$v){
						if($v->entering_date==$value->entering_date && $v->target_id==$value->target_id && $v->instalment_no==$value->instalment_no){
							switch($v->source){
								// 3
                                case SOURCE_LENDING:
									$sub_list[] = array(
										"user_from"				=> $user_name[$v->user_from],
										"v_bank_account_from"	=> $v->v_bank_account_from,
										"v_amount_from"			=> $v->amount,
									);
									$amount += $v->amount;
									break;
								// 4
                                case SOURCE_FEES:
									$platform_fee = $v->amount;
									break;
								default:
									break;
							}
						}
					}

					$amount -= $platform_fee;
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
                        "target_id"				=> $value->target_id,
						"source_type"			=> "lending",
						"user_to"				=> $user_name[$value->user_to],
						"bank_account_to"		=> $value->bank_account_to,
						"amount_to"				=> $amount,
						"platform_fee"			=> $platform_fee,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}

                // 4, 特殊情境, 退還平台服務費, issue#816
                if($value->source == SOURCE_FEES && $value->v_bank_account_from == PLATFORM_VIRTUAL_ACCOUNT){
                    $source_type    = 'platform_fee';
                    $list[] = array(
                        "entering_date"         => $value->entering_date,
                        "target_no"             => $value->target_no,
                        "target_id"             => $value->target_id,
                        "source_type"           => 'platform_wrong_tx',
                        "user_from"             => '平台',
                        "bank_account_from"     => $value->bank_account_from,
                        "amount_from"           => $value->amount_from,
                        "v_bank_account_from"   => $value->v_bank_account_from,
                        "v_amount_from"         => $value->v_amount_from,
                        "user_to"               => isset($user_name[$value->user_to])?$user_name[$value->user_to]:0,
                        "bank_account_to"       => $value->bank_account_to,
                        "amount_to"             => $value->amount_to,
                        "v_bank_account_to"     => $value->v_bank_account_to,
                        "v_amount_to"           => $value->v_amount_to,
                        "created_at"            => $value->created_at,
                        // 必為負數
                        "platform_fee"          => "-" . $value->amount
                    );
                }

                // 85, 特殊情境, 不明原因退款, issue#861
                if($value->source == SOURCE_UNKNOWN_R){
                    $source_type    = 'platform_fee';
                    $list[] = array(
                        "entering_date"         => $value->entering_date,
                        "target_no"             => $value->target_no,
                        "target_id"             => $value->target_id,
                        "source_type"           => 'unknown_refund',
                        "user_from"             => '平台',
                        "bank_account_from"     => $value->bank_account_from,
                        "amount_from"           => $value->amount_from,
                        "v_bank_account_from"   => $value->v_bank_account_from,
                        "v_amount_from"         => $value->v_amount_from,
                        "user_to"               => isset($user_name[$value->user_to])?$user_name[$value->user_to]:0,
                        "bank_account_to"       => $value->bank_account_to,
                        "amount_to"             => $value->amount_to,
                        "v_bank_account_to"     => $value->v_bank_account_to,
                        "v_amount_to"           => $value->v_amount_to,
                        "created_at"            => $value->created_at,
                        "platform_fee"          => 0
                    );
                }

                // 32, 法催執行費, issue#1016
                if($value->source == SOURCE_LAW_FEE){
                    $source_type    = 'platform_law_fee';
                    $list[] = array(
                        "entering_date"         => $value->entering_date,
                        "target_no"             => $value->target_no,
                        "target_id"             => $value->target_id,
                        "source_type"           => 'platform_law_fee',
                        "user_from"             => isset($user_name[$value->user_from])?$user_name[$value->user_from]:'',
                        "bank_account_from"     => $value->bank_account_from,
                        "amount_from"           => $value->amount_from,
                        "v_bank_account_from"   => $value->v_bank_account_from,
                        "v_amount_from"         => $value->v_amount_from,
                        "user_to"               => '平台',
                        "bank_account_to"       => $value->bank_account_to,
                        "amount_to"             => $value->amount_to,
                        "v_bank_account_to"     => $value->v_bank_account_to,
                        "v_amount_to"           => $value->v_amount_to,
                        "created_at"            => $value->created_at,
                        "platform_fee"          => 0
                    );
                }
			}

			foreach($data as $key => $value){
                // 12
				if($value->source == SOURCE_PRINCIPAL && !in_array($value->target_id.'_'.$value->instalment_no.'_'.$value->entering_date,$damage_target)){
					$damage_target[] 	= $value->target_id.'_'.$value->instalment_no.'_'.$value->entering_date;
					$value->target_no 	= $target_no[$value->target_id];
					$sub_list 			= array();
					$user_to_info 		= array();
					$amount 			= 0;
					foreach($data as $k =>$v){
						if($v->entering_date==$value->entering_date && $v->target_id==$value->target_id && $v->instalment_no==$value->instalment_no){
							if($v->investment_id && !isset($user_to_info[$v->investment_id])){
								$user_to_info[$v->investment_id] = array(
									"principal"		=> 0,
									"interest"		=> 0,
									"platform_fee"	=> 0,
									"allowance"		=> 0,
								);
							}
							switch($v->source){
								// 12
                                case SOURCE_PRINCIPAL:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["principal"]			+= $v->amount;
									$user_to_info[$v->investment_id]["user_to"]				= $v->user_to;
									$user_to_info[$v->investment_id]["v_bank_account_to"]	= $v->v_bank_account_to;
									break;
								// 14
                                case SOURCE_INTEREST:
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["interest"]	+= $v->amount;
									break;
								// 4
                                case SOURCE_FEES:
									$user_to_info[$v->investment_id]["platform_fee"]+= $v->amount;
									break;
								default:
									break;
							}
						}
					}
					if($user_to_info){
						foreach($user_to_info as $k =>$v){
							$sub_list[] = array(
								"user_to"				=> $user_name[$v['user_to']],
								"v_bank_account_to"		=> $v['v_bank_account_to'],
								"v_amount_to"			=> $v['principal'] + $v['interest'] - $v['platform_fee'],
								"principal"				=> $v['principal'],
								"interest"				=> $v['interest'],
								"platform_fee"			=> $v['platform_fee'],
							);
						}
					}

					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
						"target_id"				=> $value->target_id,
						"source_type"			=> 'charge_normal',
						"user_from"				=> $user_name[$value->user_from],
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $amount,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
			}

			$num = count($list);
			for($i = 0 ; $i < $num ; $i++){
				for ($j=$i+1;$j<$num;$j++) {
					$a = $list[$i]["created_at"];
					$b = $list[$j]["created_at"];
					if( $a > $b ){
						$tmp      = $list[$i];
						$list[$i] = $list[$j];
						$list[$j] = $tmp;
					}
				}
			}

			$num = count($list);
			for($i = 0 ; $i < $num ; $i++){
				for ($j=$i+1;$j<$num;$j++) {
					$a = $list[$i]["entering_date"];
					$b = $list[$j]["entering_date"];
					if( $a > $b ){
						$tmp      = $list[$i];
						$list[$i] = $list[$j];
						$list[$j] = $tmp;
					}
				}
			}
		}

		$page_data['list'] 					= $list;
		$page_data['transaction_source'] 	= $this->config->item('transaction_source');
		$page_data['transaction_type_name'] = $this->config->item('transaction_type_name');
		if($display=="pdf"){
			$html = $this->load->view('estatement/daily_report',$page_data,TRUE);
			$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
			//$pdf->SetProtection($permissions , $password , PDF_OWNER_PASSWORD, 0, null);
			$pdf->SetCreator(GMAIL_SMTP_NAME);
			$pdf->SetAuthor(GMAIL_SMTP_NAME);
			$pdf->SetTitle('日報表-'.date("Y-m-d"));
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->setFontSubsetting(true);
			$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->SetFont('msungstdlight', '', 8);
			$pdf->AddPage('L', 'A3');
			$pdf->writeHTML($html, 1, 0, true, true, '');
			$files = $pdf->Output("daily report_".date("Y-m-d").'.pdf',"D");
		}
        elseif (in_array($display, ['excel', 'excel2']))
        { // 匯出Excel
            $this->load->library('Spreadsheet_lib');
            $spreadsheet = $this->_draw_daily_report_excel($list, $page_data, $display);

            $start_date = '';
            $end_date = '';
            if ( ! empty($sdate) && ($start_timestamp = strtotime($sdate)) !== FALSE)
            {
                $start_date = date("Ymd", $start_timestamp);
            }
            if ( ! empty($edate) && ($end_timestamp = strtotime($edate)) !== FALSE)
            {
                $end_date = date("Ymd", $end_timestamp);
            }
            else if ( ! isset($start_timestamp) || $start_timestamp === FALSE)
            {
                $end_date = date("Ymd");
            }

            $this->spreadsheet_lib->download("虛擬帳戶交易明細({$start_date}-{$end_date}).xlsx", $spreadsheet);
        }
        else{
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/account_daily_report',$page_data);
			$this->load->view('admin/_footer');
		}
	}

    /**
     * 日報表 資料
     * 
     * @created_at                   2023-03-17
     * @created_by                   Howard
     */
    public function daily_report_sheet(){
        $get        = $this->input->get(NULL, TRUE);
        $display    = isset($get['display'])&&$get['display']?$get['display']:"";
        $sdate      = isset($get['sdate'])&&$get['sdate']?$get['sdate']:get_entering_date();
        $edate      = isset($get['edate'])&&$get['edate']?$get['edate']:get_entering_date();
        $data = $this->daily_report_client->request('GET', 'daily_report', [
                'query' => array(
                'sdate' => $sdate,
                'edate' => $edate
            )
        ])->getBody()->getContents();

        $page_data 	= array(
            'type' 	=> 'list',
            'sdate'	=> $sdate,
            'edate'	=> $edate
        );
        $page_data['list'] = json_decode($data, true);

        if($display=="pdf"){
            $html = $this->load->view('estatement/daily_report',$page_data,TRUE);
            $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
            // $pdf->SetProtection($permissions , $password , PDF_OWNER_PASSWORD, 0, null);
            $pdf->SetCreator(GMAIL_SMTP_NAME);
            $pdf->SetAuthor(GMAIL_SMTP_NAME);
            $pdf->SetTitle('日報表-'.date("Y-m-d"));
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->setFontSubsetting(true);
            $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->SetFont('msungstdlight', '', 8);
            $pdf->AddPage('L', 'A3');
            $pdf->writeHTML($html, 1, 0, true, true, '');
            $files = $pdf->Output("daily report_".date("Y-m-d").'.pdf',"D");
        }
        else{
            $this->load->view('admin/_header');
            $this->load->view('admin/_title',$this->menu);
            $this->load->view('admin/account_daily_report_new',$page_data);
            $this->load->view('admin/_footer');
        }
    }

    /**
     * 日報表 excel
     * 
     * @created_at                   2023-03-16
     * @created_by                   Howard
     */
    public function daily_report_export(){
        // get file from guzzle daily_report/excel
        $res = $this->daily_report_client->request('GET', 'daily_report/excel', [
            'query' => $this->input->get()
        ]);
        $des = $res->getHeader('content-disposition')[0];
        $data = $res->getBody()->getContents();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        setcookie('fileDownload', 'true', 0, '/');
        echo $data;
        die();
    }

    /**
     * 匯出「虛擬帳戶交易明細表」-excel格式
     * @param $list
     * @param $page_data
     * @param $display
     * @return mixed
     */
    private function _draw_daily_report_excel($list, $page_data, $display)
    {
        $title_rows = [
            'entering_date' => ['name' => '交易日期', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'target_no' => ['name' => '案件號碼', 'width' => 22, 'rowspan' => 2, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'source_type' => ['name' => '交易種類', 'width' => 16, 'rowspan' => 2, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'withdraw' => ['name' => '提出', 'colspan' => 5, 'merge' => [
                'user_from' => ['name' => '戶名', 'width' => 15, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'v_bank_account_from' => ['name' => '虛擬帳號', 'width' => 15, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'v_amount_from' => ['name' => '金額', 'width' => 12, 'alignment' => ['h' => 'right', 'v' => 'center']],
                'bank_account_from' => ['name' => '銀行帳戶', 'width' => 20, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'amount_from' => ['name' => '金額', 'width' => 12, 'alignment' => ['h' => 'right', 'v' => 'center']],
            ]],
            'deposit' => ['name' => '存入', 'colspan' => 5, 'merge' => [
                'user_to' => ['name' => '戶名', 'width' => 15, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'v_bank_account_to' => ['name' => '虛擬帳號', 'width' => 15, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'v_amount_to' => ['name' => '金額', 'width' => 12, 'alignment' => ['h' => 'right', 'v' => 'center']],
                'bank_account_to' => ['name' => '銀行帳戶', 'width' => 20, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING, 'alignment' => ['h' => 'center', 'v' => 'center']],
                'amount_to' => ['name' => '金額', 'width' => 12, 'alignment' => ['h' => 'right', 'v' => 'center']],
            ]],
            'principal' => ['name' => '本金金額', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
            'interest' => ['name' => '利息金額', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
            'platform_fee' => ['name' => '平台服務費', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
            'damages' => ['name' => '違約金', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
            'allowance' => ['name' => '提還補貼金', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
            'delay_interest' => ['name' => '延滯息', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
            'else' => ['name' => '債權差額', 'width' => 12, 'rowspan' => 2, 'alignment' => ['h' => 'right', 'v' => 'center']],
        ];
        $sum = [
            'entering_date' => '合計',
            'v_amount_from' => 0,
            'amount_from' => 0,
            'v_amount_to' => 0,
            'amount_to' => 0,
            'principal' => 0,
            'interest' => 0,
            'platform_fee' => 0,
            'damages' => 0,
            'allowance' => 0,
            'delay_interest' => 0,
            'else' => 0,
        ];
        $data_rows_index = 0;
        foreach ($list as $value)
        {
            $data_rows[$data_rows_index] = [
                'entering_date' => $value['entering_date'] ?? '',
                'target_no' => $value['target_no'] ?? '',
                'source_type' => $page_data['transaction_type_name'][$value['source_type'] ?? ''] ?? '',
                'user_from' => $value['user_from'] ?? '',
                'v_bank_account_from' => $value['v_bank_account_from'] ?? '',
                'v_amount_from' => ! empty($value['v_amount_from']) && is_numeric($value['v_amount_from']) ? (int) $value['v_amount_from'] : '',
                'bank_account_from' => $value['bank_account_from'] ?? '',
                'amount_from' => ! empty($value['amount_from']) && is_numeric($value['amount_from']) ? (int) $value['amount_from'] : '',
                'user_to' => $value['user_to'] ?? '',
                'v_bank_account_to' => $value['v_bank_account_to'] ?? '',
                'v_amount_to' => ! empty($value['v_amount_to']) && is_numeric($value['v_amount_to']) ? (int) $value['v_amount_to'] : '',
                'bank_account_to' => $value['bank_account_to'] ?? '',
                'amount_to' => ! empty($value['amount_to']) && is_numeric($value['amount_to']) ? (int) $value['amount_to'] : '',
                'principal' => ! empty($value['principal']) && is_numeric($value['principal']) ? (int) $value['principal'] : '',
                'interest' => ! empty($value['interest']) && is_numeric($value['interest']) ? (int) $value['interest'] : '',
                'platform_fee' => ! empty($value['platform_fee']) && is_numeric($value['platform_fee']) ? (int) $value['platform_fee'] : '',
                'damages' => ! empty($value['damages']) && is_numeric($value['damages']) ? (int) $value['damages'] : '',
                'allowance' => ! empty($value['allowance']) && is_numeric($value['allowance']) ? (int) $value['allowance'] : '',
                'delay_interest' => ! empty($value['delay_interest']) && is_numeric($value['delay_interest']) ? (int) $value['delay_interest'] : '',
                'else' => ! empty($value['else']) && is_numeric($value['else']) ? (int) $value['else'] : '',
            ];

            $sum = $this->_daily_report_sum_amount_extracted($data_rows[$data_rows_index], $sum);

            if (empty($value['sub_list']))
            {
                $data_rows_index++;
                continue;
            }

            if ($display == 'excel')
            {
                $sub_list_count = count($value['sub_list']) + 1;
                $data_rows[$data_rows_index]['entering_date'] = ['value' => $data_rows[$data_rows_index]['entering_date'], 'rowspan' => $sub_list_count];
                $data_rows[$data_rows_index]['target_no'] = ['value' => $data_rows[$data_rows_index]['target_no'], 'rowspan' => $sub_list_count];
                $data_rows[$data_rows_index]['source_type'] = ['value' => $data_rows[$data_rows_index]['source_type'], 'rowspan' => $sub_list_count];
                foreach ($value['sub_list'] as $sub_list_value)
                {
                    $data_rows_index++;
                    $data_rows[$data_rows_index] = [
                        'user_from' => $sub_list_value['user_from'] ?? '',
                        'v_bank_account_from' => $sub_list_value['v_bank_account_from'] ?? '',
                        'v_amount_from' => ! empty($sub_list_value['v_amount_from']) && is_numeric($sub_list_value['v_amount_from']) ? (int) $sub_list_value['v_amount_from'] : '',
                        'bank_account_from' => $sub_list_value['bank_account_from'] ?? '',
                        'amount_from' => ! empty($sub_list_value['amount_from']) && is_numeric($sub_list_value['amount_from']) ? (int) $sub_list_value['amount_from'] : '',
                        'user_to' => $sub_list_value['user_to'] ?? '',
                        'v_bank_account_to' => $sub_list_value['v_bank_account_to'] ?? '',
                        'v_amount_to' => ! empty($sub_list_value['v_amount_to']) && is_numeric($sub_list_value['v_amount_to']) ? (int) $sub_list_value['v_amount_to'] : '',
                        'bank_account_to' => $sub_list_value['bank_account_to'] ?? '',
                        'amount_to' => ! empty($sub_list_value['amount_to']) && is_numeric($sub_list_value['amount_to']) ? (int) $sub_list_value['amount_to'] : '',
                        'principal' => ! empty($sub_list_value['principal']) && is_numeric($sub_list_value['principal']) ? (int) $sub_list_value['principal'] : '',
                        'interest' => ! empty($sub_list_value['interest']) && is_numeric($sub_list_value['interest']) ? (int) $sub_list_value['interest'] : '',
                        'platform_fee' => ! empty($sub_list_value['platform_fee']) && is_numeric($sub_list_value['platform_fee']) ? (int) $sub_list_value['platform_fee'] : '',
                        'damages' => ! empty($sub_list_value['damages']) && is_numeric($sub_list_value['damages']) ? (int) $sub_list_value['damages'] : '',
                        'allowance' => ! empty($sub_list_value['allowance']) && is_numeric($sub_list_value['allowance']) ? (int) $sub_list_value['allowance'] : '',
                        'delay_interest' => ! empty($sub_list_value['delay_interest']) && is_numeric($sub_list_value['delay_interest']) ? (int) $sub_list_value['delay_interest'] : '',
                        'else' => ! empty($sub_list_value['else']) && is_numeric($sub_list_value['else']) ? (int) $sub_list_value['else'] : '',
                    ];

                    $sum = $this->_daily_report_sum_amount_extracted($data_rows[$data_rows_index], $sum);
                }
            }
            else
            {
                foreach ($value['sub_list'] as $sub_list_value)
                {
                    $data_rows_index++;
                    $data_rows[$data_rows_index] = [
                        'entering_date' => $data_rows[$data_rows_index - 1]['entering_date'],
                        'target_no' => $data_rows[$data_rows_index - 1]['target_no'],
                        'source_type' => $data_rows[$data_rows_index - 1]['source_type'],
                        'user_from' => $sub_list_value['user_from'] ?? '',
                        'v_bank_account_from' => $sub_list_value['v_bank_account_from'] ?? '',
                        'v_amount_from' => ! empty($sub_list_value['v_amount_from']) && is_numeric($sub_list_value['v_amount_from']) ? (int) $sub_list_value['v_amount_from'] : '',
                        'bank_account_from' => $sub_list_value['bank_account_from'] ?? '',
                        'amount_from' => ! empty($sub_list_value['amount_from']) && is_numeric($sub_list_value['amount_from']) ? (int) $sub_list_value['amount_from'] : '',
                        'user_to' => $sub_list_value['user_to'] ?? '',
                        'v_bank_account_to' => $sub_list_value['v_bank_account_to'] ?? '',
                        'v_amount_to' => ! empty($sub_list_value['v_amount_to']) && is_numeric($sub_list_value['v_amount_to']) ? (int) $sub_list_value['v_amount_to'] : '',
                        'bank_account_to' => $sub_list_value['bank_account_to'] ?? '',
                        'amount_to' => ! empty($sub_list_value['amount_to']) && is_numeric($sub_list_value['amount_to']) ? (int) $sub_list_value['amount_to'] : '',
                        'principal' => ! empty($sub_list_value['principal']) && is_numeric($sub_list_value['principal']) ? (int) $sub_list_value['principal'] : '',
                        'interest' => ! empty($sub_list_value['interest']) && is_numeric($sub_list_value['interest']) ? (int) $sub_list_value['interest'] : '',
                        'platform_fee' => ! empty($sub_list_value['platform_fee']) && is_numeric($sub_list_value['platform_fee']) ? (int) $sub_list_value['platform_fee'] : '',
                        'damages' => ! empty($sub_list_value['damages']) && is_numeric($sub_list_value['damages']) ? (int) $sub_list_value['damages'] : '',
                        'allowance' => ! empty($sub_list_value['allowance']) && is_numeric($sub_list_value['allowance']) ? (int) $sub_list_value['allowance'] : '',
                        'delay_interest' => ! empty($sub_list_value['delay_interest']) && is_numeric($sub_list_value['delay_interest']) ? (int) $sub_list_value['delay_interest'] : '',
                        'else' => ! empty($sub_list_value['else']) && is_numeric($sub_list_value['else']) ? (int) $sub_list_value['else'] : '',
                    ];

                    $sum = $this->_daily_report_sum_amount_extracted($data_rows[$data_rows_index], $sum);
                }
            }
            $data_rows_index++;
        }
        $data_rows[] = $sum;
        return $this->spreadsheet_lib->load($title_rows, $data_rows);
    }

	function passbook_report(){
		$get 		= $this->input->get(NULL, TRUE);
		$date 		= isset($get['date'])&&$get['date']?$get['date']:get_entering_date();
		$page_data 	= array("type"=>"list","date"=>$date);
		$date_range     = entering_date_range($date);
		$edatetime      = $date_range?$date_range["edatetime"]:"";

		// TODO: query with codeigniter orm
		// sql - inner join
		$sql = <<<TEMP
			SELECT T1.`virtual_account`, T2.`name`, T3.`investor` AS investor_status, SUM(T1.`amount`) AS total_amount
			FROM p2p_transaction.`virtual_passbook` T1
			INNER JOIN p2p_user.`users` T2
			INNER JOIN p2p_user.`virtual_account` T3
			WHERE T1.`virtual_account` = T3.`virtual_account`
			AND T3.`user_id` = T2.`id`
			AND T1.`virtual_account` != '{PLATFORM_VIRTUAL_ACCOUNT}'
			AND T1.`tx_datetime` <= '{$edatetime}'
			GROUP BY T1.`virtual_account`
			ORDER BY T1.`virtual_account` ASC
TEMP;


		$query_script = $this->db->query($sql);
		$result_data = $query_script->result();

		$row_length = count($result_data);
		for ($i = 0; $i < $row_length; $i++) {
			if ($result_data[$i]->total_amount == "0") {
				unset($result_data[$i]);
			}
		}

		$page_data['list'] = $result_data;
		$page_data['investor_list'] = $this->virtual_account_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/account_passbook_report',$page_data);
		$this->load->view('admin/_footer');
	}

	function estatement()
    {
		$page_data 	= array("type"=>"list","list"=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();
		$where		= array();
		$sdate 		= isset($input['sdate'])&&$input['sdate']?$input['sdate']:'';
		$edate 		= isset($input['edate'])&&$input['edate']?$input['edate']:'';
		$fields 	= ['investor','user_id'];
        $name       = '';

		if($sdate && $edate && $edate > $sdate ){
			$where	= array(
				"sdate >="	=> $sdate,
				"edate <="	=> $edate,
			);
		}

		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}

		if(!empty($input['name'])){
		    $name = $input['name'];
            $get_user_id = $this->user_model->get_by(['name'=>$input['name']]);
            if($get_user_id){
                $where['user_id'] = $get_user_id->id;
            }
        }

		if(!empty($where)){
			$list = $this->user_estatement_model->order_by("user_id","asc")->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$user = $this->user_model->get($value->user_id);
					$list[$key]->user_name 		= $user->name;
				}
			}
		}
        $page_data['name'] 		    = $name;
		$page_data['sdate'] 		= $sdate;
		$page_data['edate'] 		= $edate;
		$page_data['list'] 			= $list?$list:array();
		$page_data['investor_list'] = $this->user_estatement_model->investor_list;
		$page_data['status_list']   = $this->user_estatement_model->status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/estatement_list',$page_data);
		$this->load->view('admin/_footer');
	}

    public function estatement_excel()
    {
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('Estatement_lib');
        $this->estatement_lib->get_estatement_investor_detail($get['user_id'],$get['sdate'],$get['edate'],true);
    }

    public function estatement_s_excel(){
        $get = $this->input->get(NULL, TRUE);
        $this->load->library('Estatement_lib');
        $this->estatement_lib->get_estatement_investor($get['user_id'],$get['sdate'],$get['edate'],true);
    }

    /**
     * @param $data_rows
     * @param array $sum
     * @return array
     */
    private function _daily_report_sum_amount_extracted($data_rows, array $sum): array
    {
        $sum['v_amount_from'] += (int) $data_rows['v_amount_from'];
        $sum['amount_from'] += (int) $data_rows['amount_from'];
        $sum['v_amount_to'] += (int) $data_rows['v_amount_to'];
        $sum['amount_to'] += (int) $data_rows['amount_to'];
        $sum['principal'] += (int) $data_rows['principal'];
        $sum['interest'] += (int) $data_rows['interest'];
        $sum['platform_fee'] += (int) $data_rows['platform_fee'];
        $sum['damages'] += (int) $data_rows['damages'];
        $sum['allowance'] += (int) $data_rows['allowance'];
        $sum['delay_interest'] += (int) $data_rows['delay_interest'];
        $sum['else'] += (int) $data_rows['else'];
        return $sum;
    }
}
