<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use Symfony\Component\HttpClient\HttpClient;

use function PHPSTORM_META\type;

class PostLoan extends MY_Admin_Controller {

	protected $edit_method = array('legal_doc');
	private $task_list = array(
		'legalConfirmLetter' => '存證信函',
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
                    else
                    {
                        $target_where['user_id'] = 0;
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
			$page_data['process_status'] = $this->log_legaldoc_status_model->process_status;
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

    // 畫列表頁
    public function deduct()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/deduct.php');
        $this->load->view('admin/_footer');
    }

    // 取得法催扣繳列表
    public function get_deduct_list()
    {
        $this->load->model('user/deduct_model');

        // 取得搜尋條件
        $get = $this->input->get(NULL, TRUE);
        $data = $this->deduct_model->get_deduct_list(
            $get['user_id'] ?? 0,       // 投資人ID
            $get['created_at_s'] ?? '', // 代支日期 開始
            $get['created_at_e'] ?? ''  // 代支日期 結束
        );

        $status_list = $this->deduct_model->status_list;
        array_walk($data, function (&$value, $key) use (&$data, $status_list) {
            $data[$key]['status'] = [
                'code' => (int) $data[$key]['status'],
                'name' => $status_list[$data[$key]['status']] ?? '',
            ];

            switch ($data[$key]['status']['code'])
            {
                case DEDUCT_STATUS_CONFIRM: // 已付
                    $data[$key]['status']['updated_at'] = $data[$key]['updated_at'];
                    $data[$key]['status']['updated_admin'] = $data[$key]['updated_admin'];
                    break;
                case DEDUCT_STATUS_CANCEL: // 註銷
                    $data[$key]['status']['updated_at'] = $data[$key]['updated_at'];
                    $data[$key]['status']['cancel_reason'] = $data[$key]['cancel_reason'];
                    $data[$key]['status']['updated_admin'] = $data[$key]['updated_admin'];
                    break;
            }

            unset($data[$key]['updated_at']);
            unset($data[$key]['cancel_reason']);
        }, $data);

        echo json_encode($data);
    }

    // 虛擬帳號狀態查詢
    public function virtual_account_status()
    {
        $this->load->view('admin/virtual_account_status.php',$data = [
          'menu'      => $this->menu,
          'use_vuejs' => TRUE,
          'scripts'   => [
              '/assets/admin/js/postloan/virtual_account_status.js'
          ]
        ]);
    }

    // 協議清償表狀態查詢
    public function repayment_agreement()
    {
        $this->load->view('admin/repayment_agreement.php',$data = [
          'menu'      => $this->menu,
          'use_vuejs' => TRUE,
          'scripts'   => [
              '/assets/admin/js/postloan/repayment_agreement.js'
          ]
        ]);
    }

    public function get_virtual_account_status()
    {
        $httpClient = HttpClient::create();
        $data = $httpClient->request(
            'GET',
            getenv('ENV_POST_LOAN_HOST') . '/virtual_account_status',
            [
                'query' => $this->input->get()
            ],
            [
                'headers' => ['timeout' => 2.5]
            ]
        )->getContent();
        echo $data;
        die();
    }

    public function post_virtual_account_status()
    {
        $post = json_decode($this->input->raw_input_stream, TRUE);
        $httpClient = HttpClient::create();
        $data = $httpClient->request(
            'PUT',
            getenv('ENV_POST_LOAN_HOST') . '/virtual_account_status',
            [
                'query' => [
                    'user_id_int' => $post['user_id_int'],
                    'admin_id_int' => $this->login_info->id
                ],
                'json' => [
                    'status_int' => $post['status_int']
                ]
            ],
            [
                'headers' => ['timeout' => 2.5],
            ]
        )->getContent();
        echo $data;
        die();
    }

    public function get_repayment_agreement()
    {
        $httpClient = HttpClient::create();
        $data = $httpClient->request(
            'GET',
            getenv('ENV_POST_LOAN_HOST') . '/repayment_agreement',
            [
                'query' => $this->input->get()
            ],
            [
                'headers' => ['timeout' => 2.5]
            ]
        )->getContent();
        echo $data;
        die();
    }

    public function repayment_agreement_sheet()
    {
        // get file from /repayment_agreement/excel
        $httpClient = HttpClient::create();
        $res = $httpClient->request(
            'GET',
            getenv('ENV_POST_LOAN_HOST') . '/repayment_agreement/excel',
            [
                'query' => $this->input->get()
            ],
            [
                'headers' => ['timeout' => 2.5]
            ]
        );
        $des = $res->getHeaders()['content-disposition'][0];
        $data = $res->getContent();
        // create download file by data
        header('content-type: application/octet-stream');
        header('content-disposition:' . $des);
        header('content-length: ' . strlen($data));
        echo $data;
        die();
    }

    public function repayment_agreement_confirm()
    {
        $get = $this->input->get();
        $get['admin_id_int'] = $this->login_info->id;
        $httpClient = HttpClient::create();
        $data = $httpClient->request(
            'POST',
            getenv('ENV_POST_LOAN_HOST') . '/repayment_agreement/confirm',
            [
                'query' => $get,
            ],
            [
                'headers' => ['timeout' => 2.5],
            ]
        )->getContent();
        echo $data;
        die();
    }

    // 新增法催扣繳紀錄
    public function add_deduct_info()
    {

        try
        {
            $this->load->model('user/deduct_model');

            $post = json_decode($this->input->raw_input_stream, TRUE);

            if (empty($post['user_id']))
            {
                throw new Exception('新增失敗，投資人ID必填');
            }

            if (empty($post['amount']))
            {
                throw new Exception('新增失敗，金額必填');
            }

            if (empty($post['reason']))
            {
                throw new Exception('新增失敗，事由必填');
            }

            $this->load->model('virtual_account_model');

            $virtual_account = $this->virtual_account_model->get_investor_account_by_user_id($post['user_id']);
            if (empty($virtual_account))
            {
                throw new Exception('新增失敗，查無投資人虛擬帳戶');
            }

            $this->load->model('transaction_model');
            $this->transaction_model->trans_begin();
            $this->deduct_model->trans_begin();

            $rollback = function () {
                $this->deduct_model->trans_rollback();
                $this->transaction_model->trans_rollback();
            };

            // 新增「內帳交易」紀錄
            $data = [
                'source' => SOURCE_AR_LAW_FEE,
                'entering_date' => date('Y-m-d'),
                'user_from' => $post['user_id'],
                'bank_account_from' => $virtual_account,
                'amount' => $post['amount'],
                'bank_account_to' => PLATFORM_VIRTUAL_ACCOUNT,
                'limit_date' => NULL,
            ];
            $data['created_at'] = $data['updated_at'] = time();
            $data['created_ip'] = $data['updated_ip'] = get_ip();
            $transaction_id = $this->transaction_model->insert_get_id($data);
            if ($transaction_id === FALSE || $this->transaction_model->trans_status() === FALSE)
            {
                $rollback();
                throw new Exception('新增內帳失敗，請洽工程師');
            }

            // 新增「法催扣繳」紀錄
            $this->deduct_model->insert([
                'transaction_id' => $transaction_id,
                'user_id' => $post['user_id'],
                'amount' => (int) $post['amount'],
                'reason' => $post['reason'],
                'created_admin_id' => $this->login_info->id,
                'updated_admin_id' => $this->login_info->id
            ]);
            if ($this->deduct_model->trans_status() === FALSE)
            {
                $rollback();
                throw new Exception('新增扣繳失敗，請洽工程師');
            }

            $this->deduct_model->trans_commit();
            $this->transaction_model->trans_commit();
        }
        catch (Exception $e)
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => $e->getMessage()
            ]);
        }
    }

    // 更新法催扣繳資料
    public function update_deduct_info()
    {
        $this->load->model('user/deduct_model');

        $post = json_decode($this->input->raw_input_stream, TRUE);

        if (empty($post['id']))
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => '更新失敗，查無此法催扣繳紀錄'
            ]);
            return;
        }

        switch ($post['action'])
        {
            case DEDUCT_STATUS_CANCEL: // 註銷
                try
                {
                    if (empty($post['cancel_reason']))
                    {
                        throw new Exception('更新失敗，註銷原因必填');
                    }

                    $this->load->model('transaction_model');
                    $this->transaction_model->trans_begin();
                    $this->deduct_model->trans_begin();
                    $rollback = function () {
                        $this->deduct_model->trans_rollback();
                        $this->transaction_model->trans_rollback();
                    };

                    // 更新「內帳交易」紀錄
                    $transaction_id = $this->deduct_model->get_transaction_id_by_deduct_id($post['id']);
                    $update_result = $this->transaction_model->update($transaction_id, [
                        'status' => TRANSACTION_STATUS_DELETED
                    ]);
                    if ($update_result === FALSE || $this->transaction_model->trans_status() === FALSE)
                    {
                        $rollback();
                        throw new Exception('更新內帳失敗，請洽系統工程師');
                    }

                    // 更新「法催扣繳」紀錄
                    $this->deduct_model->update($post['id'], [
                        'status' => DEDUCT_STATUS_CANCEL,
                        'cancel_reason' => $post['cancel_reason'],
                        'updated_admin_id' => $this->login_info->id
                    ]);
                    if ($this->deduct_model->trans_status() === FALSE)
                    {
                        $rollback();
                        throw new Exception('註銷失敗，請洽系統工程師');
                    }

                    $this->deduct_model->trans_commit();
                    $this->transaction_model->trans_commit();

                    echo json_encode([
                        'result' => 'SUCCESS',
                        'msg' => '註銷成功'
                    ]);
                }
                catch (Exception $e)
                {
                    echo json_encode([
                        'result' => 'ERROR',
                        'msg' => $e->getMessage()
                    ]);
                }

                break;
            case DEDUCT_STATUS_CONFIRM: // 扣繳
                try
                {

                    $this->load->model('transaction_model');
                    $this->deduct_model->trans_begin();
                    $this->transaction_model->trans_begin();
                    $rollback = function () {
                        $this->transaction_model->trans_rollback();
                        $this->deduct_model->trans_rollback();
                    };

                    $this->load->model('user/virtual_account_model');
                    $user_id = $this->deduct_model->get_user_id_by_deduct_id($post['id']);
                    $virtual_account = $this->virtual_account_model->setVirtualAccount(
                        $user_id,
                        INVESTOR,
                        TRANSACTION_STATUS_TO_BE_PAID,
                        TRANSACTION_STATUS_PAID_OFF,
                        (CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE)
                    );
                    if (empty($virtual_account->virtual_account))
                    {
                        $rollback();
                        throw new Exception('更新失敗，查無此虛擬帳號');
                    }

                    $response_amount = $this->deduct_model->get_deduct_and_virtual_amount($post['id'], $virtual_account->virtual_account);
                    if ( ! isset($response_amount['deduct_amount']))
                    {
                        $rollback();
                        throw new Exception('更新失敗，查無此法催扣繳紀錄');
                    }

                    if ( ! isset($response_amount['account_amount']) || $response_amount['deduct_amount'] > $response_amount['account_amount'])
                    {
                        $rollback();
                        throw new Exception('更新失敗，虛擬帳戶餘額不足');
                    }

                    // 更新「內帳交易」紀錄
                    $update_result = $this->transaction_model->update($response_amount['transaction_id'], [
                        'status' => TRANSACTION_STATUS_PAID_OFF
                    ]);
                    if ($update_result === FALSE || $this->transaction_model->trans_status() === FALSE)
                    {
                        $rollback();
                        throw new Exception('更新內帳失敗，請洽系統工程師');
                    }

                    $data = [
                        'source' => SOURCE_LAW_FEE,
                        'entering_date' => date('Y-m-d'),
                        'user_from' => $response_amount['user_id'],
                        'bank_account_from' => $virtual_account->virtual_account,
                        'amount' => $response_amount['deduct_amount'],
                        'bank_account_to' => PLATFORM_VIRTUAL_ACCOUNT,
                        'limit_date' => NULL,
                        'status' => TRANSACTION_STATUS_PAID_OFF
                    ];
                    $data['created_at'] = $data['updated_at'] = time();
                    $data['created_ip'] = $data['updated_ip'] = get_ip();
                    $transaction_id = $this->transaction_model->insert_get_id($data);
                    if ($transaction_id === FALSE || $this->transaction_model->trans_status() === FALSE)
                    {
                        $rollback();
                        throw new Exception('新增內帳失敗，請洽工程師');
                    }

                    $this->load->library('passbook_lib');
                    if ($this->passbook_lib->enter_account($transaction_id) === FALSE)
                    {
                        $rollback();
                        throw new Exception('扣繳失敗，請洽工程師');
                    }

                    // 更新「法催扣繳」紀錄
                    $this->deduct_model->update($post['id'], [
                        'status' => DEDUCT_STATUS_CONFIRM,
                        'transaction_id' => $transaction_id,
                        'updated_admin_id' => $this->login_info->id
                    ]);
                    if ($this->deduct_model->trans_status() === FALSE)
                    {
                        $rollback();
                        throw new Exception('扣繳失敗，請洽系統工程師');
                    }

                    $this->virtual_account_model->setVirtualAccount(
                        $user_id,
                        INVESTOR,
                        TRANSACTION_STATUS_PAID_OFF,
                        TRANSACTION_STATUS_TO_BE_PAID,
                        (CATHAY_VIRTUAL_CODE.INVESTOR_VIRTUAL_CODE)
                    );

                    $this->transaction_model->trans_commit();
                    $this->deduct_model->trans_commit();

                    echo json_encode([
                        'result' => 'SUCCESS',
                        'msg' => '扣繳成功'
                    ]);
                }
                catch (Exception $e)
                {
                    echo json_encode([
                        'result' => 'ERROR',
                        'msg' => $e->getMessage()
                    ]);
                }

                break;
        }
    }

    /**
     * 取得投資人姓名、虛擬帳號餘額
     * @param int $user_id : 使用者ID
     */
    public function get_deduct_user_info(int $user_id = 0)
    {
        $this->load->model('user/deduct_model');

        if (empty($user_id))
        {
            $user_id = $this->input->get('user_id');
        }

        if (empty($user_id))
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => '查無此投資人資訊'
            ]);
        }

        $data = $this->deduct_model->get_deduct_user_info($user_id);
        if (empty($data))
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => '查無此投資人資訊'
            ]);
        }
        else
        {
            $data['account_amount'] = $data['account_amount'] ?? 0;

            echo json_encode([
                'result' => 'SUCCESS',
                'msg' => '',
                'data' => [
                    'account_amount' => $data['account_amount'],
                    'account_amount_formatted' => number_format($data['account_amount']),
                    'user_name' => $data['user_name'] ?? ''
                ]
            ]);
        }
    }

    // 取得法催扣繳資料
    public function get_deduct_info()
    {
        $this->load->model('user/deduct_model');

        $id = $this->input->get('id');
        if (empty($id))
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => '查無此扣繳資訊'
            ]);
            return;
        }

        $data = $this->deduct_model->get_deduct_info($id);

        if ( ! empty($data['deduct_amount']))
        {
            $data['deduct_amount'] = (int) $data['deduct_amount'];
            $data['deduct_amount_formatted'] = number_format($data['deduct_amount']);
        }
        else
        {
            $data['deduct_amount'] = $data['deduct_amount_formatted'] = 0;
        }

        if ( ! empty($data['account_amount']))
        {
            $data['account_amount'] = (int) $data['account_amount'];
            $data['account_amount_formatted'] = number_format($data['account_amount']);
        }
        else
        {
            $data['account_amount'] = $data['account_amount_formatted'] = 0;
        }

        if (empty($data))
        {
            echo json_encode([
                'result' => 'ERROR',
                'msg' => '查無此扣繳資訊'
            ]);
            return;
        }

        echo json_encode([
            'result' => 'SUCCESS',
            'data' => $data,
        ]);
    }
}
