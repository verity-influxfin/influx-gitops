<?php

namespace Certification_ocr;

/**
 * 設立(變更)事項登記表
 */
class Cert_governmentauthorities extends Certification_ocr_base
{
    protected $task_path = '/ocr/company_modify_or_establish_sheet';

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        $content = json_decode($this->certification['content'], TRUE);
        if ( ! empty($content['governmentauthorities_image']))
        {
            return $content['governmentauthorities_image'];
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
            'compName' => $task_res_data['company_name'],
            'compId' => $task_res_data['company_tax_id_no'],
            'stampDate' => $task_res_data['company_articles_date'],
            'prName' => $task_res_data['manager_name'],
        ];
    }
}