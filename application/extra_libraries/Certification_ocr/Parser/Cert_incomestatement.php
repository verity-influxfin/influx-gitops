<?php

namespace Certification_ocr\Parser;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 近三年損益表
 */
class Cert_incomestatement extends Ocr_parser_base
{
    protected $task_path = '/ocr/income_statement';
    protected $task_type = self::TYPE_PARSER;

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        if (empty($this->content['income_statement_image']))
        {
            return [];
        }

        $this->CI->load->model('log/log_image_model');
        $image_log = $this->CI->log_image_model->as_array()->get_many(explode(',', $this->content['income_statement_image']));
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
        $result = [];
        foreach ($task_res_data as $value)
        {
            $result[] = [
                'CompName1' => $value['company_name'], // 近一年損益表營利事業名稱
                'CompId1' => $value['company_tax_id_no'], // 近一年損益表營利事業統一編號
                'IndustryCode1' => $value['89'], // 近一年損益表營業收入分類標準代號
                'AnnualIncome1' => str_replace(',', '', $value['90']), // 近一年損益表營業收入淨額
            ];
        }
        return $result;
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