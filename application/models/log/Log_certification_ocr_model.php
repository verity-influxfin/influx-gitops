<?php

class Log_certification_ocr_model extends MY_Model
{
    public $_table = 'certification_ocr_log';
    public $before_create = ['before_data_c'];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('log', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = get_ip();
        return $data;
    }
}