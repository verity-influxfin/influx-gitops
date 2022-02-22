<?php
namespace CertificationResult;
defined('BASEPATH') or exit('No direct script access allowed');

class CertificationResultFactory
{
    public static function getInstance($certification_id, $status = 0, $resubmitExpirationMonth = 6)
    {
        switch ($certification_id)
        {
            case CERTIFICATION_IDENTITY: // 實名認證
                return new IdentityCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_STUDENT: // 學生身份認證
                return new StudentCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_SOCIAL: // 社交帳號
                return new SocialCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_EMERGENCY: // 緊急聯絡人
                return new EmergencyCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_EMAIL: // 常用電子信箱
                return new EmailCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_INVESTIGATION:
                return new InvestigationCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_JOB:
                return new JobCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_REPAYMENT_CAPACITY:
                return new RepaymentCapacityCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_SOCIAL_INTELLIGENT: // (名校貸)社交帳號
                return new SocialIntelligentCertificationResult($status, $resubmitExpirationMonth);
            default:
                return new CertificationResult($status, $resubmitExpirationMonth);
        }
    }

}