<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Questionnaire extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"questionnaire");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('platform/questionnaire_model');
		if(empty($this->login_info)){
			redirect(admin_url('admin/login'), 'refresh');
        }	
 	}
	
	public function index(){
		$this->load->model('admin/admin_model');
		$page_data 	= array("type"=>"list");
		$list 		= $this->questionnaire_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			$page_data["list"] = $list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/questionnaires_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function add(){
		$page_data 	= array("type"=>"add");
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/questionnaires_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'name', 'alias'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field." is empty",admin_url('questionnaire/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			
			$fields = ['description', 'status'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			$data["creator_id"] = $this->login_info->id;
			$rs = $this->questionnaire_model->insert($data);
			if($rs){
				alert("新增成功",admin_url('questionnaire/index'));
			}else{
				alert("新增失敗，請洽工程師",admin_url('questionnaire/index'));
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
				$info = $this->questionnaire_model->get_by('id', $id);
				if($info){
					$page_data['data'] 			= $info;
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/questionnaires_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('questionnaire/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('questionnaire/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'alias','description', 'status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->questionnaire_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('questionnaire/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('questionnaire/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('questionnaire/index'));
			}
		}
	}
	
	function content_edit(){
		$page_data 	= array("type"=>"edit");
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->questionnaire_model->get_by('id', $id);
				if($info){
					$page_data['data'] 			= $info;
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/questionnaires_content_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('questionnaire/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('questionnaire/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'alias','description', 'status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->questionnaire_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('questionnaire/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('questionnaire/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('questionnaire/index'));
			}
		}
		
	}
	
	function form(){
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/forms');
		$this->load->view('admin/_footer');
	}
}
?>