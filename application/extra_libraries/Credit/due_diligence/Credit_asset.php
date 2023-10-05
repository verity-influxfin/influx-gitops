<?php

namespace Credit\due_diligence;
defined('BASEPATH') or exit('No direct script access allowed');

use Credit\Credit_base;
use Credit\Credit_definition;

class Credit_asset extends Credit_base
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
        if (isset($this->content['capitalization']))
        {
            switch ($this->content['capitalization'])
            {
                case $this->CI->target_meta_model::GUARANTOR_JOB_LECTURER:
                    $this->set_score(10, '公家機關、大專院校講師等級以上');
                    break;
                case $this->CI->target_meta_model::GUARANTOR_JOB_PROFESSIONAL:
                    $this->set_score(7, '專業人士（醫師、會計師、律師、白領主管）');
                    break;
                case $this->CI->target_meta_model::GUARANTOR_JOB_HUGE_COMPANY_EMPLOYEE:
                    $this->set_score(5, '1000大企業員工');
                    break;
                case $this->CI->target_meta_model::GUARANTOR_JOB_GENERAL:
                    $this->set_score(3, '具正當職業（需徵勞保卡或扣繳憑單或薪轉存摺或稅額證明）');
                    break;
                case $this->CI->target_meta_model::GUARANTOR_JOB_INVALID:
                    $this->set_score(0, '無徵提保人');
                    break;
            }
        }
        return $this;
    }
}