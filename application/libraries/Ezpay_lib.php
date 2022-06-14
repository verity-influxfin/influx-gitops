<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ezpay_lib
{
    public $merchant_id;
    public $hash_key;
    public $hash_iv;
    public $order_no_prefix;
    public $tax_amt;
    public $total_amt;
    public $item_name;
    public $item_count;
    public $item_unit;
    public $item_price;
    private $item_amt;
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('log/log_ezpay_model');

        $this->merchant_id = EZPAY_ID;
        $this->hash_key = EZPAY_KEY;
        $this->hash_iv = EZPAY_IV;
        $this->order_no_prefix = 'influx';
        $this->tax_amt = 0;
        $this->total_amt = 0;
        $this->item_name = '平台服務費';
        $this->item_count = 1;
        $this->item_unit = '筆';
        $this->item_price = 0;
        $this->item_amt = 0;
    }

    public function send($user_id, $from_leasing = FALSE)
    {
        $this->tax_amt = (int) $this->tax_amt;
        $this->total_amt = (int) $this->total_amt;

        if ($this->total_amt > 0 && $this->total_amt > $this->tax_amt)
        {
            if ($this->get_item_info() !== TRUE)
            {
                return FALSE;
            }
            if ($user_id === 0 && $from_leasing === TRUE)
            { // 普匯租賃開發票
                $user_info = new stdClass();
                $user_info->name = COMPANY_NAME;
                $user_info->id_number = COMPANY_ID_NUMBER;
                $user_info->email = COMPANY_SERVICE_EMAIL;
                $user_info->phone = '';
            }
            else
            {
                $user_info = $this->CI->user_model->get($user_id);
            }

            if ($from_leasing === TRUE)
            {
                $this->merchant_id = EZPAY_ID_LEASING;
                $this->hash_key = EZPAY_KEY_LEASING;
                $this->hash_iv = EZPAY_IV_LEASING;
            }

            if ($user_info && (($user_info->email && $from_leasing !== TRUE) || $from_leasing === TRUE))
            {
				$MerchantID = $this->merchant_id;
				$hashkey 	= $this->hash_key;
				$hashiv		= $this->hash_iv;
				$order_no	= $this->get_order_no($this->order_no_prefix);
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
					'Amt'				=> $this->total_amt - $this->tax_amt,//銷售額合計
					'TaxAmt'			=> $this->tax_amt,//稅額
					'TotalAmt'			=> $this->total_amt,//發票金額
					'ItemName'			=> $this->item_name,//商品名稱
					'ItemCount'			=> $this->item_count,//商品數量
					'ItemUnit'			=> $this->item_unit,//商品單位
					'ItemPrice'			=> $this->item_price,//商品單價
					'ItemAmt'			=> $this->item_amt,//商品小計
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
							"amount"		=> $this->total_amt,
							"tax_amount"	=> $this->tax_amt,
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

    // 取得商店自訂訂單編號(20位)
    private function get_order_no($prefix)
    {
        return str_pad(($prefix . round(microtime(TRUE) * 100)), 20, rand(0, 9));
    }

    private function get_item_info(): bool
    {
        $result = FALSE;

        // 多項商品
        if (is_array($this->item_name) && is_array($this->item_count) && is_array($this->item_unit) && is_array($this->item_price))
        {
            if (count($this->item_name) == count($this->item_count) &&
                count($this->item_count) == count($this->item_unit) &&
                count($this->item_unit) == count($this->item_price) &&
                count($this->item_price) == count($this->item_name)
            )
            {
                $this->item_name = implode('|', $this->item_name); // 商品名稱
                $this->item_unit = implode('|', $this->item_unit); // 商品單位

                $this->intval_number_ary($this->item_count); // 商品數量
                $this->intval_number_ary($this->item_price); // 商品單價

                $this->item_amt = array_map(function ($x, $y) { // 商品小計
                    return $x * $y;
                }, $this->item_count, $this->item_price);

                $this->item_count = implode('|', $this->item_count);
                $this->item_price = implode('|', $this->item_price);
                $this->item_amt = implode('|', $this->item_amt);

                $result = TRUE;
            }
        }
        // 單項商品
        elseif (is_string($this->item_name) && is_numeric($this->item_count) && is_string($this->item_unit) && is_numeric($this->item_price))
        {
            $this->item_count = (int) $this->item_count;
            $this->item_price = (int) $this->item_price;
            $this->item_amt = $this->item_price;
            $result = TRUE;
        }
        return $result;
    }

    private function intval_number_ary(array &$number_list): void
    {
        $number_list = array_map(function ($element) {
            return (int) $element;
        }, $number_list);
    }

	private function addpadding($string, $blocksize = 32){
		$len = strlen($string);
		$pad = $blocksize - ($len % $blocksize);
		$string .= str_repeat(chr($pad), $pad);
		return $string;
	}
}
