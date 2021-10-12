<?php

class LoanTargetMappingMsgNo_model extends MY_Model
{
    public $_table = 'loan_target_mapping_msg_no';
    public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );

    public function __construct()
    {
        parent::__construct();
        $this->_database = $this->load->database('skbank', true);
    }

    protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

	protected function before_data_u($data)
    {
        $data['updated_at'] = time();
        $data['updated_ip'] = get_ip();
        return $data;
    }

	/**
	 * [getMaxSerialNumberByDate 找對應日期之新光交易序號後七碼最大值]
	 * @param  string $date          [日期(格式:YYYYMMDD)]
	 * @return string $serial_number [錯誤訊息/新光交易序號後七碼]
	 */
	public function getMaxSerialNumberByDate($date = '')
	{
		$serial_number = '';

		if($date && preg_match('/[0-9]{8}/',$date)){
			$this->db->select_max('loan_target_mapping_msg_no.serial_number')
			->from('p2p_skbank.loan_target_mapping_msg_no')
			->where(['date'=>$date]);
			$query = $this->db->get();
			$result = $query->result();

			$serial_number = isset($result[0]->serial_number) ? $result[0]->serial_number: '';
		}

		return $serial_number;
	}
}
