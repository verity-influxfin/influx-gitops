<?php
namespace CreditSheet;
use CreditSheet\BasicInfo\PersonalBasicInfo;
use CreditSheet\BasicInfo\ArchivingPersonalBasicInfo;
defined('BASEPATH') OR exit('No direct script access allowed');

class CreditSheetFactory {
    public static function getInstance($targetId) {
        $CI = &get_instance();
        $CI->load->model('user/user_model');
        $target = $CI->target_model->get_by(['id'=>$targetId]);
        $user = $CI->user_model->get_by(['id'=> $target->user_id ?? 0]);

        if($target->status != 0) {
            // 已封存之個金授審表
            $basicInfo = new ArchivingPersonalBasicInfo($target);
        }else{
            // 未封存之個金授審表
            $basicInfo = new PersonalBasicInfo($target);
        }
        return new PersonalCreditSheet($basicInfo);
    }

}
