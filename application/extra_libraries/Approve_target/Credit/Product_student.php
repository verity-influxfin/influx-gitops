<?php

namespace Approve_target\Credit;

use Approve_target\Approve_target_result;
use Approve_target\Trait_check_product\Check_applicant_age;
use Certification\Certification_factory;

/**
 * 核可 信用貸款
 *
 * 產品：學生貸
 * 產品ID：1 (PRODUCT_ID_STUDENT)
 * 子產品ID：0
 */
class Product_student extends Approve_target_credit_base
{
    use Check_applicant_age;

    /**
     * 檢查使用者提交的徵信項
     * @param $user_certs
     * @return bool
     */
    protected function check_cert($user_certs): bool
    {
        $cert = $this->product_config['certifications'] ?? [];
        $option_cert = $this->product_config['option_certifications'] ?? [];
        $cer_success_id = []; // 存審核成功的徵信項

        foreach ($user_certs as $value) {
            $cert_helper = Certification_factory::get_instance_by_id($value['id']);
            // 非成功或過期
            if ($cert_helper->is_succeed() === FALSE || $cert_helper->is_expired() === TRUE)
            {
                // 非為選填項 = 必填項 - 選填項
                if (in_array($value['certification_id'], array_diff($cert, $option_cert)))
                {
                    $this->result->set_action_cancel();
                    $this->result->set_status(TARGET_WAITING_APPROVE, TARGET_SUBSTATUS_NORNAL);
                    $this->result->add_memo($this->result->get_status(), "必填徵信項({$value['certification_id']})未審核成功，案件尚無法核可", Approve_target_result::DISPLAY_BACKEND);
                    return FALSE;
                }
            }
            else
            {
                $cer_success_id[] = $value['certification_id'];
            }
        }

        // 檢查系統自動過件，必要的徵信項
        $required_cert = array_diff($this->product_config_cert, $option_cert);
        if ( ! empty(array_diff($required_cert, $cer_success_id)))
        {
            $this->result->set_action_cancel();
            $this->result->add_memo($this->result->get_status(), '必填徵信項未全數成功(' . implode(',', array_diff($required_cert, $cer_success_id)) . ')，案件尚無法核可', Approve_target_result::DISPLAY_BACKEND);
            return FALSE;
        }

        // 檢查學生認證的「學制」
        $user_meta_school_system = $this->CI->user_meta_model->as_array()->get_by([
            'user_id' => $this->target_user_id,
            'meta_key' => 'school_system'
        ]);
        if ( ! empty($user_meta_school_system['meta_value']) && $user_meta_school_system['meta_value'] == 3)
        {
            // 若為五專，直接退案
            $this->result->add_msg(TARGET_FAIL, Approve_target_result::TARGET_FAIL_DEFAULT_MSG);
            $this->result->add_memo(TARGET_FAIL, '學制為五專', Approve_target_result::DISPLAY_BACKEND);
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 檢查是否符合產品設定
     * @return bool
     */
    protected function check_product(): bool
    {
        if ($this->get_check_applicant_age_res($this->target) === FALSE)
        {
            $this->result->add_msg(TARGET_FAIL, '身份非平台服務範圍');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 是否已提交審核
     * @return bool
     */
    protected function is_submitted(): bool
    {
        return $this->target['certificate_status'] == TARGET_CERTIFICATE_SUBMITTED;
    }

    /**
     * 進二審前的檢查
     * @return bool
     */
    protected function check_before_second_instance(): bool
    {
        // 風控：曾申請上班族貸“成功申貸”者->二審系統自動退件不通過
        $apply_prod_salary_man_res = $this->CI->target_model->chk_exist_by_status([
            'user_id' => $this->target_user_id,
            'product_id' => PRODUCT_ID_SALARY_MAN,
            'status' => [TARGET_REPAYMENTING, TARGET_REPAYMENTED]
        ]);
        if ($apply_prod_salary_man_res === TRUE)
        {
            $this->result->add_msg(TARGET_FAIL, Approve_target_result::TARGET_FAIL_DEFAULT_MSG);
            $this->result->add_memo(TARGET_FAIL, '曾成功申請上班族貸', Approve_target_result::DISPLAY_BACKEND);
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
        $result = parent::can_approve();

        if (empty($this->user_certs))
        {
            return $result;
        }

        $user_certs = $this->user_certs;
        if (isset($user_certs[CERTIFICATION_IDENTITY]['status']) && $user_certs[CERTIFICATION_IDENTITY]['status'] == CERTIFICATION_STATUS_SUCCEED &&
            isset($user_certs[CERTIFICATION_STUDENT]['status']) && $user_certs[CERTIFICATION_STUDENT]['status'] == CERTIFICATION_STATUS_SUCCEED)
        {
            // 1. 申請學生貸，且實名認證、學生認證已審核通過
            // 2. 通過反詐欺爬蟲（未命中、未被封鎖）
            // 符合者，將金融驗證轉為待驗證
            $this->CI->load->library('anti_fraud_lib');
            $anti_fraud_response = $this->CI->anti_fraud_lib->get_by_user_id($this->target_user_id);
            if (isset($anti_fraud_response['status']) && $anti_fraud_response['status'] == 200 && empty($anti_fraud_response['response']['results']))
            {
                $this->CI->load->model('user/user_bankaccount_model');
                $bank_account = $this->CI->user_bankaccount_model->get_by([
                    'status' => VIRTUAL_ACCOUNT_STATUS_AVAILABLE,
                    'investor' => USER_BORROWER,
                    'user_id' => $this->target_user_id
                ]);
                if (isset($bank_account->verify) && $bank_account->verify == 0)
                {
                    $cert_debit_card = $this->CI->user_certification_model->get($bank_account->user_certification_id);
                    if ( ! empty($cert_debit_card))
                    {
                        $new_content = json_encode(array_merge(
                            json_decode($cert_debit_card->content ?? '', TRUE),
                            ['in_advance' => TRUE]
                        ));
                        $this->CI->user_certification_model->update($cert_debit_card->id, ['content' => $new_content]);
                        $this->CI->user_bankaccount_model->update($bank_account->id, ['verify' => 2]);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 依不同產品檢查是否需進二審
     * @return bool
     */
    public function check_need_second_instance_by_product(): bool
    {
        $school_config = $this->CI->config->item('school_points');
        $info = $this->CI->user_meta_model->get_by(['user_id' => $this->target_user_id, 'meta_key' => 'school_name']);

        if (in_array($info->meta_value, $school_config['lock_school']))
        {
            $this->result->add_memo(TARGET_WAITING_APPROVE, "{$info->meta_value}為黑名單學校", Approve_target_result::DISPLAY_BACKEND);
            return TRUE;
        }

        // 信評等級是10，要轉二審
        if ($this->credit['level'] == 10)
        {
            return TRUE;
        }

        return FALSE;
    }
    
    /**
     * 設定額度金額以 n 為計量單位
     * @return void
     */
    protected function set_loan_amount_unit(): void
    {
        $this->loan_amount_unit = 1000;
    }

}
