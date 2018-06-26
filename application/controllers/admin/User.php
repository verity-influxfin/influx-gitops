<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class User extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"user");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
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
		
		$page_data 			= array("type"=>"list");
		$list 				= $this->user_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			$page_data["list"] = $list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/users_list',$page_data);
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
				
				$meta_data 			= array();
				$meta 				= $this->user_meta_model->get_many_by(array("user_id"=>$id));
				if($meta){
					foreach($meta as $key => $value){
						$meta_data[$value->meta_key] = $value->meta_value;
					}
				}
				
				$bank_account 		= $this->user_bankaccount_model->get_many_by(array("user_id"=>$id));
				$credit_list		= $this->credit_model->get_many_by(array("user_id"=>$id));
				$info = $this->user_model->get($id);
				if($info){
					$page_data['data'] 					= $info;
					$page_data['meta'] 					= $meta_data;
					$page_data['meta_fields'] 			= $this->config->item('user_meta_fields');
					$page_data['meta_images'] 			= $this->config->item('user_meta_images');
					$page_data['certification_list'] 	= $certification_list;
					$page_data['credit_list'] 			= $credit_list;
					$page_data['product_list'] 			= $this->product_model->get_name_list();
					$page_data['bank_account'] 			= $bank_account;
					$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
					$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/users_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('user/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('user/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'nickname', 'address', 'email', 'city', 'status', 'block_status','area'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->user_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('user/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('user/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('user/index'));
			}
		}
	}
	
}
?>