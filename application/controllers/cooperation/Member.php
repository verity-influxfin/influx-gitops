<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Member extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
        }
    }
	

	 /**
     * @api {get} /member/info 公司資訊
     * @apiGroup Member
	 * @apiParam {String} cooperation_id CooperationID
	 * @apiParam {String} time Unix Timestamp
	 * @apiParam {String} cooperation_token MD5(SHA1(cooperation_id & time) + CooperationKEY)
	 *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} company 公司名稱
     * @apiSuccess {String} tax_id 統一編號
     * @apiSuccess {String} name 負責人姓名
     * @apiSuccess {String} phone 負責人電話
     * @apiSuccess {String} my_promote_code 邀請碼
	 *
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"company": "普匯金融科技股份有限公司",
     *      	"tax_id": "68566881",
     *      	"name": "陳霈",
     *      	"phone": "0912345678",
     *      	"my_promote_code": "9JJ12CQ5",  
	 *      }
     *    }
     *
     */
	public function info_get()
    {
    }
	
	
	/**
     * @api {post} /member/contact 回報問題
     * @apiGroup Member
	 * @apiParam {String} content 內容
	 * @apiParam {String} cooperation_id CooperationID
	 * @apiParam {String} time Unix Timestamp
	 * @apiParam {String} cooperation_token MD5(SHA1(content & cooperation_id & time)+ CooperationKEY)
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
     * 
     */
	public function contact_post()
    {
    }
	
}
