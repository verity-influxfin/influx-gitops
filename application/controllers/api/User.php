<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class User extends REST_Controller {

	public function index_get()
	{
		echo '<pre>';
		$this->load->model('user/user_model');
		$rs = $this->user_model->get_all();
		var_dump($rs);		
		$this->load->model('admin/admin_model');
		$rs = $this->admin_model->get_all();
		var_dump($rs);
		$this->load->model('product/product_category_model');
		$rs = $this->product_category_model->get_all();
		var_dump($rs);
	}
}
