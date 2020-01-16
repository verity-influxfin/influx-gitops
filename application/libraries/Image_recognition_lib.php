<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image_recognition_lib
{
	const ML_URL = "http://127.0.0.1:9999/ml/api/v1.0/";

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('S3_upload');
        $this->CI->load->library('S3_lib');
    }

    public function readStudentCard($image)
    {
        if(empty($image)) {
            return;
        }

        $newImageUrl = $this->CI->s3_upload->public_image_by_data(file_get_contents($image), FRONT_S3_BUCKET);

        $url = self::ML_URL . "student-cards";
        $data = ["card_url" => $newImageUrl];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        $this->CI->s3_lib->public_delete_image($newImageUrl, FRONT_S3_BUCKET);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response->response->university;
    }
}