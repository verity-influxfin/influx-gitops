<?php

class Log_qrcode_import_collaboration_model extends MY_Model
{
	public $_table = 'qrcode_import_collaboration_log';
	public $before_create = array( 'before_data_c' );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = date("Y-m-d H:i:s");
        $data['created_ip'] = get_ip();
        return $data;
    }

    /**
     * 取得匯入合作對象的推薦碼報表紀錄
     * @param array $where
     * @return mixed
     */
    public function get_imported_log_list(array $where)
    {
        $this->_database->select('id, qrcode_collaboration_id, count, created_at')
            ->from('p2p_log.qrcode_import_collaboration_log');
        if ( ! empty($where))
            $this->_set_where([$where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);
        $this->_database
            ->select('qr.id, qc.collaborator, qr.count, qr.created_at')
            ->from('`p2p_user`.`qrcode_collaborator` AS `qc`')
            ->join("({$subQuery}) as `qr`", "`qr`.`qrcode_collaboration_id` = `qc`.`id`")
            ->order_by('qr.created_at', 'DESC');
        return $this->_database->get()->result_array();
    }
	
}
