<?php

namespace Certification_ocr;
defined('BASEPATH') or exit('No direct script access allowed');

class Certification_ocr_factory
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
            case CERTIFICATION_BUSINESSTAX:
                return new Cert_businesstax($info);
            default:
                log_msg('error', "欲建立未支援的認證徵信項目 (認證編號:{$info['certification_id']}) ");
                return NULL;
        }
    }
}