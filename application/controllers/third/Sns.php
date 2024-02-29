<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

class Sns extends REST_Controller {

	
    public function __construct()
    {
		parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('user/user_certification_model');
        $this->load->model('log/log_mailbox_model');
		$this->load->library('S3_lib');
		$this->load->library('Certification_lib');
		$client = new Aws\S3\S3Client(array(
			'version' 	=> 'latest',
			'region'  	=> 'us-west-2',
			'credentials' => [
				'key'         => AWS_ACCESS_TOKEN,
				'secret'      => AWS_SECRET_TOKEN,
			],
		));
       // Register the stream wrapper from an S3Client object
		$client->registerStreamWrapper();
    }


//	public function credit_post()
//	{
//		//第一次SNS認證 ＋＋
//		// $rawData = file_get_contents('php://input');
//		// $_POST = json_decode($rawData);
//		// $ch = curl_init($_POST->SubscribeURL) ;
//		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		// curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
//		// curl_exec($ch) ;
//		// curl_close($ch);
//		//第一次SNS認證 --
//		$list = $this->s3_lib->get_mailbox_list();
//        $process_unknown_mail = function ($s3_url, $bucket) {
//            $this->s3_lib->unknown_mail($s3_url, $bucket);
//            $this->s3_lib->public_delete_s3object($s3_url, $bucket);
//        };
//
//		if (!empty($list)) {
//			foreach ($list as $s3_url) {
//				$filename=$this->s3_lib->public_get_filename($s3_url,S3_BUCKET_MAILBOX);
//				$file_content = file_get_contents('s3://'.S3_BUCKET_MAILBOX.'/'.$filename);
//				if(!$file_content) {
//					continue;
//				}
//                $parser = new PhpMimeMailParser\Parser();
//                $parser->setText($file_content);
//                $headers = $parser->getHeaders();
//                $mailfrom = $headers['x-original-sender'] ?? '';
//                if($mailfrom == '') {
//                    $mailfrom = $parser->getAddresses('from');
//                    $mailfrom = isset($mailfrom[0]) ? $mailfrom[0]['address'] : "";
//                }
//                $mail_title = $parser->getHeader('subject');
//                $re_investigation_mail=strpos($mail_title, '聯合徵信申請');
//                $re_job_mail=strpos($mail_title, '工作認證申請');
//                $attachments = $parser->getAttachments();
//                $certification_id=($re_job_mail===false)? 9:10;
//                $cert_info = $this->user_certification_model->order_by('created_at', 'desc')->get_by(['investor' => USER_BORROWER, 'certification_id' => CERTIFICATION_EMAIL, 'status' => CERTIFICATION_STATUS_SUCCEED, "TRIM(BOTH '\"' FROM LOWER(JSON_EXTRACT(`content`, '$.email'))) = " => strtolower($mailfrom)]);
//
//                if (!isset($cert_info) || ($re_investigation_mail === false && $re_job_mail === false)) {
//                    // 沒有找到對應使用者和勞保聯徵標題關鍵字
//                    $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
//                }else{
//                    $info = $this->user_certification_model->order_by('created_at', 'desc')->limit(3)->get_many_by(['user_id' => $cert_info->user_id, 'investor' => USER_BORROWER, 'certification_id' => $certification_id]);
//                    if(empty($info)) {
//                        $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
//                        continue;
//                    }
//
//                    if (!empty($attachments)) {
//                        // 非圖片或PDF格式的檔案 或 認證項目是成功/失敗狀態者 轉為不明檔案
//                        $mime = get_mime_by_extension($attachments[0]->getFileName());
//                        if ((is_image($mime) || is_pdf($mime))
//                            && !in_array($info[0]->status, [CERTIFICATION_STATUS_SUCCEED,CERTIFICATION_STATUS_FAILED])
//                        ) {
//                            $this->process_mail($info, $attachments, $cert_info, $s3_url, $certification_id);
//                        }else{
//                            if(!in_array($info[0]->status, [1,2,4])) {
//                                $remark           = $info[0]->remark!=''?json_decode($info[0]->remark,true):[];
//                                $remark['fail']   = "夾帶附件為不支援的格式";
//                                $this->user_certification_model->update_by(['id' => $info[0]->id], [
//                                    'status' => 3,
//                                    'remark'    => json_encode($remark)
//                                ]);
//                            }
//                            $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
//                        }
//                    } else if (($drive = strpos($file_content, 'https://drive.google.com/')) !== false ||
//                        ((count($info) >= 3) && $info[0]->status == CERTIFICATION_STATUS_PENDING_TO_VALIDATE)) {
//                        // 沒附件且最近三次都失敗時，直接轉人工 / 用 google drive 傳檔案轉人工
//                        $remark           = $info[0]->remark!=''?json_decode($info[0]->remark,true):[];
//                        $remark['fail']   = $drive !== false ? "該附件由Google雲端夾帶，需人工檢驗" : "收信無附件次數達三次，請人工檢驗";
//                        $this->user_certification_model->update_by(['id' => $info[0]->id], [
//                            'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
//                            'remark'    => json_encode($remark)
//                        ]);
//                        $this->process_mail($info, null, $cert_info, $s3_url, $certification_id);
//                    } else {
//                        // 針對沒有附件的徵信項才退
//                        $content = json_decode($info[0]->content, TRUE);
//                        if ( ! $content['mail_file_status'])
//                        {
//                            // 沒附件直接失敗
//                            $this->certification_lib->set_failed($info[0]->id, '資料缺少附件', TRUE);
//                            $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
//                        }
//                    }
//                }
//			}
//		}
//	}

    /**
     * 測試用，取得信件列表
     * @return void
     */
    public function credit_test_check_total_mail_post()
    {
        $input = $this->input->post(null, true);
        if (isset($input['day']) && $input['day'] > 0) {
            $list = $this->s3_lib->get_mailbox_day_before_today_list($input['day']);
        } else {
            $list = $this->s3_lib->get_mailbox_today_list();
        }

        $this->response(['status' => 'success', 'data' => $list], REST_Controller::HTTP_OK);
    }

    /**
     * 徵信項、工作收入證明收信處理，處理完後刪除檔案
     * @return void
     */
    public function credit_post(): void
    {
        //        $list = $this->s3_lib->get_mailbox_list();

        $input = $this->input->post(null, true);
        if(isset($input['day']) && $input['day'] > 0){
            $list = $this->s3_lib->get_mailbox_day_before_today_list($input['day']);
        }else{
            $list = $this->s3_lib->get_mailbox_today_list();
        }

        if (empty($list)) {
            $this->response(['status' => 'success', 'data' => $list, 'message' => 'no item need to process'], REST_Controller::HTTP_OK);
        }

        foreach ($list as $s3_url) {
            try {
                $detail = [];
                $filename = $this->s3_lib->public_get_filename($s3_url, S3_BUCKET_MAILBOX);
                $detail['filename'] = $filename;
                $file_content = file_get_contents('s3://' . S3_BUCKET_MAILBOX . '/' . $filename);
                if (!$file_content) {
                    $detail['get_file_content'] = false;

                    $detail['remark'] = "沒有 file_content，不處理";
                    $detail['actions'] = ['None'];
                    $this->record_mailbox_log($detail);
                    continue;
                }
                $detail['get_file_content'] = true;

                $parser = new PhpMimeMailParser\Parser();
                $parser->setText($file_content);
                $headers = $parser->getHeaders();
                $mail_from = $headers['x-original-sender'] ?? '';
                if ($mail_from == '') {
                    $mail_from = $parser->getAddresses('from');
                    $mail_from = isset($mail_from[0]) ? $mail_from[0]['address'] : "";
                }
                $detail['mail_from'] = $mail_from;

                $mail_title = $parser->getHeader('subject');
                $detail['mail_title'] = $mail_title;

                $re_investigation_mail = strpos($mail_title, '聯合徵信申請');
                $detail['re_investigation_mail'] = $re_investigation_mail;

                $re_job_mail = strpos($mail_title, '工作認證申請');
                $detail['re_job_mail'] = $re_job_mail;

                $attachments = $parser->getAttachments();
                $certification_id = ($re_job_mail === false) ? 9 : 10;
                $detail['certification_id'] = $certification_id;
                $where = [
                    'investor' => USER_BORROWER,
                    'certification_id' => CERTIFICATION_EMAIL,
                    'status' => CERTIFICATION_STATUS_SUCCEED,
                    "TRIM(BOTH '\"' FROM LOWER(JSON_EXTRACT(`content`, '$.email'))) = " => strtolower($mail_from)
                ];

                $text = $parser->getMessageBody('text');
                // 如果回信內容有改記得回來改這邊
                $pattern = ($re_job_mail === false) ? '/親愛的(\d+)/' : '/使用者編號 (\d+)/';
                if (preg_match($pattern, $text, $matches)) {

                    $userId = $matches[1];
                    $where['user_id'] = $userId;
                }
                $cert_info = $this->user_certification_model->order_by('created_at', 'desc')
                    ->get_by($where);
                if (!isset($cert_info) || ($re_investigation_mail === false && $re_job_mail === false)) {
                    // 沒有找到對應使用者和勞保聯徵標題關鍵字
                    $detail['remark'] = "沒有找到對應使用者和勞保聯徵標題關鍵字，轉為不明檔案";

                    $actions = $this->process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                    $detail['actions'] = $actions;
                    $this->record_mailbox_log($detail);
                    continue;
                }

                $info = $this->user_certification_model->order_by('created_at', 'desc')->limit(3)->get_many_by(['user_id' => $cert_info->user_id, 'investor' => USER_BORROWER, 'certification_id' => $certification_id]);
                if (empty($info)) {
                    $detail['remark'] = "no user_certification，轉為不明檔案";

                    $actions = $this->process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                    $detail['actions'] = $actions;
                    $this->record_mailbox_log($detail);
                    continue;
                }

                if (!empty($attachments)) {
                    // 非圖片或PDF格式的檔案 或 認證項目是成功/失敗狀態者 轉為不明檔案
                    $detail['remark'] = "";

                    $mime = get_mime_by_extension($attachments[0]->getFileName());
                    if ((is_image($mime) || is_pdf($mime))
                        && !in_array($info[0]->status, [CERTIFICATION_STATUS_SUCCEED, CERTIFICATION_STATUS_FAILED])
                    ) {
                        $detail['remark'] = "有附件，夾帶附件為支援的格式，且認證項目不是驗證成功/驗證失敗，處理檔案";

                        $remark = $info[0]->remark != '' ? json_decode($info[0]->remark, true) : [];
                        $remark['process_mail'] = 1;
                        $this->user_certification_model->update_by(['id' => $info[0]->id], [
                            'remark' => json_encode($remark)
                        ]);
                        $result = $this->process_mail($info, $attachments, $cert_info, $s3_url, $certification_id);
                        $actions = $result ? ['process_mail'] : ['process_mail failed'];
                        $detail['actions'] = $actions;
                        $this->record_mailbox_log($detail);
                        continue;
                    }

                    if (!in_array($info[0]->status, [1, 2, 4])) {
                        $detail['remark'] = "有附件, 認證項目不是驗證成功/驗證失敗/未上傳文件，轉為不明檔案";

                        $remark = $info[0]->remark != '' ? json_decode($info[0]->remark, true) : [];
                        $remark['fail'] = "夾帶附件為不支援的格式";
                        $this->user_certification_model->update_by(['id' => $info[0]->id], [
                            'status' => 3,
                            'remark' => json_encode($remark)
                        ]);
                    }
                    if ($detail['remark'] == "") {
                        $detail['remark'] = "有附件, 認證項目是驗證成功/驗證失敗/未上傳文件，轉為不明檔案";
                    }

                    $actions = $this->process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                    $detail['actions'] = $actions;
                    $this->record_mailbox_log($detail);
                    continue;
                }

                if (
                    ($drive = strpos($file_content, 'https://drive.google.com/')) !== false
                    ||
                    ((count($info) >= 3) && $info[0]->status == CERTIFICATION_STATUS_PENDING_TO_VALIDATE)
                ) {
                    if ($info[0]->status == CERTIFICATION_STATUS_PENDING_TO_VALIDATE) {
                        $remark = $info[0]->remark != '' ? json_decode($info[0]->remark, true) : [];
                        if (isset($remark['process_mail']) && $remark['process_mail'] == 1) {
                            $detail['remark'] = "不需要處理，已經處理過了";
                            $detail['actions'] = ['already process_mail'];

                            $remark['fail'] = '';
                        } else {
                            // 沒附件且最近三次都失敗時，直接轉人工 / 用 google drive 傳檔案轉人工
                            $detail['remark'] = "沒附件且最近三次都失敗時，直接轉人工 / 用 google drive 傳檔案轉人工";

                            $remark['fail'] = $drive !== false ? "該附件由Google雲端夾帶，需人工檢驗" : "收信無附件次數達三次，請人工檢驗";
                            $this->user_certification_model->update_by(['id' => $info[0]->id], [
                                'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                                'remark' => json_encode($remark)
                            ]);
                        }

                        $result = $this->process_mail($info, null, $cert_info, $s3_url, $certification_id);
                        if (!isset($detail['actions'])) {
                            $detail['actions'] = [];
                        }
                        $detail['actions'][] = $result ?
                            'process_mail with no attachments' :
                            'process_mail failed with no attachments';

                        $this->record_mailbox_log($detail);
                    }
                    continue;
                }

                // 針對沒有附件的徵信項才退
                $content = json_decode($info[0]->content, TRUE);
                if (!$content['mail_file_status']) {
                    $detail['remark'] = "沒附件直接失敗，直接刪除檔案";

                    // 沒附件直接失敗
                    $this->certification_lib->set_failed($info[0]->id, '資料缺少附件', TRUE);
                    $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);

                    $detail['actions'] = ['public_delete_s3object'];
                    $this->record_mailbox_log($detail);
                    continue;
                }

                $detail['remark'] = "郵件沒處理";
                $detail['actions'] = ['None'];
                $this->record_mailbox_log($detail);
            } catch (Exception $e) {
                $detail['remark'] = json_encode($e->getMessage(), JSON_UNESCAPED_UNICODE);
                $detail['actions'] = ['Exception'];
                $this->record_mailbox_log($detail);
            }
        }
        $this->response(['status' => 'success', 'data' => $list, 'message' => 'end process'], REST_Controller::HTTP_OK);
    }

    /**
     * 處理未知的信件，轉為不明檔案，並刪除原始檔案
     * @param string $s3_url
     * @param string $bucket
     * @return array
     */
    private function process_unknown_mail(string $s3_url, string $bucket): array
    {
        try {
            $actions[] = 'unknown_mail';
            $rs = $this->s3_lib->unknown_mail($s3_url, $bucket);
            if (!$rs){
                throw new Exception('unknown_mail failed');
            }

            $actions[] = 'public_delete_s3object';
            $rs = $this->s3_lib->public_delete_s3object($s3_url, $bucket);
            if (!$rs){
                throw new Exception('public_delete_s3object failed');
            }
        } catch (Exception $e) {
            $actions = [$e->getMessage()];
        }
        return $actions;
    }

    /**
     * 紀錄處理信件的log
     * @param $log_data
     * @return void
     */
    private function record_mailbox_log($log_data)
    {
        $log_data['actions'] = json_encode($log_data['actions'], JSON_UNESCAPED_UNICODE);
        $this->log_mailbox_model->insert([
            "filename" => $log_data['filename'],
            "mail_from" => $log_data['mail_from'],
            "mail_title" => $log_data['mail_title'],
            "remark" => $log_data['remark'],
            "actions" => $log_data['actions'],
        ]);
        echo json_encode($log_data,JSON_UNESCAPED_UNICODE) . "\n";
    }

    /**
     * @param $info
     * @param $attachments
     * @param $cert_info
     * @param $s3_url
     * @param $certification_id
     * @return bool
     * @throws Exception
     */
    private function process_mail($info, $attachments, $cert_info, $s3_url, $certification_id): bool
    {
        if (isset($attachments)) {
            $name = ($certification_id === 9) ? 'investigation' : 'job';
            $pdf_rs = $this->s3_lib->credit_mail_pdf($attachments, $cert_info->user_id, $name, 'user_upload/' . $cert_info->user_id);
            if (empty($pdf_rs) || (isset($pdf_rs['url']) && $pdf_rs['url'] == '')) {
                $message = !isset($pdf_rs['url']) || $pdf_rs['url'] == '' ? 'failed no url' : 'failed';
                log_message('error', json_encode(['function_name' => 'credit_mail_pdf', 'message' => $message]));
                return false;
            }
            $save_rs = $this->certification_lib->save_mail_url($info['0'], $pdf_rs['url'], $pdf_rs['is_valid_pdf']);
            if (!$save_rs) {
                log_message('error', json_encode(['function_name' => 'save_mail_url', 'message' => 'failed']));
                return false;
            }
        }
        $failed_rs = $this->s3_lib->failed_mail($s3_url, S3_BUCKET_MAILBOX);
        if (!$failed_rs) {
            log_message('error', json_encode(['function_name' => 'failed_mail', 'message' => 'failed']));
            throw new Exception('failed_mail failed');
        }
        $delete_rs = $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
        if (!$delete_rs) {
            log_message('error', json_encode(['function_name' => 'public_delete_s3object', 'message' => 'failed']));
            throw new Exception('public_delete_s3object failed');
        }
        return true;
    }

    private function attachment_pdf($attachments, $cert_info, $s3_url, $certification_id): array
    {
        $name = ($certification_id === 9) ? 'investigation' : 'job';
        $rs = [];
        if (isset($attachments))
        {
            $rs = $this->s3_lib->credit_mail_pdf($attachments, $cert_info->user_id, $name, 'user_upload/' . $cert_info->user_id);
        }
        $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);

        return $rs;
    }
}
