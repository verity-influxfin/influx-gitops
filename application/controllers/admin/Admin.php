<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Admin extends MY_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('admin/admin_model');
		$this->load->helper('cookie');
		$this->load->library('encrypt');
		$method = $this->router->fetch_method();
		$nonAuthMethods = ['login'];
        if (!in_array($method, $nonAuthMethods)) {
			if(empty($this->login_info)){
				redirect(admin_url('admin/login'), 'refresh');
			}
        }	
 	}
	
	public function index(){
		$page_data 	= array();
		$list 		= $this->admin_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			foreach($list as $key => $value){
				$name_list[$value->id] = $value->name;
			}
			$page_data["list"] = $list;
			$page_data["name_list"] = $name_list;
		}
		

		$this->load->view('admin/_header');
		$this->load->view('admin/_title');
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
			
			if($admin_info){
				if(sha1($post['password'])==$admin_info->password){
					$admin_info = AUTHORIZATION::generateAdminToken($admin_info);
					admin_login($admin_info);
					redirect(admin_url('AdminDashboard/'), 'refresh');
					die();
				}else{
					alert("密碼錯誤",admin_url('admin/login'));
					die();
				}
			}else{
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
		$page_data 	= array();
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title');
			$this->load->view('admin/admins_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$fields 	= [ 'account', 'name', 'phone', 'address', 'email', 'password'];
			foreach ($fields as $field) {
				if (empty($post[$field])) {
					alert($field." is empty",admin_url('admin/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$data["creator_id"] = $this->login_info->id;
			$data["password"] 	= sha1($data["password"]);
			$rs = $this->admin_model->insert($data);
			if($rs){
				alert("新增成功",admin_url('admin/index'));
			}else{
				alert("新增失敗，請洽工程師",admin_url('admin/index'));
			}
		}
	}
	
	public function edit(){
		$page_data 	= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			
			$this->load->view('admin/_header');
			$this->load->view('admin/_title');
			$this->load->view('admin/admins_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			if(!empty($post['id'])){
				$rs = $this->admin_model->updateAccountInfo($post['id'],$post);
				if($rs===true){
					alert("update success",admin_url('admin/index'));
				}else{
					
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('admin/index'));
			}
		}
		
	}
}
?>