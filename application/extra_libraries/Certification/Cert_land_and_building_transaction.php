<?php

namespace Certification;
use Certification_ocr\Parser\Ocr_parser_factory;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 土地建物謄本徵信項
 * Class Land_and_building_transaction
 * @package Certification
 */
class Cert_land_and_building_transaction extends Certification_base
{
    /**
     * @var int 該徵信項之代表編號
     */
    protected $certification_id = CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS;

    /**
     * @var array 所需依賴徵信項之編號
     */
    protected $dependency_cert_id = [CERTIFICATION_HOUSE_DEED];
    public $dependency_cert_list = [];

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
        $parsed_content = $this->content ?? [];
        $parsed_content = array_merge(
            $parsed_content,
            $this->_get_ocr_parser_info()
        );

        $parsed_content['admin_edit'] = $parsed_content['ocr_parser']['content'] ?? [];
        $parsed_content['admin_edit']['address'] = $parsed_content['ocr_parser']['content']['buildingPart']['address_str'] ?? '';

        return $parsed_content;
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
        if ($this->_chk_ocr_status($content) === FALSE)
        {
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

    /**
     * 是否已過期
     * @return bool
     */
    public function is_expired(): bool
    {
        return FALSE;
    }

    /**
     * OCR 解析結果
     * @return array
     */
    private function _get_ocr_parser_info(): array
    {
        $result = [];
        if ( ! isset($this->content['ocr_parser']['res']))
        {
            $cert_ocr_parser = Ocr_parser_factory::get_instance($this->certification);
            $ocr_parser_result = $cert_ocr_parser->get_result();
            if ($ocr_parser_result['success'] === TRUE)
            {
                if ($ocr_parser_result['code'] == 201 || $ocr_parser_result['code'] == 202)
                { // OCR 任務剛建立，或是 OCR 任務尚未辨識完成
                    return $result;
                }
                $result['ocr_parser']['res'] = TRUE;
                $result['ocr_parser']['content'] = $ocr_parser_result['data'];
            }
            else
            {
                $result['ocr_parser']['res'] = FALSE;
                $result['ocr_parser']['msg'] = $ocr_parser_result['msg'];
            }
        }
        return $result;
    }

    // OCR 辨識後的檢查
    private function _chk_ocr_status($content): bool
    {
        if ( ! isset($content['ocr_parser']['res']))
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
            return FALSE;
        }
        else
        {
            $this->result->setStatus(CERTIFICATION_STATUS_PENDING_TO_REVIEW);
        }
        return TRUE;
    }

    /**
     * @param $certification_id
     * @return array
     */
    public function get_dependency_cert_content($certification_id): array
    {
        return $this->dependency_cert_list[$certification_id]->content ?? [];
    }

    /**
     * 審核失敗的通知
     * @return bool
     */
    public function failure_notification(): bool
    {
        return TRUE;
    }
}