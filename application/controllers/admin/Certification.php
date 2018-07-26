<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Certification extends MY_Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('platform/certification_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_model');
		$this->load->library('payment_lib');
 	}
	
	public function index(){
		
		$page_data 	= array("type"=>"list");
		$list 		= $this->certification_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			$page_data["list"] 			= $list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
			$page_data["status_list"] 	= $this->certification_model->status_list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/certifications_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function add(){
		$page_data 	= array("type"=>"add");
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/certifications_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'name', 'alias'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field." is empty",admin_url('certification/index'));
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
			$rs = $this->certification_model->insert($data);
			if($rs){
				alert("新增成功",admin_url('certification/index'));
			}else{
				alert("新增失敗，請洽工程師",admin_url('certification/index'));
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
				$info = $this->certification_model->get_by('id', $id);
				if($info){
					$page_data['data'] 			= $info;
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/certifications_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('certification/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('certification/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'alias','description', 'status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->certification_model->update($post['id'],$data);
				if($rs===true){
					alert("更新成功",admin_url('certification/index'));
				}else{
					alert("更新失敗，請洽工程師",admin_url('certification/index'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('certification/index'));
			}
		}
	}

	public function user_certification_list(){
		$page_data 			= array("type"=>"list","list"=>array());
		$input 				= $this->input->get(NULL, TRUE);
		$where				= array();
		$fields 			= ['investor','certification_id','status'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}
		
		$list					= $this->user_certification_model->get_many_by($where);
		if(!empty($list)){
			$page_data['list'] = $list;
		}

		$page_data['certification_list'] 	= $this->certification_model->get_name_list();
		$page_data['status_list'] 			= $this->user_certification_model->status_list;
		$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/user_certification_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function user_certification_edit(){
		$page_data 	= array();
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->user_certification_model->get($id);
				if($info){
					$page_data['certification_list'] 	= $this->certification_model->get_name_list();
					$page_data['data'] 					= $info;
					$page_data['content'] 				= json_decode($info->content,true);
					$page_data['remark'] 				= json_decode($info->remark,true);
					$page_data['status_list'] 			= $this->user_certification_model->status_list;
					$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/user_certification_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('certification/user_certification_list'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('certification/user_certification_list'));
			}
		}else{
			if(!empty($post['id'])){
				$info = $this->user_certification_model->get($post['id']);
				if($info){
					if($info->status=="1"){
						alert("更新成功",admin_url('certification/user_certification_list'));
					}else{
						
						if($post['status']=="1"){
							$this->load->library('Certification_lib');
							$rs = $this->certification_lib->set_success($post['id']);
						}else{
							$rs = $this->user_certification_model->update($post['id'],array("status"=>intval($post['status'])));
						}

						if($rs===true){
							alert("更新成功",admin_url('certification/user_certification_list'));
						}else{
							alert("更新失敗，請洽工程師",admin_url('certification/user_certification_list'));
						}
					}
				}else{
					alert("ERROR , id isn't exist",admin_url('certification/user_certification_list'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('certification/user_certification_list'));
			}
		}
	}

	
	public function user_bankaccount_list(){
		$page_data 			= array("type"=>"list","list"=>array());
		$input 				= $this->input->get(NULL, TRUE);
		$where				= array();

		//必填欄位
		$fields 	= ['investor','verify'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!="") {
				$where[$field] = $input[$field];
			}
		}
		
		$list = $this->user_bankaccount_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				if($value->verify==2){
					$user = $this->user_model->get($value->user_id);
					$list[$key]->user_name = $user->name;
				}
			}
		}
		
		$page_data['list'] 				= $list?$list:array();
		$page_data['verify_list'] 		= $this->user_bankaccount_model->verify_list;
		$page_data['investor_list'] 	= $this->user_bankaccount_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/user_bankaccount_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function user_bankaccount_edit(){
		$page_data 	= array();
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get["id"])?intval($get["id"]):0;
			if($id){
				$info = $this->user_bankaccount_model->get($id);
				if($info){
					$page_data['data'] 					= $info;
					$page_data['verify_list'] 			= $this->user_bankaccount_model->verify_list;
					$page_data['investor_list'] 		= $this->user_bankaccount_model->investor_list;
					
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/user_bankaccount_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert("ERROR , id isn't exist",admin_url('certification/user_bankaccount_list'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('certification/user_bankaccount_list'));
			}
		}else{
			if(!empty($post['id'])){
				$info = $this->user_bankaccount_model->get($post['id']);
				if($info){
					if($info->status=="1"){
						alert("更新成功",admin_url('certification/user_bankaccount_list'));
					}else{
						
						if($post['status']=="1"){
							$this->load->library('Certification_lib');
							$rs = $this->certification_lib->set_success($post['id']);
						}else{
							$rs = $this->user_bankaccount_model->update($post['id'],array("status"=>intval($post['status'])));
						}

						if($rs===true){
							alert("更新成功",admin_url('certification/user_bankaccount_list'));
						}else{
							alert("更新失敗，請洽工程師",admin_url('certification/user_bankaccount_list'));
						}
					}
				}else{
					alert("ERROR , id isn't exist",admin_url('certification/user_bankaccount_list'));
				}
			}else{
				alert("ERROR , id isn't exist",admin_url('certification/user_bankaccount_list'));
			}
		}
	}
	
	function user_bankaccount_verify(){
		$rs = $this->payment_lib->verify_bankaccount_txt();
		if($rs!=""){
			$rs = iconv('UTF-8', 'BIG-5', $rs);
			header("Content-type: application/text");
			header("Content-Disposition: attachment; filename=verify_".date("YmdHis").".txt");
			echo $rs;
		}else{
			alert("ERROR , id isn't exist",admin_url('certification/user_bankaccount_list'));
		}
	}
}
?>