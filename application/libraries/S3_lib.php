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
			echo '洽工程師 檢查連線問題'.$e->getMessage();
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

    private function get_mailbox_date_range_list(string $start_date = '', string $end_date = ''): array
    {
        if ($start_date === '') {
            return [];
        }

        try {
            $_start_date = (new DateTime($start_date, new DateTimeZone('Asia/Taipei')))->setTime(0, 0, 0);
            if ($end_date != '') {
                $_end_date = (new DateTime($end_date, new DateTimeZone('Asia/Taipei')))->setTime(23, 59, 59);
            }
        } catch (Exception $e) {
            return [];
        }

        $bucket = S3_BUCKET_MAILBOX;
        $continuationToken = null;

        $url_list = [];
        $filter_unknown_failed_list = [];

        try {
            do {
                $params = [
                    'Bucket' => $bucket,
                    'ContinuationToken' => $continuationToken,
                ];

                $list = $this->client_us2->listObjectsV2($params);

                $continuationToken = $list['NextContinuationToken'] ?? null;
                foreach ($list['Contents'] as $object) {
                    if (
                        // AMAZON_SES_SETUP_NOTIFICATION 不處理
                        $object['Key'] === 'AMAZON_SES_SETUP_NOTIFICATION' ||
                        // unknown 資料夾不處理
                        strpos($object['Key'], 'unknown/') !== false ||
                        // failed 資料夾不處理
                        strpos($object['Key'], 'failed/') !== false ||
                        empty($object['LastModified'])
                    ) {
                        continue;
                    }

                    $last_modified = (new DateTime($object['LastModified'], new DateTimeZone('UTC')))
                        ->setTimezone(new DateTimeZone('Asia/Taipei'));
                    if (
                        // start_date之前的信件不處理
                        $last_modified < $_start_date ||
                        // end_date之後的信件不處理
                        isset($_end_date) && ($last_modified > $_end_date)
                    ) {
                        continue;
                    }

                    $filter_unknown_failed_list[] = $object;
                }
            } while (!empty($continuationToken));

            if (empty($filter_unknown_failed_list)) {
                return [];
            }
            // 新到舊排序
            $arrayIterator = new \ArrayIterator($filter_unknown_failed_list);
            $arrayIterator->uasort(function ($a, $b) {
                $itemADate = (new DateTime($a['LastModified']));
                $itemBDate = (new DateTime($b['LastModified']));
                return $itemADate < $itemBDate;
            });
            foreach ($arrayIterator as $object) {
                $url_list[] = $this->client_us2->getObjectUrl($bucket, $object['Key']);
            }
        } catch (S3Exception $e) {
            echo '洽工程師 檢查連線問題'.$e->getMessage();
            exit();
        }
        return $url_list;
    }
    public function get_mailbox_today_list(): array
    {
        $start_date = 'today';
        try {
            return $this->get_mailbox_date_range_list($start_date);
        } catch (Exception $e) {
            return [];
        }
    }
    public function get_mailbox_day_before_today_list($day): array
    {
        try {
            $start_date = $end_date = (new DateTime('today', new DateTimeZone('Asia/Taipei')))->modify("-{$day} days")->format('Y-m-d');
            return $this->get_mailbox_date_range_list($start_date, $end_date);
        } catch (Exception $e) {
            return [];
        }
    }

	public function public_delete_s3object($s3_url,$bucket=AZURE_S3_BUCKET)
	{
		$key=str_replace('https://'.$bucket.'.s3.us-west-2.amazonaws.com/','',$s3_url);
        try {
            $result = $this->client_us2->deleteObject(array(
                'Bucket' => $bucket,
                'Key' => $key
            ));
            return true;
        }catch (Exception $e){
            error_log("public_delete_s3object: {$e->getMessage()}, ($s3_url)");
            return false;
        }
	}

	public function public_get_filename($s3_url,$bucket=S3_BUCKET_MAILBOX)
	{
        return str_replace('https://' . $bucket . '.s3.us-west-2.amazonaws.com/', '', $s3_url);

	}

	public function unknown_mail($s3_url,$bucket=S3_BUCKET_MAILBOX)
	{
		$key=str_replace('https://'.$bucket.'.s3.us-west-2.amazonaws.com/','',$s3_url);
		$filename = $this->public_get_filename($s3_url,$bucket);
		$content  = file_get_contents('s3://'.$bucket.'/'.$filename);
		if($content) {
            try {
                $result = $this->client_us2->putObject(array(
                    'Bucket' => $bucket,
                    'Key' => 'unknown/' . $key,
                    'Body' => $content
                ));
                return true;
            } catch (Exception $e) {
                error_log("unknown_mail: {$e->getMessage()}, ($s3_url)");
                return false;
            }
		}else{
			error_log("unknown_mail: The resource can't be accessed. ($s3_url)");
			echo "unknown_mail: The resource can't be accessed. ($s3_url)";
            return false;
		}
	}
    public function failed_mail($s3_url,$bucket=S3_BUCKET_MAILBOX)
    {
        $key=str_replace('https://'.$bucket.'.s3.us-west-2.amazonaws.com/','',$s3_url);
        $filename = $this->public_get_filename($s3_url,$bucket);
        $content  = file_get_contents('s3://'.$bucket.'/'.$filename);
        if($content) {
            try {
                $result = $this->client_us2->putObject(array(
                    'Bucket' => $bucket,
                    'Key' => 'failed/' . $key,
                    'Body' => $content
                ));
                return true;
            } catch (Exception $e) {
                error_log("failed_mail: {$e->getMessage()}, ($s3_url)");
                return false;
            }
        }else{
            error_log("failed_mail: The resource can't be accessed. ($s3_url)");
            echo "failed_mail: The resource can't be accessed. ($s3_url)";
            return false;
        }
    }
	public function credit_mail_pdf($attachments, $user_id = 0, $name = 'credit', $type = 'test') : array
	{
		$images = [];
		$fileList = [];
		$pdfPath = '';
		$result = [];
        $is_valid_pdf = 1;

		try {
			if (!$attachments) {
                return [];
            }
			$dir = 'pdf/';

			foreach ($attachments as $attachment) {
				$filename = $attachment->save($dir, TRUE, PhpMimeMailParser\Parser::ATTACHMENT_RANDOM_FILENAME);
				$fileList[] = $filename;

				$fileType = get_mime_by_extension($filename);
				if(is_image($fileType))
					$images[] = $filename;
				else if(is_pdf($fileType))
					$pdfPath = $filename;
			}

			// 合併多張圖片至PDF檔案
			if(!empty($images) && $pdfPath == '') {
				$pdfPath = $dir . $user_id . round(microtime(true) * 1000) . '_combined.pdf';
				$pdfImagick = new Imagick($images);
				$pdfImagick->setImageFormat('pdf');
				$pdfImagick->writeImages($pdfPath, true);
                $is_valid_pdf = 0;
			}

			$content = @file_get_contents($pdfPath);
			if($content !== false) {
				$result = $this->client->putObject(array(
					'Bucket' => S3_BUCKET,
					'Key' => $type . '/' . $name . $user_id . round(microtime(true) * 1000) . rand(1, 99) . ".pdf",
					'Body' => $content
				));
			}
		} catch (S3Exception $e) {
			error_log('Connecting to S3 was failed. Error in '.$e->getFile()." at line ".$e->getLine());
		} finally {
			foreach ($fileList as $filename) {
				unlink($filename);
			}
		}

        $res = [
            'url' => '',
            'is_valid_pdf' => $is_valid_pdf,
        ];

        if (isset($result['ObjectURL']))
        {
            $res['url'] = $result['ObjectURL'];
        }
        return $res;
	}

    // 新光附件圖片壓 pdf
    public function imagesToPdf($fileUrlList = [], $user_id = 0, $name = 'credit', $type = 'skbank_raw_data')
    {
        $images = [];
        $fileList = [];
        $dir = 'pdf/';
        $result = [];
        try {
            if ( empty($fileUrlList) || ! is_array($fileUrlList) )
                return '';

            foreach($fileUrlList as $list_name => $url)
            {
                $path = sprintf('%s%s_%s.jpg', $dir, $list_name, $type);
                if ( is_writable($dir) )
                {
                    $content = FALSE;
                    $content = @file_get_contents($url);
                    if ( $content !== FALSE )
                    {
                        file_put_contents($path, $content);
                    }
                    else
                    {
                        log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'image file get content failed']));
                    }
                    if ( file_exists($path) )
                    {
                        $real_path = realpath($path);
                        // 壓縮圖片
                        $compression_image = new Imagick();
                        $compression_image->readImage($real_path);
                        $compression_image->setImageCompression(Imagick::COMPRESSION_JPEG);
                        $compression_image->setImageCompressionQuality(10);
                        $compression_image->setImageFormat('jpg');
                        $compression_image->stripImage();
                        $compression_image->writeImage($real_path);
                        $compression_image->destroy();

                        if ( file_exists($real_path) )
                        {
                            $images[] = $real_path;
                        }
                        else
                        {
                            log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'array insert failed : file not exists, maybe ']));
                        }
                    }
                    else
                    {
                        log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'image file create failed']));
                    }
                }
                else
                {
                    log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'folder is not writable']));
                }
            }
            if ( ! empty($images) && count($fileUrlList) == count($images) )
            {
                // 拆分檔案，五個一組
                $images = array_chunk($images,5);
                // 合併多張圖片至PDF檔案
                foreach ($images as $chunk_images)
                {
                    $content = FALSE;
                    $pdfPath = $dir . round(microtime(TRUE) * 1000) . '_combined.pdf';

                    $pdfImagick = new Imagick($chunk_images);
                    $pdfImagick->setImageFormat('pdf');
                    $pdfImagick->writeImages($pdfPath, TRUE);
                    $pdfImagick->destroy();

                    if ( file_exists($pdfPath) )
                    {
                        $content = @file_get_contents($pdfPath);

                        // 上傳PDF至S3
                        if( $content !== FALSE )
                        {
                            $s3_result = $this->client->putObject(array(
                                'Bucket' => S3_BUCKET,
                                'Key' =>   'user_upload/' .$user_id .'/'. round(microtime(TRUE) * 1000) . rand(1, 99) . '.pdf',
                                'Body' => $content
                            ));
                            if ( ! empty($s3_result)
                                && isset($s3_result['ObjectURL'])
                                && ! empty($s3_result['ObjectURL']) )
                            {
                                $result[] = $s3_result['ObjectURL'];
                            }
                            else
                            {
                                log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'file upload failed with s3']));
                            }
                        }
                        else
                        {
                            log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'pdf file get content failed']));
                        }
                    }
                    else
                    {
                        log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'pdf file not exists']));
                    }
                }
            }
            else
            {
                log_message('error',json_encode(['function_name'=>'imagesToPdf','message'=>'number of S3 urls not equal to compression images']));
            }
        }
        catch (S3Exception $e)
        {
            error_log('Connecting to S3 was failed. Error in '.$e->getFile().' at line '.$e->getLine());
        }
        finally
        {
            $images = array_reduce($images, 'array_merge', array());
            foreach ($images as $filename)
            {
                if ( file_exists($filename) )
                    unlink($filename);
            }
		}
        return $result;
    }

}
?>
