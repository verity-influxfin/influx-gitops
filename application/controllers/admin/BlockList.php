<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class BlockList extends MY_Admin_Controller
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
        $this->load->view('admin/block_list');
        $this->load->view('admin/_footer');
        $this->test();
    }

    public function test()
    {
        $this->load->model('user/block_list_model');

        $this->block_list_model->update_block_list();
    }
}