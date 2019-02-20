<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Article extends MY_Admin_Controller {
	
	protected $edit_method = ['edit'];
	
	public function __construct() {
		parent::__construct();
		$this->load->model('admin/article_model');
		$this->load->library('Predis_lib');
 	}
	
	public function index(){
		$page_data 	= ['type'=>'list'];
		$input 		= $this->input->get(NULL, TRUE);
		$type		= isset($input['type'])&&$input['type']?$input['type']:1;

		$page_data['list'] 			= $this->article_model->order_by('rank','desc')->get_many_by([
			'type'		=> $type,
			'status !='	=> 2
		]);
		$page_data['type'] 			= $type;
		$page_data['name_list'] 	= $this->admin_model->get_name_list();
		$page_data['status_list'] 	= $this->article_model->status_list;
		$page_data['type_list'] 	= $this->article_model->type_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/article_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function add(){
		$input 		= $this->input->get(NULL, TRUE);
		$post 		= $this->input->post(NULL, TRUE);
		$type		= isset($input['type'])&&$input['type']?$input['type']:1;
		if(empty($post)){
			$page_data['type'] 			= $type;
			$page_data['status_list'] 	= $this->article_model->status_list;
			$page_data['type_list'] 	= $this->article_model->type_list;
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/article_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'type' ,'title', 'content', 'rank', 'url'];
			$data				= [];
			foreach ($required_fields as $field) {
				if (isset($post[$field])) {
					$data[$field] = $post[$field];
				}
			}
			
			if(isset($_FILES['image']) && $_FILES['image']['name']){
				$this->load->library('S3_upload');
				$data['image'] = $this->s3_upload->image_public($_FILES,'image');
			}
			
			$rs = $this->article_model->insert($data);
			if($rs){
				$this->predis_lib->get_event_list(true);
				$this->predis_lib->get_news_list(true);
				alert('新增成功',admin_url('Article/index?type='.$data['type']));
			}else{
				alert('更新失敗，請洽工程師',admin_url('Article/index?type='.$data['type']));
			}
		}
	}
	
	public function edit(){
		$get 		= $this->input->get(NULL, TRUE);
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$info = $this->article_model->get($id);
				if($info){
					$page_data['type'] 			= $info->type;
					$page_data['data'] 			= $info;
					$page_data['status_list'] 	= $this->article_model->status_list;
					$page_data['type_list'] 	= $this->article_model->type_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/article_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('Article/index?type='.$data['type']));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('Article/index?type='.$data['type']));
			}
		}else{
			$id = isset($post['id'])?intval($post['id']):0;
			if($id){
				$required_fields 	= [ 'type' ,'title', 'content', 'rank', 'url'];
				$data				= [];
				foreach ($required_fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				
				if(isset($_FILES['image']) && $_FILES['image']['name']){
					$this->load->library('S3_upload');
					$data['image'] = $this->s3_upload->image_public($_FILES,'image');
				}
				
				$rs = $this->article_model->update($id,$data);
				if($rs){
					$this->predis_lib->get_event_list(true);
					$this->predis_lib->get_news_list(true);
					alert('新增成功',admin_url('Article/index?type='.$data['type']));
				}else{
					alert('更新失敗，請洽工程師',admin_url('Article/index?type='.$data['type']));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('Article/index?type='.$data['type']));
			}
		}

	}
	
	function success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->article_model->get($id);
			if($info){
				$this->article_model->update($id,['status'=>1]);
				$this->predis_lib->get_event_list(true);
				$this->predis_lib->get_news_list(true);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->article_model->get($id);
			if($info){
				$this->article_model->update($id,['status'=>0]);
				$this->predis_lib->get_event_list(true);
				$this->predis_lib->get_news_list(true);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
	function del(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->article_model->get($id);
			if($info){
				$this->article_model->update($id,['status'=>2]);
				$this->predis_lib->get_event_list(true);
				$this->predis_lib->get_news_list(true);
				echo '刪除成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}
	
}
?>