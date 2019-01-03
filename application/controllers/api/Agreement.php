<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Agreement extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('admin/agreement_model');
    }
	
	/**
     * @api {get} /agreement/list 協議 協議列表
	 * @apiVersion 0.1.0
	 * @apiName GetAgreementList
     * @apiGroup Agreement
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Agreement ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} alias 代號

     * @apiSuccessExample {Object} SUCCESS
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
		$agreement_list = $this->agreement_model->get_many_by(array('status'=>1));
		$list			= array();
		if(!empty($agreement_list)){
			foreach($agreement_list as $key => $value){
				$list[] = array(
					'id' 		=> $value->id,
					'name' 		=> $value->name,
					'content' 	=> $value->content,
					'alias' 	=> $value->alias,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array('list' => $list) ));
    }

	
	/**
     * @api {get} /agreement/info/:alias 協議 協議書
	 * @apiVersion 0.1.0
	 * @apiName GetAgreementInfo
     * @apiGroup Agreement
	 * @apiParam {String} alias 代號
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Agreement ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} alias 代號

     * @apiSuccessExample {Object} SUCCESS
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
     * @apiError 701 此協議書不存在
     * @apiErrorExample {Object} 701
     *     {
     *       "result": "ERROR",
     *       "error": "701"
     *     }
     */
	public function info_get($alias)
    {
		if(!empty($alias)){
			$agreement = $this->agreement_model->get_by(array('alias'=>$alias));
			if($agreement && $agreement->status){
				$data = array(
					'id' 			=> $agreement->id,
					'name' 			=> $agreement->name,
					'content' 		=> $agreement->content,
					'alias' 		=> $agreement->alias,
				);
				$this->response(array('result' => 'SUCCESS','data' => $data ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => AGREEMENT_NOT_EXIST ));
    }
	
}
