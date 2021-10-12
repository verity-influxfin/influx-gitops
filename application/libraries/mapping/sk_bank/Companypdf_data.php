<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companypdf_data
{

	public function __construct()
	{
		$this->CI = &get_instance();
	}
	// 取得 公司資料表資料
	public function getCompanyData(){
		$response = [
			'name' => '',
			'address' => '',
			'phone' => '',
		];
	}
}
