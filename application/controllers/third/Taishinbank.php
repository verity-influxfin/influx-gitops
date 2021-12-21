<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');

class Taishinbank extends REST_Controller {

	
    public function __construct()
    {
		parent::__construct();
		$this->load->library('Payment_lib');
		
    }


	public function debit_note_post()
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$res = $this->payment_lib->script_get_taishin_info($data);
		if ($res == 'SUCCESS') {
			$this->response(array('result' => 'SUCCESS'));
		} else if ($res == OrderExists) {
			$this->response(array('result' => 'ERROR', 'error' => OrderExists));
		} else {
			$this->response(array('result' => 'ERROR', 'error' => UnknownMethod));
		}
	}

}