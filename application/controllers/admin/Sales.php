<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/MY_Admin_Controller.php');

class Sales extends MY_Admin_Controller {
	
	protected $edit_method = array();
	
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

        $statusToMethodMapping = [
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
            '2-0' => 'NewStudents',
            '2-1' => 'ExistingStudents',
            '3-0' => 'NewOfficeWorkers',
            '3-1' => 'ExistingOfficeWorkers',
            '4-0' => 'NewOfficeWorkers',
            '4-1' => 'ExistingOfficeWorkers',
        ];

        $newApplicantRows = $this->target_model->getCountByStatus($status, true, $createdRange, $convertedRange);
        $existingApplicantRows = $this->target_model->getCountByStatus($status, false, $createdRange, $convertedRange);

        $rowsByApplicantType = [$newApplicantRows, $existingApplicantRows];

        for ($i = 0; $i < 2; $i++) {
            $rows = $rowsByApplicantType[$i];
            foreach ($rows as $row) {
                if (!isset($productToTableMapping[$row->product_id])) {
                    continue;
                }
                $key = "{$row->product_id}-{$i}";
                if (!isset($productAndTypeToRowMapping[$key])) {
                    continue;
                }
                $tables = $productToTableMapping[$row->product_id];

                $productAndSubProduct = "{$row->product_id}-{$row->sub_product_id}";
                if (isset($subProductToTableMapping[$productAndSubProduct])) {
                    $tables = $subProductToTableMapping[$productAndSubProduct];
                }

                $key = "{$row->product_id}-{$i}";
                $getRowMethod = "get" . $productAndTypeToRowMapping[$key];

                $getMethod = 'get' . $statusToMethodMapping[$row->status];
                $setMethod = 'set' . $statusToMethodMapping[$row->status];

                foreach ($tables as $table) {
                    $current = $$table->$getRowMethod()->$getMethod() + intval($row->count);
                    $$table->$getRowMethod()->$setMethod($current);

                    if ($row->status != 0) {
                        $current = $$table->$getRowMethod()->getApplicants() + intval($row->count);
                        $$table->$getRowMethod()->setApplicants($current);
                    }
                }
            }
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
}

?>