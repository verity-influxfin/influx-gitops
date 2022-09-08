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
        $targets = $this->CI->target_model->get_many_by([
            'status' => [TARGET_WAITING_APPROVE, TARGET_WAITING_SIGNING,
                TARGET_WAITING_VERIFY, TARGET_ORDER_WAITING_VERIFY,
                TARGET_ORDER_WAITING_SHIP, TARGET_BANK_FAIL],
            'user_id' => $this->certification['user_id'],
        ]);
        foreach ($targets as $target) {
            $this->CI->target_lib->reject($target, SYSTEM_ADMIN_ID, '偵測到徵信項PDF竄改，駁回案件');
        }

        return TRUE;
    }
}
