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
    public const ALLOW_PRODUCT_LIST = [PRODUCT_ID_STUDENT, PRODUCT_ID_STUDENT_ORDER,
        PRODUCT_ID_SALARY_MAN, PRODUCT_ID_SALARY_MAN_ORDER];

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

        if(!isset($target) || !in_array($target->product_id, $this::ALLOW_PRODUCT_LIST))
            throw new \InvalidArgumentException("Only allow product ID ".json_encode($this::ALLOW_PRODUCT_LIST).
                ". The productID($target->product_id) of target($target->id) is invalid.");

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
                ['target_id' => $this->target->id ?? 0, 'status' => 0,
                    'review_level' => $this->finalReviewerLevel,
                    'relation' => '{}', 'certification_list' => '[]',
                    'interest_type' => self::INTEREST_TYPE_EQUAL_TOTAL_PAYMENT,
                    'drawdown_type' => self::DRAWDOWN_TYPE_PARTIAL,
                    'note' => '',
                    ],
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
            $responseCode = self::RESPONSE_CODE_TRANSACTION_ROLLBACK;

        if($responseCode == self::RESPONSE_CODE_OK) {
            $this->CI->credit_sheet_review_model->trans_commit();
            if($groupId == $this->finalReviewerLevel)
                $this->finallyApprove();
        }else {
            $this->CI->credit_sheet_review_model->trans_rollback();
        }

        return $responseCode;
    }

    /**
     * 所有核可層級通過後，會審核未核可的案件
     */
    protected function finallyApprove() {
        $this->CI->load->model('loan/credit_model');
        $this->CI->load->library('credit_lib');

        $this->CI->credit_model->trans_start();
        $credit = $this->CI->credit_lib->get_credit($this->user->id, $this->target->product_id, $this->target->sub_product_id, $this->target);

        // 取得已審核資訊
        $reviewedInfoList = $this->CI->credit_sheet_review_model->get_many_by(
            ['credit_sheet_id' => $this->creditSheetRecord->id]);

        // 上班族階段上架 或 非階段上架之其他產品
        if($this->target->sub_product_id != STAGE_CER_TARGET || $this->target->product_id == 3) {
            // 設定信評加分
            $bonusScore = array_sum(array_column($reviewedInfoList, 'score'));
            $this->CI->load->library('utility/admin/creditapprovalextra', [], 'approvalextra');
            $this->CI->approvalextra->setSkipInsertion(true);
            $this->CI->approvalextra->setExtraPoints($bonusScore);

            // 上班族階段上架
            $level = false;
            if($this->target->product_id == PRODUCT_ID_SALARY_MAN && $this->target->sub_product_id == STAGE_CER_TARGET){
                $this->CI->load->library('Certification_lib');
                $certification = $this->CI->certification_lib->get_certification_info($this->user->id, 8, 0);
                $certificationStatus = isset($certification) && $certification && $certification->status == 1;
                $level = $certificationStatus ? 3 : 4 ;
            }

            $estimatedCredit = $this->CI->credit_lib->approve_credit($this->user->id,
                $this->target->product_id, $this->target->sub_product_id,
                $this->CI->approvalextra, $level);
        }

        $separator = ', ';
        $opinions = implode($separator, array_column($reviewedInfoList, 'opinion'));
        $remark = (empty($this->target->remark) ? $opinions : $this->target->remark . $separator . $opinions);

        if (isset($estimatedCredit) && $estimatedCredit !== False && isset($credit) &&
                ($estimatedCredit["amount"] != $credit['amount']
                || $estimatedCredit["points"] != $credit['points']
                || $estimatedCredit["level"] != $credit['level'])
        ) {
            $this->CI->credit_model->update_by(
                ['user_id' => $this->user->id, 'status' => 1],
                ['status'=> 0]
            );
            $newCredit = $this->CI->credit_model->insert($estimatedCredit);
        }

        $this->CI->credit_model->trans_complete();

        // 學生階段上架
        if($this->target->product_id == PRODUCT_ID_STUDENT && $this->target->sub_product_id == STAGE_CER_TARGET) {
            $param['status'] = 1;
            $param['sub_status'] = TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET;
            $param['remark'] = $remark;
            $this->CI->target_model->update($this->target->id,$param);
        }
        else{
            $this->CI->target_lib->approve_target($this->target,$remark,true);
        }
    }

    /**
     * 封存授審表 (審核通過)
     * @param array $credit: 信用評級 Model
     * @return bool
     */
    public function archive(array $credit): bool
    {
        $this->CI->load->model('user/user_certification_model');

        if(isset($credit) && !empty($credit)) {
            $where = ['investor' => USER_BORROWER, 'status' => 1];
            $userCertList = $this->CI->user_certification_model->getCertificationsByTargetId([$this->target->id], $where);
            $userCertList = $userCertList[$this->user->id] ?? [];

            $lastRepaymentDate = '';
            $target = $this->CI->target_model->order_by('loan_date', 'desc')->get_by([
                'user_id' => $this->user->id,
                'product_id' => $this::ALLOW_PRODUCT_LIST
            ]);
            if(isset($target)) {
                $lastTransaction = $this->CI->transaction_model->order_by('limit_date', 'desc')->get_by([
                    'target_id' => $target->id,
                    'source' => SOURCE_AR_PRINCIPAL
                ]);
                if(isset($lastTransaction))
                    $lastRepaymentDate = $lastTransaction->limit_date;
            }

            $this->CI->credit_sheet_model->update_by(['id' => $this->creditSheetRecord->id],
            [
                'credit_id' => $credit['id'],
                'certification_list' => json_encode(array_column($userCertList, 'id')),
                'unused_credit_line' => $this->getUnusedCreditLine(),
                'total_line' => $this->target->amount,
                'line_expired_at' => $lastRepaymentDate,
                'review_level' => $this->finalReviewerLevel,
                'relation' => json_encode($this->basicInfo->getRelation()),
                'note' => '',
                'status' => 1,
            ]);
        }else
            return FALSE;

        return TRUE;
    }


}
