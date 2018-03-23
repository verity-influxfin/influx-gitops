<?php

	if($_GET['code']){
		$data = array(
			"client_id"		=> "622ba30fa4524019a3b36fccd862b764",
			"client_secret"	=> "851c4c9b2622438cad669b0d12ce4709",
			"grant_type"	=> "authorization_code",
			"redirect_uri"	=> "http://p2p-api.clockin.com.tw/test.php",
			"code"			=> $_GET['code'],
		);
		$rs = curl_get("https://api.instagram.com/oauth/access_token",$data);
		$rs = json_decode($rs,TRUE);
		$res = curl_get("https://api.instagram.com/v1/users/484699703/media/recent?access_token=".$rs['access_token']);
		$res = json_decode($res,TRUE);
		if($res){
			foreach($res['data'] as $key=>$value) {
				echo "<span>文章：".$value['caption']['text']."</span><br>";
				echo "<span>讚數：".$value['likes']['count']."</span><br>";
				echo '<img src="'.$value['images']['standard_resolution']['url'].'" alt="" height="200" width="200"><br><br>';
			}
		}
		
	}else{
		$url = "https://api.instagram.com/oauth/authorize/?client_id=622ba30fa4524019a3b36fccd862b764&redirect_uri=".urlencode("http://p2p-api.clockin.com.tw/test.php")."&response_type=code";
		var_dump($url);
		header('Location: '.$url);
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
?>
