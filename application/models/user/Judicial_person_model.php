<?php

class Judicial_person_model extends MY_Model
{
	public $_table = 'judicial_person';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"審核中",
		1 =>	"審核通過",
		2 =>	"審核失敗"
	);

	public $cooperation_list   = array(
		0 =>	"未開通",
		1 =>	"已開通",
		2 =>	"審核中"
	);

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('default',TRUE);
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

    // 判斷該統編是否已存在且被歸戶成功
    public function check_valid_charge_person($tax_id='')
    {
        $charge_person = false;

        $judicial_person = $this->judicial_person_model->get_by([
            'tax_id' => $tax_id,
            'status !=' => 2
        ]);
        if ($judicial_person) {
            return $judicial_person;
        }

        return false;
    }
}
