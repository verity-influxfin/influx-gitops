<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Judicialperson extends MY_Admin_Controller {
	
	protected $edit_method = array('edit','verify_success','verify_failed');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/judicial_person_model');
		$this->load->library('financial_lib');
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
				$company_data = $this->gcis_lib->account_info($info->tax_id);
				$shareholders = $this->gcis_lib->get_shareholders($info->tax_id);
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

	function verify_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->target_model->get($id);
			if($info && $info->status==2){
				if($info->sub_status==8){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_success($info,$this->login_info->id);
				}else{
					$this->target_lib->target_verify_success($info,$this->login_info->id);
				}
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function verify_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		$remark = isset($get['remark'])?$get['remark']:'';
		if($id){
			$info = $this->target_model->get($id);
			if($info && in_array($info->status,array(0,1,2))){
				if($info->sub_status==8){
					$this->load->library('subloan_lib');
					$this->subloan_lib->subloan_verify_failed($info,$this->login_info->id,$remark);
				}else{
					$this->target_lib->target_verify_failed($info,$this->login_info->id,$remark);
				}
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