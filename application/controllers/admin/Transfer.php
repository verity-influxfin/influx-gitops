<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Transfer extends MY_Admin_Controller {
	
	protected $edit_method = array();
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('loan/transfer_model');
		$this->load->model('loan/transfer_investment_model');
		$this->load->model('loan/product_model');
		$this->load->library('transfer_lib');
		$this->load->library('financial_lib');
 	}
	
	public function index(){
		$page_data 		= array("type"=>"list");
		$list 			= array();
		$transfers 		= array();
		$targets 		= array();
		$input 			= $this->input->get(NULL, TRUE);
		$show_status 	= array(2,3,10);
		$where			= array();
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
			$where["status"] = isset($where["status"])?$where["status"]:$show_status;
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

	public function waiting_transfer(){
		$page_data 		= array("type"=>"list");
		$list 			= array();
		$transfers 		= array();
		$targets 		= array();
		$input 			= $this->input->get(NULL, TRUE);
		$where			= array(
			"status"	=> 0
		);
		$target_no		= "";
		$list 	= $this->transfer_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$list[$key]->target 	= $this->target_model->get($value->target_id);
				$list[$key]->investment = $this->investment_model->get($value->investment_id);
			}
		}

		$page_data['instalment_list']			= $this->config->item('instalment');
		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $list;
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;
		$page_data['transfers'] 				= $transfers;
		$page_data['targets'] 					= $targets;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/transfer/waiting_transfer',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function waiting_transfer_success(){
		$page_data 		= array("type"=>"list");
		$list 			= array();
		$transfers 		= array();
		$targets 		= array();
		$input 			= $this->input->get(NULL, TRUE);
		$where			= array(
			"status"	=> 1
		);
		$target_no		= "";
		$list 	= $this->transfer_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$list[$key]->target 	= $this->target_model->get($value->target_id);
				$list[$key]->investment = $this->investment_model->get($value->investment_id);
				$list[$key]->transfer_investment = $this->transfer_investment_model->get_by(array(
					"transfer_id"	=> $value->id,
					"status"		=> 2,
				));
			}
		}

		$page_data['instalment_list']			= $this->config->item('instalment');
		$page_data['repayment_type']			= $this->config->item('repayment_type');
		$page_data['list'] 						= $list;
		$page_data['transfer_status_list'] 		= $this->investment_model->transfer_status_list;
		$page_data['transfers'] 				= $transfers;
		$page_data['targets'] 					= $targets;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/transfer/waiting_transfer_success',$page_data);
		$this->load->view('admin/_footer');
	}
	
	function transfer_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$ids 	= isset($get["ids"])&&$get["ids"]?explode(",",$get["ids"]):"";
		if($ids && is_array($ids)){
			$this->load->library('Transaction_lib');
			foreach($ids as $key => $id){
				$rs = $this->transaction_lib->transfer_success($id,$this->login_info->id);
			}
			echo "放行成功";die();
		}else{
			echo "查無此ID";die();
		}
	}
	
	function transfer_cancel(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get["id"])?intval($get["id"]):0;
		if($id){
			$info = $this->transfer_model->get($id);
			if($info && $info->status==1){
				$this->load->library('transfer_lib');
				$rs = $this->transfer_lib->cancel_success($info,$this->login_info->id);
				if($rs){
					echo "更新成功";die();
				}else{
					echo "更新失敗";die();
				}
			}else{
				echo "查無此ID";die();
			}
		}else{
			echo "查無此ID";die();
		}
	}
	
}
?>