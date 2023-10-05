<?php
namespace Adapter\sk_million;
use Adapter\Adapter_base;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 送件檢核表
 *
 * 銀行：凱基
 * 產品：普匯微企e秒貸
 */
class Adapter_kgi_bank extends Adapter_base
{
    public static $mapping_table = [
        'compName' => 'compName', 'compType2' => 'organizationType', 'compSetDate' => 'compSetDate',
        'registerType' => 'registerType', 'isPublic' => 'isPublic', 'compCapital' => 'compCapital', 'lastPaidInCapitalDate' => 'lastPaidInCapitalDate',
        'bizRegAddress' => 'bizRegAddress', 'hasJuridicalInvest' => 'hasJuridicalInvest', 'juridicalInvestRate' => 'juridicalInvestRate',
        'bizTaxFileWay' => 'bizTaxFileWay', 'businessType2' => 'businessType', 'isManufacturing' => 'isManufacturing',
        'lastYearRevenue' => 'lastYearRevenue', 'dailyWorkingCapital' => 'dailyWorkingCapital', 'operatingCycle' => 'operatingCycle',
        'liabilitiesAmount' => 'liabilitiesAmount', 'equityAmount' => 'equityAmount', 'compDuType' => 'compDuType', 'isCovidAffected' => 'isCovidAffected', 'getRelief' => 'getRelief',
        'isBizRegAddrSelfOw' => 'realBizRegAddressOwner', 'bizRegAddrOwner' => 'bizRegAddrOwner', 'isBizAddrEqToBizRegAddr' => 'isBizAddrEqToBizRegAddr',
        'realBizAddress' => 'realBizAddress', 'isRealBizAddrSelfOwn' => 'realBizAddressOwner', 'realBizAddrOwner' => 'realBizAddrOwner',
        'compContactName' => 'compContactName', 'compContact' => 'compContact', 'compTelExt' => 'compContactExt', 'financialOfficerName' => 'financialOfficerName',
        'financialOfficerExt' => 'financialOfficerExt', 'compEmail' => 'compEmail', 'employeeNum' => 'employeeNum', 'hasForeignInvestment' => 'hasForeignInvestment',
        'hasRelatedCompany' => 'hasRelatedCompany',
        'relatedCompAGuiNumber' => 'relatedCompAGuiNumber', 'relatedCompAName' => 'relatedCompAName', 'relatedCompAType' => 'relatedCompAType', 'relatedCompARelationship' => 'relatedCompARelationship',
        'relatedCompBGuiNumber' => 'relatedCompBGuiNumber', 'relatedCompBName' => 'relatedCompBName', 'relatedCompBType' => 'relatedCompBType', 'relatedCompBRelationship' => 'relatedCompBRelationship',
        'relatedCompCGuiNumber' => 'relatedCompCGuiNumber', 'relatedCompCName' => 'relatedCompCName', 'relatedCompCType' => 'relatedCompCType', 'relatedCompCRelationship' => 'relatedCompCRelationship',
        'businessTaxLastOneYear' => 'businessTaxLastOneYear', 'businessTaxLastTwoYear' => 'businessTaxLastTwoYear', 'businessTaxLastThreeYear' => 'businessTaxLastThreeYear',
        'lastOneYearInvoiceAmountM1M2' => 'lastOneYearInvoiceAmountM1M2', 'lastOneYearInvoiceAmountM3M4' => 'lastOneYearInvoiceAmountM3M4', 'lastOneYearInvoiceAmountM5M6' => 'lastOneYearInvoiceAmountM5M6',
        'lastOneYearInvoiceAmountM7M8' => 'lastOneYearInvoiceAmountM7M8', 'lastOneYearInvoiceAmountM9M10' => 'lastOneYearInvoiceAmountM9M10', 'lastOneYearInvoiceAmountM11M12' => 'lastOneYearInvoiceAmountM11M12',
        'lastTwoYearInvoiceAmountM1M2' => 'lastTwoYearInvoiceAmountM1M2', 'lastTwoYearInvoiceAmountM3M4' => 'lastTwoYearInvoiceAmountM3M4', 'lastTwoYearInvoiceAmountM5M6' => 'lastTwoYearInvoiceAmountM5M6',
        'lastTwoYearInvoiceAmountM7M8' => 'lastTwoYearInvoiceAmountM7M8', 'lastTwoYearInvoiceAmountM9M10' => 'lastTwoYearInvoiceAmountM9M10', 'lastTwoYearInvoiceAmountM11M12' => 'lastTwoYearInvoiceAmountM11M12',
        'lastThreeYearInvoiceAmountM1M2' => 'lastThreeYearInvoiceAmountM1M2', 'lastThreeYearInvoiceAmountM3M4' => 'lastThreeYearInvoiceAmountM3M4', 'lastThreeYearInvoiceAmountM5M6' => 'lastThreeYearInvoiceAmountM5M6',
        'lastThreeYearInvoiceAmountM7M8' => 'lastThreeYearInvoiceAmountM7M8', 'lastThreeYearInvoiceAmountM9M10' => 'lastThreeYearInvoiceAmountM9M10', 'lastThreeYearInvoiceAmountM11M12' => 'lastThreeYearInvoiceAmountM11M12',
        'prName' => 'prName', 'isPrMarried' => 'isPrMarried', 'prBirth' => 'prBirthday', 'prInChargeYear' => 'prInChargeYear', 'prStartYear' => 'prStartYear',
        'prEduLevel' => 'prEduLevel', 'prMobileNo' => 'prMobileNo', 'prEmail' => 'prEmail', 'spouseName' => 'spouseName', 'spouseId' => 'spouseId', 'isPrRegister' => 'isPrRegister',
        'othRealPrRelWithPr' => 'prRelationship', 'othRealPrName' => 'othRealPrName', 'othRealPrId' => 'othRealPrId', 'othRealPrBirth' => 'othRealPrBirth', 'othRealPrStartYear' => 'othRealPrStartYear',
        'othRealPrTitle' => 'othRealPrTitle', 'othRealPrSHRatio' => 'othRealPrSHRatio', 'hasGuarantor' => 'hasGuarantor', 'isPrSpouseGu' => 'isPrSpouseGu', 'guOneName' => 'guarantorName',
        'guOneId' => 'guarantorId', 'guOneRelWithPr' => 'guOneRelWithPr', 'guOneCompany' => 'guOneCompany', 'jcCompDebtLog' => 'jcCompDebtLog', 'compJCICQueryDate' => 'jcCompDataDate',
        'compJCICDataDate' => 'jcCompJ02YM', 'compCreditScore' => 'jcCompCreditScore', 'jcCompCreditAmount' => 'jcCompCreditAmount', 'jcCompBankDealingNum' => 'jcCompBankDealingNum',
        'prDebtLog' => 'prDebtLog', 'prJ02YM' => 'prJ02YM', 'prCreditScore' => 'prCreditPoint', 'prCreditTotalAmount' => 'prCreditTotalAmount', 'prCashCardQty' => 'prCashCardQty',
        'prCashCardAvailLimit' => 'prCashCardAvailLimit', 'prBal_CashCard' => 'prCashCardTotalBalance', 'prHasWeekMonthDelay' => 'prHasWeekMonthDelay', 'prCreditCardQty' => 'prCreditCardQty',
        'prCreditCardAvailAmount' => 'prCreditCardAvailAmount', 'prBal_CreditCard' => 'prCreditCardTotalBalance', 'prHasLastThreeMonthDelay' => 'prHasLastThreeMonthDelay',
        'prBeingOthCompPrId' => 'prBeingOthCompPrId', 'spouseDebtLog' => 'spouseDebtLog', 'spouseJ02YM' => 'spouseJ02YM', 'spouseCreditScore' => 'spouseCreditPoint',
        'spouseBeingOthCompPrId' => 'spouseBeingOthCompPrId', 'guarantorDebtLog' => 'guarantorDebtLog', 'guarantorJ02YM' => 'guarantorJ02YM', 'guOneCreditScore' => 'guarantorCreditPoint',
        'numOfInsuredYM1' => 'numOfInsuredYM1', 'numOfInsured1' => 'numOfInsured1', 'numOfInsuredYM2' => 'numOfInsuredYM2', 'numOfInsured2' => 'numOfInsured2',
        'numOfInsuredYM3' => 'numOfInsuredYM3', 'numOfInsured3' => 'numOfInsured3', 'numOfInsuredYM4' => 'numOfInsuredYM4', 'numOfInsured4' => 'numOfInsured4',
        'numOfInsuredYM5' => 'numOfInsuredYM5', 'numOfInsured5' => 'numOfInsured5', 'numOfInsuredYM6' => 'numOfInsuredYM6', 'numOfInsured6' => 'numOfInsured6',
        'numOfInsuredYM7' => 'numOfInsuredYM7', 'numOfInsured7' => 'numOfInsured7', 'numOfInsuredYM8' => 'numOfInsuredYM8', 'numOfInsured8' => 'numOfInsured8',
        'numOfInsuredYM9' => 'numOfInsuredYM9', 'numOfInsured9' => 'numOfInsured9', 'numOfInsuredYM10' => 'numOfInsuredYM10', 'numOfInsured10' => 'numOfInsured10',
        'numOfInsuredYM11' => 'numOfInsuredYM11', 'numOfInsured11' => 'numOfInsured11', 'numOfInsuredYM12' => 'numOfInsuredYM12', 'numOfInsured12' => 'numOfInsured12',
        'prLaborQryDate' => 'prLaborQryDate', 'prLaborInsSalary' => 'prLaborInsSalary', 'spouseLaborQryDate' => 'spouseLaborQryDate', 'spouseLaborInsSalary' => 'spouseLaborInsSalary',
        'guOneLaborQryDate' => 'guLaborQryDate', 'guOneLaborInsSalary' => 'guLaborInsSalary'
    ];
    public static $roc_date_table = [
        'compSetDate', 'lastPaidInCapitalDate', 'businessTaxLastOneYear', 'businessTaxLastTwoYear', 'businessTaxLastThreeYear', 'prBirthday', 'prInChargeYear',
        'prStartYear', 'othRealPrBirth', 'othRealPrStartYear', 'jcCompDataDate', 'jcCompJ02YM', 'prJ02YM', 'spouseJ02YM', 'guarantorJ02YM', 'numOfInsuredYM1',
        'numOfInsuredYM2', 'numOfInsuredYM3', 'numOfInsuredYM4', 'numOfInsuredYM5', 'numOfInsuredYM6', 'numOfInsuredYM7', 'numOfInsuredYM8', 'numOfInsuredYM9',
        'numOfInsuredYM10', 'numOfInsuredYM11', 'numOfInsuredYM12', 'prLaborQryDate', 'spouseLaborQryDate', 'guLaborQryDate'
    ];

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
    public function convert_text(array $data) : array
    {
        $result = ['data' => []];
        $result['msgNo'] = $data['MsgNo'];
        $result['compId'] = $data['CompId'];
        $result['data']['compContactTel'] = ($data['Data']['CompTelAreaCode'] ?? '') . ($data['Data']['CompTelNo'] ?? '');
        $result['data']['prId'] = $data['PrincipalId'] ?? '';
        foreach (self::$mapping_table as $origin_field => $api_field)
        {
            $result['data'][$api_field] = $data['Data'][$origin_field] ?? '';
            if(preg_match('/^(?<year>\d{4})(?<other>\d*)/', $result['data'][$api_field], $regexResult)) {
                $result['data'][$api_field] = str_pad($regexResult['year'] - 1911, 3, '0', STR_PAD_LEFT) . $regexResult['other'];
            }
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
        $result = array_intersect_key($data, self::$required_attach_table);
        if($get_api_attach_no)
        {
            $result = array_combine(array_values(array_intersect_key(self::$required_attach_table, $result)), array_values($result));
        }
        return $result;
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