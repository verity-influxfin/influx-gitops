<?php
namespace Adapter;
defined('BASEPATH') OR exit('No direct script access allowed');

use Adapter\Adapter_base;

class Adapter_sk_bank extends Adapter_base
{
    public static $mapping_table = [];
    public static $roc_date_table = [];

    /**
     * 轉換資料格式
     * @param array $data
     * @return array
     */
    public function convert_text(array $data) : array
    {
        // TODO: 暫時不處理
        return $data;
    }

}