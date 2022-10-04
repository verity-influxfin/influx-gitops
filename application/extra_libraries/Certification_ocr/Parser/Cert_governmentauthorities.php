<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 設立(變更)事項登記表
 */
class Cert_governmentauthorities extends Ocr_parser_base
{
    protected $task_path = '/ocr/company_modify_or_establish_sheet';
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
            'compName' => $task_res_data['company_name'] ?? '',
            'compId' => $task_res_data['company_tax_id_no'] ?? '',
            'compDate' => empty($task_res_data['company_articles_date']) ? '' : preg_replace(
                ['/年/', '/月/', '/日/'],
                ['.', '.', ''],
                $task_res_data['company_articles_date']
            ),
            'prName' => $task_res_data['manager_name'] ?? '',
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
            return $this->return_failure('Cannot find image list.');
        }
        if (empty($this->content['governmentauthorities_image']))
        {
            return $this->return_failure('Empty image list!');
        }
        $governmentauthorities_image = is_array($this->content['governmentauthorities_image']) ? $this->content['governmentauthorities_image'] : [$this->content['governmentauthorities_image']];
        return $this->return_success(['url_list' => $governmentauthorities_image]);
    }
}