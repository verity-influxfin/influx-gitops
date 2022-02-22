<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;
use Google\Api\Backend;

/**
 * 收支資訊證徵信項
 * Class Financial
 * @package Certification
 */
class Cert_financial extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_FINANCIAL;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [];

    /**
     * @var array 依賴該徵信項相關之徵信項編號
     */
    protected $relations = [];

    /**
     * @var int 認證持續有效月數
     */
    protected $valid_month = 6;

    /**
     * 所有項目是否已提交
     * @override
     * @return bool
     */
    public function is_submitted(): bool
    {
        return TRUE;
    }

    /**
     * 解析輸入資料
     * @return array|mixed
     */
    public function parse()
    {
        return $this->content;
    }

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool
    {
        return TRUE;
    }

    /**
     * 驗證格式是否正確
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_format($content): bool
    {
        return TRUE;
    }

    /**
     * 核實資料是否屬實
     * @param $content : 徵信內容
     * @return bool
     */
    public function verify_data($content): bool
    {
        return TRUE;
    }

    /**
     * 依照授信規則審查資料
     * @param $content : 徵信內容
     * @return bool
     */
    public function review_data($content): bool
    {
        // 直接轉人工
        $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_REVIEW);
        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool
    {
        $expire_time = new \DateTime;
        $expire_time->setTimestamp($this->certification['created_at']);
        $expire_time->modify("+ {$this->valid_month} month");
        $this->expired_timestamp = $expire_time->getTimestamp();

        return TRUE;
    }

    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool
    {
        $content = $this->content;

        $financial_income = 0;
        $financial_expense = 0;
        $income = [
            // 薪資/打工收入
            'income',
            // 零用錢收入
            'incomeStudent',
            // 獎學金收入
            'scholarship',
            'other_income',
        ];
        $expense = [
            'restaurant',
            'transportation',
            // 網路電信支出
            'telegraph_expense',
            'entertainment',
            'other_expense',
            // 租金
            'rent_expenses',
            // 教育
            'educational_expenses',
            // 保險
            'insurance_expenses',
            // 社交
            'social_expenses',
            // 房貸
            'long_assure_monthly_payment',
            // 車貸
            'mid_assure_monthly_payment',
            // 信貸
            'credit_monthly_payment',
            // 學貸
            'student_loans_monthly_payment',
            // 信用卡
            'credit_card_monthly_payment',
            // 其他民間借款
            'other_private_borrowing'
        ];

        // 收入計算
        foreach ($income as $income_fields)
        {
            if (isset($content[$income_fields]) && is_numeric($content[$income_fields]))
            {
                $financial_income += $content[$income_fields];
            }
        }

        // 支出計算
        foreach ($expense as $expense_fields)
        {
            if (isset($content[$expense_fields]) && is_numeric($content[$expense_fields]))
            {
                $financial_expense += $content[$income_fields];
            }
        }

        $data = array(
            'financial_status' => 1,
            'financial_income' => $financial_income,
            'financial_expense' => $financial_expense,
        );

        if (isset($content['creditcard_image']) && ! empty($content['creditcard_image']))
        {
            $data['financial_creditcard'] = $content['creditcard_image'];
        }
        if (isset($content['passbook_image'][0]) && ! empty($content['passbook_image'][0]))
        {
            $data['financial_passbook'] = $content['passbook_image'][0];
        }

        if (isset($content['bill_phone_image'][0]) && ! empty($content['bill_phone_image'][0]))
        {
            $data['financial_bill_phone'] = $content['bill_phone_image'][0];
        }
        $rs = $this->CI->certification_lib->user_meta_progress($data, $this->certification);
        if ($rs)
        {
            return $this->CI->certification_lib->fail_other_cer($this->certification);
        }
        return FALSE;
    }

    /**
     * 審核失敗前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_failure($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 審核失敗後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_failure($sys_check): bool
    {

        return TRUE;
    }

    /**
     * 轉人工前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_review($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 轉人工後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_review($sys_check): bool
    {
        return TRUE;
    }


}