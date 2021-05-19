<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class LoanResponse extends REST_Controller {

    public function __construct()
    {
        parent::__construct();

        // check ip, request must from bank adapter server
        $clientIp = $this->input->ip_address();
        $allowIp = $this->config->item('bank_adapter_ip');
        if ($clientIp !== $allowIp) {
            $this->output->set_status_header(404);
            exit(0);
        }
    }

    /**
     * [index_post description]
     * @return array [result]
     */
    public function index_post()
    {
        // TODO: check input data legal

        $response = [
            'result' => 'ERROR'
        ];

        $input = json_decode($this->input->raw_input_stream, true);

        // yyyy-mm-dd is between 2000-01-01 ~ 2099-12-31
        $yyyymmddRegex = '/^(20[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/';
        // decimal >= 0, not contain plus (+)
        $decimalRegex = '/^([0-9]*[.])?[0-9]+$/';

        $checkInputParamsArr = [
            // necessary items
            [$input, 'case_no'                , true , '/^(20[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(\d{8})$/'],
            // optional items
            [$input, 'apply_agree'            , false, '/^[AB]{1}$/'],
            [$input, 'apply_accept'           , false, '/^[AB]{1}$/'],
            [$input, 'loan_amount'            , false, '/^\d*$/'],
            [$input, 'loan_begin'             , false, $yyyymmddRegex],
            [$input, 'loan_expire'            , false, $yyyymmddRegex],
            [$input, 'loan_rate'              , false, $decimalRegex],
            [$input, 'interest_bearing_type'  , false, '/^[AB]{1}$/'],
            [$input, 'draw_type'              , false, '/^[ABC]{1}$/'],
            # \x{0000} is PCRE standard
            [$input, 'additional_cond'        , false, '/^[\x{0000}-\x{ffff}]{0,128}$/u'],
            [$input, 'smeg_guarantee_date'    , false, $yyyymmddRegex],
            [$input, 'smeg_guarantee_percent' , false, $decimalRegex],
            [$input, 'smeg_guarantee_begin'   , false, $yyyymmddRegex],
            [$input, 'smeg_guarantee_expire'  , false, $yyyymmddRegex],
            [$input, 'smeg_guarantee_no'      , false, '/^[\w\d]{10}$/'],
            [$input, 'funding_date'           , false, $yyyymmddRegex],
            [$input, 'funding_amount'         , false, '/^\d*$/'],
            [$input, 'funding_rate'           , false, $decimalRegex],
            [$input, 'funding_begin'          , false, $yyyymmddRegex],
            [$input, 'funding_expire'         , false, $yyyymmddRegex],
            [$input, 'repayment_status'       , false, '/^[AB]{1}$/']
        ];

        // data wait to insert
        $insertData = [];

        // check data
        $allInputLegal = true;

        foreach ($checkInputParamsArr as $key => $paramArr) {
            $checkResult = $this->checkDataFormat($paramArr[0], $paramArr[1], $paramArr[2], $paramArr[3]);
            if (!$checkResult['result']) {
                $allInputLegal = false;
                $response['error'] = $checkResult['errorMsg'];
                break;
            } else if (isset($paramArr[0][$paramArr[1]])) {
                $insertData[$paramArr[1]] = $paramArr[0][$paramArr[1]];
            }
        }

        // extra check
        // if ($allInputLegal) {}

        try {
            // insert to database
            if ($allInputLegal) {
                $this->load->model('skbank/LoanResult_model');

                // check case_no not duplicated in database
                $msgNoExist = $this->LoanResult_model->get_by(['case_no' => $input['case_no']]);
                if (!$msgNoExist) {

                    // set default status
                    $insertData['status'] = 0;

                    $newLoanResultId = $this->LoanResult_model->insert($insertData);
                    if ($newLoanResultId) {
                        $response['result'] = 'SUCCESS';

                        // TODO: process target, wait to implement

                    } else {
                        $response['error'] = 'database insert fail';
                    }
                } else {
                    $response['error'] = sprintf("parameter 'case_no' value='%s' was already existed", $input['case_no']);
                }
            }
        } catch (Exception $e) {
            $response['error'] = 'server internal error, please contact administrator';
        }

        // log request
        $this->load->model('skbank/LoanReceiveResponseLog_model');
        $newLogId = $this->LoanReceiveResponseLog_model->insert([
            'case_no'           => isset($input['case_no']) ? $input['case_no'] : '',
            'process_success'   => $response['result'] == 'SUCCESS' ? true : false,
            'error_msg'         => isset($response['error']) ? $response['error'] : '',
            'content'           => json_encode($input),
        ]);

        if ($newLogId) {
            $response['meta_info'] = sprintf("insert log success, log id = %s", $newLogId);
        }

        $this->response($response);
    }

    /**
     * [checkDataFormat description]
     * @param  array  $inputElement  [input element]
     * @param  string  $inputKey     [input key]
     * @param  boolean $necessary    [required or not]
     * @param  string  $regexPattern [data check]
     * @return array                 [result]
     */
    private function checkDataFormat ($inputElement, $inputKey, $necessary = false, $regexPattern = '/.*/')
    {
        $result = [
            'result' => false
        ];

        if ($necessary) {
            if (!isset($inputElement[$inputKey])) {
                $result['errorMsg'] = sprintf("parameter '%s' is not found", $inputKey);
            } elseif (!preg_match($regexPattern, $inputElement[$inputKey])) {
                $result['errorMsg'] = sprintf("parameter '%s' value='%s' is illegal", $inputKey, $inputElement[$inputKey]);
            } else {
                $result['result'] = true;
            }
        } else {
            if (isset($inputElement[$inputKey]) && !preg_match($regexPattern, $inputElement[$inputKey])) {
                $result['errorMsg'] = sprintf("parameter '%s' value='%s' is illegal", $inputKey, $inputElement[$inputKey]);
            } else {
                $result['result'] = true;
            }
        }

        return $result;
    }
}
