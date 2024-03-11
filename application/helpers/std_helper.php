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


	function curl_get($url,$data = array(),$header = array(),$timeout=null, $get_info = FALSE) {
		$curl = curl_init($url);
		if (ENVIRONMENT == "production") {
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		} else {
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //不直接顯示回傳結果
		if(isset($timeout))
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		if(!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}

		if(!empty($header)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		}

        if ($get_info === TRUE)
        {
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        }

		$rs = curl_exec($curl);

        if ($get_info === TRUE)
        {
            $rs_decode = json_decode($rs, TRUE);
            if (json_last_error() === JSON_ERROR_NONE)
            {
                $rs_decode['curl_getinfo'] = curl_getinfo($curl);
                $rs = json_encode($rs_decode);
            }
        }

		curl_close($curl);
		return $rs;
	}

function curl_post_json(string $url, array $data = array(), array $header = array(), $timeout = null, $get_info = FALSE): string
{
        $curl = curl_init($url);

        $header[] = 'Content-Type: application/json';
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYHOST=> ENVIRONMENT == "production" ? 2 : false,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_RETURNTRANSFER => 1, //不直接顯示回傳結果
            CURLOPT_TIMEOUT => isset($timeout) ? $timeout : 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS =>json_encode($data, JSON_UNESCAPED_UNICODE),
            CURLINFO_HEADER_OUT => $get_info === true,
        ));

        $rs = curl_exec($curl);

        if ($get_info === TRUE)
        {
            $rs_decode = json_decode($rs, TRUE);
            if (json_last_error() === JSON_ERROR_NONE)
            {
                $rs_decode['curl_getinfo'] = curl_getinfo($curl);
                $rs = json_encode($rs_decode);
            }
        }

        curl_close($curl);
        return $rs;
    }
    function curl_put($url, $data = array(), $header = array(), $timeout = NULL)
    {
        $curl = curl_init($url);
        if (ENVIRONMENT == "production")
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        else
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //不直接顯示回傳結果
        if (isset($timeout))
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        if ( ! empty($data))
        {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        if ( ! empty($header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        $rs = curl_exec($curl);
        curl_close($curl);
        return $rs;
    }

    function curl_get_statuscode($url, $data = array(), $header = array(), $timeout = NULL)
    {
        $curl = curl_init($url);
        if (ENVIRONMENT == "production")
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        else
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //不直接顯示回傳結果
        if (isset($timeout))
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        if ( ! empty($data))
        {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        if ( ! empty($header))
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        $rs = curl_exec($curl);
        $return_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $result = [
            'code' => $return_code,
            'response' => json_decode($rs, TRUE)
        ];

        return $result;
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
				if(preg_match("/[A-Z]/",$alpha)){
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

	function make_promote_code($length=8) {
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
			$f < $length;
			$g = ord( $a[ $f ] ),
			$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
			$f++
		);
		return $d;
	}

	function is_virtual_account($account){
		if($account){
			$account = trim($account);
			if(strlen($account)==14 && substr($account,0,4)==CATHAY_VIRTUAL_CODE || substr($account,0,5) == TAISHIN_VIRTUAL_CODE){
				return true;
			}
		}
		return false;
	}

	function investor_virtual_account($account){
		if($account){
			if(strlen($account)==14 && substr($account,0,4)==CATHAY_VIRTUAL_CODE && substr($account,4,1)==INVESTOR_VIRTUAL_CODE || substr($account,0,5)==TAISHIN_VIRTUAL_CODE){
				return true;
			}
		}
		return false;
	}

	function get_hidden_name($name){
        $word_m = '';
        $word = $name;
        $len = mb_strlen($word);
        $w_midle_len = $len > 2 ? $len : 0;
        for ($i = 0, $c = $w_midle_len - 2; $i < $c; $i++) {
            $word_m .= '○';
        }
        return mb_substr($word, 0, 1) . $word_m . (mb_strlen($word) == 2 ? '○' : mb_substr($word, -1));
    }

	function get_company_name($name){
        preg_match_all("/有限公司|股份有限公司/u", $name, $match);
        $match_status = isset($match[0][0]) && !empty($match[0][0]);
        $match_status ? $name = preg_replace('/有限公司|股份有限公司/u', '', $name) : '';
        $word_m = '○';
        $word = $name;
        $rs = mb_substr($word, 0, 1) . $word_m . (mb_strlen($word) == 2 ? '○' : mb_substr($word, -1));
        $match_status ? ($rs .= $match[0][0]) : '';
        return $rs;
    }

    function get_hidden_id($id){
        return substr($id,0,4).'○○○'.substr($id,-3);
    }

	function get_age($date) {
        return date_diff(date_create($date), date_create('today'))->y;
	}

	function get_range_days($sdate,$edate) {
		return intval(floor((strtotime($edate) - strtotime($sdate))/(60*60*24)));
	}

	function get_entering_date() {
		return date('Y-m-d');
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

	function mb_str_splits($str){
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

	function array_columns(array $arr, array $keysSelect)
	{
		$keys = array_flip($keysSelect);
		return array_map(
			function($a) use($keys) {
				return array_intersect_key($a,$keys);
			},
			$arr
		);
	}

	function is_image($file_type)
	{
		// IE will sometimes return odd mime-types during upload, so here we just standardize all
		// jpegs or pngs to the same file type.

		$png_mimes  = array('image/x-png');
		$jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');

		if (in_array($file_type, $png_mimes))
		{
			$file_type = 'image/png';
		}
		elseif (in_array($file_type, $jpeg_mimes))
		{
			$file_type = 'image/jpeg';
		}

		$img_mimes = array('image/gif',	'image/jpeg', 'image/png');

		return in_array($file_type, $img_mimes, TRUE);
	}

	function is_pdf($file_type)
	{
		$pdf_mimes = array('application/pdf');

		return in_array($file_type, $pdf_mimes, TRUE);
	}

    function is_video($file_type)
    {
        return preg_match('/^video\/(\w+)/', $file_type);
    }

    /**
	 * 依照前綴詞取得目前已定義的常數項
	 * @param array $constants: 變數列表
	 * @param string $prefix: 前綴詞
	 * @return array
	 */
	function returnConstants (array $constants, string $prefix): array
	{
		foreach ($constants as $key=>$value)
			if (substr($key,0,strlen($prefix))==$prefix)  $dump[$key] = $value;
		if(empty($dump)) { return []; }
		else { return $dump; }
	}

	function birthdayDateFormat($birthday) {
		if(preg_match("/^([0-9]{1,3})(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])$/u",
			$birthday, $matches)){
			$birthday = "$matches[1]/$matches[2]/$matches[3]";
		}
		return $birthday;
    }

    function isValidDateTime(string $datetime) {
		return $datetime > "1911-01-01 00:00:00";
    }

	function isAvailableDate($format, $date) {
		$dt = \DateTime::createFromFormat($format, $date);
		return $dt !== false && !array_sum($dt::getLastErrors());
	}

	function addPrefixToArray(array $array, string $prefix)
	{
		return array_map(function ($arrayValues) use ($prefix) {
			return $prefix . $arrayValues;
		}, $array);
	}

	function addSuffixToArray(array $array, string $suffix)
	{
		return array_map(function ($arrayValues) use ($suffix) {
			return $arrayValues . $suffix;
		}, $array);
	}

    function isJson($string) {
		json_decode($string);
		return json_last_error() === JSON_ERROR_NONE;
	}

    function strip_ROC_date_word(string $date): string
    {
		preg_match('/民?國?([0-9]{2,3})(年|-|\/)(0?[1-9]|1[012])(月|-|\/)(0?[1-9]|[12][0-9]|3[01])(日?)$/u', $date, $regex_result);
		if(!empty($regex_result)) {
			$date = $regex_result[1].$regex_result[3].$regex_result[5];
		}
        // 2023/10/13 民國年去「0」，如：0851013 => 851013，1010103 => 1010103
		return strval(intval($date));
	}

	function pagination_config($config=[]) {
		if(empty($config))
			$config = [];

		$config['num_links'] = 2;
		$config['use_page_numbers'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['page_query_string'] = TRUE;

		$config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav>';

		$config['first_link'] = '第一頁';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '最後一頁';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '下一頁';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '上一頁';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
		$config['cur_tag_close'] = '</a</li>';

		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';

		return $config;
	}


    function array_sum_identical_keys() {
        $arrays = func_get_args();
        $keys = array_keys(array_reduce($arrays, function ($keys, $arr) { return $keys + $arr; }, array()));
        $sums = [];

        foreach ($keys as $key) {
            $sums[$key] = array_reduce($arrays, function ($sum, $arr) use ($key) {
                return $sum + $arr[$key];
            }, is_numeric($arrays[0][$key]) ? 0 : []);
        }

        return $sums;
    }

    function log_msg($level, $message)
    {
        $backtrace = debug_backtrace();
        log_message($level, $backtrace[0]['file'] .'(' . $backtrace[0]['line']  . ') :: ' . $message . '\n' .
            (!empty($backtrace[1]) ? $backtrace[1]['file'] .'(' . $backtrace[1]['line'] : ''));
    }

/**
 * @param string $function_name
 * @param string $message
 * @param bool $need_debug_backtrace
 * @return bool
 */
function log_message_line_of_function(string $function_name, string $message, bool $need_debug_backtrace = false): bool
{
	$dir = 'application/logs/' . $function_name;
	if (!is_dir($dir)) {
		mkdir($dir, 0777, true);
	}

	$log_message = "ERROR - " . date('Y-m-d H:i:s') . " --> ";
	if ($need_debug_backtrace) {
		$backtrace = debug_backtrace();
		for ($i = 0; $i < count($backtrace); $i++) {
			if ($i == 0) {
				$log_message .= $backtrace[0]['file'] . '(' . $backtrace[0]['line'] . ') :: ' . $message;
			} else {
				$log_message .= $backtrace[$i]['file'] . '(' . $backtrace[$i]['line'] . ')';
			}
			$log_message .= PHP_EOL;
		}
	} else {
		$log_message .= "message: " . $message . PHP_EOL;
	}

	$log_file = $dir . "/log-" . date('Y-m-d') . ".php";
	return error_log($log_message, 3, $log_file);
}

    //紀錄國泰api詳細資訊，並刪除1年前資料
    function write_batchno_log($batch_no, $info)
    {
        $log_path = "application/logs/batchno_to_cathy/";
        write_file($log_path. $batch_no.'.xml', $info, "w+");
        
        $last_year = strtotime(date("Y-m-d H:i:s")."-1 year");
        $file_info = get_dir_file_info($log_path);

        foreach ($file_info as $k){
            if ($k['date'] < $last_year){
                unlink($k['server_path']);
            }
        }
    }
?>
