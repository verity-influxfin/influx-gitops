<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_revenue_stability extends Credit_base
{
    static public $item = '財務要素';
    static public $subitem = '營收穩定性';

    public function scoring(): Credit_definition
    {
        if ( ! empty($this->content['lastOneYearRevenue']))
        {
            $last_one_year_revenue = $this->content['lastOneYearRevenue'];
        }
        else
        {
            $last_one_year_revenue = ($this->content['lastOneYearInvoiceAmountM1M2'] ?? 0) + ($this->content['lastOneYearInvoiceAmountM3M4'] ?? 0) +
                ($this->content['lastOneYearInvoiceAmountM5M6'] ?? 0) + ($this->content['lastOneYearInvoiceAmountM7M8'] ?? 0) +
                ($this->content['lastOneYearInvoiceAmountM9M10'] ?? 0) + ($this->content['lastOneYearInvoiceAmountM11M12'] ?? 0);
        }
        if ( ! empty($this->content['lastTwoYearRevenue']))
        {
            $last_two_year_revenue = $this->content['lastTwoYearRevenue'];
        }
        else
        {
            $last_two_year_revenue = ($this->content['lastTwoYearInvoiceAmountM1M2'] ?? 0) + ($this->content['lastTwoYearInvoiceAmountM3M4'] ?? 0) +
                ($this->content['lastTwoYearInvoiceAmountM5M6'] ?? 0) + ($this->content['lastTwoYearInvoiceAmountM7M8'] ?? 0) +
                ($this->content['lastTwoYearInvoiceAmountM9M10'] ?? 0) + ($this->content['lastTwoYearInvoiceAmountM11M12'] ?? 0);
        }

        if ($last_one_year_revenue && $last_two_year_revenue)
        {
            // 營收穩定性 = ((近一年營收-近兩年營收)/近一年營收)X100%
            $revenue_stability = ($last_one_year_revenue - $last_two_year_revenue) / $last_one_year_revenue * 100;
            if ($revenue_stability > 20)
            {
                $this->set_score(10, '近12個月平均營收較前年完整年度成長>20%');
            }
            else if ($revenue_stability >= 10)
            {
                $this->set_score(7, '近12個月平均營收較前年完整年度成長10-20%');
            }
            else if ($revenue_stability >= 0)
            {
                $this->set_score(4, '近12個月平均營收較前年完整年度成長0-10% (或無整年度)');
            }
            else
            {
                $this->set_score(0, '近12個月平均營收較前年完整年度成長<0%');
            }
        }
        return $this;
    }
}