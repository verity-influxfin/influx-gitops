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
	
	public function check_certifications()
	{
		$this->load->library('Certification_lib'); 
		$script  	= 8;
		$start_time = time();
		$count 		= $this->certification_lib->script_check_certification();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "check_certifications",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
	
	public function daily_tax()
	{
		$this->load->library('Payment_lib'); 
		$script  	= 9;
		$start_time = time();
		$count 		= $this->payment_lib->script_daily_tax();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "daily_tax",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}

	public function create_estatement_pdf()
	{
		$this->load->library('Estatement_lib'); 
		$script  	= 10;
		$start_time = time();
		$count 		= $this->estatement_lib->script_create_estatement_pdf();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "create_estatement_pdf",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
	
	public function alert_account_remaining()
	{
		$this->load->library('Passbook_lib'); 
		$script  	= 11;
		$start_time = time();
		$count 		= $this->passbook_lib->script_alert_account_remaining();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "alert_account_remaining",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die("KO");
	}
}

