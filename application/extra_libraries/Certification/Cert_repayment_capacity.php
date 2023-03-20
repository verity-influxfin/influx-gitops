<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;

/**
 * 還款力徵信項
 * Class Repayment_capacity
 * @package Certification
 */
class Cert_repayment_capacity extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_REPAYMENT_CAPACITY;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [CERTIFICATION_INVESTIGATION, CERTIFICATION_JOB];

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
     * @var array 聯合徵信報告資料
     */
    private $investigation_content = [];

    /**
     * @var array 工作收入證明資料
     */
    private $job_content = [];

    /**
     * 所有項目是否已提交
     * @override
     * @return bool
     */
    public function is_submitted(): bool
    {
        // 還款力沒有提交項，而是以下面兩個徵信項的資料計算得出：
        // 1.「聯合徵信報告 CERTIFICATION_INVESTIGATION」
        // 2.「工作收入證明 CERTIFICATION_JOB」
        return TRUE;
    }

    /**
     * 解析輸入資料
     * @return array|mixed
     * @return bool
     */
    public function parse()
    {
        // 聯合徵信報告資料
        $this->investigation_content = $this->dependency_cert_list[CERTIFICATION_INVESTIGATION]->certification['content'] ?? '';
        $this->investigation_content = json_decode($this->investigation_content, TRUE);

        // 工作收入證明資料
        $this->job_content = $this->dependency_cert_list[CERTIFICATION_JOB]->certification['content'] ?? '';
        $this->job_content = json_decode($this->job_content, TRUE);

        // 還款力資料初始化
        $parsed_content = [
            'monthly_repayment' => '', // 薪資收入
            'total_repayment' => '', // 薪資22倍
        ];

        if (isset($this->job_content['salary']) && is_numeric($this->job_content['salary']))
        {
            // 薪資收入
            $parsed_content['monthly_repayment'] = $this->job_content['salary'] / 1000;
            // 薪資22倍
            $parsed_content['total_repayment'] = $this->job_content['salary'] * 22 / 1000;
        }

        // 紙本寄送直接進人工
        if ( ! isset($this->investigation_content['return_type']) || $this->investigation_content['return_type'] != 1)
        {
            $this->result->addMessage('待人工驗證：聯徵為紙本寄送', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return $parsed_content;
        }

        // 尚未回信上傳檔案直接進人工
        if ( ! isset($this->investigation_content['mail_file_status']) || $this->investigation_content['mail_file_status'] != 1)
        {
            $this->result->addMessage('待人工驗證：聯徵未回信上傳', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return $parsed_content;
        }

        $url = $this->investigation_content['pdf_file'] ?? '';

        $mime = get_mime_by_extension($url);
        if (is_image($mime))
        {
            $this->result->addMessage('待人工驗證：聯徵格式非PDF', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }
        else if (is_pdf($mime))
        {
            if ( ! isset($this->investigation_content['result']) || ! $this->investigation_content['result'])
            {
                $this->result->addMessage('待人工驗證：聯徵PDF解析失敗', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                return $parsed_content;
            }
            $this->CI->load->library('mapping/user/Certification_data');
            $this->transform_data = $this->CI->certification_data->transform_joint_credit_to_repayment_capacity($this->investigation_content);
            $parsed_content = array_merge($parsed_content, $this->transform_data);

            // 負債比計算，投保薪資不能為0
            if ( ! empty($parsed_content['monthly_repayment']) && is_numeric($parsed_content['monthly_repayment']))
            {
                $parsed_content['debt_to_equity_ratio'] = round(
                    $this->transform_data['totalMonthlyPayment'] / $parsed_content['monthly_repayment'] * 100,
                    2
                );
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
        $this->result = $this->CI->data_verify_lib->check_repayment_capacity(
            $this->result,
            $content
        );
        if ($this->result->getStatus() == CERTIFICATION_STATUS_FAILED)
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_REVIEW);
        }

        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool
    {
        // 依照印表時間設定過期時間
        if ( ! empty($this->transform_data['printDatetime']))
        {
            $this->CI->load->library('certification_lib');
            $this->CI->load->library('mapping/time');

            $printTimestamp = preg_replace('/\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/', '', $this->transform_data['printDatetime']);
            $printTimestamp = $this->CI->time->ROCDateToUnixTimestamp($printTimestamp);

            $expire_time = new \DateTime;
            $expire_time->setTimestamp($printTimestamp);
            $expire_time->modify("+ {$this->valid_month} month");
            $this->expired_timestamp = $expire_time->getTimestamp();
        }
        else
        {
            // 若無印表時間，則以徵信項的建立時間加三個月
            $expire_time = new \DateTime;
            $expire_time->setTimestamp($this->certification->created_at);
            $expire_time->modify('+ 3 month');
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
