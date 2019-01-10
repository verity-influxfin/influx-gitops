<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Judicialperson extends MY_Admin_Controller {
	
	protected $edit_method = array('edit','verify_success','verify_failed');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/judicial_person_model');
		$this->load->model('user/cooperation_model');
		$this->load->library('Judicialperson_lib');
 	}
	
	public function index(){
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$where		= array();
		$list		= array();
		$fields 	= ['status','user_id','tax_id'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}
		if(!empty($where)){
			$list = $this->judicial_person_model->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$user_info 	= $this->user_model->get($value->user_id);
					$list[$key]->user_name = $user_info?$user_info->name:"";
				}
			}
		}

		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->judicial_person_model->status_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();
		$page_data['company_type'] 		= $this->config->item('company_type');


		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/judicial_person/judicial_person_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function edit(){
		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
		$id 		= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->judicial_person_model->get($id);
			if($info){
				$this->load->library('Gcis_lib'); 
				$user_info 					= $this->user_model->get($info->user_id);
				$info->user_name			= $user_info->name;
				$page_data['company_data'] 	= $this->gcis_lib->account_info($info->tax_id);
				$page_data['shareholders'] 	= $this->gcis_lib->get_shareholders($info->tax_id);
				$page_data['data'] 			= $info;
				$page_data['status_list'] 	= $this->judicial_person_model->status_list;
				$page_data['name_list'] 	= $this->admin_model->get_name_list();
				$page_data['company_type'] 	= $this->config->item('company_type');
				$this->load->view('admin/_header');
				$this->load->view('admin/_title',$this->menu);
				$this->load->view('admin/judicial_person/judicial_person_edit',$page_data);
				$this->load->view('admin/_footer');
			}else{
				alert('查無此ID',admin_url('judicialperson/index'));
			}
		}else{
			alert('查無此ID',admin_url('judicialperson/index'));
		}
	}

	function apply_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->judicial_person_model->get($id);
			if($info && $info->status==0){
				$this->judicialperson_lib->apply_success($id,$this->login_info->id);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function apply_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		$remark = isset($get['remark'])?$get['remark']:'';
		if($id){
			$info = $this->judicial_person_model->get($id);
			if($info && $info->status==0){
				$this->judicialperson_lib->apply_failed($id,$this->login_info->id,$remark);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	public function cooperation(){
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$where		= array();
		$list		= array();
		
		$fields 	= ['cooperation','user_id','tax_id'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}

		if(!empty($where)){
			$where['status'] = 1;
			$list = $this->judicial_person_model->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$user_info 	= $this->user_model->get($value->user_id);
					$list[$key]->user_name = $user_info?$user_info->name:"";
				}
			}
		}

		$page_data['list'] 				= $list;
		$page_data['cooperation_list'] 	= $this->judicial_person_model->cooperation_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/judicial_person/cooperation_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function cooperation_edit(){
		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
		$id 		= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->judicial_person_model->get($id);
			if($info){
				$user_info 					= $this->user_model->get($info->user_id);
				$this->load->library('Gcis_lib'); 
				$page_data['company_data'] 	= $this->gcis_lib->account_info($info->tax_id);
				$page_data['shareholders'] 	= $this->gcis_lib->get_shareholders($info->tax_id);
				$page_data['user_info'] 	= $user_info;
				$page_data['data'] 			= $info;
				$page_data['content'] 		= json_decode($info->cooperation_content,true);
				$page_data['cooperation_list'] 	= $this->judicial_person_model->cooperation_list;
				$page_data['company_type'] 		= $this->config->item('company_type');
				$this->load->view('admin/_header');
				$this->load->view('admin/_title',$this->menu);
				$this->load->view('admin/judicial_person/cooperation_edit',$page_data);
				$this->load->view('admin/_footer');
			}else{
				alert('查無此ID',admin_url('judicialperson/cooperation'));
			}
		}else{
			alert('查無此ID',admin_url('judicialperson/cooperation'));
		}
	}

	function cooperation_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->judicial_person_model->get($id);
			if($info && $info->status==1 && $info->cooperation==2){
				$this->judicialperson_lib->cooperation_success($id,$this->login_info->id);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function cooperation_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		$remark = isset($get['remark'])?$get['remark']:'';
		if($id){
			$info = $this->judicial_person_model->get($id);
			if($info && $info->status==1 && $info->cooperation==2){
				$this->judicialperson_lib->cooperation_failed($id,$this->login_info->id,$remark);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
}
?>