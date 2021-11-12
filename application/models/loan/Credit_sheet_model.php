<?php

class Credit_sheet_model extends MY_Model
{
	public $_table = 'credit_sheet';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"已失效",
		1 =>	"有效"
	);

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
}
