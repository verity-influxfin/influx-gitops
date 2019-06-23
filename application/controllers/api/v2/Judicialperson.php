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
		$this->load->model('log/log_image_model');
		
        $method = $this->router->fetch_method();
        $class 	= $this->router->fetch_class();
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

			if($this->request->method != 'get'){
				$this->load->model('log/log_request_model');
				$this->log_request_model->insert(array(
					'method' 	=> $this->request->method,
					'url'	 	=> $this->uri->uri_string(),
					'investor'	=> $tokenData->investor,
					'user_id'	=> $tokenData->id,
					'agent'		=> $tokenData->agent,
				));
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
	 * @apiSuccess {Number} cooperation 經銷商功能 0:未開通 1:已開通 2:審核中
	 * @apiSuccess {Number} status 狀態 0:審核中 1:審核通過 2:審核失敗
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"company_type":4,
     * 				"company":"普匯金融科技股份有限公司",
     * 				"tax_id":"68566881",
     * 				"remark":"盡快與您聯絡",
     * 				"status":1,
     * 				"created_at":1520421572
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
					'company_type' 		    => intval($value->company_type),
					'company' 			    => $value->company,
                    'tax_id' 			    => $value->tax_id,
                    'cooperation_contact' 	=> $value->cooperation_contact,
                    'cooperation_phone' 	=> $value->cooperation_phone,
                    'cooperation_address' 	=> $value->cooperation_address,
					'remark' 			    => $value->remark,
					'cooperation' 		    => $value->cooperation,
					'status' 			    => intval($value->status),
					'created_at' 		    => intval($value->created_at)
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
     * @apiParam {Number=0,1} [cooperation=0] 0:法人帳號 1:法人經銷商帳號
	 * ->cancel@apiParam {String} [server_ip] 綁定伺服器IP，多組時，以逗號分隔(經銷商必填)
	 * @apiParam {Number} [facade_image] 店門正面照(經銷商必填)( 圖片ID )
	 * @apiParam {Number} [store_image] 店內正面照(經銷商必填)( 圖片ID )
	 * @apiParam {Number} [front_image] 銀行流水帳正面(經銷商必填)( 圖片ID )
	 * @apiParam {String} [passbook_image] 銀行流水帳內頁(經銷商必填)( 圖片IDs 以逗號隔開，最多三個)
	 * 
	 * 
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
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
     * @apiErrorExample {Object} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 208 未滿20歲
     * @apiErrorExample {Object} 208
     *     {
     *       "result": "ERROR",
     *       "error": "208"
     *     }
	 *
     * @apiError 212 未通過所需的驗證(Email)
     * @apiErrorExample {Object} 212
     *     {
     *       "result": "ERROR",
     *       "error": "212"
     *     }
	 *
     * @apiError 214 此公司已申請過
     * @apiErrorExample {Object} 214
     *     {
     *       "result": "ERROR",
     *       "error": "214"
     *     }
	 *
     */
	public function apply_post()
    {
		//$this->not_support_company();
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$investor 	= $this->user_info->investor;
		$param		= array('user_id'=> $user_id);
		$bank_parm  = [];

		//必填欄位
		$fields 	= ['company_type','tax_id','bank_code','branch_code','bank_account'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
			    !preg_match('/bank|branch_code/i',$field)?$param[$field]=$input[$field]:$bank_parm[$field]=$input[$field];
			}
		}
		$param['cooperation'] = isset($input['cooperation'])&&$input['cooperation']?2:0;

        if($param['tax_id'] && strlen($param['tax_id'])==8){

			//檢查認證 NOT_VERIFIED
			if(empty($this->user_info->id_number) || $this->user_info->id_number==''){
				$this->response(array('result' => 'ERROR','error' => NOT_VERIFIED ));
			}

			//檢查認證 NOT_VERIFIED_EMAIL
			if(empty($this->user_info->email) || $this->user_info->email==''){
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

			//綁定金融帳號
            $this->load->model('user/user_bankaccount_model');
            $this->certification = $this->config->item('certifications');
            $certification 		 = $this->certification[3];
            if($certification && $certification['status']==1) {
                if (strlen($bank_parm['bank_code']) != 3) {
                    $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BANK_CODE_ERROR));
                }
                if (strlen($bank_parm['branch_code']) != 4) {
                    $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BRANCH_CODE_ERROR));
                }
                if (strlen(intval($bank_parm['bank_account'])) < 8 || strlen($bank_parm['bank_account']) < 10 || strlen($bank_parm['bank_account']) > 14 || is_virtual_account($bank_parm['bank_account'])) {
                    $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BANK_ACCOUNT_ERROR));
                }

                $where = [
                    'investor' => $investor,
                    'bank_code' => $bank_parm['bank_code'],
                    'bank_account' => $bank_parm['bank_account'],
                    'status' => 1,
                ];

                $user_bankaccount = $this->user_bankaccount_model->get_by($where);
                if ($user_bankaccount) {
                    $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BANK_ACCOUNT_EXIST));
                }

                //營利事業登記證
                $file_fields 	= ['enterprise_registration_image'];
                foreach ($file_fields as $field) {
                    $image_ids = explode(',',$input[$field]);
                    if(count($image_ids)>4){
                        $image_ids = array_slice($image_ids,0,4);
                    }
                    $list = $this->log_image_model->get_many_by([
                        'id'		=> $image_ids,
                        'user_id'	=> $user_id,
                    ]);

                    if($list && count($list)==count($image_ids)){
                        $pic[$field] = [];
                        foreach($list as $k => $v){
                            $pic[$field][] = $v->url;
                        }
                    }else{
                        $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
                    }
                }
                $param['enterprise_registration'] = json_encode($pic);
            }

			if($param['cooperation']==2){
                $param['cooperation_contact'] = isset($input['cooperation_contact'])&&$input['cooperation_contact']?$input['cooperation_contact']:'';
                $param['cooperation_phone'] = isset($input['cooperation_phone'])&&$input['cooperation_phone']?$input['cooperation_phone']:'';
                $param['cooperation_address'] = isset($input['cooperation_address'])&&$input['cooperation_address']?$input['cooperation_address']:'';

				//if (empty($input['server_ip'])) {
					//$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				//}
				//上傳檔案欄位
				$content		= [];
				$file_fields 	= ['facade_image','front_image'];
				foreach ($file_fields as $field) {
					$image_id = intval($input[$field]);
					if (!$image_id) {
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}else{
						$rs = $this->log_image_model->get_by([
							'id'		=> $image_id,
							'user_id'	=> $user_id,
						]);

						if($rs){
							$content[$field] = $rs->url;
						}else{
							$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
						}
					}
				}

				//多個檔案欄位
				$file_fields 	= ['store_image','passbook_image','bankbook_image'];
				foreach ($file_fields as $field) {
					$image_ids = explode(',',$input[$field]);
					if(count($image_ids)>4){
						$image_ids = array_slice($image_ids,0,4);
					}
					$list = $this->log_image_model->get_many_by([
						'id'		=> $image_ids,
						'user_id'	=> $user_id,
					]);

					if($list && count($list)==count($image_ids)){
						$content[$field] = [];
						foreach($list as $k => $v){
							$content[$field][] = $v->url;
						}
					}else{
						$this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
					}
				}

				$param['cooperation_content'] 	  = json_encode($content);
				//$param['cooperation_server_ip'] = trim($input['server_ip']);
			}

            $param['company_user_id'] = $this->user_info->transaction_password.','.$bank_parm['bank_code'].','.$bank_parm['branch_code'].','.$bank_parm['bank_account'];
			$exist = $this -> judicial_person_model->get_by(array(
				'user_id'         => $user_id,
				'tax_id'          => $param['tax_id'],
				'status'          => 2
			));

			if($exist){
				$param['status'] = 0;
				$rs = $this->judicial_person_model -> update($exist->id,$param);
			}else{
				$rs = $this->judicial_person_model -> insert($param);
			}

			if($rs){
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
        $device_id  = isset($input['device_id']) && $input['device_id'] ?$input['device_id']:null;
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
            //判斷鎖定狀態並解除
            $this->load->library('user_lib');
            $unblock_status = $this->user_lib->unblock_user($user_info->id);
            if($unblock_status){
                $user_info->block_status = 0;
            }
            if($user_info->block_status == 3) {
                $this->response(array('result' => 'ERROR','error' => SYSTEM_BLOCK_USER ));
            } elseif ($user_info->block_status == 2) {
                $this->response(array('result' => 'ERROR','error' => TEMP_BLOCK_USER ));
            }

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
						$this->insert_login_log($input['tax_id'],$investor,1,$user_info->id,$device_id);
						if($first_time){
							$this->load->library('notification_lib'); 
							$this->notification_lib->first_login($user_info->id,$investor);
						}
						$this->response(array('result' => 'SUCCESS', 'data' => array( 
							'token' 		=> $request_token,
							'expiry_time'	=> $token->expiry_time,
							'first_time'	=> $first_time
						)));
					}
				}
				$this->insert_login_log($input['tax_id'],$investor,0,$user_info->id,$device_id);
				$this->response(array('result' => 'ERROR','error' => USER_NOT_EXIST ));
			}
            $remind_count = $this->insert_login_log($input['phone'],$investor,0,$user_info->id,$device_id);
            $this->response([
                'result' => 'ERROR',
                'error'  => PASSWORD_ERROR,
                'data'   => [
                    'remind_count' => $remind_count,
                ]
            ]);
		}
		$this->insert_login_log($input['phone'],$investor,0,0,$device_id);
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
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
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
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
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
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} user_id 代理人UserID
	 * @apiSuccess {String} name 姓名
	 * @apiSuccess {String} id_number 身分證字號
	 * @apiSuccess {Number} created_at 新增日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"user_id":1,
     * 				"name": "曾志偉",
     * 				"id_number":"A1234*****",
     * 				"created_at":1520421572
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
	 * ->cancel@apiParam {String} server_ip 綁定伺服器IP，多組時，以逗號分隔
	 * @apiParam {Number} facade_image 店門正面照 ( 圖片ID )
	 * @apiParam {Number} store_image 店內正面照 ( 圖片ID )
	 * @apiParam {Number} front_image 銀行流水帳正面 ( 圖片ID )
	 * @apiParam {String} passbook_image 銀行流水帳內頁 ( 圖片IDs 以逗號隔開，最多三個)
	 * 
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
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
		$this->load->library('S3_upload');
        $company_user_id = $this->user_info->id;

		$judicial_person = $this->judicial_person_model->get_by(array(
			'company_user_id' 	=> $company_user_id,
		));
		if($judicial_person && $judicial_person->cooperation != 0){
			$this->response(array('result' => 'ERROR','error' => COOPERATION_EXIST ));
		}

		//if (empty($input['server_ip'])) {
			//$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
		//}

        if (empty($input['cooperation_address'])) {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }

            //上傳檔案欄位
		$content		= [];
		$file_fields 	= ['facade_image','front_image'];
		foreach ($file_fields as $field) {
			$image_id = intval($input[$field]);
			if (!$image_id) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$rs = $this->log_image_model->get_by([
					'id'		=> $image_id,
					'user_id'	=> $company_user_id,
				]);

				if($rs){
					$content[$field] = $rs->url;
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}
		}
		
		$file_fields 	= ['store_image','passbook_image','bankbook_image'];
		foreach ($file_fields as $field) {
			$image_ids = explode(',',$input[$field]);
			if(count($image_ids)>4){
				$image_ids = array_slice($image_ids,0,4);
			}
			$list = $this->log_image_model->get_many_by([
				'id'		=> $image_ids,
				'user_id'	=> $company_user_id,
			]);

			if($list && count($list)==count($image_ids)){
				$content[$field] = [];
				foreach($list as $k => $v){
					$content[$field][] = $v->url;
				}
			}else{
				$this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
			}
		}

		if($judicial_person){
			$param	= array(
				'cooperation'			=> 2,
                'cooperation_contact'	=> isset($input['cooperation_contact'])&&$input['cooperation_contact']?$input['cooperation_contact']:'',
				'cooperation_address'   => $input['cooperation_address'],
                'cooperation_phone'	    => isset($input['cooperation_phone'])&&$input['cooperation_phone']?$input['cooperation_phone']:'',
				'cooperation_content'	=> json_encode($content),
				//'cooperation_server_ip'	=> trim($input['server_ip']),
			);
			$rs = $this->judicial_person_model->update($judicial_person->id,$param);
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
     * @apiSuccess {Object} result SUCCESS
	 * ->cancel@apiSuccess {String} server_ip 綁定伺服器IP
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} status 狀態 0:未開通 1:已開通 2:審核中
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"server_ip": "192.168.0.1",
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
        $company_user_id = $this->user_info->id;

		$judicial_person = $this->judicial_person_model->get_by(array(
			'company_user_id' 	=> $company_user_id,
		));
		
		if($judicial_person){
            $data = array(
                //'server_ip'	=> $judicial_person->cooperation_server_ip,
                'cooperation_contact'	 => $judicial_person->cooperation_contact,
                'cooperation_phone'	     => $judicial_person->cooperation_phone,
                'cooperation_address'	 => $judicial_person->cooperation_address,
                'status'	             => $judicial_person->cooperation,
                'remark'	             => $judicial_person->remark,
            );
		    if($judicial_person->cooperation == 1){
                $this->load->model('user/cooperation_model');
                $cooperation= $this->cooperation_model->get_by(array(
                    'company_user_id' 	 => $company_user_id,
                ));
                $data['cooperation_id']  = $cooperation -> cooperation_id;
                $data['cooperation_key'] = $cooperation -> cooperation_key;
		    }
		}else{
			$this->response(array('result' => 'ERROR','error' => COOPERATION_NOT_EXIST ));
		}
		
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }
	
	private function insert_login_log($account='',$investor=0,$status=0,$user_id=0,$device_id=null){
        $this->load->model('log/log_userlogin_model');
        $this->load->library('user_agent');

        $this->agent->device_id=$device_id;
        $this->log_userlogin_model->insert(array(
            'account'	=> $account,
            'investor'	=> $investor,
            'user_id'	=> $user_id,
            'status'	=> $status
        ));

        $this->load->library('user_lib');
        $remind_count = $this->user_lib->auto_block_user($account,$investor,$user_id,$device_id);

        return $remind_count;
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