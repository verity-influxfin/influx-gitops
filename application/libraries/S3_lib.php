<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

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
	}
		
		public function public_delete_image($s3_url,$bucket=AZURE_S3_BUCKET)
		{  

			$key=str_replace('https://'.$bucket.'.s3.ap-northeast-1.amazonaws.com/','',$s3_url);
			$result= $this->client->deleteObject(array(
				'Bucket' 		=> $bucket,
				'Key'    		=> $key,
			));
			if(isset($result['@metadata']['effectiveUri'])){
			$data = array(
				'type'		=> 'delete_image',
				'user_id'	=> 0,
				'file_name'	=> 'azure_image',
				'url'		=> $result['@metadata']['effectiveUri'],
				'exif'		=> 'public',
			);
            
			$this->CI->log_image_model->insert($data);
			return true;
			}else{
				return false;	
			}
			
		}

	}

?>

