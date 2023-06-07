<?php
namespace CreditSheet;
use CreditSheet\BasicInfo\PersonalBasicInfo;
use CreditSheet\BasicInfo\CompanyBasicInfo;
use CreditSheet\BasicInfo\ArchivingPersonalBasicInfo;
use CreditSheet\BasicInfo\ArchivingCompanyBasicInfo;
use CreditSheet\CreditLine\CreditLineInfo;
use CreditSheet\CreditLine\ArchivingCreditLineInfo;
use CreditSheet\CashLoan\CashLoanInfo;
use CreditSheet\CashLoan\ArchivingCashLoanInfo;
defined('BASEPATH') OR exit('No direct script access allowed');

class CreditSheetFactory {
    public static function getInstance($targetId)
    {
        $CI = &get_instance();
        $CI->load->model('user/user_model');
        $CI->load->model('loan/credit_sheet_model');
        $CI->load->library('target_lib');

        $target = $CI->target_model->get_by(['id'=>$targetId]);
        $user = $CI->user_model->get_by(['id'=> $target->user_id ?? 0]);
        $creditSheetRecord = $CI->credit_sheet_model->get_by(['target_id' => $target->id ?? 0, 'status' => 1]);

        // 判斷類型
        $call_type = '';
        if (in_array($target->product_id, $CI->target_lib->get_product_id_by_tab(PRODUCT_TAB_ENTERPRISE)))
        {
            $call_type = USER_IS_COMPANY;
        }
        elseif (in_array($target->product_id, $CI->target_lib->get_product_id_by_tab(PRODUCT_TAB_INDIVIDUAL)))
        {
            $call_type = USER_NOT_COMPANY;
        }
        elseif (in_array($target->product_id, $CI->target_lib->get_product_id_by_tab(PRODUCT_TAB_HOME_LOAN)))
        {
            $call_type = USER_NOT_COMPANY;
        }
        else
        {
            return NULL;
        }

        if(isset($creditSheetRecord)) {
            // 已封存之個金授審表
            if ($call_type == USER_NOT_COMPANY)
            {
                $basicInfo = new ArchivingPersonalBasicInfo();
                $creditLineInfo = new ArchivingCreditLineInfo();
                $cashLoanInfo = new ArchivingCashLoanInfo();
            }
            // 已封存之企金授審表
            elseif ($call_type == USER_IS_COMPANY)
            {
                $basicInfo = new ArchivingCompanyBasicInfo();
                $creditLineInfo = new ArchivingCreditLineInfo();
                $cashLoanInfo = new ArchivingCashLoanInfo();
            }
        }else{
            // 未封存之個金授審表
            if ($call_type == USER_NOT_COMPANY)
            {
                $basicInfo = new PersonalBasicInfo();
                $creditLineInfo = new CreditLineInfo();
                $cashLoanInfo = new CashLoanInfo();
            }
            // 未封存之企金授審表
            elseif ($call_type == USER_IS_COMPANY)
            {
                $basicInfo = new CompanyBasicInfo();
                $creditLineInfo = new CreditLineInfo();
                $cashLoanInfo = new CashLoanInfo();
            }
        }

        $returnObject = NULL;
        try{
            if ($call_type == USER_NOT_COMPANY)
            {
                $returnObject = new PersonalCreditSheet($target, $user, $basicInfo, $creditLineInfo, $cashLoanInfo);
            }
            elseif ($call_type == USER_IS_COMPANY)
            {
                $returnObject = new CompanyCreditSheet($target, $user, $basicInfo, $creditLineInfo, $cashLoanInfo);
            }
        }catch (\InvalidArgumentException $e) {
            error_log("Invalid Argument Exception: ". $e->getMessage());
        }catch (\Exception $e) {
            error_log("Exception: ". $e->getMessage());
        }
        return $returnObject;
    }

}
