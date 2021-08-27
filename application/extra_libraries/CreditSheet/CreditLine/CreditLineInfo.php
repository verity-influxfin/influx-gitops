<?php
namespace CreditSheet\CreditLine;
use CreditSheet\CreditSheetTrait;

defined('BASEPATH') OR exit('No direct script access allowed');

class CreditLineInfo implements CreditLineBase {
    use CreditSheetTrait;
    use CreditLineTrait;

    protected $CI;

    /**
     * @var array
     */
    protected $approvedCreditList;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loan/credit_model');
        $this->CI->load->model('loan/credit_sheet_model');
        $this->CI->load->model('user/user_meta_model');
        $this->CI->load->library('Certification_lib');

    }

    /**
     * 取得核可額度區塊的資料
     * @return array
     */
    public function getCreditLineInfo(): array
    {
        $response = [];
        if(isset($this->creditSheet->target)) {
            $this->approvedCreditList = $this->getApprovedCreditList();

            $response['unusedCreditLine'] = $this->getUnusedCreditLine();
            $response['applyLineType'] = $this->getApplyLineType();
            $response['todayApplyLine'] = $this->getTodayApplyLine();
            $response['creditLineExpiredDate'] = $this->getCreditLineExpiredDate();

            $response['approvedCreditList'] = [];
            foreach ($this->approvedCreditList as $creditRecord) {
                $credit = [];
                $credit['bookkeeping'] = $this->getBookkeepingType($creditRecord->instalment);
                $credit['unusedCreditLine'] = $this->creditSheet->viewConverter->chineseThousandUnit($creditRecord->unused_credit_line);
                $credit['applyLine'] = $this->creditSheet->viewConverter->chineseThousandUnit($creditRecord->amount);
                $credit['instalment'] = $this->creditSheet->viewConverter->chineseMonthUnit($creditRecord->instalment);
                $credit['interestRate'] = $this->creditSheet->viewConverter->percentSymbol($creditRecord->interest_rate);
                $credit['interestType'] = $creditRecord->interest_type;
                $credit['drawdownType'] = $creditRecord->drawdown_type;
                $response['approvedCreditList'][] = $credit;
            }

            $response['totalUnusedCreditLine'] = $this->creditSheet->viewConverter->chineseThousandUnit($this->getUnusedCreditLine());
            $response['totalApplyLine'] = $this->creditSheet->viewConverter->chineseThousandUnit($this->getTodayApplyLine());

            $targets = $this->getTodayTargets();
            $reasonList = [];
            foreach ($targets as $target) {
                $description = json_decode($target->reason,true);
                $reason = $description['reason_description'] ?? $target->reason;
                $reasonList[] = $reason;
            }
            $response['reasonList'] = $reasonList;
            $response['paymentType'] = $this->getPaymentType();
            $response['otherCondition'] = $this->getOtherCondition();
            $response['unusedCreditLine2'] = $this->creditSheet->viewConverter->chineseThousandUnit($this->getUnusedCreditLine());
            $response['reviewedInfoList'] = $this->getReviewedInfoList();
        }
        return $response;
    }

    /**
     * 取得動撥方式定義列表
     * @return array
     */
    public function getDrawdownTypeList(): array
    {
        return self::DRAWDOWN_TYPE_LIST;
    }

    /**
     * 取得計息方式定義列表
     * @return array
     */
    public function getInterestTypeList(): array
    {
        return self::INTEREST_TYPE_LIST;
    }

    /**
     * 取得申貸額度及條件定義列表
     * @return array
     */
    public function getApplyLineTypeList(): array
    {
        return self::APPLY_LINE_TYPE_LIST;
    }

    /**
     * 取得可審查人員定義列表
     * @return array
     */
    public function getReviewerList(): array
    {
        return self::REVIEWER_LIST;
    }

    /**
     * 取得核准額度（可動用額度）
     * @return string
     */
    public function getUnusedCreditLine(): string
    {
        return '';
    }

    /**
     * 取得申貸額度及條件
     * @return string
     */
    public function getApplyLineType() : string
    {
        return self::APPLY_LINE_TYPE_SINGLE;
    }

    /**
     * 取得額度合計
     * @return string
     */
    public function getTodayApplyLine() : string
    {
        return '';
    }

    /**
     * 取得額度到期日
     * @return string
     */
    public function getCreditLineExpiredDate(): string
    {
        return '';
    }

    /**
     * 取得核准紀錄列表
     * @return array
     */
    protected function getApprovedCreditList(): array
    {
        $endDate = date('Y-m-d H:i:s');
        return $this->_getApprovedCreditList($endDate);
    }

    /**
     * 取得今日申貸案件
     * @return mixed
     */
    public function getTodayTargets() {
        $startDate = date('Y-m-d 0:0:0');
        $endDate = date('Y-m-d H:i:s');
        return $this->_getTodayTargets($startDate, $endDate);
    }

    /**
     * 取得還款方式
     * @return string
     */
    public function getPaymentType(): string
    {
        return self::INTEREST_TYPE_LIST[self::INTEREST_TYPE_EQUAL_TOTAL_PAYMENT];
    }

    /**
     * 取得其他條件說明
     * @return string
     */
    public function getOtherCondition(): string
    {
        return '需在額度核准後2個月內動用';
    }

    protected function _getApprovedCreditList($endDate): array {
        return $this->CI->credit_sheet_model->getCreditSheetWithTarget(
            [
                'user_id' => $this->creditSheet->user->id,
            ],
            [
                'created_at <=' => $endDate
            ]
        );

//        return $this->CI->target_model->getLastPaymentDate(
//            [
//                'user_id' => $this->creditSheet->basicInfo->user->id,
//                'status' => 5
//            ],
//            [
//                'tra.source' => SOURCE_AR_PRINCIPAL,
//                'tra.status' => 1,
//                't.instalment' => 'tra.instalment_no'
//            ]
//        );

    }

    public function _getTodayTargets($startDate, $endDate) {
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
     * 取得已審查的資料列表
     * @return array
     */
    public function getReviewedInfoList(): array
    {
        return array_fill_keys(array_keys(self::REVIEWER_LIST), [
            'name' => '',
            'opinion' => '',
            'score' => ''
        ]);
    }

}
