<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_debit_ratio extends Credit_base
{
    static public $item = '財務要素';
    static public $subitem = '負債比';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['compCapital']) && isset($this->content['jcCompCreditAmount']) &&
            isset($this->content['prCreditTotalAmount']) && isset($this->content['prCreditCardTotalBalance']))
        {
            // 負債比 =（總借款 / 實收資本）
            $debit_ratio = ($this->content['jcCompCreditAmount'] +
                    $this->content['prCreditTotalAmount'] + $this->content['prCreditCardTotalBalance'] +
                    ($this->content['spouseCreditTotalAmount'] ?? 0) + ($this->content['spouseCreditCardTotalBalance'] ?? 0)) / $this->content['compCapital'];
            if ($debit_ratio >= 400)
            {
                $this->set_score(0, '≧400%');
            }
            else
            {
                $this->set_score(5, '<400%');
            }
        }
        return $this;
    }
}