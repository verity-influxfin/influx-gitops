<?php

class Loan_manager_role_model extends MY_Model
{
    public $_table = 'roles';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    private $status_list = array(
        0 => "停用",
        1 => "啟用",
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('loan_manager', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function get_role_list()
    {
        $data = array();
        $list = $this->get_all();
        if ($list) {
            foreach ($list as $key => $value) {
                $data[] = [
                    'id' => $value->id,
                    'name' => $value->name,
                    'permission' => $value->permission,
                    'creator_id' => $value->creator_id,
                    'status' => $this->status_list[$value->status],
                ];
            }
        }
        return $data;
    }

    public function get_role_info($role_id)
    {
        $data = array();
        $userInfo = $this->get($role_id);
        if ($userInfo) {
            $data = [
                'id' => $userInfo->id,
                'name' => $userInfo->name,
                'permission' => $userInfo->permission,
                'creator_id' => $userInfo->creator_id,
                'status' => $this->status_list[$userInfo->status],
            ];
        }
        return $data;
    }
}