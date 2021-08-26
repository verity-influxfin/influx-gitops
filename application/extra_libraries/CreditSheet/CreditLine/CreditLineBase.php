<?php
namespace CreditSheet\CreditLine;

interface CreditLineBase
{
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

    // 利息方式
    public const APPLY_LINE_TYPE_SINGLE = 1;
    public const APPLY_LINE_TYPE_MIXED = 2;
    public const APPLY_LINE_TYPE_LIST = [
        self::APPLY_LINE_TYPE_SINGLE => "單項額度",
        self::APPLY_LINE_TYPE_MIXED => "綜合額度",
    ];

    // 可審查人員
    public const REVIEWER_CREDIT_ANALYST = 1;
    public const REVIEWER_CHIEF_RISK_OFFICER = 2;
    public const REVIEWER_CHIEF_EXECUTIVE_OFFICER = 3;
    public const REVIEWER_LIST = [
        self::REVIEWER_CREDIT_ANALYST => "二審",
        self::REVIEWER_CHIEF_RISK_OFFICER => "風控長",
        self::REVIEWER_CHIEF_EXECUTIVE_OFFICER => "總經理",
    ];

    // 取得核可額度資料
    public function getCreditLineInfo() : array;

    // 動撥方式
    public function getDrawdownTypeList() : array;

    // 計息方式
    public function getInterestTypeList() : array;

    // 申貸額度及條件定義列表
    public function getApplyLineTypeList() : array;
}