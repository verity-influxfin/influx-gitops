<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Certification extends REST_Controller {

	public $user_info,$certification;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_certification_model');
		$this->load->library('S3_upload');
		$this->load->library('Certification_lib');
        $method 				= $this->router->fetch_method();
		$this->certification 	= $this->config->item('certifications');
        $nonAuthMethods = ['verifyemail'];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
			
			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
			}

			if($this->user_info->block_status != 0){
				$this->response(array('result' => 'ERROR','error' => BLOCK_USER ));
			}
			
			//暫不開放法人
			if(isset($tokenData->company) && $tokenData->company != 0 && $method != 'debitcard' ){
				$this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }
	
	/**
     * @api {get} /v2/certification/list 認證 認證列表
	 * @apiVersion 0.2.0
	 * @apiName GetCertificationList
     * @apiGroup Certification
     *
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} id Certification ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} alias 代號
	 * @apiSuccess {Number} user_status 用戶認證狀態：null:尚未認證 0:認證中 1:已完成 2:認證失敗
	 * 
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"實名認證",
     * 				"description":"實名認證",
     * 				"alias":"id_card",
     * 				"user_status":1,
     * 			},
     * 			{
     * 				"id":"2",
     * 				"name":"金融帳號認證",
     * 				"description":"金融帳號認證",
	 * 				"alias":"debit_card",
     * 				"user_status":1,
     * 			}
     * 			]
     * 		}
     * }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     */
	 
	public function list_get()
    {
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$certification_list	= $this->certification_lib->get_status($user_id,$investor);
		$list				= array();
		if(!empty($certification_list)){
			$list = $certification_list;
		}
		$this->response(array('result' => 'SUCCESS','data' => array('list' => $list) ));
    }
	
	/**
     * @api {get} /v2/certification/:alias 認證 取得認證資料
	 * @apiVersion 0.2.0
	 * @apiName GetCertificationIndex
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"certification_id": "3", 
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432",     
     *      	"name": "toy",
     *      	"id_number": "G121111111",
     *      	"id_card_date": "1060707",
     *      	"id_card_place": "北市",
     *      	"birthday": "1020101",
     *      	"address": "全家就是我家"
	 *      }
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {Object} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function index_get($alias='')
    {
		$certification = array();
		foreach($this->certification as $key => $value){
			if($value['alias']==trim($alias)){
				$certification 	= $this->certification[$key];
			}
		}
		
		if($certification && $certification['status']==1){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification['id'],$investor);
			if($rs){
				$data = array(
					'certification_id'	=> $rs->certification_id,
					'status'			=> $rs->status,
					'expire_time'		=> $rs->expire_time,
					'created_at'		=> $rs->created_at,
					'updated_at'		=> $rs->updated_at,
				);

				switch($certification['id']){
					case 1: 
						$fields 	= ['name','id_number','id_card_date','id_card_place','birthday','address'];
						break;
					case 2: 
						$fields 	= ['school','major','department','student_id','system','email','grade'];
						break;
					case 3: 
						$fields 	= ['bank_code','branch_code','bank_account'];
						break;
					case 4: 
						$fields 	= [];
						break;
					case 5: 
						$fields 	= ['name','phone','relationship'];
						break;
					case 6: 
						$fields 	= ['email'];
						break;
					case 7: 
						$fields 	= ['parttime','allowance','scholarship','other_income','restaurant','transportation','entertainment','other_expense'];
						break;
					case 8: 
						$fields 	= ['school','system'];
						break;
					case 9: 
						$fields 	= ['return_type'];
						break;
					case 10: 
						$fields 	= [];
						break;
					default:
						break;
				}
				
				foreach ($fields as $field) {
					if (isset($rs->content[$field]) && !empty($rs->content[$field])) {
						$data[$field] = $rs->content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS','data' => $data));
			}
			$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /v2/certification/idcard 認證 實名認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationIdcard
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String{2..15}} name 姓名
     * @apiParam {String} id_number 身分證字號
     * @apiParam {String} id_card_date 發證日期(民國) ex:1060707
     * @apiParam {String} id_card_place 發證地點
     * @apiParam {String} birthday 生日(民國) ex:1020101
     * @apiParam {String} address 地址
     * @apiParam {file} front_image 身分證正面照
     * @apiParam {file} back_image 身分證背面照
     * @apiParam {file} person_image 本人照
     * @apiParam {file} healthcard_image 健保卡照
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 504 身分證字號格式錯誤
     * @apiErrorExample {Object} 504
     *     {
     *       "result": "ERROR",
     *       "error": "504"
     *     }
	 *
     * @apiError 505 身分證字號已存在
     * @apiErrorExample {Object} 505
     *     {
     *       "result": "ERROR",
     *       "error": "505"
     *     }
	 *
     */
	public function idcard_post()
    {
		$certification_id 	= 1;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			
			//是否驗證過
			$this->was_verify($certification_id);
			
			//必填欄位
			$fields 	= ['name','id_number','id_card_date','id_card_place','birthday','address'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,15}$/u',$content['name'])){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			
			if(mb_strlen($content['name']) < 2 || mb_strlen($content['name']) > 15){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			
			//檢查身分證字號
			$id_check = check_cardid($input['id_number']);
			if(!$id_check){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_IDNUMBER_ERROR ));
			}

			//檢查身分證字號
			$id_number_used = $this->user_model->get_by(array( 'id_number' => $input['id_number'] ));
			if($id_number_used && $id_number_used->id != $user_id){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_IDNUMBER_EXIST ));
			}

			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image','person_image','healthcard_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$certification['alias']);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /v2/certification/student 認證 學生身份認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationStudent
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String} school 學校名稱
	 * @apiParam {String=0,1,2} [system=0] 學制 0:大學 1:碩士 2:博士
	 * @apiParam {String} major 學門
	 * @apiParam {String} department 系所
	 * @apiParam {String} grade 年級
	 * @apiParam {String} student_id 學號
	 * @apiParam {String} email 校內電子信箱
     * @apiParam {file} front_image 學生證正面照
     * @apiParam {file} back_image 學生證背面照
	 * @apiParam {String} sip_account SIP帳號
	 * @apiParam {String} sip_password SIP密碼
	 * @apiParam {file} [transcript_image] 成績單
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 510 此學號已被使用過
     * @apiErrorExample {Object} 510
     *     {
     *       "result": "ERROR",
     *       "error": "510"
     *     }
	 *
     * @apiError 511 此學生Email已被使用過
     * @apiErrorExample {Object} 511
     *     {
     *       "result": "ERROR",
     *       "error": "511"
     *     }
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {Object} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
	 *
     */
	public function student_post()
    {
		$certification_id 	= 2;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			//是否驗證過
			$this->was_verify($certification_id);

			//必填欄位
			$fields 	= [
				'school',
				'department',
				'grade',
				'student_id',
				'email',
				'major',
				'sip_account',
				'sip_password'
			];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}

			$content['system'] 	= isset($input['system']) && in_array($input['system'],array(0,1,2))?$input['system']:0;
			if (!filter_var($content['email'], FILTER_VALIDATE_EMAIL) || substr($content['email'],-7,7)!='.edu.tw') {
				$this->response(array('result' => 'ERROR','error' => INVALID_EMAIL_FORMAT ));
			}
			
			$this->load->model('user/user_meta_model');
			
			//Email是否使用過
			$user_meta = $this->user_meta_model->get_by(array(
				'meta_key'	=> 'school_email',
				'meta_value'=> $content['email'],
			));
			
			if($user_meta && $user_meta->user_id != $user_id){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_STUDENTEMAIL_EXIST ));
			}
			
			//學號是否使用過
			$user_meta = $this->user_meta_model->get_by(array(
				'meta_key'	=> 'student_id',
				'meta_value'=> $content['student_id'],
			));
			
			if($user_meta && $user_meta->user_id != $user_id){
				$user_school = $this->user_meta_model->get_by(array(
					'user_id'	=> $user_meta->user_id,
					'meta_key'	=> 'school_name',
				));

				if($user_school && $user_school->meta_value==$content['school']){
					$this->response(array('result' => 'ERROR','error' => CERTIFICATION_STUDENTID_EXIST ));
				}
			}
			
			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$certification['alias']);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}

			if (isset($_FILES['transcript_image']) && !empty($_FILES['transcript_image'])) {
				$content['transcript_image'] = $this->s3_upload->image($_FILES,'transcript_image',$user_id,$certification['alias']);
			}else{
				$content['transcript_image'] = '';
			}
			
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('Sendemail');
				$this->sendemail->send_verify_school($insert,$content['email']);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	

	/**
     * @api {post} /v2/certification/debitcard 認證 金融帳號認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationDebitcard
     * @apiGroup Certification
	 * @apiDescription 法人登入時，只有負責人情況下可操作。
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String{3}} bank_code 銀行代碼三碼
	 * @apiParam {String{4}} branch_code 分支機構代號四碼
	 * @apiParam {String{10..14}} bank_account 銀行帳號
     * @apiParam {file} front_image 金融卡正面照
     * @apiParam {file} back_image 金融卡背面照
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 506 銀行代碼長度錯誤
     * @apiErrorExample {Object} 506
     *     {
     *       "result": "ERROR",
     *       "error": "506"
     *     }
	 *
     * @apiError 507 分支機構代號長度錯誤
     * @apiErrorExample {Object} 507
     *     {
     *       "result": "ERROR",
     *       "error": "507"
     *     }
	 *
     * @apiError 508 銀行帳號長度錯誤
     * @apiErrorExample {Object} 508
     *     {
     *       "result": "ERROR",
     *       "error": "508"
     *     }
	 *
     * @apiError 509 銀行帳號已存在
     * @apiErrorExample {Object} 509
     *     {
     *       "result": "ERROR",
     *       "error": "509"
     *     }
	 *
     */
	public function debitcard_post()
    {
		$this->load->model('user/user_bankaccount_model');
		$certification_id 	= 3;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			if($this->user_info->company==1 && $this->user_info->incharge != 1){
				$this->response(array('result' => 'ERROR','error' => NOT_IN_CHARGE ));
			}
			
			//是否驗證過
			$this->was_verify($certification_id);
			
			//必填欄位
			$fields 	= ['bank_code','branch_code','bank_account'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = trim($input[$field]);
				}
			}
			
			if(strlen($content['bank_code'])!=3){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_BANK_CODE_ERROR ));
			}
			if(strlen($content['branch_code'])!=4){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_BRANCH_CODE_ERROR ));
			}
			if(strlen(intval($content['bank_account']))<8 || strlen($content['bank_account'])<10 || strlen($content['bank_account'])>14 || is_virtual_account($content['bank_account'])){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_BANK_ACCOUNT_ERROR ));
			}
			
			$where = array(
				'investor'		=> $investor,
				'bank_code'		=> $content['bank_code'],
				'bank_account'	=> $content['bank_account'],
				'status'		=> 1,
			);
			
			$user_bankaccount = $this->user_bankaccount_model->get_by($where);
			if($user_bankaccount){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_BANK_ACCOUNT_EXIST ));
			}
			
			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$certification['alias']);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'expire_time'		=> strtotime('+20 years'),
				'content'			=> json_encode($content),
			);
			
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$bankaccount_info = array(
					'user_id'		=> $user_id,
					'investor'		=> $investor,
					'user_certification_id'	=> $insert,
					'bank_code'		=> $content['bank_code'],
					'branch_code'	=> $content['branch_code'],
					'bank_account'	=> $content['bank_account'],
					'front_image'	=> $content['front_image'],
					'back_image'	=> $content['back_image'],
				);
				
				if($investor){
					$bankaccount_info['verify'] = 2;
					$this->load->library('Sendemail');
					$this->sendemail->admin_notification('新的一筆金融帳號驗證 出借端會員ID:'.$user_id,'有新的一筆金融帳號驗證 出借端會員ID:'.$user_id);
				}else{
					$this->certification_lib->set_success($insert);
					$target = $this->target_model->get_by(array(
						'user_id'	=> $user_id,
						'status'	=> 2,
					));
					if($target){
						$bankaccount_info['verify'] = 2;
					}
				}
				
				$this->user_bankaccount_model->insert($bankaccount_info);
				
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	/**
     * @api {post} /v2/certification/emergency 認證 緊急聯絡人
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationEmergency
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String{2..15}} name 緊急聯絡人姓名
	 * @apiParam {String} phone 緊急聯絡人電話
	 * @apiParam {String} relationship 緊急聯絡人關係
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function emergency_post()
    {
		$certification_id 	= 5;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			
			//是否驗證過
			$this->was_verify($certification_id);
			
			//必填欄位
			$fields 	= ['name','phone','relationship'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			$name_limit = array('爸爸','媽媽','爺爺','奶奶','父親','母親');
			if(in_array($content['name'],$name_limit)){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,15}$/u',$content['name'])){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			if(mb_strlen($content['name']) < 2 || mb_strlen($content['name']) > 15){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			
			if(!preg_match("/^09[0-9]{2}[0-9]{6}$/", $content['phone'])){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			
			$phone_exist = $this->user_model->get_by(array(
				'phone'		=> $content['phone'],
				'status'	=> 1,
			));
			if($phone_exist){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
			
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert 			= $this->user_certification_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	
	
	/**
     * @api {post} /v2/certification/email 認證 常用電子信箱
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationEmail
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String} email Email
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {Object} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
	 *
     */
	public function email_post()
    {
		$certification_id 	= 6;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			
			//是否驗證過
			$this->was_verify($certification_id);

			//必填欄位
			$fields 	= ['email'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			if (!filter_var($content['email'], FILTER_VALIDATE_EMAIL)) {
				$this->response(array('result' => 'ERROR','error' => INVALID_EMAIL_FORMAT ));
			}
		
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('Sendemail');
				$this->sendemail->send_verify_email($insert,$content['email'],$investor);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /v2/certification/verifyemail 認證 認證電子信箱(學生身份、常用電子信箱)
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationVerifyemail
     * @apiGroup Certification
	 * @apiParam {String} type 認證Type
	 * @apiParam {String} email Email
	 * @apiParam {String} code 認證Code
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {Object} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {Object} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
	 *
     */
	public function verifyemail_post()
    {
		$this->load->library('Sendemail');
		$input 		= $this->input->post(NULL, TRUE);

		//必填欄位
		$fields 	= ['type','email','code'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}
		}
		
		$input['email'] = base64_decode($input['email']);
		
		if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
			$this->response(array('result' => 'ERROR','error' => INVALID_EMAIL_FORMAT ));
		}
	
		$rs = $this->sendemail->verify_code($input['type'],$input['email'],$input['code']);
		if($rs){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR','error' => VERIFY_CODE_ERROR ));
		}

    }
	
	/**
     * @api {post} /v2/certification/financial 認證 財務訊息認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationFinancial
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} parttime 打工收入
	 * @apiParam {Number} allowance 零用錢收入
	 * @apiParam {Number} scholarship 獎學金收入
	 * @apiParam {Number} other_income 其他收入
	 * @apiParam {Number} restaurant 餐飲支出
	 * @apiParam {Number} transportation 交通支出
	 * @apiParam {Number} entertainment 娛樂支出
	 * @apiParam {Number} other_expense 其他支出
     * @apiParam {file} [creditcard_image] 信用卡帳單照
     * @apiParam {file} [passbook_image] 存摺內頁照
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function financial_post()
    {
		$certification_id 	= 7;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			 
			//是否驗證過
			$this->was_verify($certification_id);
			
			//必填欄位
			$fields 	= [
				'parttime',
				'allowance',
				'scholarship',
				'other_income',
				'restaurant',
				'transportation',
				'entertainment',
				'other_expense'
			];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$content[$field] = 0;
				}else{
					$content[$field] = intval($input[$field]);
				}
			}
			
			//上傳檔案欄位
			$file_fields 	= ['creditcard_image','passbook_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$certification['alias']);
					if($image){
						$content[$field] = $image;
					}else{
						$content[$field] = '';
					}
				}else{
					$content[$field] = '';
				}
			}
			
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->certification_lib->set_success($insert);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /v2/certification/social 認證 社交認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationSocial
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String=instagram} type 認證類型
     * @apiParam {String} access_token Instagram AccessToken
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function social_post()
    {
		$certification_id 	= 4;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$type  		= 'instagram';
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			 
			//是否驗證過
			$this->was_verify($certification_id);
			
			$fields = ['access_token'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}

			$this->load->library('instagram_lib'); 
			$info 		= $this->instagram_lib->get_info($input['access_token']);
		
			$content = array(
				'type'			=> $type,
				'info'			=> $info,
				'access_token'	=> $input['access_token'],
			);
			
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	/**
     * @api {post} /v2/certification/diploma 認證 最高學歷認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationDiploma
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String} school 學校名稱
	 * @apiParam {String=0,1,2} [system=0] 學制 0:大學 1:碩士 2:博士
     * @apiParam {file} diploma_image 畢業證書照
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 510 此學號已被使用過
     * @apiErrorExample {Object} 510
     *     {
     *       "result": "ERROR",
     *       "error": "510"
     *     }
	 *
     * @apiError 511 此學生Email已被使用過
     * @apiErrorExample {Object} 511
     *     {
     *       "result": "ERROR",
     *       "error": "511"
     *     }
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {Object} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
	 *
     */
	public function diploma_post()
    {
		$certification_id 	= 8;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			//是否驗證過
			$this->was_verify($certification_id);

			if (empty($input['school'])) {
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}else{
				$content['school'] = $input['school'];
			}
			
			$content['system'] = isset($input['system']) && in_array($input['system'],array(0,1,2))?$input['system']:0;

			
			//上傳檔案欄位
			if (isset($_FILES['diploma_image']) && !empty($_FILES['diploma_image'])) {
				$image 	= $this->s3_upload->image($_FILES,'diploma_image',$user_id,$certification['alias']);
				if($image){
					$content['diploma_image'] = $image;
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}else{
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /v2/certification/investigation 認證 聯合徵信認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationInvestigation
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 *
	 * @apiParam {String=0,1,2} [return_type=0] 回寄方式 0:不需寄回 1:Email
     * @apiParam {file} postal_image 郵遞回單照
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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function investigation_post()
    {
		$certification_id 	= 9;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			 
			//是否驗證過
			$this->was_verify($certification_id);
			
			$content['return_type'] = isset($input['return_type']) && intval($input['return_type'])?$input['return_type']:0;
			
			//上傳檔案欄位
			$file_fields 	= ['postal_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image = $this->s3_upload->image($_FILES,$field,$user_id,$certification['alias']);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}
			
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	/**
     * @api {post} /v2/certification/job 認證 工作認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationJob
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiSuccess {String} company 公司名稱
	 * @apiSuccess {String} tax_id 公司統一編號
     * @apiParam {file} labor_image 勞健保卡
     * @apiParam {file} business_image 名片/工作證明
     * @apiParam {file} passbook_image 存摺內頁照
     * @apiParam {file} auxiliary_image 收入輔助證明

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
	 * @apiUse IsCompany
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {Object} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function job_post()
    {
		$certification_id 	= 10;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			//是否驗證過
			$this->was_verify($certification_id);

			//必填欄位
			$fields 	= ['company','tax_id'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}

			//上傳檔案欄位
			$file_fields 	= ['labor_image','business_image','passbook_image','auxiliary_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$certification['alias']);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}
			}
			
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('Sendemail');
				$this->sendemail->send_verify_school($insert,$content['email']);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	private function was_verify($certification_id=0){
		$user_certification	= $this->certification_lib->get_certification_info($this->user_info->id,$certification_id,$this->user_info->investor);
		if($user_certification){
			$this->response(array('result' => 'ERROR','error' => CERTIFICATION_WAS_VERIFY ));
		}
	}
}
