<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Contract extends MY_Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $contracts 	= $this->contract_model->get_many_by(["status" => 1]);
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
        $contract 	= $this->contract_model->get($id);
        $viewData  	= [
            'contract' => $contract,
        ];
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/contract_edit', $viewData);
        $this->load->view('admin/_footer');
    }

    private function redirectToIndex()
    {
        redirect(admin_url('contract'));
    }

    public function insertContract()
    {
        $param = $this->validateInput();

        if ($this->form_validation->run()) {
            $param['status'] = 1;
            $this->contract_model->insert($param);
            $this->redirectToIndex();
        } else {
            $this->editContract();
        }
    }

    public function updateContract()
    {
        $id    = $this->input->post('id');
        $param = $this->validateInput($id);

        if ($this->form_validation->run()) {
            $this->contract_model->update($id, $param);
            $this->redirectToIndex();
        } else {
            alert('alias不可重複', admin_url('contract'));
        }
    }

    private function validateInput($id = 0)
    {
        $this->form_validation->set_rules('alias', 'alias',
            "required|max_length[20]|callback_aliasUnique[$id]", [
                'required'   => '%s 未填',
                'max_length' => '%s 過長',
            ]);

        $this->form_validation->set_rules('name', 'name',
            'required|max_length[128]', [
                'required'   => '%s 未填',
                'max_length' => '%s 過長',
            ]);

        $param            = [];
        $param['alias']   = $this->input->post('alias');
        $param['name']    = $this->input->post('name');
        $param['content'] = $this->input->post('content');

        return $param;
    }

    public function aliasUnique($alias, $id)
    {
        $where      = ['alias' => $alias, 'id !=' => $id];
        $contracts = $this->contract_model->get_many_by($where);

        if (count($contracts) !== 0) {
            $this->form_validation->set_message('aliasUnique', '%s 不可重複');

            return false;
        }

        return true;
    }
}