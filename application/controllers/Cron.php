<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {


    function __construct()
    {
		parent::__construct();
        $this->load->model('log/log_script_model');
		$this->load->library('Payment_lib'); 
    }
	
	public function cathay()
	{
		$start_time = time();
		$ids 		= $this->payment_lib->insert_cathay_info();
		$num		= $ids?count($ids):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "cathay",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}

	public function handle_payment()
	{
		$start_time = time();
		$count 		= $this->payment_lib->handle_payment();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "handle_payment",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
}

