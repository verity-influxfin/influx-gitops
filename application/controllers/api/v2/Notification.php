<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Notification extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_notification_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
			
			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
			}
			
			if($this->user_info->block_status != 0){
				$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
			}
			
			if($this->request->method != 'get'){
				$this->load->model('log/log_request_model');
				$this->log_request_model->insert([
					'method' 	=> $this->request->method,
					'url'	 	=> $this->uri->uri_string(),
					'investor'	=> $tokenData->investor,
					'user_id'	=> $tokenData->id,
					'agent'		=> $tokenData->agent,
				]);
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }
	
	/**
     * @api {get} /v2/notification/list 消息 消息列表
	 * @apiVersion 0.2.0
	 * @apiName GetNotificationList
     * @apiGroup Notification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Notification ID
	 * @apiSuccess {String} title 標題
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {Number} status 1:未讀 2:已讀
	 * @apiSuccess {Number} created_at 創建日期
	 

     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id": 1,
     * 				"title":"用戶資料認證未通過",
     * 				"content":"您好！ 您的資料認證未通過，請重新認證。",
     * 				"status": 1,
     * 				"created_at":"1519635711"
     * 			},
     * 			{
    * 				"id": 241,
    * 				"title": "【會員】 交易密碼設置成功",
    * 				"content": "您好！\r\n\t\t\t\t\t您的交易密碼設置成功。",
    * 				"status": 1,
    * 				"created_at": 1548303563
     * 			},
     * 			]
     * 		}
     * }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     */
	 
	public function list_get()
    {
		$user_id			= $this->user_info->id;
		$investor			= $this->user_info->investor;
		$data				= [];
		$notification_list 	= $this->user_notification_model->limit(150)->order_by('created_at','desc')->get_many_by([
			'user_id'		=> $user_id,
			'status <>'		=> 0,
			'investor'		=> [$investor,2]
		]);
		$list				= [];
		if(!empty($notification_list)){
			foreach($notification_list as $key => $value){
				$list[] = [
					'id' 		 => intval($value->id),
					'title' 	 => $value->title,
					'content' 	 => $value->content,
					'status' 	 => intval($value->status),
					'created_at' => intval($value->created_at),
				];
			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['list' => $list] ]);
    }
	
	/**
     * @api {get} /v2/notification/info/:id 消息 消息內容（已讀）
	 * @apiVersion 0.2.0
	 * @apiName GetNotificationInfo
     * @apiGroup Notification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
	 * @apiParam {String} id 代號
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Notification ID
	 * @apiSuccess {String} title 標題
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {Number} status 1:未讀 2:已讀
	 * @apiSuccess {Number} created_at 創建日期

     * @apiSuccessExample {Object} SUCCESS
     * {
     * 	"result":"SUCCESS",
     * 		"data":{
     * 			"id": 224,
     * 			"title":"用戶資料認證未通過",
     * 			"content":"您好！ 您的資料認證未通過，請重新認證。",
     * 			"status": 1,
     * 			"created_at": 1548133390
     * 		}
     * }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     * @apiError 601 此消息不存在
     * @apiErrorExample {Object} 601
     *     {
     *       "result": "ERROR",
     *       "error": "601"
     *     }
     */
	public function info_get($id)
    {
		$user_id			= $this->user_info->id;
		$investor			= $this->user_info->investor;

		if(!empty($id)){
			$notification = $this->user_notification_model->get_by([
				'user_id'	=> $user_id,
				'id'		=> $id,
				'status <>'	=> '0'
			]);
			if($notification && $notification->status){
				$data = [
					'id' 			=> intval($notification->id),
					'title' 		=> $notification->title,
					'content' 		=> $notification->content,
					'status' 		=> intval($notification->status),
					'created_at' 	=> intval($notification->created_at),
				];
				$this->user_notification_model->update($id,['status'=>2]);
				$this->response(['result' => 'SUCCESS','data' => $data]);
			}
		}
		$this->response(['result' => 'ERROR','error' => NOTIFICATION_NOT_EXIST ]);
    }
	
	/**
     * @api {get} /v2/notification/readall 消息 一鍵已讀
	 * @apiVersion 0.2.0
	 * @apiName GetNotificationReadall
     * @apiGroup Notification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 	"result":"SUCCESS"
     * }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     */
	public function readall_get()
    {
		$user_id		= $this->user_info->id;
		$investor		= $this->user_info->investor;
		$notification 	= $this->user_notification_model->update_by([
			'user_id'	=> $user_id,
			'status'	=> 1,
			'investor'	=> [$investor,2]
		],['status' => 2]);
		$this->response(['result' => 'SUCCESS']);
    }
}
