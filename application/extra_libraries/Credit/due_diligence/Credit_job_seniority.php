<?php

namespace Credit\due_diligence;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_base;
use Credit\Credit_definition;

class Credit_job_seniority extends Credit_base
{
    static public $item = 'DD查核';
    static public $subitem = '本業資歷';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        parent::__construct($content, $user_id = 0, $investor = NULL);
        $this->CI->load->model('loan/target_meta_model');
    }

    public function scoring(): Credit_definition
    {
        if (isset($this->content['seniority']))
        {
            switch ($this->content['seniority'])
            {
                case $this->CI->target_meta_model::JOB_SENIORITY_TEN_YEARS_ABOVE:
                    $this->set_score(5, '10年以上');
                    break;
                case $this->CI->target_meta_model::JOB_SENIORITY_FIVE_YEARS_ABOVE:
                    $this->set_score(3, '5年以上');
                    break;
                case $this->CI->target_meta_model::JOB_SENIORITY_THREE_YEARS_ABOVE:
                    $this->set_score(1, '3年以上');
                    break;
                case $this->CI->target_meta_model::JOB_SENIORITY_OTHER:
                    $this->set_score(0, '其他');
                    break;
            }
        }
        return $this;
    }
}