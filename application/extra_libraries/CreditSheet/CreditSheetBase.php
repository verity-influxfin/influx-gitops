<?php


namespace CreditSheet;

abstract class CreditSheetBase
{
    public $creditSheetRecord;
    public $creditRecord;

    public const TYPE_PERSONAL = 1;
    public const TYPE_JUDICIAL = 2;
    public const TYPE_LIST = [
        self::TYPE_PERSONAL => "personal",
        self::TYPE_JUDICIAL => "judicial",
    ];

    // 顯示欄位的轉換器
    public $viewConverter;

    // 授審表類型
    protected $type;

    // 視圖讀取路徑
    protected $viewPath = '';

    // 允許承作的產品類別列表
    public const ALLOW_PRODUCT_LIST = [];

    /**
     * 取得視圖的檔案路徑
     * @return string
     */
    abstract public function getViewPath() : string;

    /**
     * 取得結構資料
     * @return array
     */
    abstract public function getStructuralData() : array;

    /**
     * 取得資料
     * @return array
     */
    abstract public function getData() : array;

    /**
     * 取得授審表類型
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 取得授審表類型的名稱
     * @return string
     */
    public function getTypeName(): string
    {
        return self::TYPE_LIST[$this->type];
    }
}