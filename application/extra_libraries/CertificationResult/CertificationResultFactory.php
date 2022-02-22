<?php
namespace CertificationResult;
defined('BASEPATH') OR exit('No direct script access allowed');

class CertificationResultFactory
{
    public static function getInstance($certification_id, $status=0, $resubmitExpirationMonth=6) {
        switch ($certification_id) {
            case CERTIFICATION_INVESTIGATION:
                return new InvestigationCertificationResult($status, $resubmitExpirationMonth);
            case CERTIFICATION_JOB:
                return new JobCertificationResult($status, $resubmitExpirationMonth);
            default:
                return new CertificationResult($status, $resubmitExpirationMonth);
        }
    }

}