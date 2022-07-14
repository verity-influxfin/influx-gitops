<?php

namespace Certification_ocr\Parser;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 資產負債表
 */
class Cert_balancesheet extends Ocr_parser_base
{
    protected $task_path = '/ocr/balance_sheet';
    protected $task_type = self::TYPE_PARSER;

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        if ( ! empty($this->content['balance_sheet_image']))
        {
            return is_array($this->content['balance_sheet_image']) ? $this->content['balance_sheet_image'] : [$this->content['balance_sheet_image']];
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
            'assetsAmount' => (int) str_replace(',', '', $task_res_data['1000']), // 資產總額
            'liabilitiesAmount' => (int) str_replace(',', '', $task_res_data['2000']), // 負債總額
            'equityAmount' => (int) str_replace(',', '', $task_res_data['3000']), // 權益總額
            'liabEquityAmount' => (int) str_replace(',', '', $task_res_data['9000']), // 負債及權益總額
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