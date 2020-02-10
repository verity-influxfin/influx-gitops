<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Close extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		if(!app_access()){
			show_404();
		}
	}
	public function index()
	{
		$this->load->view('admin/close');
	}
}
?>
