<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class User extends REST_Controller {

	public function index_get()
	{
		$this->load->model('user_model');
		$rs = $this->user_model->get_all();
		var_dump($rs);
	}
}
