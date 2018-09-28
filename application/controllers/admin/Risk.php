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
		$this->load->model('loan/product_model');
		$this->load->library('target_lib');
		$this->load->library('certification_lib');
		
 	}
	
	public function index(){
		$page_data 					= array("type"=>"list");
		$input 						= $this->input->get(NULL, TRUE);
		$where						= array("status"=>2);
		$page_data["product_name"]	= $this->product_model->get_name_list();
		$certification 				= $this->config->item('certifications');
		$list 						= array();
		$user_list 					= array();
		$certification_list 		= array();
		$targets 					= $this->target_model->get_many_by(array(
			"status"	=> array(0,1,2)
		));
		if($targets){
			foreach($targets as $key => $value){
				$user_list[] 	= $value->user_id;
				$list[] 		= $value;
			}
			
			$user_list = array_unique($user_list);
			foreach($user_list as $key => $value){
				$certification_list[$value] = $this->certification_lib->get_last_status($value,0);
			}
		}

		$page_data['list'] 					= $list;
		$page_data['certification'] 		= $certification;
		$page_data['certification_list'] 	= $certification_list;
		$page_data['status_list'] 			= $this->target_model->status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk_target',$page_data);
		$this->load->view('admin/_footer');
	}
	

}
?>