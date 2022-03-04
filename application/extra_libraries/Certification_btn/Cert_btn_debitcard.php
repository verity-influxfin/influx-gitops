<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

class Cert_btn_debitcard extends Certification_btn_base
{
    public function draw(): string
    {
        $sys_check = $this->sys_check === 0 ? ' btn-circle' : ' ';
        $status = $this->expire_time <= time() && ! in_array($this->certification_id, [CERTIFICATION_IDENTITY, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL])
            ? 'danger'
            : 'success';

        $this->CI->load->model('user/user_bankaccount_model');
        $user_bank_account = $this->CI->user_bankaccount_model->get_by(array(
            'user_certification_id' => $this->id,
        ));

        if ( ! isset($user_bank_account->id))
        {
            return '';
        }

        switch ($this->status)
        {
            case CERTIFICATION_STATUS_PENDING_TO_VALIDATE:
            case CERTIFICATION_STATUS_PENDING_TO_REVIEW:
            case CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION:
            case CERTIFICATION_STATUS_AUTHENTICATED:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '" class="btn btn-default btn-md nhide">驗證</a><span class="sword" style="display:none">可驗證</span>';
            case CERTIFICATION_STATUS_SUCCEED:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '"><button type="button" class="btn btn-' . $status . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
            case CERTIFICATION_STATUS_FAILED:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
            default:
                return '';
        }
    }
}