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
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    } 	
	
	protected function before_data_u($data)
    {
		if(isset($data['password']) && !empty($data['password'])){
			$data['password'] 	= sha1($data['password']);
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
}
