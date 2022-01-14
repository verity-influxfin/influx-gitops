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
    public static function getInstance($targetId): ?PersonalCreditSheet
    {
        $CI = &get_instance();
        $CI->load->model('user/user_model');
        $CI->load->model('loan/credit_sheet_model');

        $target = $CI->target_model->get_by(['id'=>$targetId]);
        $user = $CI->user_model->get_by(['id'=> $target->user_id ?? 0]);
        $creditSheetRecord = $CI->credit_sheet_model->get_by(['target_id' => $target->id ?? 0, 'status' => 1]);

        // 判斷類型
        $call_type = '';
        if (in_array($target->product_id,['1002']))
        {
            $call_type = TYPE_COMPANY;
        }
        elseif (in_array($target->product_id,['1','2','3','4']))
        {
            $call_type = TYPE_PERSONAL;
        }

        if(isset($creditSheetRecord)) {
            // 已封存之個金授審表
            if ($call_type == TYPE_PERSONAL)
            {
                $basicInfo = new ArchivingPersonalBasicInfo();
                $creditLineInfo = new ArchivingCreditLineInfo();
                $cashLoanInfo = new ArchivingCashLoanInfo();
            }
            elseif ($call_type == TYPE_COMPANY)
            {
            }
        }else{
            // 未封存之個金授審表
            if ($call_type == TYPE_PERSONAL)
            {
                $basicInfo = new PersonalBasicInfo();
                $creditLineInfo = new CreditLineInfo();
                $cashLoanInfo = new CashLoanInfo();
            }
            elseif ($call_type == TYPE_COMPANY)
            {
            }
        }

        $returnObject = NULL;
        try{
            if ($call_type == TYPE_PERSONAL)
            {
                $returnObject = new PersonalCreditSheet($target, $user, $basicInfo, $creditLineInfo, $cashLoanInfo);
            }
            elseif ($call_type == TYPE_COMPANY)
            {
            }
        }catch (\InvalidArgumentException $e) {
            error_log("Invalid Argument Exception: ". $e->getMessage());
        }catch (\Exception $e) {
            error_log("Exception: ". $e->getMessage());
        }
        return $returnObject;
    }

}
