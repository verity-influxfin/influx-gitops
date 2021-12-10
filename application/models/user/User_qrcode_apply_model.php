<?php

class user_qrcode_apply_model extends MY_Model
{
	public $_table = 'user_qrcode_apply';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
    public $status_list   = array(
        0 =>	'待制定合約',
        1 =>	'審核成功',
        2 =>	'審核失敗',
        3 =>	'已送出審核'
    );
	
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
     * 取得 qrcode 申請的審核列表
     * @param array $user_where
     * @param array $qrcode_where
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function get_review_list(array $user_where=[], array $qrcode_where=[], int $limit=0, int $offset=0) {
        $this->_database->select('*')
            ->from("`p2p_user`.`user_qrcode`");
        if(!empty($user_where))
            $this->_set_where([$user_where]);
        $sub_query = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('u.user_id, u.alias, uqa.id as qrcode_apply_id, uqa.user_qrcode_id, uqa.contract_content, uqa.status, uqa.contract_format_id, uqa.created_at, uqa.draw_up_at')
            ->from('`p2p_user`.`user_qrcode_apply` AS `uqa`')
            ->join("($sub_query) as `u`", "`u`.`id` = `uqa`.`user_qrcode_id`");
        if($limit) {
            $offset = max(0, $offset);
            $limit = max(0, $limit);
            $this->_database->limit($limit, $offset);
        }
        if(!empty($qrcode_where)) {
            $qrcode_where = array_combine(addPrefixToArray(array_keys($qrcode_where), "uqa."), array_values($qrcode_where));
            $this->_set_where([$qrcode_where]);
        }

        return $this->_database->get()->result_array();
    }

}
