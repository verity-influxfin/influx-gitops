<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Website extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();
		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->library('Contract_lib');
        $method = $this->router->fetch_method();
        $nonAuthMethods = ['list'];
		if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }

			//只限出借人
			if($tokenData->investor != 1){
				$this->response(array('result' => 'ERROR','error' => NOT_INVERTOR ));
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
				$this->log_request_model->insert([
					'method' 	=> $this->request->method,
					'url'	 	=> $this->uri->uri_string(),
					'investor'	=> $tokenData->investor,
					'user_id'	=> $tokenData->id,
					'agent'		=> $tokenData->agent,
				]);
			}

			$this->user_info->investor 		= $tokenData->investor;
			$this->user_info->company 		= $tokenData->company;
			$this->user_info->incharge 		= $tokenData->incharge;
			$this->user_info->agent 		= $tokenData->agent;
			$this->user_info->expiry_time 	= $tokenData->expiry_time;
        }
    }

	/**
     * @api {get} /v2/target/list 出借方 取得標的列表
	 * @apiVersion 0.2.0
	 * @apiName GetTargetList
     * @apiGroup Target
	 * @apiParam {Number=5,10} [status=5] 案件狀態
	 * @apiParam {Number} [product_id] 產品編號
	 * @apiParam {String=credit_level,instalment,interest_rate,created_at} [orderby="credit_level"] 排序值
	 * @apiParam {String=asc,desc} [sort=asc] 降序/升序
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Targets ID
	 * @apiSuccess {String} target_no 標的號
	 * @apiSuccess {Number} product_id 產品ID
	 * @apiSuccess {Number} credit_level 信用評等
	 * @apiSuccess {Number} user_id User ID
	 * @apiSuccess {Object} user 借款人基本資訊
	 * @apiSuccess {String} user.sex 性別 F/M
	 * @apiSuccess {Number} user.age 年齡
	 * @apiSuccess {String} user.company_name 單位名稱
	 * @apiSuccess {Number} loan_amount 核准金額
	 * @apiSuccess {Number} interest_rate 年化利率
	 * @apiSuccess {Number} instalment 期數
	 * @apiSuccess {Number} repayment 還款方式
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} invested 目前投標量
	 * @apiSuccess {String} reason 借款原因
	 * @apiSuccess {Number} status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還 8:轉貸的標的
	 * @apiSuccess {Number} created_at 申請日期
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				{
     * 					"id": 30,
     * 					"target_no": "STN2019011414457",
     * 					"product_id": 1,
     * 					"credit_level": 6,
     * 					"user_id": 1,
     * 					"user": {
     * 						"sex": "M",
     * 						"age": 29,
     * 						"company_name": "國立宜蘭大學"
     * 					},
     * 					"loan_amount": 5000,
     * 					"interest_rate": 10,
     * 					"instalment": 3,
     * 					"repayment": 1,
     * 					"expire_time": 1547792055,
     * 					"invested": 0,
     * 					"reason": "",
     * 					"status": 3,
     * 					"sub_status": 0,
     * 					"created_at": 1547455529
     * 				}
     * 			]
     * 		}
     *    }
     */

	public function list_get()
    {
		$input 			= $this->input->get();
		$list			= [];
        $where			= [];
		if(!isset($input['status']) || !in_array($input['status'],[5, 10])) {
            $this->response(array('result' => 'SUCCESS','data' => [ 'list' => [] ] ));
        }else
            $where['status'] = $input['status'];

		if(isset($input['product_id'])) {
            $where['product_id'] = $input['product_id'];
        }

		$orderby 		= isset($input['orderby'])&&in_array($input['orderby'],array('credit_level','instalment','interest_rate','created_at'))?$input['orderby']:'credit_level';
		$sort			= isset($input['sort'])&&in_array($input['sort'],array('desc','asc'))?$input['sort']:'asc';
		$this->target_model->order_by($orderby,$sort);

		// 已結案的只能撈一百筆
		if($input['status'] == 10) {
            $this->target_model->limit(100);
        }
        $target_list  = $this->target_model->get_many_by($where);
        $product_list = $this->config->item('product_list');
        $user_meta = new stdClass();

        if(!empty($target_list)){
            foreach($target_list as $key => $value){
				$user_info 	= $this->user_model->get($value->user_id);
				$user		= [];
				if($user_info){
                    $product = $product_list[$value->product_id];
                    $sub_product_id = $value->sub_product_id;
                    $product_name = $product['name'];
                    if($this->is_sub_product($product,$sub_product_id)){
                        $product = $this->trans_sub_product($product,$sub_product_id);
                        $product_name = $product['name'];
                    }

                    $age = get_age($user_info->birthday);
                    if ($product_list[$value->product_id]['identity'] == 1) {
                        $user_meta = $this->user_meta_model->get_by(['user_id' => $value->user_id, 'meta_key' => 'school_name']);
                        if (is_object($user_meta)) {
                            $user_meta->meta_value = preg_replace('/\(自填\)/', '', $user_meta->meta_value);
                        } else {
                            $user_meta = new stdClass();
                            $user_meta->meta_value = '未提供學校資訊';
                        }
                    } elseif ($product_list[$value->product_id]['identity'] == 2) {
                        $meta_info = $this->user_meta_model->get_many_by([
                            'user_id' => $value->user_id,
                            'meta_key' => ['job_company', 'diploma_name']
                        ]);
                        if ($meta_info) {
                            $job_company = ($meta_info[0]->meta_key == 'job_company'
                                ? $meta_info[0]->meta_value
                                : (isset($meta_info[1]) >= 2
                                    ? $meta_info[1]->meta_value
                                    : false));
                            $diploma_name = $meta_info[0]->meta_key == 'diploma_name'
                                ? $meta_info[0]->meta_value
                                : (isset($meta_info[1]) >= 2
                                    ? $meta_info[1]->meta_value
                                    : false);
                            $user_meta->meta_value = $job_company ? $job_company : $diploma_name;
                        } else {
                            $user_meta = new stdClass();
                            $user_meta->meta_value = '未提供相關資訊';
                        }
                    }

                    $user = array(
                        'sex' 			=> $user_info->sex,
                        'age'			=> $age,
                        'company_name'	=> $user_meta?$user_meta->meta_value:'',
                    );

                    $targetDatas = [];
                    $targetData = json_decode($value->target_data);
                    if($product['visul_id'] == 'DS2P1'){
                        $targetDatas = [
                            'brand' => $targetData->brand,
                            'name' => $targetData->name,
                            'selected_image' => $targetData->selected_image,
                            'purchase_time' => $targetData->purchase_time,
                            'factory_time' => $targetData->factory_time,
                            'product_description' => $targetData->product_description,
                        ];
                        foreach ($product['targetData'] as $skey => $svalue) {
                            if(in_array($skey,['car_photo_front_image','car_photo_back_image','car_photo_all_image','car_photo_date_image','car_photo_mileage_image'])){
                                if(isset($targetData->$key) && !empty($targetData->$key)){
                                    $pic_array = [];
                                    foreach ($targetData->$key as $svalue){
                                        preg_match('/\/image.+/', $svalue,$matches);
                                        $pic_array[] = FRONT_CDN_URL.'stmps/tarda'.$matches[0];
                                    }
                                    $targetDatas[$key] = $pic_array;
                                }
                                else{
                                    $targetDatas[$key] = '';
                                }
                            }
                        }
                        $user = array(
                            'sex' 			=> '',
                            'age'			=> '',
                            'company_name'	=> '',
                        );
                    }
				}

                $reason = $value->reason;
                $json_reason = json_decode($reason);
                if(isset($json_reason->reason)){
                    $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                }

                $param = [
                    'id' 				=> intval($value->id),
                    'target_no' 		=> $value->target_no,
                    'product_name' => $product_name,
                    'product_id' 		=> intval($value->product_id),
                    'sub_product_id' => intval($value->sub_product_id),
                    'credit_level' 		=> intval($value->credit_level),
                    'user_id' 			=> intval($value->user_id),
                    'user' 				=> $user,
                    'loan_amount' 		=> intval($value->loan_amount),
                    'interest_rate' 	=> floatval($value->interest_rate),
                    'instalment' 		=> intval($value->instalment),
                    'repayment' 		=> intval($value->repayment),
                    'expire_time' 		=> intval($value->expire_time),
                    'invested' 			=> intval($value->invested),
                    'reason' 			=> $reason,
                    'targetDatas' => $targetDatas,
                    'isTargetOpaque' => $sub_product_id==9999?true:false,
                    'status' 			=> intval($value->status),
                    'sub_status' 		=> intval($value->sub_status),
                    'created_at' 		=> intval($value->created_at),
                ];

                isset($targetData->original_interest_rate) && $targetData->original_interest_rate != $value->interest_rate ? $param['is_rate_increase'] = true : '';

				$list[] = $param;
			}
		}

		$this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ] ));
    }

    private function sub_product_profile($product,$sub_product){
        return array(
            'id' => $product['id'],
            'visul_id' => $sub_product['visul_id'],
            'type' => $product['type'],
            'identity' => $product['identity'],
            'name' => $sub_product['name'],
            'description' => $sub_product['description'],
            'loan_range_s' => $sub_product['loan_range_s'],
            'loan_range_e' => $sub_product['loan_range_e'],
            'interest_rate_s' => $sub_product['interest_rate_s'],
            'interest_rate_e' => $sub_product['interest_rate_e'],
            'charge_platform' => $sub_product['charge_platform'],
            'charge_platform_min' => $sub_product['charge_platform_min'],
            'certifications' => $sub_product['certifications'],
            'instalment' => $sub_product['instalment'],
            'repayment' => $sub_product['repayment'],
            'targetData' => $sub_product['targetData'],
            'dealer' => $sub_product['dealer'],
            'multi_target' => $sub_product['multi_target'],
            'status' => $sub_product['status'],
        );
    }

    private function is_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        return isset($sub_product_list[$sub_product_id]['identity'][$product['identity']]) && in_array($sub_product_id,$product['sub_product']);
    }

    private function trans_sub_product($product,$sub_product_id){
        $sub_product_list = $this->config->item('sub_product_list');
        $sub_product_data = $sub_product_list[$sub_product_id]['identity'][$product['identity']];
        $product = $this->sub_product_profile($product,$sub_product_data);
        return $product;
    }
}
