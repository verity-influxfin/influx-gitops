<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

class Cert_btn_default extends Certification_btn_base
{
    public function draw(): string
    {
        $sys_check = $this->sys_check !== SYSTEM_CHECK ? ' btn-circle ' : ' ';

        $status_meaning = $this->get_status_meaning();
        switch ($this->status)
        {
            case CERTIFICATION_STATUS_PENDING_TO_VALIDATE:
                if ($this->is_submitted === FALSE)
                {
                    return '<a data-sorting="0" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-exclamation"></i></button></a><span class="sword" style="display:none">' . $status_meaning . '</span>';
                }
                return '';
            case CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION:
            case CERTIFICATION_STATUS_AUTHENTICATED:
                return '<a data-sorting="1" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '"><button type="button" class="btn btn-warning' . $sys_check . ' nhide"><i class="fa fa-refresh"></i></button></a><span class="sword" style="display:none">' . $status_meaning . '</span>';
            case CERTIFICATION_STATUS_SUCCEED:
                if ($this->is_expired === FALSE)
                {
                    return '<a data-sorting="2" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '"><button type="button" class="btn btn-success' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">' . $status_meaning . '</span>';
                }
                return '<a data-sorting="3" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-check"></i></button></a><span class="sword" style="display:none">' . $status_meaning . '</span>';
            case CERTIFICATION_STATUS_FAILED:
                if ($this->sub_status == CERTIFICATION_SUBSTATUS_REVIEW_FAILED)
                {
                    return '<a data-sorting="4" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-times"></i></button></a><span class="sword" style="display:none">' . $status_meaning . '</span>';
                }
                if ($this->sub_status == CERTIFICATION_SUBSTATUS_NOT_ONE_MONTH)
                {
                    return '<a data-sorting="5" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '"><button type="button" class="btn btn-danger' . $sys_check . ' nhide"><i class="fa fa-minus"></i></button></a><span class="sword" style="display:none">' . $status_meaning . '</span>';
                }
                return '';
            case CERTIFICATION_STATUS_PENDING_TO_REVIEW:
                if ($this->sub_status == CERTIFICATION_SUBSTATUS_WRONG_FORMAT)
                {
                    return '<a data-sorting="6" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '" class="btn btn-outline btn-danger btn-md nhide">格式不符</a><span class="sword" style="display:none">' . $status_meaning . '</span>';
                }
                return '<a data-sorting="7" target="_blank" href="' . admin_url('certification/user_certification_detail?id=' . $this->id) . '" class="btn btn-default btn-md nhide">驗證</a><span class="sword" style="display:none">' . $status_meaning . '</span>';
            default:
                return '';
        }
    }

    /**
     * @return string
     */
    public function get_status_meaning(): string
    {
        switch ($this->status)
        {
            case CERTIFICATION_STATUS_PENDING_TO_VALIDATE:
                if ($this->is_submitted === FALSE)
                {
                    return '資料未繳交完全';
                }
                return '';
            case CERTIFICATION_STATUS_PENDING_TO_AUTHENTICATION:
            case CERTIFICATION_STATUS_AUTHENTICATED:
                return '資料更新中';
            case CERTIFICATION_STATUS_SUCCEED:
                if ($this->is_expired === FALSE)
                {
                    return '認證完成';
                }
                return '認證過期';
            case CERTIFICATION_STATUS_FAILED:
                if ($this->sub_status == CERTIFICATION_SUBSTATUS_REVIEW_FAILED)
                {
                    return '未符合授信標準';
                }
                if ($this->sub_status == CERTIFICATION_SUBSTATUS_NOT_ONE_MONTH)
                {
                    return '資料逾期';
                }
                return '';
            case CERTIFICATION_STATUS_PENDING_TO_REVIEW:
                if ($this->sub_status == CERTIFICATION_SUBSTATUS_WRONG_FORMAT)
                {
                    return '格式不符';
                }
                return '可驗證';
            default:
                return '';
        }
    }
}