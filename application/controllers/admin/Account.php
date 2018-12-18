<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Account extends MY_Admin_Controller {
	
	protected $edit_method = array();
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/virtual_account_model');
		$this->load->model('transaction/virtual_passbook_model');
		$this->load->model('transaction/transaction_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('user/user_estatement_model');
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
			SOURCE_PREPAYMENT_DAMAGE,
			SOURCE_VERIFY_FEE_R,
			SOURCE_REMITTANCE_FEE_R,
			SOURCE_DAMAGE,
			SOURCE_PREPAYMENT_ALLOWANCE,
			SOURCE_VERIFY_FEE,
			SOURCE_REMITTANCE_FEE
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
		if(!empty($data)){
			foreach($data as $key =>$value){
				if(!isset($list[$value->source])){
					$list[$value->source] = array("debit"=>0,"credit"=>0);
				}
				
				if($value->bank_account_from==PLATFORM_VIRTUAL_ACCOUNT){
					$list[$value->source]['credit'] += $value->amount;
				}
				
				if($value->bank_account_to==PLATFORM_VIRTUAL_ACCOUNT){
					$list[$value->source]['debit'] += $value->amount;
				}
			}
		}
		$page_data['list'] 					= $list;
		$page_data['sum'] 					= array("debit"=>0,"credit"=>0);
		$page_data['transaction_source'] 	= $this->config->item('transaction_source');
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
		$source		= array(
			SOURCE_AR_FEES,
			SOURCE_AR_PRINCIPAL,
			SOURCE_AR_INTEREST,
			SOURCE_AR_DAMAGE,
			SOURCE_AR_DELAYINTEREST,
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
		
			foreach($data as $key =>$value){
				if($value->target_id){
					$value->target_no = $target_no[$value->target_id];
				}else{
					$value->target_no = "";
				}
				
				
				if($value->source == SOURCE_RECHARGE || $value->source == SOURCE_WITHDRAW){
					$switch  		= array(SOURCE_RECHARGE=>'recharge',SOURCE_WITHDRAW=>'withdraw');
					$source_type 	= $switch[$value->source];
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
						"source_type"			=> $source_type,
						"user_from"				=> $user_name[$value->user_from],
						"bank_account_from"		=> $value->bank_account_from,
						"amount_from"			=> $value->amount_from,
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $value->v_amount_from,
						"user_to"				=> $user_name[$value->user_to],
						"bank_account_to"		=> $value->bank_account_to,
						"amount_to"				=> $value->amount_to,
						"v_bank_account_to"		=> $value->v_bank_account_to,
						"v_amount_to"			=> $value->v_amount_to,
						"created_at"			=> $value->created_at,
					);
				}

				if($value->source == SOURCE_TRANSFER){
					$sub_list = array();
					foreach($data as $k =>$v){
						if($v->source==SOURCE_TRANSFER_FEE && $v->investment_id==$value->investment_id && $v->user_from==$value->user_to){
							$sub_list[] = array(
								"user_to"				=> $user_name[$value->user_to],
								"bank_account_to"		=> $value->bank_account_to,
								"amount_to"				=> $value->amount_to,
								"v_bank_account_to"		=> $value->v_bank_account_to,
								"v_amount_to"			=> $value->v_amount_to - $v->amount,
								"platform_fee"			=> $v->amount,
							);
						}
					}
					
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
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
									$user_to_info[$v->investment_id]["user_to"]				= $v->user_to;
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
								"user_to"				=> $user_name[$v['user_to']],
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
						"source_type"			=> 'prepayment',
						"user_from"				=> $user_name[$value->user_from],
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $amount,
						"damages"				=> $damages,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
				
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
									$user_to_info[$v->investment_id]["user_to"]				= $v->user_to;
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
									$user_to_info[$v->investment_id]["platform_fee"]+= $v->amount;
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
								"user_to"				=> $user_name[$v['user_to']],
								"v_bank_account_to"		=> $v['v_bank_account_to'],
								"v_amount_to"			=> $v['principal'] + $v['interest'] + $v['delay_interest'] - $v['platform_fee'],
								"principal"				=> $v['principal'],
								"interest"				=> $v['interest'],
								"platform_fee"			=> $v['platform_fee'],
								"delay_interest"		=> $v['delay_interest'],
							);
						}
					}
					$amount += $damages;
					$list[] = array(
						"entering_date"			=> $value->entering_date,
						"target_no"				=> $value->target_no,
						"source_type"			=> 'charge_delay',
						"user_from"				=> $user_name[$value->user_from],
						"v_bank_account_from"	=> $value->v_bank_account_from,
						"v_amount_from"			=> $amount,
						"damages"				=> $damages,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
				
				if($value->source == SOURCE_LENDING && !in_array($value->target_id,$lending_target)){
					$lending_target[] = $value->target_id;
					$sub_list 		= array();
					$user_to_info 	= array();
					$platform_fee 	= 0;
					$amount 		= 0;
					foreach($data as $k =>$v){
						if($v->entering_date==$value->entering_date && $v->target_id==$value->target_id && $v->instalment_no==$value->instalment_no){
							switch($v->source){
								case SOURCE_LENDING: 
									$sub_list[] = array(
										"user_from"				=> $user_name[$v->user_from],
										"v_bank_account_from"	=> $v->v_bank_account_from,
										"v_amount_from"			=> $v->amount,
									);
									$amount += $v->amount;
									break;
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
						"source_type"			=> "lending",
						"user_to"				=> $user_name[$value->user_to],
						"bank_account_to"		=> $value->bank_account_to,
						"amount_to"				=> $amount,
						"platform_fee"			=> $platform_fee,
						"sub_list"				=> $sub_list,
						"created_at"			=> $value->created_at,
					);
				}
			}
			
			foreach($data as $key => $value){
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
								case SOURCE_PRINCIPAL: 
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["principal"]			+= $v->amount;
									$user_to_info[$v->investment_id]["user_to"]				= $v->user_to;
									$user_to_info[$v->investment_id]["v_bank_account_to"]	= $v->v_bank_account_to;
									break;
								case SOURCE_INTEREST: 
									$amount += $v->amount;
									$user_to_info[$v->investment_id]["interest"]	+= $v->amount;
									break;
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
		}else{
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/account_daily_report',$page_data);
			$this->load->view('admin/_footer');
		}
	}
	
	function passbook_report(){
		$page_data 	= array("type"=>"list");
		$where		= array(
			"status" 		=> array(4,5),
		);
		
		$get 		= $this->input->get(NULL, TRUE);
		$date 		= isset($get['date'])&&$get['date']?$get['date']:get_entering_date();
		$page_data 	= array("type"=>"list","date"=>$date);
		$date_range	= entering_date_range($date);
		$edatetime	= $date_range?$date_range["edatetime"]:"";
		$list		= array();
		$info		= array();
		if($edatetime){
			$virtual_passbook = $this->virtual_passbook_model->order_by("virtual_account","ASC")->get_many_by(array(
				"virtual_account <>" 	=> PLATFORM_VIRTUAL_ACCOUNT,
				"tx_datetime <=" 		=> $edatetime,
			));
			if(!empty($virtual_passbook)){
				foreach($virtual_passbook as $key => $value){
					if(!isset($list[$value->virtual_account])){
						$list[$value->virtual_account] = 0;
						$info[$value->virtual_account] = $this->virtual_account_model->get_by(array(
							"virtual_account" => $value->virtual_account
						));
						$info[$value->virtual_account]->user_info = $this->user_model->get($info[$value->virtual_account]->user_id);
					}
					$list[$value->virtual_account] += $value->amount;
				}
				
				foreach($list as $key => $value){
					if($value==0){
						unset($list[$key]);
					}
				}
			}
		}
		
		$page_data['investor_list'] = $this->virtual_account_model->investor_list;
		$page_data['list'] 			= $list;
		$page_data['info'] 			= $info;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/account_passbook_report',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function estatement(){
		$page_data 	= array("type"=>"list","list"=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();
		$where		= array();
		$sdate 		= isset($input['sdate'])&&$input['sdate']?$input['sdate']:'';
		$edate 		= isset($input['edate'])&&$input['edate']?$input['edate']:'';
		$fields 	= ['investor','user_id'];
		
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
		
		if(!empty($where)){
			$list = $this->user_estatement_model->order_by("user_id","asc")->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$user = $this->user_model->get($value->user_id);
					$list[$key]->user_name 		= $user->name;
				}
			}
		}
		
		$page_data['sdate'] 		= $sdate;
		$page_data['edate'] 		= $edate;
		$page_data['list'] 			= $list?$list:array();
		$page_data['investor_list'] = $this->user_estatement_model->investor_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/estatement_list',$page_data);
		$this->load->view('admin/_footer');
	}
}