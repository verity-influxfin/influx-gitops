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
     * @apiError 303 新增時發生錯誤
     * @apiErrorExample {json} 303
     *     {
     *       "result": "ERROR",
     *       "error": "303"
     *     }
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
			if(isset($_FILES["front_image"]) && !empty($_FILES["front_image"])){
				$content["front_image"] = $this->s3_upload->image($_FILES,"front_image",$user_id,"healthcard");
			}else{
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}

			$user_uertification = $this->user_certification_model->get_by(array("certification_id"=>$certification->id,"status"=>1,"user_id"=>$user_id));
			if($user_uertification){
				$this->response(array('result' => 'ERROR',"error" => CERTIFICATION_WAS_VERIFY ));
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
	
}
