<?php

class user_version_update_model extends MY_Model
{
    public $_table = 'user_version_update';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public $app_list  	= array(
        0 =>	"出借端",
        1 =>	"借款端",
        2 =>	"經銷商",
    );

    public $platform_list  	= array(
        0 =>	"ANDROID",
        1 =>	"IOS",
        2 =>	"PC",
    );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }
}
