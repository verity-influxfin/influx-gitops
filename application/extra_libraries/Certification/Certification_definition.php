<?php
namespace Certification;
defined('BASEPATH') OR exit('No direct script access allowed');

interface Certification_definition
{
    /**
     * 是否已可開始驗證
     * @return bool
     */
    public function can_verify(): bool;

    /**
     * 解析輸入資料
     */
    public function parse();

    /**
     * 驗證之前的前置確認作業
     * @return bool
     */
    public function check_before_verify(): bool;

    /**
     * 開始驗證徵信的程序
     * @return bool
     */
    public function verify(): bool;

    /**
     * 驗證格式是否正確
     * @param $content: 徵信內容
     * @return bool
     */
    public function verify_format($content): bool;

    /**
     * 核實資料是否屬實
     * @param $content: 徵信內容
     * @return bool
     */
    public function verify_data($content): bool;

    /**
     * 依照授信規則審查資料
     * @param $content: 徵信內容
     * @return bool
     */
    public function review_data($content): bool;

    /**
     * 審核成功
     * @param bool $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_success(bool $sys_check, string $msg): bool;

    /**
     * 審核失敗
     * @param bool $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_failure(bool $sys_check, string $msg): bool;

    /**
     * 轉人工
     * @param $sys_check
     * @param string $msg
     * @return bool
     */
    public function set_review($sys_check, string $msg): bool;

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool;

    /**
     * 審核失敗前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_failure($sys_check): bool;

    /**
     * 轉人工前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_review($sys_check): bool;


    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool;

    /**
     * 審核失敗後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_failure($sys_check): bool;

    /**
     * 轉人工後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_review($sys_check): bool;

    /**
     * 審核成功的通知
     * @return bool
     */
    public function success_notification(): bool;

    /**
     * 審核失敗的通知
     * @return bool
     */
    public function failure_notification(): bool;

    /**
     * 轉人工的通知
     * @return bool
     */
    public function review_notification(): bool;

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool;

    /**
     * 所有項目是否已提交
     * @return bool
     */
    public function is_submitted(): bool;

    /**
     * 該徵信項是否已完成
     * @return bool
     */
    public function is_succeed(): bool;

    /**
     * 該徵信項是否已失敗
     * @return bool
     */
    public function is_failed(): bool;

    /**
     * 該徵信項是否因為格式不符而失敗
     * @return bool
     */
    public function is_wrong_format(): bool;

    /**
     * 該徵信項是否因為身份核實失敗而失敗
     * @return bool
     */
    public function is_failed_verification(): bool;

    /**
     * 該徵信項是否因為授信不符標準而失敗
     * @return bool
     */
    public function is_failed_review(): bool;

    /**
     * 該徵信項是否待人工審核
     * @return bool
     */
    public function is_pending_to_review(): bool;

    /**
     * 該徵信項是否待驗證
     * @return bool
     */
    public function is_pending_to_verify(): bool;

    /**
     * 該徵信項是否曾送出審核過
     * @return bool
     */
    public function is_submit_to_review(): bool;

    /**
     * 是否已完成依賴的相關徵信項目
     * @return bool
     */
    public function is_qualified(): bool;

    /**
     * 是否可以重新提交
     * @return bool
     */
    public function can_re_submit(): bool;

    /**
     * 驗證結束後處理函數
     * @return bool
     */
    public function post_verify(): bool;
}