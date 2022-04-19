<?php

class Transfer_investment_model extends MY_Model
{
	public $_table = 'transfer_investment';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );

	
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

    public function get_principle_list($from_user_id_list = [], $to_user_id_list = [], $product_id_list = [], $is_group=TRUE)
    {
        $this->db
            ->select('tr.transfer_date AS tx_date')
            ->from('`p2p_loan`.`transfers` AS tr')
            ->join('`p2p_loan`.`targets` AS t', 't.id = tr.target_id')
            ->where_in('tr.status', [TRANSFER_STATUS_FINISHED]);

        if($is_group)
        {
            $this->db->select('SUM(tr.principal) AS amount')
                ->group_by('tr.transfer_date');
        }else{
            $this->db->select('tr.principal AS amount');
        }
        if ( ! empty($from_user_id_list))
        {
            $this->db->join('`p2p_loan`.`investments` AS i', 'i.id = tr.investment_id')
                ->where_in('i.user_id', $from_user_id_list);
        }
        if ( ! empty($to_user_id_list))
        {
            $this->db->join('`p2p_loan`.`investments` AS i', 'i.id = tr.new_investment')
                ->where_in('i.user_id', $to_user_id_list);
        }
        if ( ! empty($product_id_list))
            $this->db->where_in('t.product_id', $product_id_list);

        return $this->db->get()->result_array();
    }
}
