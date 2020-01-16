<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image_recognition_lib
{
	const ML_URL = "http://127.0.0.1:9999/ml/api/v1.0/";

    function __construct()
    {
        $this->CI = &get_instance();
    }

    public function readStudentCard($image)
    {
        if(empty($image)) {
            return;
        }

        $url = self::ML_URL . "student-cards";
        $data = ["card_url" => $image];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response->response->university;
    }
}