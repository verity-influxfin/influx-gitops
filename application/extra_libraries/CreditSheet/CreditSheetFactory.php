<?php
namespace CreditSheet;
use CreditSheet\BasicInfo\PersonalBasicInfo;
use CreditSheet\BasicInfo\ArchivingPersonalBasicInfo;
use CreditSheet\CreditLine\CreditLineInfo;
use CreditSheet\CreditLine\ArchivingCreditLineInfo;
use CreditSheet\CashLoan\CashLoanInfo;
use CreditSheet\CashLoan\ArchivingCashLoanInfo;
defined('BASEPATH') OR exit('No direct script access allowed');

class CreditSheetFactory {
    public static function getInstance($targetId) {
        $CI = &get_instance();
        $CI->load->model('user/user_model');
        $CI->load->model('loan/credit_sheet_model');

        $target = $CI->target_model->get_by(['id'=>$targetId]);
        $user = $CI->user_model->get_by(['id'=> $target->user_id ?? 0]);
        $creditSheetRecord = $CI->credit_sheet_model->get_by(['target_id' => $target->id ?? 0]);

        if(isset($creditSheetRecord)) {
            // 已封存之個金授審表
            $basicInfo = new ArchivingPersonalBasicInfo();
            $creditLineInfo = new ArchivingCreditLineInfo();
            $cashLoanInfo = new ArchivingCashLoanInfo();
        }else{
            // 未封存之個金授審表
            $basicInfo = new PersonalBasicInfo();
            $creditLineInfo = new CreditLineInfo();
            $cashLoanInfo = new CashLoanInfo();
        }
        return new PersonalCreditSheet($target, $user, $basicInfo, $creditLineInfo, $cashLoanInfo);
    }

}
