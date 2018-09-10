<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {


    function __construct()
    {
		parent::__construct();
		if(!app_access()){
			show_404();
		}
        $this->load->model('log/log_script_model');
    }
	
	public function cathay()
	{
		$this->load->library('Payment_lib'); 
		$script  	= 1;
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
		$this->load->library('Payment_lib'); 
		$script  	= 2;
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
		$this->load->library('Target_lib'); 
		$script  	= 3;
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
		$this->load->library('Target_lib'); 
		$script  	= 4;
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
		$this->load->library('Transfer_lib'); 
		$script  	= 5;
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
	
	public function charge_targets()
	{
		$this->load->library('Charge_lib'); 
		$script  	= 6;
		$start_time = time();
		$count 		= $this->charge_lib->script_charge_targets();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "charge_targets",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
	
	public function prepayment_targets()
	{
		$this->load->library('Charge_lib'); 
		$script  	= 7;
		$start_time = time();
		$count 		= $this->charge_lib->script_prepayment_targets();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "prepayment_targets",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
}

