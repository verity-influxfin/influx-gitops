<?php

class Charity_institution_model extends MY_Model
{
    public $_table = 'charity_institution';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public $status_list = array(
        0 => '停用',
        1 => '有效'
    );

    public $TaiwanUnivHospitalAliasName = 'NTUH';

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
        $data['updated_ip'] = get_ip();
        return $data;
    }

    /**
     * 撈取「啟用」的慈善機構
     * @return mixed
     */
    public function get_withdraw_list()
    {
        $this->db
            ->select('jp.user_id')
            ->select('ci.virtual_account')
            ->from("p2p_user.{$this->_table} ci")
            ->join('p2p_user.judicial_person jp', 'jp.id=ci.judicial_person_id')
            ->where('ci.status', CHARITY_INSTITUTION_STATUS_AVAILABLE);

        return $this->db->get()->result_array();
    }
}
