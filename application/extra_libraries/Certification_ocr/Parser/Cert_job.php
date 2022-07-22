<?php

namespace Certification_ocr\Parser;

use Certification\Certification_factory;

/**
 * OCR 辨識
 * Type : 一般的資料解析
 * Cert : 工作收入證明
 */
class Cert_job extends Ocr_parser_base
{
    protected $task_path = '/ocr/labor_pdf';
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
        if (empty($task_res_data)) return [];

        $insurance_list = [];
        foreach ($task_res_data['laborRowData_list'] ?? [] as $value)
        {
            $insurance_list[] = [
                'insuranceId' => $value['insurance_no_str'],
                'companyName' => $value['unit_str'],
                'detailList' => [
                    [
                        'insuranceSalary' => $value['salary_str'] ?? '',
                        'startDate' => $value['start_date_str'] ?? '',
                        'endDate' => $value['end_date_str'] ?? '',
                        'comment' => $value['remark_str'] ?? '',
                        'arrearage' => $value['arrear_str'] ?? '',
                    ]
                ]
            ];
        }

        return [
            'pageList' => [
                [
                    'personId' => $task_res_data['person_id_str'] ?? '',
                    'name' => $task_res_data['name'] ?? '',
                    'birthday' => $task_res_data['birth_date_str'] ?? '',
                    'reportDate' => $task_res_data['end_query_date_str'] ?? '',
                    'insuranceList' => $insurance_list
                ]
            ]
        ];
    }

    /**
     * 取得 OCR 欲解析的圖片及其相關資訊
     * @return array
     */
    public function get_request_body(): array
    {
        $get_file = $this->get_pdf_url();
        if ($get_file['success'] === FALSE)
        {
            return $this->return_failure($get_file['msg']);
        }

        return $this->return_success([
            'pdf_url' => $get_file['data']['pdf_url'],
        ]);
    }

    /**
     * 取得欲解析的 pdf 檔案 URL
     * @return array
     */
    public function get_pdf_url(): array
    {
        $cert = Certification_factory::get_instance_by_model_resource($this->certification);
        if (empty($cert))
        {
            return $this->return_failure('Cannot find pdf file.');
        }
        if ($cert->is_submitted() === FALSE)
        {
            return $this->return_failure('Pdf file not submitted yet!');
        }
        if (empty($this->content['pdf_file']))
        {
            return $this->return_failure('Empty pdf file!');
        }
        return $this->return_success(['pdf_url' => $this->content['pdf_file']]);
    }
}