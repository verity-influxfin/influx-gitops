<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class User extends MY_Admin_Controller {

	protected $edit_method = array('edit');
	public $certification;

	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_meta_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->model('loan/credit_model');
		$this->load->model('log/log_userlogin_model');
		$this->load->model('log/log_blockedlist_model');
		$this->certification = $this->config->item('certifications');
 	}

	public function index(){

		$page_data 			= array('type'=>'list');
		$input 				= $this->input->get(NULL, TRUE);
		$where				= array();
		$list				= array();
		$fields 			= ['id','name','phone'];

		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				if($field=='phone' || $field=='name'){
					$where[$field.' like'] = '%'.$input[$field].'%';
				}else{
					$where[$field] = $input[$field];
				}
			}
		}

		if(!empty($where)){
			$list 	= $this->user_model->get_many_by($where);
		}

		if ($this->input->is_ajax_request()) {
			$this->load->library('output/json_output');
			$this->load->library('output/user/user_output', ["data" => $list]);

			if (!$list) {
				$this->json_output->setStatusCode(204)->send();
			}
			$this->json_output->setStatusCode(200)->setResponse(["users" => $this->user_output->toMany()])->send();
		} else {
			$page_data['list'] = $list;
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/users_list',$page_data);
			$this->load->view('admin/_footer');
		}
	}

	public function edit(){
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);

		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$certification_list = array();
				if($this->certification){
					foreach($this->certification as $key => $value){
						$certification_list[$value['alias']] = $value['name'];
					}
				}

				$meta_data 			= [];
				$meta 				= $this->user_meta_model->get_many_by([
					'user_id'	=> $id,
					'meta_key'	=> ['fb_id']
				]);
				if($meta){
					foreach($meta as $key => $value){
						$meta_data[$value->meta_key] = $value->meta_value;
					}
				}
				$bank_account = $this->user_bankaccount_model->get_many_by([
					'user_id'	=> $id,
					'status'	=> 1,
					//'verify'	=> 1,
				]);
				$credit_list		= $this->credit_model->get_many_by(array(
					'user_id' => $id,
					'status !=' => 2,
				));

                $this->load->model('loan/credit_sheet_model');
                $new_credit_list = $this->credit_sheet_model->get_credit_list($id);

				$info 			= $this->user_model->get($id);
				if($info){
					$this->load->library('certification_lib');
					$page_data['data'] 					= $info;
					$page_data['meta'] 					= $meta_data;
					$page_data['school_system'] 		= $this->config->item('school_system');
					$page_data['certification'] 		= $this->certification_lib->get_last_status($info->id,BORROWER,$info->company_status);
					$page_data['certification_investor']= $this->certification_lib->get_last_status($info->id,INVESTOR,$info->company_status);
					$page_data['credit_list'] 			= $credit_list;
					$page_data['new_credit_list']       = $new_credit_list;
					$page_data['product_list']			= $this->config->item('product_list');
					$page_data['bank_account'] 			= $bank_account;
					$page_data['certification_list'] 	= $certification_list;
					$page_data['sub_product_list'] = $this->config->item('sub_product_list');
					$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
					$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
					//新增設備ID ++
					$login_log_invest 	= $this->log_userlogin_model->order_by("created_at", "desc")->get_by([
						'user_id' 		=> $info->id,
						'investor' 		=> 1
					]);
					$device_id_invest   = null;
					if (isset($login_log_invest->client)) {
						$device_id_invest = json_decode($login_log_invest->client);
					}

					if ($device_id_invest) {
						$page_data['device_id_invest'] = $device_id_invest->device_id;
					}
					$login_log_borrow	= $this->log_userlogin_model->order_by("created_at", "desc")->get_by([
						'user_id' 		=> $info->id,
						'investor' 		=> 0
					]);
					$device_id_borrow = null;
					if (isset($login_log_borrow->client)) {
						$device_id_borrow = json_decode($login_log_borrow->client);
					}

					if ($device_id_borrow) {
					$page_data['device_id_borrow'] = $device_id_borrow->device_id;
					}
					//新增設備ID --
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/users_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('user/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('user/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['name', 'nickname', 'address', 'email', 'city', 'status', 'block_status','area'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$rs = $this->user_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('user/index'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('user/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('user/index'));
			}
		}
	}



	public function display(){

		$page_data 	= array('type'=>'edit');
		$get 		= $this->input->get(NULL, TRUE);
		$id = isset($get['id'])?intval($get['id']):0;
		if($id){
			$certification_list = array();
			if($this->certification){
				foreach($this->certification as $key => $value){
					$certification_list[$value['alias']] = $value['name'];
				}
			}

			$meta_data 			= array();
				$meta 				= $this->user_meta_model->get_many_by([
					'user_id'	=> $id,
					'meta_key'	=> ['fb_id']
				]);
			if($meta){
				foreach($meta as $key => $value){
					$meta_data[$value->meta_key] = $value->meta_value;
				}
			}
			$bank_account 		= $this->user_bankaccount_model->get_many_by(array(
				'user_id'	=> $id,
				'status'	=> 1,
			));
			$credit_list		= $this->credit_model->get_many_by(array(
				'user_id' => $id,
				'status !=' => 2,
			));
			$info = $this->user_model->get($id);
			if($info){
                $this->load->model('loan/credit_sheet_model');
                $new_credit_list = $this->credit_sheet_model->get_credit_list($id);

                $this->load->library('certification_lib');
				$page_data['data'] 					= $info;
				$page_data['meta'] 					= $meta_data;
				$page_data['school_system'] 		= $this->config->item('school_system');
                $page_data['certification'] 		= $this->certification_lib->get_last_status($info->id,BORROWER,$info->company_status);
                $page_data['certification_investor']= $this->certification_lib->get_last_status($info->id,INVESTOR,$info->company_status);
				$page_data['certification_list'] 	= $certification_list;
				$page_data['credit_list'] 			= $credit_list;
				$page_data['new_credit_list']       = $new_credit_list;
				$page_data['product_list']			= $this->config->item('product_list');
				$page_data['sub_product_list'] = $this->config->item('sub_product_list');
				$page_data['bank_account'] 			= $bank_account;
				$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
				$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
				//新增設備ID ++
                $login_log_invest 	= $this->log_userlogin_model->order_by("created_at", "desc")->get_by([
                    'user_id' 		=> $info->id,
                    'investor' 		=> 1
                ]);
                $device_id_invest   = null;
                if (isset($login_log_invest->client)) {
                    $device_id_invest = json_decode($login_log_invest->client);
                }

                if ($device_id_invest) {
                    $page_data['device_id_invest'] = $device_id_invest->device_id;
                }
                $login_log_borrow	= $this->log_userlogin_model->order_by("created_at", "desc")->get_by([
                    'user_id' 		=> $info->id,
                    'investor' 		=> 0
                ]);
                $device_id_borrow = null;
                if (isset($login_log_borrow->client)) {
                    $device_id_borrow = json_decode($login_log_borrow->client);
                }

                if ($device_id_borrow) {
                $page_data['device_id_borrow'] = $device_id_borrow->device_id;
                }
				//新增設備ID --
				$this->load->view('admin/_header');
				$this->load->view('admin/users_edit',$page_data);
				$this->load->view('admin/_footer');
			}else{
				alert('ERROR , id is not exist',admin_url('user/index'));
			}
		}else{
			alert('ERROR , id is not exist',admin_url('user/index'));
		}
	}

    public function blocked_users() {
        $get 		= $this->input->get(NULL, TRUE);

        $page_data = [];
		$block_user = $this->log_blockedlist_model->getBlockedLogs();
		$this->load->library('output/log/block_output', ["data" => $block_user]);

        if ($block_user && !empty($block_user)) {
            $page_data['list'] = $this->block_output->toMany();
            $page_data['block_status_list'] = $this->user_model->block_status_list;
        }

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/users_block_list', $page_data);
        $this->load->view('admin/_footer');
    }

    public function sms_verify()
    {
        $this->load->model('user/user_model');
        $this->load->model('user/sms_verify_model');

        $user_id = $this->input->get('user_id');
        $phone = $this->input->get('phone');

        $verification_code = [];

        if ($user_id || $phone) {
            if ($user_id) {
                $user = $this->user_model->get_by('id', $user_id);

                if ($user) {
                    $verification_code = $this->sms_verify_model
                        ->order_by('expire_time', 'desc')
                        ->get_many_by([
                            'phone' => $user->phone,
                            'expire_time >=' => time()
                        ]);
                }
            } else {
                $verification_code = $this->sms_verify_model
                    ->order_by('expire_time', 'desc')
                    ->get_many_by([
                        'phone' => $phone,
                        'expire_time >=' => time()
                    ]);
            }
        }

        $page_data = ['verification_code' => $verification_code];

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/users_verify_code', $page_data);
        $this->load->view('admin/_footer');
    }

	public function block_users() {
		$input = $this->input->post(NULL, TRUE);

		if (!$this->input->is_ajax_request()) {
			alert('ERROR, 只接受Ajax', admin_url('user/blocked_users'));
		}

		$this->load->library('output/json_output');

		if (!is_array($input)) {
			$this->json_output->setStatusCode(400)->setErrorCode(ArgumentError)->send();
		}

		$status = isset($input['status']) ? $input['status'] : '';
		$userId = isset($input['id']) ? intval($input['id']) : 0;
		$reason = isset($input['reason']) ? strval($input['reason']) : '';
		if (!$status || $userId <= 0) {
			$this->json_output->setStatusCode(400)->setErrorCode(ArgumentError)->send();
		}

		try {
			$this->load->library('mapping/block/blockstatus', ["status" => $status]);
		} catch (OutOfBoundsException $e) {
			$this->json_output->setStatusCode(400)->setErrorCode(ArgumentError)->send();
		}

		$block = $this->log_blockedlist_model->get_by(['blocked_user_id' => $userId]);
		if ($block) {
			$this->log_blockedlist_model->update_by(
				['blocked_user_id' => $userId],
				[
					"admin_id" => $this->login_info->id,
					"status" => $this->blockstatus->getValueInDB(),
					"reason" => $reason,
				]
			);
		} else {
			$this->log_blockedlist_model->insert([
				'admin_id' => $this->login_info->id,
				'blocked_user_id' => $userId,
				'status' => $this->blockstatus->getValueInDB(),
				'reason' => $reason,
			]);
		}

		$success = $this->user_model->update(
			$userId,
			["block_status" => $this->blockstatus->getValueInDB()]
		);

		$this->load->model('log/log_userlogin_model');
		$info = $this->user_model->get($userId);
		$this->log_userlogin_model->insert([
			'account'	=> $info->phone,
			'investor'	=> 0,
			'user_id'	=> $info->id,
			'status'	=> 1
		]);

		$blockRecord = $this->log_blockedlist_model->findByUserId($userId);

		$this->load->library('output/log/block_output', ["data" => $blockRecord]);

		if ($success !== true) {
			$this->json_output->setStatusCode(500)->setErrorCode(InsertError)->send();
		}
		$this->json_output->setStatusCode(200)->setResponse(["block" => $this->block_output->toOne()])->send();
	}

    public function related_users()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('Anti_fraud_lib');
        $this->load->library('output/json_output');
        $relatedUserData = $this->anti_fraud_lib->related_users($input["id"]);

        $this->load->library('output/user/related_user_output', ["data" => $relatedUserData]);

        $relatedUsers = $this->related_user_output->toMany();

        if (!$relatedUsers) {
            $this->json_output->setStatusCode(204)->send();
        }
        $this->json_output->setStatusCode(200)->setResponse(["related_users" => $relatedUsers])->send();
    }

    public function judicialyuan()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('Anti_fraud_lib');
        $this->load->library('output/json_output');
        $judicialYuanData = $this->anti_fraud_lib->judicialyuan($input["id"]);

        $this->load->library('output/user/related_user_output', ["data" => $judicialYuanData]);

        $judicialYuan = $this->related_user_output->toMany();

        if (!$judicialYuan) {
            $this->json_output->setStatusCode(204)->send();
        }
        $this->json_output->setStatusCode(200)->setResponse($judicialYuan)->send();
    }

	public function migrateData()
	{
	    $input = $this->input->get(NULL, TRUE);
	    $startAt = isset($input["start_at"]) ? $input["start_at"] : 0;
	    $endAt = isset($input["end_at"]) ? $input["end_at"] : 0;
	    $host = getenv('ENV_MONGO_HOST');
	    $port = getenv('ENV_MONGO_PORT');
	    $username = getenv('ENV_MONGO_USERNAME');
	    $password = getenv('ENV_MONGO_PASSWORD');
	    $db = new MongoDB\Driver\Manager("mongodb://{$username}:{$password}@{$host}:{$port}");
	    $i = 0;
	    $whereQuery = [];
	    if ($startAt > 0) {
	        $whereQuery["created_at >="] = $startAt;
	    }
	    if ($endAt > 0) {
	        $whereQuery["created_at <="] = $endAt;
	    }

	    while ($loginLogs = $this->log_userlogin_model->limit(1000, $i*1000)->get_many_by($whereQuery)) {
	        $bulk = new MongoDB\Driver\BulkWrite();
	        foreach ($loginLogs as $loginLog) {
	                $log = json_decode(json_encode($loginLog), true);
	                $log["client"] = json_decode($log["client"]);
	                $log["id"] = intval($log["id"]);
	                $log["investor"] = intval($log["investor"]);
	                $log["user_id"] = intval($log["user_id"]);
	                $log["status"] = intval($log["status"]);
	                $log["created_at"] = intval($log["created_at"]);
	                $bulk->insert($log);
	        }
	        $writeConcern = new MongoDB\Driver\WriteConcern(0, 300);
	        $db->executeBulkWrite('influx_logs.user-login-logs', $bulk, $writeConcern);
	        $i++;
	    }
	}

	public function get_user_notification()
	{
		$input = $this->input->get(NULL, TRUE);
		$userId = $input['id'];
		$investor = $input['investor'];
		$this->load->model('user/user_notification_model');
		$notification_list = $this->user_notification_model->get_many_by([
			'user_id' => $userId,
			'status !=' => 0,
			'investor' => $investor
		]);
		$cell = [];
		if (!empty($notification_list)) {
			foreach ($notification_list as $key => $value) {
				$cell[] = [
					date('Y-m-d H:i:s', $value->created_at),
					$value->title,
					$value->content,
				];
			}
		}
		$this->load->library('Phpspreadsheet_lib');
		$sheetTItle = ['日期','標題','內容'];
		$contents[] = [
			'sheet' => '使用者通知信',
			'title' => $sheetTItle,
			'content' => $cell,
		];
		$file_name = date("YmdHis", time()) . ' user ' . $userId . ' app信件紀錄';
		$descri = '普匯inFlux 後台管理者 ' . $this->login_info->id . ' [ app信件紀錄 ]';
		$this->phpspreadsheet_lib->excel($file_name, $contents, 'app信件紀錄', '查核使用', $descri, $this->login_info->id, true);
	}
}
?>
