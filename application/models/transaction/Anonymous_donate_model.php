<?php

class Anonymous_donate_model extends MY_Model
{
    public $_table = 'anonymous_donate';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];

    const MATCH_STATUS_DEFAULT = 0;
    const MATCH_STATUS_AMOUNT = 1;
    const MATCH_STATUS_SEARCH = 2;

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('transaction', TRUE);
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
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function get_donates($data)
    {
        $this->_database
            ->select('id, charity_anonymous_id')
            ->from('`p2p_transaction`.`anonymous_donate`')
            ->where([
                'last5' => $data['last5'],
                'amount' => $data['amount'],
            ]);

        return $this->_database->get()->result_array();
    }
}
