<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Charge_lib{
	
	private $user_id = "";
	private $product_id = "";
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('loan/credit_model');
		$this->CI->load->model('user/user_meta_model');
		$this->CI->load->model('user/user_model');
    }
	

}
