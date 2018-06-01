<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Agreement extends MY_Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->login_info = check_admin();
        $this->load->model("platform/agreement_model");
        $this->load->library('form_validation');
        if (empty($this->login_info)) {
            redirect(admin_url('admin/login'), 'refresh');
        }
    }

    public function index()
    {
        $agreements = $this->agreement_model->get_many_by(["status" => 1]);
        $viewData   = [
            'agreements' => $agreements,
        ];
        $this->load->view('admin/_header');
        $this->load->view('admin/_title');
        $this->load->view('admin/agreement_list', $viewData);
        $this->load->view('admin/_footer');
    }

    public function editAgreement()
    {
        $id        = $this->input->get('id');
        $agreement = $this->agreement_model->get($id);
        $viewData  = [
            'agreement' => $agreement,
        ];
        $this->load->view('admin/_header');
        $this->load->view('admin/_title');
        $this->load->view('admin/agreement', $viewData);
        $this->load->view('admin/_footer');
    }

    private function redirectToIndex()
    {
        redirect(admin_url('agreement'));
    }

    public function insertAgreement()
    {
        $param = $this->validateInput();

        if ($this->form_validation->run()) {
            $param['status'] = 1;
            $this->agreement_model->insert($param);
            $this->redirectToIndex();
        } else {
            $this->editAgreement();
        }
    }

    public function updateAgreement()
    {
        $id    = $this->input->post('id');
        $param = $this->validateInput($id);

        if ($this->form_validation->run()) {
            $this->agreement_model->update($id, $param);
            $this->redirectToIndex();
        } else {
            alert('alias不可重複', admin_url('agreement'));
        }
    }

    public function deleteAgreement()
    {
        $id = $this->input->get('id');
        $this->agreement_model->update($id, ["status" => 0]);

        $this->redirectToIndex();
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
        $agreements = $this->agreement_model->get_many_by($where);

        if (count($agreements) !== 0) {
            $this->form_validation->set_message('aliasUnique', '%s 不可重複');

            return false;
        }

        return true;
    }
}