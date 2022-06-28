<?php

namespace Certification_ocr;
defined('BASEPATH') or exit('No direct script access allowed');

interface Certification_ocr_definition
{
    /**
     * 取得該徵信項的 OCR 任務 id
     * @return string
     */
    public function get_ocr_task_id(): string;

    /**
     * 建立該徵信項的 OCR 任務
     * @param array $image_list : 欲解析的圖片 url
     * @return array
     */
    public function create_ocr_task(array $image_list): array;

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
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array;

    /**
     * 取得 content 中儲存圖片的 key
     * @return string[]
     */
    public function get_image_fields(): array;

    /**
     * 把任務的回傳值填到對應的 content key
     * @param array $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping(array $task_res_data): array;
}