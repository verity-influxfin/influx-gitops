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
		$return_msg = [];
        $send_request_data = [];
		$this->load->library('output/json_output');

		$this->load->model('loan/target_model');
		$target_info = $this->target_model->get_by(['id'=>$target_id]);
		if($target_info){
			$target_data = isset($target_info->target_data) ? json_decode($target_info->target_data,true): '';
			if($table_type == 'check'){
				$this->load->library('mapping/sk_bank/check_list');
				// 取得交易序號相關資料
				$msg_no_info = $this->getMappingMsgNo($target_id);
                // 有上筆送出資料時
				if($msg_no_info){
					$response['msg_no'] = isset($msg_no_info['data']['send_log']['msg_no']) ? $msg_no_info['data']['send_log']['msg_no'] : '';
					$response['case_no'] = isset($msg_no_info['data']['send_log']['case_no']) ? $msg_no_info['data']['send_log']['case_no']: '';
					if(isset($msg_no_info['data']['send_log']['send_success'])){
						$response['send_success'] = $msg_no_info['data']['send_log']['send_success'] == 1 ? '成功' : '失敗';
					}else{
						$response['send_success'] = '未送出';
					}

                    if(!empty($msg_no_info['data']['send_log'])){
                        $return_msg = json_decode($msg_no_info['data']['send_log']['response_content'],true);
                        // 填入上筆送出資料
                        if(!empty($msg_no_info['data']['send_log']['request_content'])){
                            $send_request_data = json_decode($msg_no_info['data']['send_log']['request_content'],true);
                            $response['CompId_content'] = isset($send_request_data['unencrypted']['CompId']) ? $send_request_data['unencrypted']['CompId'] : '';
                            $response['PrincipalId_content'] = isset($send_request_data['unencrypted']['PrincipalId']) ? $send_request_data['unencrypted']['PrincipalId'] : '';
                            $send_request_data = !empty($send_request_data['unencrypted']['Data']) ? $send_request_data['unencrypted']['Data'] : [];
                            if(!empty($send_request_data)){
                                foreach($send_request_data as $send_request_data_key => $send_request_data_value){
                                    $response[$send_request_data_key.'_content'] = $send_request_data_value;
                                }
                            }
                        }
                    }
                    if(isset($return_msg['ReturnMsg'])){
                        $response['return_msg'] = $return_msg['ReturnMsg'];
                    }
                    if(!isset($return_msg['ReturnMsg']) && isset($msg_no_info['data']['send_log']['error_msg'])){
                        $response['return_msg'] = $msg_no_info['data']['send_log']['error_msg'];
                    }
					$response['action_user'] = isset($msg_no_info['data']['send_log']['action_user']) ? $msg_no_info['data']['send_log']['action_user'] : '';
				}

				//
				if(empty($send_request_data)){
                    $user_list = [];
					$this->load->library('Target_lib');
                    // 加入法人ID
                    $user_list[] = $target_info->user_id;
					// 找案件的相關人員(負責人、實際負責人、配偶、保證人) User ID
					$guarantors = $this->target_lib->get_associates_user_data($target_id, 'all', [0 ,1], false);
					if($guarantors && is_array($guarantors)){
						foreach($guarantors as $k=>$v){
                            $user_list[] = isset($v->user_id) ? $v->user_id : '';
							// if($v->status == 1){
							// 	$user_id = isset($v->user_id) ? $v->user_id : '';
							// 	if($user_id){
							// 		// 負責人
							// 		if($v->character == 0 || $v->character == 1){
							// 			$user_role = 'Pr';
							// 		}
							// 		// 配偶
							// 		if($v->character == 3){
							// 			$user_role = 'Spouse';
							// 		}
							// 		// 甲保證人
							// 		if($v->character == 4){
							// 			$user_role = 'GuOne';
							// 		}
							// 		// 乙保證人
							// 		if($v->character == 5){
							// 			$user_role = 'GuTwo';
							// 		}
							// 	}
							// }
						}

					}

                    if(!empty($user_list)){
                        $this->load->model('user/user_certification_model');
                        $certification_info = $this->user_certification_model->get_skbank_check_list($user_list);

                        if(!empty($certification_info)){
                            foreach($certification_info as $info_value){
                                $content = json_decode($info_value->content,true);
                                if(is_array($content) && isset($content['skbank_form']) && !empty($content['skbank_form'])){
                                    $data = array_map(function($key,$values) {
                                        $key = $key.'_content';
                                        return [$key=>$values];
                                    }, array_keys($content['skbank_form']), $content['skbank_form']);
                                    $data = array_reduce($data, 'array_merge', array());
                                    $response = array_merge($response,$data);
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
		$data_type = isset($input['data_type']) ? $input['data_type'] : 'text';

		$this->load->library('mapping/sk_bank/msgno');
		$response = $this->msgno->getSKBankInfoByTargetId($target_id, $data_type);

		if($action == 'send'){
			$this->load->model('skbank/LoanTargetMappingMsgNo_model');
			$data = [
				'target_id' => $target_id,
				'msg_no' => isset($response['data']['msg_no']) ? $response['data']['msg_no'] : '',
				'action_user_id' => $action_user,
				'type' => $data_type,
				'date' => isset($response['data']['msg_no']) ? substr($response['data']['msg_no'],0,8) : '',
				'serial_number' => isset($response['data']['msg_no']) ? substr($response['data']['msg_no'],8) : '',

			];
			$this->LoanTargetMappingMsgNo_model->insert($data);
		}

		if($action){
			$this->load->library('output/json_output');
			$this->json_output->setStatusCode(200)->setResponse((array)$response)->send();
		}else{
			return $response;
		}
	}

    public function saveCheckListData(){
        $input = $this->input->get(NULL, TRUE);
        // $request_data = $this->input->post(NULL, TRUE);
        $request_data = $this->input->raw_input_stream;
        $this->load->model('skbank/LoanTargetMappingMsgNo_model');
        $mapping_info = $this->LoanTargetMappingMsgNo_model->get_by(['msg_no'=>$input['msg_no'],'type'=>$input['data_type']]);

        if($mapping_info){
            $mapping_info = $this->LoanTargetMappingMsgNo_model->update($mapping_info->id,['content'=>$request_data]);
        }
        // print_r($input);exit;
    }
}
