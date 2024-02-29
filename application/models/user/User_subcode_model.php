<?php

class user_subcode_model extends MY_Model
{
    public $_table = 'user_subcode';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    public $status_list = array(
        PROMOTE_STATUS_DISABLED => '停用',
        PROMOTE_STATUS_AVAILABLE => '啟用'
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
        $data['updated_ip'] = get_ip();
        return $data;
    }

    /**
     * 取得 subcode 的推薦碼資訊列表
     * @param $master_qrcode_id : 推薦主碼 id
     * @param array $subcode_conditions
     * @param array $qrcode_conditions
     * @return mixed
     */
    public function get_subcode_list($master_qrcode_id, array $subcode_conditions = [], array $qrcode_conditions = [])
    {
        $this->_database->select('*')
            ->from('p2p_user.user_subcode as us');
        if ( ! empty($subcode_conditions))
        {
            $this->_set_where([0 => $subcode_conditions]);
        }

        if (is_array($master_qrcode_id) && ! empty($master_qrcode_id))
        {
            $this->_database->where_in('master_user_qrcode_id', $master_qrcode_id);
        }
        else if ( ! empty($master_qrcode_id))
        {
            $this->_database->where('master_user_qrcode_id', $master_qrcode_id);
        }

        $user_subcode = $this->_database->get_compiled_select('', TRUE);

        $this->_database->select('r.id, r.alias, r.registered_id, r.master_user_qrcode_id, uq.id AS user_qrcode_id, uq.promote_code, uq.status, uq.start_time, uq.end_time')
            ->select('r.status AS user_subcode_status')
            ->select('r.sub_status AS user_subcode_sub_status')
            ->select('uq.user_id')
            ->from('p2p_user.user_qrcode as uq')
            ->join("({$user_subcode}) as `r`", "`r`.`user_qrcode_id` = `uq`.`id`");

        if ( ! empty($qrcode_conditions))
        {
            $qrcode_conditions = array_combine(addPrefixToArray(array_keys($qrcode_conditions), "uq."), array_values($qrcode_conditions));
            $this->_set_where([$qrcode_conditions]);
        }
        return $this->_database->get()->result_array();
    }

    public function get_subcode_by_id($ids) {
        $this->db->select('*')
            ->from('p2p_user.user_subcode')
            ->where_in('user_qrcode_id', $ids);
        return $this->db->get()->result_array();
    }

    public function get_id_by_user_id($user_id)
    {
        $subquery = $this->db
            ->select('id')
            ->from('p2p_user.user_qrcode')
            ->where('user_id', $user_id)
            ->where('status !=', PROMOTE_STATUS_DISABLED)
            ->get_compiled_select('', TRUE);

        return $this->db->select('us.id')
            ->from('p2p_user.user_subcode us')
            ->join("({$subquery}) a", 'a.id=us.user_qrcode_id')
            ->where('us.status', PROMOTE_SUBCODE_STATUS_AVAILABLE)
            ->get()
            ->result_array();
    }

    public function get_info_by_user_id($user_id, $where = [])
    {
        $subquery = $this->db
            ->select('id')
            ->from('p2p_user.user_qrcode')
            ->where('user_id', $user_id)
            ->where('status !=', PROMOTE_STATUS_DISABLED)
            ->get_compiled_select('', TRUE);

        $this->_database
            ->select('us.*')
            ->from('p2p_user.user_subcode us')
            ->join("({$subquery}) a", 'a.id=us.user_qrcode_id')
            ->order_by('created_at', 'DESC');
        if ( ! empty($where))
        {
            $this->_set_where([0 => $where]);
        }

        return $this->_database->get()->result_array();
    }

    public function get_user_qrcode_info_by_subcode_id($subcode_id)
    {
        $subquery = $this->db->select('us.user_qrcode_id')
            ->from('p2p_user.user_subcode us')
            ->where('us.id', $subcode_id)
            ->get_compiled_select();

        return $this->db->select(['uq.*', 'users.company_status'])
            ->from('p2p_user.user_qrcode uq')
            ->join("({$subquery}) a", 'a.user_qrcode_id=uq.id')
            ->join('p2p_user.users', 'users.id=uq.user_id')
            ->get()
            ->first_row('array');
    }
}
