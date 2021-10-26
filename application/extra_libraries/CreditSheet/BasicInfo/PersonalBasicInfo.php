<?php
namespace CreditSheet\BasicInfo;
use CreditSheet\CreditSheetDefinition;
use CreditSheet\CreditSheetTrait;

defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalBasicInfo implements BasicInfoBase, CreditSheetDefinition {
    use CreditSheetTrait;

    protected $repayableTargets;
    protected $CI;
    protected $certificationProcessList = [
        CERTIFICATION_IDCARD => ['name', 'id_number', 'birthday', 'address'],
        CERTIFICATION_JOB => ['company'],
        CERTIFICATION_STUDENT => ['school'],
        CERTIFICATION_EMAIL => ['email'],
    ];

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loan/credit_model');
        $this->CI->load->model('user/user_meta_model');
        $this->CI->load->library('Certification_lib');
    }

    /**
     * 取得基本資料區塊的資料
     * @return array
     */
    public function getBasicInfo(): array
    {
        $response = [];
        if(isset($this->creditSheet->target)) {
            $response = array_merge($response, $this->getAllCertification());
            $response['birthday'] = birthdayDateFormat($response['birthday'] ?? '');

            $response['firstApplyDate'] = $this->getFirstApplyDate();
            $response['productCategory'] = $this->getProductCategories();
            $response['creditCategory'] = $this->getCreditCategory();
            $response['reviewedLevel'] = $this->getReviewedLevel();
            $response['reportDate'] = $this->creditSheet->viewConverter->dateFormatToChinese($this->getReportDate());
            $response['creditDate'] = $this->getCreditDate();
            $response['creditRank'] = $this->getCreditRank();
            $response['relation'] = $this->getRelation();

        }
        return $response;
    }

    /**
     * 取得授信類型定義列表
     * @return array
     */
    public function getCreditCategoryList(): array
    {
        return self::CREDIT_CATEGORY_LIST;
    }

    /**
     * 取得核貸層次定義列表
     * @return array
     */
    public function getReviewLevelList(): array
    {
        return self::CREDIT_REVIEW_LEVEL_LIST;
    }

    public function getProductCategoryList(): array
    {
        $productList 	= $this->CI->config->item('product_list');
        $allowProductList = array_filter($productList, function ($item) {
            return in_array($item['id'], $this->creditSheet::ALLOW_PRODUCT_LIST) && $item['type'] == PRODUCT_TYPE_NORMAL;
        });

        return array_column($allowProductList, 'name', 'id');

    }

    /**
     * 取得所需認證項目之對應數值
     * @return array
     */
    protected function getAllCertification() {
        $response = [];
        foreach ($this->certificationProcessList as $certId => $columnList) {
            $response = array_merge($response, $this->getCertInfo($certId, $columnList));
        }
        return $response;
    }

    /**
     * 取得已通過的徵信項目的資料
     * @param $certId: 徵信項目代表編號
     * @param $selectColumnList: 需求欄位名稱列表
     * @return array
     */
    protected function getCertInfo($certId, $selectColumnList) {
        $result = [];
        $info = $this->CI->certification_lib->get_certification_info($this->creditSheet->user->id, $certId, 0);
        if($info && $info->status == 1) {
            foreach ($selectColumnList as $columnName) {
                $result[$columnName] = $info->content[$columnName] ?? '';
            }
        }else{
            $result = array_fill_keys($selectColumnList, '');
        }
        return $result;
    }

    /**
     * 獲得初貸日期
     * @return string
     */
    protected function getFirstApplyDate(): string
    {
        $target = $this->CI->target_model->order_by('id','ASC')->get_by(['user_id'=>$this->creditSheet->user->id]);
        $rs = "";
        if(isset($target)) {
            $dateStr = date('Y/m/d', $target->created_at);
            $rs = $dateStr !== false ? $dateStr : "";
        }
        return $rs;
    }

    /**
     * 獲得用戶還款中的案件產品類型列表
     * @return array|false
     */
    protected function getProductCategories() {
        $this->repayableTargets = $this->CI->target_model->order_by('id','ASC')->get_many_by(
                ['user_id'=>$this->creditSheet->user->id, 'status' => TARGET_REPAYMENTING]);
        return array_map('intval', array_unique(array_column($this->repayableTargets, 'product_id')));
    }

    /**
     * 取得授信核貸層次，未審核過不能選取任何項目
     * @return int
     */
    protected function getReviewedLevel() {
        return 0;
    }

    /**
     * 獲取該案授信類型
     * 新案     : 無額度或申請產品類別不同 (需重跑額度)
     * 產轉案件  : 該案件是逾期案產轉
     * 改貸     : 申貸案件與核准期數不同
     * 增貸     : 有本金餘額且有核准紀錄
     * @return int
     */
    protected function getCreditCategory(): int
    {
        $this->CI->load->model('loan/credit_model');
        $credit = $this->CI->credit_model->order_by('created_at', 'DESC')->
            get_by(['user_id' => $this->creditSheet->user->id, 'created_at <=' => 'UNIX_TIMESTAMP(DATE_ADD(FROM_UNIXTIME(created_at), INTERVAL 6 MONTH))']);
        if(!isset($credit) || $this->creditSheet->target->product_id != $credit->product_id) {
            return self::CREDIT_CATEGORY_NEW_TARGET;
        }else if($this->creditSheet->target->sub_status == TARGET_SUBSTATUS_SUBLOAN_TARGET) {
            return self::CREDIT_CATEGORY_SUBLOAN;
        }else if($credit->instalment != $this->creditSheet->target->instalment) {
            return self::CREDIT_CATEGORY_CHANGE_LOAN;
        }else if(count($this->repayableTargets)) {
            return self::CREDIT_CATEGORY_INCREMENTAL_LOAN;
        }
    }

    /**
     * 取得製表日期 (該案核准時間)
     * @return string
     */
    protected function getReportDate(): string
    {
        return '';
    }

    /**
     * 取得信評產生日期 (核准後才顯示)
     * @return string
     */
    protected function getCreditDate(): string
    {
        return '';
    }

    /**
     * 取得信評等級 (核准後才顯示)
     * @return string
     */
    protected function getCreditRank(): string
    {
        return '';
    }

    /**
     * TODO: 待確認關係戶是在核准過還是核准前顯示，以及如何實作
     * 取得關係戶之 用戶名稱 與 user_id
     * @return array
     */
    public function getRelation(): array
    {
        return [];
    }


}
