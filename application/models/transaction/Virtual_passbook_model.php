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

}
