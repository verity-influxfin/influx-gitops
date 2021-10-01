<?php
namespace CreditSheet;

Trait CreditSheetTrait
{
    public $creditSheet;

    public function setCreditSheetCallback($creditSheet) {}

    public function setCreditSheet($creditSheet) {
        $this->creditSheet = $creditSheet;
        $this->setCreditSheetCallback($creditSheet);
    }


    /**
     * 取得核准紀錄列表
     * @param $endDate
     * @return array
     */
    protected function _getApprovedCreditList($endDate): array {
        $this->CI->load->model('loan/credit_sheet_model');

        return $this->CI->credit_sheet_model->getCreditSheetWithTarget(
            [
                'user_id' => $this->creditSheet->user->id,
            ],
            [
                'created_at <=' => $endDate
            ]
        );
    }

    /**
     * 取得已放款之核准紀錄列表
     * @param $endDate
     * @return array
     */
    protected function getLoanedCreditList($endDate): array {
        $this->CI->load->model('loan/credit_sheet_model');

        return $this->CI->credit_sheet_model->getCreditSheetWithTarget(
            [
                'user_id' => $this->creditSheet->user->id,
                'status' => [TARGET_REPAYMENTING,TARGET_REPAYMENTED],
                'loan_date <= ' => $endDate,
            ],
            [
                'created_at <=' => $endDate
            ]
        );
    }

    protected function _getTodayTargets($startDate, $endDate) {
        $this->CI->load->model('loan/credit_sheet_model');
        return $this->CI->credit_sheet_model->getCreditSheetWithTarget(
            [
                'user_id' => $this->creditSheet->user->id,
            ],
            [
                'created_at >=' => $startDate,
                'created_at <=' => $endDate
            ]
        );
    }

    /**
     * 將期數轉為會計科目的種類名稱
     * @param $instalment
     * @return string
     */
    protected function getBookkeepingType($instalment): string
    {
        if($instalment <= 12)
            return "短放";
        else if($instalment <= 24)
            return "長放";
    }
}