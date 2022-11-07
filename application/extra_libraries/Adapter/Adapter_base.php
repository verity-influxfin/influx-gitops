<?php
namespace Adapter;
defined('BASEPATH') OR exit('No direct script access allowed');

use Adapter\Adapter_definition;

abstract class Adapter_base implements Adapter_definition
{
    /**
     * 轉換資料格式
     * @param array $data
     * @return array
     */
    abstract public function convert_text(array $data) : array;

    /**
     * 轉換圖片列表
     * @param array $data
     * @param bool $get_api_attach_no
     * @return array
     */
    abstract public function convert_attach(array $data, bool $get_api_attach_no = FALSE) : array;

    /**
     * 檢查必填欄位
     * @param array $data
     * @return array
     */
    abstract public function check_required_column(array $data): array;

}