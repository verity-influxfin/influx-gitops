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
    }
}
?>