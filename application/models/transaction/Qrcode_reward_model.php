<?php

class Qrcode_reward_model extends MY_Model
{
	public $_table = 'qrcode_reward';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	'停用',
		1 =>	'有效'
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
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
     * 取得推薦碼結帳的相關資訊
     * @param array $qrcode_where
     * @return mixed
     */
    public function getSettlementRewardList(array $reward_where=[], array $qrcode_where=[]) {
        $this->_database->select('*')
            ->from("`p2p_transaction`.`qrcode_reward`");
        if(!empty($reward_where))
            $this->_set_where([$reward_where]);
        $subQuery = $this->_database->get_compiled_select('', TRUE);

        $this->_database
            ->select('qr.*, uq.user_id, uq.alias, uq.promote_code, uq.settings')
            ->from('`p2p_user`.`user_qrcode` AS `uq`')
            ->join("($subQuery) as `qr`", "`qr`.`user_qrcode_id` = `uq`.`id`", 'right');
        if(!empty($qrcode_where)) {
            $qrcode_where = array_combine(addPrefixToArray(array_keys($qrcode_where), "uq."), array_values($qrcode_where));
            $this->_set_where([$qrcode_where]);
        }

        return $this->_database->get()->result_array();
    }
}
