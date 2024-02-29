<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Website extends REST_Controller {

	public $user_info;

    public function __construct()
    {
        parent::__construct();

		$this->load->model('loan/investment_model');
		$this->load->model('user/user_meta_model');
		$this->load->library('Contract_lib');
        $this->load->library('Transfer_lib');
    }

    private function _check_visit_freq($token_data, $freq=3, $interval=10)
    {
        $this->load->model('log/log_request_model');
        $rs = $this->log_request_model->get_many_by([
            'user_id' => $token_data->id,
            'investor' => $token_data->investor,
            'url' => $this->uri->uri_string(),
            'created_at >= ' => time() - $interval
        ]);
        if (count($rs) >= $freq)
        {
            return FALSE;
        }
        else
        {
            $this->log_request_model->insert([
                'method' => $this->request->method,
                'url' => $this->uri->uri_string(),
                'investor' => $token_data->investor,
                'user_id' => $token_data->id,
                'agent' => $token_data->agent,
            ]);
            return TRUE;
        }
    }
    private function _check_jwt_token($token) {
        if ( ! app_access())
        {
            $this->response(array('result' => 'ERROR', 'data' => []), 401);
        }

        $tokenData = AUTHORIZATION::getUserInfoByToken($token);
        if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time())
        {
            $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
        }

        $this->user_info = $this->user_model->get($tokenData->id);
        if ($tokenData->auth_otp != $this->user_info->auth_otp)
        {
            $this->response(array('result' => 'ERROR', 'error' => TOKEN_NOT_CORRECT));
        }
        return $tokenData;
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
        if(!app_access()){
            $this->response(array('result' => 'ERROR','data' => [ ] ), 401);
        }
		$input 			= $this->input->get();
		$list			= [];
        $where			= [];

        // User: 115088 ，不想被官網優質案例的API抓到
        $where['user_id != '] = 115088;
        
		if(!isset($input['status']) || !in_array($input['status'],[3, 10])) {
            $this->response(array('result' => 'SUCCESS','data' => [ 'list' => [] ] ));
        }else
            $where['status'] = $input['status'];

		if(isset($input['product_id'])) {
            $where['product_id'] = $input['product_id'];
        }

		$orderby 		= isset($input['orderby'])&&in_array($input['orderby'],array('loan_amount', 'credit_level','instalment','interest_rate','created_at'))?$input['orderby']:'created_at';
		$sort			= isset($input['sort'])&&in_array($input['sort'],array('desc','asc'))?$input['sort']:'desc';
		$this->target_model->order_by($orderby,$sort);

		// 已結案的只能撈五十筆
		if(! isset($input['limited']) || empty($input['limited']) || $input['limited'] >= 50) {
            $input['limited'] = 50;
        }
        $this->target_model->limit($input['limited']);
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

    /**
     * @api {get} /v2/transfer/transfer_list 出借方 債權標的列表
	 * @apiVersion 0.2.0
	 * @apiName GetTransferList
     * @apiGroup Transfer
	 * @apiHeader {String} request_token 登入後取得的 Request Token
	 * @apiParam {String=credit_level,instalment,interest_rate} [orderby="credit_level"] 排序值
	 * @apiParam {String=asc,desc} [sort=asc] 降序/升序
     *
	 * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {Number} id Transfer ID
	 * @apiSuccess {Number} amount 價金
     * @apiSuccess {Boolean} same_user 是出借方本人
	 * @apiSuccess {Number} principal 剩餘本金
	 * @apiSuccess {Number} interest 已發生利息
	 * @apiSuccess {Number} delay_interest 已發生延滯利息
	 * @apiSuccess {Float} bargain_rate 增減價比率(%)
	 * @apiSuccess {Number} instalment 剩餘期數
	 * @apiSuccess {Number} combination Combination ID
	 * @apiSuccess {Number} expire_time 流標時間
	 * @apiSuccess {Number} accounts_receivable 應收帳款
	 * @apiSuccess {Object} target 原案資訊
	 * @apiSuccess {String} target.target_no 案號
	 * @apiSuccess {Number} target.product_id 產品ID
	 * @apiSuccess {Number} target.credit_level 信用指數
	 * @apiSuccess {Number} target.user_id User ID
	 * @apiSuccess {Number} target.loan_amount 核准金額
	 * @apiSuccess {Number} target.interest_rate 核可利率
	 * @apiSuccess {Number} target.instalment 期數
	 * @apiSuccess {Number} target.repayment 還款方式
	 * @apiSuccess {Number} target.delay 是否逾期 0:無 1:逾期中
	 * @apiSuccess {Number} target.delay_days 逾期天數
	 * @apiSuccess {String} target.reason 借款原因
	 * @apiSuccess {String} target.remark 備註
	 * @apiSuccess {Number} target.status 狀態 0:待核可 1:待簽約 2:待驗證 3:待出借 4:待放款（結標）5:還款中 8:已取消 9:申請失敗 10:已結案
	 * @apiSuccess {Number} target.sub_status 狀態 0:無 1:轉貸中 2:轉貸成功 3:申請提還 4:完成提還
	 * @apiSuccess {Number} target.created_at 申請日期
	 * @apiSuccess {Object} target.user 借款人基本資訊
	 * @apiSuccess {Number} target.user.age 年齡
	 * @apiSuccess {String} target.user.sex 性別 F/M
	 * @apiSuccess {Object} combination_list 整包債權列表
     * @apiSuccess {Number} combination_list.id Combination ID
     * @apiSuccess {Boolean} same_user 是出借方本人
     * @apiSuccess {String} combination_list.combination_no 整包轉讓號
     * @apiSuccess {Boolean} combination_list.password 是否需要密碼
     * @apiSuccess {Number} combination_list.count 筆數
     * @apiSuccess {Number} combination_list.amount 整包轉讓價金
     * @apiSuccess {Number} combination_list.principal 整包剩餘本金
     * @apiSuccess {Number} combination_list.interest 整包已發生利息
     * @apiSuccess {Number} combination_list.delay_interest 整包已發生延滯息
     * @apiSuccess {Number} combination_list.max_instalment 最大剩餘期數
     * @apiSuccess {Number} combination_list.min_instalment 最小剩餘期數
     * @apiSuccess {Float} combination_list.bargain_rate 增減價比率(%)
     * @apiSuccess {Float} combination_list.interest_rate 平均年表利率(%)
     * @apiSuccess {Number} combination_list.accounts_receivable 整包應收帳款
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 			{
     * 				"id": 17,
     *                                 "same_user":true,
     * 				"amount": 4010,
     * 				"principal": 5000,
     * 				"interest": 6,
     * 				"delay_interest": 0,
     * 				"bargain_rate": -19.9,
     * 				"instalment": 18,
     * 				"combination": 2,
     * 				"expire_time": 1547913599,
     * 				"accounts_receivable": 5398,
     * 				"target": {
     * 					"id": 9,
     * 					"target_no": "STN2019011414213",
     * 					"product_id": 1,
     * 					"credit_level": 3,
     * 					"user_id": 19,
     * 					"loan_amount": 5000,
     * 					"interest_rate": 7,
     * 					"instalment": 3,
     * 					"repayment": 1,
     * 					"delay": 0,
     * 					"delay_days": 0,
     * 					"reason": "",
     * 					"remark": "",
     * 					"status": 5,
     * 					"sub_status": 0,
     * 					"created_at": 1547444954,
     * 					"user": {
     * 						"sex": "M",
     * 						"age": 30
     * 					}
     * 				}
     * 			}
     * 			],
     * 			"combination_list": [
     * 			{
     * 				"id": 2,
     *                                 "same_user":true,
     * 				"combination_no": "PKG1547810358209546",
     * 				"password": false,
     * 				"count": 3,
     * 				"amount": 12028,
     * 				"principal": 15000,
     * 				"interest": 16,
     * 				"max_instalment": 18,
     * 				"min_instalment": 3,
     * 				"delay_interest": 0,
     * 				"bargain_rate": -19.9,
     * 				"interest_rate": 8.56,
     * 				"accounts_receivable": 15626
     * 			}
     * 			]
     * 		}
     *    }
	 *
	 * @apiUse TokenError
	 * @apiUse BlockUser
	 *
     */

    public function transfer_list_get()
    {
        if(!app_access()){
            $this->response(array('result' => 'ERROR','data' => [ ] ), 401);
        }
        $input 			= $this->input->get();
        $list			= [];
        $combination_list = [];
        $combination_ids = [];
        $product_list 	= $this->config->item('product_list');
        $orderby 		= isset($input['orderby'])&&in_array($input['orderby'],['credit_level','instalment','interest_rate'])?$input['orderby']:'';
        $sort			= isset($input['sort'])&&in_array($input['sort'],['desc','asc'])?$input['sort']:'asc';

        $this->load->model('loan/transfer_model');
        $this->transfer_model->order_by($orderby,$sort);

		// 已結案的只能撈五十筆
		if(! isset($input['limited']) || empty($input['limited']) || $input['limited'] >= 50) {
            $input['limited'] = 50;
        }
        $this->transfer_model->limit($input['limited']);
        $transfer = $this->transfer_model->get_many_by(['status' => [0,1,2]]);
        $my_investment  = array();
        $my_combination = array();
        // $user_id 			= $this->user_info->id;
        // $investments		= $this->investment_model->get_many_by([
        //     'user_id'	=> $user_id,
        //     'status'	=> 3
        // ]);
        // foreach($investments as $key => $value){
        //     array_push($my_investment,$value->id);
        // }

        if(!empty($transfer)){
            foreach($transfer as $key => $value){
                $target 	= $this->target_model->get($value->target_id);
                $product = $product_list[$target->product_id];
                $sub_product_id = $target->sub_product_id;
                $product_name = $product['name'];
                if($this->is_sub_product($product,$sub_product_id)){
                    $product = $this->trans_sub_product($product,$sub_product_id);
                    $product_name = $product['name'];
                }
                $user_info 	= $this->user_model->get($target->user_id);
                $user		= [];
                if($user_info){
                    $user = array(
                        'sex' 	=> $user_info->sex,
                        'age'	=> get_age($user_info->birthday),
                    );
                }

                //動態回寫accounts_receivable
                if($value->accounts_receivable == 0){
                    $investment           = $this->investment_model->get($value->investment_id);
                    if($investment->status != 10){
                        $get_pretransfer_info = $this->transfer_lib->get_pretransfer_info($investment,0,0,true,$target);
                        $accounts_receivable= $get_pretransfer_info['accounts_receivable'];
                        // $this->load->model('loan/transfer_model');
                        // $this->transfer_model->update($value->id,['accounts_receivable' => $accounts_receivable]);
                        $value->accounts_receivable = $accounts_receivable;
                    }
                }

                $reason = $target->reason;
                $json_reason = json_decode($reason);
                if(isset($json_reason->reason)){
                    $reason = $json_reason->reason.' - '.$json_reason->reason_description;
                }

                $target_info = [
                    'id' 				=> intval($target->id),
                    'target_no' 		=> $target->target_no,
                    'product_name' => $product_name,
                    'product_id' 		=> intval($target->product_id),
                    'credit_level' 		=> intval($target->credit_level),
                    'user_id' 			=> intval($target->user_id),
                    'loan_amount' 		=> intval($target->loan_amount),
                    'interest_rate' 	=> floatval($target->interest_rate),
                    'instalment' 		=> intval($target->instalment),
                    'repayment' 		=> intval($target->repayment),
                    'delay' 			=> intval($target->delay),
                    'delay_days' 		=> intval($target->delay_days),
                    'reason' 			=> $reason,
                    'remark' 			=> $target->remark,
                    'status' 			=> intval($target->status),
                    'sub_status' 		=> intval($target->sub_status),
                    'created_at' 		=> intval($target->created_at),
                    'user' 				=> $user,
                ];

                $list[] 	= [
                    'id'				=> intval($value->id),
                    'same_user'		    => in_array($value->investment_id,$my_investment),
                    'amount'			=> intval($value->amount),
                    'principal'			=> intval($value->principal),
                    'interest'			=> intval($value->interest),
                    'delay_interest'	=> intval($value->delay_interest),
                    'bargain_rate'		=> floatval($value->bargain_rate),
                    'instalment'		=> intval($value->instalment),
                    'combination'		=> intval($value->combination),
                    'expire_time'		=> intval($value->expire_time),
                    'accounts_receivable'	=> intval($value->accounts_receivable),
                    'target'			=> $target_info,
                ];

                if(in_array($value->investment_id,$my_investment)){
                    array_push($my_combination,$value->combination);
                }

                if($value->combination > 0){
                    $combination_ids[$value->combination] = $value->combination;
                }
            }

            if(!empty($combination_ids)){
                $this->load->model('loan/transfer_combination_model');
                $combinations = $this->transfer_combination_model->get_many($combination_ids);
                if($combinations){
                    foreach($combinations as $key => $value){
                        $combination_list[] 	= [
                            'id'				=> intval($value->id),
                            'same_user'         => in_array($value->id,$my_combination),
                            'combination_no'	=> $value->combination_no,
                            'password'			=> empty($value->password)?false:true,
                            'count'				=> intval($value->count),
                            'amount'			=> intval($value->amount),
                            'principal'			=> intval($value->principal),
                            'interest'			=> intval($value->interest),
                            'max_instalment'	=> intval($value->max_instalment),
                            'min_instalment'	=> intval($value->min_instalment),
                            'delay_interest'	=> intval($value->delay_interest),
                            'bargain_rate'		=> floatval($value->bargain_rate),
                            'interest_rate'		=> floatval($value->interest_rate),
                            'accounts_receivable'	=> intval($value->accounts_receivable),
                        ];
                    }
                }
            }


            if(!empty($orderby) && !empty($sort) && !empty($list)){
                $num = count($list);
                for($i = 0 ; $i < $num ; $i++){
                    for ($j=$i+1;$j<$num;$j++) {
                        switch($orderby){
                            case 'credit_level':
                                $a = $list[$i]['target']['credit_level'];
                                $b = $list[$j]['target']['credit_level'];
                                break;
                            case 'instalment':
                                $a = $list[$i]['instalment'];
                                $b = $list[$j]['instalment'];
                                break;
                            case 'interest_rate':
                                $a = $list[$i]['target']['interest_rate'];
                                $b = $list[$j]['target']['interest_rate'];
                                break;
                            default:
                                break;
                        }
                        if ($sort=='desc') {
                            if( $a > $b ){
                                $tmp      = $list[$i];
                                $list[$i] = $list[$j];
                                $list[$j] = $tmp;
                            }
                        }else{
                            if( $a < $b ){
                                $tmp      = $list[$i];
                                $list[$i] = $list[$j];
                                $list[$j] = $tmp;
                            }
                        }
                    }
                }
            }
        }
        $this->response(array('result' => 'SUCCESS','data' => [ 'list' => $list ,'combination_list' => $combination_list] ));
    }

    /**
     * @api {get} /v2/website/credit_school 官網 取得學校評分
     * @apiVersion 0.2.0
     * @apiName Get_Credit_School
     * @apiGroup Website
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				{
     *                  "name": "國立清華大學",
     *                  "points": 550,
     *                  "national": 1
     *              },
     *              {
     *                  "name": "國立臺灣大學",
     *                  "points": 600,
     *                  "national": 1
     *              },
     * 			]
     * 		}
     *    }
     */

    public function credit_school_get()
    {
        if(!app_access()){
            $this->response(array('result' => 'ERROR','data' => [ ] ), 401);
        }
        $input = $this->input->get();
        $this->config->load('school_points');
        $school_list = $this->config->item('school_points');
        $this->response(array('result' => 'SUCCESS','data' => [ 'list' => $school_list ] ));
    }

    /**
     * @api {get} /v2/website/credit_department 官網 取得學校科系評分
     * @apiVersion 0.2.0
     * @apiName Get_Credit_Department
     * @apiGroup Website
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     *    {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				"台南應用科技大學": {
     * 				    "score": {
     * 				        "資訊管理系": 220,
     * 				        "資訊管理系娛樂與網路應用組": 220,
     * 				        "財務金融系": 130,
     * 				        "企業管理系": 130,
     * 				        "國際企業經營系": 130,
     * 				        "會計資訊系": 120,
     * 				        "美容造型設計系": 80,
     * 				        "運動休閒與健康管理系": 80,
     * 				        "旅館管理系": 70,
     * 				        "生活服務產業系": 70,
     * 				        "幼兒保育系": 70,
     * 				        "養生休閒管理學位學程": 60,
     * 				        "餐飲系": 60,
     * 				        "服飾設計管理系": 50,
     * 				        "漫畫學士學位學程": 50,
     * 				        "商品設計系": 50,
     * 				        "室內設計系": 50,
     * 				        "時尚設計系": 40,
     * 				        "多媒體動畫系": 40,
     * 				        "視覺傳達設計系": 30,
     * 				        "應用英語系": 20
     * 				    }
     * 				},
     * 				"台北海洋科技大學": {
     * 				    "score": {
     * 				        "海空物流與行銷系(淡水校本部)": 100,
     * 				        "旅遊管理系(淡水校本部)": 70,
     * 				        "健康促進與銀髮保健系(淡水校本部)": 60,
     * 				        "餐飲管理系(士林校區)": 50,
     * 				        "健康照顧社會工作系(淡水校本部)": 40,
     * 				        "食品科技與行銷系(士林校區)": 40,
     * 				        "時尚造型設計管理系寵物美容設計組(淡水校本部)": 40,
     * 				        "海洋運動休閒系(士林校區)": 30,
     * 				        "海洋休閒觀光系(士林校區)": 30,
     * 				        "時尚造型設計管理系整體造型設計組(淡水校本部)": 30,
     * 				        "表演藝術系(淡水校本部)": 20,
     * 				        "數位遊戲與動畫設計系(淡水校本部)": 10,
     * 				        "視覺傳達設計系(淡水校本部)": 0
     * 				    }
     * 				}
     * 			]
     * 		}
     *    }
     */

    public function credit_department_get()
    {
        if(!app_access()){
            $this->response(array('result' => 'ERROR','data' => [ ] ), 401);
        }
        $input = $this->input->get();
        $this->config->load('school_points');
        $department_list = $this->config->item('department_points');
        $this->response(array('result' => 'SUCCESS','data' => [ 'list' => $department_list ] ));
    }

    public function department_get()
    {
        $input = $this->input->get();
        $this->config->load('school_points');
        $department_list = $this->config->item('department_points');

        $data = array_map(function($key,$values) {
            if(isset($values['score'])){
                unset($values['score']);
            }
            return [$key=>$values];
        }, array_keys($department_list), $department_list);

        $data = array_reduce($data, 'array_merge', array());
        $this->response(array('result' => 'SUCCESS','data' => [ 'list' => $data ] ));
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

    /**
     * @api {get} /v2/website/company_name 官網 利用統一編號取得公司(或商行)名稱
     * @apiVersion 0.2.0
     * @apiGroup Website
     *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} SUCCESS
     * {
     * "result": "SUCCESS",
     * "data": "普匯金融科技股份有限公司"
     * }
     */
    public function company_name_get()
    {
        $tax_id = $this->input->get('tax_id');
        if (empty($tax_id))
        {
            $this->response([
                'result' => 'ERROR',
                'data' => ''
            ]);
        }

        $data = $this->company_info($tax_id);
        if (isset($data['result']) && $data['result'] === TRUE && isset($data['data'][0]['Company_Name']))
        {
            $this->response(['result' => 'SUCCESS', 'data' => $data['data'][0]['Company_Name']]);
        }

        $data = $this->president_info($tax_id);
        if (isset($data['result']) && $data['result'] === TRUE && isset($data['data'][0]['Business_Name']))
        {
            $this->response(array('result' => 'SUCCESS', 'data' => $data['data'][0]['Business_Name']));
        }

        $this->response(array('result' => 'SUCCESS', 'data' => ''));
    }

    private function company_info(string $tax_id)
    {
        try
        {
            $this->load->library('Gcis_lib');
            $data = $this->gcis_lib->get_company_info($tax_id);

            return ['result' => TRUE, 'data' => $data];
        }
        catch (Exception $e)
        {
            return ['result' => FALSE, 'msg' => $e->getMessage()];
        }
    }

    private function president_info(string $tax_id)
    {
        try
        {
            $this->load->library('Gcis_lib');
            $data = $this->gcis_lib->get_president_info($tax_id);

            return ['result' => TRUE, 'data' => $data];
        }
        catch (Exception $e)
        {
            return ['result' => FALSE, 'msg' => $e->getMessage()];
        }
    }

    /*
     * @api {get} /v2/website/total_loan_amount 出借方 累積放款(媒合)金額
     * @apiVersion 0.2.0
     * @apiName GetWebsiteTotalLoanAmount
     * @apiGroup Website
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} amount 累積媒合金額
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *        "amount": 9999,
     *      }
     *    }
     */
    public function total_loan_amount_get()
    {
        $this->load->model('loan/target_model');

        $data = [
            'amount' => $this->target_model->get_total_loan_amount(),
        ];

        $this->response([
            'result' => 'SUCCESS',
            'data' => $data,
        ]);
    }

    /**
     * @api {get} /v2/website/transaction_count 出借方 累積交易(成交)筆數
     * @apiVersion 0.2.0
     * @apiName GetWebsiteTransactionCount
     * @apiGroup Website
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} count 累積交易筆數
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *        "count": 9999,
     *      }
     *    }
     */
    public function transaction_count_get()
    {
        $this->load->model('loan/target_model');

        $data = [
            'count' => $this->target_model->get_transaction_count(),
        ];

        $this->response([
            'result' => 'SUCCESS',
            'data' => $data,
        ]);
    }

    /**
     * @api {get} /v2/website/member_count 會員 累積註冊用戶
     * @apiVersion 0.2.0
     * @apiName GetWebsiteMemberCount
     * @apiGroup Website
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccess {Number} count 累積註冊用戶
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *        "count": 9999,
     *      }
     *    }
     */
    public function member_count_get()
    {
        $this->load->model('user/user_model');

        $data = [
            'count' => $this->user_model->get_member_count(),
        ];

        $this->response([
            'result' => 'SUCCESS',
            'data' => $data,
        ]);
    }

    /**
     * @api {get} /v2/website/ntu_donation_list 會員 台大慈善捐款名單
     * @apiVersion 0.2.0
     * @apiName GetWebsiteNtuDonationList
     * @apiGroup Website
     * @apiSuccessExample {Object} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *          "id": "1",
     *          "institution_id": "1",
     *          "user_id": "47181",
     *          "investor": "1",
     *          "amount": "101.000",
     *          "transaction_id": "2352866",
     *          "tx_datetime": "2021-11-11 15:15:30",
     *          "receipt_type": "0",
     *          "data": "{"name\": \"User姓名\", "email\": \"test@gmail.com\", "phone\": \"09123456789\", "receipt_address\": \"地址XXX\", "receipt_id_number\": \"A123456789\"}",
     *          "created_at": "2021-11-11 15:15:30",
     *          "created_ip": "172.18.0.1",
     *          "updated_at": "2021-11-11 15:15:30",
     *          "updated_ip": "172.18.0.1",
     *          "alias": "NTUH",
     *          "name": "財團法人台大兒童健康基金會",
     *          "CoA_content": null
     *      }
     *    }
     */
    public function ntu_donation_list_get()
    {
        $max = (int) $this->input->get('max');

        $this->load->model('transaction/charity_model');
        $data = $this->charity_model->get_ntu_donation_list($max);

        $this->response([
            'result' => 'SUCCESS',
            'data' => $data,
        ]);
    }

    public function ntu_donation_list_manual_get()
    {
        $max = (int) $this->input->get('max');

        $this->load->model('user/ntu_model');
        $data = $this->ntu_model->get_list_bigger_than($max);

        $this->response([
            'result' => 'SUCCESS',
            'data' => $data,
        ]);
    }

    public function get_investor_report_get()
    {
        if ( ! app_access())
        {
            $this->response(array('result' => 'ERROR', 'data' => []), 401);
        }

        $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : '';
        $token_data = $this->_check_jwt_token($token);
        if ( ! $this->_check_visit_freq($token_data))
        {
            $this->response(array('result' => 'ERROR', 'data' => []), 503);
        }

        $this->load->library('user_lib');
        $report_data = $this->user_lib->get_investor_report($token_data->id, [], date('Y-m-d'));

        $this->response(array('result' => 'SUCCESS', 'data' => $report_data));
    }

    public function download_investor_report_get()
    {
        if ( ! app_access())
        {
            $this->response(array('result' => 'ERROR', 'data' => []), 401);
        }

        $token = isset($this->input->request_headers()['request_token']) ? $this->input->request_headers()['request_token'] : '';
        $token_data = $this->_check_jwt_token($token);
        if ( ! $this->_check_visit_freq($token_data))
        {
            $this->response(array('result' => 'ERROR', 'data' => []), 503);
        }


        $this->load->library('user_lib');
        $this->load->library('spreadsheet_lib');

        $report_data = $this->user_lib->get_investor_report($token_data->id, [], date('Y-m-d'));
        $html = $this->load->view('admin/excel/investor_report', ['data' => $report_data], TRUE);
        $spreadsheet = $this->spreadsheet_lib->get_investor_report_from_html($html, $report_data);
        $this->spreadsheet_lib->download('普匯投資報告書('.$token_data->id.').xlsx', $spreadsheet);
        exit(1);
    }

    /**
     * 更新使用者的蒙太奇圖片
     *
     * @param reference 蒙太奇底層圖片名稱
     * @param user_id 使用者id (p2p_user.users.id)
     * @param img 使用者上傳的原始圖片，轉換成base64格式
     */
    public function update_user_montage_post()
    {
        $reference = $this->input->post('reference');
        $user_id = $this->input->post('user_id');
        $img = $this->input->post('img');
        if (empty($reference) || empty($user_id) || empty($img))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $url = $this->_get_pandora_url() . '/montage/user';
        $response = curl_put($url, json_encode([
            'reference' => $reference,
            'user_id' => $user_id,
            'img' => $img
        ]), ['Content-Type: application/json']);
        $response = json_decode($response, TRUE);

        if ( ! empty($response['message'] && $response['message'] == $user_id . '更新中'))
        {
            $this->response(['result' => 'SUCCESS']);
        }

        $this->response(['result' => 'ERROR',$response]);
    }

    /**
     * 新增使用者的蒙太奇圖片
     *
     * @param reference 蒙太奇底層圖片名稱
     * @param user_id 使用者id (p2p_user.users.id)
     * @param img 使用者上傳的原始圖片，轉換成base64格式
     */
    public function new_user_montage_post()
    {
        $reference = $this->input->post('reference');
        $user_id = $this->input->post('user_id');
        $img = $this->input->post('img');
        if (empty($reference) || empty($user_id) || empty($img))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $url = $this->_get_pandora_url() . '/montage/user';
        $response = curl_get($url, json_encode([
            'reference' => $reference,
            'user_id' => $user_id,
            'img' => $img
        ]), ['Content-Type: application/json']);
        $response = json_decode($response, TRUE);

        if ( ! empty($response['message'] && $response['message'] == $user_id . '新增中'))
        {
            $this->response(['result' => 'SUCCESS']);
        }

        $this->response(['result' => 'ERROR']);
    }

    /**
     * 找使用者的蒙太奇圖片
     *
     * @param reference 蒙太奇底層圖片名稱
     * @param user_id 使用者id (p2p_user.users.id)
     */
    public function user_montage_get()
    {
        $reference = $this->input->get('reference');
        $user_id = $this->input->get('user_id');
        if (empty($reference) || empty($user_id))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $url = $this->_get_pandora_url() . '/montage/user?reference=' . $reference . '&user_id=' . $user_id;
        $response = curl_get($url);
        $response = json_decode($response, TRUE);

        if (isset($response['message']))
        {
            switch ($response['message'])
            {
                case '此蒙太奇名稱不存在':
                    $this->response(['result' => 'SUCCESS', 'data' => ['status' => MONTAGE_USER_STATUS_NO_REFERENCE]]);
                    break;
                case '此user_id未上傳過圖片':
                    $this->response(['result' => 'SUCCESS', 'data' => ['status' => MONTAGE_USER_STATUS_NO_USER]]);
                    break;
            }
        }
        elseif ( ! empty($response['user_info']))
        {
            $this->response(['result' => 'SUCCESS', 'data' => ['status' => MONTAGE_USER_STATUS_EXISTS]]);
        }
        $this->response(['result' => 'ERROR']);
    }

    /**
     * 找蒙太奇圖片
     *
     * @param reference 蒙太奇底層圖片名稱
     */
    public function montage_get()
    {
        $reference = $this->input->get('reference');
        if (empty($reference))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $url = $this->_get_pandora_url() . '/montage/img?reference=' . $reference;
        $response = curl_get($url);
        $response = json_decode($response, TRUE);

        if ( ! empty($response['img']))
        {
            $this->response(['result' => 'SUCCESS', 'data' => $response['img']]);
        }
        $this->response(['result' => 'ERROR']);
    }

    private function _get_pandora_url()
    {
        return 'http://' . getenv('PANDORA_IP') . ':' . getenv('PANDORA_PORT');
    }

    /**
     * 取得每個月的風險報告書數據
     * @return void
     */
    public function each_month_risk_report_get()
    {
        $year = $this->input->get('year');
        $month = $this->input->get('month');

        if (empty($year) || empty($month))
        {
            $this->response(['result' => 'ERROR', 'error' => INPUT_NOT_CORRECT]);
        }

        $this->load->library('risk_report_lib');
        $info_by_month = $this->risk_report_lib->get_info_by_month($year, $month);
        $info_from_beginning = $this->risk_report_lib->get_info_from_beginning($year, $month);
        $delayed_info = $this->risk_report_lib->get_delay_rate_by_level($year, $month);

        $data = $this->risk_report_lib->get_initial_data();
        $data['year'] = $year;
        $data['month'] = $month;
        ! isset($info_from_beginning['yearly_rate_of_return']) ?: $data['yearly_rate_of_return'] = (int) $info_from_beginning['yearly_rate_of_return'];

        ! isset($info_by_month['apply_loans_amount']) ?: $data['this_month_apply']['amount'] = (int) $info_by_month['apply_loans_amount'];
        ! isset($info_by_month['apply_student_loans_count']) ?: $data['this_month_apply']['student'] = (int) $info_by_month['apply_student_loans_count'];
        ! isset($info_by_month['apply_work_loans_count']) ?: $data['this_month_apply']['work'] = (int) $info_by_month['apply_work_loans_count'];
        $data['this_month_apply']['all'] = ($data['this_month_apply']['student'] ?? 0) + ($data['this_month_apply']['work'] ?? 0);
        ! isset($info_by_month['delay_users_count']) ?: $data['this_month_apply']['delay_users_count'] = (int) $info_by_month['delay_users_count'];
        ! isset($info_by_month['delay_loans_count']) ?: $data['this_month_apply']['delay_loans_count'] = (int) $info_by_month['delay_loans_count'];

        ! isset($info_from_beginning['total_apply_success']) ?: $data['total_apply']['success'] = (int) $info_from_beginning['total_apply_success'];
        ! isset($info_from_beginning['total_apply_amount']) ?: $data['total_apply']['amount'] = (int) $info_from_beginning['total_apply_amount'];
        ! isset($info_from_beginning['total_apply_count']) ?: $data['total_apply']['count'] = (int) $info_from_beginning['total_apply_count'];
        ! isset($info_from_beginning['avg_invest']) ?: $data['total_apply']['avg_invest'] = round($info_from_beginning['avg_invest'], 2);
        ! isset($info_from_beginning['avg_invest_student']) ?: $data['total_apply']['avg_invest_student'] = round($info_from_beginning['avg_invest_student'], 2);
        ! isset($info_from_beginning['avg_invest_work']) ?: $data['total_apply']['avg_invest_work'] = round($info_from_beginning['avg_invest_work'], 2);
        ! isset($info_from_beginning['delay_return_amount']) ?: $data['total_apply']['delay_return_amount'] = (int) $info_from_beginning['delay_return_amount'];

        foreach ($delayed_info as $value)
        {
            $data['delay_rate']['level' . $value['level']] = round($value['rate'], 2);
        }

        $this->response(['result' => 'SUCCESS', 'data' => $data]);
    }
}
