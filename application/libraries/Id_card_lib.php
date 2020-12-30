<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Id_card_lib {
	public function __construct()
	{
		$this->CI = &get_instance();
		// $this->CI->load->library('JWT');
	}

	// TODO: handle private key properly.
	public function send_request($personId, $applyCode, $applyYyymmdd, $issueSiteId){
		$privateKey = file_get_contents('file:///home/ubuntu/influx_privkey/ris_private.pem');
		$payload = array(
			"orgId" => "68566881",
			"apId" => "INF00",
			"userId" => "1",
			"iss" => "XYxNQ1DdEDxjZSmUIRH7VhSRBis98S5W",
			"sub" => "綠色便民專案",
			"aud" => date('Y/m/d H:i:s',time()),
			"jobId" => "V2C201",
			"opType" => "RW",
			"iat" => time() - 180, //token 有效起始時間，timestamp 格式(建議 發送時間-180 秒)
			"exp" => time() + 180, //token 有效迄止時間，timestamp 格式(建議 發送時間+180 秒)
			"jti" => md5(uniqid('JWT').time()), // JWT ID 唯一 識別碼(建議使用各語言的 JWT 套件產生)
			"conditionMap" => "{
				\"personId\": \"{$personId}\", \"applyCode\":\"{$applyCode}\",
				 \"applyYyymmdd\":\"{$applyYyymmdd}\",\"issueSiteId\":\"{$issueSiteId}\"}"
		);
		$jwt = JWT::encode($payload, $privateKey, 'RS256');

		$requestUrl = "https://rwa.moi.gov.tw:1443/integration/rwv2c2/";
		$data = array('Authorization'=>'Bearer '.$jwt, 'sris-consumerAdminId'=>00000000, 'Content-Type'=>'application/json');
		$result = curl_get($requestUrl, $data);

		return $result;
	}

}

