<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');
use Certification\Report\CertificationFactory;
use Certification\Report\CertificationBase;

class CertificationReport extends MY_Admin_Controller
{

    protected $edit_method = [];
    protected $api_method = ['get_data', 'send_data'];
    private $type;
    private $target_id;
    private $certification;
    private $input_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('loan/target_model');
        $this->load->model('user/user_certification_model');

        $http_method = $this->input->method(TRUE);

        if ($http_method == 'POST')
        {
            $this->input_data = $this->input->post(NULL, TRUE);
        }
        else if ($http_method == 'GET')
        {
            $this->input_data = $this->input->get (NULL, TRUE);
        }

        if ( ($http_method == 'POST' || $http_method == 'GET') && ! $this->input_data)
        {
            $this->input_data = $this->input->raw_input_stream;
            if ($this->input_data && is_array(json_decode($this->input_data,TRUE)))
            {
                $this->input_data = json_decode($this->input_data,TRUE);
            }
        }
        $this->target_id = $this->input_data['target_id'] ?? '';

        $method = $this->router->fetch_method();
        if (in_array($method, $this->api_method))
        {
            $this->load->library('output/json_output');
        }

        if(empty($this->target_id))
        {
            $this->load->library('output/json_output');
            $this->json_output->setStatusCode(400)->setResponse(['parameter can not empty'])->send();
        }
        $this->certification = CertificationFactory::get_instance($this->target_id);
    }

    public function index()
    {
        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/ocr/list');
        $this->load->view('admin/_footer');
    }

    public function report()
    {
        $this->load->view($this->certification->get_view_path());
    }

    /**
     * [get_data 取得徵提項目資料]
     * @return [object] [result]
     */
    public function get_data()
    {
        $response = [];
        $this->load->model('user/user_certification_report_model');
        $report_log = $this->user_certification_report_model->get_by([
            'target_id' => $this->target_id,
            'user_id' => $this->certification->user->id,
        ]);
        if ( ! empty($report_log) && is_array(json_decode($report_log->content, TRUE)))
        {
            $response = json_decode($report_log->content, TRUE);
        }
        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function send_data()
    {
        if (empty($this->certification->user)
            || ! isset($this->certification->user->id)
            || ! is_numeric($this->certification->user->id))
        {
            $this->json_output->setStatusCode(400)->setResponse(['user info not found'])->send();
        }

        if (empty($this->input_data['report_data']) || ! is_array($this->input_data['report_data']))
        {
            $this->json_output->setStatusCode(400)->setResponse(['parameter "report_data" error'])->send();
        }
        $admin_id = $this->login_info->id;
        $admin_name = $this->login_info->name;
        $this->load->model('user/user_certification_report_model');
        $rs = $this->user_certification_report_model->insert_or_update($this->certification->user->id,
                $this->input_data['target_id'],$this->input_data['report_data'],$admin_id,$admin_name);
        if ($rs === TRUE)
        {
            $this->json_output->setStatusCode(200)->setResponse(['儲存成功！'])->send();
        }
        else
        {
            $this->json_output->setStatusCode(400)->setResponse(['儲存失敗。'])->send();
        }
    }
}
