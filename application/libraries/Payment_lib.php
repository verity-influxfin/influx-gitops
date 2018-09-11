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
				
				if(isset($xml["TXDETAIL"]['BACCNO']) && !empty($xml["TXDETAIL"]['BACCNO'])){
					$txdetail = $xml["TXDETAIL"];
					$xml["TXDETAIL"] = array(0=>$txdetail);
				}
				
				foreach($xml["TXDETAIL"] as $key => $value){
					
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
			$bank_code 	= $bank_account = "";
			$bank 		= bankaccount_substr($value->bank_acc);
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
				"investor"		=> $investor,
				"bank_code"		=> $bank_code,
				"bank_account"	=> $bank_account,
				"status"		=> 1,
				"verify"		=> 1
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
		$word_list = $this->CI->difficult_word_model->get_name_list();
		$where				= array(
			"status"		=> 1,
			"verify"		=> 2
		);
		$bankaccounts 	= $this->CI->user_bankaccount_model->get_many_by($where);
		$content 		= "";
		$ids 			= array();
		if($bankaccounts){
			
			foreach($bankaccounts as $key => $value){
				$user_info = $this->CI->user_model->get($value->user_id);
				if($user_info && $user_info->name){
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

					$this->CI->user_bankaccount_model->update($value->id,array("verify"=>3,"verify_at"=>time()));
					$ids[] = $value->id;
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
						"tax_to"		=> "",
						"name_to"		=> nf_to_wf($user_info->name),
						"alert_to"		=> "0",
						"email_to"		=> "",
						"fee_type"		=> "15",
						"invoice_num"	=> "",
						"remark"		=> nf_to_wf("金融帳號驗證"),
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
			$this->CI->load->model('log/log_paymentexport_model');
			$this->CI->log_paymentexport_model->insert(array(
				"type"		=> "bankaccount",
				"content"	=> json_encode($ids),
				"admin_id"	=> $admin_id
			));
		}
		return $content;
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
				$this->CI->load->model('log/log_paymentexport_model');
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "target_loan",
					"content"	=> json_encode($ids),
					"admin_id"	=> $admin_id
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
				$this->CI->load->model('log/log_paymentexport_model');
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "withdraw",
					"content"	=> json_encode($ids),
					"admin_id"	=> $admin_id
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
						$this->CI->payment_model->update($value->id,array("status"=>4));
						$amount = intval($value->amount);
						$bank 	= bankaccount_substr($value->bank_acc);
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
				$this->CI->load->model('log/log_paymentexport_model');
				$this->CI->log_paymentexport_model->insert(array(
					"type"		=> "unknown_refund",
					"content"	=> json_encode($ids),
					"admin_id"	=> $admin_id
				));
				
				return $content;
			}
		}
		return false;
	}
	
	//上傳檔案
	public function upload_file($content="",$fxml=""){
		if(is_development()){
			return true;
		}else{
			$url 	= 'https://www.globalmyb2b.com/GEBANK/AP2AP/MyB2B_AP2AP_Rev.aspx';
		}
		
		$txnkey = date("Ymd").rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		
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
			<CONTENT FileType="BRMT/BRMT/0" DrAcno="15035006475" RemitType="'.$fxml.'">
				<![CDATA['.$content.']]>
			</CONTENT>
		</DATA>
	</BODY>
</MYB2B>';
		$xml_file 	= iconv('UTF-8', 'BIG-5', $xml_file);
		$key 		= iconv('UTF-8', 'BIG-5', CATHAY_AES_KEY);
		$rs 		= iconv('UTF-8', 'BIG-5',CATHAY_CUST_ID.'            '.$this->strToHex($this->encrypt($xml_file,$key)));
		$res 		= curl_get($url,$rs,["Content-type:text/xml"]);
		$res 		= iconv('big5', 'big5//IGNORE', $res); 
		$xml 		= simplexml_load_string($res);
		$xml 		= json_decode(json_encode($xml),TRUE);
		if($xml && $xml['BODY']['DATA']['ERROR_ID']=='0000' && $txnkey==$xml['HEADER']['TXNKEY']){
			$batch_no 	= $xml['BODY']['DATA']['BATCH_NO'];
			return $batch_no;
		}
		return false;
		
	}
	//取得資訊
	public function get_batch_info($batch_no=""){
		if(is_development()){
			return array();
		}else{
			$url 	= 'https://www.globalmyb2b.com/GEBANK/AP2AP/MyB2B_AP2AP_QueryRMT.aspx';
			
		}
		$date		= date("Ymd");
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
		<FromDate>'.$date.'</FromDate>
		<ToDate>'.$date.'</ToDate>
		<FromTime>000000</FromTime>
		<ToTime>235959</ToTime>
		<BatchNo>'.$batch_no.'</BatchNo>
		<XML>Y</XML>4
		<ErrData>N</ErrData>
		<FileType>BRMT/BRMT/0</FileType>
	</BODY>
</MYB2B>'; 
		$xml_file 	= iconv('UTF-8', 'BIG-5', $xml_file);
		$key 		= iconv('UTF-8', 'BIG-5', CATHAY_AES_KEY);
		$rs 		= iconv('UTF-8', 'BIG-5',CATHAY_CUST_ID.'            '.$this->strToHex($this->encrypt($xml_file,$key)));
		$res 		= curl_get($url,$rs,["Content-type:text/xml"]);
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
}
