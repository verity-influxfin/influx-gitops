<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'/libraries/REST_Controller.php');

class Reports extends REST_Controller {

    public $user_info;

    private $allowedReports = ['balance_sheet', 'income_statement', 'business_tax_return_report','insurance_table','amendment_of_register','credit_investigation','identification_card_front','identification_card_back','national_health_insurance'];
	private $needAuthReports = ['identification_card_front', 'identification_card_back', 'national_health_insurance'];
    public function __construct()
    {
        parent::__construct();
        $this->load->library('S3_upload');
        $this->load->library('S3_lib');
        $this->load->library('log/log_image_model');

        $method = $this->router->fetch_method();
		$input = $this->input->get(NULL, TRUE);
		$type = isset($input["type"]) ? $input["type"] : '';

        if (in_array($type, $this->needAuthReports)) {
            $token 		= isset($this->input->request_headers()['request_token'])?$this->input->request_headers()['request_token']:'';
            $tokenData 	= AUTHORIZATION::getUserInfoByToken($token);
            if (empty($tokenData->id) || empty($tokenData->phone) || $tokenData->expiry_time<time()) {
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
                    )
                );
            }
        }
    }

    /**
      * @api {get} /v2/reports/info 獲取報表資訊
      * @apiVersion 0.2.0
      * @apiName ReportsInfo
      * @apiGroup Reports
      * @apiHeader {String} request_token 登入後取得的 Request Token
      *
      * @apiSuccess {Object} result SUCCESS
      * @apiSuccess {Object} balance_sheet_logs
      * @apiSuccess {Object} income_statement_logs
      * @apiSuccess {Object} business_tax_return_logs
      * @apiSuccessExample {Object} SUCCESS
      *    {
      * 		"result": "SUCCESS",
      * 		"data": {
      * 			"balance_sheet_logs" : {
      *			    "count" : 1,
      *             "items": [
      *                  {
      * 				        "id": 4,
      * 				        "status": "finished",
      * 				        "balance_sheet": {
      *                          "report_time": "107年12月31日",
      *                          "company": {
      *                              "name": "鴻海股份有限公司",
      *                              "taxId": "68566881"
      *                          }
      *                      },
      * 		    		 "created_at": 1520421572,
      *                      "updated_at": 1520421572
      *  			    }
      *              ]
      *          }
      * 		}
      *    }
      *
      * @apiUse TokenError
      * @apiUse BlockUser
      * @apiUse InputNotCorrect
      * @apiUse ExitError (Ocr subsystem not return anything back)
      *
     */
    public function info_get($ocr_type = '')
    {
        $input = $this->input->get(NULL, TRUE);
        $referenceIdString = isset($input["reference_id"]) ? $input["reference_id"] : '';
        $type = isset($input["type"]) ? $input["type"] : '';
		$identity = isset($input["identity"]) ? $input["identity"] : '';

        // if($type == 'amendment_of_register_ltd_shares' || $type == 'amendment_of_register_ltd'){
        //   $ocr_type = str_replace('amendment_of_register_','',$type);
        //   $type = 'amendment_of_register';
        // }
        $referenceIds = explode(",", $referenceIdString);

        if (!$referenceIds || !in_array($type, $this->allowedReports)) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        $this->load->model('log/log_image_model');
        $imageLogs = $this->log_image_model->get_many($referenceIds);

        if($type == 'amendment_of_register' ){
          $user_id = $imageLogs[0]->user_id;

          $this->load->model('user/user_model');
          $user_infos = $this->user_model->get_many($user_id);

          $tax_id = $user_infos[0]->id_number;
          $this->load->library('gcis_lib');

          $gcis_info = $this->gcis_lib->account_info($tax_id);
          if($gcis_info){
            $company_name = $gcis_info['Company_Name'];

            if(preg_match('/股份有限公司/',$company_name)){
              $ocr_type = 'ltd_shares';
            }else{
              $ocr_type = 'ltd';
            }

          }else{
			$ocr_type = 'ltd';
            // $this->response(['result' => 'FAIL']);exit;
          }
        }

        if($type == 'insurance_table'){
          $ocr_type = ! empty($identity) ? $identity : 'person';
        }

        if($type == 'credit_investigation'){
          $user_id = $imageLogs[0]->user_id;
          $user_info = $this->user_model->get_by(['id' => $user_id]);
          $ocr_type = $user_info->company_status ? 'company' : 'person';
        }

        if (!$imageLogs) {
            $this->response(['result' => 'ERROR','error' => INPUT_NOT_CORRECT]);
        }

        // 比對圖片記錄的擁有者是否與要求存取的人一樣
		// 不匹配時則返回權限不足
		if(isset($this->user_info)){
			$validLogs = array_filter($imageLogs, function($log){
				return $log->user_id==$this->user_info->id;
			});
	        if(count($validLogs) != count($imageLogs))
				$this->response(['result' => 'ERROR','error' => PERMISSION_DENY]);
		}

        $numRequestedIds = [];
//        $ownerId = $this->user_info->id;
        $ownerId = false;
        $batchType = "{$type}s";
        $this->load->library('ocr/report_scan_lib');

        $imageIds = [];
        $statusForImage = [];
        $logKey = "{$type}_logs";
        if ($type == 'business_tax_return_report') {
            $logKey = "business_tax_return_logs";
        }
        //assume all provided image id should be fed to ocr
        $groupId = time();
        foreach ($imageLogs as $imageLog) {
          if($type == 'amendment_of_register' || $type == 'credit_investigation' || $type == 'amendment_of_registers' || $type == 'credit_investigations' || (($type == 'insurance_table'|| $type == 'insurance_tables') && $ocr_type == 'person') ){
            if($imageLog->group_info){
              $imageIds[] = $imageLog->group_info;
              $statusForImage[$imageLog->group_info] = 204;
            }else{
              $imageGroup = $this->log_image_model->insertGroupById([$imageLog->id],['group_info'=>$groupId]);
              $imageIds[] = $groupId;
              $statusForImage[$groupId] = 204;
              $imageLog->group_info = $groupId;
            }
          }else{
            $imageIds[] = $imageLog->id;
            $statusForImage[$imageLog->id] = 204;
          }

            !$ownerId ? $ownerId = $imageLog->user_id : '';

        }
        $numRequested = count($imageLogs);
        $response = $this->report_scan_lib->requestForResult($batchType, $imageIds);
        if (!$response) {
          $this->response(['result' => 'SUCCESS','data' => []]);
          //  $this->response(['result' => 'ERROR','error' => EXIT_ERROR,'msg' => 'The result not found.']);
        }
        //removed already OCRed image so sleep time can be reduced
        if ($response->status == 200) {
            foreach ($response->response->$logKey->items as $eachOcr) {
                $statusForImage[$eachOcr->id] = 200;
                $numRequested--;
            }
        }

        if($type == 'amendment_of_register' || $type == 'credit_investigation' || $type == 'amendment_of_registers' || $type == 'credit_investigations' ||  (($type == 'insurance_table'|| $type == 'insurance_tables') && $ocr_type == 'person')){
          if($statusForImage[$imageIds[0]] == 204){
            $this->report_scan_lib->requestForScan($type, $imageLogs, $ownerId, $ocr_type);
          }
          $numRequestedIds[] = $imageIds[0];
        }else{
          foreach ($imageLogs as $imageLog) {
  //            if ($ownerId != $imageLog->user_id) {
  //                continue;
  //            }

              if ($statusForImage[$imageLog->id] == 204) {
                  $this->report_scan_lib->requestForScan($type, $imageLog, $ownerId, $ocr_type);
              }
              $numRequestedIds[] = $imageLog->id;
          }
        }

        #waiting for data to be completed
        $sleepingTime = $numRequested * 3;
        sleep($sleepingTime);
        if (!$numRequestedIds) {
            $this->response(['result' => 'ERROR','error' => EXIT_ERROR, 'msg' => 'numRequestedIds not found.']);
        }

        $scannedResult = [];
        $response = $this->report_scan_lib->requestForResult($batchType, $numRequestedIds);
        if (!$response) {
          $this->response(['result' => 'SUCCESS','data' => []]);
            // $this->response(['result' => 'ERROR','error' => EXIT_ERROR, 'msg' => 'The scan result not found.']);
        }

        if (isset($response->response)) {
            if(($type == 'amendment_of_register' && $type == 'credit_investigation') && isset($imageIds[0])){
              $resType = $type.'_logs';
              if(isset($response->response->$resType->items[0]->id) && $imageIds[0]==$response->response->$resType->items[0]->id){
                $response->response->$resType->items[0]->id = isset($referenceIds) ? $referenceIdString : $response->response->$resType->items[0]->id;
              }
            }
            $this->response(['result' => 'SUCCESS', 'data' => $response->response]);
        }
        $this->response(['result' => 'SUCCESS']);
    }
}
