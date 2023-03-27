<?php

/**
 * 檢查關係人的徵信項
 * 當 product_list.check_associates_certs = TRUE
 */
trait Check_associates_certs
{
    /**
     * @param array $target : 案件資料
     * @param array $product_config : 產品設定檔
     * @return bool
     * @uses Certification_lib
     * @uses Target_lib
     */
    protected function get_check_associates_certs_res(array $target, array $product_config): bool
    {
        if ( ! isset($product_config['check_associates_certs']) || $product_config['check_associates_certs'] !== TRUE)
        {
            return TRUE;
        }

        if ( ! isset($product_config['checkOwner']) || $product_config['checkOwner'] !== TRUE)
        {
            return TRUE;
        }

        if ($target['sub_status'] != TARGET_SUBSTATUS_WAITING_ASSOCIATES)
        {
            return TRUE;
        }

        $this->CI->certification_lib->check_associates($target['id']);
        $cer_user_list = $this->CI->target_lib->get_associates_user_data($target['id'], 'all', [0, 1], FALSE);
        foreach ($cer_user_list as $list_value)
        {
            if ($list_value->status == 0 || $list_value->user_id == NULL)
            {
                return FALSE;
            }
        }

        return $this->CI->certification_lib->associate_certs_are_succeed($target);
    }
}