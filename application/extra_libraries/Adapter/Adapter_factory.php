<?php
namespace Adapter;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 對接銀行的企金產品，送出前需先進行
 * 1. 欄位轉換
 * 2. 欄位檢查
 */
class Adapter_factory
{
    protected static $CI;

    public static function getInstance($bank, $target_id): ?Adapter_base
    {
        try
        {
            self::$CI = &get_instance();
            self::$CI->load->model('loan/target_model');
            $product_info = self::$CI->target_model->get_product_id_by_id($target_id);

            if (empty($product_info['product_id']) || empty($product_info['sub_product_id']))
            {
                goto END;
            }

            if ($product_info['product_id'] == PRODUCT_SK_MILLION_SMEG)
            {
                switch ($product_info['sub_product_id'])
                {
                    case SUB_PRODUCT_ID_SK_MILLION:
                        switch ($bank)
                        {
                            case MAPPING_MSG_NO_BANK_NUM_SKBANK:
                                $instance = new sk_million\Adapter_sk_bank();
                                break;
                            case MAPPING_MSG_NO_BANK_NUM_KGIBANK:
                                $instance = new sk_million\Adapter_kgi_bank();
                                break;
                        }
                        break;
                    case SUB_PRODUCT_ID_CREDIT_INSURANCE:
                        switch ($bank)
                        {
                            case MAPPING_MSG_NO_BANK_NUM_SKBANK:
                                $instance = new credit_insurance\Adapter_sk_bank();
                                break;
                            case MAPPING_MSG_NO_BANK_NUM_KGIBANK:
                                $instance = new credit_insurance\Adapter_kgi_bank();
                                break;
                        }
                        break;
                }
            }

            END:
            if ( ! isset($instance))
            {
                log_msg('error', "欲建立未支援的送件檢核表 (案件編號:{$target_id},銀行編號:{$bank}) ");
                return NULL;
            }
            return $instance;
        }
        catch (\Exception $e)
        {
            error_log("Exception: " . $e->getMessage());
        }
        return NULL;
    }

}
