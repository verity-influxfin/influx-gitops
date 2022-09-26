<?php
namespace Certification;
defined('BASEPATH') OR exit('No direct script access allowed');

use CertificationResult\CertificationResultFactory;
class Certification_factory
{
    private static $CI;

    /**
     * 依照 id 取得徵信實例
     * @param $id
     * @return Certification_base|Null 徵信項目實例
     */
    public static function get_instance_by_id($id)
    {
        self::$CI = &get_instance();
        self::$CI->load->model('user/user_certification_model');
        $condition = ['id' => $id];
        return self::get_certification_by_cond($condition);
    }

    /**
     * 依照使用者資訊取得徵信實例
     * @param int $certification_id
     * @param $user_id
     * @param $investor
     * @param null $status
     * @return Certification_base|Null 徵信項目實例
     */
    public static function get_instance_by_user(int $certification_id, $user_id, $investor, $status=NULL)
    {
        self::$CI = &get_instance();
        self::$CI->load->model('user/user_certification_model');
        $condition = ['certification_id' => $certification_id, 'user_id' => $user_id, 'investor' => $investor];
        if (isset($status))
        {
            $condition['status'] = $status;
        }
        return self::get_certification_by_cond($condition);
    }

    /**
     * 依照 user_certification_model 結果取得徵信實例
     * @param $rs
     * @return Certification_base|Null 徵信項目實例
     */
    public static function get_instance_by_model_resource($rs)
    {
        if (isset($rs))
        {
            if (is_object($rs))
            {
                $rs = json_decode(json_encode($rs), TRUE);
            }
            return self::get_certification($rs);
        }
        return NULL;
    }

    /**
     * 透過篩選條件取得徵信項目
     * @param $condition
     * @return Certification_base|null
     */
    private static function get_certification_by_cond($condition) {
        $rs = self::$CI->user_certification_model->get_certification($condition);
        if (isset($rs))
        {
            return self::get_certification($rs);
        }
        return NULL;
    }

    /**
     * 依照 user_certification_model 結果取得徵信項目實例
     * @param $certification
     * @return Certification_base|null
     */
    private static function get_certification($certification) {
        if ( ! isset($certification) || ! isset($certification['certification_id']))
        {
            log_msg('error', '出現錯誤的存取');
            return NULL;
        }

        if (in_array($certification['certification_id'], [CERTIFICATION_EMAIL, CERTIFICATION_COMPANYEMAIL]))
        { // 當徵信項為「常用電子信箱」時 result 預設為待驗證
            $certification_result = CertificationResultFactory::getInstance($certification['certification_id'], CERTIFICATION_STATUS_PENDING_TO_VALIDATE);
        }
        else
        { // 其餘徵信項的 result 預設為成功
            $certification_result = CertificationResultFactory::getInstance($certification['certification_id'], CERTIFICATION_STATUS_SUCCEED);
        }

        switch ($certification['certification_id']) {
            case CERTIFICATION_IDENTITY: // 實名認證
                return new Cert_identity($certification, $certification_result);
            case CERTIFICATION_STUDENT: // 學生身份認證
                return new Cert_student($certification, $certification_result);
            case CERTIFICATION_DEBITCARD: // 金融帳號認證
                return new Cert_debitcard($certification, $certification_result);
            case CERTIFICATION_SOCIAL: // 社交帳號
                return new Cert_social($certification, $certification_result);
            case CERTIFICATION_EMERGENCY: // 緊急聯絡人
                return new Cert_emergency($certification, $certification_result);
            case CERTIFICATION_EMAIL: // 常用電子信箱
                return new Cert_email($certification, $certification_result);
            case CERTIFICATION_FINANCIAL: // 收支資訊
                return new Cert_financial($certification, $certification_result);
            case CERTIFICATION_DIPLOMA: // 最高學歷證明
                return new Cert_diploma($certification, $certification_result);
            case CERTIFICATION_INVESTIGATION: // 聯合徵信報告
                return new Cert_investigation($certification, $certification_result);
            case CERTIFICATION_JOB: // 工作收入證明
                return new Cert_job($certification, $certification_result);
            case CERTIFICATION_PROFILE: // 個人基本資料
                return new Cert_profile($certification, $certification_result);
            case CERTIFICATION_FINANCIALWORKER: // 財務訊息資訊
                return new Cert_financialworker($certification, $certification_result);
            case CERTIFICATION_REPAYMENT_CAPACITY: // 還款力計算
                return new Cert_repayment_capacity($certification, $certification_result);
            case CERTIFICATION_CRIMINALRECORD: // 良民證
                return new Cert_criminalrecord($certification, $certification_result);
            case CERTIFICATION_SOCIAL_INTELLIGENT: // (名校貸)社交帳號
                return new Cert_social_intelligent($certification, $certification_result);
            case CERTIFICATION_GOVERNMENTAUTHORITIES: // 公司變更事項登記表
                return new Cert_governmentauthorities($certification, $certification_result);
            case CERTIFICATION_COMPANYEMAIL: // 公司電子信箱
                return new Cert_companyemail($certification, $certification_result);
            case CERTIFICATION_JUDICIALGUARANTEE: // 公司授權核實
                return new Cert_judicialguarantee($certification, $certification_result);
            default:
                log_msg('error', "欲建立未支援的認證徵信項目 (認證編號:{$certification['certification_id']}) ");
                return NULL;
        }
    }

}