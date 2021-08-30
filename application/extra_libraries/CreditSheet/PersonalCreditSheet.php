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

    public $target;
    public $user;
    protected $CI;

    // 授審表類型
    protected $type = self::TYPE_PERSONAL;

    // 視圖讀取路徑
    protected $viewPath = 'admin/target/credit_management/person_report';

    // 允許承作的產品類別列表
    public const ALLOW_PRODUCT_LIST = [1, 3];



    /**
     * PersonalCreditSheet constructor.
     * @param $target
     * @param $user
     * @param PersonalBasicInfo $personalBasicInfo
     * @param CreditLineInfo $creditLineInfo
     */
    function __construct($target, $user, PersonalBasicInfo $personalBasicInfo, CreditLineInfo $creditLineInfo, CashLoanInfo $cashLoanInfo)
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loan/credit_sheet_model');

        $this->viewConverter = new ViewCoverter();

        $this->target = $target;
        $this->user = $user;

        $this->creditSheetRecord = $this->CI->credit_sheet_model->get_by(['target_id' => $this->target->id ?? 0]);
        if(isset($this->creditSheetRecord))
            $this->creditRecord = $this->CI->credit_model->get_by(['id' => $this->creditSheetRecord->credit_id]);

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
        $response['basicInfo']['reviewLevelList'] = $this->basicInfo->getReviewLevelList();
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
}
