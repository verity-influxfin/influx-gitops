<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class User extends MY_Admin_Controller {
	
	protected $edit_method = array('edit');
	public $certification;
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('loan/credit_model');
		$this->certification = $this->config->item('certifications');
 	}
	
	public function index(){
		
		$page_data 			= array('type'=>'list');
		$input 				= $this->input->get(NULL, TRUE);
		$where				= array();
		$list				= array();
		$fields 			= ['id','name','phone'];
		
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				if($field=='phone' || $field=='name'){
					$where[$field.' like'] = '%'.$input[$field].'%';
				}else{
					$where[$field] = $input[$field];
				}
			}
		}
		if(!empty($where)){
			$list 	= $this->user_model->get_many_by($where);
		}
		$page_data['list'] = $list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/users_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function edit(){
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){		
				$meta_data 			= [];
				$meta 				= $this->user_meta_model->get_many_by([
					'user_id'	=> $id,
					'meta_key'	=> ['fb_id']
				]);
				if($meta){
					foreach($meta as $key => $value){
						$meta_data[$value->meta_key] = $value->meta_value;
					}
				}
				$bank_account = $this->user_bankaccount_model->get_many_by([
					'user_id'	=> $id,
					'status'	=> 1,
					//'verify'	=> 1,
				]);

				$info 			= $this->user_model->get($id);
				if($info){
					$this->load->library('certification_lib');
					$page_data['data'] 					= $info;
					$page_data['meta'] 					= $meta_data;
					$page_data['school_system'] 		= $this->config->item('school_system');
					$page_data['certification'] 		= $this->certification_lib->get_last_status($info->id,0,$info->company_status);
					$page_data['certification_investor']= $this->certification_lib->get_last_status($info->id,1,$info->company_status);
					$page_data['credit_list'] 			= $this->credit_model->get_many_by(['user_id' => $id, 'status' => 1]);
					$page_data['product_list']			= $this->config->item('product_list');
					$page_data['bank_account'] 			= $bank_account;
					$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
					$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/users_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('user/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('user/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'nickname', 'address', 'email', 'city', 'status', 'block_status','area'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->user_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('user/index'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('user/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('user/index'));
			}
		}
	}

		

	public function display(){

		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
		$id = isset($get['id'])?intval($get['id']):0;
		if($id){
			$certification_list = array();
			if($this->certification){
				foreach($this->certification as $key => $value){
					$certification_list[$value['alias']] = $value['name'];
				}
			}

			$meta_data 			= array();
			$meta 				= $this->user_meta_model->get_many_by(array('user_id'=>$id));
			if($meta){
				foreach($meta as $key => $value){
					$meta_data[$value->meta_key] = $value->meta_value;
				}
			}
			$bank_account 		= $this->user_bankaccount_model->get_many_by(array(
				'user_id'	=> $id,
				'status'	=> 1,
				'verify'	=> 1,
			));
			$credit_list		= $this->credit_model->get_many_by(array('user_id'=>$id));
			$info = $this->user_model->get($id);
			if($info){
                $this->load->library('certification_lib');
				$page_data['data'] 					= $info;
				$page_data['meta'] 					= $meta_data;
				$page_data['school_system'] 		= $this->config->item('school_system');
                $page_data['certification'] 		= $this->certification_lib->get_last_status($info->id,0,$info->company_status);
                $page_data['certification_investor']= $this->certification_lib->get_last_status($info->id,1,$info->company_status);
				$page_data['certification_list'] 	= $certification_list;
				$page_data['credit_list'] 			= $credit_list;
				$page_data['product_list']			= $this->config->item('product_list');
				$page_data['bank_account'] 			= $bank_account;
				$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
				$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
				$this->load->view('admin/_header');
				$this->load->view('admin/users_edit',$page_data);
				$this->load->view('admin/_footer');
			}else{
				alert('ERROR , id is not exist',admin_url('user/index'));
			}
		}else{
			alert('ERROR , id is not exist',admin_url('user/index'));
		}
	}

    public function block_user(){
        $get 		= $this->input->get(NULL, TRUE);
        if(empty($get)) {
            $page_data = [];
            $block_user = $this->user_model->get_many_by(array("block_status" => [1, 2, 3]));
            if ($block_user && !empty($block_user)) {
                $page_data['list'] = $block_user;
                $page_data['block_status_list'] = $this->user_model->block_status_list;
            }
            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/users_block_list', $page_data);
            $this->load->view('admin/_footer');
        }
        else{
            $success = $this->user_model->update($get['id'], ["block_status" => 0]);
            $this->load->model('log/log_userlogin_model');
            $info = $this->user_model->get($get['id']);
            $this->log_userlogin_model->insert(array(
                'account'	=> $info->phone,
                'investor'	=> 0,
                'user_id'	=> $info->id,
                'status'	=> 1
            ));
            if($success===true){
                alert('更新成功',admin_url('user/block_user'));
            }else{
                alert('更新失敗，請洽工程師',admin_url('user/block_user'));
            }
        }
    }
}
?>