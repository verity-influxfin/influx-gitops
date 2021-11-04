<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Sns extends REST_Controller {

	
    public function __construct()
    {
		parent::__construct();
		$this->load->model('user/user_model');
		$this->load->model('user/user_certification_model');
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


	public function credit_post()
	{
		//第一次SNS認證 ＋＋
		// $rawData = file_get_contents('php://input');
		// $_POST = json_decode($rawData);
		// $ch = curl_init($_POST->SubscribeURL) ;
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		// curl_exec($ch) ;
		// curl_close($ch);
		//第一次SNS認證 --
		$list = $this->s3_lib->get_mailbox_list();
        $process_unknown_mail = function ($s3_url, $bucket) {
            $this->s3_lib->unknown_mail($s3_url, $bucket);
            $this->s3_lib->public_delete_s3object($s3_url, $bucket);
        };

		if (!empty($list)) {
			foreach ($list as $s3_url) {
				$filename=$this->s3_lib->public_get_filename($s3_url,S3_BUCKET_MAILBOX);
				$file_content = file_get_contents('s3://'.S3_BUCKET_MAILBOX.'/'.$filename);
				if(!$file_content) {
					continue;
				}
                $parser = new PhpMimeMailParser\Parser();
                $parser->setText($file_content);
                $headers = $parser->getHeaders();
                $mailfrom = $headers['x-original-sender'] ?? '';
                if($mailfrom == '') {
                    $mailfrom = $parser->getAddresses('from');
                    $mailfrom = isset($mailfrom[0]) ? $mailfrom[0]['address'] : "";
                }
                $mail_title = $parser->getHeader('subject');
                $re_investigation_mail=strpos($mail_title, '聯合徵信申請');
                $re_job_mail=strpos($mail_title, '工作認證申請');
                $attachments = $parser->getAttachments();
                $certification_id=($re_job_mail===false)? 9:10;
                $cert_info = $this->user_certification_model->order_by('created_at', 'desc')->get_by(['investor' => USER_BORROWER, 'certification_id' => CERTIFICATION_EMAIL, 'status' => CERTIFICATION_STATUS_SUCCEED, "TRIM(BOTH '\"' FROM LOWER(JSON_EXTRACT(`content`, '$.email'))) = " => strtolower($mailfrom)]);

                if (!isset($cert_info) || ($re_investigation_mail === false && $re_job_mail === false)) {
                    // 沒有找到對應使用者和勞保聯徵標題關鍵字
                    $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                }else{
                    $info = $this->user_certification_model->order_by('created_at', 'desc')->limit(3)->get_many_by(['user_id' => $cert_info->user_id, 'investor' => USER_BORROWER, 'certification_id' => $certification_id]);
                    if(empty($info)) {
                        $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                        continue;
                    }

                    if (!empty($attachments)) {
                        // 非圖片或PDF格式的檔案 或 認證項目是成功/失敗狀態者 轉為不明檔案
                        $mime = get_mime_by_extension($attachments[0]->getFileName());
                        if ((is_image($mime) || is_pdf($mime))
                            && !in_array($info[0]->status, [CERTIFICATION_STATUS_SUCCEED,CERTIFICATION_STATUS_FAILED])
                        ) {
                            $this->process_mail($info, $attachments, $cert_info, $s3_url, $certification_id);
                        }else{
                            if(!in_array($info[0]->status, [1,2,4])) {
                                $remark           = $info[0]->remark!=''?json_decode($info[0]->remark,true):[];
                                $remark['fail']   = "夾帶附件為不支援的格式";
                                $this->user_certification_model->update_by(['id' => $info[0]->id], [
                                    'status' => 3,
                                    'remark'    => json_encode($remark)
                                ]);
                            }
                            $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                        }
                    } else if (($drive = strpos($file_content, 'https://drive.google.com/')) !== false ||
                        ((count($info) >= 3) && $info[0]->status == CERTIFICATION_STATUS_AUTHENTICATED)) {
                        // 沒附件且最近三次都失敗時，直接轉人工 / 用 google drive 傳檔案轉人工
                        $remark           = $info[0]->remark!=''?json_decode($info[0]->remark,true):[];
                        $remark['fail']   = $drive !== false ? "該附件由Google雲端夾帶，需人工檢驗" : "收信無附件次數達三次，請人工檢驗";
                        $this->user_certification_model->update_by(['id' => $info[0]->id], [
                            'status' => CERTIFICATION_STATUS_PENDING_TO_REVIEW,
                            'remark'    => json_encode($remark)
                        ]);
                        $this->process_mail($info, null, $cert_info, $s3_url, $certification_id);
                    } else {
                        // 沒附件直接失敗
                        $this->certification_lib->set_failed($info[0]->id, '資料缺少附件', true);
                        $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
                    }
                }
			}
		}
	}

	private function process_mail($info, $attachments, $cert_info, $s3_url,$certification_id)
	{
		$url = $this->attachment_pdf($attachments, $cert_info, $s3_url,$certification_id);
		$this->certification_lib->save_mail_url($info['0'],$url);
	}

	private function attachment_pdf($attachments,$cert_info,$s3_url,$certification_id)
	{
		$name=($certification_id===9)? 'investigation':'job';
        $url = "";
		if(isset($attachments)) {
            $url = $this->s3_lib->credit_mail_pdf($attachments, $cert_info->user_id, $name, 'user_upload/' . $cert_info->user_id);
        }
        $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);

        return $url;
	}
}
