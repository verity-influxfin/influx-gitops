<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Student_card_recognition_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        if (empty(getenv('STUDENT_CARD_IP')) || empty(getenv('STUDENT_CARD_PORT')))
        {
            throw new Exception('can not get student card ip or port');
        }
        $this->url = 'http://' . getenv('STUDENT_CARD_IP') . ':' . getenv('STUDENT_CARD_PORT') . '/task/';
    }

    /**
     * 學生證ocr辨識
     *
     * @param $front_image_log 正面圖片
     * @param $back_image_log 背面圖片
     * @param $famous_only 是否只找名校貸
     *
     * @return Array|Bool result 結果
     */
    public function request_student_card_identification($front_image_log, $back_image_log, $famous_only = FALSE)
    {
        if (! isset($front_image_log->url) || ! isset($back_image_log->url) || ! isset($front_image_log->id) || ! isset($back_image_log->id))
        {
            return FALSE;
        }

        $reference = $front_image_log->id . '-' . $back_image_log->id;
        $url = $this->url . "student_card/recognition?task_id={$reference}";
        $headers = [
            'accept: application/json',
            'Content-Type: application/json;'
        ];
        $data = json_encode([
            'front_side_url' => $front_image_log->url,
            'back_side_url' => $back_image_log->url,
            'famous_only' => $famous_only
        ]);
        $result = curl_get_statuscode($url, $data, $headers);

        if ( ! $result || $result['code'] != 201)
        {
            return FALSE;
        }
        return $result;
    }

    /**
     * 取得學生證ocr辨識結果
     *
     * @param string $reference 參考值
     *
     * @return Array|Bool res 結果
     */
    public function get_student_card_identification($reference)
    {
        $t = microtime(true);
        $res = [
            'status' => 0,
            'student_id' => '',
            'student_department' => '',
            'student_academic_degree' => '',
            'university' => '',
            'spent_time' => ''
        ];
        if (empty($reference))
        {
            return FALSE;
        }

        $url = $this->url . "{$reference}";
        $result = curl_get_statuscode($url);

        if ( ! $result || ! $result['response'] || $result['code'] != 200)
        {
            return FALSE;
        }

        if (isset($result['response']['response_body']) && $result['response']['response_status'] == 200)
        {
            $res_body = $result['response']['response_body'];

            $res['university'] = $res_body['university']['name'] ?? '';
            $res['student_id'] = $res_body['student']['student_id'] ?? '';
            $res['student_department'] = $res_body['student']['department'] ?? '';
            $res['student_academic_degree'] = $res_body['student']['academic_degree'] ?? '';
            $res['status'] = 1;
            $res['spent_time'] = microtime(true)-$t;
        }

        return $res;
    }
}