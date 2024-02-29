<?php

namespace Credit\due_diligence;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_base;
use Credit\Credit_definition;

class Credit_background extends Credit_base
{
    static public $item = 'DD查核';
    static public $subitem = '創業背景';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        parent::__construct($content, $user_id = 0, $investor = NULL);
        $this->CI->load->model('loan/target_meta_model');
    }

    public function scoring(): Credit_definition
    {
        if (isset($this->content['background']))
        {
            switch ($this->content['background'])
            {
                case $this->CI->target_meta_model::BK_SELF:
                    $this->set_score(5, '自行創業');
                    break;
                case $this->CI->target_meta_model::BK_SUCCESSOR:
                    $this->set_score(4, '二代接班');
                    break;
                case $this->CI->target_meta_model::BK_FAMILY_SUPPORT:
                    $this->set_score(3, '家族支持');
                    break;
                case $this->CI->target_meta_model::BK_PARTNERSHIP:
                    $this->set_score(2, '股東合資');
                    break;
                case $this->CI->target_meta_model::BK_OTHER:
                    $this->set_score(1, '其他');
                    break;
            }
        }
        return $this;
    }
}