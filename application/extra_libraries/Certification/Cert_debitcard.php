<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 金融帳號徵信項
 * Class Debitcard
 * @package Certification
 */
class Cert_debitcard extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_DEBITCARD;

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
        return TRUE;
    }

    /**
     * 審核成功前處理函數
     * @param $sys_check
     * @return bool
     */
    public function pre_success($sys_check): bool
    {
        return TRUE;
    }

    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool
    {
        return TRUE;
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
        // 若同user有其他待驗證的申貸案，金融驗證被退的話，則將該申貸案退回待核可
        $this->CI->load->library('target_lib');
        $target_list = $this->CI->target_model->get_by_multi_product(
            $this->certification['user_id'],
            [TARGET_WAITING_VERIFY],
            $this->related_product
        );
        if ( ! empty($target_list))
        {
            foreach ($target_list as $value)
            {

                $this->CI->target_model->update_by(
                    ['id' => $value['id']],
                    [
                        'status' => TARGET_WAITING_APPROVE,
                        'sub_status' => $value['sub_status'] == TARGET_SUBSTATUS_SECOND_INSTANCE_TARGET ? TARGET_SUBSTATUS_NORNAL : $value['sub_status'],
                        'certificate_status' => TARGET_CERTIFICATE_SUBMITTED
                    ]
                );
            }
        }
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

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return FALSE;
    }
}