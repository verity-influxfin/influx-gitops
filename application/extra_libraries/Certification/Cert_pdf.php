<?php

namespace Certification;
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
            $this->CI->target_lib->reject($target, SYSTEM_ADMIN_ID, '偵測到徵信項PDF竄改，駁回案件');
        }

        // Add this user into black list.
        $brookesia_url = "http://" . getenv('GRACULA_IP') . ":9453/brookesia/api/v1.0/blockUser/add";
        $details = $this->additional_data['pdf_fraud_details'];
        $desc = $details['轉退件標準'];
        unset($details['轉退件標準']);
        unset($details['轉人工審核標準']);
        $result = curl_get($brookesia_url, [
            'userId' => $user_id,
            'updatedBy' => SYSTEM_ADMIN_ID,
            'blockRemark' => json_encode($details),
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
}
