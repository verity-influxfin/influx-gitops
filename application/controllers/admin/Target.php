<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Target extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"target");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('loan/target_model');
		$this->load->model('user/user_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('platform/certification_model');
		$this->load->model('product/product_model');
		$this->load->model('loan/credit_model');
		if(empty($this->login_info)){
			redirect(admin_url('admin/login'), 'refresh');
        }	
 	}
	
	public function index(){
		$page_data 					= array("type"=>"list");
		$list 						= $this->target_model->get_all();
		$page_data["product_name"]	= $this->product_model->get_name_list();

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
				$certification 		= $this->certification_model->get_all();
				$certification_list = array();
				if($certification){
					foreach($certification as $key => $value){
						$certification_list[$value->alias] = $value->name;
					}
				}

				$info = $this->target_model->get($id);
				if($info){
					$user_id = $info->user_id;
					$user_meta_data = array();
					$user_meta 		= $this->user_meta_model->get_many_by(array("user_id"=>$user_id));
					if($user_meta){
						foreach($user_meta as $key => $value){
							$user_meta_data[$value->meta_key] = $value->meta_value;
						}
					}
					
					$bank_account 		= $this->user_bankaccount_model->get_many_by(array("user_id"=>$user_id));
					$credit_list		= $this->credit_model->get_many_by(array("user_id"=>$user_id));
					$user_info 			= $this->user_model->get($user_id);
					$page_data['data'] 					= $info;
					$page_data['user_info'] 			= $user_info;
					$page_data['meta'] 					= $user_meta_data;
					$page_data['meta_fields'] 			= $this->config->item('user_meta_fields');
					$page_data['meta_images'] 			= $this->config->item('user_meta_images');
					$page_data['certification_list'] 	= $certification_list;
					$page_data['credit_list'] 			= $credit_list;
					$page_data['product_list'] 			= $this->product_model->get_name_list();
					$page_data['bank_account'] 			= $bank_account;
					$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
					$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
					$page_data['instalment_list']		= $this->config->item('instalment');
					$page_data['repayment_type']		= $this->config->item('repayment_type');
					$page_data['status_list'] 			= $this->target_model->status_list;
					
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