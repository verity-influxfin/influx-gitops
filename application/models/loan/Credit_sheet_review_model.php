<?php

class Credit_sheet_review_model extends MY_Model
{
	public $_table = 'credit_sheet_review';
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

    public function get_product_by_id($credit_sheet_review_id)
    {
        $result = $this->db->select('t.product_id')
            ->from('p2p_loan.credit_sheet_review csr')
            ->from('p2p_loan.credit_sheet cs')
            ->from('p2p_loan.targets t')
            ->where('csr.credit_sheet_id=cs.id')
            ->where('cs.target_id=t.id')
            ->where('cs.id', $credit_sheet_review_id)
            ->get()
            ->first_row('array');

        return $result['product_id'];
    }

    public function has_info_by_target_id($target_id, $group = NULL): bool
    {
        $this->db
            ->select('1')
            ->from('p2p_loan.credit_sheet_review csr')
            ->join('p2p_loan.credit_sheet cs', 'cs.id = csr.credit_sheet_id AND cs.target_id = ' . $target_id);

        if (isset($group))
        {
            $this->db->where('group', $group);
        }

        $result = $this->db->get()->first_row('array');

        return ! empty($result);
    }
}
