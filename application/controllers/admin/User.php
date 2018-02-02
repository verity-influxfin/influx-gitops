<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class User extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"user");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('user/user_model');
		if(empty($this->login_info)){
			redirect(admin_url('admin/login'), 'refresh');
        }
 	}
	
	public function index(){
		
		$page_data 	= array("type"=>"list");
		$list 		= $this->user_model->get_all();
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
				$info = $this->user_model->get_by('id', $id);
				if($info){
					$page_data['data'] 			= $info;
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