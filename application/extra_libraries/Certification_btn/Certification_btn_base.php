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
    protected $cert;

    public function __construct(array $certification)
    {
        $this->CI =& get_instance();

        $this->id = $certification['id'];

        $cert = Certification_factory::get_instance_by_id($this->id);
        $this->status = ! isset($cert->certification['status']) ? 0 : (int) $cert->certification['status'];
        $this->sub_status = ! isset($cert->certification['sub_status']) ? 0 : (int) $cert->certification['sub_status'];
        $this->sys_check = ! isset($cert->certification['sys_check']) ? 0 : (int) $cert->certification['sys_check'];
        $this->is_expired = isset($cert) && $cert->is_expired();
        $this->is_submitted = isset($cert) && $cert->is_submitted();
        $this->cert = isset($cert) ? $cert->certification : [];
    }

    abstract public function draw(): string;
}