<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class Role extends REST_Controller
{

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loanmanager/loan_manager_role_model');

        $this->load->library('loanmanager/auth_lib');
        $this->auth_lib->authToken();
    }

    public function list_get()
    {
        $res = $this->loan_manager_role_model->get_role_list();
        if($res){
            $this->response([
                'result' => 'SUCCESS',
                'data' => [
                    'list' => $res,
                ]
            ]);
        }else{
            $this->response(array('result' => 'ERROR','error' => LINK_FAIL ));
        }
    }
}
