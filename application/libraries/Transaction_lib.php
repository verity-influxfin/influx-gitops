<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_lib{
	
	
	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->model('transaction/transaction_model');
		$this->CI->load->model('transaction/target_model');
		$this->CI->load->model('transaction/investment_model');
    }
	
	public function apply($user_id,$certification_id){
		if($user_id && $certification_id){
			$param = array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification_id,
				"status"			=> 1
			);
			$certification = $this->CI->user_certification_model->order_by("updated_at","desc")->get_by($param);
			if(!$certification){
				$param = array(
					"user_id"			=> $user_id,
					"certification_id"	=> $certification_id,
					"status"			=> 0
				);
				$certification = $this->CI->user_certification_model->order_by("updated_at","desc")->get_by($param);
			}
			
			if(!empty($certification)){
				$certification->content = json_decode($certification->content,true);
				return $certification;
			}
		}
		return false;
	}

}
