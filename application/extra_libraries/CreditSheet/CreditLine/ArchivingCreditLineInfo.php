<?php
namespace CreditSheet\CreditLine;

defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivingCreditLineInfo extends CreditLineInfo {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 取得核准額度（可動用額度）
     * @return string
     */
    public function getUnusedCreditLine(): string
    {
        return $this->creditSheet->creditSheetRecord->unused_credit_line ?? '';
    }

    /**
     * 取得額度合計
     * @return string
     */
    public function getTodayApplyLine() : string
    {
        return $this->creditSheet->creditSheetRecord->total_line ?? '';
    }

    /**
     * 取得額度到期日
     * @return string
     */
    public function getCreditLineExpiredDate(): string
    {
        return $this->creditSheet->creditSheetRecord->line_expired_at ?? '';
    }

    /**
     * 取得核准紀錄列表
     * @return array
     */
    protected function getApprovedCreditList(): array
    {
        $endDate = $this->creditSheet->creditSheetRecord->created_at ?? date('Y-m-d H:i:s');
        return $this->_getApprovedCreditList($endDate);
    }

    /**
     * 取得今日申貸案件
     * @return mixed
     */
    public function getTodayTargets() {
        $startDate = date('Y-m-d 0:0:0', strtotime($this->creditSheet->creditSheetRecord->created_at));
        $endDate = $this->creditSheet->creditSheetRecord->created_at;
        return $this->_getTodayTargets($startDate, $endDate);
    }

    /**
     * 取得已審查的資料列表
     * @return array
     */
    public function getReviewerList(): array
    {
        // TODO: 取得授審紀錄的相關意見
    }
}
