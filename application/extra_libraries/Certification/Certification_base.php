<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\CertificationResult;
use CertificationResult\MessageDisplay;
abstract class Certification_base implements Certification_definition
{
    protected $certification_id = 0;
    public $certification;
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
     * @var array 與此徵信項有關聯的產品(包含子產品)
     */
    protected $related_product;

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
            : (is_string($certification['content'])
                ? json_decode($certification['content'], TRUE)
                : $certification['content']);
        $this->content = ! is_array($this->content) ? [] : $this->content;

        $this->remark = empty($certification['remark'])
            ? []
            : (is_string($certification['remark'])
                ? json_decode($certification['remark'], TRUE)
                : $certification['remark']);
        $this->remark = ! is_array($this->remark) ? [] : $this->remark;

        $this->result = $result;
        $this->result->loadResult($certification);

        $this->config_cert_list = $this->CI->config->item('certifications');
        $this->config_cert = $this->config_cert_list[$this->certification_id] ?? [];

        $this->CI->load->helper('product');
    }

    /**
     * 取得依賴之徵信項
     */
    public function get_dependency_certs($status)
    {
        $this->dependency_cert_list = [];
        foreach ($this->dependency_cert_id as $cert_id)
        {
            $cert = Certification_factory::get_instance_by_user($cert_id, $this->certification['user_id'], $this->certification['investor'], $status);
            if (isset($cert))
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
        if ($this->check_before_verify())
        {
            $parsed_content = $this->parse();
            $well_formatted = $this->verify_format($parsed_content);
            if ($well_formatted)
            {
                $verified_successfully = $this->verify_data($parsed_content);
                if ($verified_successfully)
                {
                    $this->review_data($parsed_content);
                }
            }
        }

        $remark = $this->remark;
        $remark['fail'] = implode("、", $this->result->getAPPMessage(CERTIFICATION_STATUS_FAILED));
        $remark['verify_result'] = $this->result->getAllMessage(MessageDisplay::Backend);
        $remark['verify_result_json'] = $this->result->jsonDump();

        $status = $this->result->getStatus();
        $this->CI->user_certification_model->update($this->certification['id'], [
            'content' => json_encode($parsed_content, JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE),
            'remark' => json_encode($remark, JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE),
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

        return $this->post_verify();
    }

    /**
     * 審核成功
     * @param bool $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_success(bool $sys_check, string $msg = ''): bool
    {
        $this->_get_related_product();
        $pre_flag = $this->pre_success($sys_check);
        if ($pre_flag)
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
            }
            if (isset($this->remark['fail']))
            { // 清空失敗原因
                $this->remark['fail'] = '';
            }
            if ( ! empty($this->remark))
            {
                $param['remark'] = json_encode($this->remark);
            }
            $rs = $this->CI->user_certification_model->update($this->certification['id'], $param);

            $post_flag = $this->post_success($sys_check);
            $notified = $this->success_notification();
        }
        return $pre_flag && $rs && $post_flag && $notified;
    }

    /**
     * 審核失敗
     * @param bool $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_failure(bool $sys_check, string $msg = ''): bool
    {
        $this->_get_related_product();
        $pre_flag = $this->pre_failure($sys_check);
        if ($pre_flag)
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

            if ($rs && $post_flag && $notified)
            {
                // 退待簽約案件及信評分數
                $this->failed_target_credit();

                // 退與此徵信項有關的二審案件
                $this->reset_second_instance_target();

                // 驗證推薦碼失敗
                $this->CI->load->library('certification_lib');
                $this->CI->certification_lib->verify_promote_code((object) $this->certification, TRUE);

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
    public function set_review($sys_check, string $msg = ''): bool
    {
        $pre_flag = $this->pre_review($sys_check);
        if ($pre_flag)
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
        $product = [];
        array_walk($this->related_product, function ($value, $key) use (&$product) {
            if (is_judicial_product($key) === FALSE)
            {
                $product[$key] = $value;
            }
        });

        $target = $this->CI->target_model->get_by_multi_product(
            $this->certification['user_id'],
            [TARGET_WAITING_APPROVE],
            $product
        );

        if ( ! empty($target))
        {
            return TRUE;
        }

        if (isset($this->config_cert['show']) && $this->config_cert['show'] === FALSE)
        {
            return TRUE;
        }

        return $this->CI->notification_lib->certification($this->certification['user_id'],
            $this->certification['investor'], $this->config_cert['name'], CERTIFICATION_STATUS_SUCCEED);
    }

    /**
     * 審核失敗的通知
     * @return bool
     */
    public function failure_notification(): bool
    {
        $product = [];
        array_walk($this->related_product, function ($value, $key) use (&$product) {
            if (is_judicial_product($key) === FALSE)
            {
                $product[$key] = $value;
            }
        });

        $target = $this->CI->target_model->get_by_multi_product(
            $this->certification['user_id'],
            [TARGET_WAITING_APPROVE],
            $product
        );

        if ( ! empty($target))
        {
            return TRUE;
        }

        if (isset($this->config_cert['show']) && $this->config_cert['show'] === FALSE)
        {
            return TRUE;
        }

        $msg_list = $this->result->getAPPMessage(CERTIFICATION_STATUS_FAILED);
        $msg = implode("、", $msg_list);
        return $this->CI->notification_lib->certification($this->certification['user_id'],
            $this->certification['investor'], $this->config_cert['name'], CERTIFICATION_STATUS_FAILED, $msg);
    }

    /**
     * 轉人工的通知
     * @return bool
     */
    public function review_notification(): bool
    {
        return TRUE;
    }

    /**
     * 是否已完成依賴的相關徵信項目
     */
    public function is_qualified(): bool
    {
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
     * 該徵信項是否曾送出審核過
     * @return bool
     */
    public function is_submit_to_review(): bool
    {
        return $this->certification['certificate_status'] == 1;
    }

    /**
     * 退掉待簽約案件及信評分數
     */
    public function failed_target_credit()
    {
        // 若同user有其他待簽約的申貸案，命中此審核失敗的徵信項的話，則將該申貸案退回
        $this->CI->load->library('target_lib');
        $target_list = $this->CI->target_model->get_by_multi_product(
            $this->certification['user_id'],
            [TARGET_WAITING_SIGNING, TARGET_ORDER_WAITING_SHIP],
            $this->related_product
        );
        if ( ! empty($target_list))
        {
            foreach ($target_list as $value)
            {
                $this->CI->target_lib->withdraw_target_to_unapproved($value);
            }
        }

        // 退信評分數
        $this->CI->load->model('loan/credit_model');
        $credit_list = $this->CI->credit_model->get_failed_target_credit_list(
            $this->certification['user_id'],
            $this->related_product
        );
        foreach ($credit_list as $value)
        {
            if ( ! in_array($value['level'], [11, 12, 13]))
            {
                $this->CI->credit_model->update_by(
                    ['id' => $value['id']],
                    ['status' => 0]
                );
            }
        }
    }

    /**
     * 當徵信項失敗時，將有參照此徵信項的二審案件退回前一狀態
     * @return void
     */
    public function reset_second_instance_target()
    {
        // 取得該使用者的所有二審案件
        $second_instance_targets = $this->CI->target_model->get_second_instance_targets_by_user($this->certification['user_id']);
        if (empty($second_instance_targets))
        {
            return;
        }

        foreach ($second_instance_targets as $target)
        {
            if (empty($target['id']) || empty($target['target_data']))
            {
                continue;
            }

            // 案件參照的徵信項
            $target_data = json_decode($target['target_data'], TRUE);
            if ( empty($target_data['certification_id']) || ! in_array($this->certification['id'], $target_data['certification_id']))
            {
                continue;
            }
            $param = ['sub_status' => TARGET_SUBSTATUS_NORNAL];
            $this->CI->target_model->update_by([
                'id' => $target['id'],
                'status' => TARGET_WAITING_APPROVE,
                'sub_status' => TARGET_SUBSTATUS_SECOND_INSTANCE
            ], $param);
            $this->CI->target_lib->insert_change_log($target['id'], $param);
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

    private function _get_related_product()
    {
        $this->CI->load->library('loanmanager/product_lib');
        $product_list = $this->CI->config->item('product_list');
        $result = [];

        foreach ($product_list as $product)
        {
            if ( ! isset($product['id']))
            {
                continue;
            }

            $product_certs = $this->CI->product_lib->get_product_certs_by_product($product, [ASSOCIATES_CHARACTER_REGISTER_OWNER]);
            if ( ! in_array($this->certification_id, $product_certs))
            {
                continue;
            }
            $result[$product['id']] = [0];

            if ( ! isset($product['sub_product']))
            {
                continue;
            }
            for ($i = 0; $i < count($product['sub_product']); $i++)
            {
                $product_info = $this->CI->product_lib->getProductInfo($product['id'], $product['sub_product'][$i]);
                $product_certs = $this->CI->product_lib->get_product_certs_by_product($product_info, [ASSOCIATES_CHARACTER_REGISTER_OWNER]);
                if ( ! in_array($this->certification_id, $product_certs))
                {
                    continue;
                }
                $result[$product['id']][] = $product['sub_product'][$i];
            }
        }

        $this->related_product = $result;
    }

    public function post_verify(): bool
    {
        return TRUE;
    }
}
