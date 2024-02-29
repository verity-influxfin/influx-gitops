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
    public function check_valid_charge_person($tax_id = '', $user_id = '')
    {
        $charge_person = false;

        $judicial_person = $this->judicial_person_model->get_by([
            'tax_id' => $tax_id,
            'status !=' => 2
        ]);
        if ($judicial_person) {
            return $judicial_person;
        }

        if ( ! empty($user_id))
        {
            $this->load->model('user/user_certification_model');
            $cert_governmentauthorities_info = $this->user_certification_model->get_by([
                'user_id' => $user_id,
                'certification_id' => CERTIFICATION_GOVERNMENTAUTHORITIES,
                'status' => [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_FAILED, CERTIFICATION_STATUS_PENDING_TO_REVIEW]
            ]);
            if ( ! empty($cert_governmentauthorities_info))
            {
                return $cert_governmentauthorities_info;
            }
        }

        return false;
    }
}
