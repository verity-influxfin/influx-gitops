<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	function index(){
		
		$id 	= 'CO68566881';
		$key 	= '1a1de1cc3efc7cecb50bcc7216c352c8';
		$time 	= time();
		$url = 'https://dev-api.influxfin.com/cooperation/cooperation/contact';
		echo $id.'<br>';
		echo $time.'<br>';
		
		$content = '';
		
		$authorization = SHA1($id.$content.$time);
		$authorization = MD5($authorization.$key);
		echo $authorization.'<br>';
		$header = [
			'Authorization:'.$authorization,
			'CooperationID:'.$id,
			'Timestamp:'.$time,
		];
	}
}