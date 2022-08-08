<?php require(APPPATH . '/libraries/MY_Admin_Controller.php');

class CompanyList extends MY_Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->config->load('top_enterprise');
        $data = array(
            'top_enterprise_list' => $this->config->item('top_enterprise')
        );
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/company_list', $data);
        $this->load->view('admin/_footer');
    }
}
