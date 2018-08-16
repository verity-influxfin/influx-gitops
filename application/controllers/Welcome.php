<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class Welcome extends CI_Controller {


	function schedule(){
		$this->load->library('Financial_lib');
		$amount 	= intval($_GET['amount']);//額度
		$rate		= $_GET['rate'];
		$instalment = intval($_GET['instalment']);//期數
		$date 		= $_GET['date'];//起始日
		$repayment	= isset($_GET['repayment'])?intval($_GET['repayment']):1;//起始日
		$schedule 	= $this->financial_lib->get_amortization_schedule($amount,$instalment,$rate,$date,$repayment); 
		echo '<span>本金：'		.$schedule['amount']		.'</span><br>';
		echo '<span>年利率：'		.$schedule['rate']		.'%</span><br>';
		echo '<span>每期應繳：'	.$schedule['total_payment']	.'</span><br>';
		echo '<span>Day0：'		.$schedule['date']			.'</span><br>';
		echo '<span>有無需考慮閏年：'.$schedule['leap_year']	.'</span><br>';
		echo '<span>期數：'.$schedule['instalment']			.'</span><br>';
		echo '<span>XIRR：'.($schedule['XIRR'])			.'%</span><br>';
		echo '<table style="width:50%;text-align:center;"><tr><th>期數</th><th>還款日</th><th>日數</th><th>期初本金餘額</th><th>還款本金</th><th>還款利息</th><th>還款合計</th></tr>';

		foreach($schedule['schedule'] as $key =>$value){
			echo "<tr>";
			echo "<td>".$key."</td>";
			echo "<td>".$value['repayment_date']."</td>";
			echo "<td>".$value['days']."</td>";
			echo "<td>".$value['remaining_principal']."</td>";
			echo "<td>".$value['principal']."</td>";
			echo "<td>".$value['interest']."</td>";
			echo "<td>".$value['total_payment']."</td>";
			echo "</tr>";
		}
		
		echo "<td>合計</td><td></td><td></td><td></td>";
		echo "<td>".$schedule['total']['principal']."</td>"; 
		echo "<td>".$schedule['total']['interest']."</td>";
		echo "<td>".$schedule['total']['total_payment']."</td>";
		echo "</tr>";
		echo "</table>";
		
	}

	public function test(){
		
		$url 	= "http://218.32.90.71/GEBANK/AP2AP/MyB2B_AP2AP_Rev.aspx";
		$tano = date("Ymd").rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$a 			= '0        20180816SPU          0130017001035022704    68566881  普匯金融科技股份有限公司                                              TWD+000000000001000132033203506027355              郭基永                                                                0                                                  150000金融帳號驗證                                      
0        20180816SPU          0130017001035022704    68566881  普匯金融科技股份有限公司                                              TWD+00000000000100812002328881003050014            謝承翰                                                                0                                                  150000金融帳號驗證                                      
0        20180816SPU          0130017001035022704    68566881  普匯金融科技股份有限公司                                              TWD+00000000000100812002328881003050014            謝承瀚                                                                0                                                  150000金融帳號驗證                                      ';
		$b = 
		'<?xml version="1.0" encoding="big5"?>
<MYB2B>
	<HEADER>
		<SERVICE>PAYSVC</SERVICE>
		<ACTION>BTRS01</ACTION>
		<TXNKEY>'.$tano.'</TXNKEY>
	</HEADER>
	<BODY>
		<LOGON>
			<IDNO>68566881</IDNO>
			<PASSWORD>aaa123</PASSWORD>
			<USERNO>test001</USERNO>
			<BRANCH>001</BRANCH>
		</LOGON>
		<DATA>
			<CONTENT FileType="BTRS/BRMT/0" DrAcno="">
				<![CDATA['.$a.']]>
			</CONTENT>
		</DATA>
	</BODY>
</MYB2B>';
echo $url."<pre>";
var_dump(htmlspecialchars($b)); 
		$b 		= iconv('UTF-8', 'BIG-5', $b);
		$key 	= iconv('UTF-8', 'BIG-5', 'abcdefgh68566881');//influx6856688100
		$rs 	= $this->encrypt($b,$key);
		$rs 	= $this->strToHex($rs);
		$rs 	= "68566881            ".$rs;
		$rs 	= iconv('UTF-8', 'BIG-5', $rs);
		//dump(htmlspecialchars(iconv( 'BIG-5','UTF-8', $rs)));
		$res 	= curl_get($url,$rs,["Content-type:text/xml"]);
		$res 	= iconv('big5', 'big5//IGNORE', $res); 
		$xml 	= simplexml_load_string($res);
		$xml 	= json_decode(json_encode($xml),TRUE);
		echo "<br>";
		var_dump($xml);
		//TXNKEY 2018081578439
		//BATCH_NO 00033602
		
	}

	public function test2(){
		$url 	= "https://www.globalmyb2b.com/GEBANK/AP2AP/MyB2B_AP2AP_QueryRMT.aspx";
		$b = 
		'<?xml version="1.0" encoding="big5"?>
<MYB2B>
	<HEADER>
		<SERVICE>PAYSVC</SERVICE>
		<ACTION>BTRS03</ACTION>
	</HEADER>
	<BODY>
		<IDNO>68566881</IDNO>
		<PASSWORD>fable1234</PASSWORD>
		<USERNO>IT0001</USERNO>
		<ACNO>015035006475</ACNO>
		<FromDate>20180810</FromDate>
		<ToDate>20180816</ToDate>
		<FromTime></FromTime>
		<ToTime></ToTime>
		<BatchNo>07126392</BatchNo>
		<RemitType></RemitType>
		<XML>Y</XML>
		<ErrData>Y</ErrData>
		<FileType>BMUL/BRMT/0</FileType>
	</BODY>
</MYB2B>';
echo $url."<pre>";
var_dump(htmlspecialchars($b));
		$b 		= iconv('UTF-8', 'BIG-5', $b);
		$key 	= iconv('UTF-8', 'BIG-5', 'influx6856688100');//influx6856688100
		$rs 	= $this->encrypt($b,$key);
		$rs 	= $this->strToHex($rs);
		$rs 	= "68566881            ".$rs;
		$rs 	= iconv('UTF-8', 'BIG-5', $rs);
		//dump(htmlspecialchars(iconv( 'BIG-5','UTF-8', $rs)));
		$res 	= curl_get($url,$rs,["Content-type:text/xml"]);
		$res 	= iconv('big5', 'big5//IGNORE', $res); 
		$xml 	= simplexml_load_string($res);
		$xml 	= json_decode(json_encode($xml),TRUE);
		var_dump($xml);
		//TXNKEY 2018081578439
		//BATCH_NO 00033602
	}
	
    public function encrypt($src, $key, $size = 128, $mode = 'ECB') {
        if (is_null($key)) {
            log_message('error', 'Key為空值');
            return null;
        }

        $method = $this->findMethod($size, $mode);
        $ivSize = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $encrypted = openssl_encrypt($src, $method, mb_convert_encoding($key, 'big5', 'utf-8'), OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    function findMethod($size, $mode = 'ECB') {
        return 'AES-' . $size . '-' . $mode;
    }
	

	function strToHex($string){
		$hex='';
		for ($i=0; $i < strlen($string); $i++){
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}


}



