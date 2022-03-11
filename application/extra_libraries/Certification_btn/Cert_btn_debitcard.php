<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

class Cert_btn_debitcard extends Certification_btn_base
{
    public function draw(): string
    {
        $sys_check = $this->sys_check !== SYSTEM_CHECK ? ' btn-circle' : ' ';
        $status = $this->is_expired ? 'danger' : 'success';

        $this->CI->load->model('user/user_bankaccount_model');
        $user_bank_account = $this->CI->user_bankaccount_model->get_by(array(
            'user_certification_id' => $this->id,
        ));

        if ( ! isset($user_bank_account->id))
        {
            return '';
        }

        switch ($user_bank_account->verify)
        {
            case 2:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '"><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">待驗證</span>';
            case 3:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '"><button type="button" class="btn btn-warning btn-circle nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">已發送</span>';
            case 1:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '"><button type="button" class="btn btn-' . $status . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">驗證成功</span>';
            case 4:
                return '<a target="_blank" href="' . admin_url('certification/user_bankaccount_edit?id=' . $user_bank_account->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">驗證失敗</span>';
            default:
                return '';
        }
    }
}