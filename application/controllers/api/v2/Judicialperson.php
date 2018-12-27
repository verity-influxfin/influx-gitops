<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Judicialperson extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/judicial_person_model');
		$this->load->model('user/judicial_agent_model');
		
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
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
     * @api {get} /v2/judicialperson/list 法人會員 已申請法人列表
	 * @apiVersion 0.2.0
	 * @apiName GetJudicialpersonList
     * @apiGroup Judicialperson
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * 
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} company_type 公司類型 1:獨資 2:合夥,3:有限公司 4:股份有限公司
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
     * 				"remark":"盡快與您聯絡",
     * 				"status":"1",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
     *
     */
	public function list_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$param				= array( 'user_id'=> $user_id);
		$judicial_person	= $this->judicial_person_model->get_many_by($param);
		$list				= array();
		if(!empty($judicial_person)){
			foreach($judicial_person as $key => $value){
				$list[] = array(
					'company_type' 		=> $value->company_type,
					'company' 			=> $value->company,
					'tax_id' 			=> $value->tax_id,
					'remark' 			=> $value->remark,
					'status' 			=> $value->status,
					'created_at' 		=> $value->created_at
				);
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => array('list' => $list) ));
    }

	
		/**
     * @api {post} /v2/judicialperson/apply 法人會員 申請法人身份
	 * @apiVersion 0.2.0
	 * @apiName PostJudicialpersonApply
     * @apiGroup Judicialperson
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number=1,2,3,4} company_type 公司類型 1:獨資 2:合夥,3:有限公司 4:股份有限公司
     * @apiParam {String{8}} tax_id 公司統一編號
	 * 
	 * 
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     * @apiError 202 未通過所需的驗證(實名驗證)
     * @apiErrorExample {json} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 208 未滿20歲
     * @apiErrorExample {json} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     * @apiError 212 未通過所需的驗證(Email)
     * @apiErrorExample {json} 212
     *     {
     *       "result": "ERROR",
     *       "error": "212"
     *     }
	 *
     * @apiError 213 申請人非公司負責人
     * @apiErrorExample {json} 213
     *     {
     *       "result": "ERROR",
     *       "error": "213"
     *     }
	 *
     * @apiError 214 此公司已申請過
     * @apiErrorExample {json} 214
     *     {
     *       "result": "ERROR",
     *       "error": "214"
     *     }
	 *
     */
	public function apply_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= array('user_id'=> $user_id);
		
		//必填欄位
		$fields 	= ['company_type','tax_id'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = $input[$field];
			}
		}
		
		if($param['tax_id'] && strlen($param['tax_id'])==8){

			//檢查認證 NOT_VERIFIED
			if(empty($this->user_info->id_number) || $this->user_info->id_number==""){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
			}
			
			//檢查認證 NOT_VERIFIED_EMAIL
			if(empty($this->user_info->email) || $this->user_info->email==""){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
			}
		
			if(get_age($this->user_info->birthday) < 20){
				$this->response(array('result' => 'ERROR','error' => UNDER_AGE ));
			}

			$this->load->library('Gcis_lib'); 
			$company_data = $this->gcis_lib->check_responsible($param['tax_id'],$this->user_info->name);
			if(!$company_data){
				$this->response(array('result' => 'ERROR','error' => NOT_IN_CHARGE ));
			}
			
			$param['company'] = $company_data['Company_Name'];
			$exist = $this->judicial_person_model->get_by(array(
				'tax_id' 	=> $param['tax_id'],
				'status !=' => 2
			));
			if($exist){
				$this->response(array('result' => 'ERROR','error' => COMPANY_EXIST ));
			}
			
			$insert = $this->judicial_person_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		
		$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
    }
	
	/**
     * @api {post} /v2/judicialperson/login 法人會員 用戶登入
	 * @apiVersion 0.2.0
	 * @apiName PostJudicialpersonLogin
     * @apiGroup Judicialperson
     * @apiParam {String{8}} tax_id 公司統一編號
     * @apiParam {String} phone 手機號碼
     * @apiParam {String{6..}} password 密碼
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} token request_token
	 * @apiSuccess {Number} first_time 是否首次本端
	 * @apiSuccess {String} expiry_time token時效
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjMiLCJuYW1lIjoiIiwicGhvbmUiOiIwOTEyMzQ1Njc4Iiwic3RhdHVzIjoiMSIsImJsb2NrX3N0YXR1cyI6IjAifQ.Ced85ewiZiyLJZk3yvzRqO3005LPdMjlE8HZdYZbGAE",
     *      	"expiry_time": "1522673418",
	 * 			"first_time":1		
     *      }
     *    }
	 * @apiUse InputError
	 * @apiUse BlockUser
     *
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 304 密碼錯誤
     * @apiErrorExample {Object} 304
     *     {
     *       "result": "ERROR",
     *       "error": "304"
     *     }
     *
     */
	public function login_post(){

		$input = $this->input->post(NULL, TRUE);
        $fields 	= ['tax_id','phone','password'];
        foreach ($fields as $field) {
            if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }
		$investor	= 1;
		/*$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			if(sha1($input['password'])==$user_info->password){
				
				if($user_info->block_status != 0){
					$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
				}
			
				$token 		= new stdClass();
				$first_time = 0;
				if($investor==1 && $user_info->investor_status==0){
					$user_info->investor_status = 1;
					$this->user_model->update($user_info->id,array("investor_status"=>1));
					$first_time = 1;
				}else if($investor==0 && $user_info->status==0){
					$user_info->status = 1;
					$this->user_model->update($user_info->id,array("status"=>1));
					$first_time = 1;
				}

				$token->id			= $user_info->id;
				$token->phone		= $user_info->phone;
				$token->auth_otp	= get_rand_token();
				$token->expiry_time	= time()+REQUEST_TOKEN_EXPIRY;
				$token->investor 	= $investor;
				$request_token = AUTHORIZATION::generateUserToken($token);
				$this->user_model->update($user_info->id,array("auth_otp"=>$token->auth_otp));
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor ,"user_id"=>$user_info->id,"status"=>1));
				if($first_time){
					$this->load->library('notification_lib'); 
					$this->notification_lib->first_login($user_info->id,$investor);
				}
				$this->response(array('result' => 'SUCCESS', 'data' => array( "token" => $request_token,"expiry_time"=>$token->expiry_time,"first_time"=>$first_time) ));
			}else{
				$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"user_id"=>$user_info->id,"status"=>0));
				$this->response(array('result' => 'ERROR','error' => PASSWORD_ERROR ));
			}
		}else{
			$this->log_userlogin_model->insert(array("account"=>$input['phone'],"investor"=>$investor,"status"=>0));
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}*/
	}
	
	
}