<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class AntiFraud extends MY_Admin_Controller
{
	protected $edit_method = array("editAgreement","insertAgreement","updateAgreement","deleteAgreement");
	
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/anti_fraud');
        $this->load->view('admin/_footer');
    }
}