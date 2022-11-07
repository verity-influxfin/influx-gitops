<?php
namespace Adapter;

defined('BASEPATH') OR exit('No direct script access allowed');

class Adapter_factory
{
    public static function getInstance($bank): ?Adapter_base
    {
        try
        {
            switch ($bank)
            {
                case MAPPING_MSG_NO_BANK_NUM_SKBANK:
                    $instance = new Adapter_sk_bank();
                    break;
                default:
                case MAPPING_MSG_NO_BANK_NUM_KGIBANK:
                    $instance = new Adapter_kgi_bank();
                    break;
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
