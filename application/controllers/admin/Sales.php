<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Sales extends MY_Admin_Controller {
	
	protected $edit_method = array('promote_reward_loan');

	public function __construct() {
		parent::__construct();
		$this->load->model('user/user_meta_model');
		$this->load->model('admin/partner_model');
		$this->load->model('admin/partner_type_model');
 	}
	
	public function index(){
		$get 			= $this->input->get(NULL, TRUE);
		$sdate 			= isset($get['sdate'])&&$get['sdate']?$get['sdate']:date('Y-m-d');
		$edate 			= isset($get['edate'])&&$get['edate']?$get['edate']:date('Y-m-d');
		$page_data 		= array('sdate'=>$sdate,'edate'=>$edate);	
		$list			= array();
		$count 			= 0;
		$admins_qrcode 	= $this->admin_model->get_qrcode_list();
		$admins_name 	= $this->admin_model->get_name_list();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$partner_list 	= $this->partner_model->get_many_by(array('status'=>1));
		$partner_list_byid = array();
		if($partner_list){
			$partner_qrcode = array();
			foreach($partner_list as $key => $value){
				$partner_qrcode[$value->my_promote_code] = $value->id;
				$partner_list_byid[$value->id] = $value;
			}
		}
		
		
		if($sdate=='all' || $edate=='all'){
			$target_list	= $this->target_model->get_all();
		}else{
			$target_list	= $this->target_model->get_many_by(array(
				'created_at >='	=> strtotime($sdate.' 00:00:00'),
				'created_at <='	=> strtotime($edate.' 23:59:59'),
			));
		}
		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				$count++;
				if(isset($partner_qrcode[$value->promote_code]) && $partner_qrcode[$value->promote_code]){
					$list['partner'][$partner_qrcode[$value->promote_code]][$value->status][] = array(
						'id'			=> $value->id,
						'amount'		=> $value->amount,
						'loan_amount'	=> $value->loan_amount,
						'platform_fee'	=> $value->platform_fee,
						'loan_date'		=> $value->loan_date,
						'status'		=> $value->status,
						'promote_code'	=> $value->promote_code,
						'created_date'	=> date('Y-m-d',$value->created_at),
					);
				}
				
				if(isset($admins_qrcode[$value->promote_code]) && $admins_qrcode[$value->promote_code]){
					$list['sales'][$admins_qrcode[$value->promote_code]][$value->status][] = array(
						'id'			=> $value->id,
						'amount'		=> $value->amount,
						'loan_amount'	=> $value->loan_amount,
						'platform_fee'	=> $value->platform_fee,
						'loan_date'		=> $value->loan_date,
						'status'		=> $value->status,
						'promote_code'	=> $value->promote_code,
						'created_date'	=> date('Y-m-d',$value->created_at),
					);
				}
				
				if($value->promote_code=='' || (!isset($admins_qrcode[$value->promote_code]) && !isset($partner_qrcode[$value->promote_code]))){
					$list['platform'][$value->status][] = array(
						'id'			=> $value->id,
						'amount'		=> $value->amount,
						'loan_amount'	=> $value->loan_amount,
						'platform_fee'	=> $value->platform_fee,
						'loan_date'		=> $value->loan_date,
						'status'		=> $value->status,
						'promote_code'	=> $value->promote_code,
						'created_date'	=> date('Y-m-d',$value->created_at),
					);
				}
			}
		}
		$page_data['list'] 			= $list;
		$page_data['count'] 		= $count;
		$page_data['partner_list'] 	= $partner_list_byid;
		$page_data['admins_name'] 	= $admins_name;
		$page_data['partner_type'] 	= $partner_type;
		$page_data['target_status'] = $this->target_model->status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/sales_loan',$page_data);
		$this->load->view('admin/_footer');
	}

	public function register_report(){
		$get 			= $this->input->get(NULL, TRUE);
		$sdate 			= isset($get['sdate'])&&$get['sdate']?$get['sdate']:date('Y-m-d');
		$edate 			= isset($get['edate'])&&$get['edate']?$get['edate']:date('Y-m-d');
		$page_data 		= ['sdate'=>$sdate,'edate'=>$edate];	
		$admins_qrcode 	= $this->admin_model->get_qrcode_list();
		$partner_list 	= $this->partner_model->get_many_by(['status'=>1]);
		$partner_id_list = [];
		$partner_qrcode = [];
		$count 			= 0;
		if($partner_list){
			foreach($partner_list as $key => $value){
				$partner_qrcode[$value->my_promote_code] = $value->id;
				$partner_id_list[$value->id] = $value;
			}
		}
		
		$list = [
			'platform'	=>['count'=>0,'name'=>0,'school'=>0,'fb'=>0],
			'partner' 	=>[],
			'marketing' =>[],
			'sales' 	=>[],
		];
		$user_list		= $this->user_model->get_many_by([
			'status' 		=> 1,
			'created_at >='	=> strtotime($sdate.' 00:00:00'),
			'created_at <='	=> strtotime($edate.' 23:59:59'),
		]);
		if(!empty($user_list)){
			$total_list = [];
			$user_ids 	= [];
			foreach($user_list as $key => $value){
				$user_ids[] = $value->id;
				$total_list[$value->id] 		= $value;
				$total_list[$value->id]->school = 0;
			}
			
			$school_list 	= $this->user_meta_model->get_many_by([
				'user_id' =>$user_ids,
				'meta_key'=>'student_status',
			]);
			if(!empty($school_list)){
				foreach($school_list as $key => $value){
					$total_list[$value->user_id]->school = 1;
				}
			}
			
			foreach($total_list as $key => $value){
				$count++;
				if(isset($partner_qrcode[$value->promote_code]) && $partner_qrcode[$value->promote_code]){
					$id = $partner_qrcode[$value->promote_code];
					if(!isset($list['partner'][$id])){
						$list['partner'][$id] = ['count'=>0,'name'=>0,'school'=>0,'fb'=>0];
					}
					
					$list['partner'][$id]['count'] ++;
					if($value->school)
						$list['partner'][$id]['school'] ++;
					if(!empty($value->nickname))
						$list['partner'][$id]['fb'] ++;
					if(!empty($value->name))
						$list['partner'][$id]['name'] ++;
					
				}else if(isset($admins_qrcode[$value->promote_code]) && $admins_qrcode[$value->promote_code]){
					$id = $admins_qrcode[$value->promote_code];
					if(!isset($list['sales'][$id])){
						$list['sales'][$id] = ['count'=>0,'name'=>0,'school'=>0,'fb'=>0];
					}
					
					$list['sales'][$id]['count'] ++;
					if($value->school)
						$list['sales'][$id]['school'] ++;
					if(!empty($value->nickname))
						$list['sales'][$id]['fb'] ++;
					if(!empty($value->name))
						$list['sales'][$id]['name'] ++;
					
				} elseif ($value->promote_code) {
					if (!isset($list['marketing'][$value->promote_code])) {
						$list['marketing'][$value->promote_code] = ['count'=>0,'name'=>0,'school'=>0,'fb'=>0];
					}

					$list['marketing'][$value->promote_code]['count'] ++;
					if($value->school)
						$list['marketing'][$value->promote_code]['school'] ++;
					if(!empty($value->nickname))
						$list['marketing'][$value->promote_code]['fb'] ++;
					if(!empty($value->name))
						$list['marketing'][$value->promote_code]['name'] ++;
				} else{
					$list['platform']['count'] ++;
					if($value->school)
						$list['platform']['school'] ++;
					if(!empty($value->nickname))
						$list['platform']['fb'] ++;
					if(!empty($value->name))
						$list['platform']['name'] ++;
				}
				
			}
		}

		$page_data['list'] 			= $list;
		$page_data['partner_list'] 	= $partner_id_list;
		$page_data['admins_name'] 	= $this->admin_model->get_name_list();
		$page_data['partner_type'] 	= $this->partner_type_model->get_name_list();
		$page_data['count'] 		= $count;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/sales_register',$page_data);
		$this->load->view('admin/_footer');
	}
	
	public function bonus_report(){
		$get 		= $this->input->get(NULL, TRUE);
		$sdate 		= isset($get['sdate'])&&$get['sdate']?$get['sdate']:date('Y-m-d');
		$edate 		= isset($get['edate'])&&$get['edate']?$get['edate']:date('Y-m-d');
		$page_data 		= array('sdate'=>$sdate,'edate'=>$edate);	
		$list			= array();
		$count 			= 0;
		$admins_qrcode 	= $this->admin_model->get_qrcode_list();
		$admins_name 	= $this->admin_model->get_name_list();
		$partner_type 	= $this->partner_type_model->get_name_list();
		$partner_list 	= $this->partner_model->get_many_by(array('status'=>1));
		$partner_list_byid = array();
		if($partner_list){
			$partner_qrcode = array();
			foreach($partner_list as $key => $value){
				$partner_qrcode[$value->my_promote_code] = $value->id;
				$partner_list_byid[$value->id] = $value;
			}
		}
		
		$target_list	= $this->target_model->get_many_by(array(
			'status'		=> array(5,10),
			'loan_date >='	=> $sdate,
			'loan_date <='	=> $edate,
		));

		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				$count++;
				if(isset($partner_qrcode[$value->promote_code]) && $partner_qrcode[$value->promote_code]){
					$list['partner'][$partner_qrcode[$value->promote_code]][] = array(
						'id'			=> $value->id,
						'loan_amount'	=> $value->loan_amount,
						'platform_fee'	=> $value->platform_fee,
						'loan_date'		=> $value->loan_date,
						'status'		=> $value->status,
						'promote_code'	=> $value->promote_code,
					);
				}
				
				if(isset($admins_qrcode[$value->promote_code]) && $admins_qrcode[$value->promote_code]){
					$list['sales'][$admins_qrcode[$value->promote_code]][] = array(
						'id'			=> $value->id,
						'amount'		=> $value->amount,
						'loan_amount'	=> $value->loan_amount,
						'platform_fee'	=> $value->platform_fee,
						'loan_date'		=> $value->loan_date,
						'status'		=> $value->status,
						'promote_code'	=> $value->promote_code,
					);
				} elseif ($value->promote_code) {
					if (!isset($list['marketing'][$value->promote_code])) {
						$list['marketing'][$value->promote_code] = [];
					}
					$list['marketing'][$value->promote_code][] = [
						'id' => $value->id,
						'amount' => $value->amount,
						'loan_amount' => $value->loan_amount,
						'platform_fee' => $value->platform_fee,
						'loan_date' => $value->loan_date,
						'status' => $value->status,
						'promote_code' => $value->promote_code,
					];
				}
				
				if($value->promote_code=='' || (!isset($admins_qrcode[$value->promote_code]) && !isset($partner_qrcode[$value->promote_code]))){
					$list['platform'][] = array(
						'id'			=> $value->id,
						'amount'		=> $value->amount,
						'loan_amount'	=> $value->loan_amount,
						'platform_fee'	=> $value->platform_fee,
						'loan_date'		=> $value->loan_date,
						'status'		=> $value->status,
						'promote_code'	=> $value->promote_code,
					);
				}
			}
		}
		$page_data['list'] 			= $list;
		$page_data['count'] 		= $count;
		$page_data['partner_list'] 	= $partner_list_byid;
		$page_data['admins_name'] 	= $admins_name;
		$page_data['partner_type'] 	= $partner_type;
		$page_data['target_status'] = $this->target_model->status_list;

		$this->load->view('admin/_header');
		$this->load->view('admin/_title',$this->menu);
		$this->load->view('admin/sales_bonus',$page_data);
		$this->load->view('admin/_footer');
	}

	public function bonus_report_detail(){
		$get 		= $this->input->get(NULL, TRUE);
		$type 		= isset($get['type'])&&$get['type']?$get['type']:date('Y-m-d');
		$id 		= isset($get['id'])&&$get['id']?$get['id']:0;
		$code		= isset($get['code'])&&$get['code']?$get['code'] : '';
		$sdate 		= isset($get['sdate'])&&$get['sdate']?$get['sdate']:date('Y-m-d');
		$edate 		= isset($get['edate'])&&$get['edate']?$get['edate']:date('Y-m-d');
		$list		= array();
		$target_list = array();
		$name 		 = '';

		if($type=='partner' && $id){
			$info  = $this->partner_model->get($id);
			if($info){
				$name			= $info->company;
				$target_list	= $this->target_model->order_by('loan_date')->get_many_by(array(
					'status'		=> array(5,10),
					'loan_date >='	=> $sdate,
					'loan_date <='	=> $edate,
					'promote_code'  => $info->my_promote_code,
				));
			}
		}

		if($type=='sales' && $id){
			$info  = $this->admin_model->get($id);
			if($info){
				$name			= $info->name;
				$target_list	= $this->target_model->order_by('loan_date')->get_many_by(array(
					'status'		=> array(5,10),
					'loan_date >='	=> $sdate,
					'loan_date <='	=> $edate,
					'promote_code'  => $info->my_promote_code,
				));
			}
		}
		
		if ($type == 'marketing' && $code) {
			$target_list = $this->target_model->order_by('loan_date')->get_many_by(array(
				'status' => array(5,10),
				'loan_date >=' => $sdate,
				'loan_date <=' => $edate,
				'promote_code' => $code,
			));
		}

		if($type=='platform'){
			$name			= '無分類';
			$admins_qrcode 	= $this->admin_model->get_qrcode_list();
			$partner_list 	= $this->partner_model->get_many_by(array('status'=>1));
			$partner_qrcode = array();
			if($partner_list){
				foreach($partner_list as $key => $value){
					$partner_qrcode[$value->my_promote_code] = $value->id;
				}
			}
			
			$target_list	= $this->target_model->order_by('loan_date')->get_many_by(array(
				'status'			=> [5,10],
				'loan_date >='		=> $sdate,
				'loan_date <='		=> $edate,
				'promote_code NOT' 	=> array_merge(array_keys($admins_qrcode),array_keys($partner_qrcode)),
			));
		}
		
		if(!empty($target_list)){
			foreach($target_list as $key => $value){
				if ($type == "platform" && isset($value->promote_code) && $value->promote_code) {
					continue;
				}
				$list[] = $value;
			}
		}
		
		$page_data = array(
			'list'			=> $list,
			'name'			=> $name,
			'sdate'			=> $sdate,
			'edate'			=> $edate,
			'product_list'	=> $this->config->item('product_list'),
		);

		$this->load->view('admin/_header');
		$this->load->view('admin/sales_bonus_detail',$page_data);
		$this->load->view('admin/_footer');
	}

	public function accounts()
	{
		if (!$this->input->is_ajax_request()) {
			$this->load->view('admin/_header');
			$this->load->view('admin/_title',$this->menu);
			$this->load->view('admin/sales_accounts');
			$this->load->view('admin/_footer');
			return;
		}

		$get = $this->input->get(NULL, TRUE);
		$type = isset($get["type"]) ? $get["type"] : "";
		$sdate = isset($get['sdate'])&&$get['sdate']?$get['sdate']:date('Y-m-d');
		$edate = isset($get['edate'])&&$get['edate']?$get['edate']:date('Y-m-d');
		$category = isset($get["category"]) ? $get["category"] : "";
		$partnerId = isset($get["partner_id"]) ? $get["partner_id"] : "";
		$code = isset($get["code"]) ? $get["code"] : "";
		$adminId = isset($get["admin_id"]) ? $get["admin_id"] : "";
		$offset = isset($get["offset"]) && $get["offset"] >= 1 ? ($get["offset"] - 1) * 20 : 0;
		$limit = 20;

		$this->load->library('output/json_output');
		if ($category == "partner" || $category == "sales-marketing") {
			$partners 	= $this->partner_model->get_many_by(['status'=>1]);
			$partnerQrCode = null;
			$partnerQrCodes = [];
			if($partners){
				foreach($partners as $key => $value){
					if ($partnerId) {
						if ($partnerId == $value->id) {
							$partnerQrCode = $value->my_promote_code;
						}
					} else {
						$partnerQrCodes[] = $value->my_promote_code;
					}
				}
			}
		}

		$adminQrCode = "";
		if ($category == "sales") {
			if (!$adminId) {
				$this->json_output->setStatusCode(400)->send();
			}
			$adminQrCodes = $this->admin_model->get_qrcode_list();
			if ($adminQrCodes) {
				foreach ($adminQrCodes as $qrCode => $id) {
					if ($id == $adminId) {
						$adminQrCode = $qrCode;
						break;
					}
				}
			}
		}

		if ($type == "student") {
			$filters = [
				['status', '=', 1],
				['created_at', '>=', strtotime($sdate.' 00:00:00')],
				['created_at', '<=', strtotime($edate.' 23:59:59')],
				['meta_key', '=', 'student_status']
			];
			if ($category == "others") {
				$filters[] = ['promote_code', '=', ''];
			} elseif ($category == "partner") {
				if ($partnerQrCode) {
					$filters[] = ['promote_code', '=', $partnerQrCode];
				}
				if ($partnerQrCodes) {
					$filters[] = ['promote_code', 'in', $partnerQrCodes];
				}
			} elseif ($category == 'sales-marketing') {
				$filters[] = ['promote_code', '!=', ''];
				$filters[] = ['promote_code', "not in" ,$partnerQrCodes];
			} elseif ($category == "marketing") {
				$filters[] = ['promote_code', '=', $code];
			} elseif ($category == "sales") {
				$filters[] = ['promote_code', '=', $adminQrCode];
			}

			$users = $this->user_model->getStudents($filters, $offset, $limit);
		} else {
			$filters = [
				'status' => 1,
				'created_at >='	=> strtotime($sdate.' 00:00:00'),
				'created_at <='	=> strtotime($edate.' 23:59:59'),
			];
			if ($type == "fb") {
				$filters["nickname !="] = "";
			}
			if ($type == "name") {
				$filters["name !="] = "";
			}
			if ($category == "others") {
				$filters["promote_code"] = "";
			} elseif ($category == "partner") {
				if ($partnerQrCode) {
					$filters['promote_code'] = $partnerQrCode;
				}
				if ($partnerQrCodes) {
					$filters['promote_code'] = $partnerQrCodes;
				}
			} elseif ($category == 'sales-marketing') {
				$filters['promote_code !='] = '';
				$filters['promote_code NOT'] = $partnerQrCodes;
			} elseif ($category == "marketing") {
				$filters['promote_code'] = $code;
			} elseif ($category == "sales") {
				$filters['promote_code'] = $adminQrCode;
			}

			$users = $this->user_model->limit($limit, $offset)->get_many_by($filters);
		}

		if (!$users) {
			$this->json_output->setStatusCode(204)->send();
		}

		$this->load->library('output/user/user_output', ["data" => $users]);

		$userOutputs = $this->user_output->toMany("mapForSales");

		$response = ["users" => $userOutputs];
		$this->json_output->setStatusCode(200)->setResponse($response)->send();
	}

    public function loan_overview()
    {
        if (!$this->input->is_ajax_request()) {
            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/sales_loan_overview');
            $this->load->view('admin/_footer');
            return;
        }

        $get = $this->input->get(NULL, TRUE);
        $loanStartAt = isset($get["loan_sdate"]) ? $get["loan_sdate"] : "";
        $loanEndAt = isset($get["loan_edate"]) ? $get["loan_edate"] : "";
        $convertStartAt = isset($get["conversion_sdate"]) ? $get["conversion_sdate"] : "";
        $convertEndAt = isset($get["conversion_edate"]) ? $get["conversion_edate"] : "";

        $this->load->library('output/json_output');
        if (!$loanStartAt || !$loanEndAt || !$convertStartAt || !$convertEndAt) {
            $this->json_output->setStatusCode(400)->send();
        }

        $status = [0, 1, 2, 4, 5, 10, 21, 22, 23, 24];
        $createdRange = [
            "start" => strtotime($loanStartAt . ' 00:00:00'),
            "end" => strtotime($loanEndAt . ' 23:59:59'),
        ];
        $convertedRange = [
            "start" => strtotime($convertStartAt . ' 00:00:00'),
            "end" => strtotime($convertEndAt . ' 23:59:59'),
        ];

        $tableTypes = ['total', 'creditLoan', 'techiLoan', 'mobilePhoneLoan'];
        $tables = [];
        foreach ($tableTypes as $tableType) {
            $this->load->library('report/loan/loan_table', ["type" => $tableType], "{$tableType}Table");
            $tableName = "{$tableType}Table";
            $$tableName = $this->$tableName;
        }

        $statusToApplicantMethodMapping = [
            0 => 'Applicants',
            1 => 'PendingSigningApplicants',
            2 => 'PendingSigningApplicants',
            21 => 'PendingSigningApplicants',
            22 => 'PendingSigningApplicants',
            23 => 'PendingSigningApplicants',
            3 => 'OnTheMarket',
            4 => 'OnTheMarket',
            5 => 'MatchedApplicants',
            10 => 'MatchedApplicants',
            24 => 'MatchedApplicants',
        ];

        $statusToApplicationAmountMapping = [
            1 => 'ApprovedPendingSigningAmount',
            2 => 'ApprovedPendingSigningAmount',
            21 => 'ApprovedPendingSigningAmount',
            22 => 'ApprovedPendingSigningAmount',
            23 => 'ApprovedPendingSigningAmount',
            3 => 'OnTheMarketAmount',
            4 => 'OnTheMarketAmount',
            5 => 'MatchedAmount',
            10 => 'MatchedAmount',
            24 => 'MatchedAmount',
        ];

        $productToTableMapping = [
            1 => ['totalTable', 'creditLoanTable'],
            2 => ['totalTable', 'mobilePhoneLoanTable'],
            3 => ['totalTable', 'creditLoanTable'],
            4 => ['totalTable', 'mobilePhoneLoanTable'],
        ];
        $subProductToTableMapping = [
            '1-1' => ['totalTable', 'techiLoanTable'],
            '3-1' => ['totalTable', 'techiLoanTable'],
        ];
        $productAndTypeToRowMapping = [
            '1-0' => 'NewStudents',
            '1-1' => 'ExistingStudents',
            '1-2' => 'NewStudents',
            '1-3' => 'NewStudents',
            '1-4' => 'NewStudents',
            '2-0' => 'NewStudents',
            '2-1' => 'ExistingStudents',
            '2-2' => 'NewStudents',
            '2-3' => 'NewStudents',
            '2-4' => 'NewStudents',
            '3-0' => 'NewOfficeWorkers',
            '3-1' => 'ExistingOfficeWorkers',
            '3-2' => 'NewOfficeWorkers',
            '3-3' => 'NewOfficeWorkers',
            '3-4' => 'NewOfficeWorkers',
            '4-0' => 'NewOfficeWorkers',
            '4-1' => 'ExistingOfficeWorkers',
            '4-2' => 'NewOfficeWorkers',
            '4-3' => 'NewOfficeWorkers',
            '4-4' => 'NewOfficeWorkers',
        ];

        $newApplicantRows = $this->target_model->getUniqueApplicantCountByStatus($status, true, $createdRange, $convertedRange);
        $existingApplicantRows = $this->target_model->getUniqueApplicantCountByStatus($status, false, $createdRange, $convertedRange);

        $applicationCountByStatus = $this->target_model->getApplicationCountByStatus([], $createdRange, $convertedRange);
        $matchedCountByStatus = $this->target_model->getApplicationCountByStatus([5, 10], $createdRange, $convertedRange);

        $applicationAmounts = $this->target_model->getApplicationAmountByStatus([1, 2, 3, 4, 5, 10, 21, 22, 23, 24], $createdRange, $convertedRange);

        $rowsByApplicantType = [
            $newApplicantRows,
            $existingApplicantRows,
            $applicationCountByStatus,
            $matchedCountByStatus,
            $applicationAmounts
        ];

        for ($i = 0; $i < count($rowsByApplicantType); $i++) {
            $rows = $rowsByApplicantType[$i];
            foreach ($rows as $row) {
                if (!isset($productToTableMapping[$row->product_id])) {
                    continue;
                }
                $key = "{$row->product_id}-{$i}";
                if ($i < 2 && !isset($productAndTypeToRowMapping[$key])) {
                    continue;
                }
                $tables = $productToTableMapping[$row->product_id];

                $productAndSubProduct = "{$row->product_id}-{$row->sub_product_id}";
                if (isset($subProductToTableMapping[$productAndSubProduct])) {
                    $tables = $subProductToTableMapping[$productAndSubProduct];
                }

                $key = "{$row->product_id}-{$i}";
                $getRowMethod = "get" . $productAndTypeToRowMapping[$key];

                if ($i < 2) {
                    $getMethod = 'get' . $statusToApplicantMethodMapping[$row->status];
                    $setMethod = 'set' . $statusToApplicantMethodMapping[$row->status];


                } elseif ($i == 2) {
                    $getMethod = 'getApplications';
                    $setMethod = 'setApplications';
                } elseif ($i == 3) {
                    $getMethod = 'getMatchedApplications';
                    $setMethod = 'setMatchedApplications';
                } elseif ($i == 4) {
                    if (isset($statusToApplicationAmountMapping[$row->status])) {
                        $amountMethod = $statusToApplicationAmountMapping[$row->status];
                        $getAmountMethod = "get{$amountMethod}";
                        $setAmountMethod = "set{$amountMethod}";
                    }
                }

                foreach ($tables as $table) {
                    if ($i < 4) {
                        $current = $$table->$getRowMethod()->$getMethod() + intval($row->count);
                        $$table->$getRowMethod()->$setMethod($current);

                        if ($row->status != 0) {
                            $current = $$table->$getRowMethod()->getApplicants() + intval($row->count);
                            $$table->$getRowMethod()->setApplicants($current);
                        }
                    } elseif ($i == 4 && isset($statusToApplicationAmountMapping[$row->status])) {
                        $currentAmount = $$table->$getRowMethod()->$getAmountMethod() + intval($row->sumAmount);
                        $$table->$getRowMethod()->$setAmountMethod($currentAmount);
                    }
                }
            }
        }

        foreach ($tableTypes as $tableType) {
            $tableName = "{$tableType}Table";
            $this->$tableName->aggregate();
        }

        $this->load->library('output/report/loan/loan_table_output', ["data" => $totalTable, "alias" => "total_table"], "total_table_output");
        $this->load->library('output/report/loan/loan_table_output', ["data" => $creditLoanTable, "alias" => "credit_loan_table"], "credit_loan_table_output");
        $this->load->library('output/report/loan/loan_table_output', ["data" => $techiLoanTable, "alias" => "techi_loan_table"], "techi_loan_table_output");
        $this->load->library('output/report/loan/loan_table_output', ["data" => $mobilePhoneLoanTable, "alias" => "mobile_phone_loan_table"], "mobile_phone_loan_table_output");

        $response = [
            'total_table' => $this->total_table_output->toOne(),
            'credit_loan_table' => $this->credit_loan_table_output->toOne(),
            'techi_loan_table' => $this->techi_loan_table_output->toOne(),
            'mobile_phone_loan_table' => $this->mobile_phone_loan_table_output->toOne(),
        ];

        $this->json_output->setStatusCode(200)->setResponse($response)->send();
    }

    public function promote_list() {
        $this->load->library('user_lib');
        $this->load->model('user/qrcode_setting_model');

        $input 		= $this->input->get(NULL, TRUE);
        $where		= [];
        $list   	= [];
        $fields 	= ['alias'];

        foreach ($fields as $field) {
            if (isset($input[$field])&&$input[$field]!='') {
                $where[$field] = $input[$field];
            }
        }
        if(isset($input['tsearch'])&&$input['tsearch']!=''){
            $tsearch = $input['tsearch'];
            if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $tsearch))
            {
                $name = $this->user_model->get_many_by(array(
                    'name like '    => '%'.$tsearch.'%',
                    'status'	    => 1
                ));
                if($name){
                    foreach($name as $k => $v){
                        $where['user_id'][] = $v->id;
                    }
                }
            }else{
                if(preg_match_all('/[A-Za-z]/', $tsearch)==1){
                    $id_number	= $this->user_model->get_many_by(array(
                        'id_number  like'	=> '%'.$tsearch.'%',
                        'status'	        => 1
                    ));
                    if($id_number){
                        foreach($id_number as $k => $v){
                            $where['user_id'][] = $v->id;
                        }
                    }
                }
                elseif(preg_match_all('/\D/', $tsearch)==0){
                    $where['user_id'] = $tsearch;
                }
                else{
                    $where['promote_code like'] = '%'.$tsearch.'%';
                }
            }
        }
        $input['sdate'] = $input['sdate'] ?? '';
        $input['edate'] = $input['edate'] ?? '';
        if(isset($where['alias'])) {
            if($where['alias'] == "all")
                unset($where['alias']);

            $list = $this->user_lib->getPromotedRewardInfo($where, $input['sdate'], $input['edate']);
        }

        $qrcodeSettingList = $this->qrcode_setting_model->get_all();
        $alias_list = ['all' => "全部方案"];
        $alias_list = array_merge($alias_list, array_combine(array_column($qrcodeSettingList, 'alias'), array_column($qrcodeSettingList, 'description')));

        $page_data['list'] = $list;
        $page_data['alias_list'] = $alias_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/promote_list',$page_data);
        $this->load->view('admin/_footer');

    }

    public function promote_edit() {
        $this->load->library('user_lib');
        $this->load->model('user/qrcode_setting_model');
        $this->load->library('contract_lib');

        $input 		= $this->input->get(NULL, TRUE);
        $where		= [];
        $page_data  = [];

        $fields 	= ['id'];
        foreach ($fields as $field) {
            if (isset($input[$field])&&$input[$field]!='') {
                $where[$field] = $input[$field];
            }
        }

        $list = $this->user_lib->getPromotedRewardInfo($where, $input['sdate']??'', $input['edate']??'');

        $page_data['data'] = reset($list);

        $contract = $this->contract_lib->get_contract(isset($page_data['data']['info']) ? $page_data['data']['info']['contract_id'] : 0);
        $page_data['contract'] = $contract ? $contract['content'] : "";

        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/promote_edit',$page_data);
        $this->load->view('admin/_footer');

    }

    public function promote_reward_loan() {
        if (!$this->input->is_ajax_request()) {
            alert('ERROR, 只接受Ajax', admin_url('sales/promote_reward_list'));
        }
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/virtual_account_model');
        $this->load->model('transaction/qrcode_reward_model');
        $this->load->model('transaction/transaction_model');
        $this->load->model('transaction/virtual_passbook_model');
        $this->load->model('log/log_promote_reward_model');
        $this->load->library('passbook_lib');
        $this->load->library('output/json_output');

        $input 		= $this->input->post(NULL, TRUE);
        $ids 		= $input['ids'] ?? [];
        $totalAmount = 0;
        $successIdList = [];

        if(!empty($ids)) {
            $date = get_entering_date();
            $list = $this->qrcode_reward_model->getSettlementRewardList(['id' => $ids, 'status' => PROMOTE_REWARD_STATUS_TO_BE_PAID, 'amount > ' => 0]);
            if(empty($list))
                $this->json_output->setStatusCode(200)->setResponse(['success'=> true, 'msg' => "放款失敗，找不到對應的獎勵紀錄。"])->send();

            $rollback = function () {
                $this->user_qrcode_model->trans_rollback();
                $this->qrcode_reward_model->trans_rollback();
                $this->transaction_model->trans_rollback();
                $this->virtual_passbook_model->trans_rollback();
            };

            $bankAccountList = [];
            $bankAccountRs 	= $this->virtual_account_model->get_many_by([
                'user_id'	=> array_column($list, 'user_id'),
                'status'	=> 1,
                'virtual_account like ' => TAISHIN_VIRTUAL_CODE . "%",
            ]);
            foreach ($bankAccountRs as $bankAccount) {
                $bankAccountList[$bankAccount->user_id][$bankAccount->investor] = $bankAccount;
            }

            foreach ($list as $value) {
                // 找不到虛擬帳號
                $settings = json_decode($value['settings'], true) ?? [];
                if(!isset($bankAccountList[$value['user_id']]) ||
                    !isset($settings['investor']) || !isset($bankAccountList[$value['user_id']][$settings['investor']])
                ) {
                    continue;
                }
                $bankAccount = $bankAccountList[$value['user_id']][$settings['investor']];

                // 虛擬帳號使用中，直接跳過
                $virtual_account = $this->virtual_account_model->setVirtualAccount($value['user_id'], $settings['investor'],
                    VIRTUAL_ACCOUNT_STATUS_AVAILABLE, VIRTUAL_ACCOUNT_STATUS_USING, $bankAccount->virtual_account);
                if (empty($virtual_account))
                    continue;

                // 推薦碼結算中，直接跳過
                $promoteCode = $this->user_qrcode_model->setUserPromoteLock($value['promote_code'], PROMOTE_IS_NOT_SETTLEMENT, PROMOTE_IS_SETTLEMENT);
                if (empty($promoteCode))
                    continue;

                $this->user_qrcode_model->trans_begin();
                $this->qrcode_reward_model->trans_begin();
                $this->transaction_model->trans_begin();
                $this->virtual_passbook_model->trans_begin();
                try {
                    $amount = intval(round($value['amount'], 0));
                    $transaction_param[] = [
                        'source' => SOURCE_PROMOTE_REWARD,
                        'entering_date' => $date,
                        'user_from' => 0,
                        'bank_account_from' => PLATFORM_VIRTUAL_ACCOUNT,
                        'amount' => $amount,
                        'target_id' => 0,
                        'investment_id' => 0,
                        'instalment_no' => 0,
                        'user_to' => $value['user_id'],
                        'bank_account_to' => $bankAccount->virtual_account,
                        'status' => TRANSACTION_STATUS_PAID_OFF
                    ];

                    $transRsList = $this->transaction_model->insert_many($transaction_param);
                    if ($transRsList) {
                        foreach ($transRsList as $transRs) {
                            $this->passbook_lib->enter_account($transRs);
                        }
                        $data = json_decode($value['json_data'], true);
                        $data['transaction_id'] = $transRsList;
                        $this->qrcode_reward_model->update_by(['id' => $value['id']], ['status' => PROMOTE_REWARD_STATUS_PAID_OFF,
                            'json_data' => json_encode($data), 'settlement_time' => date('Y-m-d H:i:s')]);
                    }else{
                        throw new Exception("The list of insertions is empty.");
                    }

                    if ($this->user_qrcode_model->trans_status() === TRUE && $this->qrcode_reward_model->trans_status() === TRUE &&
                        $this->transaction_model->trans_status() === TRUE && $this->virtual_passbook_model->trans_status() === TRUE) {
                        $this->user_qrcode_model->trans_commit();
                        $this->qrcode_reward_model->trans_commit();
                        $this->transaction_model->trans_commit();
                        $this->virtual_passbook_model->trans_commit();
                        $totalAmount += $amount;
                        $successIdList[] = $value['id'];
                    }else{
                        throw new Exception("transaction_status is invalid.");
                    }
                } catch (Exception $e) {
                    $rollback();
                }
                $this->user_qrcode_model->setUserPromoteLock($value['promote_code'], PROMOTE_IS_SETTLEMENT, PROMOTE_IS_NOT_SETTLEMENT);
                $this->virtual_account_model->setVirtualAccount($value['user_id'], $settings['investor'],
                    VIRTUAL_ACCOUNT_STATUS_USING, VIRTUAL_ACCOUNT_STATUS_AVAILABLE, $bankAccount->virtual_account);
            }

            $this->log_promote_reward_model->insert(['amount' => $totalAmount, 'ids' => json_encode($successIdList),
                'admin_id' => $this->login_info->id]);
        }

        $this->json_output->setStatusCode(200)->setResponse(['success'=> true, 'msg' => "放款成功 ".count($successIdList)." 筆，共 ".$totalAmount." 元。"])->send();
    }

    public function promote_receipt() {
        $this->load->view('email/promote_receipt');
    }

    public function promote_reward_list() {
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('transaction/qrcode_reward_model');

        $input 		= $this->input->get(NULL, TRUE);
        $where		= [];
        $list   	= [];
        $fields 	= ['alias'];

        foreach ($fields as $field) {
            if (isset($input[$field])&&$input[$field]!='') {
                $where[$field] = $input[$field];
            }
        }
        if(isset($input['tsearch'])&&$input['tsearch']!=''){
            $tsearch = $input['tsearch'];
            if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $tsearch))
            {
                $name = $this->user_model->get_many_by(array(
                    'name like '    => '%'.$tsearch.'%',
                    'status'	    => 1
                ));
                if($name){
                    foreach($name as $k => $v){
                        $where['user_id'][] = $v->id;
                    }
                }
            }else{
                if(preg_match_all('/[A-Za-z]/', $tsearch)==1){
                    $id_number	= $this->user_model->get_many_by(array(
                        'id_number  like'	=> '%'.$tsearch.'%',
                        'status'	        => 1
                    ));
                    if($id_number){
                        foreach($id_number as $k => $v){
                            $where['user_id'][] = $v->id;
                        }
                    }
                }
                elseif(preg_match_all('/\D/', $tsearch)==0){
                    $where['user_id'] = $tsearch;
                }
                else{
                    $where['promote_code like'] = '%'.$tsearch.'%';
                }
            }
        }
        $input['sdate'] = $input['sdate'] ?? '';
        $input['edate'] = $input['edate'] ?? '';
        if(isset($where['alias'])) {
            if($where['alias'] == "all")
                unset($where['alias']);

            $reward_where = ['status' => PROMOTE_REWARD_STATUS_TO_BE_PAID, 'amount > ' => 0];
            if($input['sdate'] != "")
                $reward_where['start_time >= '] = $input['sdate'];
            if($input['edate'] != "")
                $reward_where['end_time <= '] = $input['edate'];

            $list = $this->qrcode_reward_model->getSettlementRewardList($reward_where, $where);
        }

        $qrcodeSettingList = $this->qrcode_setting_model->get_all();
        $alias_list = ['all' => "全部方案"];
        $alias_list = array_merge($alias_list, array_combine(array_column($qrcodeSettingList, 'alias'), array_column($qrcodeSettingList, 'description')));

        $page_data['list'] = $list;
        $page_data['alias_list'] = $alias_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title',$this->menu);
        $this->load->view('admin/promote_reward_list',$page_data);
        $this->load->view('admin/_footer');

    }
}

?>