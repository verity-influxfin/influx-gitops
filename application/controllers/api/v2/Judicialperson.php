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
        $nonAuthMethods = ['login'];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
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
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
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
	 * @apiUse IsCompany
     *
     */
	public function list_get()
    {
		$this->not_support_company();
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
	 * @apiUse NotIncharge
	 * @apiUse IsCompany
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
		$this->not_support_company();
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
	 * @apiParam {Number=0,1} [investor=1] 1:投資端
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
	 * 			"first_time": 1		
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
		
		if(strlen($input['password']) < PASSWORD_LENGTH || strlen($input['password'])> PASSWORD_LENGTH_MAX ){
			$this->response(array('result' => 'ERROR','error' => PASSWORD_LENGTH_ERROR ));
		}
		
		$investor	= 1;
		$user_info 	= $this->user_model->get_by('phone', $input['phone']);	
		if($user_info){
			if(sha1($input['password'])==$user_info->password){
				
				if($user_info->block_status != 0){
					$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
				}
				
				$company_info 	= $this->user_model->get_by('phone', $input['tax_id']);
				if($company_info){
					$judicial_agent = $this->judicial_agent_model->get_by(array(
						'company_user_id'	=> $company_info->id,
						'user_id'			=> $user_info->id,
						'status'			=> 1,
					));
					if($judicial_agent){
						$first_time = 0;
						if($company_info->investor_status==0){
							$company_info->investor_status = 1;
							$this->user_model->update($company_info->id,array('investor_status'=>1));
							$first_time = 1;
						}

						$token = (object) [
							'id'			=> $company_info->id,
							'phone'			=> $company_info->phone,
							'auth_otp'		=> get_rand_token(),
							'expiry_time'	=> time() + REQUEST_TOKEN_EXPIRY,
							'investor'		=> $investor,
							'company'		=> 1,
							'incharge'		=> $judicial_agent->incharge,
							'agent'			=> $user_info->id,
						];
						
						$request_token 		= AUTHORIZATION::generateUserToken($token);
						$this->user_model->update($company_info->id,array(
							'auth_otp'	=> $token->auth_otp
						));
						$this->insert_login_log($input['tax_id'],$investor,1,$user_info->id);
						if($first_time){
							$this->load->library('notification_lib'); 
							$this->notification_lib->first_login($user_info->id,$investor);
						}
						$this->response(array('result' => 'SUCCESS', 'data' => array( 'token' => $request_token,'expiry_time'=>$token->expiry_time,'first_time'=>$first_time) ));
					}
				}
				$this->insert_login_log($input['tax_id'],$investor,0,$user_info->id);
				$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
			}
			$this->insert_login_log($input['phone'],$investor,0,$user_info->id);
			$this->response(array('result' => 'ERROR','error' => PASSWORD_ERROR ));
		}
		$this->insert_login_log($input['phone'],$investor,0,0);
		$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
	}
	
	/**
     * @api {post} /v2/judicialperson/agent 法人代理 新增代理人
	 * @apiVersion 0.2.0
	 * @apiName PostJudicialpersonAgent
     * @apiGroup Judicialperson
	 * @apiDescription 只有負責人登入法人帳號情況下可操作。
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
     * @apiParam {String{10}} id_number 代理人身分證字號
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
	 * @apiUse NotIncharge
	 * @apiUse NotCompany
     *
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
     *
     * @apiError 313 代理人已存在
     * @apiErrorExample {Object} 313
     *     {
     *       "result": "ERROR",
     *       "error": "313"
     *     }
	 *
     * @apiError 504 身分證字號格式錯誤
     * @apiErrorExample {Object} 504
     *     {
     *       "result": "ERROR",
     *       "error": "504"
     *     }
	 *
     */
	public function agent_post()
    {
		$this->not_incharge();
		$input 	= $this->input->post(NULL, TRUE);

		if (empty($input['id_number'])){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}

		//檢查身分證字號
		$id_check = check_cardid($input['id_number']);
		if(!$id_check){
			$this->response(array('result' => 'ERROR','error' => CERTIFICATION_IDNUMBER_ERROR ));
		}

		//檢查身分證字號
		$id_number_user = $this->user_model->get_by(array('id_number'=>$input['id_number']));
		if($id_number_user && $id_number_user->id != $this->user_info->agent){
			
			$judicial_agent = $this->judicial_agent_model->get_by(array(
				'company_user_id'	=> $this->user_info->id,
				'user_id'			=> $id_number_user->id,
				'incharge'			=> 0,
			));
			
			if($judicial_agent){
				if($judicial_agent->status==1){
					$this->response(array('result' => 'ERROR','error' => AGENT_EXIST ));
				}else{
					$rs = $this->judicial_agent_model->update($judicial_agent->id,array('status'=>1));
				}
			}else{
				$param	= array(
					'incharge'			=> 0,
					'company_user_id'	=> $this->user_info->id,
					'user_id'			=> $id_number_user->id,
				);
				$rs = $this->judicial_agent_model->insert($param);
			}
			
			if($rs){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		
		}else{
			$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
		}
    }
	
	/**
     * @api {delete} /v2/judicialperson/agent/:user_id 法人代理 刪除代理人
	 * @apiVersion 0.2.0
	 * @apiName DeleteJudicialpersonAgent
     * @apiGroup Judicialperson
	 * @apiDescription 只有負責人登入法人帳號情況下可操作。
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
     * @apiParam {String} user_id 代理人UserID
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
	 * @apiUse NotIncharge
	 * @apiUse NotCompany
     *
     * @apiError 302 會員不存在
     * @apiErrorExample {Object} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
	 *
     */
	public function agent_delete($user_id=0)
    {
		$this->not_incharge();
		
		if (empty($user_id) && intval($user_id)){
			$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		}
		
		//檢查身分證字號
		$user_info = $this->user_model->get(intval($user_id));
		if($user_info && $user_info->id != $this->user_info->agent){
			
			$judicial_agent = $this->judicial_agent_model->get_by(array(
				'company_user_id'	=> $this->user_info->id,
				'user_id'			=> $user_info->id,
				'status'			=> 1,
				'incharge'			=> 0,
			));
			
			if($judicial_agent){
				$rs = $this->judicial_agent_model->update($judicial_agent->id,array('status'=>0));
				if($rs){
					$this->response(array('result' => 'SUCCESS'));
				}else{
					$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
				}
			}
		}
		$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
    }
	
	/**
     * @api {get} /v2/judicialperson/agent 法人代理 代理人名單
	 * @apiVersion 0.2.0
	 * @apiName GetJudicialpersonAgent
     * @apiGroup Judicialperson
	 * @apiDescription 只有負責人登入法人帳號情況下可操作。
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id 代理人UserID
	 * @apiSuccess {String} name 姓名
	 * @apiSuccess {String} id_number 身分證字號
	 * @apiSuccess {String} created_at 新增日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"user_id":"1",
     * 				"name": "曾志偉",
     * 				"id_number":"A1234*****",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotIncharge
	 * @apiUse NotCompany
     *
     */
	public function agent_get()
    {
		$this->not_incharge();
		$input 		= $this->input->get(NULL, TRUE);
		$list		= array();
		$agent_list = $this->judicial_agent_model->order_by('user_id','asc')->get_many_by(array(
			'company_user_id'	=> $this->user_info->id,
			'incharge'			=> 0,
			'status'			=> 1
		));

		if(!empty($agent_list)){
			foreach($agent_list as $key => $value){
				$user_info 	= $this->user_model->get($value->user_id);
				$list[] 	= array(
					'user_id' 		=> $value->user_id,
					'name' 			=> $user_info->name,
					'id_number' 	=> substr($user_info->id_number,0,6).'****',
					'created_at'	=> $value->updated_at
				);
			}
		}
		$data['list'] = $list;
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }
	
	/**
     * @api {post} /v2/judicialperson/cooperation 法人經銷 申請為經銷商
	 * @apiVersion 0.2.0
	 * @apiName PostJudicialpersonCooperation
     * @apiGroup Judicialperson
	 * @apiDescription 只有負責人登入法人帳號情況下可操作。
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
	 * @apiParam {file} facade_image 店門正面照
	 * @apiParam {file} store_image 店內正面照
	 * @apiParam {file} front_image 銀行流水帳正面
	 * @apiParam {file} passbook_image 銀行流水帳內頁
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
	 * @apiUse NotIncharge
	 * @apiUse NotCompany
     *
     * @apiError 314 已申請過經銷商
     * @apiErrorExample {Object} 314
     *     {
     *       "result": "ERROR",
     *       "error": "314"
     *     }
	 *
     */
	public function cooperation_post()
    {
		$this->not_incharge();
		$input 	= $this->input->post(NULL, TRUE);
		$this->load->model('user/cooperation_model');
		$this->load->library('S3_upload');
		
		$cooperation = $this->cooperation_model->get_by(array(
			'company_user_id'	=> $this->user_info->id,
		));
		
		if($cooperation && $cooperation->status != 2){
			$this->response(array('result' => 'ERROR','error' => COOPERATION_EXIST ));
		}
		
		//上傳檔案欄位
		$content		= [];
		$file_fields 	= ['facade_image','store_image','front_image','passbook_image'];
		foreach ($file_fields as $field) {
			if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
				$image 	= $this->s3_upload->image($_FILES,$field,$this->user_info->id,'cooperation');
				if($image){
					$content[$field] = $image;
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}

		if($cooperation){
			$rs = $this->cooperation_model->update($cooperation->id,array(
				'status'	=> 0,
				'content'	=> json_encode($content)
			));
		}else{
			$param	= array(
				'company_user_id'	=> $this->user_info->id,
				'content'			=> json_encode($content),
				'cooperation_id'	=> 'CO'.$this->user_info->id_number,
				'cooperation_key'	=> SHA1('CO'.$this->user_info->id_number.time())
			);
			$rs = $this->cooperation_model->insert($param);
		}
		
		if($rs){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
		}
    }
	
	/**
     * @api {get} /v2/judicialperson/cooperation 法人經銷 查詢經銷申請
	 * @apiVersion 0.2.0
	 * @apiName GetJudicialpersonCooperation
     * @apiGroup Judicialperson
	 * @apiDescription 只有負責人登入法人帳號情況下可操作。
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * 
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:審核中 1:審核通過 2:審核失敗
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"remark":"",
     * 			"status": "1"
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse NotIncharge
	 * @apiUse NotCompany
     *
     * @apiError 315 未申請過經銷商
     * @apiErrorExample {Object} 315
     *     {
     *       "result": "ERROR",
     *       "error": "315"
     *     }
	 *
     */
	public function cooperation_get()
    {
		$this->not_incharge();
		$this->load->model('user/cooperation_model');

		
		$cooperation = $this->cooperation_model->get_by(array(
			'company_user_id'	=> $this->user_info->id,
		));
		if($cooperation){
			$data = array(
				'status'	=> $cooperation->status,
				'remark'	=> $cooperation->remark,
			);
		}else{
			$this->response(array('result' => 'ERROR','error' => COOPERATION_NOT_EXIST ));
		}
		
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }
	
	private function insert_login_log($account='',$investor=0,$status=0,$user_id=0){
		$this->load->model('log/log_userlogin_model');
		return $this->log_userlogin_model->insert(array(
			'account'	=> $account,
			'investor'	=> $investor,
			'user_id'	=> $user_id,
			'status'	=> $status
		));
	}
	
	private function not_support_company(){
		if($this->user_info->company != 0 ){
			$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
		}
	}

	private function not_incharge(){
		if($this->user_info->company != 1 ){
			$this->response(array('result' => 'ERROR','error' => NOT_COMPANY ));
		}
		
		if($this->user_info->incharge != 1 ){
			$this->response(array('result' => 'ERROR','error' => NOT_IN_CHARGE ));
		}
	}
	
}