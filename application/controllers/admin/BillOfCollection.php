<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class BillOfCollection extends MY_Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/bill_of_collection');
        $this->load->view('admin/_footer');
    }
}
