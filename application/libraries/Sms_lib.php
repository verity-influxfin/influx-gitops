<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use Aws\Sns\SnsClient;

class Sms_lib {
	
	private $client;
	
	public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('user/sms_verify_model');
		$this->CI->load->model('log/log_sns_model');
		$this->client 	= SnsClient::factory(
			array(
				'version' 	=> 'latest',
				'region'  	=> 'ap-northeast-1',
				'credentials' => [
					'key'         => AWS_ACCESS_TOKEN,
					'secret'      => AWS_SECRET_TOKEN,
				],
			)
		);
    }

	
	public function send_register($phone=""){
		if(!empty($phone)){
			$code	 = rand(1, 9).rand(0, 9).rand(0, 9).rand(0, 9);
			$content = "P2P認證簡訊，您的驗證碼為".$code."，請注意有效時間為30分鐘以內";
			$param = array(
				"type" 			=> SMS_TYPE_REGISTER,
				"phone"			=> $phone,
				"code"			=> $code,
				"expire_time"	=> time()+SMS_EXPIRE_TIME,
			);
			$this->CI->sms_verify_model->insert($param);
			$data = array(
					'Message' 					=> $content,
					'PhoneNumber' 				=> '+886'.$phone,
					'MessageAttributes' 		=> array(
						'AWS.SNS.SMS.SMSType' 	=> array('StringValue' => 'Transactional', 'DataType' => 'String'),
					)
				);
			$result = $this->client->publish($data);
			$this->CI->log_sns_model->insert(array(
				"type" 		=> "sms",
				"request"	=> json_encode($data),
				"response"	=> json_encode($result->toArray())
			));
			return true;
		}
		return false;
	}

	public function verify_register($phone="",$code=""){
		if(!empty($phone) && !empty($code)){
			$param = array(
				"type" 			=> SMS_TYPE_REGISTER,
				"phone"			=> $phone,
				"status"		=> 0,
			);
			$rs = $this->CI->sms_verify_model->order_by("expire_time","desc")->get_by($param);
			if($rs){
				if($rs->code == $code && $rs->expire_time>=time()){
					$this->CI->sms_verify_model->update($rs->id,array("status"=>1));
					return true;
				}
			}
		}
		return false;
	}

	
}
