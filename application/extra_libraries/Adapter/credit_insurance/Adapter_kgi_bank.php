<?php

namespace Adapter\credit_insurance;

use Adapter\Adapter_base;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 送件檢核表
 *
 * 銀行：凱基
 * 產品：信保專案融資
 */
class Adapter_kgi_bank extends Adapter_base
{
    public static $mapping_table = [];
    public static $roc_date_table = [];

    public static $required_attach_table = [
        'A01' => 'A01', // 公司變更事項登記卡及工商登記查詢
        'B02' => 'A02', // 負責人身分證 + 健保卡
        'B03' => 'A03', // 負責人配偶身分證 + 健保卡
        'B04' => 'A04', // 保證人身分證 + 健保卡
        'A03' => 'A05', // 營業據點建物登記謄本(公司或負責人或保證人自有才須提供)
        'A04' => 'A06', // 近三年公司所得稅申報書
        'A05' => 'A07', // 近12月勞保局投保資料
        'B08' => 'A08', // 公司聯徵資料
        'B09' => 'A09', // 負責人聯徵資料
        'B10' => 'A10', // 負責人配偶聯徵資料
        'B11' => 'A11', // 保證人聯徵資料
        'A07' => 'A12', // 負責人及保證人之被保險人勞保異動查詢
        'B13' => 'A13', // 公司近六個月往來存摺影本+內頁
        'B14' => 'A14', // 負責人近六個月往來存摺影本+內頁
        'B15' => 'A15', // 保證人近六個月往來存摺影本+內頁
        'B16' => 'A16', // 近三年 401/403/405
    ];

    /**
     * 轉換資料格式
     * @param array $data
     * @return array
     */
    public function convert_text(array $data): array
    {
        return $data;
    }

    /**
     * 轉換圖片列表
     * @param array $data
     * @param bool $get_api_attach_no
     * @return array
     */
    public function convert_attach(array $data, bool $get_api_attach_no = FALSE): array
    {
        return $data;
    }

    /**
     * 檢查必填欄位
     * @param array $data
     * @return array
     */
    public function check_required_column(array $data): array
    {
        return ['success' => TRUE];
    }

    /**
     * 檢查日期欄位的格式
     * @param array $data
     * @return array
     */
    public function check_date_format(array $data): array
    {
        return ['success' => TRUE];
    }
}