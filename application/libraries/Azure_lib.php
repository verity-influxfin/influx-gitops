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
    }
	//Azure API Face - Detect
	public function detect($url){
		$api_url=AZURE_API_URL.'/detect?returnFaceId=true&returnFaceLandmarks=false&recognitionModel=recognition_02&returnRecognitionModel=true&detectionModel=detection_01';
        $bucket=AZURE_S3_BUCKET;
        $s3_url=$this->CI->s3_upload->public_image_by_data(file_get_contents($url),$bucket);
        $data= [
			'url' =>  $s3_url,
		];
        $result = $this->azure_face_curl($api_url,$data);
        $this->CI->s3_lib->public_delete_image($s3_url,$bucket);
		return  $result;
	}

	//Azure API Face - Verify
	public function verify($url1,$url2){
		$api_url=AZURE_API_URL.'/verify';
		$detect1=json_decode($this->detect($url1),1);
		$detect2=json_decode($this->detect($url2),1);
		$faceId1=$detect1[0]['faceId'];
		$faceId2=$detect2[0]['faceId'];
		$data= [
			'faceId1' =>  $faceId1,
			'faceId2' =>  $faceId2,
		];
   
		$result = $this->azure_face_curl($api_url,$data);
		return  $result;
    }
    

    private function azure_face_curl($api_url,$data){
       
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
		return  $result;

    }

}