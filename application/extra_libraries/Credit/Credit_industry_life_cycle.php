<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_industry_life_cycle extends Credit_base
{
    static public $item = '產業要素';
    static public $subitem = '產業生命週期';

    function __construct($content, $user_id = 0, $investor = NULL)
    {
        parent::__construct($content, $user_id = 0, $investor = NULL);
        $this->CI->load->library('credit_lib');
    }

    public function scoring(): Credit_definition
    {
        if (isset($this->content['businessType']))
        {
            $code = $this->CI->credit_lib->get_business_type_code($this->content['businessType']);
            switch ($code)
            {
                case 'M':
                case 'N':
                case 'Q':
                case 'R':
                case 'S':
                    $this->set_score(10, '成長期');
                    break;
                case 'A':
                case 'B':
                case 'D':
                case 'E':
                case 'G':
                case 'I':
                    $this->set_score(6, '成熟期');
                    break;
                case 'C':
                case 'F':
                case 'H':
                case 'J':
                case 'L':
                case 'P':
                    $this->set_score(4, '衰退期');
                    break;
                default:
                    $this->set_score(4, '導入期');
                    break;
            }
        }
        return $this;
    }
}