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

	public function user_check_all_rules()
	{
		$input = $this->input->get(NULL, TRUE);
		$userId  = isset($input['userId']) ? $input['userId'] : '';
		$this->load->library('output/json_output');

		if(!$userId){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_lib');
		$userCheckAllRules = $this->brookesia_lib->userCheckAllRules($userId);

		$response = json_decode(json_encode($userCheckAllRules), true);

		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function user_check_all_log()
	{
		$input = $this->input->get(NULL, TRUE);
		$userId  = isset($input['userId']) ? $input['userId'] : '';
		$this->load->library('output/json_output');

		if(!$userId){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_lib');
		$userCheckAllLog = $this->brookesia_lib->userCheckAllLog($userId);

		$response = json_decode(json_encode($userCheckAllLog), true);

		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => [$response['response']['result']]];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function user_rule_hit()
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

	public function user_related_user()
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


# for brookesia react lib

	public function get_all_rule_type()
	{
		$input = $this->input->get(NULL, TRUE);
		$this->load->library('output/json_output');

		$this->load->library('brookesia/brookesia_react_lib');
		$user_result = $this->brookesia_react_lib->getAllRuleType();

		$response = json_decode(json_encode($user_result), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response['response']['results']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function get_result_by_rule()
	{
		$input = $this->input->get(NULL, TRUE);
		$typeId  = isset($input['typeId']) ? $input['typeId'] : '';
		$ruleId  = isset($input['ruleId']) ? $input['ruleId'] : '';

		$this->load->library('output/json_output');

		if(!$typeId || !$ruleId){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_react_lib');
		$result = $this->brookesia_react_lib->getResultByRule($typeId, $ruleId);

		$response = json_decode(json_encode($result), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response['response']['results'], "columnMap"=> $response['response']['columnMap']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

	public function get_rule_info_by_type()
	{
		$input = $this->input->get(NULL, TRUE);
		$typeId  = isset($input['typeId']) ? $input['typeId'] : '';

		$this->load->library('output/json_output');

		if(!$typeId){
			$this->json_output->setStatusCode(400)->send();
		}

		$this->load->library('brookesia/brookesia_react_lib');
		$result = $this->brookesia_react_lib->getRuleInfoByTypeId($typeId);

		$response = json_decode(json_encode($result), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$response = ["results" => $response['response']['results']];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

}
