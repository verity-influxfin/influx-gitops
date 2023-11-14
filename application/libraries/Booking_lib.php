<?php

use GuzzleHttp\Client;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 房貸子系統預約功能
 */
class Booking_lib
{
    protected $CI;
    protected $client;

    public function __construct()
    {
        $this->CI = &get_instance();

        $api_url = 'http://' . getenv('CERT_OCR_IP') . ':' . CERT_OCR_HOME_LOAN_BOOKING_PORT;
        $this->client = new Client(['base_uri' => $api_url]);
    }

    /**
     * 取得完整的預約時間表、預約情況
     * @param string $start_date : 開始時間
     * @param string $end_date : 結束時間
     * @return array
     */
    public function get_whole_booking_timetable(string $start_date = '', string $end_date = ''): array
    {
        try
        {
            $start_date = empty($start_date) ? date('Y-m-d') : (new DateTimeImmutable($start_date))->format('Y-m-d');
            $end_date = empty($end_date) ? date('Y-m-d') : (new DateTimeImmutable($end_date))->format('Y-m-d');

            $request = $this->client->request('GET', 'bookable_session', [
                'query' => [
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ]
            ]);
            $res_content = $request->getBody()->getContents();
            $result = json_decode($res_content, TRUE);
            if (json_last_error() !== JSON_ERROR_NONE)
            {
                return ['result' => 'SUCCESS', 'data' => ['booking_table' => []]];
            }
            return ['result' => 'SUCCESS', 'data' => ['booking_table' => array_column($result, 'session_list', 'date')]];
        }
        catch (Exception $e)
        {
            return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
        }
        catch (\GuzzleHttp\Exception\GuzzleException $e)
        {
            return ['result' => 'ERROR', 'error' => SUB_SYSTEM_REQUEST_ERROR];
        }
    }

    /**
     * 取得對應案件/使用者的預約情況
     * @param $target_id : 案件 ID
     * @param $user_id : 使用者 ID
     * @return array
     */
    public function get_booked_list_by_user($target_id, $user_id): array
    {
        try
        {
            if (empty($target_id))
            {
                return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
            }

            $this->CI->load->model('loan/target_model');
            $target_exist = $this->CI->target_model->count_by([
                'user_id' => $user_id,
                'id' => $target_id,
            ]);
            if ($target_exist < 1)
            {
                return ['result' => 'ERROR', 'error' => PRODUCT_NOT_EXIST];
            }

            $request = $this->client->request('GET', 'booked_session', [
                'query' => [
                    'target_id_int' => $target_id,
                    'user_id_int' => $user_id,
                ]
            ]);
            $res_content = $request->getBody()->getContents();
            $result = json_decode($res_content, TRUE);
            if (json_last_error() !== JSON_ERROR_NONE)
            {
                return ['result' => 'SUCCESS', 'data' => ['booking_table' => []]];
            }
            return ['result' => 'SUCCESS', 'data' => ['booking_table' => $result]];
        }
        catch (Exception $e)
        {
            return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
        }
        catch (\GuzzleHttp\Exception\GuzzleException $e)
        {
            return ['result' => 'ERROR', 'error' => SUB_SYSTEM_REQUEST_ERROR];
        }
    }

    public function get_booked_list_by_target($target_id): array
    {
        try
        {
            if (empty($target_id))
            {
                return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
            }

            $this->CI->load->model('loan/target_model');
            $target_info = $this->CI->target_model->get_user_id_by_id($target_id);
            if (empty($target_info))
            {
                return ['result' => 'ERROR', 'error' => PRODUCT_NOT_EXIST];
            }

            $request = $this->client->request('GET', 'booked_session', [
                'query' => [
                    'target_id_int' => $target_id,
                    'user_id_int' => $target_info['user_id'] ?? '',
                ]
            ]);
            $res_content = $request->getBody()->getContents();
            $result = json_decode($res_content, TRUE);
            if (json_last_error() !== JSON_ERROR_NONE)
            {
                return ['result' => 'SUCCESS', 'data' => ['booking_table' => []]];
            }
            return ['result' => 'SUCCESS', 'data' => ['booking_table' => $result]];
        }
        catch (Exception $e)
        {
            return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
        }
        catch (\GuzzleHttp\Exception\GuzzleException $e)
        {
            return ['result' => 'ERROR', 'error' => SUB_SYSTEM_REQUEST_ERROR];
        }
    }

    /**
     * 新增一筆預約
     * @param $target_id : 案件 ID
     * @param $user_id : 使用者 ID
     * @param $date : 日期
     * @param $time : 時間
     * @param string $title : 標題
     * @return array|string[]
     */
    public function create_booking($target_id, $user_id, $date, $time, string $title = ''): array
    {
        try
        {
            if (empty($date) || empty($time))
            {
                return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
            }

            if ( ! empty($target_id))
            {
                $this->CI->load->model('loan/target_model');
                $target_exist = $this->CI->target_model->count_by([
                    'user_id' => $user_id,
                    'id' => $target_id,
                ]);
                if ($target_exist < 1)
                {
                    return ['result' => 'ERROR', 'error' => PRODUCT_NOT_EXIST];
                }
            }

            $request = $this->client->request('POST', 'booking', [
                'body' => json_encode([
                    'date' => $date,
                    'session_name' => $time,
                    'target_id_int' => $target_id,
                    'user_id_int' => $user_id,
                    'title' => $title
                ])
            ]);
            $res_content = $request->getBody()->getContents();
            $result = json_decode($res_content, TRUE);

            if ( ! empty($result['_id']))
            {
                return ['result' => 'SUCCESS', 'data' => $result];
            }
            if ( ! empty($result['detail']) && $request->getStatusCode() == 400)
            {
                return ['result' => 'ERROR', 'error' => PRODUCT_CANNOT_BOOK_TIME];
            }

            return ['result' => 'ERROR', 'error' => SUB_SYSTEM_REQUEST_ERROR];
        }
        catch (Exception|\GuzzleHttp\Exception\GuzzleException $e)
        {
            return ['result' => 'ERROR', 'error' => SUB_SYSTEM_REQUEST_ERROR];
        }
    }

    /**
     * 取消一筆預約
     * @param $booking_id : 預約 ID
     * @return array|string[]
     */
    public function cancel_booking($booking_id): array
    {
        try
        {
            if (empty($booking_id))
            {
                return ['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT];
            }

            $request = $this->client->request('DELETE', "booking/{$booking_id}");

            if ($request->getStatusCode() != 200)
            {
                return ['result' => 'ERROR', 'error' => '取消預約失敗'];
            }
            return ['result' => 'SUCCESS'];
        }
        catch (Exception|\GuzzleHttp\Exception\GuzzleException $e)
        {
            return ['result' => 'ERROR', 'error' => SUB_SYSTEM_REQUEST_ERROR];
        }
    }
}
