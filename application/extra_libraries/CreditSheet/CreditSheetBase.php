<?php


namespace CreditSheet;

abstract class CreditSheetBase
{
    public const TYPE_PERSONAL = 1;
    public const TYPE_JUDICIAL = 2;
    public const TYPE_LIST = [
        self::TYPE_PERSONAL => "personal",
        self::TYPE_JUDICIAL => "judicial",
    ];

    // 允許承作的產品類別列表
    public const ALLOW_PRODUCT_LIST = [1, 3];

    public $creditSheetRecord;
    public $creditRecord;
    protected $type;

    /**
     * 取得結構資料
     * @return mixed
     */
    abstract public function getStructuralData();


    /**
     * 取得資料
     * @return mixed
     */
    abstract public function getData();

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