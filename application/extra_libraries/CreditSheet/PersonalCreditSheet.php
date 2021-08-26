<?php
namespace CreditSheet;
use CreditSheet\BasicInfo\PersonalBasicInfo;
use CreditSheet\CreditLine\CreditLineInfo;

defined('BASEPATH') OR exit('No direct script access allowed');

class PersonalCreditSheet extends CreditSheetBase {

    /**
     * @var PersonalBasicInfo
     */
    public $basicInfo;
    public $creditLineInfo;
    public $target;
    public $user;
    protected $CI;

    function __construct($target, $user, PersonalBasicInfo $personalBasicInfo, CreditLineInfo $creditLineInfo)
    {
        $this->target = $target;
        $this->user = $user;

        $this->CI = &get_instance();
        $this->CI->load->model('loan/credit_sheet_model');

        $this->basicInfo = $personalBasicInfo;
        $this->basicInfo->setCreditSheet($this);

        $this->creditLineInfo = $creditLineInfo;
        $this->creditLineInfo->setCreditSheet($this);

        $this->creditSheetRecord = $this->CI->credit_sheet_model->get_by(['target_id' => $this->target->id]);
        if(isset($this->creditSheetRecord))
            $this->creditRecord = $this->CI->credit_model->get_by(['id' => $this->creditSheetRecord->credit_id]);

    }



}
