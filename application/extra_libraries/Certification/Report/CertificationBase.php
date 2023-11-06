<?php
namespace Certification\Report;

abstract class CertificationBase implements CertificationDefinition
{
    public $target;
    public $user;
    protected $CI;

    // 顯示欄位的轉換器
    public $view_converter;

    // 授審表類型
    protected $type;

    // 視圖讀取路徑
    protected $view_path = '';

    // 允許承作的產品類別列表
    public const ALLOW_PRODUCT_LIST = [];

    /**
     * 取得視圖的檔案路徑
     * @return string
     */
    abstract public function get_view_path() : string;

    /**
     * 取得資料
     * @return array
     */
    abstract public function get_data() : array;

    /**
     * 送出資料
     * @return array
     */
    abstract public function send_data() : array;

}
