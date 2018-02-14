<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class S3_upload {

	public $error = "";
	public $image_type = array(
		'image/jpeg' =>	'.jpg' ,
		'image/png'	 => '.png' ,
		'image/gif'	 => '.gif' ,
	);
	
	private $client;
	
    public function __construct()
    {
        $this->CI 		= &get_instance();
		$this->client 	= S3Client::factory(
			array(
				'key' 		=> AWS_ACCESS_TOKEN,
				'secret'	=> AWS_SECRET_TOKEN,
			)
		);
    }

    public function image ($files,$name="image",$user_id="",$type="")
    {
		if (isset($files[$name]) && $files[$name]) {
			
			if(isset($this->image_type[$files[$name]['type']])){
				$result = $this->client->putObject(array(
					'Bucket' 		=> S3_BUCKET,
					'Key'    		=> 'dev/image/'.'img'.time().rand(1,9).$this->image_type[$files[$name]['type']],
					'SourceFile'   	=> $files[$name]['tmp_name'],
					'ACL'    		=> 'public-read'
				));

				if(isset($result['ObjectURL'])){
					$this->CI->load->model('log/log_image_model');
					$exif = exif_read_data($files[$name]['tmp_name'],0, true);
					$data = array(
						"type"		=> $type,
						"user_id"	=> $user_id,
						"file_name"	=> $files[$name]['name'],
						"url"		=> $result['ObjectURL'],
						"exif"		=> json_encode($exif),
					);
					$this->CI->log_image_model->insert($data);
					return $result['ObjectURL'];
				}else{
					$this->error = 'upload error.';
				}
			}else{
				$this->error = '只支援jpg gif png 圖檔';
			}
			
        }else{
			$this->error = "No file.";
		}
		
		return false;
    }

}
