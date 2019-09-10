<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/payment_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->library('Transaction_lib');
    }
	
	public function script_get_cathay_info($date=""){
		if(empty($date)){
			$date = date("Ymd");
		}
		
		$from_date 	= date("Ymd",strtotime($date." -1 day"));
		$to_date 	= date("Ymd",strtotime($date));
		$param = array(
			"cust_id"		=> CATHAY_CUST_ID,
			"cust_nickname"	=> CATHAY_CUST_NICKNAME,
			"cust_pwd"		=> CATHAY_CUST_PASSWORD,
			"acno"			=> CATHAY_CUST_ACCNO,
			"from_date"		=> $from_date,
			"to_date"		=> $to_date,
			"xml"			=> "Y",
			"txdate8"		=> "Y",
		);
		$rs 	= curl_get(CATHAY_API_URL,$param);
		$rs 	= iconv('big5', 'big5//IGNORE', $rs); 
		$xml 	= simplexml_load_string($rs);
		$xml 	= json_decode(json_encode($xml),TRUE);
		
		$insert_param = array();
		if(isset($xml["@attributes"]["error_id"]) && $xml["@attributes"]["error_id"]=="0"){
			if(isset($xml["TXDETAIL"])&& !empty($xml["TXDETAIL"])){
				$sdate 			= date("Y-m-d",strtotime($from_date))." 00:00:00";
				$edate 			= date("Y-m-d",strtotime($to_date))." 23:59:59";
				$old			= array();
				$payment_list 	= $this->CI->payment_model->get_many_by(array("tx_datetime >="=>$sdate,"tx_datetime <="=>$edate));
				if(!empty($payment_list)){
					foreach($payment_list as $pkey => $pvalue){
						$okey = $pvalue->tx_seq_no.'_'.$pvalue->tx_datetime.'_'.$pvalue->amount;
						$old[$okey] = true;
					}
				}
				
				if(isset($xml['TXDETAIL']['BACCNO']) && !empty($xml['TXDETAIL']['BACCNO'])){
					$txdetail = $xml['TXDETAIL'];
					$xml['TXDETAIL'] = array(0=>$txdetail);
				}
				
				foreach($xml['TXDETAIL'] as $key => $value){
					
					if(is_array($value)){
						foreach($value as $k =>$v){
							if(is_array($v)){
								$value[$k] = "";
							}else{
								$value[$k] = trim($value[$k]);
							}
						}
					}
					$tx_datetime = date("Y-m-d H:i:s",strtotime($value['TX_DATE'].' '.$value['TX_TIME']));
					$amount 	 = intval($value['SIGN'].$value['AMOUNT']);
					if($value['DC']=="1"){
						$amount = $amount*-1;
					}
					$virtual_account = "";
					if(is_virtual_account($value['MEMO1'])){
						$virtual_account = $value['MEMO1'];
					}
					
					$bank_amount = intval($value['BSIGN'].$value['BAMOUNT']);
					$data = array(
						"bankaccount_no"	=> $value['BACCNO'],
						"tx_datetime"		=> $tx_datetime,
						"tx_seq_no"			=> $value['TX_SEQNO'],
						"tx_id_no"			=> $value['TX_IDNO'],
						"amount"			=> $amount,
						"memo"				=> $value['MEMO1'],
						"bank_amount"		=> $bank_amount,
						"bank_id"			=> $value['BANKID'],
						"acc_name"			=> $value['ACCNAME'],
						"bank_acc"			=> $value['MEMO2'],
						"tx_mach"			=> $value['TX_MACH'],
						"tx_spec"			=> $value['TX_SPEC'],
						"virtual_account"	=> $virtual_account,
					);
					$insert = true;
					$okey 	= $data["tx_seq_no"].'_'.$data["tx_datetime"].'_'.$data["amount"];
					if(!empty($old) && isset($old[$okey]) && $old[$okey]){
						$insert = false;
					}
					
					if($insert){
						$insert_param[] = $data;
					}
				}
				if(!empty($insert_param)){
					$ids = $this->CI->payment_model->insert_many($insert_param);
					return $ids;
				}
			}
		}
		return false;
	}
	
	public function script_handle_payment($num=20){ 
		$count = 0;
		$this->CI->payment_model->limit($num)->update_by(array("status"=>0),array("status"=>2));
		$payments = $this->CI->payment_model->get_many_by(array("status"=>2));
		if($payments && !empty($payments)){
			foreach($payments as $key => $value){
				if($value->amount>0){
					$rs = $this->receipt($value);
				}else{
					$rs = $this->expense($value);
				}
				$count++;
			}
			return $count;
		}
		return false;
	}

	//入帳處理
	private function receipt($value){
		if(!empty($value->virtual_account)){
			$bank_code 		= $bank_account = "";
			$bank 			= bankaccount_substr($value->bank_acc);
			$value->bank_id = substr($value->bank_id,0,3);
			
			if($bank['bank_code']==$value->bank_id){
				$bank_code 		= $bank['bank_code'];
				$bank_account 	= $bank['bank_account'];
			}else{
				$bank_code 		= $value->bank_id;
				$bank_account 	= $value->bank_acc;
			}
			
			$this->CI->load->model('user/virtual_account_model');
			$virtual_account 	= $this->CI->virtual_account_model->get_by(array("virtual_account"=>$value->virtual_account));
			$investor			= investor_virtual_account($value->virtual_account)?1:0;
			$where				= array(
				"investor"			=> $investor,
				"bank_code"			=> $bank_code,
				"bank_account like"	=> '%'.$bank_account,
				"status"			=> 1,
				"verify"			=> 1
			);
			$user_bankaccount 	= $this->CI->user_bankaccount_model->get_by($where);
			if($virtual_account && $user_bankaccount){
				if($virtual_account->user_id == $user_bankaccount->user_id){
					$this->CI->transaction_lib->recharge($value->id);
					return true;
				}else{
					if(!investor_virtual_account($value->virtual_account)){
						$this->CI->transaction_lib->recharge($value->id);
						return true;
					}
				}
			}else{
				if($virtual_account){
					if(!investor_virtual_account($value->virtual_account)){
						$this->CI->transaction_lib->recharge($value->id);
						return true;
					}
				}
			}
		}else{
			if(in_array($value->amount,array(1,30)) && in_array($value->tx_spec,array('匯出退匯','錯誤更正','沖ＦＸＭ'))){
				$this->CI->transaction_lib->verify_fee($value);
				return true;
			}
		}
		
		if($virtual_account){
			$this->CI->load->library('Notification_lib');
			$this->CI->notification_lib->unknown_refund($virtual_account->user_id);
		}
		
		$this->CI->payment_model->update($value->id,array("status"=>5));
		return false;
	}
	
	//出帳處理
	private function expense($value){
		if(in_array($value->amount,array(-1,-30,-31))){
			$this->CI->transaction_lib->verify_fee($value);
			return true;
		}else{
			$this->CI->payment_model->update($value->id,array("status"=>3));
			return false;
		}
	}
	
	public function verify_bankaccount_txt($admin_id=0){
		$this->CI->load->model('admin/difficult_word_model');
		$word_list 			= $this->CI->difficult_word_model->get_name_list();
		$where				= array(
			"status"		=> 1,
			"verify"		=> 2
		);
		$bankaccounts 	= $this->CI->user_bankaccount_model->get_many_by($where);
		$content 		= "";
		$xml_content 	= "";
		$xml_bank_list 	= $this->CI->config->item('xml_bank_list');
		$ids 			= array();
		$xml_ids 		= array();
		if($bankaccounts){
			
			foreach($bankaccounts as $key => $value){
				$user_info = $this->CI->user_model->get($value->user_id);
				if($user_info && $user_info->name){
					$difficult = false;
					$name_list = mb_str_split($user_info->name);
					if($name_list){
						$name = "";
						foreach($name_list as $k => $v){
							if(!iconv('UTF-8', 'BIG-5//IGNORE', $v)){
								$v 			= isset($word_list[$v])?$word_list[$v]:"";
								$difficult 	= true;
							}
							$name .= $v;
						}
						$user_info->name = $name;
					}

					$this->CI->user_bankaccount_model->update($value->id,array("verify"=>3,"verify_at"=>time(),"sys_check"=>0));

					$data = array(
						"code"			=> "0",
						"upload_date"	=> "",
						"entering_date"	=> date("Ymd"),
						"t_type"		=> "SPU",
						"t_code"		=> "",
						"bankcode_from"	=> CATHAY_BANK_CODE.CATHAY_BRANCH_CODE,
						"bankacc_from"	=> CATHAY_CUST_ACCNO,
						"tax_from"		=> CATHAY_CUST_ID,
						"name_from"		=> nf_to_wf(CATHAY_COMPANY_NAME),
						"TWD"			=> "TWD",
						"plus"			=> "+",
						"amount"		=> 1,//靠左補0
						"bankcode_to"	=> $value->bank_code.$value->branch_code,
						"bankacc_to"	=> $value->bank_account,
						"tax_to"		=> strtoupper($user_info->id_number),
						"name_to"		=> nf_to_wf($user_info->name),
						"alert_to"		=> "0",
						"email_to"		=> "",
						"fee_type"		=> "15",
						"invoice_num"	=> "",
						"remark"		=> nf_to_wf("金融帳號驗證"),
					);
				
					$data = $this->check_len($data);
					
					if(!$difficult && in_array($value->bank_code,$xml_bank_list)){
						$xml_ids[] = $value->id;
						if($xml_content != ""){
							$xml_content .= "\n";
						}
						
						foreach($data as $key => $value){
							$xml_content .= $value;
						}
					}else{
						$ids[] = $value->id;
						if($content != ""){
							$content .= "\n";
						}
						
						foreach($data as $key => $value){
							$content .= $value;
						}
					}
					
				}
			}
			$this->CI->load->model('log/log_paymentexport_model');
			
			if($content !=""){
				$upload 	= $this->upload_file($content,'normal');
				$batch_no 	= $upload?$upload['batch_no']:"";
				$txn_key 	= $upload?$upload['txn_key']:"";
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "bankaccount",
					"content"	=> json_encode($ids),
					"cdata"		=> base64_encode($content),
					"batch_no"	=> $batch_no,
					"txn_key"	=> $txn_key,
					"admin_id"	=> $admin_id,
				));
			}

			if($xml_content !=""){
				$upload 	= $this->upload_file($xml_content,'fxml');
				$batch_no 	= $upload?$upload['batch_no']:"";
				$txn_key 	= $upload?$upload['txn_key']:"";
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "bankaccount",
					"content"	=> json_encode($xml_ids),
					"cdata"		=> base64_encode($xml_content),
					"batch_no"	=> $batch_no,
					"txn_key"	=> $txn_key,
					"admin_id"	=> $admin_id,
				));
			}
		}
		return array(
			"content"		=> $content,
			"xml_content"	=> $xml_content
		);
	}
	
	public function loan_txt($ids=array(),$admin_id=0){
		$this->CI->load->model('admin/difficult_word_model');
		$word_list = $this->CI->difficult_word_model->get_name_list();		
		if($ids){
			$targets = $this->CI->target_model->get_many($ids);
			if($targets){
				$content 	= "";
				$ids 		= array();
				foreach($targets as $key => $value){
					if($value->status==4 && $value->sub_status==0 && $value->loan_status==2){
						$user_info = $this->CI->user_model->get($value->user_id);
						if($user_info){
							$bankaccount 	= $this->CI->user_bankaccount_model->get_by(array(
								"user_id"		=> $value->user_id,
								"investor"		=> 0,
								"status"		=> 1,
								"verify"		=> 1
							));
							if($bankaccount){
								$target_update_param = array("loan_status"=>3);
								$this->CI->target_model->update($value->id,$target_update_param);
								$this->CI->load->library('target_lib');
								$this->CI->target_lib->insert_change_log($value->id,$target_update_param,0,$admin_id);
								$amount = intval($value->loan_amount) - intval($value->platform_fee);
								$ids[] 	= $value->id;
								
								$name_list = mb_str_split($user_info->name);
								if($name_list){
									$name = "";
									foreach($name_list as $k => $v){
										if(!iconv('UTF-8', 'BIG-5', $v)){
											$v = isset($word_list[$v])?$word_list[$v]:"";
										}
										$name .= $v;
									}
									$user_info->name = $name;
								}

								$data = array(
									"code"			=> "0",
									"upload_date"	=> "",
									"entering_date"	=> date("Ymd"),
									"t_type"		=> "SPU",
									"t_code"		=> "",
									"bankcode_from"	=> CATHAY_BANK_CODE.CATHAY_BRANCH_CODE,
									"bankacc_from"	=> CATHAY_CUST_ACCNO,
									"tax_from"		=> CATHAY_CUST_ID,
									"name_from"		=> nf_to_wf(CATHAY_COMPANY_NAME),
									"TWD"			=> "TWD",
									"plus"			=> "+",
									"amount"		=> $amount,//靠左補0
									"bankcode_to"	=> $bankaccount->bank_code.$bankaccount->branch_code,
									"bankacc_to"	=> $bankaccount->bank_account,
									"tax_to"		=> "",
									"name_to"		=> nf_to_wf($user_info->name),
									"alert_to"		=> "0",
									"email_to"		=> "",
									"fee_type"		=> "13",
									"invoice_num"	=> "",
									"remark"		=> nf_to_wf($value->target_no."放款"),
								);
								$data = $this->check_len($data);
								if($content != ""){
									$content .= "\n";
								}
								foreach($data as $key => $value){
									$content .= $value;
								}
							}
						}
					}
				}
				
				$upload 	= $this->upload_file($content,'atm');
				$batch_no 	= $upload?$upload['batch_no']:"";
				$txn_key 	= $upload?$upload['txn_key']:"";
				
				$this->CI->load->model('log/log_paymentexport_model');
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "target_loan",
					"content"	=> json_encode($ids),
					"cdata"		=> base64_encode($content),
					"batch_no"	=> $batch_no,
					"txn_key"	=> $txn_key,
					"admin_id"	=> $admin_id,
				));

				return $content;
			}
		}
		return false;
	}
	
	public function withdraw_txt($ids=array(),$admin_id=0){
		$this->CI->load->model('admin/difficult_word_model');
		$this->CI->load->model('transaction/withdraw_model');
		$word_list = $this->CI->difficult_word_model->get_name_list();
		if($ids){
			$withdraws = $this->CI->withdraw_model->get_many($ids);
			if($withdraws){
				$content 	= "";
				$ids 		= array();
				foreach($withdraws as $key => $value){
					if($value->status==0 && $value->frozen_id>0){
						if($value->user_id==0 && $value->virtual_account==PLATFORM_VIRTUAL_ACCOUNT){
							$this->CI->withdraw_model->update($value->id,array("status"=>2));
							$amount = intval($value->amount);
							$ids[] 	= $value->id;
							$data = array(
								"code"			=> "0",
								"upload_date"	=> "",
								"entering_date"	=> date("Ymd"),
								"t_type"		=> "SPU",
								"t_code"		=> "",
								"bankcode_from"	=> CATHAY_BANK_CODE.CATHAY_BRANCH_CODE,
								"bankacc_from"	=> CATHAY_CUST_ACCNO,
								"tax_from"		=> CATHAY_CUST_ID,
								"name_from"		=> nf_to_wf(CATHAY_COMPANY_NAME),
								"TWD"			=> "TWD",
								"plus"			=> "+",
								"amount"		=> $amount,//靠左補0
								"bankcode_to"	=> CATHAY_BANK_CODE.CATHAY_BRANCH_CODE,
								"bankacc_to"	=> CATHAY_COMPANY_ACCOUNT,
								"tax_to"		=> CATHAY_CUST_ID,
								"name_to"		=> nf_to_wf(CATHAY_COMPANY_NAME),
								"alert_to"		=> '0',
								"email_to"		=> '',
								"fee_type"		=> '13',
								"invoice_num"	=> '',
								"remark"		=> nf_to_wf('提領放款'),
							);
						
							$data = $this->check_len($data);
							
							if($content != ""){
								$content .= "\n";
							}
							
							foreach($data as $key => $value){
								$content .= $value;
							}
						}else{
							$user_info = $this->CI->user_model->get($value->user_id);
							if($user_info){
								$bankaccount 	= $this->CI->user_bankaccount_model->get_by(array(
									"user_id"		=> $value->user_id,
									"investor"		=> $value->investor,
									"status"		=> 1,
									"verify"		=> 1
								));
								if($bankaccount){
									$this->CI->withdraw_model->update($value->id,array("status"=>2));
									$amount = intval($value->amount);
									$ids[] 	= $value->id;
									
									$name_list = mb_str_split($user_info->name);
									if($name_list){
										$name = "";
										foreach($name_list as $k => $v){
											if(!iconv('UTF-8', 'BIG-5', $v)){
												$v = isset($word_list[$v])?$word_list[$v]:"";
											}
											$name .= $v;
										}
										$user_info->name = $name;
									}

									$data = array(
										"code"			=> "0",
										"upload_date"	=> "",
										"entering_date"	=> date("Ymd"),
										"t_type"		=> "SPU",
										"t_code"		=> "",
										"bankcode_from"	=> CATHAY_BANK_CODE.CATHAY_BRANCH_CODE,
										"bankacc_from"	=> CATHAY_CUST_ACCNO,
										"tax_from"		=> CATHAY_CUST_ID,
										"name_from"		=> nf_to_wf(CATHAY_COMPANY_NAME),
										"TWD"			=> "TWD",
										"plus"			=> "+",
										"amount"		=> $amount,//靠左補0
										"bankcode_to"	=> $bankaccount->bank_code.$bankaccount->branch_code,
										"bankacc_to"	=> $bankaccount->bank_account,
										"tax_to"		=> "",
										"name_to"		=> nf_to_wf($user_info->name),
										"alert_to"		=> "0",
										"email_to"		=> "",
										"fee_type"		=> "13",
										"invoice_num"	=> "",
										"remark"		=> nf_to_wf("提領放款"),
									);
								
									$data = $this->check_len($data);
									
									if($content != ""){
										$content .= "\n";
									}
									
									foreach($data as $key => $value){
										$content .= $value;
									}
								}
							}
						}
					}
				}
				
				$upload 	= $this->upload_file($content,'atm');
				$batch_no 	= $upload?$upload['batch_no']:"";
				$txn_key 	= $upload?$upload['txn_key']:"";
				
				$this->CI->load->model('log/log_paymentexport_model');
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "withdraw",
					"content"	=> json_encode($ids),
					"cdata"		=> base64_encode($content),
					"batch_no"	=> $batch_no,
					"txn_key"	=> $txn_key,
					"admin_id"	=> $admin_id,
				));
				
				return $content;
			}
		}
		return false;
	}
	
	public function unknown_txt($ids=array(),$admin_id=0){

		$this->CI->load->model('transaction/payment_model');
		if($ids){
			$payments = $this->CI->payment_model->get_many($ids);
			if($payments){
				$content 	= "";
				$ids 		= array();
				foreach($payments as $key => $value){
					if($value->status==5 && $value->amount > 15){
						$this->CI->payment_model->update($value->id,array("status"=>4,"refund_at"=>time()));
						$amount 		= intval($value->amount);
						$bank 			= bankaccount_substr($value->bank_acc);
						$value->bank_id = substr($value->bank_id,0,3);
						if($bank['bank_code']==$value->bank_id){
							$bank_code 		= $bank['bank_code'];
							$bank_account 	= $bank['bank_account'];
						}else{
							$bank_code 		= $value->bank_id;
							$bank_account 	= $value->bank_acc;
						}
						$ids[] 	= $value->id;
						$data = array(
							"code"			=> "0",
							"upload_date"	=> "",
							"entering_date"	=> date("Ymd"),
							"t_type"		=> "SPU",
							"t_code"		=> "",
							"bankcode_from"	=> CATHAY_BANK_CODE.CATHAY_BRANCH_CODE,
							"bankacc_from"	=> CATHAY_CUST_ACCNO,
							"tax_from"		=> CATHAY_CUST_ID,
							"name_from"		=> nf_to_wf(CATHAY_COMPANY_NAME),
							"TWD"			=> "TWD",
							"plus"			=> "+",
							"amount"		=> $amount,//靠左補0
							"bankcode_to"	=> $bank_code.'0000',
							"bankacc_to"	=> $bank_account,
							"tax_to"		=> "",
							"name_to"		=> "",
							"alert_to"		=> "0",
							"email_to"		=> "",
							"fee_type"		=> "13",
							"invoice_num"	=> "",
							"remark"		=> nf_to_wf("不明退款"),
						);
					
						$data = $this->check_len($data);
						if($content != ""){
							$content .= "\n";
						}
						foreach($data as $key => $value){
							$content .= $value;
						}
					}
				}
				
				$upload 	= $this->upload_file($content,'atm');
				$batch_no 	= $upload?$upload['batch_no']:"";
				$txn_key 	= $upload?$upload['txn_key']:"";
				
				$this->CI->load->model('log/log_paymentexport_model');
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "unknown_refund",
					"content"	=> json_encode($ids),
					"cdata"		=> base64_encode($content),
					"batch_no"	=> $batch_no,
					"txn_key"	=> $txn_key,
					"admin_id"	=> $admin_id,
				));

				return $content;
			}
		}
		return false;
	}
	
	//上傳檔案
	public function upload_file($content='',$type='normal'){
		if(is_development()){
			return false;
		}
		
		$fxml 	= '';
		$ftype 	= 'BRMT/BRMT/0';
		$source = '1';
		if($type=='atm'){
			$fxml 	= '';
			$ftype 	= 'BTRS/BRMT/0';
			$source = '2';
		}
		if($type=='fxml'){
			$fxml 	= 'FXML';
			$ftype 	= 'BRMT/BRMT/0';
			$source = '3';
		}

		
		$txnkey = date("YmdHis").$source.rand(0, 9);
		$xml_file 	= 
'<?xml version="1.0" encoding="big5"?>
<MYB2B>
	<HEADER>
		<SERVICE>PAYSVC</SERVICE>
		<ACTION>BTRS01</ACTION>
		<TXNKEY>'.$txnkey.'</TXNKEY>
	</HEADER>
	<BODY>
		<LOGON>
			<IDNO>'.CATHAY_CUST_ID.'</IDNO>
			<PASSWORD>'.CATHAY_CUST_PASSWORD.'</PASSWORD>
			<USERNO>'.CATHAY_CUST_NICKNAME.'</USERNO>
			<BRANCH>'.substr(CATHAY_BRANCH_CODE,0,3).'</BRANCH>
		</LOGON>
		<DATA>
			<CONTENT FileType="'.$ftype.'" DrAcno="'.CATHAY_CUST_ACCNO.'" RemitType="'.$fxml.'">
				<![CDATA['.$content.']]>
			</CONTENT>
		</DATA>
	</BODY>
</MYB2B>';
		$xml_file 	= iconv('UTF-8', 'BIG-5',$xml_file);
		$key 		= iconv('UTF-8', 'BIG-5',CATHAY_AES_KEY);
		$rs 		= iconv('UTF-8', 'BIG-5',CATHAY_CUST_ID.'            '.$this->strToHex($this->encrypt($xml_file,$key)));
		$res 		= curl_get(CATHAY_AP2AP_API_URL,$rs,["Content-type:text/xml"]);
		$res 		= iconv('big5', 'big5//IGNORE', $res); 
		$xml 		= simplexml_load_string($res);
		$xml 		= json_decode(json_encode($xml),TRUE);
		if($xml && $xml['BODY']['DATA']['ERROR_ID']=='0000' && $txnkey==$xml['HEADER']['TXNKEY']){
			$batch_no 	= $xml['BODY']['DATA']['BATCH_NO'];
			return array( 'batch_no' => $batch_no,'txn_key' => $txnkey );
		}
		return false;
		
	}
	// //hsiang  串國泰回應API 邏輯
	public function check_batchno_to_cathay(){
		$this->CI->load->model('log/log_paymentexport_model');
		$now=time();
		$yesterday =$now-86400;
		$where				= array(
			"status"		=> 0,
			"batch_no >="   => '0',
			"created_at >="   => $yesterday,
		);
		
		$res		= $this->CI->log_paymentexport_model->order_by("created_at","desc")->get_many_by($where);
		$res =$this->object_array($res);//obj轉array
	    $log_data=array();
		if($res!==null){
			foreach($res as $key=>$value){
 
				 unset($value['cdata'],$value['admin_id'],$value['created_ip']); //刪除元素
				 $log_data[$key]=$value;
				 $this->CI->log_paymentexport_model->update($log_data[$key]['id'],['status'=>1]);

          	}
			 $this->get_batchno_to_cathay($log_data);
		}
		return false;
	 }
	 public function get_batchno_to_cathay($log_data){
		$batch_no=null;
		 //拿batch_no搓國泰API
		 foreach($log_data as $key=>$value){
 
			$batch_no= $value['batch_no'];
			$id= $value['id'];
			$type= $value['type'];
			$content= json_decode($value['content'],1);//需要對到國泰回傳的array
			$data=$this->get_batch_info($batch_no);
				if((!empty($data))){
					//開始確認資料
					$this->check_detail_data($batch_no,$id,$content,$type,$data);
				}
		
		   }

	 }


	 public function check_detail_data($batch_no,$id,$content,$type,$data){
		if(array_key_exists('Rtn_Code',$data)){//一個array 
			if($data['Rtn_Code']=='0000'){	//國泰回傳交易成功
			//判斷type
             switch($type){
					case "bankaccount": //金融帳號認證
					$this->get_onlyone_bankaccount_detail($batch_no,$id,$content,$data);
						break;
					case "target_loan": //借款的放款
					$this->get_onlyone_target_loan_detail($batch_no,$id,$content,$data);
						break;
					case "withdraw": //投資人 提領
					  $this->get_onlyone_withdraw_detail($batch_no,$id,$content,$data);
						break;
					}
			}
		}else{                   //多個array
		   foreach($data as $key=>$value){
			$content_data=$content[$key];
				if(array_key_exists('Rtn_Code',$value)){
					if($value['Rtn_Code']=='0000'){	//國泰回傳交易成功
						switch($type){
							case "bankaccount": //金融帳號認證 要姓名跟id
							$this->get_more_bankaccount_detail($batch_no,$id,$content_data,$value);
								break;
							case "target_loan": //借款的放款
							$this->get_more_target_loan_detail($batch_no,$id,$content_data,$value);
								break;
							case "withdraw": //投資人 提領
							$this->get_more_withdraw_detail($batch_no,$id,$content_data,$value);
								break;
							}
					}
			  }
		   }

		}


	 }


	 public function get_more_withdraw_detail($batch_no,$id,$content_data,$value){    //比對國泰跟payment結合
		$this->CI->load->model('transaction/withdraw_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/user_model');
		$this->CI->load->model('log/log_paymentexport_model');
		
		$bank_txtime=$value['TxDate'];
		$bank_txtime=date("Y-m-d",strtotime($bank_txtime));

		$bankamount= (int)$value['Amount'];//
		$bankamount= $bankamount-$value['Fee'];//國泰回的資料-手續費
		//需要的比對資料
		//
		$value['Beneficiary_BankCode']=substr( $value['Beneficiary_BankCode'],0, 3); //取前三碼

		$where				= array(
	      	"bank_id"   => $value['Beneficiary_BankCode'],
			"ABS(amount)"   => $bankamount,
			"DATE(tx_datetime)"   => $bank_txtime,
			"bank_acc like"		=> '%'.$value['Beneficiary_AccountNo']
		);
		
		 $payment_detail=$this->CI->payment_model->get_many_by($where);
		 $payment_detail =$this->object_array($payment_detail); //obj轉array
		 $payment_size=count($payment_detail);	

		  	if($payment_size==1){  
				$withdraw_detail=$this->CI->withdraw_model->get($content_data);
				$created_at=date('Y-m-d',$withdraw_detail->created_at);
			 	//抓sys_check=0   status=0//提領 - 待放款
			 if(((!empty($withdraw_detail)&&($withdraw_detail->sys_check==0))&&($withdraw_detail->status==0))){
				 //sys_check=0才開始檢查 並檢查一次
				//開始update db
				if( (abs($withdraw_detail->amount)==$bankamount+$value['Fee'])&&($created_at==$bank_txtime)){ //比對金額 時間
					$this->CI->withdraw_model->update($content_data,['sys_check'=>20]);//已驗證成功
					}else{
						$this->CI->withdraw_model->update($content_data,['sys_check'=>21]);//轉人工
	
					} 
			   }
			}
	 }

	 public function get_more_target_loan_detail($batch_no,$id,$content_data,$value){    //比對國泰跟payment結合
		$bank_txtime=$value['TxDate'];
		$bank_txtime=date("Y-m-d",strtotime($bank_txtime));
		$bankamount= (int)$value['Amount'];//
		$bankamount= $bankamount-$value['Fee'];//國泰回的資料-手續費
     
		//需要的比對資料
		//
		$value['Beneficiary_BankCode']=substr( $value['Beneficiary_BankCode'],0, 3); //取前三碼
		$where				= array(
	      	"bank_id"   => $value['Beneficiary_BankCode'],
			"ABS(amount)"   => $bankamount,
			"DATE(tx_datetime)"   => $bank_txtime,
			"bank_acc like"		=> '%'.$value['Beneficiary_AccountNo']
		);
		
		 $payment_detail=$this->CI->payment_model->get_many_by($where);
		 $payment_detail  =$this->object_array($payment_detail);//obj轉array
		 $payment_size=count($payment_detail);
	 
		
		 $this->CI->load->model('log/Log_targetschange_model');
		  if($payment_size==1){ //第一層邏輯 payment vs 國泰 資料比對    
				$target_detail=$this->CI->target_model->get($content_data);
				$target_detail = $this->object_array($target_detail);//obj轉array
				$target_detail_amout = $target_detail['loan_amount']-$target_detail['platform_fee']; 
				$created_at=date('Y-m-d',$target_detail['created_at']);
				$this->CI->load->model('log/Log_targetschange_model'); 
				//抓sub_status=0
				//status sub script loan 4 0 0 3
				if(((!empty($target_detail))&&($target_detail['status']==4))&&(($target_detail['sub_status']==0)&&($target_detail['script_status']==0))&&($target_detail['loan_status']==3)){ 
					if( ($target_detail_amout==$bankamount+$value['Fee'])&&($created_at==$bank_txtime)){ //比對金額 時間
					$this->CI->target_model->update($content_data,['sub_status'=>20]);//已驗證成功
					//加db log
		
						$param		= [
							'target_id'		=> $target_detail['id'],
							'sub_status'	=> 20
						];
						$this->CI->Log_targetschange_model->insert($param);
					}else{
							$this->CI->target_model->update($content_data,['sub_status'=>21]);//轉人工
						//加db log
						$param		= [
							'target_id'		=> $target_detail['id'],
							'sub_status'	=> 21
						];
						$this->CI->Log_targetschange_model->insert($param);

					}
						
				}
	 
			   }
	 
		 
	 }

	 public function get_more_bankaccount_detail($batch_no,$id,$content_data,$value){    //比對content跟data結合
		$bank_txtime=$value['TxDate'];
		$bank_txtime=date("Y-m-d",strtotime($bank_txtime));
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/user_model');
		$this->CI->load->model('log/Log_userbankaccount_model');
		$bankamount= (int)$value['Amount'];//國泰回的資料
		//需要的比對資料
        $value['Beneficiary_BankCode']=substr( $value['Beneficiary_BankCode'],0, 3); //取前三碼
		$where				= array(
	      	"bank_id"   => $value['Beneficiary_BankCode'],
			"ABS(amount)"   => $bankamount,
			"DATE(tx_datetime)"   => $bank_txtime,
			"bank_acc like"		=> '%'.$value['Beneficiary_AccountNo']
		);
		$payment_detail=$this->CI->payment_model->get_many_by($where);
	   $payment_size=count($payment_detail);
	   if($payment_size==1){ //第一層邏輯 payment vs 國泰 資料比對    

		 	$bankaccount_detail=$this->CI->user_bankaccount_model->get($content_data);
		 	$user_id=$bankaccount_detail->user_id;
		 	$user_detail=$this->CI->user_model->get($user_id);
		//開始比對資料
		if((!empty($bankaccount_detail)&&$bankaccount_detail->sys_check==0)&&($bankaccount_detail->verify==3)){//verify=3檢查已發送
				if($user_detail->name==$value['Beneficiary_Name'] ){ //比對姓名	
				  //開始update db
					$this->CI->user_bankaccount_model->update($content_data,array("sys_check"=>20));//已驗證成功
						//加db log
						$param		= [
							'user_id'		=> $bankaccount_detail->user_id,
							'sys_check'	=> 20
						];
						$this->CI->Log_userbankaccount_model->insert($param);
					}else {
						$this->CI->user_bankaccount_model->update($content_data,array("sys_check"=>21));//轉人工
						//加db log
						$param		= [
							'user_id'		=> $bankaccount_detail->user_id,
							'sys_check'	=> 21
						];
						$this->CI->Log_userbankaccount_model->insert($param);
					}
			 } 
	 	  }
	 }
 
 
	 
	 public function get_onlyone_bankaccount_detail($batch_no,$id,$content,$data){    //比對content跟data結合
		$content=$content['0'];
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/user_model');
		$this->CI->load->model('log/Log_userbankaccount_model'); 
		$bank_txtime=$data['TxDate'];
		$bank_txtime=date("Y-m-d",strtotime($bank_txtime));
		$bankamount= (int)$data['Amount'];//國泰回的資料
		//需要的比對資料
        $data['Beneficiary_BankCode']=substr( $data['Beneficiary_BankCode'],0, 3); //取前三碼
		$where				= array(
	      	"bank_id"   => $data['Beneficiary_BankCode'],
			"ABS(amount)"   => $bankamount,
			"DATE(tx_datetime)"   => $bank_txtime,
			"bank_acc like"		=> '%'.$data['Beneficiary_AccountNo']
		);
		$payment_detail=$this->CI->payment_model->get_many_by($where);
	   $payment_size=count($payment_detail);
	   if($payment_size==1){ //第一層邏輯 payment vs 國泰 資料比對    
		 	$bankaccount_detail=$this->CI->user_bankaccount_model->get($content);
		 	$user_id=$bankaccount_detail->user_id;
		 	$user_detail=$this->CI->user_model->get($user_id);
		//開始比對資料
		if((!empty($bankaccount_detail)&&($bankaccount_detail->sys_check==0))&&(($bankaccount_detail->verify==3) )){//verify=3檢查已發送
                	//開始update db
				if($user_detail->name==$data['Beneficiary_Name'] ){ //比對姓名	
						//開始update db
					 $this->CI->user_bankaccount_model->update($content,array("sys_check"=>20));//已驗證成功
							  //加db log
							  $param		= [
								  'user_id'		=> $bankaccount_detail->user_id,
								  'sys_check'	=> 20
							  ];
							  $this->CI->Log_userbankaccount_model->insert($param);
						  }else {
							  $this->CI->user_bankaccount_model->update($content,array("sys_check"=>21));//轉人工
							  //加db log
							  $param		= [
								  'user_id'		=> $bankaccount_detail->user_id,
								  'sys_check'	=> 21
							  ];
							  $this->CI->Log_userbankaccount_model->insert($param);
						  }
			 }	
	 	  }
	 }
 


	 public function get_onlyone_withdraw_detail($batch_no,$id,$content,$data){    //比對content跟data結合
		$content=$content['0'];
		$this->CI->load->model('transaction/withdraw_model');
		$this->CI->load->model('user/user_bankaccount_model');
		$this->CI->load->model('user/user_model');
		$this->CI->load->model('log/log_paymentexport_model');
		$bank_txtime=$data['TxDate'];
		$bank_txtime=date("Y-m-d",strtotime($bank_txtime));
		$bankamount= (int)$data['Amount'];//國泰回的資料
		$bankamount= $bankamount-$data['Fee'];//國泰回的資料-手續費
		$data['Beneficiary_BankCode']=substr( $data['Beneficiary_BankCode'],0, 3); //取前三碼
		//需要的比對資料
		//
		$where				= array(
	      	"bank_id"   => $data['Beneficiary_BankCode'],
			"ABS(amount)"   => $bankamount,
			"DATE(tx_datetime)"   => $bank_txtime,
			"bank_acc like"		=> '%'.$data['Beneficiary_AccountNo']
		);

	   $payment_detail=$this->CI->payment_model->get_many_by($where);
	   $payment_size=count($payment_detail);
	   if($payment_size==1){ //第一層邏輯 payment vs 國泰 資料比對    
		$withdraw_detail=$this->CI->withdraw_model->get($content);
        $created_at=date('Y-m-d',$withdraw_detail->created_at);
		 //抓sys_check=0   status=0//提領 - 待放款
		 if(((!empty($withdraw_detail)&&($withdraw_detail->sys_check==0))&&($withdraw_detail->status==0))){
			 //sys_check=0才開始檢查 並檢查一次
			//開始update db
			if( (abs($withdraw_detail->amount)==$bankamount+$data['Fee'])&&($created_at==$bank_txtime)){ //比對金額 時間
				$this->CI->withdraw_model->update($content,['sys_check'=>20]);//已驗證成功
				}else{
					$this->CI->withdraw_model->update($content,['sys_check'=>21]);//轉人工

				} 
		   }
	   	} 
	 }
 
	 public function get_onlyone_target_loan_detail($batch_no,$id,$content,$data){    //比對content跟data結合
		$content=$content['0'];
		$bank_txtime=$data['TxDate'];
		$bank_txtime=date("Y-m-d",strtotime($bank_txtime));
		$bankamount= (int)$data['Amount'];//國泰回的資料
		$bankamount= $bankamount-$data['Fee'];//國泰回的資料-手續費

		$data['Beneficiary_BankCode']=substr( $data['Beneficiary_BankCode'],0, 3); //取前三碼
		//需要的比對資料
		//
		$where				= array(
	      	"bank_id"   => $data['Beneficiary_BankCode'],
			"ABS(amount)"   => $bankamount,
			"DATE(tx_datetime)"   => $bank_txtime,
			"bank_acc like"		=> '%'.$data['Beneficiary_AccountNo']
		);
		
	   $payment_detail=$this->CI->payment_model->get_many_by($where);
	   $payment_detail =  $this->object_array($payment_detail);//obj轉array
 
	   $payment_size=count($payment_detail);
 
	   if($payment_size==1){ //第一層邏輯 payment vs 國泰 資料比對    
	 
		$target_detail=$this->CI->target_model->get($content);
		$target_detail = $this->object_array($target_detail);//obj轉array
		$target_detail_amout = $target_detail['loan_amount']-$target_detail['platform_fee']; 
		$created_at=date('Y-m-d',$target_detail['created_at']);
		$this->CI->load->model('log/Log_targetschange_model'); 
		 //抓sub_status=0
		 //status sub script loan 4 0 0 3
		 if((!empty($target_detail)&&($target_detail['status']==4))&&(($target_detail['sub_status']==0)&&($target_detail['script_status']==0))&&($target_detail['loan_status']==3)){ 

			if( ($target_detail_amout==$bankamount+$data['Fee'])&&($created_at==$bank_txtime)){ //比對金額 時間
			 $this->CI->target_model->update($content,['sub_status'=>20]);//已驗證成功
			 //加db log
 
				$param		= [
					'target_id'		=> $target_detail['id'],
					'sub_status'	=> 20
				];
				$this->CI->Log_targetschange_model->insert($param);
			}else{
					$this->CI->target_model->update($content,['sub_status'=>21]);//轉人工
				//加db log
				$param		= [
					'target_id'		=> $target_detail['id'],
					'sub_status'	=> 21
				];
				$this->CI->Log_targetschange_model->insert($param);

			}
				
		   }
 
	   	}
	 }


 

	//取得資訊
	public function get_batch_info($batch_no=""){
		if(empty($date)){
			$date = date("Ymd");
		}
		
		$from_date 	= date("Ymd",strtotime($date));
		$to_date 	= date("Ymd",strtotime($date));
	
		$xml_file 	= 
'<?xml version="1.0" encoding="big5"?>
<MYB2B>
	<HEADER>
		<SERVICE>PAYSVC</SERVICE>
		<ACTION>BTRS03</ACTION>
	</HEADER>
	<BODY>
		<IDNO>'.CATHAY_CUST_ID.'</IDNO>
		<PASSWORD>'.CATHAY_CUST_PASSWORD.'</PASSWORD>
		<USERNO>'.CATHAY_CUST_NICKNAME.'</USERNO>
		<ACNO>'.CATHAY_CUST_ACCNO.'</ACNO>
		<FromDate>'.$from_date.'</FromDate>
		<ToDate>'.$to_date.'</ToDate>
		<FromTime></FromTime>
		<ToTime></ToTime>
		<BatchNo>'.$batch_no.'</BatchNo>
		<XML>Y</XML>
		<ErrData>N</ErrData>
		<FileType>BRMT/BRMT/0</FileType>
	</BODY>
</MYB2B>'; 
		$xml_file 	= iconv('UTF-8', 'BIG-5', $xml_file);
		$key 		= iconv('UTF-8', 'BIG-5', CATHAY_AES_KEY);
		$rs 		= iconv('UTF-8', 'BIG-5',CATHAY_CUST_ID.'            '.$this->strToHex($this->encrypt($xml_file,$key)));
		$res 		= curl_get(CATHAY_AP2APINFO_API_URL,$rs,["Content-type:text/xml"]);
		$res 		= iconv('big5', 'big5//IGNORE', $res); 
		$xml 		= simplexml_load_string($res);
		$xml 		= json_decode(json_encode($xml),TRUE);

		if($xml && $xml['HEADER']['RETURN_CODE']=='0000'){
			$data 	= $xml['BODY']['DATAS']['DATA'];
			return $data;
		}
		return array();
	}
	
    private function encrypt($src, $key, $size = 128, $mode = 'ECB') {
        if (is_null($key)) {
            return false;
        }

        $method 	= 'AES-' . $size . '-' . $mode;
        $ivSize 	= openssl_cipher_iv_length($method);
        $iv 		= openssl_random_pseudo_bytes($ivSize);
        $encrypted 	= openssl_encrypt($src, $method, mb_convert_encoding($key, 'big5', 'utf-8'), OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }
	private  function object_array($array) {  
		if(is_object($array)) {  
			$array = (array)$array;  
		 } if(is_array($array)) {  
			 foreach($array as $key=>$value) {  
				 $array[$key] = $this->object_array($value);  
				 }  
		 }  
		 return $array;  
	}
	private function strToHex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	
	private function check_len($data = array()){
		$data_len = array(
			"code"			=> 1,
			"upload_date"	=> 8,
			"entering_date"	=> 8,
			"t_type"		=> 3,
			"t_code"		=> 10,
			"bankcode_from"	=> 7,
			"bankacc_from"	=> 16,
			"tax_from"		=> 10,
			"name_from"		=> 70,
			"TWD"			=> 3,
			"plus"			=> 1,
			"amount"		=> 14,//靠左補0
			"bankcode_to"	=> 7,
			"bankacc_to"	=> 16,
			"tax_to"		=> 10,
			"name_to"		=> 70,
			"alert_to"		=> 1,
			"email_to"		=> 50,
			"fee_type"		=> 2,
			"invoice_num"	=> 4,
			"remark"		=> 50,
		);
		
		foreach($data_len as $key => $value){
			$param = isset($data[$key])?$data[$key]:"";
			if(in_array($key,array("name_from","name_to","remark"))){
				$len = mb_strlen($param)*2;
				$len = $value>$len?intval($value-$len):0;
				if($len){
					for($i=1;$i<=$len;$i++){
						$data[$key] = $data[$key]." ";
					}
				}
			}else if($key == "amount"){
				$data[$key] = intval($data[$key])."00";
				$len = strlen($data[$key]);
				$len = $value>$len?intval($value-$len):0;
				if($len){
					for($i=1;$i<=$len;$i++){
						$data[$key] = "0".$data[$key];
					}
				}
			}else if($key == "invoice_num"){
				$len = strlen($data[$key]);
				$len = $value>$len?intval($value-$len):0;
				if($len){
					for($i=1;$i<=$len;$i++){
						$data[$key] = "0".$data[$key];
					}
				}
			}else{
				$len = strlen($param);
				$len = $value>$len?intval($value-$len):0;
				if($len){
					for($i=1;$i<=$len;$i++){
						$data[$key] = $data[$key]." ";
					}
				}
			}
		}
		return $data;
	}
	
	public function script_daily_tax(){
		$this->CI->load->library('Financial_lib');
		$this->CI->load->library('Ezpay_lib');
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/receipt_model');
		$date		= date("Y-m-d",strtotime(get_entering_date().' -1 day'));
		$script  	= 9;
		$count 		= 0;
		$source		= array(
			SOURCE_FEES,
			SOURCE_SUBLOAN_FEE,
			SOURCE_TRANSFER_FEE,
			SOURCE_PREPAYMENT_DAMAGE,
			SOURCE_DAMAGE,
			SOURCE_PREPAYMENT_ALLOWANCE,
		);


		$where	= array(
			"entering_date"	=> $date,
			"source"		=> $source,
			"status <>"		=> 0
		);
		$data 		= $this->CI->transaction_model->order_by("user_from","ASC")->get_many_by($where);
		if($data && !empty($data)){
			$tax_list 	= array();
			$prepayment = array();
			foreach($data as $key => $value){
				if($value->source!=SOURCE_PREPAYMENT_ALLOWANCE){
					if(!isset($tax_list[$value->user_from])){
						$tax_list[$value->user_from] = 0;
					}
					$tax_list[$value->user_from] += $value->amount;
					if($value->source == SOURCE_PREPAYMENT_DAMAGE){
						$prepayment[$value->target_id] = $value->user_from;
					}
				}
			}
			if(!empty($prepayment)){
				foreach($data as $key => $value){
					if($value->source==SOURCE_PREPAYMENT_ALLOWANCE){
						$tax_list[$prepayment[$value->target_id]] -= $value->amount;
					}
				}
			}
			
			if(!empty($tax_list)){
				foreach($tax_list as $user_id => $amount){
					$today 		= $this->CI->receipt_model->get_by(array(
						"entering_date"	=> $date,
						"user_id"		=> $user_id,
					));
					
					if(!$today){
						$tax 		= $this->CI->financial_lib->get_tax_amount($amount);
						$tax_info 	= $this->CI->ezpay_lib->send($user_id,$amount,$tax);
						if($tax_info){
							$this->CI->receipt_model->insert(array(
								"entering_date"	=> $date,
								"user_id"		=> $user_id,
								"amount"		=> $tax_info['amount'],
								"tax_amount"	=> $tax_info['tax_amount'],
								"tax_id"		=> $tax_info['tax_id'],
								"order_no"		=> $tax_info['order_no'],
							));
							$count++;
						}
					}
				}
			}
		}
		return $count;
	}
}
