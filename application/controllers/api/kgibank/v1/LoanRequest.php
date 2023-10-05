<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Adapter\Adapter_factory;

class LoanRequest extends REST_Controller {

    // crypto url
    private $vestaKgibankCryptoUrl = VESTA_ENDPOINT . "/vesta/api/v1.0/kgibank";

    // send text url
    private $kgibankRequestUrl = KGIBANK_LOAN_ENDPOINT . "/eCredit/v1/order";

    // send image url
    private $kgibankImageUrl = KGIBANK_LOAN_ENDPOINT . "/eCredit/v1/attach";

    // send image completed url
    private $kgibankImageCompleteUrl = KGIBANK_LOAN_ENDPOINT . "/eCredit/v1/finish";

    // image compress url
    private $ocrCompressImageUrl = INFLUX_OCR_ENDPOINT . "/ocr/api/v1.0/image-process/compress";

    // megabytes
    private $singleImageMaxSize = 2;

    public function __construct()
    {
        parent::__construct();

        // check ip, request must come from allowed ip
        $clientIp = $this->input->ip_address();
        $allowIpArr = $this->config->item('access_ip_list');
        if (!in_array($clientIp, $allowIpArr)) {
            $this->output->set_status_header(404);
            exit(0);
        }
    }

    // TODO: wait to modify
    // total send text and image
    public function apply_all_post()
    {
        // sendImage method extract image from internet, may exceed default excute timeout (30s)
        // TODO: wait to estimate process seconds
        set_time_limit(1200);

        $result = [
            'send_text_success'           => false,
            'send_text_error'             => '',
            'send_total_image_success'    => false,
            'send_total_image_error'      => '',
            'send_image_complete_success' => false,
            'send_image_complete_error'   => '',
        ];

        $inputArr = json_decode($this->input->raw_input_stream, true);

        if (!isset($inputArr["request_text"])) {
            $result["error"] = "input parameter 'request_text' is not found";
            goto END;
        }

        if (!isset($inputArr["request_image_list"])) {
            $result["error"] = "input parameter 'request_image_list' is not found";
            goto END;
        }

        $textInputArr = $inputArr["request_text"];
        $imageListInputArr = $inputArr["request_image_list"];

        // send text request
        $sendTextResult = $this->sendText($textInputArr);
        // log send text request
        $this->writeSendTextLog($sendTextResult['msg_no'], $sendTextResult['case_no'], $sendTextResult['success'], $sendTextResult['error'], $sendTextResult['request_content'], $sendTextResult['response_content']);

        // check text request send success
        if ($sendTextResult["success"]) {
            $result["send_text_success"] = true;

            $msgNo = $sendTextResult["msg_no"];
            $caseNo = $sendTextResult["case_no"];
            $companyId = $inputArr["request_text"]["CompId"];

            $totalImageCount = count($imageListInputArr);
            $sendImageSuccessCount = 0;
            foreach ($imageListInputArr as $key => $value) {
                $imageInputArr = $value;
                $imageInputArr["CaseNo"] = $caseNo;
                // send image request
                $sendImageResult = $this->sendImage($imageInputArr);
                // log send image request
                $this->writeSendImageLog($sendImageResult['msg_no'], $sendImageResult['case_no'], $sendImageResult['success'], $sendImageResult['error'], $sendImageResult['request_content'], $sendImageResult['response_content']);

                if ($sendImageResult["success"]) {
                    $sendImageSuccessCount++;
                }
            }

            // check all image send success
            if ($sendImageSuccessCount == $totalImageCount) {
                $result["send_total_image_success"] = true;

                $inputCompeleteArr = [
                    "MsgNo" => $msgNo,
                    "CaseNo" => $caseNo,
                    "CompId" => $companyId,
                ];
                // send image complete request
                $sendImageCompleteResult = $this->sendImageComplete($inputCompeleteArr);
                // log send image complete request
                $this->writeSendImageCompleteLog($sendImageCompleteResult['msg_no'], $sendImageCompleteResult['case_no'], $sendImageCompleteResult['success'], $sendImageCompleteResult['error'], $sendImageCompleteResult['request_content'], $sendImageCompleteResult['response_content']);

                if ($sendImageCompleteResult["success"]) {
                    $result["send_image_complete_success"] = true;
                } else {
                    $result["send_image_complete_error"] = $sendImageCompleteResult["error"];
                }
            } else {
                $errMsg = sprintf("total image not send success, success=%s, total=%s", $sendImageSuccessCount, $totalImageCount);
                $result["send_total_image_error"] = $errMsg;
            }
        } else {
            $result["send_text_error"] = $sendTextResult["error"];
        }
END:
        $this->response($result);

    }

    // single send a text
    public function apply_text_post()
    {
        $result = [
            'success' => false,
            'error' => '',
            'msg_no' => '',
            'case_no' => '',
            'meta_info' => ''
        ];

        $applyTextInputArr = json_decode($this->input->raw_input_stream, true);
        $applyTextResult = $this->sendText($applyTextInputArr);

        $result['success'] = $applyTextResult['success'];
        $result['error'] = $applyTextResult['error'];
        $result['msg_no'] = $applyTextResult['msg_no'];
        $result['case_no'] = $applyTextResult['case_no'];

        // log request
        $newLogId = $this->writeSendTextLog($applyTextResult['msg_no'], $applyTextResult['case_no'], $applyTextResult['success'], $applyTextResult['error'], $applyTextResult['request_content'], $applyTextResult['response_content']);
        if ($newLogId) {
            $result['meta_info'] = sprintf("insert log success, log id = %s", $newLogId);
        } else {
            $result['meta_info'] = "insert log faild";
        }

        $this->response($result);
    }

    // single send a image
    public function apply_image_post()
    {
        $result = [
            'success' => false,
            'error' => '',
            'msg_no' => '',
            'case_no' => '',
            'meta_info' => ''
        ];

        $applyImageInputArr = json_decode($this->input->raw_input_stream, true);
        $applyImageResult = $this->sendImage($applyImageInputArr);

        $result['success'] = $applyImageResult['success'];
        $result['error'] = $applyImageResult['error'];
        $result['msg_no'] = $applyImageResult['msg_no'];
        $result['case_no'] = $applyImageResult['case_no'];

        // log request
        $newLogId = $this->writeSendImageLog($applyImageResult['msg_no'], $applyImageResult['case_no'], $applyImageResult['success'], $applyImageResult['error'], $applyImageResult['request_content'], $applyImageResult['response_content']);
        if ($newLogId) {
            $result['meta_info'] = sprintf("insert log success, log id = %s", $newLogId);
        } else {
            $result['meta_info'] = "insert log faild";
        }

        $this->response($result);
    }

    // single send image list
    public function apply_image_list_post()
    {
        // sendImage method extract image from internet, may exceed default excute timeout (30s)
        // TODO: wait to estimate process seconds
        set_time_limit(1000);

        $result = [
            'success' => false,
            'error' => '',
            'fail_result_list' => [],
            'total_image_count' => 0,
            'log_id_list' => [],
        ];

        $applyImageListInputArr = json_decode($this->input->raw_input_stream, true);
        $applyImageListResult = $this->sendImageList($applyImageListInputArr);
        $result['success'] = $applyImageListResult['success'];
        $result['error'] = $applyImageListResult['error'];
        $result['fail_result_list'] = $applyImageListResult['fail_result_list'];

        // log request
        $totalResultList = $applyImageListResult['total_result_list'];
        $log_id_list = [];
        foreach ($totalResultList as $key => $sendImageResult) {
            $newLogId = $this->writeSendImageLog($sendImageResult['msg_no'], $sendImageResult['case_no'], $sendImageResult['success'], $sendImageResult['error'], $sendImageResult['request_content'], $sendImageResult['response_content']);
            if ($newLogId) {
                $log_id_list[] = $newLogId;
            }
        }

        $result['total_image_count'] = count($totalResultList);
        $result['log_id_list'] = $log_id_list;

        $this->response($result);
    }

    // single send image complete
    public function apply_image_complete_post()
    {
        $result = [
            'success' => false,
            'error' => '',
            'msg_no' => '',
            'case_no' => '',
            'meta_info' => ''
        ];

        $applyImageCompleteInputArr = json_decode($this->input->raw_input_stream, true);
        $applyImageCompleteResult = $this->sendImageComplete($applyImageCompleteInputArr);

        $result['success'] = $applyImageCompleteResult['success'];
        $result['error'] = $applyImageCompleteResult['error'];
        $result['msg_no'] = $applyImageCompleteResult['msg_no'];
        $result['case_no'] = $applyImageCompleteResult['case_no'];

        // log request
        $newLogId = $this->writeSendImageCompleteLog($applyImageCompleteResult['msg_no'], $applyImageCompleteResult['case_no'], $applyImageCompleteResult['success'], $applyImageCompleteResult['error'], $applyImageCompleteResult['request_content'], $applyImageCompleteResult['response_content']);

        // change target status
        if ($applyImageCompleteResult['success'] == 1) {
            $this->load->model('skbank/LoanSendRequestLog_model');
            $loanRequestLogInfo = $this->LoanSendRequestLog_model->get_by(['case_no' => $applyImageCompleteResult['case_no']]);
            if ($loanRequestLogInfo) {
                // search mapping info by loan request msg_no
                $this->load->model('skbank/LoanTargetMappingMsgNo_model');
                $loanTargetMappingInfo = $this->LoanTargetMappingMsgNo_model->get_by(['msg_no' => $loanRequestLogInfo->msg_no]);
                if ($loanTargetMappingInfo) {
                    // check target exist and update info
                    $this->load->model('loan/target_model');
                    $targetInfo = $this->target_model->get_by(['id' => $loanTargetMappingInfo->target_id,'product_id' => PRODUCT_SK_MILLION_SMEG]);
                    if($targetInfo){
                        $updateTarget = $this->target_model->update($targetInfo->id,[
                            'status' => TARGET_BANK_VERIFY
                        ]);
                    }
                }
            }
        }

        if ($newLogId) {
            $result['meta_info'] = sprintf("insert log success, log id = %s", $newLogId);
        } else {
            $result['meta_info'] = "insert log faild";
        }

        $this->response($result);
    }

    private function sendText($inputArr)
    {
        $result = [
            'success' => false,
            'error' => '',
            'msg_no' => '',
            'case_no' => '',
            'request_content' => [],
            'response_content' => []
        ];

        $msgNo = '';
        $caseNo = '';
        $requestContent = ['raw_input' => json_encode($inputArr)];
        $responseContent = [];

        try {
            // check input parameter
            $checkInputParamsArr = [
                // necessary items
                [$inputArr, 'MsgNo'       , true, '/^[\d]{15}$/'],
                [$inputArr, 'CompId'      , true, '/^[\d]{8,11}$/']
            ];
            foreach ($checkInputParamsArr as $key => $value) {
                $checkResult = $this->checkDataFormat($value[0], $value[1], $value[2], $value[3]);
                if (!$checkResult['result']) {
                    $result['error'] = $checkResult['errorMsg'];
                    goto END;
                }
            }

            $this->load->model('skbank/LoanTargetMappingMsgNo_model');
            $mapping_info = $this->LoanTargetMappingMsgNo_model->get_by([
                'msg_no' => $inputArr['MsgNo'], 'type' => 'text']);

            if($mapping_info)
            {
                $adapter = Adapter_factory::getInstance($mapping_info->bank, $mapping_info->target_id);
                if ( ! isset($adapter))
                {
                    $result['error'] = 'cannot get Adapter instance';
                    goto END;
                }
                $inputArr = $adapter->convert_text($inputArr);
            }

            $dataJsonStr = json_encode($inputArr["data"]);
            // extra check data
            if (!isset($inputArr["data"])) {
                $result['error'] = sprintf("parameter 'Data' is not found");
                goto END;
            } else if (!$this->isJson($dataJsonStr)) {
                $result['error'] = sprintf("parameter 'Data' value is illegal");
                goto END;
            }

            $msgNo = $inputArr["msgNo"];
            $compId = $inputArr["compId"];
            $sendTime = $this->getCurrentSendTime();
            $contentData = $inputArr["data"];

            $request = [
                // necessary
                "msgNo"       => $msgNo,
                "compId"      => $compId,
                "sendTime"    => $sendTime,
            ];

            $unencryptedRequest = $request;
            $unencryptedRequest["data"] = $contentData;

            // check encrypt process
            $encryptedContentData = $this->getEncryptedContent($contentData);
            if (!$encryptedContentData) {
                $result['error'] = "get encrypted content data failed";
                goto END;
            }

            $encryptedRequest = $request;
            $encryptedRequest["data"] = $encryptedContentData;

            // check checksum process
            $checksum = $this->getKgibApiDataCheckSum($encryptedRequest, $this->kgibankRequestUrl);
            if (!$checksum) {
                $result['error'] = "get checksum failed";
                goto END;
            }

            $headers = [
                'Content-Type: application/json; charset=UTF-8',
                'KeyId: ' . KGIBANK_LOAN_KEYID,
                'checkSum: ' . $checksum
            ];

            $kgibankRequestUrl = $this->kgibankRequestUrl;
            $postJsonData = json_encode($encryptedRequest);
            $sendResult = curl_get($kgibankRequestUrl, $postJsonData, $headers);
            $responseResult = json_decode($sendResult, true);

            // log request
            $requestContent = [
                'unencrypted'   => $unencryptedRequest,
                'encrypted'     => $encryptedRequest,
                'output_header' => $headers
            ];

            $responseContent = json_decode($sendResult);

            if ($sendResult) {
                if (isset($responseResult["ReturnCode"])) {
                    if ($responseResult["ReturnCode"] == "0000") {
                        $caseNo = $responseResult["CaseNo"];
                        $result['case_no'] = $caseNo;
                        $result['success'] = true;
                    } else {
                        $result['error'] = sprintf("ReturnCode '%s' != '0000'", $responseResult["ReturnCode"]);
                    }
                } else {
                    $result['error'] = sprintf("response 'ReturnCode' not exist");
                }
            } else {
                $result['error'] = sprintf("endpoint '%s' no response", $kgibankRequestUrl);
            }
        } catch (Exception $e) {
            $result['error'] = 'server internal error, please contact administrator';
        }
END:
        $result['msg_no'] = $msgNo;
        $result['case_no'] = $caseNo;
        $result['request_content'] = $requestContent;
        $result['response_content'] = $responseContent;

        return $result;
    }

    private function sendImageList($inputArr)
    {
        $result = [
            'success' => false,
            'error' => '',
            'total_result_list' => [],
            'fail_result_list' => []
        ];
        if (isset($inputArr["request_image_list"])) {
            if (is_array($inputArr["request_image_list"])) {
                $imageListInputArr = $inputArr["request_image_list"];
                $totalImageCount = count($imageListInputArr);
                $sendImageSuccessCount = 0;

                $totalResultList = [];
                $failInfoList = [];
                foreach ($imageListInputArr as $key => $value) {
                    $sendImageResult = $this->sendImage($value);
                    $totalResultList[] = $sendImageResult;

                    if ($sendImageResult["success"]) {
                        $sendImageSuccessCount++;
                    } else {
                        $failInfoList[] = [
                            "error" => $sendImageResult["error"],
                            "origin_request" => $value
                        ];
                    }
                }

                $result["total_result_list"] = $totalResultList;
                $result["fail_result_list"] = $failInfoList;

                // check image list size > 0 and total send success
                if ($totalImageCount > 0 && $sendImageSuccessCount == $totalImageCount) {
                    $result["success"] = true;
                } else {
                    $result["error"] = sprintf("send seccess = %s, total = %s", $sendImageSuccessCount, $totalImageCount);
                }
            } else {
                $result["error"] = "parameter 'request_image_list' value is illegal";
                goto END;
            }
        } else {
            $result["error"] = "parameter 'request_image_list' is not found";
            goto END;
        }

END:
        return $result;
    }

    private function sendImage($inputArr)
    {
        $result = [
            'success' => false,
            'error' => '',
            'msg_no' => '',
            'case_no' => '',
            'request_content' => [],
            'response_content' => []
        ];

        $msgNo = '';
        $caseNo = '';
        $requestContent = ['raw_input' => json_encode($inputArr)];
        $responseContent = [];

        try {
            // check input parameter
            $checkInputParamsArr = [
                // necessary items
                [$inputArr, 'MsgNo'       , true, '/^[\d]{15}$/'],
                [$inputArr, 'CompId'      , true, '/^[\d]{8,11}$/'],
                [$inputArr, 'CaseNo'      , true, '/^[\d]{15}$/'],
                [$inputArr, 'DocType'     , true, '/^[A-Z][0-9][0-9]$/'],
                [$inputArr, 'DocSeq'      , true, '/^[\d]{1,2}$/'],
                [$inputArr, 'DocUrl'      , true, '/^https:\/\/.*$/'],
                [$inputArr, 'DocFileType' , true, '/^[1-7]$/']
            ];
            foreach ($checkInputParamsArr as $key => $value) {
                $checkResult = $this->checkDataFormat($value[0], $value[1], $value[2], $value[3]);
                if (!$checkResult['result']) {
                    $result['error'] = $checkResult['errorMsg'];
                    goto END;
                }
            }

            $msgNo = $inputArr["MsgNo"];
            $compId = $inputArr["CompId"];
            $caseNo = $inputArr["CaseNo"];
            $docType = $inputArr["DocType"];
            $docSeq = $inputArr["DocSeq"];
            $docUrl = $inputArr["DocUrl"];
            $docFileType = $inputArr["DocFileType"];
            $sendTime = $this->getCurrentSendTime();

            try {
                // get image data by url
                $fileData = file_get_contents($docUrl);

                if (!$fileData) {
                    $result['error'] = sprintf("DocUrl '%s' get image fail", $docUrl);
                    goto END;
                }

                // mega bytes
                $fileSize = strlen($fileData) / 1024 / 1024;

                // convert image to base64
                $docImgBase64 = base64_encode($fileData);

                // check image size
                if ($fileSize > $this->singleImageMaxSize) {
                    // try to compress image
                    // 2 = jpg, 3=jpeg
                    // TODO: wait to add other image type
                    if ($docFileType == 2 || $docFileType == 3) {
                        $fileExtension = "";
                        switch ($docFileType) {
                            case 2:
                                $fileExtension = "jpg";
                                break;
                            case 3:
                                $fileExtension = "jpeg";
                                break;
                            case 4:
                                $fileExtension = "png";
                                break;
                            case 6:
                                $fileExtension = "heic";
                                break;
                            case 7:
                                $fileExtension = "heif";
                                break;
                            default:
                                break;
                        }
                        $fileBase64 = $docImgBase64;
                        $compressedImageBase64 = $this->getCompressedImage($fileBase64, $fileExtension, $this->singleImageMaxSize * 1024);

                        if ($compressedImageBase64) {
                            // image compress success
                            $docImgBase64 = $compressedImageBase64;
                        } else {
                            // image compress fail
                            $result['error'] = sprintf("DocUrl '%s' size  %s bytes > max size %s bytes, image compress fail", $docUrl, $fileSize, $this->singleImageMaxSize);
                            goto END;
                        }

                    } else {
                        $result['error'] = sprintf("DocUrl '%s' size  %s bytes > max size %s bytes, DocFileType=%s can not be compressed", $docUrl, $fileSize, $this->singleImageMaxSize, $docFileType);
                        goto END;
                    }

                }
            } catch (Exception $e) {
                $result['error'] = sprintf("DocUrl '%s' convert to base64 fail", $docUrl);
                goto END;
            }

            $outputRequest = [
                // necessary
                "msgNo"       => $msgNo,
                "compId"      => $compId,
                "caseNo"      => $caseNo,
                "docType"     => $docType,
                "docFileType" => $docFileType,
                "docSeq"      => $docSeq,
                "sendTime"    => $sendTime,
                "docCont"     => $docImgBase64
            ];

            // check checksum process
            $checksum = $this->getKgibApiDataCheckSum($outputRequest, $this->kgibankImageUrl);
            if (!$checksum) {
                $result['error'] = "get checksum failed";
                goto END;
            }

            $headers = [
                'Content-Type: application/json; charset=UTF-8',
                'KeyId: ' . KGIBANK_LOAN_KEYID,
                'checkSum: ' . $checksum
            ];

            $kgibankImageUrl = $this->kgibankImageUrl;
            $postJsonData = json_encode($outputRequest);
            $sendResult = curl_get($kgibankImageUrl, $postJsonData, $headers);
            $responseResult = json_decode($sendResult, true);

            // log request
            $requestContent = [
                'request_type'  => 'send_image',
                'output_body'     => [
                    "msgNo"       => $msgNo,
                    "compId"      => $compId,
                    "caseNo"      => $caseNo,
                    "docType"     => $docType,
                    "docFileType" => $docFileType,
                    "docSeq"      => $docSeq,
                    "sendTime"    => $sendTime,
                    "docUrl"      => $docUrl
                ],
                'output_header' => $headers
            ];

            $responseContent = json_decode($sendResult);

            if ($sendResult) {
                if (isset($responseResult["ReturnCode"])) {
                    if ($responseResult["ReturnCode"] == "0000") {
                        $result['success'] = true;
                    } else {
                        $result['error'] = sprintf("ReturnCode '%s' != '0000'", $responseResult["ReturnCode"]);
                    }
                } else {
                    $result['error'] = sprintf("response 'ReturnCode' not exist");
                }
            } else {
                $result['error'] = sprintf("endpoint '%s' no response", $kgibankImageUrl);
            }
        } catch (Exception $e) {
            $result['error'] = 'server internal error, please contact administrator';
        }
END:
        $result['msg_no'] = $msgNo;
        $result['case_no'] = $caseNo;
        $result['request_content'] = $requestContent;
        $result['response_content'] = $responseContent;

        return $result;
    }

    private function sendImageComplete($inputArr)
    {
        $result = [
            'success' => false,
            'error' => '',
            'msg_no' => '',
            'case_no' => '',
            'request_content' => [],
            'response_content' => []
        ];

        $msgNo = '';
        $caseNo = '';
        $requestContent = ['raw_input' => json_encode($inputArr)];
        $responseContent = [];

        try {
            // check input parameter
            $checkInputParamsArr = [
                // necessary items
                [$inputArr, 'MsgNo'    , true, '/^[\d]{15}$/'],
                [$inputArr, 'CompId'   , true, '/^[\d]{8,11}$/'],
                [$inputArr, 'CaseNo'   , true, '/^[\d]{15}$/']
            ];
            foreach ($checkInputParamsArr as $key => $value) {
                $checkResult = $this->checkDataFormat($value[0], $value[1], $value[2], $value[3]);
                if (!$checkResult['result']) {
                    $result['error'] = $checkResult['errorMsg'];
                    goto END;
                }
            }

            $msgNo = $inputArr["MsgNo"];
            $compId = $inputArr["CompId"];
            $caseNo = $inputArr["CaseNo"];
            $sendTime = $this->getCurrentSendTime();

            $outputRequest = [
                // necessary
                "msgNo"    => $msgNo,
                "compId"   => $compId,
                "caseNo"   => $caseNo,
                "sendTime" => $sendTime
            ];

            // check checksum process
            $checksum = $this->getKgibApiDataCheckSum($outputRequest, $this->kgibankImageCompleteUrl);
            if (!$checksum) {
                $result['error'] = "get checksum failed";
                goto END;
            }

            $headers = [
                'Content-Type: application/json; charset=UTF-8',
                'KeyId: ' . KGIBANK_LOAN_KEYID,
                'checkSum: ' . $checksum
            ];

            $kgibankImageCompleteUrl = $this->kgibankImageCompleteUrl;
            $postJsonData = json_encode($outputRequest);
            $sendResult = curl_get($kgibankImageCompleteUrl, $postJsonData, $headers);
            $responseResult = json_decode($sendResult, true);

            // log request
            $requestContent = [
                'request_type'  => 'send_image_complete',
                'output_body'   => $outputRequest,
                'output_header' => $headers
            ];
            $responseContent = json_decode($sendResult);;

            if ($sendResult) {
                if (isset($responseResult["ReturnCode"])) {
                    if ($responseResult["ReturnCode"] == "0000") {
                        $result['success'] = true;
                    } else {
                        $result['error'] = sprintf("ReturnCode '%s' != '0000'", $responseResult["ReturnCode"]);
                    }
                } else {
                    $result['error'] = sprintf("response 'ReturnCode' not exist");
                }
            } else {
                $result['error'] = sprintf("endpoint '%s' no response", $kgibankImageCompleteUrl);
            }
        } catch (Exception $e) {
            $result['error'] = 'server internal error, please contact administrator';
        }
END:
        $result['msg_no'] = $msgNo;
        $result['case_no'] = $caseNo;
        $result['request_content'] = $requestContent;
        $result['response_content'] = $responseContent;

        return $result;
    }

    private function writeSendTextLog($msgNo, $caseNo, $sendResult, $errMsg, $requestContent, $responseContent)
    {
        $this->load->model('skbank/LoanSendRequestLog_model');
        $logId = $this->LoanSendRequestLog_model->insert([
            'msg_no'           => $msgNo,
            'case_no'          => $caseNo,
            'send_success'     => $sendResult,
            'error_msg'        => $errMsg,
            'request_content'  => json_encode($requestContent, JSON_UNESCAPED_UNICODE),
            'response_content' => json_encode($responseContent, JSON_UNESCAPED_UNICODE)
        ]);
        return $logId;
    }

    private function writeSendImageLog($msgNo, $caseNo, $sendResult, $errMsg, $requestContent, $responseContent)
    {
        $this->load->model('skbank/LoanSendRequestImageLog_model');
        $logId = $this->LoanSendRequestImageLog_model->insert([
            'msg_no'           => $msgNo,
            'case_no'          => $caseNo,
            'send_success'     => $sendResult,
            'error_msg'        => $errMsg,
            'request_content'  => json_encode($requestContent, JSON_UNESCAPED_UNICODE),
            'response_content' => json_encode($responseContent, JSON_UNESCAPED_UNICODE)
        ]);
        return $logId;
    }

    private function writeSendImageCompleteLog($msgNo, $caseNo, $sendResult, $errMsg, $requestContent, $responseContent)
    {
        return $this->writeSendImageLog($msgNo, $caseNo, $sendResult, $errMsg, $requestContent, $responseContent);
    }

    private function getKgibApiDataCheckSum($content=[], $skbApiSource='')
    {
        $kgibankChecsumkUrl = $this->vestaKgibankCryptoUrl;
        $postData = json_encode([
            "action" => "checksum",
            "type" => "KGIBANK-DATA-CHECKSUM",
            "data" => [
                "message" => json_encode($content),
                "kgib_api_source" => $skbApiSource
            ]
        ]);

        $result = curl_get($kgibankChecsumkUrl, $postData, ["Content-Type: application/json; charset=utf-8"]);

        $response = json_decode($result);
        if (isset($response->status) && $response->status == 200) {
            return $response->response->checksum;
        }

        return '';
    }

    private function getEncryptedContent($content=[])
    {
        $kgibankEncryptUrl = $this->vestaKgibankCryptoUrl;
        $postData = json_encode([
            "action" =>  "encrypt",
            "type" => "AES256-EBC-PKCS7",
            "data" => [
                "message" => json_encode($content, JSON_UNESCAPED_UNICODE)
            ]
        ]);

        $result = curl_get($kgibankEncryptUrl, $postData, ["Content-Type: application/json; charset=utf-8"]);

        $response = json_decode($result);
        if (isset($response->status) && $response->status == 200) {
            return $response->response->encrypted_data;
        }

        return '';
    }

    /**
     * [getCompressedImage description]
     * @param  [string] $fileBase64             [base64]
     * @param  [string] $fileExtension          [image type, ex: jpg, jpeg]
     * @param  [int] $compressedMaxKilobytes    [file size, ex: 1024KB]
     * @return [string]                         [compressed base64]
     */
    private function getCompressedImage($fileBase64, $fileExtension, $compressedMaxKilobytes)
    {
        $ocrCompressImageUrl = $this->ocrCompressImageUrl;
        $postData = [
            "file_base64" => $fileBase64,
            "file_extension" => $fileExtension,
            "compressed_max_kilobytes" => $compressedMaxKilobytes
        ];

        $result = curl_get($ocrCompressImageUrl, $postData, ["Content-Type: multipart/form-data; charset=utf-8"]);
        $response = json_decode($result);
        if (isset($response->status) && $response->status == 200 && $response->response->compressed_success) {
            return $response->response->compressed_base64;
        }

        return '';
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

    private function isJson($text)
    {
        json_decode($text);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function getCurrentSendTime()
    {
        date_default_timezone_set("Asia/Taipei");
        return date("YmdHis", time());
    }
}
