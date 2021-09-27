<?php

class Virtual_passbook_model extends MY_Model
{
	public $_table = 'virtual_passbook';
	public $before_create = array( 'before_data_c' );
//	public $before_update = array( 'before_data_u' );

	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

	/**
	 * 取得虛擬帳號目前的可用餘額
	 * @param string $virtual_account: 虛擬帳號
	 * @return int
	 */
	public function get_virtual_acc_balance($virtual_account) {
		$this->db
			->select('SUM(amount) as balance, virtual_account')
			->from('`p2p_transaction.virtual_passbook`')
			->where('virtual_account = ', $virtual_account)
			->group_by('virtual_account');

		$query = $this->db->get();
		$result = $query->row();
		return isset($result)?intval($result->balance):0;
	}

    public function getVirtualAccFunds($virtualAccountList){
        $this->db
            ->select('virtual_account, SUM(amount) AS total')
            ->from("`p2p_transaction`.`virtual_passbook`")
            ->where_in('virtual_account', $virtualAccountList)
            ->group_by('virtual_account');
        $subquery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('virtual_account, SUM(amount) AS frozen')
            ->from("`p2p_transaction`.`frozen_amount`")
            ->where_in('virtual_account', $virtualAccountList)
            ->where('status', 1)
            ->group_by('virtual_account');
        $subquery2 = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('vp.virtual_account, vp.total, IFNULL(fa.frozen, 0), vp.total - IFNULL(fa.frozen, 0) AS balance')
            ->from("($subquery) as `vp`")
            ->join("($subquery2) as `fa`", "`vp`.`virtual_account` = `fa`.`virtual_account`", "left");

        return $this->db->get()->result_array();
    }
}
