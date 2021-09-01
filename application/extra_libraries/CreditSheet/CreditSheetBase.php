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
     * 授審表核准-設定意見及加分項目
     * @param int $groupId: 核可層級
     * @param string $opinion: 核可意見
     * @param int $score: 調整分數
     * @param int $adminId: 管理員編號
     * @return int
     */
    abstract public function approve(int $groupId, string $opinion, int $score=0, int $adminId=0): int;

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
            })) === $targetGroup-self::REVIEWER_CREDIT_ANALYST)) {
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

}