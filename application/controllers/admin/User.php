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

				$info 			= $this->user_model->get($id);
				if($info){
					$this->load->library('certification_lib');
					$page_data['data'] 					= $info;
					$page_data['meta'] 					= $meta_data;
					$page_data['school_system'] 		= $this->config->item('school_system');
					$page_data['certification'] 		= $this->certification_lib->get_last_status($info->id,0,$info->company_status);
					$page_data['certification_investor']= $this->certification_lib->get_last_status($info->id,1,$info->company_status);
					$page_data['credit_list'] 			= $this->credit_model->get_many_by(['user_id' => $id, 'status' => 1]);
					$page_data['product_list']			= $this->config->item('product_list');
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
			$meta 				= $this->user_meta_model->get_many_by(array('user_id'=>$id));
			if($meta){
				foreach($meta as $key => $value){
					$meta_data[$value->meta_key] = $value->meta_value;
				}
			}
			$bank_account 		= $this->user_bankaccount_model->get_many_by(array(
				'user_id'	=> $id,
				'status'	=> 1,
				'verify'	=> 1,
			));
			$credit_list		= $this->credit_model->get_many_by(array('user_id'=>$id));
			$info = $this->user_model->get($id);
			if($info){
                $this->load->library('certification_lib');
				$page_data['data'] 					= $info;
				$page_data['meta'] 					= $meta_data;
				$page_data['school_system'] 		= $this->config->item('school_system');
                $page_data['certification'] 		= $this->certification_lib->get_last_status($info->id,0,$info->company_status);
                $page_data['certification_investor']= $this->certification_lib->get_last_status($info->id,1,$info->company_status);
				$page_data['certification_list'] 	= $certification_list;
				$page_data['credit_list'] 			= $credit_list;
				$page_data['product_list']			= $this->config->item('product_list');
				$page_data['bank_account'] 			= $bank_account;
				$page_data['bank_account_investor'] = $this->user_bankaccount_model->investor_list;
				$page_data['bank_account_verify'] 	= $this->user_bankaccount_model->verify_list;
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

		$userId = isset($input["id"]) ? intval($input["id"]) : 0;

		$this->load->library('output/json_output');
		if ($userId <= 0) {
			return $this->json_output->setStatusCode(400)->setErrorCode(ArgumentError)->send();
		}

		$loginUserLogs = $this->log_userlogin_model->get_many_by(['user_id' => $userId]);
		$deviceIds = [];
		foreach ($loginUserLogs as $login) {
			$content = json_decode($login->client);
			if (isset($content->device_id)) {
				$deviceIds[] = $content->device_id;
			}
		}
		$usersWithSameDeviceId = $this->log_userlogin_model->get_same_device_id_users($userId, $deviceIds);

		$timeBefore = 1564102800;
		$usersWithSameIp = $this->log_userlogin_model->get_same_ip_users($userId, $timeBefore);

		$usersWithSameEmergencyContact = $this->user_meta_model->get_users_with_same_emergency_contact($userId);

		$emergencyContact = $this->user_meta_model->get_emergency_contact_who_is_member($userId);

		$this->load->model('user/user_certification_model');
		$certificationRequests = $this->user_certification_model->get_many_by([
			'user_id' => $userId,
			'certification_id' => [1,3,6],
		]);

		$addresses = [];
		$bankAccounts = [];
		$idCardNumbers = [];
		$emails = [];
		foreach ($certificationRequests as $certificationRequest) {
			$content = json_decode($certificationRequest->content);
			$certificationId = $certificationRequest->certification_id;
			if ($certificationId == 1) {
				$idCardNumbers[] = $content->id_number;
				$addresses[] = $content->address;
			} elseif ($certificationId == 3) {
				$bankAccounts[] = $content->bank_account;
			} elseif ($certificationId == 6) {
				$emails[] = $content->email;
			}
		}

		$usersWithSameBankAccount = $this->user_certification_model->get_users_with_same_value($userId, 'bank_account', $bankAccounts);
		$usersWithSameIdNumber = $this->user_certification_model->get_users_with_same_value($userId, 'id_number', $idCardNumbers);

		$potentialPhoneNumbers = [];
		foreach ($emails as $email) {
			preg_match('/[0|886][0-9]{8,9}/', $email, $matches);
			foreach ($matches as $phone) {
				$potentialPhoneNumbers[] = $phone;
			}
		}
		$usersWithSamePhoneNumber = [];
		if ($potentialPhoneNumbers) {
			$usersWithSamePhoneNumber = $this->user_model->get_many_by(['phone' => $potentialPhoneNumbers]);
		}

		$usersWithSameAddress = $this->user_certification_model->get_users_with_same_value($userId, 'address', $addresses);

		$currentUser = $this->user_model->get($userId);
		$introducer =$this->user_model->get_by(['promote_code' => $currentUser->promote_code]);

		$data = new stdClass();
		$data->same_device_id = $usersWithSameDeviceId;
		$data->same_ip = $usersWithSameIp;
		$data->samp_contact = $usersWithSameEmergencyContact;
		$data->emergency_contact = $emergencyContact;
		$data->same_bank_account = $usersWithSameBankAccount;
		$data->same_id_number = $usersWithSameIdNumber;
		$data->same_phone_number = $usersWithSamePhoneNumber;
		$data->same_address = $usersWithSameAddress;
		$data->introducer = $introducer;

		$this->load->library('output/user/related_user_output', ["data" => $data]);

		$relatedUsers = $this->related_user_output->toMany();
		if (!$relatedUsers) {
			$this->json_output->setStatusCode(204)->send();
		}
		$this->json_output->setStatusCode(200)->setResponse(["related_users" => $relatedUsers])->send();
	}
}
?>
