<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Order extends REST_Controller {

	public $cooperation_info;
	
    public function __construct()
    {
        parent::__construct();
		$authorization 	= isset($this->input->request_headers()['Authorization'])?$this->input->request_headers()['Authorization']:'';
		$time 			= isset($this->input->request_headers()['Timestamp'])?$this->input->request_headers()['Timestamp']:'';
		$cooperation_id = isset($this->input->request_headers()['CooperationID'])?$this->input->request_headers()['CooperationID']:'';
		
		if(strlen($authorization) != 39 || substr($authorization,0,7) != 'Bearer '){
			$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		}
		
		$time_ragne = time() - intval($time);
		if($time_ragne > COOPER_TIMEOUT){
			$this->response(['error' =>'TimeOut'],REST_Controller::HTTP_FORBIDDEN);//403 TimeOut
		}
		
		if($cooperation_id){
			$this->load->model('user/cooperation_model');
			$cooperation = $this->cooperation_model->get_by([
				'cooperation_id'	=> $cooperation_id,
				'status'			=> 1,
			]);
			if($cooperation){
				$this->cooperation_info = $cooperation;
				$ips = explode(',',$cooperation->server_ip);
				if(!in_array(get_ip(),$ips)){
					$this->response(['error' =>'IllegalIP'],REST_Controller::HTTP_UNAUTHORIZED);//401 違法IP
				}
			}else{
				$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
			}
		}else{
			$this->response(['error' =>'CooperationNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此id
		}


		$middles = '';
		if($this->request->method == 'post'){
			$method = $this->router->fetch_method();
			$input 	= $this->input->post(NULL, TRUE);
			ksort($input);
			$middles = implode('',array_values($input));
		}
		
		$signature = 'Bearer '.MD5(SHA1($cooperation_id.$middles.$time).$cooperation->cooperation_key);
		if($signature != $authorization){
			$this->response(['error' =>'AuthorizationRequired'],REST_Controller::HTTP_UNAUTHORIZED);//401 Authorization錯誤
		}
    }
	
	/**
     * @api {get} /order/product Product List
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderProduct
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
     *
     * @apiSuccess {String} result SUCCESS
	 * @apiSuccess {String} id Product ID
	 * @apiSuccess {String} name Product Name
	 * @apiSuccess {String} description Description
	 * @apiSuccess {Number} loan_range_s Minimum Loan Amount
	 * @apiSuccess {Number} loan_range_e Maximum Loan Amount
	 * @apiSuccess {Number} interest_rate_s Minimum Interest Rate(%)
	 * @apiSuccess {Number} interest_rate_e Maximum Interest Rate(%)
	 * @apiSuccess {Number} charge_platform Platform Fee Rate(%)
	 * @apiSuccess {Number} charge_platform_min Minimum Platform Fee
	 * @apiSuccess {Object} instalment Number Of Instalment
     * @apiSuccessExample {Object} SUCCESS
     * {
     * 		"result":"SUCCESS",
     * 		"data":{
     * 			"list":[
     * 				{
     * 				"id":"1",
     * 				"name":"學生區",
     * 				"description":"學生區",
     * 				"loan_range_s":"5000",
     * 				"loan_range_e":"120000",
     * 				"interest_rate_s":"12",
     * 				"interest_rate_e":"20",
     * 				"charge_platform":"3",
     * 				"charge_platform_min":"500",
	 * 				"instalment": [
     *					3,
     * 				    6,
     * 				    12,
     * 				    18,
     * 				    24
     * 				  ]
     * 				}
     * 			]
     * 		}
     * }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse CooperationNotFound
     */
	 	
	public function product_get()
    {
		$list			= array();
		$product_list 	= $this->config->item('product_list');
		if(!empty($product_list)){
			foreach($product_list as $key => $value){
				if($value['type']==2){
					$list[] = array(
						'id' 					=> $value['id'],
						'name' 					=> $value['name'],
						'description' 			=> $value['description'],
						'loan_range_s'			=> $value['loan_range_s'],
						'loan_range_e'			=> $value['loan_range_e'],
						'interest_rate_s'		=> $value['interest_rate_s'],
						'interest_rate_e'		=> $value['interest_rate_e'],
						'charge_platform'		=> PLATFORM_FEES,
						'charge_platform_min'	=> PLATFORM_FEES_MIN,
						'instalment'			=> $value['instalment']
					);
				}
				
			}
		}
		$data['list'] = $list;
		$this->response(array('result' => 'SUCCESS','data' => $data ));
    }
	
	/**
     * @api {get} /order/amount Repayment Total Amount
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderAmount
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiParam {Number} product_id Product ID
	 * @apiParam {Number{5000-300000}} amount Amount
     *
     * @apiSuccess {String} result SUCCESS
	 * @apiSuccess {Object} instalment Number Of Instalment
	 * @apiSuccess {Number} principal Total Principal
	 * @apiSuccess {Number} interest Total Interest
	 * @apiSuccess {Number} total_payment Total Amount
	 *
     * @apiSuccessExample {Object} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
	 *      "data": {
	 *      	"list": [
	 *      	{
	 *      		"instalment": 3,
	 *      		"principal": 20000,
	 *      		"interest": 434,
	 *      		"total_payment": 20434
	 *      	},
	 *      	{
	 *      		"instalment": 6,
	 *      		"principal": 20000,
	 *      		"interest": 691,
	 *      		"total_payment": 20691
	 *      	},
	 *      	{
	 *      		"instalment": 12,
	 *      		"principal": 20000,
	 *      		"interest": 1210,
	 *      		"total_payment": 21210
	 *      	},
	 *      	{
	 *      		"instalment": 18,
	 *      		"principal": 20000,
	 *      		"interest": 1732,
	 *      		"total_payment": 21732
	 *      	},
	 *      	{
	 *      		"instalment": 24,
	 *      		"principal": 20000,
	 *      		"interest": 2270,
	 *      		"total_payment": 22270
	 *      	}
	 *      	]
	 *      }
	 *     }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (400) ArgumentError Insert Error.
	 * @apiErrorExample ArgumentError
	 *     HTTP/1.1 400 Not Found
	 *     {
	 *       "error": "ArgumentError"
	 *     }
     * 
     */
	public function amount_get()
    {
		$product_list 	= $this->config->item('product_list');
		$input 			= $this->input->get(NULL, TRUE);
		$fields 		= ['product_id','amount'];
		foreach ($fields as $field) {
			$input[$field] = intval($input[$field]);
			if (empty($input[$field])) {
				$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
			} 
		}

		if(isset($product_list[$input['product_id']]) && $product_list[$input['product_id']]['type']==2){
			$product_info 	= $product_list[$input['product_id']];
			$platform_fees 	= intval(round( $input['amount'] * PLATFORM_FEES / (100-PLATFORM_FEES) ,0));
			$platform_fees 	= $platform_fees > PLATFORM_FEES_MIN ? $platform_fees : PLATFORM_FEES_MIN;
			$input['amount'] += $platform_fees;
			if($input['amount'] >= $product_info['loan_range_s'] && $input['amount'] <= $product_info['loan_range_e']){
				$list = [];
				foreach($product_info['instalment'] as $key => $value){
					$amortization_schedule = $this->financial_lib->get_amortization_schedule($input['amount'],$value,ORDER_INTEREST_RATE,'',1);
					$list[] = [
						'instalment'	=> $value,
						'principal'		=> $amortization_schedule['total']['principal'],
						'interest'		=> $amortization_schedule['total']['interest'],
						'total_payment'	=> $amortization_schedule['total']['total_payment'],
					];
				}
				$this->response(array('result' => 'SUCCESS','data' => ['list'=>$list]));
			}
		}
		$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
    }
	
	/**
     * @api {get} /order/schedule Repayment Schedule
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderSchedule
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiParam {Number} product_id Product ID
	 * @apiParam {Number{5000-300000}} amount Amount
	 * @apiParam {Number} instalment Number Of Instalment
     *
     * @apiSuccess {String} result SUCCESS
	 * @apiSuccess {Object} amortization_schedule Amortization Schedule
	 * @apiSuccess {Number} amortization_schedule.amount Loan Amount
	 * @apiSuccess {Number} amortization_schedule.instalment Number Of Instalment
	 * @apiSuccess {Number} amortization_schedule.rate Annual Rate Of Interest.(%)
	 * @apiSuccess {String} amortization_schedule.date Start Date
	 * @apiSuccess {Number} amortization_schedule.total_payment PMT
	 * @apiSuccess {Boolean} amortization_schedule.leap_year Leap Year
	 * @apiSuccess {Number} amortization_schedule.year_days Days This Year
	 * @apiSuccess {Number} amortization_schedule.XIRR XIRR(%)
	 * @apiSuccess {Object} amortization_schedule.schedule Repayment Schedule
	 * @apiSuccess {Number} amortization_schedule.schedule.instalment Current Instalment
	 * @apiSuccess {String} amortization_schedule.schedule.repayment_date Repayment Due Date
	 * @apiSuccess {Number} amortization_schedule.schedule.days Current Days
	 * @apiSuccess {Number} amortization_schedule.schedule.remaining_principal Remaining Principal
	 * @apiSuccess {Number} amortization_schedule.schedule.principal Principal
	 * @apiSuccess {Number} amortization_schedule.schedule.interest Interest
	 * @apiSuccess {Number} amortization_schedule.schedule.total_payment Current Repayment Amount
	 * @apiSuccess {Object} amortization_schedule.total Total
	 * @apiSuccess {Number} amortization_schedule.total.principal Total Principal
	 * @apiSuccess {Number} amortization_schedule.total.interest Total Interest
	 * @apiSuccess {Number} amortization_schedule.total.total_payment Total Amount
	 *
     * @apiSuccessExample {Object} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
	 *      "data": {
	 *      	"amortization_schedule": {
	 *      		"amount": 20000,
	 *      		"instalment": 6,
	 *      		"rate": 10,
	 *      		"date": "2019-01-21",
	 *      		"total_payment": 3432,
	 *      		"leap_year": false,
	 *      		"year_days": 365,
	 *      		"XIRR": 10.47,
	 *      		"schedule": {
	 *      			"1": {
	 *      				"instalment": 1,
	 *      				"repayment_date": "2019-03-10",
	 *      				"days": 48,
	 *      				"remaining_principal": 20000,
	 *      				"principal": 3169,
	 *      				"interest": 263,
	 *      				"total_payment": 3432
	 *      			},
	 *      			"2": {
	 *      				"instalment": 2,
	 *      				"repayment_date": "2019-04-10",
	 *      				"days": 31,
	 *      				"remaining_principal": 16831,
	 *      				"principal": 3289,
	 *      				"interest": 143,
	 *      				"total_payment": 3432
	 *      			},
	 *      			"3": {
	 *      				"instalment": 3,
	 *      				"repayment_date": "2019-05-10",
	 *      				"days": 30,
	 *      				"remaining_principal": 13542,
	 *      				"principal": 3321,
	 *      				"interest": 111,
	 *      				"total_payment": 3432
	 *      			},
	 *      			"4": {
	 *      				"instalment": 4,
	 *      				"repayment_date": "2019-06-10",
	 *      				"days": 31,
	 *      				"remaining_principal": 10221,
	 *      				"principal": 3345,
	 *      				"interest": 87,
	 *      				"total_payment": 3432
	 *      			},
	 *      			"5": {
	 *      				"instalment": 5,
	 *      				"repayment_date": "2019-07-10",
	 *      				"days": 30,
	 *      				"remaining_principal": 6876,
	 *      				"principal": 3375,
	 *      				"interest": 57,
	 *      				"total_payment": 3432
	 *      			},
	 *      			"6": {
	 *      				"instalment": 6,
	 *      				"repayment_date": "2019-08-10",
	 *      				"days": 31,
	 *      				"remaining_principal": 3501,
	 *      				"principal": 3501,
	 *      				"interest": 30,
	 *      				"total_payment": 3531
	 *      			}
	 *      		},
	 *      		"total": {
	 *      			"principal": 20000,
	 *      			"interest": 691,
	 *      			"total_payment": 20691
	 *      		}
	 *      	}
	 *    	}
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (400) ArgumentError Insert Error.
	 * @apiErrorExample ArgumentError
	 *     HTTP/1.1 400 Not Found
	 *     {
	 *       "error": "ArgumentError"
	 *     }
     * 
     */
	public function schedule_get()
    {
		$product_list 	= $this->config->item('product_list');
		$input 			= $this->input->get(NULL, TRUE);
		$fields 		= ['product_id','amount','instalment'];
		foreach ($fields as $field) {
			$input[$field] = intval($input[$field]);
			if (empty($input[$field])) {
				$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
			}
		}
		
		if(isset($product_list[$input['product_id']]) && $product_list[$input['product_id']]['type']==2){
			$product_info 	= $product_list[$input['product_id']];
			$platform_fees 	= intval(round( $input['amount'] * PLATFORM_FEES / (100-PLATFORM_FEES) ,0));
			$platform_fees 	= $platform_fees > PLATFORM_FEES_MIN ? $platform_fees : PLATFORM_FEES_MIN;
			$input['amount'] += $platform_fees;
			if($input['amount'] >= $product_info['loan_range_s'] && $input['amount'] <= $product_info['loan_range_e']){
				if(in_array($input['instalment'],$product_info['instalment'])){
					$amortization_schedule = $this->financial_lib->get_amortization_schedule($input['amount'],$input['instalment'],ORDER_INTEREST_RATE,'',1);
					$this->response(array('result' => 'SUCCESS','data' => ['amortization_schedule'=>$amortization_schedule]));
				}
			}
		}
		$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
    }
	
	/**
     * @api {post} /order/create Create Order
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName PostOrderCreate
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + amount + instalment + item_count + item_name + item_price + merchant_order_no + phone + product_id + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiParam {Number} product_id Product ID
	 * @apiParam {String{10}} phone User Phone Number
	 * @apiParam {Number{5000-300000}} amount Amount
	 * @apiParam {Number} instalment Number Of Instalment
	 * @apiParam {String{8..32}} merchant_order_no Custom Order NO
	 * @apiParam {String} item_name 商品名稱，多項商品時，以逗號分隔
	 * @apiParam {String} item_count 商品數量，多項商品時，以逗號分隔
	 * @apiParam {String} item_price 商品單價，多項商品時，以逗號分隔
	 *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS"
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (400) ArgumentError Insert Error.
	 * @apiErrorExample ArgumentError
	 *     HTTP/1.1 400 Not Found
	 *     {
	 *       "error": "ArgumentError"
	 *     }
	 *
	 * @apiError (409) InsertError Insert Error.
	 * @apiErrorExample InsertError
	 *     HTTP/1.1 409 Not Found
	 *     {
	 *       "error": "InsertError"
	 *     }
	 *
	 * @apiError (409) OrderExists Merchant Order NO Exists.
	 * @apiErrorExample OrderExists
	 *     HTTP/1.1 409 Not Found
	 *     {
	 *       "error": "OrderExists"
	 *     }
     * 
     */
	public function create_post()
    {
		$product_list 	= $this->config->item('product_list');
		$input 			= $this->input->post(NULL, TRUE);
		$fields 		= ['product_id','phone','amount','instalment','merchant_order_no','item_name','item_count','item_price'];
		$content		= [];
		foreach ($fields as $field) {
			if (empty($input[$field])) {
				$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
			}else{
				$content[$field] = $input[$field];
			}
		}

		if(!preg_match('/^09[0-9]{2}[0-9]{6}$/', $content['phone'])){
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		if(strlen($content['merchant_order_no'])< 8 || strlen($content['merchant_order_no'])>32){
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		//產品
		if(isset($product_list[$input['product_id']]) && $product_list[$input['product_id']]['type']==2){
			$product_info = $product_list[$input['product_id']];
		}else{
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		//期數
		if(!in_array($input['instalment'],$product_info['instalment'])){
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		//金額
		if($input['amount'] < $product_info['loan_range_s'] && $input['amount'] > $product_info['loan_range_e']){
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		//商品
		$item_name 	= explode(',',$content['item_name']);
		$item_count = explode(',',$content['item_count']);
		$item_price = explode(',',$content['item_price']);
		if(count($item_name) == count($item_count) && count($item_name) == count($item_price)){
			$total = 0;
			foreach($item_count as $key => $value){
				$total += intval($value) * ($item_price[$key]);
			}
			
			if($total != $content['amount']){
				$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
			}
		}else{
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		$this->load->model('transaction/order_model');
		$exist = $this->order_model->get_by([
			'company_user_id'	=> $this->cooperation_info->company_user_id,
			'merchant_order_no'	=> $content['merchant_order_no'],
		]);
		if($exist){
			$this->response(['error' =>'OrderExists'],REST_Controller::HTTP_CONFLICT);//409 單號存在
		}
		
		$content['platform_fee'] 	= intval(round( $total * PLATFORM_FEES / (100-PLATFORM_FEES) ,0));
		$content['platform_fee'] 	= $content['platform_fee'] > PLATFORM_FEES_MIN ? $content['platform_fee'] : PLATFORM_FEES_MIN;
		$content['total'] 			= $total + $content['platform_fee'];
		$content['company_user_id'] = $this->cooperation_info->company_user_id;
		$content['order_no'] 		= $this->get_order_no();
		$rs = $this->order_model->insert($content);
		if($rs){
			$this->response(['result' => 'SUCCESS']);
		}else{
			$this->response(['error' =>'InsertError'],REST_Controller::HTTP_CONFLICT);//409 新增錯誤
		}		
    }

	 /**
     * @api {get} /order/info Order Information
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderInfo
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiParam {String} merchant_order_no Custom Order NO
	 *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} merchant_order_no Custom Order NO
	 * @apiSuccess {String} phone User Phone Number
	 * @apiSuccess {Number} product_id Product ID
	 * @apiSuccess {Number} amount Amount
	 * @apiSuccess {Number} instalment Number Of Instalment
	 * @apiSuccess {Number} status Order Status 0:Pendding 1:Success 2:Verifying 8:Cancel 9:Fail
	 * @apiSuccess {Number} created_at Created Unix Timestamp
	 *
     * @apiSuccessExample {Object} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"merchant_order_no": "2017545465159489",
     *      	"phone": "0977254651",
     *      	"product_id": 2,
     *      	"amount": 6000,
     *      	"instalment": 3,
     *      	"status": 0,
     *      	"created_at": 1547558418
	 *      }
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (404) OrderNotFound Merchant Order NO Not Found.
	 * @apiErrorExample OrderNotFound
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "OrderNotFound"
	 *     }
     *
     */
	public function info_get()
    {
		$input 			= $this->input->get(NULL, TRUE);
		if (empty($input['merchant_order_no'])) {
			$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
		}
		
		$this->load->model('transaction/order_model');
		$order = $this->order_model->get_by([
			'company_user_id'	=> $this->cooperation_info->company_user_id,
			'merchant_order_no'	=> $input['merchant_order_no'],
		]);
		
		if($order){
			$data = [
				'merchant_order_no'	=> $order->merchant_order_no,
				'phone'				=> $order->phone,
				'product_id'		=> intval($order->product_id),
				'amount'			=> intval($order->amount),
				'instalment'		=> intval($order->instalment),
				'status'			=> intval($order->status),
				'created_at'		=> intval($order->created_at),
			];
			$this->response(['result' => 'SUCCESS','data' => $data]);
		}
		$this->response(['error' =>'OrderNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此單號
    }

	 /**
     * @api {get} /order/list Order List
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName GetOrderList
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiDescription Dates In 90-Days Range.
	 *
	 * @apiParam {String} [start_date=today] Start Date (YYYY-mm-dd)
	 * @apiParam {String} [end_date=today] End Date (YYYY-mm-dd)
	 *
     * @apiSuccess {Object} result SUCCESS
	 * @apiSuccess {String} merchant_order_no Custom Order NO
	 * @apiSuccess {String} phone User Phone Number
	 * @apiSuccess {Number} product_id Product ID
	 * @apiSuccess {Number} amount Amount
	 * @apiSuccess {Number} instalment Number Of Instalment
	 * @apiSuccess {Number} status Order Status 0:Pendding 1:Success 2:Verifying 8:Cancel 9:Fail
	 * @apiSuccess {Number} created_at Created Unix Timestamp
	 *
     * @apiSuccessExample {Object} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     * 			"list":[
     * 				{
     *      			"merchant_order_no": "2017545465159489",
     *      			"phone": "0977254651",
     *      			"product_id": 2,
     *      			"amount": 6000,
     *      			"instalment": 3,
     *      			"status": 0,
     *      			"created_at": 1547558418
     * 				}
     * 			]
	 *      }
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse CooperationNotFound
     *
	 * @apiError (400) ArgumentError Insert Error.
	 * @apiErrorExample ArgumentError
	 *     HTTP/1.1 400 Not Found
	 *     {
	 *       "error": "ArgumentError"
	 *     }
     */
	public function list_get()
    {
		$input 	= $this->input->get(NULL, TRUE);
		$list 	= [];
		$sdate	= isset($input['start_date'])?date('Y-m-d',strtotime($input['start_date'])):date('Y-m-d');
		$edate	= isset($input['end_date'])?date('Y-m-d',strtotime($input['end_date'])):date('Y-m-d');
		if($edate < $sdate || get_range_days($sdate,$edate)>90){
			$this->response(['error' =>'ArgumentError'],REST_Controller::HTTP_BAD_REQUEST);//400 參數有誤
		}
		
		$this->load->model('transaction/order_model');
		$orders = $this->order_model->get_many_by([
			'created_at >='	=> strtotime($sdate.' 00:00:00'),
			'created_at <='	=> strtotime($edate.' 23:59:59'),
		]);

		if($orders){
			foreach($orders as $key => $value){
				$list[] = [
					'merchant_order_no'	=> $value->merchant_order_no,
					'phone'				=> $value->phone,
					'product_id'		=> intval($value->product_id),
					'amount'			=> intval($value->amount),
					'instalment'		=> intval($value->instalment),
					'status'			=> intval($value->status),
					'created_at'		=> intval($value->created_at),
				];
			}
		}
		$this->response(array('result' => 'SUCCESS','data' => ['list'=> $list ]));
    }
	
	/**
     * @api {post} /order/cancel Cancel Order
     * @apiGroup Order
	 * @apiVersion 0.1.0
	 * @apiName PostOrderCancel
	 * @apiHeader {String} Authorization Bearer MD5(SHA1(CooperationID + merchant_order_no + Timestamp) + CooperationKey)
	 * @apiHeaderExample {String} Authorization
	 * Bearer fcea920f7412b5da7be0cf42b8c93759
	 * @apiHeader {String} CooperationID CooperationID
	 * @apiHeaderExample {String} CooperationID
	 * CO12345678
	 * @apiHeader {Number} Timestamp Unix Timestamp
	 * @apiHeaderExample {Number} Timestamp
	 * 1546932175
	 *
	 * @apiDescription Only Status In Pendding
	 *
	 * @apiParam {String} merchant_order_no Custom Order NO
	 *
     * @apiSuccess {Object} result SUCCESS
     * @apiSuccessExample {Object} Success-Response:
	 *     HTTP/1.1 200 OK
     *    {
     *      "result": "SUCCESS"
     *    }
     * 
	 * @apiUse AuthorizationRequired
	 * @apiUse IllegalIP
	 * @apiUse TimeOut
	 * @apiUse RequiredArguments
	 * @apiUse CooperationNotFound
	 *
	 * @apiError (404) OrderNotFound Merchant Order NO Not Found.
	 * @apiErrorExample OrderNotFound
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": "OrderNotFound"
	 *     }
     * 
     */
	public function cancel_post()
    {
		$product_list 	= $this->config->item('product_list');
		$input 			= $this->input->post(NULL, TRUE);
		if (empty($input['merchant_order_no'])) {
			$this->response(['error' =>'RequiredArguments'],REST_Controller::HTTP_BAD_REQUEST);//400 缺少參數
		}
		
		$this->load->model('transaction/order_model');
		$order = $this->order_model->get_by([
			'company_user_id'	=> $this->cooperation_info->company_user_id,
			'merchant_order_no'	=> $input['merchant_order_no'],
			'status'			=> 0,
		]);
		
		if($order){
			$this->order_model->update($order->id,['status'=>8]);
			$this->response(['result' => 'SUCCESS']);
		}
		$this->response(['error' =>'OrderNotFound'],REST_Controller::HTTP_NOT_FOUND);//404 無此單號

    }
	
	private function get_order_no(){
		return $this->cooperation_info->company_user_id.'-'.date('YmdHis').rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
	}
}
