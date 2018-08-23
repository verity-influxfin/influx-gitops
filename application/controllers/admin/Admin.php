<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Admin extends MY_Admin_Controller {
	
	protected $edit_method = array("add","edit","role_add","role_edit");
	
	public function __construct() {
		parent::__construct();
		$this->load->model('log/log_adminlogin_model');
 	}
	
	public function index(){
		$page_data 	= array();
		$where		= array("status"=>1);
		$list 		= $this->admin_model->get_many_by($where);
		$name_list	= array();
		if(!empty($list)){
			foreach($list as $key => $value){
				$url 			= BORROW_URL.'?promote_code='.$value->my_promote_code;
				$qrcode			= get_qrcode($url);
				$value->qrcode	= $qrcode;
				$list[$key] 	= $value;
			}
			$page_data["list"] 			= $list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
			$page_data["status_list"] 	= $this->admin_model->status_list;
			$page_data["role_name"] = $this->role_model->get_name_list();
		}
		

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/admins_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function login(){

		$post = $this->input->post(NULL, TRUE);
		if(empty($post)){
			$cookie = get_cookie(COOKIES_LOGIN_ADMIN);
			$cookie = AUTHORIZATION::getAdminCookieInfoByToken($cookie);
			$cookie	= $cookie?$cookie:"";
			$this->load->view('admin/login',array("cookie"=>$cookie));
		}else{
			if(isset($post['remember'])){
				$cookie = array(
                        'name'   => COOKIES_LOGIN_ADMIN,
                        'value'  => AUTHORIZATION::generateAdminCookieToken($post),
                        'expire' => COOKIE_EXPIRE,
                );
				
				set_cookie($cookie);
			}else{
				delete_cookie(COOKIES_LOGIN_ADMIN);
			}
			
			$admin_info = $this->admin_model->get_by('email', $post['email']);	
			if(!$admin_info){
				$admin_info = $this->admin_model->get_by('account', $post['email']);	
			}
			
			if($admin_info && $admin_info->status==1){
				if(sha1($post['password'])==$admin_info->password){
					$admin_token = AUTHORIZATION::generateAdminToken($admin_info);
					admin_login($admin_token);
					$this->log_adminlogin_model->insert(array("email"=>$post['email'],"status"=>1));
					redirect(admin_url('AdminDashboard/'), 'refresh');
					die();
				}else{
					$this->log_adminlogin_model->insert(array("email"=>$post['email'],"status"=>0));
					alert("密碼錯誤",admin_url('admin/login'));
					die();
				}
			}else{
				$this->log_adminlogin_model->insert(array("email"=>$post['email'],"status"=>0));
				alert("無此email",admin_url('admin/login'));
				die();
			}
		}
	}
	
	public function logout(){
		admin_logout();
		redirect(admin_url('admin/login'), 'refresh');
	}
	
	public function add(){
		$role_name 	= $this->role_model->get_name_list();
		$page_data 	= array("type"=>"add","role_name"=>$role_name);
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/admins_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'account', 'role_id', 'name', 'phone', 'birthday', 'email', 'password'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field." is empty",admin_url('admin/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$fields = ['phone', 'birthday'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			$data["creator_id"] 	= $this->login_info->id;
			$data["my_promote_code"]= $this->get_promote_code();
			$rs = $this->admin_model->insert($data);
			if($rs){
				alert("新增成功",admin_url('admin/index'));
			}else{
				alert("新增失敗，請洽工程師",admin_url('admin/index'));
			}
		}
	}
	
	public function edit(){
		$role_name 	= $this->role_model->get_name_list();
		$page_data 	= array("type"=>"edit","role_name"=>$role_name);
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$admin_info = $this->admin_model->get_by('id', $id);
				if($admin_info){
					$url 						= BORROW_URL.'?promote_code='.$admin_info->my_promote_code;
					$admin_info->qrcode			= get_qrcode($url);
					$page_data['data'] 			= $admin_info;
					$page_data["status_list"] 	= $this->admin_model->status_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/admins_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('admin/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('admin/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'role_id', 'phone', 'birthday', 'password','status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				if(isset($data['password']) && empty($data['password'])){
					unset($data['password']);
				}
				
				$rs = $this->admin_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('admin/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('admin/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('admin/index'));
			}
		}
		
	}
	
	public function role_list(){
		$page_data 	= array();
		$list 		= $this->role_model->get_all();
		if(!empty($list)){
			$page_data["list"] 			= $list;
			$page_data["status_list"] 	= $this->role_model->status_list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/roles_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function role_add(){
		$status_list 	= $this->role_model->status_list;
		$admin_menu = $this->config->item('admin_menu');
		if(!empty($admin_menu)){
			unset($admin_menu['AdminDashboard']);
		}
		$page_data 	= array(
			"type"			=> "add",
			"status_list"	=> $status_list,
			"admin_menu"	=> $admin_menu,
		);
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/roles_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'alias', 'name' ,'status'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field." is empty",admin_url('admin/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$permission = array();
			foreach($admin_menu as $key => $value){
				$r = isset($post['permission'][$key]["r"])&&$post['permission'][$key]["r"]?1:0;
				$u = isset($post['permission'][$key]["u"])&&$post['permission'][$key]["u"]?1:0;
				$permission[$key] = array(
					"r"	=> $r,
					"u"	=> $u
				);
			}

			$data["permission"] 	= json_encode($permission);
			$data["creator_id"] 	= $this->login_info->id;
			$rs = $this->role_model->insert($data);
			if($rs){
				alert("新增成功",admin_url('admin/role_list'));
			}else{
				alert("新增失敗，請洽工程師",admin_url('admin/role_list'));
			}
		}
	}
	
	public function role_edit(){
		$status_list 	= $this->role_model->status_list;
		$admin_menu = $this->config->item('admin_menu');
		if(!empty($admin_menu)){
			unset($admin_menu['AdminDashboard']);
		}
		$page_data 	= array(
			"type"			=> "edit",
			"status_list"	=> $status_list,
			"admin_menu"	=> $admin_menu,
		);
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$role_info = $this->role_model->get_by('id', $id);
				if($role_info){
					$role_info->permission = json_decode($role_info->permission,true);
					$page_data['data'] = $role_info;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/roles_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('admin/role_list'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('admin/role_list'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name','status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				
				$permission = array();
				foreach($admin_menu as $key => $value){
					$r = isset($post['permission'][$key]["r"])&&$post['permission'][$key]["r"]?1:0;
					$u = isset($post['permission'][$key]["u"])&&$post['permission'][$key]["u"]?1:0;
					$permission[$key] = array(
						"r"	=> $r,
						"u"	=> $u
					);
				}

				$data["permission"] 	= json_encode($permission);
				$rs = $this->role_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('admin/role_list'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('admin/role_list'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('admin/role_list'));
			}
		}
		
	}
	
	private function get_promote_code(){
		$code 	= "SALES".make_promote_code();
		$result = $this->admin_model->get_by('my_promote_code',$code);
		if ($result) {
			return $this->get_promote_code();
		}else{
			return $code;
		}
	}
}
?>