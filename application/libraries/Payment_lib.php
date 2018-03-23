<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/payment_model');
    }
	
	public function insert_cathay_info($date=""){
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
					if(strlen($value['MEMO1'])==14 && substr($value['MEMO1'],0,4)==CATHAY_VIRTUAL_CODE){
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
	
	
	public function handle_payment($num=20){ 
		

		//$this->CI->payment_model->limit($num)->update_by(array("status"=>0),array("status"=>2));
		$payments = $this->CI->payment_model->get_many_by(array("status"=>2));
		if($payments && !empty($payments)){
			foreach($payments as $key => $value){
				dump($value);
			}
		}
		return false;
	}

}
