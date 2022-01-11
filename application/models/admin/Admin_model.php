<?php

class Admin_model extends MY_Model
{
	public $_table = 'admins';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"停權",
		1 =>	"正常"
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('admin',TRUE);
 	}
	
	protected function before_data_c($data)
    {
		$data['password'] 	= sha1($data['password']);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
		if(isset($data['password'])){
			$data['password'] 	= sha1($data['password']);
		}
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }
	
	public function get_name_list(){
		$data 	= array();
		$list 	= $this->get_all();
		if($list){
			foreach($list as $key => $value){
				if($value->status ==1 ){
					$data[$value->id] = $value->name;
				}else{
					$data[$value->id] = $value->name.'(停權)';
				}
			}
		}
		return $data;
	}
	
	public function get_qrcode_list(){
		$data 	= array();
		$list 	= $this->get_all();
		if($list){
			foreach($list as $key => $value){
				$data[$value->my_promote_code] = $value->id;
			}
		}
		return $data;
	}

    /**
     * 撈取「後台管理員(Table: admins)」與「所屬部門(Table: groups)」資料
     * @param array $where : 搜尋條件
     * @return mixed
     */
    public function get_admin_group_data(array $where)
    {
        $this->db
            ->select('a.id,a.email,a.name,a.permission_status,g.division,g.department,g.position')
            ->from('p2p_admin.admins a')
            ->join('p2p_admin.groups g', 'g.id=a.group_id')
            ->where($where);

        return $this->db->get()->result_array();
    }

    /**
     * 依部門ID撈取「管理員(Table: admins)」和「管理員權限(Table: admin_permission)」資料
     * @param int $admin_id
     * @return array
     */
    public function get_data_by_id(int $admin_id)
    {
        try
        {
            if ( ! $admin_id)
            {
                throw new Exception();
            }

            $admin_permission_data = $this->db
                ->select('ap.model_key,ap.submodel_key,ap.action_type')
                ->from('p2p_admin.admin_permission ap')
                ->where('ap.admin_id', $admin_id)
                ->get()
                ->result_array();

            $admin_data = $this->db
                ->select('a.id,a.name,a.email,a.group_id,g.division,g.department,g.position')
                ->from('p2p_admin.admins a')
                ->join('p2p_admin.groups g', 'g.id=a.group_id')
                ->where('a.id', $admin_id)
                ->get()
                ->first_row('array');

            return array_merge($admin_data, ['permission' => $admin_permission_data]);
        }
        catch (Exception $e)
        {
            return [];
        }
    }

    // 撈取尚無「部門(Table: admins)」的管理員
    public function get_no_group_list()
    {
        $this->db
            ->select('a.id,a.email,a.name')
            ->from('p2p_admin.admins a')
            ->where('NOT EXISTS (SELECT 1 FROM p2p_admin.groups g WHERE g.id=a.group_id)', '', FALSE)
            ->order_by('a.email');

        return $this->db->get()->result_array();
    }

    /**
     * 更新「人員權限管理」表單資料
     * @param int $admin_id : 管理員ID
     * @param array $admin_data : 管理員資料
     * @param array $admin_permission_data : 管理員權限資料
     * @return bool
     */
    public function update_permission_data(int $admin_id, array $admin_data, array $admin_permission_data)
    {
        try
        {
            $this->db->trans_begin();

            if ( ! $admin_id || empty($admin_data))
            {
                throw new Exception();
            }

            // 更新人員
            $this->db->where('id', $admin_id)->update('p2p_admin.admins', $admin_data);

            // 更新人員權限
            $admin_permission_data = $this->check_permission($admin_id, $admin_permission_data);
            $this->db->where('admin_id', $admin_id)->delete('p2p_admin.admin_permission');
            if (!empty($admin_permission_data))
            {
                $this->db->insert_batch('p2p_admin.admin_permission', $admin_permission_data);
            }

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
     * 整理「人員權限管理」表單資料
     * @param int $admin_id : 管理員ID
     * @param array $admin_permission_data : 管理員權限資料
     * @return array
     */
    private function check_permission(int $admin_id, array $admin_permission_data)
    {
        $data = [];
        foreach ($admin_permission_data as $key => $value)
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
                    'admin_id' => $admin_id,
                    'model_key' => $key,
                    'submodel_key' => $key2,
                    'action_type' => array_sum($value2['action']),
                    'class' => $value2['class'] ?? '',
                    'method' => $value2['method'] ?? '',
                ];
            }
        }

        return $data;
    }

    /**
     * 撈取權限資料
     * @param int $admin_id : 管理員ID
     * @return array
     */
    public function get_permission(int $admin_id)
    {
        try
        {
            if ( ! $admin_id)
            {
                throw new Exception();
            }

            $data = $this->db
                ->select('ap.model_key,ap.submodel_key,ap.action_type,ap.class,ap.method')
                ->from('p2p_admin.admin_permission ap')
                ->where('ap.admin_id', $admin_id)
                ->get()
                ->result_array();

            return $data;
        }
        catch (Exception $e)
        {
            return [];
        }
    }
}