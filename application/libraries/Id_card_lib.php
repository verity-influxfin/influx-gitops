<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Id_card_lib {

	// 戶役政 api 查驗結果說明
	private $responseMessageMapping = [
		'checkIdCardApply' => [
			'1' => '國民身分證資料與檔存資料相符',
			'2' => '身分證字號目前驗證資料錯誤次數已達 1 次，今日錯誤累積達 3 次後，此身分證字號將無法查詢。',
			'3' => '身分證字號目前驗證資料錯誤次數已達 2 次，今日錯誤累積達 3 次後，此身分證字號將無法查詢。',
			'4' => '身分證字號目前驗證資料錯誤次數已達 3 次，今日錯誤累積達 3 次後，此身分證字號將無法查詢。(*該次查詢有進行查驗比對但比對結果不相符)',
			'5' => '身分證字號驗證資料錯誤次數已達 3 次，今日無法查詢，請明日再查!!(*該次查詢無進行查驗比對)',
			'6' => '您所查詢的國民身分證字號已停止使用。',
			'7' => '您所查詢的國民身分證，業依當事人申請登錄掛失。',
			'8' => '單一使用者出現異常使用情形，暫停使用者權限。',
		],
	];
	// 戶役政 api 參數
	private $parametersMapping = [
		'applyCode' => [
			'未領' => '0',
			'初發' => '1',
			'補發' => '2',
			'換發' => '3',
		],
		'issueSiteId' => [
			'連江' => '09007',
			'金門' => '09020',
			'北縣' => '10001',
			'宜縣' => '10002',
			'桃縣' => '10003',
			'竹縣' => '10004',
			'苗縣' => '10005',
			'中縣' => '10006',
			'彰縣' => '10007',
			'投縣' => '10008',
			'雲縣' => '10009',
			'嘉縣' => '10010',
			'南縣' => '10011',
			'高縣' => '10012',
			'屏縣' => '10013',
			'東縣' => '10014',
			'花縣' => '10015',
			'澎縣' => '10016',
			'基市' => '10017',
			'竹市' => '10018',
			'中市' => '66000',
			'嘉市' => '10020',
			'南市' => '67000',
			'北市' => '63000',
			'高市' => '64000',
			'新北市' => '65000',
			'桃市' => '68000',
		],
		'fullIssueSite' => [
			'連江縣' => '連江',
			'金門縣' => '金門',
			'臺北縣' => '北縣',
			'台北縣' => '北縣',
			'宜蘭縣' => '宜縣',
			'桃園縣' => '桃縣',
			'新竹縣' => '竹縣',
			'苗栗縣' => '苗縣',
			'臺中縣' => '中縣',
			'台中縣' => '中縣',
			'彰化縣' => '彰縣',
			'南投縣' => '投縣',
			'雲林縣' => '雲縣',
			'嘉義縣' => '嘉縣',
			'臺南縣' => '南縣',
			'台南縣' => '南縣',
			'高雄縣' => '高縣',
			'屏東縣' => '屏縣',
			'臺東縣' => '東縣',
			'台東縣' => '東縣',
			'花蓮縣' => '花縣',
			'澎湖縣' => '澎縣',
			'基隆市' => '基市',
			'新竹市' => '竹市',
			'臺中市' => '中市',
			'台中市' => '中市',
			'嘉義市' => '嘉市',
			'臺南市' => '南市',
			'台南市' => '南市',
			'臺北市' => '北市',
			'台北市' => '北市',
			'高雄市' => '高市',
			'新北市' => '新北市',
			'桃園市' => '桃市',
		],
	];

	# TODO: Add OCTOPODA_IP and RIS_PRIVATE_KEY_PATH in Apache vhost
	public function __construct()
	{
		$this->CI = &get_instance();
		$serviceAdapterPort = '9218';
		$this->serviceAdapterUrl = "http://".getenv('OCTOPODA_IP').":{$serviceAdapterPort}/service-adapter/api/v1.0";
		$this->risPrivateKeyPath = getenv('RIS_PRIVATE_KEY_PATH');
	}

	/**
	 * [send_request 戶役政身分證補換發查詢api]
	 * @param  string $personId     [身份證字號]
	 * @param  string $applyCode    [領補換代碼](0=未領/1=領證/2=補證/3=換證)
	 * @param  string $applyYyymmdd [發證日期](民國格式:YYYmmdd)
	 * @param  string $issueSiteId  [發證地點行政區域代碼]
	 * @param  string $userId       [使用api之使用者代號]
	 * @return array  $result       [查驗結果]
	 * (
	 *  [status] => 回應碼
	 *  [response]=>
	 *   (
	 *    [request] =>
	 *      (
	 *       [personId] => 身份證字號
	 *       [applyCode] => 領補換類別
	 *       [applyYyymmdd] => 發證日期
	 *       [issueSiteId] => 發證地點
	 *      )
	 *    [response] =>
	 *      (
	 *       [rowData] => 查驗結果
	 *       [checkIdCardApplyFormat] => 查驗結果說明
	 *      )
	 *   )
	 * )
	 */
	public function send_request($personId = '', $applyCode = '初發', $applyYyymmdd = '', $issueSiteId = '', $userId="inFlux001"){
		
		// 若為測試環境，戶役政回傳驗證通過，以利測試進行（測試站實名多為假資料）
		if (ENVIRONMENT == 'development') {
			$result = [
				'status' => '200',
				'response' => [
					'request' =>[
						'personId' => $personId,
						'applyCode' => $applyCode,
						'applyYyymmdd' => $applyYyymmdd,
						'issueSiteId' => $issueSiteId
					],
					'response'=>[
						'rowData' => [
							"httpCode" => "200",
							"httpMessage" => "OK",
							"rdCode" => "RS7009",
					"rdMessage" => "查詢作業完成",
							"responseData" => [
								"checkIdCardApply" => "1"
							]
						],
						'checkIdCardApplyFormat' => '國民身分證資料與檔存資料相符'
					],
				]
			];

			return $result;
		}


		$result = [
			'status' => '500',
			'response' => [
				'request' =>[
					'personId' => $personId,
					'applyCode' => $applyCode,
					'applyYyymmdd' => $applyYyymmdd,
					'issueSiteId' => $issueSiteId
				],
				'response'=>[
					'rowData' => [],
					'checkIdCardApplyFormat' => 'Wrong Parameters'
				],
			]
		];

		if(!$personId || !$applyCode || !$applyYyymmdd || !$issueSiteId){
			$result['status'] = 400;
			$result['response']['response']['checkIdCardApplyFormat'] = 'Wrong Parameters';
			// $this->CI->json_output->setStatusCode(400)->setResponse($result)->send();
			return $result;
		}
		// pattern
		$applyCodeFormat = isset($this->parametersMapping['applyCode'][$applyCode]) ? $this->parametersMapping['applyCode'][$applyCode] : '';
		$issueSiteId = isset($this->parametersMapping['fullIssueSite'][$issueSiteId]) ? $this->parametersMapping['fullIssueSite'][$issueSiteId] : $issueSiteId;
		$issueSiteIdFormat = isset($this->parametersMapping['issueSiteId'][$issueSiteId]) ? $this->parametersMapping['issueSiteId'][$issueSiteId] : '';

		if(!$applyCodeFormat || !$issueSiteIdFormat){
			$result['status'] = 401;
			$result['response']['response']['checkIdCardApplyFormat'] = 'Parameters Not Match';
			// $this->CI->json_output->setStatusCode(401)->setResponse($result)->send();
			return $result;
		}

		preg_match('/^(?<year>(1[0-9]{2}|[0-9]{2}))(?<month>(0?[1-9]|1[012]))(?<day>(0?[1-9]|[12][0-9]|3[01]))$/', $applyYyymmdd, $regexResult);
		if(!empty($regexResult)) {
			$applyYyymmdd = str_pad($regexResult['year'], 3, 0, STR_PAD_LEFT) .
				str_pad($regexResult['month'], 2, 0, STR_PAD_LEFT) .
				str_pad($regexResult['day'], 2, 0, STR_PAD_LEFT);
		}

		if(! preg_match('/^[0-9]{3}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])$/',$applyYyymmdd)){
			$result['status'] = 401;
			$result['response']['response']['checkIdCardApplyFormat'] = 'Parameters of applyYyymmdd Format Is Wrong';
			// $this->CI->json_output->setStatusCode(401)->setResponse($result)->send();
			return $result;
		}

		$privateKey = file_get_contents($this->risPrivateKeyPath);
		$payload = array(
			"orgId" => "68566881",
			"apId" => "INF00",
			"userId" => $userId,
			"iss" => "XYxNQ1DdEDxjZSmUlRH7VhSRBis98S5W",
			"sub" => "綠色便民專案",
			"aud" => date('Y/m/d H:i:s',time()),
			"jobId" => "V2C201",
			"opType" => "RW",
			"iat" => time() - 180, //token 有效起始時間，timestamp 格式(建議 發送時間-180 秒)
			"exp" => time() + 180, //token 有效迄止時間，timestamp 格式(建議 發送時間+180 秒)
			"jti" => md5(uniqid('JWT').time()), // JWT ID 唯一 識別碼(建議使用各語言的 JWT 套件產生)
			"conditionMap" => "{
				\"personId\": \"{$personId}\", \"applyCode\":\"{$applyCodeFormat}\",
				 \"applyYyymmdd\":\"{$applyYyymmdd}\",\"issueSiteId\":\"{$issueSiteIdFormat}\"}"
		);
		$jwt = JWT::encode($payload, $privateKey, 'RS256');

		$headers = array(
			'Authorization' =>  'Bearer '.$jwt,
			'sris-consumerAdminId'=> '00000000',
			'Content-Type'=> 'application/json'
		);

		$payload = array('headers' => json_encode($headers, true));
		$requestUrl = $this->serviceAdapterUrl . "/id-card/send-request";
		$apiResponse = curl_get($requestUrl, $data=$payload, [],10);

		if($apiResponse){
			$apiResponse = json_decode($apiResponse, true);
			$checkIdCardApply = isset($apiResponse['responseData']['checkIdCardApply']) ? $apiResponse['responseData']['checkIdCardApply'] : '';
			$result['response']['response'] = [
				'rowData' => $apiResponse,
				'checkIdCardApplyFormat' => isset($this->responseMessageMapping['checkIdCardApply'][$checkIdCardApply]) ? $this->responseMessageMapping['checkIdCardApply'][$checkIdCardApply]: ''
			];
		}else{
			$result['status'] = 405;
			$result['response']['response']['checkIdCardApplyFormat'] = 'No Response';
			// $this->CI->json_output->setStatusCode(405)->setResponse($response)->send();
			return $result;
		}
		$result['status'] = 200;
		// $this->CI->json_output->setStatusCode(200)->setResponse($result)->send();
		return $result;
	}

}
