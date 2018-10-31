<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Risk extends MY_Admin_Controller {
	
	protected $edit_method = array("add","edit","partner_type_add");
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('loan/product_model');
		$this->load->library('target_lib');
		$this->load->library('certification_lib');
 	}
	
	public function index(){
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$list 						= array();
		$user_list 					= array();
		$user_investor_list 		= array();
		$certification_investor_list = array();
		$user_certification_list	= $this->user_certification_model->get_many_by(array(
			"status"	=> 0,
		));
		if($user_certification_list){
			foreach($user_certification_list as $key => $value){
				if($value->investor){
					$user_investor_list[$value->user_id] = $value->user_id;
				}else{
					$user_list[$value->user_id] = $value->user_id;
				}
			}
		}

		if($user_investor_list){
			ksort($user_investor_list);
			foreach($user_investor_list as $key => $value){
				$certification_investor_list[$value] = $this->certification_lib->get_last_status($value,1);
			}
		}
		
		if($user_list){
			$targets = $this->target_model->get_many_by(array(
				"user_id"	=> $user_list,
				"status"	=> 0
			));
			if($targets){
				foreach($targets as $key => $value){
					$list[$value->id] = $value;
				}
			}
		}
		
		$targets = $this->target_model->get_many_by(array(
			"status"	=> array(1,2)
		));
		if($targets){
			foreach($targets as $key => $value){
				$list[$value->id] = $value;
			}
		}
		
		if($list){
			ksort($list);
			foreach($list as $key => $value){
				$list[$key]->certification = $this->certification_lib->get_last_status($value->user_id,0);
				if($value->status==2){
					$bank_account 		= $this->user_bankaccount_model->get_by(array(
						"user_id"	=> $value->user_id,
						"investor"	=> 0,
						"status"	=> 1,
						"verify"	=> 1,
					));
					$list[$key]->bank_account_verify = $bank_account?1:0;
				}
			}
		}

		$page_data['list'] 					= $list;
		$page_data['certification_investor_list'] 	= $certification_investor_list;
		$page_data['certification'] 		= $this->config->item('certifications');
		$page_data['status_list'] 			= $this->target_model->status_list;
		$page_data['product_name']			= $this->product_model->get_name_list();
		
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk_target',$page_data);
		$this->load->view('admin/_footer');
	}
	

}
?>