<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use Symfony\Component\HttpClient\HttpClient;

class Contact extends MY_Admin_Controller {
	
	protected $edit_method = array('edit','send_email');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_contact_model');
		$this->load->library('Notification_lib');
		$this->load->helper('admin');
 	}
	
	public function index(){
		$page_data 	= array('type'=>'list');
		$input 		= $this->input->get(NULL, TRUE);
		$where		= array('status'=>'0');
		$fields 	= ['status','user_id'];
		foreach ($fields as $field) {
			if (isset($input[$field])&&$input[$field]!='') {
				$where[$field] = $input[$field];
			}
		}

		$page_data['where'] 		= $where;
		$page_data['list'] 			= $this->user_contact_model->get_many_by($where);
		$page_data['name_list'] 	= $this->admin_model->get_name_list();
		$page_data['status_list'] 	= $this->user_contact_model->status_list;
		$page_data['investor_list'] = $this->user_contact_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/contacts_list',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function edit(){
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$get 		= $this->input->get(NULL, TRUE);
		
		if(empty($post)){
			$id = isset($get['id'])?intval($get['id']):0;
			if($id){
				$info = $this->user_contact_model->get_by('id', $id);
				if($info){
					$this->load->model('user/user_model');
					$page_data['user']			= $this->user_model->get($info->user_id);
					$page_data['data'] 			= $info;
					$page_data['name_list'] 	= $this->admin_model->get_name_list();
					$page_data['status_list'] 	= $this->user_contact_model->status_list;
					$page_data['investor_list'] = $this->user_contact_model->investor_list;
					$this->load->view('admin/_header');
					$this->load->view('admin/_title',$this->menu);
					$this->load->view('admin/contacts_edit',$page_data);
					$this->load->view('admin/_footer');
				}else{
					alert('ERROR , id is not exist',admin_url('contact/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('contact/index'));
			}
		}else{
			if(!empty($post['id'])){
				$fields = ['remark', 'status'];
				foreach ($fields as $field) {
					if (isset($post[$field])) {
						$data[$field] = $post[$field];
					}
				}
				$data['admin_id'] = $this->login_info->id;
				$rs = $this->user_contact_model->update($post['id'],$data);
				if($rs===true){
					alert('更新成功',admin_url('contact/index'));
				}else{
					alert('更新失敗，請洽工程師',admin_url('contact/index'));
				}
			}else{
				alert('ERROR , id is not exist',admin_url('contact/index'));
			}
		}
	}

	public function update_notificaion() {
		// TODO: 增加權限控管，若無權限或該筆紀錄非發送者擁有時需 reject
		$id = $this->input->input_stream('id');
		$action = $this->input->input_stream('action');
		// $user_id = $this->input->input_stream('user_id');

		$this->load->model('admin/role_model');
		$this->login_info = check_admin();

		try {
			// 目標狀態是未登入 或 欲審核推播的會員不是管理員時
			if(!isset($this->login_info) || (!$this->notification_lib->has_check_permission($this->login_info) &&
				($action == 1 || $action == 2))) {
				$statusCode = 401;
				$statusDescription = '你沒有權限做此操作。';
			}else {
				$httpClient = HttpClient::create();
				$response = $httpClient->request('PUT', ENV_NOTIFICATION_REQUEST_URL, [
					'body' => json_encode(['id' => $id, 'action' => $action, 'user_id' => $this->login_info->id]),
					'headers' => ['Content-Type' => 'application/json'],
				]);

				$statusCode = $response->getStatusCode();
				$statusDescription = '更新成功。';
			}
		} catch (Exception $e) {
			$statusCode = -1;
			$statusDescription = '無法連線到推播中心';
		} finally {
			return json_encode(['code' => $statusCode, 'description' => $statusDescription]);
		}
	}

	/** 寄送 Email 及 app 通知的頁面 (GET & POST)
	 *
	 * @return null
	 */
	public function send_email(){
		$page_data 	= array('type'=>'edit');
		$post 		= $this->input->post(NULL, TRUE);
		$this->load->library('Sendemail');
		$this->load->model('admin/role_model');
		$this->login_info = check_admin();
		$role_name 	= $this->role_model->get_list();

		if(empty($post)){
			// For GET Method
			$data = array('user_id' => $this->login_info->id);

			try {
				$notification_content = array('data' => array());

				$httpClient = HttpClient::create();
				$response = $httpClient->request('GET', ENV_NOTIFICATION_REQUEST_URL, [
					'body' => json_encode($data),
					'headers' => ['Content-Type' => 'application/json'],
					'timeout' => 2.5
				]);

				$statusCode = $response->getStatusCode();
				$statusDescription = '';
			} catch (Exception $e) {
				$statusCode = -1;
				$statusDescription = '無法連線到推播中心';
			} finally {
				if ($statusCode == 200) {
					$permission = $this->notification_lib->has_check_permission($this->login_info);
					$notification_content = array('permission' => $permission,
						'data' => $response->toArray()['data']);
				} else {
					$msg = '取得不到推播中心的資料! 請洽工程師。 (狀態碼:'.$statusCode.' '.$statusDescription.')';
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><script language="javascript">alert("'.$msg.'");</script>';
				}
			}

			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('core/page_wrapper_start');
			$this->load->view('admin/send_email',$page_data);
			$this->load->view('admin/send_notification',$notification_content);
			$this->load->view('core/page_wrapper_end');
			$this->load->view('admin/_footer');
		}else{
			// For POST Method

			// 如果 payload 有帶 notification，則視為發送推播
			if(isset($post["notification"])) {
				$this->load->model('log/Log_userlogin_model');

				// 投資人 / 借貸人 / 不過濾
				$targetCategory = isset($post['investment'])?NotificationTargetCategory::Investment:0;
				$targetCategory += isset($post['loan'])?NotificationTargetCategory::Loan:0;

				$platform = array();
				if(isset($post['android']))
					$platform[] = 'android';
				if(isset($post['ios']))
					$platform[] = 'ios';

				if(empty($platform)) {
					alert('必須選定發送的平台，請重新填入。', admin_url('contact/send_email'));
				}else if(!isset($post['send_date']) || $post['send_date'] != date('Y-m-d H:i',strtotime($post['send_date']))) {
					alert('預定發送時間不能為空，或是格式有誤。', admin_url('contact/send_email'));
				}else if(time() - strtotime($post['send_date']) > 0) {
					alert('預定發送時間不能比目前時間早，請重新填入。', admin_url('contact/send_email'));
				}else if(!$targetCategory) {
					alert('必須勾選發送的對象，如投資端等。', admin_url('contact/send_email'));
				}

				$devices = $this->Log_userlogin_model->get_filtered_deviceid($post, $targetCategory);
				$data = array(
					'user_id' => $this->login_info->id,
					'sender_name' => $this->login_info->name,
					'target_category' => $this->config->item('notification')['target_category_name'][$targetCategory],
					'target_platform' => implode("/", $platform),
					'tokens' => $devices,
					"notification"=>array(
						"title" => $post['title'],
						"body" => $post['content']
					),
					"data" => array(),
					"send_at" => $post['send_date'],
					"apns" => array(
						"payload" => array(
							"category" => "NEW_MESSAGE_CATEGORY"
						)
    				),
					"type" => NotificationType::Manual
				);

				if("" != trim($post['target']))
					$data['data']['targetNo']= trim($post['target']);

				$result = $this->notification_lib->send_notification($data);
				if ($result['code'] == 200 || $result['code'] == 201) {
					$c = (count($devices ,COUNT_RECURSIVE) - count($devices) - array_sum(array_map('count',$devices )));
					if ($c == 0)
						alert('推播預約失敗! 因為沒有任何匹配的設備，請重新選取篩選規則。', admin_url('contact/send_email'));
					else
						alert('推播預約成功! 該推播總共會有 ' . $c . ' 個設備收到推播訊息。', admin_url('contact/send_email'));
				}else{
					alert('推播預約失敗! 請洽工程師。 (狀態碼:'.$result['code'].' '.$result['msg'].')', admin_url('contact/send_email'));
				}

			}else {
				$fields = ['email', 'title', 'content'];
				foreach ($fields as $field) {
					if (isset($post[$field]) && !empty($post[$field])) {
						$data[$field] = trim($post[$field]);
					}else{
						alert('缺少參數:'.$field,admin_url('contact/send_email'));
					}
				}

				$rs = $this->sendemail->email_notification($data['email'], $data['title'], $data['content']);
				if ($rs === true) {
					alert('發送成功', admin_url('contact/send_email'));
				} else {
					alert('發送失敗，請洽工程師', admin_url('contact/send_email'));
				}
			}
		}
	}

	public function update_user_platform() {
		$this->load->model('log/Log_userlogin_model');
		$get = $this->input->get(NULL, TRUE);
		$result = array();

		if(!empty($get)) {
			$offset = isset($get['offset']) ? intval($get['offset']) : 0;
			$limit = isset($get['limit']) ? intval($get['limit']) : 0;
			$result = $this->Log_userlogin_model->get_latest_devices(1, $offset, $limit);
		}else{
			$result = $this->Log_userlogin_model->get_latest_devices(1);
		}

		$deviceList = array();
		foreach($result as $i => $log) {
			$device_id_borrow = json_decode($log->client);
			if($log->user_id && isset($device_id_borrow) && !empty($device_id_borrow->device_id)) {
				if(!empty($deviceList[$log->user_id]['android']) && !empty($deviceList[$log->user_id]['ios']) )
					continue;

				if(!empty($device_id_borrow->os) && !isset($deviceList[$log->user_id][strtolower($device_id_borrow->os)])) {
					$deviceList[$log->user_id][strtolower($device_id_borrow->os)] = array($i, $log->id);
				} else {
					$httpClient = HttpClient::create();
					$response = $httpClient->request('GET', 'https://iid.googleapis.com/iid/info/'.$device_id_borrow->device_id , [
						'headers' => [
							'Content-Type' => 'application/json',
							'Authorization' => 'key='.ENV_NOTIFICATION_INVEST_API_KEY],
						'timeout' => 2.5
					]);

					try {
						$statusCode = $response->getStatusCode();
					} catch (Exception $e) {
						$statusCode = -1;
						$statusDescription = '無法連線到 iid.googleapis.com';
					} finally {
						if ($statusCode == 200) {
							$data = $response->toArray();
							if(isset($data['platform']) && empty($deviceList[$log->user_id][strtolower($data['platform'])])) {
								$deviceList[$log->user_id][strtolower($data['platform'])] = array($i, $log->id);
							}
						}
					}
				}
			}
		}

		$updatedcount = 0;
		foreach ($deviceList as $device) {
			foreach (array('android', 'ios') as $key) {
				if(!isset($device[$key]))
					continue;
				$client = json_decode($result[$device[$key][0]]->client);
				$client->os = $key;
				$this->Log_userlogin_model->update($device[$key][1], array("client" => json_encode($client)));
				$updatedcount++;
			}
		}
		alert('更新成功，已更新'.$updatedcount."個設備。", admin_url('contact/send_email'));
	}
	public function certifications()
	{
		$this->load->model('user/user_certification_model');
		$certification 	= $this->config->item('certifications');
		foreach($certification as $id => $value){
			$certification_name_list[$id] = $value['name'];
		}

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

		foreach ($list as $certification) {
			$certification->remark = json_decode($certification->remark);
		}

		$page_data['list'] 					= $list;
		$page_data['certification_list'] 	= $certification_name_list;
		unset($page_data['certification_list'][3]);
		$page_data['status_list'] 			= $this->user_certification_model->status_list;
		$page_data['investor_list'] 		= $this->user_certification_model->investor_list;
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/contact_user_certification_list',$page_data);
		$this->load->view('admin/_footer');
	}
}
?>