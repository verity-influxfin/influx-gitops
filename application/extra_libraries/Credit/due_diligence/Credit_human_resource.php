<?php

namespace Credit\due_diligence;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_base;
use Credit\Credit_definition;


class Credit_human_resource extends Credit_base
{
    static public $item = 'DD查核';
    static public $subitem = '人力變動狀況(可複選)';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        parent::__construct($content, $user_id = 0, $investor = NULL);
        $this->CI->load->model('loan/target_meta_model');
    }

    public function scoring(): Credit_definition
    {
        if (isset($this->content['changes']))
        {
            $options = [];
            if ($this->content['changes'] & $this->CI->target_meta_model::HUMAN_RESOURCE_KEYMAN_GE_THREE)
            {
                $options[] = ['score' => 5, 'option' => '核心團隊股東人數>3人(不包含負責人配偶)'];
            }
            if ($this->content['changes'] & $this->CI->target_meta_model::HUMAN_RESOURCE_DIMISSION_RATE_GE_HALF)
            {
                $options[] = ['score' => 3, 'option' => '公司離職率<50%'];
            }
            if ($this->content['changes'] & $this->CI->target_meta_model::HUMAN_RESOURCE_AVERAGE_SALARY_GE_50000)
            {
                $options[] = ['score' => 2, 'option' => '平均薪資>5萬'];
            }
            if ($this->content['changes'] & $this->CI->target_meta_model::HUMAN_RESOURCE_INVALID)
            {
                $options[] = ['score' => 0, 'option' => '未提供或提供無效資訊者'];
            }
            if ( ! empty($options))
            {
                $this->set_score(array_sum(array_column($options, 'score')), join('、', array_column($options, 'option')));
            }
        }
        return $this;
    }
}