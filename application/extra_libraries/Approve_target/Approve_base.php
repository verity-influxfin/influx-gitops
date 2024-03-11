<?php

namespace Approve_target;

use CertificationResult\IdentityCertificationResult;
use CertificationResult\MessageDisplay;
use CertificationResult\SocialCertificationResult;
use CreditSheet\CreditSheetFactory;

/**
 * @property  \CI_Controller $CI
 */
abstract class Approve_base implements Approve_interface
{
    protected $CI;
    protected $target;
    protected $result;
    protected $credit;
    protected $product_config;
    protected $product_config_cert;
    protected $target_product_id;
    protected $target_sub_product_id;
    protected $target_user_id;
    protected $script_status;
    protected $user_certs;
    protected $loan_amount;
    protected $platform_fee;
    protected $loan_amount_unit;

    const BROOKESIA_CLEAR = 'clear';
    const BROOKESIA_SECOND_INSTANCE = 'second_instance';
    const BROOKESIA_BLOCK = 'block';

    public function __construct($target)
    {
        $this->CI = &get_instance();

        $this->target = $target;
        $this->result = $this->get_initial_result($this->target['status'], $this->target['sub_status']);
        $this->script_status = TARGET_SCRIPT_STATUS_APPROVE_TARGET;

        $this->CI->load->library('anti_fraud_lib');
        $this->CI->load->library('brookesia/black_list_lib');
        $this->CI->load->library('brookesia/brookesia_lib');
        $this->CI->load->library('certification_lib');
        $this->CI->load->library('contract_lib');
        $this->CI->load->library('credit_lib');
        $this->CI->load->library('loanmanager/product_lib');
        $this->CI->load->library('judicialperson_lib');
        $this->CI->load->library('target_lib');
        $this->CI->load->library('user_bankaccount_lib');
        $this->CI->load->library('verify/data_verify_lib');

        $this->CI->load->model('log/log_integration_model');
        $this->CI->load->model('log/log_usercertification_model');
        $this->CI->load->model('loan/credit_sheet_review_model');
        $this->CI->load->model('loan/target_associate_model');
        $this->CI->load->model('loan/subloan_model');
        $this->CI->load->model('transaction/order_model');
        $this->CI->load->model('user/judicial_person_model');
        $this->CI->load->model('user/user_bankaccount_model');
        $this->CI->load->model('user/user_certification_model');
        $this->CI->load->model('user/user_meta_model');

        $this->CI->config->load('credit', TRUE);
        $this->set_loan_amount_unit();
    }

    /**
     * 案件核可主行為
     * @param $renew : 是否為人工同意通過
     * @return bool
     */
    public function approve(bool $renew): bool
    {
        $match_brookesia = FALSE;

        // 檢查是否為產轉
        $subloan_status = $this->CI->target_lib->is_sub_loan($this->target['target_no']);

        // 核可前的行為
        if ($this->check_before_approve() === FALSE)
        {
            goto END;
        }

        // 檢查申貸時間
        if ($this->check_apply_time() === FALSE)
        {
            goto END;
        }

        // 檢查使用者提交的徵信項，沒完成不繼續
        if ($this->check_cert($this->user_certs) === FALSE)
        {
            goto END;
        }
        // 檢查是否符合產品設定，不符合不繼續
        if ($this->check_product() === FALSE)
        {
            goto END;
        }

        // 檢查額度
        $this->credit = $this->get_user_credit();
        if ($this->check_credit($subloan_status) === FALSE)
        {
            goto END;
        }

        // 2023-10-19 所有產轉都不用檢查是否命中反詐欺
        if (!$subloan_status) {
            // 檢查是否命中反詐欺
            switch ($this->check_brookesia())
            {
                case self::BROOKESIA_BLOCK:
                    goto END;
                case self::BROOKESIA_CLEAR:
                    break;
                case self::BROOKESIA_SECOND_INSTANCE:
                default:
                    $match_brookesia = TRUE;
            }
        }

        $user_checked = $this->CI->brookesia_lib->is_user_checked($this->target_user_id, $this->target['id']);
        if ($user_checked === FALSE)
        {
            $this->CI->brookesia_lib->userCheckAllRules($this->target_user_id, $this->target['id']);
            $this->result->set_action_cancel();
            $this->result->add_memo($this->result->get_status(), '反詐欺子系統未處理完畢，案件尚無法核可', Approve_target_result::DISPLAY_DEBUG);
            goto END;
        }

        // 檢查戶役政
        if ($this->check_identity($this->target_user_id) === FALSE)
        {
            goto END;
        }

        END:
        if ($this->result->action_is_cancel())
        {
            $this->set_action_cancellation();
            return FALSE;
        }

        // 檢查是否需要進二審
        $need_second_instance = $this->get_need_second_instance($match_brookesia);
        if ($this->check_before_second_instance() === TRUE)
        {
            if ($need_second_instance === TRUE)
            {
                $this->result->set_status(TARGET_WAITING_APPROVE, TARGET_SUBSTATUS_SECOND_INSTANCE);
            }
            elseif ( ! empty($this->loan_amount) && ! empty($this->platform_fee) && ! empty($this->credit))
            {
                $this->result->set_status(TARGET_WAITING_SIGNING);
            }
            else
            {
                $status = $this->result->get_status();
                $this->result->add_memo($status, "因無credit/platform_fee/loan_amount，案件維持status={$status}", Approve_target_result::DISPLAY_DEBUG);
            }
        }

        $status = $this->result->get_status();
        if ($subloan_status === TRUE && $status === TARGET_WAITING_APPROVE)
        {
            $status = TARGET_WAITING_SIGNING;
        }

        switch ($status)
        {
            case TARGET_WAITING_SIGNING:
            case TARGET_ORDER_WAITING_VERIFY:
                $res = $this->set_target_success($renew, $subloan_status);
                if ($res === TRUE)
                {
                    $res = $this->success_notify($subloan_status);
                }
                break;
            case TARGET_FAIL:
                $res = $this->set_target_failure($subloan_status);
                if ($res === TRUE)
                {
                    $res = $this->failure_notify($subloan_status);
                }
                break;
            case TARGET_WAITING_APPROVE:
                if ($need_second_instance === TRUE)
                {
                    $res = $this->set_target_second_instance();
                }
                else
                {
                    $res = $this->set_target_waiting_approve();
                }
                break;
            default:
                log_message('error', "該狀態 ({$this->target['status']}:{$this->target['sub_status']}) 於 approve target 後，無對應的行為");
                $res = $this->set_action_cancellation();
        }

        return $res;
    }

    /**
     * 取得 flag 案件是否需進二審
     * @param bool $match_brookesia : 是否命中反詐欺
     * @return bool
     */
    public function get_need_second_instance(bool $match_brookesia): bool
    {
        if ($this->result->get_status() === TARGET_FAIL)
        {
            return FALSE;
        }
        if ($this->check_need_second_instance_by_product() === TRUE)
        {
            return TRUE;
        }
        if ($match_brookesia === TRUE)
        {
            // 命中反詐欺
            return TRUE;
        }
        if ($this->product_config['secondInstance'] ?? FALSE)
        {
            // 產品設定檔設定需二審
            return TRUE;
        }

        // todo: 如果後台二審審核通過也要共用這個架構，再想辦法

        return FALSE;
    }

    /**
     * 進二審前的檢查
     * @return bool
     */
    protected function check_before_second_instance(): bool
    {
        return TRUE;
    }

    /**
     * 依不同產品檢查是否需進二審
     * @return bool
     */
    public function check_need_second_instance_by_product(): bool
    {
        return FALSE;
    }

    /**
     * 檢查申貸時間
     * @return bool
     */
    protected function check_apply_time(): bool
    {
        //自動取消
        $limit_date = date('Y-m-d', strtotime('-' . TARGET_APPROVE_LIMIT . ' days'));
        $create_date = date('Y-m-d', $this->target['created_at']);

        if ($limit_date > $create_date)
        {
            $this->result->add_msg(TARGET_FAIL, '系統自動取消');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 檢查使用者提交的徵信項
     * @param $user_certs : 使用者提交的徵信項
     * @return bool
     */
    abstract protected function check_cert($user_certs): bool;

    /**
     * 檢查是否符合產品設定
     * @return bool
     */
    abstract protected function check_product(): bool;

    /**
     * 檢查戶役政
     * @param $user_id
     * @return bool
     */
    protected function check_identity($user_id): bool
    {
        if ($this->is_submitted() === TRUE)
        {
            $identity_cert = $this->CI->user_certification_model->get_by([
                'investor' => BORROWER,
                'status' => CERTIFICATION_STATUS_SUCCEED,
                'user_id' => $user_id,
                'certification_id' => CERTIFICATION_IDENTITY
            ]);
            if ($identity_cert)
            {
                //20240227 戶役政目前無法使用，先暫時關閉，直接通過
                return TRUE;
                // Avoid checking for the same target too many times.
                $api_verify_log = $this->CI->log_integration_model->order_by('created_at', 'DESC')->get_by([
                    'user_certification_id' => $identity_cert->id
                ]);
                $check_id_card = FALSE;
                if ( ! empty($api_verify_log))
                {
                    $current_time = date('Y-m-d H:i:s');
                    $one_day_ago = date("Y-m-d H:i:s", strtotime($current_time . ' -1 day'));
                    if ($api_verify_log->created_at < $one_day_ago)
                    {
                        $check_id_card = TRUE;
                    }
                }
                else
                {
                    $check_id_card = TRUE;
                }

                if ($check_id_card === TRUE)
                {
                    $identity_content = json_decode($identity_cert->content, TRUE);
                    $remark = json_decode($identity_cert->remark, TRUE);
                    $err_msg = $remark['error'] ?? '';
                    $result = $this->CI->certification_lib->verify_id_card_info(
                        $identity_cert->id, $identity_content, $err_msg, $remark['OCR'] ?? []
                    );

                    // Update user_certification.
                    $to_update = ['content' => json_encode($identity_content, JSON_INVALID_UTF8_IGNORE)];
                    if ($err_msg)
                    {
                        $remark['error'] = $err_msg;
                        $to_update['remark'] = json_encode($remark, JSON_INVALID_UTF8_IGNORE);
                    }
                    $this->CI->user_certification_model->update($identity_cert->id, $to_update);

                    if ($result[0] && $result[1])  // Existed id card info is wrong.
                    {
                        $this->CI->log_usercertification_model->insert([
                            'user_certification_id' => $identity_cert->id,
                            'status' => CERTIFICATION_STATUS_FAILED,
                            'change_admin' => SYSTEM_ADMIN_ID,
                        ]);
                        $cert_helper = \Certification\Certification_factory::get_instance_by_model_resource($identity_cert);
                        if (isset($cert_helper))
                        {
                            $rs = $cert_helper->set_failure(TRUE, IdentityCertificationResult::$RIS_CHECK_FAILED_MESSAGE);
                        }
                        else
                        {
                            $rs = $this->CI->certification_lib->set_failed($identity_cert->id, IdentityCertificationResult::$RIS_CHECK_FAILED_MESSAGE);
                        }
                        if ($rs === TRUE)
                        {
                            $this->CI->user_certification_model->update($identity_cert->id, [
                                'certificate_status' => CERTIFICATION_CERTIFICATE_STATUS_SENT
                            ]);
                        }
                        else
                        {
                            log_message('error', "實名認證 user_certification {$identity_cert->id} 退件失敗");
                        }
                        $this->result->set_action_cancel();
                        $this->result->add_memo($this->result->get_status(), '實名認證被退件，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
                        return FALSE;
                    }
                    elseif ($result[0] === FALSE && $result[1] === TRUE)
                    {
                        $this->CI->log_usercertification_model->insert([
                            'user_certification_id' => $identity_cert->id,
                            'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                            'change_admin' => SYSTEM_ADMIN_ID,
                        ]);
                        $cert_helper = \Certification\Certification_factory::get_instance_by_model_resource($identity_cert);
                        if (isset($cert_helper))
                        {
                            $cert_helper->result->addMessage(IdentityCertificationResult::$RIS_NO_RESPONSE_MESSAGE . '，需人工驗證', CERTIFICATION_STATUS_PENDING_TO_REVIEW, MessageDisplay::Backend);
                            $cert_helper->remark['verify_result'] = $cert_helper->result->getAllMessage(MessageDisplay::Backend);
                            $cert_helper->remark['verify_result_json'] = $cert_helper->result->jsonDump();
                            $this->CI->user_certification_model->update($identity_cert->id, [
                                'remark' => json_encode($cert_helper->remark, JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE),
                            ]);

                            $rs = $cert_helper->set_review(TRUE, IdentityCertificationResult::$RIS_NO_RESPONSE_MESSAGE);
                        }
                        else
                        {
                            $rs = FALSE;
                        }
                        if ($rs === TRUE)
                        {
                            $this->CI->user_certification_model->update($identity_cert->id, [
                                'certificate_status' => CERTIFICATION_CERTIFICATE_STATUS_SENT
                            ]);
                        }
                        else
                        {
                            log_message('error', "實名認證 user_certification {$identity_cert->id} 轉人工失敗");
                        }
                        $this->result->set_action_cancel();
                        $this->result->add_memo($this->result->get_status(), '實名認證轉人工，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
                        return FALSE;
                    }
                    else
                    {
                        return TRUE;
                    }
                }
                else
                {
                    return TRUE;
                }
            }
        }
        $this->result->set_action_cancel();
        $this->result->add_memo($this->result->get_status(), '未送出審核，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
        return FALSE;
    }

    /**
     * 檢查是否命中反詐欺
     * @return string
     */
    protected function check_brookesia(): string
    {
        $match_brookesia = self::BROOKESIA_CLEAR;

        // 檢查黑名單結果，是否需要處置
        $is_user_blocked = $this->CI->black_list_lib->check_user($this->target_user_id, CHECK_APPLY_PRODUCT);
        $is_user_second_instance = $this->CI->black_list_lib->check_user($this->target_user_id, CHECK_SECOND_INSTANCE);

        // 確認黑名單結果是否需轉二審
        if (empty($is_user_blocked) || empty($is_user_second_instance))
        {
            // 子系統回應異常 -> 二審
            $this->CI->black_list_lib->add_block_log(['userId' => $this->target_user_id]);
            $match_brookesia = self::BROOKESIA_SECOND_INSTANCE;
        }
        elseif ($is_user_blocked['isUserBlocked'])
        {
            // 禁止申貸 -> 退件
            $this->CI->black_list_lib->add_block_log($is_user_blocked);
            $match_brookesia = self::BROOKESIA_BLOCK;
            $reason = '命中反詐欺規則' . (empty($is_user_blocked['blockDescription']) ?: "：{$is_user_blocked['blockDescription']}");
            $this->result->add_msg(TARGET_FAIL, Approve_target_result::TARGET_FAIL_DEFAULT_MSG);
            $this->result->add_memo(TARGET_FAIL, $reason, Approve_target_result::DISPLAY_BACKEND);
        }
        elseif ($is_user_second_instance['isUserSecondInstance'])
        {
            // 二審
            $this->CI->black_list_lib->add_block_log($is_user_second_instance);
            $match_brookesia = self::BROOKESIA_SECOND_INSTANCE;
        }
        return $match_brookesia;
    }

    /**
     * 檢查額度
     * @param bool $subloan_status : 是否為產轉案件
     * @return bool
     */
    protected function check_credit(bool $subloan_status): bool
    {
        if (empty($this->credit))
        {
            // 算不出或找不到信用額度->失敗
            $this->result->set_action_cancel();
            $this->result->add_memo($this->result->get_status(), '無信用額度，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
            return FALSE;
        }

        if (empty($this->credit['rate']))
        {
            // 無核可利率->失敗
            $this->result->add_msg(TARGET_FAIL, Approve_target_result::TARGET_FAIL_DEFAULT_MSG);
            $this->result->add_memo(TARGET_FAIL, '無核可利率', Approve_target_result::DISPLAY_BACKEND);
            return FALSE;
        }

        $used_amount = 0;
        $other_used_amount = 0;
        $user_max_credit_amount = $this->CI->credit_lib->get_user_max_credit_amount($this->target_user_id);

        // 取得所有產品申請或進行中的案件
        $target_list = $this->CI->target_model->get_many_by([
            'id !=' => $this->target['id'],
            'user_id' => $this->target_user_id,
            'status NOT' => [TARGET_CANCEL, TARGET_FAIL, TARGET_REPAYMENTED]
        ]);
        if ( ! empty($target_list))
        {
            foreach ($target_list as $value)
            {
                if ($this->target_product_id == $value->product_id)
                {
                    $used_amount = $used_amount + (int) $value->loan_amount;
                }
                else
                {
                    $other_used_amount = $other_used_amount + (int) $value->loan_amount;
                }
                // 取得案件已還款金額
                $pay_back_transactions = $this->CI->transaction_model->get_many_by(array(
                    'source' => SOURCE_PRINCIPAL,
                    'user_from' => $this->target_user_id,
                    'target_id' => $value->id,
                    'status' => TRANSACTION_STATUS_PAID_OFF
                ));
                // 扣除已還款金額
                foreach ($pay_back_transactions as $value2)
                {
                    if ($this->target_product_id == $value->product_id)
                    {
                        $used_amount = $used_amount - (int) $value2->amount;
                    }
                    else
                    {
                        $other_used_amount = $other_used_amount - (int) $value2->amount;
                    }
                }
            }

            // 無條件進位使用額度 (基本單位為千，詳細需視產品辦法而定)
            $amount_unit = $this->get_loan_amount_unit();
            $used_amount = ($used_amount % $amount_unit != 0)
                ? ceil($used_amount / $amount_unit) * $amount_unit
                : $used_amount;
            $other_used_amount = ($other_used_amount % $amount_unit != 0)
                ? ceil($other_used_amount / $amount_unit) * $amount_unit
                : $other_used_amount;
        }

        // 產轉不需檢查金額
        if ($subloan_status === FALSE)
        {
            $amount_unit = $this->get_loan_amount_unit();

            // 取得個人最高歸戶剩餘額度
            $user_current_max_credit_amount = $user_max_credit_amount - ($used_amount + $other_used_amount);
            // 取得同產品同期間的剩餘額度
            $user_current_credit_amount = $this->credit['amount'] - $used_amount;
            // 上述兩剩餘額度取小
            $user_current_remain_amount = min($user_current_max_credit_amount, $user_current_credit_amount);

            if ($user_current_remain_amount < $amount_unit)
            {
                // 可用額度低於 $amount_unit ->失敗
                $this->result->add_msg(TARGET_FAIL, Approve_target_result::TARGET_FAIL_DEFAULT_MSG);
                $this->result->add_memo(TARGET_FAIL, "可用額度不足{$amount_unit}", Approve_target_result::DISPLAY_BACKEND);
                return FALSE;
            }

            // 檢查申貸額度
            $loan_amount = ($this->target['amount'] > $user_current_remain_amount)
                ? $user_current_remain_amount
                : $this->target['amount'];

            // 金額取整
            $loan_amount = ($loan_amount % $amount_unit != 0)
                ? floor($loan_amount / $amount_unit) * $amount_unit
                : $loan_amount;

            if ($loan_amount < $this->product_config['loan_range_s'])
            {
                $this->result->add_msg(TARGET_FAIL, Approve_target_result::TARGET_FAIL_DEFAULT_MSG);
                $this->result->add_memo(TARGET_FAIL, '可用額度不足產品設定之最低申貸額度', Approve_target_result::DISPLAY_BACKEND);
                return FALSE;
            }
        }
        else
        {
            $loan_amount = $this->target['amount'];
        }

        // 申貸金額
        $this->loan_amount = $loan_amount;
        // 平台服務費
        $this->platform_fee = $this->CI->financial_lib->get_platform_fee($this->loan_amount, $this->product_config['charge_platform'], $this->product_config['charge_platform_min']);

        return TRUE;
    }

    /**
     * 取得額度金額以 n 為計量單位
     * @return int
     */
    protected function get_loan_amount_unit(): int
    {
        return $this->loan_amount_unit;
    }

    /**
     * 設定額度金額以 n 為計量單位
     * @return void
     */
    abstract protected function set_loan_amount_unit(): void;

    /**
     * 取得使用者信用額度
     * @return array
     */
    protected function get_user_credit(): array
    {
        $credit = $this->CI->credit_lib->get_credit($this->target_user_id, $this->target_product_id, $this->target_sub_product_id, $this->target);
        if (empty($credit))
        {
            if (isset($this->product_config['checkOwner']) && $this->product_config['checkOwner'] === TRUE)
            {
                $mix_credit = $this->CI->target_lib->get_associates_user_data($this->target['id'], 'all', [0, 1], TRUE);
                $total_point = 0;
                foreach ($mix_credit as $value)
                {
                    $total_point += $this->CI->credit_lib->approve_credit($value, $this->target_product_id, $this->target_sub_product_id, NULL, FALSE, FALSE, TRUE, $this->target['instalment'], $this->target);
                }
                $rs = $this->CI->credit_lib->approve_associates_credit($this->target, $total_point);
            }
            else
            {
                $rs = $this->CI->credit_lib->approve_credit($this->target_user_id, $this->target_product_id, $this->target_sub_product_id, NULL, FALSE, $credit, FALSE, $this->target['instalment'], $this->target);
            }

            if ( ! $rs)
            {
                $this->result->set_action_cancel();
                $this->result->add_memo($this->result->get_status(), '無核可信用額度，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
                return [];
            }

            $credit = $this->CI->credit_lib->get_credit($this->target_user_id, $this->target_product_id, $this->target_sub_product_id, $this->target);
        }
        return $credit;
    }

    /**
     * 取得申貸戶提交之徵信項
     * @param $user_id : 使用者 ID
     * @param $product_id : 產品 ID
     * @param $target : 案件
     * @return array
     */
    protected function get_user_cert($user_id, $product_id, $target): array
    {
        // 取得產品設定的徵信項設定檔
        $cert_config = $this->CI->config->item('certifications');
        $product_cert = $this->product_config_cert;
        $cert_config = array_filter($cert_config, function ($value) use ($product_cert) {
            return in_array($value, $product_cert);
        }, ARRAY_FILTER_USE_KEY);

        // 取得案件略過的徵信項 ID
        $skip_cert_ids = $this->CI->certification_lib->get_skip_certification_ids($target);

        // 確認是否為法人
        $is_judicial = $this->is_judicial_product($product_id);
        if ($is_judicial)
        {
            // 取得公司負責人
            $natural_person_info = $this->CI->judicialperson_lib->getNaturalPerson($user_id);
        }

        $result = [];
        foreach ($cert_config as $key => $value)
        {
            if ($is_judicial && is_judicial_certification($key) === FALSE)
            {
                $user_cert = $this->CI->certification_lib->get_certification_info($natural_person_info->id, $key, USER_BORROWER, FALSE, TRUE);
            }
            else
            {
                $user_cert = $this->CI->certification_lib->get_certification_info($user_id, $key, USER_BORROWER, FALSE, TRUE);
            }

            if ($user_cert === FALSE)
            {
                continue;
            }

            // 社交認證過期，案件狀態退回一審前 (status=0 && sub_status=0)
            // 徵信項要改成失敗 (status=2)
            if ($user_cert->certification_id == CERTIFICATION_SOCIAL && $user_cert->expire_time < time())
            {
                $this->result->set_status(TARGET_WAITING_APPROVE, TARGET_SUBSTATUS_NORNAL);
                $cert_helper = \Certification\Certification_factory::get_instance_by_id($user_cert->id);
                if ( ! isset($cert_helper))
                {
                    continue;
                }
                $cert_helper->set_failure(TRUE, SocialCertificationResult::$EXPIRED_MESSAGE);
                continue;
            }

            if ( ! empty($user_cert) && $user_cert->status == CERTIFICATION_STATUS_SUCCEED)
            {
                $result[$key] = [
                    'id' => $user_cert->id,
                    'status' => (int) $user_cert->status,
                    'certification_id' => (int) $user_cert->certification_id,
                ];
            }
            elseif (in_array($key, $skip_cert_ids))
            {
                $result[$key] = [
                    'id' => $user_cert->id,
                    'status' => CERTIFICATION_STATUS_SUCCEED,
                    'certification_id' => (int) $user_cert->certification_id,
                ];
            }
        }
        return $result;
    }

    /**
     * assign property: $product_config_cert
     * @return void
     */
    public function set_product_config_cert()
    {
        $this->product_config_cert = $this->CI->product_lib->get_product_certs_by_product_id($this->target_product_id, $this->target_sub_product_id, []);
    }

    /**
     * 是否為法人產品
     * @param $product_id : 產品 ID
     * @return bool
     */
    protected function is_judicial_product($product_id): bool
    {
        $this->CI->load->helper('product');
        return is_judicial_product($product_id) === TRUE;
    }

    /**
     * 取得產品設定檔
     * @param $product_id : 產品 ID
     * @param $sub_product_id : 子產品 ID
     * @return array
     */
    public function get_product_config($product_id, $sub_product_id): array
    {
        return $this->CI->product_lib->get_exact_product($product_id, $sub_product_id);
    }

    /**
     * 定義屬性值
     * @return void
     */
    private function assign_property()
    {
        $this->target_product_id = $this->get_target_product_id();
        $this->target_sub_product_id = $this->get_target_sub_product_id();
        $this->target_user_id = $this->get_target_user_id();
    }

    /**
     * 取得案件的產品 ID
     * @return string
     */
    private function get_target_product_id(): string
    {
        return $this->target['product_id'] ?? '';
    }

    /**
     * 取得案件的子產品 ID
     * @return string
     */
    private function get_target_sub_product_id(): string
    {
        return $this->target['sub_product_id'] ?? '';
    }

    /**
     * 取得案件的使用者 ID
     * @return string
     */
    private function get_target_user_id(): string
    {
        return $this->target['user_id'] ?? '';
    }

    /**
     * 核可前的行為
     * @return bool
     */
    private function check_before_approve(): bool
    {
        // 取得案件基本資訊
        $this->target = $this->CI->target_model->as_array()->get($this->target['id']);
        $this->assign_property();

        // 取得使用者提交的徵信項
        $this->set_product_config_cert();
        $this->user_certs = $this->get_user_cert($this->target_user_id, $this->target_product_id, $this->target);

        // 檢查案件是否可核可
        if ($this->can_approve() === FALSE)
        {
            $this->result->set_action_cancel();
            $this->result->add_memo($this->result->get_status(), '未送出審核、或被其他排程處理中，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
            return FALSE;
        }

        // 取得產品設定
        $this->product_config = $this->get_product_config($this->target_product_id, $this->target_sub_product_id);
        if (empty($this->product_config))
        {
            log_message('error', "Approve target 查無產品設定檔 ({$this->target_product_id}:{$this->target_sub_product_id})");
            $this->result->set_action_cancel();
            $this->result->add_memo($this->result->get_status(), '查無產品設定檔，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
            return FALSE;
        }

        // 檢查是否有二審通過的 credit sheet review，但案件卻還卡在二審
        $credit_sheet_review = $this->CI->credit_sheet_review_model->has_info_by_target_id($this->target['id'], 2);
        if ($credit_sheet_review && $this->target['status'] == TARGET_WAITING_APPROVE && $this->target['sub_status'] == TARGET_SUBSTATUS_SECOND_INSTANCE)
        {
            $this->result->set_action_cancel();
            $this->result->set_status(TARGET_WAITING_SIGNING, TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET);
            return FALSE;
        }

        return TRUE;
    }

    /**
     * 是否可進行核可流程
     * @return bool
     */
    public function can_approve(): bool
    {
        if ($this->is_waiting_approve_status() === TRUE &&
            $this->is_script_status_not_use() === TRUE &&
            $this->is_submitted() === TRUE)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 是否為待核可狀態
     * @return bool
     */
    abstract protected function is_waiting_approve_status(): bool;

    /**
     * 是否未被其他跑批處理中
     * @return bool
     */
    protected function is_script_status_not_use(): bool
    {
        $affected_row = $this->update_target_script_status();
        return $affected_row > 0;
    }

    /**
     * 是否已提交審核
     * @return bool
     */
    abstract protected function is_submitted(): bool;

    /**
     * 將案件 script status 改為 approve target 處理中
     * @return int
     */
    protected function update_target_script_status(): int
    {
        $param = [
            'script_status' => $this->script_status,
        ];

        $res = $this->CI->target_model->get_affected_after_update($this->target['id'], $param, [
            'script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE
        ]);

        if ( ! $res)
        {
            return 0;
        }
        $this->CI->target_lib->insert_change_log($this->target['id'], $param);

        return $res;
    }

    /**
     * 取得 property $result 的初始值
     * @param $status
     * @param $sub_status
     * @return Approve_target_result
     */
    public function get_initial_result($status, $sub_status): Approve_target_result
    {
        return new Approve_target_result($status, $sub_status);
    }

    /**
     * 取消該次該案的跑批流程
     * @return bool
     */
    protected function set_action_cancellation(): bool
    {
        $param = $this->get_action_cancel_param();
        if(isset($param['memo'])){
            log_message('error', 'Approve_target '.$this->target['id'].' set_action_cancellation memo: ' . json_encode($param['memo']));
        }
        $res = $this->CI->target_model->update($this->target['id'], $param);
        if ($res)
        {
            $this->CI->target_lib->insert_change_log($this->target['id'], $param);
            return TRUE;
        }
        return FALSE;
    }

    protected function get_action_cancel_param(): array
    {
        $status = $this->result->get_status();
        return [
            'status' => $status,
            'sub_status' => $this->result->get_sub_status(),
            'script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE,
            'memo' => json_encode($this->result->get_all_memo($status)),
        ];
    }

    private function get_new_target_data(): array
    {
        $target_data = json_decode($this->target['target_data'], TRUE);
        $target_data['certification_id'] = array_column($this->user_certs, 'id');

        return $target_data;
    }

    /**
     * 案件審核成功
     * @param $renew : 是否為人工同意通過
     * @param bool $subloan_status : 是否為產轉案件
     * @return bool
     */
    public function set_target_success($renew, bool $subloan_status): bool
    {
        $param = $this->get_approve_success_param($renew, $subloan_status);
        if (empty($param))
        {
            return FALSE;
        }

        if(isset($param['memo'])){
            log_message('error', 'Approve_target '.$this->target['id'].' set_target_success memo: ' . json_encode($param['memo']));
        }

        $this->CI->target_model->update($this->target['id'], $param);

        $credit_sheet = CreditSheetFactory::getInstance($this->target['id']);
        $credit_sheet->approve($credit_sheet::CREDIT_REVIEW_LEVEL_SYSTEM, '一審通過');
        if ($this->target['status'] == TARGET_WAITING_APPROVE)
        {
            $credit_sheet->setFinalReviewerLevel($credit_sheet::CREDIT_REVIEW_LEVEL_SYSTEM);
            $credit_sheet->archive($this->credit);
        }

        $this->CI->target_lib->insert_change_log($this->target['id'], $param);

        return TRUE;
    }

    /**
     * 取得更新「審核成功案件」的參數
     * @param $renew : 是否為人工同意通過
     * @param $subloan_status
     * @return array
     */
    protected function get_approve_success_param($renew, $subloan_status): array
    {
        $target_data = $this->get_new_target_data();
        $param = [
            'sub_product_id' => $this->target_sub_product_id,
            'loan_amount' => $this->loan_amount,
            'credit_level' => $this->credit['level'],
            'platform_fee' => $this->get_platform_fee($subloan_status),
            'interest_rate' => $this->credit['rate'],
            'status' => TARGET_WAITING_SIGNING,
            'sub_status' => $renew ? TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET : $this->result->get_sub_status(),
            'target_data' => json_encode($target_data),
            'script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE,
        ];

        $remark = $this->result->get_msg(TARGET_WAITING_SIGNING);
        if ( ! empty($remark))
        {
            $param['remark'] = (empty($this->target['remark']) ? $remark : $this->target['remark'] . ', ' . $remark);
        }

        $memo = $this->result->get_all_memo(TARGET_WAITING_SIGNING);
        if ( ! empty($memo))
        {
            $param['memo'] = json_encode($memo, JSON_PRETTY_PRINT);
        }

        if (empty($this->target['contract_id']) || $this->target['loan_amount'] != $this->loan_amount)
        {
            $contract_type = 'lend';

            if ($this->target_product_id == PRODUCT_FOREX_CAR_VEHICLE && $this->target_sub_product_id == 3)
            {
                $company_data = $this->CI->judicial_person_model->get_by(['company_user_id' => $this->target_user_id]);
                $userData = $this->CI->user_model->get($company_data->user_id);
                $contract_year = date('Y') - 1911;
                $contract_month = date('m');
                $contract_day = date('d');
                $contract_type = 'lend_FEV';
                $contract_data = ['', $this->target_user_id, $this->loan_amount, $this->target['interest_rate'], $contract_year, $contract_month, $contract_day, $target_data['vin'], '', '', '', $company_data->company, $userData->name, $company_data->tax_id, $company_data->cooperation_address];
            }
            else
            {
                $contract_data = ['', $this->target_user_id, $this->loan_amount, $this->credit['rate'], ''];
            }
            $param['contract_id'] = $this->CI->contract_lib->sign_contract($contract_type, $contract_data);
        }

        return $param;
    }

    /**
     * @param $subloan_status
     * @return int
     */
    protected function get_platform_fee($subloan_status): int
    {
        return $this->platform_fee;
    }

    /**
     * 案件審核失敗
     * @param $subloan_status
     * @return bool
     */
    public function set_target_failure($subloan_status): bool
    {
        $param = $this->get_approve_failure_param();
        if (empty($param))
        {
            return FALSE;
        }

        if(isset($param['memo'])){
            log_message('error', 'Approve_target '.$this->target['id'].' set_target_failure memo: ' . json_encode($param['memo']));
        }

        $this->CI->target_model->update($this->target['id'], $param);
        $this->CI->target_lib->insert_change_log($this->target['id'], $param);

        if ($subloan_status === TRUE)
        {
            $subloan = $this->CI->subloan_model->get_by(array(
                'status' => [0, 1],
                'new_target_id' => $this->target['id'],
            ));
            if ( ! empty($subloan))
            {
                $result = $this->CI->subloan_model->update($subloan->id, array('status' => 9));
                if ($result)
                {
                    $this->CI->target_lib->insert_change_log($subloan->target_id, array('sub_status' => TARGET_SUBSTATUS_NORNAL), 0, SYSTEM_ADMIN_ID);
                    $this->CI->target_model->update($subloan->target_id, array('sub_status' => TARGET_SUBSTATUS_NORNAL));
                }
            }
        }

        return TRUE;
    }

    /**
     * 取得更新「案件審核失敗」的參數
     * @return array
     */
    protected function get_approve_failure_param(): array
    {
        $param = [
            'loan_amount' => 0,
            'status' => TARGET_FAIL,
            'remark' => $this->result->get_msg(TARGET_FAIL),
            'script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE,
        ];

        $memo = $this->result->get_all_memo(TARGET_FAIL);
        if ( ! empty($memo))
        {
            $param['memo'] = json_encode($memo, JSON_PRETTY_PRINT);
        }

        return $param;
    }

    public function set_target_waiting_approve(): bool
    {
        $param = [
            'script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE,
            'status' => TARGET_WAITING_APPROVE,
            'sub_status' => $this->result->get_sub_status(),
        ];

        $memo = $this->result->get_all_memo(TARGET_WAITING_APPROVE);
        if ( ! empty($memo))
        {
            $param['memo'] = json_encode($memo, JSON_PRETTY_PRINT);
            log_message('error', 'Approve_target '.$this->target['id'].' set_target_waiting_approve memo: ' . json_encode($param['memo']));
        }

        $this->CI->target_model->update($this->target['id'], $param);

        $this->CI->target_lib->insert_change_log($this->target['id'], $param);
        $this->target = array_replace($this->target, $param);
        return TRUE;
    }

    /**
     * 案件進二審
     * @return bool
     */
    public function set_target_second_instance(): bool
    {
        $param = $this->get_approve_second_instance_param();
        if (empty($param))
        {
            return FALSE;
        }
        if(isset($param['memo'])){
            log_message('error', 'Approve_target '.$this->target['id'].' set_target_second_instance memo: ' .
                json_encode($param['memo']));
        }
        $credit_sheet = CreditSheetFactory::getInstance($this->target['id']);
        $credit_sheet_approve_res = $credit_sheet->approve($credit_sheet::CREDIT_REVIEW_LEVEL_SYSTEM, '需二審查核');
        if ($credit_sheet_approve_res !== $credit_sheet::RESPONSE_CODE_OK)
        {
            // 避免已處理的案件 script_status 卡在 4
            $res = $this->CI->target_model->update_by([
                'id' => $this->target['id']
            ], ['script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE]);
            return FALSE;
        }

        $res = $this->CI->target_model->update_by([
            'id' => $this->target['id'],
            'status' => TARGET_WAITING_APPROVE
        ], $param);

        if ( ! $res)
        {
            return FALSE;
        }
        $this->CI->target_lib->insert_change_log($this->target['id'], $param);
        $this->target = array_replace($this->target, $param);
        return TRUE;
    }

    /**
     * 取得更新「進二審案件」的參數
     * @return array
     */
    protected function get_approve_second_instance_param(): array
    {
        $target_data = $this->get_new_target_data();
        return [
            'sub_product_id' => $this->target_sub_product_id,
            'loan_amount' => $this->loan_amount,
            'credit_level' => $this->credit['level'],
            'platform_fee' => $this->platform_fee,
            'interest_rate' => $this->credit['rate'],
            'status' => TARGET_WAITING_APPROVE,
            'sub_status' => TARGET_SUBSTATUS_SECOND_INSTANCE,
            'target_data' => json_encode($target_data),
            'script_status' => TARGET_SCRIPT_STATUS_NOT_IN_USE,
            'memo' => json_encode($this->result->get_all_memo(TARGET_WAITING_APPROVE))
        ];
    }

    /**
     * 審核成功的通知
     * @param bool $subloan_status : 是否為產轉案件
     * @return bool
     */
    public function success_notify(bool $subloan_status): bool
    {
        if ($this->target['status'] == TARGET_WAITING_APPROVE)
        {
            return $this->CI->notification_lib->approve_target($this->target_user_id, TARGET_WAITING_SIGNING, $this->target, $this->loan_amount, $subloan_status);
        }
        return TRUE;
    }

    /**
     * 審核失敗的通知
     * @param bool $subloan_status : 是否為產轉案件
     * @return bool
     */
    public function failure_notify(bool $subloan_status): bool
    {
        return $this->CI->notification_lib->approve_target($this->target_user_id, TARGET_FAIL, $this->target, 0, $subloan_status, $this->target['remark']);
    }
}