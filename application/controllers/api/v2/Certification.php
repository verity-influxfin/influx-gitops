<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

use Certification\Certification_factory;

class Certification extends REST_Controller {

	public $user_info,$certification;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('user/user_certification_model');
		$this->load->model('log/log_image_model');
        $this->load->library('Notification_lib');
		$this->load->library('Certification_lib');
        $method 				= $this->router->fetch_method();
		$this->certification 	= $this->config->item('certifications');
        $nonAuthMethods 		= ['verifyemail','cerjudicial'];
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

            $this->user_info->originalID = $tokenData->id;
            $this->user_info->investor 		= $tokenData->investor;
            $this->user_info->company 		= $tokenData->company;
            $this->user_info->incharge 		= $tokenData->incharge;
            $this->user_info->agent 		= $tokenData->agent;
            $this->user_info->expiry_time 	= $tokenData->expiry_time;

			if(isset($tokenData->company) && $tokenData->company != 0){
                $this->load->library('Judicialperson_lib');
                $this->user_info->naturalPerson = $this->judicialperson_lib->getNaturalPerson($tokenData->id);
                if($this->user_info->naturalPerson && $this->request->method == 'post'){
                    $this->load->library('certification_lib');
                    //檢核變卡認證，並排除以下認證
                    if(!in_array($method, ['governmentauthorities','identity','debitcard','email','investigation','profile','simplificationfinancial','simplificationjob','investigationa11','livingBody'])){
                        $cerGovernmentauthorities = $this->certification_lib->get_certification_info($tokenData->id, CERTIFICATION_GOVERNMENTAUTHORITIES, $this->user_info->investor);
                        if(!$cerGovernmentauthorities && $method != 'governmentauthorities'){
                            $this->response(array('result' => 'ERROR','error' => NO_CER_GOVERNMENTAUTHORITIES ));
                        }
                    }
                    //要求先完成實名相關
                    if(!in_array($method, ['identity','debitcard','email','financial','diploma','investigation','job','investigationa11','financialWorker','livingBody'])){
                        $cerIDENTITY = $this->certification_lib->get_certification_info($this->user_info->naturalPerson->id, CERTIFICATION_IDENTITY, 0);
                        if(!$cerIDENTITY){
                            $this->response(array('result' => 'ERROR','error' => NO_CER_IDENTITY ));
                        }
                    }
//                elseif(!in_array($method,['debitcard','list','social','investigation','businesstax','balancesheet','incomestatement','investigationjudicial','passbookcashflow','salesdetail','governmentauthorities','charter','registerofmembers','mainproductstatus','startupfunds','business_plan','verification','condensedbalancesheet','condensedincomestatement','purchasesalesvendorlist','employeeinsurancelist','companyemail'])){
//                    $this->response(array('result' => 'ERROR','error' => IS_COMPANY ));
//                }

                }
			}
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
	 * @apiSuccess {String} alias 認證代號
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
	 * @apiUse NotIncharge
     */

	public function list_get()
    {
		$user_id 			= $this->user_info->id;
		$investor 			= $this->user_info->investor;
		$company 			= $this->user_info->company;
        $incharge           = $this->user_info->incharge;
        $certification_list = $this->certification_lib->get_status($user_id, $investor, $company);
		$list				= array();
		if(!empty($certification_list)){
		    $sort = $this->config->item('certifications_sort');
		    $new_list = [];
		    foreach ($sort as $key => $value){
                if(isset($certification_list[$value])
                    && (
                        $company == 0 && $value < CERTIFICATION_FOR_JUDICIAL
                        || $company == 1 && $value >= CERTIFICATION_FOR_JUDICIAL
                    )
                ){
                    count($certification_list[$value]['optional']) == 0 ? $certification_list[$value]['optional'] = false : '';
                    $new_list[$value] = $certification_list[$value];
                }
            }
			$list = $new_list;
		}

		//讓代理人傳空值
        if($company==1&&$incharge==0&&$this->user_info->name!=null) {
            $list = array();
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
	 * @apiSuccess {String} alias 認證代號
	 * @apiSuccess {String} certification_id Certification ID
	 * @apiSuccess {String} status 狀態 0:等待驗證 1:驗證成功 2:驗證失敗 3:待人工驗證
	 * @apiSuccess {String} created_at 創建日期
	 * @apiSuccess {String} updated_at 最近更新日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"alias": "debitcard",
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
	 * @apiUse NotIncharge
     *
     * @apiError 501 此驗證尚未啟用
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
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
        $data	    = [];
        $company_type = false;
		if($certification && $certification['status']==1){
            $user_id    = $this->user_info->id;
            $investor 	= $this->user_info->investor;
            if(isset($this->user_info->naturalPerson)){
                $certification['id'] < 1000 ? $user_id = $this->user_info->naturalPerson->id :'';
            }
            // if($this->user_info->company && $certification['id'] == 9){
            //     $this->load->model('user/judicial_person_model');
            //     $judicial_person = $this->judicial_person_model->get_by(array(
            //         'company_user_id' 	=> $user_id,
            //     ));
            //     $user_id = $judicial_person->user_id;
            // }

            $rs = $this->certification_lib->get_certification_info($user_id, $certification['id'], $investor);
            if($rs){
				$data = array(
					'alias'				=> $alias,
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
						$line_exist = $this->user_meta_model->get_by(array(
							'user_id'  => $user_id,
							'meta_key' => 'line_access_token'
						));
						$line_bind = $line_exist ? 1 : 0;
						if (isset($rs->content['type'])) {
							if ($rs->content['type'] == 'instagram') {
								$ig_exist = 1;
							}
                            if ($rs->content['type'] == 'facebook') {
								$fb_exist = 1;
							}
                        }
                        if (isset($rs->content['facebook']['name'])) {
							$fb_exist = 1;
						}
                        if (isset($rs->content['instagram'])&&$rs->content['instagram'] != null) {
							$ig_exist = 1;
						}
                        $fb_bind = isset($fb_exist) ? 1 : 0;
						$ig_bind =  isset($ig_exist) ? 1 : 0;
						$fields 	= [];
						$data['line_bind'] = $line_bind;
						$data['fb_bind'] = $fb_bind;
						$data['ig_bind'] = $ig_bind;
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
						$fields 	= ['school','major','department','system'];
						break;
					case 9:
						$fields 	= ['return_type'];
						break;
					case 10:
						$fields 	= ['tax_id','company','company_address','company_phone_number','industry','employee','position','type','seniority','job_seniority','salary'];
						break;
					case 11:
                        $fields 	= [];
						break;
					case 12:
                        $fields 	= ['investigationa11', 'return_type'];
						break;
                    case 14:
                        $fields 	= [];
						break;
                    case CERTIFICATION_SOCIAL_INTELLIGENT:
                        if (isset($rs->content['type']))
                        {
                            if ($rs->content['type'] == 'instagram')
                            {
                                $ig_exist = 1;
                            }
                        }
                        if (isset($rs->content['instagram']) && $rs->content['instagram'] != NULL)
                        {
                            $ig_exist = 1;
                        }
                        $ig_bind = isset($ig_exist) ? 1 : 0;
                        $fields = [];
                        $data['ig_bind'] = $ig_bind;
                        break;
					case 500:
                        $fields 	= [];
						break;
					case 501:
                        $fields 	= [];
						break;
					case 1000:
                        $fields 	= ['businesstax'];
						break;
					case 1001:
						$fields 	= ['balancesheet'];
						break;
					case 1002:
						$fields 	= ['incomestatement'];
						break;
					case 1003:
						$fields 	= ['return_type'];
						break;
					case 1004:
						$fields 	= ['passbookcashflow'];
						break;
					case 1007:
						$fields 	= ['governmentauthorities'];
						break;
					case 1008:
						$fields 	= ['charter'];
						break;
					case 1009:
						$fields 	= ['registerofmembers'];
						break;
					case 1010:
						$fields 	= ['mainproductstatus'];
						break;
					case 1011:
						$fields 	= ['startupfunds'];
						break;
					case 1012:
						$fields 	= ['business_plan'];
						break;
					case 1013:
						$fields 	= ['verification'];
						break;
					case 1014:
						$fields 	= ['condensedbalancesheet'];
						break;
					case 1015:
						$fields 	= ['condensedincomestatement'];
						break;
					case 1016:
						$fields 	= ['purchasesalesvendorlist'];
						break;
					case 1017:
						$fields 	= ['employeeinsurancelist'];
						break;
					case 1018:
						$fields 	= [];
						break;
					case 1019:
						$fields 	= ['email'];
						break;
					case 1020:
						$fields 	= [];
						break;
					case 2000:
						$fields 	= ['salesdetail'];
						break;
					default:
						break;
				}

				if($data['status'] == 4){
				    unset($rs->content['save']);
                    $this->response(array('result' => 'SUCCESS','data' => $rs->content));
                }

				foreach ($fields as $field) {
					if (isset($rs->content[$field]) && !empty($rs->content[$field])) {
						$data[$field] = $rs->content[$field];
					}
				}
				$this->response(array('result' => 'SUCCESS','data' => $data));
			}
            if($certification['id'] == 4){
				isset($line_bind)? $data['line_bind']: null;
				isset($ig_bind )?$data['ig_bind']: null;
				isset($fb_bind )?$data['fb_bind']: null;
				empty($data)
					? $this->response(array('result' => 'SUCCESS','data' => (object) null))
					: $this->response(array('result' => 'SUCCESS','data' => $data));
			}
			$this->response(array('result' => 'SUCCESS','data' => $data));
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	/**
     * @api {post} /v2/certification/identity 認證 實名認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationIdentity
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String{2..15}} name 姓名
     * @apiParam {String} id_number 身分證字號
     * @apiParam {String} id_card_date 發證日期(民國) ex:1060707
     * @apiParam {String} id_card_place 發證地點
     * @apiParam {String} birthday 生日(民國) ex:1020101
     * @apiParam {String} address 地址
     * @apiParam {Number} front_image 身分證正面照 ( 圖片ID )
     * @apiParam {Number} back_image 身分證背面照 ( 圖片ID )
     * @apiParam {Number} person_image 本人照 ( 圖片ID )
     * @apiParam {Number} healthcard_image 健保卡照 ( 圖片ID )
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
	public function identity_post()
    {
		$certification_id 	= 1;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			//必填欄位
			$fields 	= ['id_number','id_card_date','id_card_place','birthday','name','address'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}

            $content['id_card_date'] = strip_ROC_date_word($content['id_card_date']);
            $content['birthday'] = strip_ROC_date_word($content['birthday']);

            $content['name'] 	= isset($input['name'])?$input['name']:"";
            $content['address'] = isset($input['address'])?$input['address']:"";

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
				$image_id = intval($input[$field]);
				if (!$image_id) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$rs = $this->log_image_model->get_by([
						'id'		=> $image_id,
						'user_id'	=> $this->user_info->originalID,
					]);

					if($rs){
						$content[$field] = $rs->url;
						$content[$field."_id"] = $image_id;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}
			}

			// 使用者手填資料
			$content['SpouseName'] = isset($input['SpouseName']) ? $input['SpouseName'] : '';

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert)
            {
                $this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    /**
     * @api {get} /v2/certification/identity 認證 實名認證
     * @apiVersion 0.2.0
     * @apiName GetCertificationIdentity
     * @apiGroup Certification
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String{2..15}} name 姓名
     * @apiParam {String} id_number 身分證字號
     * @apiParam {String} id_card_date 發證日期(民國) ex:1060707
     * @apiParam {String} id_card_place 發證地點
     * @apiParam {String} birthday 生日(民國) ex:1020101
     * @apiParam {String} address 地址
     * @apiParam {Number} front_image 身分證正面照 ( 圖片ID )
     * @apiParam {Number} back_image 身分證背面照 ( 圖片ID )
     * @apiParam {Number} person_image 本人照 ( 圖片ID )
     * @apiParam {Number} healthcard_image 健保卡照 ( 圖片ID )
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
     */
    public function identity_get()
    {
        $certification_id 	= 1;
        $certification 		= $this->certification[$certification_id];
        $return_column_list = ['gender','id_number','id_card_date','id_card_place','issueType','birthday','name','front_image_id','front_image',
                'father', 'mother', 'spouse', 'military_service', 'born', 'address','back_image_id','back_image',
                'person_image_id','person_image',
                'healthcard_name', 'healthcard_birthday', 'healthcard_id_number', 'healthcard_image_id', 'healthcard_image'];
        $empty_check_list = ['id_card_date', 'birthday'];
        $data = array_combine(array_values($return_column_list), array_fill(0, count($return_column_list), ''));
        if($certification && $certification['status']==1){
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;

            $param = array(
                'user_id'			=> $user_id,
                'certification_id'	=> CERTIFICATION_IDENTITY,
                'investor'			=> $investor,
            );

            $last_certification = $this->user_certification_model->order_by('created_at','desc')->get_by($param);
            $data['status'] = (int)($last_certification->status ?? 0);
            $certification = $this->user_certification_model->order_by('created_at','desc')->get_by(
                array_merge($param, ['status' => [CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_FAILED]])
            );
            if($certification) {
                $content = json_decode($certification->content, TRUE);
                $remark = json_decode($certification->remark, TRUE);
                if(isset($remark['OCR']) && is_array($remark['OCR']))
                    $data = array_replace_recursive($data, $remark['OCR']);
                $data = array_replace_recursive($data,
                    array_intersect_key($content, array_flip($return_column_list)));

                if(isset($remark['failed_type_list'])) {
                    $remove_column_list = [];
                    foreach ($remark['failed_type_list'] as $failed_type) {
                        switch ($failed_type) {
                            case REALNAME_IMAGE_TYPE_FRONT:
                                $remove_column_list = array_merge($remove_column_list, ['gender', 'id_number','id_card_date','id_card_place','birthday','name','front_image_id','front_image']);
                                break;
                            case REALNAME_IMAGE_TYPE_BACK:
                                $remove_column_list = array_merge($remove_column_list, ['father', 'mother', 'spouse', 'military_service', 'born', 'address','back_image_id', 'back_image']);
                                break;
                            case REALNAME_IMAGE_TYPE_PERSON:
                                $remove_column_list = array_merge($remove_column_list, ['person_image_id','person_image']);
                                break;
                            case REALNAME_IMAGE_TYPE_HEALTH:
                                $remove_column_list = array_merge($remove_column_list, ['healthcard_name', 'healthcard_birthday', 'healthcard_id_number', 'healthcard_image_id', 'healthcard_image']);
                                break;
                        }
                    }
                    $data = array_replace_recursive($data,
                        array_combine(array_values($remove_column_list), array_fill(0, count($remove_column_list), '')));
                }
                $this->load->library('S3_upload');

                $url_key_list = ['front_image', 'back_image', 'person_image', 'healthcard_image'];
                foreach ($url_key_list as $key) {
                    if(empty($data[$key]))
                        continue;
                    $path_info = pathinfo($data[$key]);
                    if(empty($path_info['basename']))
                        continue;

                    $newImageUrl = $this->s3_upload->public_image_by_data(
                        file_get_contents($data[$key]),
                        FRONT_S3_BUCKET,
                        $user_id,
                        [
                            'type' => 'tmp/'.$user_id,
                            'name' => md5($path_info['basename']) . '.jpg',
                        ]
                    );
                    $data[$key] = str_replace(S3_BUCKET, FRONT_CDN_URL, $newImageUrl);
                }
            }

            foreach ($empty_check_list as $field)
            {
                if (isset($data[$field]) && empty($data[$field]))
                {
                    unset($data[$field]);
                }
            }
            $this->response(array('result' => 'SUCCESS','data' => $data));
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
     * @apiParam {Number} front_image 學生證正面照 ( 圖片ID )
     * @apiParam {Number} back_image 學生證背面照 ( 圖片ID )
	 * @apiParam {String} sip_account SIP帳號
	 * @apiParam {String} sip_password SIP密碼
	 * @apiParam {Number} [transcript_image] 成績單 ( 圖片ID )
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
     */
	public function student_post()
    {
		$certification_id 	= 2;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			//必填欄位
			$fields 	= [
				'school',
				'department',
				'grade',
				'student_id',
				'major',
				'sip_account',
				'sip_password'
			];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array(
					    'result'  => 'ERROR',
                        'error'   => INPUT_NOT_CORRECT,
                        'err_msg' => $field . 'is empty!'
                    ));
				}else{
					$content[$field] = $input[$field];
				}
			}

            // for news 前端判斷email 待app新上版要刪除
            $content['email'] = '';
            // alleninflux   2020-02-21 15:29:32 +0800 不知道作用
            isset($input['retry']) ? $content['retry'] = json_decode($input['retry']) : '';

			$content['system'] 	 = isset($input['system']) && in_array($input['system'],array(0,1,2))?$input['system']:0;
      isset($input['programming_language'])?$content['programming_language']=$input['programming_language']:'';

			$this->load->model('user/user_meta_model');

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

            $file_fields 	= ['front_image','back_image'];
            foreach ($file_fields as $field) {
                $image_id = intval($input[$field]);
                if (!$image_id) {
                    $this->response(array(
                        'result' => 'ERROR',
                        'error' => INPUT_NOT_CORRECT,
                        'err_msg' => $field . 'is empty!'
                    ));
                }else{
                    $rs = $this->log_image_model->get_by([
                        'id'		=> $image_id,
                        'user_id'	=> $user_id,
                    ]);

                    if($rs){
                        $content[$field . '_id'] = $rs->id;
                        $content[$field] = $rs->url;
                    }else{
                        $this->response(array(
                            'result' => 'ERROR',
                            'error' => INPUT_NOT_CORRECT,
                            'err_msg' => $image_id . 'not found in db!'
                        ));
                    }
                }
            }

			$file_fields = [];
            isset($input['transcript_image']) && is_numeric($input['transcript_image'])?$file_fields[]='transcript_image':'';
            isset($input['pro_certificate'])? $content['pro_certificate']=$input['pro_certificate']:'';
            isset($input['pro_certificate_image'])?$file_fields[]='pro_certificate_image':'';
            isset($input['game_work'])?$content['game_work']=$input['game_work']:'';
            isset($input['game_work_image']) && !empty($input['game_work_image'])?$file_fields[]='game_work_image':'';
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$input[$field]);
                if(count($image_ids)>5){
                    $image_ids = array_slice($image_ids,0,5);
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

            $content['graduate_date'] = isset($input['graduate_date'])?$input['graduate_date']:'';
            $content['school'] = trim($content['school'], ' ');
            $content['sip_account'] = trim($content['sip_account'], ' ');
            $content['sip_password'] = trim($content['sip_password'], ' ');

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
			);
			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$this->load->library('scraper/sip_lib.php');
				$this->sip_lib->requestDeep($content['school'], $content['sip_account'], $content['sip_password']);
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    /**
     * @api {post} /student_cards 辨識 學生證的學校
     * @apiVersion 0.2.0
     * @apiName PostCertificationStudentCards
     * @apiGroup Certification
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} photo id
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
     *
     * @apiUse InputError
     * @apiUse TokenError
     *
     */
    public function student_cards_post()
    {
        $post = $this->input->post(NULL, TRUE);
        $front_image_id = isset($post['front_id']) ? intval($post['front_id']) : 0;
        $back_image_id  = isset($post['back_id']) ? intval($post['back_id']) : 0;

        if ( ! $front_image_id || ! $back_image_id)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $this->load->model('log/log_image_model');
        $front_image_log = $this->log_image_model->get($front_image_id);
        $back_image_log  = $this->log_image_model->get($back_image_id);

        $ownerId = $this->user_info->id;
        if ( ! $front_image_log || ! $back_image_log)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        if ($front_image_log->user_id != $ownerId || $back_image_log->user_id != $ownerId)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $this->load->library('student_card_recognition_lib');
        $this->student_card_recognition_lib->request_student_card_identification($front_image_log, $back_image_log);

        $this->response(['result' => 'SUCCESS']);
    }

    /**
     * @api {post} /student_cards 辨識 學生證的學校
     * @apiVersion 0.2.0
     * @apiName PostCertificationStudentCards
     * @apiGroup Certification
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {String} photo front_id
     * @apiParam {String} photo back_id
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data":
     *          {
     *              status' => 0,
     *              student_id' => '',
     *              student_department' => '',
     *              student_academic_degree' => '',
     *              university' => '',
     *              spent_time' => 0.15646
     *          }
     *    }
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data":
     *          {
     *              status' => 1,
     *              student_id' => 'A100345871',
     *              student_department' => '資工所',
     *              student_academic_degree' => '碩士',
     *              university' => '國立臺灣大學',
     *              spent_time' => 8.34556
     *          }
     *    }
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
     *
     * @apiUse InputError
     * @apiUse TokenError
     * @apiUse PictureNotClearError
     *
     */
    public function student_cards_get()
    {
        $get = $this->input->get(NULL, TRUE);
        $front_image_id = isset($get['front_id']) ? intval($get['front_id']) : 0;
        $back_image_id  = isset($get['back_id']) ? intval($get['back_id']) : 0;

        if ( ! $front_image_id || ! $back_image_id)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $this->load->model('log/log_image_model');
        $front_image_log = $this->log_image_model->get($front_image_id);
        $back_image_log  = $this->log_image_model->get($back_image_id);

        $ownerId = $this->user_info->id;
        if ( ! $front_image_log || ! $back_image_log)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        if ($front_image_log->user_id != $ownerId || $back_image_log->user_id != $ownerId)
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $reference = $front_image_log->id . '-' . $back_image_log->id;
        $this->load->library('student_card_recognition_lib');
        $result = $this->student_card_recognition_lib->get_student_card_identification($reference);

        if ( ! $result)
        {
            $this->response(['result' => 'ERROR', 'error' => EXIT_ERROR]);
        }
        else
        {
            $this->response(['result' => 'SUCCESS', 'data' => $result]);
        }
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
     * @apiParam {Number} front_image 金融卡正面照 ( 圖片ID )
     * @apiParam {Number} back_image 金融卡背面照 ( 圖片ID )
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
            //是否驗證過
            $this->was_verify($certification_id);

			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= [];

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

			$where = [
				'investor'		=> $investor,
				'bank_code'		=> $content['bank_code'],
				'bank_account'	=> $content['bank_account'],
				'status'		=> CERTIFICATION_STATUS_SUCCEED,
			];

			$user_bankaccount = $this->user_bankaccount_model->get_by($where);
			if($user_bankaccount){
				$this->response(array('result' => 'ERROR','error' => CERTIFICATION_BANK_ACCOUNT_EXIST ));
			}

			//上傳檔案欄位
			$file_fields 	= ['front_image','back_image'];
			foreach ($file_fields as $field) {
				$image_id = intval($input[$field]);
				if (!$image_id) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$rs = $this->log_image_model->get_by([
						'id'		=> $image_id,
						'user_id'	=> $this->user_info->originalID,
					]);

					if($rs){
						$content[$field] = $rs->url;
					}else{
						$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
					}
				}
			}

			$param		= [
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'expire_time'		=> strtotime('+20 years'),
				'content'			=> json_encode($content),
			];

			$insert = $this->user_certification_model->insert($param);
			if($insert){
				$bankaccount_info = [
					'user_id'		=> $user_id,
					'investor'		=> $investor,
					'user_certification_id'	=> $insert,
					'bank_code'		=> $content['bank_code'],
					'branch_code'	=> $content['branch_code'],
					'bank_account'	=> $content['bank_account'],
					'front_image'	=> $content['front_image'],
					'back_image'	=> $content['back_image'],
				];

				if($investor){
					$bankaccount_info['verify'] = 2;
				}else{
                    isset($this->user_info->naturalPerson) ? $user_id = [$user_id, $this->user_info->originalID] : '';
					$this->certification_lib->set_success($insert);
					$target = $this->target_model->get_by([
						'user_id'	=> $user_id,
						'status'	=> 2,
					]);
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
	 * @apiParam {Number} [household_image] 戶口名簿 ( 圖片ID )
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
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];


            //必填欄位
			$fields 	= ['name','phone','relationship'];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}

            $content['name'] 	= isset($input['name'])?$input['name']:"";

			if (isset($input['household_image']) && is_numeric($input['household_image'])) {
				$rs = $this->log_image_model->get_by([
					'id'		=> intval($input['household_image']),
					'user_id'	=> $user_id,
				]);
				if($rs){
					$content['household_image'] = $rs->url;
				}
			}

            if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,15}$/u',$content['name'])){
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
            if(mb_strlen($content['name']) < 2 || mb_strlen($content['name']) > 15){
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }

            if(!preg_match('/^09[0-9]{2}[0-9]{6}$/', $content['phone'])){
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }

            if($investor == USER_BORROWER || $investor == USER_INVESTOR){
                $name_limit = array('爸爸','媽媽','爺爺','奶奶','父親','母親');
                if(in_array($content['name'],$name_limit)){
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }

                $phone_exist = $this->user_model->get_by([
                    'phone'		=> $content['phone'],
                    'id'        => $user_id,
                    'status'	=> 1,
                ]);
                if($phone_exist){
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }
            }

			$param		= [
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE,
			];
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
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

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

			$param		= [
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
			];
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
     * @api {post} /v2/certification/financial 認證 收支資訊認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationFinancial
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} income 打工收入
	 * @apiParam {Number} incomeStudent 零用錢收入
	 * @apiParam {Number} scholarship 獎學金收入
	 * @apiParam {Number} other_income 其他收入
	 * @apiParam {Number} restaurant 餐飲支出
	 * @apiParam {Number} transportation 交通支出
	 * @apiParam {Number} entertainment 娛樂支出
	 * @apiParam {Number} other_expense 其他支出
     * @apiParam {Number} [creditcard_image] 信用卡帳單照 ( 圖片ID )
     * @apiParam {Number} [passbook_image] 存摺內頁照 ( 圖片ID )
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
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

			//必填欄位
			$fields 	= [
                // 薪資/打工收入
				'income',
                // 零用錢收入
				'incomeStudent',
                // 獎學金收入
                'scholarship',
				'other_income',
				'restaurant',
				'transportation',
				// 網路電信支出
				'telegraph_expense',
				'entertainment',
				'other_expense',
				// 租金
				'rent_expenses',
				// 教育
				'educational_expenses',
				// 保險
				'insurance_expenses',
				// 社交
				'social_expenses',
				// 房貸
				'long_assure_monthly_payment',
				// 車貸
				'mid_assure_monthly_payment',
				// 信貸
				'credit_monthly_payment',
				// 學貸
				'student_loans_monthly_payment',
				// 信用卡
				'credit_card_monthly_payment',
				// 其他民間借款
				'other_private_borrowing'
			];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$content[$field] = 0;
				}else{
					$content[$field] = intval($input[$field]);
				}
			}

			//上傳檔案欄位
			$file_field 	= ['creditcard_image'];
			foreach ($file_field as $field) {
                if(isset($input[$field])) {
                    $image_id = !empty($input[$field]) != null ? intval($input[$field]) : null;
                    if (!$image_id) {
                        //$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                    } else {
                        $rs = $this->log_image_model->get_by([
                            'id' => $image_id,
                            'user_id' => $this->user_info->originalID,
                        ]);

                        if ($rs) {
                            $content[$field] = $rs->url;
                        } else {
                            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                        }
                    }
                }else{
                    $content['creditcard_image'] = '';
                }
			}

            $file_fields 	= ['passbook_image','bill_phone_image'];
			foreach ($file_fields as $fieldS) {
			    if(isset($input[$fieldS])){
                    $image_ids = explode(',', $input[$fieldS]);
                    if (count($image_ids) > 3) {
                        $image_ids = array_slice($image_ids, 0, 3);
                    }
                    $list = $this->log_image_model->get_many_by([
                        'id' => $image_ids,
                        'user_id' => $this->user_info->originalID,
                    ]);

                    if ($list && count($list) == count($image_ids)) {
                        $content[$fieldS] = [];
                        foreach ($list as $k => $v) {
                            $content[$fieldS][] = $v->url;
                        }
                    }
                }else{
                    $content[$fieldS][] = '';
                }
            }

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
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
     * @api {post} /v2/certification/financialWorker 認證 財務資訊認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationFinancial
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {Number} income 薪資/兼職收入
	 * @apiParam {Number} pocketMoney 投資理財收入
	 * @apiParam {Number} other_income 其他收入
	 * @apiParam {Number} restaurant 餐飲支出
	 * @apiParam {Number} transportation 交通支出
	 * @apiParam {Number} entertainment 娛樂支出
	 * @apiParam {Number} other_expense 其他支出
     * @apiParam {Number} [creditcard_image] 信用卡帳單照 ( 圖片ID )
     * @apiParam {Number} [passbook_image] 存摺內頁照 ( 圖片ID )
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
	public function financialWorker_post()
    {
        $certification_id = CERTIFICATION_FINANCIALWORKER;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();
            // 是否需要轉人工(有傳圖片的話要)
            $should_check = false;

			//是否驗證過
			$this->was_verify($certification_id);

			//必填欄位
			$fields 	= [
                // 薪資/兼職收入
				'income',
                // 投資理財收入
				'pocketMoney',
				'other_income',
				'restaurant',
				'transportation',
				// 網路電信支出
				'telegraph_expense',
				'entertainment',
				'other_expense',
				// 租金
				'rent_expenses',
				// 教育
				'educational_expenses',
				// 保險
				'insurance_expenses',
				// 社交
				'social_expenses',
				// 房貸
				'long_assure_monthly_payment',
				// 車貸
				'mid_assure_monthly_payment',
				// 信貸
				'credit_monthly_payment',
				// 學貸
				'student_loans_monthly_payment',
				// 信用卡
				'credit_card_monthly_payment',
				// 其他民間借款
				'other_private_borrowing'
			];
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$content[$field] = 0;
				}else{
					$content[$field] = intval($input[$field]);
				}
			}

			//上傳檔案欄位
			$file_field 	= ['creditcard_image'];
			foreach ($file_field as $field) {
                if(isset($input[$field])) {
                    $should_check = true;
                    $image_id = !empty($input[$field]) != null ? intval($input[$field]) : null;
                    if (!$image_id) {
                        //$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                    } else {
                        $rs = $this->log_image_model->get_by([
                            'id' => $image_id,
                            'user_id' => $user_id,
                        ]);

                        if ($rs) {
                            $content[$field] = $rs->url;
                        } else {
                            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                        }
                    }
                }else{
                    $content['creditcard_image'] = '';
                }
			}

            $file_fields 	= ['passbook_image','bill_phone_image'];
			foreach ($file_fields as $fieldS) {
			    if(isset($input[$fieldS])){
                    $should_check = true;
                    $image_ids = explode(',', $input[$fieldS]);
                    if (count($image_ids) > 3) {
                        $image_ids = array_slice($image_ids, 0, 3);
                    }
                    $list = $this->log_image_model->get_many_by([
                        'id' => $image_ids,
                        'user_id' => $user_id,
                    ]);

                    if ($list && count($list) == count($image_ids)) {
                        $content[$fieldS] = [];
                        foreach ($list as $k => $v) {
                            $content[$fieldS][] = $v->url;
                        }
                    }
                }else{
                    $content[$fieldS] = [];
                }
            }

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			);

            $param['sys_check'] = 1;
            // 有傳圖片的話轉人工，沒有自動過件
            if($should_check == true){
                $param['status'] = CERTIFICATION_STATUS_PENDING_TO_REVIEW;
            }

			$insert = $this->user_certification_model->insert($param);
			if($insert){
                // 有傳圖片的話轉人工，沒有自動過件
                if($should_check == false){
                    $info = $this->user_certification_model->order_by('certification_id', 'DESC')->get_by([
                        'certification_id' => $certification_id,
                        'user_id' => $user_id
                    ]);
                    $cert = Certification_factory::get_instance_by_model_resource($info);
                    $cert->set_success(TRUE);
                }
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
			$type  		= $input['type'];
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;

            $fields = ['access_token'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }
            }
            switch ($type) {
                case "facebook":
                    $this->load->library('facebook_lib');
                    $info       = $this->facebook_lib->get_info($input['access_token']);
                    $get_data = $this->user_certification_model->order_by('id', 'desc')->get_by([
                        'user_id'    => $user_id,
                        'certification_id' => 4,
                        'status' => [CERTIFICATION_STATUS_PENDING_TO_VALIDATE ,CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_AUTHENTICATED],
                        'investor' => $investor,
                    ]);
                    if (empty($get_data)) {
                        $initialize_id = $this->social_initialize($user_id, $investor);
                        $content = [
                            'facebook' => $info,
                            'instagram' => '',
                        ];
                        $rs = $this->user_certification_model->update($initialize_id, ["content" => json_encode($content)]);
                        $rs ? $this->response(array('result' => 'SUCCESS'))
                            : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    } else {
                        $content_data = json_decode($get_data->content);
                        $content = [
                            'facebook' => $info,
                            'instagram' => $content_data->instagram,
                        ];
                        $rs = $this->user_certification_model->update($get_data->id, ["content" => json_encode($content)]);
                        $rs ? $this->response(array('result' => 'SUCCESS'))
                            : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    }
                    break;
                case "instagram":
                    $this->load->library('scraper/instagram_lib');
                    $log_status = $this->instagram_lib->getLogStatus($user_id, $input['access_token']);
                    $info['username'] = preg_replace('/\s+/', '', $input['access_token']);
                    $info['link'] = 'https://www.instagram.com/' . $input['access_token'];
                    $info['info'] = [];
                    $time = isset($log_status['response']['result']['updatedAt']) ? $log_status['response']['result']['updatedAt'] : 0;
                    if ($log_status && $log_status['status'] == SCRAPER_STATUS_NO_CONTENT || $time > strtotime('-72 hours'))
                    {
                        $this->instagram_lib->updateRiskControlInfo($user_id, $input['access_token']);
                    }

                    $get_data = $this->user_certification_model->order_by('id', 'desc')->get_by([
                        'user_id' => $user_id,
                        'certification_id' => 4,
                        'status' => [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_AUTHENTICATED],
                        'investor' => $investor,
                    ]);
                    if (empty($get_data))
                    {
                        $initialize_id = $this->social_initialize($user_id, $investor);
                        $content = [
                            'facebook' => '',
                            'instagram' => $info,
                        ];
                        $rs = $this->user_certification_model->update($initialize_id, ["content" => json_encode($content)]);
                        $rs ? $this->response(array('result' => 'SUCCESS'))
                            : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    }
                    else
                    {
                        $content_data = json_decode($get_data->content);
                        $content = [
                            'facebook' => $content_data->facebook,
                            'instagram' => $info,
                        ];
                        $rs = $this->user_certification_model->update($get_data->id, ["content" => json_encode($content)]);
                        $rs ? $this->response(array('result' => 'SUCCESS'))
                            : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    }
                    break;
                case "line":
                    $data = array(
                        "line_access_token" => $input["access_token"],
                        "line_displayName"  => base64_decode($input["displayName"]),
                        "line_pictureUrl"   => $input["pictureUrl"],
                    );
                    $exist         = $this->user_meta_model->get_by(array('user_id' => $user_id, 'meta_key' => 'line_access_token'));
                    if ($exist) {
                        foreach ($data as $key => $value) {
                            $param = array(
                                'user_id'        => $user_id,
                                'meta_key'         => $key,
                            );
                            $rs  = $this->user_meta_model->update_by($param, array('meta_value'    => $value));
                        }
                    } else {
                        $param = [];
                        foreach ($data as $key => $value) {
                            $param[] = array(
                                'user_id'        => $user_id,
                                'meta_key'         => $key,
                                'meta_value'    => $value
                            );
                        }
                        $rs  = $this->user_meta_model->insert_many($param);
                    }
                    $rs ? $this->response(array('result' => 'SUCCESS'))
                        : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    break;
            }
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

     /**
     * @api {post} /v2/certification/social_intelligent 認證 (名校貸)社交認證
     * @apiVersion 0.2.0
     * @apiName PostCertificationSocialIntelligent
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
    public function social_intelligent_post()
    {
        $certification_id = CERTIFICATION_SOCIAL_INTELLIGENT;
        $certification = $this->certification[$certification_id];
        if ($certification && $certification['status'] == CERTIFICATION_STATUS_SUCCEED)
        {
            $input = $this->input->post(NULL, TRUE);
            $type = $input['type'];
            $user_id = $this->user_info->id;
            $investor = $this->user_info->investor;

            $fields = ['access_token'];
            foreach ($fields as $field)
            {
                if (empty($input[$field]))
                {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                }
            }
            switch ($type)
            {
                case 'instagram':
                    $this->load->library('scraper/instagram_lib');
                    $log_status = $this->instagram_lib->getLogStatus($user_id, $input['access_token']);
                    $info['username'] = $input['access_token'];
                    $info['link'] = 'https://www.instagram.com/' . $input['access_token'];
                    $info['info'] = [];
                    $time = isset($log_status['response']['result']['updatedAt']) ? $log_status['response']['result']['updatedAt'] : 0;
                    if ($log_status && $log_status['status'] == SCRAPER_STATUS_NO_CONTENT || $time > strtotime('-72 hours'))
                    {
                        $this->instagram_lib->updateRiskControlInfo($user_id, $input['access_token']);
                    }

                    $get_data = $this->user_certification_model->order_by('id', 'desc')->get_by([
                        'user_id' => $user_id,
                        'certification_id' => $certification_id,
                        'status' => [CERTIFICATION_STATUS_PENDING_TO_VALIDATE, CERTIFICATION_STATUS_PENDING_TO_REVIEW, CERTIFICATION_STATUS_AUTHENTICATED],
                        'investor' => $investor,
                    ]);
                    if (empty($get_data))
                    {
                        $initialize_id = $this->social_initialize($user_id, $investor, CERTIFICATION_SOCIAL_INTELLIGENT);
                        $content = [
                            'facebook' => '',
                            'instagram' => $info,
                        ];
                        $rs = $this->user_certification_model->update($initialize_id, ['content' => json_encode($content)]);
                        $rs ? $this->response(array('result' => 'SUCCESS'))
                            : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    }
                    else
                    {
                        $content_data = json_decode($get_data->content);
                        $content = [
                            'facebook' => $content_data->facebook,
                            'instagram' => $info,
                        ];
                        $rs = $this->user_certification_model->update($get_data->id, ["content" => json_encode($content)]);
                        $rs ? $this->response(array('result' => 'SUCCESS'))
                            : $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                    }
                    break;
            }
        }
        $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NOT_ACTIVE));
    }


	/**
     * @api {post} /v2/certification/diploma 認證 最高學歷認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationDiploma
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String} school 學校名稱
	 * @apiParam {String=0,1,2} [system=0] 學制 0:大學 1:碩士 2:博士
     * @apiParam {Number} diploma_image 畢業證書照 ( 圖片ID )
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
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= array();

            //必填欄位
			$fields 	= ['school'];//,'major','department'
			foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields 	= [];
            $content['system']           = isset($input['system']) && in_array($input['system'],array(0,1,2))?$input['system']:0;
            $content['major']            = isset($input['major'])?$input['major']:"";
            $content['department']       = isset($input['department'])?$input['department']:"";
            $content['diploma_date']     = isset($input['diploma_date'])?$input['diploma_date']:"";
            $content['sip_account'] 	 = isset($input['sip_account']) ? $input['sip_account'] : "";
            $content['sip_password'] 	 = isset($input['sip_password']) ? $input['sip_password'] : "";
            $content['transcript_image'] = isset($input['transcript_image']) ? $file_fields[]='transcript_image' : "";
            $content['diploma_image'] 	 = isset($input['diploma_image']) ? $file_fields[]='diploma_image' : "";
            foreach ($file_fields as $field) {
                $image_ids = explode(',', $input[$field]);
                if (count($image_ids) > 5) {
                    $image_ids = array_slice($image_ids, 0, 5);
                }
                $list = $this->log_image_model->get_many_by([
                    'id' => $image_ids,
                    'user_id' => $user_id,
                ]);

                if ($list && count($list) == count($image_ids)) {
                    $content[$field] = [];
                    foreach ($list as $k => $v) {
                        $content[$field][] = $v->url;
                    }
                }
            }
			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
                'expire_time'		=> strtotime('+20 years'),
                'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
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
     * @apiParam {Number} postal_image 郵遞回單照 ( 圖片ID )
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
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= array();
			$content['return_type'] = isset($input['return_type']) && intval($input['return_type'])?$input['return_type']:0;

			$send_mail = false;
			if($content['return_type']==0){
                //上傳檔案欄位
                $file_fields 	= ['postal_image'];
                foreach ($file_fields as $field) {
                    $image_id = intval($input[$field]);
                    if (!$image_id) {
                        $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                    }else{
                        $rs = $this->log_image_model->get_by([
                            'id'		=> $image_id,
                            'user_id'	=> $this->user_info->originalID,
                        ]);

                        if($rs){
                            $content[$field] = $rs->url;
                        }else{
                            $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                        }
                    }
                }
            }else{
                $this->mail_check($user_id,$investor);
                $target = [];
                $targets = $this->target_model->get_many_by(array(
                    'user_id'       => $user_id,
                    'status'		=> [20,21,22,23],
                ));
                foreach ($targets as $value){
                    $target[] = $value->target_no;
                }

                $send_mail = true;
				// 加入檔案尚未為回傳標記
				$content['mail_file_status'] = 0;
            }


			//退信評
            $this->load->library('target_lib');
            $this->load->model('loan/credit_model');
            $targets = $this->target_model->get_many_by(array(
                'user_id'   => $user_id,
                'status'	=> array(0,22)
            ));
            if($targets){
                $credit_list = $this->credit_model->get_many_by([
                    'user_id'    => $user_id,
                    'product_id' => [3,4],
                    'status'     => 1
                ]);
                foreach($credit_list as $ckey => $cvalue){
                    //信用低落
                    if(!in_array($cvalue->level,[11,12,13])){
                        $this->credit_model->update_by(
                            ['id'    => $cvalue->id],
                            ['status'=> 0]
                        );
                    }
                }
            }

			$param		= array(
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
			);
			$insert = $this->user_certification_model->insert($param);

			if($insert){
			    if($send_mail){
                    $this->notification_lib->notice_cer_investigation($user_id, implode(' / ', $target));
                }
				$this->response(array('result' => 'SUCCESS'));
			}else{
				$this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    // 聯徵信件重新寄送
    public function resend_email_post(){
        $certification_id 	= 9;
		$certification 		= $this->certification[$certification_id];
        if($certification && $certification['status']==1){
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $target = [];
            $targets = $this->target_model->get_many_by(array(
                'user_id'       => $user_id,
                'status'		=> [20,21,22,23],
            ));
            foreach ($targets as $value){
                $target[] = $value->target_no;
            }
            $this->notification_lib->notice_cer_investigation($user_id, implode(' / ', $target));
            $this->response(array('result' => 'SUCCESS'));
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	/**
     * @api {post} /v2/certification/job 認證 工作認證
	 * @apiVersion 0.2.0
	 * @apiName PostCertificationJob
     * @apiGroup Certification
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String} tax_id 公司統一編號
	 * @apiParam {String} company 公司名稱
	 * @apiParam {String=A-S} industry 公司類型
	 * <br>A：農、林、漁、牧業
	 * <br>B：礦業及土石採取業
	 * <br>C：製造業
	 * <br>D：電力及燃氣供應業
	 * <br>E：用水供應及污染整治業
	 * <br>F：營建工程業
	 * <br>G：批發及零售業
	 * <br>H：運輸及倉儲業
	 * <br>I：住宿及餐飲業
	 * <br>J：出版、影音製作、傳播及資通訊服務業
	 * <br>K：金融及保險業
	 * <br>L：不動產業
	 * <br>M：專業、科學及技術服務業
	 * <br>N：支援服務業
	 * <br>O：公共行政及國防；強制性社會安全
	 * <br>P：教育業
	 * <br>Q：醫療保健及社會工作服務業
	 * <br>R：藝術、娛樂及休閒服務業
	 * <br>S：其他服務業
	 * @apiParam {Number=0,1,2,3,4,5,6} employee=0 企業規模
	 * <br>0：1~20（含）
	 * <br>1：20~50（含）
	 * <br>2：50~100（含）
	 * <br>3：100~500（含）
	 * <br>4：500~1000（含）
	 * <br>5：1000~5000（含）
	 * <br>6：5000以上
	 * @apiParam {Number=0,1,2,3} position=0 職位 <br>0：一般員工 <br>1：初級管理 <br>2：中級管理 <br>3：高級管理
	 * @apiParam {Number=0,1} type=0 職務性質 <br>0：外勤 <br>1：内勤
	 * @apiParam {Number=0,1,2,3,4} seniority=0 畢業以來的工作期間 <br>0：三個月以内（含） <br>1：三個月至半年（含） <br>2：半年至一年（含） <br>3：一年至三年（含） <br>4：三年以上
	 * @apiParam {Number=0,1,2,3,4} job_seniority=0 本公司工作期間 <br>0：三個月以内（含） <br>1：三個月至半年（含） <br>2：半年至一年（含） <br>3：一年至三年（含） <br>4：三年以上
	 * @apiParam {Number} salary 月薪
	 * @apiParam {Number} business_image 名片/工作證明 ( 圖片ID )
	 * @apiParam {Number} [license_image] 專業證照 ( 圖片ID )
     * @apiParam {String} labor_image 勞健保卡 ( 圖片IDs 以逗號隔開，最多三個)
     * @apiParam {String} passbook_image 存摺內頁照 ( 圖片IDs 以逗號隔開，最多三個)
     * @apiParam {String} auxiliary_image 收入輔助證明 ( 圖片IDs 以逗號隔開，最多三個)

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
            isset($this->user_info->naturalPerson) ? $this->user_info->id = $this->user_info->naturalPerson->id : '';
			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= [];
			$file_fields= [];

            $cer_exists = $this->user_certification_model->get_by([
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'status' => CERTIFICATION_STATUS_NOT_COMPLETED,
            ]);
            if (isset($input['save']) && $input['save']) {
                $param = [
                    'user_id' => $user_id,
                    'certification_id' => $certification_id,
                    'investor' => $investor,
                    'content' => json_encode($input),
                    'status' => CERTIFICATION_STATUS_NOT_COMPLETED,
                ];

                if ($cer_exists) {
                    $input = (object)array_merge((array)json_decode($cer_exists->content), (array)$input);
                    $rs = $this->user_certification_model->update($cer_exists->id, [
                        'content' => json_encode($input),
                    ]);
                } else {
                    $rs = $this->user_certification_model->insert($param);
                }
                if ($rs) {
                    $this->response(['result' => 'SUCCESS','msg' => 'SAVED']);
                }
            }

			//是否驗證過
            if(!$cer_exists || $cer_exists->status != CERTIFICATION_STATUS_NOT_COMPLETED){
                $this->was_verify($certification_id);
            }

            //必填欄位
			$fields 	= ['tax_id','industry','salary'];//,'company'
			foreach ($fields as $field) {
				if (empty($input[$field])) {
					$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
				}else{
					$content[$field] = $input[$field];
				}
			}

			// to do : 加入商業司爬蟲相關機制
			$this->load->library('gcis_lib');
			$gcis_response =  $this->gcis_lib->account_info($content['tax_id']);
			if($gcis_response){
				if($gcis_response['Paid_In_Capital_Amount']){
					$content['capital_amount'] = $gcis_response['Paid_In_Capital_Amount'];
				}else{
					$content['capital_amount'] = $gcis_response['Capital_Stock_Amount'];
				}
			}

            $content['company'] 	  = isset($input['company'])?$input['company']:"";
            $content['company_address'] 	  = isset($input['company_address'])?$input['company_address']:"";
            $content['company_phone_number'] 	  = isset($input['company_phone_number'])?$input['company_phone_number']:"";
            isset($input['programming_language'])?$content['programming_language']=$input['programming_language']:"";
            isset($input['license_des'])?$content['license_des']=$input['license_des']:"";

            $employee_range 		  = $this->config->item('employee_range');
			$position_name 			  = $this->config->item('position_name');
			$seniority_range 		  = $this->config->item('seniority_range');
			$industry_name 			  = $this->config->item('industry_name');
			$job_type_name 			  = $this->config->item('job_type_name');
			$content['employee'] 	  = array_key_exists(intval($input['employee']),$employee_range)?intval($input['employee']):0;
			$content['position'] 	  = array_key_exists(intval($input['position']),$position_name)?intval($input['position']):0;
			$content['type'] 		  = array_key_exists(intval($input['type']),$job_type_name)?intval($input['type']):0;
			$content['seniority'] 	  = array_key_exists(intval($input['seniority']),$seniority_range)?intval($input['seniority']):0;
			$content['job_seniority'] = array_key_exists(intval($input['job_seniority']),$seniority_range)?intval($input['job_seniority']):0;
            $content['job_title'] = $input['job_title'] ?? ''; // 工作職稱

			// 使用者手填資料
			$content['LaborQryDate'] = isset($input['LaborQryDate']) ? $input['LaborQryDate'] : '';
			$content['LaborInsSalary'] = isset($input['LaborInsSalary']) ? $input['LaborInsSalary'] : '';

			if(!array_key_exists($input['industry'],$industry_name)){
				$this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
			}

            isset($input['incomeDate'])?$content['incomeDate']=($input['incomeDate']>=1&&$input['incomeDate']<=31?$input['incomeDate']:5):"";

            isset($input['business_image'])?$file_fields[]='business_image':'';
            isset($input['license_image'])?$file_fields[]='license_image':'';
            isset($input['financial_image'])?$file_fields[]='financial_image':'';
            isset($input['auxiliary_image'])?$file_fields[]='auxiliary_image':'';

            $send_mail = false;
            if(isset($input['labor_type'])){
                if($input['labor_type']==0){
                    $file_fields[] = 'labor_image';
                }
                elseif($input['labor_type']==1){
					$content['labor_type']=$input['labor_type'];
                    $this->mail_check($user_id,$investor);
                    $send_mail =true;
					// 加入檔案回傳標記
					$content['mail_file_status'] = 0;
                }
            }
            if(isset($input['passbook_image_type'])){
                if($input['passbook_image_type']==1){
                    array_push($file_fields, 'passbook_cover_image','passbook_image');
                }
                elseif($input['passbook_image_type']==2){
                    $file_fields[] = 'passbook_image';
                }
                elseif($input['passbook_image_type']==0){
                    $file_fields[] = 'income_prove_image';
                }
            }

			//多個檔案欄位
            isset($input['pro_certificate'])? $content['pro_certificate']=$input['pro_certificate']:"";
            isset($input['pro_certificate_image'])?$file_fields[]='pro_certificate_image':'';
            isset($input['game_work'])?$content['game_work']=$input['game_work']:"";
            isset($input['game_work_image']) && !empty($input['game_work_image'])?$file_fields[]='game_work_image':'';
			foreach ($file_fields as $field) {
                $list = false;
    			$image_ids = isset($input[$field]) && !empty($input[$field]) ? explode(',',$input[$field]) : [];
                if(!empty($image_ids)){
                    if(count($image_ids) > 0){
                        if(count($image_ids)>15){
                            $image_ids = array_slice($image_ids,0,15);
                        }
                        $list = $this->log_image_model->get_many_by([
                            'id'		=> $image_ids,
                            'user_id'	=> $user_id,
                        ]);
                    }

    				if($list && count($list)==count($image_ids)){
    					$content[$field] = [];
    					foreach($list as $k => $v){
    						$content[$field][] = $v->url;
    					}
    				}else{
    					$this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
    				}
                }
			}

			$param		= [
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
                'status'            => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
			];

            if ($cer_exists) {
                $param['status'] = CERTIFICATION_STATUS_PENDING_TO_REVIEW;
                $rs = $this->user_certification_model->update($cer_exists->id, $param);
            }else{
                $rs = $this->user_certification_model->insert($param);
            }
			if($rs){
			    if($send_mail){
                    $this->notification_lib->notice_cer_job($user_id);
                }
				$this->response(['result' => 'SUCCESS']);
			}else{
				$this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    /**
     * @api {post} /v2/certification/verify_certifications 開始審核徵信階段的徵信項目
     * @apiVersion 0.2.0
     * @apiName PostVerifyCertifications
     * @apiGroup Certification
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {Number} target_id 案件流水號
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS"
     *    }
     *
     *
     * @apiError 130 權限不足
     * @apiErrorExample {Object} 130
     *     {
     *       "result": "ERROR",
     *       "error": "130"
     *     }
     *
     * @apiError 200 參數錯誤
     * @apiErrorExample {Object} 200
     *     {
     *       "result": "ERROR",
     *       "error": "200"
     *     }
     *
     * @apiError 207 參數錯誤
     * @apiErrorExample {Object} 207
     *     {
     *       "result": "ERROR",
     *       "error": "207"
     *     }
     *
     * @apiError 501 此驗證尚未啟用 (尚未提交所有徵信項)
     * @apiErrorExample {Object} 501
     *     {
     *       "result": "ERROR",
     *       "error": "501"
     *     }
     *
     * @apiError 801 標的不存在
     * @apiErrorExample {Object} 801
     *     {
     *       "result": "ERROR",
     *       "error": "801"
     *     }
     *
     */
    public function verify_certifications_post()
    {
        $input 		= $this->input->post(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $investor 	= $this->user_info->investor;

        if ( ! isset($input['target_id']))
        {
            $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
        }
        $targetId = $input['target_id'];

        $target = $this->target_model->get($targetId);
        if(!isset($target)) {
            $this->response(array('result' => 'ERROR','error' => TARGET_NOT_EXIST));
        }else if($user_id != $target->user_id) {
            $this->response(array('result' => 'ERROR','error' => PERMISSION_DENY));
        }

        if($investor != 0) {
            $this->response(array('result' => 'ERROR','error' => IS_INVERTOR));
        }

        $this->load->library('Certification_lib');
        $result = $this->certification_lib->verify_certifications($target, 1);
        if ($result)
        {
            $this->load->helper('product');
            if (is_judicial_product($target->product_id) === FALSE)
            {
                $this->target_model->update($targetId, [
                    'status' => TARGET_WAITING_APPROVE,
                    'certificate_status' => TARGET_CERTIFICATE_SUBMITTED
                ]);
            }
            $this->response(['result' => 'SUCCESS']);
        }
        else
            $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function simplificationfinancial_post()
    {
        $certification_id 	= CERTIFICATION_SIMPLIFICATIONFINANCIAL;
        $certification 		= $this->certification[$certification_id];
        if($certification && $certification['status']==1){
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= array();

            $file_fields 	= ['passbook_image'];
            foreach ($file_fields as $field) {
                $list = false;
                $image_ids = isset($input[$field]) ? explode(',',$input[$field]) : [];
                if(count($image_ids) > 0){
                    if (count($image_ids) > 15) {
                        $image_ids = array_slice($image_ids,0,15);
                    }
                    $list = $this->log_image_model->get_many_by([
                        'id'		=> $image_ids,
                        'user_id'	=> $this->user_info->originalID,
                    ]);
                }

                if($list && count($list)==count($image_ids)){
                    $content[$field] = [];
                    foreach($list as $k => $v){
                        $content[$field][] = $v->url;
                    }
                }else{
                    $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
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

	public function simplificationjob_post()
    {
		$certification_id 	= CERTIFICATION_SIMPLIFICATIONJOB;
		$certification 		= $this->certification[$certification_id];
		if($certification && $certification['status']==1){
            //是否驗證過
            $this->was_verify($certification_id);

			$input 		= $this->input->post(NULL, TRUE);
			$user_id 	= $this->user_info->id;
			$investor 	= $this->user_info->investor;
			$content	= [];
			$file_fields= [];

            //必填欄位
            $send_mail = false;
            if(isset($input['labor_type'])){
                if($input['labor_type']==0){
                    $file_fields[] = 'labor_image';
                }
                elseif($input['labor_type']==1){
					$content['labor_type']=$input['labor_type'];
                    $this->mail_check($user_id,$investor);
                    $send_mail =true;
                }
            }

			foreach ($file_fields as $field) {
                $list = false;
				$image_ids = isset($input[$field]) ? explode(',',$input[$field]) : [];
				if(count($image_ids) > 0){
                    if(count($image_ids)>15){
                        $image_ids = array_slice($image_ids,0,15);
                    }
                    $list = $this->log_image_model->get_many_by([
                        'id'		=> $image_ids,
                        'user_id'	=> $this->user_info->originalID,
                    ]);
                }

				if($list && count($list)==count($image_ids)){
					$content[$field] = [];
					foreach($list as $k => $v){
						$content[$field][] = $v->url;
					}
				}else{
					$this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
				}
			}

            isset($input['LaborQryDate']) ? $content['LaborQryDate'] = $input['LaborQryDate'] : "";
            isset($input['LaborInsSalary']) ? $content['LaborInsSalary'] = $input['LaborInsSalary'] : "";

			$param		= [
				'user_id'			=> $user_id,
				'certification_id'	=> $certification_id,
				'investor'			=> $investor,
				'content'			=> json_encode($content),
			];

            $rs = $this->user_certification_model->insert($param);
			if($rs){
			    if($send_mail){
                    $this->notification_lib->notice_cer_job($user_id);
                }
				$this->response(['result' => 'SUCCESS']);
			}else{
				$this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
			}
		}
		$this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    // 確認使用者與登記負責人關係
    public function profile_get()
    {
        $input = $this->input->get(NULL, TRUE);
        if(isset($input['target_id']) && is_numeric($input['target_id'])){
            $user_id = $this->user_info->id;

            // 使用者為法人時
            if($this->user_info->company_status == 1){
                $user_id = '';
                $investor = '';
                $user_info = $this->user_model->get_by(array( 'phone' => $this->user_info->phone, 'company_status' => 0));
                if(!empty($user_info)){
                    $user_id = $user_info->id;
                }else{
                    $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
                }
            }

            $this->load->model('loan/target_associate_model');

            $this->target_associate_model->limit(1)->order_by("id", "desc");
            $target_associate_info = $this->target_associate_model->get_by(['user_id'=>$user_id,'target_id'=>$input['target_id']]);
            if($target_associate_info){
                $character = isset($target_associate_info->character) && is_numeric($target_associate_info->character) ? (int)$target_associate_info->character : '';

                // 根據擔任角色與負責人關係給予相對應個人資料表應有狀態
                // 配偶擔任保證人
                if($target_associate_info->character == 3 && $target_associate_info->guarantor == 1){
                    $character = 4;
                }

                $response = [
                    'relaction_type' => $character
                ];

                $this->response(array('result' => 'SUCCESS', 'data' => $response));
            }else{
                // 找不到資料
                $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NOT_EXIST));
            }
        }
        $this->response(array('result' => 'SUCCESS', 'data' => []));
    }

    public function profile_post()
    {
        $certification_id 	= CERTIFICATION_PROFILE;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $time = time();
            $content	= [];

            $cer_profilejudicial = $this->config->item('cer_profile');
            //選填欄位
            // $fields 	= ['PrCurAddrZip','PrCurAddrZipName','PrCurlAddress','PrTelAreaCode','PrTelNo','PrTelExt','PrMobileNo','RealPr','IsPrSpouseGu','PrStartYear','PrEduLevel','OthRealPrRelWithPr','OthRealPrName','OthRealPrId','OthRealPrBirth','OthRealPrStartYear','OthRealPrTitle','OthRealPrSHRatio','GuOneRelWithPr','GuOneCompany','GuTwoRelWithPr','GuTwoCompany','SpouseCurAddrZip','SpouseCurAddrZipName','SpouseCurlAddress','SpouseMobileNo','SpouseTelAreaCode','SpouseTelNo','SpouseTelExt','GuOneCurAddrZip','GuOneCurAddrZipName','GuOneCurlAddress','GuOneTelAreaCode','GuOneTelNo','GuOneTelExt','GuOneMobileNo','GuTwoCurAddrZip','GuTwoCurAddrZipName','GuTwoCurlAddress','GuTwoTelAreaCode','GuTwoTelNo','GuTwoTelExt','GuTwoMobileNo','CompType','EmployeeNum','ShareholderNum'];
            // foreach ($fields as $field) {
            //     if (isset($input[$field])) {
            //         $content[$field] = $input[$field];
            //     }
            // }
            $content = $input;
            $content['skbank_form'] = $input;

            // 個人資料表加入歸戶關係
            if(isset($input['target_id'])){
                $this->load->model('loan/target_associate_model');
                $associate_info = $this->target_associate_model->order_by('id', 'desc')->get_by(array(
                    'user_id' => $user_id,
                    'status' => 1,
                    'target_id' => $input['target_id'],
                    'is_applicant' => 0
                ));
                if($associate_info){
                    // 與負責人關係轉為新光代號
                    $associate_mapping = [
                        '0' => 'A',
                        '1' => 'B',
                        '2' => 'C',
                        '3' => 'D',
                        '4' => 'E',
                        '5' => 'F',
                        '6' => 'G',
                        '7' => 'H',
                    ];
                    $relationship = $associate_info->relationship;
                    if(isset($associate_mapping[$relationship])){
                        $associate_value = $associate_mapping[$relationship];
                        $content['skbank_form']['OthRealPrRelWithPr'] = $associate_value;
                        $content['skbank_form']['GuOneRelWithPr'] = $associate_value;
                        $content['skbank_form']['GuTwoRelWithPr'] = $associate_value;
                    }
                }
            }

            $res = $content;

            $param = [
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'investor' => $investor,
                'content' => json_encode($res),
            ];
            $insert = $this->user_certification_model->insert($param);
            if ($insert) {
                // $this->certification_lib->set_success($insert);
                $this->response(['result' => 'SUCCESS']);
            } else {
                $this->response(['result' => 'ERROR', 'error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NOT_ACTIVE));
    }

    public function businesstax_post()
    {
        $certification_id 	= 1000;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['business_tax_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['business_tax_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>6){
                    $image_ids = array_slice($image_ids,0,6);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function balancesheet_post()
    {
        $certification_id 	= 1001;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['balance_sheet_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['balance_sheet_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>6){
                    $image_ids = array_slice($image_ids,0,6);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function incomestatement_post()
    {
        $certification_id 	= 1002;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            //改圖片欄位可選填，公司成立小於一年沒有損益表
            // $fields 	= ['income_statement_image'];
            // foreach ($fields as $field) {
            //     if (empty($input[$field])) {
            //         $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            //     }else{
            //         $content[$field] = $input[$field];
            //     }
            // }

            $file_fields = ['income_statement_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                if(isset($input[$field]) && !empty($input[$field])){
                    $image_ids = explode(',',$input[$field]);
                    if(count($image_ids)>6){
                        $image_ids = array_slice($image_ids,0,6);
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
            }

			// 使用者手填資料
			$content['skbank_form'] = $input;

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    /**
     * @api {post} /v2/certification/investigationjudicial 認證 聯合徵信(法人)
     * @apiVersion 0.2.0
     * @apiName PostCertificationInvestigationjudicial
     * @apiGroup Certification
     * @apiHeader {String} request_token 登入後取得的 Request Token
     *
     * @apiParam {String=0,1} 寄回方式 0:由郵局 1:由聯徵中心
     * @apiParam {Number} receipt_postal_image 郵局申請的收執聯 ( 圖片IDs，最多15張，以逗號隔開)
     * @apiParam {Number} receipt_jcic_image 臨櫃申請的收執聯 ( 圖片IDs，最多15張，以逗號隔開)
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
    public function investigationjudicial_post()
    {
        $certification_id 	= 1003;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['return_type'];
            foreach ($fields as $field) {
                if (! isset($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['legal_person_mq_image','postal_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                if(isset($input[$field])){
                    $image_ids = explode(',',$input[$field]);
                    if(count($image_ids)>15){
                        $image_ids = array_slice($image_ids,0,15);
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
    					$content['group_id'] = isset($list[0]->group_info) ? $list[0]->group_info : '';
                    }else{
                        $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
                    }
                }
            }

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                if (isset($input['target_id']))
                {
                    $a11_param = $this->_get_investigationa11($input['target_id']);
                    if ( $a11_param && ! $this->user_certification_model->insert_many($a11_param))
                    {
                        $this->response(['result' => 'ERROR', 'error' => INSERT_ERROR]);
                    }
                }

                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    // 取得負責人與負責人配偶(若有)的聯徵A11
    private function _get_investigationa11(int $target_id)
    {
        $certification_id = CERTIFICATION_INVESTIGATIONA11;
        // 找不到徵信項設定
        if ( ! isset($this->certification[$certification_id]))
        {
            $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NOT_ACTIVE));
        }

        // 徵信項已提交驗證
        if ($this->was_verify($certification_id, FALSE) === TRUE)
        {
            return FALSE;
        }

        // 如果從法人端登入上傳則將資料上傳位置更換為自然人ID
        if ($this->user_info->company_status == 1)
        {
            $natural_user = $this->user_model->get_by(array('phone' => $this->user_info->phone, 'company_status' => 0, 'status' => 1, 'block_status' => 0));
            if (empty($natural_user))
            {
                $this->response(['result' => 'ERROR', 'error' => INSERT_ERROR]);
            }
            $user_id = $natural_user->id;
        }
        else
        {
            $user_id = $this->user_info->id;
        }

        $param = [
            [
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'investor' => BORROWER,
                'content' => json_encode([]),
                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
            ]
        ];

        $this->load->model('loan/target_associate_model');
        $target_associates = $this->target_associate_model->get_by([
            'target_id' => $target_id,
            'character' => ASSOCIATES_CHARACTER_SPOUSE,
            'status' => [ASSOCIATES_STATUS_WAITTING_APPROVE, ASSOCIATES_STATUS_APPROVED]
        ]);
        // 找不到配偶
        if (empty($target_associates))
        {
            return $param;
        }
        if ( ! $target_associates->user_id)
        { // 不知道是誰
            if ($this->user_certification_model->get_by(['user_id' => $user_id, 'status' => CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE, 'certification_id' => $certification_id]))
            {
                return $param;
            }
            $param[] = [
                'user_id' => $user_id, // 暫時用負責人ID
                'certification_id' => $certification_id,
                'investor' => BORROWER,
                'content' => json_encode([]),
                'status' => CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE // 待配偶歸戶
            ];
        }
        elseif ( ! $this->certification_lib->get_certification_info($target_associates->user_id, $certification_id))
        { // 知道是誰，且該配偶名下無此徵信項
            $param[] = [
                'user_id' => $target_associates->user_id,
                'certification_id' => $certification_id,
                'investor' => BORROWER,
                'content' => json_encode([]),
                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW
            ];
        }
        return $param;
    }

	// 負責人聯徵
	public function investigationa11_post()
    {
        $certification_id 	= 12;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['return_type','person_mq_image'];
            foreach ($fields as $field) {
                if (empty($input[$field]) && $input[$field] != 0) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['person_mq_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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
					$content['group_id'] = isset($list[0]->group_info) ? $list[0]->group_info : '';
					$content['mail_file_status'] = 1;
                }else{
                    $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
                }
            }

            // 如果從發人端登入上傳則將資料上傳位置更換為自然人ID
            if($this->user_info->company_status == 1){
                $user_id = '';
                $this->user_info = $this->user_model->get_by(array( 'phone' => $this->user_info->phone, 'company_status' => 0, 'status' => 1, 'block_status' => 0));

                if(!empty($this->user_info)){
                    $user_id = $this->user_info->id;
                }
            }

            if(empty($user_id)){
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> 0,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function passbookcashflow_post()
    {
        $certification_id 	= 1004;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['passbook_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['passbook_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
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
     * @apiParam {Number} front_image 金融卡正面照 ( 圖片ID )
     * @apiParam {Number} back_image 金融卡背面照 ( 圖片ID )
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
    public function passbook_post()
    {
        $this->load->model('user/user_bankaccount_model');
        $certification_id = CERTIFICATION_PASSBOOK;
        $certification = $this->certification[$certification_id];
        if ($certification && $certification['status'] == CERTIFICATION_STATUS_SUCCEED)
        {
            //是否驗證過
            $this->was_verify($certification_id);

            $input = $this->input->post(NULL, TRUE);
            $user_id = $this->user_info->id;
            $investor = $this->user_info->investor;
            $company = $this->user_info->company;
            $content = [];

            //必填欄位
            $fields = ['bank_code', 'branch_code', 'bank_account'];
            foreach ($fields as $field)
            {
                if (empty($input[$field]))
                {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                }
                else
                {
                    $content[$field] = trim($input[$field]);
                }
            }

            if (strlen($content['bank_code']) != 3)
            {
                $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BANK_CODE_ERROR));
            }
            if (strlen($content['branch_code']) != 4)
            {
                $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BRANCH_CODE_ERROR));
            }
            if (strlen(intval($content['bank_account'])) < 8 || strlen($content['bank_account']) < 10 || strlen($content['bank_account']) > 14 || is_virtual_account($content['bank_account']))
            {
                $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BANK_ACCOUNT_ERROR));
            }

            // TODO: 存摺如果是公司，需要驗證哪些徵信項

            $where = [
                'investor' => $investor,
                'bank_code' => $content['bank_code'],
                'bank_account' => $content['bank_account'],
                'status' => CERTIFICATION_STATUS_SUCCEED,
            ];

            $user_bankaccount = $this->user_bankaccount_model->get_by($where);
            if ($user_bankaccount)
            {
                $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_BANK_ACCOUNT_EXIST));
            }

            //上傳檔案欄位
            $file_fields = ['front_image', 'back_image'];
            foreach ($file_fields as $field)
            {
                $image_id = intval($input[$field]);
                if ( ! $image_id)
                {
                    $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                }
                else
                {
                    $rs = $this->log_image_model->get_by([
                        'id' => $image_id,
                        'user_id' => $this->user_info->originalID,
                    ]);

                    if ($rs)
                    {
                        $content[$field] = $rs->url;
                    }
                    else
                    {
                        $this->response(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT));
                    }
                }
            }

            $param = [
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'investor' => $investor,
                'expire_time' => strtotime('+20 years'),
                'content' => json_encode($content),
            ];

            $insert = $this->user_certification_model->insert($param);
            if ($insert)
            {
                $bankaccount_info = [
                    'user_id' => $user_id,
                    'investor' => $investor,
                    'user_certification_id' => $insert,
                    'bank_code' => $content['bank_code'],
                    'branch_code' => $content['branch_code'],
                    'bank_account' => $content['bank_account'],
                    'front_image' => $content['front_image'],
                    'back_image' => $content['back_image'],
                ];

                if ($investor)
                {
                    $bankaccount_info['verify'] = 2;
                }
                else
                {
                    isset($this->user_info->naturalPerson) ? $user_id = [$user_id, $this->user_info->originalID] : '';
                    $this->certification_lib->set_success($insert);
                    $target = $this->target_model->get_by([
                        'user_id' => $user_id,
                        'status' => TARGET_WAITING_VERIFY,
                    ]);
                    if ($target)
                    {
                        $bankaccount_info['verify'] = 2;
                    }
                }

                $this->user_bankaccount_model->insert($bankaccount_info);

                $this->response(array('result' => 'SUCCESS'));
            }
            else
            {
                $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
            }
        }
        $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NOT_ACTIVE));
    }

    public function governmentauthorities_post()
    {
        $certification_id 	= 1007;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['governmentauthorities_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['governmentauthorities_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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
										// 變卡為圖片多對一ID,需額外存取 group id
										$content['group_id'] = isset($list[0]->group_info) ? $list[0]->group_info : '';
                }else{
                    $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
                }
            }

			// 寫入使用者手填資料
			$content['skbank_form'] = [
			  'CompId' => isset($input['CompId']) ? $input['CompId'] : '',
			  'CompName' => isset($input['CompName']) ? $input['CompName'] : '',
			  'CompCapital' => isset($input['CompCapital']) ? $input['CompCapital'] : '',
			  'CompRegAddress' => isset($input['CompRegAddress']) ? $input['CompRegAddress'] : '',
			  'PrName' => isset($input['PrName']) ? $input['PrName'] : '',
			  'PrincipalId' => isset($inout['PrincipalId']) ? $inout['PrincipalId'] : ''
			];

			// 董監事
			$count_array =[
				'1' => 'A',
				'2' => 'B',
				'3' => 'C',
				'4' => 'D',
				'5' => 'E',
				'6' => 'F',
				'7' => 'G',
			];
			for($i=1;$i<=7;$i++){
				$content['skbank_form']["Director{$count_array[$i]}Id"] = isset($input["Director{$count_array[$i]}Id"]) ? $input["Director{$count_array[$i]}Id"] : '';
				$content['skbank_form']["Director{$count_array[$i]}Name"] = isset($input["Director{$count_array[$i]}Name"]) ? $input["Director{$count_array[$i]}Name"] : '';
			}

            // 商業司爬蟲
            $company_user_info = $this->user_model->get_by(array( 'id' => $this->user_info->id ));
            if($company_user_info && !empty($company_user_info->id_number)){
                $this->load->library('scraper/Findbiz_lib');
                $this->findbiz_lib->requestFindBizData($company_user_info->id_number);
            }

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function charter_post()
    {
        $certification_id 	= 1008;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['charter_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['charter_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function registerofmembers_post()
    {
        $certification_id 	= 1009;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['registerofmembers_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['registerofmembers_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function mainproductstatus_post()
    {
        $certification_id 	= 1010;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['mainproductstatus_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['mainproductstatus_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function startupfunds_post()
    {
        $certification_id 	= 1011;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['startupfunds_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['startupfunds_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function business_plan_post()
    {
        $certification_id 	= 1012;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['business_plan_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['business_plan_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function verification_post()
    {
        $certification_id 	= 1013;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['verification_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['verification_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function condensedbalancesheet_post()
    {
        $certification_id 	= 1014;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['condensedbalancesheet_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['condensedbalancesheet_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function condensedincomestatement_post()
    {
        $certification_id 	= 1015;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['condensedincomestatement_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['condensedincomestatement_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function purchasesalesvendorlist_post()
    {
        $certification_id 	= 1016;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['purchasesalesvendorlist_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['purchasesalesvendorlist_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function employeeinsurancelist_post()
    {
        $certification_id 	= 1017;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['employeeinsurancelist_image', 'affidavit_image'];
            $empty_flag = TRUE;
            foreach ($fields as $field) {
                if (isset($input[$field]) && ! empty($input[$field]))
                {
                    $empty_flag = FALSE;
                    $content[$field] = $input[$field];
                }
            }
            if($empty_flag)
            {
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }

            $file_fields = ['employeeinsurancelist_image', 'affidavit_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                if ( ! isset($content[$field]) || empty($content[$field]))
                {
                    continue;
                }
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

			// 使用者手填資料
            $content['skbank_form'] = [
				'ReportTime' => isset($input['ReportTime']) ? $input['ReportTime'] : '',
				'CompName' => isset($input['CompName']) ? $input['CompName'] : '',
				'range' => isset($input['range']) ? $input['range'] : '',
			];
			foreach($input as $k=>$v){
				if(preg_match('/NumOfInsuredYM|NumOfInsured/',$k)){
					$content['skbank_form'][$k] = $v;
				}
			}

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	public function salesdetail_post()
    {
        $certification_id 	= 2000;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //是否驗證過
            $this->was_verify($certification_id);

            //必填欄位
            $fields 	= ['car_sales_image'];
            foreach ($fields as $field) {
                if (empty($input[$field])) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $content[$field] = $input[$field];
                }
            }

            $file_fields = ['car_sales_image'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                $image_ids = explode(',',$content[$field]);
                if(count($image_ids)>15){
                    $image_ids = array_slice($image_ids,0,15);
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

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(['result' => 'SUCCESS']);
            }else{
                $this->response(['result' => 'ERROR','error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

	public function profilejudicial_post()
    {
        $certification_id 	= CERTIFICATION_PROFILEJUDICIAL;
        $certification 		= $this->certification[$certification_id];
        if($certification){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $time = time();
            $content	= [];

            $cer_exists = $this->user_certification_model->get_by([
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'status' => 4,
            ]);
            if (isset($input['save']) && $input['save']) {
                $param = [
                    'user_id' => $user_id,
                    'certification_id' => $certification_id,
                    'investor' => $investor,
                    'content' => json_encode($input),
                    'status' => 4,
                ];

                if ($cer_exists) {
                    $input = (object)array_merge((array)json_decode($cer_exists->content), (array)$input);
                    $rs = $this->user_certification_model->update($cer_exists->id, [
                        'content' => json_encode($input),
                    ]);
                } else {
                    $rs = $this->user_certification_model->insert($param);
                }
                if ($rs) {
                    $this->response(['result' => 'SUCCESS','msg' => 'SAVED']);
                }
            }

            //是否驗證過
            if(!$cer_exists || $cer_exists->status != 4){
                $this->was_verify($certification_id);
            }

            $cer_profilejudicial = $this->config->item('cer_profilejudicial');

            // 選填欄位
            $fields 	= ['CompMajorAddrZip','CompMajorAddrZipName','CompMajorAddress','CompMajorCityName','CompMajorAreaName','CompMajorSecName','CompMajorSecNo','CompMajorOwnership','CompMajorSetting','CompTelAreaCode','CompTelNo','CompTelExt','BusinessType','Comptype','IsBizRegAddrSelfOwn','BizRegAddrOwner','IsBizAddrEqToBizRegAddr','RealBizAddrCityName','RealBizAddrAreaName','RealBizAddrRoadName','RealBizAddrRoadType','RealBizAddrSec','RealBizAddrLn','RealBizAddrAly','RealBizAddrNo','RealBizAddrNoExt','RealBizAddrFloor','RealBizAddrFloorExt','RealBizAddrRoom','RealBizAddrOtherMemo','IsRealBizAddrSelfOwn','RealBizAddrOwner','BizTaxFileWay','DirectorAName','DirectorAId','DirectorBName','DirectorBId','DirectorCName','DirectorCId','DirectorDName','DirectorDId','DirectorEName','DirectorEId','DirectorFName','DirectorFId','DirectorGName','DirectorGId','main_business','main_product','history','contectName','mainBuildSetting','DocTypeA03',

                // 員工人數
                'EmployeeNum',

                // 股東人數
                'ShareholderNum',

                // 公司產業別
                'CompDuType'
            ];
            foreach ($fields as $field) {
                if (isset($input[$field])) {
                    $content[$field] = $input[$field];
                }
            }
            $content['skbank_form'] = $input;

            $file_fields = ['BizLandOwnership','BizHouseOwnership','RealLandOwnership','RealHouseOwnership','DocTypeA03'];
            //多個檔案欄位
            foreach ($file_fields as $field) {
                if(isset($input[$field]) && !empty($input[$field])){
                    $image_ids = explode(',',$input[$field]);
                    if(count($image_ids)>15){
                        $image_ids = array_slice($image_ids,0,15);
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
            }

            $res = $content;

            $param = [
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'investor' => $investor,
                'content' => json_encode($res),
            ];
            if ($cer_exists) {
                $param['status'] = 0;
                $rs = $this->user_certification_model->update($cer_exists->id, $param);
            }else{
                $rs = $this->user_certification_model->insert($param);
            }
            if ($rs) {
                $this->response(['result' => 'SUCCESS']);
            } else {
                $this->response(['result' => 'ERROR', 'error' => INSERT_ERROR]);
            }
        }
        $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_NOT_ACTIVE));
    }

    private function was_verify($certification_id = 0, $need_output = TRUE)
    {
        if(isset($this->user_info->naturalPerson) && $certification_id < 1000) {
            $this->user_info->id = $this->user_info->naturalPerson->id;
        }
        $user_certification	= $this->certification_lib->get_certification_info($this->user_info->id,$certification_id,$this->user_info->investor);
        if($user_certification){
            if ($need_output === TRUE)
            {
                $this->response(array('result' => 'ERROR', 'error' => CERTIFICATION_WAS_VERIFY));
            }
            return TRUE;
        }
        return FALSE;
    }


	private function mail_check($user_id,$investor){
        $user_certification	= $this->certification_lib->get_certification_info($user_id,6,$investor);
        if(!$user_certification||$user_certification->status!=1){
            $this->response(array('result' => 'ERROR','error' => NOT_VERIFIED_EMAIL ));
        }
	}

    private function social_initialize($user_id, $investor, $certification_id = CERTIFICATION_SOCIAL)
    {
        $content = [
			'facebook' => '',
			'instagram' => '',
        ];
        $param	= [
			'user_id'			=> $user_id,
			'certification_id'	=> $certification_id,
			'investor'			=> $investor,
			'content'			=> json_encode($content),
            'status'            => CERTIFICATION_STATUS_PENDING_TO_VALIDATE
        ];
        $insert_id = $this->user_certification_model->insert($param);
        if($insert_id){
			return $insert_id;
        }else{
			$this->response(array('result' => 'ERROR','error' => INSERT_ERROR));
        }
	}

    public function companyemail_post()
    {
        $certification_id 	= CERTIFICATION_COMPANYEMAIL;
        $certification 		= $this->certification[$certification_id];
        if($certification && $certification['status']==1){
            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= array();
            $time = time();

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

            // $res['result'][$time] = $content;
            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'content'			=> json_encode($content),
            ];
            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->load->library('Sendemail');
                $this->sendemail->send_verify_email($insert,$content['email'],$investor, 'company');
                $this->response(array('result' => 'SUCCESS'));
            }else{
                $this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function judicialguarantee_post()
    {
        $certification_id 	= CERTIFICATION_JUDICIALGUARANTEE;
        $certification 		= $this->certification[$certification_id];
        if($certification && $certification['status']==1){
            $input = $this->input->post(NULL, TRUE);
            $user_id = $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $image_id = isset($input['image']) ? $input['image'] : '';
            $time = time();
            $content = array();

            //是否驗證過
            $this->was_verify($certification_id);

            // 檢查是否存在歸戶資料
            $this->load->model('user/judicial_person_model');
            $judicial_person_info = $this->judicial_person_model->get_by(['company_user_id' => $user_id]);
            // if(!$judicial_person_info){
            //     $this->response(array('result' => 'ERROR','error' => NO_CER_GOVERNMENTAUTHORITIES ));
            // }

            // 檢查圖片是否存在
            $image_info = $this->log_image_model->get_by([
                'id'		=> $image_id,
                'user_id'	=> $user_id,
            ]);
            if(!$image_info || !isset($image_info->url)){
                $this->response(array('result' => 'ERROR','error' => PICTURE_NOT_EXIST ));exit;
            }

            // 更新歸戶資料，加入對保自拍圖片
            $data_content = [];
            $data_content = isset($judicial_person_info->sign_video) && json_decode($judicial_person_info->sign_video,true) ? json_decode($judicial_person_info->sign_video,true) : [];
            $data_content['image_url'] = $image_info->url;
            $this->judicial_person_model->update_by(['company_user_id' => $user_id],['sign_video' => json_encode($data_content), 'status' => 0]);

            $res['image_url'] = $image_info->url;

            $param = [
                'user_id' => $user_id,
                'certification_id' => $certification_id,
                'investor' => $investor,
                'content' => json_encode($res),
            ];
            $insert = $this->user_certification_model->insert($param);
            if ($insert) {
                $this->response(array('result' => 'SUCCESS'));
            } else {
                $this->response(array('result' => 'ERROR', 'error' => INSERT_ERROR));
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    /**
     * @api {post} /v2/certification/criminal_record 認證 良民證
     * @apiVersion 0.2.0
     * @apiName PostCertificationCriminalRecord
     * @apiGroup Certification
     * @apiDescription 上傳良民證。
     * @apiHeader {String} request_token 登入後取得的 Request Token
     * @apiParam {Number} criminal_record_image 良民證照片 ( 圖片ID )
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
     *
     *
     */
    public function criminal_record_post()
    {
        $this->load->model('user/user_bankaccount_model');
        $certification_id 	= CERTIFICATION_CRIMINALRECORD;
        $certification 		= $this->certification[$certification_id];
        if($certification && $certification['status']==1){
            //是否驗證過
            $this->was_verify($certification_id);

            $input 		= $this->input->post(NULL, TRUE);
            $user_id 	= $this->user_info->id;
            $investor 	= $this->user_info->investor;
            $content	= [];

            //上傳檔案欄位
            $file_fields 	= ['criminal_record_image'];
            foreach ($file_fields as $field) {
                $image_id = intval($input[$field]);
                if (!$image_id) {
                    $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                }else{
                    $rs = $this->log_image_model->get_by([
                        'id'		=> $image_id,
                        'user_id'	=> $this->user_info->originalID,
                    ]);

                    if($rs){
                        $content[$field] = $rs->url;
                    }else{
                        $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
                    }
                }
            }

            $param		= [
                'user_id'			=> $user_id,
                'certification_id'	=> $certification_id,
                'investor'			=> $investor,
                'expire_time'		=> strtotime('+20 years'),
                'content'			=> json_encode($content),
                'status'            => 3,
            ];

            $insert = $this->user_certification_model->insert($param);
            if($insert){
                $this->response(array('result' => 'SUCCESS'));
            }else{
                $this->response(array('result' => 'ERROR','error' => INSERT_ERROR ));
            }
        }
        $this->response(array('result' => 'ERROR','error' => CERTIFICATION_NOT_ACTIVE ));
    }

    public function livingBody_post(){
        $input 		= $this->input->post(NULL, TRUE);
        $user_id 	= $this->user_info->id;
        $investor 	= $this->user_info->investor;

        //必填欄位
        $fields 	= ['imageId'];
        foreach ($fields as $field) {
            if(isset($input[$field])){
                $content[$field] = $input[$field];
            }else{
                $this->response(array('result' => 'ERROR','error' => INPUT_NOT_CORRECT ));
            }
        }

        // 檢查圖片是否存在
        $image_info = $this->log_image_model->get_by([
            'id'		=> $content['imageId'],
            'user_id'	=> $user_id,
        ]);
        if(!$image_info || !isset($image_info->url)){
            $this->response(array('result' => 'ERROR','error' => PICTURE_NOT_EXIST ));
        }

        $this->load->library('Papago_lib');
		$face8_person_face = $this->papago_lib->detect($image_info->url, $user_id);
        $this->response(array('result' => 'SUCCESS','data' => $face8_person_face ));
    }
}
