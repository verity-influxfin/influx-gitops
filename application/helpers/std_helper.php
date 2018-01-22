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


	function curl_get($url,$data = array()) {
		$curl = curl_init($url); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //不直接顯示回傳結果 
		if(!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
		}
		$rs = curl_exec($curl); 
		curl_close($curl);
		return $rs;
	}

	function alert($msg="", $url="", $second=0) {
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

?>