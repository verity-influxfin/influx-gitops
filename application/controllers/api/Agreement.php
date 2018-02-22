<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Agreement extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('platform/agreement_model');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list','info'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone)) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			$this->user_info = $tokenData;
        }
    }
	
	/**
     * @api {get} /agreement/list 協議 協議列表
     * @apiGroup Agreement
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Agreement ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} alias 代號

     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"用戶協議",
     * 				"content":"用戶協議",
     * 				"alias":"user",
     * 			},
     * 			{
     * 				"id":"2",
     * 				"name":"投資人協議",
     * 				"content":"投資人協議",
     * 				"alias":"investor",
     * 			}
     * 			]
     * 		}
     * }
     */
	 
	public function list_get()
    {
		$agreement_list 	= $this->agreement_model->get_many_by(array("status"=>1));
		$list					= array();
		if(!empty($agreement_list)){
			foreach($agreement_list as $key => $value){
				$list[] = array(
					"id" 			=> $value->id,
					"name" 			=> $value->name,
					"content" 	=> $value->content,
					"alias" 		=> $value->alias,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }

	
	/**
     * @api {get} /agreement/info/{alias} 協議 協議書
     * @apiGroup Agreement
	 * @apiParam {String} alias (required) 代號
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Agreement ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} alias 代號

     * @apiSuccessExample {json} SUCCESS
     * {
     * 	"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"name":"用戶協議",
     * 			"content":"用戶協議",
     * 			"alias":"user",
     * 		}
     * }
	 *
	 * @apiUse InputError
     */
	public function info_get($alias)
    {
		if(!empty($alias)){
			$agreement = $this->agreement_model->get_by(array("alias"=>$alias));
			if($agreement && $agreement->status){
				$data = array(
					"id" 			=> $agreement->id,
					"name" 			=> $agreement->name,
					"content" 		=> $agreement->content,
					"alias" 		=> $agreement->alias,
				);
				$this->response(array('result' => 'SUCCESS',"data" => $data ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => AGREEMENT_NOT_EXIST ));
    }
	
}
