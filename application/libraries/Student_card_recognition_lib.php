<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_card_recognition_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        if (empty(getenv('STUDENT_CARD_IP')) || empty(getenv('STUDENT_CARD_PORT'))) {
            throw new Exception('can not get student card machine learning ip or port');
        }
        $this->url = 'http://' . getenv('STUDENT_CARD_IP') . ':' . getenv('STUDENT_CARD_PORT') . '/student_card/';
    }

    public function get_student_card_identification($image)
    {
        if(empty($image)) {
            return FALSE;
        }

        $url = $this->url  . 'recognition';
        $headers = [
            'accept: application/json',
            'Content-Type: application/json;'
        ];
        $data = json_encode(['image_url' => $image->url]);
        $result = curl_get_statuscode($url, $data, $headers);

        if (!$result) {
            return FALSE;
        }

        return $result;
    }
}