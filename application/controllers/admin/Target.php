<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Target extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"target");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('loan/target_model');
		$this->load->model('product/product_model');
		if(empty($this->login_info)){
			redirect(admin_url('admin/login'), 'refresh');
        }	
 	}
	
	public function index(){
		$page_data 		= array("type"=>"list");
		$list 			= $this->target_model->get_all();
		$product_list 	= $this->product_model->get_all();
		$page_data["product_name"]	= array();
		foreach($product_list as $key => $value){
			$page_data["product_name"][$value->id] = $value->name;
		}

		if(!empty($list)){
			$page_data['instalment_list']	= $this->config->item('instalment');
			$page_data['repayment_type']	= $this->config->item('repayment_type');
			$page_data['list'] 				= $list;
			$page_data['status_list'] 		= $this->target_model->status_list;
			$page_data['name_list'] 		= $this->admin_model->get_name_list();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/targets_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function edit(){
		$page_data 	= array("type"=>"edit");
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->target_model->get($id);
				if($info){
					$page_data['info'] 				= $info;
					$page_data['instalment_list']	= $this->config->item('instalment');
					$page_data['repayment_type']	= $this->config->item('repayment_type');
					$page_data['instalment'] 		= json_decode($info->instalment,TRUE);
					$page_data['status_list'] 		= $this->target_model->status_list;
					$page_data['target_fields'] 	= $this->config->item('target_fields');
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/targets_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('target/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('target/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->product_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('target/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('target/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('target/index'));
			}
		}
	}

}
?>