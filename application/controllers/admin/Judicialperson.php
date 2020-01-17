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
		$this->load->library('S3_upload');
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
	
	public function media_upload()
	{   
		$post 		= $this->input->post(NULL, TRUE);  //接到user_id
		if (!empty($post)) {
			$media	= $this->s3_upload->media($_FILES, 'media', $post['user_id'], 'confirmation_for_judicial_person');
			if ($media === false) {
				alert('檔案上傳失敗，請洽工程師', 'index?status=0');
			} else {
				$sign_video = json_decode($this->judicial_person_model->get($post['id'])->sign_video,true);
				if (empty($sign_video)) {
					$sign_video['judi_admin_video'][] = $media;
				} else {
					if (isset($sign_video['judi_admin_video'])) {
						array_push($sign_video['judi_admin_video'],$media);
					} else {
						$sign_video['judi_admin_video'][] = $media;
					}
				}
				$res = $this->judicial_person_model->update($post['id'], [
					'sign_video' 			=> json_encode($sign_video)
				]);
				($res)?
					alert('檔案上傳成功', 'index?status=0')
					:alert('檔案上傳失敗，請洽工程師', 'index?status=0');
			}
		} else {
			alert('檔案上傳失敗，請洽工程師', 'index?status=0');
		}
	}
	public function edit(){
		$page_data 	= array('type'=>'edit');
        $post 		= $this->input->post(NULL, TRUE);
        $get 		= $this->input->get(NULL, TRUE);
        if(empty($post)) {
            $id = isset($get['id']) ? intval($get['id']) : 0;
            if ($id) {
                $info = $this->judicial_person_model->get($id);
                if ($info) {
                    $this->load->library('Gcis_lib');
                    $user_info = $this->user_model->get($info->user_id);
					$info->user_name = $user_info->name;
					$page_data['company_data'] = $this->gcis_lib->account_info($info->tax_id);//公司統編查詢
					$page_data['search_type']=0;
					if(empty($page_data['company_data'])){//商業統編查詢，利用商業登記基本資料取得商業司資料
						$page_data['company_data'] = $this->gcis_lib->account_info_businesss($info->tax_id);
						$page_data['search_type']=1;//查詢方式 0：利用公司統編查詢,1：利用商業統編查詢
					 }
                    $page_data['shareholders'] = $this->gcis_lib->get_shareholders($info->tax_id);
                    $page_data['data']         = $info;
                    $page_data['content'] 	   = json_decode($info->enterprise_registration,true);
                    $page_data['status_list']  = $this->judicial_person_model->status_list;
					$page_data['name_list']    = $this->admin_model->get_name_list();

					$where = array(
						'user_id'	=> $info->company_user_id,
						'status'	=> 1,
						'verify'	=> 1,
						'investor'	=> 1
					);
					$this->load->model('user/user_bankaccount_model');
					$user_bankaccount 	= $this->user_bankaccount_model->get_by($where);
					if($user_bankaccount){
						$page_data['bankaccount_id'] = $user_bankaccount->id;
					}

					$media_data =json_decode($info->sign_video,true);
					$page_data['media_list'] = $media_data;
                    $page_data['company_type'] = $this->config->item('company_type');
                    $this->load->view('admin/_header');
					$this->load->view('admin/_title', $this->menu);

                    $this->load->view('admin/judicial_person/judicial_person_edit', $page_data);
                    $this->load->view('admin/_footer');
                } else {
                    alert('查無此ID', admin_url('index?status=0'));
                }
            } else {
                alert('查無此ID', admin_url('index?status=0'));
            }
        }else {
			if (!empty($post['id'])) {
				$info = $this->judicial_person_model->get($post['id']);
				$media_data = json_decode($info->sign_video, true);
				if ($info && !empty($media_data) && isset($media_data['judi_admin_video'])) {
					if ($post['status'] == '1') {
						$rs = $this->judicialperson_lib->apply_success($post['id']);
					} else if ($post['status'] == '2') {
						$rs = $this->judicialperson_lib->apply_failed($post['id']);
					}

					if ($rs === true) {
						alert('更新成功', 'index?status=0');
					} else {
						alert('更新失敗，請洽工程師', 'index?status=0');
					}
				} else if ($info && (empty($media_data) || !isset($media_data['judi_admin_video']) || !isset($media_data['judi_user_video']))) {
					alert('請先上傳法人或請負責人上傳對保影片', 'index?status=0');
				} else {
					alert('查無此ID', admin_url('index?status=0'));
				}
			} else {
				alert('查無此ID', admin_url('index?status=0'));
			}
        }
	}

	function apply_success(){
		$get 	= $this->input->get(NULL, TRUE);
		$id 	= isset($get['id'])?intval($get['id']):0;
		if($id){
			$info = $this->judicial_person_model->get($id);
			if ($info && $info->status == 0) {
				$res = $this->judicialperson_lib->apply_success($id, $this->login_info->id);
				if ($res===false) { 
					echo '更新失敗';
					die();
				} else {
					echo '更新成功';
					die();
				}
			} else {
				echo '查無此ID';
				die();
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
				$this->judicialperson_lib->apply_failed($id,$this->login_info->id);
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
        $post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
        if(empty($post)) {
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
					$facade_image =array_column($page_data, 'facade_image');//找array內是否有facade_image
					$store_image =array_column($page_data, 'store_image');//找array內是否有store_image
					$bankbook_image =array_column($page_data, 'bankbook_image');//找array內是否有bankbook_image
					
					if($facade_image ==null){
						$page_data['content']['facade_image']= false;
					}
					if($store_image ==null){
						$page_data['content']['store_image']=false;
					}
					if($bankbook_image ==null){
						$page_data['content']['bankbook_image']=false;
					}
				 // error_log(__CLASS__ . '::' . __FUNCTION__ . ' page_data = ' .print_r($page_data,1)."\n", 3, "application/debug.log");

					$this->load->view('admin/judicial_person/cooperation_edit',$page_data);

                    $this->load->view('admin/_footer');
                }else{
                    alert('查無此ID',admin_url('cooperation?cooperation=2'));
                }
            }else{
                alert('查無此ID',admin_url('cooperation?cooperation=2'));
            }
        }else {
            if (!empty($post['id'])) {
                $info = $this->judicial_person_model->get($post['id']);
                if ($info) {
                    if ($post['cooperation'] == '1') {
                        $rs = $this->judicialperson_lib->cooperation_success($post['id']);
                    } else if ($post['cooperation'] == '0') {
                        $rs = $this->judicialperson_lib->cooperation_failed($post['id']);
                    }

                    if ($rs === true) {
                        alert('更新成功', 'cooperation?cooperation=2');
                    } else {
                        alert('更新失敗，請洽工程師', 'cooperation?cooperation=2');
                    }
                } else {
                    alert('查無此ID', admin_url('cooperation?cooperation=2'));
                }
            } else {
                alert('查無此ID', admin_url('cooperation?cooperation=2'));
            }
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
