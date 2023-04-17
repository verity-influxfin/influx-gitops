<?php

class Withdraw_model extends MY_Model
{
	public $_table = 'withdraw';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $sys_check_list = array(
		0 =>	"未驗證",
		20 =>	"交易成功(系統自動)",
		21 =>	"需轉人工",
	);
	public $status_list  	= array(
		0 =>	"待出款",
		1 =>	"提領成功",
		2 =>	"出款中",
		3 =>	"取消",
	);
	public $investor_list  	= array(
		0 =>	"借款端",
		1 =>	"出借端",
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('transaction',TRUE);
 	}
	
	protected function before_data_c($data)
    {
        $data['created_at'] = $data['updated_at'] = time();
        $data['created_ip'] = $data['updated_ip'] = get_ip();
        return $data;
    }
	
	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

    public function get_auto_withdraw_list()
    {
        $sql = '
            SELECT `w`.`id` FROM `p2p_transaction`.`withdraw` `w`
            WHERE `w`.`status` = ' . WITHDRAW_STATUS_WAITING . '
            AND `w`.`frozen_id` > 0
            AND (`w`.`investor` = ' . USER_BORROWER . '
                AND `w`.`user_id` NOT IN (
                    SELECT DISTINCT `t`.`user_id` FROM `p2p_loan`.`targets` `t`
                    WHERE `t`.`status` = ' . TARGET_REPAYMENTING . '
                    AND (
                        `t`.`delay_days` > 0 OR
                        `t`.`id` IN (
                            SELECT `s`.`new_target_id` FROM `p2p_loan`.`subloan` `s`
                            WHERE `s`.`status` NOT IN (' . SUBLOAN_STATUS_CANCELED . ',' . SUBLOAN_STATUS_FAILED . ')
                        )
                    )
                )
                OR `w`.`investor` = ' . USER_INVESTOR . '
            )
        ';

        return $this->db->query($sql)->result_array();
    }
}
