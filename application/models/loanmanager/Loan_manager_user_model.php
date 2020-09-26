<?php

class Loan_manager_user_model extends MY_Model
{
    public $_table = 'users';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    private $status_list = array(
        0 => "停權",
        1 => "正常",
        2 => "未啟用"
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('loan_manager', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['password'] = sha1($data['password']);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        if (isset($data['password'])) {
            $data['password'] = sha1($data['password']);
        }
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function get_user_list()
    {
        $data = array();
        $list = $this->get_all();
        if ($list) {
            foreach ($list as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'email' => $value->email,
                    'role_id' => $value->role_id,
                    'name' => $value->name,
                    'phone' => $value->phone,
                    'status' => $this->status_list[$value->status],
                ];
            }
        }
        return $data;
    }

    public function get_user_info($user_id)
    {
        $data = array();
        $userInfo = $this->get($user_id);
        if ($userInfo) {
            $data = [
                'id' => $userInfo->id,
                'email' => $userInfo->email,
                'role_id' => $userInfo->role_id,
                'name' => $userInfo->name,
                'phone' => $userInfo->phone,
                'status' => $this->status_list[$userInfo->status],
            ];
        }
        return $data;
    }
}