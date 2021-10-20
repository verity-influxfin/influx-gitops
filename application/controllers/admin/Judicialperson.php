<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Judicialperson extends MY_Admin_Controller {

	protected $edit_method = array('edit','apply_success','apply_failed','cooperation_edit','cooperation_success','cooperation_failed','media_upload');

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
				$where['status'] == 0 ? $where['status'] = [0,3] : '';
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
				alert('檔案上傳失敗，請洽工程師', 'edit?id='.$post['id']);
			} else {
				$sign_video = json_decode($this->judicial_person_model->get($post['id'])->sign_video,true);
				if (empty($sign_video)) {
					$sign_video['judi_admin_video'][] = $media;
				} else {
					if (!isset($sign_video['judi_admin_video'])) {
						$sign_video['judi_admin_video'] = [];
						}
						$sign_video['judi_admin_video'][] = $media;
				}
				$res = $this->judicial_person_model->update($post['id'], [
					'sign_video' 			=> json_encode($sign_video)
				]);
				($res)?
					alert('檔案上傳成功', 'edit?id='.$post['id'])
					:alert('檔案上傳失敗，請洽工程師', 'edit?id='.$post['id']);
			}
		} else {
			alert('檔案上傳失敗，請洽工程師', 'edit?id='.$post['id']);
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
					$info->user_name = isset($user_info->name)?$user_info->name:'';
					$page_data['company_data'] = $this->gcis_lib->account_info($info->tax_id);//公司統編查詢
					$page_data['search_type']=0;
					if(empty($page_data['company_data'])){//商業統編查詢，利用商業登記基本資料取得商業司資料
						$page_data['company_data'] = $this->gcis_lib->account_info_businesss($info->tax_id);
						$page_data['search_type']=1;//查詢方式 0：利用公司統編查詢,1：利用商業統編查詢
					 }
                    $page_data['shareholders'] = $this->gcis_lib->get_shareholders($info->tax_id);
                    $page_data['data']         = $info;
                    $page_data['content'] 	   = json_decode($info->enterprise_registration,true);
					$sign_video_content = json_decode($info->sign_video);
                    isset($sign_video_content->bankbook_images)
						? $page_data['bankbook'] = json_decode(urldecode($sign_video_content->bankbook_images))->bankbook_image
						: '';
                    $page_data['status_list']  = $this->judicial_person_model->status_list;
					$page_data['name_list']    = $this->admin_model->get_name_list();

					$where = array(
						'user_id'	=> $info->company_user_id,
						'status'	=> 1,
						'verify'	=> 1,
						'investor'	=> 0
					);
					$this->load->model('user/user_bankaccount_model');
					$user_bankaccount 	= $this->user_bankaccount_model->get_by($where);
					if($user_bankaccount){
						$page_data['bankaccount_id'] = $user_bankaccount->id;
					}

					// $face_data =json_decode($info->sign_video,true);
					$content = isset($info->sign_video) && json_decode($info->sign_video,true) ? json_decode($info->sign_video,true) : [];
					// 人臉比對
					$page_data['face_list'] = '';
					if($content){
			      $data['main_image'] = isset($content['person_image_url']) ? $content['person_image_url'] : '';
			      $data['sub_image'] = isset($content['image_url']) ? $content['image_url'] : '';

			      $data['main_title'] = '持證自拍照片';
			      $data['sub_title'] = '對保照片';
			      // 微軟
			      $data['azure']['main_data'] = ! empty($content['azure']['person']) ? $content['azure']['person'] : [];
			      $data['azure']['sub_data'] = ! empty($content['azure']['governmentauthorities']) ? $content['azure']['governmentauthorities'] : [];
			      $data['azure']['table'] = '';
			      // 微軟比較表
			      if(!empty($content['azure']['compare'])){
			        $table = [];
			        $num = count($data['azure']['main_data']);
			        $sub_num = count($data['azure']['sub_data']);

			        $table_info = array_chunk($content['azure']['compare'],$num);
			        for($i=0;$i<=$sub_num;$i++){
			          if($i==0){
			            $table[$i]['title'] = ' ';
			          }else{
			            $table[$i]['title'] = $data['sub_title'].'座標'.$i;
			          }
			          for($j=1;$j<=$num;$j++){
			            if($i==0){
			              $table[$i]['value'][] = $data['main_title'].'座標'.$j;
			            }else{
			              $table[$i]['value'][] = isset($table_info[$i-1][$j-1]['confidence']) ? $table_info[$i-1][$j-1]['confidence'] : '-';
			            }
			          }
			        }
			        $data['azure']['table'] = $this->load->view('admin/certification/ocr/total_table',['data' => $table],true);
			      }
			      // face++
			      $data['faceplusplus']['compare'] = ! empty($content['faceplusplus']['compare']) ? implode(', ',$content['faceplusplus']['compare']) : '-';
			      // papago
			      $data['papago']['main_data'] = ! empty($content['papago']['person']) ? $content['papago']['person'] : [];
			      $data['papago']['sub_data'] = ! empty($content['papago']['governmentauthorities']) ? $content['papago']['governmentauthorities'] : [];
			      // papago比較表
			      if(!empty($content['papago']['compare'])){
			        $table = [];
			        $num = count($data['papago']['main_data']['faces']);
			        $sub_num = count($data['papago']['sub_data']['faces']);

			        $table_info = array_chunk($content['papago']['compare'],$num);
			        for($i=0;$i<=$sub_num;$i++){
			          if($i==0){
			            $table[$i]['title'] = ' ';
			          }else{
			            $table[$i]['title'] = $data['sub_title'].'座標'.$i;
			          }
			          for($j=1;$j<=$num;$j++){
			            if($i==0){
			              $table[$i]['value'][] = $data['main_title'].'座標'.$j;
			            }else{
			              $table[$i]['value'][] = isset($table_info[$i-1][$j-1]['confidence']) ? $table_info[$i-1][$j-1]['confidence'] : '-';
			            }
			          }
			        }
			        $data['papago']['table'] = $this->load->view('admin/certification/ocr/total_table',['data' => $table],true);
			      }

			      $page_data['face_list'] = $this->load->view('admin/certification/component/face_confidence',$data,true);
					}
					$page_data['jid'] = $get['id'];
                    $page_data['company_type'] = $this->config->item('company_type');
                    $this->load->view('admin/_header');
					$this->load->view('admin/_title', $this->menu);

                    $this->load->view('admin/judicial_person/judicial_person_edit', $page_data);
                    $this->load->view('admin/_footer');
                } else {
                    alert('查無此ID', admin_url('edit?id='.$post['id']));
                }
            } else {
                alert('查無此ID', admin_url('edit?id='.$post['id']));
            }
        }else {
			if (!empty($post['id'])) {
				$info = $this->judicial_person_model->get($post['id']);
				$media_data = json_decode($info->sign_video, true);
				if ($info && !empty($media_data) ) {
					if ($post['status'] == '1') {
						$rs = $this->judicialperson_lib->apply_success($post['id']);
					} else if ($post['status'] == '2') {
						$rs = $this->judicialperson_lib->apply_failed($post['id']);
					}

					if ($rs === true) {
						alert('更新成功', 'edit?id='.$post['id']);
					} else {
						alert('更新失敗，請洽工程師', 'edit?id='.$post['id']);
					}
				} else if ($info && (empty($media_data) || !isset($media_data['judi_admin_video']) || !isset($media_data['judi_user_video']))) {
					alert('請先上傳法人或請負責人上傳對保影片', 'edit?id='.$post['id']);
				} else {
					alert('查無此ID', admin_url('edit?id='.$post['id']));
				}
			} else {
				alert('查無此ID', admin_url('edit?id='.$post['id']));
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
					$list[$key]->no_taishin = !$this->get_taishinAccount($value);
					$list[$key]->user_name = $user_info?$user_info->name:"";
					$list[$key]->cerCreditJudicial = $this->get_cerCreditJudicial($value->company_user_id);
					$this->checkAndCreat_borrow_bankaccount($value);
				}
			}
		}

		$page_data['selling_type'] 		= $this->config->item('selling_type');
		$page_data['list'] 				= $list;
		$page_data['cooperation_list'] 	= $this->judicial_person_model->cooperation_list;
		$page_data['status_list'] 		= isset($input['cooperation'])?$input['cooperation']:2;

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
					$info->no_taishin = !$this->get_taishinAccount($info);
                    $user_info = $this->user_model->get($info->user_id);
					$info->cerCreditJudicial = $this->get_cerCreditJudicial($info->company_user_id);
                    $this->load->library('Gcis_lib');
					$where = [
						'user_id' => $info->user_id,
						'status' => 1,
					];

					$this->load->model('user/user_bankaccount_model');
					$user_bankaccount 	= $this->user_bankaccount_model->get_by($where);
					if($user_bankaccount){
						$page_data['bankaccount_id'] = $user_bankaccount->id;
					}

					$page_data['bankbook_image'] = $this->user_bankaccount_model->get_by($where);
                    $page_data['company_data'] 	= $this->gcis_lib->account_info($info->tax_id);
                    $page_data['shareholders'] 	= $this->gcis_lib->get_shareholders($info->tax_id);
                    $page_data['user_info'] 	= $user_info;
					$page_data['data'] 			= $info;
					$page_data['selling_type'] 		= $this->config->item('selling_type');
                    $page_data['content'] 		= json_decode($info->cooperation_content,true);
                    $page_data['cooperation_list'] 	= $this->judicial_person_model->cooperation_list;
                    $page_data['company_type'] 		= $this->config->item('company_type');
                    $this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
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
					if(isset($post['create_taishin']) && $post['create_taishin'] == 1){
						if($info->cooperation!=1){
							$data['msg'] = '經銷商未開通';
						}
						else{
							$account = $this->get_taishinAccount($info);
							if(!$account){
								$rs = $this->virtual_account_model->insert([
									'investor'			=> 0,
									'user_id'			=> $info->company_user_id,
									'virtual_account'	=> TAISHIN_VIRTUAL_CODE.'0'.substr($info->tax_id,0,8),
								]);
								$data['msg'] = $rs?'建立成功':'建立失敗';
							}else{
								$data['msg'] = '已存在帳號';
							}
						}
					}elseif ($post['cooperation'] == '1') {
                        $rs = $this->judicialperson_lib->cooperation_success($post['id']);
						$data['msg'] = $rs?'修改成功':'修改失敗';
                    } else if ($post['cooperation'] == '0') {
                        $rs = $this->judicialperson_lib->cooperation_failed($post['id']);
						$data['msg'] = $rs?'變更成功':'變更失敗';
					}
					alert($data['msg'],admin_url('judicialperson/cooperation_edit?id='.$post['id']));
					//echo json_encode($data);
                } else {
                    alert('查無此ID', admin_url('cooperation?cooperation=2'));
                }
            }
            else {
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
				$account = $this->get_taishinAccount($info);
				if(!$account){
					$rs = $this->virtual_account_model->insert([
						'investor'			=> 0,
						'user_id'			=> $info->company_user_id,
						'virtual_account'	=> TAISHIN_VIRTUAL_CODE.'0'.substr($info->tax_id,0,8),
					]);
					$data['msg'] = $rs?'建立成功':'建立失敗';
				}
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

	private function get_cerCreditJudicial($user_id){
		$this->load->library('certification_lib');
		return $this->certification_lib->get_certification_info($user_id,CERTIFICATION_CERCREDITJUDICIAL,0);
	}

	private function get_taishinAccount($data){
		if(in_array($data->selling_type,$this->config->item('use_taishin_selling_type'))){
			return  $this->virtual_account_model->get_by(array(
				'investor' => 0,
				'user_id' => $data->company_user_id,
				'virtual_account' => TAISHIN_VIRTUAL_CODE.'0'.$data->tax_id,
			));
		}
		return false;
	}

	private function checkAndCreat_borrow_bankaccount($data){
		if(in_array($data->selling_type,$this->config->item('use_borrow_account_selling_type'))) {
			$user_bankaccount = $this->user_bankaccount_model->get_many_by([
				'user_id' => $data->company_user_id,
			]);
			if (count($user_bankaccount) == 1) {
				$user_bankaccount[0]->investor = 0;
				$this->user_bankaccount_model->insert([
					"user_id" => $user_bankaccount[0]->user_id,
					"investor" => 0,
					"user_certification_id" => $user_bankaccount[0]->user_certification_id,
					"bank_code" => $user_bankaccount[0]->bank_code,
					"branch_code" => $user_bankaccount[0]->branch_code,
					"bank_account" => $user_bankaccount[0]->bank_account,
					"front_image" => $user_bankaccount[0]->front_image,
					"back_image" => $user_bankaccount[0]->back_image,
					"verify" => 2,
				]);
			}
		}
	}
}
?>
