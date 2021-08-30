<?php
namespace CreditSheet\CreditLine;

interface CreditLineBase
{
    // 取得核可額度資料
    public function getCreditLineInfo() : array;

    // 動撥方式
    public function getDrawdownTypeList() : array;
    // 計息方式
    public function getInterestTypeList() : array;
    // 申貸額度及條件定義列表
    public function getApplyLineTypeList() : array;

}