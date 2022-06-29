<?php

namespace Certification_ocr;

/**
 * 近三年401/403/405表
 */
class Cert_businesstax extends Certification_ocr_base
{
    protected $task_path = '/ocr/table_401';

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        $image_list = [];
        $content = json_decode($this->certification['content'], TRUE);
        $image_fields = $this->get_image_fields();
        array_walk($content, function ($element, $key) use (&$image_list, &$image_fields) {
            if (in_array($key, $image_fields))
            {
                unset($image_fields[$key]);
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
        // todo: OCR只辨識第一張，問欄位值怎麼處理
        // https://dev-c.influxfin.com/doc/#api-Certification-PostCertificationBusinessTax
        // 預設填入近一年申報的相關資料
        // todo: 開立發票金額是對應文件上的哪個欄位
        return [
            'businessTaxLastOneYear' => $task_res_data['yyy'],
        ];
    }
}