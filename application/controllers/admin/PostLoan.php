<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use Symfony\Component\HttpClient\HttpClient;

class PostLoan extends MY_Admin_Controller {

	protected $edit_method = array('legal_doc');

	private $task_list = array(
		'legalConfirmLetter' => '存證信函',
	);

	private $process_status = array(
		0 => "系統通知/APP、Mail",
		1 => "LINE客服",
		2 => "人工電話",
		3 => "還款計畫訪談表",
		4 => "產品轉換",
		5 => "緊急聯絡人",
		6 => "存證信函-電子",
		7 => "存證信函-紙本",
		8 => "投資人-委任處理",
		9 => "投資人-自行處理",
		10 => "支付命令-聲請",
		11 => "支付命令-回函",
		12 => "支付命令-確認書",
		13 => "查調債務人課稅資料",
		14 => "強制執行-聲請",
		15 => "強制執行-查扣薪資財產",
		16 => "債權分配",
		17 => "債權憑證",
		18 => "結案",
		19 => "其他說明",
	);

	public function __construct() {
		parent::__construct();
		$this->load->model('user/virtual_account_model');
		$this->load->model('transaction/frozen_amount_model');
		$this->load->model('transaction/withdraw_model');
		$this->load->model('loan/investment_model');
		$this->load->library('passbook_lib');
		$this->login_info = check_admin();
	}

	public function index(){
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$where		= [];
		$list		= [];

		if(isset($input['virtual_account']) && $input['virtual_account']!='' ) {
			$where['virtual_account like'] = '%'.$input['virtual_account'].'%';
		}

		if(isset($input['user_id']) && $input['user_id']!='' ) {
			$where['user_id'] = $input['user_id'];
		}

		if(!empty($where)){
			$list = $this->virtual_account_model->order_by('user_id','ASC')->get_many_by($where);
		}

		$page_data['list'] 				= $list;
		$page_data['status_list'] 		= $this->virtual_account_model->status_list;
		$page_data['investor_list'] 	= $this->virtual_account_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/passbook_list',$page_data);
		$this->load->view('admin/_footer');
	}

	public function save_status() {
		$post = $this->input->post(NULL, TRUE);
		$msg = "OK";
		$success = true;
		$this->load->library('output/json_output');

		if(isset($post['log_id']) && isset($post['status']) && isset($post['target_id'])) {

			$this->load->model('log/log_legaldoc_export_model');
			$this->load->model('log/log_legaldoc_status_model');

			$existed = $this->log_legaldoc_status_model->order_by('id','DESC')->limit(1)->get_by([
				'target_id'=> $post['target_id']
			]);
			if(isset($existed) && $existed->status == $post['status']) {
				$success = false;
				$msg = "目標狀態相同";
			}else {
				if ($post['log_id'] != "") {
					$this->log_legaldoc_export_model->update_by(
						[
							'id' => $post['log_id']
						],
						[
							'status' => $post['status'],
						]
					);
				} else {
					$this->log_legaldoc_export_model->insert([
						'admin_id' => $this->login_info->id,
						'target_id' => $post['target_id'],
						'limit_date' => '0000-00-00',
						'delay_days' => '0',
						'done_task' => '{}',
						'send_date' => '0000-00-00',
						'status' => $post['status'],
						'investors' => '[]'
					]);
				}
				if($post['status']<10) {
					$this->investment_model->update_by([
						'target_id'=>$post['target_id'],
						'status'=>3,
					],
						['legal_collection_at'=>'0000-00-00 00:00:00']
					);
				}
				$this->log_legaldoc_status_model->insert([
					'admin_id' => $this->login_info->id,
					'target_id' => $post['target_id'],
					'status' => $post['status']
				]);
			}
		}
		$this->json_output->setStatusCode(200)->setResponse(['success'=> $success, 'msg' => $msg])->send();
	}

	public function legal_doc_status() {
		$post = $this->input->post(NULL, TRUE);
		$this->load->library('output/json_output');

		if(isset($post['tasksLogId'])) {
			$httpClient = HttpClient::create();
			$response = $httpClient->request('GET', ENV_ANUBIS_REQUEST_URL . 'payment_order?tasksLogId=' . $post['tasksLogId'], [
				'headers' => [
					'timeout' => 2.5
				]
			]);

			try {
				$statusCode = $response->getStatusCode();
			} catch (Exception $e) {
				$statusCode = -1;
			} finally {
				if ($statusCode == 200) {
					$statusDescription = 'OK!';
					$data = $response->toArray();
					$this->json_output->setStatusCode(200)->setResponse($data)->send();
				} else {
					$data = ['status' => $statusCode, 'response' => 'failed'];
					$statusDescription = '無法連線到法催子系統';
				}
				$data['description'] = $statusDescription;
				$this->json_output->setStatusCode($statusCode)->setResponse($data)->send();
			}
		}else{
			$this->json_output->setStatusCode(400)->setResponse(['status' => 400, 'response' => 'The parameter is invalid.'])->send();
		}
	}

	public function legal_doc()
	{
		$page_data = ['type' => 'list'];
		$dateOfToday = get_entering_date();
		$input = $this->input->get(NULL, TRUE);
		$post = json_decode($this->input->raw_input_stream, true);
		$where = ['status' => '1', 'limit_date <=' => $dateOfToday];
		$target_where = [];
		$list = [];
		$this->load->model('log/log_legaldoc_export_model');
		$this->load->model('log/log_legaldoc_status_model');
		$this->load->model('loan/transfer_model');
        $this->load->model('loan/investment_model');
        $this->load->library('Notification_lib');
		$this->load->library('Subloan_lib');

		if (empty($post)) {
			if (isset($input['tsearch']) && $input['tsearch'] != '') {
				$tsearch = $input['tsearch'];
				if (preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $tsearch)) {
					$name = $this->user_model->get_many_by(array(
						'name like ' => '%' . $tsearch . '%',
						'status' => 1
					));
					if ($name) {
						foreach ($name as $k => $v) {
							$target_where['user_id'][] = $v->id;
						}
					}
				} else {
					if (preg_match_all('/[A-Za-z]/', $tsearch) == 1) {
						$id_number = $this->user_model->get_many_by(array(
							'id_number  like' => '%' . $tsearch . '%',
							'status' => 1
						));
						if ($id_number) {
							foreach ($id_number as $k => $v) {
								$target_where['user_id'][] = $v->id;
							}
						}
					} elseif (preg_match_all('/\D/', $tsearch) == 0) {
						$target_where['user_id'] = $tsearch;
					} else {
						$target_where['target_no like'] = '%' . $tsearch . '%';
					}
				}
			}

			if (isset($input['inquiry'])) {
				$start = isset($input['sdate']) && $input['sdate'] != '' ? $input['sdate'] : false;
				$end = isset($input['edate']) && $input['edate'] != '' ? $input['edate']+30 : ($start !== false ? $start+30: 0);

				if($end) {
					$target_where['delay_days > '] = $start;
					$target_where['delay_days <= '] = $end;
				}
				$result = $this->transaction_model->getDelayedTargetInfoList($where, $target_where);

				foreach ($result as $v) {
					if (!isset($list[$v->target_id])) {
						$list[$v->target_id] = $v;
						$logs = $this->log_legaldoc_export_model->order_by('id', 'ASC')->get_many_by([
							'target_id'=>$v->target_id
						]);
						$statusLogs = $this->log_legaldoc_status_model->order_by('id', 'ASC')->get_many_by([
							'target_id'=>$v->target_id
						]);
						if(count($logs)) {
							foreach($logs as $k => $log) {
								$logs[$k]->done_task = json_decode($logs[$k]->done_task);
								$logs[$k]->investors = json_decode($logs[$k]->investors);
							}
							$allLogs = array_merge(json_decode(json_encode($logs), true), json_decode(json_encode($statusLogs), true));
							foreach($allLogs as $k => $log) {
								$admin = $this->admin_model->get_by([
									'id' => $allLogs[$k]["admin_id"],
								]);
								$allLogs[$k]["admin"] = $admin->name;
							}
							usort($allLogs, function($a, $b) {
								return $a['created_at'] <=> $b['created_at'];
							});
							$list[$v->target_id]->logs = json_decode(json_encode($allLogs));
						}else{
							$list[$v->target_id]->logs = [];
						}
					}
					if (!isset($list[$v->target_id]->investor_list))
						$list[$v->target_id]->investor_list = [];
					$list[$v->target_id]->investor_list[] = $v->investor_userid;
				}
			}

			$product_list = $this->config->item('product_list');
			$sub_product_list = $this->config->item('sub_product_list');
			$status_list = $this->target_model->status_list;
			$delay_list = $this->target_model->delay_list;

			$page_data['product_list'] = $product_list;
			$page_data['sub_product_list'] = $sub_product_list;
			$page_data['list'] = $list;
			$page_data['process_status'] = $this->process_status;
			$page_data['task_list'] = $this->task_list;
			$page_data['name_list'] = $this->admin_model->get_name_list();

			$this->load->view('admin/_header');
			$this->load->view('admin/_title', $this->menu);
			$this->load->view('admin/legal_doc', $page_data);
			$this->load->view('admin/_footer');

		}else{
			if(isset($post['data'])) {
				$this->load->library('output/json_output');
				$insertedIdList = [];
				foreach($post['data'] as $value) {
					$target 	= $this->target_model->get($value['targetId']);
					if(!isset($target))
						continue;

					$this->investment_model->update_by([
						'user_id'=>$value['investorUserIds'],
						'target_id'=>$value['targetId'],
						'status'=>3,
					],
						['legal_collection_at'=>$value['sendDate']]
					);
					$insertedIdList[] = $this->log_legaldoc_export_model->insert([
						'admin_id'=> $this->login_info->id,
						'target_id'=> $value['targetId'],
						'limit_date'=> $value['limitDate'],
						'delay_days'=> $value['delayDays'],
						'done_task'=> json_encode($value['doneTask']),
						'send_date'=> $value['sendDate'],
						'status' => $value['status'],
						'investors'=> json_encode($value['investorUserIds'])
					]);

					// 取消案件產轉申請
					$subloan = $this->subloan_lib->get_subloan($target);
					if (isset($subloan) && $subloan) {
					    if(in_array($subloan->status,array(0,1,2))) {
					        $this->subloan_lib->cancel_subloan($subloan);
					    }
					}

					// 取消投資人債轉
					$this->load->library('transfer_lib');
					$investment = $this->investment_model->get_many_by([
						'user_id'=>$value['investorUserIds'],
						'target_id'=>$value['targetId'],
						'transfer_status'=>1,
					]);
					if(isset($investment) && count($investment)) {
						$investmentList = array_column(json_decode(json_encode($investment), true), 'id');

						// 0:待出借 1:待放款
						$transfer_list = $this->transfer_model->get_many_by([
							'investment_id' => $investmentList,
							'status'     => [0,1]
						]);
						foreach($transfer_list as $value){
							if($value->combination!=0){
								$transfer = $this->transfer_model->get_many_by(['combination' => $value->combination]);
								$this->transfer_lib->cancel_combination_transfer($transfer);
							}else{
								$this->transfer_lib->cancel_transfer($value);
							}
                            $transfer_investment = $this->investment_model->get($value->investment_id);
                            $this->notification_lib->legal_collection_cancel_transfer($transfer_investment->user_id,$target->target_no,$target->user_id);

						}
					}
				}

				array_walk($post['data'], function (&$item, $key) {
					$item = array_merge($item, $item['doneTask']);
					$item['adminId'] = $this->login_info->id;
				});
				$httpClient = HttpClient::create();
				$response = $httpClient->request('POST', ENV_ANUBIS_REQUEST_URL.'payment_order' , [
					'headers' => [
						'Content-Type' => 'application/json',
						'timeout' => 2.5
					],
					'body' => json_encode(['payload' => $post['data']])
				]);

				try {
					$statusCode = $response->getStatusCode();
				} catch (Exception $e) {
					$statusCode = -1;
				} finally {
					if ($statusCode == 200) {
						$statusDescription = 'OK!';
						$data = $response->toArray();

						if(isset($data['response']['tasksLogId'])) {
							$this->log_legaldoc_export_model->update_by(
								['id' => $insertedIdList],
								['task_log_id' => $data['response']['tasksLogId']]
							);
						}

					} else {
						$data = ['status' => $statusCode, 'response' => 'failed'];
						$statusDescription = '無法連線到法催子系統';
					}
					$data['description'] = $statusDescription;
					$this->json_output->setStatusCode($statusCode)->setResponse($data)->send();
				}

			}
		}
	}
}
