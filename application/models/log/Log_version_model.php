<?php

class Log_version_model extends MY_Model
{
	public $_table        = 'version_log';
	public $before_create = array( 'before_data_c' );

    public $app_name_list   = array(
        0 =>	"APP_INVEST",
        1 =>	"APP_BORROW"
    );
    public $platform_list   = array(
        0 =>	"ANDROID",
        1 =>	"IOS",
        2 =>	"PC"
    );

	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('log',TRUE);
 	}

	protected function before_data_c($data)
    {
        $data['created_at'] = time();
        $data['created_ip'] = get_ip();
        return $data;
    }

}
