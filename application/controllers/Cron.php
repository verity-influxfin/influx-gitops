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
	{	//每五分鐘
		$this->load->library('Payment_lib'); 
		$script  	= 1;
		$start_time = time();
		$ids 		= $this->payment_lib->script_get_cathay_info();
		$num		= $ids?count($ids):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'cathay',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}

	public function handle_payment()
	{	//每五分鐘
		$this->load->library('Payment_lib'); 
		$script  	= 2;
		$start_time = time();
		$count 		= $this->payment_lib->script_handle_payment();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'handle_payment',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function check_bidding()
	{	//每五分鐘
		$this->load->library('Target_lib'); 
		$script  	= 3;
		$start_time = time();
		$count 		= $this->target_lib->script_check_bidding();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'check_bidding',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function approve_target()
	{	//每五分鐘
		$this->load->library('Target_lib'); 
		$script  	= 4;
		$start_time = time();
		$count 		= $this->target_lib->script_approve_target();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'approve_target',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function check_transfer_bidding()
	{	//每五分鐘
		$this->load->library('Transfer_lib'); 
		$script  	= 5;
		$start_time = time();
		$count 		= $this->transfer_lib->script_check_bidding();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'check_transfer_bidding',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function charge_targets()
	{	//每五分鐘
		$this->load->library('Charge_lib'); 
		$script  	= 6;
		$start_time = time();
		$count 		= $this->charge_lib->script_charge_targets();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'charge_targets',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function prepayment_targets()
	{	//每五分鐘
		$this->load->library('Charge_lib'); 
		$script  	= 7;
		$start_time = time();
		$count 		= $this->charge_lib->script_prepayment_targets();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'prepayment_targets',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function check_certifications()
	{	//每五分鐘
		$this->load->library('Certification_lib'); 
		$script  	= 8;
		$start_time = time();
		$count 		= $this->certification_lib->script_check_certification();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'check_certifications',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function daily_tax()
	{	//每天下午一點
		$this->load->library('Payment_lib'); 
		$script  	= 9;
		$start_time = time();
		$count 		= $this->payment_lib->script_daily_tax();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'daily_tax',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}

	public function create_estatement_pdf()
	{	//每五分鐘
		$this->load->library('Estatement_lib'); 
		$script  	= 10;
		$start_time = time();
		$count 		= $this->estatement_lib->script_create_estatement_pdf();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'create_estatement_pdf',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function alert_account_remaining()
	{	//需要時才手動跑
		$this->load->library('Passbook_lib'); 
		$script  	= 11;
		$start_time = time();
		$count 		= $this->passbook_lib->script_alert_account_remaining();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'alert_account_remaining',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function create_estatement_html()
	{	//每五分鐘
		$this->load->library('Estatement_lib'); 
		$script  	= 12;
		$start_time = time();
		$count 		= $this->estatement_lib->script_create_estatement_content();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "create_estatement_html",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die('1');
	}

	public function send_estatement_pdf()
	{	//每五分鐘
		$this->load->library('Estatement_lib'); 
		$script  	= 13;
		$start_time = time();
		$count 		= $this->estatement_lib->script_send_estatement_pdf();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= array(
			"script_name"	=> "send_estatement_pdf",
			"num"			=> $num,
			"start_time"	=> $start_time,
			"end_time"		=> $end_time
		);
		$this->log_script_model->insert($data);
		die('1');
	}
	
	public function check_transfer_success()
	{	//每五分鐘
		$this->load->library('Transfer_lib'); 
		$script  	= 14;
		$start_time = time();
		$count 		= $this->transfer_lib->script_transfer_success();
		$num		= $count?intval($count):0;
		$end_time 	= time();
		$data		= [
			'script_name'	=> 'transfer_success',
			'num'			=> $num,
			'start_time'	=> $start_time,
			'end_time'		=> $end_time
		];
		$this->log_script_model->insert($data);
		die('1');
	}

    public function unlock_block_user()
    {	//每五分鐘
        $this->load->library('User_lib');
        $script  	= 15;
        $start_time = time();
        $count 		= $this->user_lib->script_unlock_block_user();
        $num		= $count?intval($count):0;
        $end_time 	= time();
        $data		= [
            'script_name'	=> 'unlock_block_user',
            'num'			=> $num,
            'start_time'	=> $start_time,
            'end_time'		=> $end_time
        ];
        $this->log_script_model->insert($data);
        die('1');
    }
}

