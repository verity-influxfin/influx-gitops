<?php

class Credit_model extends MY_Model
{
	public $_table = 'credits';
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

    // 撈取額度已過期的待簽約案件
    public function get_expired_signing_list()
    {
        $subquery = $this->db
            ->select('t.id')
            ->select('t.remark')
            ->select('t.user_id')
            ->from('p2p_loan.targets t')
            ->where('t.status', TARGET_WAITING_SIGNING)
            ->where('t.script_status', 0)
            ->where('t.order_id', 0)
            ->get_compiled_select(NULL, TRUE);

        return $this->db
            ->select('cs.target_id')
            ->select('t.*')
            ->from('p2p_loan.credits c')
            ->join('p2p_loan.credit_sheet cs', 'cs.credit_id=c.id')
            ->join("({$subquery}) t", 't.id=cs.target_id')
            ->where('cs.status', 1)
            ->where('c.expire_time <', time())
            ->get()
            ->result_array();
    }
}
