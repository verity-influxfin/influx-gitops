<?php
namespace Certification;
defined('BASEPATH') OR exit('No direct script access allowed');

use CertificationResult\CertificationResult;
use CertificationResult\MessageDisplay;
abstract class Certification_base implements Certification_definition
{
    protected $certification_id = 0;
    protected $certification;
    public $result;
    public $content;
    public $remark;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [];

    /**
     * @var array 依照 certification_id 存放的 cert list
     */
    protected $dependency_cert_list = [];

    /**
     * @var array 依賴該徵信項相關之徵信項編號
     */
    protected $relations = [];

    /**
     * @var int 認證持續有效月數
     */
    protected $valid_month = 0;

    /**
     * @var int 徵信項過期時間
     */
    protected $expired_timestamp = 0;

    /**
     * @var Object CI singleton Object
     */
    protected $CI;

    /**
     * @var array config 的 certification list
     */
    protected $config_cert_list;

    /**
     * @var array config 的 certification list 內之該徵信項
     */
    protected $config_cert;

    /**
     * CertificationBase constructor.
     * @param $certification
     * @param CertificationResult $result
     */
    public function __construct($certification, CertificationResult $result)
    {
        $this->CI =& get_instance();
        $this->CI->load->model('user/user_certification_model');

        $this->certification = $certification;

        $this->content = empty($certification['content'])
            ? []
            : is_string($certification['content'])
                ? json_decode($certification['content'], TRUE)
                : $certification['content'];
        $this->content = ! is_array($this->content) ? [] : $this->content;

        $this->remark = empty($certification['remark'])
            ? []
            : is_string($certification['remark'])
                ? json_decode($certification['remark'], TRUE)
                : $certification['remark'];
        $this->remark = ! is_array($this->remark) ? [] : $this->remark;

        $this->result = $result;
        $this->result->loadResult($certification);

        $this->config_cert_list = $this->CI->config->item('certifications');
        $this->config_cert = $this->config_cert_list[$this->certification_id] ?? [];
    }

    /**
     * 取得依賴之徵信項
     */
    protected function get_dependency_certs($status) {
        $this->dependency_cert_list = [];
        foreach ($this->dependency_cert_id as $cert_id)
        {
            $cert = Certification_factory::get_instance_by_user($cert_id, $this->certification['user_id'], $this->certification['investor'], $status);
            if(isset($cert))
            {
                $this->dependency_cert_list[$cert->certification_id] = $cert;
            }
        }
    }

    /**
     * 是否已可開始驗證
     * @return bool
     */
    public function can_verify(): bool
    {
        if (
            // 驗證項非待驗證狀態
            ! $this->is_pending_to_verify() ||
            // 資料未提交完畢
            ! $this->is_submitted() ||
            // 依賴項徵信項尚未完成
            ! $this->is_qualified()
        )
        {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 開始驗證徵信的程序
     * @return bool: 驗證動作執行與否
     */
    public function verify(): bool
    {
        if ( ! $this->can_verify())
        {
            return FALSE;
        }

        // 如果前置檢查沒過就不繼續解析和驗證
        $parsed_content = $this->content;
        $this->result->clear();
        if($this->check_before_verify())
        {
            $parsed_content = $this->parse();
            $well_formatted = $this->verify_format($parsed_content);
            if ($well_formatted)
            {
                $verified_successfully = $this->verify_data($parsed_content);
                if ($verified_successfully)
                {
                    $reviewed_successfully = $this->review_data($parsed_content);
                }
            }
        }

        $remark = $this->remark;
        $remark['fail'] = implode("、", $this->result->getAPPMessage(CERTIFICATION_STATUS_FAILED));
        $remark['verify_result'] = $this->result->getAllMessage(MessageDisplay::Backend);
        $remark['verify_result_json'] = $this->result->jsonDump();

        $status = $this->result->getStatus();
        $this->CI->user_certification_model->update($this->certification['id'], [
            'content' => json_encode($parsed_content, JSON_INVALID_UTF8_IGNORE),
            'remark' => json_encode($remark, JSON_INVALID_UTF8_IGNORE),
        ]);

        switch ($status)
        {
            case CERTIFICATION_STATUS_SUCCEED:
                $this->set_success(TRUE);
                break;
            case CERTIFICATION_STATUS_FAILED:
                $this->set_failure(TRUE);
                break;
            case CERTIFICATION_STATUS_PENDING_TO_REVIEW:
                $this->set_review(TRUE);
                break;
        }

        return TRUE;
    }

    /**
     * 審核成功
     * @param $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_success($sys_check, string $msg=''): bool
    {
        $pre_flag = $this->pre_success($sys_check);
        if($pre_flag)
        {
            $sub_status = $this->result->getSubStatus();
            $param = [
                'status' => CERTIFICATION_STATUS_SUCCEED,
                'sub_status' => $sub_status,
                'sys_check' => ($sys_check == TRUE ? 1 : 0),
            ];
            if ($this->expired_timestamp)
            {
                $param['expire_time'] = $this->expired_timestamp;
            }
            if ( ! empty($msg))
            {
                $this->remark['success'] = $msg;
                $param['remark'] = json_encode($this->remark);
            }
            $rs = $this->CI->user_certification_model->update($this->certification['id'], $param);

            $post_flag = $this->post_success($sys_check);
            $notified = $this->success_notification();

            // 驗證推薦碼
            $this->CI->load->library('certification_lib');
            $this->CI->certification_lib->verify_promote_code((object)$this->certification, FALSE);
        }
        return $pre_flag && $rs && $post_flag && $notified;
    }

    /**
     * 審核失敗
     * @param $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_failure($sys_check, string $msg=''): bool
    {
        $pre_flag = $this->pre_failure($sys_check);
        if($pre_flag)
        {
            $sub_status = $this->result->getSubStatus();
            $param = [
                'status' => CERTIFICATION_STATUS_FAILED,
                'sub_status' => $sub_status,
                'sys_check' => ($sys_check == TRUE ? 1 : 0),
            ];

            // 禁止再次提交期間
            $canResubmitDate = $this->result->getCanResubmitDate($this->certification['created_at']);
            if ( ! empty($canResubmitDate))
            {
                $param['can_resubmit_at'] = $canResubmitDate;
            }
            if ($this->expired_timestamp)
            {
                $param['expire_time'] = $this->expired_timestamp;
            }
            if ( ! empty($msg))
            {
                $this->remark['fail'] = $msg;
                $param['remark'] = json_encode($this->remark);
            }

            $rs = $this->CI->user_certification_model->update($this->certification['id'], $param);

            $post_flag = $this->post_failure($sys_check);
            $notified = $this->failure_notification();

            if($rs && $post_flag && $notified)
            {
                // 退待簽約案件及信評分數
                $this->failed_target_credit();

                // 驗證推薦碼失敗
                $this->CI->load->library('certification_lib');
                $this->CI->certification_lib->verify_promote_code((object)$this->certification, TRUE);

                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 轉人工
     * @param $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_review($sys_check, string $msg=''): bool
    {
        $pre_flag = $this->pre_review($sys_check);
        if($pre_flag)
        {
            $sub_status = $this->result->getSubStatus();
            $param = [
                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                'sub_status' => $sub_status,
                'sys_check' => ($sys_check == TRUE ? 1 : 0),
            ];
            if ($this->expired_timestamp)
            {
                $param['expire_time'] = $this->expired_timestamp;
            }
            if ( ! empty($msg))
            {
                $this->remark['review'] = $msg;
                $param['remark'] = json_encode($this->remark);
            }
            $rs = $this->CI->user_certification_model->update($this->certification['id'], $param);

            $post_flag = $this->post_review($sys_check);
            $notified = $this->review_notification();
        }
        return $pre_flag && $rs && $post_flag && $notified;
    }

    /**
     * 審核成功的通知
     * @return bool
     */
    public function success_notification(): bool
    {
        // todo: 目前個金徵信項改為不通知，但企金配偶的徵信項是否通知？

        return TRUE;
        //        return $this->CI->notification_lib->certification($this->certification['user_id'],
        //            $this->certification['investor'], $this->config_cert['name'], CERTIFICATION_STATUS_SUCCEED);
    }

    /**
     * 審核失敗的通知
     * @return bool
     */
    public function failure_notification(): bool
    {
        // todo: 目前個金徵信項改為不通知，但企金配偶的徵信項是否通知？

        return TRUE;
        //        $msg_list = $this->result->getAPPMessage(CERTIFICATION_STATUS_FAILED);
        //        $msg = implode("、", $msg_list);
        //        return $this->CI->notification_lib->certification($this->certification['user_id'],
        //            $this->certification['investor'], $this->config_cert['name'], CERTIFICATION_STATUS_FAILED, $msg);
    }

    /**
     * 轉人工的通知
     * @return bool
     */
    public function review_notification(): bool {
        return TRUE;
    }

    /**
     * 是否已完成依賴的相關徵信項目
     */
    public function is_qualified() : bool {
        $this->get_dependency_certs(CERTIFICATION_STATUS_SUCCEED);

        $qualified = TRUE;
        foreach ($this->dependency_cert_id as $cert_id)
        {
            if ( ! isset($this->dependency_cert_list[$cert_id]) || ! $this->dependency_cert_list[$cert_id]->is_succeed())
            {
                $qualified = FALSE;
                break;
            }
        }
        return $qualified;
    }

    /**
     * 所有項目是否已提交
     * @return bool
     */
    public function is_submitted(): bool
    {
        return TRUE;
    }

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return ! empty($this->certification['expire_time']) && $this->certification['expire_time'] < time();
    }

    /**
     * 是否認證完成
     * @return bool
     */
    public function is_succeed(): bool
    {
        return $this->certification['status'] == CERTIFICATION_STATUS_SUCCEED;
    }

    /**
     * 是否認證失敗
     * @return bool
     */
    public function is_failed(): bool
    {
        return $this->certification['status'] == CERTIFICATION_STATUS_FAILED;
    }

    /**
     * 該徵信項是否因為格式不符而失敗
     * @return bool
     */
    public function is_wrong_format(): bool
    {
        return $this->certification['sub_status'] == CERTIFICATION_SUBSTATUS_WRONG_FORMAT;
    }

    /**
     * 該徵信項是否因為身份核實失敗而失敗
     * @return bool
     */
    public function is_failed_verification(): bool
    {
        return $this->certification['sub_status'] == CERTIFICATION_SUBSTATUS_VERIFY_FAILED;
    }

    /**
     * 該徵信項是否因為授信不符標準而失敗
     * @return bool
     */
    public function is_failed_review(): bool
    {
        return $this->certification['sub_status'] == CERTIFICATION_SUBSTATUS_REVIEW_FAILED;
    }

    /**
     * 是否待人工審核
     * @return bool
     */
    public function is_pending_to_review(): bool
    {
        return $this->certification['status'] == CERTIFICATION_STATUS_PENDING_TO_REVIEW;
    }

    /**
     * 是否待驗證
     * @return bool
     */
    public function is_pending_to_verify(): bool
    {
        return $this->certification['status'] == CERTIFICATION_STATUS_PENDING_TO_VALIDATE;
    }

    /**
     * 退掉待簽約案件及信評分數
     */
    public function failed_target_credit()
    {
        // 案件待簽約退回
        // TODO: 沒有識別產品需求徵信像，不分青紅皂別退回感覺有問題，待確認修復？
        $this->CI->load->library('target_lib');
        $targets = $this->CI->target_model->get_many_by([
            'user_id' => $this->certification['user_id'],
            'status' => [TARGET_WAITING_SIGNING, TARGET_ORDER_WAITING_SHIP]
        ]);
        if ( ! empty($targets))
        {
            foreach ($targets as $value)
            {
                $this->CI->target_model->update_by(
                    ['id' => $value->id],
                    ['status' => $value->status == TARGET_WAITING_SIGNING ? TARGET_WAITING_APPROVE : TARGET_ORDER_WAITING_VERIFY]
                );
            }
        }

        // 退信評分數
        $this->CI->load->model('loan/credit_model');
        $credit_list = $this->CI->credit_model->get_many_by([
            'user_id' => $this->certification['user_id'],
            'status' => 1
        ]);
        foreach ($credit_list as $value)
        {
            if ( ! in_array($value->level, [11, 12, 13]))
            {
                $this->CI->credit_model->update_by(
                    ['id' => $value->id],
                    ['status' => 0]
                );
            }
        }
    }

    /**
     * 是否可以重新提交
     * @return bool
     */
    public function can_re_submit(): bool
    {
        return $this->certification['can_resubmit_at'] < time();
    }
}