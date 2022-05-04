<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_card_recognition_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
        if (empty(getenv('STUDENT_CARD_IP')) || empty(getenv('STUDENT_CARD_PORT')))
        {
            throw new Exception('can not get student card machine learning ip or port');
        }
        $this->url = 'http://' . getenv('STUDENT_CARD_IP') . ':' . getenv('STUDENT_CARD_PORT') . '/student_card/';
    }

    public function request_student_card_identification($front_image_log, $back_image_log, $famous_only = 'false', $score_threshold = '')
    {
        if (empty($front_image_log) || empty($back_image_log) || ! isset($front_image_log->url) || ! isset($back_image_log->url))
        {
            return FALSE;
        }

        $reference = $front_image_log->id . '-' . $back_image_log->id;
        $image_url_list = array($front_image_log->url, $back_image_log->url);

        if ($score_threshold)
        {
            $url = $this->url . "batch_recognition/task/{$reference}?famous_only={$famous_only}&score_threshold={$score_threshold}";
        }
        else
        {
            $url = $this->url . "batch_recognition/task/{$reference}?famous_only={$famous_only}";
        }
        $headers = [
            'accept: application/json',
            'Content-Type: application/json;'
        ];
        $data = json_encode(['image_url_list' => $image_url_list]);
        $result = curl_get_statuscode($url, $data, $headers);

        if ( ! $result || $result['code'] != 201)
        {
            return FALSE;
        }

        return $result;
    }

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

        $url = $this->url . "batch_recognition/task/{$reference}";
        $result = curl_get_statuscode($url);

        if ( ! $result || $result['code'] != 200)
        {
            return FALSE;
        }

        if (isset($result['response']['responseItem']['status_code']) && $result['response']['responseItem']['status_code'] == 200)
        {
            $rs = $this->get_result_by_score($result);
            if ($rs)
            {
                $res['university'] = isset($rs['university_name']) ? $rs['university_name'] : '';
                $res['student_id'] = isset($rs['student_id']) ? $rs['student_id'] : '';
                $res['student_department'] = isset($rs['student_department']) ? $rs['student_department'] : '';
                $res['student_academic_degree'] = isset($rs['student_academic_degree']) ? $rs['student_academic_degree'] : '';
            }
            $res['status'] = 1;
        }
        $res['spent_time'] = microtime(true)-$t;
        return $res;
    }

    public function get_student_card_recognition($image)
    {
        if (empty($image))
        {
            return FALSE;
        }

        $url = $this->url . 'recognition';
        $headers = [
            'accept: application/json',
            'Content-Type: application/json;'
        ];
        $data = json_encode(['image_url' => $image->url]);
        $result = curl_get_statuscode($url, $data, $headers);

        if ( ! $result)
        {
            return FALSE;
        }

        return $result;
    }

    private function get_result_by_score($result)
    {
        $front_list = $result['response']['responseItem']['body']['studentCardRecognitionOut_list'][0];
        $back_list  = $result['response']['responseItem']['body']['studentCardRecognitionOut_list'][1];

        $f_0_score  = $front_list['compare_result_list'][0]['score'];
        $f_1_score  = $front_list['compare_result_list'][1]['score'];
        $b_0_score  = $back_list['compare_result_list'][0]['score'];
        $b_1_score  = $back_list['compare_result_list'][1]['score'];

        // 找出符合標準的命中結果
        if ( ! $f_1_score || ! $b_1_score)
        {
            $front_rate = $f_0_score > 0.1 ? $f_0_score : 0;
            $back_rate  = $b_0_score > 0.1 ? $b_0_score : 0;
        }
        else
        {
            $front_rate = $f_0_score / $f_1_score > 1.3 ? $f_0_score / $f_1_score : 0;
            $back_rate  = $b_0_score / $b_1_score > 1.3 ? $b_0_score / $b_1_score : 0;
        }

        // 回傳符合標準的命中結果
        if ($front_rate || $back_rate)
        {
            if ($front_rate > $back_rate)
            {
                return $front_list;
            }
            else
            {
                return $back_list;
            }
        }
        return FALSE;
    }
}