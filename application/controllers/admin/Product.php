<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Product extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"product");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('product/product_model');
		if(empty($this->login_info)){
			redirect(admin_url('admin/login'), 'refresh');
        }	
 	}
	
	public function index(){
		$page_data 	= array("type"=>"list");
		$list 		= $this->product_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			$page_data["list"] 			= $list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/products_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function add(){
		$page_data 	= array("type"=>"add");
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$page_data["instalment_list"]	= $this->config->item('instalment');
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/products_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'name','loan_range_s', 'loan_range_e', 'interest_rate_s', 'interest_rate_e','instalment'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field." is empty",admin_url('product/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$fields = ['description', 'parent_id','status'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			$data["creator_id"] = $this->login_info->id;
			$rs = $this->product_model->insert($data);
			if($rs){
				alert("新增成功",admin_url('product/index'));
			}else{
				alert("新增失敗，請洽工程師",admin_url('product/index'));
			}
		}
	}
	
	public function edit(){
		$page_data 	= array("type"=>"edit");
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->product_model->get_by('id', $id);
				if($info){
					$page_data['data'] 				= $info;
					$page_data["instalment_list"]	= $this->config->item('instalment');
					$page_data['instalment'] 		= json_decode($info->instalment,TRUE);
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/products_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('product/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('product/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'loan_range_s', 'loan_range_e', 'interest_rate_s', 'interest_rate_e','description', 'instalment','status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->product_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('product/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('product/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('product/index'));
			}
		}
	}


	/*public function rating_edit(){
		$page_data 	= array("type"=>"edit");
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		$this->load->model('platform/rating_model');
		$rating 	= $this->rating_model->get_many_by(array("status"=>1));
		
		if(empty($post)){
			$id 	= isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->product_model->get_by('id', $id);
				if($info){
					$page_data['data'] 				= $info;
					$page_data['product_rating'] 	= json_decode($info->ratings,TRUE);
					$page_data['rating'] 			= $rating;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/product_rating_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('product/'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('product/'));
			}
		}else{
			if(!empty($post['id'])){
				$data = array();
				if($rating){
					foreach($rating as $key =>$value){
						$status 		= isset($post["rating"][$value->id])&&$post["rating"][$value->id]?1:0;
						$rating_value	= isset($post["rating_value"][$value->id])&&$post["rating_value"][$value->id]?$post["rating_value"][$value->id]:0;
						$data[$value->id] = array(
							"id" 		=> $value->id,
							"status"	=> $status,
							"value"		=> $rating_value,
						);
					}
				}
				$rs = $this->product_model->update($post['id'],array("ratings"=>json_encode($data)));
				if($rs===true){
					alert("更新成功",admin_url('product/'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('product/'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('product/'));
			}
		}
	}*/
}
?>