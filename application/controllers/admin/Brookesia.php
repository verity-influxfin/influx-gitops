<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brookesia extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		if(!app_access()){
			show_404();
		}
		$this->load->model('log/log_script_model');
	}

	function user_check_all_rules()
	{
		$input = $this->input->get(NULL, TRUE);
		$userid  = isset($input['userid']) ? $input['userid'] : '';
		$this->load->library('output/json_output');

		if(!$userid){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_lib');
		$userCheckAllRules = $this->brookesia_lib->userCheckAllRules($userid);

		$response = json_decode(json_encode($userCheckAllRules), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	function user_rule_hit()
	{
		$input = $this->input->get(NULL, TRUE);
		$userId  = isset($input['userId']) ? $input['userId'] : '';
		$this->load->library('output/json_output');

		if(!$userId){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_lib');
		$user_result = $this->brookesia_lib->getRuleHitByUserId($userId);

		$response = json_decode(json_encode($user_result), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response['response']['results']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	function user_related_user()
	{
		$input = $this->input->get(NULL, TRUE);
		$userId  = isset($input['userId']) ? $input['userId'] : '';
		$this->load->library('output/json_output');

		if(!$userId){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_lib');
		$user_result = $this->brookesia_lib->getRelatedUserByUserId($userId);

		$response = json_decode(json_encode($user_result), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response['response']['results']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}



}
