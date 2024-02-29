<?php

namespace Approve_target\Credit;

use Approve_target\Trait_check_product\Check_applicant_age;

/**
 * 核可 信用貸款
 *
 * 產品：房產消費貸 購房貸
 * 產品ID：5 (PRODUCT_ID_HOME_LOAN)
 * 子產品ID：10 (SUB_PRODUCT_ID_HOME_LOAN_SHORT)
 */
class Product_home_loan_short extends Approve_target_credit_base
{
    use Check_applicant_age;

    /**
     * 檢查使用者提交的徵信項
     * @param $user_certs : 使用者提交的徵信項
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

//    /**
//     * 取得額度金額以 n 為計量單位
//     * @return int
//     */
//    protected function get_loan_amount_unit(): int
//    {
//        return 10000;
//    }

    /**
     * 設定額度金額以 n 為計量單位
     * @return void
     */
    protected function set_loan_amount_unit(): void
    {
        $this->loan_amount_unit = 10000;
    }

    /**
     * @param $subloan_status
     * @return int
     */
    protected function get_platform_fee($subloan_status): int
    {
        // 產品轉換手續費：每筆產轉酌收手續費15000元
        if ($subloan_status === TRUE)
        {
            return 15000;
        }
        return $this->platform_fee;
    }

}
