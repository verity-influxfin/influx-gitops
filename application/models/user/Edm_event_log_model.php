<?php

class Edm_event_log_model extends MY_Model
{
	public $_table = 'edm_event_log';
	public $before_create 	= array( 'before_data_c' );
	public $before_update 	= array( 'before_data_u' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
	
	protected function before_data_u($data)
    {
        return $data;
    }

    /**
     * 獲取 符合上班族(還款中/已結案/未核准)之
     * 最後一筆更動案件狀態的資料
     * @return mixed
     */
    public function getUnsentUsersByObankEvent($event_id, $investor, $type) {
        $date = date('Y-m-d H:i:s');
        $this->_database
            ->select('user_id')
            ->from("`p2p_user`.`edm_event_log`")
            ->where(["edm_event_id" => $event_id, "type" => $type, "investor" => $investor]);

        $sub_query = $this->_database->get_compiled_select('', TRUE);
        $this->_database
            ->select('id, user_id, status, remark')
            ->from('`p2p_loan`.`targets`')
            ->where_in('product_id', [3, 4])
            ->group_start()
            ->where_in('status', [TARGET_REPAYMENTING, TARGET_REPAYMENTED])
            ->or_group_start()
            ->where('status', TARGET_FAIL)
            ->group_end()
            ->group_end()
            ->where_not_in('user_id', $sub_query, FALSE);
        $target_query = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('MAX(tc.id) as id')
            ->from('`p2p_log`.`targets_change_log` as `tc`')
            ->join("($target_query) as `t`", "`t`.`id` = `tc`.`target_id`")
            ->group_by('user_id');
        $latest_log_query = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('tc.id, tc.target_id, t.status, t.remark, t.user_id')
            ->from('`p2p_log`.`targets_change_log` as `tc`')
            ->join("($target_query) as `t`", "`t`.`id` = `tc`.`target_id`");
        $main_query = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('R.*')
            ->from("($main_query) as R")
            ->join("($latest_log_query) as `R2`", "`R`.`id` = `R2`.`id`");

        return $this->_database->get()->result_array();
    }
}
