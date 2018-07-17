<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Certification extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('platform/certification_model');
		$this->load->model('user/user_certification_model');
		$this->load->model('user/user_model');
		$this->load->library('S3_upload');
		$this->load->library('Certification_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ["verifyemail"];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			$this->user_info = $this->user_model->get($tokenData->id);
			if($tokenData->auth_otp != $this->user_info->auth_otp){
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
			}
			
			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;

        }
    }
	
	/**
     * @api {get} /certification/list 認證 認證列表
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Certification ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} alias 代號
	 * @apiSuccess {number} user_status 用戶認證狀態：null:尚未認證 0:認證中 1:已完成 2:認證失敗
	 * 
     * @apiSuccessExample {json} SUCCESS
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
     */
	 
	public function list_get()
    {
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$certification_list	= $this->certification_lib->get_status($user_id,$investor);
		if(!empty($certification_list)){
			foreach($certification_list as $key => $value){
				$list[] = array(
					"id" 			=> $value->id,
					"name" 			=> $value->name,
					"description" 	=> $value->description,
					"alias" 		=> $value->alias,
					"user_status" 	=> $value->user_status,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }

	/**
     * @api {post} /certification/idcard 認證 實名認證
     * @apiGroup Certification
     * @apiParam {String} name 姓名
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
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 504 身分證字號格式錯誤
     * @apiErrorExample {json} 504
     *     {
     *       "result": "ERROR",
     *       "error": "504"
     *     }
	 *
     * @apiError 505 身分證字號已存在
     * @apiErrorExample {json} 505
     *     {
     *       "result": "ERROR",
     *       "error": "505"
     *     }
	 *
     */
	public function idcard_post()
    {
		$alias 			= "id_card";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
			);
			
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['name','id_number','id_card_date','id_card_place','birthday','address'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			//檢查身分證字號
			$id_check = check_cardid($input['id_number']);
			if(!$id_check){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_IDNUMBER_ERROR ));
			}

			//檢查身分證字號
			$id_number_used = $this->user_model->get_by(array( "id_number" => $input['id_number'] ));
			if($id_number_used && $id_number_used->id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_IDNUMBER_EXIST ));
			}

			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image','person_image','healthcard_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$alias);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}
			}

			$param['content'] 	= json_encode($content);
			$insert 			= $this->user_certification_model->insert($param);
			if($insert){
				$this->certification_lib->idcard_verify($insert);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/idcard 認證 實名認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} name 姓名
     * @apiSuccess {String} id_number 身分證字號
     * @apiSuccess {String} id_card_date 發證日期(民國) ex:1060707
     * @apiSuccess {String} id_card_place 發證地點
     * @apiSuccess {String} birthday 生日(民國) ex:1020101
     * @apiSuccess {String} address 地址
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function idcard_get()
    {
		$alias 			= "id_card";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['name','id_number','id_card_date','id_card_place','birthday','address'];
				foreach ($fields as $field) {
					if (isset($content[$field]) && !empty($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /certification/student 認證 學生身份認證
     * @apiGroup Certification
	 * @apiParam {String} school 學校名稱
	 * @apiParam {String=0,1,2} [system=0] 學制 0:大學 1:碩士 2:博士
	 * @apiParam {String} department 系所
	 * @apiParam {String} grade 年級
	 * @apiParam {String} student_id 學號
	 * @apiParam {String} email 校內電子信箱
     * @apiParam {file} front_image 學生證正面照
     * @apiParam {file} back_image 學生證背面照
	 * @apiParam {String} [sip_account] SIP帳號
	 * @apiParam {String} [sip_password] SIP密碼
     * @apiParam {file} [transcript_image] 成績單
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {json} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
	 *
     */
	public function student_post()
    {
		$alias 		= "student";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
			);
			
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['school','department','grade','student_id','email'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			$content['system'] 			= isset($input['system']) && in_array($input['system'],array(0,1,2))?$input['system']:0;
			$content['sip_account'] 	= isset($input['sip_account'])?$input['sip_account']:"";
			$content['sip_password'] 	= isset($input['sip_password'])?$input['sip_password']:"";

			if (!filter_var($content['email'], FILTER_VALIDATE_EMAIL) || substr($content['email'],-7,7)!=".edu.tw") {
				$this->response(array('result' => 'ERROR',"error" => INVALID_EMAIL_FORMAT ));
			}
			
			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$alias);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}
			}
			
			if (isset($_FILES['transcript_image']) && !empty($_FILES['transcript_image'])) {
				$content['transcript_image'] = $this->s3_upload->image($_FILES,'transcript_image',$user_id,$alias);
			}else{
				$content['transcript_image'] = "";
			}
			
			$param['content'] = json_encode($content);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('Sendemail');
				$this->sendemail->send_verify_school($insert,$content['email']);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/student 認證 學生身份認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} school 學校名稱
	 * @apiSuccess {String} system 學制 0:大學 1:碩士 2:博士
	 * @apiSuccess {String} department 系所
	 * @apiSuccess {String} grade 年級
	 * @apiSuccess {String} student_id 學號
	 * @apiSuccess {String} email 校內Email
	 * @apiSuccess {String} front_image 學生證正面照
	 * @apiSuccess {String} back_image 學生證背面照
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"certification_id": "3",
     *      	"school": "國立宜蘭大學",
     *      	"department": "電機工程學系",
     *      	"grade": "1",
     *      	"student_id": "1496B032", 
     *      	"email": "xxxxx@xxx.edu.com.tw",     
     *      	"system": "0",     
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432"     
	 *      }
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function student_get()
    {
		$alias 		= "student";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['school','department','student_id','system','email','grade'];
				foreach ($fields as $field) {
					if (isset($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /certification/debitcard 認證 金融帳號認證
     * @apiGroup Certification
	 * @apiParam {String{3}} bank_code 銀行代碼三碼
	 * @apiParam {String{4}} branch_code 分支機構代號四碼
	 * @apiParam {String{10..16}} bank_account 銀行帳號
     * @apiParam {file} front_image 金融卡正面照
     * @apiParam {file} back_image 金融卡背面照
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 506 銀行代碼長度錯誤
     * @apiErrorExample {json} 506
     *     {
     *       "result": "ERROR",
     *       "error": "506"
     *     }
	 *
     * @apiError 507 分支機構代號長度錯誤
     * @apiErrorExample {json} 507
     *     {
     *       "result": "ERROR",
     *       "error": "507"
     *     }
	 *
     * @apiError 508 銀行帳號長度錯誤
     * @apiErrorExample {json} 508
     *     {
     *       "result": "ERROR",
     *       "error": "508"
     *     }
	 *
     * @apiError 509 銀行帳號已存在
     * @apiErrorExample {json} 509
     *     {
     *       "result": "ERROR",
     *       "error": "509"
     *     }
	 *
     */
	public function debitcard_post()
    {
		$this->load->model('user/user_bankaccount_model');
		$alias 			= "debit_card";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
				"expire_time"		=> strtotime("+20 years"),
			);
			
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}

			//必填欄位
			$fields 	= ['bank_code','branch_code','bank_account'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = trim($input[$field]);
				}
			}
			
			if(strlen($content['bank_code'])!=3){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_BANK_CODE_ERROR ));
			}
			if(strlen($content['branch_code'])!=4){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_BRANCH_CODE_ERROR ));
			}
			if(strlen($content['bank_account'])<10 || is_virtual_account($content['bank_account'])){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_BANK_ACCOUNT_ERROR ));
			}
			
			$where = array(
				"investor"		=> $investor,
				"bank_code"		=> $content["bank_code"],
				"bank_account"	=> intval($content["bank_account"]),
			);
			
			$user_bankaccount = $this->user_bankaccount_model->get_by($where);
			if($user_bankaccount){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_BANK_ACCOUNT_EXIST ));
			}
			
			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image'];
			foreach ($file_fields as $field) {
				if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$alias);
					if($image){
						$content[$field] = $image;
					}else{
						$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
					}
				}else{
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}
			}

			$param['content'] = json_encode($content);
	
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$bankaccount_info = array(
					"user_id"		=> $user_id,
					"investor"		=> $investor,
					"bank_code"		=> $content["bank_code"],
					"branch_code"	=> $content["branch_code"],
					"bank_account"	=> intval($content["bank_account"]),
					"front_image"	=> $content["front_image"],
					"back_image"	=> $content["back_image"],
				);
				
				if($investor){
					$bankaccount_info['verify'] = 2;
				}else{
					$this->certification_lib->set_success($insert);
				}
				
				$this->user_bankaccount_model->insert($bankaccount_info);
				
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/debitcard 認證 金融帳號認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} user_name User 姓名
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} bank_code 銀行代碼三碼
	 * @apiSuccess {String} branch_code 分支機構代號四碼
	 * @apiSuccess {String} bank_account 銀行帳號
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"certification_id": "4",
     *      	"bank_code": "822",
     *      	"branch_code": "1234",
     *      	"bank_account": "149612222032", 
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432"     
	 *      }
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function debitcard_get()
    {
		$alias 			= "debit_card";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$user_name 	= $this->user_info->name;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"user_name"			=> $user_name,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['bank_code','branch_code','bank_account'];
				foreach ($fields as $field) {
					if (isset($content[$field]) && !empty($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
		/**
     * @api {post} /certification/emergency 認證 緊急聯絡人
     * @apiGroup Certification
	 * @apiParam {String} name 緊急聯絡人姓名
	 * @apiParam {String} phone 緊急聯絡人電話
	 * @apiParam {String} relationship 緊急聯絡人關係
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function emergency_post()
    {
		$alias 		= "emergency";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
			);
			
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['name','phone','relationship'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			if(!preg_match("/09[0-9]{2}[0-9]{6}/", $content['phone'])){
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}
		
			$param['content'] 	= json_encode($content);
			$insert 			= $this->user_certification_model->insert($param);
			if($insert){
				$this->certification_lib->set_success($insert);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/emergency 認證 緊急聯絡人資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} name 緊急聯絡人姓名
	 * @apiSuccess {String} phone 緊急聯絡人電話
	 * @apiSuccess {String} relationship 緊急聯絡人關係
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "8",
     *      	"certification_id": "6",
     *      	"name": "XXX",
     *      	"phone": "0912345678",
     *      	"relationship": "配偶", 
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432"     
	 *      }
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function emergency_get()
    {
		$alias 		= "emergency";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['name','phone','relationship'];
				foreach ($fields as $field) {
					if (isset($content[$field]) && !empty($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /certification/email 認證 常用電子信箱
     * @apiGroup Certification
	 * @apiParam {String} email Email
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {json} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
	 *
     */
	public function email_post()
    {
		$alias 		= "email";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
			);
			
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['email'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			if (!filter_var($content['email'], FILTER_VALIDATE_EMAIL)) {
				$this->response(array('result' => 'ERROR',"error" => INVALID_EMAIL_FORMAT ));
			}
		
			$param['content'] = json_encode($content);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('Sendemail');
				$this->sendemail->send_verify_email($insert,$content['email']);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/email 認證 常用電子信箱資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} email Email
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "8",
     *      	"certification_id": "6",
     *      	"email": "XXX",
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432"     
	 *      }
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function email_get()
    {
		$alias 			= "email";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['email'];
				foreach ($fields as $field) {
					if (isset($content[$field]) && !empty($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /certification/verifyemail 認證 認證電子信箱(學生身份、常用電子信箱)
     * @apiGroup Certification
	 * @apiParam {String} type 認證Type
	 * @apiParam {String} email Email
	 * @apiParam {String} code 認證Code
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 *
     * @apiError 204 Email格式錯誤
     * @apiErrorExample {json} 204
     *     {
     *       "result": "ERROR",
     *       "error": "204"
     *     }
     *
     * @apiError 303 驗證碼錯誤
     * @apiErrorExample {json} 303
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
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}
		}
		
		if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
			$this->response(array('result' => 'ERROR',"error" => INVALID_EMAIL_FORMAT ));
		}
	
		$rs = $this->sendemail->verify_code($input["type"],$input["email"],$input["code"]);
		if($rs){
			$this->response(array('result' => 'SUCCESS'));
		}else{
			$this->response(array('result' => 'ERROR',"error" => VERIFY_CODE_ERROR ));
		}

    }
	
		/**
     * @api {post} /certification/financial 認證 財務訊息認證
     * @apiGroup Certification
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
     * @apiSuccess {json} result SUCCESS
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
	 *
	 * @apiUse InputError
	 * @apiUse InsertError
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function financial_post()
    {
		$alias 			= "financial";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
			);
			 
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['parttime','allowance','scholarship','other_income','restaurant','transportation','entertainment','other_expense'];
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
					$image 	= $this->s3_upload->image($_FILES,$field,$user_id,$alias);
					if($image){
						$content[$field] = $image;
					}else{
						$content[$field] = "";
					}
				}else{
					$content[$field] = "";
				}
			}
			
			$param['content'] = json_encode($content);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->certification_lib->set_success($insert);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/financial 認證 財務訊息認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {Number} parttime 打工收入
	 * @apiSuccess {Number} allowance 零用錢收入
	 * @apiSuccess {Number} scholarship 獎學金收入
	 * @apiSuccess {Number} other_income 其他收入
	 * @apiSuccess {Number} restaurant 餐飲支出
	 * @apiSuccess {Number} transportation 交通支出
	 * @apiSuccess {Number} entertainment 娛樂支出
	 * @apiSuccess {Number} other_expense 其他支出
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"certification_id": "8",
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432",
     *      	"parttime": 100,
     *      	"allowance": 200,
     *      	"scholarship": 300,
     *      	"other_income": 400,
     *      	"restaurant": 0,
     *      	"transportation": 1,
     *      	"entertainment": 2,
     *      	"other_expense": 3     
	 *      }
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function financial_get()
    {
		$alias 			= "financial";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields = ['parttime','allowance','scholarship','other_income','restaurant','transportation','entertainment','other_expense'];
				foreach ($fields as $field) {
					if (isset($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /certification/social 認證 社交認證
     * @apiGroup Certification
     * @apiParam {String="facebook"} type 認證類型
     * @apiParam {String} access_token access_token
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
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 502 此驗證已通過驗證
     * @apiErrorExample {json} 502
     *     {
     *       "result": "ERROR",
     *       "error": "502"
     *     }
	 *
     */
	public function social_post()
    {
		$alias 			= "social";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
				"investor"			=> $investor,
			);
			 
			//是否驗證過
			$where = array(
				"certification_id"	=> $certification->id,
				"expire_time >="	=> time(),
				"status"			=> array(0,1),
				"user_id"			=> $user_id,
				"investor"			=> $investor,
			);
			$user_certification = $this->user_certification_model->get_by($where);
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['type','access_token'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}
			
			$param['content'] = json_encode($content);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->certification_lib->set_success($insert);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/social 認證 社交認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} type 認證類型
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"certification_id": "8",
     *      	"status": "0",     
     *      	"created_at": "1518598432",     
     *      	"updated_at": "1518598432",
     *      	"parttime": 100,
     *      	"allowance": 200,
     *      	"scholarship": 300,
     *      	"other_income": 400,
     *      	"restaurant": 0,
     *      	"transportation": 1,
     *      	"entertainment": 2,
     *      	"other_expense": 3     
	 *      }
     *    }
	 *
	 * @apiUse TokenError
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {json} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
	 *
     * @apiError 503 尚未驗證過
     * @apiErrorExample {json} 503
     *     {
     *       "result": "ERROR",
     *       "error": "503"
     *     }
     */
	public function social_get()
    {
		$alias 			= "social";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id,$investor);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"expire_time"		=> $rs->expire_time,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields = ['type'];
				foreach ($fields as $field) {
					if (isset($content[$field])) {
						$data[$field] = $content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
}
