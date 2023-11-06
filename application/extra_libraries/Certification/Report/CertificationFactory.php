<?php
namespace Certification\Report;
defined('BASEPATH') OR exit('No direct script access allowed');

class CertificationFactory {
    public static function get_instance($targetId): ?CompanyReport
    {
        $CI = &get_instance();
        $CI->load->model('user/user_model');
        $CI->load->model('loan/target_model');

        $target = $CI->target_model->get_by(['id'=>$targetId]);
        $user = $CI->user_model->get_by(['id'=> $target->user_id ?? 0]);

        $CI->load->model('user/user_certification_report_model');

        $return_object = NULL;
        try
        {
            $return_object = new CompanyReport($target, $user);
        }
        catch ( \InvalidArgumentException $e )
        {
            error_log("Invalid Argument Exception: ". $e->getMessage());
        }
        catch ( \Exception $e )
        {
            error_log("Exception: ". $e->getMessage());
        }
        return $return_object;
    }

}
