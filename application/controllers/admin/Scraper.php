<?php

defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Scraper extends MY_Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('scraper/judicial_yuan_lib');
        $this->load->library('scraper/sip_lib');
        $this->load->library('scraper/findbiz_lib');
        $this->load->library('scraper/business_registration_lib');
        // $this->load->library('scraper/google_lib');
        // $this->load->library('scraper/ptt_lib');
        $this->load->library('scraper/instagram_lib');
    }

    public function index()
    {
        $input = $this->input->get(NULL, TRUE);
        $page_view = isset($input['view']) ? $input['view'] : '';
        $this->load->view('admin/_header',$data=['use_vuejs'=>true]);
        $this->load->view('admin/_title', $this->menu);
        if ( ! empty($page_view))
        {
            $this->load->view("admin/certification/scraper/{$page_view}");
        }
        else
        {
            alert('尚未上線，敬請期待～',admin_url('AdminDashboard'));
        }
        $this->load->view('admin/_footer');
    }

    public function sip()
    {
        $input = $this->input->get(NULL, TRUE);
        $this->load->library('output/json_output');
        if (empty($input) || ! is_array($input))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'data type not correct'])->send();
        }

        if (empty($input['university']) || empty($input['account']))
        {
            $this->json_output->setStatusCode(405)->setResponse(['message' => 'parameter not correct'])->send();
        }

        $response = $this->sip_lib->getDeepData($input['university'], $input['account']);
        if (empty($response['response']))
        {
            $response = isset($response) ? $response['response'] : ['message' => 'sip not response'];
            $this->json_output->setStatusCode(401)->setResponse($response)->send();
        }

        $school_status = $this->sip_lib->getUniversityModel($input['university'], $input['account']);
        if (isset($school_status['response']))
        {
            $response['response']['school_status'] = $school_status['response'];
        }

        $this->json_output->setStatusCode(200)->setResponse($response['response'])->send();
    }
}
?>
