<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_management
{
    public function __construct(){
		$this->CI = &get_instance();
		$this->CI->load->model('loan/target_model');
	}

    public function get_data(){

    }
}
