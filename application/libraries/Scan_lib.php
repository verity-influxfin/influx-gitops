<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scan_lib{

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->library('Googlevision_lib');
        $this->CI->load->library('Azure_lib');
    }

	public function azureScanData($img_url,$user_id,$cer_id=0){
        $content       = $this->CI->azure_lib->OCR($img_url,$user_id,$cer_id);
        $pure_text     = $this->CI->azure_lib->pure_text($content);
        return $pure_text;
	}

    public function scanData($img_url,$user_id,$cer_id=0){
        $content       = $this->CI->googlevision_lib->google_document($img_url,$user_id,$cer_id);
        $formatContent = $this->contentFormat(implode('',$content),'\s|\/|\\n');
        return $formatContent;
    }

    public function scanDataArr($img_url,$user_id,$cer_id=0){
        $content       = $this->CI->googlevision_lib->google_document($img_url,$user_id,$cer_id);
        $formatContent = $this->contentFormat(implode('|',$content),'\s|\/|\\n');
        return $formatContent;
    }

    public function detectText($img_url,$user_id,$cer_id=0,$replaEx=''){
        $content       = $this->CI->googlevision_lib->detect_text($img_url,$user_id,$cer_id);
        $formatContent = $this->contentFormat($content,$replaEx);
        return $formatContent;
    }

    private function contentFormat($content,$exr)
    {
        $content = preg_replace('/'.$exr.'/','',strtoupper($content));
        return $content;
    }
}
