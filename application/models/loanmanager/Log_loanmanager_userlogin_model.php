<?php

class Log_loanmanager_userlogin_model extends MY_Model
{
    public $_table = 'user_login_log';
    public $before_create = array('before_data_c');

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('loan_manager', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();

        $this->load->library('user_agent');
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $data['client'] = json_encode([
            'agent' => $this->agent->agent_string(),
            'device_id' => isset($this->agent->device_id) ? $this->agent->device_id : null
        ]);

        return $data;
    }

    public function getCurrentInstance($data)
    {
        $convertedData = $this->before_data_c($data);
        $convertedData["client"] = json_decode($convertedData["client"]);
        return $convertedData;
    }
}
