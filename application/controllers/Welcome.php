<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	function get_investor_list(){
		if(!app_access()){
			show_404();
		}
		$this->load->model('transaction/transaction_model');
		$this->load->library('estatement_lib');
		$sdate = "2018-10-11";
		$edate = "2018-11-10";
		$date_range			= entering_date_range($edate);
		$edatetime			= $date_range?$date_range["edatetime"]:"";
		$date_range			= entering_date_range($sdate);
		$sdatetime			= $date_range?$date_range["sdatetime"]:"";
		$user_list 			= array();
		if($edatetime){
			$transaction 	= $this->transaction_model->get_many_by(array(
				"source" 				=> "1",
				"bank_account_to like" 	=> CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE."%",
				"entering_date <=" 		=> $edate,
			));
			if(!empty($transaction)){
				foreach($transaction as $key => $value){
					$user_list[$value->user_to] = $value->user_to;
				}
				
				if($user_list){
					$user_list = array_values($user_list);
					foreach($user_list as $key => $user_id){
						$rs = $this->estatement_lib->get_estatement_investor($user_id,$sdate,$edate);
						dump($rs);
						$rs = $this->estatement_lib->get_estatement_investor_detail($user_id,$sdate,$edate);
						dump($rs);
					}
				}
			}
		}
	}
	
	function get_borrower_list(){
		if(!app_access()){
			show_404();
		}
		$this->load->model('transaction/target_model');
		$this->load->library('estatement_lib');
		$sdate = "2018-10-11";
		$edate = "2018-11-10";
		$date_range			= entering_date_range($edate);
		$edatetime			= $date_range?$date_range["edatetime"]:"";
		$date_range			= entering_date_range($sdate);
		$sdatetime			= $date_range?$date_range["sdatetime"]:"";
		$user_list 			= array();
		if($edatetime){
			$target 		= $this->target_model->get_many_by(array(
				"status" 		=> array(5,10),
				"loan_date <=" 	=> $edate,
			));
			if(!empty($target)){
				foreach($target as $key => $value){
					$user_list[$value->user_id] = $value->user_id;
				}

				if($user_list){
					$user_list = array_values($user_list);
					foreach($user_list as $key => $user_id){
						$rs = $this->estatement_lib->get_estatement_borrower($user_id,$sdate,$edate);
						dump($rs);
					}
				}
			}
		}
	}
}