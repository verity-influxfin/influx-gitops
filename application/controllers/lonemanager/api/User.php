<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . 'libraries/REST_Controller.php');

class User extends REST_Controller
{

    public $user_info;

    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();

        $nonAuthMethods = ['login'];
        if (!in_array($method, $nonAuthMethods)) {
            $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : '';
            $tokenData = AUTHORIZATION::validateLoneManagerToken($token);


            if (empty($tokenData->id) || $tokenData->expiry_time < time()) {
                $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            $this->user_info = $this->user_model->get($tokenData->id);
            if ($tokenData->auth_otp != $this->user_info->auth_otp) {
                $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
            }

            if ($this->user_info->block_status != 0) {
                $this->response(array('result' => 'ERROR', 'error' => BLOCK_USER));
            }

            $this->load->model('lonemanager/log_lonemanager_user_model');
            $this->log_lonemanager_user_model->insert([
                'admin_id' 		=> $this->login_info->id,
                'url'	 		=> $this->uri->uri_string(),
                'get_param'		=> json_encode($this->input->get(NULL, TRUE)),
                'post_param'	=> json_encode($this->input->post(NULL, TRUE)),
            ]);

            $this->user_info->expiry_time = $tokenData->expiry_time;
        }
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
        $this->load->model('lonemanager/Lone_manager_user_model');
        $user_info = $this->Lone_manager_user_model->get_by(['email' => $input['email']]);
        if ($user_info) {
            if (sha1($input['password']) == $user_info->password) {
                $token = (object)[
                    'id' => $user_info->id,
                    'email' => $user_info->email,
                    'role_id' => $user_info->role_id,
                    'auth_otp' => get_rand_token(),
                    'expiry_time' => time() + REQUEST_TOKEN_EXPIRY,
                ];
                $request_token = AUTHORIZATION::generateLoneManagerToken($token);
                $this->Lone_manager_user_model->update($user_info->id, array('auth_otp' => $token->auth_otp));

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

    public function editpw_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $data		= array();
        $user_id 	= $this->user_info->id;
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

        $this->load->library('sms_lib');
        $rs = $this->sms_lib->verify_code($user_info->phone,$data['code']);
        if(!$rs){
            $this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
        }

        $res = $this->user_model->update($user_info->id,array('password'=>$data['new_password']));
        if($res){
            $this->response(array('result' => 'SUCCESS'));
        }else{
            $this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
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

        $this->load->model('lonemanager/Log_lonemanager_userlogin_model');
        $this->Log_lonemanager_userlogin_model->insert($loginLog);
        $this->Log_lonemanager_userlogin_model->getCurrentInstance($loginLog);
    }
}
