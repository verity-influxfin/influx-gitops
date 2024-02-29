<?php

namespace Credit\due_diligence;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_base;
use Credit\Credit_definition;

class Credit_team_seniority extends Credit_base
{
    static public $item = 'DD查核';
    static public $subitem = '團隊資歷';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        parent::__construct($content, $user_id = 0, $investor = NULL);
        $this->CI->load->model('loan/target_meta_model');
    }

    public function scoring(): Credit_definition
    {
        if (isset($this->content['group_seniority']))
        {
            switch ($this->content['group_seniority'])
            {
                case $this->CI->target_meta_model::TEAM_SENIORITY_THREE_YEARS_ABOVE:
                    $this->set_score(5, '團隊平均工作年資>3年');
                    break;
                case $this->CI->target_meta_model::TEAM_SENIORITY_KEY_PROJECT_EXP:
                    $this->set_score(2, '專案銷售成功案例提供');
                    break;
                case $this->CI->target_meta_model::TEAM_SENIORITY_INVALID:
                    $this->set_score(0, '未提供或提供無效資訊者');
                    break;
            }
        }
        return $this;
    }
}