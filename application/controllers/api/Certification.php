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
		$this->load->library('S3_upload');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone)) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			$this->user_info = $tokenData;
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

     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"身分證認證",
     * 				"description":"身分證認證",
     * 				"alias":"id_card",
     * 			},
     * 			{
     * 				"id":"2",
     * 				"name":"健保卡認證",
     * 				"description":"健保卡認證",
     * 				"alias":"health_card",
     * 			}
     * 			]
     * 		}
     * }
     */
	 
	public function list_get()
    {
		$certification_list 	= $this->certification_model->get_many_by(array("status"=>1));
		$list					= array();
		if(!empty($certification_list)){
			foreach($certification_list as $key => $value){
				$list[] = array(
					"id" 			=> $value->id,
					"name" 			=> $value->name,
					"description" 	=> $value->description,
					"alias" 		=> $value->alias,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }

	
	/**
     * @api {post} /certification/idcard 認證 實名認證
     * @apiGroup Certification
     * @apiParam {String} name (required) 姓名
     * @apiParam {String} id_number (required) 身分證字號
     * @apiParam {String} id_card_date (required) 發證日期(民國) ex:1060707
     * @apiParam {String} id_card_place (required) 發證地點
     * @apiParam {String} birthday (required) 生日(民國) ex:1020101
     * @apiParam {String} address (required) 地址
     * @apiParam {file} front_image (required) 身分證正面照
     * @apiParam {file} back_image (required) 身分證背面照
     * @apiParam {file} person_image (required) 本人照
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
     */
	public function idcard_post()
    {
		$alias 			= "id_card";
		$certification 	= $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
			);
			
			//是否驗證過
			$user_certification = $this->user_certification_model->get_by(array("certification_id"=>$certification->id,"status"=>1,"user_id"=>$user_id));
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
			
			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image','person_image'];
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
				$this->load->library('Certification_lib');
				//TODO 串接身分證識別去給status
				$this->certification_lib->id_card_verify($user_id,$certification->id);
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
	 * @apiSuccess {String} name (required) 姓名
     * @apiSuccess {String} id_number (required) 身分證字號
     * @apiSuccess {String} id_card_date (required) 發證日期(民國) ex:1060707
     * @apiSuccess {String} id_card_place (required) 發證地點
     * @apiSuccess {String} birthday (required) 生日(民國) ex:1020101
     * @apiSuccess {String} address (required) 地址
	 * @apiSuccess {String} front_image 身分證正面照
	 * @apiSuccess {String} back_image 身分證背面照
	 * @apiSuccess {String} person_image 本人照
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"certification_id": "3",
     *      	"front_image": "https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg",    
     *      	"back_image": "https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg",    
     *      	"person_image": "https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg",    
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
		$alias 		= "id_card";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$this->load->library('Certification_lib');
			$user_id 	= $this->user_info->id;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['name','id_number','id_card_date','id_card_place','birthday','address','front_image','back_image','person_image'];
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
     * @api {post} /certification/healthcard 認證 健保卡認證
     * @apiGroup Certification
     * @apiParam {file} front_image (required) 健保卡正面照
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
	public function healthcard_post()
    {
		$alias 		= "health_card";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
			);
			
			//是否驗證過
			$user_certification = $this->user_certification_model->get_by(array("certification_id"=>$certification->id,"status"=>1,"user_id"=>$user_id));
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//上傳檔案
			if(isset($_FILES["front_image"]) && !empty($_FILES["front_image"])){
				$content["front_image"] = $this->s3_upload->image($_FILES,"front_image",$user_id,$alias);
			}else{
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}
			
			$param['content'] = json_encode($content);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('Certification_lib');
				//TODO 串接健保卡識別去給status
				$this->certification_lib->health_card_verify($user_id,$certification->id);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/healthcard 認證 健保卡認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} front_image 健保卡正面照
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"user_id": "1",
     *      	"certification_id": "3",
     *      	"front_image": "https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg",    
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
	public function healthcard_get()
    {
		$alias 		= "health_card";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status){
			$this->load->library('Certification_lib');
			$user_id 	= $this->user_info->id;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"front_image"		=> $content['front_image'],
					"status"			=> $rs->status,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$this->response(array('result' => 'SUCCESS',"data" => $data));
			}
			$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NEVER_VERIFY ));
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {post} /certification/student 認證 學生證認證
     * @apiGroup Certification
	 * @apiParam {String} school (required) 學校名稱
	 * @apiParam {String} department (required) 系所
	 * @apiParam {String} student_id (required) 學號
     * @apiParam {file} front_image (required) 學生證正面照
     * @apiParam {file} back_image (required) 學生證背面照
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
	public function student_post()
    {
		$alias 		= "student";
		$certification = $this->certification_model->get_by(array("alias"=>$alias));
		if($certification && $certification->status==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$content	= array();
			$param		= array(
				"user_id"			=> $user_id,
				"certification_id"	=> $certification->id,
			);
			
			//是否驗證過
			$user_certification = $this->user_certification_model->get_by(array("certification_id"=>$certification->id,"status"=>1,"user_id"=>$user_id));
			if($user_certification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
			}
			
			//必填欄位
			$fields 	= ['school','department','student_id'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
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
				$this->load->library('Certification_lib');
				//TODO 串接學生證認證去給status
				$this->certification_lib->student_verify($user_id,$certification->id);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_NOT_ACTIVE ));
    }
	
	/**
     * @api {get} /certification/student 認證 學生證認證資料
     * @apiGroup Certification
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} certification_id Certification ID
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
     *      	"student_id": "1496B032",
     *      	"front_image": "https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg",    
     *      	"back_image": "https://influxp2p.s3.amazonaws.com/dev/image/img15185984312.jpg",    
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
			$this->load->library('Certification_lib');
			$user_id 	= $this->user_info->id;
			$data		= array();
			$rs			= $this->certification_lib->get_certification_info($user_id,$certification->id);
			if($rs){
				$content = $rs->content;
				$data = array(
					"user_id"			=> $rs->user_id,
					"certification_id"	=> $rs->certification_id,
					"status"			=> $rs->status,
					"created_at"		=> $rs->created_at,
					"updated_at"		=> $rs->updated_at,
				);
				$fields 	= ['school','department','student_id','front_image','back_image'];
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
}
