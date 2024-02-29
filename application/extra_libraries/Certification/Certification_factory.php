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
            case CERTIFICATION_INVESTIGATIONA11: // 聯合徵信報告+A11
                return new Cert_investigationA11($certification, $certification_result);
            case CERTIFICATION_FINANCIALWORKER: // 財務訊息資訊
                return new Cert_financialworker($certification, $certification_result);
            case CERTIFICATION_REPAYMENT_CAPACITY: // 還款力計算
                return new Cert_repayment_capacity($certification, $certification_result);
            case CERTIFICATION_CRIMINALRECORD: // 良民證
                return new Cert_criminalrecord($certification, $certification_result);
            case CERTIFICATION_SOCIAL_INTELLIGENT: // (名校貸)社交帳號
                return new Cert_social_intelligent($certification, $certification_result);
            case CERTIFICATION_HOUSE_CONTRACT:
                return new Cert_house_contract($certification, $certification_result);
            case CERTIFICATION_HOUSE_RECEIPT:
                return new Cert_house_receipt($certification, $certification_result);
            case CERTIFICATION_RENOVATION_CONTRACT:
                return new Cert_renovation_contract($certification, $certification_result);
            case CERTIFICATION_RENOVATION_RECEIPT:
                return new Cert_renovation_receipt($certification, $certification_result);
            case CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT:
                return new Cert_appliance_contract_receipt($certification, $certification_result);
            case CERTIFICATION_HOUSE_DEED:
                return new Cert_house_deed($certification, $certification_result);
            case CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS:
                return new Cert_land_and_building_transaction($certification, $certification_result);
            case CERTIFICATION_SITE_SURVEY_VIDEO:
                return new Cert_site_survey_video($certification, $certification_result);
            case CERTIFICATION_SITE_SURVEY_BOOKING:
                return new Cert_site_survey_booking($certification, $certification_result);
            case CERTIFICATION_SIMPLIFICATIONFINANCIAL: // 財務收支
                return new Cert_simplificationfinancial($certification, $certification_result);
            case CERTIFICATION_SIMPLIFICATIONJOB: // 工作資料
                return new Cert_simplificationjob($certification, $certification_result);
            case CERTIFICATION_PASSBOOKCASHFLOW_2: // (自然人)近六個月往來存摺封面及內頁
                return new Cert_passbookcashflow_2($certification, $certification_result);
            case CERTIFICATION_BUSINESSTAX: // 近三年401/403/405表
                return new Cert_businesstax($certification, $certification_result);
            case CERTIFICATION_BALANCESHEET: // 資產負債表
                return new Cert_balancesheet($certification, $certification_result);
            case CERTIFICATION_INCOMESTATEMENT: // 近三年所得稅結算申報書
                return new Cert_incomestatement($certification, $certification_result);
            case CERTIFICATION_INVESTIGATIONJUDICIAL: // 公司聯合徵信
                return new Cert_investigationjudicial($certification, $certification_result);
            case CERTIFICATION_PASSBOOKCASHFLOW: // 近六個月往來存摺封面+內頁
                return new Cert_passbookcashflow($certification, $certification_result);
            case CERTIFICATION_GOVERNMENTAUTHORITIES: // 變更登記事項表/商業登記證明
                return new Cert_governmentauthorities($certification, $certification_result);
            case CERTIFICATION_EMPLOYEEINSURANCELIST: // 近12個月員工投保人數資料
                return new Cert_employeeinsurancelist($certification, $certification_result);
            case CERTIFICATION_PROFILEJUDICIAL: // 公司資料表
                return new Cert_profilejudicial($certification, $certification_result);
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