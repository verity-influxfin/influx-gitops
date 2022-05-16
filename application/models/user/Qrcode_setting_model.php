<?php

class Qrcode_setting_model extends MY_Model
{
	public $_table = 'qrcode_setting';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );

    // 一般方案 qrcode_setting 的 alias
	public $generalCaseAliasName = 'general';

	// 特約商方案 qrcode_setting 的 alias
	public $appointedCaseAliasName = 'appointed';

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


}
