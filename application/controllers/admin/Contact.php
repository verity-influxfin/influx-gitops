<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use Symfony\Component\HttpClient\HttpClient;

class Contact extends MY_Admin_Controller {
	
	protected $edit_method = array('edit','send_email');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_contact_model');
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

		$notification_url = $this->config->item('notification')['url'];
		$httpClient = HttpClient::create();
		$response = $httpClient->request('PUT', $notification_url, [
			'body' => json_encode(['id' => $id, 'action' => $action]),
			'headers' => ['Content-Type' => 'application/json'],
		]);

		try {
			$statusCode = $response->getStatusCode();
		} catch (Exception $e) {
			$statusCode = -1;
			$statusDescription = '無法連線到推播中心';
		} finally {
			if ($statusCode == 200) {
				return json_encode(['code' => $statusCode, 'description' => 'success']);
			} else {
				return json_encode(['code' => $statusCode, 'description' => 'failure']);
			}
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
			$notification_url = $this->config->item('notification')['url'];
			$httpClient = HttpClient::create();
			$response = $httpClient->request('GET', $notification_url, [
				'body' => json_encode($data),
				'headers' => ['Content-Type' => 'application/json'],
				'timeout' => 2.5
			]);

			try {
				$notification_content = array('data' => array());
				$statusCode = $response->getStatusCode();
			} catch (Exception $e) {
				$statusCode = -1;
				$statusDescription = '無法連線到推播中心';
			} finally {
				if ($statusCode == 200) {
					$notification_content = array('data' => $response->toArray()['data']);
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
				$this->load->model('user/user_notification_model');

				// 投資人 / 借貸人 / 不過濾
				$targetCategory = isset($post['investment'])?NotificationTargetCategory::investment:0;
				$targetCategory += isset($post['loan'])?NotificationTargetCategory::loan:0;

				$platform = array();
				if(isset($post['android']))
					$platform[] = 'android';
				if(isset($post['ios']))
					$platform[] = 'ios';

				if(!isset($post['send_date']) || $post['send_date'] != date('Y-m-d H:i',strtotime($post['send_date']))) {
					alert('預定發送時間不能為空，或是格式有誤。', admin_url('contact/send_email'));
				}

				$devices = $this->user_notification_model->get_filtered_deviceid($post, $targetCategory);
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
					"data" => array(
						'targetNo' => $post['target']
					),
					"send_at" => $post['send_date'],
					"apns" => array(
						"payload" => array(
							"category" => "NEW_MESSAGE_CATEGORY"
						)
    				),
				);

				$notification_url = $this->config->item('notification')['url'];
				$httpClient = HttpClient::create();
				$response = $httpClient->request('POST', $notification_url, [
					'body' => json_encode($data),
					'headers' => ['Content-Type' => 'application/json']
				]);

				try {
					$statusCode = $response->getStatusCode();
					$statusDescription = '';
				} catch (Exception $e) {
					$statusCode = -1;
					$statusDescription = '無法連線到推播中心';
				} finally {
					if ($statusCode == 200) {
						if (count($devices) == 0)
							alert('推播預約失敗! 因為沒有任何匹配的設備，請重新選取篩選規則。', admin_url('contact/send_email'));
						else
							alert('推播預約成功! 該推播總共會有 ' . count($devices) . ' 個設備收到推播訊息。', admin_url('contact/send_email'));
					}else{
						alert('推播預約失敗! 請洽工程師。 (狀態碼:'.$statusCode.' '.$statusDescription.')', admin_url('contact/send_email'));
					}
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
