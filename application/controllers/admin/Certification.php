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
		'difficult_word_edit',
		'judicial_yuan_case'
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
		$back_url 	= admin_url('close');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		if(empty($post)){
			$id 	= isset($get['id'])?intval($get['id']):0;
			$cid 	= isset($get['cid'])?intval($get['cid']):0;
			$from 	= isset($get['from'])?$get['from']:'';
			if(!empty($from)){
				$back_url = admin_url('close');
			}
			if($id||$cid){
				$info = $this->user_certification_model->get($id);
				if($info){
					$certification = $this->certification[$info->certification_id];
					$page_data['certification_list'] = $this->certification_name_list;
					$page_data['data'] = $info;
					$page_data['content'] = json_decode($info->content, true);
				}
				if($cid == CERTIFICATION_CERCREDITJUDICIAL || $info->certification_id == CERTIFICATION_CERCREDITJUDICIAL){
					$selltype = isset($get['selltype'])?$get['selltype']:0;
					$user_id = isset($get['user_id'])?$get['user_id']:0;
					$new = true;
					if($info){
						$user_id = $info->user_id;
						$cid = $info->certification_id;
						$this->load->model('user/judicial_person_model');
						$list = $this->judicial_person_model->get_by(['company_user_id' => $user_id]);
						$list?$selltype=$list->selling_type:'';
						$new = false;
					}
					$this->config->load('credit');
					$creditJudicial = $this->config->item('creditJudicial');
					if(isset($creditJudicial[$selltype])){
						$certification = $this->certification[$cid];
						$page_data['user_id'] = $user_id;
						$page_data['selltype'] = $selltype;
						$page_data['selling_type'] = $this->config->item('selling_type')[$selltype];
						$page_data['cid'] = $cid;
						$page_data['data'] = $info;
						$page_data['content'] = isset($info->content)?json_decode($info->content,true):false;
						$page_data['creditJudicialConfig'] = $creditJudicial[$selltype];
						$page_data['certification_list'] = $this->certification_name_list;
						$page_data['certifications_msg'] 		= $this->config->item('certifications_msg');

						$this->load->view('admin/_header');
						$this->load->view('admin/_title',$this->menu);
						$this->load->view('admin/certification/'.$certification['alias'],$page_data);
						$this->load->view('admin/_footer');
						return true;
					}
					alert('此廠商類別無報告樣板',base_url('admin/Judicialperson/cooperation?cooperation=1'));
				}
				elseif($info->certification_id == CERTIFICATION_STUDENT) {
					//加入SIP網址++
					$school_data = trim(file_get_contents(FRONT_CDN_URL.'json/school_with_loaction.json'), "\xEF\xBB\xBF");
					$school_data = json_decode($school_data, true);
					$school = $page_data['content']['school'];
					$sipURL = isset($school_data[$school]['sipURL']) ? $school_data[$school]['sipURL'] : '';
					$page_data['content']['sipURL'] = isset($sipURL) ? $sipURL : "";
					//加入SIP網址--

				}elseif ($info->certification_id == CERTIFICATION_INVESTIGATION) {
					$content = json_decode($info->content);
					$page_data['report_page'] = '';

					// initialize
					$report_data['type'] = 'person';
					$report_data['data'] = [];

					if ($content->return_type !== 0 && isset($content->pdf_file) && isset($content->result)) {
						//聯徵檔案報告產生
						$info_content = json_decode($info->content, true);
						if($info_content){
							$group_id = isset($info_content['group_id']) ? $info_content['group_id'] : '';
							if(isset($info_content['result'][$group_id])){
								$report_data['type'] = 'person';
								$report_data['data'] = $info_content['result'][$group_id];
								// 還款力計算
								// 薪資22倍
								$report_data['data']['total_repayment'] = $info_content['total_repayment'];
								// 投保金額
								$report_data['data']['monthly_repayment'] = $info_content['monthly_repayment'];
								// 借款總額是否小於薪資22倍
								$report_data['data']['total_repayment_enough'] = $info_content['total_repayment_enough'];
								// 每月還款是否小於投保金額
								$report_data['data']['monthly_repayment_enough'] = $info_content['monthly_repayment_enough'];

								// 負債比
								if(isset($info_content['debt_to_equity_ratio'])) {
									$report_data['data']['debt_to_equity_ratio'] = $info_content['debt_to_equity_ratio'];
								}else
									$report_data['data']['debt_to_equity_ratio'] = round(floatval($report_data['data']['totalMonthlyPayment']) / floatval($report_data['data']['monthly_repayment']) * 100, 2);

								$convertToIntegerList = ['liabilities_totalAmount', 'total_repayment'];
								foreach($convertToIntegerList as $key) {
									preg_match('/(\d+[,]*)+/', $report_data['data'][$key], $regexResult);
									if (!empty($regexResult)) {
										$multiplier = 1;
										if (preg_match('/千元/', $report_data['data'][$key], $resultForThousand))
											$multiplier = 1000;
										$report_data['data'][$key] = floatval(str_replace(",", "", $regexResult[0])) * $multiplier;
									}
								}

							}
						}
						// $this->joint_credits();
						// return;
					}
					$page_data['report_page'] = $this->load->view('admin/certification/component/joint_credit_report', $report_data , true);
				}
				elseif($info->certification_id == CERTIFICATION_JOB){
					// if(isset(json_decode($info->content)->pdf_file)) {
					// 	$this->job_credits();
					// 	return;
					// }
					$page_data['employee_range'] 		= $this->config->item('employee_range');
					$page_data['position_name']			= $this->config->item('position_name');
					$page_data['seniority_range'] 		= $this->config->item('seniority_range');
					$page_data['industry_name'] 		= $this->config->item('industry_name');
					$page_data['job_type_name'] 		= $this->config->item('job_type_name');
					if (isset($page_data['content']['job_title'])){
						$job_title = file_get_contents(FRONT_CDN_URL.'json/cert_title.json');
						$cut  = preg_split('/'.$page_data['content']['job_title'].'","des":"/',$job_title);
						$page_data['job_title'] = isset($cut[1]) ? preg_split('/"},{/',preg_split('/'.$page_data['content']['job_title'].'","des":"/',$job_title)[1])[0] : '' ;
					}
				}

				if(isset($page_data['content']['programming_language'])){
					$languageList = json_decode(trim(file_get_contents(FRONT_CDN_URL.'json/config_techi.json'), "\xEF\xBB\xBF"))->languageList;
					$set_lang_level =['入門','參與開發','獨立執行'];
					$programming_language = json_decode($page_data['content']['programming_language']);
					if(count($programming_language) > 0){
						foreach($programming_language as $lang_list => $lang){
							$lang_level = ' ('.$set_lang_level[$lang->level-1].')';
							$lang->id != '' ? $techie_lang[] = $languageList->{$lang->id} . $lang_level : $other_lang[] = $lang->des . $lang_level;
						}
					}
					$page_data['techie_lang'] = isset($techie_lang) ? $techie_lang : false;
					$page_data['other_lang']  = isset($other_lang) ? $other_lang : false;
				}
				$page_data['id'] 					= $id;
				$page_data['remark'] 				= json_decode($info->remark,true);
				$page_data['status_list'] 			= $this->user_certification_model->status_list;
				$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
				$page_data['school_system'] 		= $this->config->item('school_system');
				$page_data['certifications_msg'] 		= $this->config->item('certifications_msg');
				$page_data['from'] 					= $from;
				$page_data['sys_check'] 			= $info->sys_check;
				$this->load->view('admin/_header');
				$this->load->view('admin/_title', $this->menu);
				$this->load->view('admin/certification/' . $certification['alias'], $page_data);
				$this->load->view('admin/_footer');
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
			}elseif (!empty($post['cid'])){
				$certification_id 	= $post['cid'];
				$certification 		= $this->certification[$certification_id];
				if($certification){
					$user_id = $post['id'];
					$selltype = $post['selltype'];
					$content	= [];

					$this->load->library('Certification_lib');
					$user_certification	= $this->certification_lib->get_certification_info($user_id,$certification_id,0);
					if(!$user_certification || $user_certification->status == 2){
						$this->config->load('credit');
						$creditJudicial = $this->config->item('creditJudicial');
						if(isset($creditJudicial[$selltype])){
							foreach ($creditJudicial[$selltype] as $key => $value) {
								if($value['selctType'] == 'select' && isset($post[$key])){
									$content[$key] = $post[$key];
								}elseif ($value['selctType'] == 'radio'){
									foreach ($value['descrtion'] as $descrtionKey => $descrtionValue) {
										if(isset($post[$descrtionValue[0]])){
											$content[$descrtionValue[0]] = $post[$descrtionValue[0]];
										}
										else{
											$data['msg'] = '報告產生異常';
										}
									}
								}
							}
							$insert = $this->user_certification_model->insert([
								'user_id'			=> $user_id,
								'certification_id'	=> $certification_id,
								'investor'			=> 0,
								'content'			=> json_encode($content),
							]);
							$insert = $this->certification_lib->set_success($insert);
							if($insert){
								$data['msg'] = '報告儲存成功';
							}else{
								$data['msg'] = '報告儲存失敗';
							}
						}
					}else{
						$data['msg'] = '報告已存在';
					}
				}
				$data['redirect'] = base_url('admin/Judicialperson/cooperation?cooperation=1');
				print(json_encode($data));
				return false;
			}elseif(!empty($post['id'])){
				$from 	= isset($post['from'])?$post['from']:'';
				$fail 	= isset($post['fail'])?$post['fail']:'';
				if(!empty($from)){
					//$back_url = admin_url($from);
					$back_url = admin_url('close');
				}

				$info = $this->user_certification_model->get($post['id']);
				if($info){
					$certification = $this->certification[$info->certification_id];
					if($certification['alias']=='debitcard'){
						alert('金融帳號認證請至 金融帳號驗證區 操作',$back_url);
					}else{
						if ($info->certification_id == CERTIFICATION_JOB){
							$license_status = 0;
							$game_work_level = 0;
							$pro_level = 0;
							$content 					= json_decode($info->content,TRUE);
							$remark						= json_decode($info->remark, TRUE);
							if(!is_array($remark)) {
								$remark = [isset($remark) ? strval($remark) : ''];
							}
							$remark['verify_result'] 	= [$fail];
							if(isset($post['license_status'])){
								$license_status = is_numeric($post['license_status'])&&$post['license_status']<=3?$post['license_status']:0;
							}
							if(isset($post['game_work_level'])){
								$game_work_level = is_numeric($post['game_work_level'])&&$post['game_work_level']<=2?$post['game_work_level']:0;
							}
							if(isset($post['pro_level'])){
								$pro_level = is_numeric($post['pro_level'])&&$post['pro_level']<=5?$post['pro_level']:0;
							}
							$content['license_status'] 	= $license_status;
							$content['game_work_level'] = $game_work_level;
							$content['pro_level'] 		= $pro_level;
							isset($post['printDate']) && !empty($post['printDate'])?$content['printDate'] = $post['printDate'] : '';
							$expiretime = isset($post['printDate']) ? strtotime('+ 30 days',strtotime($post['printDate'])) : strtotime('+ 30 days',time());
							$expiretime < time() ? $post['status'] = 2 : '';
							$this->user_certification_model->update($post['id'],[
								'content'=> json_encode($content,JSON_INVALID_UTF8_IGNORE),
								'remark' => json_encode($remark),
								'expire_time'=>$expiretime,
							]);
							if($post['status'] == 2) {
								// 退工作認證時，需把聯徵也一起退掉 issue #1202
								$this->load->library('Certification_lib');
								$this->certification_lib->withdraw_investigation($info->user_id, $info->investor);
							}
						} elseif ($info->certification_id == CERTIFICATION_INVESTIGATION) {
							$content 					= json_decode($info->content,TRUE);
							$content['times'] 			= isset($post['times'])?intval($post['times']):0;
							$content['credit_rate'] 	= isset($post['credit_rate'])?floatval($post['credit_rate']):0;
							$content['months'] 			= isset($post['months'])?intval($post['months']):0;
							isset($post['printDate']) && !empty($post['printDate'])?$content['printDate'] = $post['printDate'] : '';
							$expiretime = isset($post['printDate']) ? strtotime('+ 30 days',strtotime($post['printDate'])) : strtotime('+ 30 days',time());
							$expiretime < time() ? $post['status'] = 2 : '';
							$this->user_certification_model->update($post['id'],[
								'content'=>json_encode($content),
								'expire_time'=>$expiretime,
							]);

						} elseif ($info->certification_id == CERTIFICATION_STUDENT) {
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
						} elseif ($info->certification_id == CERTIFICATION_CERCREDITJUDICIAL) {
							$fail = '評估表已失效';
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
							$rs = $this->user_certification_model->update($post['id'],array(
								'status' => intval($post['status']),
								'sys_check' => 0,
							));
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
				if($field == 'investor' && $input['investor'] ==3){
					$where['investor']   = 1;
					$where['back_image'] = '';
				}elseif($field == 'investor' && $input['investor'] ==2){
                    $where['investor']   = 0;
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
				if($user){
					$list[$key]->user_name 		= $user->name;
					$list[$key]->user_name_list = $user->name?mb_str_splits($user->name):'';
				}
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

				// 如果是借款人的金融帳號通過，才需要對案件進行處理
				if($info->investor == 0) {
					$this->load->library('target_lib');
					$target = $this->target_model->get_by([
						'user_id' => $info->user_id,
						'status' => TARGET_WAITING_VERIFY,
					]);
					$product_list = $this->config->item('product_list');
					$product = $product_list[$target->product_id];
					$sub_product_id = $target->sub_product_id;
					if ($this->is_sub_product($product, $sub_product_id)) {
						$product = $this->trans_sub_product($product, $sub_product_id);
					}

					$allow_fast_verify_product = $this->config->item('allow_fast_verify_product');
					if (in_array($target->product_id, $allow_fast_verify_product)
						&& $target->sub_product_id != STAGE_CER_TARGET
						&& $target->sub_status != 8
					) {
						$targetData = json_decode($target->target_data);
						$faceDetect = isset($targetData->autoVerifyLog)
							? count($targetData->autoVerifyLog) >= 2
								? false : true
							: true;
						if ($faceDetect) {
							$this->load->library('certification_lib');
							$faceDetect_res = $this->certification_lib->veify_signing_face($target->user_id, $target->person_image);
							if ($faceDetect_res['error'] == '') {
								$target->status = TARGET_WAITING_VERIFY;
								$targetData->autoVerifyLog[] = [
									'faceDetect' => $faceDetect_res,
									'res' => TARGET_WAITING_BIDDING,
									'verify_at' => time()
								];
								$param['target_data'] = json_encode($targetData);
								$this->target_lib->target_verify_success($target, 0, $param);
							} else {
								$targetData->autoVerifyLog[] = [
									'faceDetect' => $faceDetect_res,
									'res' => TARGET_WAITING_SIGNING,
									'verify_at' => time()
								];
								$param['target_data'] = json_encode($targetData);
								$this->target_lib->target_sign_failed($target, 0, $product['name'], $param);
							}
						}
					}
				}
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

	public function job_credits(){
		$get = $this->input->get(NULL, TRUE);
		isset($get['id'])?intval($get['id']):0;
		$id = isset($get["id"]) ? intval($get["id"]) : 0;
		$info = $this->user_certification_model->get($id);
		if ($info) {
			$page_data['status'] = ($info->status);
			isset((json_decode($info->content, true))['printDate']) ? $page_data['printDate'] = (json_decode($info->content, true))['printDate']:0;
		}
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
			$this->load->model('user/user_meta_model');


			$job_credits = json_decode($certification->content);
			$certification->content = $job_credits;
			$certification->content->industry = $this->config->item('industry_name')[$certification->content->industry];
			$certification->content->seniority = $this->config->item('seniority_range')[$certification->content->seniority];
			$certification->content->employee = $this->config->item('employee_range')[$certification->content->employee];
			$certification->content->position = $this->config->item('position_name')[$certification->content->position];
			$certification->content->type = $this->config->item('job_type_name')[$certification->content->type];

			$this->load->library('output/user/job_credit_output', [
				"data" => (isset($job_credits->result) ? $job_credits->result : false),
				"certification" => $certification
			]);
			$remark  = isset(json_decode($certification->remark)->fail) ?  json_decode($certification->remark)->fail: '';
			$response = [
				"user" => $this->user_output->toOne(),
				"job_credits" => $this->job_credit_output->toOne(),
				"statuses" => $this->user_certification_model->status_list,
				"remark" => $remark,
				"fail_msg" => $this->config->item('certifications_msg')[10],
			];

			$this->json_output->setStatusCode(200)->setResponse($response)->send();
		}
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/certification/job',$page_data);
		$this->load->view('admin/_footer');
	}

	public function joint_credits(){
	    $get = $this->input->get(NULL, TRUE);
		isset($get['id'])?intval($get['id']):0;
		$id = isset($get["id"]) ? intval($get["id"]) : 0;
		$info = $this->user_certification_model->get($id);
		if ($info) {
			$page_data['times'] 				= isset((json_decode($info->content, true))['times'])?(json_decode($info->content, true))['times']:0;
			$page_data['credit_rate'] 				= isset((json_decode($info->content, true))['credit_rate'])?(json_decode($info->content, true))['credit_rate']:0;
			$page_data['months'] 				= isset((json_decode($info->content, true))['months'])?(json_decode($info->content, true))['months']:0;
			$page_data['status'] 				= ($info->status);
			isset((json_decode($info->content, true))['printDate']) ? $page_data['printDate'] = (json_decode($info->content, true))['printDate']:0;
		}

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
			$certification->content = $joint_credits;
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
	    $this->load->view('admin/certification/joint_credits',$page_data);
	    $this->load->view('admin/_footer');
	}

    public function sip()
    {
        $input = $this->input->get(NULL, TRUE);
        $university = isset($input['university']) ? $input['university'] : '';
        $account = isset($input['account']) ? $input['account'] : '';
        $this->load->library('scraper/sip_lib');
        $loginInfo = $this->sip_lib->getSipLogin($university, $account);

        $this->load->library('output/json_output');
        if (!$loginInfo) {
            $this->json_output->setStatusCode(204)->send();
        }

        $response = ["sip" => json_decode(json_encode($loginInfo), true)];
        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function sip_login()
    {
        $input = $this->input->post(NULL, TRUE);
        $university = isset($input['university']) ? $input['university'] : '';
        $account = isset($input['account']) ? $input['account'] : '';
        $password = isset($input['password']) ? $input['password'] : '';

        $this->load->library('output/json_output');
        if (!$university || !$account || !$password) {
            $this->json_output->setStatusCode(400)->send();
        }

        $this->load->library('scraper/sip_lib.php');
        $this->sip_lib->requestSipLogin(
            $university,
            $account,
            $password
        );

        $this->json_output->setStatusCode(200)->send();
    }

    public function migrate_ocr()
    {
        $get = $this->input->get(NULL, TRUE);
        $certification = isset($get['certification']) ? $get['certification'] : '';
        $startAt = isset($get['start_at']) ? $get['start_at'] : 0;
        $endAt = isset($get['end_at']) ? $get['end_at'] : 0;
        $offset = isset($get['offset']) ? $get['offset'] : 1;
        $limit = isset($get['limit']) ? $get['limit'] : 20;

        $this->load->library('output/json_output');

        $certificationId = 0;
        if ($certification == 'id_card') {
            $certificationId = 1;
        } else {
            $this->json_output->setStatusCode(400)->send();
        }

        $where = ["certification_id" => $certificationId];
        if ($startAt > 0) {
            $where["updated_at >="] = $startAt;
        }
        if ($endAt > 0) {
            $where["updated_at <="] = $endAt;
        }

        $skipBy = ($offset - 1) * $limit;

        $this->load->model('user/user_certification_model');
        $certifications = $this->user_certification_model->limit($limit, $skipBy)->get_many_by($where);

        if (!$certifications) {
            $this->json_output->setStatusCode(204)->send();
        }

        $this->load->model('mongo/ocr_model');

        $textComparisons = ["name", "id_number", "id_card_date", "id_card_place", "birthday", "address"];
        $faceComparisons = ["face", "face_flag", "face_plus", "face_count"];
        foreach ($certifications as $certification) {
            $content = json_decode($certification->content);
            $remark = json_decode($certification->remark);

            $ocr = [
                'reference' => md5($certification->user_id . "-" . $certification->id),
                'certification' => $certificationId,
                'pass' => $certification->sys_check == 1
            ];
            if (isset($remark->OCR) && $remark->OCR) {
                foreach ($textComparisons as $key) {
                    $ocr["comparison"][$key] = false;
                    if (isset( $remark->OCR->$key) && $content->$key == $remark->OCR->$key) {
                        $ocr["comparison"][$key] = true;
                    }
                }
            }

            foreach ($faceComparisons as $key) {
                $ocr["comparison"][$key] = [];
                if (isset($remark->$key) && $remark->$key) {
                    $ocr["comparison"][$key] = $remark->$key;
                }
            }

            $this->ocr_model->save($ocr);
        }

        $this->json_output->setStatusCode(200)->send();
    }

		public function verdict_statuses(){
			$input = $this->input->get(NULL, TRUE);
			$reference = isset($input['user_id']) ? $input['user_id'] : '';

			$this->load->library('output/json_output');

			if(!$reference){
				$this->json_output->setStatusCode(400)->send();
			}

			$this->load->library('scraper/judicial_yuan_lib.php');
			$verdict_statuses = $this->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($reference);

			if(!$verdict_statuses){
				$this->json_output->setStatusCode(400)->send();
			}

			$response = json_decode(json_encode($verdict_statuses), true);

			if($response['status']=='204'){
				$this->json_output->setStatusCode(204)->send();
			}

			$response = ["verdict_statuses" => $response];

			$this->json_output->setStatusCode(200)->setResponse($response)->send();
		}

		public function verdict_count(){
			$input = $this->input->get(NULL, TRUE);
			$name = isset($input['name']) ? $input['name'] : '';

			$this->load->library('output/json_output');

			if(!$name){
				$this->json_output->setStatusCode(400)->send();
			}

			$this->load->library('scraper/judicial_yuan_lib.php');
			$verdict_count = $this->judicial_yuan_lib->requestJudicialYuanVerdictsCount(urlencode($name));

			if(!$verdict_count){
				$this->json_output->setStatusCode(400)->send();
			}

			$response = json_decode(json_encode($verdict_count), true);

			if($response['status']=='204'){
				$this->json_output->setStatusCode(204)->send();
			}

			$response = ["verdict_count" => $response];

			$this->json_output->setStatusCode(200)->setResponse($response)->send();
		}

		public function verdict(){
			$input = $this->input->get(NULL, TRUE);
			$name = isset($input['name']) ? $input['name'] : '';
			$address = isset($input['address']) ?  $input['address']: '';
			$user_id = isset($input['user_id']) ? $input['user_id'] : '';

			$this->load->library('output/json_output');

			if(!$name || !$address || !$user_id){
				$this->json_output->setStatusCode(400)->send();
			}

			$this->load->library('scraper/judicial_yuan_lib.php');
			$scraper_response = $this->judicial_yuan_lib->requestJudicialYuanVerdicts($name, $address, $user_id);

			if(!$scraper_response){
				$this->json_output->setStatusCode(400)->send();
			}

			if($scraper_response['status']=='201'){
				$this->json_output->setStatusCode(201)->send();
			}

			$this->json_output->setStatusCode(200)->send();
		}

	public function get_papagoface_report()
	{
		$get = $this->input->get(NULL, TRUE);
		$limit = isset($get['limit']) ? $get['limit'] : 10;
		$this->load->library('Certification_lib');
		$cell = $this->certification_lib->papago_facedetact_report($limit);
		$this->load->library('Phpspreadsheet_lib');
		$mergeTitle = [
			'2:4' => 'Azure',
			'5:7' => 'Face++',
			'8:10' => 'Face8',
		];
		$sheetTItle = ['user_id', '發證日期', '人臉數', 'face1準確度', 'face2準確度', '人臉數', 'face1準確度', 'face2準確度', '人臉數', 'face1準確度', 'face2準確度'];
		$contents[] = [
			'sheet' => 'PAPAGO FACE8測試',
			'title' => $sheetTItle,
			'content' => $cell,
		];
		$file_name = date("YmdHis", time()) . '_PAPAGO';
		$descri = '普匯inFlux 後台管理者 ' . $this->login_info->id . ' [ 債權管理查詢 ]';
		$this->phpspreadsheet_lib->excel($file_name, $contents, '本金餘額攤還表', '各期金額', $descri, $this->login_info->id, true, false, false, $mergeTitle);
	}

	public function get_social_report()
	{
		$get = $this->input->get(NULL, TRUE);
		$limit = isset($get['limit']) ? $get['limit'] : 10;
		$this->load->library('Certification_lib');
		$cell = $this->certification_lib->get_social_report($limit);
		$this->load->library('Phpspreadsheet_lib');
		$sheetTItle = ['user_id', '性別', '逾期用戶', '貼文總數', '用戶粉絲人數', '用戶追蹤中人數', '時間', '內文', '按讚數'];
		$contents[] = [
			'sheet' => '社交認證',
			'title' => $sheetTItle,
			'content' => $cell,
		];
		$file_name = date("YmdHis", time()) . '_Certificatuin_analysis';
		$descri = '普匯inFlux 後台管理者 ' . $this->login_info->id . ' [ 認證分析報告 ]';
		$this->phpspreadsheet_lib->excel($file_name, $contents, '認證分析報告', '內部分析使用', $descri, $this->login_info->id, true);
	}

	private function is_sub_product($product,$sub_product_id){
		$sub_product_list = $this->config->item('sub_product_list');
		return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
	}

	private function trans_sub_product($product,$sub_product_id){
		$sub_product_list = $this->config->item('sub_product_list');
		$sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
		$product = $this->sub_product_profile($product,$sub_product_data);
		return $product;
	}
	private function sub_product_profile($product,$sub_product){
		return array(
			'id' => $product['id'],
			'visul_id' => $sub_product['visul_id'],
			'type' => $product['type'],
			'identity' => $product['identity'],
			'name' => $sub_product['name'],
			'description' => $sub_product['description'],
			'loan_range_s' => $sub_product['loan_range_s'],
			'loan_range_e' => $sub_product['loan_range_e'],
			'interest_rate_s' => $sub_product['interest_rate_s'],
			'interest_rate_e' => $sub_product['interest_rate_e'],
			'charge_platform' => $sub_product['charge_platform'],
			'charge_platform_min' => $sub_product['charge_platform_min'],
			'certifications' => $sub_product['certifications'],
			'instalment' => $sub_product['instalment'],
			'repayment' => $sub_product['repayment'],
			'targetData' => $sub_product['targetData'],
			'dealer' => $sub_product['dealer'],
			'multi_target' => $sub_product['multi_target'],
			'status' => $sub_product['status'],
		);
	}
	public function judicial_yuan_case(){
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

		if($input['count']>10){
			$total_page = number_format($input['count']/10, 0);
			if($input['count']%10 >0){
				$total_page = $total_page++;
			}
		}else{
			$total_page = 1;
		}

		$this->load->library('scraper/judicial_yuan_lib.php');
		$page_data['list'] = $this->judicial_yuan_lib->requestJudicialYuanVerdictsCase(urlencode($input['name']), urlencode($input['case']), $input['page']);
		$page_data['case_info']['count'] = $input['count'];
		$page_data['case_info']['name'] = $input['name'];
		$page_data['case_info']['case'] = $input['case'];
		$page_data['case_info']['page'] = $input['page'];
		$page_data['case_info']['total_page'] = $total_page;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/certification/component/judicial_yuan_case',$page_data);
		$this->load->view('admin/_footer');
	}
}
?>
