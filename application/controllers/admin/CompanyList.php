<?php require(APPPATH . '/libraries/MY_Admin_Controller.php');

class CompanyList extends MY_Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->config->load('taiwan_1000');
        $data = array(
            'title' => '台灣千大企業清單',
            'company_list' => $this->config->item('taiwan_1000')
        );
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/company_list', $data);
        $this->load->view('admin/_footer');
    }

    public function world_500()
    {
        $this->config->load('world_500');
        $data = array(
            'title' => '世界500大企業清單',
            'company_list' => $this->config->item('world_500')
        );
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/company_list', $data);
        $this->load->view('admin/_footer');
    }

    public function medical_institute()
    {
        $this->config->load('medical_institute');
        $data = array(
            'title' => '醫療院所清單',
            'company_list' => $this->config->item('medical_institute')
        );
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/company_list', $data);
        $this->load->view('admin/_footer');
    }

    public function public_agency()
    {
        $this->config->load('public_agency');
        $data = array(
            'title' => '公家機關清單',
            'company_list' => $this->config->item('public_agency')
        );
        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/company_list', $data);
        $this->load->view('admin/_footer');
    }
}
