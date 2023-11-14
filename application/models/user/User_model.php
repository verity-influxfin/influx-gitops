<?php

class User_model extends MY_Model
{
	public $_table = 'users';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	'已停權',
		1 =>	'正常'
	);
	
	public $block_status_list   = array(
		0 =>	'正常',
		1 =>	'人工停權',
		2 =>	'系統暫時鎖定',
        3 =>    '系統鎖定',
	);
	
	public $token_fields  = array(
		'id',
		'name',
		'picture',
		'nickname',
		'sex',
		'id_number',
		'phone',
		'my_promote_code',
		'created_at',
		'updated_at'
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
		$data['password'] 	= sha1($data['password']);
        ! isset($data['user_id']) ?: $data['user_id'] = sha1($data['user_id']);
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
		if(isset($data['password']) && !empty($data['password'])){
			$data['password'] 	= sha1($data['password']);
		}
        if (isset($data['user_id']) && ! empty($data['user_id']))
        {
            $data['user_id'] = sha1($data['user_id']);
        }
		
		if(isset($data['transaction_password']) && !empty($data['transaction_password'])){
			$data['transaction_password'] 	= sha1($data['transaction_password']);
		}
		
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

	public function getStudents($filters, $offset = 0, $limit = 20)
	{
        $this->db->select('users.*')
                 ->from ('p2p_user.users')
                 ->join('p2p_user.user_meta as meta', 'users.id = meta.user_id');

        foreach ($filters as $filter) {
            if ($filter[0] == 'status') {
                $this->db->where('users.status ' . $filter[1], $filter[2]);
            }
            if ($filter[0] == 'created_at') {
                $this->db->where('users.created_at ' . $filter[1], $filter[2]);
            }
            if ($filter[0] == 'meta_key') {
                $this->db->where('meta.meta_key ' . $filter[1], $filter[2]);
            }
            if ($filter[0] == 'promote_code') {
                if ($filter[1] == "!=") {
                    $this->db->where('users.promote_code ' . $filter[1], $filter[2]);
                } elseif ($filter[1] == "in") {
                    $this->db->where_in('users.promote_code', $filter[2]);
                } elseif ($filter[1] == "not in") {
                    $this->db->where_not_in('users.promote_code', $filter[2]);
                } else {
                    $this->db->where('users.promote_code', $filter[2]);
                }
            }
        }

        $this->db->limit($limit, $offset);

            $query = $this->db->get();

            return $query->result();
    }

    public function getUsersBy($criteria, $column_not_null=[], $offset = 0, $limit = 1){
        $this->db->select('*')
                 ->from('p2p_user.users as user');
         if (isset($criteria['certification_id'])) {
                     $this->db->join('p2p_user.user_certification as certification', 'user.id = certification.user_id')
                     ->where('certification.certification_id', $criteria['certification_id'])
                     ->where('certification.status =', 1);
            }
            if(! empty($column_not_null)){
                foreach($column_not_null as $value){
                    $this->db->where('user.'.$value.' != ', '');
                }
            }
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
        return $query->result();
    }

    public function get_ids($exclude_ids)
    {
        if (empty($exclude_ids))
        {
            return [];
        }
        $result_arr = $this->db->select('id')
            ->from('p2p_user.users')
            ->where_not_in('id', $exclude_ids)
            ->get()
            ->result_array();
        return array_column($result_arr, 'id');
    }

    public function getDelayedTargetByInvestor($user_id) {
        $this->db->select('*')
            ->from("`p2p_loan`.`investments`")
            ->where('user_id', $user_id)
            ->where('status', 3);
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('t.*')
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("($subquery) as `i`", "`t`.`id` = `i`.`target_id`")
            ->where('t.delay_days > ', GRACE_PERIOD);

        return $this->db->get()->result();
    }

    /**
     * 取得指定時間範圍內申貸成功的推薦用戶數量
     * @param string $promote_code: 推薦碼
     * @param int $start_date: 搜尋起始時間點
     * @param int $end_date: 搜尋結束時間點
     * @return mixed
     */
    public function getPromotedCount(string $promote_code, int $start_date, int $end_date) {
        $this->db->select('id')
            ->from("`p2p_user`.`users`")
            ->where('promote_code', $promote_code)
            ->where('created_at >=', $start_date)
            ->where('created_at <=', $end_date);
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('u.id, COUNT(t.id) as count')
            ->from('`p2p_loan`.`targets` AS `t`')
            ->join("($subquery) as `u`", "`u`.`id` = `t`.`user_id`")
            ->where_in('`t`.`status`', [5, 10])
            ->group_by('t.user_id');
        return $this->db->get()->result();
    }

    /**
     * 撈取總會員筆數
     * @return int
     */
    public function get_member_count()
    {
        $result = $this->db
            ->select('COUNT(1) AS `member_count`')
            ->from('`p2p_user`.`users`')
            ->get()
            ->first_row('array');

        return (int) ($result['member_count'] ?? 0);
    }

    // 取得指定日子加入的新會員
    public function get_new_members_at_day(DateTimeInterface $date)
    {
        $unixtime_query = sprintf('FROM_UNIXTIME(created_at, \'%s\')', $date->format('Y-m-d'));

        $query = $this->db->select('COUNT(id) AS amount')
            ->select($unixtime_query . ' AS date')
            ->from('p2p_user.users')
            ->where([
                'created_at >=' => $date->getTimestamp(),
                'created_at <' => $date->modify('+1 day')->getTimestamp(),
            ])
            ->group_by($unixtime_query)
            ->get()
            ->first_row('array');

        return $query['amount'] ?? 0;
    }

    public function get_company_status_by_ids(array $id_list)
    {
        return $this->db->select(['id', 'company_status'])
            ->from('p2p_user.users')
            ->where_in('id', $id_list)
            ->get()
            ->result_array();
    }

    /**
     * 取得相同手機號碼的公司資料
     * @param $phone : 手機號碼
     * @return mixed
     */
    public function get_same_company_responsible_user_by_phone($phone)
    {
        $sub_query = $this->db
            ->select('user_id')
            ->where('status', CERTIFICATION_STATUS_SUCCEED)
            ->where('certification_id', CERTIFICATION_GOVERNMENTAUTHORITIES)
            ->get_compiled_select('user_certification', TRUE);

        return $this->db
            ->select(['u.id', 'u.name', 'u.id_number AS tax'])
            ->from('users u')
            ->join("({$sub_query}) a", 'a.user_id=u.id')
            ->where('u.phone', $phone)
            ->where('u.company_status', USER_IS_COMPANY)
            ->where('u.status', 1)
            ->get()
            ->result_array();
    }

    /**
     * 以統編取得使用者 ID
     * @param $tax_id : 統編
     * @return mixed
     */
    public function get_exit_judicial_person($tax_id)
    {
        $query = $this->db->query("
            SELECT `jp`.`user_id` FROM `p2p_user`.`judicial_person` `jp` WHERE `jp`.`tax_id`='{$tax_id}' AND `jp`.`status`!=2
	        UNION
	        SELECT `u`.`id` AS `user_id` FROM `p2p_user`.`users` `u` WHERE `u`.`id_number`='{$tax_id}' AND `u`.`company_status`=1
	    ");
        return $query->first_row('array');
    }

    /**
     * 檢查帳號是否已存在
     * @param $user_id : 帳號 (users.user_id)
     * @param $tax_id : 統編 (users.id_number)
     * @return bool
     */
    public function check_user_id_exist($user_id, $tax_id)
    {
        $this->db
            ->select('1')
            ->from('users')
            ->where('user_id', sha1($user_id))
            ->where('id_number !=', $tax_id);
        return ! empty($this->db->get()->first_row());
    }

    /**
     * 依條件取得使用者 ID
     * @param array $where
     * @return mixed
     */
    public function get_id_by_condition(array $where = [])
    {
        $this->_database
            ->select('id')
            ->from('p2p_user.users');
        if ( ! empty($where))
        {
            $this->_set_where([0 => $where]);
        }

        return $this->_database->get()->result_array();
    }

    public function get_user_name_by_id($id)
    {
        $info = $this->db
            ->select('name')
            ->from('p2p_user.users')
            ->where('id', $id)
            ->get()
            ->first_row('array');

        return $info['name'] ?? '';
    }

    public function get_company_list_with_identity_status($phone)
    {
        $sub_query = $this->db->select('id')->where('phone', $phone)->where('company_status', USER_IS_COMPANY)->get_compiled_select('p2p_user.users', TRUE);

        $sub_query = $this->db
            ->select(['MAX(id)', 'user_id', 'status'])
            ->where("user_id IN ({$sub_query})")
            ->where('certification_id', CERTIFICATION_GOVERNMENTAUTHORITIES)
            ->where('status !=', CERTIFICATION_STATUS_FAILED)
            ->group_by('user_id')
            ->get_compiled_select('p2p_user.user_certification', TRUE);
        
        return $this->db
            ->select('id')
            ->select('name')
            ->select('id_number as tax')
            ->select('a.status')
            ->from('p2p_user.users')
            ->join("($sub_query) as a", 'a.user_id=users.id', 'LEFT')
            ->where('phone', $phone)
            ->where('company_status', USER_IS_COMPANY)
            ->get()
            ->result_array();
    }

    public function get_name_by_id($id)
    {
        $result = $this->db->select('name')->from('p2p_user.users')->where('id', $id)
            ->get()->first_row('array');
        return $result['name'] ?? '';
    }
}
