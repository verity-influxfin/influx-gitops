<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

use Certification\Certification_factory;

abstract class Certification_btn_base
{
    protected $CI;

    protected $id;
    protected $status;
    protected $sub_status;
    protected $sys_check;
    protected $is_expired;
    protected $is_submitted;

    public function __construct(array $certification)
    {
        $this->CI =& get_instance();

        $this->id = $certification['id'];
        $this->status = ! isset($certification['status']) ? 0 : (int) $certification['status'];
        $this->sub_status = ! isset($certification['sub_status']) ? 0 : (int) $certification['sub_status'];
        $this->sys_check = ! isset($certification['sys_check']) ? 0 : (int) $certification['sys_check'];

        $cert = Certification_factory::get_instance_by_model_resource($certification);
        $this->is_expired = isset($cert) && $cert->is_expired();
        $this->is_submitted = isset($cert) && $cert->is_submitted();
    }

    abstract public function draw(): string;
}