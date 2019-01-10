<?php

	//Creating a Secure, Random String
	function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$str = '';
		$max = mb_strlen($keyspace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$str .= $keyspace[rand(0, $max)];
		}
		return $str;
	}

	function getCurrentUrl()
	{
		$currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		$currentURL .= $_SERVER["SERVER_NAME"];
	 
		if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
		{
			$currentURL .= ":".$_SERVER["SERVER_PORT"];
		} 
	 
			$currentURL .= $_SERVER["REQUEST_URI"];
		return $currentURL;
	}
	
	function dump($var,$die=false) {
		$trace = debug_backtrace();
		echo "<br />File: <b> ".$trace[0]['file']."</b><br/>Line : <b>".$trace[0]['line']."</b><br/>";
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		if ($die) {
			die();
		}
	}

	 /**
	 * 獲得當前使用者IP地址
	 */
	function get_ip() {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $aah) {
			if (!isset($_SERVER[$aah])) continue;
			$cur2ip = $_SERVER[$aah];
			$curip = explode('.', $cur2ip);
			if (count($curip) !== 4)  break; // If they've sent at least one invalid IP, break out
			foreach ($curip as $sup)
			if (($sup = intval($sup)) < 0 or $sup > 255)
			break 2;
			$curip_bin = $curip[0] << 24 | $curip[1] << 16 | $curip[2] << 8 | $curip[3];
			foreach (array(
			//    hexadecimal ip  ip mask
			array(0x7F000001,     0xFFFF0000), // 127.0.*.*
			array(0x0A000000,     0xFFFF0000), // 10.0.*.*
			array(0xC0A80000,     0xFFFF0000), // 192.168.*.*
			) as $ipmask) {
				if (($curip_bin & $ipmask[1]) === ($ipmask[0] & $ipmask[1])) break 2;
			}
			return $ip = $cur2ip;
		}
		return $ip = $_SERVER['REMOTE_ADDR'];
	}


	function curl_get($url,$data = array(),$header = array()) {
		$curl = curl_init($url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //不直接顯示回傳結果 
		
		if(!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
		}
		
		if(!empty($header)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		}
		
		$rs = curl_exec($curl); 
		curl_close($curl);
		return $rs;
	}

	function alert($msg='', $url='', $second=0) {
		if(empty($second)){
			if(!empty($msg)) {
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><script language="javascript">alert("'.$msg.'");</script>';
			}
			else
				header('Location:'.$url);
			
			if (!empty($url)) 
				echo "<meta http-equiv=\"REFRESH\" content=\"0;URL='".$url."'\">";
			else 
				echo '<script language="javascript">window.top.location = window.top.location</script>';	
		}
		else {
			$inf = '<style>body {background-color:#eee}</style><div style="text-align:center"><div style="width:600px; border:5px solid #ddd; margin:50px; padding:0.8em; text-align:left; background-color:white; font-size:1.3em">';
			$inf .= $msg;
			$inf .= '<div style="font-size:12px; margin-top:1em; color:gray;">頁面正在自動跳轉<a href="'.$url.'" style="color:blue;">這個地址</a>，請稍候...<b id="left_time">'.$second.'</b> 秒,</div></div></div>';
			if($_SERVER['SERVER_PORT'] == '443'){
				echo "<meta http-equiv=\"REFRESH\" content=\"$second;URL='".$url."'\">";
			}else{	
				$inf .= '<script type="text/javascript">var sec = '.$second.'; window.setInterval(cooldown,1000);';
				$inf .= 'function cooldown(){if(sec>0) sec--; else{window.clearInterval(); window.location.replace("'.$url.'"); }; document.getElementById("left_time").innerHTML = sec;}';
				$inf .= '</script>';
			}
			echo $inf;
		}
		exit;
	}
	
	function app_access()
	{
		$CI 	=& get_instance();
		$list 	= $CI->config->item('access_ip_list');
		foreach($list as $ip){
			if(preg_match('/\.\*$/',$ip)){
				list($main, $sub) = explode('.*', $ip);
				if(stripos(get_ip(), $main)!==false){
					return true;
				}
			}
			if(get_ip()==$ip){
				return true;
			}
		}
		return false;
	}

	function is_development()
	{
		if(ENVIRONMENT=='development'){
			return true;
		}
		return false;
	}
	
	function check_cardid($cardid='') {
		if(!empty($cardid)){
			$alphabet =['A'=>'10','B'=>'11','C'=>'12','D'=>'13','E'=>'14','F'=>'15','G'=>'16','H'=>'17','I'=>'34',
						'J'=>'18','K'=>'19','L'=>'20','M'=>'21','N'=>'22','O'=>'35','P'=>'23','Q'=>'24','R'=>'25',
						'S'=>'26','T'=>'27','U'=>'28','V'=>'29','W'=>'32','X'=>'30','Y'=>'31','Z'=>'33'];
			if(strlen($cardid)==10){
				$alpha = substr($cardid,0,1);
				$alpha = strtoupper($alpha);
				if(preg_match("/[A-Za-z]/",$alpha)){
					$nx 	= $alphabet[$alpha];
					$total 	= $nx[0] + $nx[1]*9 + substr($cardid,8,1) + substr($cardid,9,1);
					if(in_array(substr($cardid,1,1),['1','2'])){
						$i = 8;
						$j = 1;
						while($i >= 2){
							$total 	= $total + (substr($cardid,$j,1) * $i);
							$j+=1;
							$i--;	
						}
						if( ($total%10) ==0)
							return true;
					}
				}
			}
		}
		return false;
	}
	
	function bankaccount_substr($bank_account){
		$bank_account 	= trim($bank_account);
		$len 		 	= strlen($bank_account);
		$bank_code 		= substr($bank_account,0,3);
		$bank_account	= substr($bank_account,3,$len-3);
		return array(
			'bank_code'		=> $bank_code,
			'bank_account'	=> intval($bank_account)
		);
	}

	function get_rand_token(){
		if(is_development()){
			return '000000';
		}
		return rand(1, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
	}
	
	function make_promote_code() {
		$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$rand = $code[rand(0,25)]
			.strtoupper(dechex(date('m')))
			.date('d').substr(time(),-5)
			.substr(microtime(),2,5)
			.sprintf('%02d',rand(0,99));
		for(
			$a = md5( $rand, true ),
			$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
			$d = '',
			$f = 0;
			$f < 8;
			$g = ord( $a[ $f ] ),
			$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
			$f++
		);
		return $d;
	}
	
	function is_virtual_account($account){
		if($account){
			$account = trim($account);
			if(strlen($account)==14 && substr($account,0,4)==CATHAY_VIRTUAL_CODE){
				return true;
			}
		}
		return false;
	}
	
	function investor_virtual_account($account){
		if($account){
			if(strlen($account)==14 && substr($account,0,4)==CATHAY_VIRTUAL_CODE && substr($account,4,1)==INVESTOR_VIRTUAL_CODE){
				return true;
			}
		}
		return false;
	}
	
	function get_age($date) {
		return intval(date('Y', time() - strtotime($date))) - 1970;
	}

	function get_range_days($sdate,$edate) {
		return intval(floor((strtotime($edate) - strtotime($sdate))/(60*60*24)));
	}

	function get_entering_date() {
		$entering_date 	= date('Y-m-d');
		return $entering_date;
	}
	
	function entering_date_range($date='') {
		if($date){
			$sdate 	= date('Y-m-d',strtotime($date)).' 00:00:00';
			$edate 	= date('Y-m-d',strtotime($date)).' 23:59:59';
			return [
				'sdatetime' => $sdate,
				'edatetime' => $edate
			];
		}
		return false;
	}
	
	function get_qrcode($url){
		return 'https://chart.apis.google.com/chart?cht=qr&choe=UTF-8&chl='.urlencode($url).'&chs=500x500';
	}
	
	function nf_to_wf($strs){  //全形半形轉換
		$nft = array(
			"(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
			"-", "?", "!", "@", "#", "$", "%", "&", "|", "\\",
			"/", "+", "=", "*", "~", "`", "'", "\"", "<", ">",
			"^", "_",
			"0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
			"k", "l", "m", "n", "o", "p", "q", "r", "s", "t",
			"u", "v", "w", "x", "y", "z",
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J",
			"K", "L", "M", "N", "O", "P", "Q", "R", "S", "T",
			"U", "V", "W", "X", "Y", "Z",
			" "
		);
		$wft = array(
			"（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
			"－", "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
			"／", "＋", "＝", "＊", "～", "、", "、", "＂", "＜", "＞",
			"︿", "＿",
			"０", "１", "２", "３", "４", "５", "６", "７", "８", "９",
			"ａ", "ｂ", "ｃ", "ｄ", "ｅ", "ｆ", "ｇ", "ｈ", "ｉ", "ｊ",
			"ｋ", "ｌ", "ｍ", "ｎ", "ｏ", "ｐ", "ｑ", "ｒ", "ｓ", "ｔ",
			"ｕ", "ｖ", "ｗ", "ｘ", "ｙ", "ｚ",
			"Ａ", "Ｂ", "Ｃ", "Ｄ", "Ｅ", "Ｆ", "Ｇ", "Ｈ", "Ｉ", "Ｊ",
			"Ｋ", "Ｌ", "Ｍ", "Ｎ", "Ｏ", "Ｐ", "Ｑ", "Ｒ", "Ｓ", "Ｔ",
			"Ｕ", "Ｖ", "Ｗ", "Ｘ", "Ｙ", "Ｚ",
			"　"
		);

		return str_replace($nft, $wft, $strs);
	}
	
	function myErrorHandler($errno,$errstr,$errfile,$errline)  
	{  
		echo "ERROR: [ID $errno] $errstr (Line: $errline of $errfile) \n";  
	}
	
	function mb_str_split($str){
		return preg_split('/(?<!^)(?!$)/u', $str );
	}
	//民國轉西元
	function r_to_ad($str){
		
		if($str == date('Y-m-d',strtotime($str))){
			return $str;
		}
		
		if($str == preg_replace('/[^\d]/','',$str)){
			return date('Y-m-d',strtotime($str+19110000));
		}else{
			$arr = [];
			preg_match_all('/\d+/',$str,$arr);
			if($arr[0] && count($arr[0])==3){
				$y = $arr[0][0]+1911;
				$m = $arr[0][1];
				$d = $arr[0][2];
				return date('Y-m-d',strtotime("$y-$m-$d"));
			}
			return false;
		}
	}
?>