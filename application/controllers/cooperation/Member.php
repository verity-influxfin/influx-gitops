<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Member extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['register'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
        }
    }
	

	 /**
     * @api {get} /member/info 合作商 個人資訊
     * @apiGroup Member
     *
	 * @apiParam {String} cooperation_id CooperationID
	 * @apiParam {String} time Unix Timestamp
	 * @apiParam {String} cooperation_token MD5(SHA1(cooperation_id=xxxx+time=1543831102)+CooperationKEY)
     * @apiSuccess {json} result SUCCESS
	 *
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"id": "1",
     *      	"name": "",
     *      	"picture": "https://graph.facebook.com/2495004840516393/picture?type=large",
     *      	"nickname": "陳霈",
     *      	"phone": "0912345678",
     *      	"investor_status": "1",
     *      	"my_promote_code": "9JJ12CQ5",
     *      	"id_number": null,
     *      	"transaction_password": true,
     *      	"investor": 1,  
     *      	"created_at": "1522651818",     
     *      	"updated_at": "1522653939",     
     *      	"expiry_time": "1522675539"     
	 *      }
     *    }
     *
     */
	public function info_get()
    {
    }
	
	
	/**
     * @api {post} /member/contact 合作商 聯絡我們
     * @apiGroup Member
	 * @apiParam {String} content 內容
     * @apiParam {file} [image1] 附圖1
     * @apiParam {file} [image2] 附圖2
     * @apiParam {file} [image3] 附圖3
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
