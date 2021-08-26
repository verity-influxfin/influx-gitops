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
        $data['created_at'] 	= $data['updated_at'] = time();
        $data['created_ip'] 	= $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function getCreditSheetWithTarget($target_where, $creditSheet_where) {
        $this->db->select('id, user_id, instalment, amount, interest_rate, reason')
            ->from("`p2p_loan`.`targets`")
            ->where($target_where);
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('*')
            ->from('`p2p_loan`.`credit_sheet` AS `cs`')
            ->where($creditSheet_where)
            ->join("($subquery) as `t`", "`t`.`id` = `cs`.`target_id`")
            ->order_by('cs.created_at', 'desc');
        return $this->db->get()->result();
    }
}
