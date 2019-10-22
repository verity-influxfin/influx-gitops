<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Sns extends REST_Controller {

	
    public function __construct()
    {
        parent::__construct();
		

    }


	public function certification_post()
    {
		$rawData = file_get_contents('php://input');
		$_POST = json_decode($rawData);
		$ch = curl_init($_POST->SubscribeURL) ;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_exec($ch) ;
		curl_close($ch);
    }

}
