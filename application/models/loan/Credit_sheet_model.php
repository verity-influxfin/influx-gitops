<?php
use CreditSheet\CreditSheetBase;

class Credit_sheet_model extends MY_Model
{
	public $_table = 'credit_sheet';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
    public $status_list = CreditSheetBase::STATUS_LIST;

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('loan',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] 	= $data['updated_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] 	= $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function getCreditSheetWithTarget($target_where, $creditSheet_where) {
        $this->_database->select('id, user_id, instalment, amount, loan_amount, interest_rate, loan_date, reason, contract_id')
            ->from("`p2p_loan`.`targets`");
        $this->_set_where([0 => $target_where]);
        $subquery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('`t`.*, `ct`.`format_id` AS `format_id`')
            ->from('`p2p_loan`.`contracts` AS `ct`')
            ->join("($subquery) as `t`", "`t`.`contract_id` = `ct`.`id`")
            ->order_by('created_at', 'desc');
        $subquery2 = $this->_database->get_compiled_select('', TRUE);

        $creditSheetCondition = [];
        $creditSheet_where = array_walk($creditSheet_where, function ($val, $key) use (&$creditSheetCondition) {
            $creditSheetCondition['`cs`.'.$key] = $val;
        });
        $this->_database
            ->select('*')
            ->from('`p2p_loan`.`credit_sheet` AS `cs`')
            ->where($creditSheetCondition)
            ->join("($subquery2) as `t`", "`t`.`id` = `cs`.`target_id`")
            ->order_by('cs.created_at', 'desc');
        return $this->_database->get()->result();
    }

    public function insertWhenEmpty($insertData, $condition) {
        $this->db
            ->select('*')
            ->from('`p2p_loan`.`credit_sheet`')
            ->where($condition);
        $subQuery = $this->db->get_compiled_select('', TRUE);
        $keys = array_keys($insertData);
        $values = array_values($insertData);
        $sql = 'INSERT INTO `p2p_loan`.`credit_sheet` ('.
            implode(', ', $keys).') SELECT '.
            implode(', ', array_fill(0, count($values), '?')).
            " WHERE NOT EXISTS ($subQuery)";

        return $this->db->query($sql, $values);
    }

    public function getCreditSheetsByUserId($userId, $statusList=[], $productIdList=[], $creditFilterTime='') {
	    $this->db->select('*')
            ->from('`p2p_loan`.`targets`')
            ->where('user_id', $userId);
	    if(!empty($productIdList))
            $this->db->where_in('product_id', $productIdList);
        $subQuery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('cs.*')
            ->from('`p2p_loan`.`credit_sheet` AS `cs`')
            ->where_in('cs.status', $statusList)
            ->join("($subQuery) as `t`", "`t`.`id` = `cs`.`target_id`")
            ->order_by('cs.created_at', 'desc');
        if(!empty($creditFilterTime))
            $this->db->where('cs.updated_at >= ', $creditFilterTime);
        return $this->db->get()->result();
    }

    /**
     * 取得最近的可用核可額度
     * @param $userId
     * @param array $productIdList
     * @return mixed
     */
    public function getLastAvailableCredit($userId, array $productIdList=[]) {
        // 注意：這邊不能直接取用 amount，如需額度和利率需透過 credit_lib 的 get_credit 取得
        $this->db->select('`c`.`product_id`, `c`.`sub_product_id`, `c`.`level`, `c`.`points`,
                `c`.`created_at`, `c`.`expire_time`, `t`.`id` AS `target_id`, `t`.`instalment`')
            ->from('`p2p_loan`.`credits` AS `c`')
            ->join("`p2p_loan`.`credit_sheet` AS `cs`", "`c`.`id` = `cs`.`credit_id`")
            ->join("`p2p_loan`.`targets` AS `t`", "`t`.`id` = `cs`.`target_id`")
            ->where('`c`.`user_id`', $userId)
            ->where('`c`.`status`', 1)
            ->order_by('`c`.`expire_time`', 'DESC');
        if(!empty($productIdList))
            $this->db->where_in('`c`.`product_id`', $productIdList);
        return $this->db->get()->result();
    }

    public function get_credit_list($user_id)
    {
        $this->db->select('*')
            ->from('`p2p_loan`.`credits`')
            ->where('user_id', $user_id);
        $sub_query = $this->db->get_compiled_select('', TRUE);

        $this->db->distinct()
            ->select('`c`.`user_id`, `c`.`product_id`, `c`.`sub_product_id`, `c`.`instalment`, `c`.`level`,
                `c`.`points`, `c`.`amount`, `c`.`status`, `c`.`expire_time`, `c`.`created_at`')
            ->from('`p2p_loan`.`credit_sheet` AS `cs`')
            ->join("({$sub_query}) AS `c`", '`c`.`id` = `cs`.`credit_id`', 'right')
            ->join('`p2p_loan`.`targets` AS `t`', '`t`.`user_id` = `c`.`user_id`', 'right')
            ->where('`t`.`user_id`', $user_id)
            ->group_start()
            ->where('`cs`.`status`', CreditSheetBase::STATUS_APPROVED)
            ->or_where('`c`.`level` > ', 10)
            ->group_end();

        return $this->db->get()->result();
    }
}
