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
				$file_content  =  file_get_contents('s3://'.S3_BUCKET_MAILBOX.'/'.$filename);
                $parser = new PhpMimeMailParser\Parser();
                $parser->setText($file_content);
                $mailfrom = $parser->getAddresses('from');
                $mailfrom = !empty($mailfrom) ? $mailfrom[0]['address'] : '';
                $mail_title = $parser->getHeader('subject');
                $re_investigation_mail=strpos($mail_title, '聯合徵信申請');
                $re_job_mail=strpos($mail_title, '工作認證申請');
                $attachments = $parser->getAttachments();

                $certification_id=($re_job_mail===false)? 9:10;
                $user_info = $this->user_model->order_by('created_at', 'desc')->get_by('email', $mailfrom);

                if (!isset($user_info) || ($re_investigation_mail === false && $re_job_mail === false)) {
                    // 沒有找到對應使用者和勞保聯徵標題關鍵字
                    $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                    return null;
                }else{
                    $info = $this->user_certification_model->order_by('created_at', 'desc')->limit(3)->get_many_by(['user_id' => $user_info->id, 'investor' => 0, 'certification_id' => $certification_id]);
                    if(empty($info)) {
                        $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                        return null;
                    }

                    if (!empty($attachments)) {
                        // 非圖片或PDF格式的檔案 或 認證項目是成功/失敗狀態者 轉為不明檔案
                        $mime = get_mime_by_extension($attachments[0]->getFileName());
                        if ((is_image($mime) || is_pdf($mime))
                            && !in_array($info[0]->status, [1,2])) {
                            $this->process_mail($info, $attachments, $user_info, $s3_url, $certification_id);
                        }else{
                            $process_unknown_mail($s3_url, S3_BUCKET_MAILBOX);
                        }
                    } else if ((count($info) >= 3) && $info[0]->status == 0) {
                        // 沒附件且最近三次都失敗時，直接轉人工
                        $this->process_mail($info, null, $user_info, $s3_url, $certification_id);
                    } else {
                        // 沒附件直接失敗
                        $this->certification_lib->set_failed($info[0]->id, '資料缺少附件', true);
                        $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
                    }
                }
			}
		}
	}

	private function process_mail($info, $attachments, $user_info, $s3_url,$certification_id)
	{
		$url = $this->attachment_pdf($attachments, $user_info, $s3_url,$certification_id);
		$this->certification_lib->save_mail_url($info['0'],$url);
	}

	private function attachment_pdf($attachments,$user_info,$s3_url,$certification_id)
	{
		$name=($certification_id===9)? 'investigation':'job';
        $url = $this->s3_lib->credit_mail_pdf($attachments, $user_info->id, $name, 'user_upload/' . $user_info->id);
        if ($url !== '') { //刪S3資料
            $this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
        }
        return $url;
	}
}
