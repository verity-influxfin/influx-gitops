<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Product extends MY_Admin_Controller {
	
	protected $edit_method = array('add','edit');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('loan/product_model');
 	}
	
	public function index(){
		$page_data 					= array('type'=>'list');
		$list 						= $this->product_model->order_by('rank','asc')->get_all();
		$page_data['list'] 			= $list;
		$page_data['name_list'] 	= $this->admin_model->get_name_list();
		$page_data['status_list'] 	= $this->product_model->status_list;
		$page_data['product_type'] 	= $this->config->item('product_type');
		
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/products_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function add(){
		$page_data 	= array('type'=>'add');
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$page_data['instalment_list']	= $this->config->item('instalment');
			$page_data['product_type'] 		= $this->config->item('product_type');
			$page_data['status_list'] 	= $this->product_model->status_list;
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/products_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= ['type','name','loan_range_s','loan_range_e','interest_rate_s','interest_rate_e','instalment'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field.' is empty',admin_url('product/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$fields = ['rank','description', 'parent_id','status'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			$data['creator_id'] = $this->login_info->id;
			$rs = $this->product_model->insert($data);
			if($rs){
				alert('新增成功',admin_url('product/index'));
			}else{
				alert('新增失敗，請洽工程師',admin_url('product/index'));
			}
		}
	}
	
	public function edit(){
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$info = $this->product_model->get_by('id', $id);
				if($info){
					$page_data['data'] 				= $info;
					$page_data['instalment_list']	= $this->config->item('instalment');
					$page_data['instalment'] 		= json_decode($info->instalment,TRUE);
					$page_data['product_type'] 		= $this->config->item('product_type');
					$page_data['status_list'] 		= $this->product_model->status_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/products_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('product/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('product/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['type','rank','name','loan_range_s','loan_range_e','interest_rate_s','interest_rate_e','description', 'instalment','status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->product_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('product/index'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('product/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('product/index'));
			}
		}
	}

}
?>