<?php

class Product_category_model extends MY_Model
{
	public $_table = 'product_category';
	
	public function __construct()
	{
		parent::__construct();
		$this->_database = $this->load->database('product',TRUE);
 	}
}
