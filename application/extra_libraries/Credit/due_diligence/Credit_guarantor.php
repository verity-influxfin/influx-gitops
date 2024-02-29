<?php

namespace Credit\due_diligence;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_base;
use Credit\Credit_definition;

class Credit_guarantor extends Credit_base
{
    static public $item = 'DD查核';
    static public $subitem = '公司、負責人(配偶)資產市值';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        parent::__construct($content, $user_id = 0, $investor = NULL);
        $this->CI->load->model('loan/target_meta_model');
    }

    public function scoring(): Credit_definition
    {
        if (isset($this->content['guarantor']))
        {
            switch ($this->content['guarantor'])
            {
                case $this->CI->target_meta_model::ASSET_GE_30_MILLION:
                    $this->set_score(10, '>3000萬');
                    break;
                case $this->CI->target_meta_model::ASSET_BT_10_30_MILLION:
                    $this->set_score(7, '1000-3000萬(含)');
                    break;
                case $this->CI->target_meta_model::ASSET_BT_5_10_MILLION:
                    $this->set_score(5, '500-1000萬(含)');
                    break;
                case $this->CI->target_meta_model::ASSET_BT_0_5_MILLION:
                    $this->set_score(3, '0-500萬(含)');
                    break;
                case $this->CI->target_meta_model::ASSET_INVALID:
                    $this->set_score(0, '未提供或提供無效資訊者');
                    break;
            }
        }
        return $this;
    }
}