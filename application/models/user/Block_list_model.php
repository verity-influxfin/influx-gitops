<?php

class Block_list_model extends MY_Model
{
    public $_table = 'p2p_user.block_list';

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('default', TRUE);
    }

    public function insert_block_list($user_id, $status, $create_by, $last_by, $block_rules, $remark, $block_time, $created_admin, $created_ip, $updated_admin, $updated_ip)
    {
        $data = array(
            'user_id' => $user_id,
            'status' => $status,
            'created_by' => $create_by,
            'last_by' => $last_by,
            'block_rules' => $block_rules,
            'remark' => $remark,
            'expire_time' => time(),
            'block_time' => $block_time,
            'created_at' => time(),
            'created_admin' => $created_admin,
            'created_ip' => $created_ip,
            'updated_at' => time(),
            'updated_admin' => $updated_admin,
            'updated_ip' => $updated_ip,
        );

        $this->db->insert($this->_table, $data);
    }

    public function update_block_list($user_id, $data)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 1);
        $result = $this->db->update($this->_table, $data);
        $this->db->limit(10);
        var_dump($result);

    }

    public function get_data($data)
    {
        $query = $this->db->get('mytable', 10, 20);
    }
}