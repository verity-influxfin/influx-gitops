<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BusinessRegistration extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!app_access()){
			show_404();
		}
		$this->load->model('log/log_script_model');
	}

	public function download()
	{
		$this->load->library('output/json_output');
		$this->load->library('scraper/Business_registration_lib');
		$download_request = $this->business_registration_lib->downloadBusinessRegistration();

		$response = json_decode(json_encode($download_request), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["download_request" => $response];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function result()
	{
		$input = $this->input->get(NULL, TRUE);
		$businessid  = isset($input['businessid']) ? $input['businessid'] : '';

		$this->load->library('output/json_output');
		if (!$businessid) {
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('scraper/Business_registration_lib');
		$business_registration_result = $this->business_registration_lib->getResultByBusinessId(
			$businessid
		);

		$response = json_decode(json_encode($business_registration_result), true);

		if($response['status']=='204' || !$response['status']){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["business_registration_result" => $response['response']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function date()
	{
		$this->load->library('output/json_output');
		$this->load->library('scraper/Business_registration_lib');
		$get_date = $this->business_registration_lib->getDate();

		$response = json_decode(json_encode($get_date), true);

		if($response['status']=='204' || !$response['status']){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["date" => $response['response']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

}
