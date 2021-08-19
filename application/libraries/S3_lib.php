<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\S3\StreamWrapper;
class S3_lib {

	public $error = '';
	public $image_type = array(
		'image/jpeg' =>	'.jpg' ,
		'image/png'	 => '.png' ,
		'image/gif'	 => '.gif' ,
	);
	
	private $client;
	
    public function __construct()
	{   
        $this->CI 		= &get_instance();
		$this->CI->load->model('log/log_image_model');
		$this->client 	= S3Client::factory(
			array(
				'version' 	=> 'latest',
				'region'  	=> 'ap-northeast-1',
				'credentials' => [
					'key'         => AWS_ACCESS_TOKEN,
					'secret'      => AWS_SECRET_TOKEN,
				],
			)
		);
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

		$this->client_us2 	= S3Client::factory(
			array(
				'version' 	=> 'latest',
				'region'  	=> 'us-west-2',
				'credentials' => [
					'key'         => AWS_ACCESS_TOKEN,
					'secret'      => AWS_SECRET_TOKEN,
				],
			)
		);
	}
		
    public function public_delete_image($s3_url,$bucket=AZURE_S3_BUCKET,$imageInfo=[])
    {
        $filename = 'azure_image';
        if ($imageInfo) {
            $filename = $imageInfo["name"];
        }

        $key = str_replace('https://' . $bucket . '.s3.ap-northeast-1.amazonaws.com/', '', $s3_url);
        $result= $this->client->deleteObject([
            'Bucket' => $bucket,
            'Key' => $key,
        ]);

        if(isset($result['@metadata']['effectiveUri'])){
            $data = [
                'type' => 'delete_image',
                'user_id' => 0,
                'file_name'	=> $filename,
                'url' => $result['@metadata']['effectiveUri'],
                'exif' => 'public',
            ];

            $this->CI->log_image_model->insert($data);
            return true;
        } else {
            return false;
        }
    }

	public function get_mailbox_list()
	{   
		$data_list = array();
		$url_list = array();
		$bucket = S3_BUCKET_MAILBOX;
		try {
			$list = $this->client_us2->listObjects(array('Bucket' => $bucket));
		} catch (S3Exception $e) {
			echo '洽工程師 檢查連線問題';
			exit();
		}
		if (!empty($list['Contents'])) {
			foreach ($list['Contents'] as $object) {
				$overlook_file =	strpos($object['Key'], 'unknown/');
				if (($object['Key'] !== 'AMAZON_SES_SETUP_NOTIFICATION') && ($overlook_file === false)) {
					$url_list[] = $this->client_us2->getObjectUrl($bucket, $object['Key']);
				}
			};
			return $url_list;
		} else {
			return null;
		}
	}

	public function public_delete_s3object($s3_url,$bucket=AZURE_S3_BUCKET)
	{  
		$key=str_replace('https://'.$bucket.'.s3.us-west-2.amazonaws.com/','',$s3_url);
		$result= $this->client_us2->deleteObject(array(
			'Bucket' 		=> $bucket,
			'Key'    		=> $key
		));
		
	}

	public function public_get_filename($s3_url,$bucket=S3_BUCKET_MAILBOX)
	{  
		$key=str_replace('https://'.$bucket.'.s3.us-west-2.amazonaws.com/','',$s3_url);
		return $key;
		
	}

	public function unknown_mail($s3_url,$bucket=S3_BUCKET_MAILBOX)
	{  
		$key=str_replace('https://'.$bucket.'.s3.us-west-2.amazonaws.com/','',$s3_url);
		$result= $this->client_us2->putObject(array(
			'Bucket' 		=> $bucket,
			'Key'    		=> 'unknown/'.$key
		));
		
	}
	public function credit_mail_pdf($attachment, $user_id = 0, $name = 'credit', $type = 'test')
	{
		$filename = '';
		try {
			if(!$attachment)
				return '';
			$dir = 'pdf/';
			$filename = $attachment->save($dir, TRUE, PhpMimeMailParser\Parser::ATTACHMENT_RANDOM_FILENAME);
			$path_parts = pathinfo($filename);
			$content = file_get_contents($filename);

			$result = $this->client->putObject(array(
				'Bucket' 		=> S3_BUCKET,
				'Key'    		=> $type . '/' . $name . $user_id . round(microtime(true) * 1000) . rand(1, 99) . "." . $path_parts['extension'],
				'Body'   		=> $content
			));
		} catch (S3Exception $e) {
			error_log('Connecting to S3 was failed. Error in '.$e->getFile()." at line ".$e->getLine());
		} finally {
			if ($filename != '')
				unlink($filename);
		}

		if (isset($result['ObjectURL'])) {
			return $result['ObjectURL'];
		} else {
			return '';
		}
	}



}

?>

