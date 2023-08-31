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
     * @param $startTime
     * @param $endTime
     * @param bool $group_by_code
     * @return array
     */
    public function getFirstOpenCountByPromoteCode($promoteCode, $startTime, $endTime, $is_group_by_code = TRUE): array
    {
	    if(empty($promoteCode))
	        return [];

        $this->db
            ->select('data1 as promote_code, created_at, device_id')
            ->from('`behavion`.`user_behavior`')
            ->where('action', 'first_open');

        if(is_array($promoteCode))
            $this->db->where_in('data1', $promoteCode);
        else
            $this->db->where('data1', $promoteCode);

        $subQuery = $this->db->get_compiled_select('', TRUE);
        $this->db
            ->select('uq.id AS user_qrcode_id, r.promote_code, r.created_at, r.device_id')
            ->from('`p2p_user`.`user_qrcode` AS `uq`')
            ->join("($subQuery) as `r`", "`r`.`promote_code` = `uq`.`promote_code`")
            ->where('`r`.`created_at` >= `uq`.`start_time`')
            ->where('`r`.`created_at` < `uq`.`end_time`');

        if ($is_group_by_code)
        {
            $this->db->select('COUNT(r.promote_code) as count')
                ->group_by('`r`.`promote_code`');
        }
        else
        {
            $this->db->select('1 as count');
        }

        if($startTime!='')
            $this->db->where("`r`.`created_at` >=",  $startTime);
        if($endTime!='')
            $this->db->where("`r`.`created_at` <",  $endTime);

        return $this->db->get()->result_array();
    }

}
