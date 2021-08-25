<?php


namespace CreditSheet;

interface CreditSheetDefinition
{
    // 核貸層次
    public const CREDIT_REVIEW_LEVEL_SYSTEM = 1;
    public const CREDIT_REVIEW_LEVEL_MANUAL = 2;
    public const CREDIT_REVIEW_LEVEL_CRO = 3;
    public const CREDIT_REVIEW_LEVEL_CEO = 4;
    public const CREDIT_REVIEW_LEVEL_LIST = [
        self::CREDIT_REVIEW_LEVEL_SYSTEM => "一審",
        self::CREDIT_REVIEW_LEVEL_MANUAL => "二審",
        self::CREDIT_REVIEW_LEVEL_CRO => "風控長",
        self::CREDIT_REVIEW_LEVEL_CEO => "總經理",
    ];

    // 授信類型
    public const CREDIT_CATEGORY_NEW_TARGET = 1;
    public const CREDIT_CATEGORY_INCREMENTAL_LOAN = 2;
    public const CREDIT_CATEGORY_CHANGE_LOAN = 3;
    public const CREDIT_CATEGORY_SUBLOAN = 4;
    public const CREDIT_CATEGORY_LIST = [
        self::CREDIT_CATEGORY_NEW_TARGET => "新案",
        self::CREDIT_CATEGORY_INCREMENTAL_LOAN => "增貸",
        self::CREDIT_CATEGORY_CHANGE_LOAN => "改貸",
        self::CREDIT_CATEGORY_SUBLOAN => "產品轉換",
    ];

    // 取得基本資料
    public function getBasicInfo();
    public function getCreditCategoryList();
    public function getReviewLevelList();
}