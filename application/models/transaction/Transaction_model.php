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

	public function getDelayTargetInfoList($transaction_where, $target_where){
		$transactions = $this->db->select('MIN(`limit_date`) as `min_limit_date`, `target_id`')
			->from("`p2p_transaction`.`transactions`")
			->where($transaction_where)
			->group_by('target_id');
		$subquery = $this->db->get_compiled_select('', TRUE);
		$this->db
			->select('ta.user_id, ta.loan_date, ta.product_id, ta.sub_product_id, ta.target_no, ta.delay_days, t.*')
			->from('`p2p_loan`.`targets` AS `ta`')
			->where($target_where)
			->join("($subquery) as `t`", "`ta`.`id` = `t`.`target_id`");
		$subquery2 = $this->db->get_compiled_select('', TRUE);
		$this->db
			->select('i.target_id, i.user_id as investor_userid, r.user_id, r.loan_date, r.product_id, r.sub_product_id, r.target_no, r.min_limit_date, r.delay_days')
			->from('`p2p_loan`.`investments` AS `i`')
			->where('status', 3)
			->where('transfer_status <', '2')
			->join("($subquery2) as `r`", "`i`.`target_id` = `r`.`target_id`")
			->order_by('i.target_id', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}


}
