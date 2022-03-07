<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

class Cert_btn_default extends Certification_btn_base
{
    public function draw(): string
    {
        $sys_check = $this->sys_check === 0 ? ' btn-circle ' : ' ';
        $status = $this->is_expired ? 'danger' : 'success';

        switch ($this->status)
        {
            case CERTIFICATION_STATUS_PENDING_TO_VALIDATE:
            case CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION:
            case CERTIFICATION_STATUS_AUTHENTICATED:
                return '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $this->id) . '"><button type="button" class="btn btn-warning' . $sys_check . ' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">資料更新中</span>';
            case CERTIFICATION_STATUS_SUCCEED:
                return '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $this->id) . '"><button type="button" class="btn btn-' . $status . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">完成</span>';
            case CERTIFICATION_STATUS_FAILED:
                return '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $this->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">已退回</span>';
            case CERTIFICATION_STATUS_PENDING_TO_REVIEW:
                return '<a target="_blank" href="' . admin_url('certification/user_certification_edit?id=' . $this->id) . '" class="btn btn-default btn-md nhide">驗證</a><span class="sword" style="display:none">可驗證</span>';
            default:
                return '';
        }
    }
}