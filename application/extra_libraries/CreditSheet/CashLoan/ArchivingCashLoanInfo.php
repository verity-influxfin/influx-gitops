<?php
namespace CreditSheet\CashLoan;
use CreditSheet\CashLoan\CashLoanBase;
use CreditSheet\CreditSheetTrait;

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivingCashLoanInfo extends CashLoanInfo
{
    use CreditSheetTrait;

    protected $CI;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 設定授審表時呼叫的 callback function
     * @param $creditSheet
     */
    public function setCreditSheetCallback($creditSheet) {
        if(isset($creditSheet->creditSheetRecord->created_at))
            $this->endDate = $creditSheet->creditSheetRecord->created_at;
    }

    /**
     * 取得本次核准日期
     * @return string
     */
    protected function getApprovedDate(): string
    {
        return $this->creditSheet->viewConverter->dateFormatToChinese($this->creditSheet->creditSheetRecord->created_at);
    }

    /**
     * 取得最近一年時間
     * @return string
     */
    protected function getLastYearDate(): string
    {
        $lastYear = date('Y年m月', strtotime("-12 months",
            strtotime($this->creditSheet->creditSheetRecord->created_at)));
        return $lastYear === FALSE ? '' : $lastYear;
    }

    /**
     * 取得核准紀錄列表
     * @return array
     */
    protected function getApprovedCreditList(): array
    {
        return $this->_getApprovedCreditList($this->creditSheet->creditSheetRecord->created_at);
    }

    /**
     * 取得其他說明
     * @return string
     */
    protected function getNote(): string
    {
        return $this->creditSheet->creditSheetRecord->note ?? '';
    }

    /**
     * 取得最近一年總額度
     * @return string
     */
    protected function getLatestLine(): string
    {
        return $this->creditSheet->creditRecord->amount ??  '';
    }

}
