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
	{
        // 進行貸案處理，將還款中的交易帳務過帳核算，更改狀態
        $script     = 6;
        $start_time = time();

        // 每五分鐘
		$this->load->library('Charge_lib');

        $count      = $this->charge_lib->script_charge_targets();
        $num        = $count?intval($count):0;
        $end_time   = time();
        $data = [
            'script_name'   => 'charge_targets',
            'num'           => $num,
            'start_time'    => $start_time,
            'end_time'      => $end_time
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
        $input 	= $this->input->get();
        $start  = isset($input['dstart'])?$input['dstart']:'';
        $end    = isset($input['dend'])?$input['dend']:'';
		$start_time = time();
		$count 		= $this->payment_lib->script_daily_tax($start,$end);
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

    public function re_create_estatement_html()
    {	//重新產生指定使用者對帳單
        $this->load->library('Estatement_lib');
        $start_time = time();
        $input 	= $this->input->get();
        $user_id = $input['user_id'];
        $start = $input['start'];
        $end = $input['end'];
        $investor = $input['investor'];
        $detail = $input['detail'];
        //user_id,開始時間.結束時間,投資端(option),detail(option)
        $count 		= $this->estatement_lib->script_re_create_estatement_content($user_id,$start,$end,$investor,$detail);
        $num		= $count?intval($count):0;
        $end_time 	= time();
        $data		= array(
            "script_name"	=> "re_create_estatement_html",
            "num"			=> $num,
            "start_time"	=> $start_time,
            "end_time"		=> $end_time
        );
        $this->log_script_model->insert($data);
        die("1");
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
        die("1");
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
    //hsiang  串國泰回應API
	public function check_batchno_to_cathay()
	{
        //每六分鐘
        $this->load->library('Payment_lib');
        $script  	 = 15;
        $start_time = time();
        $id_list 	 = $this->payment_lib->check_batchno_to_cathay();
        $num		= count($id_list);
        $end_time 	= time();
        $data		= [
            'script_name'	=> 'check_batchno_to_cathay',
            'num'			=> $num,
            'start_time'	=> $start_time,
            'end_time'		=> $end_time,
            'parameter'     => json_encode($id_list)
		];
		$this->log_script_model->insert($data);
		die('1');
	}

    public function EDM()
    {
        $input = $this->input->get();
        $user_id = isset($input['user_id']) ? $input['user_id'] : 0;
        $title = $input['title'];
        $content = $input['content'];
        $EDM = $input['EDM'];
        $url = $input['url'];
        $investor = isset($input['investor']) ? $input['investor'] : 0;
        $school = isset($input['school']) && $input['school'] != '' ? $input['school'] : false;
        $years = isset($input['years']) && $input['years'] != '' ? $input['years'] : false;
        $sex = isset($input['sex']) && $input['sex'] != '' ? $input['sex'] : false;
        $app = isset($input['app']) && $input['app'] != '' ? $input['app'] : false;
        $mail = isset($input['mail']) && $input['mail'] != '' ? $input['mail'] : false;
        $this->load->library('Notification_lib');

        $start_time = time();
        $count = $this->notification_lib->EDM($user_id, $title, $content, $EDM, $url, $investor, $school, $years, $sex, $app, $mail);
        $num = $count ? intval($count) : 0;
        $end_time = time();
        $data = [
            'script_name' => 'EDM',
            'num' => $num,
            'parameter' => json_encode([$user_id, $title, $content, $EDM, $url, $investor, $school, $years, $sex, $app, $mail]),
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->log_script_model->insert($data);
    }

	public function notice_msg(){
        $input = $this->input->get();
        $user_id = isset($input['user_id']) ? $input['user_id'] : 0;
        $title = $input['title'];
        $content = $input['content'];
        $investor = isset($input['investor']) ? $input['investor'] : 0;
        $type = isset($input['type']) ? $input['type'] : 'b03';
        $this->load->library('Notification_lib');
        $this->notification_lib->notice_msg($user_id, $title, $content, $investor, $type);
    }
    public function rescraper_file(){

      $input = $this->input->get();
      $ip = isset($input['ip']) ? $input['ip'] : '';

      if(!$ip){
        echo'paarams not ip value';exit;
      }
  		if(! file_exists('reScraper.txt')){
  			fopen("reScraper.txt", "w");
  		}
      $current = json_decode(file_get_contents('reScraper.txt'));

      if( !$current || ! isset($current->status) || ! isset($current->updated_at) ){
        $current = new stdClass();
        $current->status = 'no_data';
        $current->offset = '0';
        $current->updated_at = date('Y-m-d H:i:s');

        file_put_contents('reScraper.txt', json_encode($current));
      }

      if(isset($current->status)&& $current->status=='全部完成'){
        exit;
      }elseif(isset($current->status)&& $current->status=='no_data'){
          $current->offset++;
      }

      $this->load->model('user/user_model');
      $meta = $this->user_model->getUsersBy(['certification_id'=>1], ['name', 'id_card_place'], $current->offset, 1);

      $this->load->library('scraper/judicial_yuan_lib.php',['ip'=>$ip]);
      $current->userId = $meta['0']->user_id;
      $scraper_response = $this->judicial_yuan_lib->requestJudicialYuanVerdictsStatuses($meta['0']->user_id);
      $current->status = isset($scraper_response['response']['status'])?$scraper_response['response']['status']:'no_data';
      $worker_time = isset($scraper_response['response']['updatedAt'])?date('Y-m-d H:i:s',strtotime('+2 hours', $scraper_response['response']['updatedAt'])):'';

      if($current->status == '爬蟲執行完成' || (isset($worker_time) && $current->updated_at < $worker_time) || (isset($scraper_response['status']) && $scraper_response['status']=='204') ){
        if($current->status == '爬蟲執行完成' || (isset($worker_time) && $current->updated_at > $worker_time)){
          $current->offset++;
        }

        $meta = $this->user_model->getUsersBy(['certification_id'=>1], ['name', 'id_card_place'], $current->offset, 1);

        if(! empty($meta)){
          $scraper_response = $this->judicial_yuan_lib->requestJudicialYuanVerdicts($meta['0']->name, $meta['0']->id_card_place, $meta['0']->user_id);
          $current->status = 'no_data';
        }else{
          $current->status = '全部完成';
        }

      }

      $current->updated_at = date('Y-m-d H:i:s');
      file_put_contents('reScraper.txt', json_encode($current));
      echo'ok';
  	}

	public function send_ID_card_request()
	{
		$input = $this->input->get(NULL, TRUE);
		$personId  = isset($input['personId']) ? $input['personId'] : '';
		$applyCode  = isset($input['applyCode']) ? $input['applyCode'] : '';
		$applyYyymmdd  = isset($input['applyYyymmdd']) ? $input['applyYyymmdd'] : '';
		$issueSiteId  = isset($input['issueSiteId']) ? $input['issueSiteId'] : '';
		$this->load->library('output/json_output');
		$this->load->library('id_card_lib');

		if(!$personId || !$applyCode || !$applyYyymmdd || !$issueSiteId){
			$response = array("Wrong Parameters");
			$this->json_output->setStatusCode(400)->setResponse($response)->send();
		}

		$result = $this->id_card_lib->send_request($personId, $applyCode, $applyYyymmdd, $issueSiteId);

		$response = json_decode(json_encode($result), true);
		if(!$response){
			$this->json_output->setStatusCode(204)->send();
		}

		$this->json_output->setStatusCode(200)->setResponse([$response])->send();
	}

}

