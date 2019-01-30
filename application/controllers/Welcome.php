<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {
	
	function index(){
		/*try{
			$this->load->driver('cache');
			if($this->cache->redis->is_supported()){
				echo $this->cache->redis->get('key11');
			}
		} catch (Exception $e) {
			die('t');	
		}*/
		
		$this->load->library('Ocr_lib');
		$url = 'https://dev-influxp2p-personal.s3.ap-northeast-1.amazonaws.com/signing_target/person_image1915474455445.jpg';
		$text = $this->ocr_lib->google_document($url);
		dump($text);
	}
	function tool(){
		$id 	= 'CO68566881';
		$key 	= 'ae2d208d6c4cac0ef1c1080b338920c0';
		$time 	= time();
		$url = 'https://dev-api.influxfin.com/cooperation/cooperation/contact';
		echo $id.'<br>';
		echo $time.'<br>';
		
		$content = "";
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