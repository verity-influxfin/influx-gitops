<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Sns extends REST_Controller {

	
    public function __construct()
    {
        parent::__construct();
		

    }


	public function certification_post()
    {
		$rawData = file_get_contents('php://input');
		$rawData_test = json_decode($rawData);
		$this->load->model('log/log_request_model');
				$this->log_request_model->insert(array(
					'method' 	=> $rawData,
					'url'	 	=> $rawData_test,
					'investor'	=> $rawData_test,
					'user_id'	=>$rawData,
					'agent'		=> $rawData_test,
				));
    }

}
