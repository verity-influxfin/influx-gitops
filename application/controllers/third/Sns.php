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
				$user_info = $this->user_model->order_by('created_at', 'desc')->get_by('email', $mailfrom);
				$subject=substr($file_content, (strpos($file_content, 'Subject: ')+19) ,100);
				$subject=explode( "\n" , $subject);
				$get_subject=substr($subject[0],0,-3);
				$mail_title= base64_decode($get_subject);
				$re_investigation_mail=strpos($mail_title, '聯合徵信申請');
				$re_job_mail=strpos($mail_title, '工作認證申請');
				if (empty($user_info)||(($re_investigation_mail === false)&&($re_job_mail === false))) {
					$this->s3_lib->unknown_mail($s3_url,S3_BUCKET_MAILBOX);
					$this->s3_lib->public_delete_s3object($s3_url,S3_BUCKET_MAILBOX);
					return null;
				}
				$certification_id=($re_job_mail===false)? 9:10;
				$have_pdf_file  = strpos($file_content, 'application/pdf');
				$have_attachment = strpos($file_content, 'ttachment');
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
		$this->certification_lib->save_mail_url($info['0'],$url);
	}

	private function attachment_pdf($file_content,$user_info,$s3_url,$certification_id)
	{
		$name=($certification_id===9)? 'investigation':'job';
		$get_pdf_file  = strpos($file_content, 'JVBERi0xLjQKJ');//pdf keyword
		$file_len=strlen($file_content);
		$file =substr($file_content,$get_pdf_file ,$file_len-$get_pdf_file);
		$file =explode( "\n" , $file);
		$mailrole=array_search("\r",$file);
		if($mailrole){//hotmail
			$get_file=array_slice($file,0,$mailrole);
			$fileContent=implode("\n" , $get_file);
			$url = $this->s3_lib->credit_mail_pdf(base64_decode($fileContent), $user_info->id, $name, 'user_upload/' . $user_info->id);
			if (!empty($url) && ($url !== false)) { //刪S3資料
				$this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
			}
			return $url;
		}else{ //gmail yahoo
			$fileContent=implode("\n" , $file);
			$url = $this->s3_lib->credit_mail_pdf(base64_decode($fileContent), $user_info->id, $name, 'user_upload/' . $user_info->id);
			if (!empty($url) && ($url !== false)) { //刪S3資料
				$this->s3_lib->public_delete_s3object($s3_url, S3_BUCKET_MAILBOX);
			}
			return $url;
		}
		
	}
}