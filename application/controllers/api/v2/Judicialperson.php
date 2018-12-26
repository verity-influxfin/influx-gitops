<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Judicialperson extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/judicial_person_model');
		
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
			
			//只限出借人
			if($tokenData->investor != 1){
				$this->response(array('result' => 'ERROR','error' => NOT_INVERTOR ));
			}
			
			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
			}
			
			if($this->user_info->block_status != 0){
				$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }

	/**
     * @api {get} /v2/judicialperson/list 出借方 已申請法人列表
	 * @apiVersion 0.2.0
	 * @apiName GetJudicialpersonList
     * @apiGroup Judicialperson
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} company_type 公司類型
	 * @apiSuccess {String} company 公司名稱
	 * @apiSuccess {String} tax_id 公司統一編號
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:審核中 1:審核通過 2:審核失敗
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"company_type":"股份有限公司",
     * 				"company":"普匯金融科技股份有限公司",
     * 				"tax_id":"68566881",
     * 				"remark":"人員會盡快與您聯絡",
     * 				"status":"1",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotInvestor
     *
     */
	public function list_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$param				= array( "user_id"=> $user_id);
		$judicial_person	= $this->judicial_person_model->get_many_by($param);
		$list				= array();
		if(!empty($judicial_person)){
			foreach($judicial_person as $key => $value){
				$list[] = array(
					"company_type" 		=> $value->company_type,
					"company" 			=> $value->company,
					"tax_id" 			=> $value->tax_id,
					"remark" 			=> $value->remark,
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array("list" => $list) ));
    }

}