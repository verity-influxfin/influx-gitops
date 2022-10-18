<?php

namespace Certification;
use CertificationResult\CertificationResult;
use CertificationResult\MessageDisplay;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 須提供 PDF 檔的徵信項
 * @package Certification
 */
abstract class Cert_pdf extends Certification_base
{
    /**
     * @var array 驗證後的額外資料
     */
    protected $additional_data = [];

    public function post_verify(): bool
    {
        if (empty($this->additional_data))
        {
            return TRUE;
        }
        if ( ! $this->additional_data['pdf_fraud_reject'])
        {
            return TRUE;
        }

        // Reject targets of the user if fraud PDF detected.
        $this->CI->load->library('Target_lib');
        $this->CI->load->model('target_model');
        $user_id = $this->certification['user_id'];
        $targets = $this->CI->target_model->get_many_by([
            'status' => [TARGET_WAITING_APPROVE, TARGET_WAITING_SIGNING,
                         TARGET_WAITING_VERIFY, TARGET_ORDER_WAITING_VERIFY,
                         TARGET_ORDER_WAITING_SHIP, TARGET_BANK_FAIL],
            'user_id' => $user_id,
        ]);
        foreach ($targets as $target) {
            $this->CI->target_lib->reject($target, SYSTEM_ADMIN_ID, CertificationResult::$FAILED_MESSAGE);
        }

        // Add this user into black list.
        $brookesia_url = "http://" . getenv('GRACULA_IP') . ":9453/brookesia/api/v1.0/blockUser/add";
        $details = $this->additional_data['pdf_fraud_details'];
        $desc = $details['desc'];
        unset($details['desc']);
        if ($details)
        {
            $remark = json_encode($details, JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $remark = '';
        }
        $result = curl_get($brookesia_url, [
            'userId' => $user_id,
            'updatedBy' => SYSTEM_ADMIN_ID,
            'blockRemark' => $remark,
            'blockTimeText' => '永久封鎖',
            'blockRule' => '授信政策',
            'blockRisk' => '拒絕',
            'blockDescription' => $desc
        ]);
        if ( ! $result)
        {
            log_message('error', "Failed adding fraud-pdf user {$user_id} to black list.");
        }

        return TRUE;
    }

    /**
     * @param $parsed_content
     * @param $pdf_url
     * @return bool TRUE if passed fraud detection, FALSE otherwise
     */
    protected function verify_fraud_pdf(&$parsed_content, $pdf_url): bool
    {
        if ( ! isset($parsed_content['pdf_fraud_detect']))
        {
            // Check if PDF edited.
            $this->CI->load->helper('user_certification');
            $fraud_result = verify_fraud_pdf($pdf_url);
            $cert_status = $fraud_result[0];
            if ($cert_status != CERTIFICATION_STATUS_PENDING_TO_VALIDATE)
            {
                $parsed_content['pdf_fraud_detect'] = [];
                $parsed_content['pdf_fraud_detect']['pass'] = FALSE;
                $parsed_content['pdf_fraud_detect']['certification_status'] = $cert_status;
                $parsed_content['pdf_fraud_detect']['details'] = $fraud_result[1];
                $this->additional_data['pdf_fraud_reject'] = ($cert_status == CERTIFICATION_STATUS_FAILED);
                $this->additional_data['pdf_fraud_details'] = $fraud_result[1];
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * @param $content
     * @return bool TRUE if not fraud PDF, FALSE otherwise
     */
    protected function check_pdf_fraud_result($content): bool
    {
        if ( ! isset($content['pdf_fraud_detect']) || $content['pdf_fraud_detect']['pass'] !== FALSE)
        {
            return TRUE;
        }
        $fraud_detect_status = $content['pdf_fraud_detect']['certification_status'];
        $details = json_encode($content['pdf_fraud_detect']['details'], JSON_UNESCAPED_UNICODE);
        $desc = $details['desc'];
        unset($details['desc']);
        $this->result->setStatus($fraud_detect_status);
        if ($fraud_detect_status == CERTIFICATION_STATUS_FAILED)
        {
            $this->result->addMessage("聯徵PDF已被竄改過：{$desc}。細節：\n{$details}", CERTIFICATION_STATUS_FAILED, MessageDisplay::Backend);
        }
        else // CERTIFICATION_STATUS_PENDING_TO_REVIEW
        {
            $this->result->addMessage("聯徵PDF疑似被竄改過，需人工驗證：{$desc}。細節：\n{$details}", CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
        }
        return FALSE;
    }

    /**
     * @param $sys_check
     * @return bool TRUE if no need to do further pre_failure action, FALSE otherwise.
     */
    public function pre_failure($sys_check): bool
    {
        return isset($this->additional_data['pdf_fraud_reject']) && $this->additional_data['pdf_fraud_reject'];
    }
}
