<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class AdminDashboard extends MY_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_contact_model');
	}
	
	public function index()
	{
		$data 			= array();
		$target_count 	= array(
			"approve"	=> 0,
			"bidding"	=> 0,
			"success"	=> 0,
			"delay"		=> 0,
		);
		$target_list 	= $this->target_model->get_many_by(array("status" => array(2,3,4,5,10)));
		$contact_list 	= $this->user_contact_model->order_by("created_at","desc")->limit(5)->get_many_by(array("status" => 0));
		if($target_list){
			$this->load->model('user/user_bankaccount_model');
			foreach($target_list as $key => $value){
				if($value->delay==1 && $value->status==5){
					$target_count["delay"] += 1;
				}
				if($value->status==2){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						"user_id"	=> $value->user_id,
						"investor"	=> 0,
						"status"	=> 1,
						"verify"	=> 1,
					));
					if($bank_account){
						$target_count["approve"] += 1;	
					}
				}
				
				if($value->status==3){
					$target_count["bidding"] += 1;
				}
				
				if($value->status==4){
					$target_count["success"] += 1;
				}
			}
		}

		$data["target_count"] = $target_count;
		$data["contact_list"] = $contact_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/index',$data);
		$this->load->view('admin/_footer');
	}

	public function personal(){
		$role_name 	= $this->role_model->get_name_list();
		$page_data 	= array("type"=>"edit","role_name"=>$role_name);
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		$id 		= $this->login_info->id;
		if(empty($post)){
			$admin_info = $this->admin_model->get_by('id', $id);
			if($admin_info){
				$url 				= BORROW_URL.'?promote_code='.$admin_info->my_promote_code;
				$admin_info->qrcode	= get_qrcode($url);
				$page_data['data'] 	= $admin_info;
				$this->load->view('admin/_header');
				$this->load->view('admin/_title',$this->menu);
				$this->load->view('admin/personal',$page_data);
				$this->load->view('admin/_footer');
			}else{
				alert("ERROR , id isn't exist",admin_url('AdminDashboard'));
			}
		}else{
			if(!empty($post['name'])){
				$fields = ['name', 'phone', 'birthday', 'password'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				if(isset($data['password']) && empty($data['password'])){
					unset($data['password']);
				}
				
				$rs = $this->admin_model->update($id,$data);
				if($rs===true){
					alert("更新成功",admin_url('AdminDashboard'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('AdminDashboard'));
				}
			}else{
				alert("ERROR , 姓名不得空白",admin_url('AdminDashboard'));
			}
		}
	}
}
?>
