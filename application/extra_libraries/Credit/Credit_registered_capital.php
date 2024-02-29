<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_registered_capital extends Credit_base
{
    static public $item = '借戶要素';
    static public $subitem = '實收資本額(經濟部登記資訊)';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['compCapital']))
        {
            if ($this->content['compCapital'] >= 30000000)
            {
                $this->set_score(10, '≧3000萬');
            }
            else if ($this->content['compCapital'] > 10000000)
            {
                $this->set_score(8, '1000-3000萬');
            }
            else if ($this->content['compCapital'] > 5000000)
            {
                $this->set_score(4, '500-1000萬');
            }
            else if ($this->content['compCapital'] > 3000000)
            {
                $this->set_score(2, '300-500萬');
            }
            else
            {
                $this->set_score(0, '0-300萬');
            }
        }
        return $this;
    }
}