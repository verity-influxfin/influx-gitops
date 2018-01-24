<?php

class Product_model extends MY_Model
{
	public $_table = 'products';
	public $before_create = array( 'before_data_c' );
	public $before_update = array( 'before_data_u' );
	public $status_list   = array(
		0 =>	"下架中",
		1 =>	"上架中"
	);
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('product',TRUE);
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
}