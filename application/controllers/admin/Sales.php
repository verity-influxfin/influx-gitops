<?php defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH.'/libraries/MY_Admin_Controller.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Sales extends MY_Admin_Controller {
	
	protected $edit_method = array('promote_reward_loan');

    private $excel_config;

    public function __construct() {
		parent::__construct();
		$this->load->model('user/user_meta_model');
		$this->load->model('admin/partner_model');
		$this->load->model('admin/partner_type_model');
		$this->load->model('user/sale_goals_model');
        $this->excel_config = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFFFFFFF'],
                ]
            ]
        ];
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
		
		$target_list	= $this->target_model->get_bonus_report_detail($sdate, $edate);

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
				
				if ($value->promote_code == '')
                {
                    // 整理「無邀請碼」
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
				$target_list	= $this->target_model->get_bonus_report_detail($sdate, $edate, ['promote_code' => $info->my_promote_code]);
			}
		}

		if($type=='sales' && $id){
			$info  = $this->admin_model->get($id);
			if($info){
				$name			= $info->name;
				$target_list	= $this->target_model->get_bonus_report_detail($sdate, $edate, ['promote_code' => $info->my_promote_code]);
			}
		}
		
		if ($type == 'marketing' && $code) {
			$target_list = $this->target_model->get_bonus_report_detail($sdate, $edate, ['promote_code' => $code]);
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
			
			$target_list	= $this->target_model->get_bonus_report_detail($sdate, $edate, [
                'promote_code NOT' => array_merge(array_keys($admins_qrcode),array_keys($partner_qrcode))
            ]);
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
        $this->load->library("pagination");
        $this->load->library('qrcode_lib');
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

        if (($where['alias'] ?? "") == "all")
        {
            unset($where['alias']);
        }
        $where['subcode_flag'] = IS_NOT_PROMOTE_SUBCODE;

        $fullPromoteList = [];
        if ( ! empty($where) || ! empty($input['sdate']) || ! empty($input['edate']))
        {
            $fullPromoteList = $this->qrcode_lib->get_promoted_reward_info($where, $input['sdate'], $input['edate']);
        }

        if (isset($input['export']))
        { // 匯出報表
            $this->load->library('Spreadsheet_lib');
            switch ($input['export'])
            {
                case 1:
                    $title_rows = [
                        'contract_end_time' => ['name' => '合約截止日', 'width' => 20],
                        'user_id' => ['name' => '會員 ID'],
                        'settings' => ['name' => '類型', 'width' => 15],
                        'name' => ['name' => '名稱', 'width' => 10],
                        'promote_code' => ['name' => '邀請碼', 'width' => 14],
                        'fullMemberCount' => ['name' => '註冊+下載數量', 'width' => 16],
                        'loaned_count_student' => ['name' => '學生貸核貸數量', 'width' => 16],
                        'loaned_count_salary_man' => ['name' => '上班族貸核貸數量', 'width' => 18],
                        'loaned_count_collaboration' => ['name' => '合作產品核貸數量', 'width' => 18],
                        'totalRewardAmount' => ['name' => '累計獎金', 'width' => 12],
                        'status' => ['name' => '狀態']
                    ];
                    $data_rows = array_map(function ($element) {
                        $element['contract_end_time'] = $element['info']['contract_end_time'] ?? '';
                        $element['user_id'] = $element['info']['user_id'] ?? '';
                        $element['settings'] = $element['info']['settings']['description'] ?? '';
                        $element['name'] = $element['info']['name'] ?? '';
                        $element['promote_code'] = $element['info']['promote_code'] ?? '';
                        $element['loaned_count_student'] = $element['loanedCount']['student']??'';
                        $element['loaned_count_salary_man'] = $element['loanedCount']['salary_man']??'';
                        $element['loaned_count_collaboration'] = (($element['loanedCount']['small_enterprise'] ?? 0) + (array_sum($element['collaborationCount'] ?? [])));
                        $element['status'] = ($element['info']['status'] ?? '') == 1 ? '啟用' : '停用';
                        return $element;
                    }, $fullPromoteList);
                    $spreadsheet = $this->spreadsheet_lib->load($title_rows, $data_rows);

                    $start_date = '';
                    $end_date = '';
                    if ( ! empty($input['sdate']) && ($start_timestamp = strtotime($input['sdate'])) !== FALSE)
                    {
                        $start_date = date("Ymd", $start_timestamp);
                    }
                    if ( ! empty($input['edate']) && ($end_timestamp = strtotime($input['edate'])) !== FALSE)
                    {
                        $end_date = date("Ymd", $end_timestamp);
                    }
                    else if ( ! isset($start_timestamp) || $start_timestamp === FALSE)
                    {
                        $end_date = date("Ymd");
                    }

                    $this->spreadsheet_lib->download("推薦有賞報表({$start_date}-{$end_date}).xlsx", $spreadsheet);
                    return;
                default:
                    return;
            }
        }
        else
        { // 畫列表
            $config = pagination_config();
            $config['per_page'] = 40; //每頁顯示的資料數
            $config['base_url'] = admin_url('sales/promote_list');
            $config["total_rows"] = count($fullPromoteList);

            $this->pagination->initialize($config);
            $page_data["links"] = $this->pagination->create_links();

            $current_page = max(1, intval($input['per_page'] ?? '1'));
            $offset = ($current_page - 1) * $config['per_page'];

            $list = array_slice($fullPromoteList, $offset, $config['per_page']);

            $client = new Client([
                'base_uri' => getenv('ENV_ERP_HOST'),
                'timeout' => 300,
            ]);
            foreach ($list as $key => $item){
                $param = [
                    'user_id' => $item['info']['user_id'] ?? '',
                    'objective_promote_code' => $item['info']['promote_code'] ?? '',
                    'start_date' => $input['sdate'] ?? '',
                    'end_date' => $input['edate'] ?? '',
                ];
                // 移除空字串的鍵值對
                $param = array_filter($param, function ($value) {
                    return $value !== '';
                });

                try {
                    $res = $client->request('GET', '/user_qrcode/promote_performance', [
                        'query' => $param
                    ]);
                    $content = $res->getBody()->getContents();
                    $json = json_decode($content, true);
                    $list[$key]['api'] = $json;
                }catch (RequestException $e) {
                    $detail = '';
                    if ($e->hasResponse()) {
                        $responseBody = $e->getResponse()->getBody()->getContents();
                        $json = json_decode($responseBody, true);
                        if ($e->getResponse()->getStatusCode() == 422) {
                            $detail = $json['detail'][0]['msg'] ?? ''; // 取得錯誤訊息
                        } else {
                            $detail = $json['detail'] ?? '';
                        }
                    }
                    $list[$key]['api'] = ['status' => 'error', 'detail' => $detail];
                }
            }
            $qrcodeSettingList = $this->qrcode_setting_model->get_all();
            $alias_list = ['all' => "全部方案"];
            $alias_list = array_merge($alias_list, array_combine(array_column($qrcodeSettingList, 'alias'), array_column($qrcodeSettingList, 'description')));

            $page_data['list'] = $list;
            $page_data['alias_list'] = $alias_list;

            $this->load->view('admin/_header');
            $this->load->view('admin/_title', $this->menu);
            $this->load->view('admin/promote_code/promote_list', $page_data);
            $this->load->view('admin/_footer');
        }
    }

    public function promote_edit()
    {
        $this->load->library('user_lib');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/qrcode_collaborator_model');
        $this->load->library('contract_lib');
        $this->load->library('qrcode_lib');

        $input 		= $this->input->get(NULL, TRUE);
        $where		= [];
        $page_data  = [];

        $fields 	= ['id'];
        foreach ($fields as $field) {
            if (isset($input[$field])&&$input[$field]!='') {
                $where[$field] = $input[$field];
            }
        }
        $where['subcode_flag'] = IS_NOT_PROMOTE_SUBCODE;

        $list = $this->qrcode_lib->get_promoted_reward_info($where, $input['sdate'] ?? '', $input['edate'] ?? '');
        $page_data['collaborator_list'] = json_decode(json_encode($this->qrcode_collaborator_model->get_all(['status' => 1])), TRUE) ?? [];
        $page_data['data'] = reset($list);

        $client = new Client([
            'base_uri' => getenv('ENV_ERP_HOST'),
            'timeout' => 300,
        ]);
        $param = [
            'user_id' => $page_data['data']['info']['user_id'] ?? '',
            'objective_promote_code' => $page_data['data']['info']['promote_code'] ?? '',
            'start_date' => $input['sdate'] ?? '',
            'end_date' => $input['edate'] ?? '',
        ];
        // 移除空字串的鍵值對
        $param = array_filter($param, function ($value) {
            return $value !== '';
        });

        try {
            $res = $client->request('GET', '/user_qrcode/promote_performance', [
                'query' => $param
            ]);
            $content = $res->getBody()->getContents();
            $json = json_decode($content, true);
            $page_data['data']['api'] = $json;
        }catch (RequestException $e) {
            $detail = '';
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody()->getContents();
                $json = json_decode($responseBody, true);
                if ($e->getResponse()->getStatusCode() == 422) {
                    $detail = $json['detail'][0]['msg'] ?? ''; // 取得錯誤訊息
                } else {
                    $detail = $json['detail'] ?? '';
                }
            }
            $page_data['data']['api'] = ['status' => 'error', 'detail' => $detail];
        }

        $contract = $this->contract_lib->get_contract(isset($page_data['data']['info']) ? $page_data['data']['info']['contract_id'] : 0, [], FALSE);
        $page_data['contract'] = $contract ? htmlentities($contract['content']) : "";

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/promote_code/promote_edit', $page_data);
        $this->load->view('admin/_footer');

    }

    public function promote_reward_loan()
    {
        if ( ! $this->input->is_ajax_request())
        {
            alert('ERROR, 只接受Ajax', admin_url('sales/promote_reward_list'));
        }
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/virtual_account_model');
        $this->load->model('transaction/qrcode_reward_model');
        $this->load->model('transaction/transaction_model');
        $this->load->model('transaction/virtual_passbook_model');
        $this->load->model('log/log_qrcode_reward_model');
        $this->load->library('passbook_lib');
        $this->load->library('output/json_output');

        $input 		= $this->input->post(NULL, TRUE);
        $ids 		= $input['ids'] ?? [];
        $totalAmount = 0;
        $successIdList = [];

        if ( ! empty($ids))
        {
            $date = get_entering_date();
            $list = $this->qrcode_reward_model->getSettlementRewardList(['id' => $ids, 'status' => PROMOTE_REWARD_STATUS_TO_BE_PAID, 'amount > ' => 0]);
            if (empty($list))
            {
                $this->json_output->setStatusCode(200)->setResponse(['success' => TRUE, 'msg' => "放款失敗，找不到對應的獎勵紀錄。"])->send();
            }

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
            foreach ($bankAccountRs as $bankAccount)
            {
                $bankAccountList[$bankAccount->user_id][$bankAccount->investor] = $bankAccount;
            }

            foreach ($list as $value)
            {
                // 找不到虛擬帳號
                $settings = json_decode($value['settings'], TRUE);
                if ($settings === FALSE || ! isset($bankAccountList[$value['user_id']]) ||
                    ! isset($settings['investor']) || ! isset($bankAccountList[$value['user_id']][$settings['investor']])
                )
                {
                    continue;
                }
                $bankAccount = $bankAccountList[$value['user_id']][$settings['investor']];

                // 虛擬帳號使用中，直接跳過
                $virtual_account = $this->virtual_account_model->setVirtualAccount($value['user_id'], $settings['investor'],
                    VIRTUAL_ACCOUNT_STATUS_AVAILABLE, VIRTUAL_ACCOUNT_STATUS_USING, $bankAccount->virtual_account);
                if (empty($virtual_account))
                {
                    continue;
                }

                // 推薦碼結算中，直接跳過
                $promoteCode = $this->user_qrcode_model->setUserPromoteLock($value['promote_code'], PROMOTE_IS_NOT_SETTLEMENT, PROMOTE_IS_SETTLEMENT);
                if (empty($promoteCode))
                {
                    continue;
                }

                $this->user_qrcode_model->trans_begin();
                $this->qrcode_reward_model->trans_begin();
                $this->transaction_model->trans_begin();
                $this->virtual_passbook_model->trans_begin();
                try
                {
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
                    }
                    else
                    {
                        throw new Exception("The list of insertions is empty.");
                    }

                    if ($this->user_qrcode_model->trans_status() === TRUE && $this->qrcode_reward_model->trans_status() === TRUE &&
                        $this->transaction_model->trans_status() === TRUE && $this->virtual_passbook_model->trans_status() === TRUE)
                    {
                        $this->user_qrcode_model->trans_commit();
                        $this->qrcode_reward_model->trans_commit();
                        $this->transaction_model->trans_commit();
                        $this->virtual_passbook_model->trans_commit();
                        $totalAmount += $amount;
                        $successIdList[] = $value['id'];
                    }
                    else
                    {
                        throw new Exception("transaction_status is invalid.");
                    }
                }
                catch (Exception $e)
                {
                    $rollback();
                }
                $this->user_qrcode_model->setUserPromoteLock($value['promote_code'], PROMOTE_IS_SETTLEMENT, PROMOTE_IS_NOT_SETTLEMENT);
                $this->virtual_account_model->setVirtualAccount($value['user_id'], $settings['investor'],
                    VIRTUAL_ACCOUNT_STATUS_USING, VIRTUAL_ACCOUNT_STATUS_AVAILABLE, $bankAccount->virtual_account);
            }

            $this->log_qrcode_reward_model->insert(['amount' => $totalAmount, 'ids' => json_encode($successIdList),
                'admin_id' => $this->login_info->id]);
        }

        $this->json_output->setStatusCode(200)->setResponse(['success' => TRUE, 'msg' => "放款成功 " . count($successIdList) . " 筆，共 " . $totalAmount . " 元。"])->send();
    }

    public function promote_reward_list()
    {
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('transaction/qrcode_reward_model');
        $this->load->model('user/virtual_account_model');

        $input = $this->input->get(NULL, TRUE);
        $where = [];
        $list = [];
        $virtual_account_list = [];
        $fields = ['alias'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
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

            // 確認虛擬帳號
            $user_ids = array_column($list, 'user_id');
            if ( ! empty($user_ids))
            {
                $virtual_account_rs = $this->virtual_account_model->get_many_by([
                    'user_id' => $user_ids,
                    'status' => [VIRTUAL_ACCOUNT_STATUS_AVAILABLE, VIRTUAL_ACCOUNT_STATUS_USING],
                    'virtual_account like ' => CATHAY_VIRTUAL_CODE . "%",
                ]);

                foreach ($virtual_account_rs as $virtual_account)
                {
                    $virtual_account_list[$virtual_account->user_id][$virtual_account->investor] = $virtual_account;
                }
            }

            foreach ($list as $key => $value)
            {
                // 找不到虛擬帳號
                $settings = json_decode($value['settings'], TRUE);
                if ($settings === FALSE || ! isset($virtual_account_list[$value['user_id']]) ||
                    ! isset($settings['investor']) || ! isset($virtual_account_list[$value['user_id']][$settings['investor']])
                )
                {
                    $list[$key]['has_virtual_account'] = FALSE;
                }
                else
                {
                    $list[$key]['has_virtual_account'] = TRUE;
                }
            }
        }

        $qrcode_setting_list = $this->qrcode_setting_model->get_all();
        $alias_list = ['all' => "全部方案"];
        $alias_list = array_merge($alias_list, array_combine(array_column($qrcode_setting_list, 'alias'), array_column($qrcode_setting_list, 'description')));

        $page_data['list'] = $list;
        $page_data['alias_list'] = $alias_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/promote_code/promote_reward_list', $page_data);
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
                    'certification_id' => [CERTIFICATION_IDENTITY, CERTIFICATION_STUDENT]
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

                    $list[$user_id]['identity'] = isset($cert_passed_list[$user_id][CERTIFICATION_IDENTITY]) ? '有' : '無';
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

                $spreadsheet = $this->spreadsheet_lib->load($title_rows, $list);
                $this->spreadsheet_lib->download("{$product_info['name']}_高價值用戶_{$start_date}_{$end_date}.xlsx", $spreadsheet);
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
        $goal_ym = $this->input->get('goal_ym') ?? date('Y-m');
        $at_month = $this->_parse_goal_ym_to_at_month($goal_ym);

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
            if (empty($value['goal'][0]))
            {
                continue;
            }
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

    // 將 月日 跟 星期 結合 => X月Y日(六) ，呈現在後台的樣式跟報表不一樣
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

    // 載入更新單一目標頁
    public function goal_edit($id)
    {
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

    // 更新單一目標
    public function set_goal($goal_id)
    {
        $number = $this->input->get('number');
        if (is_numeric($number))
        {
            $this->sale_goals_model->update($goal_id, ['number' => $number]);
            echo "<script>alert('更新成功');parent.location.href='/admin/Sales/sales_report';</script>";
            exit;
        }

        echo "<script>alert('績效請勿亂填');parent.location.href='/admin/AdminDashboard';</script>";
    }

    // 載入更新整月目標頁
    public function monthly_goals_edit()
    {
        $goal_ym = $this->input->get('goal_ym') ?? date('Y-m');
        $at_month = $this->_parse_goal_ym_to_at_month($goal_ym);

        $goals = $this->sale_goals_model->get_goals_number_at_this_month($at_month);

        $page_data = [
            'goal_ym' => $goal_ym,
            'goal_items' => $this->sale_goals_model->type_name_mapping(),
            'goal_number' => $this->_parse_goal_struct($goals),
        ];

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/sales_monthly_goals_edit', $page_data);
        $this->load->view('admin/_footer');
    }

    private function _parse_goal_struct($goals)
    {
        $data = [];
        foreach ($goals as $value)
        {
            $struct = [
                'id' => $value['id'],
                'number' => $value['number'],
                'status' => $value['status']
            ];
            $data[$value['type']] = $struct;
        }

        return $data;
    }

    public function qrcode_modify_settings()
    {
        if ( ! $this->input->is_ajax_request())
        {
            alert('ERROR, 只接受Ajax', admin_url('sales/promote_reward_list'));
        }
        $this->load->model('user/user_qrcode_model');
        $this->load->model('log/log_qrcode_modify_model');
        $this->load->library('user_lib');
        $this->load->library('output/json_output');

        $input = $this->input->post(NULL, TRUE);
        $qrcode_id = isset($input['id']) ? (int) $input['id'] : 0;
        $formula_list = $input['data'] ?? [];
        if ($qrcode_id)
        {
            $qrcode = $this->user_qrcode_model->get($qrcode_id);
            if ( ! $qrcode)
            {
                $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "找不到對應的 promote_code, ID: " . $qrcode_id])->send();
            }
            $settings = json_decode($qrcode->settings, TRUE);
            if ($settings === FALSE)
            {
                $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "promote_code 的設定有誤, 無法解析, ID: " . $qrcode_id])->send();
            }

            foreach ($formula_list as $category => $info)
            {
                foreach ($info as $type => $value)
                {
                    switch ($type)
                    {
                        case 'amount':
                            $value = (int) $value;
                            break;
                        case 'percent':
                            $value = (float) $value;
                            break;
                    }

                    if (array_key_exists($category, $this->user_lib->rewardCategories))
                    {
                        $settings['reward']['product'][$category] = ['product_id' => $this->user_lib->rewardCategories[$category]];
                        $settings['reward']['product'][$category][$type] = $value;
                    }
                    else
                    {
                        $settings['reward'][$category][$type] = $value;
                    }
                }
            }

            $this->log_qrcode_modify_model->insert([
                'qrcode_id' => $qrcode_id,
                'settings' => json_encode($formula_list),
                'admin_id' => $this->login_info->id,
            ]);
            $user_qrcode_update_param = [
                'settings' => json_encode($settings)
            ];
            $this->user_qrcode_model->update_by(['id' => $qrcode_id], $user_qrcode_update_param);
            // 寫 log
            $this->load->model('log/log_user_qrcode_model');
            $user_qrcode_update_param['user_qrcode_id'] = $qrcode_id;
            $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

            $this->json_output->setStatusCode(200)->setResponse(['success' => TRUE, 'msg' => "修改成功。"])->send();
        }

        $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "沒有輸入對應的 promote_code ID: "])->send();
    }

    public function promote_import_report()
    {
        if ( ! $this->input->is_ajax_request())
        {
            alert('ERROR, 只接受Ajax', admin_url('sales/promote_reward_list'));
        }
        $this->load->library('output/json_output');
        $this->load->model('user/qrcode_collaborator_model');
        $this->load->model('user/user_qrcode_collaboration_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('log/log_qrcode_import_collaboration_model');

        $keyword_list = ['promote_code' => '推薦碼', 'collaborator' => '合作對象', 'datetime' => '時間'];
        $col_num_list = array_combine(array_keys($keyword_list), array_fill(0, count($keyword_list), -1));
        $col_str_list = array_combine(array_keys($keyword_list), array_fill(0, count($keyword_list), ''));

        if ( ! isset($_FILES['file']))
        {
            $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "沒有選擇的檔案，無法上傳。"])->send();
        }

        if ($_FILES['file']['error'] == 0)
        {
            $tmpName = $_FILES['file']['tmp_name'];

            try
            {
                $input_file_type = IOFactory::identify($tmpName);
                $reader = IOFactory::createReader($input_file_type);
                $spreadsheet = $reader->load($tmpName);
                $sheet = $spreadsheet->getActiveSheet();
            }
            catch (\PhpOffice\PhpSpreadsheet\Exception $e)
            {
                $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "無法匯入檔案，請檢查檔案格式是否有誤。"])->send();
            }

            // 獲取內容的最大列 如:D
            $max_column_index = Coordinate::columnIndexFromString($sheet->getHighestColumn());

            // 獲取內容的最大行 如:4
            $row = $sheet->getHighestRow();

            // 建立欄位名稱與 Excel 欄位索引的對應
            for ($i = 1; $i <= $max_column_index; $i++)
            {
                $key = $sheet->getCellByColumnAndRow($i, 1)->getValue();
                foreach ($keyword_list as $idx => $keyword)
                {
                    if ($key == $keyword)
                    {
                        $col_num_list[$idx] = $i;
                        $col_str_list[$idx] = Coordinate::stringFromColumnIndex($i);
                    }
                }
            }

            $nof_found_list = [];
            foreach ($col_num_list as $idx => $col_num)
            {
                if ($col_num < 0)
                {
                    $nof_found_list[] = $keyword_list[$idx];
                }
            }
            if ( ! empty($nof_found_list))
            {
                $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "第一列找不到關鍵欄位，匯入失敗。(需有欄位：" . implode('/', $nof_found_list) . ")"])->send();
            }

            // 轉成 JSON 格式並去除標題列
            $sheet_data = $spreadsheet->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);
            $sheet_data = array_slice($sheet_data, 1);

            $insert_list = [];
            $log_list = [];
            $user_qrcode_list = $this->user_qrcode_model->db->where(['status' => 1])->get('user_qrcode')->result_array();
            $user_qrcode_list = array_column($user_qrcode_list, NULL, 'promote_code');
            $collaborator_list = $this->qrcode_collaborator_model->db->where(['status' => 1])->get('qrcode_collaborator')->result_array();
            $collaborator_list = array_combine(array_column($collaborator_list, 'id'), array_column($collaborator_list, 'collaborator'));

            $data = array_columns($sheet_data, array_values($col_str_list));
            foreach ($data as $i => $info)
            {
                // 整理資料 (將 Excel 欄位轉成對應欄位名稱)
                $data[$i] = array_combine(array_keys($keyword_list), array_values($info));
                $data[$i]['datetime'] = date('Y-m-d H:i:s', strtotime($data[$i]['datetime']));

                $collaborator_idx = array_search($data[$i]['collaborator'], $collaborator_list);
                if ($collaborator_idx === FALSE)
                {
                    $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "上傳的檔案內有不合作之對象，請刪除後再試。(錯誤對象:" . $data[$i]['collaborator'] . ")"])->send();
                }
                else if ( ! array_key_exists($data[$i]['promote_code'], $user_qrcode_list))
                {
                    $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "上傳的檔案內有不存在之推薦碼，請刪除後再試。(錯誤推薦碼:" . $data[$i]['promote_code'] . ")"])->send();
                }
                else
                {
                    $imported_info = [
                        'user_qrcode_id' => $user_qrcode_list[$data[$i]['promote_code']]['id'],
                        'qrcode_collaborator_id' => $collaborator_idx,
                        'loan_time' => $data[$i]['datetime']
                    ];
                    $insert_list[] = $imported_info;
                    $log_list[$collaborator_idx][] = $imported_info;
                }
            }

            $rs = $this->user_qrcode_collaboration_model->insert_many($insert_list);

            // 新增匯入紀錄
            foreach ($log_list as $collaborator_idx => $info)
            {
                $this->log_qrcode_import_collaboration_model->insert([
                    'qrcode_collaboration_id' => $collaborator_idx,
                    'count' => count($info),
                    'content' => json_encode($info),
                    'admin_id' => $this->login_info->id,
                ]);
            }
            $this->json_output->setStatusCode(200)->setResponse(['success' => TRUE, 'msg' => "匯入成功，共匯入 " . count($rs) . " 筆。"])->send();
        }
        else
        {
            $this->json_output->setStatusCode(400)->setResponse(['success' => FALSE, 'msg' => "上傳檔案出現錯誤，請洽工程師。(錯誤編號:" . $_FILES['file']['error'] . ")"])->send();
        }
    }

    public function promote_import_list()
    {
        $this->load->library('user_lib');
        $this->load->library("pagination");
        $this->load->model('log/log_qrcode_import_collaboration_model');
        $this->load->model('user/qrcode_collaborator_model');

        $input = $this->input->get(NULL, TRUE);
        $where = [];
        $fields = ['collaboration_id'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $where[$field] = $input[$field];
            }
        }
        $input['sdate'] = $input['sdate'] ?? '';
        $input['edate'] = $input['edate'] ?? '';
        if ( ! empty($input['sdate']))
            $where['created_at >= '] = $input['sdate'];
        if ( ! empty($input['edate']))
            $where['created_at <= '] = $input['edate'];

        if (isset($where['collaboration_id']))
        {
            if ($where['collaboration_id'] != "all")
            {
                $where['qrcode_collaboration_id'] = $where['collaboration_id'];
            }
            unset($where['collaboration_id']);
        }

        $imported_list = $this->log_qrcode_import_collaboration_model->get_imported_log_list($where);

        $config = pagination_config();
        $config['per_page'] = 40; //每頁顯示的資料數
        $config['base_url'] = admin_url('sales/promote_import_list');
        $config["total_rows"] = count($imported_list);

        $this->pagination->initialize($config);
        $page_data["links"] = $this->pagination->create_links();

        $current_page = max(1, intval($input['per_page'] ?? '1'));
        $offset = ($current_page - 1) * $config['per_page'];

        $list = array_slice($imported_list, $offset, $config['per_page']);

        $collaborator_rs = $this->qrcode_collaborator_model->get_many_by(['status' => 1]);
        $collaborator_list = ['all' => "全部對象"];
        $collaborator_list = array_replace_recursive($collaborator_list, array_combine(array_column($collaborator_rs, 'id'), array_column($collaborator_rs, 'collaborator')));

        $page_data['list'] = $list;
        $page_data['collaborator_list'] = $collaborator_list;

        $this->load->view('admin/_header');
        $this->load->view('admin/_title', $this->menu);
        $this->load->view('admin/promote_code/promote_import_list', $page_data);
        $this->load->view('admin/_footer');
    }

    public function promote_review_contract()
    {
        $this->load->model('user/user_qrcode_apply_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('admin/contract_format_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->library('qrcode_lib');
        $this->load->library('output/json_output');

        $list = [];
        $where = [];
        $input = $this->input->get(NULL, TRUE);

        if (isset($input['qrcode_apply_id']))
        {
            $where['id'] = $input['qrcode_apply_id'];
        }
        else
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數 qrcode_apply_id'))->send();
        }

        $review_list = $this->user_qrcode_apply_model->get_review_list([], $where);
        $keys = array_flip(['qrcode_apply_id', 'user_id']);
        if ( ! empty($review_list))
        {
            $review_list = reset($review_list);
            $list = array_intersect_key($review_list, $keys);
            $contract_format = $this->contract_format_model->order_by('created_at', 'desc')->get_by(['id' => $review_list['contract_format_id']]);
            $contract_type_name = $this->qrcode_lib->get_contract_type_by_alias($review_list['alias']);
            $contract_content = json_decode($review_list['contract_content'], TRUE);

            $format_setting = $this->qrcode_lib->get_contract_format($contract_type_name, $contract_content, '%');
            $format_setting_without_format = $this->qrcode_lib->get_contract_format($contract_type_name, $contract_content);
            $content = $format_setting['content'];

            $list['content'] = array_combine(
                array_slice($format_setting_without_format['content'],
                $format_setting['input_start'], $format_setting['input_end']-$format_setting['input_start']+1),
                array_slice(json_decode($review_list['contract_content'], TRUE) ?? [],
                    $format_setting['input_start'], $format_setting['input_end']-$format_setting['input_start']+1)
            );
            $list['contract'] = '';
            if (isset($contract_format->content))
            {
                $list['contract'] = vsprintf($contract_format->content, $content);
            }
        }
        else
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT))->send();
        }
        $this->json_output->setStatusCode(200)->setResponse(array('result' => 'SUCCESS', 'data' => $list))->send();
    }

    public function promote_apply_list()
    {
        $this->load->library('output/json_output');
        $this->load->library('user_lib');
        $this->load->library("pagination");
        $this->load->library('contract_lib');
        $this->load->model('admin/contract_format_model');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('log/log_qrcode_import_collaboration_model');
        $this->load->model('user/qrcode_collaborator_model');
        $this->load->model('user/user_qrcode_apply_model');

        $input = $this->input->get(NULL, TRUE);
        $list = [];
        $where = [];
        $user_where = [];
        $fields = ['alias'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $user_where[$field] = $input[$field];
            }
            else
            {
                $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數' . $field))->send();
            }
        }
        $input['sdate'] = $input['sdate'] ?? '';
        $input['edate'] = $input['edate'] ?? '';

        if (($user_where['alias'] ?? "") == "all")
        {
            unset($user_where['alias']);
        }

        if ( ! empty($input['sdate']))
            $where['created_at >= '] = $input['sdate'];
        if ( ! empty($input['edate']))
            $where['created_at <= '] = $input['edate'];
        if ( ! empty($input['user_id']))
            $user_where['user_id'] = $input['user_id'];

        $qrcodeSettingList = $this->qrcode_setting_model->get_all();
        $alias_list = ['all' => "全部方案"];
        $alias_list = array_replace_recursive($alias_list, array_combine(array_column($qrcodeSettingList, 'alias'), array_column($qrcodeSettingList, 'description')));

        $keys = array_flip(['qrcode_apply_id', 'user_id', 'alias', 'status', 'created_at']);
        $review_list = $this->user_qrcode_apply_model->get_review_list($user_where, $where);
        foreach ($review_list as $key => $info)
        {
            $list[$key] = array_intersect_key($info, $keys);
            $list[$key]['status_name'] = $this->user_qrcode_apply_model->status_list[$info['status']];
            $list[$key]['alias_chinese_name'] = $alias_list[$info['alias']];
            $list[$key]['created_at'] = date('Y-m-d', strtotime($info['created_at']));
        }

        $config = [];
        $config['per_page'] = 40; //每頁顯示的資料數
        $config["total_rows"] = count($review_list);

        $current_page = max(1, intval($input['current_page'] ?? '1'));
        $offset = ($current_page - 1) * $config['per_page'];

        $list = array_slice($list, $offset, $config['per_page']);

        $data['pagination'] = [];
        $data['pagination']['total_rows'] = $config["total_rows"];
        $data['pagination']['per_page'] = $config['per_page'];
        $data['pagination']['last_page'] = max((int) ceil(($config["total_rows"] / $config['per_page'])), 1);
        $data['pagination']['current_page'] = $current_page;

        $data['list'] = $list;
        $data['alias_list'] = $alias_list;

        $this->json_output->setStatusCode(200)->setResponse(array('result' => 'SUCCESS', 'data' => $data))->send();
    }

    public function promote_review_list()
    {
        $this->load->library('output/json_output');
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/user_qrcode_apply_model');

        $input = $this->input->get(NULL, TRUE);
        $list = [];
        $where = [];
        $user_where = [];
        $fields = ['status'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $where[$field] = $input[$field];
            }
            else
            {
                $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數' . $field))->send();
            }
        }
        $input['sdate'] = $input['sdate'] ?? '';
        $input['edate'] = $input['edate'] ?? '';

        if ( ! empty($input['sdate']))
            $where['draw_up_at >= '] = $input['sdate'];
        if ( ! empty($input['edate']))
            $where['draw_up_at <= '] = $input['edate'];
        if ( ! empty($input['user_id']))
            $user_where['user_id'] = $input['user_id'];

        $status_list = [PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW => "待審核", PROMOTE_REVIEW_STATUS_SUCCESS => '審核完成'];
        if ( ! in_array($where['status'], array_keys($status_list)))
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '不合法的狀態'))->send();
        }
        $qrcodeSettingList = $this->qrcode_setting_model->get_all();
        $alias_list = array_combine(array_column($qrcodeSettingList, 'alias'), array_column($qrcodeSettingList, 'description'));

        $keys = array_flip(['qrcode_apply_id', 'user_id', 'alias', 'status', 'created_at', 'draw_up_at']);
        $review_list = $this->user_qrcode_apply_model->get_review_list($user_where, $where);
        foreach ($review_list as $key => $info)
        {
            $list[$key] = array_intersect_key($info, $keys);
            $list[$key]['alias_chinese_name'] = $alias_list[$info['alias']];
            $list[$key]['status_name'] = $this->user_qrcode_apply_model->status_list[$info['status']];
            $list[$key]['content'] = array_slice(json_decode($info['contract_content'], TRUE) ?? [], 4, 4);
            $list[$key]['draw_up_at'] = date('Y-m-d', strtotime($info['draw_up_at']));
            $list[$key]['created_at'] = date('Y-m-d', strtotime($info['created_at']));
        }

        $config = [];
        $config['per_page'] = 40; //每頁顯示的資料數
        $config["total_rows"] =

        $current_page = max(1, intval($input['current_page'] ?? '1'));
        $offset = ($current_page - 1) * $config['per_page'];

        $list = array_slice($list, $offset, $config['per_page']);

        $data['pagination'] = [];
        $data['pagination']['total_rows'] = count($review_list);
        $data['pagination']['per_page'] = 40;
        $data['pagination']['last_page'] = max((int) ($config["total_rows"] / $config['per_page']), 1);
        $data['pagination']['current_page'] = $current_page;

        $data['list'] = $list;
        $data['alias_list'] = $status_list;

        $this->json_output->setStatusCode(200)->setResponse(array('result' => 'SUCCESS', 'data' => $data))->send();
    }

    public function promote_modify_contract()
    {
        $this->load->library('output/json_output');
        $this->load->library('qrcode_lib');
        $this->load->model('user/user_qrcode_apply_model');

        $input = $this->input->post(NULL, TRUE);
        $data = [];
        $fields = ['qrcode_apply_id'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $data[$field] = $input[$field];
            }
        }

        if ( ! isset($data['qrcode_apply_id']))
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數 qrcode_apply_id'))->send();
        }

        $apply_info = $this->user_qrcode_apply_model->get($data['qrcode_apply_id']);
        if ( ! isset($apply_info) || $apply_info->status != PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP)
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '目標id不是合法的可更改狀態'))->send();
        }
        $this->load->model('user/user_qrcode_model');
        $user_qrcode = $this->user_qrcode_model->get_by(['id' => $apply_info->user_qrcode_id]);
        if ( ! isset($user_qrcode))
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '找不到對應的qrcode資料'))->send();
        }

        $contract_type = $this->qrcode_lib->get_contract_type_by_alias($user_qrcode->alias);
        $format_setting = $this->qrcode_lib->get_contract_format($contract_type);
        $fields = array_flip($format_setting['content']);
        $contract_content = json_decode($apply_info->contract_content, TRUE);

        foreach ($fields as $field => $idx)
        {
            if (isset($input[$field]))
            {
                $contract_content[$idx] = $input[$field];
            }
        }
        $rs = $this->user_qrcode_apply_model->update_by(['id' => $data['qrcode_apply_id']],
            ['contract_content' => json_encode($contract_content)]);
        if ( ! $rs)
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => EXIT_DATABASE))->send();
        }
        $this->json_output->setStatusCode(200)->setResponse(array('result' => 'SUCCESS', 'data' => []))->send();
    }

    public function promote_contract_submit()
    {
        $this->load->library('output/json_output');
        $this->load->library('notification_lib');
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_qrcode_apply_model');

        $input = $this->input->post(NULL, TRUE);
        $where = [];
        $fields = ['qrcode_apply_id'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $where[$field] = $input[$field];
            }
            else
            {
                $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數' . $field))->send();
            }
        }

        $apply_info = $this->user_qrcode_apply_model->get($where['qrcode_apply_id']);
        if ( ! isset($apply_info) || ! in_array($apply_info->status, [PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP]))
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '目標id不是合法的可更改狀態'))->send();
        }

        $rs = $this->user_qrcode_apply_model->update_by(['id' => $where['qrcode_apply_id']],
            ['status' => PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW]);
        if ( ! $rs)
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => EXIT_DATABASE))->send();
        }

        // 通知管理員審核
        $admin = $this->admin_model->get(1);
        if (isset($admin))
        {
            $user_qrcode = $this->user_qrcode_model->get($apply_info->user_qrcode_id);
            if (isset($user_qrcode))
            {
                $this->notification_lib->promote_contract_review($admin->email, $user_qrcode->user_id);
            }
        }

        $this->json_output->setStatusCode(200)->setResponse(array('result' => 'SUCCESS', 'data' => []))->send();
    }


    public function promote_contract_approve()
    {
        $rs = $this->promote_contract_set(PROMOTE_REVIEW_STATUS_SUCCESS);
        if ($rs['result'] == "ERROR")
        {
            $this->json_output->setStatusCode(200)->setResponse($rs)->send();
        }
        $this->load->model('user/user_qrcode_apply_model');
        $this->load->model('user/user_qrcode_model');
        $this->load->library('notification_lib');
        $this->load->library('user_lib');
        $this->load->library('contract_lib');
        $this->load->library('qrcode_lib');
        $input = $this->input->post(NULL, TRUE);
        $apply_info = $this->user_qrcode_apply_model->get_by(['id' => $input['qrcode_apply_id'], 'status' => PROMOTE_REVIEW_STATUS_SUCCESS]);
        if (isset($apply_info))
        {
            $qrcode_code = $this->user_qrcode_model->get($apply_info->user_qrcode_id);
            if ( ! isset($qrcode_code))
            {
                $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => EXIT_DATABASE, 'msg' => '找不到對應的推薦碼'))->send();
            }
            $settings = json_decode($qrcode_code->settings, TRUE);

            $contract_content = json_decode($apply_info->contract_content, TRUE);
            $this->contract_lib->update_contract($qrcode_code->contract_id, $contract_content);

            $contract_type = $this->qrcode_lib->get_contract_type_by_alias($qrcode_code->alias);
            $format_setting = $this->qrcode_lib->get_contract_format($contract_type);
            $fields = array_flip($format_setting['content']);
            foreach ($fields as $field => $idx)
            {
                switch ($field) {
                    case 'student_reward_amount':
                        $settings['reward']['product']['student']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                    case 'salary_man_reward_amount':
                        $settings['reward']['product']['salary_man']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                    case 'small_enterprise_reward_amount':
                        $settings['reward']['product']['small_enterprise']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                    case 'small_enterprise2_reward_amount':
                        $settings['reward']['product']['small_enterprise2']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                    case 'small_enterprise3_reward_amount':
                        $settings['reward']['product']['small_enterprise3']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                    case 'student_platform_fee':
                        $settings['reward']['product']['student']['borrower_percent'] = (float) $contract_content[$idx] ?? 0;
                        break;
                    case 'salary_man_platform_fee':
                        $settings['reward']['product']['salary_man']['borrower_percent'] = (float) $contract_content[$idx] ?? 0;
                        break;
                    case 'small_enterprise_platform_fee':
                        $settings['reward']['product']['small_enterprise']['borrower_percent'] = (float) $contract_content[$idx] ?? 0;
                        break;
                    case 'small_enterprise2_platform_fee':
                        $settings['reward']['product']['small_enterprise2']['borrower_percent'] = (float) $contract_content[$idx] ?? 0;
                        break;
                    case 'small_enterprise3_platform_fee':
                        $settings['reward']['product']['small_enterprise3']['borrower_percent'] = (float) $contract_content[$idx] ?? 0;
                        break;
                    case 'full_member':
                        $settings['reward']['full_member']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                    case 'download':
                        $settings['reward']['download']['amount'] = (int) $contract_content[$idx] ?? 0;
                        break;
                }
            }

            $user_qrcode_update_param = ['settings' => json_encode($settings),
                'status' => PROMOTE_STATUS_CAN_SIGN_CONTRACT];
            $this->user_qrcode_model->update_by(['id' => $qrcode_code->id], $user_qrcode_update_param);
            // 寫 log
            $this->load->model('log/log_user_qrcode_model');
            $user_qrcode_update_param['user_qrcode_id'] = $qrcode_code->id;
            $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

            $this->load->model('user/user_certification_model');
            $this->load->library('certification_lib');

            // 通知審核合約成功
            if (isset($settings) && isset($settings['investor']))
            {
                $this->load->library('judicialperson_lib');
                $emails = $this->judicialperson_lib->get_company_email_list($qrcode_code->user_id);
                $emails = array_flip($emails);
                foreach ($emails as $email => $user_id)
                {
                    $this->notification_lib->promote_contract_done($qrcode_code->user_id, $settings['investor'], 1);
                }
            }
        }
        $this->json_output->setStatusCode(200)->setResponse($rs)->send();
    }

    public function promote_contract_withdraw()
    {
        $rs = $this->promote_contract_set(PROMOTE_REVIEW_STATUS_PENDING_TO_DRAW_UP);
        $this->json_output->setStatusCode(200)->setResponse($rs)->send();
    }

    public function promote_contract_reject()
    {
        $rs = $this->promote_contract_set(PROMOTE_REVIEW_STATUS_WITHDRAW);
        if ($rs['result'] == "ERROR")
        {
            $this->json_output->setStatusCode(200)->setResponse($rs)->send();
        }

        // 通知合約失敗
        $this->load->model('user/user_qrcode_model');
        $this->load->model('user/user_qrcode_apply_model');
        $this->load->library('notification_lib');
        $input = $this->input->post(NULL, TRUE);
        $apply_info = $this->user_qrcode_apply_model->get_by(['id' => $input['qrcode_apply_id'], 'status' => PROMOTE_REVIEW_STATUS_WITHDRAW]);
        if (isset($apply_info))
        {
            $qrcode_code = $this->user_qrcode_model->get($apply_info->user_qrcode_id);
            if ( ! isset($qrcode_code))
            {
                $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => EXIT_DATABASE, 'msg' => '找不到對應的推薦碼'))->send();
            }
            $user_qrcode_update_param = ['status' => PROMOTE_STATUS_PENDING_TO_SENT, 'sub_status' => PROMOTE_SUB_STATUS_DEFAULT];
            $this->user_qrcode_model->update_by(['id' => $apply_info->user_qrcode_id], $user_qrcode_update_param);
            // 寫 log
            $this->load->model('log/log_user_qrcode_model');
            $user_qrcode_update_param['user_qrcode_id'] = $apply_info->user_qrcode_id;
            $this->log_user_qrcode_model->insert_log($user_qrcode_update_param);

            $settings = json_decode($qrcode_code->settings, TRUE);
            if (isset($settings) && isset($settings['investor']))
            {
                $this->load->library('judicialperson_lib');
                $emails = $this->judicialperson_lib->get_company_email_list($qrcode_code->user_id);
                $emails = array_flip($emails);
                foreach ($emails as $email => $user_id)
                {
                    $this->notification_lib->promote_contract_done($user_id, $settings['investor'], 2);
                }
            }
        }
        $this->json_output->setStatusCode(200)->setResponse($rs)->send();
    }

    private function promote_contract_set($status): array
    {
        $this->load->library('output/json_output');
        $this->load->model('user/user_qrcode_apply_model');
        $this->load->model('log/log_qrcode_apply_review_model');

        $input = $this->input->post(NULL, TRUE);
        $where = [];
        $fields = ['qrcode_apply_id'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $where[$field] = $input[$field];
            }
            else
            {
                return array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數' . $field);
            }
        }

        $apply_info = $this->user_qrcode_apply_model->get($where['qrcode_apply_id']);
        if ( ! isset($apply_info) || $apply_info->status != PROMOTE_REVIEW_STATUS_PENDING_TO_REVIEW)
        {
            return array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '目標id不是合法的可更改狀態');
        }

        $rs = $this->user_qrcode_apply_model->update_by(['id' => $where['qrcode_apply_id']],
            ['status' => $status]);
        if ( ! $rs)
        {
            return array('result' => 'ERROR', 'error' => EXIT_DATABASE);
        }
        $this->log_qrcode_apply_review_model->insert([
            'admin_id' => $this->login_info->id,
            'qrcode_apply_id' => $where['qrcode_apply_id'],
            'status' => $status,
        ]);
        return array('result' => 'SUCCESS', 'data' => []);
    }

    public function promote_set_status(): array
    {
        if ( ! $this->input->is_ajax_request())
        {
            alert('ERROR, 只接受Ajax', admin_url('sales/promote_edit'));
        }
        $this->load->model('user/user_qrcode_model');
        $this->load->library('output/json_output');
        $this->load->model('user/user_qrcode_apply_model');
        $this->load->model('log/log_qrcode_modify_model');

        $input = $this->input->post(NULL, TRUE);
        $where = [];
        $fields = ['user_qrcode_id', 'status'];

        foreach ($fields as $field)
        {
            if (isset($input[$field]) && $input[$field] != '')
            {
                $where[$field] = $input[$field];
            }
            else
            {
                $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '缺少參數' . $field))->send();
            }
        }

        $target_status = $where['status'];
        if ( ! in_array($target_status, [PROMOTE_STATUS_DISABLED, PROMOTE_STATUS_AVAILABLE]))
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '不合法的目標狀態'))->send();
        }
        $user_qrcode = $this->user_qrcode_model->get($where['user_qrcode_id']);
        if ( ! isset($user_qrcode) ||
            ($target_status == PROMOTE_STATUS_DISABLED && $user_qrcode->status != PROMOTE_STATUS_AVAILABLE) ||
            ($target_status == PROMOTE_STATUS_AVAILABLE && $user_qrcode->status != PROMOTE_STATUS_DISABLED))
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '目標id不是合法的可更改狀態'))->send();
        }

        // For update
        $param = ['status' => $target_status];
        switch ($target_status)
        {
            case PROMOTE_STATUS_DISABLED:
                $param['end_time'] = date('Y-m-d H:i:s');
                break;
            case PROMOTE_STATUS_AVAILABLE:
                $user_qrcode_rs = $this->user_qrcode_model->get_many_by([
                    'user_id' => $user_qrcode->user_id, 'status' => PROMOTE_STATUS_AVAILABLE]);
                if ( ! empty($user_qrcode_rs))
                {
                    $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => INPUT_NOT_CORRECT, 'msg' => '該使用者已有啟用中的推薦碼，不能同時啟用兩個以上的推薦碼。'))->send();
                }
                $param['end_time'] = $user_qrcode->contract_end_time;
                break;
        }

        $rs = $this->user_qrcode_model->update_by(['id' => $where['user_qrcode_id']], $param);
        // 寫 log
        $this->load->model('log/log_user_qrcode_model');
        $this->log_user_qrcode_model->insert_log(array_merge($param, ['user_qrcode_id' => $where['user_qrcode_id']]));

        if ( ! $rs)
        {
            $this->json_output->setStatusCode(200)->setResponse(array('result' => 'ERROR', 'error' => EXIT_DATABASE))->send();
        }
        $this->log_qrcode_modify_model->insert([
            'admin_id' => $this->login_info->id,
            'qrcode_id' => $where['user_qrcode_id'],
            'status' => $target_status,
        ]);
        $this->json_output->setStatusCode(200)->setResponse(['success' => TRUE, 'msg' => "修改成功。"])->send();
    }

    /**
     * QR Code 方案設定頁面
     *
     * @created_at        2021-12-13
     * @created_by        Jack
     */
    public function qrcode_projects()
    {
        $this->load->view(
            'admin/sales/qrcode_projects',
            $data = [
                'menu' => $this->menu,
                'use_vuejs' => TRUE,
                'scripts' => [
                    '/assets/admin/js/sales/qrcode_projects.js'
                ]
            ]
        );
    }

    /**
     * QR Code 合約審核頁面
     *
     * @created_at        2021-12-13
     * @created_by        Jack
     */
    public function qrcode_contracts()
    {
        $this->load->view(
            'admin/sales/qrcode_contracts',
            $data = [
                'menu' => $this->menu,
                'use_vuejs' => TRUE,
                'scripts' => [
                    '/assets/admin/js/sales/qrcode_contracts.js'
                ]
            ]
        );
    }

    public function set_monthly_goals($goal_ym)
    {
        $input = $this->input->post(NULL, TRUE);
        $at_month = $this->_parse_goal_ym_to_at_month($goal_ym);

        $goals = $this->sale_goals_model->get_goals_number_at_this_month($at_month);
        $db_goals = array_column($goals, null, 'id');

        if ($this->_is_input_vaild($input, $db_goals))
        {
            $updated_data = [];
            array_walk($input, function ($element, $key) use ($db_goals, &$updated_data) {
                if ( ! empty($db_goals[$key]))
                {
                    if (empty($element['status']))
                    {
                        $element['status'] = 0;
                    }
                    $diff_tmp = array_diff_assoc($element, $db_goals[$key]);
                    if ( ! empty($diff_tmp))
                    {
                        $updated_data[$key] = $diff_tmp;
                    }
                }
            });
            if ($updated_data)
            {
                foreach ($updated_data as $key => $value)
                {
                    $this->sale_goals_model->update($key, $value);
                }
            }
        }

        redirect('/admin/Sales/sales_report', 'refresh');
    }

    private function _is_input_vaild($input, $db_goals)
    {
        $same_key = array_intersect_key($input, $db_goals);
        return (count($same_key) == count($input)) ? TRUE : FALSE;
    }

    public function goals_export()
    {
        $goal_ym = $this->input->get('goal_ym');
        $at_month = $this->_parse_goal_ym_to_at_month($goal_ym);

        $this->load->library('Sales_lib', ['at_month' => $at_month]);
        $days_info = $this->sales_lib->get_days();

        // 整理日期的匯出格式
        $first_row = $days_info['date'];
        array_unshift($first_row, '日期');
        $second_row = $days_info['week'];
        array_unshift($second_row, '總和');

        $content1 = [];
        $content2 = [];

        // 內容先塞月日星期
        $format1 = [NumberFormat::FORMAT_GENERAL, NumberFormat::FORMAT_GENERAL];
        $format2 = [NumberFormat::FORMAT_GENERAL, NumberFormat::FORMAT_GENERAL];
        $content1[] = $first_row;
        $content1[] = $second_row;
        $content2[] = $first_row;
        $content2[] = $second_row;

        // 取出該月資料
        $datas = $this->sales_lib->calculate();

        // 依照 excel 需要的匯出順序塞入 content
        $sort1 = [
            [
                'title' => '業務推廣',
                'items' => [
                    [
                        'key' => sale_goals_model::GOAL_WEB_TRAFFIC,
                        'kpi' => ['目標流量', '實際流量', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_USER_REGISTER,
                        'kpi' => ['目標會員數', '實際總會員數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_APP_DOWNLOAD,
                        'kpi' => ['目標下載數', '實際下載數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_LOAN_TOTAL,
                        'kpi' => ['目標申貸戶數', '實際完成申貸戶數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ]
                ]
            ]
        ];
        foreach ($sort1 as $sort_key => $sort_value)
        {
            foreach ($sort_value['items'] as $item_key => $item_value)
            {
                if (empty($datas[$item_value['key']]['goal']))
                {
                    unset($sort1[$sort_key]['items'][$item_key]);
                    continue;
                }
                $content1[] = $datas[$item_value['key']]['goal'];
                $format1[] = $item_value['format'][0];
                $content1[] = $datas[$item_value['key']]['real'];
                $format1[] = $item_value['format'][1];
                $content1[] = $datas[$item_value['key']]['rate'];
                $format1[] = $item_value['format'][2];
            }
        }

        // 第二頁
        $sort2 = [
            [
                'title' => '申貸指標',
                'items' => [
                    [
                        'key' => sale_goals_model::GOAL_LOAN_SALARY_MAN,
                        'kpi' => ['目標申貸戶數', '實際申貸戶數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_LOAN_STUDENT,
                        'kpi' => ['目標申貸戶數', '實際申貸戶數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_LOAN_SMART_STUDENT,
                        'kpi' => ['目標申貸戶數', '實際申貸戶數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_LOAN_CREDIT_INSURANCE,
                        'kpi' => ['目標申貸戶數', '實際申貸戶數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ], [
                        'key' => sale_goals_model::GOAL_LOAN_SME,
                        'kpi' => ['目標申貸戶數', '實際申貸戶數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ]
                ]
            ], [
                'title' => '成交指標',
                'items' => [
                    [
                        'key' => sale_goals_model::GOAL_DEAL_SALARY_MAN,
                        'kpi' => ['目標成交筆數', '實際成交筆數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ],
                    [
                        'key' => sale_goals_model::GOAL_DEAL_STUDENT,
                        'kpi' => ['目標成交筆數', '實際成交筆數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ],
                    [
                        'key' => sale_goals_model::GOAL_DEAL_SMART_STUDENT,
                        'kpi' => ['目標成交筆數', '實際成交筆數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ],
                    [
                        'key' => sale_goals_model::GOAL_DEAL_CREDIT_INSURANCE,
                        'kpi' => ['目標成交筆數', '實際成交筆數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ],
                    [
                        'key' => sale_goals_model::GOAL_DEAL_SME,
                        'kpi' => ['目標成交筆數', '實際成交筆數', '達成率'],
                        'format' => [NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_NUMBER, NumberFormat::FORMAT_PERCENTAGE]
                    ],
                ]
            ]
        ];
        foreach ($sort2 as $sort_key => $sort_value)
        {
            foreach ($sort_value['items'] as $item_key => $item_value)
            {
                if (empty($datas[$item_value['key']]['goal']))
                {
                    unset($sort2[$sort_key]['items'][$item_key]);
                    continue;
                }
                $content2[] = $datas[$item_value['key']]['goal'];
                $format2[] = $item_value['format'][0];
                $content2[] = $datas[$item_value['key']]['real'];
                $format2[] = $item_value['format'][1];
                $content2[] = $datas[$item_value['key']]['rate'];
                $format2[] = $item_value['format'][2];
            }
        }

        // 最後補上 成交總計
        $content2[] = $datas['total_deals'];

        // 匯出資料內容
        $excel_contents = [
            [
                'sheet' => '貸前指標',
                'content' => $content1,
                'format' => $format1,
            ],
            [
                'sheet' => '貸中指標',
                'content' => $content2,
                'format' => $format2,
            ],
        ];
        $sheet_highlight = 'KPI指標-' . $days_info['int_month'] . '月';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setTitle('績效統計表');

        foreach ($excel_contents as $sheet => $contents)
        {
            $sheet > 0 ? $spreadsheet->createSheet() : '';
            $row = 1;

            // 前幾行合併儲存格的項目先處理
            if (empty($sheet))
            {
                $spreadsheet->getActiveSheet()->mergeCells('A1:C2');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A1', $sheet_highlight);
                $spreadsheet->getActiveSheet($sheet)->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');

                $index_a = 3;
                $index_b = 3;
                $spreadsheet = $this->_draw_title($spreadsheet, $sheet, $sort1, $index_a, $index_b);
            }
            else
            {
                // 這邊反過來是因為要先設定 Sheet 不然會合併到前一個 sheet 的格子
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A1', $sheet_highlight);
                $spreadsheet->getActiveSheet()->mergeCells('A1:C2');
                $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center')->setVertical('center');

                $index_a = 3;
                $index_b = 3;
                $spreadsheet = $this->_draw_title($spreadsheet, $sheet, $sort2, $index_a, $index_b);

                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B' . $index_b, '總數');
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C' . $index_b, '總成交數');
                $contents['format'][] = NumberFormat::FORMAT_NUMBER;

                $spreadsheet->getActiveSheet()->getStyle('B' . $index_b)->applyFromArray($this->excel_config);
                $spreadsheet->getActiveSheet()->getStyle('C' . $index_b)->applyFromArray($this->excel_config);
            }

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(TRUE);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE);
            $width_list = [['column' => 'A', 'width' => 8.5], ['column' => 'B', 'width' => 12.5], ['column' => 'C', 'width' => 12.5]];

            // 從 D 行開始塞入上面整理的內容
            foreach ($contents['content'] as $key => $row_content)
            {
                foreach ($row_content as $content_index => $value)
                {
                    $column = Coordinate::stringFromColumnIndex($content_index + 4); // A = 1
                    $spreadsheet->setActiveSheetIndex($sheet)->setCellValue($column . $row, $value);
                    $spreadsheet->getActiveSheet()->getStyle($column . $row)->applyFromArray($this->excel_config);
                    $spreadsheet->getActiveSheet()->getStyle($column . $row)->getNumberFormat()->setFormatCode($contents['format'][$key]);
                    $spreadsheet->getActiveSheet($sheet)->getStyle($column . $row)->getAlignment()->setHorizontal('center');
                    $spreadsheet->getActiveSheet($sheet)->getColumnDimension($column)->setAutoSize(TRUE);
                }
                $row++;
            }

            $spreadsheet->setActiveSheetIndex($sheet)->setTitle($contents['sheet']);

            // 針對低於最小寬度的欄位重新調整
            $spreadsheet->getActiveSheet()->calculateColumnWidths();
            foreach ($width_list as $info)
            {
                if ($spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->getWidth() < $info['width'])
                {
                    $spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->setAutoSize(FALSE);
                    $spreadsheet->getActiveSheet()->getColumnDimension($info['column'])->setWidth($info['width']);
                }
            }
        }
        $spreadsheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . '績效統計表' . date('Ymd') . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function _draw_title($spreadsheet, $sheet, $sort_list, &$index_a, &$index_b)
    {
        $rowspan = 2;
        foreach ($sort_list as $sort)
        {
            $merge_col = count($sort['items']) * ($rowspan + 1);
            $spreadsheet->getActiveSheet()->mergeCells('A' . $index_a . ':A' . ($index_a + $merge_col - 1));
            $spreadsheet->getActiveSheet()->getStyle('A' . $index_a)->getAlignment()->setHorizontal('center')->setVertical('center');
            $spreadsheet->getActiveSheet()->getStyle('A' . $index_a . ':A' . ($index_a + $merge_col - 1))->applyFromArray($this->excel_config);
            $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('A' . $index_a, $sort['title']);
            $index_a = $index_a + $merge_col;

            foreach ($sort['items'] as $item)
            {
                $kpi = $item['kpi'];
                $spreadsheet->getActiveSheet()->mergeCells('B' . $index_b . ':B' . ($index_b + $rowspan));
                $spreadsheet->getActiveSheet()->getStyle('B' . $index_b)->getAlignment()->setHorizontal('center')->setVertical('center');
                $spreadsheet->getActiveSheet()->getStyle('B' . $index_b . ':B' . ($index_b + $rowspan))->applyFromArray($this->excel_config);
                $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('B' . $index_b, $this->sale_goals_model->type_name_mapping()[$item['key']]);

                for ($i = 0; $i < count($kpi); $i++)
                {
                    $spreadsheet->setActiveSheetIndex($sheet)->setCellValue('C' . ($index_b + $i), $kpi[$i]);
                    $spreadsheet->getActiveSheet()->getStyle('C' . ($index_b + $i))->applyFromArray($this->excel_config);
                }

                $index_b += $rowspan + 1;
            }
        }
        return $spreadsheet;
    }

    private function _parse_goal_ym_to_at_month($goal_ym)
    {
        try
        {
            $day = new DateTimeImmutable($goal_ym);
        }
        catch (Exception $e)
        {
            return date('Ym');
        }

        return $day->format('Ym');
    }

    /**
     * 匯出推薦碼統計報表
     */
    public function promote_report_export()
    {
        $this->load->model('user/qrcode_setting_model');
        $this->load->model('user/qrcode_collaborator_model');
        $this->load->model('user/user_subcode_model');
        $this->load->model('behavion/user_behavior_model');
        $this->load->library('contract_lib');
        $this->load->library('qrcode_lib');
        $this->load->library('user_lib');
        $this->load->library('spreadsheet_lib');
        $this->load->library('target_lib');
        $instalment_list = $this->config->item('instalment');
        $repayment_type = $this->config->item('repayment_type');
        $status_list = $this->target_model->status_list;

        $input = $this->input->get(NULL, TRUE);
        $where = [];
        $data = [];

        $main_qrcode_id = $input['id'] ?? 0;
        $start_date = $input['sdate'] ?? '';
        $end_date = $input['edate'] ?? '';

        $where['id'] = $main_qrcode_id;
        $where['subcode_flag'] = IS_NOT_PROMOTE_SUBCODE;
        $list = $this->qrcode_lib->get_promoted_reward_info($where, $start_date, $end_date);
        $list = reset($list);
        if (empty($list))
        {
            alert('查無此推薦碼，請重新確認。', admin_url("sales/promote_edit?id={$main_qrcode_id}"));
            return FALSE;
        }

        $title_rows = [
            'register_date' => ['name' => '註冊日期', 'width' => 12, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'user_id' => ['name' => '使用者編號', 'width' => 10, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'target_no' => ['name' => '案號', 'width' => 17.5, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'product_name' => ['name' => '產品', 'width' => 15, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'user_identity' => ['name' => '是否完成實名認證', 'width' => 15, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'apply_date' => ['name' => '申請日期', 'width' => 10.5, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'approve_date' => ['name' => '核准日期', 'width' => 10.5, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'loan_date' => ['name' => '放款日期', 'width' => 10.5, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'apply_amount' => ['name' => '申請金額', 'width' => 8, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'credit_amount' => ['name' => '核准金額', 'width' => 8, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'loan_amount' => ['name' => '動用金額', 'width' => 8, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'unused_credit_line' => ['name' => '可動用金額', 'width' => 9.5, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'principle_balance' => ['name' => '本金餘額', 'width' => 8, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'interest_rate' => ['name' => '年化利率', 'alignment' => ['h' => 'center', 'v' => 'center']],
            'instalment' => ['name' => '期數', 'alignment' => ['h' => 'center', 'v' => 'center']],
            'repayment_type' => ['name' => '還款方式', 'alignment' => ['h' => 'center', 'v' => 'center']],
            'delay_status' => ['name' => '逾期狀況', 'alignment' => ['h' => 'center', 'v' => 'center']],
            'delay_days' => ['name' => '逾期天數', 'alignment' => ['h' => 'center', 'v' => 'center']],
            'target_status' => ['name' => '狀態', 'alignment' => ['h' => 'center', 'v' => 'center']],
            'reason' => ['name' => '借款原因', 'width' => 12, 'alignment' => ['h' => 'center', 'v' => 'center']],
            'remark' => ['name' => '備註', 'width' => 18, 'alignment' => ['h' => 'center', 'v' => 'center']]
        ];
        $init_report_data = array_combine(array_keys($title_rows), array_fill(0, count($title_rows), 'n/a'));

        foreach ($list['fullMember'] as $rs)
        {
            $report_data = $init_report_data;
            $report_data['user_id'] = $rs['user_id'];
            $report_data['register_date'] = date('Y-m-d', strtotime($rs['created_at']));
            $user_register_date[$rs['user_id']] = $report_data['register_date'];
            $data[] = $report_data;
        }

        // 撈取所有推薦申貸案件
        $apply_list = $this->qrcode_lib->get_promoted_apply_info($where, $start_date, $end_date);

        $target_ids = [];
        foreach ($this->user_lib->rewardCategories as $category => $productIdList)
        {
            $target_ids = array_merge($target_ids, array_column($list[$category], 'id'));
            $temp_target_list = array_column($apply_list ?? [], $category);
            if ( ! empty($temp_target_list))
            {
                $temp_target_list = call_user_func_array('array_merge', $temp_target_list);
            }
            $target_ids = array_merge($target_ids, array_column($temp_target_list ?? [], 'id'));
        }
        $target_ids = array_unique($target_ids);

        if ( ! empty($target_ids))
        {
            $targets = $this->target_lib->get_targets_detail($target_ids);
            foreach ($targets as $target)
            {
                $reason_list = json_decode($target['reason'], TRUE);
                $report_data = $init_report_data;
                $report_data['register_date'] = $user_register_date[$target['user_id']] ?? '-';
                $report_data['user_id'] = $target['user_id'];
                $report_data['target_no'] = $target['target_no'];
                $report_data['product_name'] = $target['product_name'];
                $report_data['user_identity'] = $target['user_identity'];
                $report_data['apply_date'] = $target['apply_date'];
                $report_data['approve_date'] = isset($target['credit_created_at']) ? date("Y-m-d", $target['credit_created_at']) : '-';
                $report_data['loan_date'] = empty($target['loan_date']) ? '-' : $target['loan_date'];
                $report_data['apply_amount'] = $target['amount'];
                $report_data['credit_amount'] = empty($target['credit_amount']) ? '-' : $target['credit_amount'];
                $report_data['loan_amount'] = empty($target['loan_amount']) ? '-' : $target['loan_amount'];
                $report_data['unused_credit_line'] = $target['unused_credit_line'] ?? '-';
                $report_data['principle_balance'] = $target['principle_balance'];
                $report_data['interest_rate'] = $target['interest_rate'];
                $report_data['instalment'] = $instalment_list[$target['instalment']];
                $report_data['repayment_type'] = $repayment_type[$target['repayment']];
                $report_data['delay_status'] = $target['delay'] != '0' ? '逾期中' : '未逾期';
                $report_data['delay_days'] = $target['delay_days'];
                $report_data['target_status'] = $status_list[$target['status']];
                $report_data['reason'] = $reason_list['reason'] ?? $target['reason'];
                $report_data['remark'] = $target['remark'];
                $data[] = $report_data;
            }
        }
        $spreadsheet = $this->spreadsheet_lib->load($title_rows, $data);
        $this->spreadsheet_lib->download("推薦碼統計表({$list['info']['promote_code']}).xlsx", $spreadsheet);
        exit(0);
    }
}