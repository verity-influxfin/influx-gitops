<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scan_lib{

	public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->library('Googlevision_lib');
    }
	
	public function idcardFront($img_url,$user_id){
        $content       = $this->CI->googlevision_lib->google_document($img_url,$user_id);
        $formatContent = $this->contentFormat(implode('',$content));
        return $formatContent;
	}

    public function scanData($img_url,$user_id){
        $content       = $this->CI->googlevision_lib->google_document($img_url,$user_id);
        $formatContent = $this->contentFormat(implode('',$content));
        return $formatContent;
    }

    public function scanDataArr($img_url,$user_id){
        $content       = $this->CI->googlevision_lib->google_document($img_url,$user_id);
        $formatContent = $this->contentFormat(implode('|',$content));
        return $formatContent;
    }

    private function contentFormat($content)
    {
        $content = preg_replace('/\s|\//','',strtoupper($content));
        return $content;
    }
}
