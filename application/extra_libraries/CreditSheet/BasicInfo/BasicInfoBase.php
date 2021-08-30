<?php
namespace CreditSheet\BasicInfo;

interface BasicInfoBase
{
    // 取得基本資料
    public function getBasicInfo() : array;

    // 取得授信類型
    public function getCreditCategoryList() : array;
    // 取得核貸層次定義列表
    public function getReviewLevelList() : array;
    // 取得可承作產品類別列表
    public function getProductCategoryList() : array;
}