<?php
namespace Adapter;
defined('BASEPATH') OR exit('No direct script access allowed');

use Adapter\Adapter_base;

class Adapter_sk_bank extends Adapter_base
{
    public static $mapping_table = [];
    public static $roc_date_table = [];
    public static $required_attach_table = [
        'A01' => 'A01', // 公司變更事項登記卡及工商登記查詢
        'A02' => 'A02', // 負責人及保證人身分證影本及第二證件、戶役政查詢
        'A03' => 'A03', // 營業據點建物登記謄本(公司或負責人或保證人自有才須提供)
        'A04' => 'A04', // 近三年公司所得稅申報書
        'A05' => 'A05', // 近12月勞保局投保資料
        'A06' => 'A06', // 公司、負責人、配偶及保證人的聯徵資料 J01、J02、J10、J20、A13、A11
        'A07' => 'A07', // 負責人及保證人之被保險人勞保異動查詢
        'A08' => 'A08', // 公司、負責人及保證人近六個月存摺餘額明細及存摺封面
    ];

    /**
     * 轉換資料格式
     * @param array $data
     * @return array
     */
    public function convert_text(array $data) : array
    {
        // TODO: 暫時不處理
        return $data;
    }

    /**
     * 轉換圖片列表
     * @param array $data
     * @return array
     */
    public function convert_attach(array $data, bool $get_api_attach_no = FALSE) : array
    {
        return array_intersect_key($data, self::$required_attach_table);
    }

}