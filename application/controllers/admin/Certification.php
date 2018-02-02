<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Certification extends MY_Admin_Controller {
	
	public $menu = array("menu"=>"certification");
	public function __construct() {
		parent::__construct();
		$this->login_info = check_admin();
		$this->load->model('platform/certification_model');
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