<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Certification extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"certification");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('platform/certification_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_model');
		if(empty($this->login_info)){
			redirect(admin_url('admin/login'), 'refresh');
        }	
 	}
	
	public function index(){
		$this->load->model('admin/admin_model');
		$page_data 	= array("type"=>"list");
		$list 		= $this->certification_model->get_all();
		$name_list	= array();
		if(!empty($list)){
			$page_data["list"] = $list;
			$page_data["name_list"] 	= $this->admin_model->get_name_list();
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
		$this->load->model('admin/admin_model');
		$page_data 			= array("type"=>"list");
		$certification 		= $this->certification_model->get_all();
		$list				= $this->user_certification_model->get_all();
		$certification_list = array();
		foreach($certification as $key => $value){
			$certification_list[$value->id] = $value->name;
		}
		if(!empty($list)){
			$page_data["list"] 				= $list;
			$page_data["certification_list"] = $certification_list;
			$page_data["status_list"] 		= $this->user_certification_model->status_list;
		}

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
					$certification 		= $this->certification_model->get_all();
					$certification_list = array();
					foreach($certification as $key => $value){
						$certification_list[$value->id] = $value->name;
					}
					$page_data['certification_list'] 	= $certification_list;
					$page_data['data'] 					= $info;
					$page_data['content'] 				= json_decode($info->content,true);
					$page_data["status_list"] 			= $this->user_certification_model->status_list;
					
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
							$this->certification_lib->set_success($post['id']);
						}
						
						$rs = $this->user_certification_model->update($post['id'],array("status"=>intval($post['status'])));
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
	
	public function school(){
		
		$rs = file_get_contents(base_url()."assets/school.json");
		$data = json_decode($rs,TRUE);
		if(!empty($data)){
			unset($data[0],$data[1]); 
			$list = array();
			foreach($data as $key => $value){
				$list[] = (object) array(
				'id' 		=> $value["106學年度大專校院名錄"],
				'name' 		=> $value[1],
				'public' 	=> $value[2],
				'city' 		=> $value[3],
				'address' 	=> $value[4],
				'phone' 	=> $value[5],
				'url' 		=> $value[6],
				);
			}
			$page_data["list"] = $list;
		}
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/school_list',$page_data);
		$this->load->view('admin/_footer');
	}
}
?>