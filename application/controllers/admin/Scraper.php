<?php

defined('BASEPATH') or exit('No direct script access allowed');
require(APPPATH . '/libraries/MY_Admin_Controller.php');

class Scraper extends MY_Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('scraper/sip_lib');
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
