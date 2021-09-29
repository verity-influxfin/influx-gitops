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
}
