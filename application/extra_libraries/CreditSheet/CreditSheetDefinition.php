<?php


namespace CreditSheet;

interface CreditSheetDefinition
{
    public const TYPE_PERSONAL = 1;
    public const TYPE_JUDICIAL = 2;
    public const TYPE_LIST = [
        self::TYPE_PERSONAL => "personal",
        self::TYPE_JUDICIAL => "judicial",
    ];

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

    // 動撥方式
    public const DRAWDOWN_TYPE_FULL = 1;
    public const DRAWDOWN_TYPE_PARTIAL = 2;
    public const DRAWDOWN_TYPE_REVOLVING = 3;
    public const DRAWDOWN_TYPE_LIST = [
        self::DRAWDOWN_TYPE_FULL => "一次動用",
        self::DRAWDOWN_TYPE_PARTIAL => "分次動用",
        self::DRAWDOWN_TYPE_REVOLVING => "循環動用",
    ];

    // 利息方式
    // 按月計息，本息均攤
    public const INTEREST_TYPE_EQUAL_TOTAL_PAYMENT = 1;
    // 按月繳息，本金到期一次清償
    public const INTEREST_TYPE_INTEREST_ONLY = 2;
    public const INTEREST_TYPE_LIST = [
        self::INTEREST_TYPE_EQUAL_TOTAL_PAYMENT => "按月計息，本息均攤",
        self::INTEREST_TYPE_INTEREST_ONLY => "按月繳息，本金到期一次清償",
    ];

    // 申貸額度及條件
    public const APPLY_LINE_TYPE_SINGLE = 1;
    public const APPLY_LINE_TYPE_MIXED = 2;
    public const APPLY_LINE_TYPE_LIST = [
        self::APPLY_LINE_TYPE_SINGLE => "單項額度",
        self::APPLY_LINE_TYPE_MIXED => "綜合額度",
    ];

    // 可審查人員
    public const REVIEWER_CREDIT_SYSTEM = 1;
    public const REVIEWER_CREDIT_ANALYST = 2;
    public const REVIEWER_CHIEF_RISK_OFFICER = 3;
    public const REVIEWER_CHIEF_EXECUTIVE_OFFICER = 4;
    public const REVIEWER_LIST = [
        self::REVIEWER_CREDIT_SYSTEM => "一審",
        self::REVIEWER_CREDIT_ANALYST => "二審",
        self::REVIEWER_CHIEF_RISK_OFFICER => "風控長",
        self::REVIEWER_CHIEF_EXECUTIVE_OFFICER => "總經理",
    ];

    // 回應代碼
    public const RESPONSE_CODE_OK = 0;
    public const RESPONSE_CODE_NO_PERMISSION = 1;
    public const RESPONSE_CODE_TRANSACTION_ROLLBACK = 2;
    public const RESPONSE_CODE_REPEATED_APPROVAL = 3;
    public const RESPONSE_CODE_INVALID_ACTION = 4;
    public const RESPONSE_CODE_LIST = [
        self::RESPONSE_CODE_OK => "已完成",
        self::RESPONSE_CODE_NO_PERMISSION => "沒有操作權限",
        self::RESPONSE_CODE_TRANSACTION_ROLLBACK => "此次操作失敗，請重新再試",
        self::RESPONSE_CODE_REPEATED_APPROVAL => "無法重複審核",
        self::RESPONSE_CODE_INVALID_ACTION => "無效的操作",
    ];

}