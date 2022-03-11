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

    public function get_failed_target_credit_list(int $user_id, array $prod_subprod_id)
    {
        $this->db
            ->select('id')
            ->select('level')
            ->from('p2p_loan.credits')
            ->where('user_id', $user_id)
            ->where_in('status', 1);

        if ($prod_subprod_id)
        {
            $this->db->group_start();
            foreach ($prod_subprod_id as $key => $value)
            {
                $this->db
                    ->or_group_start()
                    ->where('product_id', $key)
                    ->where_in('sub_product_id', $value)
                    ->group_end();

            }
            $this->db->group_end();
        }

        return $this->db->get()->result_array();
    }
}
