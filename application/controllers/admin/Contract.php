<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Contract extends MY_Admin_Controller
{
	protected $edit_method = array("editContract");
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/contract_format_model');
    }

    public function index()
    {
        $contracts = $this->contract_format_model->get_all();
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
        $contracts = $this->contract_format_model->get($id);
        $viewData  	= [
            'contract' => $contracts,
        ];
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/contract_edit', $viewData);
        $this->load->view('admin/_footer');
    }

    public function updateContract()
    {
        $id = $this->input->post('id');
        $param = $this->validateInput($id);


        $this->contract_format_model->update($id, $param);
        $this->redirectToIndex();
    }

    private function redirectToIndex()
    {
        redirect(admin_url('contract'));
    }

    private function validateInput($id = 0)
    {
        //$this->form_validation->set_rules('type', 'type',
        //    "required|max_length[20]|callback_typeUnique[$id]", [
        //        'required'   => '%s 未填',
        //        'max_length' => '%s 過長',
        //    ]);

        //$this->form_validation->set_rules('name', 'name',
        //    'required|max_length[128]', [
        //        'required'   => '%s 未填',
        //        'max_length' => '%s 過長',
        //    ]);

        $param            = [];
        //$param['type']   = $this->input->post('type');
        //$param['name']    = $this->input->post('name');
        $param['content'] = $this->input->post('content');
        $param['version'] = $this->input->post('version');
        $param['remark'] = $this->input->post('remark');

        return $param;
    }

    public function typeUnique($type, $id)
    {
        $where      = ['type' => $type, 'id !=' => $id];
        $contracts = $this->contract_format_model->get_many_by($where);

        if (count($contracts) !== 0) {
            $this->form_validation->set_message('typeUnique', '%s 不可重複');

            return false;
        }

        return true;
    }

}