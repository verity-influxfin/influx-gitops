<?php

namespace Certification_ocr;

/**
 * 資產負債表
 */
class Cert_balancesheet extends Certification_ocr_base
{
    protected $task_path = '/ocr/balance_sheet';

    /**
     * 回傳欲解析的圖片 url
     * @return array
     */
    public function get_image_list(): array
    {
        $content = json_decode($this->certification['content'], TRUE);
        if ( ! empty($content['balance_sheet_image']))
        {
            return $content['balance_sheet_image'];
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
}