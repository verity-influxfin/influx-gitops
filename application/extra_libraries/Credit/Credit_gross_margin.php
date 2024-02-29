<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_gross_margin extends Credit_base
{
    static public $item = '經營效能';
    static public $subitem = '毛利率';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['lastOneYearRevenue']) &&
            isset($this->content['lastOneYearGrossMargin']))
        {
            // 毛利率=(毛利/營業收入)X100%
            $gross_margin_rate = $this->content['lastOneYearGrossMargin'] / $this->content['lastOneYearRevenue'] * 100;
            if ($gross_margin_rate >= 50)
            {
                $this->set_score(5, '50%以上');
            }
            else if ($gross_margin_rate > 30)
            {
                $this->set_score(3, '30%~50%');
            }
            else if ($gross_margin_rate > 10)
            {
                $this->set_score(1, '10%~30%');
            }
            else
            {
                $this->set_score(0, '10%以下');
            }
        }
        return $this;
    }
}