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
    }
 
	public function get_estatement_investor($user_id=0,$sdate="",$edate=""){
		$user_info = $this->CI->user_model->get($user_id);
		if($user_info){
			$virtual_account = $this->CI->virtual_account_model->get_by(array("status"=>1,"investor"=>1,"user_id"=>$user_id));
			if($virtual_account){
				$date_range	= entering_date_range($edate);
				$edatetime	= $date_range?$date_range["edatetime"]:"";
				$date_range	= entering_date_range($sdate);
				$sdatetime	= $date_range?$date_range["sdatetime"]:"";
				$total 		= $frozen = $interest = $interest_count = $allowance = $allowance_count = 0;
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
						"source"			=> array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST),
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
							"source"			=> array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST),
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
						$ar_total_count = $ar_principal_count + $ar_interest_count + $delay_ar_principal_count + $delay_ar_interest_count;
						$ar_total		= $ar_principal + $ar_interest + $delay_ar_principal + $delay_ar_interest;
					}
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
				$url 	= $this->upload_pdf($user_id,$html,$user_info->id_number,"投資人對帳單-".$edate,$user_id."-estatement-".$edate.".pdf","investor/".$edate);
				if($url){
					$param = array(
						"user_id"	=> $user_id,
						"type"		=> "estatement",
						"investor"	=> 1,
						"sdate"		=> $sdate,
						"edate"		=> $edate,
						"url"		=> $url,
					);
					$rs = $this->CI->user_estatement_model->insert($param);
					return $rs;
				}
				
			}
		}
		return false;
	}
	
	public function get_estatement_borrower($user_id=0,$sdate="",$edate=""){
		$user_info = $this->CI->user_model->get($user_id);
		if($user_info){
			$virtual_account = $this->CI->virtual_account_model->get_by(array("status"=>1,"investor"=>0,"user_id"=>$user_id));
			if($virtual_account){
				$credit 	 = $this->CI->credit_lib->get_credit($user_id,1);
				$used_credit = 0;
				$target_list 	= $this->CI->target_model->get_many_by(array("product_id"=>1,"user_id"=>$user_id,"status <="=>5));
				if($target_list){
					foreach($target_list as $key =>$value){
						$used_credit += intval($value->loan_amount);
					}
				}
				
				$date_range	= entering_date_range($edate);
				$edatetime	= $date_range?$date_range["edatetime"]:"";
				$date_range	= entering_date_range($sdate);
				$sdatetime	= $date_range?$date_range["sdatetime"]:"";
				$normal_count = 0;
				$total = $normal_amount = $normal_rapay = $ar_principal = 0;
				$normal_target = array();
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
					
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"source"			=> array(SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST),
						"user_from" 		=> $user_id,
						"status" 			=> array(1,2)
					));
					if($transactions){
						foreach($transactions as $key => $value){
							switch ($value->source) {
								case SOURCE_AR_PRINCIPAL: 
									$ar_principal += $value->amount;
									if($value->limit_date>=$sdate && $value->limit_date<=$edate){
										$normal_target[$value->target_id] = $value->target_id;
										$normal_amount += $value->amount;
									}
									break;
								case SOURCE_AR_INTEREST:
									if($value->limit_date>=$sdate && $value->limit_date<=$edate){
										$normal_amount += $value->amount;
									}
									break;
								default:
									break;
							}
						}
					}
					
					$transactions 	= $this->CI->transaction_model->get_many_by(array(
						"entering_date <="	=> $edate,
						"source"			=> array(SOURCE_PRINCIPAL,SOURCE_INTEREST),
						"user_from" 		=> $user_id,
						"status" 			=> 2
					));
					if($transactions){
						foreach($transactions as $key => $value){
							if($value->entering_date>=$sdate && $value->entering_date<=$edate){
								$normal_rapay += $value->amount;
							}
							switch ($value->source) {
								case SOURCE_PRINCIPAL: 
									$ar_principal -= $value->amount;
									break;
								default:
									break;
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
					"normal_count"		=> count($normal_target),
					"normal_amount"		=> number_format($normal_amount),
					"normal_rapay"		=> number_format($normal_rapay),
					"ar_principal"		=> number_format($ar_principal),
					"virtual_account"	=> $virtual_account->virtual_account,
				);
				$html 	= $this->CI->parser->parse('estatement/borrower', $data,TRUE);
				$url 	= $this->upload_pdf($user_id,$html,$user_info->id_number,"借款人對帳單-".$edate,$user_id."-estatement-".$edate.".pdf","borrower/".$edate);
				if($url){
					$param = array(
						"user_id"	=> $user_id,
						"type"		=> "estatement",
						"investor"	=> 0,
						"sdate"		=> $sdate,
						"edate"		=> $edate,
						"url"		=> $url,
					);
					$rs = $this->CI->user_estatement_model->insert($param);
					return $rs;
				}
				
			}
		}
		return false;
	}
	
	public function upload_pdf($user_id=0,$html="",$password="",$title="",$file_name="",$path=""){
		if($user_id){
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
			$pdf->SetProtection($permissions , $password , PDF_OWNER_PASSWORD, 0, null);
			$pdf->SetCreator(GMAIL_SMTP_NAME);
			$pdf->SetAuthor(GMAIL_SMTP_NAME);
			$pdf->SetTitle($title);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->setFontSubsetting(true);
			$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->AddPage('L');
			$pdf->writeHTML($html, 1, 0, true, true, '');
			$files 		= $pdf->Output("","S");
			$file_url 	= $this->CI->s3_upload->pdf ($files,$file_name,$user_id,'estatement/'.$path);
			if($file_url){
				return $file_url;
			}
		}
		return false;
	}
}


