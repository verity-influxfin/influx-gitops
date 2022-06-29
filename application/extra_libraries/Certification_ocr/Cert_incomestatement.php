<?php

namespace Certification_ocr;

/**
 * 近三年損益表
 */
class Cert_incomestatement extends Certification_ocr_base
{
    protected $task_path = '/ocr/income_statement';

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        $content = json_decode($this->certification['content'], TRUE);
        if (empty($content['income_statement_image']))
        {
            return [];
        }

        $this->CI->load->model('log/log_image_model');
        $image_log = $this->CI->log_image_model->as_array()->get_many(explode(',', $content['income_statement_image']));
        $image_list = [];
        array_walk($image_log, function ($element) use (&$image_list) {
            if ( ! empty($element['url']))
            {
                $image_list[] = $element['url'];
            }
        });
        return $image_list;
    }

    /**
     * 把任務的回傳值填到對應的 content key
     * @param $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping($task_res_data): array
    {
        return [
            'CompName1' => $task_res_data['company_name'], // 近一年損益表營利事業名稱
            'CompId1' => $task_res_data['company_tax_id_no'], // 近一年損益表營利事業統一編號
            'IndustryCode1' => $task_res_data['89'], // 近一年損益表營業收入分類標準代號
            'AnnualIncome1' => str_replace(',', '', $task_res_data['90']), // 近一年損益表營業收入淨額
        ];
    }
}