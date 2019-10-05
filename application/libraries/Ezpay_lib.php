<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ezpay_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('log/log_ezpay_model');
    }
	
	public function send($user_id=0,$amount=0,$tax=0){
		if($user_id && $amount>0 && $amount > $tax ){
			$user_info = $this->CI->user_model->get($user_id);
			if($user_info && $user_info->email){
				$MerchantID = EZPAY_ID;
				$hashkey 	= EZPAY_KEY;
				$hashiv		= EZPAY_IV;
				$order_no	= $this->get_order_no();
				if(is_development()){
					$url 		= 'https://cinv.ezpay.com.tw/Api/invoice_issue';		
				}else{
					$url 		= 'https://inv.ezpay.com.tw/Api/invoice_issue';
				}

				$data		= array(
					'RespondType'		=> 'JSON',
					'Version'			=> '1.4',
					'TimeStamp'			=> time(),
					'MerchantOrderNo'	=> $order_no,
					'Status'			=> '1',
					'Category'			=> 'B2C',
					'BuyerName'			=> $user_info->name,//買受人名稱
					'BuyerEmail'		=> $user_info->email,//買受人電子信箱
					'CarrierType'		=> '2',
					'CarrierNum'		=> rawurlencode($user_info->phone),//載具編號
					'PrintFlag'			=> 'N',//索取紙本發票
					'TaxType'			=> '1',//課稅別
					'TaxRate'			=> TAX_RATE,//稅率
					'Amt'				=> intval($amount-$tax),//銷售額合計
					'TaxAmt'			=> $tax,//稅額
					'TotalAmt'			=> intval($amount),//發票金額
					'ItemName'			=> '平台服務費',//商品名稱
					'ItemCount'			=> '1',//商品數量
					'ItemUnit'			=> '筆',//商品單位
					'ItemPrice'			=> intval($amount),//商品單價
					'ItemAmt'			=> intval($amount),//商品小計
				);
				if (strlen($user_info->id_number) === 8) {
					$data['Category'] = 'B2B';
					$data['CarrierType'] = null;
					$data['PrintFlag'] = 'Y';
					$data['BuyerUBN'] = $user_info->id_number;
					$data['CarrierNum'] = null;
				}
				$post_data_str = http_build_query($data);

				if (phpversion() > 7) {
					$post_data = trim(bin2hex(openssl_encrypt($this->addpadding($post_data_str),'AES-256-CBC', $hashkey, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $hashiv)));
				}else{
					$post_data = trim(bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $hashkey,$this->addpadding($post_data_str), MCRYPT_MODE_CBC, $hashiv)));
				}
				
				$transaction_data_array = array(
					'MerchantID_' 	=> $MerchantID,
					'PostData_' 	=> $post_data
				);
				

				$rs = curl_get($url,$transaction_data_array);
				$rs = json_decode($rs,true);
				if($rs && $rs["Status"]=='SUCCESS'){
					$result = json_decode($rs['Result'],true);
					if($result && $result['MerchantID']==$MerchantID && $result['MerchantOrderNo']==$order_no){
						$tax_id = $result['InvoiceNumber']; 
						return array(
							"user_id"		=> $user_id,
							"tax_id"		=> $tax_id,
							"order_no"		=> $order_no,
							"amount"		=> $amount,
							"tax_amount"	=> $tax,
						);
					}
				}
				
				$this->CI->log_ezpay_model->insert(array(
					"order_no"	=> $order_no,
					"user_id"	=> $user_id,
					"request"	=> json_encode($transaction_data_array),
					"response"	=> json_encode($rs),
				));
			}
		}
		return false;
	}
	
	private function get_order_no(){
		return 'influx'.round(microtime(true) * 100).rand(0, 9).rand(0, 9);
	}
	
	private function addpadding($string, $blocksize = 32){
		$len = strlen($string);
		$pad = $blocksize - ($len % $blocksize);
		$string .= str_repeat(chr($pad), $pad);
		return $string;
	}
}
