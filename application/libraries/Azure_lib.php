<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Azure_lib
{
	
	private $config = array();
	
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('S3_upload');
        $this->CI->load->library('S3_lib');
        $this->CI->load->model('log/log_azure_model');
    }
	//Azure Computer Vision API - OCR
	public function OCR($url,$user_id=0,$cer_id=0,$lang=0){
        $language = $lang==0?'zh-Hant':'en';
		$api_url=AZURE_API_URL.'vision/v1.0/ocr?language='.$language.'&detectOrientation=true';
        $bucket=AZURE_S3_BUCKET;
        $s3_url=$this->CI->s3_upload->public_image_by_data(file_get_contents($url),$bucket);
        $data= [
			'url' =>  $s3_url,
		];
        $result = $this->azure_curl($api_url,$data);
        $this->CI->s3_lib->public_delete_image($s3_url,$bucket);
        $this->log_event('OCR',$user_id,$cer_id,$result,$data);
		return  $result;
	}

    public function pure_text($data){
        $res = '';
        foreach($data['regions'] as $regions){
            foreach($regions['lines'] as $lines){
                foreach($lines['words'] as $words){
                    $res.=$words['text'];
                }
            }
        }
        return $res;
    }

	//Azure API Face - Detect
	public function detect($url,$user_id=0,$cer_id=0){
		$api_url=AZURE_API_URL.'face/v1.0/detect?returnFaceId=true&recognitionModel=recognition_02&returnRecognitionModel=true&detectionModel=detection_02';
        $bucket=AZURE_S3_BUCKET;

        if(empty($url))
        	return [];
        $file = @file_get_contents($url);
        if(!$file)
        	return [];

        $s3_url=$this->CI->s3_upload->public_image_by_data($file, $bucket);
        $data= [
			'url' =>  $s3_url,
		];
        $result = $this->azure_curl($api_url,$data);
        $this->CI->s3_lib->public_delete_image($s3_url,$bucket);
        $this->log_event('detect',$user_id,$cer_id,$result,$data);
		return  $result;
	}

	//Azure API Face - Verify
	public function verify($var1,$var2,$user_id=0,$cer_id=0){
        $api_url=AZURE_API_URL.'face/v1.0/verify';
        if(preg_match('/\-/',$var1)){
            $faceId1=$var1;
            $faceId2=$var2;
        }
        else{
            $detect1=$this->detect($var1);
            $detect2=$this->detect($var2);
            $faceId1=$detect1[0]['faceId'];
            $faceId2=$detect2[0]['faceId'];
        }

        $data= [
			'faceId1' =>  $faceId1,
			'faceId2' =>  $faceId2,
		];
   
		$result = $this->azure_curl($api_url,$data);
        $this->log_event('verify',$user_id,$cer_id,$result,$data);
		return  $result;
    }
    

    private function azure_curl($api_url,$data){
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Ocp-Apim-Subscription-Key:' . AZURE_API_KEY
        ]);
		$result = curl_exec($ch);
		curl_close($ch);   
		return  json_decode($result,1);

    }

    private function log_event($type,$user_id,$cer_id,$rs,$image){
        $log_data	= array(
            "type"		=> $type,
            "user_id"	=> $user_id,
            "cer_id"	=> $cer_id,
            "request"	=> json_encode($image),
            "response"	=> json_encode($rs),
        );
        $this->CI->log_azure_model->insert($log_data);
    }

}
