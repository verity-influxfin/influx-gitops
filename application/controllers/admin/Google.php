<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!app_access()){
			show_404();
		}
		$this->load->model('log/log_script_model');
	}

	function google_request()
	{
		$input = $this->input->get(NULL, TRUE);
		$userid  = isset($input['userid']) ? $input['userid'] : '';
		$keyword = isset($input['keyword']) ? $input['keyword'] : '';
		$this->load->library('output/json_output');

		if(!$userid || !$keyword){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('scraper/google_lib');
		$google_request = $this->google_lib->requestGoogle($userid, $keyword);

		$response = json_decode(json_encode($google_request), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["google_request" => $response];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function google_status()
	{
		$input = $this->input->get(NULL, TRUE);
		$userid  = isset($input['userid']) ? $input['userid'] : '';
		$keyword = isset($input['keyword']) ? $input['keyword'] : '';

		$this->load->library('output/json_output');
		if (!$userid || !$keyword) {
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('scraper/google_lib');
		$google_status = $this->google_lib->getGoogleStatus(
			$userid,
			$keyword
		);

		$response = json_decode(json_encode($google_status), true);

		if($response['status']=='204' || !$response['status']){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["google_status" => $response['response']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function google_result()
	{
		$input = $this->input->get(NULL, TRUE);
		$userid  = isset($input['userid']) ? $input['userid'] : '';
		$keyword = isset($input['keyword']) ? $input['keyword'] : '';

		$this->load->library('output/json_output');
		if (!$userid || !$keyword) {
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('scraper/google_lib');
		$google_result = $this->google_lib->getGoogleResult(
			$userid,
			$keyword
		);

		$response = json_decode(json_encode($google_result), true);
		if($response['status']=='204' || !$response['status']){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["google_result" => $response['response']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function google_allResult()
	{
		$input = $this->input->get(NULL, TRUE);
		$userid = isset($input['userid']) ? $input['userid'] : '';

		$this->load->library('output/json_output');
		if (!$userid) {
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('scraper/google_lib');
		$google_results = $this->google_lib->getGoogleResultsByUserid(
			$userid
		);

		$response = json_decode(json_encode($google_results), true);
		if ($response['status'] == '204' || !$response['status']) {
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["google_allResult" => $response['response']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

}
