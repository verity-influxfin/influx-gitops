<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class User extends REST_Controller
{

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('loanmanager/auth_lib');
        $this->auth_lib->authToken();
    }


    public function login_post()
    {

        $input = $this->input->post(NULL, TRUE);
        $fields = ['email', 'password'];
        $device_id = isset($input['device_id']) && $input['device_id'] ? $input['device_id'] : null;
        $location = isset($input['location']) ? trim($input['location']) : '';
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        if (strlen($input['password']) < PASSWORD_LENGTH || strlen($input['password']) > PASSWORD_LENGTH_MAX) {
            $this->response(array('result' => 'ERROR', 'error' => PASSWORD_LENGTH_ERROR));
        }
        $user_info = $this->loan_manager_user_model->get_by(['email' => $input['email']]);
        if ($user_info) {
            if (sha1($input['password']) == $user_info->password) {
                $accessList = $this->role_lib->accessList($user_info->role_id);
                $token = (object)[
                    'id' => $user_info->id,
                    'email' => $user_info->email,
                    'accessList' => $accessList,
                    'auth_otp' => get_rand_token(),
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                ];
                $request_token = AUTHORIZATION::generateLoanManagerToken($token);
                $this->loan_manager_user_model->update($user_info->id, array('auth_otp' => $token->auth_otp));

                $this->insert_login_log($input['email'],  1, $user_info->id, $device_id, $location);

                $this->response([
                    'result' => 'SUCCESS',
                    'data' => [
                        'token' => $request_token,
                        'expiry_time' => $token->expiry_time
                    ]
                ]);
            } else {
                $this->insert_login_log($input['email'],  0, $user_info->id, $device_id, $location);
                $this->response([
                    'result' => 'ERROR',
                    'error' => PASSWORD_ERROR
                ]);
            }
        } else {
            $this->response(array('result' => 'ERROR', 'error' => USER_NOT_EXIST));
        }
    }

    public function info_get()
    {
        $res = $this->loan_manager_user_model->get_user_info($this->user_info->id);
        if($res){
            $this->response([
                'result' => 'SUCCESS',
                'data' => [
                    'info' => $res,
                ]
            ]);
        }else{
            $this->response(array('result' => 'ERROR','error' => LINK_FAIL ));
        }
    }

    public function editpw_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $data		= array();
        $fields 	= ['password','new_password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }else{
                $data[$field] = $input[$field];
            }
        }

        if(strlen($input['new_password']) < PASSWORD_LENGTH || strlen($input['new_password'])> PASSWORD_LENGTH_MAX ){
            $this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
        }

        $user_info = $this->user_info;
        if(sha1($data['password'])!=$user_info->password){
            $this->response(array('result' => 'ERROR','error' => PASSWORD_ERROR ));
        }

        $res = $this->loan_manager_user_model->update($user_info->id,array('password'=>$data['new_password']));
        if($res){
            $this->response(array('result' => 'SUCCESS'));
        }else{
            $this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
        }
    }


    public function add_post()
    {
        $input = $this->input->post(NULL, TRUE);
        $fields = ['email', 'role_id', 'name', 'phone'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
                $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
            }
        }

        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $this->response(array('result' => 'ERROR', 'error' => INVALID_EMAIL_FORMAT));
        }

        if(!preg_match('/^09[0-9]{2}[0-9]{6}$/', $input['phone'])){
            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
        }

        $mail_exist = $this->loan_manager_user_model->get_by('email',$input['email']);
        if ($mail_exist) {
            $this->response(array('result' => 'ERROR','error' => MAIL_EXIST ));
        }

        $user_exist = $this->loan_manager_user_model->get_by('phone',$input['phone']);
        if ($user_exist) {
            $this->response(array('result' => 'ERROR','error' => USER_EXIST ));
        }


        $res = $this->loan_manager_user_model->insert([
            'email' => $input['email'],
            'password' => sha1($input['email'] . 'influxfin' . $input['phone']),
            'role_id' => $input['role_id'],
            'name' => $input['name'],
            'phone' => $input['phone'],
            'status' => 2,
        ]);
        if($res){
            $this->response([
                'result' => 'SUCCESS',
            ]);
        }else{
            $this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
        }
    }

    public function list_get()
    {
        $res = $this->loan_manager_user_model->get_user_list();
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

    private function insert_login_log($account = '', $status = 0, $user_id = 0, $device_id = null, $location = '')
    {
        $this->load->library('user_agent');
        $this->agent->device_id = $device_id;
        $loginLog = [
            'account' => $account,
            'user_id' => intval($user_id),
            'location' => $location,
            'status' => intval($status)
        ];

        $this->load->model('loanmanager/Log_loanmanager_userlogin_model');
        $this->Log_loanmanager_userlogin_model->insert($loginLog);
        $this->Log_loanmanager_userlogin_model->getCurrentInstance($loginLog);
    }
}
