<?php

namespace Certification_ocr\Parser;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 設立(變更)事項登記表
 */
class Cert_governmentauthorities extends Ocr_parser_base
{
    protected $task_path = '/ocr/company_modify_or_establish_sheet';
    protected $task_type = self::TYPE_PARSER;

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        if ( ! empty($this->content['governmentauthorities_image']))
        {
            return is_array($this->content['governmentauthorities_image']) ? $this->content['governmentauthorities_image'] : [$this->content['governmentauthorities_image']];
        }
        return [];
    }

    /**
     * 把任務的回傳值填到對應的 content key
     * @param $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping($task_res_data): array
    {
        return [
            'compName' => $task_res_data['company_name'] ?? '',
            'compId' => $task_res_data['company_tax_id_no'] ?? '',
            'stampDate' => $task_res_data['company_articles_date'] ?? '',
            'prName' => $task_res_data['manager_name'] ?? '',
        ];
    }

    /**
     * 取得 OCR 欲解析的圖片及其相關資訊
     * @return array
     */
    public function get_request_body(): array
    {
        $url_list = $this->get_image_list();
        if (empty($url_list))
        {
            return $this->return_failure('Empty image list!');
        }

        return $this->return_success([
            'url_list' => $url_list,
        ]);
    }
}