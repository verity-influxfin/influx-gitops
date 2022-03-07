<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

use Certification\Certification_factory;

abstract class Certification_btn_base
{
    protected $CI;

    protected $id;
    protected $status;
    protected $sys_check;
    protected $is_expired;

    public function __construct(array $certification)
    {
        $this->CI =& get_instance();

        $this->id = $certification['id'];
        $this->status = empty($certification['status']) ? 0 : (int) $certification['status'];
        $this->sys_check = empty($certification['sys_check']) ? 0 : (int) $certification['sys_check'];

        $cert = Certification_factory::get_instance_by_model_resource($certification);
        $this->is_expired = $cert->is_expired();
    }

    abstract public function draw(): string;
}