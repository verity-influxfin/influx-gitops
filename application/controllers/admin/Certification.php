<?php
//use function GuzzleHttp\json_decode;

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use Certification\Certification_factory;
use Certification_ocr\Parser\Ocr_parser_factory;

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
		'judicial_yuan_case',
		'media_upload'
	);
	public $certification;
	public $certification_name_list;
	public $ocr_template_page =[PRODUCT_FOREX_CAR_VEHICLE, PRODUCT_SK_MILLION_SMEG];

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
			$product_id = isset($get['product_id'])?$get['product_id']:'';
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
					if(isset($page_data['content']['scraper']['DepartmentOfCommerce']) && isJson($page_data['content']['scraper']['DepartmentOfCommerce']))
                    {
                        $page_data['content']['scraper']['DepartmentOfCommerce'] = json_decode($page_data['content']['scraper']['DepartmentOfCommerce'], TRUE);
                    }
                    $page_data['user_name'] = $this->user_model->get_user_name_by_id($info->user_id);
				}
                $certification_content = isset($info->content) ? json_decode($info->content,TRUE) : [];
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
                    $this->load->library('scraper/sip_lib');

                    $school_status = $this->sip_lib->getUniversityModel($page_data['content']['school']);
                    if (isset($school_status['response']))
                    {
                        $sipURL = $school_status['response']['url'];
                        $sipUniversity = $school_status['response']['university'];
                    }

                    $page_data['content']['sipURL'] = isset($sipURL) ? $sipURL : "";
                    $page_data['content']['sipUniversity'] = isset($sipURL) ? $sipUniversity : "";

                    $page_data['config']['school_system_list'] = $this->config->item('school_system');
                    $page_data['config']['school_department_list'] = file_get_contents(FRONT_CDN_URL . 'json/config_school.json');
                    if ( ! empty($page_data['config']['school_department_list']))
                    {
                        $tmp = json_decode($page_data['config']['school_department_list'], TRUE);
                        $page_data['config']['school_department_list'] = array_keys($tmp);
                        asort($page_data['config']['school_department_list']);
                        $page_data['config']['school_department_list'] = array_flip($page_data['config']['school_department_list']);

                        array_walk($tmp, function (&$item) {
                            $item = call_user_func_array('array_merge', array_values($item['discipline']));
                            asort($item);
                        });

                        $page_data['config']['school_department_list'] = array_replace($page_data['config']['school_department_list'], $tmp);
                    }
                    $page_data['cert_identity_name'] = $this->user_model->get_name_by_id($info->user_id);
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

                                $convertToIntegerList = ['liabilities_totalAmount'];
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
						$page_data['job_title'] = isset($cut[1]) ? preg_split('/"}/',preg_split('/'.$page_data['content']['job_title'].'","des":"/',$job_title)[1])[0] : '' ;
					}
				}
                elseif ($info->certification_id == CERTIFICATION_REPAYMENT_CAPACITY)
                {
                    // 負債比
                    if ( ! isset($page_data['content']['debt_to_equity_ratio']))
                    {
                        if ( ! isset($page_data['content']['totalMonthlyPayment']) || empty($page_data['content']['monthly_repayment']) || ! is_numeric($page_data['content']['monthly_repayment']))
                        {
                            $page_data['content']['debt_to_equity_ratio'] = 0;
                        }
                        else
                        {
                            $page_data['content']['debt_to_equity_ratio'] = round((float) $page_data['content']['totalMonthlyPayment'] / (float) $page_data['content']['monthly_repayment'] * 100, 2);
                        }
                    }

                    $certification = $this->certification[$info->certification_id];
                    $page_data['certification_type'] = $certification['name'];
                    $page_data['status'] = $info->status;
                }
                elseif ($info->certification_id == CERTIFICATION_BUSINESSTAX)
                {
                    $years = ['LastOneYearInvoiceImage' => '近一年', 'LastTwoYearInvoiceImage' => '近二年', 'LastThreeYearInvoiceImage' => '近三年', 'LastFourYearInvoiceImage' => '近四年'];
                    $months = ['M1M2', 'M3M4', 'M5M6', 'M7M8', 'M9M10', 'M11M12'];
                    foreach ($years as $year_key => $year_val)
                    {
                        $page_data['images'][$year_key] = [
                            'name' => $year_val,
                            'url' => [],
                            'upload' => ''
                        ];
                        for ($i = 0; $i < count($months); $i++)
                        {
                            $page_data['images'][$year_key]['url'][$months[$i]] = $certification_content[$year_key . $months[$i]] ?? '';
                        }
                        if ($this->_can_upload_by_cert_status($info->status))
                        {
                            $page_data['images'][$year_key]['upload'] = $this->_upload_page($info, ['image_key' => $year_key . 'Others']);
                        }
                        if ( ! empty($certification_content[$year_key . 'Others']))
                        {
                            $page_data['images'][$year_key]['url'] = array_merge($page_data['images'][$year_key]['url'], $certification_content[$year_key . 'Others']);
                        }
                    }
                    if ( ! empty($certification_content['others_image']))
                    {
                        $page_data['images']['others']['url'] = $certification_content['others_image'];
                    }
                }
                elseif (in_array($info->certification_id, [CERTIFICATION_RENOVATION_CONTRACT, CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT, CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS]))
                {
                    $cert_house_deed = $this->user_certification_model->as_array()->order_by('created_at', 'desc')->get_by([
                        'certification_id' => CERTIFICATION_HOUSE_DEED,
                        'status' => CERTIFICATION_STATUS_SUCCEED,
                        'user_id' => $info->user_id,
                        'investor' => $info->investor
                    ]);
                    $cert_house_deed_content = json_decode($cert_house_deed['content'] ?? '', TRUE);
                    $page_data['house_deed_address'] = $cert_house_deed_content['admin_edit']['address'] ?? $cert_house_deed_content['address'] ?? '';
                    $page_data['house_deed_address_by_user'] = $cert_house_deed_content['address'] ?? '';
                }
				// 獲取 ocr 相關資料
				// to do : ocr table 需優化 index 與 clinet table view
				$this->load->library('mapping/user/Certification_table');

                if(in_array($info->certification_id,['1007','1017','1002','1003','12'])){
                    $page_data['ocr']['url'] = $this->certification_table->getOcrUrl($info->id,$info->certification_id,$certification_content);
                }

                // 可上傳圖片的徵信項
                $cert_can_upload_image = [
                    CERTIFICATION_INVESTIGATION, CERTIFICATION_PROFILE, CERTIFICATION_INVESTIGATIONA11,
                    CERTIFICATION_SIMPLIFICATIONFINANCIAL, CERTIFICATION_SIMPLIFICATIONJOB, CERTIFICATION_PASSBOOKCASHFLOW_2,
                    CERTIFICATION_BUSINESSTAX, CERTIFICATION_BALANCESHEET, CERTIFICATION_INCOMESTATEMENT, CERTIFICATION_INVESTIGATIONJUDICIAL, CERTIFICATION_PASSBOOKCASHFLOW, CERTIFICATION_GOVERNMENTAUTHORITIES, CERTIFICATION_EMPLOYEEINSURANCELIST, CERTIFICATION_PROFILEJUDICIAL, CERTIFICATION_JUDICIALGUARANTEE,
                    CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS,
                    CERTIFICATION_SITE_SURVEY_VIDEO,
                ];
                // 可上傳 PDF 的徵信項
                $cert_can_upload_pdf = [
                    CERTIFICATION_INVESTIGATION, CERTIFICATION_INVESTIGATIONA11,
                    CERTIFICATION_SIMPLIFICATIONJOB, CERTIFICATION_PASSBOOKCASHFLOW_2,
                    CERTIFICATION_BUSINESSTAX, CERTIFICATION_BALANCESHEET, CERTIFICATION_INCOMESTATEMENT, CERTIFICATION_INVESTIGATIONJUDICIAL, CERTIFICATION_PASSBOOKCASHFLOW, CERTIFICATION_GOVERNMENTAUTHORITIES, CERTIFICATION_EMPLOYEEINSURANCELIST, CERTIFICATION_PROFILEJUDICIAL, CERTIFICATION_JUDICIALGUARANTEE,
                    CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS
                ];
                // 可上傳影片的徵信項
                $cert_can_upload_video = [
                    CERTIFICATION_SITE_SURVEY_VIDEO,
                ];
                if (in_array($info->certification_id, $cert_can_upload_image))
                {
                    // 上傳檔案功能

                    if ($info->certification_id == CERTIFICATION_INVESTIGATION && (in_array($info->status, [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_PENDING_TO_REVIEW])))
                    {
                        $input_config_pdf['data'] = ['upload_location' => 'Certification/media_upload', 'file_type' => '.pdf', 'is_multiple' => 0, 'extra_info' => ['user_certification_id' => $info->id, 'user_id' => $info->user_id, 'certification_id' => $info->certification_id]];
                        $input_config_img['data'] = ['upload_location' => 'Certification/media_upload', 'file_type' => 'image/*,.heic,.heif', 'is_multiple' => 1, 'extra_info' => ['user_certification_id' => $info->id, 'user_id' => $info->user_id, 'certification_id' => $info->certification_id]];
                        if (empty($certification_content['pdf_file']) && empty($certification_content['images']))
                        {
                            $page_data['ocr']['upload_page']['pdf_file'] = $this->load->view('admin/certification/component/media_upload', $input_config_pdf, TRUE);
                            $page_data['ocr']['upload_page']['images'] = $this->load->view('admin/certification/component/media_upload', $input_config_img, TRUE);
                        }
                    }
                    elseif ($info->status == CERTIFICATION_STATUS_PENDING_TO_VALIDATE || $info->status == CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                    {
                        $input_config['data'] = ['upload_location'=>'Certification/media_upload','file_type'=> 'image/*,.heic,.heif','is_multiple'=>1,'extra_info'=>['user_certification_id'=>$info->id,'user_id'=>$info->user_id,'certification_id'=>$info->certification_id]];

                        if ($this->_can_upload_by_cert_status($info->status))
                        {
                            $file_type = [];

                            if (in_array($info->certification_id, $cert_can_upload_pdf))
                            {
                                $file_type[] = '.pdf';
                            }
                            if (in_array($info->certification_id, $cert_can_upload_video))
                            {
                                $file_type[] = 'video/mp4';
                                $file_type[] = 'video/ogg';
                            }
                            if (!empty($file_type))
                            {
                                // (2023-10-16) 土地建物謄本，不能上傳圖片('image/*,.heic,.heif')，只能上傳pdf
                                $use_default_file_type = $info->certification_id != CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS;
                                $page_data['ocr']['upload_page'] = $this->_upload_page($info, [], ['.pdf'], $use_default_file_type);
                            }
                            else
                            {
                                $page_data['ocr']['upload_page'] = $this->_upload_page($info);
                            }
                        }
                    }
                    $return_config = [
                        '1003' => [
                            0 => '郵局申請(紙本)',
                            1 => '臨櫃申請(紙本)'
                        ],
                        '9' => [
                            '0' => '郵局申請(紙本)',
                            '1' => '自然人憑證',
                            '2' => '投資人行動網',
                            '3' => '臨櫃申請(紙本)',
                        ],
                        '12' => [
                            '0' => '郵局申請(紙本)',
                            '1' => '臨櫃申請(紙本)',
                        ],
                    ];
                    $return_type = isset($certification_content['return_type']) ? $certification_content['return_type'] : '';
                    $page_data['return_type'] = isset($return_config[$info->certification_id][$return_type]) ? $return_config[$info->certification_id][$return_type] : '';
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
				$this->load->view('admin/_header', $data=['use_vuejs'=>true]);
				$this->load->view('admin/_title', $this->menu);

				$this->load->view('admin/certification/' . $certification['alias'], $page_data);
				$this->load->view('admin/_footer');
			} else {
				alert('ERROR , id is not exist', $back_url);
			}
		}else{

            if (isset($post['certification_id']))
            {
                switch ($post['certification_id'])
                {
                    case CERTIFICATION_REPAYMENT_CAPACITY: // 還款力計算
                        $save_result = $this->_save_certification_repayment_capacity($post);
                        if (isset($save_result['result']))
                        {
                            if ($save_result['result'] === TRUE)
                            {
                                alert($save_result['msg'] ?? '更新成功', $back_url);
                            }
                            else
                            {
                                alert($save_result['msg'] ?? '更新失敗', $back_url);
                            }
                        }
                        else
                        {
                            alert('更新失敗', $back_url);
                        }
                        break;
                    case CERTIFICATION_HOUSE_CONTRACT:
                        $info = $this->user_certification_model->get($post['id']);
                        if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            goto GENERAL_SAVE;
                        }
                        $filter_key = ['address', 'contract_amount', 'down_payment', 'contract_date'];
                        $admin_edit_upd_res = $this->admin_edit_extracted($post, $filter_key, $info);
                        if ($admin_edit_upd_res === TRUE)
                        {
                            goto GENERAL_SAVE;
                        }
                        alert('更新失敗', $back_url);
                        break;
                    case CERTIFICATION_RENOVATION_CONTRACT:
                        $info = $this->user_certification_model->get($post['id']);
                        if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            goto GENERAL_SAVE;
                        }
                        $filter_key = ['contract_amount', 'contract_date'];
                        $admin_edit_upd_res = $this->admin_edit_extracted($post, $filter_key, $info);
                        if ($admin_edit_upd_res === TRUE)
                        {
                            goto GENERAL_SAVE;
                        }
                        alert('更新失敗', $back_url);
                        break;
                    case CERTIFICATION_HOUSE_RECEIPT:
                    case CERTIFICATION_RENOVATION_RECEIPT:
                        $info = $this->user_certification_model->get($post['id']);
                        if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            goto GENERAL_SAVE;
                        }
                        $filter_key = ['receipt_number', 'receipt_amount'];
                        $admin_edit_upd_res = $this->admin_edit_extracted($post, $filter_key, $info);
                        if ($admin_edit_upd_res === TRUE)
                        {
                            goto GENERAL_SAVE;
                        }
                        alert('更新失敗', $back_url);
                        break;
                    case CERTIFICATION_APPLIANCE_CONTRACT_RECEIPT:
                        $info = $this->user_certification_model->get($post['id']);
                        if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            goto GENERAL_SAVE;
                        }
                        $filter_key = ['amount', 'receipt_number', 'contract_date'];
                        $admin_edit_upd_res = $this->admin_edit_extracted($post, $filter_key, $info);
                        if ($admin_edit_upd_res === TRUE)
                        {
                            goto GENERAL_SAVE;
                        }
                        alert('更新失敗', $back_url);
                        break;
                    case CERTIFICATION_HOUSE_DEED:
                        $info = $this->user_certification_model->get($post['id']);
                        if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            goto GENERAL_SAVE;
                        }
                        $filter_key = ['address'];
                        $admin_edit_upd_res = $this->admin_edit_extracted($post, $filter_key, $info);
                        if ($admin_edit_upd_res === TRUE)
                        {
                            goto GENERAL_SAVE;
                        }
                        alert('更新失敗', $back_url);
                        break;
                    case CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS:
                        $info = $this->user_certification_model->get($post['id']);
                        if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            goto GENERAL_SAVE;
                        }
                        $content = json_decode($info->content ?? '', TRUE);
                        $content['admin_edit'] = array_replace_recursive($content['admin_edit'] ?? [], $post['admin_edit']);
                        $admin_edit_upd_res = $this->user_certification_model->update($post['id'], [
                            'content' => json_encode($content),
                        ]);
                        if ($admin_edit_upd_res === TRUE)
                        {
                            goto GENERAL_SAVE;
                        }
                        alert('更新失敗', $back_url);
                        break;
                    case CERTIFICATION_TARGET_APPLY: // 開通法人認購債權
                        if (empty($post['id']))
                        {
                            alert('更新失敗，無此id', $back_url);
                        }
                        $res = $this->user_certification_model->update($post['id'], [
                            'status' => $post['status']
                        ]);
                        if ( ! $res)
                        {
                            alert('更新失敗，請洽工程師', $back_url);
                        }
                        alert('更新成功', $back_url);
                        break;
                }
            }

            if(!empty($post['salary'])){
				$permission_granted = $this->permission_granted['target']['waiting_evaluation']['action']['granted'];
				$id = $post['id'];
				if ($permission_granted >= 3){
					$info = $this->user_certification_model->get($id);
					$content = json_decode($info->content,true);
					$content['admin_salary'] = $post['salary'];
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
						$this->load->library('Target_lib');
						foreach ($targets as $value)
						{
							$this->target_lib->withdraw_target_to_unapproved($value, 0, $this->login_info->id, 0);
						}
					}
					alert('更新成功','user_certification_edit?id='.$id);
				}else{
					alert('無權限修改','user_certification_edit?id='.$id);
				}
            }elseif(!empty($post['name'])){
				$id = $post['id'];
				$info = $this->user_certification_model->get($id);
				$content = json_decode($info->content,true);
				$content['name'] = $post['name'];
				$this->user_certification_model->update($id,['content
				'=>json_encode($content)]);
				alert('更新成功','user_certification_edit?id='.$id);
			}elseif(!empty($post['delete_pdf_file'])){
				$this->load->model('user/user_certification_ocr_task_model');
				$id = $post['id'];
				$pdf_file = $post['delete_pdf_file'];
				$info = $this->user_certification_model->get($id);
				$content = json_decode($info->content,true);
				$remark = json_decode($info->remark,true);
				
				if ($content['pdf_file'] == $pdf_file){
					$this->user_certification_model->trans_begin();
					$this->user_certification_ocr_task_model->trans_begin();
					
					$allowed  = ['return_type', 'mail_file_status'];
					$filtered = array_filter(
						$content,
						function ($key) use ($allowed) {return in_array($key, $allowed);},
						ARRAY_FILTER_USE_KEY
					);
					$content = $filtered;
					$remark = array();

					$this->user_certification_model->update($id,[
						'content'=>json_encode($content),
						'remark'=>json_encode($remark, JSON_FORCE_OBJECT)
					]);
					$this->user_certification_ocr_task_model->delete_by(['user_certification_id'=>$id]);
					
					if ($this->user_certification_model->trans_status() === TRUE &&
						$this->user_certification_ocr_task_model->trans_status() === TRUE)
						{
						$this->user_certification_model->trans_commit();
						$this->user_certification_ocr_task_model->trans_commit();
						$this->user_certification_model->update($post['id'], [
                            'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
                        ]);
						alert('更新成功','user_certification_edit?id='.$id);
					}else{
						$this->user_certification_model->trans_rollback();
						$this->user_certification_ocr_task_model->trans_rollback();
						alert('更新失敗，請洽工程師','user_certification_edit?id='.$id);
					}
				}

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
                $fail   = $post['fail'] ?? '';
                $fail   = ! empty($post['fail2']) ? $post['fail2'] : $fail;
				if(!empty($from)){
					//$back_url = admin_url($from);
					$back_url = admin_url('close');
				}

				$info = $this->user_certification_model->get($post['id']);
                $rs = FALSE;
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
							$this->load->library('Certification_lib');
							if($post['status'] == 2 && $this->certification_lib->isRejectedResult($fail)) {
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
                            $info = $this->user_certification_model->get($post['id']);
                            if ((int) ($info->status) !== CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                            {
                                goto GENERAL_SAVE;
                            }

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

                            $content['admin_edit']['school'] = $post['admin_edit']['school'] ?? '';
                            $content['admin_edit']['department'] = $post['admin_edit']['department'] ?? '';
                            $content['admin_edit']['system'] = $post['admin_edit']['system'] ?? '';
							$this->user_certification_model->update($post['id'],['content'=>json_encode($content)]);
						} elseif ($info->certification_id == CERTIFICATION_CERCREDITJUDICIAL) {
							$fail = '評估表已失效';
						} elseif ($info->certification_id == CERTIFICATION_IDENTITY) {
							if(isset($post['failed_type_list'])) {
								$remark = json_decode($info->remark, TRUE);
								if ($remark === FALSE)
									$remark = [];
								$remark['failed_type_list'] = $post['failed_type_list'];
								$this->user_certification_model->update($post['id'], [
									'remark' => json_encode($remark)
								]);
							}
						}
                        elseif($info->certification_id == CERTIFICATION_INVESTIGATIONA11)
                        { // 聯徵+A11
                            if ($info->status == CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE)
                            {
                                alert('配偶尚未歸戶，不得更改狀態');
                            }
                        }
                        GENERAL_SAVE:
						$this->load->library('Certification_lib');
						$this->load->model('log/log_usercertification_model');
						$this->log_usercertification_model->insert(array(
							'user_certification_id'	=> $post['id'],
							'status'				=> $post['status'],
							'change_admin'			=> $this->login_info->id,
						));

                        $cert = \Certification\Certification_factory::get_instance_by_id($post['id']);
                        if ($post['status'] == CERTIFICATION_STATUS_SUCCEED)
                        {
                            if (isset($cert))
                            {
                                $rs = $cert->set_success(FALSE);
                            }
                            else
                            {
                                $rs = $this->certification_lib->set_success($post['id']);
                            }
                        }
                        else if ($post['status'] == CERTIFICATION_STATUS_FAILED)
                        {
                            $fail = ! empty($post['fail2']) ? $post['fail2'] : ($post['fail'] ?? '');
                            if (isset($cert))
                            {
                                $rs = $cert->set_failure(FALSE, $fail);
                            }
                            else
                            {
                                $rs = $this->certification_lib->set_failed($post['id'],$fail);
                            }
                        }
                        else
                        {
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

    private function _get_default_upload_file_type()
    {
        return 'image/*,.heic,.heif';
    }

    /**
     * 上傳檔案功能
     * @param $certification_info : 使用者徵信項(Table user_certification)
     * @param array $extra_info
     * @param array $additional_file_type
     * @return mixed
     */
    private function _upload_page($certification_info, array $extra_info = [], array $additional_file_type = [], bool $use_default_file_type = true)
    {
        if ($use_default_file_type) {
            $additional_file_type[] = $this->_get_default_upload_file_type();
        }

        return $this->load->view('admin/certification/component/media_upload', ['data' => [
            'upload_location' => 'Certification/media_upload',
            'file_type' => implode(',', $additional_file_type),
            'is_multiple' => 1,
            'extra_info' => array_merge([
                'user_certification_id' => $certification_info->id,
                'user_id' => $certification_info->user_id,
                'certification_id' => $certification_info->certification_id
            ], $extra_info)
        ]], TRUE);
    }

    /**
     * 以徵信項狀態確認，是否可由後台上傳圖片
     * @param int $cert_status : 徵信項狀態 (user_certification.status)
     * @return bool
     */
    private function _can_upload_by_cert_status(int $cert_status): bool
    {
        if ($cert_status == CERTIFICATION_STATUS_PENDING_TO_VALIDATE ||
            $cert_status == CERTIFICATION_STATUS_PENDING_TO_REVIEW ||
            $cert_status == CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE)
        {
            return TRUE;
        }
        return FALSE;
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
                    $page_data['certifications_msg'] = $this->config->item('certifications_msg');

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
            if ( ! empty($post['id']))
            {
                $info = $this->user_bankaccount_model->get($post['id']);
                $fail = $post['fail'] ?? '';
                $fail = empty($post['fail2']) ? $fail : $post['fail2'];
                if ($info)
                {
                    $cert = Certification_factory::get_instance_by_id($info->user_certification_id);
                    if ( ! $cert->is_failed())
                    {
                        $this->load->model('log/log_usercertification_model');
                        $this->log_usercertification_model->insert(array(
                            'user_certification_id' => $info->user_certification_id,
                            'status' => 2,
                            'change_admin' => $this->login_info->id,
                        ));
                        $cert->set_failure(FALSE, $fail);
                        $bankaccount_info = array('verify' => 4, 'status' => 0);
                        $this->user_bankaccount_model->update($post['id'], $bankaccount_info);

                        // 寫 Log
                        $this->load->library('user_bankaccount_lib');
                        $this->user_bankaccount_lib->insert_change_log($post['id'], $bankaccount_info);

                        alert('更新成功', admin_url('close'));
                    }
                    else
                    {
                        alert('金融驗證已經是失敗狀態，無法更新', admin_url('close'));
                    }
                }
                else
                {
                    alert('ERROR , id is not exist', admin_url('close'));
                }
            }
            else
            {
                alert('ERROR , id is not exist', admin_url('close'));
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
                $bankaccount_info = array('verify' => 1);
                $this->user_bankaccount_model->update($id, $bankaccount_info);

                // 寫 Log
                $this->load->library('user_bankaccount_lib');
                $this->user_bankaccount_lib->insert_change_log($id, $bankaccount_info);

				// 如果是借款人的金融帳號通過，才需要對案件進行處理
				if($info->investor == 0) {
					$this->load->library('target_lib');
					$target = $this->target_model->get_by([
						'user_id' => $info->user_id,
						'status' => TARGET_WAITING_VERIFY,
					]);
                    if (empty($target))
                    {
                        goto END;
                    }
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
                END:
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
                $bankaccount_info = array('verify' => 4, 'status' => 0);
                $this->user_bankaccount_model->update($id, $bankaccount_info);

                // 寫 Log
                $this->load->library('user_bankaccount_lib');
                $this->user_bankaccount_lib->insert_change_log($id, $bankaccount_info);

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
                $bankaccount_info = array('verify' => 2);
                $this->user_bankaccount_model->update($id, $bankaccount_info);

                // 寫 Log
                $this->load->library('user_bankaccount_lib');
                $this->user_bankaccount_lib->insert_change_log($id, $bankaccount_info);

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

        # 目前只有實名認證的頁面會 call 這
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

			if(isset($scraper_response['status']) && $scraper_response['status']=='201'){
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
			'checkOwner' => isset($product['checkOwner']) ? $product['checkOwner'] : false,
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

	public function media_upload()
	{
        $this->load->library('output/json_output');
		$post 		= $this->input->post();
		if (!empty($post)) {
			$this->load->library('S3_upload');
			$file_array = [];
			$media_check = true;
            $media = ['image' => [], 'pdf' => [], 'video' => []];
			if(!empty($_FILES['file_upload_tmp'])){
				foreach($_FILES['file_upload_tmp'] as $k=>$v){
					foreach($v as $k1=>$v1){
						$file_array[$k1][$k] = $v1;
					}
				}
				foreach ($file_array as $field) {
                    $file = [];
                    if (is_pdf($field['type']))
                    {
                        $file['pdf'] = $field;
                        $pdf = $this->s3_upload->pdf(
                            file_get_contents($field['tmp_name']),
                            'pdf' . $post['user_id'] . round(microtime(TRUE) * 1000) . rand(1, 99) . 'admin' . '.pdf',
                            $post['user_id'],
                            "certification/{$post['user_certification_id']}"
                        );
                        if ($pdf === FALSE)
                        {
                            $media_check = FALSE;
                            continue;
                        }
                        $media['pdf'][] = $pdf;
                    }
                    elseif (is_video($field['type']))
                    {
                        $file['video'] = $field;
                        $extension_array = explode('.', $field['name']);
                        $video = $this->s3_upload->video(
                            file_get_contents($field['tmp_name']),
                            'video' . $post['user_id'] . round(microtime(TRUE) * 1000) . rand(1, 99) . '.' . end($extension_array),
                            $post['user_id'],
                            "certification/{$post['user_certification_id']}"
                        );
                        if ($video === FALSE)
                        {
                            $media_check = FALSE;
                            continue;
                        }
                        $media['video'][] = $video;
                    }
                    elseif (is_image($field['type']))
                    {
                        $file['image'] = $field;
                        $image = $this->s3_upload->image($file, 'image', $post['user_id'], "certification/{$post['user_certification_id']}");
                        if ($image === FALSE)
                        {
                            $media_check = FALSE;
                            continue;
                        }
                        $media['image'][] = $image;
                    }
				}
				if ($media_check === false) {
                    $this->json_output->setStatusCode(204)->setStatusMessage('檔案上傳失敗，請洽工程師')->send();
				} else {
					$group_id = time();
					$this->load->model('log/log_image_model');
                    $image_id_array = [];
					$image_id = $this->log_image_model->getIDByUrl(call_user_func_array('array_merge', $media));
					foreach($image_id as $v){
						$image_id_array[] = $v->id;
					}
					$this->log_image_model->insertGroupById($image_id_array,['group_info'=>$group_id]);
                    $user_certification_info = $this->user_certification_model->get($post['user_certification_id']);
                    $certification_content = json_decode($user_certification_info->content, TRUE);
                    // TODO: 暫時寫死
                    if (isset($post['certification_id']))
                    {
                        if ($post['certification_id'] == CERTIFICATION_BUSINESSTAX)
                        {
                            $image_name = $post['image_key'] ?? 'others_image';
                        }
                        else
                        {
                            $image_name = $this->_get_image_name_by_cert_id($post['certification_id']);
                        }
                    }
                    else
                    {
                        $image_name = '';
                    }

                    if(isset($certification_content[$image_name])){
                        if(is_array($certification_content[$image_name])){
                            $certification_content[$image_name] = array_merge($certification_content[$image_name], $media['image']);
                        }else{
                            $certification_content[$image_name] = array_merge([$certification_content[$image_name]], $media['image']);
                        }
                    }else{
                        $certification_content[$image_name] = [];
                        $certification_content[$image_name] = array_merge($certification_content[$image_name], $media['image']);
                    }

                    $this->_get_pdf_name_by_cert_id($media['pdf'], $certification_content, $post['certification_id'] ?? '');

					$certification_content['group_id'] = $group_id;
                    $param = ['content' => json_encode($certification_content)];
                    $post['certification_id'] != CERTIFICATION_INVESTIGATION ?: $param['status'] = CERTIFICATION_STATUS_PENDING_TO_VALIDATE;
					$res = $this->user_certification_model->update($post['user_certification_id'], $param);
					// 觸發上傳檔案 ocr
					// $this->load->library('ocr/report_scan_lib');
					// to do : 可能會有聯徵之外的檔案從後台上傳並觸發
					// if($post['user_id']){
					// 	if($post['certification_id'] == 1003){
					// 		$ocr_type = 'company';
					// 	}else{
					// 		$ocr_type = 'person';
					// 	}
					// 	// print_r($media);exit;
					// 	$this->load->model('log/log_image_model');
				    //     $imageLogs = $this->log_image_model->getUrlByGroupID($group_id);
					// 	$this->report_scan_lib->requestForScan('credit_investigation', $imageLogs, $post['user_id'], $ocr_type);
					// }
                    if (isset($certification_content['video']))
                    {
                        $certification_content['video'] = array_merge($certification_content['video'], $media['video']);
                    }
                    else
                    {
                        $certification_content['video'] = $media['video'];
                    }

					$certification_content['group_id'] = $group_id;
                    $update_data = [
                        'content' => json_encode($certification_content)
                    ];
                    if ($user_certification_info->certification_id == CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS)
                    {
                        if ($user_certification_info->status != CERTIFICATION_STATUS_PENDING_TO_REVIEW)
                        {
                            $this->json_output->setStatusCode(200)->setErrorCode('資料更改失敗，狀態未在待人工審核中')->send();
                        }
                        // 若為土地建物謄本者，一旦上傳完資料就將狀態改為待驗證
                        $update_data['status'] = CERTIFICATION_STATUS_PENDING_TO_VALIDATE;
                    }
                    $res = $this->user_certification_model->update($post['user_certification_id'], $update_data);
                    if ($res) {
                        $this->json_output->setStatusCode(200)->setResponse(['message'=>'檔案上傳成功'])->send();
                    }else {
                        $this->json_output->setStatusCode(204)->setStatusMessage('檔案上傳失敗，資料更新失敗，請洽工程師')->send();
                    }
				}
			}
		} else {
            $this->json_output->setStatusCode(204)->setStatusMessage('檔案上傳失敗，缺少參數，請洽工程師')->send();
		}
	}

    private function _get_image_name_by_cert_id($cert_id)
    {
        switch ($cert_id)
        {
            case CERTIFICATION_INVESTIGATION: // 聯合徵信報告
                return 'images';
            case CERTIFICATION_INVESTIGATIONA11: // 聯合徵信報告+A11
                return 'person_mq_image';
            case CERTIFICATION_SIMPLIFICATIONFINANCIAL: // 財務收支
                return 'passbook_image';
            case CERTIFICATION_SIMPLIFICATIONJOB: // 工作資料
                return 'labor_image';
            case CERTIFICATION_LAND_AND_BUILDING_TRANSACTIONS:
                return 'transactions_image';
            case CERTIFICATION_PASSBOOKCASHFLOW_2: // (自然人)近六個月往來存摺封面及內頁
                return 'passbook_image';
            case CERTIFICATION_BUSINESSTAX:
                return 'other_image';
            case CERTIFICATION_BALANCESHEET: // 資產負債表
                return 'balance_sheet_image';
            case CERTIFICATION_INCOMESTATEMENT: // 近三年所得稅結算申報書(稅簽)
                return 'income_statement_image';
            case CERTIFICATION_INVESTIGATIONJUDICIAL: // 公司聯合徵信
                return 'other_image';
            case CERTIFICATION_PASSBOOKCASHFLOW: // 近6個月封面及內頁公司存摺
                return 'passbook_image';
            case CERTIFICATION_GOVERNMENTAUTHORITIES: // 變卡正本拍攝(全頁)
                return 'governmentauthorities_image';
            case CERTIFICATION_EMPLOYEEINSURANCELIST: // 員工投保人數資料
                return 'employeeinsurancelist_image';
            case CERTIFICATION_PROFILEJUDICIAL: // 公司資料表
                return 'other_image';
            case CERTIFICATION_JUDICIALGUARANTEE: // 公司授權核實
                return 'image_url';
            default:
                return 'backend_upload';
        }
    }

    private function _get_pdf_name_by_cert_id($media_pdf, &$cert_content, $cert_id)
    {
        switch ($cert_id)
        {
            case CERTIFICATION_INVESTIGATION:
                $cert_content['pdf_file'] = $media_pdf[0] ?? '';
                break;
            default:
                if (isset($cert_content['pdf']) && is_array($cert_content['pdf']))
                {
                    $cert_content['pdf'] = array_merge($cert_content['pdf'], $media_pdf);
                }
                else
                {
                    $cert_content['pdf'] = $media_pdf;
                }
        }
    }

    // 加入是否有配偶
    public function hasSpouse(){
        $post = $this->input->post();

        if(! isset($post['hasSpouse'])){
            alert('資料更改失敗，缺少參數', admin_url('certification/user_certification_edit?id='.$post['id']));
        }
        $certification_info = $this->user_certification_model->get_by(['id' => $post['id']]);

        if(! $certification_info){
            alert('資料更改失敗，找不到資料', admin_url('certification/user_certification_edit?id='.$post['id']));
        }

        if(isset($certification_info->status) && $certification_info->status != 3){
            alert('資料更改失敗，狀態未在待人工審核中', admin_url('certification/user_certification_edit?id='.$post['id']));
        }

        $content = isset($certification_info->content) ? json_decode($certification_info->content,true) : [];
        $content['hasSpouse'] = $post['hasSpouse'] == 1 ? true : false;
        $this->user_certification_model->update_by(
            ['id'  => $post['id']],
            ['content' => json_encode($content)]
        );
        alert('資料更新成功', admin_url('certification/user_certification_edit?id='.$post['id']));
    }

    // 新光送件檢核表送出資料
    public function save_company_cert(){
        $post = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);

        if(! isset($post['id']) || empty($post['id'])){
			echo json_encode(['result'=>'資料更改失敗，缺少參數']);
			die();
        }

        $certification_info = $this->user_certification_model->get_by(['id' => $post['id']]);

        if(! $certification_info){
			echo json_encode(['result'=>'資料更改失敗，找不到資料']);
			die();
        }

        if(isset($certification_info->status) &&
            ! in_array($certification_info->status, [CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE])){
            echo json_encode(['result'=>'資料更改失敗，狀態未在待人工審核中']);
			die();
        }

        $content = isset($certification_info->content) ? json_decode($certification_info->content,true) : [];
        unset($post['id']);
        $content = array_replace_recursive($content, $post);
        $this->user_certification_model->update_by(
            ['id'  => $certification_info->id],
            ['content' => json_encode($content)]
        );
        echo json_encode(['result'=>'資料更新成功']);
		die();
    }

    // 新光送件檢核表回填資料
    public function getSkbank(){
        $get = $this->input->get();
        $this->load->library('output/json_output');

        $response_data = [];

        if(! isset($get['id']) || empty($get['id'])){
            $this->json_output->setStatusCode(204)->setStatusMessage('缺少參數，無法找資料')->send();
        }

        $certification_info = $this->user_certification_model->get_by(['id' => $get['id']]);
        if(! $certification_info){
            $this->json_output->setStatusCode(204)->setStatusMessage('找不到資料')->send();
        }

        $content = isset($certification_info->content) ? json_decode($certification_info->content,true) : [];
        if ( ! empty($content))
        {
            switch ($certification_info->certification_id)
            {
                case CERTIFICATION_PROFILEJUDICIAL: // 公司資料表
                    $replace_content = $content;
                    foreach($replace_content as $key => $value){
                        if(is_array($value)){
                            unset($replace_content[$key]);
                        }
                    }
                    $response_data = $replace_content;
                    break;
                case CERTIFICATION_BUSINESSTAX: // 近三年401/403/405表
                case CERTIFICATION_INCOMESTATEMENT: // 近三年所得稅結算申報書
                    if (empty($content['ocr_parser']['content']))
                    {
                        break;
                    }
                    $ocr_parser_content = call_user_func_array('array_merge', $content['ocr_parser']['content']);
                    foreach ($ocr_parser_content as $key => $value)
                    {
                        if (empty($content[$key]))
                        {
                            $content[$key] = $value;
                        }
                    }
                    break;
                case CERTIFICATION_GOVERNMENTAUTHORITIES: // 設立(變更)事項登記表
                    if (empty($content['ocr_parser']['content']))
                    {
                        break;
                    }
                    foreach ($content['ocr_parser']['content'] as $key => $value)
                    {
                        if (empty($content[$key]))
                        {
                            $content[$key] = $value;
                        }
                    }
                    break;
            }
        }

        if (isset($content) && ! empty($content))
        {
            $response_data = $content;
        }
        $this->json_output->setStatusCode(200)->setResponse($response_data)->send();
    }

    // 人工填寫風控因子
    public function save_meta(){
        $post = $this->input->post();

        if(! isset($post['id']) || empty($post['id'])){
            alert('資料更改失敗，缺少參數', admin_url('certification/user_certification_edit?id='.$post['id']));
        }

        $certification_info = $this->user_certification_model->get_by(['id' => $post['id']]);

        if(! $certification_info){
            alert('資料更改失敗，找不到資料', admin_url('certification/user_certification_edit?id='.$post['id']));
        }

        if ( ! in_array($certification_info->status,
            [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_PENDING_TO_REVIEW]))
        {
            alert('資料更改失敗，狀態未在待驗證/待人工審核的狀態', admin_url('certification/user_certification_edit?id=' . $post['id']));
        }

        $content = isset($certification_info->content) ? json_decode($certification_info->content,true) : [];
        unset($post['id']);
        $content['meta'] = $post;
        $this->user_certification_model->update_by(
            ['id'  => $certification_info->id],
            ['content' => json_encode($content)]
        );
        alert('資料更新成功', admin_url('certification/user_certification_edit?id='.$certification_info->id));
    }

    public function save_job_meta()
    {
        $post = $this->input->post();

        if (empty($post['id']))
        {
            alert('資料更改失敗，缺少參數', admin_url('certification/user_certification_edit?id=' . $post['id']));
        }

        $certification_info = $this->user_certification_model->get_by(['id' => $post['id']]);

        if ( ! $certification_info)
        {
            alert('資料更改失敗，找不到資料', admin_url('certification/user_certification_edit?id=' . $post['id']));
        }

        if ( ! in_array($certification_info->status,
            [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_PENDING_TO_REVIEW]))
        {
            alert('資料更改失敗，狀態未在待驗證/待人工審核的狀態', admin_url('certification/user_certification_edit?id=' . $post['id']));
        }

        $content = isset($certification_info->content) ? json_decode($certification_info->content, TRUE) : [];
        $content['job_has_license'] = $post['job_has_license'] ? '1' : '0';
        $this->user_certification_model->update_by(
            ['id' => $certification_info->id],
            ['content' => json_encode($content)]
        );
        alert('資料更新成功', admin_url('certification/user_certification_edit?id=' . $certification_info->id));
    }

    // 帶入風控因子
    public function getMeta(){
        $get = $this->input->get();
        $this->load->library('output/json_output');

        $response_data = [];

        if(! isset($get['id']) || empty($get['id'])){
            $this->json_output->setStatusCode(204)->setStatusMessage('缺少參數，無法找資料')->send();
        }

        $certification_info = $this->user_certification_model->get_by(['id' => $get['id']]);
        if(! $certification_info){
            $this->json_output->setStatusCode(204)->setStatusMessage('找不到資料')->send();
        }

        $content = isset($certification_info->content) ? json_decode($certification_info->content,true) : [];

        if(isset($content['meta']) && !empty($content['meta'])){
            $response_data = $content['meta'];
        }
        $this->json_output->setStatusCode(200)->setResponse($response_data)->send();
    }

    // 後台儲存「還款力計算」的表單欄位
    private function _save_certification_repayment_capacity($post_data)
    {
        if (empty($post_data))
        {
            return $this->_return_error('資料傳輸錯誤，更新失敗');
        }

        if (empty($post_data['id']))
        {
            return $this->_return_error('查無此ID，更新失敗');
        }

        $this->load->model('user/user_certification_model');
        $this->load->library('certification_lib');
        $this->load->library('verify/data_verify_lib');

        $old_data = $this->user_certification_model->get_certification_data_by_id($post_data['id']);
        if (empty($old_data['status']) ||  ! in_array($old_data['status'], [CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_SUCCEED]))
        {
            return $this->_return_error('狀態非【待人工審核】或【審核成功】，更新失敗');
        }
        $old_data_content = json_decode($old_data['content'] ?? '', TRUE);

        $new_data_content = $old_data_content;

        // 長期擔保
        if (isset($post_data['longAssure']))
        {
            $new_data_content['longAssure'] = (int) str_replace(',', '', $post_data['longAssure']);
            $new_data_content['longAssureMonthlyPayment'] = $this->certification_lib->get_long_assure_monthly_payment($new_data_content['longAssure']);
        }

        // 中期擔保
        if (isset($post_data['midAssure']))
        {
            $new_data_content['midAssure'] = (int) str_replace(',', '', $post_data['midAssure']);
            $new_data_content['midAssureMonthlyPayment'] = $this->certification_lib->get_mid_assure_monthly_payment($new_data_content['midAssure']);
        }

        // 長期放款
        if (isset($post_data['long']))
        {
            $new_data_content['long'] = (int) str_replace(',', '', $post_data['long']);
            $new_data_content['longMonthlyPayment'] = $this->certification_lib->get_long_monthly_payment($new_data_content['long']);
        }

        // 中期放款
        if (isset($post_data['mid']))
        {
            $new_data_content['mid'] = (int) str_replace(',', '', $post_data['mid']);
            $new_data_content['midMonthlyPayment'] = $this->certification_lib->get_mid_monthly_payment($new_data_content['mid']);
        }

        // 短期放款
        if (isset($post_data['short']))
        {
            $new_data_content['short'] = (int) str_replace(',', '', $post_data['short']);
            $new_data_content['shortMonthlyPayment'] = $this->certification_lib->get_short_monthly_payment($new_data_content['short']);
        }

        // 助學貸款
        if (isset($post_data['studentLoans']))
        {
            $new_data_content['studentLoans'] = (int) str_replace(',', '', $post_data['studentLoans']);
            $new_data_content['studentLoansCount'] = (int) str_replace(',', '', $post_data['studentLoansCount']);

            if (($new_data_content['studentLoans'] !== 0 || $new_data_content['studentLoansCount'] !== 0) &&
                ($new_data_content['studentLoans'] * $new_data_content['studentLoansCount'] === 0))
            {
                return $this->_return_error('助學貸款總訂約金額與總筆數，不可其中一個為0，另一個不為0');
            }
            $new_data_content['studentLoansMonthlyPayment'] = $this->certification_lib->get_student_loans_monthly_payment(
                $new_data_content['studentLoans'],
                $new_data_content['studentLoansCount']
            );
        }

        // 信用卡
        if (isset($post_data['creditCard']))
        {
            $new_data_content['creditCard'] = (int) str_replace(',', '', $post_data['creditCard']);
            $new_data_content['creditCardMonthlyPayment'] = $this->certification_lib->get_credit_card_monthly_payment($new_data_content['creditCard']);
        }

        // 借款總餘額
        if ( ! empty($post_data['liabilitiesWithoutAssureTotalAmount']))
        {
            $new_data_content['liabilitiesWithoutAssureTotalAmount'] = (float) str_replace(',', '', $post_data['liabilitiesWithoutAssureTotalAmount']);
        }

        // 信用借款+信用卡+現金卡總餘額
        if ( ! empty($post_data['totalEffectiveDebt']))
        {
            $new_data_content['totalEffectiveDebt'] = (float) str_replace(',', '', $post_data['totalEffectiveDebt']);
        }

        // 總共月繳
        $new_data_content['totalMonthlyPayment'] =
            $new_data_content['longAssureMonthlyPayment'] +
            $new_data_content['midAssureMonthlyPayment'] +
            $new_data_content['longMonthlyPayment'] +
            $new_data_content['midMonthlyPayment'] +
            $new_data_content['shortMonthlyPayment'] +
            $new_data_content['studentLoansMonthlyPayment'] +
            $new_data_content['creditCardMonthlyPayment'];

        // 負債比
        if ( ! empty($new_data_content['monthly_repayment']) && is_numeric($new_data_content['monthly_repayment']))
        {
            $new_data_content['debt_to_equity_ratio'] = round(
                (float) $new_data_content['totalMonthlyPayment'] / (float) $new_data_content['monthly_repayment'] * 100, 2
            );
        }
        else
        {
            $new_data_content['debt_to_equity_ratio'] = 0;
        }

        $verified_result = new \CertificationResult\RepaymentCapacityCertificationResult(CERTIFICATION_STATUS_SUCCEED);
        $this->load->library('verify/data_verify_lib');

        // 印表日期
        $this->load->library('mapping/time');
        $print_timestamp = preg_replace('/\s[0-9]{2}\:[0-9]{2}\:[0-9]{2}/', '', $old_data_content['printDatetime'] ?? '');
        $print_timestamp = $this->time->ROCDateToUnixTimestamp($print_timestamp);

        $verified_result->addMessage('人工審核通過', CERTIFICATION_STATUS_SUCCEED, \CertificationResult\MessageDisplay::Backend);
        $this->certification_lib->update_repayment_certification(
            $post_data['id'],
            $print_timestamp,
            $verified_result,
            $new_data_content,
            [], // =user_certification.remark
            $old_data['created_at']
        );

        return ['result' => TRUE];
    }

    private function _return_error($msg = 'ERROR')
    {
        return ['result' => FALSE, 'msg' => $msg];
    }

	public function income_statement_ocr_page() {
		$get = $this->input->get(NULL, TRUE);
		$id = isset($get['id']) ? intval($get['id']) : 0;
		$info = $this->user_certification_model->get($id);
		$page_data['data'] = $info->content;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title', $this->menu);
		$this->load->view('admin/certification/incomestatement_ocr', $page_data);
		$this->load->view('admin/_footer');
	}
    /**
     * @param $post
     * @param array $filter_key
     * @param $info
     * @return mixed
     */
    private function admin_edit_extracted($post, array $filter_key, $info)
    {
        if (empty($post['admin_edit']))
        {
            return FALSE;
        }
        $admin_edit_data = array_filter($post['admin_edit'], function ($value) use ($filter_key) {
            return in_array($value, $filter_key);
        }, ARRAY_FILTER_USE_KEY);
        $update_content_data = json_decode($info->content ?? '', TRUE);
        $update_content_data['admin_edit'] = $admin_edit_data;
        $this->user_certification_model->update($post['id'], [
            'content' => json_encode($update_content_data)
        ]);
        return TRUE;
    }

    public function recheck_land_and_building_transactions_ocr_parser():string
    {
        $response = ['success' => FALSE];
        try
        {
            $id = $this->input->get('id');
            if (empty($id))
            {
                $response['msg'] = '查無徵信項資訊';
                echo json_encode($response);
                die();
            }

            $user_certification_info = $this->user_certification_model->as_array()->get($id);
            if (empty($user_certification_info))
            {
                $response['msg'] = '查無徵信項資訊';
                echo json_encode($response);
                die();
            }
            if ($user_certification_info['status'] != CERTIFICATION_STATUS_PENDING_TO_REVIEW)
            {
                $response['msg'] = '狀態非待人工審核，無法重新執行';
                echo json_encode($response);
                die();
            }

            $cert_ocr_parser_instance = Ocr_parser_factory::get_instance($user_certification_info);
            $cert_ocr_parser_instance->set_retry_failed_scraper_task(TRUE);
            $ocr_parser_result = $cert_ocr_parser_instance->get_result();

            if (isset($ocr_parser_result['success']) && $ocr_parser_result['success'] === TRUE)
            {
                $tmp_content['ocr_parser']['res'] = TRUE;
                $tmp_content['ocr_parser']['content'] = $ocr_parser_result['data'];

                $old_content = json_decode($user_certification_info['content'], TRUE);
                $new_content = array_replace_recursive($old_content, $tmp_content);

                $new_content['admin_edit'] = $this->array_replace_when_empty($new_content['admin_edit'] ?? [], $ocr_parser_result['data']);
                if (empty($new_content['admin_edit']['address']))
                {
                    $new_content['admin_edit']['address'] = $parsed_content['ocr_parser']['content']['buildingPart']['address_str'] ?? '';
                }

                $this->user_certification_model->update($id, [
                    'content' => json_encode($new_content),
                ]);
            }
            echo json_encode(['success' => TRUE]);
            die();
        }
        catch (\Exception $e)
        {
            $response['msg'] = $e->getMessage();
            echo json_encode($response);
            die();
        }
    }

    private function array_replace_when_empty($array, $replacement)
    {
        $result = [];
        foreach ($replacement as $_key => $_value)
        {
            if ( ! isset($array[$_key]))
            {
                $result[$_key] = $_value;
            }
            elseif ( ! is_array($_value))
            {
                if ( ! empty($array[$_key]))
                {
                    $result[$_key] = $array[$_key];
                    continue;
                }
                $result[$_key] = $_value;
            }
            else
            {
                $result[$_key] = $this->array_replace_when_empty($array[$_key], $_value);
            }
        }
        return $result;
    }
}

