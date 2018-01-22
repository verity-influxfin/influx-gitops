<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class AdminDashboard extends MY_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('admin/admin_model');
		$this->load->helper('cookie');
		$method = $this->router->fetch_method();
		$nonAuthMethods = [];
        if (!in_array($method, $nonAuthMethods)) {
			if(empty($this->login_info)){
				redirect(admin_url('admin/login'), 'refresh');
			}
        }	
	}
	
	public function index()
	{
		$this->load->view('admin/_header');
		$this->load->view('admin/_title');
		$this->load->view('admin/index');
		$this->load->view('admin/_footer');
	}

}
?>
