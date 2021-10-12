<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Ocr extends MY_Admin_Controller
{

	public $result_config = [
		'amendment_of_register' => [
			'taxId' => 'tax_id',
			'name' => 'name',
			'address' => 'address',
			'amountOfCapital' => 'capital',
			'paidInCapital' => 'capital',
			'owner' => 'owner',
			'director_title_1' => 'director_title',
			'director_name_1' => 'director_name',
			'director_id_1' => 'owner_id',
		],

		'income_statement' => [
			'companyName' => 'company_name',
			'taxId' => 'company_tax_id',
			'89' => 'input_89',
			'90' => 'input_90',
			'4' => 'input_4_1',
			'4_AddAdjust' => 'input_4_2',
			'end_at' => 'report_time',

		],

		'insurance_table' => [
			'companyInfo' => 'company_name',
			'insurancePeriod' => 'range',
			'reportTime' => 'report_time'
		],

	];
	protected $edit_method = array();

	public function __construct()
    {
		parent::__construct();
        $this->load->library('ocr/report_scan_lib');
				$this->load->library('ocr/report_check_lib');
	}

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/ocr/list');
        $this->load->view('admin/_footer');
    }

    public function reports()
    {
        $input = $this->input->get(NULL, TRUE);
        $type = isset($input['type']) ? $input['type'] : '';
        $offset = isset($input['offset']) ? intval($input['offset']) : 1;
        $limit = isset($input['limit']) ? intval($input['limit']) : 60;

        $types = ['income_statements', 'balance_sheets', 'business_tax_return_reports', 'insurance_tables', 'amendment_of_registers','credit_investigations', 'insurance_table_company'];
        $this->load->library('output/json_output');
        if (!in_array($type, $types)) {
            $this->json_output->setStatusCode(400)->setErrorCode(ArgumentError)->send();
        }

        $response = $this->report_scan_lib->requestAll($type, $offset, $limit);

        $logs_list = [
            'balance_sheets' => 'balance_sheet_logs',
            'income_statements' => 'income_statement_logs',
            'business_tax_return_reports' => 'business_tax_return_logs',
            'insurance_tables' => 'insurance_table_logs',
            'amendment_of_registers' => 'amendment_of_register_logs',
            'credit_investigations' => 'credit_investigation_logs',
        ];

        if (!$response || $response->status == 204) {
            $this->json_output->setStatusCode(204)->send();
        }

				$img_list = [];
				if(isset($logs_list[$type])){
					$type_logs = $logs_list[$type];
					$imgs = $response->response->$type_logs->items;
					foreach($imgs as $v){
						array_push($img_list,$v->id);
					}
				}

				if($img_list){
					$this->load->model('log/log_image_model');
					if($type == 'amendment_of_registers'){
						$img_list = $this->log_image_model->getUrlByGroupID($img_list);
						if($img_list){
							foreach($response->response->$type_logs->items as $k=>$v){
								foreach($img_list as $v1){
									if($v1->group_info==$v->id){
										$response->response->$type_logs->items[$k]->url[$v1->id] =isset($v1->url)?$v1->url: '#';
									}
								}
							}
						}
					}else{
						$img_list = $this->log_image_model->getUrlByID($img_list);
						if($img_list){
							foreach($response->response->$type_logs->items as $k=>$v){
								foreach($img_list as $v1){
									if($v1->id==$v->id){
										$response->response->$type_logs->items[$k]->url =isset($v1->url)?$v1->url: '#';
									}
								}
							}
						}
					}
				}

        $this->json_output->setStatusCode(200)->setResponse((array) $response->response)->send();
    }

    public function report()
    {
        $input = $this->input->get(NULL, TRUE);
        $type = isset($input['type']) ? $input['type'] : '';
				$id = isset($input['id']) ? intval($input['id']) : 0;
				$certification_id = isset($input['certification']) ? intval($input['certification']) : '';
				$certification_info = [];

        if (!$this->input->is_ajax_request()) {
            if ($type == 'income_statement') {
                $this->load->view('admin/ocr/income_statement');
								return;
            }

						if ($type == 'balance_sheet') {
                $this->load->view('admin/ocr/balance_sheet');
								return;
            }

						if ($type == 'business_tax_return') {
                $this->load->view('admin/ocr/business_tax_return_reports');
								return;
            }

						if ($type == 'insurance_table') {
							$response = $this->report_scan_lib->requestForAResult($type, $id);
							$user_type = isset($response->response->insurance_table_logs->items[0]->insurance_table->type) ? $response->response->insurance_table_logs->items[0]->insurance_table->type : '';
							if($user_type && $user_type == 'company'){
								$type = 'company';
							}
                            if($user_type && $user_type == 'person'){
								$type = 'person';
							}
              $this->load->view("admin/ocr/insurance_tables/insurance_{$type}");
							return;
            }
            if($type == 'insurance_table_company'){
                $this->load->view("admin/ocr/insurance_tables/insurance_company");
                return;
            }

						if ($type == 'amendment_of_register') {

								$response = $this->report_scan_lib->requestForAResult($type, $id);
								$company_type = isset($response->response->amendment_of_register_logs->items[0]->amendment_of_register->companyInfo->ltdType) ? $response->response->amendment_of_register_logs->items[0]->amendment_of_register->companyInfo->ltdType : '';
								$company_registered = isset($response->response->amendment_of_register_logs->items[0]->amendment_of_register->companyInfo->registeredType) ? $response->response->amendment_of_register_logs->items[0]->amendment_of_register->companyInfo->registeredType : '';
								if($company_type && $company_registered){
									if($company_registered != 'first'){
										$company_registered = 'changed';
									}else{
										if($company_type == 'limited'){
											$company_registered = '';
										}else{
											$company_registered = 'first';
										}
									}
									$company_type = strtolower($company_type);
									$this->load->view("admin/ocr/amendment_of_registers/{$company_type}{$company_registered}");
								}else{
									$this->load->view("admin/ocr/amendment_of_registers/limitedbyshareschanged");
								}
								return;
						}

						if ($type == 'balance_sheet_check') {
                $this->load->view('admin/ocr/balance_sheet_check');
								return;
            }

						if ($type == 'income_statement_check') {
                $this->load->view('admin/ocr/income_statement_check');
								return;
            }

						if ($type == 'credit_investigation') {
								$response = $this->report_scan_lib->requestForAResult($type, $id);
								$user_type = isset($response->response->credit_investigation_logs->items[0]->credit_investigation->applierType) ? $response->response->credit_investigation_logs->items[0]->credit_investigation->applierType : '';
								if($user_type){
									if($user_type == 'person'){
										$type = 'person';
									}else{
										$type = 'company';
									}
									$this->load->view("admin/ocr/credit_investigations/credit_investigation_{$type}");
								}else{
									$this->load->view("admin/ocr/credit_investigations/credit_investigation_person");
								}
								return;
            }
        }

        $types = [$type];
        if($type == 'insurance_table_company'){
            $type = 'insurance_table';
        }
        $this->load->library('output/json_output');
        if (!in_array($type, $types)) {
            $this->json_output->setStatusCode(400)->setErrorCode(ArgumentError)->send();
        }

				if($certification_id){
					$this->load->model('user/user_certification_model');
					$certification_info = $this->user_certification_model->get_many_by(array(
							'id'=> $certification_id
					));
					$response = isset($certification_info[0]->content) ? json_decode($certification_info[0]->content) : [];
					if(isset($response->$type->$id->data)){
						$response->response = $response->$type->$id->data;
						$response->response->data_type = 'deus';
						$this->json_output->setStatusCode(200)->setResponse((array) $response->response)->send();
						exit;
					}
				}

				$response = $this->report_scan_lib->requestForAResult($type, $id);
				if (!$response || $response->status == 204) {
						$this->json_output->setStatusCode(204)->send();
				}

				$check = isset($input['check']) ? intval($input['check']) : 0;
				if($check){
					$check_data = $this->report_check_lib->report_check($type, $response->response);
					$this->json_output->setStatusMessage($check_data['msg']);
					if($check_data['status'] == 'success'){
						$response->response = $check_data['data'];
					}
				}

        $this->json_output->setStatusCode(200)->setResponse((array) $response->response)->send();
    }

		public function save(){
			$input = $this->input->post(NULL, TRUE);
			$data = isset($input['table_data']) ? $input['table_data'] : '';
			$id = isset($input['id']) ? $input['id'] : '';
			$certification_id = isset($input['certification_id']) ? $input['certification_id'] : '';
			$type = isset($input['ocr_type']) ? $input['ocr_type'] : '';
			$action_user = isset($this->login_info->id) ? $this->login_info->id : '';

			if($certification_id && $type && $action_user && $id){
				$this->load->model('user/user_certification_model');
				$certification_info = $this->user_certification_model->get_many_by(array(
						'id'=> $certification_id
				));
				if($certification_info){
					$content = isset($certification_info[0]->content) ? json_decode($certification_info[0]->content,true) : [];
					// if($content){

					// 聯徵資料格式統一
					if($type == 'credit_investigation'){
						$this->load->library('mapping/user/certification_data');
						$data = $this->certification_data->transformJointCreditToOcrData($data);
					}
					$save_log = [
						'data' => $data,
						'action_user' => $action_user,
						'status' => 0,
						'send_time' => time(),
					];
					if($type == 'insurance_table_person'){
						$type = 'insurance_table';
					}
					$content[$type][$id] = $save_log;
					$this->user_certification_model->update($certification_id,array(
						'content' => json_encode($content)
					));
					// }else{
					// 	print_r('無此資料');exit;
					// }
				}
			}else{
				print_r('缺少參數');exit;
			}

			print_r('ok');exit;
		}

		public function send(){
			$input = $this->input->post(NULL, TRUE);
			$id = isset($input['id']) ? $input['id'] : '';
			$certification_id = isset($input['certification_id']) ? $input['certification_id'] : '';
			$type = isset($input['ocr_type']) ? $input['ocr_type'] : '';
			$table_data = isset($input['table_data']) ? $input['table_data'] : '';
			$action_user = isset($this->login_info->id) ? $this->login_info->id : '';

			if($certification_id && $type && $action_user && $id && $table_data){
				$this->load->model('user/user_certification_model');
				$certification_info = $this->user_certification_model->get_many_by(array(
						'id'=> $certification_id
				));
				if($certification_info){
					$content = isset($certification_info[0]->content) ? json_decode($certification_info[0]->content,true) : [];
					if(isset($content[$type][$id])){
						$save_result = [];
						$save_result['action_user'] = $action_user;
						$save_result['send_time'] = time();
						$save_result['status'] = '已由後台人員編輯';
						$save_result['origin_type'] = 'user_confirm';

						foreach($table_data as $k=>$v){
							if(isset($this->result_config[$type][$k])){
								$save_result[$this->result_config[$type][$k]] = $v;
							}else{
								$save_result[$k] = $v;
							}
						}

						$content['result'][$id] = $save_result;
						$content['error_location'][$id] = [];
						$this->user_certification_model->update($certification_id,array(
							'content' => json_encode($content),
							'status' => 0,
							'remark' => '',

						));
					}else{
						print_r('無此資料，請先按儲存');exit;
					}
				}
			}else{
				print_r('缺少參數');exit;
			}

			print_r('ok');exit;
		}
}
