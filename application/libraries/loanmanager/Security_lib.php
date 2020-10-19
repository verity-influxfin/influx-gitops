<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Security_lib
{
    function __construct()
    {
        $this->CI = &get_instance();
    }

    public function logParam($tokenData, $method){
        $noLog = $this->CI->config->item('loanmanager')['noLog'];
        if (!in_array($method, $noLog)) {
            $post = in_array($method, ['editpw']) ? '********' : $this->CI->input->post(NULL, TRUE);
            $this->CI->load->model('loanmanager/log_loanmanager_user_model');
            $this->CI->log_loanmanager_user_model->insert([
                'user_id' 		=> $tokenData->id,
                'url'	 		=> $this->CI->uri->uri_string(),
                'get_param'		=> json_encode($this->CI->input->get(NULL, TRUE)),
                'post_param'	=> json_encode($post),
            ]);
        }
    }
}