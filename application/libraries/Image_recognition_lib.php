<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Image_recognition_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('S3_upload');
        $this->CI->load->library('S3_lib');
        $this->ml_url = "http://127.0.0.1:9997/ml/api/v1.0/";
    }

    public function requestStudentCardIdentification($image, $ownerId)
    {
        if(empty($image) || $ownerId <= 0) {
            return false;
        }

        $newImageUrl = $this->CI->s3_upload->public_image_by_data(
            file_get_contents($image->url),
            FRONT_S3_BUCKET,
            $ownerId,
            [
                'type' => 'stmps/students',
                'name' => md5($image->url) . '.jpg',
            ]
        );
        $newImageUrl = str_replace(INFLUX_S3_URL, FRONT_CDN_URL, $newImageUrl);

        $url = $this->ml_url  . "student-cards/{$image->id}";

        $data = ["card_url" => $newImageUrl, "owner_id" => intval($ownerId)];

        $result = curl_get($url, $data);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return false;
        }

        return true;
    }

    public function getStudentCardIdentification($image)
    {
        if(empty($image)) {
            return;
        }

        $url = $this->ml_url  . "student-cards/{$image->id}";

        $result = curl_get($url);
        $response = json_decode($result);

        if (!$result || !isset($response->status) || $response->status != 200) {
            return;
        }

        return $response->response->university;
    }
}
