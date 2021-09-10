<?php
namespace CreditSheet;
use CreditSheet\BasicInfo\PersonalBasicInfo;
use CreditSheet\CashLoan\CashLoanInfo;
use CreditSheet\CreditLine\CreditLineInfo;
use Utility\ViewCoverter;

defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalCreditSheet extends CreditSheetBase {
    public $basicInfo;
    public $creditLineInfo;
    public $cashLoanInfo;

    // 授審表類型
    protected $type = self::TYPE_PERSONAL;

    // 視圖讀取路徑
    protected $viewPath = 'admin/target/credit_management/person_report';

    // 允許承作的產品類別列表
    public const ALLOW_PRODUCT_LIST = [1, 3];

    // 最終核准層次
    protected $finalReviewerLevel = self::REVIEWER_CREDIT_ANALYST;

    /**
     * PersonalCreditSheet constructor.
     * @param $target
     * @param $user
     * @param PersonalBasicInfo $personalBasicInfo
     * @param CreditLineInfo $creditLineInfo
     * @param CashLoanInfo $cashLoanInfo
     */
    function __construct($target, $user, PersonalBasicInfo $personalBasicInfo, CreditLineInfo $creditLineInfo, CashLoanInfo $cashLoanInfo)
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loan/credit_sheet_model');
        $this->CI->load->model('admin/admin_model');

        $this->viewConverter = new ViewCoverter();

        $this->target = $target;
        $this->user = $user;

        $this->creditSheetRecord = $this->CI->credit_sheet_model->get_by(['target_id' => $this->target->id ?? 0]);
        if(isset($this->creditSheetRecord)) {
            $this->finalReviewerLevel = $this->creditSheetRecord->review_level;
            if ($this->creditSheetRecord->status == 1)
                $this->creditRecord = $this->CI->credit_model->get_by(['id' => $this->creditSheetRecord->credit_id]);
        } else {
            $this->CI->credit_sheet_model->insertWhenEmpty(
                ['target_id' => $this->target->id ?? 0, 'status' => 0],
                ['target_id' => $this->target->id ?? 0]
            );
            $this->creditSheetRecord = $this->CI->credit_sheet_model->get_by(['target_id' => $this->target->id ?? 0]);
        }

        $this->basicInfo = $personalBasicInfo;
        $this->basicInfo->setCreditSheet($this);

        $this->creditLineInfo = $creditLineInfo;
        $this->creditLineInfo->setCreditSheet($this);

        $this->cashLoanInfo = $cashLoanInfo;
        $this->cashLoanInfo->setCreditSheet($this);
    }

    /**
     * 取得視圖讀取路徑
     * @return string
     */
    public function getViewPath() : string {
        return $this->viewPath;
    }

    /**
     * 取得結構資料
     * @return array
     */
    public function getStructuralData(): array
    {
        $response = [];
        $response['basicInfo']['reviewedLevelList'] = $this->basicInfo->getReviewLevelList();
        $response['basicInfo']['creditCategoryList'] = $this->basicInfo->getCreditCategoryList();
        $response['basicInfo']['productCategoryList'] = $this->basicInfo->getProductCategoryList();

        $response['creditLineInfo']['drawdownTypeList'] = $this->creditLineInfo->getDrawdownTypeList();
        $response['creditLineInfo']['interestTypeList'] = $this->creditLineInfo->getInterestTypeList();
        $response['creditLineInfo']['applyLineTypeList'] = $this->creditLineInfo->getApplyLineTypeList();
        $response['creditLineInfo']['reviewerList'] = $this->creditLineInfo->getReviewerList();

        return $response;
    }

    /**
     * 取得資料
     * @return array
     */
    public function getData(): array
    {
        $response = [];
        $response['basicInfo'] = $this->basicInfo->getBasicInfo();
        $response['creditLineInfo'] = $this->creditLineInfo->getCreditLineInfo();
        $response['cashLoanInfo'] = $this->cashLoanInfo->getCashLoanInfo();

        return $response;
    }

    /**
     * 授審表核准-設定意見及加分項目
     * @param int $groupId: 核可層級
     * @param string $opinion: 核可意見
     * @param int $score: 調整分數
     * @param int $adminId: 管理員編號
     * @return int
     */
    public function approve(int $groupId, string $opinion, int $score=0, int $adminId=0): int
    {
        $this->CI->load->model('loan/credit_sheet_review_model');
        $responseCode = self::RESPONSE_CODE_OK;

        $admin = null;
        if($adminId && (
            ( ($admin = $this->CI->admin_model->get_by(['id' => $adminId])) != null &&
                !$this->hasReviewPermission($groupId, $this->getUserGroup($admin)) ) || !isset($admin))) {
            return self::RESPONSE_CODE_NO_PERMISSION;
        }
        $name = $adminId === 0 ? self::REVIEWER_LIST[self::REVIEWER_CREDIT_SYSTEM] : $admin->name;

        $this->CI->credit_sheet_review_model->trans_begin();
        $reviewList = array_column($this->CI->credit_sheet_review_model->get_many_by(
            ['credit_sheet_id' => $this->creditSheetRecord->id]), 'admin_id', 'group');

        if(array_key_exists($groupId, $reviewList)) {
            $responseCode = self::RESPONSE_CODE_REPEATED_APPROVAL;
        }else if(!$this->canReview($groupId, array_keys($reviewList))) {
            $responseCode = self::RESPONSE_CODE_INVALID_ACTION;
        }else {
            $this->CI->credit_sheet_review_model->insert(
                ['credit_sheet_id' => $this->creditSheetRecord->id,
                    'admin_id' => $adminId, 'name' => $name, 'opinion' => $opinion,
                    'score' => $score, 'group' => $groupId]);
        }

        if($this->CI->credit_sheet_review_model->trans_status() == FALSE)
            $responseCode =  self::RESPONSE_CODE_TRANSACTION_ROLLBACK;

        if($responseCode == self::RESPONSE_CODE_OK) {
            $this->CI->credit_sheet_review_model->trans_commit();
            if($groupId == $this->finalReviewerLevel)
                $this->finallyApprove();
        }else {
            $this->CI->credit_sheet_review_model->trans_rollback();
        }

        return $responseCode;
    }

    public function finallyApprove() {
        $reviewList = $this->CI->credit_sheet_review_model->get_many_by(
            ['credit_sheet_id' => $this->creditSheetRecord->id]);
    }


}
