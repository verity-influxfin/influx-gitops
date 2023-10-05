<?php
namespace Adapter\sk_million;
use Adapter\Adapter_base;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 送件檢核表
 *
 * 銀行：新光
 * 產品：普匯微企e秒貸
 */
class Adapter_sk_bank extends Adapter_base
{
    public static $mapping_table = ['compName' => 'CompName', 'compSetDate' => 'CompSetDate', 'compCapital' => 'CompCapital', 'businessCycleCode' => 'CompIdustry', 'businessTypeCode' => 'BusinessType', 'compContactTel' =>'CompTelNo', 'compContactExt' => 'CompTelExt', 'prName' => 'PrName', 'realBizRegAddressOwner' => 'IsBizRegAddrSelfOwn', 'bizRegAddrOwner' => 'BizRegAddrOwner', 'isBizAddrEqToBizRegAddr' => 'IsBizAddrEqToBizRegAddr', 'realBizAddressOwner' => 'IsRealBizAddrSelfOwn', 'realBizAddrOwner' => 'RealBizAddrOwner', 'employeeNum' => 'EmployeeNum',
        'directorAName' => 'DirectorAName', 'directorAId' => 'DirectorAId', 'directorBName' => 'DirectorBName', 'directorBId' => 'DirectorBId', 'directorCName' => 'DirectorCName', 'directorCId' => 'DirectorCId', 'directorDName' => 'DirectorDName', 'directorDId' => 'DirectorDId', 'directorEName' => 'DirectorEName', 'directorEId' => 'DirectorEId', 'directorFName' => 'DirectorFName', 'directorFId' => 'DirectorFId', 'lastPaidInCapitalDate' => 'LastPaidInCapitalDate',
        'bizRegAddressCity' => 'BizRegAddrCityName', 'bizRegAddressArea' => 'BizRegAddrAreaName', 'bizRegAddressRoad' => 'BizRegAddrRoadName', 'bizRegAddressRoadType' => 'BizRegAddrRoadType', 'bizRegAddressSec' => 'BizRegAddrSec', 'bizRegAddressLn' => 'BizRegAddrLn', 'bizRegAddressAly' => 'BizRegAddrAly', 'bizRegAddressNo' => 'BizRegAddrNo', 'bizRegAddressNoExt' => 'BizRegAddrNoExt', 'bizRegAddressFloor' => 'BizRegAddrFloor', 'bizRegAddressFloorExt' => 'BizRegAddrFloorExt', 'bizRegAddressRoom' => 'BizRegAddrRoom',
        'realBizAddressCity' => 'RealBizAddrCityName', 'realBizAddressArea' => 'RealBizAddrAreaName', 'realBizAddressRoad' => 'RealBizAddrRoadName', 'realBizAddressRoadType' => 'RealBizAddrRoadType', 'realBizAddressSec' => 'RealBizAddrSec', 'realBizAddressLn' => 'RealBizAddrLn', 'realBizAddressAly' => 'RealBizAddrAly', 'realBizAddressNo' => 'RealBizAddrNo', 'realBizAddressNoExt' => 'RealBizAddrNoExt', 'realBizAddressFloor' => 'RealBizAddrFloor', 'realBizAddressFloorExt' => 'RealBizAddrFloorExt', 'realBizAddressRoom' => 'RealBizAddrRoom', 'stockholderNum' => 'ShareholderNum'];

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

    public static $mapping_option_table = ['BizRegAddrOwner' => ['A' => 'B', 'B' => 'C', 'C' => 'A'], 'RealBizAddrOwner' => ['A' => 'B', 'B' => 'C', 'C' => 'A'], 'BusinessType' => ['1' => 'A', '2' => 'B', '3' => 'C']];
    public static $required_column_table = ['CompName', 'CompSetDate', 'CompCapital', 'CompIdustry', 'CompType', 'CompDuType', 'CompRegAddrZip', 'CompRegAddrZipName', 'CompRegAddress', 'CompMajorAddrZip', 'CompMajorAddrZipName', 'CompMajorAddress', 'CompTelAreaCode', 'CompTelNo', 'PrName', 'PrBirth'];
    public static $date_format_table = ['CompSetDate' => 'YYYYMMDD', 'PrBirth' => 'YYYYMMDD', 'SpouseBirth' => 'YYYYMMDD', 'GuOneBirth' => 'YYYYMMDD', 'GuTwoBirth' => 'YYYYMMDD', 'PrOnboardDay' => 'YYYYMMDD', 'ExPrOnboardDay' => 'YYYYMMDD', 'ExPrOnboardDay2' => 'YYYYMMDD', 'AnnualIncomeYear1' => 'YYYYMMDD', 'AnnualIncomeYear2' => 'YYYYMMDD', 'AnnualIncomeYear3' => 'YYYYMMDD',
        'NumOfInsuredYM1' => 'YYYYMM', 'NumOfInsuredYM2' => 'YYYYMM', 'NumOfInsuredYM3' => 'YYYYMM', 'NumOfInsuredYM4' => 'YYYYMM', 'NumOfInsuredYM5' => 'YYYYMM', 'NumOfInsuredYM6' => 'YYYYMM', 'NumOfInsuredYM7' => 'YYYYMM', 'NumOfInsuredYM8' => 'YYYYMM', 'NumOfInsuredYM9' => 'YYYYMM', 'NumOfInsuredYM10' => 'YYYYMM', 'NumOfInsuredYM11' => 'YYYYMM', 'NumOfInsuredYM12' => 'YYYYMM',
        'CompJCICQueryDate' => 'YYYYMMDD', 'CompJCICDataDate' => 'YYYYMM', 'MidTermLnYM' => 'YYYYMM', 'ShortTermLnYM' => 'YYYYMM', 'PrLaborQryDate' => 'YYYYMMDD', 'SpouseLaborQryDate' => 'YYYYMMDD', 'GuOneLaborQryDate' => 'YYYYMMDD', 'GuTwoLaborQryDate' => 'YYYYMMDD', 'PrJCICQueryDate' => 'YYYYMMDD', 'PrJCICDataDate' => 'YYYYMM', 'SpouseJCICQueryDate' => 'YYYYMMDD', 'SpouseJCICDataDate' => 'YYYYMM',
        'GuOneJCICQueryDate' => 'YYYYMMDD', 'GuOneJCICDataDate' => 'YYYYMM', 'GuTwoJCICQueryDate' => 'YYYYMMDD', 'GuTwoJCICDataDate' => 'YYYYMM', 'LastPaidInCapitalDate' => 'YYYYMMDD', 'PrStartYear' => 'YYYY', 'OthRealPrBirth' => 'YYYYMMDD', 'OthRealPrStartYear' => 'YYYY'];

    /**
     * 轉換資料格式
     * @param array $data
     * @return array
     */
    public function convert_text(array $data) : array
    {
        $result = [
            'MsgNo' => $data['MsgNo'],
            'CompId' => $data['CompId'],
            'PrincipalId' => $data['PrincipalId'],
            'Data' => $data['Data'] ?? [],
        ];
        foreach (self::$mapping_table as $origin_field => $api_field)
        {
            $result['Data'][$api_field] = $data['Data'][$origin_field] ?? '';
            unset($result['Data'][$origin_field]);
        }
        foreach (self::$mapping_option_table as $api_field => $mapping_option)
        { // $mapping_option = ['origin_value' => 'api_value']
            $result['Data'][$api_field] = $mapping_option[$result['Data'][$api_field]] ?? '';
        }
        return $result;
    }

    /**
     * 轉換圖片列表
     * @param array $data
     * @param bool $get_api_attach_no
     * @return array
     */
    public function convert_attach(array $data, bool $get_api_attach_no = FALSE) : array
    {
        return array_intersect_key($data, self::$required_attach_table);
    }

    /**
     * 檢查必填欄位
     * @param array $data
     * @return array
     */
    public function check_required_column(array $data): array
    {
        $empty_column = [];

        if (empty($data['CompId']))
        {
            $empty_column[] = 'CompId';
        }
        if (empty($data['PrincipalId']))
        {
            $empty_column[] = 'PrincipalId';
        }

        foreach (self::$required_column_table as $api_field)
        {
            if ( ! empty($data['Data'][$api_field]) || is_numeric($data['Data'][$api_field]))
            {
                continue;
            }
            $empty_column[] = $api_field;
        }
        if ( ! empty($empty_column))
        {
            return ['success' => FALSE, 'error' => '尚有必填欄位未填寫: ' . implode(',', $empty_column)];
        }
        return ['success' => TRUE];
    }

    /**
     * 檢查日期欄位的格式
     * @param array $data
     * @return array
     */
    public function check_date_format(array $data): array
    {
        $wrong_format_column = [];

        foreach (self::$date_format_table as $api_field => $api_date_format)
        {
            if (empty($data['Data'][$api_field]))
            {
                continue;
            }
            $format = preg_replace(['/YYYY/', '/MM/', '/DD/'], ['Y', 'm', 'd'], $api_date_format);
            $date_obj = \DateTime::createFromFormat($format, $data['Data'][$api_field]);

            if (($date_obj && $date_obj->format($format) == $data['Data'][$api_field]) !== TRUE)
            {
                $wrong_format_column[] = $api_field;
            }
        }
        if ( ! empty($wrong_format_column))
        {
            return ['success' => FALSE, 'error' => '日期欄位格式錯誤: ' . implode(',', $wrong_format_column)];
        }
        return ['success' => TRUE];
    }
}