<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Contract extends MY_Admin_Controller
{
	protected $edit_method = array("editContract");
	
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
		$contracts = "";
        $viewData   = [
            'contracts' => $contracts,
        ];
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/contract_list', $viewData);
        $this->load->view('admin/_footer');
    }

    public function editContract()
    {
        $id        	= $this->input->get('id');
        $viewData  	= [
            'contract' => $contract,
        ];
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/contract_edit', $viewData);
        $this->load->view('admin/_footer');
    }

}