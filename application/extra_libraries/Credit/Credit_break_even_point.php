<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_break_even_point extends Credit_base
{
    static public $item = '財務要素';
    static public $subitem = 'BEP分析';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['lastOneYearRevenue']) && isset($this->content['lastOneYearVariableCost']) &&
            isset($this->content['lastOneYearFixedCost']))
        {
            // BEP分析 = 固定費用/(1-變動成本/營業額)
            $break_even_point = $this->content['lastOneYearFixedCost'] / (1 - $this->content['lastOneYearVariableCost'] / $this->content['lastOneYearRevenue']);
            if ($break_even_point >= 0)
            {
                $this->set_score(15, 'BEP≧0');
            }
            else if ($break_even_point > $break_even_point * 0.85)
            {
                $this->set_score(5, 'BEP*(1-15%)<BEP<0');
            }
            else
            {
                $this->set_score(0, 'BEP<BEP*(1-15%)');
            }
        }
        return $this;
    }
}