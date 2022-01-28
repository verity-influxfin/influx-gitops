<?php

class Transaction_model extends MY_Model
{
	public $_table = 'transactions';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"已刪除",
		1 =>	"正常",
		2 =>	"已結清",
	);
	
	public $passbook_status_list = array(
		0 =>	"未處理",
		1 =>	"入帳",
		2 =>	"處理中",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
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

    public function getDelayUserList(){
	    $list = [];
        $this->db->select('
                    user_from
                 ')
            ->from("p2p_transaction.transactions")
            ->where('source =', 93);
        $this->db->group_by('user_from');
        $query = $this->db->get()->result();
        foreach ($query as $k => $v){
            $list[] = $v->user_from;
        }
        return $list;
    }

    public function getDelayedAccountPayableByTarget($source_list=[], $user_from=0, $before_date="") {
	    return $this->_getDelayedAccountPayable('tra.target_id, tra.user_to, SUM(tra.amount) AS amount',
            0,$source_list,$user_from,$before_date,'target_id');
    }

    public function getDelayedAccountPayable($select="tra.target_id, tra.source, tra.investment_id, tra.user_to, SUM(tra.amount) AS amount",
                                             $target_id=0, $source_list=[], $user_from=0,
                                              $before_date='', $group_by='investment_id, source')
    {
        return $this->_getDelayedAccountPayable($select, $target_id, $source_list, $user_from,
            $before_date, $group_by);
    }

    /**
     * 取得案件的所有逾期應付款項，並依照 investment_id 進行分群
     * @param int $target_id
     * @param array $source_list: 欲撈取交易科目列表
     * @param string $before_date: 篩選該時間以前的資料之日期
     * @return mixed
     */
    private function _getDelayedAccountPayable($select="tra.*", $target_id=0, $source_list=[], $user_from=0,
                                             $before_date='', $group_by='investment_id, source') {
        if($before_date == "")
            $before_date = get_entering_date();

        $condition = [
            'source' => SOURCE_AR_PRINCIPAL,
            'status' => 1,
            'limit_date < ' => $before_date
        ];
        if($target_id)
            $condition['target_id'] = $target_id;
        if($user_from)
            $condition['user_from'] = $user_from;
        if(empty($source_list))
            $source_list = [SOURCE_AR_DAMAGE,SOURCE_AR_DELAYINTEREST,SOURCE_AR_PRINCIPAL,SOURCE_AR_INTEREST,SOURCE_AR_LAW_FEE];

        $this->db
            ->select('target_id, limit_date')
            ->from('`p2p_transaction`.`transactions`')
            ->where($condition)
            ->group_by('target_id')
            ->having('MIN(`limit_date`) = `limit_date`');
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select($select)
            ->from('`p2p_transaction`.`transactions` AS `tra`')
            ->join("($subquery) as `r_tra`", "`tra`.`target_id` = `r_tra`.`target_id`")
            ->where_in('source', $source_list)
            ->where('status', 1)
            ->where('`tra`.`limit_date` >= `r_tra`.`limit_date`')
            ->order_by('investment_id');
        if($group_by != "")
            $this->db->group_by($group_by);

        return $this->db->get()->result();
    }

	public function getDelayedTargetInfoList($transaction_where, $target_where){
		$transactions = $this->db->select('MIN(`limit_date`) as `min_limit_date`, `target_id`')
			->from("`p2p_transaction`.`transactions`")
			->where($transaction_where)
			->group_by('target_id');
		$subquery = $this->db->get_compiled_select('', TRUE);
		$this->db
			->select('ta.user_id, ta.loan_date, ta.product_id, ta.sub_product_id, ta.target_no, ta.delay_days, ta.script_status, t.*')
			->from('`p2p_loan`.`targets` AS `ta`')
			->where($target_where)
			->join("($subquery) as `t`", "`ta`.`id` = `t`.`target_id`");
		$subquery2 = $this->db->get_compiled_select('', TRUE);
		$this->db
			->select('i.target_id, i.user_id as investor_userid, r.user_id, r.loan_date, r.product_id, r.sub_product_id, r.target_no, r.min_limit_date, r.delay_days, r.script_status')
			->from('`p2p_loan`.`investments` AS `i`')
			->where('status', 3)
			->where('transfer_status <', '2')
			->join("($subquery2) as `r`", "`i`.`target_id` = `r`.`target_id`")
			->order_by('i.target_id', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}

    // 新增內帳交易紀錄，並回傳ID
    public function insert_get_id($data)
    {
        $this->db->set($data)->insert("`p2p_transaction`.`{$this->_table}`");
        return $this->db->insert_id();
    }

    /**
     * 依target ID、狀態取得明細數量
     * @param $target_id
     * @param int|array $status : 狀態
     * @return int|mixed
     */
    public function get_count_by_target_id($target_id, $status)
    {
        $this->db
            ->select('COUNT(1) AS count')
            ->from('p2p_transaction.' . $this->_table)
            ->where('target_id', $target_id);

        if (is_numeric($status))
        {
            $this->db->where('status', $status);
        }
        elseif (is_array($status))
        {
            $this->db->where_in('status', $status);
        }

        $result = $this->db->get()->first_row('array');

        return $result['count'] ?? 0;
    }
}
