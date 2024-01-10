<?php

namespace Approve_target\Trait_check_product;

/**
 * 檢查申貸者年齡
 */
trait Check_applicant_age
{
    /**
     * @param array $target
     * @return bool
     * @uses Judicialperson_lib
     * @uses Product_lib
     * @uses Target_lib
     * @uses User_model
     * @uses product_helper
     */
    protected function get_check_applicant_age_res(array $target): bool
    {
        if (!$this->CI->product_lib->need_chk_allow_age($target['product_id'])) {
            return true;
        }
        if (is_judicial_product($target['product_id'])) {
            // 若為法人戶，改取負責人的生日來計算
            $user_info = $this->CI->judicialperson_lib->getNaturalPerson($target['user_id']);
        } else {
            $user_info = $this->CI->user_model->get($target['user_id']);
        }

        $age = get_age($user_info->birthday);

        return boolval(
            $this->CI->product_lib->is_age_available($age, $target['product_id'], $target['sub_product_id'])
        );
    }
}
