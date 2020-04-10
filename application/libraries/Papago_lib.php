<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Papago_lib
{

    private $config = array();

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_papago_model');
    }

    //Papago API Face - Detect
    public function detect($url, $user_id = 0, $cer_id = 0)
    {
        $api_url = 'https://api.face8.ai/api/detect';
        $data = [
            'image_base64' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($url)),
            'min_face_size' => 48,
            'return_attributes' => 'liveness',
        ];
        $result = $this->papago_curl($api_url, $data);
        $this->log_event('detect', $user_id, $cer_id, $result, $url);
        return $result;
    }

    //Papago API Face - compare
    public function compare($token=[], $user_id = 0, $cer_id = 0)
    {
        $api_url = 'https://api.face8.ai/api/compare';
        $data = [
            'face_token1' => $token[0],
            'face_token2' => $token[1],
        ];
        $result = $this->papago_curl($api_url, $data);
        $this->log_event('compare', $user_id, $cer_id, $result, $token);
        return $result;
    }

    private function papago_curl($api_url, $data)
    {

        $data['api_key'] = '4d6343a7f514420fa97cc0154bc6189b';
        $data['api_secret'] = '09b073190360481695691dbdd9a4c2cd';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, 1);

    }

    private function log_event($type, $user_id, $cer_id, $rs, $image)
    {
        $log_data = array(
            "type" => $type,
            "user_id" => $user_id,
            "cer_id" => $cer_id,
            "request" => json_encode($image),
            "response" => json_encode($rs),
        );
        $this->CI->log_papago_model->insert($log_data);
    }

}