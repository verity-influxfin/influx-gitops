<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 資產負債表
 */
class Cert_balancesheet extends Ocr_parser_base
{
    protected $task_path = '/ocr/balance_sheet';
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

        return [
            'assetsAmount' => empty($task_res_data['1000']) ? '' : (int) str_replace(',', '', $task_res_data['1000']), // 資產總額
            'liabilitiesAmount' => empty($task_res_data['2000']) ? '' : (int) str_replace(',', '', $task_res_data['2000']), // 負債總額
            'equityAmount' => empty($task_res_data['3000']) ? '' : (int) str_replace(',', '', $task_res_data['3000']), // 權益總額
            'liabEquityAmount' => empty($task_res_data['9000']) ? '' : (int) str_replace(',', '', $task_res_data['9000']), // 負債及權益總額
        ];
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
            return $this->return_failure('Cannot find image_list.');
        }
        if (empty($this->content['balance_sheet_image']))
        {
            return $this->return_failure('Empty image list!');
        }
        $balance_sheet_image = is_array($this->content['balance_sheet_image']) ? $this->content['balance_sheet_image'] : [$this->content['balance_sheet_image']];
        return $this->return_success(['url_list' => $balance_sheet_image]);
    }
}