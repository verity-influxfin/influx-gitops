<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Google\Cloud\Vision\V1\ImageAnnotatorClient;
class Googlevision_lib
{
	
	private $config = array();
	
    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('log/log_googlevision_model');
    }
	
	public function google_document($url = '',$user_id=0){
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
						//$block_text .= "\n";
					}
					$content[] = $block_text;
				}
			}
			$imageAnnotator->close();
		}
        $this->log_event('google_document',$user_id,$content,$url);
		return $content; 
	}

    function detect_text($path,$user_id=0)
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
        $imageAnnotator->close();
        $this->log_event('detect_text',$user_id,$result,$path);
        return $result;
    }

    function detect_label($path)
    {
        $imageAnnotator = new ImageAnnotatorClient();
        $result = '';

        # annotate the image
        $image = file_get_contents($path);
        $response = $imageAnnotator->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        if ($labels) {
            //print("Labels:" . PHP_EOL);
            foreach ($labels as $label) {
                //print($label->getDescription() . PHP_EOL);
                $result.=$label->getDescription();
            }
        } else {
            //print('No label found' . PHP_EOL);
        }

        return $result;
        $imageAnnotator->close();
    }

    function detect_face($path, $outFile = null)
    {
        $imageAnnotator = new ImageAnnotatorClient();

        # annotate the image
        // $path = 'path/to/your/image.jpg'
        $image = file_get_contents($path);
        $response = $imageAnnotator->faceDetection($image);
        $faces = $response->getFaceAnnotations();

        # names of likelihood from google.cloud.vision.enums
        $likelihoodName = ['UNKNOWN', 'VERY_UNLIKELY', 'UNLIKELY',
            'POSSIBLE','LIKELY', 'VERY_LIKELY'];

        //printf("%d faces found:" . PHP_EOL, count($faces));
        //foreach ($faces as $face) {
         //   $anger = $face->getAngerLikelihood();
        //    printf("Anger: %s" . PHP_EOL, $likelihoodName[$anger]);

        //    $joy = $face->getJoyLikelihood();
        //    printf("Joy: %s" . PHP_EOL, $likelihoodName[$joy]);

        //    $surprise = $face->getSurpriseLikelihood();
        //    printf("Surprise: %s" . PHP_EOL, $likelihoodName[$surprise]);

        //    # get bounds
        //    $vertices = $face->getBoundingPoly()->getVertices();
        //    $bounds = [];
        //    foreach ($vertices as $vertex) {
        //        $bounds[] = sprintf('(%d,%d)', $vertex->getX(), $vertex->getY());
        //    }
        //    print('Bounds: ' . join(', ',$bounds) . PHP_EOL);
        //    print(PHP_EOL);
        //}
        return count($faces);
    }

    private function log_event($type,$user_id,$rs,$image){
        $log_data	= array(
            "type"		=> $type,
            "user_id"	=> $user_id,
            "response"	=> json_encode($rs),
            "request"	=> json_encode($image),
        );
        $this->CI->log_googlevision_model->insert($log_data);
    }
}