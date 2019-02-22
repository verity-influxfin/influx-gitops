<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {
	
	function tool(){
		$id 	= 'CO68566881';
		$key 	= 'b7792187f6d2309cbab7f42c007167b4';
		$time 	= time();
		$url = 'https://dev-api.influxfin.com/cooperation/cooperation/contact';
		echo $id.'<br>';
		echo $time.'<br>';
		
		$content = "1000031小雞10000toytoytoy123309772546512"; 
		//amount + instalment + item_count + item_name + item_price + merchant_order_no + phone + product_id
		
		$authorization = SHA1($id.$content.$time);
		$authorization = MD5($authorization.$key);
		echo $authorization.'<br>';
		$header = [
			'Authorization:'.$authorization,
			'CooperationID:'.$id,
			'Timestamp:'.$time,
		];

	}
	
	function index(){
		dump(iconv('UTF-8', 'BIG-5//IGNORE', '業'));
	}
}