<?php

/**
 * 檢查案件的 target_data
 * 當 product_list.targetData = TRUE
 */
trait Check_target_data
{
    /**
     * @param array $target
     * @param array $product_config
     * @return bool
     */
    protected function get_check_target_data_res(array $target, array $product_config): bool
    {
        if (empty($product_config['targetData']))
        {
            return TRUE;
        }

        $target_data = json_decode($target['target_data']);
        if (json_last_error() !== JSON_ERROR_NONE || empty($target_data))
        {
            return TRUE;
        }

        foreach ($product_config['targetData'] as $target_data_key => $target_data_value)
        {
            if (empty($target_data->$target_data_key) && isset($target_data_value[3]) && ! $target_data_value[3])
            {
                return FALSE;
            }
        }
        return TRUE;
    }
}