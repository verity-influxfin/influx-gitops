<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Order extends REST_Controller {

	public $user_info;
	
    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();
        $nonAuthMethods = [];
        if (!in_array($method, $nonAuthMethods)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:"";
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time < time()) {
				$this->response(array('result' => 'ERROR','error' => TOKEN_NOT_CORRECT ));
            }
        }
    }
	
	/**
     * @api {get} /order/schedule 還款計畫
     * @apiGroup Order
	 * @apiParam {number} amount 總金額
	 * @apiParam {number} instalment 期數
	 * @apiParam {String} merchant_order_no 自訂編號
	 * @apiParam {String} cooperation_id CooperationID
	 * @apiParam {String} time Unix Timestamp
	 * @apiParam {String} cooperation_token MD5(SHA1(amount & instalment & cooperation_id & time)+ CooperationKEY)
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} request_token Request Token
	 * @apiSuccess {json} amortization_schedule 預計還款計畫
	 * @apiSuccess {String} amortization_schedule.amount 借款金額
	 * @apiSuccess {String} amortization_schedule.instalment 借款期數
	 * @apiSuccess {String} amortization_schedule.rate 年利率
	 * @apiSuccess {String} amortization_schedule.date 起始時間
	 * @apiSuccess {String} amortization_schedule.total_payment 每月還款金額
	 * @apiSuccess {String} amortization_schedule.leap_year 是否為閏年
	 * @apiSuccess {String} amortization_schedule.year_days 本年日數
	 * @apiSuccess {String} amortization_schedule.XIRR XIRR
	 * @apiSuccess {String} amortization_schedule.schedule 還款計畫
	 * @apiSuccess {String} amortization_schedule.schedule.instalment 第幾期
	 * @apiSuccess {String} amortization_schedule.schedule.repayment_date 還款日
	 * @apiSuccess {String} amortization_schedule.schedule.days 本期日數
	 * @apiSuccess {String} amortization_schedule.schedule.remaining_principal 剩餘本金
	 * @apiSuccess {String} amortization_schedule.schedule.principal 還款本金
	 * @apiSuccess {String} amortization_schedule.schedule.interest 還款利息
	 * @apiSuccess {String} amortization_schedule.schedule.total_payment 本期還款金額
	 * @apiSuccess {String} amortization_schedule.total 還款總計
	 * @apiSuccess {String} amortization_schedule.total.principal 本金
	 * @apiSuccess {String} amortization_schedule.total.interest 利息
	 * @apiSuccess {String} amortization_schedule.total.total_payment 加總
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
  	 *      "amortization_schedule": {
  	 *          "amount": "12000",
  	 *          "instalment": "6",
  	 *          "rate": "9",
  	 *          "date": "2018-04-17",
  	 *          "total_payment": 2053,
  	 *          "leap_year": false,
  	 *          "year_days": 365,
  	 *          "XIRR": 0.0939,
  	 *          "schedule": {
 	 *                "1": {
   	 *                  "instalment": 1,
   	 *                  "repayment_date": "2018-06-10",
   	 *                  "days": 54,
   	 *                  "remaining_principal": "12000",
   	 *                  "principal": 1893,
   	 *                  "interest": 160,
   	 *                  "total_payment": 2053
   	 *              },
   	 *              "2": {
  	 *                   "instalment": 2,
   	 *                  "repayment_date": "2018-07-10",
   	 *                  "days": 30,
  	 *                   "remaining_principal": 10107,
  	 *                   "principal": 1978,
  	 *                   "interest": 75,
 	 *                    "total_payment": 2053
  	 *               },
   	 *              "3": {
 	 *                    "instalment": 3,
 	 *                    "repayment_date": "2018-08-10",
 	 *                    "days": 31,
 	 *                    "remaining_principal": 8129,
  	 *                   "principal": 1991,
  	 *                   "interest": 62,
 	 *                    "total_payment": 2053
 	 *                }
 	 *            },
  	 *           "total": {
 	 *                "principal": 12000,
 	 *                "interest": 391,
 	 *                "total_payment": 12391
	 *            }
	 *      }
     *    }
     * 
     */
	public function schedule_get()
    {
    }
	
	/**
     * @api {post} /order/add 新增訂單
     * @apiGroup Order
	 * @apiParam {number} amount 總金額
	 * @apiParam {number} instalment 期數
	 * @apiParam {String} merchant_order_no 自訂編號
	 * @apiParam {String} item_name 商品名稱，多項商品時，以逗號分隔
	 * @apiParam {number} item_count 商品數量，多項商品時，以逗號分隔
	 * @apiParam {number} item_price 商品單價，多項商品時，以逗號分隔
	 * @apiParam {String} cooperation_id CooperationID
	 * @apiParam {String} time Unix Timestamp
	 * @apiParam {String} cooperation_token MD5(SHA1(amount & instalment & merchant_order_no & item_name & item_count & item_price & cooperation_id & time)+ CooperationKEY)
     *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} merchant_order_no 廠商自訂編號
     * @apiSuccess {String} order_no 訂單單號
     * @apiSuccess {String} request_token RequestToken
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
	 *		"merchant_order_no": "A123456789",
	 *		"order_no": "20180405113558632",
     *      "request_token": "fcea920f7412b5da7be0cf42b8c93759"
     *    }
     * 
     */
	public function add_post()
    {
    }

	 /**
     * @api {get} /order/info 訂單資訊
     * @apiGroup Order
	 * @apiParam {String} cooperation_id CooperationID
	 * @apiParam {String} time Unix Timestamp
	 * @apiParam {String} cooperation_token MD5(SHA1(cooperation_id & time) + CooperationKEY)
	 *
     * @apiSuccess {json} result SUCCESS
     * @apiSuccess {String} company 公司名稱
     * @apiSuccess {String} tax_id 統一編號
     * @apiSuccess {String} name 負責人姓名
     * @apiSuccess {String} phone 負責人電話
     * @apiSuccess {String} my_promote_code 邀請碼
	 *
     * @apiSuccessExample {json} SUCCESS
     *    {
     *      "result": "SUCCESS",
     *      "data": {
     *      	"company": "普匯金融科技股份有限公司",
     *      	"tax_id": "68566881",
     *      	"name": "陳霈",
     *      	"phone": "0912345678",
     *      	"my_promote_code": "9JJ12CQ5",  
	 *      }
     *    }
     *
     */
	public function info_get()
    {
    }

	
}
