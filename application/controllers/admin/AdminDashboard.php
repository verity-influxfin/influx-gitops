<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class AdminDashboard extends MY_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('loan/target_model');
		$this->load->model('user/user_contact_model');
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
		$data 			= array();
		$target_count 	= array(
			"approve"	=> 0,
			"bidding"	=> 0,
			"repay"		=> 0,
			"delay"		=> 0,
		);
		$target_list 	= $this->target_model->get_many_by(array("status" => array(2,3,4,5,10)));
		$contact_list 	= $this->user_contact_model->order_by("created_at","desc")->limit(5)->get_many_by(array("status" => 0));
		if($target_list){
			foreach($target_list as $key => $value){
				if($value->delay==1){
					$target_count["delay"] += 1;
				}
				if($value->status==2){
					$target_count["approve"] += 1;
				}
				
				if($value->status==3 || $value->status==4){
					$target_count["bidding"] += 1;
				}
				
				if($value->status==5){
					$target_count["repay"] += 1;
				}
			}
		}

		$data["target_count"] = $target_count;
		$data["contact_list"] = $contact_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title');
		$this->load->view('admin/index',$data);
		$this->load->view('admin/_footer');
	}

}
?>
