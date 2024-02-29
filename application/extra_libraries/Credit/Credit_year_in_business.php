<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_year_in_business extends Credit_base
{
    static public $item = '借戶要素';
    static public $subitem = '企業經營資歷 經濟部公司設立年資';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['compSetDate']))
        {
            if (preg_match('/^(?<year>\d{4})(?<month>\d{2})(?<day>\d{2})/', $this->content['compSetDate'], $regexResult))
            {
                $date_str = sprintf('%d-%d-%d', intval($regexResult['year']), intval($regexResult['month']), intval($regexResult['day']));
                $set_date = \DateTime::createFromFormat('Y-m-d', $date_str);
                $diff_date = (new \DateTime())->diff($set_date);
                $settled_year = $diff_date->y;
                if ($settled_year > 5)
                {
                    $this->set_score(10, '>5年');
                }
                else if ($settled_year > 3)
                {
                    $this->set_score(8, '3-5年');
                }
                else if ($settled_year > 1)
                {
                    $this->set_score(6, '1-3年');
                }
                else if ($settled_year > 0)
                {
                    $this->set_score(4, '0-1年');
                }
                // TODO: 籌備處 6 分？
            }
        }
        return $this;
    }
}