<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_per_captial_output extends Credit_base
{
    static public $item = '經營效能';
    static public $subitem = '人均產值';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['lastOneYearRevenue']))
        {
            $per_capital = 0;
            if (isset($this->content['numOfInsured1']))
            {
                $per_capital = $this->content['lastOneYearRevenue'] / $this->content['numOfInsured1'];
            }
            else if (isset($certs_content[CERTIFICATION_PROFILEJUDICIAL]['employeeNum']))
            {
                $per_capital = $this->content['lastOneYearRevenue'] / $this->content['employeeNum'];
            }

            if ($per_capital > 3000000)
            {
                $this->set_score(5, '300萬以上');
            }
            else if ($per_capital > 1000000)
            {
                $this->set_score(3, '100萬~300萬');
            }
            else
            {
                $this->set_score(0, '100萬以下');
            }
        }
        return $this;
    }
}