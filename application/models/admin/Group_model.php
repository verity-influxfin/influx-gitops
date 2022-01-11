<?php

class Group_model extends MY_Model
{
    public $_table = 'groups';
    public $before_create = array('before_data_c');
    public $before_update = array('before_data_u');

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('admin', TRUE);
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
     * 依部門ID撈取「部門(Table: group)」和「部門權限(Table: group_permission)」資料
     * @param int $id : 部門ID
     * @return array
     */
    public function get_data_by_id(int $id)
    {
        $group_permission_data = $this->db
            ->select('gp.model_key,gp.submodel_key,gp.action_type')
            ->from('p2p_admin.group_permission gp')
            ->where('gp.group_id', $id)
            ->get()
            ->result_array();

        $group_data = $this->db
            ->select('g.id,g.division,g.department,g.position')
            ->from('p2p_admin.groups g')
            ->where('g.id', $id)
            ->get()
            ->first_row('array');

        return array_merge($group_data, ['permission' => $group_permission_data]);
    }

    /**
     * 依部門ID撈取「部門權限」資料
     * @param int $group_id : 部門ID
     * @return array
     */
    public function get_permission_by_group_id(int $group_id)
    {
        $group_permission_data = $this->db
            ->select('gp.model_key,gp.submodel_key,gp.action_type')
            ->from('p2p_admin.group_permission gp')
            ->where('gp.group_id', $group_id)
            ->get()
            ->result_array();

        return $group_permission_data;
    }

    /**
     * 新增「部門權限管理」表單資料
     * @param array $group_data : 部門資料
     * @param array $group_permission_data : 部門權限資料
     * @return bool
     */
    public function insert_form_data(array $group_data, array $group_permission_data)
    {
        try
        {
            $this->db->trans_begin();

            if ( ! is_array($group_data) || empty($group_data) || ! is_array($group_permission_data) || empty($group_permission_data))
            {
                throw new Exception('Invalid data.');
            }

            // 新增部門
            $this->db->insert('p2p_admin.groups', $group_data);
            $group_id = $this->db->insert_id();
            if ( ! $group_id)
            {
                throw new Exception('Invalid group.');
            }

            // 新增部門權限
            $group_permission_data = $this->_check_permission($group_id, $group_permission_data);
            if (empty($group_permission_data))
            {
                throw new Exception('Invalid permission data.');
            }
            $this->db->where('group_id', $group_id)->delete('p2p_admin.group_permission');
            $this->db->insert_batch('p2p_admin.group_permission', $group_permission_data);

            $this->db->trans_commit();
            return TRUE;
        }
        catch (Exception $e)
        {
            $this->db->trans_rollback();
            return FALSE;
        }
    }

    /**
     * 更新「部門權限管理」表單資料
     * @param int $group_id : 部門ID
     * @param array $group_data : 部門資料
     * @param array $group_permission_data : 部門權限資料
     * @return bool
     */
    public function update_form_data(int $group_id, array $group_data, array $group_permission_data)
    {
        try
        {
            $this->db->trans_begin();

            if ( ! $group_id)
            {
                throw new Exception('Invalid group.');
            }

            if ( ! is_array($group_data) || empty($group_data) || ! is_array($group_permission_data) || empty($group_permission_data))
            {
                throw new Exception('Invalid data.');
            }

            // 更新部門
            $this->db->where('id', $group_id)->update('p2p_admin.groups', $group_data);

            // 更新部門權限
            $group_permission_data = $this->_check_permission($group_id, $group_permission_data);
            if (empty($group_permission_data))
            {
                throw new Exception('Invalid permission data.');
            }
            $this->db->where('group_id', $group_id)->delete('p2p_admin.group_permission');
            $this->db->insert_batch('p2p_admin.group_permission', $group_permission_data);

            $this->db->trans_commit();
            return TRUE;
        }
        catch (Exception $e)
        {
            $this->db->trans_rollback();
            return FALSE;
        }
    }

    /**
     * 整理「部門權限管理」表單資料
     * @param int $group_id : 部門ID(groups.id=group_permission.group_id)
     * @param array $group_permission_data : 部門權限資料
     * [
     *   'Target' => [
     *     'index' => 1,
     *     'waiting_evaluation' => 2,
     *     ...
     *   ],
     *   ...
     * ]
     * @return array
     */
    private function _check_permission(int $group_id, array $group_permission_data)
    {
        $data = [];
        foreach ($group_permission_data as $key => $value)
        {
            if ( ! is_array($value))
            {
                continue;
            }

            foreach ($value as $key2 => $value2)
            {
                if ( ! isset($value2['action']))
                {
                    continue;
                }

                $data[] = [
                    'group_id' => $group_id,
                    'model_key' => $key,
                    'submodel_key' => $key2,
                    'action_type' => array_sum($value2['action']),
                ];
            }
        }

        return $data;
    }

    public function get_group_list()
    {
        $subquery = $this->db
            ->select("division,department,GROUP_CONCAT(CONCAT('\"',id,'\":\"',position,'\"')) AS position")
            ->group_by('division,department')
            ->get_compiled_select('p2p_admin.groups', TRUE);

        $this->db
            ->select("g.division,GROUP_CONCAT(DISTINCT concat('{\"name\":\"',a.department,'\",\"position\":{',a.position,'}}')) as department")
            ->from('p2p_admin.groups g')
            ->join("($subquery) a", 'a.division=g.division')
            ->group_by('g.division');

        return $this->db->get()->result_array();
    }
}
