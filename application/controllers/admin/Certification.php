<?php
use function GuzzleHttp\json_decode;

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Certification extends MY_Admin_Controller {
	
	protected $edit_method = array(
		'add',
		'edit',
		'user_certification_edit',
		'user_bankaccount_edit',
		'user_bankaccount_failed',
		'user_bankaccount_success',
		'difficult_word_add',
		'difficult_word_edit'
	);
	public $certification;
	public $certification_name_list;
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/user_meta_model');
		$this->certification 	= $this->config->item('certifications');
		foreach($this->certification as $id => $value){
			$this->certification_name_list[$id] = $value['name'];
		}
 	}
	
	public function index(){
		
		$page_data 	= array('type'=>'list');
		$list 		= $this->certification;
		$name_list	= array();
		if(!empty($list)){
			$page_data['list'] 			= $list;
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/certifications_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function user_certification_list(){
		$page_data 	= array('type'=>'list','list'=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();
		$where		= array();
		$fields 	= ['user_id','certification_id','status'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}
		
		if(!empty($where)){
			if(!isset($where['certification_id'])){
				$where['certification_id !='] = 3;
			}
			$list	= $this->user_certification_model->order_by('id','ASC')->get_many_by($where);
		}
		
		$page_data['list'] 					= $list;
		$page_data['certification_list'] 	= $this->certification_name_list;
		unset($page_data['certification_list'][3]);
		$page_data['status_list'] 			= $this->user_certification_model->status_list;
		$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/user_certification_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function user_certification_edit(){
		$page_data 	= array();
		$back_url 	= admin_url('certification/user_certification_list');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		if(empty($post)){
			$id 	= isset($get['id'])?intval($get['id']):0;
			$from 	= isset($get['from'])?$get['from']:'';
			if(!empty($from)){
				$back_url = admin_url($from);
			}
			if($id){
				$info = $this->user_certification_model->get($id);
				if($info){
					$certification 						= $this->certification[$info->certification_id];
					$page_data['certification_list'] 	= $this->certification_name_list;
					$page_data['data'] 					= $info;
					$page_data['content'] 				= json_decode($info->content,true);
					$page_data['remark'] 				= json_decode($info->remark,true);
					$page_data['status_list'] 			= $this->user_certification_model->status_list;
					$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
					$page_data['school_system'] 		= $this->config->item('school_system');
					if($info->certification_id==10){
						$page_data['employee_range'] 		= $this->config->item('employee_range');
						$page_data['position_name']			= $this->config->item('position_name');
						$page_data['seniority_range'] 		= $this->config->item('seniority_range');
						$page_data['industry_name'] 		= $this->config->item('industry_name');
						$page_data['job_type_name'] 		= $this->config->item('job_type_name');
					}
					$page_data['from'] 					= $from;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/certification/'.$certification['alias'],$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',$back_url);
				}
			}else{
				alert('ERROR , id is not exist',$back_url);
			}
		}else{
			if(!empty($post['id'])){
				$from 	= isset($post['from'])?$post['from']:'';
				$fail 	= isset($post['fail'])?$post['fail']:'';
				if(!empty($from)){
					$back_url = admin_url($from);
				}

				$info = $this->user_certification_model->get($post['id']);
				if($info){
					$certification = $this->certification[$info->certification_id];
					if($certification['alias']=='debitcard'){
						alert('金融帳號認證請至 金融帳號驗證區 操作',$back_url);
					}else{
						if($info->certification_id==10){
							$content 					= json_decode($info->content,TRUE);
							$content['license_status'] 	= isset($post['license_status'])?$post['license_status']:0;
							$this->user_certification_model->update($post['id'],['content'=>json_encode($content)]);
						}
						if($info->certification_id==9){
							$content 					= json_decode($info->content,TRUE);
							$content['times'] 			= isset($post['times'])?intval($post['times']):0;
							$content['credit_rate'] 	= isset($post['credit_rate'])?floatval($post['credit_rate']):0;
							$content['months'] 			= isset($post['months'])?intval($post['months']):0;
							$this->user_certification_model->update($post['id'],['content'=>json_encode($content)]);

						}
						$this->load->library('Certification_lib');
						$this->load->model('log/log_usercertification_model');
						$this->log_usercertification_model->insert(array(
							'user_certification_id'	=> $post['id'],
							'status'				=> $post['status'],
							'change_admin'			=> $this->login_info->id,
						));

						if($post['status']=='1'){
							$rs = $this->certification_lib->set_success($post['id']);
						}else if($post['status']=='2'){
							$rs = $this->certification_lib->set_failed($post['id'],$fail);
						}else{
							$rs = $this->user_certification_model->update($post['id'],array('status'=>intval($post['status'])));
						}
					}

					if($rs===true){
						alert('更新成功',$back_url);
					}else{
						alert('更新失敗，請洽工程師',$back_url);
					}
				}else{
					alert('ERROR , id is not exist',$back_url);
				}
			}else{
				alert('ERROR , id is not exist',$back_url);
			}
		}
	}


	public function user_bankaccount_list(){
		$page_data 			= array('type'=>'list','list'=>array());
		$input 				= $this->input->get(NULL, TRUE);
		$where				= array('status'=> 1);

		//必填欄位
		$fields 	= ['investor','verify'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
			    if($field == 'investor' && $input['investor'] ==2){
                    $where['investor']   = 1;
                    $where['back_image'] = '';
                }elseif($field == 'investor' && $input['investor'] ==1){
                    $where['investor']      = 1;
                    $where['back_image !='] = '';
                }
				else{
				    $where[$field] = $input[$field];
				}
			}
		}
		$list = $this->user_bankaccount_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$user = $this->user_model->get($value->user_id);
				$list[$key]->user_name 		= $user->name;
				$list[$key]->user_name_list = $user->name?mb_str_split($user->name):'';
			}
		}

		$this->load->model('admin/difficult_word_model');

		$page_data['list'] 			= $list?$list:array();
		$page_data['verify_list'] 	= $this->user_bankaccount_model->verify_list;
		$page_data['investor_list'] = $this->user_bankaccount_model->investor_list;
		$page_data['word_list'] 	= $this->difficult_word_model->get_name_list();

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
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$info = $this->user_bankaccount_model->get($id);
				if($info){
					$page_data['data'] 					= $info;
					$page_data['verify_list'] 			= $this->user_bankaccount_model->verify_list;
					$page_data['investor_list'] 		= $this->user_bankaccount_model->investor_list;
                    $page_data['status_list'] 			= $this->user_certification_model->status_list;

					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$front_image=$page_data['data']->front_image;//抓取obj內的front_image
					$bankbook_image=strpos($front_image,'bankbook_image');//找是否有bankbook_image的關鍵字 若有則回傳位置
					//error_log(__CLASS__ . '::' . __FUNCTION__ . ' page_data bankbook_image= '.print_r($bankbook_image,1)."\n", 3, "application/debug.log");

					if(empty($bankbook_image)){//檢查變數是否為空值
						$page_data['bankbook']=0;
						//error_log(__CLASS__ . '::' . __FUNCTION__ . ' page_data = '.print_r($page_data,1)."\n", 3, "application/debug.log");
						$this->load->view('admin/user_bankaccount_edit',$page_data);
						$this->load->view('admin/_footer');	
					}else{
						$page_data['bankbook']=1; //法人專用
						$front_image=json_decode($front_image,TRUE);
						$page_data['bankbook_image']=$front_image['bankbook_image'];
						//error_log(__CLASS__ . '::' . __FUNCTION__ . ' page_data = '.print_r($page_data,1)."\n", 3, "application/debug.log");
					    $this->load->view('admin/user_bankaccount_edit',$page_data);
					    $this->load->view('admin/_footer');	
					}
				    
					
				}else{
					alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
			}
		}else{
			if(!empty($post['id'])){
				$info = $this->user_bankaccount_model->get($post['id']);
				if($info){
					if($post['status']=='2'){
                        ;
                        $this->load->model('log/log_usercertification_model');
                        $this->log_usercertification_model->insert(array(
                            'user_certification_id'	=> $info->user_certification_id,
                            'status'				=> 2,
                            'change_admin'			=> $this->login_info->id,
                        ));
                        $this->user_certification_model->update($info->user_certification_id,array('status'=>2));
                        $this->user_bankaccount_model->update($post['id'],array('verify'=>4,'status'=>0));
                        $rs = true;
                    }

                    if($rs===true){
                        alert('更新成功',admin_url('certification/user_bankaccount_list'));
                    }else{
                        alert('更新失敗，請洽工程師',admin_url('certification/user_bankaccount_list'));
                    }
				}else{
					alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
			}
		}
	}

	function user_bankaccount_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->user_bankaccount_model->get($id);
			if($info && $info->verify==3){
				$this->load->model('log/log_usercertification_model');
				$this->log_usercertification_model->insert(array(
					'user_certification_id'	=> $info->user_certification_id,
					'status'				=> 1,
					'change_admin'			=> $this->login_info->id,
				));
				$this->load->library('Certification_lib');
				$this->certification_lib->set_success($info->user_certification_id);
				$this->user_bankaccount_model->update($id,array('verify'=>1));
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}

	function user_bankaccount_failed(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->user_bankaccount_model->get($id);
			if($info && $info->verify!=1){
				$this->load->model('log/log_usercertification_model');
				$this->log_usercertification_model->insert(array(
					'user_certification_id'	=> $info->user_certification_id,
					'status'				=> 2,
					'change_admin'			=> $this->login_info->id,
				));
				$this->user_certification_model->update($info->user_certification_id,array('status'=>2));
				$this->user_bankaccount_model->update($id,array('verify'=>4,'status'=>0));
				$this->load->library('Notification_lib');
				$this->notification_lib->bankaccount_verify_failed($info->user_id,$info->investor);
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}

	function user_bankaccount_resend(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->user_bankaccount_model->get($id);
			if($info && $info->verify==3){
				$this->user_bankaccount_model->update($id,array('verify'=>2));
				echo '更新成功';die();
			}else{
				echo '查無此ID';die();
			}
		}else{
			echo '查無此ID';die();
		}
	}

	function user_bankaccount_verify(){
		$this->load->library('payment_lib');
		$rs = $this->payment_lib->verify_bankaccount_txt($this->login_info->id);
		if($rs && $rs['content']=='' && $rs['xml_content']==''){
			alert('沒有待驗證的金融帳號',admin_url('certification/user_bankaccount_list?verify=2'));
		}else{
			alert('轉出成功',admin_url('certification/user_bankaccount_list?verify=3'));
		}
	}

	public function difficult_word_list(){
		$this->load->model('admin/difficult_word_model');
		$page_data 	= array('type'=>'list');
		$list 		= $this->difficult_word_model->get_all();
		if(!empty($list)){
			$page_data['list'] 		= $list;
			$page_data['name_list'] = $this->admin_model->get_name_list();
		}

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/difficult_word_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function difficult_word_add(){
		$this->load->model('admin/difficult_word_model');
		$page_data 	= array('type'=>'add');
		$data		= array();
		$post 		= $this->input->post(NULL, TRUE);
		if(empty($post)){
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/difficult_word_edit',$page_data);
			$this->load->view('admin/_footer');
		}else{
			$required_fields 	= [ 'word', 'spelling'];
			foreach ($required_fields as $field) {
				if (empty($post[$field])) {
					alert($field.' is empty',admin_url('certification/difficult_word_list'));
				}else{
					$data[$field] = trim($post[$field]);
				}
			}

			$data['creator_id'] = $this->login_info->id;
			$rs = $this->difficult_word_model->insert($data);
			if($rs){
				alert('新增成功',admin_url('certification/difficult_word_list'));
			}else{
				alert('新增失敗，請洽工程師',admin_url('certification/difficult_word_list'));
			}
		}
	}

	public function difficult_word_edit(){
		$this->load->model('admin/difficult_word_model');
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);

		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$info = $this->difficult_word_model->get_by('id', $id);
				if($info){
					$page_data['data'] 			= $info;

					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/difficult_word_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['spelling'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->difficult_word_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('certification/difficult_word_list'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('certification/difficult_word_list'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('certification/difficult_word_list'));
			}
		}
	}
}
?>