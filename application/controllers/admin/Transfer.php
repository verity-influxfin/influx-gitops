<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Transfer extends MY_Admin_Controller {
	
	protected $edit_method = array("edit","verify_success","verify_failed","loan_success","loan_failed");
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('loan/transfer_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('loan/product_model');
		$this->load->model('loan/credit_model');
		$this->load->library('target_lib');
		$this->load->library('financial_lib');
 	}
	
	public function index(){
		$page_data 		= array("type"=>"list");
		$list 			= array();
		$transfers 		= array();
		$targets 		= array();
		$input 			= $this->input->get(NULL, TRUE);
		$show_status 	= array(2,3,10);
		$where			= array(
			"status"	=> $show_status
		);
		$target_no		= "";
		$fields 		= ['status','target_no','user_id'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				if($field=='target_no'){
					$target_no = '%'.$input[$field].'%';
				}else{
					$where[$field] = $input[$field];
				}
			}
		}
		
		if($target_no!="" || !empty($where)){
			
			if(!empty($target_no)){
				$target_ids 	= array();
				$target_list 	= $this->target_model->get_many_by(array(
					"target_no like" => $target_no
				));
				if($target_list){
					foreach($target_list as $key => $value){
						$target_ids[] = $value->id;
					}
				}
				$where["target_id"] = $target_ids;
			}
			
			$list 	= $this->investment_model->order_by("target_id","ASC")->get_many_by($where);
			if($list){
				$target_ids = array();
				$ids 		= array();
				foreach($list as $key => $value){
					$target_ids[] 	= $value->target_id;
					$ids[] 			= $value->id;
				}
				
				$target_list 	= $this->target_model->get_many($target_ids);
				if($target_list){
					foreach($target_list as $key => $value){
						$targets[$value->id] = $value;
					}
				}
				
				$transfer_list 	= $this->transfer_model->get_many_by(array("investment_id"=>$ids));
				if($transfer_list){
					foreach($transfer_list as $key => $value){
						$transfers[$value->investment_id] = $value;
					}
				}
			}
		}
		$page_data['instalment_list']			= $this->config->item('instalment');
		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $list;
		$page_data['delay_list'] 				= $this->target_model->delay_list;
		$page_data['status_list'] 				= $this->target_model->status_list;
		$page_data['show_status'] 				= $show_status;
		$page_data['investment_status_list'] 	= $this->investment_model->status_list;
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;
		$page_data['transfers'] 				= $transfers;
		$page_data['targets'] 					= $targets;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/target/targets_assets',$page_data);
		$this->load->view('admin/_footer');
	}

}
?>