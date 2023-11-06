<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

use Certification_btn\Certification_btn_factory;

class Risk extends MY_Admin_Controller {

	protected $edit_method = array('add','edit','partner_type_add');

	public function __construct() {
		parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/virtual_account_model');
		$this->load->model('loan/credit_model');
		$this->load->model('loan/target_model');
		$this->load->library('target_lib');
		$this->load->library('certification_lib');
 	}

    public function natural_person()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/risk_target_natural_person');
        $this->load->view('admin/_footer');
    }

    public function natural_person_export()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/risk_target_natural_person_export');
        $this->load->view('admin/_footer');
    }

    public function get_natural_person_list()
    {
        // 取得$_GET的產品ID和子產品ID
        list($product_id, $sub_product_id) = array_pad(explode(':', $this->input->get('product')), 2, 0);
        $this->load->library('loanmanager/product_lib');
        $product_info = $this->product_lib->get_exact_product($product_id, $sub_product_id);

        $stage = $this->input->get('stage');
        // 取得現階段要呈現的徵信項
        switch ($stage)
        {
            case 0: // 身份驗證階段
                $this_stage_cert = $product_info['certifications_stage'][0] ?? [];
                $prev_stage_cert = [];
                break;
            case 1: // 收件檢核階段
                $this_stage_cert = call_user_func_array('array_merge', $product_info['certifications_stage'] ?? []);
                $prev_stage_cert = $product_info['certifications_stage'][0] ?? [];
                break;
            case 2: // 審核中階段
                $this_stage_cert = call_user_func_array('array_merge', $product_info['certifications_stage'] ?? []);
                $prev_stage_cert = $this_stage_cert;
                break;
            default:
                echo json_encode([]);
                return TRUE;
        }

        $result = ['cols' => [
            ['id' => 'target_no', 'name' => '案號'],
            ['id' => 'user', 'name' => '會員編號'],
            ['id' => 'product', 'name' => '產品名稱'],
            ['id' => 'status', 'name' => '狀態'],
            ['id' => 'updated_at', 'name' => '最後更新時間'],
        ]];
        $cert_config = $this->config->item('certifications');
        $cert_config_name = array_column($cert_config, 'name', 'id');

        array_walk($this_stage_cert, function ($value, $key) use (&$result, $cert_config_name) {
            $result['cols'][] = ['id' => 'cert' . $key, 'name' => $cert_config_name[$value]];
        });

        if ($product_id == PRODUCT_ID_STUDENT)
        {
            $main_product_info = $this->product_lib->get_product_info($product_id);
            $sub_product_id = $main_product_info['sub_product'];
            $sub_product_id[] = SUB_PRODUCT_GENERAL;
        }
        elseif ($product_id == PRODUCT_ID_SALARY_MAN)
        {
            if ($sub_product_id == STAGE_CER_TARGET)
            {
                $sub_product_id = [STAGE_CER_TARGET];
            }
            else
            {
                $main_product_info = $this->product_lib->get_product_info($product_id);
                $sub_product_id = array_diff($main_product_info['sub_product'], [STAGE_CER_TARGET]);
                $sub_product_id[] = SUB_PRODUCT_GENERAL;
            }
        }
        else
        {
            $sub_product_id = [$sub_product_id];
        }

        // 撈target
        $target_list = $this->target_model->get_risk_person_list(BORROWER, [
            CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
            CERTIFICATION_STATUS_SUCCEED,
            CERTIFICATION_STATUS_PENDING_TO_REVIEW
        ], $product_id, $sub_product_id);
        if (empty($target_list))
        {
            echo json_encode($result);
            return TRUE;
        }
        $userStatusList = $this->target_model->getUserStatusByTargetId(array_column($target_list, 'id'));
        $userStatusList = array_column($userStatusList, 'total_count', 'user_id');

        $user_list = [];
        $user_cert_list = [];
        $user_prod_list = [];
        foreach ($target_list as $target)
        {
            if ( ! isset($user_prod_list[$target->product_id][$target->sub_product_id]))
            {
                $product_info = $this->product_lib->getProductInfo($product_id, $target->sub_product_id);
                $user_prod_list[$target->product_id][$target->sub_product_id] = $product_info['name'];
            }

            if ( ! isset($user_list[$target->user_id]))
            {
                $user_status = $this->target_model->get_old_user([$target->user_id], $target->created_at);
                $user_status = array_column($user_status, 'user_from', 'user_from');

                $user_type = isset($user_status[$target->user_id]) ? '舊戶' : '新戶';
                $href = admin_url("User/display?id={$target->user_id}");
                $text = "{$target->user_id} {$user_type}";
                $user_list[$target->user_id]['user_name'] = "<a class=\"fancyframe\" href=\"{$href}\" >{$text}</a>";

                // 撈user每個徵信項的最新狀態
                $tmp = $this->certification_lib->get_last_status($target->user_id, BORROWER, USER_NOT_COMPANY, $target, FALSE, TRUE, TRUE);
                $tmp = array_reduce($tmp, function ($list, $item) {
                    $list[$item['id']] = [
                        'id' => $item['certification_id'], // =user_certification.id
                        'certification_id' => $item['id'], // =user_certification.certification_id
                        'status' => $item['user_status'],  // =user_certification.status
                        'sub_status' => $item['user_sub_status'],
                        'sys_check' => $item['sys_check'], // =user_certification.sys_check
                        'expire_time' => $item['expire_time']
                    ];
                    return $list;
                }, []);
                // 整理出驗證成功的徵信項
                $tmp_success = $this->certification_lib->filterCertIdsInStatusList($tmp, [CERTIFICATION_STATUS_SUCCEED]);
                $user_list[$target->user_id]['success_cert'] = $tmp_success;
                $user_list[$target->user_id]['cert_list'] = $tmp;
            }

            if ( ! isset($user_cert_list[$target->user_id][$product_id][$target->sub_product_id]))
            {
                if ( ! isset($user_list[$target->user_id]['success_cert']) || ! isset($user_list[$target->user_id]['cert_list']))
                {
                    continue;
                }

                $this_stage_cert_tmp = $this_stage_cert;
                if ($target->product_id == PRODUCT_ID_STUDENT && $target->sub_product_id == SUBPRODUCT_INTELLIGENT_STUDENT)
                {
                    $cert_key_tmp = array_search(CERTIFICATION_SOCIAL, $this_stage_cert_tmp);
                    if ($cert_key_tmp !== FALSE)
                    {
                        $this_stage_cert_tmp[$cert_key_tmp] = CERTIFICATION_SOCIAL_INTELLIGENT;
                    }
                }

                $user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['this_success_status'] =
                    count(array_intersect($this_stage_cert_tmp, $user_list[$target->user_id]['success_cert'])) == count($this_stage_cert_tmp);

                $user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['prev_success_status'] =
                    count(array_intersect($prev_stage_cert, $user_list[$target->user_id]['success_cert'])) == count($prev_stage_cert);

                // 畫徵信項的狀態按鈕
                $user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['btn'] = [];
                foreach ($this_stage_cert_tmp as $key => $cert)
                {
                    $btn_factory = Certification_btn_factory::get_instance($user_list[$target->user_id]['cert_list'][$cert]);

                    if ($btn_factory)
                    {
                        $user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['btn']['cert' . $key] = $btn_factory->draw();
                    }
                    else
                    {
                        $user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['btn']['cert' . $key] = '';
                    }
                }
            }

            $additional_btn = [];
            if ($stage == 0 )
            {
                if ($user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['this_success_status'] ||
                    $target->certificate_status == TARGET_CERTIFICATE_SUBMITTED)
                {
                    continue;
                }
            }
            elseif ($stage == 1)
            {
                if ( ! $user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['prev_success_status'] ||
                    $target->certificate_status == TARGET_CERTIFICATE_SUBMITTED)
                {
                    continue;
                }
            }
            else
            {
                if ( $target->certificate_status != TARGET_CERTIFICATE_SUBMITTED)
                {
                    continue;
                }
                $additional_btn['report'] = '<a class="btn btn-primary btn-info" href="' .
                    admin_url('Creditmanagement/report_natural_person') . '?target_id=' . $target->id . '&type=person" target="_blank" >查看<br />授信審核表</a>';
                $additional_btn['fail'] = '<button class="btn btn-outline btn-danger" onclick="failed(' . $target->id . ',\'' . $target->target_no . '\')" >退件</button>';
            }

            $result['list'][] = array_merge($user_cert_list[$target->user_id][$product_id][$target->sub_product_id]['btn'], [
                // todo: 因應權限表設定，url可能需要調整
                'target_no' => '<a class="fancyframe" href="' . admin_url('Target/edit?display=1&id=' . $target->id) . '" >' . $target->target_no . '</a>',
                'user' => $user_list[$target->user_id]['user_name'],
                'product' => $user_prod_list[$target->product_id][$target->sub_product_id],
                'status' => $this->target_model->status_list[$target->status] ?? '',
                'updated_at' => date('Y-m-d H:i:s', $target->updated_at)
            ], $additional_btn);
        }

        if ($stage == 2)
        {
            $result['cols'] = array_merge($result['cols'], [
                ['id' => 'report', 'name' => '授信審核表'],
                ['id' => 'fail', 'name' => '退件']
            ]);
        }

        echo json_encode($result);
        return TRUE;
    }

    public function export_natural_person_list()
    {
        $data_rows = [];
        $title_rows = [];

        $input = $this->input->post(NULL, TRUE);
        if (empty($input['product_id']) || (empty($input['export_column']) && empty($input['export_column_cert'])))
        {
            goto END;
        }

        // 產品別
        $input_product_id = $input['product_id'];
        // 案件相關欄位
        $input_target_column = $input['export_column'] ?? [];
        $input_target_column = array_flip($input_target_column);
        $title_target = array_intersect_key([
            'target_no' => ['name' => '案號', 'width' => 20],
            'user_id' => ['name' => '會員編號', 'width' => 20, 'alignment' => ['h' => 'right']],
            'user_name' => ['name' => '姓名'],
            'user_phone' => ['name' => '電話', 'datatype' => \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING, 'width' => 12],
            'product_name' => ['name' => '產品名稱', 'width' => 20],
            'target_status' => ['name' => '狀態'],
            'updated_at' => ['name' => '最後更新時間', 'width' => 20]
        ], $input_target_column
        );
        // 徵信項相關欄位
        $input_cert_id = $input['export_column_cert'] ?? [];
        $cert_config = $this->config->item('certifications');
        $title_cert = array_intersect_key(array_column($cert_config, 'name', 'id'), array_flip($input_cert_id));
        array_walk($title_cert, function (&$item) {
            $item = ['name' => $item, 'width' => 15];
        });

        $title_rows = array_replace($title_target, $title_cert);

        $this->load->library('target_lib');
        $target_list = $this->target_lib->get_natural_person_export_list($input_product_id);

        if (empty($target_list))
        {
            goto END;
        }

        $this->load->library('certification_lib');
        $this->load->library('loanmanager/product_lib');

        $user_list = [];
        $user_cert_list = [];
        $user_prod_list = [];
        foreach ($target_list as $target)
        {
            // 取得案件、使用者相關資訊
            if (empty($user_prod_list[$target['product_id']][$target['sub_product_id']]))
            {
                $product_info = $this->product_lib->get_exact_product($input_product_id, $target['sub_product_id']);
                $merge_certifications_stage = call_user_func_array('array_merge', $product_info['certifications_stage']);
                $user_prod_list[$target['product_id']][$target['sub_product_id']] = [
                    'name' => $product_info['name'],
                    'stage' => [
                        [
                            // 身份驗證階段
                            'this_stage_cert' => $product_info['certifications_stage'][0] ?? [],
                            'prev_stage_cert' => []
                        ], [
                            // 收件檢核階段
                            'this_stage_cert' => $merge_certifications_stage,
                            'prev_stage_cert' => $product_info['certifications_stage'][0] ?? [],
                        ], [
                            // 審核中階段
                            'this_stage_cert' => $merge_certifications_stage,
                            'prev_stage_cert' => $merge_certifications_stage
                        ]
                    ]
                ];
            }
            $target['product_name'] = $user_prod_list[$target['product_id']][$target['sub_product_id']]['name'];
            $target['updated_at'] = date('Y-m-d H:i:s', $target['updated_at']);
            $target['target_status'] = $this->target_model->status_list[$target['status']] ?? '';
            $target['user_name'] = mb_substr($target['user_name'], 0, 1);
            if ( ! empty($target['user_name']))
            {
                $target['user_name'] .= '○○';
            }

            // 取得徵信項相關資訊
            if (empty($user_list[$target['user_id']]))
            {
                $tmp = $this->certification_lib->get_last_status($target['user_id'], BORROWER, USER_NOT_COMPANY, $target, FALSE, TRUE, TRUE);
                $tmp = array_reduce($tmp, function ($list, $item) {
                    $list[$item['id']] = [
                        'id' => $item['certification_id'], // =user_certification.id
                        'certification_id' => $item['id'], // =user_certification.certification_id
                        'status' => $item['user_status'],  // =user_certification.status
                        'sub_status' => $item['user_sub_status'],
                        'sys_check' => $item['sys_check'], // =user_certification.sys_check
                        'expire_time' => $item['expire_time']
                    ];
                    return $list;
                }, []);
                $tmp_success = $this->certification_lib->filterCertIdsInStatusList($tmp, [CERTIFICATION_STATUS_SUCCEED]);
                $user_list[$target['user_id']] = [
                    'success_cert' => $tmp_success,
                    'cert_list' => $tmp,
                ];
            }
            if ( ! isset($user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]))
            {
                if ( ! isset($user_list[$target['user_id']]['success_cert']) || ! isset($user_list[$target['user_id']]['cert_list']))
                {
                    continue;
                }

                foreach ($user_prod_list[$target['product_id']][$target['sub_product_id']]['stage'] as $stage => $stage_cert)
                {
                    $this_stage_cert = $stage_cert['this_stage_cert'];
                    $prev_stage_cert = $stage_cert['prev_stage_cert'];
                    $this_stage_cert_tmp = $this_stage_cert;

                    if ($target['product_id'] == PRODUCT_ID_STUDENT && $target['sub_product_id'] == SUBPRODUCT_INTELLIGENT_STUDENT)
                    {
                        $cert_key_tmp = array_search(CERTIFICATION_SOCIAL, $this_stage_cert_tmp);
                        if ($cert_key_tmp !== FALSE)
                        {
                            $this_stage_cert_tmp[$cert_key_tmp] = CERTIFICATION_SOCIAL_INTELLIGENT;
                        }
                    }

                    $user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]['stage'][$stage]['this_success_status'] =
                        count(array_intersect($this_stage_cert_tmp, $user_list[$target['user_id']]['success_cert'])) == count($this_stage_cert_tmp);

                    $user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]['stage'][$stage]['prev_success_status'] =
                        count(array_intersect($prev_stage_cert, $user_list[$target['user_id']]['success_cert'])) == count($prev_stage_cert);

                    // 畫徵信項的狀態按鈕
                    $user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]['stage'][$stage]['btn'] = [];
                    foreach ($this_stage_cert_tmp as $cert)
                    {
                        $btn_factory = Certification_btn_factory::get_instance($user_list[$target['user_id']]['cert_list'][$cert]);

                        if ($btn_factory)
                        {
                            $user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]['stage'][$stage]['btn'][$cert] = $btn_factory->get_status_meaning();
                        }
                        else
                        {
                            $user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]['stage'][$stage]['btn'][$cert] = '';
                        }
                    }
                }
            }

            foreach ($user_cert_list[$target['user_id']][$target['product_id']][$target['sub_product_id']]['stage'] as $stage => $user_stage_value)
            {
                if ($stage == 0 )
                {
                    if ($user_stage_value['this_success_status'] ||
                        $target['certificate_status'] == TARGET_CERTIFICATE_SUBMITTED)
                    {
                        continue;
                    }
                }
                elseif ($stage == 1)
                {
                    if ( ! $user_stage_value['prev_success_status'] ||
                        $target['certificate_status'] == TARGET_CERTIFICATE_SUBMITTED)
                    {
                        continue;
                    }
                }
                else
                {
                    if ( $target['certificate_status'] != TARGET_CERTIFICATE_SUBMITTED)
                    {
                        continue;
                    }
                }

                // 組裝資料
                $user_cert = array_intersect_key($user_stage_value['btn'], $title_cert);
                if ($target['has_delayed'] === '1')
                {
                    $target['user_id'] .= '(曾逾期)';
                }
                $data_rows[$stage][] = array_replace(array_intersect_key($target, $input_target_column), $user_cert);
            }
        }

        END:
        setcookie('export_natural_person', TRUE);
        $this->load->library('spreadsheet_lib');
        $sheet_title = ['身份驗證','收件檢核','審核中'];
        $spreadsheet = NULL;
        foreach ($sheet_title as $key => $value)
        {
            $spreadsheet = $this->spreadsheet_lib->load_multi_sheet($title_rows, $data_rows[$key], $spreadsheet, $value);
        }

        // 命名檔案
        $product_type_dict = [
            PRODUCT_ID_STUDENT => '學生貸',
            PRODUCT_ID_SALARY_MAN => '上班族貸',
        ];
        $file_name = $product_type_dict[$input['product_id']] ?? 'export';
        $file_name .= date('Ymd') . '.xlsx';

        $this->spreadsheet_lib->download($file_name, $spreadsheet);
    }

	public function index(){
		$input 						= $this->input->get(NULL, TRUE);
		$this->load->view('admin/_header');
		if(!isset($input['target_id'])){
			$this->load->view('admin/_title',$this->menu);
		}

		if(isset($input['company']) && $input['company'] == 1) {
			$page_data = $this->company_index($input);
			$this->load->view('admin/risk_target_company',$page_data);
		}else{
			$page_data = $this->person_index($input);
			$this->load->view('admin/risk_target_person',$page_data);
		}

		$this->load->view('admin/_footer');
	}

	private function person_index($input) {
		$page_data 					= array('type'=>'list');
		$list 						= array();
		$user_list 					= array();
		$cer_list 					= array();
		$user_investor_list 		= array();
		$certification_investor_list = array();
		$company = isset($input['company']) ? true : false;

		$target_status = [0,1,2,21,22,30,31,32];
		$cer_parm = [
			'status'	=> array(0,1,3),
		];

		$cer_parm['investor'] = isset($input['investor']) ? $input['investor'] : 0;

		$user_certification_list	= $this->user_certification_model->get_many_by($cer_parm);
		if($user_certification_list){
			foreach($user_certification_list as $key => $value){
				if( $cer_parm['investor'] == 1){
					$user_investor_list[$value->user_id] = $value->user_id;
				}else{
					$user_list[$value->user_id] = $value->user_id;
				}
			}
		}

		if($user_investor_list){
			ksort($user_investor_list);
			foreach($user_investor_list as $key => $value){
				$certification_investor_list[$value] = $this->certification_lib->get_last_status($value,1);
				if(isset($certification_investor_list[$value][3]['certification_id'])){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_certification_id'	=> $certification_investor_list[$value][3]['certification_id'],
					));
					$certification_investor_list[$value]['bank_account']  = $bank_account;
				}
			}
		}

		if($user_list){
			$target_parm = [
				'user_id'	=> $user_list,
				'status'	=> $target_status
			];
			isset($input['company'])
				? $input['company'] == 1
				?$target_parm['product_id >='] = 1000
				:$target_parm['product_id <'] = 1000
				: '';
			$targets = $this->target_model->order_by('user_id','desc')->get_many_by($target_parm);
			if($targets){
				foreach($targets as $key => $value){
					$list[$value->id] = $value;
				}
			}
		}

		$listStage = [];
		if($list){
			$userStatusList = $this->target_model->getUserStatusByTargetId(array_keys($list));
			$userStatusList = array_column($userStatusList, 'total_count', 'user_id');

			ksort($list);
			foreach($list as $key => $value){
				if(isset($userStatusList[$value->user_id]) && $userStatusList[$value->user_id] > 0) {
					$value->user_status = 1;
				}else{
					$value->user_status = 0;
				}

				$status = $company ? $value : false;
				!isset($cer_list[$value->user_id]) ? $cer_list[$value->user_id] = $this->certification_lib->get_last_status($value->user_id, 0, 0, $status, FALSE, FALSE, TRUE) : '';
				$value->certification = $cer_list[$value->user_id];
				if(isset($value->certification[3]['certification_id'])){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_certification_id'	=> $value->certification[3]['certification_id'],
					));
					$value->bank_account 	 	 = $bank_account;
					$value->bank_account_verify = isset($bank_account->verify)&&$bank_account->verify==1?1:0;
				}
				elseif($value->product_id >= 1000){
					$value->bank_account 	 	 = '';
					$value->bank_account_verify = 1;
				}
				$value->certification_stage_list = $this->certification_lib->getCertificationsStageList($value->product_id);
				$tempCertList = array_reduce($cer_list[$value->user_id], function ($list, $item) {
					$list[$item['id']] = ['id' => $item['id'], 'status' => $item['user_status']];
					return $list;
				}, []);
				$filteredCertIds = $this->certification_lib->filterCertIdsInStatusList($tempCertList, [1]);
				$certStage1 = $this->certification_lib->checkVerifiedStage($value->product_id, $filteredCertIds, 0);
				if(!$certStage1) {
					$listStage[0][] = $list[$key];
				}else{
					$certStage2 = $this->certification_lib->checkVerifiedStage($value->product_id, $filteredCertIds, 1);
					if(!$certStage2) {
						$listStage[1][] = $list[$key];
					}else{
						$listStage[2][] = $list[$key];
					}
				}
			}
			if(!empty($this->role_info['group']))
				$listStage = array_intersect_key($listStage, array_flip($this->role_info['group']));
		}

		$page_data['list'] 					= $listStage;
		$page_data['certification_investor_list'] 	= $certification_investor_list;
		$page_data['certification'] 		= $this->config->item('certifications');
		$page_data['status_list'] 			= $this->target_model->status_list;
		$page_data['product_list']			= $this->config->item('product_list');
		$page_data['sub_product_list'] = $this->config->item('sub_product_list');
		$page_data['input'] = $input;
		return $page_data;
	}

	private function company_index($input) {
		$page_data 					= array('type'=>'list');
		$list 						= array();
		$plist 						= array();
		$cer_list 					= array();
		$certification_investor_list = array();
		$cer = $this->config->item('certifications');
		$product_list = $this->config->item('product_list');
        $this->load->library('loanmanager/product_lib');

		$target_status = [
			TARGET_WAITING_APPROVE,
			TARGET_WAITING_SIGNING, TARGET_WAITING_VERIFY,
			TARGET_ORDER_WAITING_SIGNING,
			TARGET_ORDER_WAITING_VERIFY,
			30,
			31,
			32,
			TARGET_BANK_VERIFY,
			TARGET_BANK_GUARANTEE,
			TARGET_BANK_LOAN
		];

		//分類身分供風控頁面使用
		$character = [
			0 => 'owner',
			1 => 'owner',
			2 => 'guarantor',
			3 => 'spouse',
			4 => 'guarantor',
			5 => 'guarantor',
		];

		$cer_parm['investor'] = isset($input['investor']) ? $input['investor'] : 0;
		if($cer_parm['investor'] == 1){
			$cer_parm['certification_id'] = [1, 3, 5, 6];
			$cer_parm['status'] = [0, 3];
			$user_certification_list	= $this->user_certification_model->get_many_by($cer_parm);
			if($user_certification_list){
				foreach($user_certification_list as $key => $value){
					$user_investor_list[$value->user_id] = $value->user_id;
				}
			}
			$investor_cer = [CERTIFICATION_IDENTITY, CERTIFICATION_DEBITCARD, CERTIFICATION_EMERGENCY, CERTIFICATION_EMAIL];
			foreach($investor_cer as $ckey => $cvalue){
				$useCer[$cvalue] = $cer[$cvalue];
			}
			ksort($user_investor_list);
			foreach($user_investor_list as $key => $value){
				$certification_investor_list[$value] = $this->certification_lib->get_last_status($value,INVESTOR);
				if(isset($certification_investor_list[$value][3]['certification_id'])){
					$bank_account 	= $this->user_bankaccount_model->get_by(array(
						'user_certification_id'	=> $certification_investor_list[$value][3]['certification_id'],
					));
					$certification_investor_list[$value]['bank_account']  = $bank_account;
				}
			}
		}else{
			$useCer = [];
			$target_parm = [
                'status' => $target_status,
                'sub_status != ' => TARGET_SUBSTATUS_WAITING_TRANSFER_INTERNAL
			];
			isset($input['company'])
				? $input['company'] == 1
				?$target_parm['product_id >='] = PRODUCT_FOR_JUDICIAL
				:$target_parm['product_id <'] = PRODUCT_FOR_JUDICIAL
				: '';

			isset($input['target_id']) ? $target_parm['id'] = $input['target_id'] : '';
			$targets = $this->target_model->order_by('user_id','desc')->get_many_by($target_parm);
			if($targets){
                $this->load->model('loan/target_meta_model');
				foreach($targets as $key => $value) $list[$value->id] = $value;
				ksort($list);
				foreach($list as $key => $value){
					$product = $product_list[$value->product_id];
					$sub_product_id = $value->sub_product_id;
					if($this->is_sub_product($product,$sub_product_id)){
						$product = $this->trans_sub_product($product,$sub_product_id);
					}
                    $product_certs = $this->product_lib->get_product_certs_by_product_id($value->product_id, $value->sub_product_id, [ASSOCIATES_CHARACTER_REGISTER_OWNER]);

                    foreach($product_certs as $ckey => $cvalue){
						if(!isset($useCer[$value->product_id][$cvalue])){
							$useCer[$value->product_id][$cvalue] = $cer[$cvalue];
						}
					}

					//產品是否需要保證人
					if($product['checkOwner'] ?? false){
						$associates_list = $this->target_lib->get_associates_user_data($value->id, 'all', [0, 1], false);
						foreach($associates_list as $associate){
							$setCharacter = $character[$associate->character];
							$status = 1;
							if($associate->user_id != null){
								if($cer_status = $this->certification_lib->get_last_status($associate->user_id, BORROWER)){
									foreach($product_certs as $cer_statusKey => $cer_statusValue) {
										if($cer_statusValue < PRODUCT_FOR_JUDICIAL && isset($cer_status[$cer_statusValue])){
											$cer_status[$cer_statusValue]['user_status'] != 1 ? $status = 0 : '';
										}
									}
								}
							}
							else{
								$status = 0;
							}
							$value->associate[$setCharacter] = $status;
						}
					}

                    if ( ! isset($cer_list[$value->user_id]))
                    {
                        $cer_list[$value->user_id] = $this->certification_lib->get_last_status($value->user_id, BORROWER, 1, $value, FALSE, FALSE, TRUE);
                    }
                    else
                    {
                        $cer_list[$value->user_id] = array_replace($cer_list[$value->user_id], $this->certification_lib->get_last_status($value->user_id, BORROWER, 1, $value, FALSE, FALSE, TRUE));
                    }
					$value->certification = $cer_list[$value->user_id];
					if(isset($list[$key]->certification[3]['certification_id'])){
						$bank_account 	= $this->user_bankaccount_model->get_by(array(
							'user_certification_id'	=> $value->certification[3]['certification_id'],
						));
						$value->bank_account 	 	 = $bank_account;
						$value->bank_account_verify = $bank_account->verify==1?1:0;
					}
					elseif($value->product_id >= PRODUCT_FOR_JUDICIAL){
						$value->bank_account 	 	 = '';
						$value->bank_account_verify = 1;
					}
					// 所有認證最後更新時間
					$lastUpdate = max(array_column($cer_list[$value->user_id], 'updated_at'));
					$value->lastUpdate = ! empty($lastUpdate) ? $lastUpdate : $value->updated_at;
					$plist[$value->product_id][$key] = $value;

                    // 判斷DD查核是否已填寫完
                    $target_meta_info = $this->target_meta_model->get_required_key_value($value->id);
                    if (count($target_meta_info) !== count($this->target_meta_model::REQUIRED_KEY))
                    {
                        $plist[$value->product_id][$key]->dd_edit_done = FALSE;
                    }
                    else
                    {
                        $plist[$value->product_id][$key]->dd_edit_done = TRUE;
                    }
                }
			}
		}

		$page_data['list'] 					= $plist;
		$page_data['certification_investor_list'] 	= $certification_investor_list;
		$page_data['certification'] 		= $useCer;
		$page_data['status_list'] 			= $this->target_model->status_list;
		$page_data['product_list']			= $product_list;
		$page_data['sub_product_list'] = $this->config->item('sub_product_list');
		$page_data['externalCooperation'] = $this->config->item('externalCooperation');
		$page_data['input'] = $input;
		return $page_data;
	}

	function credit(){
		$page_data 	= array('type'=>'list','list'=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();
		$where		= array();
		$fields 	= ['user_id'];

		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}

		if(!empty($where)){
			$list = $this->credit_model->order_by('expire_time','desc')->get_many_by($where);
			if($list){
				foreach($list as $key => $value){
					$user = $this->user_model->get($value->user_id);
					$list[$key]->user_name 	= $user->name;
				}
			}
		}

		$page_data['list'] 			= $list;
		$page_data['status_list']	= $this->credit_model->status_list;
		$page_data['product_list']	= $this->config->item('product_list');
        $page_data['sub_product_list'] = $this->config->item('sub_product_list');

        $this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/credit_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function loaned_wait_push(){
		$this->load->model('admin/debt_processing_model');
		$this->load->model('admin/debt_audit_model');
		$page_data 		= array('type'=>'list');
		$input 			= $this->input->get(NULL, TRUE);
		$where			= ['status'=>5,'delay'=>1];
		$list 			= [];
		$fields 		= ['status','target_no','user_id','all'];
		$push_data		= [];
		$result_data	= [];
		if(isset($input['slist'])&&$input['slist']!=null){
			$page_data['slist']=$input['slist'];
			$input['status']=1;
		}

		if(!empty($input)){
			foreach ($fields as $field) {
				if (isset($input[$field])&&$input[$field]!='') {
					if($field!='status'){
						$where[$field] = $input[$field];
					}
				}
			}
		}

		$list = $this->target_model->get_many_by($where);
		if($list){
			foreach($list as $key => $value){
				$temp=$this->debt_audit_model->get_by(array('target_id'=> $value->id));
				$list[$key]->push_status = $temp?1:0;
				if(isset($input['status'])&&$input['status']!=null){
					$list[$key]->push_status==$input['status']?$result_data[$key]=$list[$key]:null;
				}
				else{
					$result_data[$key]=$list[$key];
				}
			}
		}
		$page_data['instalment_list']	= $this->config->item('instalment');
		$page_data['repayment_type']	= $this->config->item('repayment_type');
		$page_data['list'] 				= $result_data;
		$page_data['push_status_list'] 	= $this->debt_processing_model->push_status_list;
		$page_data['status_list'] 		= $this->target_model->status_list;
		$page_data['delay_list'] 		= $this->target_model->delay_list;
		$page_data['name_list'] 		= $this->admin_model->get_name_list();

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk/risk_loaned',$page_data);
		$this->load->view('admin/_footer');
	}

	public function push_info(){
		$this->load->model('admin/debt_processing_model');
		$this->load->model('user/user_meta_model');
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$target_id	= isset($input['id'])?intval($input['id']):0;
		$list		= $this->debt_processing_model->order_by('created_at','desc')->get_many_by(array('target_id'=> $target_id));
		$user_id	= $this->target_model->get($target_id)->user_id;
		$meta_data 	= [];
		$meta 		= $this->user_meta_model->get_many_by(array('user_id'=>$user_id));
		$info 		= $this->user_model->get($user_id);

		if($list){
			foreach($list as $key => $value){
				$list[$key]->admin_name = $this->admin_model->get($value->admin_id)->name;
			}
		}

		if($meta){
			foreach($meta as $key => $value){
				$meta_data[$value->meta_key] = $value->meta_value;
			}
		}

		if(isset($input['slist']) && $input['slist']!=null){
			$page_data['slist']=$input['slist'];
		}

		$page_data['id']	= $target_id;
		$page_data['list']	= $list;
		$page_data['data']	= $info;
		$page_data['meta']	= $meta_data;
		$page_data['push_by_status_list'] 	= $this->debt_processing_model->push_by_status_list;
		$page_data['result_status_list'] 	= $this->debt_processing_model->result_status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/risk/risk_push_info',$page_data);
		$this->load->view('admin/_footer');
	}

	public function push_info_add(){
		$this->load->model('admin/debt_processing_model');
		$input 	= $this->input->post(NULL, TRUE);
		$get 	= $this->input->get(NULL, TRUE);
		$info 	= $this->target_model->get($get['id']);
		$rs 	= $this->debt_processing_model->insert(array(
			'admin_id'		=> $this->login_info->id,
			'user_id'		=> $info->user_id,
			'target_id'		=> $get['id'],
			'contact_person'=> $input['contact_person'],
			'contact_phone'	=> $input['contact_phone'],
			'result'		=> $input['result'],
			'push_by'		=> $input['push_by'],
			'remark'		=> $input['remark'],
			'start_time'	=> $input['start_time'],
			'end_time'		=> $input['end_time']
		));
	}

	public function push_info_remove(){
		$this->load->model('admin/debt_processing_model');
		$input 	= $this->input->post(NULL, TRUE);
		$rs 	= $this->debt_processing_model->delete($input['id']);
	}

	public function push_info_update(){
		$this->load->model('admin/debt_processing_model');
		$input 	= $this->input->post(NULL, TRUE);
		$rs 	= $this->debt_processing_model->update($input['id'],array(
			'admin_id'		=> $this->login_info->id,
			'contact_person'=> $input['contact_person'],
			'contact_phone'	=> $input['contact_phone'],
			'result'		=> $input['result'],
			'push_by'		=> $input['push_by'],
			'remark'		=> $input['remark'],
			'start_time'	=> $input['start_time'],
			'end_time'		=> $input['end_time']
		));
	}

	public function push_audit(){
		$this->load->model('admin/debt_audit_model');
		$role_name 	= $this->role_model->get_name_list();
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$target_id	= isset($input['id'])?intval($input['id']):0;
		$list 		= array();
		$list		= $this->debt_audit_model->order_by('created_at','desc')->get_many_by(array('target_id'=> $target_id));

		if($list){
			foreach($list as $key => $value){
				$temp=$this->admin_model->get($value->admin_id);
				$list[$key]->admin_name = $temp->name;
				$list[$key]->role_id = $temp->role_id;
			}
		}

		if(isset($input['slist']) && $input['slist']!=null){
			$page_data['slist']=$input['slist'];
		}
		$page_data['id']	=$target_id;
		$page_data['list']	=$list;
		$page_data['role_name']	=$role_name;
		$page_data['product_level'] 		= $this->debt_audit_model->product_level;

		$this->load->view('admin/_header');
		$this->load->view('admin/risk/risk_push_audit',$page_data);
		$this->load->view('admin/_footer');
	}

	public function push_audit_add(){
		$this->load->model('admin/debt_audit_model');
		$input 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		$delay_days	= $this->target_model->get($get['id'])->delay_days;
		$last_push	= $this->debt_audit_model->order_by('created_at','desc')->get_by(array('target_id'=> $get['id']))->next_push;
		$info 		= $this->target_model->get($get['id']);
		if(isset($last_push)){
			$start_time=$last_push;
		}else{
			$start_time=time()-$delay_days*86400;
		}
		$rs = $this->debt_audit_model->insert(array(
			'admin_id'		=> $this->login_info->id,
			'user_id'		=> $info->user_id,
			'target_id'		=> $get['id'],
			'remark'		=> $input['remark'],
			'product_level'	=> $input['product_level'],
			'next_push'		=> $input['next_push'],
			'result'		=> $input['result'],
			'end_time'		=> $input['end_time'],
			'start_time'	=> $start_time
		));
	}

	function judicial_associates(){
		$page_data 	= array('type'=>'list','list'=>array());
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();

		if(isset($input['target_id'])) {
			//target_no
			$target_parm['id'] = $input['target_id'];
			$target = $this->target_model->get_by($target_parm);

			$product_list = $this->config->item('product_list');
			$certification_list = $this->config->item('certifications');
			$product = $product_list[$target->product_id];
			$sub_product_id = $target->sub_product_id;
			if ($this->is_sub_product($product, $sub_product_id)) {
				$product = $this->trans_sub_product($product, $sub_product_id);
			}

            $this->load->library('loanmanager/product_lib');
            $product_certs = $this->product_lib->get_product_certs_by_product_id($target->product_id, $target->sub_product_id, [ASSOCIATES_CHARACTER_REGISTER_OWNER]);
			foreach ($product_certs as $ckey => $cvalue) {
				if (!isset($useCer[$cvalue]) && $cvalue < CERTIFICATION_FOR_JUDICIAL) {
					$useCer[$cvalue] = $certification_list[$cvalue];
				}
			}
			//									$associate_certification_ids = [1,3,6,11,501,9,7];
			$associates_list = $this->target_lib->get_associates_user_data($target->id, 'all', [0, 1], false);
            $this->load->model('user/user_certification_model');
			foreach ($associates_list as $associate) {
				$status = 1;
				$lastUpdate = 0;
				if ($associate->user_id != null) {
					if ($cer_status = $this->certification_lib->get_last_status($associate->user_id, BORROWER)) {
						foreach($product_certs as $cer_statusKey => $cer_statusValue) {
							if($cer_statusValue < CERTIFICATION_FOR_JUDICIAL){
								if(isset($cer_status[$cer_statusValue])){
									$cer_status[$cer_statusValue]['user_status'] != 1 ? $status = 0 : '';
									$associate->certification[$cer_statusValue] = $cer_status[$cer_statusValue];
									$cer_status[$cer_statusValue]['updated_at'] > $lastUpdate ? $lastUpdate = $cer_status[$cer_statusValue]['updated_at'] : '';

									//驗證銀行帳號
//									if($cer_statusValue == 3){
//										$associate->bank_account_verify = 0;
//										$bank_account 	= $this->user_bankaccount_model->get_by(array(
//											'user_certification_id'	=> $cer_status[$cer_statusValue]['certification_id'],
//										));
//										if($bank_account){
//											$associate->bank_account = $bank_account;
//											$associate->bank_account_verify = $bank_account->verify==1?1:0;
//										}
//									}
								}
								else{
									$status = 0;
								}
							}
						}
					}
//					$associate->bank_account_verify == 1 && $status == 1 ? $status = 2 : '';
				}else{
                    if ($associate->character == ASSOCIATES_CHARACTER_SPOUSE)
                    {
                        $cert_info = $this->user_certification_model->get_spouse_last_certification_info($input['target_id'], CERTIFICATION_INVESTIGATIONA11, BORROWER);
                        $associate->certification[CERTIFICATION_INVESTIGATIONA11] = $cert_info;
                    }
					$status=0;
				}
				$associate->lastUpdate = $lastUpdate;
				$associate->cerStatus = $status;
				$list[] = $associate;
			}
		}

		$character_list = $this->config->item('character');
		$page_data['character_list'] = $character_list;
		$page_data['list'] 	= $list;
		$page_data['input'] = $input;
		$page_data['target_no'] = $target->target_no;
		$page_data['certification_list'] = $useCer;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/judicial_person/judicial_associates_certification_list',$page_data);
		$this->load->view('admin/_footer');

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
            'checkOwner' => $product['checkOwner'],
            'status' => $sub_product['status'],
        );
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

    public function black_list(){
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/black_list');
        $this->load->view('admin/_footer');

    }

	// 授信審核表
	// public function credit_management(){
	//
	// 	return
	// }
	public function sme_loan(){
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/risk/sme_loan');
		$this->load->view('admin/_footer');
	}

    // 入屋現勘/遠端視訊預約時間
    public function booking_timetable()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/booking_timetable');
        $this->load->view('admin/_footer');
    }

    public function get_booking_timetable()
    {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        $this->load->library('booking_lib');
        $response = $this->booking_lib->get_whole_booking_timetable($start_date, $end_date);

        echo json_encode($response);
        die();
    }

    public function create_booking()
    {
        $target_id = 0;
        $user_id = 0;

        $input = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);
        $date = $input['date'] ?? '';
        $time = $input['time'] ?? '';
        $admin_id = $this->login_info->id;
        $title = "Admin-{$admin_id} arrangement";

        $this->load->library('booking_lib');
        $response = $this->booking_lib->create_booking($target_id, $user_id, $date, $time, $title);

        echo json_encode($response);
        die();
    }

    public function site_survey_booking($target_id = '')
    {
        $page_data = [];
        if (empty($target_id))
        {
            goto END;
        }

        $this->load->library('booking_lib');
        $booking_info = $this->booking_lib->get_booked_list_by_target($target_id);
        if ($booking_info['result'] !== 'SUCCESS' || empty($booking_info['data']['booking_table']))
        {
            goto END;
        }

        $booking_info = current($booking_info['data']['booking_table']);
        $page_data = [
            'date' => empty($booking_info['date']) ? '' : (new DateTimeImmutable($booking_info['date']))->format('Y-m-d'),
            'time' => empty($booking_info['session_name']) ? '' : $booking_info['session_name'],
        ];

        END:
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/site_survey_booking', $page_data);
        $this->load->view('admin/_footer');
    }
}

