<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Bankdata extends MY_Admin_Controller
{

		protected $edit_method = array();
		protected $mapping_config = [
			'check' => [
				'1003_credit_investigation',
				'1007_governmentauthorities',
				'1002_incomestatement',
				'1017_employeeinsurancelist',
				'1018_profilejudicial'
			],
		];

	public function __construct()
    {
		parent::__construct();
	}

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/ocr/list');
        $this->load->view('admin/_footer');
    }

    public function report()
    {
        $input = $this->input->get(NULL, TRUE);
        $target_id = isset($input['target_id']) ? $input['target_id'] : '';
		$table_type = isset($input['table_type']) ? $input['table_type'] : '';

		if(!$target_id || !$table_type){
			echo '參數不正確'.'參數：target_id = '.$target_id.' , table_type = '.$table_type;exit;
		}

        if (!$this->input->is_ajax_request()) {
			if($table_type == 'check'){
				$this->load->view('admin/sk_bank/check_list');
				return;
			}
        }
		$response = [];
		$this->load->library('output/json_output');

		$this->load->model('loan/target_model');
		$target_info = $this->target_model->get_by(['id'=>$target_id]);
		if($target_info){
			$target_data = isset($target_info->target_data) ? json_decode($target_info->target_data,true): '';
			if($table_type == 'check'){
				$this->load->model('user/user_certification_model');
				$this->load->model('user/user_meta_model');
				$this->load->library('mapping/sk_bank/check_list');
				// 取得交易序號相關資料
				$msg_no_info = $this->getMappingMsgNo($target_id);
				if($msg_no_info){
					$response['msg_no'] = isset($msg_no_info['data']['send_log']['msg_no']) ? $msg_no_info['data']['send_log']['msg_no'] : '';
					$response['case_no'] = isset($msg_no_info['data']['send_log']['case_no']) ? $msg_no_info['data']['send_log']['case_no']: '';
					if(isset($msg_no_info['data']['send_log']['send_success'])){
						$response['send_success'] = $msg_no_info['data']['send_log']['send_success'] == 1 ? '成功' : '失敗';
					}else{
						$response['send_success'] = '未送出';
					}
					$response['return_msg'] = isset($msg_no_info['data']['send_log']['response_content']) ? json_decode($msg_no_info['data']['send_log']['response_content'],true)['ReturnMsg']: '';
					$response['action_user'] = isset($msg_no_info['data']['send_log']['action_user']) ? $msg_no_info['data']['send_log']['action_user'] : '';
				}
				// 法人徵提資料

				// foreach($target_data['certification_id'] as $certification_id){
				// 	$certification_info = $this->user_certification_model->get_by(['id'=>$certification_id]);
				// 	if($certification_info){
				// 		if(isset($this->mapping_config['check'][$certification_info->certification_id]) && isset($certification_info->content)){
				// 			$name_of_get_data_function = 'get_'.$this->mapping_config['check'][$certification_info->certification_id].'_data';
				// 			$certification_content = json_decode($certification_info->content,true);
				// 			$meragre_array = $this->$name_of_get_data_function($certification_content);
				// 			$response = array_merge($response,$meragre_array);
				// 		}
				// 	}
				// }
				foreach($this->mapping_config['check'] as $v){
					$user_meta = $this->user_meta_model->get_by(['user_id'=>$target_info->user_id,'meta_key'=>$v]);
					$function_name = "get_{$v}_data";
					if($user_meta && isset($user_meta->meta_value) && method_exists($this->check_list,$function_name)){
						$meragre_array = $this->check_list->$function_name($user_meta->meta_value);
						$response = array_merge($response,$meragre_array);
					}
				}

				$this->load->library('Target_lib');
				// 找案件的相關人員(負責人、實際負責人、配偶、保證人) User ID
				$guarantors = $this->target_lib->get_associates_user_data($target_id, 'all', [0 ,1], false);
				// print_r($guarantors);exit;
				if($guarantors && is_array($guarantors)){
					foreach($guarantors as $k=>$v){
						if($v->status == 1){
							$user_id = isset($v->user_id) ? $v->user_id : '';
							if($user_id){
								// 負責人
								if($v->character == 0 || $v->character == 1){
									$user_role = 'Pr';
								}
								// 配偶
								if($v->character == 3){
									$user_role = 'Spouse';
								}
								// 甲保證人
								if($v->character == 4){
									$user_role = 'GuOne';
								}
								// 乙保證人
								if($v->character == 5){
									$user_role = 'GuTwo';
								}
								// 身份證資料
								$identity_info = $this->user_certification_model->get_by(['user_id'=>$user_id, 'certification_id'=>'1']);
								// print_r($identity_info);exit;
								if($identity_info){
									if(isset($identity_info->content)){
										$identity_info_content = json_decode($identity_info->content,true);
										$meragre_array = $this->check_list->get_company_user_data($identity_info_content,$user_role);
										$response = array_merge($response,$meragre_array);
									}
								}
								// 勞保異動明細資料
								$security_info = $this->user_certification_model->get_by(['user_id'=>$user_id, 'certification_id'=>'10']);
								if($security_info){
									if(isset($security_info->content)){
										$security_content = json_decode($security_info->content,true);
										$meragre_array = $this->check_list->get_insurance_table_user_data($security_content,$user_role);
										$response = array_merge($response,$meragre_array);
									}
								}
								// 自然人聯徵資料
								$credit_investigation = $this->user_meta_model->get_by(['user_id'=>$user_id,'meta_key'=>'9_credit_investigation']);
								if($credit_investigation){
									if(isset($credit_investigation->meta_value)){
										$credit_investigation_content = json_decode($credit_investigation->meta_value,true);
										$meragre_array = $this->check_list->get_9_credit_investigation_data($credit_investigation_content,$user_role);
										$response = array_merge($response,$meragre_array);
									}
								}
								// 個人資料表
								$profile_info = $this->user_meta_model->get_by(['user_id'=>$user_id,'meta_key'=>'11_profile']);
								if($profile_info){
									if(isset($profile_info->meta_value)){
										$profile_content = json_decode($profile_info->meta_value,true);
										$meragre_array = $this->check_list->get_11_profile_data($profile_content);
										$response = array_merge($response,$meragre_array);
									}
								}

							}
						}
					}

				}
				// 附件檢核表
				$meragre_array = $this->check_list->get_raw_data($target_info);
				$response = array_merge($response,$meragre_array);
			}
		}else{
			$this->json_output->setStatusCode(404)->setResponse(['找不到該案件資料'])->send();
		}
        $this->json_output->setStatusCode(200)->setResponse((array)$response)->send();
    }

	/**
	 * [getMappingMsgNo 取得新光交易編號]
	 * @param  string $target_id      [案件號]
	 * @param  string $action_user    [操作人員 ID]
	 * @param  string $action         [呼叫動作]
	 * @param  string $data_type      [送出資料類型]
	 * @return array $msg_no          [新光交易編號]
	 */
	public function getMappingMsgNo($target_id=''){
		$input = $this->input->get(NULL, TRUE);
        $target_id = isset($input['target_id']) ? $input['target_id'] : $target_id;
		$action_user = isset($this->login_info->id) ? $this->login_info->id : '';
		$action = isset($input['action']) ? $input['action'] : '';
		$data_type = isset($input['data_type']) ? $input['data_type'] : '';


		$this->load->library('mapping/sk_bank/msgno');
		$response = $this->msgno->getSKBankInfoByTargetId($target_id);

		if($action == 'send'){
			$this->load->model('skbank/loantargetmappingmsgno_model');
			$data = [
				'target_id' => $target_id,
				'msg_no' => isset($response['data']['msg_no']) ? $response['data']['msg_no'] : '',
				'action_user_id' => $action_user,
				'type' => $data_type,
				'date' => isset($response['data']['msg_no']) ? substr($response['data']['msg_no'],0,8) : '',
				'serial_number' => isset($response['data']['msg_no']) ? substr($response['data']['msg_no'],8) : '',

			];
			$this->loantargetmappingmsgno_model->insert($data);
		}

		if($action){
			$this->load->library('output/json_output');
			$this->json_output->setStatusCode(200)->setResponse((array)$response)->send();
		}else{
			return $response;
		}
	}
}
