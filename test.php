<?php
/*	if($_GET['code']){
		$data = array(
			"client_id"		=> "622ba30fa4524019a3b36fccd862b764",
			"client_secret"	=> "851c4c9b2622438cad669b0d12ce4709",
			"grant_type"	=> "authorization_code",
			"redirect_uri"	=> "http://p2p-api.clockin.com.tw/test.php",
			"code"			=> $_GET['code'],
		);
		$rs = curl_get("https://api.instagram.com/oauth/access_token",$data);
		$rs = json_decode($rs,TRUE);
		var_dump($rs);die();
		$res = curl_get("https://api.instagram.com/v1/users/self/?access_token=".$rs['access_token']);
		$res = json_decode($res,TRUE);
		echo "<pre>";
		var_dump($res);die();
		$res = curl_get("https://api.instagram.com/v1/users/484699703/media/recent?access_token=".$rs['access_token']);
		$res = json_decode($res,TRUE);
		var_dump($rs);
		if($res){
			foreach($res['data'] as $key=>$value) {
				echo "<span>文章：".$value['caption']['text']."</span><br>";
				echo "<span>讚數：".$value['likes']['count']."</span><br>";
				echo '<img src="'.$value['images']['standard_resolution']['url'].'" alt="" height="200" width="200"><br><br>';
			}
		}
		
	}else{
		$url = "https://api.instagram.com/oauth/authorize/?client_id=622ba30fa4524019a3b36fccd862b764&redirect_uri=".urlencode("http://p2p-api.clockin.com.tw/test.php")."&response_type=code";
		//$url = "https://api.instagram.com/oauth/authorize/?client_id=622ba30fa4524019a3b36fccd862b764&redirect_uri=".urlencode("http://p2p-api.clockin.com.tw/test.php")."&response_type=token";
		header('Location: '.$url);
	}
*/
	echo "<pre>";
	$state			= "123456789aaaa";
	$client_id		= "1508139296";
	$client_secret	= "7f57ae86e8ff067d9e11248b2a75973e";
	$redirect_uri = urlencode("http://p2p-api.clockin.com.tw/test.php");
	/*if($_GET['code']){
		$post = "grant_type=authorization_code&code=".$_GET['code']."&redirect_uri=".$redirect_uri."&client_id=".$client_id."&client_secret=".$client_secret;
		$url = "https://api.line.me/oauth2/v2.1/token";
		$rs = curl_get($url,$post);
		$rs = json_decode($rs,true);
		var_dump($rs);
	}else{
		$url = "https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&state=$state&scope=openid%20profile";
		header('Location: '.$url);
	}*/
	$access_token 	= 'eyJhbGciOiJIUzI1NiJ9.voxBkMkzzaPv0JfwYGR5v4FCimrg031mN9XWBJzZWvNPh6-N7irvpPdV0p5y9tdwiJo82-6alkPs3kaNOlVeVSNa5EXx4J91rzWhOPSIWtz07qZG9dDysGcWxnUlUhBw8KmdtLZM6H2gYMq691LFvc1HlUwdMlzsfDsYaEw6B9g.wr2Z-qG_9ik2SFzbydHVX0FHZHx5XwqhirAF0nMRWFc';
	$token_type		= "Bearer";
	$url = "https://api.line.me/oauth2/v2.1/verify?access_token=".$access_token;
	$rs  = curl_get($url);
	$rs	 = json_decode($rs,true);
	var_dump($rs['client_id']);
		
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

?>
