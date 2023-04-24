<?php

namespace Certification_ocr\Marker;
defined('BASEPATH') or exit('No direct script access allowed');

class Ocr_marker_factory
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
            case CERTIFICATION_JOB:
                return new Cert_job($info);
            default:
                log_msg('error', "欲建立未支援的認證徵信 OCR marker 項目 (認證編號:{$info['certification_id']}) ");
                return NULL;
        }
    }
}