<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;

/**
 * 勞保異動明細徵信項
 * Class Investigation
 * @package Certification
 */
class Cert_job extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_JOB;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [CERTIFICATION_IDENTITY];

    /**
     * @var array 依賴該徵信項相關之徵信項編號
     */
    protected $relations = [];

    /**
     * @var int 認證持續有效月數
     */
    protected $valid_month = 1;

    /**
     * @var array 轉換後的資料
     */
    private $transform_data = [];

    /**
     * 所有項目是否已提交
     * @override
     * @return bool
     */
    public function is_submitted(): bool
    {
        return
            ! empty($this->content['financial_image']) ||
            (isset($this->content['labor_type']) &&
                // 非 PDF 提交方式
                ($this->content['labor_type'] != 1 ||
                    // PDF 提交方式時，需要有回信檔案
                    ($this->content['labor_type'] == 1 && isset($this->content['mail_file_status']) && $this->content['mail_file_status'] == 1)));
    }

    /**
     * 解析輸入資料
     * @return array|mixed
     */
    public function parse()
    {
        $parsed_content = $this->content ?? [];
        $url = $this->content['pdf_file'] ?? '';

        $mime = get_mime_by_extension($url);
        if (is_image($mime))
        {
            $this->result->addMessage('需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }
        else if (is_pdf($mime))
        {
            $text = '';
            $this->CI->load->library('labor_insurance_lib');
            $parser = new \Smalot\PdfParser\Parser();
            try
            {
                $pdf = $parser->parseFile($url);
                $text = $pdf->getText();
                $response = $this->CI->labor_insurance_lib->transfrom_pdf_data($text);
            }
            catch (\Exception $e)
            {
                $response = FALSE;
            }

            if ( ! $response || strpos($text, '勞動部勞工保險局ｅ化服務系統') === FALSE)
            {
                $this->result->addMessage('勞保PDF解析失敗，需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            }
            else
            {
                // 用統編檢查商業司
                if (isset($parsed_content['tax_id']) && $parsed_content['tax_id'])
                {
                    $this->CI->load->library('gcis_lib');
                    $gcis_res = $this->CI->gcis_lib->account_info($parsed_content['tax_id']);
                    $parsed_content['gcis_info'] = $gcis_res;
                    $response['gcis_info'] = $gcis_res;
                }

                $this->CI->load->library('mapping/user/Certification_data');
                $this->transform_data = $this->CI->certification_data->transformJobToResult($response);
                $parsed_content['pdf_info'] = $this->transform_data;
                $parsed_content['salary'] = $this->transform_data['last_insurance_info']['insuranceSalary'];
            }
        }
        return $parsed_content;
    }

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool
    {
        // 紙本寄送直接進人工
        if (isset($this->content['labor_type']) && $this->content['labor_type'] == 0)
        {
            $this->result->addMessage('需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 驗證格式是否正確
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_format($content): bool
    {
        return TRUE;
    }

    /**
     * 核實資料是否屬實
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_data($content): bool
    {
        // 勞保異動明細正確性驗證
        $this->CI->load->library('verify/data_legalize_lib');
        $this->result = $this->CI->data_legalize_lib->legalize_job($this->result, $this->certification['user_id'],
            $this->transform_data, $content, $this->certification['created_at']);

        if ($this->result->getStatus() == CERTIFICATION_STATUS_FAILED)
        {
            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_VERIFY_FAILED);
        }
        if (in_array($this->result->getStatus(), [CERTIFICATION_STATUS_FAILED, CERTIFICATION_STATUS_PENDING_TO_REVIEW]))
        {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content : 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
        // 聯徵授信規則驗證
        $this->CI->load->library('verify/data_verify_lib');
        $this->result = $this->CI->data_verify_lib->check_job($this->result, $this->certification['user_id'],
            $this->transform_data, $content);
        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool
    {
        // 更新過期時間
        $this->CI->load->library('certification_lib');
        preg_match('/^(?<year>(1[0-9]{2}|[0-9]{2}))(?<month>(0?[1-9]|1[012]))(?<day>(0?[1-9]|[12][0-9]|3[01]))$/',
            $this->transform_data['report_date'], $regexResult);
        if ( ! empty($regexResult))
        {
            $date = sprintf("%d-%'.02d-%'.02d", intval($regexResult['year']) + 1911,
                intval($regexResult['month']), intval($regexResult['day']));
            $expire_time = \DateTime::createFromFormat('Y-m-d', $date);
            $expire_time->modify("+ {$this->valid_month} month");
            $this->expired_timestamp = $expire_time->getTimestamp();
        }
        return TRUE;
    }

    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool
    {
        // 更新 user_meta 歸戶資訊
        $data = [
            'job_status' => 1,
            'job_tax_id' => $this->content['tax_id'] ?? '',
            'job_company' => $this->content['company'] ?? '',
            'job_industry' => $this->content['industry'] ?? '',
            'job_employee' => $this->content['employee'] ?? '',
            'job_position' => $this->content['position'] ?? '',
            'job_type' => $this->content['type'] ?? '',
            'job_seniority' => $this->content['seniority'] ?? '',
            'job_company_seniority' => $this->content['job_seniority'] ?? '',
            'job_salary' => $this->content['salary'] ?? '',
            'job_license' => $this->content['license_status'] ?? '',
            'job_pro_level' => $this->content['pro_level'] ?? '',
            'game_work_level' => $this->content['game_work_level'] ?? '',
        ];

        $data['job_programming_language'] = $this->content['programming_language'] ?? '';
        $data['job_title'] = $this->content['job_title'] ?? '';

        $this->CI->certification_lib->user_meta_progress($data, $this->certification);

        return $this->CI->certification_lib->fail_other_cer($this->certification);
    }

    /**
     * 審核失敗前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_failure($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 審核失敗後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_failure($sys_check): bool
    {
        // 退工作認證時，需把聯徵也一起退掉 issue #1202
        $msg_list = $this->result->getAPPMessage(CERTIFICATION_STATUS_FAILED);
        $msg = implode("、", $msg_list);
        if ($this->CI->certification_lib->isRejectedResult($msg))
        {
            $this->CI->certification_lib->withdraw_investigation($this->certification['user_id'], $this->certification['investor']);
        }

        return TRUE;
    }

    /**
     * 轉人工前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_review($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 轉人工後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_review($sys_check): bool
    {
        return TRUE;
    }


}