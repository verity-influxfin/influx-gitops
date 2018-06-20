<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Admin extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"admin");
	
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('admin/role_model');
		$this->load->model('log/log_adminlogin_model');
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
				$url 			= BORROW_URL.'?promote_code='.$value->my_promote_code;
				$qrcode			= "http://chart.apis.google.com/chart?cht=qr&choe=UTF-8&chl=".urlencode($url)."&chs=200x200";
				$value->qrcode	= $qrcode;
				$list[$key] 	= $value;
			}
			$page_data["list"] 		= $list;
			$page_data["name_list"] = $this->admin_model->get_name_list();
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
			
			if($admin_info){
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
					$page_data['data'] = $admin_info;
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
				$fields = ['name', 'role_id', 'phone', 'birthday', 'password'];
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