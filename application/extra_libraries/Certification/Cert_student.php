<?php

namespace Certification;
defined('BASEPATH') or exit('No direct script access allowed');

use CertificationResult\MessageDisplay;

/**
 * 學生身份證徵信項
 * Class Student
 * @package Certification
 */
class Cert_student extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_STUDENT;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [CERTIFICATION_IDENTITY];

    /**
     * @var array 依賴該徵信項相關之徵信項編號
     */
    protected $relations = [];

    /**
     * @var int 認證持續有效月數
     */
    protected $valid_month = 6;

    /**
     * @var array 轉換後的資料
     */
    private $transform_data = [];

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
        if (time() > $this->certification['created_at'] + 3600)
        {
            $this->result->addMessage(
                '未在有效時間內完成認證',
                CERTIFICATION_STATUS_FAILED,
                MessageDisplay::Client
            );

            $this->result->setSubStatus(CERTIFICATION_SUBSTATUS_VERIFY_FAILED);

            return FALSE;
        }
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
        $data = array(
            'student_status' => 1,
            'school_name' => $content['school'],
            'school_system' => $content['system'],
            'school_department' => $content['department'],
            'school_major' => $content['major'],
            'school_email' => $content['email'],
            'school_grade' => $content['grade'],
            'student_id' => $content['student_id'],
            'student_card_front' => $content['front_image'],
            'student_card_back' => $content['back_image'],
            'student_sip_account' => $content['sip_account'],
            'student_sip_password' => $content['sip_password'],
            'student_license_level' => $content['license_level'],
            'student_game_work_level' => $content['game_work_level'],
            'student_pro_level' => $content['pro_level'],
        );
        isset($content['graduate_date']) ? $data['graduate_date'] = $content['graduate_date'] : '';
        isset($content['programming_language']) ? $data['student_programming_language'] = count($content['programming_language']) : '';
        isset($content['transcript_image']) ? $data['transcript_front'] = $content['transcript_image'][0] : '';

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

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return FALSE;
    }
}