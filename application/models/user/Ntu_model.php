<?php
// 台大慈善記者會
// 捐款名單權重列表

class Ntu_model extends MY_Model
{
    public $_table = 'ntu_press_conference';
    public $before_create = ['before_data_c'];
    public $before_update = ['before_data_u'];

    // 顯示狀態
    public $status_list = [
        0 => '未顯示',
        1 => '已顯示'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function get_list(int $user_id)
    {
        $this->db
            ->select('n.id')
            ->select('n.user_id')
            ->select('n.amount')
            ->select('n.status')
            ->select('n.weight')
            ->select('n.data_source')
            ->select('n.created_at')
            ->select('n.updated_at')
            ->select('n.type')
            ->select('u.name AS user_name')
            ->select('a.name AS admin')
            ->from("{$this->_table} n")
            ->join('users u', 'u.id=n.user_id')
            ->join('p2p_admin.admins a', 'a.id=n.updated_admin_id');

        // 投資人ID
        if ( ! empty($user_id))
        {
            $this->db->where('n.user_id', $user_id);
        }

        return $this->db->get()->result_array();
    }

    public function get_info(int $id)
    {
        $this->db
            ->select('n.id')
            ->select('n.user_id')
            ->select('n.amount')
            ->select('n.weight')
            ->select('n.status')
            ->select('n.data_source')
            ->select('n.type')
            ->select('u.name AS user_name')
            ->from("{$this->_table} n")
            ->join('users u', 'u.id=n.user_id')
            ->where('n.id', $id);

        return $this->db->get()->first_row('array');
    }

    public function get_list_bigger_than($max_id)
    {
        $this->db
            ->select('n.id')
            ->select('n.amount')
            ->select('n.weight')
            ->select('n.type')
            ->select('n.created_at')
            ->select('n.updated_at')
            ->select('u.name AS user_name')
            ->from("{$this->_table} n")
            ->join('users u', 'u.id=n.user_id')
            ->where('n.id>', $max_id);

        return $this->db->get()->result_array();
    }
}