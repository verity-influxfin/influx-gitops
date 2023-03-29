<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use Certification_ocr\Parser\Ocr_parser_factory;
use CertificationResult\MessageDisplay;

/**
 * 聯合徵信報告徵信項
 * Class Investigation
 * @package Certification
 */
class Cert_investigation extends Cert_pdf
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_INVESTIGATION;

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
    public $valid_month = 1;

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
        return isset($this->content['return_type']) &&
            // 非 PDF 提交方式
            ($this->content['return_type'] != 1 ||
            // PDF 提交方式時，需要有回信檔案
            ($this->content['return_type'] == 1 && isset($this->content['mail_file_status']) && $this->content['mail_file_status'] == 1) ||
            // 有聯徵的 PDF
            ! empty($this->content['pdf_file']) ||
            // 有聯徵的圖片
            ! empty($this->content['images']));
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
        if (is_image($mime) ||
            // 由圖片組成的 PDF 會將 is_valid_pdf 標記為 0, 需直接轉人工
            (isset($this->content['is_valid_pdf']) && $this->content['is_valid_pdf'] == 0))
        {
            $this->result->addMessage('需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_WRONG_FORMAT);
        }
        else if (is_pdf($mime) || ! empty($this->content['images']))
        {
            if ( ! $this->verify_fraud_pdf($parsed_content, $url))
            {
                return $parsed_content;
            }
            $parsed_content = array_merge(
                $parsed_content,
                $this->_get_ocr_parser_info()
            );
            if ( ! empty($parsed_content['ocr_parser']['content']))
            {
                $response = $parsed_content['ocr_parser']['content'];

                // 資料轉 result
                $this->CI->load->library('mapping/user/Certification_data');
                $this->transform_data = $this->CI->certification_data->transformJointCreditToResult($response);

                // 印表日期
                $this->CI->load->library('mapping/time');
                $printTimestamp = preg_replace('/\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/', '', $this->transform_data['printDatetime']);
                $printTimestamp = $this->CI->time->ROCDateToUnixTimestamp($printTimestamp);
                if($printTimestamp !== FALSE)
                {
                    $printDatetime = date('Y-m-d', $printTimestamp);
                }
                else
                {
                    $printDatetime = '';
                }

                $group_id = $this->content['group_id'] ?? time();
                $parsed_content['group_id'] = $group_id;
                $parsed_content['result'][$group_id] = $this->transform_data;
                $parsed_content['times'] = $this->transform_data['S1Count'] ?? 0;
                $parsed_content['credit_rate'] = $this->transform_data['creditCardUseRate'] ?? 0;
                $parsed_content['has_using_credit_card'] = ($this->transform_data['creditCardCount'] ?? 0) > 0;
                $parsed_content['months'] = $this->transform_data['creditLogCount'] ?? 0;
                $parsed_content['printDatetime'] = time();
                $parsed_content['printDate'] = $printDatetime;
            }
        }
        return $parsed_content;
    }

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool {
        // 紙本寄送且未由人工上傳 pdf 檔案或是圖片者，直接進人工
        if(isset($this->content['return_type']) && $this->content['return_type'] == 0 && empty($this->content['pdf_file']) && empty($this->content['images']))
        {
            $this->result->addMessage('需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 驗證格式是否正確
     * @param $content: 徵信內容
     * @return bool
     */
    public function verify_format($content): bool
    {
        return TRUE;
    }

    private function _chk_data_complete($content): bool
    {
        return isset($content['ocr_parser']['content']['is_valid']) && $content['ocr_parser']['content']['is_valid'] === TRUE;
    }

    /**
     * 核實資料是否屬實
     * @param $content: 徵信內容
     * @return bool
     */
    public function verify_data($content): bool
    {
        if ($this->_chk_ocr_status($content) === FALSE)
        {
            if ( ! $this->check_pdf_fraud_result($content)) {
                return FALSE;
            }
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
            return FALSE;
        }

        $details = $content['ocr_parser']['msg'];
        if ($content['ocr_parser']['res'] === FALSE)
        {
            $this->result->addMessage("聯徵PDF解析失敗，需人工驗證，細節：{$details}", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return FALSE;
        }

        if($this->_chk_data_complete($content) === FALSE)
        {
            $this->result->addMessage("聯徵PDF辨識結果有誤，需人工驗證，細節：{$details}", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
            return FALSE;
        }

        // 自然人聯徵正確性驗證
        $this->CI->load->library('verify/data_legalize_lib');
        $this->result = $this->CI->data_legalize_lib->legalize_investigation($this->result, $this->certification['user_id'],
            $this->transform_data, $this->certification['created_at']);
        if ($this->result->getStatus() == CERTIFICATION_STATUS_FAILED && $this->result->getSubStatus() == 0)
        {
            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_VERIFY_FAILED);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content: 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
        // 聯徵授信規則驗證
        $this->CI->load->library('verify/data_verify_lib');
        $this->result = $this->CI->data_verify_lib->check_investigation($this->result, $this->transform_data,
            $content);
        if ($this->result->getStatus() == CERTIFICATION_STATUS_FAILED)
        {
            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_REVIEW_FAILED);
        }
        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool {
        // 依照印表時間設定過期時間
        if (isset($this->transform_data['printDatetime']) && ! empty($this->transform_data['printDatetime']))
        {
            $this->CI->load->library('certification_lib');
            $printTimestamp = preg_replace('/\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/', '', $this->transform_data['printDatetime']);
            $printTimestamp = $this->CI->time->ROCDateToUnixTimestamp($printTimestamp);

            $expire_time = new \DateTime;
            $expire_time->setTimestamp($printTimestamp);
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
            'investigation_status' => 1,
            'investigation_times' => $this->content['times'] ?? '',
            'investigation_credit_rate' => $this->content['credit_rate'] ?? '',
            'investigation_months' => $this->content['months'] ?? '',
            'investigation_has_using_credit_card' => ($this->content['has_using_credit_card'] ?? FALSE) === TRUE,
        ];

        $rs = $this->CI->certification_lib->user_meta_progress($data, $this->certification);
        if ($rs)
        {
            return $this->CI->certification_lib->fail_other_cer($this->certification);
        }
        return TRUE;
    }

    /**
     * 審核失敗前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_failure($sys_check): bool {
        if (parent::pre_failure($sys_check))
        {
            return TRUE;
        }
        // 除了 PDF 防偽驗證，系統過的暫時全部轉人工
        if($sys_check == TRUE)
        {
            $this->set_review(TRUE);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 審核失敗後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_failure($sys_check): bool {
        // 退聯徵時，需退掉所有還款力計算
        return $this->CI->certification_lib->set_fail_repayment_capacity($this->certification['user_id'], '因聯合徵信報告審核失敗');
    }

    /**
     * 轉人工前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_review($sys_check): bool {
        return TRUE;
    }

    /**
     * 轉人工後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_review($sys_check): bool {
        return TRUE;
    }

    /**
     * OCR 解析結果
     * @return array
     */
    private function _get_ocr_parser_info(): array
    {
        $result = [];
        if ( ! isset($this->content['ocr_parser']['res']))
        {
            $cert_ocr_parser = Ocr_parser_factory::get_instance($this->certification);
            $ocr_parser_result = $cert_ocr_parser->get_result();
            $result['ocr_parser']['msg'] = $ocr_parser_result['msg'];
            if ($ocr_parser_result['success'] === TRUE)
            {
                if ($ocr_parser_result['code'] == 201 || $ocr_parser_result['code'] == 202)
                { // OCR 任務剛建立，或是 OCR 任務尚未辨識完成
                    return $result;
                }
                $result['ocr_parser']['res'] = TRUE;
                $result['ocr_parser']['content'] = $ocr_parser_result['data'];
            }
            else
            {
                $result['ocr_parser']['res'] = FALSE;
            }
        }
        return $result;
    }

    /**
     * OCR 辨識後的檢查
     * @param $content
     * @return bool
     */
    private function _chk_ocr_status($content): bool
    {
        if ( ! isset($content['ocr_parser']['res']))
        {
            return FALSE;
        }
        return TRUE;
    }
}
