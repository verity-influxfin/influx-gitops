<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ezpey_lib{
	
	public function __construct()
    {
        $this->CI = &get_instance();
    }
	
	public function test(){
		$MerchantID = '31109025';
		$hashkey 	= 'FmimazLJGdxHsFcoacjiekM1JGcxYuTf';
		$hashiv		= 'C5DmFnFvj79MpXdG';
		$url 		= 'https://cinv.ezpay.com.tw/Api/invoice_issue';
		$data		= array(
			'RespondType'		=> 'JSON',
			'Version'			=> '1.4',
			'TimeStamp'			=> time(),
			'MerchantOrderNo'	=> time().'test'.rand(0, 9),
			'Status'			=> '1',
			'Category'			=> 'B2C',
			'BuyerName'			=> '謝承翰',//買受人名稱
			'BuyerEmail'		=> 'news@influxfin.com',//買受人電子信箱
			'CarrierType'		=> '2',
			'CarrierNum'		=> rawurlencode('news@influxfin.com'),//載具編號
			'PrintFlag'			=> 'N',//索取紙本發票
			'TaxType'			=> '1',//課稅別
			'TaxRate'			=> 5,//稅率
			'Amt'				=> 50,//銷售額合計
			'TaxAmt'			=> 2,//稅額
			'TotalAmt'			=> 52,//發票金額
			'ItemName'			=> '平台服務費',//商品名稱
			'ItemCount'			=> '1',//商品數量
			'ItemUnit'			=> '案',//商品單位
			'ItemPrice'			=> '50',//商品單價
			'ItemAmt'			=> '50',//商品小計
		);  
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
		
		dump(json_decode($rs,true));
	}
	
	private function addpadding($string, $blocksize = 32){
		$len = strlen($string);
		$pad = $blocksize - ($len % $blocksize);
		$string .= str_repeat(chr($pad), $pad);
		return $string;
	}
}
