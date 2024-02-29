<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_business_finance_ratio extends Credit_base
{
    static public $item = '財務要素';
    static public $subitem = '營授比';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['lastOneYearRevenue']) && isset($this->content['jcCompCreditAmount']) &&
            isset($this->content['prCreditTotalAmount']) && isset($this->content['prCreditCardTotalBalance']))
        {
            // 營授比 = 總借款/年營收 (總借款=聯徵信用報告「公司+負責人+負責人配偶」加計信用卡餘額)
            $business_finance_ratio = ($this->content['jcCompCreditAmount'] +
                    $this->content['prCreditTotalAmount'] + $this->content['prCreditCardTotalBalance'] +
                    ($this->content['spouseCreditTotalAmount'] ?? 0) + ($this->content['spouseCreditCardTotalBalance'] ?? 0)) / $this->content['lastOneYearRevenue'];
            if ($business_finance_ratio > 70)
            {
                $this->set_score(0, '>70%以上');
            }
            else if ($business_finance_ratio > 45)
            {
                $this->set_score(3, '46%－70%');
            }
            else if ($business_finance_ratio > 20)
            {
                $this->set_score(4, '21%－45%');
            }
            else
            {
                $this->set_score(5, '≦20%');
            }
        }
        return $this;
    }
}