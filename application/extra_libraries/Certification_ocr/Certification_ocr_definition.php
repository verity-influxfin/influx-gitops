<?php

namespace Certification_ocr;
defined('BASEPATH') or exit('No direct script access allowed');

interface Certification_ocr_definition
{
    /**
     * 取得該徵信項的 OCR 任務 id
     * @param $type : 辨識類型
     * @return string
     */
    public function get_ocr_task_id($type): string;

    /**
     * 建立該徵信項的 OCR 任務
     * @param $body : 欲解析的圖片 url 及相關資訊
     * @return array
     */
    public function create_ocr_task($body): array;

    /**
     * 取得該徵信項的 OCR 結果
     * @param $task_id : 任務 id
     * @return array
     */
    public function get_ocr_task_response($task_id): array;

    /**
     * 取得 OCR 辨識結果
     * @return array
     */
    public function get_result(): array;

    /**
     * 取得 OCR 欲解析的圖片及其相關資訊
     * @return array
     */
    public function get_request_body(): array;

    /**
     * 把任務的回傳值填到對應的 content key
     * @param array $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping(array $task_res_data): array;
}