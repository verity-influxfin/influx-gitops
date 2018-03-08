<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_lib{
	
	private $user_id = "";
	private $product_id = "";
	
	public function __construct($param)
    {
		
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('product/product_model');
		if(!empty($param)){
			$this->user_id 		= $param['user_id'];
			$this->product_id 	= $param['product_id'];
		}
    }
	
	//取得信用評分
	public function get_credit_points(){
		if($this->user_id && $this->product_id){
			return 200;
		}
		return false;
	}

	//取得信用額度
	public function get_credit_amount(){
		if($this->user_id && $this->product_id){
			return 50000;
		}
		return false;
	}
	
	//取得利率%
	public function get_interest_rate(){
		if($this->user_id && $this->product_id){
			return 12;
		}
		return false;
	}
	
	//檢查信用額度
	public function check_credit_amount(){
		if($this->user_id && $this->product_id){
			return true;
		}
		return false;
	}
	
	
}
