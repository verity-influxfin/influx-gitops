<?php

namespace Certification;
use CertificationResult\CertificationResult;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 公司授權核實
 * Class Judicialguarantee
 * @package Certification
 */
class Cert_judicialguarantee extends Certification_base
{
    public function __construct($certification, CertificationResult $result)
    {
        parent::__construct($certification, $result);
        $this->CI->load->library('judicialperson_lib');
    }

    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_JUDICIALGUARANTEE;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [CERTIFICATION_GOVERNMENTAUTHORITIES];

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
        $res = $this->CI->judicialperson_lib->script_check_judicial_person_face($this->certification);
        if ( ! empty($res))
        {
            $this->content['judicialPersonId'] = $res['judicialPersonId'];
            $this->content['compareResult'] = $res['compareResult'];
            $this->result->setStatus($res['status']);
        }

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

        return $this->CI->judicialperson_lib->succeed_in_company_guaranty($this->certification['user_id']);
    }

    /**
     * 審核成功後處理函數
     * @param $sys_check
     * @return bool
     */
    public function post_success($sys_check): bool
    {
        return $this->CI->certification_lib->fail_other_cer($this->certification);
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