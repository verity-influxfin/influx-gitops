<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {


    function __construct()
    {
		parent::__construct();
        $this->load->model('log/log_script_model');
		$this->load->library('Transaction_lib'); 
		$this->load->library('Target_lib'); 
		$this->load->library('Transfer_lib'); 
		$this->load->library('Payment_lib'); 
    }
	
	public function cathay()
	{
		$start_time = time();
		$ids 		= $this->payment_lib->script_get_cathay_info();
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
		$count 		= $this->payment_lib->script_handle_payment();
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
	
	public function check_bidding()
	{
		$start_time = time();
		$count 		= $this->target_lib->script_check_bidding();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "check_bidding",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
	
	public function approve_target()
	{
		$start_time = time();
		$count 		= $this->target_lib->script_approve_target();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "approve_target",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
	
	public function check_transfer_bidding()
	{
		$start_time = time();
		$count 		= $this->transfer_lib->script_check_bidding();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "check_transfer_bidding",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
}

