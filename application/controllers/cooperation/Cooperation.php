<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Cooperation extends REST_Controller {

	public $cooperation_info;
	
    public function __construct()
    {
        parent::__construct();
		$authorization 	= isset($this->input->request_headers()['Authorization'])?$this->input->request_headers()['Authorization']:'';
		$time 			= isset($this->input->request_headers()['Timestamp'])?$this->input->request_headers()['Timestamp']:'';
		$cooperation_id = isset($this->input->request_headers()['CooperationID'])?$this->input->request_headers()['CooperationID']:'';
		
		if(strlen($authorization) != 39 || substr($authorization,0,7) != 'Bearer '){
			$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		}
		
		$time_ragne = time() - intval($time);
		if($time_ragne > COOPER_TIMEOUT){
			$this->response(['error' =>'TimeOut'],REST_Controller::HTTP_FORBIDDEN);//403 TimeOut
		}
		
		if($cooperation_id){
			$this->load->model('user/cooperation_model');
			$cooperation = $this->cooperation_model->get_by([
				'cooperation_id'	=> $cooperation_id,
				'status'			=> 1,
			]);
			if($cooperation){
				$this->cooperation_info = $cooperation;
			}else{
				$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
			}
		}else{
			$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
		}


		$middles = '';
		if($this->request->method == 'post'){
			$method = $this->router->fetch_method();
			$input 	= $this->input->post(NULL, TRUE);
			ksort($input);
			$middles = implode('',array_values($input));
		}
		
		$signature = 'Bearer '.MD5(SHA1($cooperation_id.$middles.$time).$cooperation->cooperation_key);
		if($signature != $authorization){
			$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		}

		
		
		//$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
		//$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		//$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
		//$this->response(['error' =>'InsertError'],REST_Controller::CONFLICT);//409 新增錯誤
		//$this->response(['error' =>'OrderNOExists'],REST_Controller::CONFLICT);//409 單號存在
		//$this->response(['error' =>'TimeOut'],REST_Controller::HTTP_FORBIDDEN);//403 TimeOut
    }

	/**
     * @apiDefine AuthorizationRequired
	 * @apiError (401) AuthorizationRequired Authorization Required.
	 * @apiErrorExample AuthorizationRequired
	 *     HTTP/1.1 401 Not Found
	 *     {
	 *       "error": "AuthorizationRequired"
	 *     }
     */
	/**
     * @apiDefine TimeOut
	 * @apiError (403) TimeOut Time Out.
	 * @apiErrorExample TimeOut
	 *     HTTP/1.1 403 Not Found
	 *     {
	 *       "error": "TimeOut"
	 *     }
     */
	 /**
     * @apiDefine RequiredArguments
	 * @apiError (400) RequiredArguments Required Arguments.
	 * @apiErrorExample RequiredArguments
	 *     HTTP/1.1 400 Not Found
	 *     {
	 *       "error": "RequiredArguments"
	 *     }
     */
	 /**
     * @apiDefine CooperationNotFound
	 * @apiError (404) CooperationNotFound Cooperation not found.
	 * @apiErrorExample CooperationNotFound
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "CooperationNotFound"
	 *     }
     */
	 
	 /**
     * @api {get} /cooperation/info Company Information
     * @apiGroup Cooperation
	 * @apiVersion 0.1.0
	 * @apiName GetCooperationInformation
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
     * @apiSuccess {String} result SUCCESS
     * @apiSuccess {String} company Company
     * @apiSuccess {String} tax_id tax ID number
	 *
     * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"company": "普匯金融科技股份有限公司",
     *      	"tax_id": "68566881"
	 *      }
     *    }
     *
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse CooperationNotFound
	 *
     */
	public function info_get()
    {
		$user_info 	= $this->user_model->get($this->cooperation_info->company_user_id);
		$data 		= [
			'company'	=> $user_info->name,
			'tax_id'	=> $user_info->id_number,
		];
		$this->response(array('result' => 'SUCCESS','data' => $data));
    }
	
	
	/**
     * @api {post} /cooperation/contact Contact Us
	 * @apiGroup Cooperation
	 * @apiVersion 0.1.0
	 * @apiName PostCooperationContact
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + content + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 * @apiParam {String} content Content
     *
     * @apiSuccess {String} result SUCCESS
     * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS"
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (409) InsertError Insert Error.
	 * @apiErrorExample InsertError
	 *     HTTP/1.1 409 Not Found
	 *     {
	 *       "error": "InsertError"
	 *     }
	 *
     */
	public function contact_post()
    {
		$this->load->model('user/user_contact_model');
        $input 	= $this->input->post(NULL, TRUE);

		if (empty($input['content'])) {
			$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
		}

		$param	= [
			'user_id' 	=> $this->cooperation_info->company_user_id,
			'investor'	=> 2,
			'content'	=> $input['content']
		];
		
		$insert = $this->user_contact_model->insert($param);
		if($insert){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(['error' =>'InsertError'],REST_Controller::CONFLICT);//409 新增錯誤
		}
    }
	
}
