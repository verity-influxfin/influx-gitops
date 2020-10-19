<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Auth_lib
{
    private $CI;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('loanmanager/loan_manager_user_model');
    }

    public function authToken(){
        $request_token = isset($this->CI->input->request_headers()['request_token']) ? $this->CI->input->request_headers()['request_token'] : '';
        $this->CI->load->library('loanmanager/role_lib');
        $this->CI->load->library('loanmanager/security_lib');

        $this->CI->config->load('loanmanager',TRUE);
        $nonAuthMethods = $this->CI->config->item('loanmanager')['nonAuthMethods'];

        $method = $this->CI->router->method;
        $class = $this->CI->router->class;
        if (!in_array($method, $nonAuthMethods)) {
            $tokenData = AUTHORIZATION::validateLoanManagerToken($request_token);
            if (empty($tokenData->id) || $tokenData->expiry_time < time()) {
                $this->CI->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            $this->CI->user_info = $this->CI->loan_manager_user_model->get($tokenData->id);
            if ($tokenData->auth_otp != $this->CI->user_info->auth_otp) {
                $this->CI->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            $this->CI->security_lib->logParam($tokenData, $method);
            $this->CI->user_info->id = $tokenData->id;
            $this->CI->user_info->expiry_time = $tokenData->expiry_time;

            $permission = $this->CI->role_lib->permission($tokenData->accessList, $method, $class);
            if($permission){
                $this->CI->response(array('result' => 'ERROR', 'error' => PERMISSION_DENY));
            }
        }
    }
}