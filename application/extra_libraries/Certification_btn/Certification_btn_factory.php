<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

class Certification_btn_factory
{
    public static function get_instance($certification)
    {
        if (empty($certification['certification_id']) || empty($certification['id']))
        {
            return NULL;
        }

        switch ($certification['certification_id'])
        {
            case CERTIFICATION_DEBITCARD:
                return new Cert_btn_debitcard($certification);
            default:
                return new Cert_btn_default($certification);
        }
    }
}
