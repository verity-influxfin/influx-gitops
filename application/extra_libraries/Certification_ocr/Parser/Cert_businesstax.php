<?php

namespace Certification_ocr\Parser;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 近三年401/403/405表
 */
class Cert_businesstax extends Ocr_parser_base
{
    protected $task_path = '/ocr/table_401';
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
            'lastOneYearInvoiceImageM1M2',
            'lastOneYearInvoiceImageM3M4',
            'lastOneYearInvoiceImageM5M6',
            'lastOneYearInvoiceImageM7M8',
            'lastOneYearInvoiceImageM9M10',
            'lastOneYearInvoiceImageM11M12',
            'lastTwoYearInvoiceImageM1M2',
            'lastTwoYearInvoiceImageM3M4',
            'lastTwoYearInvoiceImageM5M6',
            'lastTwoYearInvoiceImageM7M8',
            'lastTwoYearInvoiceImageM9M10',
            'lastTwoYearInvoiceImageM11M12',
            'lastThreeYearInvoiceImageM1M2',
            'lastThreeYearInvoiceImageM3M4',
            'lastThreeYearInvoiceImageM5M6',
            'lastThreeYearInvoiceImageM7M8',
            'lastThreeYearInvoiceImageM9M10',
            'lastThreeYearInvoiceImageM11M12',
            'lastFourYearInvoiceImageM1M2',
            'lastFourYearInvoiceImageM3M4',
            'lastFourYearInvoiceImageM5M6',
            'lastFourYearInvoiceImageM7M8',
            'lastFourYearInvoiceImageM9M10',
            'lastFourYearInvoiceImageM11M12'
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
            preg_match('/(last(One|Two|Three|Four)YearInvoice)Image(M\dM\d)/', $image_key, $matches);
            if (empty($matches))
            {
                continue;
            }
            $year = $matches[2];
            $month = $matches[3];
            $result[] = [
                'img_url' => $value['img_url'] ?? '',
                "businessTaxLast{$year}Year" => $value['yyy'] ?? '',
                "last{$year}YearInvoiceAmount{$month}" => empty($value['21']) || empty($value['22'])
                    ? ''
                    : (int) str_replace(',', '', $value['21']) + (int) str_replace(',', '', $value['22']),
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