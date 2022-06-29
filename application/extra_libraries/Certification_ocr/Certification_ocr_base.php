<?php

namespace Certification_ocr;
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;

abstract class Certification_ocr_base implements Certification_ocr_definition
{
    private $api_url;
    private $client;

    protected $CI;
    protected $certification;
    protected $task_path;

    public function __construct($certification)
    {
        $this->CI =& get_instance();
        $this->CI->load->model('user/user_certification_ocr_task_model');
        $this->CI->load->model('log/log_certification_ocr_model');
        $this->certification = $certification;
        $this->api_url = 'http://' . getenv('CERT_OCR_IP') . ':' . getenv('CERT_OCR_PORT');
        $this->client = new Client(['base_uri' => $this->api_url]);
    }

    /**
     * 取得該徵信項的 OCR 任務 id
     * @return string
     */
    public function get_ocr_task_id(): string
    {
        $res = $this->CI->user_certification_ocr_task_model->get_by([
            'user_certification_id' => $this->certification['id']
        ]);

        return $res->task_id ?? '';
    }

    /**
     * 建立該徵信項的 OCR 任務
     * @param array $image_list : 欲解析的圖片 url
     * @return array
     * @throws RequestException
     */
    public function create_ocr_task(array $image_list): array
    {
        try
        {
            $request = $this->client->request('POST', 'task/' . urlencode($this->task_path), [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode([
                    'url_list' => $image_list,
                ])
            ]);
            $res_content = $request->getBody()->getContents();
            $this->_insert_log($request->getStatusCode(), $res_content);

            $res_content = json_decode($res_content, TRUE);
            if (empty($res_content['task_id']))
            {
                $result = FALSE;
            }
            else
            {
                $this->_insert_task($res_content['task_id']);
                $result = TRUE;
            }
        }
        catch (RequestException $e)
        {
            $this->_insert_log($e->getResponse()->getStatusCode(), $e->getResponse()->getBody()->getContents());
            $result = FALSE;
        }
        return $this->_chk_ocr_task_create($result);
    }

    /**
     * 取得該徵信項的 OCR 結果
     * @param $task_id : 任務 id
     * @return array
     * @throws RequestException
     */
    public function get_ocr_task_response($task_id): array
    {
        try
        {
            $request = $this->client->request('GET', "/task/{$task_id}", [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]
            ]);
            $res_content = $request->getBody()->getContents();
            $this->_insert_log($request->getStatusCode(), $res_content);
            $result = json_decode($res_content, TRUE);
        }
        catch (BadResponseException $e)
        {
            $this->_insert_log($e->getResponse()->getStatusCode(), $e->getResponse()->getBody()->getContents());
            $result = FALSE;
        }
        catch (\Exception $e)
        {
            $this->_insert_log($e->getCode(), $e->getMessage());
            $result = FALSE;
        }
        return $this->_chk_ocr_task_response($result);
    }

    /**
     * 取得 OCR 辨識結果
     * @return array
     */
    public function get_result(): array
    {
        $task_id = $this->get_ocr_task_id();
        if (empty($task_id))
        {
            $image_list = $this->get_image_list();
            if (empty($image_list))
            {
                return $this->_return_failure('Empty image list!');
            }
            return $this->create_ocr_task($image_list);
        }

        $res = $this->get_ocr_task_response($task_id);
        if ($res['success'] === FALSE)
        {
            return $res;
        }

        $content = $this->data_mapping($res['data']);
        return $this->_return_success($content, '', 200);
    }

    /**
     * @param string $msg : 回傳的訊息
     * @param string $code
     * @return array
     */
    private function _return_failure(string $msg = '', string $code = ''): array
    {
        return ['success' => FALSE, 'msg' => $msg, 'code' => $code];
    }

    /**
     * @param array $data : 回傳的資料
     * @param string $msg : 回傳的訊息
     * @param string $code
     * @return array
     */
    private function _return_success(array $data = [], string $msg = '', string $code = ''): array
    {
        return ['success' => TRUE, 'data' => $data, 'msg' => $msg, 'code' => $code];
    }

    /**
     * 新增 log 記錄 response 結果
     * @param $res_status
     * @param $res_body
     * @return void
     */
    private function _insert_log($res_status, $res_body)
    {
        $this->CI->log_certification_ocr_model->insert([
            'task_path' => $this->task_path,
            'user_certification_id' => $this->certification['id'],
            'status_code' => $res_status,
            'response' => (string) $res_body,
        ]);
    }

    /**
     * 新增徵信項的 OCR 任務
     * @param $task_id : OCR 任務 id
     * @return void
     */
    private function _insert_task($task_id)
    {
        $this->CI->user_certification_ocr_task_model->insert([
            'user_certification_id' => $this->certification['id'],
            'task_id' => $task_id
        ]);
    }

    /**
     * 檢查 OCR 任務建立是否成功
     * @param bool $res
     * @return array
     */
    private function _chk_ocr_task_create(bool $res): array
    {
        if ($res === TRUE)
        {
            return $this->_return_success([], 'OCR task is created successfully.', 201);
        }
        return $this->_return_failure('OCR task is created unsuccessfully.');
    }

    /**
     * 檢查 OCR 任務解析是否成功
     * @param $res
     * @return array
     */
    private function _chk_ocr_task_response($res): array
    {
        if ($res === FALSE)
        {
            return $this->_return_failure('Exception occurred while attempting to GET task response.');
        }
        if (empty($res['response_body']))
        {
            return $this->_return_failure('Task processing has not done yet.');
        }
        return $this->_return_success($res['response_body']);
    }
}