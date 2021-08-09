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
}
