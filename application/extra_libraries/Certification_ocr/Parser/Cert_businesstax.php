<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 近三年401/403/405表
 */
class Cert_businesstax extends Ocr_parser_base
{
    protected $task_path = '/ocr/table_401';
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
            'LastOneYearInvoiceImageM1M2',
            'LastOneYearInvoiceImageM3M4',
            'LastOneYearInvoiceImageM5M6',
            'LastOneYearInvoiceImageM7M8',
            'LastOneYearInvoiceImageM9M10',
            'LastOneYearInvoiceImageM11M12',
            'LastTwoYearInvoiceImageM1M2',
            'LastTwoYearInvoiceImageM3M4',
            'LastTwoYearInvoiceImageM5M6',
            'LastTwoYearInvoiceImageM7M8',
            'LastTwoYearInvoiceImageM9M10',
            'LastTwoYearInvoiceImageM11M12',
            'LastThreeYearInvoiceImageM1M2',
            'LastThreeYearInvoiceImageM3M4',
            'LastThreeYearInvoiceImageM5M6',
            'LastThreeYearInvoiceImageM7M8',
            'LastThreeYearInvoiceImageM9M10',
            'LastThreeYearInvoiceImageM11M12',
            'LastFourYearInvoiceImageM1M2',
            'LastFourYearInvoiceImageM3M4',
            'LastFourYearInvoiceImageM5M6',
            'LastFourYearInvoiceImageM7M8',
            'LastFourYearInvoiceImageM9M10',
            'LastFourYearInvoiceImageM11M12'
        ];
    }
}