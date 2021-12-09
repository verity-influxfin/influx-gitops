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
    public function getFirstOpenCountByPromoteCode($promoteCode,$startTime,$endTime): array
    {
	    if(empty($promoteCode))
	        return [];

        $this->db
            ->select('data1 as promote_code, created_at')
            ->from('`behavion`.`user_behavior`')
            ->where('action', 'first_open');

        if(is_array($promoteCode))
            $this->db->where_in('data1', $promoteCode);
        else
            $this->db->where('data1', $promoteCode);

        $subQuery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('r.promote_code, COUNT(r.promote_code) as count')
            ->from('`p2p_user`.`user_qrcode` AS `uq`')
            ->join("($subQuery) as `r`", "`r`.`promote_code` = `uq`.`promote_code`")
            ->where('`uq`.`status`', 1)
            ->where('`r`.`created_at` >= `uq`.`start_time`')
            ->where('`r`.`created_at` < `uq`.`end_time`')
            ->group_by('`r`.`promote_code`');
        if($startTime!='')
            $this->db->where("`r`.`created_at` >=",  $startTime);
        if($endTime!='')
            $this->db->where("`r`.`created_at` <=",  $endTime);

        return $this->db->get()->result_array();
    }
	
}
