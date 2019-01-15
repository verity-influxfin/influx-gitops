<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {
	
	function tool(){
		$id 	= 'CO68566881';
		$key 	= 'ae2d208d6c4cac0ef1c1080b338920c0';
		$time 	= time();
		$url = 'https://dev-api.influxfin.com/cooperation/cooperation/contact';
		echo $id.'<br>';
		echo $time.'<br>';
		
		$content = "854896151651";
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
}