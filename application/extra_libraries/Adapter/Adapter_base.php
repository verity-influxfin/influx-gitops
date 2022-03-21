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

}