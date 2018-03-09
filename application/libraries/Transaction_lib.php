<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/investment_model');
		$this->CI->load->model('user/user_bankaccount_model');
    }

}
