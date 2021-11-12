<?php


namespace CreditSheet;

abstract class CreditSheetBase implements CreditSheetDefinition
{
    public $creditSheetRecord;
    public $creditRecord;
    public $target;
    public $user;
    protected $CI;

    // 顯示欄位的轉換器
    public $viewConverter;

    // 授審表類型
    protected $type;

    // 視圖讀取路徑
    protected $viewPath = '';

    // 允許承作的產品類別列表
    public const ALLOW_PRODUCT_LIST = [];

    // 最終核准層次
    protected $finalReviewerLevel = self::REVIEWER_CREDIT_ANALYST;

    // 可評分範圍
    protected $scoringMin = -500;
    protected $scoringMax = 500;

    /**
     * 取得視圖的檔案路徑
     * @return string
     */
    abstract public function getViewPath() : string;

    /**
     * 取得結構資料
     * @return array
     */
    abstract public function getStructuralData() : array;

    /**
     * 取得資料
     * @return array
     */
    abstract public function getData() : array;

    /**
     * 判斷是否已擁有核可額度
     * @return bool
     */
    abstract public function hasCreditLine() : bool;

    /**
     * 授審表核准-設定意見及加分項目
     * @param int $groupId: 核可層級
     * @param string $opinion: 核可意見
     * @param int $score: 調整分數
     * @param int $adminId: 管理員編號
     * @return int
     */
    abstract public function approve(int $groupId, string $opinion, int $score=0, int $adminId=0): int;

    /**
     * 所有核可層級通過後，會審核未核可的案件
     */
    abstract protected function finallyApprove();

    /**
     * 封存授審表 (審核通過)
     * @param array $credit: 信用評級 Model
     * @return bool
     */
    abstract public function archive(array $credit): bool;

    /**
     * 設定最終核准層次
     * @param int $level
     * @return bool
     */
    public function setFinalReviewerLevel(int $level): bool {
        $this->finalReviewerLevel = $level;
        $this->CI->load->model('loan/credit_sheet_model');
        $this->CI->credit_sheet_model->update_by(['id' => $this->creditSheetRecord->id ?? 0],
            ['review_level' => $this->finalReviewerLevel]);
        return TRUE;
    }

    /**
     * 取得授審表類型
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 取得授審表類型的名稱
     * @return string
     */
    public function getTypeName(): string
    {
        return self::TYPE_LIST[$this->type];
    }

    /**
     * 查詢有無核可權限
     * @param $targetGroup: 目標核可層次等級
     * @param $userGroup: 後台人員的核可層次等級
     * @return bool
     */
    public function hasReviewPermission($targetGroup, $userGroup): bool
    {
        if($targetGroup == self::REVIEWER_CREDIT_SYSTEM)
            return FALSE;
        return $userGroup == $targetGroup;
    }

    /**
     * 確認 該核可層次 以前的核可是否都已完成 / 核可的範圍內
     * @param $targetGroup: 目標核可層次等級
     * @param array $reviewedGroupList: 已審核的 Group ID List
     * @return bool
     */
    public function canReview($targetGroup, array $reviewedGroupList): bool
    {
        if($targetGroup <= $this->finalReviewerLevel && ($targetGroup == self::REVIEWER_CREDIT_SYSTEM ||
            count(array_filter($reviewedGroupList, function($v) use ($targetGroup) {
                return $v < $targetGroup;
            })) === $targetGroup-self::REVIEWER_CREDIT_SYSTEM)) {
            return TRUE;
        }
        return FALSE;
    }
    /**
     * 取得後台人員的審核層級
     * @param $admin
     * @return int
     */
    public function getUserGroup($admin=null): int
    {
        if(isset($admin)) {
            // TODO: 目前無風控長跟總經理之權限設計，暫時以二審回傳
            return self::REVIEWER_CREDIT_ANALYST;
        }
        // 系統審查
        return self::REVIEWER_CREDIT_SYSTEM;
    }

    /**
     * 將回應代碼轉為說明文字
     * @param $code
     * @return string
     */
    public function convertResponseCodeToMsg($code): string
    {
        return self::RESPONSE_CODE_LIST[$code] ?? '';
    }

    /**
     * 取得有效的歸戶最大額度
     * @param $startTime: 篩選的時間標記
     * @return int
     */
    public function getMaxCreditLine($startTime): int
    {
        $this->CI->load->model('loan/credit_model');
        $param = [
            'user_id' => $this->user->id,
            'status' => 1,
            'expire_time >=' => $startTime,
            'product_id' => $this::ALLOW_PRODUCT_LIST,
        ];
        $credit = $this->CI->credit_model->order_by('amount', 'desc')->get_by($param);
        return isset($credit) ? $credit->amount : 0;
    }


    /**
     * 取得可動用額度
     * @param int $unusedCreditLine
     * @return int
     */
    public function getUnusedCreditLine(int $unusedCreditLine=0): int
    {
        if(!$unusedCreditLine)
            $unusedCreditLine = $this->getMaxCreditLine(time());

        // 取得所有產品申請或進行中的案件
        $targetList = $this->CI->target_model->get_many_by([
            'id !=' => $this->target->id,
            'user_id' => $this->user->id,
            'status NOT' => [TARGET_CANCEL, TARGET_FAIL, TARGET_REPAYMENTED],
            'product_id' => $this::ALLOW_PRODUCT_LIST,
        ]);

        if ($targetList) {
            // 無條件進位使用額度(千元) ex: 1001 -> 1100
            $usedCreditLine = array_sum(array_column($targetList, 'loan_amount'));
            $usedCreditLine = $usedCreditLine % 1000 != 0 ? ceil($usedCreditLine * 0.001) * 1000 : $usedCreditLine;

            // 取得案件已還款金額
            $paidTransactions = $this->CI->transaction_model->get_many_by(array(
                "source" => SOURCE_PRINCIPAL,
                "user_from" => $this->user->id,
                "target_id" => array_column($targetList, 'id'),
                "status" => TRANSACTION_STATUS_PAID_OFF
            ));
            $repaidAmount = array_sum(array_column($paidTransactions, 'amount'));

            $unusedCreditLine = $unusedCreditLine - $usedCreditLine + $repaidAmount;
        }

        return $unusedCreditLine;
    }

}