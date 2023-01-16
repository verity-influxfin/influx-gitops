<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 學生身份認證
 */
class Cert_student extends Ocr_parser_base
{
    protected $task_path = '/student_card/recognition';
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
     * 取得 OCR 子系統 port
     * @return string
     */
    protected function get_ocr_port(): string
    {
        return getenv('STUDENT_CARD_PORT');
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

        return $task_res_data;
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
            'front_side_url' => $get_image['data']['front_side_url'],
            'back_side_url' => $get_image['data']['back_side_url'],
            'famous_only' => FALSE,
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
        if (empty($this->content['front_image']) || empty($this->content['back_image']))
        {
            return $this->return_failure('Empty image list!');
        }
        return $this->return_success(['front_side_url' => $this->content['front_image'], 'back_side_url' => $this->content['back_image']]);
    }
}