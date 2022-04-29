<?php defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/MY_Admin_Controller.php');

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
                'virtual_account like ' => CATHAY_VIRTUAL_CODE . "%",
            ]);
            foreach ($bankAccountRs as $bankAccount) {
                $bankAccountList[$bankAccount->user_id][$bankAccount->investor] = $bankAccount;
            }

            foreach ($list as $value) {
                // 找不到虛擬帳號
                $settings = json_decode($value['settings'], TRUE);
                if($settings === FALSE || !isset($bankAccountList[$value['user_id']]) ||
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
                    $transaction_param = [
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

                    $trans_rs = $this->transaction_model->insert($transaction_param);
                    if ($trans_rs) {
                        $this->passbook_lib->enter_account($trans_rs);

                        $data = json_decode($value['json_data'], true);
                        $data['transaction_id'] = $trans_rs;
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

    public function valuable_report()
    {
        $this->load->model('transaction/transaction_model');
        $this->load->model('user/user_certification_model');
        $this->load->model('transaction/transaction_model');
        $this->load->model('loan/credit_model');
        $this->load->model('user/user_certification_model');
        $this->load->library('target_lib');
        $this->load->library('credit_lib');

        $input = $this->input->get(NULL, TRUE);
        $where = [];
        $page_data = [];
        $list = [];

        $fields = ['product_id'];
        foreach ($fields as $field)
        {
            if (!empty($input[$field]))
            {
                $where[$field] = $input[$field];
            }
        }

        $product_list = $this->config->item('product_list');
        if(isset($where['product_id']))
        {
            $product_info = $product_list[$where['product_id']];
            if ($this->target_lib->is_sub_product($product_info, $where['sub_product_id'] ?? 0))
            {
                $product_info = $this->trans_sub_product($product_info, $where['sub_product_id'] ?? 0);
            }

            $start_date = isset($input['start_date']) && !empty($input['start_date']) ? $input['start_date'] : date('Y-m-1');
            $six_months_ago_date = date('Y-m-d', strtotime($start_date . '-6 months'));
            $end_date = isset($input['end_date']) && !empty($input['end_date']) ? $input['end_date'] : date('Y-m-d');
            $is_export = $input['export'] ?? 0;

            $condition = [
                'product_id' => $where['product_id'],
                'created_at >= ' => strtotime($start_date),
                'created_at <= ' => strtotime($end_date),
            ];

            $target_list = $this->target_model->get_many_by($condition);
            $target_list = array_column(json_decode(json_encode($target_list), TRUE), NULL, 'id');
            if ( ! empty($target_list))
            {
                $user_ids = array_unique(array_column($target_list, 'user_id'));

                $condition_with_user = array_replace_recursive($condition, [
                    'user_id' => $user_ids]);

                $apply_count_list = array_column($this->target_model->get_apply_target_count(array_replace_recursive($condition_with_user, [
                    'created_at >= ' => strtotime($six_months_ago_date)])), NULL, 'user_id');

                $apply_frequent_list = array_column($this->target_model->get_apply_frequent(array_replace_recursive($condition_with_user, [
                    'created_at >= ' => strtotime($six_months_ago_date)])), NULL, 'user_id');
                $target_ids = array_unique(array_merge(array_column($apply_frequent_list, 'last_id'), array_column($apply_frequent_list, 'first_id')));
                $target_list = $this->target_model->get_many_by(['id' => $target_ids]);
                $target_list = array_column(json_decode(json_encode($target_list), TRUE), NULL, 'id');

                $target_banned_list = array_column($this->target_model->get_banned_list($condition_with_user), 'total_count', 'user_id');
                $cert_banned_list = array_column($this->user_certification_model->get_banned_list(['user_id' => $user_ids]), 'total_count', 'user_id');

                $loaned_count_list = array_column($this->target_model->getUserStatusByTargetId($target_ids), 'total_count', 'user_id');
                $principal_list = array_column($this->transaction_model->get_delayed_principle($target_ids, $end_date, 'user_id'), NULL, 'user_id');

                $cert_passed_list = $this->user_certification_model->as_array()->get_many_by([
                    'user_id' => $user_ids,
                    'status' => CERTIFICATION_STATUS_SUCCEED,
                    'investor' => USER_BORROWER,
                    'certification_id' => [CERTIFICATION_IDCARD, CERTIFICATION_STUDENT]
                ]);

                $cert_passed_list = array_reduce($cert_passed_list, function ($list, $item) {
                    $list[$item['user_id']][$item['certification_id']] = $item;
                    return $list;
                }, []);

                foreach ($user_ids as $user_id)
                {
                    // 使用者編號
                    $list[$user_id]['user_id'] = $user_id;

                    // 半年內申貸案數
                    $list[$user_id]['count'] = $apply_count_list[$user_id]['total_count'] ?? 0;

                    // 申貸頻率(天)
                    $list[$user_id]['frequent'] = 1;
                    $info = $apply_frequent_list[$user_id];
                    if ($info['last_id'] != $info['first_id'])
                    {
                        $time = $target_list[$info['last_id']]['created_at'] - $target_list[$info['first_id']]['created_at'];
                        $list[$info['user_id']]['frequent'] = round($time / 86400 / $list[$user_id]['count'], 2);
                    }

                    // 系統拒絕紀錄
                    $banned_count = ($target_banned_list[$user_id]['total_count'] ?? 0) + ($cert_banned_list[$user_id]['total_count'] ?? 0);
                    $list[$user_id]['banned_flag'] = $banned_count > 0 ? '有' : '無';

                    // 申貸紀錄
                    $loaned_count = $loaned_count_list[$user_id] ?? 0;
                    if ($loaned_count <= 0 && ($principal_list[$user_id] ?? 0) <= 0)
                    {
                        $list[$user_id]['apply_status'] = 'NN';
                    }
                    else if ($loaned_count > 0)
                    {
                        $list[$user_id]['apply_status'] = '有';
                    }
                    else
                    {
                        $list[$user_id]['apply_status'] = '無';
                    }

                    // 信用額度
                    $list[$user_id]['credit_status'] = '失效/未評估';
                    $credit = $this->credit_lib->get_credit($user_id, $where['product_id'], $where['sub_product_id'] ?? 0, FALSE);
                    if ($credit !== FALSE)
                    {
                        $amount = $credit['amount'] - ($principal_list[$user_id]['total_amount'] ?? 0);
                        if ($amount >= $product_info['loan_range_s'])
                        {
                            $list[$user_id]['credit_status'] = $amount;
                        }
                        else
                        {
                            // 該戶已無信用額度 (已全部動撥、該戶為逾期戶)
                            $list[$user_id]['credit_status'] = '無';
                        }
                    }

                    $list[$user_id]['identity'] = isset($cert_passed_list[$user_id][CERTIFICATION_IDCARD]) ? '有' : '無';
                    $list[$user_id]['student'] = isset($cert_passed_list[$user_id][CERTIFICATION_STUDENT]) ? '有' : '無';
                }
            }

            if ($is_export)
            {
                $this->load->library('spreadsheet_lib');
                $title_rows = [
                    'user_id' => ['name' => '借款人ID', 'width' => 10, 'alignment' => ['h' => 'center','v' => 'center']],
                    'count' => ['name' => '半年內申貸案數', 'width' => 14, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC, 'alignment' => ['h' => 'center','v' => 'center']],
                    'frequent' => ['name' => '申貸頻率(天)', 'width' => 12, 'datatype' => PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC, 'alignment' => ['h' => 'center','v' => 'center']],
                    'banned_flag' => ['name' => '系統拒絕紀錄', 'width' => 12,'alignment' => ['h' => 'center','v' => 'center']],
                    'apply_status' => ['name' => '申貸紀錄', 'width' => 10,'alignment' => ['h' => 'center','v' => 'center']],
                    'credit_status' => ['name' => '信用額度', 'width' => 15, 'alignment' => ['h' => 'center','v' => 'center']],
                    'identity' => ['name' => '通過實名', 'width' => 12,'alignment' => ['h' => 'center','v' => 'center']],
                    'student' => ['name' => '通過學生認證', 'width' => 12,'alignment' => ['h' => 'center','v' => 'center']]
                ];
                $this->spreadsheet_lib->save($title_rows, $list, "{$product_info['name']}_高價值用戶_{$start_date}_{$end_date}.xlsx");
                return;
            }
        }

        $page_data['list'] = $list;
        $page_data['product_list'] = array_intersect_key($product_list, array_flip([PRODUCT_ID_STUDENT, PRODUCT_ID_SALARY_MAN, PRODUCT_SK_MILLION_SMEG]));

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/valuable_list', $page_data);
        $this->load->view('admin/_footer');
    }

    public function sales_report()
    {
        $this->load->model('user/sale_goals_model');
        // $this->load->model('user/sale_dashboard_model');

        $goal_ym = $this->input->get('goal_ym') ?? date('Y-m');
        $at_month = str_replace('-', '', $goal_ym);

        // 檢查如果是新的月份有沒有設定過目標了 - 好像可以直接用別的功能 TODO
        $new_goals = $this->sale_goals_model->get_goals_number_at_this_month();

        // 把大部分的東西都改寫到 library 裡面
        $this->load->library('Sales_lib', ['at_month' => $at_month]);

        // 處理日期列
        $days_info = $this->sales_lib->get_days();
        $title_dates = $this->_parse_day_week_for_admin_dashboard($days_info);
        array_unshift($title_dates, '總和');
        $trtd_date = $this->_parse_array_to_trtd_string($title_dates);

        $return_trtd_datas = [];

        // 取得此月份的績效相關資料
        $datas = $this->sales_lib->calculate();

        // 取得各目標的 id 才能組成進入編輯頁的連結
        $goals_id = $this->sales_lib->get_goals_id();

        // 將資料做轉換處理 array -> string
        foreach ($datas as $key => $value)
        {
            if (is_numeric($key))
            {
                $value['goal'][0] = $this->_parse_goal_number_add_href($goals_id[$key], $value['goal'][0]);
                $return_trtd_datas[$key]['goal'] = $this->_parse_array_to_trtd_string($value['goal']);
                $return_trtd_datas[$key]['real'] = $this->_parse_array_to_trtd_string($value['real']);
                $return_trtd_datas[$key]['rate'] = $this->_parse_array_to_trtd_string($value['rate']);
            }
        }

        $page_data = [
            'trtd_date' => $trtd_date,
            'goal_ym' => $goal_ym,
            'datas' => $return_trtd_datas,
            'total_deals' => $this->_parse_array_to_trtd_string($datas['total_deals']),
        ];

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/sales_target_setting', $page_data);
        $this->load->view('admin/_footer');
    }

    // 將 月日 跟 星期 結合 => X月Y日(六) ，呈現在後台的樣式跟報表不一樣應該沒差吧
    private function _parse_day_week_for_admin_dashboard($days_info)
    {
        $data = [];
        for ($i = 0; $i < count($days_info['date']); $i++)
        {
            array_push($data, $days_info['date'][$i] . '(' . $days_info['week'][$i] . ')');
        }

        return $data;
    }

    // 將陣列資料掛上 td 標籤組成可以直接塞回 html table 的字串
    private function _parse_array_to_trtd_string($datas)
    {
        return '<td>' . implode('</td><td>', $datas) . '</td>';
    }

    // 業績目標的數字在上 td 標籤前先包 a 標籤
    private function _parse_goal_number_add_href($id, $number)
    {
        return "<a href='/admin/Sales/goal_edit/{$id}' target='_blank'>{$number}</a>";
    }

    public function goal_edit($id)
    {
        $this->load->model('user/sale_goals_model');
        $goal_info = $this->sale_goals_model->as_array()->get($id);

        // 檢查只有當月的績效目標才可以修改
        $at_month = date('Ym');

        if (empty($goal_info) ||
            $goal_info['at_month'] < $at_month)
        {
            // 直接回傳 alert
            echo "<script>alert('請勿更新本月以前的目標');parent.location.href='/admin/AdminDashboard';</script>";
            exit;
        }

        $page_data['id'] = $goal_info['id'];
        $page_data['name'] = $this->sale_goals_model->type_name_mapping()[$goal_info['type']];
        $page_data['number'] = $goal_info['number'];

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/sales_goal_edit', $page_data);
        $this->load->view('admin/_footer');
    }

    // 改成另一個查看單一目標的頁面
    public function set_goals($goal_id)
    {
        $this->load->model('user/sale_goals_model');

        $number = $this->input->get('number');
        if (is_numeric($number))
        {
            $this->sale_goals_model->update($goal_id, ['number' => $number]);
            echo "<script>alert('更新成功');parent.location.href='/admin/Sales/sales_report';</script>";
            exit;
        }

        echo "<script>alert('績效請勿亂填');parent.location.href='/admin/AdminDashboard';</script>";
    }

    public function goals_export()
    {
        $goal_ym = $this->input->get('goal_ym') ?? date('Y-m');
        $at_month = str_replace('-', '', $goal_ym);

        $this->load->library('Sales_lib', ['at_month' => $at_month]);
        $days_info = $this->sales_lib->get_days();

        // 整理日期的匯出格式
        $first_row = $days_info['date'];
        array_unshift($first_row, '日期');
        $second_row = $days_info['week'];
        array_unshift($second_row, '總和');

        $content1 = [];
        $content2 = [];

        $content1[] = $first_row;
        $content1[] = $second_row;
        $content2[] = $first_row;
        $content2[] = $second_row;

        // 取出該月資料
        $datas = $this->sales_lib->calculate();

        // 依照 excel 需要的匯出順序塞入 content
        $sort1 = [0, 3, 1, 2];
        foreach ($sort1 as $value)
        {
            $content1[] = $datas[$value]['goal'];
            $content1[] = $datas[$value]['real'];
            $content1[] = $datas[$value]['rate'];
        }

        // 第二頁
        $sort2 = [4, 5, 6, 10, 11, 7, 8, 9, 12, 13];
        foreach ($sort2 as $value)
        {
            $content2[] = $datas[$value]['goal'];
            $content2[] = $datas[$value]['real'];
            $content2[] = $datas[$value]['rate'];
        }

        // 最後補上 成交總計
        $content2[] = $datas['total_deals'];

        // 匯出資料內容
        $excel_contents = [
            [
                'sheet' => '貸前指標',
                'content' => $content1,
            ],
            [
                'sheet' => '貸中指標',
                'content' => $content2,
            ],
        ];
        $sheet_highlight = 'KPI指標-' . $days_info['int_month'] . '月';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle('績效統計表');

        foreach ($excel_contents as $sheet => $contents)
        {
            $sheet > 0 ? $spreadsheet->createSheet() : '';
            $row = 1;

            // 前幾行合併儲存格的項目處理完
            if (empty($sheet))
            {
                $spreadsheet->getActiveSheet()->mergeCells('A1:C2');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A1', $sheet_highlight);
                $spreadsheet->getActiveSheet($sheet)->getStyle('A1')->getAlignment()->setHorizontal('center');
                $spreadsheet->getActiveSheet()->mergeCells('A3:A14');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A3', '業務推廣');
                $spreadsheet->getActiveSheet()->mergeCells('B3:B5');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B3', '官網流量');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C3', '目標流量');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C4', '實際流量');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C5', '達成率');
                $spreadsheet->getActiveSheet()->mergeCells('B6:B8');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B6', '會員註冊');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C6', '目標會員數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C7', '實際總會員數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C8', '達成率');
                $spreadsheet->getActiveSheet()->mergeCells('B9:B11');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B9', 'APP下載');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C9', '目標下載數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C10', '實際下載數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C11', '達成率');
                $spreadsheet->getActiveSheet()->mergeCells('B12:B14');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B12', '申貸總計');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C12', '目標申貸戶數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C13', '實際完成申貸戶數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C14', '達成率');
            }
            else
            {
                // 這邊反過來是因為要先設定 Sheet 不然會合併到前一個的格子
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A1', $sheet_highlight);
                $spreadsheet->getActiveSheet()->mergeCells('A1:C2');
                $spreadsheet->getActiveSheet($sheet)->getStyle('A1')->getAlignment()->setHorizontal('center');

                $spreadsheet->getActiveSheet()->mergeCells('A3:A17');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A3', '申貸指標');
                $spreadsheet->getActiveSheet()->mergeCells('B3:B5');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B3', '上班族貸');
                $spreadsheet->getActiveSheet()->mergeCells('B6:B8');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B6', '學生貸');
                $spreadsheet->getActiveSheet()->mergeCells('B9:B11');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B9', '3S名校貸');
                $spreadsheet->getActiveSheet()->mergeCells('B12:B14');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B12', '信保專案');
                $spreadsheet->getActiveSheet()->mergeCells('B15:B17');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B15', '中小企業');

                $spreadsheet->getActiveSheet()->mergeCells('A18:A33');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A18', '成交指標');
                $spreadsheet->getActiveSheet()->mergeCells('B18:B20');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B18', '上班族貸');
                $spreadsheet->getActiveSheet()->mergeCells('B21:B23');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B21', '學生貸');
                $spreadsheet->getActiveSheet()->mergeCells('B24:B26');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B24', '3S名校貸');
                $spreadsheet->getActiveSheet()->mergeCells('B27:B29');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B27', '信保專案');
                $spreadsheet->getActiveSheet()->mergeCells('B30:B32');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B30', '中小企業');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B33', '總數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C33', '總成交數');
                $kpi_loan = ['目標申貸戶數', '實際申貸戶數', '達成率'];
                $kpi_deal = ['目標成交筆數', '實際成交筆數', '達成率'];
                for ($i = 3; $i < 33; $i++)
                {
                    if ($i <= 17)
                    {
                        $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C' . $i, $kpi_loan[$i % 3]);
                    }
                    else
                    {
                        $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C' . $i, $kpi_deal[$i % 3]);
                    }
                }
            }

            foreach ($contents['content'] as $key => $row_content)
            {
                foreach ($row_content as $content_index => $value)
                {
                    $column = Coordinate::stringFromColumnIndex($content_index + 4); // A = 1
                    $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($column . $row, $value);
                    $spreadsheet->getActiveSheet($sheet)->getStyle($column . $row)->getAlignment()->setHorizontal('center');
                }
                $row++;
            }

            $spreadsheet->setActiveSheetIndex($sheet)->setTitle($contents['sheet']);
        }
        $spreadsheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . 'testMergeCell' . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
