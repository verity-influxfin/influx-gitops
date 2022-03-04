<?php

namespace Certification_btn;
defined('BASEPATH') or exit('No direct script access allowed');

abstract class Certification_btn_base
{
    protected $CI;

    protected $id;
    protected $certification_id;
    protected $status;
    protected $sys_check;
    protected $expire_time;

    public function __construct(array $certification)
    {
        $this->CI =& get_instance();

        $this->id = $certification['id'];
        $this->certification_id = $certification['certification_id'];
        $this->status = $certification['status'];
        $this->sys_check = $certification['sys_check'];
        $this->expire_time = $certification['expire_time'];
    }

    abstract public function draw(): string;
}