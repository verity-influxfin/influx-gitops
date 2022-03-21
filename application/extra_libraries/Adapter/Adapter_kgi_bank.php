<?php
namespace Adapter;
defined('BASEPATH') OR exit('No direct script access allowed');

use Adapter\Adapter_base;

class Adapter_kgi_bank extends Adapter_base
{
    public static $mapping_table = [
        'CompName' => 'compName', 'CompType2' => 'organizationType', 'CompSetDate' => 'compSetDate',
        'RegisterType' => 'RegisterType', 'IsPublic' => 'isPublic', 'CompCapital' => 'compCapital', 'LastPaidInCapitalDate' => 'lastPaidInCapitalDate',
        'BizRegAddress' => 'bizRegAddress', 'HasJuridicalInvest' => 'hasJuridicalInvest', 'JuridicalInvestRate' => 'juridicalInvestRate',
        'BizTaxFileWay' => 'bizTaxFileWay', 'BusinessType2' => 'businessType', 'IsManufacturing' => 'isManufacturing',
        'LastYearRevenue' => 'lastYearRevenue', 'DailyWorkingCapital' => 'dailyWorkingCapital', 'OperatingCycle' => 'operatingCycle',
        'LiabilitiesAmount' => 'liabilitiesAmount', 'EquityAmount' => 'equityAmount', 'CompDuType' => 'compDuType', 'IsCovidAffected' => 'isCovidAffected',
        'IsBizRegAddrSelfOw' => 'realBizRegAddressOwner', 'BizRegAddrOwner' => 'bizRegAddrOwner', 'IsBizAddrEqToBizRegAddr' => 'isBizAddrEqToBizRegAddr',
        'RealBizAddress' => 'realBizAddress', 'IsRealBizAddrSelfOwn' => 'realBizAddressOwner', 'RealBizAddrOwner' => 'realBizAddrOwner',
        'CompContactName' => 'compContactName', 'CompContact' => 'compContact', 'CompTelExt' => 'compContactExt', 'FinancialOfficerName' => 'financialOfficerName',
        'FinancialOfficerExt' => 'financialOfficerExt', 'CompEmail' => 'compEmail', 'EmployeeNum' => 'employeeNum', 'HasForeignInvestment' => 'hasForeignInvestment',
        'HasRelatedCompany' => 'hasRelatedCompany',
        'RelatedCompAGuiNumber' => 'relatedCompAGuiNumber', 'RelatedCompAName' => 'relatedCompAName', 'RelatedCompAType' => 'relatedCompAType', 'RelatedCompARelationship' => 'relatedCompARelationship',
        'RelatedCompBGuiNumber' => 'relatedCompBGuiNumber', 'RelatedCompBName' => 'relatedCompBName', 'RelatedCompBType' => 'relatedCompBType', 'RelatedCompBRelationship' => 'relatedCompBRelationship',
        'RelatedCompCGuiNumber' => 'relatedCompCGuiNumber', 'RelatedCompCName' => 'relatedCompCName', 'RelatedCompCType' => 'relatedCompCType', 'RelatedCompCRelationship' => 'relatedCompCRelationship',
        'BusinessTaxLastOneYear' => 'businessTaxLastOneYear', 'BusinessTaxLastTwoYear' => 'businessTaxLastTwoYear', 'BusinessTaxLastThreeYear' => 'businessTaxLastThreeYear',
        'LastOneYearInvoiceAmountM1M2' => 'lastOneYearInvoiceAmountM1M2', 'LastOneYearInvoiceAmountM3M4' => 'lastOneYearInvoiceAmountM3M4', 'LastOneYearInvoiceAmountM5M6' => 'lastOneYearInvoiceAmountM5M6',
        'LastOneYearInvoiceAmountM7M8' => 'lastOneYearInvoiceAmountM7M8', 'LastOneYearInvoiceAmountM9M10' => 'lastOneYearInvoiceAmountM9M10', 'LastOneYearInvoiceAmountM11M12' => 'lastOneYearInvoiceAmountM11M12',
        'LastTwoYearInvoiceAmountM1M2' => 'lastTwoYearInvoiceAmountM1M2', 'LastTwoYearInvoiceAmountM3M4' => 'lastTwoYearInvoiceAmountM3M4', 'LastTwoYearInvoiceAmountM5M6' => 'lastTwoYearInvoiceAmountM5M6',
        'LastTwoYearInvoiceAmountM7M8' => 'lastTwoYearInvoiceAmountM7M8', 'LastTwoYearInvoiceAmountM9M10' => 'lastTwoYearInvoiceAmountM9M10', 'LastTwoYearInvoiceAmountM11M12' => 'lastTwoYearInvoiceAmountM11M12',
        'LastThreeYearInvoiceAmountM1M2' => 'lastThreeYearInvoiceAmountM1M2', 'LastThreeYearInvoiceAmountM3M4' => 'lastThreeYearInvoiceAmountM3M4', 'LastThreeYearInvoiceAmountM5M6' => 'lastThreeYearInvoiceAmountM5M6',
        'LastThreeYearInvoiceAmountM7M8' => 'lastThreeYearInvoiceAmountM7M8', 'LastThreeYearInvoiceAmountM9M10' => 'lastThreeYearInvoiceAmountM9M10', 'LastThreeYearInvoiceAmountM11M12' => 'lastThreeYearInvoiceAmountM11M12',
        'PrName' => 'prName', 'IsPrMarried' => 'isPrMarried', 'PrBirth' => 'prBirthday', 'PrInChargeYear' => 'prInChargeYear', 'PrStartYear' => 'prStartYear',
        'PrEduLevel' => 'prEduLevel', 'PrMobileNo' => 'prMobileNo', 'PrEmail' => 'prEmail', 'SpouseName' => 'spouseName', 'SpouseId' => 'spouseId', 'IsPrRegister' => 'isPrRegister',
        'OthRealPrRelWithPr' => 'prRelationship', 'OthRealPrName' => 'othRealPrName', 'OthRealPrId' => 'othRealPrId', 'OthRealPrBirth' => 'othRealPrBirth', 'OthRealPrStartYear' => 'othRealPrStartYear',
        'OthRealPrTitle' => 'othRealPrTitle', 'OthRealPrSHRatio' => 'othRealPrSHRatio', 'HasGuarantor' => 'hasGuarantor', 'IsPrSpouseGu' => 'isPrSpouseGu', 'GuOneName' => 'guarantorName',
        'GuOneId' => 'guarantorId', 'GuOneRelWithPr' => 'guOneRelWithPr', 'GuOneCompany' => 'guCompany', 'JcCompDebtLog' => 'jcCompDebtLog', 'CompJCICQueryDate' => 'jcCompDataDate',
        'CompJCICDataDate' => 'jcCompJ02YM', 'CompCreditScore' => 'jcCompCreditScore', 'JcCompCreditAmount' => 'jcCompCreditAmount', 'JcCompBankDealingNum' => 'jcCompBankDealingNum',
        'PrDebtLog' => 'prDebtLog', 'PrJ02YM' => 'prJ02YM', 'PrCreditScore' => 'prCreditPoint', 'PrCreditTotalAmount' => 'prCreditTotalAmount', 'PrCashCardQty' => 'prCashCardQty',
        'PrCashCardAvailLimit' => 'prCashCardAvailLimit', 'PrBal_CashCard' => 'prCashCardTotalBalance', 'PrHasWeekMonthDelay' => 'prHasWeekMonthDelay', 'PrCreditCardQty' => 'prCreditCardQty',
        'PrCreditCardAvailAmount' => 'prCreditCardAvailAmount', 'PrBal_CreditCard' => 'prCreditCardTotalBalance', 'PrHasLastThreeMonthDelay' => 'prHasLastThreeMonthDelay',
        'PrBeingOthCompPrId' => 'prBeingOthCompPrId', 'SpouseDebtLog' => 'spouseDebtLog', 'SpouseJ02YM' => 'SpouseJ02YM', 'SpouseCreditScore' => 'spouseCreditPoint',
        'SpouseBeingOthCompPrId' => 'spouseBeingOthCompPrId', 'GuarantorDebtLog' => 'guarantorDebtLog', 'GuarantorJ02YM' => 'guarantorJ02YM', 'GuOneCreditScore' => 'guarantorCreditPoint',
        'NumOfInsuredYM1' => 'numOfInsuredYM1', 'NumOfInsured1' => 'numOfInsured1', 'NumOfInsuredYM2' => 'numOfInsuredYM2', 'NumOfInsured2' => 'numOfInsured2',
        'NumOfInsuredYM3' => 'numOfInsuredYM3', 'NumOfInsured3' => 'numOfInsured3', 'NumOfInsuredYM4' => 'numOfInsuredYM4', 'NumOfInsured4' => 'numOfInsured4',
        'NumOfInsuredYM5' => 'numOfInsuredYM5', 'NumOfInsured5' => 'numOfInsured5', 'NumOfInsuredYM6' => 'numOfInsuredYM6', 'NumOfInsured6' => 'numOfInsured6',
        'NumOfInsuredYM7' => 'numOfInsuredYM7', 'NumOfInsured7' => 'numOfInsured7', 'NumOfInsuredYM8' => 'numOfInsuredYM8', 'NumOfInsured8' => 'numOfInsured8',
        'NumOfInsuredYM9' => 'numOfInsuredYM9', 'NumOfInsured9' => 'numOfInsured9', 'NumOfInsuredYM10' => 'numOfInsuredYM10', 'NumOfInsured10' => 'numOfInsured10',
        'NumOfInsuredYM11' => 'numOfInsuredYM11', 'NumOfInsured11' => 'numOfInsured11', 'NumOfInsuredYM12' => 'numOfInsuredYM12', 'NumOfInsured12' => 'numOfInsured12',
        'PrLaborQryDate' => 'prLaborQryDate', 'PrLaborInsSalary' => 'prLaborInsSalary', 'SpouseLaborQryDate' => 'spouseLaborQryDate', 'SpouseLaborInsSalary' => 'spouseLaborInsSalary',
        'GuOneLaborQryDate' => 'guLaborQryDate', 'GuOneLaborInsSalary' => 'guLaborInsSalary'
    ];
    public static $roc_date_table = [
        'compSetDate', 'lastPaidInCapitalDate', 'businessTaxLastOneYear', 'businessTaxLastTwoYear', 'businessTaxLastThreeYear', 'prBirthday', 'prInChargeYear',
        'prStartYear', 'othRealPrBirth', 'othRealPrStartYear', 'jcCompDataDate', 'jcCompJ02YM', 'prJ02YM', 'spouseJ02YM', 'guarantorJ02YM', 'numOfInsuredYM1',
        'numOfInsuredYM2', 'numOfInsuredYM3', 'numOfInsuredYM4', 'numOfInsuredYM5', 'numOfInsuredYM6', 'numOfInsuredYM7', 'numOfInsuredYM8', 'numOfInsuredYM9',
        'numOfInsuredYM10', 'numOfInsuredYM11', 'numOfInsuredYM12', 'prLaborQryDate', 'spouseLaborQryDate', 'guLaborQryDate'
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

}