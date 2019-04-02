<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
class Ocr_lib
{
	
	private $config = array();
	
    function __construct()
    {
        $this->CI = &get_instance();
    }

	//全民健康保险卡1030
	//台湾身份证正面1031
	//台湾身份证背面1032
    public function identify($image,$type)
    {	return false;//棄用
		if(in_array($type,array('1030','1031','1032'))){
			$data 	= array();
			$field	= array(
				'1030' => array(
					1 => 'name',
					2 => 'id_number',
					3 => 'birthday',
					4 => 'code',
					//5 => 'face',
					
				),
				'1031' => array(
					1 => 'name',
					2 => 'sex',
					3 => 'birthday',
					4 => 'id_card_date',
					5 => 'id_number',
					//6 => 'face',
				),
				'1032' => array(
					1 => 'father',
					2 => 'mother',
					3 => 'spouse',
					4 => 'service',
					5 => 'city',
					6 => 'address',
					7 => 'code',
				),
			);
			
			$file_content 	= base64_encode( file_get_contents($image) );
			$file_content 	= $file_content.'==##' . $type . '==##' . '' . '==##' . 'null';
			$param 	= array(
					'arg0' => 'test',
					'arg1' => $file_content,
					'arg2' => null,
					'arg3' => 'jpg'
				);
				
			try {
				$client = new SoapClient(OCR_API_URL);
				$rs 	= $client->__soapCall('doAllCardRecon', array('parameters' => $param));
			} catch (Exception $e) {
				error_log('Failed to connect to OCR');
				return false;
			}
			$return = str_replace('==@@','',$rs->return);
			$xml 	= simplexml_load_string($return,null,LIBXML_NOCDATA);
			$xml 	= json_decode(json_encode($xml),TRUE);
			if($xml['message']['value']=='识别完成'){
				$item = $xml['cardsinfo']['card']['item'];
				if(!empty($item)){
					foreach($item as $key => $value){
						if(is_array($value)){
							$value = '';
						}
						if(isset($field[$type][$key]) && $field[$type][$key])
							$data[$field[$type][$key]] = $value; 
					}
					return $data;
				}
			} 
		}
		return false;
    }
	
	public function google_document($url = ''){
		$image 	 = file_get_contents($url);
		$content = [];
		try {
			$imageAnnotator = new ImageAnnotatorClient();
			$response 		= $imageAnnotator->documentTextDetection($image);
			$annotation 	= $response->getFullTextAnnotation();
		} catch (Exception $e) {
			return $content;
		}

		if ($annotation) {
			$content = [];
			foreach ($annotation->getPages() as $page) {
				foreach ($page->getBlocks() as $block) {
					$block_text = '';
					foreach ($block->getParagraphs() as $paragraph) {
						foreach ($paragraph->getWords() as $word) {
							foreach ($word->getSymbols() as $symbol) {
								$block_text .= $symbol->getText();
							}
							$block_text .= ' ';
						}
						$block_text .= "\n";
					}
					$content[] = $block_text;
				}
			}
			$imageAnnotator->close();
		}
		return $content; 
	}

    function detect_text($path)
    {
        $result = "";
        $imageAnnotator = new ImageAnnotatorClient();

        # annotate the image
        $image = file_get_contents($path);
        $response = $imageAnnotator->textDetection($image);
        $texts = $response->getTextAnnotations();

        //printf('%d texts found:' . PHP_EOL, count($texts));
        foreach ($texts as $text) {
            //$result.=$text->getDescription() . PHP_EOL;
            $result.=$text->getDescription();
        //    # get bounds
        //    $vertices = $text->getBoundingPoly()->getVertices();
        //    $bounds = [];
        //    foreach ($vertices as $vertex) {
        //        $bounds[] = sprintf('(%d,%d)', $vertex->getX(), $vertex->getY());
        //    }
        //     print('Bounds: ' . join(', ',$bounds) . PHP_EOL);
        }
        return $result;
        $imageAnnotator->close();
    }

    function detect_label($path)
    {
        $imageAnnotator = new ImageAnnotatorClient();

        # annotate the image
        $image = file_get_contents($path);
        $response = $imageAnnotator->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        if ($labels) {
            print("Labels:" . PHP_EOL);
            foreach ($labels as $label) {
                print($label->getDescription() . PHP_EOL);
            }
        } else {
            print('No label found' . PHP_EOL);
        }

        $imageAnnotator->close();
    }
}