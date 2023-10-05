<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_days_sales_of_inventory extends Credit_base
{
    static public $item = '經營效能';
    static public $subitem = '存貨周轉天期';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['lastOneYearCostOfGoodsSold']) &&
            isset($this->content['lastOneYearInventory']) &&
            isset($this->content['lastTwoYearInventory']))
        {
            // 平均收現天數=(365/銷貨成本/(期初存貨+期末存貨)/2)
            $average_return_cash_day = 365 / $this->content['lastOneYearCostOfGoodsSold'] /
                ($this->content['lastTwoYearInventory'] + $this->content['lastOneYearInventory']) / 2;
            if ($average_return_cash_day > 90)
            {
                $this->set_score(0, '90天以上');
            }
            else if ($average_return_cash_day > 60)
            {
                $this->set_score(1, '60天~90天');
            }
            else if ($average_return_cash_day > 30)
            {
                $this->set_score(3, '30天~60天');
            }
            else
            {
                $this->set_score(5, '<30天');
            }
        }
        return $this;
    }
}