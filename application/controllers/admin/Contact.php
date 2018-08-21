<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Contact extends MY_Admin_Controller {
	
	protected $edit_method = array("edit");
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_contact_model');
 	}
	
	public function index(){
		$page_data 	= array("type"=>"list");
		$list 		= $this->user_contact_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			$page_data["list"] 			= $list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
			$page_data["status_list"] 	= $this->user_contact_model->status_list;
			$page_data["investor_list"] = $this->user_contact_model->investor_list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/contacts_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function edit(){
		$page_data 	= array("type"=>"edit");
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->user_contact_model->get_by('id', $id);
				if($info){
					$page_data["data"] 			= $info;
					$page_data["name_list"] 	= $this->admin_model->get_name_list();
					$page_data["status_list"] 	= $this->user_contact_model->status_list;
					$page_data["investor_list"] = $this->user_contact_model->investor_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/contacts_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('contact/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('contact/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['remark', 'status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$data["admin_id"] = $this->login_info->id;
				$rs = $this->user_contact_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('contact/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('contact/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('contact/index'));
			}
		}
	}
}
?>