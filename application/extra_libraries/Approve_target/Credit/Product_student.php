<?php

namespace Approve_target\Credit;

use Approve_target\Approve_target_result;
use Approve_target\Trait_check_product\Check_applicant_age;

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
        $option_cert = $this->product_config['option_certifications'] ?? [];
        $cer_success_id = []; // 存審核成功的徵信項

        foreach ($user_certs as $value)
        {
            if ($value['status'] != CERTIFICATION_STATUS_SUCCEED)
            {
                // 非為選填項
                if ( ! in_array($value['certification_id'], $option_cert))
                {
                    $this->result->set_action_cancel();
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
}