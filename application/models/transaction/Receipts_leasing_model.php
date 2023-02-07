<?php

class Receipts_leasing_model extends MY_Model
{
    public $_table = 'receipts_leasing';
    public $before_create = array('before_data_c');

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('transaction', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

}