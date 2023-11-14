<?php

namespace Credit;
defined('BASEPATH') or exit('No direct script access allowed');

class Credit_smec_qualification extends Credit_base
{
    static public $item = '保證要素';
    static public $subitem = '信保基金送保資格';

    public function scoring(): Credit_definition
    {
        if (isset($this->content['status']) && isset($this->content['sub_status']))
        {
            if ($this->content['sub_status'] == TARGET_SUBSTATUS_SECOND_INSTANCE)
            {
                $this->set_score(10, '符合信保資格');
            }
            else
            {
                $this->set_score(0, '不符合信保資格');
            }
        }
        return $this;
    }
}