<?php

class Edm_event_model extends MY_Model
{
	public $_table = 'edm_event';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = date('Y-m-d H:i:s');
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_ip'] = get_ip();
        return $data;
    }

    /**
     * 獲取可觸發的事件
     * @return mixed
     */
    public function getCanTriggerEvent() {
	    $date = date('Y-m-d H:i:s');
        $this->db
            ->select('*')
            ->from("`p2p_user`.`edm_event`")
            ->where("status", 1)
            ->group_start()
            ->where("`triggered_at` IS NULL")
            ->or_where("DATE_ADD(`triggered_at`, INTERVAL `minute_freq` MINUTE) <=", $date)
            ->group_end();
        return $this->db->get()->result_array();
    }
}
