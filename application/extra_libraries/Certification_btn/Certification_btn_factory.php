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

        if (empty($certification['status']))
        {
            $certification['status'] = 0;
        }

        if (empty($certification['sys_check']))
        {
            $certification['sys_check'] = 0;
        }

        if (empty($certification['expire_time']))
        {
            $certification['expire_time'] = 0;
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
