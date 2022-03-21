<?php

class Payment_model extends MY_Model
{
	public $_table = 'payments';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"未處理",
		1 =>	"已處理",
		2 =>	"處理中",
		3 =>	"需人工",
		4 =>	"已退款",
		5 =>	"不明資金",
		6 =>	"已處理",
        # iseeus 861
        7 =>    "已退款"
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

    public function get_userid_by_payment($payment_id) {
        $this->db
            ->select('*')
            ->from('`p2p_transaction`.`payments`')
            ->where_in('id', $payment_id);
        $sub_query = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('`va`.`user_id` as `user_id`')
            ->from('`p2p_user`.`virtual_account` AS `va`')
            ->join("($sub_query) as `p`", "`p`.`virtual_account` = `va`.`virtual_account`");
        return $this->db->get()->result();
    }

    public function get_chraity_withdraw_info($user_bank_acc, $sdate = '', $edate = '')
    {
        $this->_database->select('')
            ->where('bank_acc', $user_bank_acc)
            ->where('status', 1)
            ->from('payments');

        if ( ! empty($sdate))
        {
            $this->_database->where('tx_datetime >= ', "{$sdate} 00:00:00");
        }
        if ( ! empty($edate))
        {
            $this->_database->where('tx_datetime <= ', "{$edate} 23:59:59");
        }

        $payments = $this->_database->get()->result_array();
        $fees = $this->_get_fee_from_same_spec($payments);
        return $this->_parser_fees($fees);
    }

    private function _get_fee_from_same_spec($payments)
    {
        // TODO
    }

    private function _parser_fees($fees)
    {
        // TODO
    }
}
