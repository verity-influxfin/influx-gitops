<?php

class Loan_manager_target_model extends MY_Model
{
    public $_table = 'roles';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');
    private $status_list = array(
        0 => "待核可",
        1 => "待簽約",
        2 => "待驗證",
        3 => "待出借",
        4 => "待放款（結標）",
        5 => "還款中",
        8 => "已取消",
        9 => "申請失敗",
        10 => "已結案",

        20 => "待報價 (分期)",
        21 => "待簽約 (分期)",
        22 => "待驗證 (分期)",
        23 => "待審批 (分期)",
        24 => "待債轉上架 (分期)",

        30 => "待核可 (外匯車)",
        31 => "待簽約 (外匯車)",
    );
    public $sub_list = array(
        0 => "無",
        1 => "轉貸中",
        2 => "轉貸成功",
        3 => "申請提還",
        4 => "提還成功",
        5 => "已通知出貨",
        6 => "出貨鑑賞期",
        7 => "退貨中",
        8 => "產轉案件",
        9 => "待二審",
        10 => "二審過件",

        20 => "交易成功(系統自動)",
        21 => "需轉人工",

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

    public function get_target_list($select, $where = [],  $offset = 0, $limit = false)
    {
        is_array($select) ? $select = implode(',', $select) : '*';

        $this->db->select($select,false)
            ->from('p2p_user.users as user');
        $this->db->join(
            'p2p_loan.targets as target',
            'target.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.debt_processing as processing',
            'processing.user_id = user.id',
            'left'
        );
        $this->db->join(
            'loan_manager.push_data as push',
            'push.user_id = user.id'
        );
        $this->db->join(
            'loan_manager.users as admin',
            'processing.admin_id = admin.id'
        );

        if(count($where) > 0){
            $this->db->where($where);
        }

        $this->db->order_by("processing.updated_at",'desc');
        $limit ? $this->db->limit($limit, $offset) : '';
        $query = $this->db->get();
        return $query->result();
    }

    public function get_delayUser_list($select,  $offset = 0, $limit = false)
    {
        $this->db->select($select)->from('p2p_user.users as user');
        $this->db->join(
            'p2p_loan.targets as target',
            'target.user_id = user.id'
        );
        $this->db->where('target.delay', 1);
        $this->db->group_by('user.id');
        $limit ? $this->db->limit($limit, $offset) : '';
        $query = $this->db->get();
        return $query->result();
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