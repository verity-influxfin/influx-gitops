<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
    {
		if(in_array($type,array("1030","1031","1032"))){
			$data 	= array();
			$field	= array(
				"1030" => array(
					1 => "name",
					2 => "id_number",
					3 => "birthday",
					4 => "code",
					5 => "face",
					
				),
				"1031" => array(
					1 => "name",
					2 => "sex",
					3 => "birthday",
					4 => "id_card_date",
					5 => "id_number",
					6 => "face",
				),
				"1032" => array(
					1 => "father",
					2 => "mother",
					3 => "spouse",
					4 => "service",
					5 => "city",
					6 => "address",
					7 => "code",
				),
			);
			$client 		= new SoapClient(OCR_API_URL);
			$file_content 	= base64_encode( file_get_contents($image) );
			$file_content 	= $file_content."==##" . $type . "==##" . '' . "==##" . 'null';
			$param 	= array(
					'arg0' => 'test',
					'arg1' => $file_content,
					'arg2' => null,
					'arg3' => 'jpg'
				);
			$rs 	= $client->__soapCall('doAllCardRecon', array('parameters' => $param));
			$return = str_replace("==@@","",$rs->return);
			$xml 	= simplexml_load_string($return,null,LIBXML_NOCDATA);
			$xml 	= json_decode(json_encode($xml),TRUE);
			if($xml['message']['value']=="识别完成"){
				$item = $xml['cardsinfo']['card']['item'];
				if(!empty($item)){
					foreach($item as $key => $value){
						if(is_array($value)){
							$value = "";
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
	
}