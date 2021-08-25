<?php
namespace CreditSheet;
use CreditSheet\BasicInfo\BasicInfoBase;

defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalCreditSheet {

    /**
     * @var BasicInfoBase
     */
    public $basicInfo;

    function __construct(BasicInfoBase $personalBasicInfo)
    {
        $this->basicInfo = $personalBasicInfo;
    }
}
