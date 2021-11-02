<?php

class User_behavior_model extends MY_Model
{
	public $_table = 'user_behavior';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('behavion',TRUE);

 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['created_ip'] = get_ip();
		return $data;
    }

    /**
     * 依照推薦碼取得首次登入數量
     * @param $promoteCode
     * @return array
     */
    public function getFirstOpenCountByPromoteCode($promoteCode): array
    {
	    if(empty($promoteCode))
	        return [];

        $this->db
            ->select('data1 as promote_code, COUNT(id) as count')
            ->from('`behavion`.`user_behavior`')
            ->where('action', 'first_open')
            ->group_by('data1');

        if(is_array($promoteCode))
            $this->db->where_in('data1', $promoteCode);
        else
            $this->db->where('data1', $promoteCode);

        return $this->db->get()->result_array();
    }
	
}
