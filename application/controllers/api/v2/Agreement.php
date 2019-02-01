<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Agreement extends REST_Controller {

	public $user_info;
	public $redis=true;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->library('Predis_lib');
    }
	
	/**
     * @api {get} /v2/agreement/list 協議 協議列表
	 * @apiVersion 0.2.0
	 * @apiName GetAgreementList
     * @apiGroup Agreement
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} alias 代號

     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"name":"用戶協議",
     * 				"alias":"user",
     * 			},
     * 			{
     * 				"name":"投資人協議",
     * 				"alias":"investor",
     * 			}
     * 			]
     * 		}
     * }
     */
	 
	public function list_get()
    {
		$agreement_list = $this->predis_lib->get_agreement_list();
		$list 			= [];
		if($agreement_list){
			foreach($agreement_list as $key => $value){
				$list[] = [
					'name'	=> $value->name,
					'alias'	=> $value->alias,
				];
			}
		}
		$this->response(['result' => 'SUCCESS','data' => ['list' => $list]]);
    }

	
	/**
     * @api {get} /v2/agreement/info/:alias 協議 協議書
	 * @apiVersion 0.2.0
	 * @apiName GetAgreementInfo
     * @apiGroup Agreement
	 * @apiParam {String} alias 代號
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} content 內容
	 * @apiSuccess {String} alias 代號

     * @apiSuccessExample {Object} SUCCESS
     * {
     * 	"result":"SUCCESS",
     * 		"data":{
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
			$agreement_list = $this->predis_lib->get_agreement_list();
			$list 			= [];
			if($agreement_list && isset($agreement_list[$alias])){
				$agreement = $agreement_list[$alias];
				$data = [
					'name' 		=> $agreement->name,
					'content' 	=> $agreement->content,
					'alias' 	=> $agreement->alias,
				];
				$this->response(['result' => 'SUCCESS','data' => $data]);
			}
		}
		$this->response(['result' => 'ERROR','error' => AGREEMENT_NOT_EXIST]);
    }
	
}
