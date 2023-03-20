<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 近三年損益表
 */
class Cert_incomestatement extends Ocr_parser_base
{
    protected $task_path = '/ocr/income_statement';
    protected $task_type = self::TYPE_PARSER;
    private $content;

    public function __construct($certification)
    {
        parent::__construct($certification);
        if ( ! empty($this->certification['content']))
        {
            $this->content = json_decode($this->certification['content'], TRUE);
        }
        else
        {
            $this->content = [];
        }
    }

    /**
     * 把任務的回傳值填到對應的 content key
     * @param $task_res_data : 任務解析完的 response_body
     * @return array
     */
    public function data_mapping($task_res_data): array
    {
        if (empty($task_res_data))
        {
            return [];
        }

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
            $last_year_label = [1 => 'One', 2 => 'Two', 3 => 'Three'];
            $result[] = [
                'img_url' => $value['img_url'] ?? '',
                "CompName{$year}" => $value['company_name'] ?? '', // 近一年損益表營利事業名稱
                "CompId{$year}" => $value['company_tax_id_no'] ?? '', // 近一年損益表營利事業統一編號
                "IndustryCode{$year}" => $value['89'], // 近一年損益表營業收入分類標準代號
                "AnnualIncome{$year}" => empty($value['90']) ? '' : (int) str_replace(',', '', $value['90']), // 近一年損益表營業收入淨額
                "last{$last_year_label[$year]}YearRevenue" => empty($value['01'][0]) ? '' : (int) str_replace(',', '', $value['01'][0]), // 近一年度營業額
                "last{$last_year_label[$year]}YearCostOfGoodsSold" => empty($value['05'][0]) ? '' : (int) str_replace(',', '', $value['05'][0]), // 近一年銷貨成本
                "last{$last_year_label[$year]}YearGrossMargin" => empty($value['07'][0]) ? '' : (double) str_replace([',', '%'], '', $value['07'][0]), // 近一年毛利率
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
        $get_image = $this->get_image_list();
        if ($get_image['success'] === FALSE)
        {
            return $this->return_failure($get_image['msg']);
        }

        return $this->return_success([
            'url_list' => $get_image['data']['url_list'],
        ]);
    }

    /**
     * 取得欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        $cert = Certification_factory::get_instance_by_model_resource($this->certification);
        if (empty($cert))
        {
            return $this->return_failure('Cannot find image list.');
        }
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
        if (empty($image_list))
        {
            return $this->return_failure('Empty image list!');
        }
        return $this->return_success(['url_list' => $image_list]);
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
}