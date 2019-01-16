<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Product extends MY_Admin_Controller {
	
	protected $edit_method = [];
	
	public function __construct() {
		parent::__construct();
 	}
	
	public function index(){
		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/products_list',[
			'type'				=>'list',
			'product_list'		=> $this->config->item('product_list'),
			'product_type'		=> $this->config->item('product_type'),
			'product_identity'	=> $this->config->item('product_identity'),
		]);
		$this->load->view('admin/_footer');
	}

}
?>