<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Product extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
		$this->load->model('product/product_model');
		$this->load->model('product/product_category_model');
		$this->load->model('platform/certification_model');
		$this->load->model('transaction/target_model');
		$this->load->library('Certification_lib');
		$this->load->library('Target_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['category'];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
			$nonCheckMethods = ['list'];
            if ((empty($tokenData->id) || empty($tokenData->phone)) && !in_array($method, $nonCheckMethods)) {
				$this->response(array('result' => 'ERROR',"error" => TOKEN_NOT_CORRECT ));
            }
			$this->user_info = $tokenData;
        }
    }
	
	/**
     * @api {get} /product/category 借款方 取得產品分類列表
     * @apiGroup Product
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} parent_id 父層級
	 * @apiSuccess {String} rank 排序
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"parent_id":"0",
     * 				"rank":"0"
     * 			},
     * 			{
     * 				"id":"2",
     * 				"name":"房屋方案",
     * 				"description":"房屋方案",
     * 				"parent_id":"0",
     * 				"rank":"0"
     * 			}
     * 			]
     * 		}
     * }
     */
	 
	public function category_get()
    {
		$category_list 	= $this->product_category_model->get_many_by(array("status"=>1));
		$list			= array();
		if(!empty($category_list)){
			foreach($category_list as $key => $value){
				$list[] = array(
					"id" 			=> $value->id,
					"name" 			=> $value->name,
					"description" 	=> $value->description,
					"parent_id" 	=> $value->parent_id,
					"rank"			=> $value->rank,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	/**
     * @api {get} /product/list 借款方 取得產品列表
     * @apiGroup Product
	 * @apiParam {number} category 產品分類ID
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} parent_id 父層產品
	 * @apiSuccess {String} rank 排序
	 * @apiSuccess {json} instalment 可申請期數
	 * @apiSuccess {json} repayment 可選還款方式
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {json} category 分類資訊
	 * @apiSuccess {json} target 申請資訊（未簽約）
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"category": {
     * 				"id": "1",
     * 				"name": "學生區",
     * 				"description": "學生區啊啊啊啊啊啊啊",
     * 				"parent_id": "0",
     * 				"rank": "0"
     * 			},
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"alias":"FA",
     * 				"description":"學生區",
     * 				"category":"1",
     * 				"parent_id":"0",
     * 				"rank":"0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
     * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
	 * 				"instalment": [
	  * 				{
     * 				      "name": "3期",
     * 				      "value": 3
     * 				    },
	  * 				{
     * 				      "name": "12期",
     * 				      "value": 12
     * 				    },
	  * 				{
     * 				      "name": "24期",
     * 				      "value": 24
     * 				    },
	 * 				],
	 * 				"repayment": [
	  * 				{
     * 				      "name": "等額本息",
     * 				      "value": 1
     * 				    }
	 * 				],
	 * 				"target":{
     * 					"id":"1",
     * 					"target_no": "1803269743",
     * 					"amount":"5000",
     * 					"loan_amount":"",
     * 					"status":"0",
     * 					"created_at":"1520421572"
     * 				}
     * 			}
     * 			]
     * 		}
     * }
     */
	 	
	public function list_get()
    {
		$instalment_list= $this->config->item('instalment');
		$repayment_type = $this->config->item('repayment_type');
		$input 			= $this->input->get();
		$data			= array();
		$list			= array();
		$where			= array( "status" => 1 );
		if(isset($input['category']) && intval($input['category'])){
			$where['category'] = intval($input['category']);
			$category 	= $this->product_category_model->get(intval($input['category']));
			if($category){
				$data['category'] = array(
					"id" 			=> $category->id,
					"name" 			=> $category->name,
					"description" 	=> $category->description,
					"parent_id" 	=> $category->parent_id,
					"rank"			=> $category->rank,
				);
			}
		}
		
		$product_list 	= $this->product_model->get_many_by($where);
		if(!empty($product_list)){
			foreach($product_list as $key => $value){
				$target = array();
				if(isset($this->user_info->id) && $this->user_info->id){
					$targets = $this->target_model->get_by(array("status <="=>1,"user_id"=>$this->user_info->id,"product_id"=>$value->id));
					if($targets){
						$target['id'] 			= $targets->id;
						$target['target_no'] 	= $targets->target_no;
						$target['status'] 		= $targets->status;
						$target['amount'] 		= $targets->amount;
						$target['loan_amount'] 	= $targets->loan_amount;
						$target['created_at'] 	= $targets->created_at;
					}
				}
				
				$instalment = json_decode($value->instalment,TRUE);
				foreach($instalment as $k => $v){
					$instalment[$k] = array("name"=>$instalment_list[$v],"value"=>$v);
				}
				
				$repayment = json_decode($value->repayment,TRUE);
				foreach($repayment as $k => $v){
					$repayment[$k] = array("name"=>$repayment_type[$v],"value"=>$v);
				}
				
				$list[] = array(
					"id" 					=> $value->id,
					"name" 					=> $value->name,
					"description" 			=> $value->description,
					"alias" 				=> $value->alias,
					"category"				=> $value->category,
					"parent_id"				=> $value->parent_id,
					"rank"					=> $value->rank,
					//"status"				=> $value->status,
					"loan_range_s"			=> $value->loan_range_s,
					"loan_range_e"			=> $value->loan_range_e,
					"interest_rate_s"		=> $value->interest_rate_s,
					"interest_rate_e"		=> $value->interest_rate_e,
					"charge_platform"		=> $value->charge_platform,
					"charge_platform_min"	=> $value->charge_platform_min,
					"instalment"			=> $instalment,
					"repayment"				=> $repayment,
					"target"				=> $target,
				);
			}
		}
		$data["list"] = $list;
		$this->response(array('result' => 'SUCCESS',"data" => $data ));
    }

	/**
     * @api {get} /product/info/{ID} 借款方 取得產品資訊
     * @apiGroup Product
	 * @apiParam {number} ID 產品ID
     *
     * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name 名稱
	 * @apiSuccess {String} description 簡介
	 * @apiSuccess {String} alias 產品
	 * @apiSuccess {String} category 分類ID
	 * @apiSuccess {String} parent_id 父層產品
	 * @apiSuccess {String} rank 排序
	 * @apiSuccess {String} loan_range_s 最低借款額度(元)
	 * @apiSuccess {String} loan_range_e 最高借款額度(元)
	 * @apiSuccess {String} interest_rate_s 年利率下限(%)
	 * @apiSuccess {String} interest_rate_e 年利率上限(%)
	 * @apiSuccess {String} charge_platform 平台服務費(%)
	 * @apiSuccess {String} charge_platform_min 平台最低服務費(元)	
	 * @apiSuccess {String} charge_overdue 逾期管理費(%/天)	
	 * @apiSuccess {String} charge_sub_loan 轉貸服務費(%)
	 * @apiSuccess {String} charge_prepayment 提還手續費(%)
	 * @apiSuccess {json} certifications 需完成的認證列表
	 * @apiSuccess {json} instalment 可申請期數
	 * @apiSuccess {json} target 申請資訊（未簽約）
	 * @apiSuccess {json} credit 信用評分
     * @apiSuccessExample {json} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"target":{
     * 				"id":"1",
     * 				"target_no": "1803269743",
     * 				"product_id":"2",
     * 				"user_id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
     * 				"interest_rate":"",
     * 				"total_interest":"",
     * 				"instalment":"3",
     * 				"bank_code":"",
     * 				"branch_code":"",
     * 				"bank_account":"",
     * 				"virtual_account":"",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"created_at":"1520421572"
     * 			}
     * 			"credit":{
     * 				"level":"1",
     * 				"points":"1985",
     * 				"created_at":"1520421572"
     * 			}
     * 			"product":
     * 			{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"alias":"FT",
     * 				"category":"3",
     * 				"parent_id":"0",
     * 				"rank":"0",
     * 				"loan_range_s":"12222",
     * 				"loan_range_e":"14333333",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"14",
     * 				"charge_platform":"0",
     * 				"charge_platform_min":"0",
     * 				"charge_overdue":"0",
     * 				"charge_sub_loan":"0",
     * 				"charge_prepayment":"0",
     * 				"certifications":[{"id":"1","name":"身分證認證","description":"身分證認證","alias":"id_card","user_status":1},{"id":"2","name":"學生證認證","description":"學生證認證","alias":"student","user_status":1}],
	 * 				"instalment": [
	  * 				{
     * 				      "name": "3期",
     * 				      "value": 3
     * 				    },
	  * 				{
     * 				      "name": "12期",
     * 				      "value": 12
     * 				    },
	  * 				{
     * 				      "name": "24期",
     * 				      "value": 24
     * 				    },
	 * 				],
	 * 				"repayment": [
	  * 				{
     * 				      "name": "等額本息",
     * 				      "value": 1
     * 				    }
	 * 				],
     * 			}
     * 		}
     * }
	 *
	 * @apiUse TokenError
	 *
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
	 *
	 * @apiError 408 未完成預先申請
     * @apiErrorExample {json} 408
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     */
	 
	public function info_get($id)
    {
		if($id){
			$data			= array('target'=>array());
			$product 		= $this->product_model->get(intval($id));
			$user_id 		= $this->user_info->id;
			$instalment_list= $this->config->item('instalment');
			$repayment_type = $this->config->item('repayment_type');
			if($product && $product->status == 1 ){
				$product->certifications 	= json_decode($product->certifications,TRUE);
				$certification				= array();
				$certification_list			= $this->certification_lib->get_status($user_id);
				if(!empty($certification_list)){
					foreach($certification_list as $key => $value){
						if(in_array($value->id,$product->certifications)){
							$certification[] = array(
								"id" 			=> $value->id,
								"name" 			=> $value->name,
								"description" 	=> $value->description,
								"alias" 		=> $value->alias,
								"user_status" 	=> $value->user_status,
							);
						}
					}
				}
				
				$target = $this->target_model->get_by(array("status <="=>1,"user_id"=>$user_id,"product_id"=>$product->id));
				if($target){
					$fields = $this->target_model->detail_fields;
					foreach($fields as $field){
						$data['target'][$field] = isset($target->$field)?$target->$field:"";
						if($field=="instalment"){
							$data['target'][$field] = $instalment_list[$target->$field];
						}
						
						if($field=="repayment"){
							$data['target'][$field] = $repayment_type[$target->$field];
						}
					}
					$data['target']['repayment_plan'] = $this->target_lib->get_repayment_plan($target->loan_amount,$target->interest_rate,$target->instalment);
				}
				
				$this->load->library('credit_lib',array("user_id"=>$user_id,"product_id"=>$product->id));
				$credit = $this->credit_lib->get_credit();
				if($credit){
					$data['credit'] = $credit;
				}else{
					$data['credit'] = array();
				}
				
				$instalment = json_decode($product->instalment,TRUE);
				foreach($instalment as $k => $v){
					$instalment[$k] = array("name"=>$instalment_list[$v],"value"=>$v);
				}
				
				$repayment = json_decode($product->repayment,TRUE);
				foreach($repayment as $k => $v){
					$repayment[$k] = array("name"=>$repayment_type[$v],"value"=>$v);
				}
				
			
				$data['product'] = array(
					"id" 					=> $product->id,
					"name" 					=> $product->name,
					"description" 			=> $product->description,
					"alias" 				=> $product->alias,
					"category"				=> $product->category,
					"parent_id"				=> $product->parent_id,
					"rank"					=> $product->rank,
					"loan_range_s"			=> $product->loan_range_s,
					"loan_range_e"			=> $product->loan_range_e,
					"interest_rate_s"		=> $product->interest_rate_s,
					"interest_rate_e"		=> $product->interest_rate_e,
					"charge_platform"		=> $product->charge_platform,
					"charge_platform_min"	=> $product->charge_platform_min,
					"charge_overdue"		=> $product->charge_overdue,
					"charge_sub_loan"		=> $product->charge_sub_loan,
					"charge_prepayment"		=> $product->charge_prepayment,
					"certifications"		=> $certification,
					"instalment"			=> $instalment,
					"repayment"				=> $repayment,
				);
				$this->response(array('result' => 'SUCCESS',"data" => $data ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
    }
	
	/**
     * @api {post} /product/apply 借款方 申請產品
     * @apiGroup Product
	 * @apiParam {number} product_id (required) 產品ID
     * @apiParam {number} amount (required) 借款金額
     * @apiParam {number} instalment (required) 申請期數
     * @apiParam {String} emergency_contact (required) 緊急聯絡人
     * @apiParam {String} emergency_phone (required) 緊急聯絡人電話
     * @apiParam {String} emergency_relationship (required) 緊急聯絡人關係
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
     *
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
	 *
     * @apiError 402 超過此產品可申請額度
     * @apiErrorExample {json} 402
     *     {
     *       "result": "ERROR",
     *       "error": "402"
     *     }
	 *
     * @apiError 403 不支援此期數
     * @apiErrorExample {json} 403
     *     {
     *       "result": "ERROR",
     *       "error": "403"
     *     }
	 *
     * @apiError 408 重複申請
     * @apiErrorExample {json} 408
     *     {
     *       "result": "ERROR",
     *       "error": "408"
     *     }
	 *
     */
	public function apply_post()
    {
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("user_id"=> $user_id);
		
		//必填欄位
		$fields 	= ['product_id','amount','instalment'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}

		//必填欄位
		$fields 	= ['emergency_contact','emergency_phone','emergency_relationship'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = trim($input[$field]);
			}
		}

		$product = $this->product_model->get($input['product_id']);
		if($product && $product->status == 1 ){
			$product->instalment 		= json_decode($product->instalment,TRUE);
			if(!in_array($input['instalment'],$product->instalment)){
				$this->response(array('result' => 'ERROR',"error" => PRODUCT_INSTALMENT_ERROR ));
			}
			
			if($input['amount']<$product->loan_range_s || $input['amount']>$product->loan_range_e){
				$this->response(array('result' => 'ERROR',"error" => PRODUCT_AMOUNT_RANGE ));
			}

			$target = $this->target_model->get_by(array("status <="=>1,"user_id"=>$user_id,"product_id"=>$product->id));
			if($target){
				$this->response(array('result' => 'ERROR',"error" => APPLY_EXIST ));
			}
			$param["target_no"] = $this->get_target_no();
			$insert = $this->target_model->insert($param);
			if($insert){
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$param["target_no"] = $this->get_target_no();
				$insert = $this->target_model->insert($param);
				if($insert){
					$this->response(array('result' => 'SUCCESS'));
				}
				$this->response(array('result' => 'ERROR',"error" => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
    }

	/**
     * @api {post} /product/signing 借款方 申請簽約
     * @apiGroup Product
	 * @apiParam {number} target_id (required) 產品ID
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
	 * @apiError 401 產品不存在
     * @apiErrorExample {json} 401
     *     {
     *       "result": "ERROR",
     *       "error": "401"
     *     }
     *
     * @apiError 404 此申請不存在
     * @apiErrorExample {json} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {json} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {json} 406
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     * @apiError 302 會員不存在
     * @apiErrorExample {json} 302
     *     {
     *       "result": "ERROR",
     *       "error": "302"
     *     }
	 *
     * @apiError 202 未通過所需的驗證
     * @apiErrorExample {json} 202
     *     {
     *       "result": "ERROR",
     *       "error": "202"
     *     }
	 *
     * @apiError 203 未綁定金融帳號
     * @apiErrorExample {json} 203
     *     {
     *       "result": "ERROR",
     *       "error": "203"
     *     }
	 *
     * @apiError 206 人臉辨識不通過
     * @apiErrorExample {json} 206
     *     {
     *       "result": "ERROR",
     *       "error": "206"
     *     }
	 *
     */
	public function signing_post()
    {
		$this->load->library('S3_upload');
		$this->load->model('user/user_model');
		$this->load->model('user/user_bankaccount_model');
		$this->load->library('Certification_lib');
		$input 		= $this->input->post(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$param		= array("status"=>2);
		
		//必填欄位
		$fields 	= ['target_id'];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}else{
				$param[$field] = intval($input[$field]);
			}
		}
		
		//上傳檔案欄位
		$file_fields 	= ['person_image'];
		foreach ($file_fields as $field) {
			if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
				$image 	= $this->s3_upload->image($_FILES,$field,$user_id,'signing_target');
				if($image){
					$param[$field] = $image;
				}else{
					$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
				}
			}else{
				$this->response(array('result' => 'ERROR',"error" => INPUT_NOT_CORRECT ));
			}
		}
		
		$targets 	= $this->target_model->get($param['target_id']);
		if(!empty($targets)){

			if($targets->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}
			
			$product = $this->product_model->get($targets->product_id);
			if($product && $product->status == 1 ){

				$user_info = $this->user_model->get($user_id);	
				if($user_info){
					//檢查認證 NOT_VERIFIED
					$product->certifications 	= json_decode($product->certifications,TRUE);
					$certification_list	= $this->certification_lib->get_status($user_id);
					foreach($certification_list as $key => $value){
						if(in_array($value->id,$product->certifications) && $value->user_status!=1){
							$this->response(array('result' => 'ERROR',"error" => NOT_VERIFIED ));
						}
					}
				}else{
					$this->response(array('result' => 'ERROR',"error" => USER_NOT_EXIST ));
				}
				
				//檢查金融卡綁定 NO_BANK_ACCOUNT
				$bank_account = $this->user_bankaccount_model->get_by(array("status"=>1,"user_id"=>$user_id ));
				if(!$bank_account){
					$this->response(array('result' => 'ERROR',"error" => NO_BANK_ACCOUNT ));
				}
				
				if($targets->status == 1){
					unset($param['target_id']);
					$rs = $this->target_model->update($targets->id,$param);
					$this->response(array('result' => 'SUCCESS'));
				}else{
					$this->response(array('result' => 'ERROR',"error" => APPLY_STATUS_ERROR ));
				}
			}
			$this->response(array('result' => 'ERROR',"error" => PRODUCT_NOT_EXIST ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /product/applylist 借款方 申請紀錄列表
     * @apiGroup Product
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Targets ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請額度
	 * @apiSuccess {String} loan_amount 核可額度
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期
     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id":"1",
     * 				"target_no": "1803269743",
     * 				"product_id":"2",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"user_id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
     * 				"interest_rate":"0,
     * 				"total_interest":"",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"created_at":"1520421572"
     * 			},
     * 			{
     * 				"id":"2",
     * 				"target_no": "1803269713",
     * 				"product_id":"2",
     * 				"product":{
     * 					"id":"2",
     * 					"name":"輕鬆學貸",
     * 					"description":"輕鬆學貸",
     * 					"alias":"FA"
     * 				},
     * 				"user_id":"1",
     * 				"amount":"5000",
     * 				"loan_amount":"",
     * 				"interest_rate":"",
     * 				"total_interest":"",
     * 				"instalment":"3期",
     * 				"repayment":"等額本息",
     * 				"remark":"",
     * 				"delay":"0",
     * 				"status":"0",
     * 				"created_at":"1520421572"
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
     *
     */
	public function applylist_get()
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$param				= array( "user_id"=> $user_id);
		$targets 			= $this->target_model->get_many_by($param);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$list				= array();
		if(!empty($targets)){
			foreach($targets as $key => $value){
				$product_info = $this->product_model->get($value->product_id);
				$product = array(
					"id"			=> $product_info->id,
					"name"			=> $product_info->name,
					"description"	=> $product_info->description,
					"alias"			=> $product_info->alias,
				);
				
				$list[] = array(
					"id" 				=> $value->id,
					"target_no" 		=> $value->target_no,
					"product_id" 		=> $value->product_id,
					"product" 			=> $product,
					"user_id" 			=> $value->user_id,
					"amount" 			=> $value->amount,
					"loan_amount" 		=> $value->loan_amount?$value->loan_amount:"",
					"interest_rate" 	=> $value->interest_rate?$value->interest_rate:"",
					"total_interest" 	=> $value->total_interest?$value->total_interest:"",
					"instalment" 		=> $instalment_list[$value->instalment],
					"repayment" 		=> $repayment_type[$value->repayment],
					"contract" 			=> $value->contract,
					"remark" 			=> $value->remark,
					"delay" 			=> $value->delay,
					"status" 			=> $value->status,
					"created_at" 		=> $value->created_at,
				);
			}
		}
		$this->response(array('result' => 'SUCCESS',"data" => array("list" => $list) ));
    }
	
	/**
     * @api {get} /product/applyinfo/{ID} 借款方 申請紀錄資訊
     * @apiGroup Product
	 * @apiParam {number} ID Targets ID
	 * 
	 * @apiSuccess {json} result SUCCESS
	 * @apiSuccess {String} id Target ID
	 * @apiSuccess {String} target_no 案號
	 * @apiSuccess {String} product_id Product ID
	 * @apiSuccess {json} product 產品資訊
	 * @apiSuccess {String} user_id User ID
	 * @apiSuccess {String} amount 申請額度
	 * @apiSuccess {String} loan_amount 核可額度
	 * @apiSuccess {String} interest_rate 核可利率
	 * @apiSuccess {String} total_interest 總利息
	 * @apiSuccess {String} instalment 期數
	 * @apiSuccess {String} repayment 還款方式
	 * @apiSuccess {String} repayment_plan 還款計畫
	 * @apiSuccess {String} bank_code 借款人收款銀行代碼
	 * @apiSuccess {String} branch_code 借款人收款分行代碼
	 * @apiSuccess {String} bank_account 借款人收款帳號
	 * @apiSuccess {String} virtual_account 還款虛擬帳號
	 * @apiSuccess {String} remark 備註
	 * @apiSuccess {String} delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {String} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {String} created_at 申請日期

     * @apiSuccessExample {json} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"id":"1",
     * 			"target_no": "1803269743",
     * 			"product_id":"2",
     * 			"product":{
     * 				"id":"2",
     * 				"name":"輕鬆學貸",
     * 				"description":"輕鬆學貸",
     * 				"alias":"FA"
     * 			},
     * 			"user_id":"1",
     * 			"amount":"5000",
     * 			"loan_amount":"",
     * 			"interest_rate":"",
     * 			"total_interest":"",
     * 			"instalment":"3期",
     * 			"repayment":"等額本息",
     * 			"bank_code":"",
     * 			"branch_code":"",
     * 			"bank_account":"",
     * 			"virtual_account":"",
     * 			"remark":"",
     * 			"delay":"0",
     * 			"status":"0",
     * 			"created_at":"1520421572"
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {json} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {json} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
     */
	public function applyinfo_get($target_id)
    {
		$input 				= $this->input->get(NULL, TRUE);
		$user_id 			= $this->user_info->id;
		$target 			= $this->target_model->get($target_id);
		$instalment_list 	= $this->config->item('instalment');
		$repayment_type 	= $this->config->item('repayment_type');
		$data				= array();
		if(!empty($target)){
			if($target->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}

			$product_info = $this->product_model->get($target->product_id);
			$product = array(
				"id"			=> $product_info->id,
				"name"			=> $product_info->name,
				"description"	=> $product_info->description,
				"alias"			=> $product_info->alias,
			);
			
			$fields = $this->target_model->detail_fields;
			foreach($fields as $field){
				$data[$field] = isset($target->$field)?$target->$field:"";
				if($field=="instalment"){
					$data[$field] = $instalment_list[$target->$field];
				}
				
				if($field=="repayment"){
					$data[$field] = $repayment_type[$target->$field];
				}
			}
			$data["repayment_plan"] = $this->target_lib->get_repayment_plan($target->loan_amount,$target->interest_rate,$target->instalment);
			$data["product"] = $product;

			$this->response(array('result' => 'SUCCESS',"data" => $data ));
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	/**
     * @api {get} /product/cancel/{ID} 借款方 取消申請
     * @apiGroup Product
	 * @apiParam {number} id (required) Targets ID
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
	 *
     * @apiError 404 此申請不存在
     * @apiErrorExample {json} 404
     *     {
     *       "result": "ERROR",
     *       "error": "404"
     *     }
	 *
     * @apiError 405 對此申請無權限
     * @apiErrorExample {json} 405
     *     {
     *       "result": "ERROR",
     *       "error": "405"
     *     }
	 *
     * @apiError 407 目前狀態無法完成此動作
     * @apiErrorExample {json} 406
     *     {
     *       "result": "ERROR",
     *       "error": "407"
     *     }
	 *
     */
	public function cancel_get($target_id)
    {
		$input 		= $this->input->get(NULL, TRUE);
		$user_id 	= $this->user_info->id;
		$targets 	= $this->target_model->get($target_id);
		if(!empty($targets)){

			if($targets->user_id != $user_id){
				$this->response(array('result' => 'ERROR',"error" => APPLY_NO_PERMISSION ));
			}

			if(in_array($targets->status,array(0,1,2))){
				$rs = $this->target_model->update($targets->id,array("status"=>8));
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR',"error" => APPLY_STATUS_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR',"error" => APPLY_NOT_EXIST ));
    }
	
	private function get_target_no(){
		$code = date("ymd").rand(0, 9).rand(0, 9).rand(0, 9).rand(1, 9);
		$result = $this->target_model->get_by('target_no',$code);
		if ($result) {
			return $this->get_target_no();
		}else{
			return $code;
		}
	}
}
