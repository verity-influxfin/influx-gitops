<?php

class Investment_model extends MY_Model
{
	public $_table = 'investments';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"待付款",
		1 =>	"待結標(款項已移至待交易)",
		2 =>	"待放款(已結標)",
		3 =>	"持有中",
		8 =>	"已取消",
		9 =>	"已流標",
		10 =>	"已結案",
	);

	public $bidding_status_list   = array(
		0 =>	"全部",
		2 =>	"已付款",
	);

	public $transfer_status_list   = array(
		0 =>	"",
		1 =>	"債轉申請中",
		2 =>	"已轉出",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('loan',TRUE);
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

	/**
	 * 因應已下標頁面的資料邏輯，取得所有投資紀錄
	 * @param array $where:
	 * @param string $target_no
	 * @return mixed
	 */
	public function get_bidding_investment($where, $target_no="") {
		$this->db->select("*");
		foreach($where as $key => $v) {
			if(is_array($v))
				$this->db->where_in($key, $v);
			else
				$this->db->where($key, $v);
		}
		$subquery = $this->db->get_compiled_select('p2p_loan.investments', TRUE);

		$this->db
			->select("FROM_UNIXTIME(`i`.`created_at`, '%Y-%m-%d %H:%i:%s') AS created_at, (CASE WHEN `i`.status=2 THEN FROM_UNIXTIME(`i`.`updated_at`, '%Y-%m-%d %H:%i:%s') ELSE '-' END) AS updated_at,
				`t`.target_no, `i`.user_id, `i`.amount, (CASE WHEN `i`.status=2 THEN `i`.loan_amount ELSE '-' END) AS loan_amount,
				 `t`.amount as total_amount, (CASE WHEN `i`.aiBidding=1 THEN 'v' ELSE '-' END) AS aiBidding,
				  (CASE WHEN `i`.frozen_id!=0 THEN `i`.frozen_id ELSE '-' END) AS frozen_id, `i`.status")
			->from('`p2p_loan.targets` as `t`')
			->join("($subquery) as `i`", "`t`.`id` = `i`.`target_id`")
			->order_by('`i`.`created_at`', 'ASC');
		if(!empty($target_no))
			$this->db->where('target_no like ', $target_no);
		return $this->db->get()->result();
	}

	public function getLegalCollectionInvestment($target_where, $investment_where) {
		$this->db->select("*");
		foreach($investment_where as $key => $v) {
			if(is_array($v))
				$this->db->where_in($key, $v);
			else
				$this->db->where($key, $v);
		}
		$subquery = $this->db->get_compiled_select('p2p_loan.investments', TRUE);

		foreach($target_where as $key => $v) {
			if(is_array($v))
				$this->db->where_in($key, $v);
			else
				$this->db->where($key, $v);
		}

		$this->db
			->select("")
			->from('`p2p_loan.targets` as `t`')
			->join("($subquery) as `i`", "`t`.`id` = `i`.`target_id`")
			->order_by('`i`.`created_at`', 'ASC');

		return $this->db->get()->result();
	}
}
