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
				}
			}
		}
		$this->CI->payment_model->update($value->id,array("status"=>3));
		return false;
	}
	
	//出帳處理
	private function expense($value){
		$this->CI->payment_model->update($value->id,array("status"=>0));
		return false;
	}
	
	public function verify_bankaccount_txt($admin_id=0){
		
		$check_len = array(
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
				
					foreach($check_len as $key => $value){
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
}
