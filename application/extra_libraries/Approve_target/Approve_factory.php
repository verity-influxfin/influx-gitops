<?php

namespace Approve_target;

class Approve_factory
{
    private $CI;

    public function get_instance_by_model_data($target)
    {
        $this->CI = &get_instance();

        try
        {
            if (is_object($target))
            {
                $target = json_decode(json_encode($target), TRUE);
                if (json_last_error() !== JSON_ERROR_NONE)
                {
                    log_msg('error', 'Approve target 出現錯誤的存取');
                    return NULL;
                }
            }
            elseif ( ! is_array($target))
            {
                log_msg('error', 'Approve target 出現錯誤的存取');
                return NULL;
            }

            if ( ! isset($target['product_id']) || ! isset($target['sub_product_id']))
            {
                log_msg('error', 'Approve target 出現錯誤的存取');
                return NULL;
            }

            switch ($target['product_id'])
            {
                case PRODUCT_ID_STUDENT:
                    switch ($target['sub_product_id'])
                    {
                        case 0: // 學生貸
                            $instance = new \Approve_target\Credit\Product_student($target);
                            break;
                        case 1: // 學生工程師貸
                            break;
                    }
                    break;
                case PRODUCT_ID_SALARY_MAN:
                    switch ($target['sub_product_id'])
                    {
                        case 0: // 上班族貸
                            break;
                        case 1: // 上班族工程師貸
                            break;
                    }
                    break;
            }

            if (empty($instance))
            {
                log_message('error', "欲建立未支援的產品項 ({$target['product_id']}) ");
                return NULL;
            }
            return $instance;

        }
        catch (\Exception $e)
        {
            log_message('error', 'Approve target 出現錯誤的存取 (' . $e->getMessage() . ')');
            return NULL;
        }
    }
}