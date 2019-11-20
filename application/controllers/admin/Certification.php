<?php
//use function GuzzleHttp\json_decode;

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
				if ($info) {
					$certification 						= $this->certification[$info->certification_id];
					$page_data['certification_list'] 	= $this->certification_name_list;
					$page_data['data'] 					= $info;
					$page_data['content'] 				= json_decode($info->content, true);

					if ($info->certification_id == 2) {
						//加入SIP網址++
						$school_data = trim(file_get_contents('https://influxp2p-front-assets.s3-ap-northeast-1.amazonaws.com/json/school_with_loaction.json'), "\xEF\xBB\xBF");
						$school_data = json_decode($school_data, true);
						$school = $page_data['content']['school'];
						$sipURL = isset($school_data[$school]['sipURL']) ? $school_data[$school]['sipURL'] : '';
						$page_data['content']['sipURL'] = isset($sipURL) ? $sipURL : "";
						//加入SIP網址--
					}
                    if ($info->certification_id == 9) {
                        if(json_decode($info->content)->return_type!==0){
							$this->joint_credits();
							return;
                        }
                    }
					$page_data['id'] 					= $id;
					$page_data['remark'] 				= json_decode($info->remark, true);
					$page_data['status_list'] 			= $this->user_certification_model->status_list;
					$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
					$page_data['school_system'] 		= $this->config->item('school_system');
					$page_data['certifications_msg'] 		= $this->config->item('certifications_msg');
					if ($info->certification_id == 10) {
						$page_data['employee_range'] 		= $this->config->item('employee_range');
						$page_data['position_name']			= $this->config->item('position_name');
						$page_data['seniority_range'] 		= $this->config->item('seniority_range');
						$page_data['industry_name'] 		= $this->config->item('industry_name');
						$page_data['job_type_name'] 		= $this->config->item('job_type_name');
						if (isset($page_data['content']['job_title'])) {
							$job_title = file_get_contents('https://influxp2p-front-assets.s3-ap-northeast-1.amazonaws.com/json/cert_title.json');
							$page_data['job_title'] = preg_split('/"},{/', preg_split('/' . $page_data['content']['job_title'] . '","des":"/', $job_title)[1])[0];
							if (isset($page_data['content']['programming_language'])) {
								$languageList = json_decode(trim(file_get_contents('https://influxp2p-front-assets.s3-ap-northeast-1.amazonaws.com/json/config_techi.json'), "\xEF\xBB\xBF"))->languageList;
								$set_lang_level = ['入門', '參與開發', '獨立執行'];
								foreach ($page_data['content']['programming_language'] as $lang_list => $lang) {
									$lang_level = ' (' . $set_lang_level[$lang['level'] - 1] . ')';
									$lang['id'] != '' ? $techie_lang[] = $languageList->{$lang['id']} . $lang_level : $other_lang[] = $lang['des'] . $lang_level;
								}
								$page_data['techie_lang'] = isset($techie_lang) ? $techie_lang : '';
								$page_data['other_lang']  = isset($other_lang) ? $other_lang : '';
							}
						}
					} elseif ($info->certification_id == 2) {
						if (isset($page_data['content']['programming_language'])) {
							$languageList = json_decode(trim(file_get_contents('https://influxp2p-front-assets.s3-ap-northeast-1.amazonaws.com/json/config_techi.json'), "\xEF\xBB\xBF"))->languageList;
							$set_lang_level = ['入門', '參與開發', '獨立執行'];
							foreach ($page_data['content']['programming_language'] as $lang_list => $lang) {
								$lang_level = ' (' . $set_lang_level[$lang['level'] - 1] . ')';
								$lang['id'] != '' ? $techie_lang[] = $languageList->{$lang['id']} . $lang_level : $other_lang[] = $lang['des'] . $lang_level;
							}
							$page_data['techie_lang'] = isset($techie_lang) ? $techie_lang : '';
							$page_data['other_lang']  = isset($other_lang) ? $other_lang : '';
						}
					}
					$page_data['from'] 					= $from;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title', $this->menu);
					$this->load->view('admin/certification/' . $certification['alias'], $page_data);
					$this->load->view('admin/_footer');
				} else {
					alert('ERROR , id is not exist', $back_url);
				}
			} else {
				alert('ERROR , id is not exist', $back_url);
			}
		}else{
            if(!empty($post['salary'])){
                $id = $post['id'];
                $info = $this->user_certification_model->get($id);
                $content = json_decode($info->content,true);
                $content['salary'] = $post['salary'];
                $this->user_certification_model->update($id,['content'=>json_encode($content)]);
                $param = [
                    'user_id'		=> $info->user_id,
                    'meta_key' 		=> 'job_salary',
                ];
                $this->user_meta_model->update_by($param,['meta_value'	=> $content['salary']]);

                //失效信評分數
                $this->load->model('loan/credit_model');
                $credit_list = $this->credit_model->get_many_by([
                    'user_id'    =>$info->user_id,
                    'product_id' =>[3,4],
                    'status'     => 1
                ]);
                foreach($credit_list as $ckey => $cvalue){
					//信用低落
                    if(!in_array($cvalue->level,[11,12,13])){
                        $this->credit_model->update_by(
                            ['id'    => $cvalue->id],
                            ['status'=> 0]
                        );
                    }
                }

                //退案件狀態
                $this->load->library('target_lib');
                $targets = $this->target_model->get_many_by(array(
                    'user_id'   => $info->user_id,
                    'product_id' =>[3,4],
                    'status'	=> array(1,2)
                ));
                if($targets){
                    $param = [
                        'status'      => 0,
                    ];
                    $this->load->library('Target_lib');
                    foreach($targets as $key => $value){
                        $this->target_model->update($value->id,$param);
                        $this->target_lib->insert_change_log($value->id,$param);
                    }
                }
                alert('更新成功','user_certification_edit?id='.$id);
            }elseif(!empty($post['name'])){
				$id = $post['id'];
				$info = $this->user_certification_model->get($id);
				$content = json_decode($info->content,true);
				$content['name'] = $post['name'];
				$this->user_certification_model->update($id,['content
				'=>json_encode($content)]);
				alert('更新成功','user_certification_edit?id='.$id);
			}elseif(!empty($post['address'])){
				$id = $post['id'];
				$info = $this->user_certification_model->get($id);
				$content = json_decode($info->content,true);
				$content['address'] = $post['address'];
				$this->user_certification_model->update($id,['content'=>json_encode($content)]);
				alert('更新成功','user_certification_edit?id='.$id);
			}elseif(!empty($post['company'])){
				$id = $post['id'];
				$info = $this->user_certification_model->get($id);
				$content = json_decode($info->content,true);
				$content['company'] = $post['company'];
				$this->user_certification_model->update($id,['content'=>json_encode($content)]);
				alert('更新成功','user_certification_edit?id='.$id);
			}elseif(!empty($post['id'])){
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
							$license_status = 0;
							$pro_level = 0;
							$content 					= json_decode($info->content,TRUE);
							if(isset($post['license_status'])){
								$license_status = is_numeric($post['license_status'])&&$post['license_status']<=3?$post['license_status']:0;
							}
							if(isset($post['pro_level'])){
								$pro_level = is_numeric($post['pro_level'])&&$post['pro_level']<=5?$post['pro_level']:0;
							}
							$content['license_status'] 	= $license_status;
							$content['pro_level'] 		= $pro_level;
							$this->user_certification_model->update($post['id'],['content'=>json_encode($content)]);
						}
						elseif($info->certification_id==9){
							$content 					= json_decode($info->content,TRUE);
							$content['times'] 			= isset($post['times'])?intval($post['times']):0;
							$content['credit_rate'] 	= isset($post['credit_rate'])?floatval($post['credit_rate']):0;
							$content['months'] 			= isset($post['months'])?intval($post['months']):0;
							$this->user_certification_model->update($post['id'],['content'=>json_encode($content)]);

						}elseif($info->certification_id==2){
							$license_level = 0;
							$game_work_level = 0;
							$pro_level = 0;
							$content 					= json_decode($info->content,TRUE);
							if(isset($post['license_level'])){
								$license_level = is_numeric($post['license_level'])&&$post['license_level']<=2?$post['license_level']:0;
							}
							if(isset($post['game_work_level'])){
								$game_work_level = is_numeric($post['game_work_level'])&&$post['game_work_level']<=2?$post['game_work_level']:0;
							}
							if(isset($post['pro_level'])){
								$pro_level = is_numeric($post['pro_level'])&&$post['pro_level']<=3?$post['pro_level']:0;
							}
							$content['license_level'] 	= $license_level;
							$content['game_work_level'] = $game_work_level;
							$content['pro_level'] 		= $pro_level;
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
			}
			else{
				alert('ERROR , id is not exist',$back_url);
			}
		}
	}


	public function user_bankaccount_list(){
		$page_data 			= array('type'=>'list','list'=>array());
		$input 				= $this->input->get(NULL, TRUE);
		$where				= array();//'status'=> 1

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
		$page_data['sys_check_list']     = $this->user_bankaccount_model->sys_check_list;
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

	public function joint_credits(){
	    $get = $this->input->get(NULL, TRUE);

	    $id = isset($get["id"]) ? intval($get["id"]) : 0;

	    if ($this->input->is_ajax_request()) {
	        $this->load->library('output/json_output');
	        if ($id <= 0) {
	            $this->json_output->setStatusCode(204)->send();
	        }

	        $certification = $this->user_certification_model->get($id);
			if (!$certification) {
				$this->json_output->setStatusCode(204)->send();
			}

			$user = $this->user_model->get($certification->user_id);
			$this->load->library('output/user/user_output', ["data" => $user]);

	        $joint_credits = json_decode($certification->content);
			$this->load->library('output/user/joint_credit_output', ["data" => $joint_credits->result, "certification" => $certification]);
	        $response = [
				"user" => $this->user_output->toOne(),
				"joint_credits" => $this->joint_credit_output->toOne(),
				"statuses" => $this->user_certification_model->status_list
			];

	        $this->json_output->setStatusCode(200)->setResponse($response)->send();
	    }
	    $this->load->view('admin/_header');
	    $this->load->view('admin/_title',$this->menu);
	    $this->load->view('admin/certification/joint_credits');
	    $this->load->view('admin/_footer');
	}
}
?>
