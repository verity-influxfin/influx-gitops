<?php

namespace Certification_ocr;
defined('BASEPATH') or exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;

abstract class Certification_ocr_base implements Certification_ocr_definition
{
    const TYPE_PARSER = 1;
    const TYPE_MARKER = 2;

    private $client;

    protected $api_url;
    protected $CI;
    protected $certification;
    protected $task_path;
    // OCR 任務類型
    protected $task_type;
    protected $task_type_list = [
        self::TYPE_PARSER, // 一般的資料解析
        self::TYPE_MARKER, // 標記圖片上的關鍵訊息
    ];

    public function __construct($certification)
    {
        $this->CI =& get_instance();
        $this->CI->load->model('user/user_certification_ocr_task_model');
        $this->CI->load->model('log/log_certification_ocr_model');
        $this->certification = $certification;
        $this->client = new Client(['base_uri' => $this->api_url]);
    }

    /**
     * 取得 OCR 子系統 port
     * @return string
     */
    abstract protected function get_ocr_port(): string;

    /**
     * 取得該徵信項的 OCR 任務 id
     * @param $type
     * @return string
     */
    public function get_ocr_task_id($type): string
    {
        $res = $this->CI->user_certification_ocr_task_model->get_by([
            'user_certification_id' => $this->certification['id'],
            'type' => $type
        ]);

        return $res->task_id ?? '';
    }

    /**
     * 建立該徵信項的 OCR 任務
     * @param $body : 欲解析的圖片 url 及相關資訊
     * @return array
     * @throws RequestException
     */
    public function create_ocr_task($body): array
    {
        try
        {
            $request = $this->client->request('POST', 'task/' . urlencode($this->task_path), [
                'headers' => [
                    'accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($body)
            ]);
            $res_content = $request->getBody()->getContents();
            $this->insert_log($request->getStatusCode(), $res_content);

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
            $response = $e->getResponse();
            if ($response === null) {
                $statusCode = 500;
                $content = '';
            } else {
                $statusCode = $response->getStatusCode();
                $content = $response->getBody()->getContents();
            }
            $this->insert_log($statusCode, $content);
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
            $this->insert_log($request->getStatusCode(), $res_content);
            $result = json_decode($res_content, TRUE);
        }
        catch (BadResponseException $e)
        {
            $this->insert_log($e->getResponse()->getStatusCode(), $e->getResponse()->getBody()->getContents());
            $result = FALSE;
        }
        catch (\Exception $e)
        {
            $this->insert_log($e->getCode(), $e->getMessage());
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
        // OCR 任務類型
        if ($this->get_task_type() === FALSE)
        {
            return $this->return_failure('Cannot figure out the task type!');
        }

        // OCR 任務 id
        $task_id = $this->get_ocr_task_id($this->task_type);
        if (empty($task_id))
        {
            $body = $this->get_request_body();
            if ( ! isset($body['success']) || $body['success'] !== TRUE || empty($body['data']))
            {
                return $this->return_failure($body['msg'] ?? 'Cannot get request body!');
            }
            return $this->create_ocr_task($body['data']);
        }

        // OCR 任務 response
        $res = $this->get_ocr_task_response($task_id);
        if ($res['success'] === FALSE || empty($res['data']))
        {
            return $res;
        }

        $content = $this->data_mapping($res['data']);
        return $this->return_success($content, $content['msg'] ?? '', 200);
    }

    /**
     * @param string $msg : 回傳的訊息
     * @param string $code
     * @return array
     */
    public function return_failure(string $msg = '', string $code = ''): array
    {
        return ['success' => FALSE, 'msg' => $msg, 'code' => $code];
    }

    /**
     * @param array $data : 回傳的資料
     * @param string $msg : 回傳的訊息
     * @param string $code
     * @return array
     */
    public function return_success(array $data = [], string $msg = '', string $code = ''): array
    {
        return ['success' => TRUE, 'data' => $data, 'msg' => $msg, 'code' => $code];
    }

    /**
     * 新增 log 記錄 response 結果
     * @param $res_status
     * @param $res_body
     * @return void
     */
    protected function insert_log($res_status, $res_body)
    {
        $res_body_ary = json_decode($res_body, TRUE);
        $this->CI->log_certification_ocr_model->insert([
            'task_path' => $res_body_ary['api_url_path'] ?? $this->task_path,
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
            'task_id' => $task_id,
            'type' => $this->task_type,
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
            return $this->return_success([], 'OCR task is created successfully.', 201);
        }
        return $this->return_failure('OCR task is created unsuccessfully.');
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
            return $this->return_failure('Exception occurred while attempting to GET task response.');
        }
        if (empty($res['response_body']) || ! is_array($res['response_body']))
        {
            return $this->return_success([], 'Task processing has not done yet.', 202);
        }
        return $this->return_success($res['response_body']);
    }

    /**
     * 取得 OCR 任務類型
     * @return bool
     */
    public function get_task_type(): bool
    {
        if ( ! in_array($this->task_type, $this->task_type_list))
        {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @param $value
     * @return int
     */
    public function get_number_only($value): int
    {
        if ( ! is_string($value) && ! is_numeric($value))
        {
            return '';
        }

        return (int) preg_replace(
            '/(?<=([\x{4e00}-\x{9fa5}]))\s+(?=[\x{4e00}-\x{9fa5}])/u',
            '',
            str_replace(["\n", ','], '', trim($value))
        );
    }
}
