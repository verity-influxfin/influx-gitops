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

    public function image ($files,$name="image",$user_id="",$type="test")
    {
		if (isset($files[$name]) && $files[$name]) {
			
			if(isset($this->image_type[$files[$name]['type']])){
				$exif = @exif_read_data($files[$name]['tmp_name'],0, true);
				$exif = json_decode(json_encode($exif),true);
				$fileType = $this->image_type[$files[$name]['type']];
				if($fileType == ".jpg"){
					ini_set('gd.jpeg_ignore_warning', true);
					$src = imagecreatefromjpeg($files[$name]['tmp_name']);
				}elseif($fileType == ".gif"){
					$src = imagecreatefromgif($files[$name]['tmp_name']);
				}elseif($fileType == ".png"){
					$src = imagecreatefrompng($files[$name]['tmp_name']);
				}

				if (isset($exif['IFD0']['Orientation'])) {
					switch ($exif['IFD0']['Orientation']) { 
					case 3:
						$src = imagerotate($src, 180, 0);
						break;
					case 6:
						$src = imagerotate($src, -90, 0);
						break;
					case 8:
						$src = imagerotate($src, 90, 0);
						break;
					}
				}
				
				$output_w = $src_w = imagesx($src);
				$output_h = $src_h = imagesy($src);
				if($src_w > $src_h && $src_w > IMAGE_MAX_WIDTH){
				  $output_w = IMAGE_MAX_WIDTH;
				  $output_h = intval($src_h / $src_w * IMAGE_MAX_WIDTH);
				}else if($src_h > $src_w && $src_h > IMAGE_MAX_WIDTH){
				  $output_h = IMAGE_MAX_WIDTH;
				  $output_w = intval($src_w / $src_h * IMAGE_MAX_WIDTH);
				}else if($src_h == $src_w && $src_h > IMAGE_MAX_WIDTH){
				  $output_h = IMAGE_MAX_WIDTH;
				  $output_w = IMAGE_MAX_WIDTH;
				}
				
				$output = imagecreatetruecolor($output_w, $output_h);
				imagecopyresampled($output, $src, 0, 0, 0, 0, $output_w, $output_h, $src_w, $src_h);
				
				ob_start();
				imagejpeg($output, NULL, 90);
				$image_data = ob_get_contents();
				ob_end_clean();
				$result = $this->client->putObject(array(
					'Bucket' 		=> S3_BUCKET,
					'Key'    		=> $type.'/'.$name.$user_id.time().rand(1,9).'.jpg',
					'Body'   		=> $image_data
				));

				if(isset($result['ObjectURL'])){
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
	
	public function image_by_data ($image_data="",$name="image.jpg",$user_id="",$type="test")
    {
		if (!empty($image_data)) {
			$result = $this->client->putObject(array(
				'Bucket' 		=> S3_BUCKET,
				'Key'    		=> $type.'/rotate/'.$name,
				'Body'   		=> $image_data
			));
			if(isset($result['ObjectURL'])){
				$data = array(
					"type"		=> $type,
					"user_id"	=> $user_id,
					"file_name"	=> $name,
					"url"		=> $result['ObjectURL'],
					"exif"		=> "rotate",
				);
				
				$this->CI->log_image_model->insert($data);
				return $result['ObjectURL'];
			}
        }
		return false;
    }
	
	public function image_list ()
    {
		$result = $this->client->listObjects(array('Bucket' => S3_BUCKET));
		foreach ($result['Contents'] as $object) {
			$url = $this->client->getObjectUrl(S3_BUCKET,$object['Key']);
			$file_content =  base64_encode(file_get_contents( $url ));
			echo "<img src='data:image/png;base64,".$file_content."' width=100>".$url . "<br>";
		}
		
	}
	
	public function pdf ($files="",$name="test.pdf",$user_id="",$type="test")
    {
		if (isset($files) && $files) {
			$result = $this->client->putObject(array(
				'Bucket' 		=> S3_BUCKET,
				'Key'    		=> $type.'/'.$name,
				'Body'   		=> $files
			));

			if(isset($result['ObjectURL'])){
				$data = array(
						"type"		=> 'estatement',
						"user_id"	=> $user_id,
						"file_name"	=> $name,
						"url"		=> $result['ObjectURL'],
						"exif"		=> "",
					);
					
				$this->CI->log_image_model->insert($data);
				return $result['ObjectURL'];
			}else{
				$this->error = 'upload error.';
			}
        }else{
			$this->error = "No file.";
		}
		
		return false;
    }
}

?>

