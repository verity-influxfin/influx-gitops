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

		if (!empty($list)) {
			foreach ($list as $s3_url) {
				$filename=$this->s3_lib->public_get_filename($s3_url,S3_BUCKET_MAILBOX);
				$file_content  =  file_get_contents('s3://'.S3_BUCKET_MAILBOX.'/'.$filename);
				$mailfrom = substr($file_content, strpos($file_content, 'X-Original-Sender: ') + 19, strpos($file_content, 'X-Original-Authentication-Results: mx.google.com') - strpos($file_content, 'X-Original-Sender: ') - 21);
				$user_info = $this->user_model->get_by('email', $mailfrom);
				$re_investigation_mail=strpos($file_content, 'UmU6IOOAkOiqjeitieOAkeiBr+WQiOW+teS/oeeUs+iriw');
				$re_job_mail=strpos($file_content, 'UmU6IOOAkOiqjeitieOAkeW3peS9nOiqjeitieeUs+iriw==');
				if (empty($user_info)||(($re_investigation_mail === false)&&($re_job_mail === false))) {
					$this->s3_lib->unknown_mail($s3_url,S3_BUCKET_MAILBOX);
					$this->s3_lib->public_delete_s3object($s3_url,S3_BUCKET_MAILBOX);
					return null;
				}
				$certification_id=($re_job_mail===false)? 9:10;
				$have_pdf_file  = strpos($file_content, 'application/pdf');
				$have_attachment = strpos($file_content, 'X-Attachment-Id');
				$info = $this->user_certification_model->order_by('created_at', 'desc')->limit(3)->get_many_by(['user_id' => $user_info->id, 'investor' => 0, 'certification_id' =>$certification_id]);
				if (($have_pdf_file !== false) && ($have_attachment !== false)) {
					$this->process_mail($info,$file_content, $user_info,$s3_url,$certification_id);
				}else{
					if((count($info)>=3)&&$info[0]->status==0){
						$this->process_mail($info,$file_content, $user_info,$s3_url,$certification_id);					
					}else{
						$this->certification_lib->set_failed($info[0]->id,'資料缺少附件',true);
						$this->s3_lib->public_delete_s3object($s3_url,S3_BUCKET_MAILBOX);		
					}
					//每次寄信若沒附件就直接退認證 set_fail
					//若連續第三次 此次認證不退 轉人工 人工status
				}
			}
		}
	}

	private function process_mail($info, $file_content, $user_info, $s3_url,$certification_id)
	{
		$url = $this->attachment_pdf($file_content, $user_info, $s3_url,$certification_id);
		($certification_id==9)? $this->certification_lib->investigation_readable_verify($info['0'],$url):$this->certification_lib->job_verify($info['0'],$url);
	}

	private function attachment_pdf($file_content,$user_info,$s3_url,$certification_id)
	{
		$name=($certification_id===9)? 'investigation':'job';
		$content_ID = substr(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), strpos(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), '<') + 1, strpos(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), '>') - strpos(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), '<') - 1);
		$remove_attachmentID = strpos(substr(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), strpos(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), 'X-Attachment-Id')), $content_ID);
		$remove_attachmentID_size = strlen($content_ID);
		$pdf_content = base64_decode(str_replace(array("\r", "\n", "\r\n", "\n\r"), '', substr(substr(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), strpos(substr(substr($file_content, strpos($file_content, 'Content-ID')), 0, strpos(substr($file_content, strpos($file_content, 'Content-ID')), '--0000')), 'X-Attachment-Id')), $remove_attachmentID + $remove_attachmentID_size)));
		$url=$this->s3_lib->credit_mail_pdf($pdf_content, $user_info->id, $name, 'user_upload/' . $user_info->id);
		if (!empty($url) && ($url !== false)) { //刪S3資料
			$this->s3_lib->public_delete_s3object($s3_url,S3_BUCKET_MAILBOX);		
		}	
		return $url;
	}
}
