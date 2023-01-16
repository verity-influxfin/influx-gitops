<?php

namespace Certification_ocr\Parser;
defined('BASEPATH') or exit('No direct script access allowed');

class Ocr_parser_factory
{
    public static function get_instance(array $info)
    {
        if (empty($info['certification_id']))
        {
            log_msg('error', '出現錯誤的存取');
            return NULL;
        }

        switch ($info['certification_id'])
        {
            case CERTIFICATION_STUDENT:
                return new Cert_student($info);
            case CERTIFICATION_INVESTIGATION:
                return new Cert_investigation($info);
            case CERTIFICATION_JOB:
                return new Cert_job($info);
            case CERTIFICATION_BUSINESSTAX:
                return new Cert_businesstax($info);
            case CERTIFICATION_BALANCESHEET:
                return new Cert_balancesheet($info);
            case CERTIFICATION_INCOMESTATEMENT:
                return new Cert_incomestatement($info);
            case CERTIFICATION_GOVERNMENTAUTHORITIES:
                return new Cert_governmentauthorities($info);
            case CERTIFICATION_HOUSE_DEED:
                return new Cert_house_deed($info);
            case CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS:
                return new Cert_land_and_building_transaction($info);
            default:
                log_msg('error', "欲建立未支援的認證徵信 OCR Parser 項目 (認證編號:{$info['certification_id']}) ");
                return NULL;
        }
    }
}