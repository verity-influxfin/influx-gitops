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
        $image_list = [];
        $image_fields = $this->get_image_fields();
        array_walk($this->content, function ($element, $key) use (&$image_list, &$image_fields) {
            if (in_array($key, $image_fields))
            {
                if ( ! empty($element))
                {
                    $image_list[] = $element;
                }
            }
        });
        return $image_list;
    }

    /**
     * 取得 content 中儲存圖片的 key
     * @return string[]
     */
    private function get_image_fields(): array
    {
        return [
            'nearly_a_year_image_url',
            'nearly_two_year_image_url',
            'nearly_three_year_image_url',
        ];
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
            $image_key = array_search($value['img_url'], $this->content, TRUE);
            if ($image_key === FALSE)
            {
                continue;
            }
            preg_match('/nearly_(\w+)_year_image_url/', $image_key, $matches);
            if (empty($matches))
            {
                continue;
            }
            $year = array_search($matches[1], [1 => 'a', 2 => 'two', 3 => 'three'], TRUE);
            $result[] = [
                'img_url' => $value['img_url'] ?? '',
                "CompName{$year}" => $value['company_name'] ?? '', // 近一年損益表營利事業名稱
                "CompId{$year}" => $value['company_tax_id_no'] ?? '', // 近一年損益表營利事業統一編號
                "IndustryCode{$year}" => $value['89'], // 近一年損益表營業收入分類標準代號
                "AnnualIncome{$year}" => empty($value['90']) ? '' : (int) str_replace(',', '', $value['90']), // 近一年損益表營業收入淨額
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