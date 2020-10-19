<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Role_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
    }

    public function accessList($role_id){
        $accessList = [];
        $apiList = $this->CI->config->item('loanmanager')['apiList'];

        $this->CI->load->model('loanmanager/loan_manager_role_model');
        $roleInfo = $this->CI->loan_manager_role_model->get_role_info($role_id);
        foreach ($apiList as $apiKey => $apiValue) {
            if ($roleInfo['permission'] == '*' || in_array($apiKey ,explode(',' ,$roleInfo['permission']))) {
                $accessList[] = $apiValue;
            }
        }
        return implode(',', $accessList);
    }

    public function permission($accessList ,$method ,$class){
        return in_array($method . ucfirst($class), explode(',' ,$accessList));
    }
}