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

    public function has_info_by_target_id($target_id, $group = NULL)
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
