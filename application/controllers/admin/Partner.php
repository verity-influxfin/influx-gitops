<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Partner extends MY_Admin_Controller {
	
	protected $edit_method = array('add','edit','partner_type_add');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('admin/partner_model');
		$this->load->model('admin/partner_type_model');
 	}
	
	public function index(){
		$page_data 	= array();
		$list 		= $this->partner_model->order_by('parent_id ASC','school ASC')->get_all();
		$name_list	= array();
		if(!empty($list)){
			foreach($list as $key => $value){
                $my_promote_code = $value->my_promote_code;
				//$url 			= BORROW_URL.'?promote_code='.$value->my_promote_code;
                $url 					= 'https://event.influxfin.com/r/url?p='.$my_promote_code;
                $qrcode			= get_qrcode($url);
				$value->qrcode	= $qrcode;
				$list[$key] 	= $value;
			}
			$page_data['list'] 			= $list;
			$page_data['name_list'] 	= $this->admin_model->get_name_list();
			$page_data['partner_name'] 	= $this->partner_model->get_name_list();
			$page_data['partner_type'] 	= $this->partner_type_model->get_name_list();
		}
		

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/partners_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function add(){
		$partner_name 	= $this->partner_model->get_name_list();
		$admins_name 	= $this->admin_model->get_name_list();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$school_list 	= file_get_contents('https://s3-ap-northeast-1.amazonaws.com/influxp2p/school.json');
		$school_list 	= json_decode($school_list,true);
		$page_data 	= array(
			'type'			=> 'add',
			'partner_name'	=> $partner_name,
			'partner_type'	=> $partner_type,
			'admins_name'	=> $admins_name,
			'school_list'	=> $school_list,
		);
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/partners_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'company', 'name', 'title', 'phone', 'email', 'password'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field.' is empty',admin_url('partner/index'));
				}else{
					$data[$field] = $post[$field];
				}
			}
			$fields = ['type', 'tax_id', 'admin_id' , 'parent_id','school'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			$data['creator_id'] 	= $this->login_info->id;
			$data['my_promote_code']= $this->get_promote_code();
			$rs = $this->partner_model->insert($data);
			if($rs){
				alert('新增成功',admin_url('partner/index'));
			}else{
				alert('新增失敗，請洽工程師',admin_url('partner/index'));
			}
		}
	}
	
	public function edit(){
		$partner_name 	= $this->partner_model->get_name_list();
		$admins_name 	= $this->admin_model->get_name_list();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$school_list 	= file_get_contents('https://s3-ap-northeast-1.amazonaws.com/influxp2p/school.json');
		$school_list 	= json_decode($school_list,true);
		$page_data 	= array(
			'type'			=> 'edit',
			'partner_name'	=> $partner_name,
			'partner_type'	=> $partner_type,
			'admins_name'	=> $admins_name,
			'school_list'	=> $school_list,
		);
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$partner_info = $this->partner_model->get_by('id', $id);
				if($partner_info){
                    $my_promote_code = $partner_info->my_promote_code;
					//$url 					= BORROW_URL.'?promote_code='.$partner_info->my_promote_code;
                    $url 					= 'https://event.influxfin.com/r/url?p='.$my_promote_code;
					$qrcode					= get_qrcode($url);
					$partner_info->qrcode	= $qrcode;
					unset($page_data['partner_name'][$id]);
					$page_data['data'] = $partner_info;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/partners_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('partner/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('partner/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['admin_id', 'type', 'parent_id', 'company', 'tax_id', 'name', 'title', 'phone', 'email', 'password','school'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				
				if(isset($data['parent_id']) && $data['parent_id']){
					if($data['parent_id']==$post['id']){
						alert('上層公司不可為同一間',admin_url('partner/index'));
					}
					$parent = $this->partner_model->get($data['parent_id']);
					if($parent->parent_id==$post['id']){
						alert('不可互為上層公司',admin_url('partner/index'));
					}
				}
				
				if(isset($data['password']) && empty($data['password'])){
					unset($data['password']);
				}
				
				$rs = $this->partner_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('partner/index'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('partner/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('partner/index'));
			}
		}
		
	}
	
	public function partner_type(){
		$list 					= $this->partner_type_model->get_many_by(array('status' => 1));
		$page_data['name_list'] = $this->admin_model->get_name_list();
		$page_data['list'] 		= $list;
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/partner_type', $page_data);
        $this->load->view('admin/_footer');
	}
	
	public function partner_type_add(){
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$page_data = array('type'=>'add');
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/partner_type_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$fields = ['title'];
			foreach ($fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			$data['creator_id'] = $this->login_info->id;
			$rs 				= $this->partner_type_model->insert($data);
			if($rs){
				alert('新增成功',admin_url('partner/partner_type'));
			}else{
				alert('新增失敗，請洽工程師',admin_url('partner/partner_type'));
			}
		}
	}
	private function get_promote_code(){
		$code 	= 'PARTNER'.make_promote_code();
		$result = $this->partner_model->get_by('my_promote_code',$code);
		if ($result) {
			return $this->get_promote_code();
		}else{
			return $code;
		}
	}
}
?>