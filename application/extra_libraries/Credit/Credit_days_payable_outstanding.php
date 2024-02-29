<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_days_payable_outstanding extends Credit_base
{
    static public $item = '經營效能';
    static public $subitem = '應付帳款天期';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['lastOneYearCostOfGoodsSold']) &&
            isset($this->content['lastOneYearTradeReceivable']) &&
            isset($this->content['lastTwoYearTradeReceivable']))
        {
            // 平均收現天數=(365/銷貨成本/(期初應收帳款+期末應收帳款)/2)
            $average_return_cash_day = 365 / $this->content['lastOneYearCostOfGoodsSold'] /
                ($this->content['lastTwoYearTradeReceivable'] + $this->content['lastOneYearTradeReceivable']) / 2;
            if ($average_return_cash_day >= 90)
            {
                $this->set_score(5, '90天以上');
            }
            else if ($average_return_cash_day > 60)
            {
                $this->set_score(3, '60天~90天');
            }
            else if ($average_return_cash_day > 30)
            {
                $this->set_score(1, '30天~60天');
            }
            else
            {
                $this->set_score(0, '<30天');
            }
        }
        return $this;
    }
}